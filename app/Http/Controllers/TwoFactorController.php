<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use App\Services\TwoFactorEmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    public function __construct(
        private readonly TwoFactorEmailService $twoFactor,
        private readonly ActivityLogger $activity,
    ) {}

    public function enable(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->twoFactor->generateAndSend($user);

        return response()->json([
            'ok' => true,
            'message' => 'Se envió un código de verificación a tu correo.',
        ]);
    }

    public function confirm(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        if (! $this->twoFactor->verify($user, $request->input('code'))) {
            return response()->json([
                'ok' => false,
                'message' => 'El código es incorrecto o ya expiró.',
            ], 422);
        }

        $this->twoFactor->markConfirmed($user);
        $user->setRelation('securitySetting', $user->securitySetting()->first());

        $this->activity->record(
            'two_factor_enabled',
            'security',
            'Activó la verificación en dos pasos por correo',
            user: $user,
            request: $request,
            force: true,
        );

        return response()->json([
            'ok' => true,
            'message' => 'Verificación en dos pasos activada.',
        ]);
    }

    public function disable(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->twoFactor->disable($user);

        $this->activity->record(
            'two_factor_disabled',
            'security',
            'Desactivó la verificación en dos pasos',
            user: $user,
            request: $request,
            force: true,
        );

        return response()->json([
            'ok' => true,
            'message' => 'Verificación en dos pasos desactivada.',
        ]);
    }

    public function challenge(Request $request)
    {
        if (! $request->session()->has('2fa.pending_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function verifyChallenge(Request $request)
    {
        $userId = $request->session()->get('2fa.pending_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $key = '2fa.challenge.'.$user->id.':'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'code' => 'Demasiados intentos. Inténtalo más tarde.',
            ]);
        }

        RateLimiter::hit($key);

        if (! $this->twoFactor->verify($user, $request->input('code'))) {
            return back()->withErrors(['code' => 'El código es incorrecto o ya expiró.']);
        }

        RateLimiter::clear($key);

        Auth::loginUsingId($user->id, $request->session()->get('2fa.remember', false));
        $request->session()->forget(['2fa.pending_user_id', '2fa.remember']);

        $request->session()->regenerate();

        return redirect()->intended(route('configuracion'));
    }

    public function resend(Request $request)
    {
        $userId = $request->session()->get('2fa.pending_user_id')
            ?? $request->user()?->id;

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        $key = '2fa.resend.'.$user->id.':'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'ok' => false,
                'message' => 'Espera un momento antes de reenviar.',
            ], 429);
        }

        RateLimiter::hit($key);
        $this->twoFactor->generateAndSend($user);

        return response()->json([
            'ok' => true,
            'message' => 'Se reenvió el código.',
        ]);
    }
}
