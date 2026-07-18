<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
    ) {}

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(8)->mixedCase()->numbers(),
            ],
        ], [
            'current_password.required' => 'Ingresa tu contraseña actual.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'password.required' => 'Ingresa una contraseña nueva.',
            'password.confirmed' => 'La confirmación no coincide con la contraseña nueva.',
            'password.different' => 'La contraseña nueva debe ser diferente de la actual.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.mixed' => 'Incluye letras mayúsculas y minúsculas.',
            'password.numbers' => 'Incluye al menos un número.',
        ]);

        /** @var User $user */
        $user = $request->user();

        Auth::logoutOtherDevices($validated['current_password']);

        $user->forceFill([
            'password' => $validated['password'],
            'password_changed_at' => now(),
        ])->save();

        $request->session()->regenerate();
        $this->activity->record(
            'password_changed',
            'security',
            'Cambió su contraseña',
            user: $user,
            request: $request,
        );

        return response()->json([
            'ok' => true,
            'message' => 'Contraseña actualizada correctamente.',
            'password_changed_at' => $user->password_changed_at->toIso8601String(),
        ]);
    }
}
