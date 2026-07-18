<?php

namespace App\Http\Controllers;

use App\Models\UserSession;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSessionController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
    ) {}

    public function destroy(Request $request, string $session): JsonResponse
    {
        if ($session === $request->session()->getId()) {
            return response()->json([
                'message' => 'No puedes cerrar la sesión que estás utilizando actualmente.',
            ], 422);
        }

        $userSession = UserSession::query()
            ->where('user_id', $request->user()->id)
            ->whereKey($session)
            ->firstOrFail();

        $device = $userSession->deviceLabel();
        $userSession->delete();

        $this->activity->record(
            'session_revoked',
            'security',
            'Cerró una sesión en '.$device,
            user: $request->user(),
            request: $request,
        );

        return response()->json([
            'ok' => true,
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    public function destroyOthers(Request $request): JsonResponse
    {
        $closed = UserSession::query()
            ->where('user_id', $request->user()->id)
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        $this->activity->record(
            'other_sessions_revoked',
            'security',
            'Cerró las demás sesiones de su cuenta',
            metadata: ['closed_sessions' => $closed],
            user: $request->user(),
            request: $request,
        );

        return response()->json([
            'ok' => true,
            'message' => $closed === 1
                ? 'Se cerró 1 sesión.'
                : "Se cerraron {$closed} sesiones.",
            'closed_sessions' => $closed,
        ]);
    }
}
