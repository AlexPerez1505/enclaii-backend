<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaptureDevice;
use App\Models\CapturePairingCode;
use App\Models\CaptureSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TauriCaptureController extends Controller
{
    public function redeemCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
            'device_uid' => ['nullable', 'string', 'max:255'],
        ]);

        $code = preg_replace('/\D/', '', $request->code);

        if (strlen($code) !== 6) {
            return response()->json([
                'ok' => false,
                'message' => 'El código debe tener 6 dígitos.',
            ], 422);
        }

        $pairings = CapturePairingCode::query()
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->limit(50)
            ->get();

        $pairing = $pairings->first(function ($item) use ($code) {
            return Hash::check($code, $item->code_hash);
        });

        if (! $pairing) {
            return response()->json([
                'ok' => false,
                'message' => 'Código inválido o expirado.',
            ], 422);
        }

        $device = CaptureDevice::create([
            'tenant_id' => $pairing->tenant_id,
            'user_id' => $pairing->user_id,
            'name' => $request->device_name ?: 'Computadora de captura',
            'device_uid' => $request->device_uid ?: (string) Str::uuid(),
            'last_seen_at' => now(),
            'last_ip' => $request->ip(),
            'is_active' => true,
        ]);

        $sessionPayload = [
            'tenant_id' => $pairing->tenant_id,
            'user_id' => $pairing->user_id,
            'capture_device_id' => $device->id,
            'status' => 'active',
            'started_at' => now(),
        ];

        if (Schema::hasColumn('capture_sessions', 'paciente_id')) {
            $sessionPayload['paciente_id'] = $pairing->paciente_id ?? null;
        }

        if (Schema::hasColumn('capture_sessions', 'estudio_id')) {
            $sessionPayload['estudio_id'] = $pairing->estudio_id ?? null;
        }

        if (Schema::hasColumn('capture_sessions', 'study_id')) {
            $sessionPayload['study_id'] = $pairing->estudio_id ?? $pairing->study_id ?? null;
        }

        $session = CaptureSession::create($sessionPayload);

        $pairing->update([
            'used_at' => now(),
            'device_name' => $device->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $token = $device->createToken('tauri-capture-device', [
            'capture:live',
            'capture:image',
            'capture:video',
        ])->plainTextToken;

        return response()->json([
            'ok' => true,
            'message' => 'Dispositivo vinculado correctamente.',
            'data' => [
                'token' => $token,
                'tenant_id' => $pairing->tenant_id,
                'user_id' => $pairing->user_id,
                'paciente_id' => $pairing->paciente_id ?? null,
                'estudio_id' => $pairing->estudio_id ?? null,
                'study_id' => $pairing->study_id ?? null,
                'device_id' => $device->id,
                'session_id' => $session->id,
            ],
        ]);
    }

    public function liveFrame(Request $request)
    {
        $device = $request->user();

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'frame' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $session = CaptureSession::query()
            ->where('id', $request->session_id)
            ->where('capture_device_id', $device->id)
            ->where('status', 'active')
            ->firstOrFail();

        $this->ensureSameTenant($device, $session);

        $path = $request->file('frame')->storeAs(
            'endoscopy/live/' . $session->id,
            'latest.jpg',
            'public'
        );

        $session->update([
            'live_frame_path' => $path,
            'live_frame_at' => now(),
        ]);

        $device->update([
            'last_seen_at' => now(),
            'last_ip' => $request->ip(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Frame actualizado.',
            'data' => [
                'session_id' => $session->id,
                'url' => Storage::disk('public')->url($path),
                'live_frame_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    public function storeImage(Request $request)
    {
        $device = $request->user();

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'captured_at' => ['nullable', 'date'],
        ]);

        $session = CaptureSession::query()
            ->where('id', $request->session_id)
            ->where('capture_device_id', $device->id)
            ->where('status', 'active')
            ->firstOrFail();

        $this->ensureSameTenant($device, $session);

        $folder = $this->getSessionMediaFolder($session, 'images');

        $path = $request->file('image')->store($folder, 'public');

        /*
         |--------------------------------------------------------------------------
         | AQUÍ PUEDES GUARDAR EN TU TABLA REAL DE GALERÍA
         |--------------------------------------------------------------------------
         | Si ya tienes un modelo para imágenes del estudio, por ejemplo:
         |
         | \App\Models\GaleriaImagen::create([
         |     'tenant_id' => $session->tenant_id,
         |     'paciente_id' => $session->paciente_id,
         |     'estudio_id' => $session->estudio_id ?? $session->study_id,
         |     'path' => $path,
         |     'nombre_original' => $request->file('image')->getClientOriginalName(),
         |     'capturado_en' => $request->captured_at ?: now(),
         | ]);
         */

        $device->update([
            'last_seen_at' => now(),
            'last_ip' => $request->ip(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Imagen guardada correctamente.',
            'data' => [
                'session_id' => $session->id,
                'paciente_id' => $session->paciente_id ?? null,
                'estudio_id' => $session->estudio_id ?? null,
                'study_id' => $session->study_id ?? null,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
            ],
        ]);
    }

    public function storeVideo(Request $request)
    {
        $device = $request->user();

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'video' => ['required', 'file', 'mimes:webm,mp4,mov,avi,mkv', 'max:1048576'],
            'ended_at' => ['nullable', 'date'],
        ]);

        $session = CaptureSession::query()
            ->where('id', $request->session_id)
            ->where('capture_device_id', $device->id)
            ->where('status', 'active')
            ->firstOrFail();

        $this->ensureSameTenant($device, $session);

        $folder = $this->getSessionMediaFolder($session, 'videos');

        $path = $request->file('video')->store($folder, 'public');

        /*
         |--------------------------------------------------------------------------
         | AQUÍ PUEDES GUARDAR EN TU TABLA REAL DE VIDEOS
         |--------------------------------------------------------------------------
         | Si ya tienes un modelo para videos del estudio, por ejemplo:
         |
         | \App\Models\GaleriaVideo::create([
         |     'tenant_id' => $session->tenant_id,
         |     'paciente_id' => $session->paciente_id,
         |     'estudio_id' => $session->estudio_id ?? $session->study_id,
         |     'path' => $path,
         |     'nombre_original' => $request->file('video')->getClientOriginalName(),
         |     'capturado_en' => now(),
         | ]);
         */

        $device->update([
            'last_seen_at' => now(),
            'last_ip' => $request->ip(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Video guardado correctamente.',
            'data' => [
                'session_id' => $session->id,
                'paciente_id' => $session->paciente_id ?? null,
                'estudio_id' => $session->estudio_id ?? null,
                'study_id' => $session->study_id ?? null,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
            ],
        ]);
    }

    public function finishSession(Request $request)
    {
        $device = $request->user();

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
        ]);

        $session = CaptureSession::query()
            ->where('id', $request->session_id)
            ->where('capture_device_id', $device->id)
            ->where('status', 'active')
            ->firstOrFail();

        $this->ensureSameTenant($device, $session);

        $session->update([
            'status' => 'finished',
            'ended_at' => now(),
        ]);

        $device->update([
            'last_seen_at' => now(),
            'last_ip' => $request->ip(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Sesión finalizada correctamente.',
        ]);
    }

    private function ensureSameTenant(CaptureDevice $device, CaptureSession $session): void
    {
        /*
         |--------------------------------------------------------------------------
         | VALIDACIÓN MULTITENANT
         |--------------------------------------------------------------------------
         | Si tenant_id es null en ambos, lo permitimos para desarrollo local.
         | Si en producción siempre tienes tenant_id, esto protege que un dispositivo
         | de otro tenant no pueda escribir en una sesión ajena.
         */

        $deviceTenant = $device->tenant_id;
        $sessionTenant = $session->tenant_id;

        if (is_null($deviceTenant) && is_null($sessionTenant)) {
            return;
        }

        if ((string) $deviceTenant !== (string) $sessionTenant) {
            abort(403, 'El dispositivo no pertenece a este tenant.');
        }
    }

    private function getSessionMediaFolder(CaptureSession $session, string $type): string
    {
        $studyId = $session->estudio_id ?? $session->study_id ?? null;

        if ($studyId) {
            return 'endoscopy/studies/' . $studyId . '/' . $type;
        }

        if ($session->paciente_id) {
            return 'endoscopy/patients/' . $session->paciente_id . '/sessions/' . $session->id . '/' . $type;
        }

        return 'endoscopy/sessions/' . $session->id . '/' . $type;
    }
}