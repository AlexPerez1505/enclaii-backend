<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Guarda la configuración general del usuario autenticado.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'timezone' => ['sometimes', 'timezone', 'max:50'],
            'date_format' => ['sometimes', 'string', 'max:20'],
            'time_format' => ['sometimes', 'in:12 horas (AM/PM),24 horas', 'max:30'],
            'autosave' => ['sometimes', 'boolean'],
            'confirm_delete' => ['sometimes', 'boolean'],
            'default_view' => ['sometimes', 'string', 'max:50'],
            'items_per_page' => ['sometimes', 'string', 'max:10'],
            'animations' => ['sometimes', 'boolean'],
            'compact' => ['sometimes', 'boolean'],
            'reading_mode' => ['sometimes', 'boolean'],
            'notif_email' => ['sometimes', 'boolean'],
            'notif_push' => ['sometimes', 'boolean'],
            'notif_new_studies' => ['sometimes', 'boolean'],
            'notif_reports' => ['sometimes', 'boolean'],
            'notif_reminders' => ['sometimes', 'boolean'],
            'capture_auto_capture' => ['sometimes', 'boolean'],
            'capture_auto_save' => ['sometimes', 'boolean'],
            'capture_auto_interval' => ['sometimes', 'integer', 'min:5', 'max:300'],
        ]);

        /** @var User $user */
        $user = $request->user();

        // Solo se conservan claves conocidas (definidas en los defaults).
        $allowed = array_keys(User::defaultSettings());
        $incoming = array_intersect_key($validated, array_flip($allowed));

        $user->settings = array_merge($user->settings ?? [], $incoming);
        $user->save();

        return response()->json([
            'ok' => true,
            'settings' => $user->resolvedSettings(),
        ]);
    }
}
