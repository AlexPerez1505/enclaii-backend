<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaptureDevice;
use App\Models\CapturePairingCode;
use App\Models\CaptureSession;
use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

        $path = media_store_as(
            $request->file('frame'),
            'endoscopy/live/' . $session->id,
            'latest.jpg'
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
                'url' => media_url($path),
                'live_frame_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    public function storeImage(Request $request)
    {
        $device = $request->user();
        $hasBase64 = $request->filled('data_base64');

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'image' => [Rule::requiredIf(fn () => ! $hasBase64), 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'filename' => [Rule::requiredIf(fn () => $hasBase64), 'string', 'max:180'],
            'mime_type' => ['nullable', 'string', 'max:120'],
            'data_base64' => [Rule::requiredIf(fn () => ! $request->hasFile('image')), 'string'],
            'captured_at' => ['nullable', 'date'],
        ]);

        $session = CaptureSession::query()
            ->where('id', $request->session_id)
            ->where('capture_device_id', $device->id)
            ->where('status', 'active')
            ->firstOrFail();

        $this->ensureSameTenant($device, $session);

        $folder = $this->getSessionMediaFolder($session, 'images');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
        } else {
            $binary = base64_decode($request->input('data_base64'), true);
            if ($binary === false) {
                return response()->json([
                    'ok' => false,
                    'message' => 'La captura no llego en base64 valido.',
                ], 422);
            }

            $originalName = $request->input('filename', 'capture.jpg');
            $extension = pathinfo($originalName, PATHINFO_EXTENSION) ?: 'jpg';
            $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'capture';
            $tmpPath = sys_get_temp_dir() . '/' . $safeName . '-' . Str::random(8) . '.' . $extension;
            file_put_contents($tmpPath, $binary);

            $file = new UploadedFile(
                $tmpPath,
                $originalName,
                $request->input('mime_type') ?: 'image/jpeg',
                null,
                true
            );
        }
        $path = media_store($file, $folder);
        $archivo = $this->createStudyArchive(
            session: $session,
            file: $file,
            path: $path,
            type: 'imagen',
            category: 'tauri-capture',
            capturedAt: $request->date('captured_at') ?? now(),
            description: 'Captura enviada desde la aplicacion Tauri',
        );

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
                'url' => media_url($path),
                'archivo_id' => $archivo?->id,
            ],
        ]);
    }

    public function storeVideo(Request $request)
    {
        $device = $request->user();
        $hasBase64 = $request->filled('data_base64');

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'video' => [Rule::requiredIf(fn () => ! $hasBase64), 'file', 'mimes:webm,mp4,mov,avi,mkv', 'max:1048576'],
            'filename' => [Rule::requiredIf(fn () => $hasBase64), 'string', 'max:180'],
            'mime_type' => ['nullable', 'string', 'max:120'],
            'data_base64' => [Rule::requiredIf(fn () => ! $request->hasFile('video')), 'string'],
            'ended_at' => ['nullable', 'date'],
        ]);

        $session = CaptureSession::query()
            ->where('id', $request->session_id)
            ->where('capture_device_id', $device->id)
            ->where('status', 'active')
            ->firstOrFail();

        $this->ensureSameTenant($device, $session);

        $folder = $this->getSessionMediaFolder($session, 'videos');

        if ($request->hasFile('video')) {
            $file = $request->file('video');
        } else {
            $binary = base64_decode($request->input('data_base64'), true);
            if ($binary === false) {
                return response()->json([
                    'ok' => false,
                    'message' => 'La captura no llego en base64 valido.',
                ], 422);
            }

            $originalName = $request->input('filename', 'capture.webm');
            $extension = pathinfo($originalName, PATHINFO_EXTENSION) ?: 'webm';
            $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'capture';
            $tmpPath = sys_get_temp_dir() . '/' . $safeName . '-' . Str::random(8) . '.' . $extension;
            file_put_contents($tmpPath, $binary);

            $file = new UploadedFile(
                $tmpPath,
                $originalName,
                $request->input('mime_type') ?: 'video/webm',
                null,
                true
            );
        }
        $path = media_store($file, $folder);
        $archivo = $this->createStudyArchive(
            session: $session,
            file: $file,
            path: $path,
            type: 'video',
            category: 'tauri-recording',
            capturedAt: $request->date('ended_at') ?? now(),
            description: 'Video enviado desde la aplicacion Tauri',
        );

        $studyId = $session->estudio_id ?? $session->study_id ?? null;
        if ($studyId) {
            Estudio::withoutGlobalScopes()
                ->whereKey($studyId)
                ->whereNull('video_path')
                ->update(['video_path' => $path]);
        }

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
                'url' => media_url($path),
                'archivo_id' => $archivo?->id,
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

    private function createStudyArchive(
        CaptureSession $session,
        $file,
        string $path,
        string $type,
        string $category,
        mixed $capturedAt,
        string $description,
    ): ?EstudioArchivo {
        $studyId = $session->estudio_id ?? $session->study_id ?? null;
        $study = $studyId
            ? Estudio::withoutGlobalScopes()->find($studyId)
            : null;

        $patientId = $session->paciente_id ?? $study?->paciente_id;
        $patient = $patientId
            ? Paciente::withoutGlobalScopes()->find($patientId)
            : null;

        if (! $study && ! $patient) {
            return null;
        }

        $originalName = $file->getClientOriginalName() ?: basename($path);

        return EstudioArchivo::withoutGlobalScopes()->create([
            'clinica_id' => $study?->clinica_id ?? $patient?->clinica_id,
            'estudio_id' => $study?->id,
            'paciente_id' => $patient?->id ?? $study?->paciente_id,
            'tipo' => $type,
            'categoria' => $category,
            'nombre_original' => $originalName,
            'nombre' => pathinfo($originalName, PATHINFO_FILENAME),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'descripcion' => $description,
            'capturado_en' => $capturedAt,
        ]);
    }
}
