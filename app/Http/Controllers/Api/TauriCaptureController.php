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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TauriCaptureController extends Controller
{
    /**
     * Inicia (o reanuda) una sesion de captura directamente con el token del
     * usuario (sin necesidad del codigo de emparejamiento de 6 digitos).
     * Se usa cuando "Iniciar estudio" en Tauri ya conoce el paciente_id.
     */
    public function startSession(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'paciente_id' => [
                'required',
                'integer',
                Rule::exists('pacientes', 'id')->where('clinica_id', $user->clinica_id),
            ],
            'estudio_id' => [
                'nullable',
                'integer',
                Rule::exists('estudios', 'id')->where('clinica_id', $user->clinica_id),
            ],
        ]);

        $pacienteId = $request->integer('paciente_id');
        $estudioId = $request->integer('estudio_id') ?: null;

        if (! $estudioId) {
            $estudioId = Estudio::withoutGlobalScopes()
                ->where('clinica_id', $user->clinica_id)
                ->where('paciente_id', $pacienteId)
                ->where('estado', 'en_proceso')
                ->latest()
                ->value('id');
        }

        // `estudio_archivos.estudio_id` es obligatorio en la base de datos, asi
        // que si el paciente no tiene un estudio "en_proceso" creamos uno minimo
        // para poder guardar las fotos/videos capturados desde Tauri.
        if (! $estudioId) {
            $paciente = Paciente::withoutGlobalScopes()->find($pacienteId);

            $estudio = Estudio::create([
                'clinica_id' => $user->clinica_id,
                'paciente_id' => $pacienteId,
                'paciente_nombre' => $paciente?->nombre_completo,
                'folio' => $this->generarFolioEstudio(),
                'tipo' => 'Endoscopia',
                'fecha' => now()->toDateString(),
                'hora_inicio' => now()->format('H:i:s'),
                'estado' => 'en_proceso',
                'medico' => $user->name ?? null,
            ]);

            $estudioId = $estudio->id;
        }

        $hasEstudioIdColumn = Schema::hasColumn('capture_sessions', 'estudio_id');
        $hasStudyIdColumn = Schema::hasColumn('capture_sessions', 'study_id');

        $session = CaptureSession::query()
            ->where('user_id', $user->id)
            ->whereNull('capture_device_id')
            ->where('paciente_id', $pacienteId)
            ->where(function ($query) use ($estudioId, $hasEstudioIdColumn, $hasStudyIdColumn) {
                if ($hasEstudioIdColumn) {
                    $query->where('estudio_id', $estudioId);
                }

                if ($hasStudyIdColumn) {
                    $method = $hasEstudioIdColumn ? 'orWhere' : 'where';
                    $query->{$method}('study_id', $estudioId);
                }
            })
            ->where('status', 'active')
            ->latest()
            ->first();

        if (! $session) {
            $sessionPayload = [
                'tenant_id' => $user->clinica_id,
                'user_id' => $user->id,
                'status' => 'active',
                'started_at' => now(),
            ];

            if (Schema::hasColumn('capture_sessions', 'paciente_id')) {
                $sessionPayload['paciente_id'] = $pacienteId;
            }

            if (Schema::hasColumn('capture_sessions', 'estudio_id')) {
                $sessionPayload['estudio_id'] = $estudioId;
            }

            if (Schema::hasColumn('capture_sessions', 'study_id')) {
                $sessionPayload['study_id'] = $estudioId;
            }

            $session = CaptureSession::create($sessionPayload);
        }

        $paciente = Paciente::withoutGlobalScopes()->find($pacienteId);
        $estudio = $estudioId ? Estudio::withoutGlobalScopes()->find($estudioId) : null;

        return response()->json([
            'ok' => true,
            'message' => 'Sesion de captura lista.',
            'data' => [
                'session_id' => $session->id,
                'paciente_id' => $pacienteId,
                'paciente_nombre' => $paciente?->nombre_completo,
                'estudio_id' => $estudioId,
                'estudio_tipo' => $estudio?->tipo,
            ],
        ]);
    }

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

        $paciente = $pairing->paciente_id
            ? Paciente::withoutGlobalScopes()->find($pairing->paciente_id)
            : null;
        $estudio = ($pairing->estudio_id ?? $pairing->study_id ?? null)
            ? Estudio::withoutGlobalScopes()->find($pairing->estudio_id ?? $pairing->study_id)
            : null;

        return response()->json([
            'ok' => true,
            'message' => 'Dispositivo vinculado correctamente.',
            'data' => [
                'token' => $token,
                'tenant_id' => $pairing->tenant_id,
                'user_id' => $pairing->user_id,
                'paciente_id' => $pairing->paciente_id ?? null,
                'paciente_nombre' => $paciente?->nombre_completo,
                'estudio_id' => $pairing->estudio_id ?? null,
                'estudio_tipo' => $estudio?->tipo,
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

        $session = $this->resolveActiveSession($actor, (int) $request->session_id);

        $this->ensureSameTenant($actor, $session);

        $path = media_store_as(
            $request->file('frame'),
            'endoscopy/live/' . $session->id,
            'latest.jpg'
        );

        $session->update([
            'live_frame_path' => $path,
            'live_frame_at' => now(),
        ]);

        $this->touchActor($actor, $request);

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

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'data_base64' => ['nullable', 'string'],
            'filename' => ['nullable', 'string', 'max:255'],
            'mime_type' => ['nullable', 'string', 'max:100'],
            'captured_at' => ['nullable', 'date'],
        ]);

        $session = $this->resolveActiveSession($actor, (int) $request->session_id);

        $this->ensureSameTenant($actor, $session);

        $folder = $this->getSessionMediaFolder($session, 'images');

        $file = $request->file('image') ?: $this->uploadedFileFromBase64(
            $request->input('data_base64'),
            $request->input('filename', 'captura.jpg'),
            $request->input('mime_type', 'image/jpeg'),
        );

        if (! $file) {
            return response()->json([
                'ok' => false,
                'message' => 'No se recibio ninguna imagen.',
            ], 422);
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

        $this->touchActor($actor, $request);

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

        $request->validate([
            'session_id' => ['required', 'integer', 'exists:capture_sessions,id'],
            'video' => ['nullable', 'file', 'mimes:webm,mp4,mov,avi,mkv', 'max:1048576'],
            'data_base64' => ['nullable', 'string'],
            'filename' => ['nullable', 'string', 'max:255'],
            'mime_type' => ['nullable', 'string', 'max:100'],
            'ended_at' => ['nullable', 'date'],
        ]);

        $session = $this->resolveActiveSession($actor, (int) $request->session_id);

        $this->ensureSameTenant($actor, $session);

        $folder = $this->getSessionMediaFolder($session, 'videos');

        $file = $request->file('video') ?: $this->uploadedFileFromBase64(
            $request->input('data_base64'),
            $request->input('filename', 'video.webm'),
            $request->input('mime_type', 'video/webm'),
        );

        if (! $file) {
            return response()->json([
                'ok' => false,
                'message' => 'No se recibio ningun video.',
            ], 422);
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

        $this->touchActor($actor, $request);

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

        $session = $this->resolveActiveSession($actor, (int) $request->session_id);

        $this->ensureSameTenant($actor, $session);

        $session->update([
            'status' => 'finished',
            'ended_at' => now(),
        ]);

        $estudioId = $session->estudio_id ?? $session->study_id ?? null;

        if ($estudioId) {
            $estudio = Estudio::withoutGlobalScopes()->find($estudioId);

            if ($estudio && $estudio->estado !== 'completado') {
                $estudio->update([
                    'estado' => 'completado',
                    'hora_fin' => now()->format('H:i:s'),
                ]);
            }
        }

        $this->touchActor($actor, $request);

        return response()->json([
            'ok' => true,
            'message' => 'Sesión finalizada correctamente.',
        ]);
    }

    /**
     * Busca la sesion activa del actor autenticado (dispositivo emparejado
     * por codigo, o usuario logueado directamente sin dispositivo).
     */
    private function resolveActiveSession($actor, int $sessionId): CaptureSession
    {
        $query = CaptureSession::query()
            ->where('id', $sessionId)
            ->where('status', 'active');

        if ($actor instanceof CaptureDevice) {
            $query->where('capture_device_id', $actor->id);
        } else {
            $query->where('user_id', $actor->id)->whereNull('capture_device_id');
        }

        return $query->firstOrFail();
    }

    private function touchActor($actor, Request $request): void
    {
        if ($actor instanceof CaptureDevice) {
            $actor->update([
                'last_seen_at' => now(),
                'last_ip' => $request->ip(),
            ]);
        }
    }

    private function uploadedFileFromBase64(?string $base64, string $filename, string $mimeType): ?\Illuminate\Http\UploadedFile
    {
        if (! $base64) {
            return null;
        }

        $decoded = base64_decode($base64, true);
        if ($decoded === false || $decoded === '') {
            return null;
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'tauri_capture_');
        file_put_contents($tempPath, $decoded);

        return new \Illuminate\Http\UploadedFile(
            $tempPath,
            $filename,
            $mimeType,
            null,
            true,
        );
    }

    private function ensureSameTenant($actor, CaptureSession $session): void
    {
        /*
         |--------------------------------------------------------------------------
         | VALIDACIÓN MULTITENANT
         |--------------------------------------------------------------------------
         | Si tenant_id es null en ambos, lo permitimos para desarrollo local.
         | Si en producción siempre tienes tenant_id, esto protege que un actor
         | (dispositivo o usuario) de otro tenant no pueda escribir en una sesión ajena.
         */

        $actorTenant = $actor instanceof CaptureDevice ? $actor->tenant_id : $actor->clinica_id;
        $sessionTenant = $session->tenant_id;

        if (is_null($actorTenant) && is_null($sessionTenant)) {
            return;
        }

        if ((string) $actorTenant !== (string) $sessionTenant) {
            abort(403, 'No tienes acceso a esta sesion de captura.');
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

        // `estudio_archivos.estudio_id` es obligatorio en la base de datos. Si la
        // sesion de captura no tiene un estudio asociado (por ejemplo, un codigo
        // de vinculacion generado sin estudio especifico), creamos uno minimo
        // para no perder la captura.
        if (! $study && $patient) {
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

            if (Schema::hasColumn('capture_sessions', 'estudio_id')) {
                $session->forceFill(['estudio_id' => $study->id])->save();
            }
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

    private function generarFolioEstudio(): string
    {
        $ultimoId = (int) Estudio::withoutGlobalScopes()->max('id') + 1;

        do {
            $folio = 'E-'.str_pad((string) $ultimoId, 4, '0', STR_PAD_LEFT);
            $ultimoId++;
        } while (Estudio::withoutGlobalScopes()->where('folio', $folio)->exists());

        return $folio;
    }
}
