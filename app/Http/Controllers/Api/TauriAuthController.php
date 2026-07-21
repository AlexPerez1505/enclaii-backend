<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SessionLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TauriAuthController extends Controller
{
    public function __construct(
        private readonly SessionLimitService $sessionLimits,
    ) {}

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (! Auth::once($credentials)) {
            return response()->json([
                'ok' => false,
                'message' => 'El correo o la contraseña son incorrectos.',
            ], 401);
        }

        $user = Auth::user();

        $newToken = $user->createToken('tauri-app');
        $closedTokens = $this->sessionLimits->enforceApiTokens(
            $user,
            $newToken->accessToken->getKey(),
            'tauri-app',
        );

        return response()->json([
            'ok' => true,
            'message' => 'Sesión iniciada correctamente.',
            'token' => $newToken->plainTextToken,
            'session_limit' => $this->sessionLimits->limitFor($user),
            'closed_sessions_by_limit' => $closedTokens,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'account_name' => $user->nombre_completo ?: $user->name,
                'email' => $user->email,
                'clinica_id' => $user->clinica_id,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }
}