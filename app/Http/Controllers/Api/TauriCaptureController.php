<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaptureDevice;
use App\Models\CapturePairingCode;
use App\Models\CaptureSession;
use App\Models\CaptureVideoUpload;
use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Models\Paciente;
use App\Services\MediaPathService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
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
        $actor = $request->user();

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'frame' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $session = $this->activeSessionFor($actor, (int) $request->session_id);

        $this->ensureSameTenant($actor, $session);

        $path = media_store_as(
            $request->file('frame'),
            $this->getSessionMediaFolder($session, 'thumbnails'),
            'latest.jpg'
        );

        $session->update([
            'live_frame_path' => $path,
            'live_frame_at' => now(),
        ]);

        $this->touchCaptureActor($actor, $request);

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
        $actor = $request->user();
        $hasBase64 = $request->filled('data_base64');

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'image' => [Rule::requiredIf(fn () => ! $hasBase64), 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'filename' => [Rule::requiredIf(fn () => $hasBase64), 'string', 'max:180'],
            'mime_type' => ['nullable', 'string', 'max:120'],
            'data_base64' => [Rule::requiredIf(fn () => ! $request->hasFile('image')), 'string'],
            'captured_at' => ['nullable', 'date'],
        ]);

        $session = $this->activeSessionFor($actor, (int) $request->session_id);

        $this->ensureSameTenant($actor, $session);

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

        $this->touchCaptureActor($actor, $request);

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
        $actor = $request->user();
        $hasBase64 = $request->filled('data_base64');

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'video' => [Rule::requiredIf(fn () => ! $hasBase64), 'file', 'mimes:webm,mp4,mov,avi,mkv', 'max:1048576'],
            'filename' => [Rule::requiredIf(fn () => $hasBase64), 'string', 'max:180'],
            'mime_type' => ['nullable', 'string', 'max:120'],
            'data_base64' => [Rule::requiredIf(fn () => ! $request->hasFile('video')), 'string'],
            'ended_at' => ['nullable', 'date'],
        ]);

        $session = $this->activeSessionFor($actor, (int) $request->session_id);

        $this->ensureSameTenant($actor, $session);

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

        $this->touchCaptureActor($actor, $request);

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

    /*
     |--------------------------------------------------------------------------
     | Subida de video por partes (chunks)
     |--------------------------------------------------------------------------
     | Alternativa a storeVideo() para archivos pesados: en vez de mandar el
     | video completo en un solo request (limitado por timeouts y memoria),
     | el cliente lo corta en partes y las sube una por una. Solo al final
     | (finalizeVideoUpload) se concatenan y se reutiliza la misma logica de
     | guardado (media_store + createStudyArchive) que storeVideo().
     */

    public function initVideoUpload(Request $request)
    {
        $actor = $request->user();

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'filename' => ['required', 'string', 'max:180'],
            'mime_type' => ['nullable', 'string', 'max:120'],
            'total_size' => ['required', 'integer', 'min:1', 'max:2147483648'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:100000'],
            'ended_at' => ['nullable', 'date'],
        ]);

        $session = $this->activeSessionFor($actor, (int) $request->session_id);

        $this->ensureSameTenant($actor, $session);

        $upload = CaptureVideoUpload::create([
            'upload_id' => (string) Str::uuid(),
            'session_id' => $session->id,
            'filename' => $request->input('filename'),
            'mime_type' => $request->input('mime_type') ?: 'video/webm',
            'total_size' => $request->input('total_size'),
            'total_chunks' => $request->input('total_chunks'),
            'received_chunks' => [],
            'ended_at' => $request->date('ended_at'),
            'status' => 'pending',
        ]);

        $this->touchCaptureActor($actor, $request);

        return response()->json([
            'ok' => true,
            'message' => 'Subida de video iniciada.',
            'data' => [
                'upload_id' => $upload->upload_id,
                'total_chunks' => $upload->total_chunks,
            ],
        ]);
    }

    public function uploadVideoChunk(Request $request, string $uploadId, int $chunkIndex)
    {
        $actor = $request->user();
        $upload = $this->videoUploadFor($actor, $uploadId);

        if ($chunkIndex < 0 || $chunkIndex >= $upload->total_chunks) {
            return response()->json([
                'ok' => false,
                'message' => 'Indice de parte invalido.',
            ], 422);
        }

        $binary = $request->getContent();

        if ($binary === '' || $binary === null) {
            return response()->json([
                'ok' => false,
                'message' => 'La parte llego vacia.',
            ], 422);
        }

        Storage::disk('local')->put(
            $this->videoChunkPath($uploadId, $chunkIndex),
            $binary
        );

        $received = $upload->received_chunks ?? [];

        if (! in_array($chunkIndex, $received, true)) {
            $received[] = $chunkIndex;
            sort($received);
            $upload->update(['received_chunks' => $received]);
        }

        $this->touchCaptureActor($actor, $request);

        return response()->json([
            'ok' => true,
            'data' => [
                'received_chunks' => $received,
                'total_chunks' => $upload->total_chunks,
            ],
        ]);
    }

    public function videoUploadStatus(Request $request, string $uploadId)
    {
        $actor = $request->user();
        $upload = $this->videoUploadFor($actor, $uploadId);

        return response()->json([
            'ok' => true,
            'data' => [
                'upload_id' => $upload->upload_id,
                'status' => $upload->status,
                'received_chunks' => $upload->received_chunks ?? [],
                'total_chunks' => $upload->total_chunks,
            ],
        ]);
    }

    public function finalizeVideoUpload(Request $request, string $uploadId)
    {
        $actor = $request->user();
        $upload = $this->videoUploadFor($actor, $uploadId);

        if ($upload->status === 'completed') {
            return response()->json([
                'ok' => true,
                'message' => 'Video guardado correctamente.',
                'data' => [
                    'session_id' => $upload->session_id,
                    'path' => $upload->path,
                    'url' => $upload->path ? media_url($upload->path) : null,
                ],
            ]);
        }

        $received = $upload->received_chunks ?? [];

        if (count($received) < $upload->total_chunks) {
            return response()->json([
                'ok' => false,
                'message' => 'Faltan partes por subir antes de poder finalizar el video.',
                'data' => [
                    'received_chunks' => $received,
                    'total_chunks' => $upload->total_chunks,
                ],
            ], 409);
        }

        $session = $this->activeSessionFor($actor, $upload->session_id);

        $this->ensureSameTenant($actor, $session);

        $extension = pathinfo($upload->filename, PATHINFO_EXTENSION) ?: 'webm';
        $safeName = Str::slug(pathinfo($upload->filename, PATHINFO_FILENAME)) ?: 'capture';
        $tmpPath = sys_get_temp_dir().'/'.$safeName.'-'.Str::random(8).'.'.$extension;

        $handle = fopen($tmpPath, 'wb');

        try {
            for ($index = 0; $index < $upload->total_chunks; $index++) {
                $chunkPath = $this->videoChunkPath($uploadId, $index);

                if (! Storage::disk('local')->exists($chunkPath)) {
                    fclose($handle);
                    @unlink($tmpPath);

                    return response()->json([
                        'ok' => false,
                        'message' => "Falta la parte {$index}, no se puede finalizar el video.",
                    ], 409);
                }

                fwrite($handle, Storage::disk('local')->get($chunkPath));
            }
        } finally {
            fclose($handle);
        }

        $file = new UploadedFile(
            $tmpPath,
            $upload->filename,
            $upload->mime_type ?: 'video/webm',
            null,
            true
        );

        $folder = $this->getSessionMediaFolder($session, 'videos');
        $path = media_store($file, $folder);

        $archivo = $this->createStudyArchive(
            session: $session,
            file: $file,
            path: $path,
            type: 'video',
            category: 'tauri-recording',
            capturedAt: $upload->ended_at ?? now(),
            description: 'Video enviado desde la aplicacion Tauri (subida por partes)',
        );

        $studyId = $session->estudio_id ?? $session->study_id ?? null;

        if ($studyId) {
            Estudio::withoutGlobalScopes()
                ->whereKey($studyId)
                ->whereNull('video_path')
                ->update(['video_path' => $path]);
        }

        $this->cleanupVideoChunks($uploadId, $upload->total_chunks);

        $upload->update(['status' => 'completed', 'path' => $path]);

        $this->touchCaptureActor($actor, $request);

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
        $actor = $request->user();

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
        ]);

        $session = $this->activeSessionFor($actor, (int) $request->session_id);

        $this->ensureSameTenant($actor, $session);

        $session->update([
            'status' => 'finished',
            'ended_at' => now(),
        ]);

        $this->touchCaptureActor($actor, $request);

        return response()->json([
            'ok' => true,
            'message' => 'Sesión finalizada correctamente.',
        ]);
    }

    public function startSession(Request $request)
    {
        $actor = $request->user();

        $request->validate([
            'patient_id' => ['nullable', 'integer', Rule::exists('pacientes', 'id')],
            'paciente_id' => ['nullable', 'integer', Rule::exists('pacientes', 'id')],
            'estudio_id' => ['nullable', 'integer', Rule::exists('estudios', 'id')],
            'study_id' => ['nullable', 'integer', Rule::exists('estudios', 'id')],
        ]);

        $userId = $actor->user_id ?? $actor->id;
        $tenantId = $actor->tenant_id ?? $actor->clinica_id ?? null;
        $captureDeviceId = $actor->id;

        $patientId = $request->input('patient_id') ?? $request->input('paciente_id') ?? null;
        $studyId = $request->input('study_id') ?? $request->input('estudio_id') ?? null;

        $payload = [
            'tenant_id' => $tenantId,
            'user_id' => $userId,
            'capture_device_id' => $captureDeviceId,
            'status' => 'active',
            'started_at' => now(),
        ];

        if (Schema::hasColumn('capture_sessions', 'paciente_id')) {
            $payload['paciente_id'] = $patientId;
        }

        if (Schema::hasColumn('capture_sessions', 'estudio_id')) {
            $payload['estudio_id'] = $studyId;
        }

        if (Schema::hasColumn('capture_sessions', 'study_id')) {
            $payload['study_id'] = $studyId;
        }

        $session = CaptureSession::create($payload);

        return response()->json([
            'ok' => true,
            'message' => 'Sesion de captura iniciada.',
            'data' => [
                'session_id' => $session->id,
                'device_id' => $captureDeviceId,
                'paciente_id' => $session->paciente_id,
                'estudio_id' => $session->estudio_id,
                'study_id' => $session->study_id,
            ],
        ]);
    }

    private function activeSessionFor($actor, int $sessionId): CaptureSession
    {
        return CaptureSession::query()
            ->where('id', $sessionId)
            ->where('status', 'active')
            ->where(function ($query) use ($actor) {
                if ($actor instanceof CaptureDevice) {
                    $query->where('capture_device_id', $actor->id);
                    return;
                }

                $query->where('user_id', $actor->id)
                    ->orWhere('capture_device_id', $actor->id);
            })
            ->firstOrFail();
    }

    private function ensureSameTenant($actor, CaptureSession $session): void
    {
        /*
         |--------------------------------------------------------------------------
         | VALIDACIÓN MULTITENANT
         |--------------------------------------------------------------------------
         | Si tenant_id es null en ambos, lo permitimos para desarrollo local.
         | Si en producción siempre tienes tenant_id, esto protege que un dispositivo
         | de otro tenant no pueda escribir en una sesión ajena.
         */

        $deviceTenant = $actor->tenant_id ?? $actor->clinica_id ?? null;
        $sessionTenant = $session->tenant_id;

        if (is_null($deviceTenant) && is_null($sessionTenant)) {
            return;
        }

        if ((string) $deviceTenant !== (string) $sessionTenant) {
            abort(403, 'El dispositivo no pertenece a este tenant.');
        }
    }

    private function touchCaptureActor($actor, Request $request): void
    {
        if (! $actor instanceof CaptureDevice) {
            return;
        }

        $actor->update([
            'last_seen_at' => now(),
            'last_ip' => $request->ip(),
        ]);
    }

    private function getSessionMediaFolder(CaptureSession $session, string $type): string
    {
        $study = $this->ensureStudyForSession($session);
        $studyId = $study?->id ?? $session->estudio_id ?? $session->study_id ?? 'session-'.$session->id;
        $patientId = $study?->paciente_id ?? $session->paciente_id ?? 'unassigned';
        $clinicId = $study?->clinica_id ?? $session->tenant_id;
        $mediaPaths = app(MediaPathService::class);

        return match ($type) {
            'images' => $mediaPaths->studyImages($studyId, $patientId, $clinicId),
            'videos' => $mediaPaths->studyVideos($studyId, $patientId, $clinicId),
            'thumbnails' => $mediaPaths->studyThumbnails($studyId, $patientId, $clinicId),
            'reports' => $mediaPaths->studyReports($studyId, $patientId, $clinicId),
            default => $mediaPaths->study($studyId, $patientId, $clinicId).'/'.$type,
        };
    }

    private function ensureStudyForSession(CaptureSession $session): ?Estudio
    {
        $studyId = $session->estudio_id ?? $session->study_id ?? null;

        if ($studyId) {
            return Estudio::withoutGlobalScopes()->find($studyId);
        }

        if (! $session->paciente_id) {
            return null;
        }

        $patient = Paciente::withoutGlobalScopes()->find($session->paciente_id);

        if (! $patient) {
            return null;
        }

        $study = Estudio::create([
            'clinica_id' => $patient->clinica_id,
            'paciente_id' => $patient->id,
            'paciente_nombre' => $patient->nombre_completo,
            'folio' => $this->generarFolioEstudio(),
            'tipo' => 'Endoscopia',
            'fecha' => now()->toDateString(),
            'hora_inicio' => now()->format('H:i:s'),
            'estado' => 'en_proceso',
        ]);

        $updates = [];
        if (Schema::hasColumn('capture_sessions', 'estudio_id')) {
            $updates['estudio_id'] = $study->id;
        }
        if (Schema::hasColumn('capture_sessions', 'study_id')) {
            $updates['study_id'] = $study->id;
        }

        if ($updates !== []) {
            $session->forceFill($updates)->save();
            $session->refresh();
        }

        return $study;
    }

    private function generarFolioEstudio(): string
    {
        $ultimoId = (int) Estudio::max('id') + 1;

        do {
            $folio = 'E-'.str_pad($ultimoId, 4, '0', STR_PAD_LEFT);
            $ultimoId++;
        } while (Estudio::where('folio', $folio)->exists());

        return $folio;
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

    /**
     * Carga un CaptureVideoUpload por su upload_id y valida, a traves de la
     * sesion de captura a la que pertenece, que el actor autenticado (mismo
     * dispositivo/usuario y mismo tenant) tiene permiso de seguir subiendo
     * partes o finalizarlo. Evita que un dispositivo pueda escribir/leer
     * una subida ajena solo por adivinar/reusar un upload_id.
     */
    private function videoUploadFor($actor, string $uploadId): CaptureVideoUpload
    {
        $upload = CaptureVideoUpload::where('upload_id', $uploadId)->firstOrFail();

        $session = $this->activeSessionFor($actor, $upload->session_id);
        $this->ensureSameTenant($actor, $session);

        return $upload;
    }

    private function videoChunkPath(string $uploadId, int $chunkIndex): string
    {
        return 'tmp/video-uploads/'.$uploadId.'/chunk_'.str_pad((string) $chunkIndex, 6, '0', STR_PAD_LEFT);
    }

    private function cleanupVideoChunks(string $uploadId, int $totalChunks): void
    {
        for ($index = 0; $index < $totalChunks; $index++) {
            Storage::disk('local')->delete($this->videoChunkPath($uploadId, $index));
        }

        Storage::disk('local')->deleteDirectory('tmp/video-uploads/'.$uploadId);
    }
}
