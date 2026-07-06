<?php

namespace App\Http\Controllers;

use App\Models\PatientPreregistration;
use App\Models\PatientRegistrationLink;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PublicPatientPreregistrationController extends Controller
{
    public function show(string $token): View
    {
        $link = $this->findLink($token);

        if (! $link || ! $link->isAvailable()) {
            return view('qr.public-expired');
        }

        $qrSettings = $this->qrSettingsFor($link);

        return view('qr.public-form', [
            'clinicName' => $link->clinica->nombre,
            'token' => $token,
            'expiresAt' => $link->expires_at,
            'patientMessage' => $link->patient_message,
            'qrSettings' => $qrSettings,
            'requiredFields' => collect($qrSettings['qr_required_fields'] ?? []),
            'consentText' => str_replace(
                '{clinica}',
                $link->clinica->nombre,
                $qrSettings['qr_consent_text'] ?? ''
            ),
        ]);
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $linkForSettings = $this->findLink($token);

        if (! $linkForSettings || ! $linkForSettings->isAvailable()) {
            return redirect()->route('qr.public.expired');
        }

        $qrSettings = $this->qrSettingsFor($linkForSettings);
        $photoEnabled = (bool) ($qrSettings['qr_patient_photo_enabled'] ?? true);
        $cameraAllowed = $photoEnabled && (bool) ($qrSettings['qr_allow_camera_photo'] ?? true);
        $galleryAllowed = $photoEnabled && (bool) ($qrSettings['qr_allow_gallery_photo'] ?? true);
        $requiredFields = collect($qrSettings['qr_required_fields'] ?? []);

        $rules = [
            'nombre_completo' => ['required', 'string', 'max:255'],
            'identificacion' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:today'],
            'peso' => ['nullable', 'numeric', 'min:1', 'max:999.99'],
            'altura' => ['nullable', 'numeric', 'min:0.3', 'max:2.99'],
            'sexo' => ['nullable', Rule::in(['femenino', 'masculino', 'otro'])],
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'procedimiento' => ['nullable', 'string', 'max:255'],
            'motivo_consulta' => ['nullable', 'string', 'max:3000'],
            'alergias' => ['nullable', 'string', 'max:3000'],
            'enfermedades' => ['nullable', 'string', 'max:3000'],
            'medicamentos_actuales' => ['nullable', 'string', 'max:3000'],
            'antecedentes_medicos' => ['nullable', 'string', 'max:5000'],
            'observaciones' => ['nullable', 'string', 'max:3000'],
            'foto' => $galleryAllowed
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096']
                : ['prohibited'],
            'foto_camera' => $cameraAllowed
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096']
                : ['prohibited'],
            'privacy_consent' => ['accepted'],
        ];

        foreach ($requiredFields as $field) {
            if (isset($rules[$field]) && $rules[$field][0] === 'nullable') {
                $rules[$field][0] = 'required';
            }
        }

        $validated = $request->validate($rules, [
            'nombre_completo.required' => 'Ingresa tu nombre completo.',
            'fecha_nacimiento.required' => 'Ingresa tu fecha de nacimiento.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede estar en el futuro.',
            'telefono.required' => 'Ingresa un teléfono de contacto.',
            'identificacion.required' => 'Ingresa tu identificación.',
            'sexo.required' => 'Selecciona tu sexo.',
            'email.required' => 'Ingresa tu correo electrónico.',
            'direccion.required' => 'Ingresa tu dirección.',
            'peso.required' => 'Ingresa tu peso aproximado.',
            'altura.required' => 'Ingresa tu altura.',
            'procedimiento.required' => 'Ingresa el procedimiento o estudio solicitado.',
            'motivo_consulta.required' => 'Ingresa el motivo de consulta.',
            'alergias.required' => 'Indica tus alergias conocidas o escribe “Ninguna”.',
            'enfermedades.required' => 'Indica tus enfermedades actuales o escribe “Ninguna”.',
            'medicamentos_actuales.required' => 'Indica tus medicamentos actuales o escribe “Ninguno”.',
            'antecedentes_medicos.required' => 'Ingresa tus antecedentes médicos.',
            'observaciones.required' => 'Agrega tus observaciones.',
            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.mimes' => 'La foto debe estar en formato JPG, PNG o WebP.',
            'foto.max' => 'La foto no puede pesar más de 4 MB.',
            'foto.prohibited' => 'La carga desde galería no está habilitada para este pre-registro.',
            'foto_camera.image' => 'La foto tomada debe ser una imagen válida.',
            'foto_camera.mimes' => 'La foto tomada debe estar en formato JPG, PNG o WebP.',
            'foto_camera.max' => 'La foto tomada no puede pesar más de 4 MB.',
            'foto_camera.prohibited' => 'La toma de foto con cámara no está habilitada para este pre-registro.',
            'privacy_consent.accepted' => 'Debes aceptar el aviso de privacidad para enviar tus datos.',
        ]);

        $photo = ($cameraAllowed ? $request->file('foto_camera') : null)
            ?? ($galleryAllowed ? $request->file('foto') : null);

        if (
            $photoEnabled
            && ($cameraAllowed || $galleryAllowed)
            && ($qrSettings['qr_patient_photo_required'] ?? false)
            && ! $photo
        ) {
            throw ValidationException::withMessages([
                'foto' => 'La fotografía del paciente es obligatoria para este pre-registro.',
            ]);
        }

        $photoPath = null;

        try {
            $created = DB::transaction(function () use ($request, $token, $validated, $photo, &$photoPath): bool {
                $link = PatientRegistrationLink::withoutGlobalScopes()
                    ->where('token_hash', hash('sha256', $token))
                    ->lockForUpdate()
                    ->first();

                if (! $link || ! $link->isAvailable()) {
                    return false;
                }

                if ($photo) {
                    $photoPath = $photo->store(
                        'clinicas/'.$link->clinica_id.'/pacientes',
                        'public',
                    );
                }

                $birthDate = Carbon::parse($validated['fecha_nacimiento']);
                PatientPreregistration::withoutGlobalScopes()->create([
                    'clinica_id' => $link->clinica_id,
                    'registration_link_id' => $link->id,
                    'status' => 'pending',
                    'nombre_completo' => $validated['nombre_completo'],
                    'identificacion' => $validated['identificacion'] ?? null,
                    'fecha_nacimiento' => $birthDate->toDateString(),
                    'edad' => $birthDate->age,
                    'peso' => $validated['peso'] ?? null,
                    'altura' => $validated['altura'] ?? null,
                    'sexo' => $validated['sexo'] ?? null,
                    'direccion' => $validated['direccion'] ?? null,
                    'telefono' => $validated['telefono'],
                    'email' => $validated['email'] ?? null,
                    'procedimiento' => $validated['procedimiento'] ?? null,
                    'motivo_consulta' => $validated['motivo_consulta'] ?? null,
                    'alergias' => $validated['alergias'] ?? null,
                    'enfermedades' => $validated['enfermedades'] ?? null,
                    'medicamentos_actuales' => $validated['medicamentos_actuales'] ?? null,
                    'antecedentes_medicos' => $validated['antecedentes_medicos'] ?? null,
                    'observaciones' => $validated['observaciones'] ?? null,
                    'foto' => $photoPath,
                    'consent_accepted_at' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
                ]);

                $link->update([
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);

                return true;
            });
        } catch (\Throwable $exception) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            throw $exception;
        }

        if (! $created) {
            return redirect()->route('qr.public.expired');
        }

        return redirect()->route('qr.public.success');
    }

    public function success(): View
    {
        return view('qr.public-success');
    }

    public function expired(): View
    {
        return view('qr.public-expired');
    }

    private function findLink(string $token): ?PatientRegistrationLink
    {
        return PatientRegistrationLink::withoutGlobalScopes()
            ->with(['clinica', 'creator'])
            ->where('token_hash', hash('sha256', $token))
            ->first();
    }

    private function qrSettingsFor(PatientRegistrationLink $link): array
    {
        return $link->creator?->resolvedSettings()
            ?? User::defaultSettings();
    }
}
