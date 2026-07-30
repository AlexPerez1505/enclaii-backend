<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\Notification;
use App\Services\DesktopAppReleaseNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class NotificationController extends Controller
{
    public function index(DesktopAppReleaseNotificationService $desktopAppNotifier): JsonResponse
    {
        try {
            $desktopAppNotifier->notifyCurrentReleaseForUser(Auth::user());
        } catch (Throwable $exception) {
            report($exception);
        }

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
            $payload = $n->data;
            if (is_string($payload)) {
                $decoded = json_decode($payload, true);
                $payload = is_array($decoded) ? $decoded : [];
            }
            if (!is_array($payload)) {
                $payload = [];
            }

            $merged = array_merge($payload, [
                'id'         => $n->id,
                'tipo'       => $n->tipo,
                'read'       => $n->read,
                'created_at' => $n->created_at?->toDateTimeString(),
            ]);

            if ($n->tipo === 'anuncio' && !isset($merged['contenido'])) {
                $merged['contenido'] = $anuncios[$payload['anuncio_id'] ?? 0] ?? null;
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
