<?php

namespace App\Http\Controllers;

use App\Models\CapturePairingCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CapturePairingCodeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'study_id' => ['required', 'integer'],
        ]);

        $user = $request->user();

        /*
         * Ajusta esta parte a tu multitenant real.
         * Si usas tenant_id en users:
         * $tenantId = $user->tenant_id;
         *
         * Si usas stancl/tenancy:
         * $tenantId = tenant('id');
         */
        $tenantId = $user->tenant_id ?? null;

        $plainCode = $this->generateReadableCode();

        $pairing = CapturePairingCode::create([
            'tenant_id' => $tenantId,
            'user_id' => $user->id,
            'study_id' => $request->study_id,
            'code_hash' => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Código generado correctamente.',
            'data' => [
                'pairing_id' => $pairing->id,
                'code' => $plainCode,
                'expires_at' => $pairing->expires_at?->toDateTimeString(),
            ],
        ]);
    }

    private function generateReadableCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}