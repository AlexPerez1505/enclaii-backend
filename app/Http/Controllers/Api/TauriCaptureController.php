<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaptureDevice;
use App\Models\CapturePairingCode;
use App\Models\CaptureSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            ->limit(30)
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

        $session = CaptureSession::create([
            'tenant_id' => $pairing->tenant_id,
            'user_id' => $pairing->user_id,
            'study_id' => $pairing->study_id,
            'capture_device_id' => $device->id,
            'status' => 'active',
            'started_at' => now(),
        ]);

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
                'study_id' => $pairing->study_id,
                'device_id' => $device->id,
                'session_id' => $session->id,
                'expires_note' => 'Este token queda guardado en la app Tauri hasta cerrar sesión.',
            ],
        ]);
    }

    public function liveFrame(Request $request)
    {
        $device = $request->user();

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'study_id' => ['required', 'integer'],
            'frame' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $session = CaptureSession::query()
            ->where('id', $request->session_id)
            ->where('study_id', $request->study_id)
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
                'live_frame_at' => $session->fresh()->live_frame_at?->toDateTimeString(),
            ],
        ]);
    }

    public function storeImage(Request $request)
    {
        $device = $request->user();

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'study_id' => ['required', 'integer'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'captured_at' => ['nullable', 'date'],
        ]);

        $session = CaptureSession::query()
            ->where('id', $request->session_id)
            ->where('study_id', $request->study_id)
            ->where('capture_device_id', $device->id)
            ->where('status', 'active')
            ->firstOrFail();

        $this->ensureSameTenant($device, $session);

        $path = $request->file('image')->store(
            'endoscopy/studies/' . $session->study_id . '/images',
            'public'
        );

        /*
         * Aquí puedes guardar en tu tabla real study_images.
         * Si ya tienes StudyImage, descomenta y ajusta campos:
         *
         * $image = StudyImage::create([
         *     'tenant_id' => $session->tenant_id,
         *     'study_id' => $session->study_id,
         *     'file_path' => $path,
         *     'original_file_path' => $path,
         *     'captured_at' => $request->captured_at ?: now(),
         * ]);
         */

        $device->update([
            'last_seen_at' => now(),
            'last_ip' => $request->ip(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Imagen guardada correctamente.',
            'data' => [
                'study_id' => $session->study_id,
                'session_id' => $session->id,
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
            'study_id' => ['required', 'integer'],
            'video' => ['required', 'file', 'mimes:webm,mp4,mov,avi,mkv', 'max:1048576'],
            'ended_at' => ['nullable', 'date'],
        ]);

        $session = CaptureSession::query()
            ->where('id', $request->session_id)
            ->where('study_id', $request->study_id)
            ->where('capture_device_id', $device->id)
            ->where('status', 'active')
            ->firstOrFail();

        $this->ensureSameTenant($device, $session);

        $path = $request->file('video')->store(
            'endoscopy/studies/' . $session->study_id . '/videos',
            'public'
        );

        /*
         * Aquí puedes guardar en tu tabla real study_videos.
         */

        $device->update([
            'last_seen_at' => now(),
            'last_ip' => $request->ip(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Video guardado correctamente.',
            'data' => [
                'study_id' => $session->study_id,
                'session_id' => $session->id,
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

        return response()->json([
            'ok' => true,
            'message' => 'Sesión finalizada correctamente.',
        ]);
    }

    private function ensureSameTenant(CaptureDevice $device, CaptureSession $session): void
    {
        if ((string) $device->tenant_id !== (string) $session->tenant_id) {
            abort(403, 'El dispositivo no pertenece al tenant de esta sesión.');
        }
    }
}