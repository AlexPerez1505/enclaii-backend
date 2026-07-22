<?php

namespace App\Http\Controllers;

use App\Models\LaunchPromoCode;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\SessionLimitService;
use App\Services\StripeService;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class LaunchPromoRegistrationController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
        private readonly SessionLimitService $sessionLimits,
    ) {}

    public function show(Request $request, string $token): View
    {
        $promoCode = LaunchPromoCode::findByToken($token);

        return view('auth.launch-promo-register', [
            'promoCode' => $promoCode,
            'token' => $token,
            'state' => $this->stateFor($promoCode, $request->user()),
            'trialEndsAt' => $promoCode
                ? ($promoCode->reserved_at ?: now())->copy()->addMonthsNoOverflow($promoCode->trial_months)
                : null,
        ]);
    }

    public function register(Request $request, string $token, StripeService $stripe): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'Ingresa un correo electronico valido.',
            'email.unique' => 'Este correo electronico ya esta registrado.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.confirmed' => 'Las contrasenas no coinciden.',
            'password.min' => 'La contrasena debe tener minimo 8 caracteres.',
        ]);

        [$user, $promoCode] = DB::transaction(function () use ($data, $token): array {
            $promoCode = LaunchPromoCode::query()
                ->where('token_hash', hash('sha256', $token))
                ->lockForUpdate()
                ->first();

            if (! $promoCode || ! $promoCode->isAvailable()) {
                throw ValidationException::withMessages([
                    'promo' => 'Este codigo promocional ya no esta disponible.',
                ]);
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $promoCode->reserveFor($user);

            return [$user, $promoCode->fresh()];
        });

        Auth::login($user);
        $request->session()->regenerate();
        $this->sessionLimits->syncCurrentDatabaseSession($request, $user);
        $this->sessionLimits->enforceDatabaseSessions($user, $request->session()->getId());

        $this->activity->record(
            'promo_account_created',
            'authentication',
            'Creo su cuenta con codigo promocional',
            $promoCode,
            ['promo_code' => $promoCode->code],
            user: $user,
            request: $request,
        );

        return $this->startCheckout($request, $promoCode, $stripe);
    }

    public function checkout(Request $request, string $token, StripeService $stripe): RedirectResponse
    {
        $promoCode = LaunchPromoCode::findByToken($token);

        if (! $promoCode || ! $request->user() || ! $promoCode->isOwnedBy($request->user())) {
            return redirect()
                ->route('promo.register.show', ['token' => $token])
                ->with('error', 'Este codigo no esta ligado a tu cuenta.');
        }

        return $this->startCheckout($request, $promoCode, $stripe);
    }

    public function image(string $token): Response
    {
        $promoCode = LaunchPromoCode::findByToken($token);
        abort_unless($promoCode, 404);

        $url = route('promo.register.show', ['token' => $token]);
        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 420,
            margin: 18,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(6, 16, 50),
            backgroundColor: new Color(255, 255, 255),
        );
        $result = (new SvgWriter)->write($qrCode);

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Cache-Control' => 'private, no-store, max-age=0',
            'Content-Disposition' => request()->boolean('download')
                ? 'attachment; filename="'.$promoCode->code.'.svg"'
                : 'inline',
        ]);
    }

    private function startCheckout(
        Request $request,
        LaunchPromoCode $promoCode,
        StripeService $stripe,
    ): RedirectResponse {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('promo.register.show', ['token' => $promoCode->token]);
        }

        if ($user->subscribed()) {
            return redirect()->route('dashboard');
        }

        if ($promoCode->revoked_at || ! $promoCode->isOwnedBy($user)) {
            return redirect()
                ->route('promo.register.show', ['token' => $promoCode->token])
                ->with('error', 'Este codigo ya no puede usarse.');
        }

        try {
            $priceId = $stripe->priceId($promoCode->plan, $promoCode->interval);
        } catch (Throwable $e) {
            return redirect()
                ->route('promo.register.show', ['token' => $promoCode->token])
                ->with('error', $e->getMessage());
        }

        if (! $priceId) {
            return redirect()
                ->route('promo.register.show', ['token' => $promoCode->token])
                ->with('error', 'El plan promocional aun no tiene precio configurado en Stripe.');
        }

        $trialStart = $promoCode->reserved_at ?: now();
        $trialEndsAt = $trialStart->copy()->addMonthsNoOverflow($promoCode->trial_months);

        try {
            $session = $stripe->createPromoTrialCheckout(
                $user,
                $priceId,
                $trialEndsAt->timestamp,
                route('stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
                route('promo.register.show', ['token' => $promoCode->token]),
                [
                    'type' => 'promo_trial',
                    'promo_code_id' => (string) $promoCode->id,
                    'promo_code' => $promoCode->code,
                    'plan' => $promoCode->plan,
                    'interval' => $promoCode->interval,
                    'trial_months' => (string) $promoCode->trial_months,
                ],
            );

            $promoCode->forceFill(['checkout_session_id' => $session->id])->save();
        } catch (Throwable $e) {
            Log::error('Promo trial checkout error', [
                'promo_code_id' => $promoCode->id,
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('promo.register.show', ['token' => $promoCode->token])
                ->with('error', 'No se pudo iniciar Stripe: '.$e->getMessage());
        }

        return redirect()->away($session->url);
    }

    private function stateFor(?LaunchPromoCode $promoCode, ?User $user): array
    {
        if (! $promoCode) {
            return [
                'can_register' => false,
                'can_resume' => false,
                'message' => 'Este codigo promocional no existe.',
            ];
        }

        if ($promoCode->isAvailable()) {
            return [
                'can_register' => ! $user,
                'can_resume' => false,
                'message' => $user
                    ? 'Cierra sesion para usar este codigo en una cuenta nueva.'
                    : null,
            ];
        }

        if ($user && $promoCode->isOwnedBy($user)) {
            return [
                'can_register' => false,
                'can_resume' => ! $user->subscribed(),
                'message' => $user->subscribed()
                    ? 'Tu promocion ya esta activa.'
                    : 'Tu cuenta ya esta creada. Falta validar tu tarjeta en Stripe para activar los 6 meses gratis.',
            ];
        }

        return [
            'can_register' => false,
            'can_resume' => false,
            'message' => 'Este codigo ya fue usado o no esta disponible.',
        ];
    }
}
