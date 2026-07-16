<?php

namespace App\Http\Controllers;

use App\Models\CapturePairingCode;
use App\Models\Estudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class CapturePairingCodeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => [
                'nullable',
                'integer',
                Rule::exists('pacientes', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'estudio_id' => [
                'nullable',
                'integer',
                Rule::exists('estudios', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
        ]);

        $user = $request->user();

        $tenantId = $user->clinica_id;
        $estudioId = $request->integer('estudio_id') ?: null;
        $pacienteId = $request->integer('paciente_id') ?: null;

        if ($estudioId && ! $pacienteId) {
            $pacienteId = Estudio::withoutGlobalScopes()
                ->whereKey($estudioId)
                ->value('paciente_id');
        }

        if ($pacienteId && ! $estudioId) {
            $estudioId = Estudio::withoutGlobalScopes()
                ->where('clinica_id', $tenantId)
                ->where('paciente_id', $pacienteId)
                ->where('estado', 'en_proceso')
                ->latest()
                ->value('id');
        }

        $plainCode = $this->generateReadableCode();

        $payload = [
            'tenant_id' => $tenantId,
            'user_id' => $user->id,
            'code_hash' => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(10),
        ];

        if (Schema::hasColumn('capture_pairing_codes', 'paciente_id')) {
            $payload['paciente_id'] = $pacienteId;
        }

        if (Schema::hasColumn('capture_pairing_codes', 'estudio_id')) {
            $payload['estudio_id'] = $estudioId;
        }

        if (Schema::hasColumn('capture_pairing_codes', 'study_id')) {
            $payload['study_id'] = $estudioId;
        }

        $pairing = CapturePairingCode::create($payload);

        return response()->json([
            'ok' => true,
            'message' => 'Código generado correctamente.',
            'data' => [
                'pairing_id' => $pairing->id,
                'code' => $plainCode,
                'expires_at' => $pairing->expires_at?->format('d/m/Y H:i:s'),
            ],
        ]);
    }

    private function generateReadableCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
