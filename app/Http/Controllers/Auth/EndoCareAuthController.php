<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Clinica;
use App\Models\ClinicaInvitation;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EndoCareAuthController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
    ) {}

    public function showLogin()
    {
        return view('auth.endocare-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $this->syncCurrentDatabaseSession($request, $user);
            $this->activity->record(
                'login',
                'authentication',
                'Inició sesión',
                user: $user,
                request: $request,
            );

            if ($user->hasRole('Customer Success')) {
                return redirect()->route('customer-success.dashboard');
            }

            if (!$user->subscribed()) {
                return redirect()->route('plan.only');
            }

            return redirect()->route($this->defaultRouteFor($user));
        }

        return back()
            ->withErrors([
                'email' => 'El correo o la contraseña son incorrectos.',
            ])
            ->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.endocare-register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
        ]);

        $invitation = ClinicaInvitation::pendingForEmail($data['email']);

        $user = DB::transaction(function () use ($data, $invitation): User {
            if ($invitation) {
                $lockedInvitation = ClinicaInvitation::query()
                    ->whereKey($invitation->id)
                    ->whereNull('accepted_at')
                    ->whereNull('revoked_at')
                    ->where('expires_at', '>', now())
                    ->lockForUpdate()
                    ->firstOrFail();
                $clinica = $lockedInvitation->clinica;
                $rol = $lockedInvitation->rol;
            } else {
                $clinica = Clinica::shared();
                $rol = 'usuario';
            }

            $user = User::create([
                'clinica_id' => $clinica->id,
                'clinica_rol' => $rol,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            if (isset($lockedInvitation)) {
                $lockedInvitation->update([
                    'accepted_by' => $user->id,
                    'accepted_at' => now(),
                ]);
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();
        $this->syncCurrentDatabaseSession($request, $user);

        $this->activity->record(
            'account_created',
            'authentication',
            'Creó su cuenta',
            user: $user,
            request: $request,
        );

        if ($invitation) {
            return redirect()->route('dashboard')
                ->with('success', 'Ahora formas parte de '.$invitation->clinica->nombre.'.');
        }

        return redirect()->route('plan.only')
            ->with('success', 'Cuenta creada correctamente. Selecciona un plan para acceder al sistema.');
    }

    /**
     * Determina la ruta inicial según la "Vista predeterminada" del usuario.
     */
    private function defaultRouteFor(User $user): string
    {
        $view = $user->resolvedSettings()['default_view'] ?? 'Dashboard';

        return match ($view) {
            'IA Reportes' => 'ia-reportes',
            'Agenda' => 'agendar',
            'Mensajes' => 'mensajes',
            'Nuevo estudio' => 'nuevo-estudio',
            'Galería' => 'galeria',
            default => 'dashboard',
        };
    }

    private function syncCurrentDatabaseSession(Request $request, User $user): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        $table = (string) config('session.table', 'sessions');
        if (! Schema::hasTable($table)) {
            return;
        }

        DB::table($table)->updateOrInsert(
            ['id' => $request->session()->getId()],
            [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'payload' => '',
                'last_activity' => now()->timestamp,
            ]
        );
    }

    public function logout(Request $request)
    {
        $this->activity->record(
            'logout',
            'authentication',
            'Cerró sesión',
            user: $request->user(),
            request: $request,
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function logoutCs(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
