<?php

namespace App\Http\Controllers;

use App\Models\PatientPreregistration;
use App\Models\PatientRegistrationLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PublicPatientPreregistrationController extends Controller
{
    public function show(string $token): View
    {
        $link = $this->findLink($token);

        if (! $link || ! $link->isAvailable()) {
            return view('qr.public-expired');
        }

        return view('qr.public-form', [
            'clinicName' => $link->clinica->nombre,
            'token' => $token,
            'expiresAt' => $link->expires_at,
        ]);
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $validated = $request->validate([
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
            'privacy_consent' => ['accepted'],
        ], [
            'nombre_completo.required' => 'Ingresa tu nombre completo.',
            'fecha_nacimiento.required' => 'Ingresa tu fecha de nacimiento.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede estar en el futuro.',
            'telefono.required' => 'Ingresa un teléfono de contacto.',
            'privacy_consent.accepted' => 'Debes aceptar el aviso de privacidad para enviar tus datos.',
        ]);

        $created = DB::transaction(function () use ($request, $token, $validated): bool {
            $link = PatientRegistrationLink::withoutGlobalScopes()
                ->where('token_hash', hash('sha256', $token))
                ->lockForUpdate()
                ->first();

            if (! $link || ! $link->isAvailable()) {
                return false;
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
            ->with('clinica')
            ->where('token_hash', hash('sha256', $token))
            ->first();
    }
}
