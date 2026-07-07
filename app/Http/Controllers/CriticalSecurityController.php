<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CriticalSecurityController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
    ) {}

    public function authorizeAction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'scope' => ['required', Rule::in(['patients', 'studies', 'security_settings'])],
            'current_password' => ['required', 'current_password:web'],
        ], [
            'current_password.required' => 'Ingresa tu contraseña actual.',
            'current_password.current_password' => 'La contraseña no es correcta.',
        ]);

        $token = Str::random(64);
        $request->session()->put(
            'critical_authorizations.'.hash('sha256', $token),
            [
                'user_id' => $request->user()->id,
                'scope' => $validated['scope'],
                'expires_at' => now()->addMinutes(2)->timestamp,
            ],
        );

        $this->activity->record(
            'critical_action_authorized',
            'security',
            'Confirmó su contraseña para una acción sensible',
            metadata: ['scope' => $validated['scope']],
            request: $request,
            force: true,
        );

        return response()->json([
            'message' => 'Contraseña confirmada.',
            'token' => $token,
        ]);
    }
}
