<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'qr_default_expiration_hours' => ['sometimes', 'string', Rule::in(['24', '48', '168'])],
            'qr_default_patient_message' => ['sometimes', 'nullable', 'string', 'max:150'],
            'qr_whatsapp_template' => ['sometimes', 'nullable', 'string', 'max:500'],
            'qr_patient_photo_enabled' => ['sometimes', 'boolean'],
            'qr_patient_photo_required' => ['sometimes', 'boolean'],
            'qr_allow_camera_photo' => ['sometimes', 'boolean'],
            'qr_allow_gallery_photo' => ['sometimes', 'boolean'],
            'qr_required_fields' => ['sometimes', 'array'],
            'qr_required_fields.*' => [
                'string',
                Rule::in([
                    'identificacion',
                    'sexo',
                    'email',
                    'direccion',
                    'peso',
                    'altura',
                    'procedimiento',
                    'motivo_consulta',
                    'alergias',
                    'enfermedades',
                    'medicamentos_actuales',
                    'antecedentes_medicos',
                    'observaciones',
                ]),
            ],
            'qr_consent_text' => ['sometimes', 'nullable', 'string', 'max:700'],
            'qr_duplicate_check' => ['sometimes', 'boolean'],
            'qr_duplicate_action' => ['sometimes', 'string', Rule::in(['warn', 'block_acceptance'])],
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
