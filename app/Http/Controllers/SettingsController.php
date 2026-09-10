<?php

namespace App\Http\Controllers;

use App\Models\LegalAcceptance;
use App\Models\User;
use App\Services\MediaPathService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function __construct(
        private readonly MediaPathService $mediaPaths,
    ) {}

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
            'notif_announcement_email' => ['sometimes', 'boolean'],
            'notif_announcement_screen' => ['sometimes', 'boolean'],
            'notif_new_studies_email' => ['sometimes', 'boolean'],
            'notif_new_studies_screen' => ['sometimes', 'boolean'],
            'notif_reminders_email' => ['sometimes', 'boolean'],
            'notif_reminders_screen' => ['sometimes', 'boolean'],
            'notif_updates_email' => ['sometimes', 'boolean'],
            'notif_updates_screen' => ['sometimes', 'boolean'],
            'notif_maintenance_email' => ['sometimes', 'boolean'],
            'notif_maintenance_screen' => ['sometimes', 'boolean'],
            'notif_privacy_email' => ['sometimes', 'boolean'],
            'notif_privacy_screen' => ['sometimes', 'boolean'],
            'notif_messages_screen' => ['sometimes', 'boolean'],
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
            'ai_analyze_photos' => ['sometimes', 'boolean'],
            'ai_suggest_diagnoses' => ['sometimes', 'boolean'],
            'ai_recommend_procedures' => ['sometimes', 'boolean'],
            'session_timeout' => ['sometimes', 'integer', 'min:0', 'max:1440'],
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

    public function updatePerfil(Request $request): JsonResponse
    {
        $request->merge(array_map(fn($v) => $v === '' ? null : $v, $request->all()));

        $validated = $request->validate([
            'name'               => ['sometimes', 'nullable', 'string', 'max:100'],
            'apellido_paterno'   => ['sometimes', 'nullable', 'string', 'max:100'],
            'apellido_materno'   => ['sometimes', 'nullable', 'string', 'max:100'],
            'fecha_nacimiento'   => ['sometimes', 'nullable', 'date'],
            'sexo'               => ['sometimes', 'nullable', 'string', 'max:20'],
            'email'              => ['sometimes', 'nullable', 'email', 'max:150'],
            'phone'              => ['sometimes', 'nullable', 'string', 'max:30'],
            'specialty'          => ['sometimes', 'nullable', 'string', 'max:150'],
            'subespecialidad'    => ['sometimes', 'nullable', 'string', 'max:150'],
            'professional_license' => ['sometimes', 'nullable', 'string', 'max:60'],
            'universidad'        => ['sometimes', 'nullable', 'string', 'max:200'],
            'clinica_nombre'     => ['sometimes', 'nullable', 'string', 'max:200'],
            'clinica_ciudad'     => ['sometimes', 'nullable', 'string', 'max:100'],
            'clinica_direccion'  => ['sometimes', 'nullable', 'string', 'max:255'],
            'clinica_codigo_postal' => ['sometimes', 'nullable', 'string', 'max:10'],
            'clinica_telefono'   => ['sometimes', 'nullable', 'string', 'max:30'],
            'clinica_estado'     => ['sometimes', 'nullable', 'string', 'max:100'],
            'rfc'                => ['sometimes', 'nullable', 'string', 'max:20'],
            'razon_social'       => ['sometimes', 'nullable', 'string', 'max:200'],
            'regimen_fiscal'     => ['sometimes', 'nullable', 'string', 'max:255'],
            'correo_facturacion' => ['sometimes', 'nullable', 'email', 'max:150'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $cleaned = array_map(fn($v) => ($v === '' || $v === null) ? null : $v, $validated);
        $user->update($cleaned);

        return response()->json(['ok' => true]);
    }

    public function updateFoto(Request $request): JsonResponse
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        /** @var User $user */
        $user = $request->user();

        media_delete($user->foto_perfil);

        $path = media_store($request->file('foto'), $this->mediaPaths->userProfile($user));
        $user->update(['foto_perfil' => $path]);

        return response()->json([
            'ok' => true,
            'url' => media_url($path),
        ]);
    }

    public function uploadConstancia(Request $request): JsonResponse
    {
        $request->validate([
            'constancia' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:8192'],
        ]);

        /** @var User $user */
        $user = $request->user();

        media_delete($user->constancia_fiscal);

        $path = media_store($request->file('constancia'), $this->mediaPaths->userTaxDocuments($user));
        $user->update(['constancia_fiscal' => $path]);

        $ext = strtolower($request->file('constancia')->getClientOriginalExtension());

        return response()->json([
            'ok'   => true,
            'url'  => media_url($path),
            'ext'  => $ext,
            'name' => $request->file('constancia')->getClientOriginalName(),
        ]);
    }

    public function deleteConstancia(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        media_delete($user->constancia_fiscal);

        $user->update(['constancia_fiscal' => null]);

        return response()->json(['ok' => true]);
    }

    public function deleteFoto(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        media_delete($user->foto_perfil);

        $user->update(['foto_perfil' => null]);

        return response()->json(['ok' => true]);
    }

    public function storeLegalAcceptances(Request $request): JsonResponse
    {
        $request->validate([
            'documentos'   => ['required', 'array', 'min:1'],
            'documentos.*' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $now  = now();

        foreach ($request->documentos as $documento) {
            LegalAcceptance::create([
                'user_id'   => $user->id,
                'documento' => $documento,
                'version'   => '1.0',
                'fecha'     => $now->toDateString(),
                'hora'      => $now->toTimeString(),
                'ip'        => $request->ip(),
                'navegador' => $request->userAgent(),
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
