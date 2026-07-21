<?php

namespace App\Http\Controllers;

use App\Models\Bloqueo;
use App\Models\CaptureSession;
use App\Models\Cita;
use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Models\Paciente;
use App\Models\PatientPreregistration;
use App\Models\PatientRegistrationLink;
use App\Models\Reporte;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\MediaPathService;
use App\Services\PatientFolioGenerator;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TauriFrontendController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $next = Cita::with('paciente')
            ->whereNotIn('estado', ['cancelado', 'completado'])
            ->whereRaw("CONCAT(fecha, ' ', hora) >= ?", [now()->format('Y-m-d H:i:s')])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        $today = Cita::with('paciente')
            ->whereDate('fecha', now()->toDateString())
            ->whereNotIn('estado', ['cancelado', 'completado'])
            ->orderBy('hora')
            ->limit(8)
            ->get()
            ->map(fn (Cita $cita) => $this->appointmentPayload($cita))
            ->values();

        $upcoming = Cita::with('paciente')
            ->whereNotIn('estado', ['cancelado', 'completado'])
            ->whereDate('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(8)
            ->get()
            ->map(fn (Cita $cita) => $this->appointmentPayload($cita))
            ->values();

        return response()->json([
            'ok' => true,
            'dashboard' => [
                'next_patient' => $next ? $this->appointmentPayload($next) : null,
                'summary' => [
                    'total_citas' => Cita::count(),
                    'citas_proximas' => Cita::where('estado', 'proximo')->count(),
                    'citas_completadas' => Cita::where('estado', 'completado')->count(),
                    'citas_canceladas' => Cita::where('estado', 'cancelado')->count(),
                ],
                'reportes_pendientes' => Estudio::whereDoesntHave('reportes')->count(),
                'pendientes_hoy' => $today,
                'proximos_estudios' => $upcoming,
            ],
        ]);
    }

    public function patients(Request $request): JsonResponse
    {
        $patients = Paciente::query()
            ->with(['estudios' => fn ($query) => $query->latest('fecha')->latest('id')->limit(5)])
            ->when($request->string('search')->trim()->isNotEmpty(), function ($query) use ($request) {
                $search = '%'.$request->string('search')->trim().'%';

                $query->where(function ($query) use ($search) {
                    $query
                        ->where('nombre_completo', 'like', $search)
                        ->orWhere('folio', 'like', $search)
                        ->orWhere('telefono', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });
            })
            ->latest()
            ->get()
            ->map(fn (Paciente $patient) => $this->patientPayload($patient))
            ->values();

        return response()->json([
            'ok' => true,
            'patients' => $patients,
            'data' => $patients,
        ]);
    }

    public function activeStudy(Request $request): JsonResponse
    {
        $study = $this->resolveActiveStudy($request);

        if (! $study) {
            return response()->json([
                'ok' => false,
                'message' => 'No hay un estudio activo en Laravel para guardar capturas.',
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'study' => $this->studyPayload($study),
            'estudio' => $this->studyPayload($study),
        ]);
    }

    public function startStudy(Request $request): JsonResponse
    {
        $patientId = $this->normalizeCaptureId(
            $request->input('patientId')
            ?? $request->input('patient_id')
            ?? $request->input('paciente_id')
        );
        $patient = $patientId ? Paciente::query()->find($patientId) : null;

        if (! $patient) {
            return response()->json([
                'ok' => false,
                'message' => 'Paciente no encontrado en Laravel para iniciar el estudio.',
            ], 404);
        }

        $study = Estudio::create([
            'clinica_id' => $patient->clinica_id,
            'paciente_id' => $patient->id,
            'paciente_nombre' => $patient->nombre_completo,
            'folio' => $this->nextStudyFolio(),
            'tipo' => $request->string('tipo')->trim()->toString() ?: ($patient->procedimiento ?: 'Endoscopia'),
            'fecha' => today(),
            'estado' => 'en_proceso',
            'medico' => $request->string('medico')->trim()->toString() ?: ($patient->medico ?: null),
            'hora_inicio' => now()->format('H:i:s'),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Estudio iniciado en Laravel.',
            'study' => $this->studyPayload($study->fresh('paciente')),
            'estudio' => $this->studyPayload($study->fresh('paciente')),
        ], 201);
    }

    public function deletePatient(Paciente $paciente): JsonResponse
    {
        try {
            media_delete($paciente->foto);

            $paciente->estudios()->each(function (Estudio $estudio): void {
                $estudio->archivos()->each(function (EstudioArchivo $archivo): void {
                    media_delete($archivo->path);
                    $archivo->delete();
                });

                media_delete($estudio->reporte_path);
                media_delete($estudio->video_path);

                $estudio->delete();
            });

            $paciente->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Paciente eliminado desde Laravel.',
            ]);
        } catch (\Throwable $error) {
            return response()->json([
                'ok' => false,
                'message' => 'Laravel no pudo eliminar el paciente: '.$error->getMessage(),
            ], 500);
        }
    }

    public function deletePatientByPayload(Request $request): JsonResponse
    {
        $patient = Paciente::query()
            ->when($request->filled('folio'), fn ($query) => $query->orWhere('folio', $request->string('folio')))
            ->when($request->filled('email'), fn ($query) => $query->orWhere('email', $request->string('email')))
            ->when($request->filled('phone'), fn ($query) => $query->orWhere('telefono', $request->string('phone')))
            ->first();

        if (! $patient) {
            return response()->json([
                'ok' => false,
                'message' => 'Paciente no encontrado en Laravel.',
            ], 404);
        }

        return $this->deletePatient($patient);
    }

    public function agenda(Request $request): JsonResponse
    {
        $year = (int) $request->integer('year', (int) now()->format('Y'));
        $month = (int) $request->integer('month', (int) now()->format('n'));

        $appointments = Cita::with('paciente')
            ->whereYear('fecha', $year)
            ->whereMonth('fecha', $month)
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get()
            ->map(fn (Cita $cita) => $this->appointmentPayload($cita))
            ->values();

        $blocks = Bloqueo::query()
            ->whereYear('fecha', $year)
            ->whereMonth('fecha', $month)
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get()
            ->map(fn (Bloqueo $bloqueo) => $this->blockPayload($bloqueo))
            ->values();

        return response()->json([
            'ok' => true,
            'appointments' => $appointments,
            'citas' => $appointments,
            'blocks' => $blocks,
            'bloqueos' => $blocks,
        ]);
    }

    public function storeAppointment(Request $request): JsonResponse
    {
        $clinicaId = $request->user()?->clinica_id;
        $pacienteExistsRule = Rule::exists('pacientes', 'id');
        if ($clinicaId) {
            $pacienteExistsRule = $pacienteExistsRule->where('clinica_id', $clinicaId);
        }

        $validated = $request->validate([
            'paciente_id' => [
                'nullable',
                $pacienteExistsRule,
            ],
            'paciente_nombre' => ['nullable', 'string', 'max:255'],
            'procedimiento' => ['nullable', 'string', 'max:255'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'duracion_minutos' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'sala' => ['nullable', 'string', 'max:255'],
            'notas' => ['nullable', 'string'],
        ]);

        $patient = ! empty($validated['paciente_id']) ? Paciente::find($validated['paciente_id']) : null;

        $cita = Cita::create([
            'paciente_id' => $patient?->id,
            'paciente_nombre' => $patient?->nombre_completo ?? ($validated['paciente_nombre'] ?? 'Paciente sin nombre'),
            'procedimiento' => $validated['procedimiento'] ?? null,
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'duracion_minutos' => $validated['duracion_minutos'] ?? 60,
            'estado' => 'proximo',
            'sala' => $validated['sala'] ?? null,
            'notas' => $validated['notas'] ?? null,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Cita registrada desde Tauri.',
            'appointment' => $this->appointmentPayload($cita->fresh('paciente')),
            'cita' => $this->appointmentPayload($cita->fresh('paciente')),
        ], 201);
    }

    public function storeBloqueo(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'fechas' => ['required', 'array', 'min:1', 'max:400'],
            'fechas.*' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'hora_fin' => ['nullable', 'date_format:H:i'],
        ]);

        $label = $validated['label'] ?: 'Bloqueo de tiempo';
        $hora = $validated['hora'];
        $horaFin = $validated['hora_fin'] ?? null;
        $fechas = array_unique($validated['fechas']);

        $creados = collect($fechas)->map(function ($fecha) use ($label, $hora, $horaFin) {
            return $this->blockPayload(Bloqueo::create([
                'label' => $label,
                'fecha' => $fecha,
                'hora' => $hora,
                'hora_fin' => $horaFin,
            ]));
        })->values();

        return response()->json([
            'ok' => true,
            'message' => 'Bloqueo registrado desde Tauri.',
            'blocks' => $creados,
            'bloqueos' => $creados,
        ], 201);
    }

    public function destroyBloqueo(Bloqueo $bloqueo): JsonResponse
    {
        $bloqueo->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Bloqueo eliminado desde Tauri.',
        ]);
    }

    private function blockPayload(Bloqueo $bloqueo): array
    {
        [$hI, $mI] = array_map('intval', array_pad(explode(':', $bloqueo->hora), 2, 0));
        $duracion = 60;

        if ($bloqueo->hora_fin) {
            [$hF, $mF] = array_map('intval', array_pad(explode(':', $bloqueo->hora_fin), 2, 0));
            $diff = ($hF * 60 + $mF) - ($hI * 60 + $mI);
            if ($diff > 0) {
                $duracion = $diff;
            }
        }

        return [
            'id' => $bloqueo->id,
            'label' => $bloqueo->label,
            'fecha' => $bloqueo->fecha?->format('Y-m-d'),
            'fecha_key' => $bloqueo->fecha?->format('Y-n-j'),
            'hora' => $bloqueo->hora,
            'hora_fin' => $bloqueo->hora_fin,
            'h' => $hI,
            'duracion' => $duracion,
        ];
    }

    public function reports(): JsonResponse
    {
        $reports = Reporte::with(['estudio.paciente'])
            ->latest()
            ->limit(50)
            ->get()
            ->map(fn (Reporte $report) => $this->reportPayload($report))
            ->values();

        $hallazgos = app(\App\Http\Controllers\IaReporteController::class)->hallazgosData()['hallazgos'];
        $findings = collect($hallazgos)->map(fn ($h) => [
            'name' => $h['nombre'],
            'percentage' => $h['porcentaje'],
            'critical' => $h['es_critico'],
        ])->values();

        return response()->json([
            'ok' => true,
            'reports' => $reports,
            'reportes' => $reports,
            'kpis' => [
                'reportes' => ['valor' => Reporte::count()],
                'sin_reporte' => ['valor' => Estudio::whereDoesntHave('reportes')->count()],
                'evidencias' => ['valor' => EstudioArchivo::where('tipo', 'imagen')->count()],
                'estudios' => ['valor' => Estudio::count()],
            ],
            'findings' => $findings,
        ]);
    }

    public function gallery(): JsonResponse
    {
        $patientsDb = Paciente::query()
            ->orderBy('nombre_completo')
            ->get()
            ->values();
        $patientIds = $patientsDb->pluck('id');
        $studiesByPatient = Estudio::query()
            ->with(['archivos:id,estudio_id,tipo'])
            ->whereIn('paciente_id', $patientIds)
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get()
            ->groupBy('paciente_id');
        $filesByPatient = EstudioArchivo::query()
            ->with('estudio')
            ->whereIn('paciente_id', $patientIds)
            ->orderByDesc('capturado_en')
            ->orderByDesc('id')
            ->get()
            ->groupBy('paciente_id');
        $patients = $patientsDb
            ->map(fn (Paciente $patient, int $index) => $this->galleryPatientPayload(
                $patient,
                $index,
                $studiesByPatient->get($patient->id, collect()),
                $filesByPatient->get($patient->id, collect()),
            ))
            ->values();

        return response()->json([
            'ok' => true,
            'patients' => $patients,
            'pacientes' => $patients,
            'doctors' => $patients->pluck('doctor')->filter()->unique()->values(),
            'procedures' => $patients->pluck('procedure')->filter()->unique()->values(),
        ]);
    }

    public function dashboardLayout(Request $request): JsonResponse
    {
        $layout = $request->user()?->resolvedSettings()['dashboard_layout'] ?? [];

        return response()->json([
            'ok' => true,
            'layout' => $layout,
        ]);
    }

    public function updateDashboardLayout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'layout' => ['required', 'array'],
            'layout.*.widget_id' => ['required', 'string'],
            'layout.*.w' => ['required', 'integer', 'min:1', 'max:13'],
            'layout.*.h' => ['required', 'integer', 'min:1'],
        ]);

        $user = $request->user();
        $settings = $user?->settings ?? [];
        $settings['dashboard_layout'] = $validated['layout'];
        $user->settings = $settings;
        $user->save();

        return response()->json([
            'ok' => true,
            'layout' => $validated['layout'],
        ]);
    }

    public function settings(Request $request): JsonResponse
    {
        $settings = array_merge($this->defaultSettings(), $request->isMethod('post') ? $request->all() : []);

        return response()->json([
            'ok' => true,
            'settings' => $settings,
            'security' => $settings,
            'user' => $this->userPayload($request),
            'plan' => $this->planPayload(),
        ]);
    }

    public function planPortal(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => 'Gestion de plan abierta desde Laravel.',
            'url' => url('/configuracion'),
            'plan' => $this->planPayload(),
        ]);
    }

    public function planStorage(): JsonResponse
    {
        $plan = $this->planPayload();

        return response()->json([
            'ok' => true,
            'plan' => $plan,
            'storage' => $plan['storage'],
        ]);
    }

    public function planInvoices(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => 'Facturas consultadas desde Laravel.',
            'invoices' => [
                ['folio' => 'ENC-'.now()->format('Ym').'-001', 'date' => now()->toDateString(), 'total' => 10000],
            ],
        ]);
    }

    public function planPaymentMethod(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => 'Metodo de pago solicitado desde Laravel.',
            'url' => url('/configuracion'),
            'plan' => $this->planPayload(),
        ]);
    }

    public function planRecommendations(): JsonResponse
    {
        $plan = $this->planPayload();

        return response()->json([
            'ok' => true,
            'plan' => $plan,
            'recommendations' => [
                'Revisa videos pesados y archiva estudios cerrados para liberar espacio.',
            ],
            'recommendation_text' => $plan['recommendation_text'],
        ]);
    }

    public function changePlan(Request $request): JsonResponse
    {
        $plan = $this->planPayload($request->string('plan_id')->toString() ?: 'hospital');

        return response()->json([
            'ok' => true,
            'message' => 'Solicitud de cambio de plan recibida por Laravel.',
            'plan' => $plan,
            'available_plans' => $plan['available_plans'],
        ]);
    }

    public function qr(Request $request): JsonResponse
    {
        return response()->json($this->qrPayload($request->integer('qr') ?: null));
    }

    public function createQr(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'ok' => false,
                'message' => 'No hay usuario autenticado en Laravel para crear QR.',
            ], 422);
        }

        $qrSettings = $user->resolvedSettings();
        $validated = $request->validate([
            'expires_in_hours' => ['nullable', 'integer', 'in:24,48,168'],
            'patient_message' => ['nullable', 'string', 'max:150'],
        ]);
        $expiresInHours = (int) ($validated['expires_in_hours'] ?? $qrSettings['qr_default_expiration_hours'] ?? 48);
        $patientMessage = array_key_exists('patient_message', $validated)
            ? $validated['patient_message']
            : ($qrSettings['qr_default_patient_message'] ?? null);

        $token = Str::random(64);
        $link = PatientRegistrationLink::create([
            'clinica_id' => $user->clinica_id,
            'created_by' => $user->id,
            'token' => $token,
            'token_hash' => hash('sha256', $token),
            'status' => 'active',
            'patient_message' => $patientMessage ?: null,
            'expires_at' => now()->addHours($expiresInHours),
        ]);

        app(ActivityLogger::class)->record(
            'patient_qr_created',
            'patients',
            'Generó un QR de pre-registro de paciente',
            $link,
            ['expires_at' => $link->expires_at->toIso8601String()],
            request: $request,
        );

        return response()->json($this->qrPayload($link->id, [
            'message' => 'Código QR generado correctamente.',
        ]), 201);
    }

    public function revokeQrLink(Request $request, PatientRegistrationLink $link): JsonResponse
    {
        if ($link->status !== 'active' || $link->preregistration()->exists()) {
            return response()->json([
                'ok' => false,
                'message' => 'Este QR ya no puede cancelarse.',
            ], 422);
        }

        $link->update([
            'status' => 'revoked',
            'revoked_at' => now(),
        ]);

        app(ActivityLogger::class)->record(
            'patient_qr_revoked',
            'patients',
            'Canceló un QR de pre-registro',
            $link,
            request: $request,
        );

        return response()->json($this->qrPayload(null, ['message' => 'Código QR cancelado.']));
    }

    public function archiveQrLink(Request $request, PatientRegistrationLink $link): JsonResponse
    {
        if ($link->archived_at) {
            return response()->json([
                'ok' => false,
                'message' => 'Este QR ya fue eliminado del historial.',
            ], 404);
        }

        $updates = ['archived_at' => now()];
        if ($link->status === 'active') {
            $updates['status'] = 'revoked';
            $updates['revoked_at'] = now();
        }
        $link->update($updates);

        app(ActivityLogger::class)->record(
            'patient_qr_archived',
            'patients',
            'Eliminó un QR del historial visible',
            $link,
            request: $request,
        );

        return response()->json($this->qrPayload(null, ['message' => 'Código QR eliminado de la lista.']));
    }

    public function reviewPreregistration(Request $request, PatientPreregistration $preregistration, string $action): JsonResponse
    {
        abort_unless(in_array($action, ['aceptar', 'rechazar'], true), 404);

        return $action === 'aceptar'
            ? $this->acceptPreregistration($request, $preregistration)
            : $this->rejectPreregistration($request, $preregistration);
    }

    private function acceptPreregistration(Request $request, PatientPreregistration $preregistration): JsonResponse
    {
        $user = $request->user();

        try {
            DB::transaction(function () use ($request, $preregistration, $user): void {
                $record = PatientPreregistration::query()
                    ->whereKey($preregistration->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                abort_unless($record->status === 'pending', 422, 'Este pre-registro ya fue revisado.');

                $qrSettings = $user->resolvedSettings();
                if (
                    ($qrSettings['qr_duplicate_check'] ?? true)
                    && ($qrSettings['qr_duplicate_action'] ?? 'warn') === 'block_acceptance'
                    && $this->hasPossibleDuplicate($record)
                ) {
                    abort(422, 'Existe un paciente con el mismo teléfono o correo. Revisa el expediente existente antes de aceptar este pre-registro.');
                }

                $patient = Paciente::create([
                    'clinica_id' => $user->clinica_id,
                    'folio' => app(PatientFolioGenerator::class)->next($user->clinica_id),
                    'nombre_completo' => $record->nombre_completo,
                    'identificacion' => $record->identificacion,
                    'fecha_nacimiento' => $record->fecha_nacimiento,
                    'edad' => $record->edad,
                    'peso' => $record->peso,
                    'altura' => $record->altura,
                    'sexo' => $record->sexo,
                    'direccion' => $record->direccion,
                    'telefono' => $record->telefono,
                    'email' => $record->email,
                    'medico' => $user->name,
                    'procedimiento' => $record->procedimiento,
                    'diagnostico_preliminar' => $record->motivo_consulta,
                    'alergias' => $record->alergias,
                    'enfermedades' => $record->enfermedades,
                    'medicamentos_actuales' => $record->medicamentos_actuales,
                    'antecedentes_medicos' => $record->antecedentes_medicos,
                    'foto' => null,
                ]);

                $photoPath = $this->movePatientPhotoToProfile($record->foto, $patient);
                if ($photoPath) {
                    $patient->update(['foto' => $photoPath]);
                }

                $record->update([
                    'status' => 'accepted',
                    'patient_id' => $patient->id,
                    'reviewed_by' => $user->id,
                    'reviewed_at' => now(),
                    'foto' => $photoPath,
                ]);

                app(ActivityLogger::class)->record(
                    'patient_preregistration_accepted',
                    'patients',
                    'Aceptó el pre-registro de '.$patient->nombre_completo,
                    $patient,
                    ['preregistration_id' => $record->id],
                    request: $request,
                );
            });
        } catch (HttpException $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], $e->getStatusCode());
        }

        return response()->json($this->qrPayload(null, ['message' => 'Pre-registro aceptado y expediente creado.']));
    }

    private function rejectPreregistration(Request $request, PatientPreregistration $preregistration): JsonResponse
    {
        $user = $request->user();

        try {
            $photoToDelete = DB::transaction(function () use ($preregistration, $user): ?string {
                $record = PatientPreregistration::query()
                    ->whereKey($preregistration->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                abort_unless($record->status === 'pending', 422, 'Este pre-registro ya fue revisado.');

                $photo = $record->foto;
                $record->update([
                    'status' => 'rejected',
                    'reviewed_by' => $user->id,
                    'reviewed_at' => now(),
                    'foto' => null,
                ]);

                return $photo;
            });
        } catch (HttpException $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], $e->getStatusCode());
        }

        if ($photoToDelete) {
            media_delete($photoToDelete);
            Storage::disk('public')->delete($photoToDelete);
        }

        $preregistration->refresh();
        app(ActivityLogger::class)->record(
            'patient_preregistration_rejected',
            'patients',
            'Rechazó el pre-registro de '.$preregistration->nombre_completo,
            $preregistration,
            request: $request,
        );

        return response()->json($this->qrPayload(null, ['message' => 'Pre-registro rechazado.']));
    }

    private function movePatientPhotoToProfile(?string $currentPath, Paciente $patient): ?string
    {
        if (! $currentPath) {
            return null;
        }

        $extension = pathinfo($currentPath, PATHINFO_EXTENSION) ?: 'jpg';
        $targetPath = app(MediaPathService::class)->patientProfile($patient).'/profile-'.Str::random(12).'.'.$extension;
        $disk = Storage::disk(media_disk());

        if (! $disk->exists($currentPath)) {
            return $currentPath;
        }

        $disk->copy($currentPath, $targetPath);
        $disk->delete($currentPath);

        return $targetPath;
    }

    private function hasPossibleDuplicate(PatientPreregistration $preregistration): bool
    {
        return Paciente::query()
            ->where(function ($filter) use ($preregistration): void {
                if ($preregistration->email) {
                    $filter->orWhereRaw('LOWER(email) = ?', [Str::lower($preregistration->email)]);
                }
                if ($preregistration->telefono) {
                    $filter->orWhere('telefono', $preregistration->telefono);
                }
            })
            ->exists();
    }

    public function storeCapture(Request $request): JsonResponse
    {
        $request->merge([
            'patientId' => $this->normalizeCaptureId($request->input('patientId')),
            'studyId' => $this->normalizeCaptureId($request->input('studyId')),
            'sessionId' => $this->normalizeCaptureId($request->input('sessionId')),
        ]);

        $validated = $request->validate([
            'capture_type' => ['required', 'string', 'max:20'],
            'filename' => ['required', 'string', 'max:180'],
            'mime_type' => ['nullable', 'string', 'max:120'],
            'data_base64' => ['required', 'string'],
            'patientId' => ['nullable', 'string', 'max:120'],
            'studyId' => ['nullable', 'string', 'max:120'],
            'sessionId' => ['nullable', 'string', 'max:120'],
            'captured_at' => ['nullable', 'date'],
        ]);

        $binary = base64_decode($validated['data_base64'], true);
        if ($binary === false) {
            return response()->json([
                'ok' => false,
                'message' => 'La captura no llego en base64 valido.',
            ], 422);
        }

        [$patient, $study] = $this->captureContext($validated);
        if (! $patient && ! $study) {
            return response()->json([
                'ok' => false,
                'message' => 'No hay paciente o estudio activo para guardar esta captura.',
            ], 422);
        }

        $type = Str::lower($validated['capture_type']) === 'video' ? 'video' : 'imagen';
        $extension = pathinfo($validated['filename'], PATHINFO_EXTENSION) ?: ($type === 'video' ? 'webm' : 'jpg');
        $safeName = Str::slug(pathinfo($validated['filename'], PATHINFO_FILENAME)) ?: 'capture';
        $clinicId = $study?->clinica_id ?? $patient?->clinica_id;
        $mediaPaths = app(MediaPathService::class);
        $folder = match (true) {
            $type === 'video' && (bool) $study => $mediaPaths->studyVideos($study),
            $type === 'video' => $mediaPaths->studyVideos('unassigned', $patient, $clinicId),
            (bool) $study => $mediaPaths->studyImages($study),
            default => $mediaPaths->studyImages('unassigned', $patient, $clinicId),
        };
        $path = $folder.'/'.$safeName.'-'.Str::random(8).'.'.$extension;

        Storage::disk(media_disk())->put($path, $binary);

        $archivo = EstudioArchivo::create([
            'clinica_id' => $clinicId,
            'estudio_id' => $study?->id,
            'paciente_id' => $patient?->id ?? $study?->paciente_id,
            'tipo' => $type,
            'categoria' => $type === 'video' ? 'video' : 'captura',
            'nombre_original' => $validated['filename'],
            'nombre' => pathinfo($validated['filename'], PATHINFO_FILENAME),
            'path' => $path,
            'mime_type' => $validated['mime_type'] ?? null,
            'size_bytes' => strlen($binary),
            'descripcion' => 'Captura enviada desde Tauri',
            'capturado_en' => $validated['captured_at'] ?? now(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Captura guardada por Laravel.',
            'capture' => [
                'id' => $archivo?->id,
                'path' => $path,
                'url' => media_url($path),
                'filename' => $validated['filename'],
                'capture_type' => $type,
                'patient_id' => $patient?->id ?? $study?->paciente_id,
                'study_id' => $study?->id,
                'patient_name' => $patient?->nombre_completo ?? $study?->paciente_nombre,
                'study_label' => $study ? trim(($study->tipo ?: 'Estudio').' '.($study->folio ?: '')) : null,
            ],
        ], 201);
    }

    private function patientPayload(Paciente $patient): array
    {
        $lastStudy = $patient->estudios->sortByDesc('fecha')->first();

        return [
            'id' => $patient->id,
            'patient_id' => $patient->id,
            'folio' => $patient->folio,
            'name' => $patient->nombre_completo ?: 'Paciente sin nombre',
            'initials' => $this->initials($patient->nombre_completo),
            'gender' => $patient->sexo ?: 'No especificado',
            'age' => $patient->edad ? $patient->edad.' anos' : '',
            'dob' => $patient->fecha_nacimiento?->format('Y-m-d'),
            'phone' => $patient->telefono,
            'email' => $patient->email,
            'address' => $patient->direccion,
            'medico' => $patient->medico ?: 'Sin medico',
            'status' => $lastStudy?->estado ?: 'en_proceso',
            'study_date' => $lastStudy?->fecha?->format('Y-m-d'),
            'study_type' => $lastStudy?->tipo ?: $patient->procedimiento,
            'foto_url' => $patient->foto ? media_url($patient->foto) : '',
            'historial' => $patient->estudios->map(fn (Estudio $study) => [
                'tipo' => $study->tipo ?: 'Estudio',
                'fecha' => $study->fecha?->format('Y-m-d'),
            ])->values(),
        ];
    }

    private function appointmentPayload(Cita $cita): array
    {
        $fechaIso = $cita->fecha?->format('Y-m-d');
        $fechaKey = $cita->fecha?->format('Y-n-j');
        $hora = $cita->hora
            ? \Illuminate\Support\Carbon::parse($cita->hora)->format('H:i')
            : '00:00';

        return [
            'id' => $cita->id,
            'date' => $fechaIso,
            'fecha' => $fechaIso,
            'fecha_key' => $fechaKey,
            'time' => $hora,
            'hora' => $hora,
            'hora_label' => $hora,
            'hora_h' => (int) substr($hora, 0, 2),
            'duracion_minutos' => (int) ($cita->duracion_minutos ?? 60),
            'cls' => 'ev-soon',
            'sala' => $cita->sala ?: 'Sala 3',
            'notas' => $cita->notas ?? '',
            'patient' => $cita->paciente?->nombre_completo ?: $cita->paciente_nombre ?: 'Paciente sin nombre',
            'paciente' => $cita->paciente?->nombre_completo ?: $cita->paciente_nombre ?: 'Paciente sin nombre',
            'type' => $cita->procedimiento ?: 'Procedimiento',
            'procedimiento' => $cita->procedimiento ?: 'Procedimiento',
            'status' => $cita->estado ?: 'proximo',
            'estado' => $cita->estado ?: 'proximo',
            'estado_texto' => $cita->estado_texto,
            'medico' => $cita->paciente?->medico ?: 'Sin medico',
        ];
    }

    private function reportPayload(Reporte $report): array
    {
        $study = $report->estudio;
        $patient = $study?->paciente;
        $name = $patient?->nombre_completo ?: $study?->paciente_nombre ?: 'Paciente sin nombre';

        return [
            'id' => $report->id,
            'reporte_id' => $report->id,
            'estudio_id' => $study?->id,
            'paciente' => $name,
            'initials' => $this->initials($name),
            'estudio' => $study?->tipo ?: 'Estudio',
            'fecha' => $report->created_at?->format('Y-m-d'),
            'hora' => $report->created_at?->format('H:i'),
            'critical' => (bool) $report->contiene_hallazgos_criticos,
            'estado_texto' => $report->contiene_hallazgos_criticos ? 'Critico' : 'Normal',
        ];
    }

    private function studyPayload(Estudio $study): array
    {
        $patient = $study->paciente;

        return [
            'id' => $study->id,
            'study_id' => $study->id,
            'estudio_id' => $study->id,
            'folio' => $study->folio,
            'type' => $study->tipo ?: 'Estudio',
            'tipo' => $study->tipo ?: 'Estudio',
            'status' => $study->estado,
            'estado' => $study->estado,
            'date' => $study->fecha?->format('Y-m-d'),
            'fecha' => $study->fecha?->format('Y-m-d'),
            'patient_id' => $patient?->id ?? $study->paciente_id,
            'paciente_id' => $patient?->id ?? $study->paciente_id,
            'patient_name' => $patient?->nombre_completo ?? $study->paciente_nombre,
            'paciente_nombre' => $patient?->nombre_completo ?? $study->paciente_nombre,
            'label' => trim(($study->tipo ?: 'Estudio').' '.($study->folio ?: '')),
        ];
    }

    private function galleryPatientPayload(Paciente $patient, int $index, $studies, $files): array
    {
        $lastStudy = $studies->first();
        $lastFile = $files->first();
        $lastDate = $lastFile?->capturado_en?->format('d/m/Y')
            ?? $lastStudy?->fecha?->format('d/m/Y')
            ?? '--';
        $studyDate = $lastStudy?->fecha?->format('Y-m-d') ?? '';
        $doctor = $lastStudy?->medico ?: $patient->medico ?: 'Sin medico';
        $procedure = $lastStudy?->tipo ?: $patient->procedimiento ?: 'Estudio';
        $media = $files
            ->map(fn (EstudioArchivo $file, int $mediaIndex) => $this->galleryMediaPayload($file, $mediaIndex))
            ->values();

        return [
            'id' => 'P-'.str_pad((string) $patient->id, 3, '0', STR_PAD_LEFT),
            'patient_id' => $patient->id,
            'name' => $patient->nombre_completo ?: 'Paciente sin nombre',
            'initials' => $this->initials($patient->nombre_completo),
            'age' => $patient->edad ? $patient->edad.' anos' : '--',
            'gender' => $patient->sexo ?: '--',
            'lastStudy' => $lastDate,
            'studyDate' => $studyDate,
            'studies' => $studies->count(),
            'photos' => $files->where('tipo', 'imagen')->count(),
            'videos' => $files->where('tipo', 'video')->count(),
            'status' => 'Activo',
            'studyStatus' => $this->galleryStudyStatus($lastStudy?->estado),
            'doctor' => $doctor,
            'procedure' => $procedure,
            'tone' => ['is-purple', 'is-blue', 'is-pink', 'is-mint'][$index % 4],
            'phone' => $patient->telefono ?: '',
            'detailStudies' => $studies->count(),
            'media' => $media,
        ];
    }

    private function galleryMediaPayload(EstudioArchivo $file, int $index): array
    {
        $study = $file->estudio;
        $capturedAt = $file->capturado_en ?? $file->created_at;
        $isVideo = $file->tipo === 'video';
        $studyLabel = $study
            ? trim(($study->tipo ?: 'Estudio').' '.($study->folio ?: '#'.$study->id))
            : 'Captura del paciente';

        return [
            'id' => (string) $file->id,
            'type' => $isVideo ? 'video' : 'image',
            'file' => $file->nombre_original ?: basename((string) $file->path),
            'study' => $studyLabel,
            'date' => $capturedAt?->format('d/m/Y') ?? '--',
            'studyDate' => $study?->fecha?->format('Y-m-d') ?? $capturedAt?->format('Y-m-d') ?? '',
            'time' => $capturedAt?->format('H:i:s') ?? '',
            'theme' => 'theme-'.(($index % 4) + 1),
            'src' => media_url($file->path),
            'patient_id' => $file->paciente_id,
            'study_id' => $file->estudio_id,
        ];
    }

    private function galleryStudyStatus(?string $status): string
    {
        return match (Str::lower((string) $status)) {
            'en_proceso', 'proceso', 'process' => 'process',
            'completado', 'completo', 'finalizado', 'done', 'archivado' => 'done',
            default => 'pending',
        };
    }

    private function captureContext(array $payload): array
    {
        $sessionId = $payload['sessionId'] ?? $payload['session_id'] ?? $payload['sesion_id'] ?? null;
        $session = $sessionId
            ? CaptureSession::query()->find($sessionId)
            : null;
        $studyId = $payload['studyId'] ?? $payload['study_id'] ?? $payload['estudio_id'] ?? $session?->estudio_id ?? $session?->study_id ?? null;
        $patientId = $payload['patientId'] ?? $payload['patient_id'] ?? $payload['paciente_id'] ?? $session?->paciente_id ?? null;
        $study = $studyId ? Estudio::query()->with('paciente')->find($studyId) : null;
        $patient = $patientId ? Paciente::query()->find($patientId) : null;

        if (! $study && ! $patient) {
            $study = $this->resolveActiveStudy();
        }

        if (! $patient && $study?->paciente_id) {
            $patient = $study->paciente ?: Paciente::query()->find($study->paciente_id);
        }

        return [$patient, $study];
    }

    private function normalizeCaptureId(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_scalar($value)) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : Str::limit($value, 120, '');
    }

    private function nextStudyFolio(): string
    {
        $nextId = (int) Estudio::max('id') + 1;

        do {
            $folio = 'E-'.str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
            $nextId++;
        } while (Estudio::where('folio', $folio)->exists());

        return $folio;
    }

    private function resolveActiveStudy(?Request $request = null): ?Estudio
    {
        $studyId = $request?->query('study_id')
            ?? $request?->query('estudio_id')
            ?? $request?->query('studyId')
            ?? $request?->input('study_id')
            ?? $request?->input('estudio_id')
            ?? $request?->input('studyId');

        if ($studyId) {
            $study = Estudio::query()->with('paciente')->find($studyId);
            if ($study) {
                return $study;
            }
        }

        $sessionId = $request?->query('session_id')
            ?? $request?->query('sessionId')
            ?? $request?->input('session_id')
            ?? $request?->input('sessionId');
        if ($sessionId) {
            $session = CaptureSession::query()->find($sessionId);
            $sessionStudyId = $session?->estudio_id ?? $session?->study_id;
            if ($sessionStudyId) {
                $study = Estudio::query()->with('paciente')->find($sessionStudyId);
                if ($study) {
                    return $study;
                }
            }
        }

        $patientId = $request?->query('patient_id')
            ?? $request?->query('paciente_id')
            ?? $request?->query('patientId')
            ?? $request?->input('patient_id')
            ?? $request?->input('paciente_id')
            ?? $request?->input('patientId');

        if ($patientId) {
            return Estudio::query()
                ->with('paciente')
                ->where('paciente_id', $patientId)
                ->where('estado', 'en_proceso')
                ->latest('updated_at')
                ->latest('id')
                ->first();
        }

        return null;
    }

    private function userPayload(Request $request): array
    {
        $user = $request->user();

        $accountName = $user?->nombre_completo ?: $user?->name ?: 'Doctor';
        $role = $user?->clinica_rol === 'propietario'
            ? 'Médico'
            : ($user?->clinica_rol ?: 'Médico');

        return [
            'name' => $user?->name ?: 'Doctor',
            'account_name' => $accountName,
            'role' => $role,
            'email' => $user?->email ?: '',
            'clinic' => $user?->clinica?->nombre ?: 'Clinica principal',
            'specialty' => $user?->especialidad ?: 'Endoscopia',
            'professional_license' => $user?->cedula_profesional ?: '',
        ];
    }

    private function defaultSettings(): array
    {
        $settings = auth()->user()?->resolvedSettings() ?? [];

        return array_merge([
            'reading_mode' => false,
            'timezone' => 'America/Mexico_City',
            'date_format' => 'dd/mm/yyyy',
            'time_format' => '24h',
            'autosave' => true,
            'confirm_delete' => true,
            'default_view' => 'dashboard',
            'items_per_page' => '15',
            'animations' => true,
            'compact' => false,
            'notif_email' => true,
            'notif_push' => false,
            'notif_new_studies' => true,
            'notif_reports' => true,
            'notif_reminders' => true,
            'qr_default_expiration_hours' => 48,
            'qr_default_patient_message' => 'Completa tu pre-registro antes de tu cita.',
            'qr_whatsapp_template' => 'Hola, te comparto tu enlace de pre-registro de ENCLAII: {enlace}',
            'qr_patient_photo_enabled' => true,
            'qr_patient_photo_required' => false,
            'qr_allow_camera_photo' => true,
            'qr_allow_gallery_photo' => true,
            'qr_consent_text' => 'Autorizo el envio de estos datos a {clinica}.',
            'qr_required_fields' => ['sexo', 'email', 'procedimiento'],
            'qr_duplicate_check' => true,
            'qr_duplicate_action' => 'review',
            'require_password_for_studies' => true,
            'require_password_for_patients' => true,
            'audit_sensitive_actions' => true,
        ], $settings);
    }

    private function planPayload(string $selected = 'clinica'): array
    {
        $usedBytes = (int) EstudioArchivo::sum('size_bytes');
        $usedGb = round($usedBytes / 1024 / 1024 / 1024, 2);
        $plans = $this->planOptions($selected);
        $current = collect($plans)->firstWhere('current', true) ?: $plans[0];
        $totalGb = (float) $current['storage_gb'];
        $percent = $totalGb > 0 ? min(100, round(($usedGb / $totalGb) * 100, 1)) : 0;

        return [
            'id' => $current['id'],
            'label' => $current['name'],
            'name' => $current['name'],
            'status' => 'active',
            'status_label' => 'Active',
            'renewal_date' => now()->addMonth()->format('Y-m-d'),
            'next_charge_date' => now()->addMonth()->format('Y-m-d'),
            'member_limit' => 5,
            'storage' => [
                'used_gb' => $usedGb,
                'total_gb' => $totalGb,
                'per_person_gb' => (float) ($current['storage_per_person_gb'] ?? $current['storage_gb']),
                'available_gb' => max($totalGb - $usedGb, 0),
                'percent' => $percent,
                'categories' => [
                    'images_gb' => round(EstudioArchivo::where('tipo', 'imagen')->sum('size_bytes') / 1024 / 1024 / 1024, 2),
                    'videos_gb' => round(EstudioArchivo::where('tipo', 'video')->sum('size_bytes') / 1024 / 1024 / 1024, 2),
                    'other_gb' => 0,
                ],
            ],
            'billing' => ['next_charge_date' => now()->addMonth()->format('Y-m-d')],
            'usage_history' => [
                ['label' => 'Nov 24', 'value' => max(5, $percent - 30)],
                ['label' => 'Dic 24', 'value' => max(10, $percent - 18)],
                ['label' => 'Ene 25', 'value' => max(15, $percent - 8)],
                ['label' => 'Feb 25', 'value' => $percent],
            ],
            'recommendation_text' => $percent >= 75
                ? 'Tu almacenamiento esta creciendo rapido. Revisa videos pesados y considera ampliar tu plan.'
                : 'Tu almacenamiento esta bajo control.',
            'available_plans' => $plans,
        ];
    }

    private function planOptions(string $selected): array
    {
        $selected = str_replace('-', '_', $selected);

        return array_map(fn (array $plan) => [
            ...$plan,
            'current' => $plan['id'] === $selected,
        ], [
            [
                'id' => 'clinica',
                'name' => 'Clinica',
                'storage_gb' => 5,
                'storage_per_person_gb' => 5,
                'accent' => 'cyan',
                'prices' => ['monthly' => 10000, 'quarterly' => 27000, 'annual' => 96000],
                'features' => ['Almacenamiento en la nube', 'IA Reportes basica', 'Soporte por email'],
            ],
            [
                'id' => 'hospital',
                'name' => 'Hospital',
                'storage_gb' => 10,
                'storage_per_person_gb' => 10,
                'accent' => 'purple',
                'prices' => ['monthly' => 25000, 'quarterly' => 67500, 'annual' => 240000],
                'features' => ['IA Reportes avanzada', 'Almacenamiento ampliado', 'Soporte prioritario', 'Exportacion de reportes'],
            ],
            [
                'id' => 'red_medica',
                'name' => 'Red medica',
                'storage_gb' => 15,
                'storage_per_person_gb' => 15,
                'accent' => 'red',
                'prices' => ['monthly' => 35000, 'quarterly' => 94500, 'annual' => 336000],
                'features' => ['Todo lo del plan Profesional', 'Mas almacenamiento', 'Integraciones avanzadas', 'Soporte 24/7'],
            ],
        ]);
    }

    private function qrPayload(?int $selected = null, array $extra = []): array
    {
        $links = PatientRegistrationLink::with('preregistration')
            ->whereNull('archived_at')
            ->latest()
            ->limit(30)
            ->get();
        $current = $selected ? $links->firstWhere('id', $selected) : $links->first();
        $linkPayloads = $links->map(fn (PatientRegistrationLink $link) => $this->qrLinkPayload($link))->values();
        $preregistrations = PatientPreregistration::latest()
            ->limit(30)
            ->get()
            ->map(fn (PatientPreregistration $item) => [
                'id' => $item->id,
                'name' => $item->nombre_completo,
                'status' => $item->status,
                'phone' => $item->telefono,
                'email' => $item->email,
                'created_at' => $item->created_at?->toDateTimeString(),
            ])
            ->values();

        return [
            'ok' => true,
            ...$extra,
            'kpis' => [
                'active' => $links->where('status', 'active')->count(),
                'pending' => $preregistrations->where('status', 'pending')->count(),
                'completed' => $preregistrations->where('status', 'accepted')->count(),
            ],
            'settings' => [
                'default_expiration_hours' => 48,
                'default_patient_message' => 'Completa tu pre-registro antes de tu cita.',
            ],
            'current_link' => $current ? $this->qrLinkPayload($current) : null,
            'links' => $linkPayloads,
            'history_counts' => [
                'active' => $links->where('status', 'active')->count(),
                'expired' => $links->filter(fn ($link) => $link->expires_at?->isPast())->count(),
                'used' => $links->filter(fn ($link) => filled($link->submitted_at))->count(),
            ],
            'default_history_status' => 'active',
            'preregistrations' => $preregistrations,
        ];
    }

    private function qrLinkPayload(PatientRegistrationLink $link): array
    {
        $url = route('qr.public.show', ['token' => $link->token]);

        return [
            'id' => $link->id,
            'code' => 'QR-'.$link->id,
            'public_url' => $url,
            'share_text' => 'Completa tu pre-registro: '.$url,
            'status' => $link->status,
            'expires_at' => $link->expires_at?->toDateTimeString(),
            'qr_svg' => $this->simpleQrSvg($link->id),
        ];
    }

    private function simpleQrSvg(int $seed): string
    {
        $accent = $seed % 2 === 0 ? '#0ea5e9' : '#7c3aed';

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120"><rect width="120" height="120" fill="#fff"/><rect x="10" y="10" width="28" height="28" fill="#061032"/><rect x="82" y="10" width="28" height="28" fill="#061032"/><rect x="10" y="82" width="28" height="28" fill="#061032"/><path d="M52 20h10v10H52zM68 20h6v18h-6zM50 50h12v12H50zM70 50h10v10H70zM88 52h12v12H88zM52 74h8v26h-8zM68 74h30v8H68zM84 90h18v12H84z" fill="'.$accent.'"/></svg>';
    }

    private function initials(?string $name): string
    {
        return collect(explode(' ', (string) $name))
            ->filter()
            ->take(2)
            ->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))
            ->implode('') ?: 'PX';
    }
}
