<?php

namespace App\Http\Controllers;

use App\Models\CapturePairingCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CapturePairingCodeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => ['nullable', 'integer'],
            'estudio_id' => ['nullable', 'integer'],
        ]);

        $user = $request->user();

        $tenantId = $user->tenant_id ?? null;

        $plainCode = $this->generateReadableCode();

        $payload = [
            'tenant_id' => $tenantId,
            'user_id' => $user->id,
            'code_hash' => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(10),
        ];

        if (Schema::hasColumn('capture_pairing_codes', 'paciente_id')) {
            $payload['paciente_id'] = $request->paciente_id;
        }

        if (Schema::hasColumn('capture_pairing_codes', 'estudio_id')) {
            $payload['estudio_id'] = $request->estudio_id;
        }

        if (Schema::hasColumn('capture_pairing_codes', 'study_id')) {
            $payload['study_id'] = $request->estudio_id;
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