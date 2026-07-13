<?php

namespace App\Http\Controllers\Api\CustomerSuccess;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = Notification::query()
            ->where('user_id', $request->user()->id)
            ->where('tipo', 'anuncio')
            ->where('read', false)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return response()->json($notifications->map(fn (Notification $n) => [
            'id' => $n->id,
            'message' => $n->data['message'] ?? 'Nueva notificación',
            'categoria' => $n->data['categoria'] ?? null,
            'created_at' => $n->created_at?->toDateTimeString(),
            'formatted_date' => $n->created_at?->format('d/m/Y H:i') ?? '—',
        ]));
    }

    public function markAllRead(Request $request): JsonResponse
    {
        Notification::query()
            ->where('user_id', $request->user()->id)
            ->where('tipo', 'anuncio')
            ->where('read', false)
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['ok' => true]);
    }
}
