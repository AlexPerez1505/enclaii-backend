<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $notifications = Notification::query()
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        $anuncioIds = $notifications
            ->where('tipo', 'anuncio')
            ->pluck('data.anuncio_id')
            ->filter()
            ->unique();

        $anuncios = $anuncioIds->isNotEmpty()
            ? Anuncio::whereIn('id', $anuncioIds)->pluck('contenido', 'id')
            : collect();

        return response()->json($notifications->map(function (Notification $n) use ($anuncios) {
            $merged = array_merge($n->data ?? [], [
                'id'         => $n->id,
                'tipo'       => $n->tipo,
                'read'       => $n->read,
                'created_at' => $n->created_at?->toDateTimeString(),
            ]);

            if ($n->tipo === 'anuncio' && !isset($merged['contenido'])) {
                $merged['contenido'] = $anuncios[$n->data['anuncio_id'] ?? 0] ?? null;
            }

            return $merged;
        }));
    }

    public function markAllRead(): JsonResponse
    {
        Notification::query()
            ->where('user_id', Auth::id())
            ->where('read', false)
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['ok' => true]);
    }

    public function markRead(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:notifications,id'],
        ]);

        Notification::query()
            ->where('user_id', Auth::id())
            ->whereIn('id', $validated['ids'])
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:notifications,id'],
        ]);

        Notification::query()
            ->where('user_id', Auth::id())
            ->whereIn('id', $validated['ids'])
            ->delete();

        return response()->json(['ok' => true]);
    }
}
