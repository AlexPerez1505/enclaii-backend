<?php

namespace App\Http\Controllers;

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

        return response()->json($notifications->map(fn (Notification $n) => [
            'id' => $n->id,
            'tipo' => $n->tipo,
            ...$n->data,
            'read' => $n->read,
            'created_at' => $n->created_at?->toDateTimeString(),
        ]));
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
