<?php

namespace Tests\Feature;

use App\Models\Clinica;
use App\Models\Paciente;
use App\Models\PatientPreregistration;
use App\Models\PatientRegistrationLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class QrPatientPreregistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_generate_a_locally_rendered_encrypted_qr_link(): void
    {
        [, $owner] = $this->clinicOwner('qr-owner@example.com');

        $this->actingAs($owner)
            ->post(route('qr.links.store'), [
                'expires_in_hours' => 48,
                'patient_message' => 'Trae tus estudios anteriores a la consulta.',
            ])
            ->assertRedirect(route('qr.index'));

        $link = PatientRegistrationLink::query()->firstOrFail();
        $rawStoredToken = DB::table('patient_registration_links')
            ->where('id', $link->id)
            ->value('token');

        $this->assertSame(64, strlen($link->token));
        $this->assertNotSame($link->token, $rawStoredToken);
        $this->assertSame(hash('sha256', $link->token), $link->token_hash);
        $this->assertSame('Trae tus estudios anteriores a la consulta.', $link->patient_message);

        $this->get(route('qr.links.image', $link))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml')
            ->assertSee('<svg', false);

        Auth::logout();
        $this->get(route('qr.public.show', $link->token))
            ->assertOk()
            ->assertSee('Mensaje de la clínica')
            ->assertSee('Trae tus estudios anteriores a la consulta.');
    }

    public function test_qr_configuration_sets_default_expiration_and_patient_message(): void
    {
        [, $owner] = $this->clinicOwner('qr-defaults@example.com');
        $this->travelTo(now()->startOfSecond());
        $owner->forceFill([
            'settings' => array_merge($owner->settings ?? [], [
                'qr_default_expiration_hours' => '24',
                'qr_default_patient_message' => 'Llega 15 minutos antes de tu cita.',
            ]),
        ])->save();

        $this->actingAs($owner)
            ->get(route('configuracion'))
            ->assertOk()
            ->assertSee('QR y Pre-registro')
            ->assertSee('Valores predeterminados del QR');

        $this->post(route('qr.links.store'), [])
            ->assertRedirect(route('qr.index'));

        $link = PatientRegistrationLink::query()->firstOrFail();
        $this->assertSame(
            now()->addHours(24)->toDateTimeString(),
            $link->expires_at->toDateTimeString(),
        );
        $this->assertSame('Llega 15 minutos antes de tu cita.', $link->patient_message);

        Auth::logout();
        $this->get(route('qr.public.show', $link->token))
            ->assertOk()
            ->assertSee('Llega 15 minutos antes de tu cita.');
    }

    public function test_patient_can_submit_public_form_once_without_an_account(): void
    {
        [$clinic, $owner] = $this->clinicOwner('public-owner@example.com');
        $link = $this->registrationLink($clinic, $owner);
        Auth::logout();

        $this->get(route('qr.public.show', $link->token))
            ->assertOk()
            ->assertSee($clinic->nombre)
            ->assertSee('Pre-registro de paciente');

        $this->post(route('qr.public.store', $link->token), $this->patientPayload())
            ->assertRedirect(route('qr.public.success'));

        $this->assertDatabaseHas('patient_preregistrations', [
            'clinica_id' => $clinic->id,
            'registration_link_id' => $link->id,
            'nombre_completo' => 'Paciente QR',
            'status' => 'pending',
        ]);
        $this->assertSame('submitted', $link->fresh()->status);

        $this->post(route('qr.public.store', $link->token), $this->patientPayload())
            ->assertRedirect(route('qr.public.expired'));
        $this->assertDatabaseCount('patient_preregistrations', 1);
    }

    public function test_doctor_accepts_preregistration_and_creates_patient_in_same_clinic(): void
    {
        [$clinic, $owner] = $this->clinicOwner('accept-owner@example.com');
        $link = $this->registrationLink($clinic, $owner);
        $this->post(route('qr.public.store', $link->token), $this->patientPayload())
            ->assertRedirect(route('qr.public.success'));
        $preregistration = PatientPreregistration::withoutGlobalScopes()->firstOrFail();

        $this->actingAs($owner)
            ->post(route('qr.preregistrations.accept', $preregistration))
            ->assertRedirect();

        $preregistration->refresh();
        $patient = Paciente::withoutGlobalScopes()->findOrFail($preregistration->patient_id);

        $this->assertSame('accepted', $preregistration->status);
        $this->assertSame($owner->id, $preregistration->reviewed_by);
        $this->assertSame($clinic->id, $patient->clinica_id);
        $this->assertSame('Paciente QR', $patient->nombre_completo);
        $this->assertSame('Penicilina', $patient->alergias);
        $this->assertSame('P-001', $patient->folio);
    }

    public function test_patient_can_take_a_photo_and_it_is_added_to_the_accepted_record(): void
    {
        Storage::fake('public');
        [$clinic, $owner] = $this->clinicOwner('photo-owner@example.com');
        $link = $this->registrationLink($clinic, $owner);

        $this->post(route('qr.public.store', $link->token), array_merge(
            $this->patientPayload(),
            ['foto_camera' => $this->fakePatientPhoto('selfie.png')],
        ))->assertRedirect(route('qr.public.success'));

        $preregistration = PatientPreregistration::withoutGlobalScopes()->firstOrFail();
        $this->assertNotNull($preregistration->foto);
        Storage::disk('public')->assertExists($preregistration->foto);

        $this->actingAs($owner)
            ->get(route('qr.index'))
            ->assertOk()
            ->assertSee('Fotografía enviada por el paciente')
            ->assertSee(asset('storage/'.$preregistration->foto), false);

        $this->post(route('qr.preregistrations.accept', $preregistration))
            ->assertRedirect();

        $patient = Paciente::withoutGlobalScopes()->findOrFail($preregistration->fresh()->patient_id);
        $this->assertSame($preregistration->foto, $patient->foto);
        Storage::disk('public')->assertExists($patient->foto);
    }

    public function test_qr_configuration_can_require_photo_and_extra_public_fields(): void
    {
        Storage::fake('public');
        [$clinic, $owner] = $this->clinicOwner('required-qr-settings@example.com');
        $owner->forceFill([
            'settings' => array_merge($owner->settings ?? [], [
                'qr_patient_photo_required' => true,
                'qr_required_fields' => ['procedimiento', 'alergias'],
                'qr_consent_text' => 'Acepto que {clinica} use mis datos para preparar mi atención.',
            ]),
        ])->save();
        $link = $this->registrationLink($clinic, $owner);

        Auth::logout();
        $this->get(route('qr.public.show', $link->token))
            ->assertOk()
            ->assertSee('Foto del paciente')
            ->assertSee('obligatoria')
            ->assertSee('Acepto que '.$clinic->nombre.' use mis datos');

        $payload = $this->patientPayload();
        unset($payload['procedimiento']);

        $this->post(route('qr.public.store', $link->token), array_merge(
            $payload,
            ['foto_camera' => $this->fakePatientPhoto('missing-procedure.png')],
        ))->assertSessionHasErrors(['procedimiento']);

        $this->post(route('qr.public.store', $link->token), $this->patientPayload())
            ->assertSessionHasErrors(['foto']);

        $this->post(route('qr.public.store', $link->token), array_merge(
            $this->patientPayload(),
            ['foto_camera' => $this->fakePatientPhoto('required-selfie.png')],
        ))->assertRedirect(route('qr.public.success'));

        $preregistration = PatientPreregistration::withoutGlobalScopes()->firstOrFail();
        $this->assertNotNull($preregistration->foto);
        Storage::disk('public')->assertExists($preregistration->foto);
    }

    public function test_rejecting_preregistration_removes_unneeded_patient_photo(): void
    {
        Storage::fake('public');
        [$clinic, $owner] = $this->clinicOwner('rejected-photo-owner@example.com');
        $link = $this->registrationLink($clinic, $owner);

        $this->post(route('qr.public.store', $link->token), array_merge(
            $this->patientPayload(),
            ['foto' => $this->fakePatientPhoto('gallery-photo.png')],
        ))->assertRedirect(route('qr.public.success'));

        $preregistration = PatientPreregistration::withoutGlobalScopes()->firstOrFail();
        $photoPath = $preregistration->foto;
        Storage::disk('public')->assertExists($photoPath);

        $this->actingAs($owner)
            ->post(route('qr.preregistrations.reject', $preregistration))
            ->assertRedirect();

        $this->assertSame('rejected', $preregistration->fresh()->status);
        $this->assertNull($preregistration->fresh()->foto);
        Storage::disk('public')->assertMissing($photoPath);
        $this->assertDatabaseCount('pacientes', 0);
    }

    public function test_other_clinic_cannot_view_or_accept_preregistration(): void
    {
        [$clinicA, $ownerA] = $this->clinicOwner('clinic-a-qr@example.com');
        [, $ownerB] = $this->clinicOwner('clinic-b-qr@example.com');
        $link = $this->registrationLink($clinicA, $ownerA);
        $this->post(route('qr.public.store', $link->token), $this->patientPayload());
        $preregistration = PatientPreregistration::withoutGlobalScopes()->firstOrFail();

        $this->actingAs($ownerB)
            ->get(route('qr.index'))
            ->assertOk()
            ->assertDontSee('Paciente QR');

        $this->post(route('qr.preregistrations.accept', $preregistration))
            ->assertNotFound();
        $this->assertDatabaseMissing('pacientes', [
            'clinica_id' => $ownerB->clinica_id,
            'nombre_completo' => 'Paciente QR',
        ]);
    }

    public function test_qr_configuration_can_block_accepting_possible_duplicate_patients(): void
    {
        [$clinic, $owner] = $this->clinicOwner('duplicate-block-owner@example.com');
        $owner->forceFill([
            'settings' => array_merge($owner->settings ?? [], [
                'qr_duplicate_check' => true,
                'qr_duplicate_action' => 'block_acceptance',
            ]),
        ])->save();
        Paciente::withoutGlobalScopes()->create([
            'clinica_id' => $clinic->id,
            'folio' => 'P-EXISTING',
            'nombre_completo' => 'Paciente Existente',
            'telefono' => '7221234567',
            'email' => 'otro@example.com',
        ]);
        $link = $this->registrationLink($clinic, $owner);

        Auth::logout();
        $this->post(route('qr.public.store', $link->token), $this->patientPayload())
            ->assertRedirect(route('qr.public.success'));
        $preregistration = PatientPreregistration::withoutGlobalScopes()
            ->where('status', 'pending')
            ->firstOrFail();

        $this->actingAs($owner)
            ->post(route('qr.preregistrations.accept', $preregistration))
            ->assertStatus(422);

        $this->assertSame('pending', $preregistration->fresh()->status);
        $this->assertDatabaseMissing('pacientes', [
            'clinica_id' => $clinic->id,
            'nombre_completo' => 'Paciente QR',
        ]);
    }

    public function test_expired_qr_does_not_accept_information(): void
    {
        [$clinic, $owner] = $this->clinicOwner('expired-owner@example.com');
        $link = $this->registrationLink($clinic, $owner, now()->subMinute());

        $this->get(route('qr.public.show', $link->token))
            ->assertOk()
            ->assertSee('Enlace no disponible');

        $this->post(route('qr.public.store', $link->token), $this->patientPayload())
            ->assertRedirect(route('qr.public.expired'));
        $this->assertDatabaseCount('patient_preregistrations', 0);
    }

    public function test_revoked_qr_keeps_its_expiration_and_image_but_rejects_public_access(): void
    {
        [$clinic, $owner] = $this->clinicOwner('revoked-owner@example.com');
        $expiresAt = now()->addHours(48)->startOfSecond();
        $link = $this->registrationLink($clinic, $owner, $expiresAt);

        $this->actingAs($owner)
            ->delete(route('qr.links.destroy', $link))
            ->assertRedirect();

        $link->refresh();
        $this->assertSame('revoked', $link->status);
        $this->assertTrue($expiresAt->equalTo($link->expires_at));

        $this->get(route('qr.links.image', $link))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml');

        $this->get(route('qr.index'))
            ->assertOk()
            ->assertSee('Cancelado')
            ->assertSee('Generar reemplazo')
            ->assertSee('Eliminar')
            ->assertDontSee('Copiar enlace');

        Auth::logout();
        $this->get(route('qr.public.show', $link->token))
            ->assertOk()
            ->assertSee('Enlace no disponible');
    }

    public function test_doctor_can_remove_used_qr_without_deleting_patient_information(): void
    {
        [$clinic, $owner] = $this->clinicOwner('archive-owner@example.com');
        $link = $this->registrationLink($clinic, $owner);

        $this->post(route('qr.public.store', $link->token), $this->patientPayload())
            ->assertRedirect(route('qr.public.success'));
        $preregistration = PatientPreregistration::withoutGlobalScopes()->firstOrFail();

        $this->actingAs($owner)
            ->delete(route('qr.links.archive', $link))
            ->assertRedirect();

        $this->assertNotNull($link->fresh()->archived_at);
        $this->assertDatabaseHas('patient_preregistrations', [
            'id' => $preregistration->id,
            'nombre_completo' => 'Paciente QR',
        ]);

        $this->get(route('qr.index'))
            ->assertOk()
            ->assertDontSee('QR #'.$link->id);

        Auth::logout();
        $this->get(route('qr.public.show', $link->token))
            ->assertOk()
            ->assertSee('Enlace no disponible');
    }

    private function clinicOwner(string $email): array
    {
        $clinic = Clinica::create(['nombre' => 'Clínica QR '.Str::before($email, '@')]);
        $owner = User::create([
            'clinica_id' => $clinic->id,
            'clinica_rol' => 'propietario',
            'name' => 'Doctor QR',
            'email' => $email,
            'password' => 'SecurePassword1',
            'stripe_plan' => 'clinica',
            'subscription_status' => 'active',
        ]);

        return [$clinic, $owner];
    }

    private function registrationLink(
        Clinica $clinic,
        User $owner,
        mixed $expiresAt = null,
    ): PatientRegistrationLink {
        $token = Str::random(64);

        return PatientRegistrationLink::withoutGlobalScopes()->create([
            'clinica_id' => $clinic->id,
            'created_by' => $owner->id,
            'token' => $token,
            'token_hash' => hash('sha256', $token),
            'status' => 'active',
            'expires_at' => $expiresAt ?? now()->addHours(48),
        ]);
    }

    private function patientPayload(): array
    {
        return [
            'nombre_completo' => 'Paciente QR',
            'identificacion' => 'ID-QR-001',
            'fecha_nacimiento' => '1990-05-10',
            'peso' => 70,
            'altura' => 1.72,
            'sexo' => 'masculino',
            'direccion' => 'Dirección de prueba',
            'telefono' => '7221234567',
            'email' => 'paciente.qr@example.com',
            'procedimiento' => 'Endoscopia',
            'motivo_consulta' => 'Dolor abdominal',
            'alergias' => 'Penicilina',
            'enfermedades' => 'Ninguna',
            'medicamentos_actuales' => 'Ninguno',
            'antecedentes_medicos' => 'Sin cirugías previas',
            'observaciones' => 'Prefiere cita por la mañana',
            'privacy_consent' => '1',
        ];
    }

    private function fakePatientPhoto(string $name): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $name,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
        );
    }
}
