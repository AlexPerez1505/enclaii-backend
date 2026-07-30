<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MediaPathService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TauriConfigurationController extends Controller
{
    public function __construct(
        private readonly MediaPathService $mediaPaths,
    ) {}

    public function show(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $settings = method_exists($user, 'resolvedSettings')
            ? $user->resolvedSettings()
            : (array) ($user->settings ?? []);

        $backups = method_exists($user, 'configurationBackups')
            ? $user->configurationBackups()->latest()->limit(10)->get()
            : collect();

        return response()->json([
            'ok' => true,
            'settings' => $settings,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'account_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->clinica_rol ?? 'medico',
                'clinic' => $user->clinica_nombre ?? optional($user->clinica)->nombre,
                'specialty' => $user->specialty,
                'professional_license' => $user->professional_license,
                'has_signature' => ! empty($user->signature_path),
                'signature_updated_at' => optional($user->signature_updated_at)?->toIso8601String(),
                'photo_url' => $this->mediaUrl($user->foto_perfil),
            ],
            'profile' => [
                'name' => $user->name,
                'apellido_paterno' => $user->apellido_paterno,
                'apellido_materno' => $user->apellido_materno,
                'email' => $user->email,
                'phone' => $user->phone,
                'fecha_nacimiento' => optional($user->fecha_nacimiento)?->format('Y-m-d'),
                'sexo' => $user->sexo,
                'specialty' => $user->specialty,
                'subespecialidad' => $user->subespecialidad,
                'professional_license' => $user->professional_license,
                'universidad' => $user->universidad,
                'clinica_nombre' => $user->clinica_nombre,
                'clinica_ciudad' => $user->clinica_ciudad,
                'clinica_direccion' => $user->clinica_direccion,
                'clinica_codigo_postal' => $user->clinica_codigo_postal,
                'clinica_telefono' => $user->clinica_telefono,
                'clinica_estado' => $user->clinica_estado,
                'rfc' => $user->rfc,
                'razon_social' => $user->razon_social,
                'regimen_fiscal' => $user->regimen_fiscal,
                'correo_facturacion' => $user->correo_facturacion,
                'photo_url' => $this->mediaUrl($user->foto_perfil),
                'tax_document_url' => $this->mediaUrl($user->constancia_fiscal),
            ],
            'clinic' => [
                'is_owner' => ($user->clinica_rol ?? null) === 'propietario',
                'members' => $this->clinicMembers($user),
                'invitations' => $this->clinicInvitations($user),
                'member_limit' => method_exists($user, 'clinicMemberLimit')
                    ? $user->clinicMemberLimit()
                    : null,
            ],
            'backups' => $backups->map(fn ($backup) => [
                'id' => $backup->id,
                'name' => $backup->name,
                'type' => $backup->type,
                'scope' => $backup->scope,
                'status' => $backup->status,
                'size' => $backup->size,
                'created_at' => optional($backup->created_at)?->toIso8601String(),
            ])->values(),
            'plan' => [
                'label' => $user->stripe_plan
                    ? ucfirst(str_replace('_', ' ', $user->stripe_plan))
                    : 'Sin plan',
                'status' => $user->stripe_subscription_status ?? 'active',
                'member_limit' => method_exists($user, 'clinicMemberLimit')
                    ? $user->clinicMemberLimit()
                    : null,
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'reading_mode' => ['sometimes', 'boolean'],
            'animations' => ['sometimes', 'boolean'],
            'compact' => ['sometimes', 'boolean'],
            'autosave' => ['sometimes', 'boolean'],
            'confirm_delete' => ['sometimes', 'boolean'],
            'timezone' => ['sometimes', 'string', 'max:80'],
            'date_format' => ['sometimes', 'string', 'max:30'],
            'time_format' => ['sometimes', 'string', 'max:30'],
            'default_view' => ['sometimes', 'string', 'max:80'],
            'items_per_page' => ['sometimes', 'integer', 'in:25,50,100'],
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
            'qr_default_expiration_hours' => ['sometimes', 'integer', 'in:24,48,168'],
            'qr_default_patient_message' => ['sometimes', 'nullable', 'string', 'max:150'],
            'qr_whatsapp_template' => ['sometimes', 'nullable', 'string', 'max:500'],
            'qr_patient_photo_enabled' => ['sometimes', 'boolean'],
            'qr_patient_photo_required' => ['sometimes', 'boolean'],
            'qr_allow_camera_photo' => ['sometimes', 'boolean'],
            'qr_allow_gallery_photo' => ['sometimes', 'boolean'],
            'qr_consent_text' => ['sometimes', 'nullable', 'string', 'max:700'],
            'qr_duplicate_check' => ['sometimes', 'boolean'],
            'qr_duplicate_action' => ['sometimes', 'string', 'in:warn,block_acceptance'],
            'qr_required_fields' => ['sometimes', 'array'],
            'qr_required_fields.*' => ['string', 'max:80'],
            'capture_auto_capture' => ['sometimes', 'boolean'],
            'capture_auto_save' => ['sometimes', 'boolean'],
            'capture_auto_interval' => ['sometimes', 'integer', 'in:10,30,60,120'],
        ]);

        $current = method_exists($user, 'resolvedSettings')
            ? $user->resolvedSettings()
            : (array) ($user->settings ?? []);

        $next = array_merge($current, $validated);

        if (method_exists($user, 'settings')) {
            $user->settings()->updateOrCreate([], ['settings' => $next]);
        } else {
            $user->settings = $next;
            $user->save();
        }

        return $this->show($request);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:120'],
            'apellido_paterno' => ['sometimes', 'nullable', 'string', 'max:100'],
            'apellido_materno' => ['sometimes', 'nullable', 'string', 'max:100'],
            'email' => ['sometimes', 'required', 'email', 'max:190', 'unique:users,email,'.$user->id],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'fecha_nacimiento' => ['sometimes', 'nullable', 'date'],
            'sexo' => ['sometimes', 'nullable', 'string', 'max:30'],
            'specialty' => ['sometimes', 'nullable', 'string', 'max:120'],
            'subespecialidad' => ['sometimes', 'nullable', 'string', 'max:120'],
            'professional_license' => ['sometimes', 'nullable', 'string', 'max:80'],
            'universidad' => ['sometimes', 'nullable', 'string', 'max:160'],
            'clinica_nombre' => ['sometimes', 'nullable', 'string', 'max:160'],
            'clinica_ciudad' => ['sometimes', 'nullable', 'string', 'max:120'],
            'clinica_direccion' => ['sometimes', 'nullable', 'string', 'max:255'],
            'clinica_codigo_postal' => ['sometimes', 'nullable', 'string', 'max:15'],
            'clinica_telefono' => ['sometimes', 'nullable', 'string', 'max:30'],
            'clinica_estado' => ['sometimes', 'nullable', 'string', 'max:120'],
            'rfc' => ['sometimes', 'nullable', 'string', 'max:20'],
            'razon_social' => ['sometimes', 'nullable', 'string', 'max:190'],
            'regimen_fiscal' => ['sometimes', 'nullable', 'string', 'max:190'],
            'correo_facturacion' => ['sometimes', 'nullable', 'email', 'max:190'],
        ]);

        $user->fill($validated)->save();

        return response()->json([
            'ok' => true,
            'message' => 'Perfil actualizado correctamente.',
            'profile' => $validated,
        ]);
    }

    public function updatePhoto(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $this->deleteStoredFile($user->foto_perfil);
        $path = media_store($request->file('foto'), $this->mediaPaths->userProfile($user));

        $user->foto_perfil = $path;
        $user->save();

        return response()->json([
            'ok' => true,
            'message' => 'Foto actualizada correctamente.',
            'url' => media_url($path),
        ]);
    }

    public function deletePhoto(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->deleteStoredFile($user->foto_perfil);
        $user->foto_perfil = null;
        $user->save();

        return response()->json([
            'ok' => true,
            'message' => 'Foto eliminada correctamente.',
        ]);
    }

    public function storeTaxDocument(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'constancia' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:8192'],
        ]);

        $this->deleteStoredFile($user->constancia_fiscal);
        $path = media_store($request->file('constancia'), $this->mediaPaths->userTaxDocuments($user));

        $user->constancia_fiscal = $path;
        $user->save();

        return response()->json([
            'ok' => true,
            'message' => 'Constancia fiscal actualizada.',
            'url' => media_url($path),
        ]);
    }

    public function deleteTaxDocument(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->deleteStoredFile($user->constancia_fiscal);
        $user->constancia_fiscal = null;
        $user->save();

        return response()->json([
            'ok' => true,
            'message' => 'Constancia fiscal eliminada.',
        ]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->password_changed_at = now();
        $user->save();

        return response()->json([
            'ok' => true,
            'message' => 'Contraseña actualizada correctamente.',
        ]);
    }

    public function removeMember(Request $request, int $member): JsonResponse
    {
        /** @var User $owner */
        $owner = $request->user();

        abort_unless(($owner->clinica_rol ?? null) === 'propietario', 403);

        $target = User::query()->findOrFail($member);

        abort_if($target->id === $owner->id, 422, 'No puedes retirarte a ti mismo.');
        abort_if(($target->clinica_rol ?? null) === 'propietario', 422, 'No puedes retirar al propietario.');

        $target->clinica_id = null;
        $target->clinica_rol = null;
        $target->save();

        return response()->json([
            'ok' => true,
            'message' => 'Usuario retirado de la clínica.',
        ]);
    }

    public function revokeInvitation(Request $request, int $invitation): JsonResponse
    {
        /** @var User $owner */
        $owner = $request->user();

        abort_unless(($owner->clinica_rol ?? null) === 'propietario', 403);

        if (! method_exists($owner, 'clinicInvitations')) {
            abort(404);
        }

        $owner->clinicInvitations()->whereKey($invitation)->firstOrFail()->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Invitación cancelada.',
        ]);
    }

    private function clinicMembers(User $user): array
    {
        if ($user->clinica && method_exists($user->clinica, 'users')) {
            return $user->clinica->users()
                ->get()
                ->map(fn (User $member) => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'role' => $member->clinica_rol,
                    'is_current_user' => $member->is($user),
                    'last_activity' => 'Sin acceso reciente',
                ])
                ->values()
                ->all();
        }

        return [[
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->clinica_rol ?? 'propietario',
            'is_current_user' => true,
            'last_activity' => 'Ahora',
        ]];
    }

    private function clinicInvitations(User $user): array
    {
        if (! method_exists($user, 'clinicInvitations')) {
            return [];
        }

        return $user->clinicInvitations()
            ->get()
            ->map(fn ($invitation) => [
                'id' => $invitation->id,
                'email' => $invitation->email,
                'role' => $invitation->rol,
            ])
            ->values()
            ->all();
    }

    private function mediaUrl(?string $path): ?string
    {
        return $path ? media_url($path) : null;
    }

    private function deleteStoredFile(?string $path): void
    {
        media_delete($path);
    }
}
