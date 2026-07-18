<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\PacienteDocumento;
use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

class TauriPatientController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
    ) {
    }

    /**
     * Listado de pacientes de la clínica autenticada.
     *
     * GET /api/tauri/pacientes
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Paciente::query()
            ->where('clinica_id', $user->clinica_id)
            ->withCount('estudios')
            ->with([
                'estudios' => function ($query) {
                    $query
                        ->latest()
                        ->limit(1);
                },
            ]);

        $this->applyFilters($query, $request);

        $perPage = max(
            1,
            min(
                (int) $request->integer('per_page', 100),
                100
            )
        );

        $pacientes = $query
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'ok' => true,
            'success' => true,

            'pacientes' => collect($pacientes->items())
                ->map(
                    fn (Paciente $paciente) =>
                        $this->patientData($paciente)
                )
                ->values(),

            'pagination' => [
                'current_page' => $pacientes->currentPage(),
                'last_page' => $pacientes->lastPage(),
                'per_page' => $pacientes->perPage(),
                'total' => $pacientes->total(),
                'from' => $pacientes->firstItem(),
                'to' => $pacientes->lastItem(),
            ],
        ]);
    }

    /**
     * Devuelve el folio sugerido para crear un paciente.
     *
     * GET /api/tauri/pacientes/create
     */
    public function create(Request $request): JsonResponse
    {
        $user = $request->user();

        $folio = $this->nextFolio(
            (int) $user->clinica_id
        );

        return response()->json([
            'ok' => true,
            'success' => true,

            /*
             * Se devuelve en varias claves para mantener
             * compatibilidad con diferentes versiones de Tauri.
             */
            'folio' => $folio,
            'next_folio' => $folio,
            'siguiente_folio' => $folio,

            'defaults' => [
                'folio' => $folio,
                'identificacion' => $folio,
                'nombre_completo' => '',
                'fecha_nacimiento' => null,
                'edad' => null,
                'peso' => null,
                'altura' => null,
                'sexo' => null,
                'direccion' => null,
                'telefono' => null,
                'email' => null,
                'medico' => null,
                'procedimiento' => null,
                'anestesiologo' => null,
                'referido_por' => null,
                'equipo_utilizado' => null,
                'diagnostico_preliminar' => null,
                'enfermedad' => null,
                'alergias' => null,
            ],
        ]);
    }

    /**
     * Registra un paciente.
     *
     * POST /api/tauri/pacientes
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        /*
         * El backend no depende del frontend para generar el folio.
         * Si Tauri no lo envía, se genera antes de validar.
         */
        $folio = trim(
            (string) $request->input('folio', '')
        );

        if ($folio === '') {
            $folio = $this->nextFolio(
                (int) $user->clinica_id
            );
        }

        /*
         * Folio e identificación siempre deben ser iguales.
         */
        $request->merge([
            'folio' => $folio,
            'identificacion' => $folio,
        ]);

        $validated = $request->validate(
            $this->rules(
                clinicId: (int) $user->clinica_id,
            )
        );

        try {
            $paciente = DB::transaction(
                function () use (
                    $request,
                    $validated,
                    $user
                ) {
                    $data = collect($validated)
                        ->except([
                            'foto',
                            'estudios_archivos',
                        ])
                        ->toArray();

                    $data['folio'] = trim(
                        (string) $validated['folio']
                    );

                    $data['identificacion'] =
                        $data['folio'];

                    $data['clinica_id'] =
                        $user->clinica_id;

                    if ($request->hasFile('foto')) {
                        $data['foto'] = media_store(
                            $request->file('foto'),
                            'clinicas/' .
                                $user->clinica_id .
                                '/pacientes'
                        );
                    }

                    $paciente = Paciente::create($data);

                    $this->storeDocuments(
                        $request,
                        $paciente
                    );

                    return $paciente;
                }
            );

            $this->activity->record(
                'patient_created',
                'patients',
                'Registró al paciente ' . $paciente->folio,
                $paciente,
                user: $user,
                request: $request,
            );

            $paciente->loadCount('estudios');

            $paciente->load([
                'documentos',
                'estudios' => function ($query) {
                    $query->latest();
                },
            ]);

            return response()->json([
                'ok' => true,
                'success' => true,
                'message' => 'Paciente registrado correctamente.',

                'paciente' => $this->patientData(
                    $paciente,
                    includeRelations: true,
                ),

                'paciente_id' => $paciente->id,
                'folio' => $paciente->folio,
                'next_view' => 'pacientes',
            ], 201);
        } catch (Throwable $exception) {
            Log::error(
                'Error creando paciente desde Tauri',
                [
                    'user_id' => $user->id,
                    'clinica_id' => $user->clinica_id,
                    'folio' => $folio,
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ]
            );

            return response()->json([
                'ok' => false,
                'success' => false,
                'message' => 'No se pudo registrar el paciente.',

                'error' => app()->hasDebugModeEnabled()
                    ? $exception->getMessage()
                    : null,
            ], 500);
        }
    }

    /**
     * Muestra el detalle de un paciente.
     *
     * GET /api/tauri/pacientes/{paciente}
     */
    public function show(
        Request $request,
        Paciente $paciente
    ): JsonResponse {
        $this->ensurePatientBelongsToClinic(
            $request,
            $paciente
        );

        $paciente->load([
            'documentos',

            'estudios' => function ($query) {
                $query->latest();
            },
        ]);

        $paciente->loadCount('estudios');

        return response()->json([
            'ok' => true,
            'success' => true,

            'paciente' => $this->patientData(
                $paciente,
                includeRelations: true,
            ),
        ]);
    }

    /**
     * Devuelve datos para editar un paciente.
     *
     * GET /api/tauri/pacientes/{paciente}/edit
     */
    public function edit(
        Request $request,
        Paciente $paciente
    ): JsonResponse {
        $this->ensurePatientBelongsToClinic(
            $request,
            $paciente
        );

        $paciente->load([
            'documentos',

            'estudios' => function ($query) {
                $query->latest();
            },
        ]);

        $paciente->loadCount('estudios');

        return response()->json([
            'ok' => true,
            'success' => true,

            'paciente' => $this->patientData(
                $paciente,
                includeRelations: true,
            ),
        ]);
    }

    /**
     * Actualiza un paciente.
     *
     * PUT/PATCH /api/tauri/pacientes/{paciente}
     */
    public function update(
        Request $request,
        Paciente $paciente
    ): JsonResponse {
        $this->ensurePatientBelongsToClinic(
            $request,
            $paciente
        );

        $user = $request->user();

        /*
         * Si Tauri no envía folio durante la edición,
         * se conserva el folio actual.
         */
        $folio = trim(
            (string) $request->input(
                'folio',
                $paciente->folio
            )
        );

        if ($folio === '') {
            $folio = $paciente->folio;
        }

        $request->merge([
            'folio' => $folio,
            'identificacion' => $folio,
        ]);

        $validated = $request->validate(
            $this->rules(
                clinicId: (int) $user->clinica_id,
                paciente: $paciente,
            )
        );

        try {
            DB::transaction(
                function () use (
                    $request,
                    $validated,
                    $paciente,
                    $user
                ) {
                    $data = collect($validated)
                        ->except([
                            'foto',
                            'estudios_archivos',
                        ])
                        ->toArray();

                    $data['folio'] = trim(
                        (string) $validated['folio']
                    );

                    $data['identificacion'] =
                        $data['folio'];

                    $data['clinica_id'] =
                        $user->clinica_id;

                    if ($request->hasFile('foto')) {
                        media_delete($paciente->foto);

                        $data['foto'] = media_store(
                            $request->file('foto'),
                            'clinicas/' .
                                $user->clinica_id .
                                '/pacientes'
                        );
                    }

                    $paciente->update($data);

                    $this->storeDocuments(
                        $request,
                        $paciente
                    );
                }
            );

            $this->activity->record(
                'patient_updated',
                'patients',
                'Actualizó al paciente ' . $paciente->folio,
                $paciente,
                user: $user,
                request: $request,
            );

            $paciente->refresh();

            $paciente->load([
                'documentos',

                'estudios' => function ($query) {
                    $query->latest();
                },
            ]);

            $paciente->loadCount('estudios');

            return response()->json([
                'ok' => true,
                'success' => true,
                'message' => 'Paciente actualizado correctamente.',

                'paciente' => $this->patientData(
                    $paciente,
                    includeRelations: true,
                ),
            ]);
        } catch (Throwable $exception) {
            Log::error(
                'Error actualizando paciente desde Tauri',
                [
                    'paciente_id' => $paciente->id,
                    'user_id' => $user->id,
                    'folio' => $folio,
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ]
            );

            return response()->json([
                'ok' => false,
                'success' => false,
                'message' => 'No se pudo actualizar el paciente.',

                'error' => app()->hasDebugModeEnabled()
                    ? $exception->getMessage()
                    : null,
            ], 500);
        }
    }

    /**
     * Actualización rápida de un campo.
     *
     * PATCH /api/tauri/pacientes/{paciente}/campo
     */
    public function updateField(
        Request $request,
        Paciente $paciente
    ): JsonResponse {
        $this->ensurePatientBelongsToClinic(
            $request,
            $paciente
        );

        $allowedFields = [
            'medico',
            'procedimiento',
            'anestesiologo',
            'referido_por',
            'equipo_utilizado',
        ];

        $validated = $request->validate([
            'campo' => [
                'required',
                'string',
                Rule::in($allowedFields),
            ],

            'valor' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $field = $validated['campo'];

        $paciente->{$field} =
            $validated['valor'] ?? null;

        $paciente->save();

        $this->activity->record(
            'patient_updated',
            'patients',
            'Actualizó ' .
                $field .
                ' del paciente ' .
                $paciente->folio,
            $paciente,
            user: $request->user(),
            request: $request,
        );

        return response()->json([
            'ok' => true,
            'success' => true,
            'message' => 'Campo actualizado correctamente.',
            'campo' => $field,
            'valor' => $paciente->{$field},
        ]);
    }

    /**
     * Elimina la fotografía del paciente.
     *
     * DELETE /api/tauri/pacientes/{paciente}/foto
     */
    public function destroyPhoto(
        Request $request,
        Paciente $paciente
    ): JsonResponse {
        $this->ensurePatientBelongsToClinic(
            $request,
            $paciente
        );

        media_delete($paciente->foto);

        $paciente->foto = null;
        $paciente->save();

        return response()->json([
            'ok' => true,
            'success' => true,
            'message' => 'Fotografía eliminada correctamente.',
        ]);
    }

    /**
     * Elimina un documento del paciente.
     *
     * DELETE /api/tauri/pacientes/{paciente}/documentos/{documento}
     */
    public function destroyDocument(
        Request $request,
        Paciente $paciente,
        PacienteDocumento $documento
    ): JsonResponse {
        $this->ensurePatientBelongsToClinic(
            $request,
            $paciente
        );

        abort_unless(
            (int) $documento->paciente_id ===
                (int) $paciente->id,
            404
        );

        media_delete($documento->path);

        $documento->delete();

        return response()->json([
            'ok' => true,
            'success' => true,
            'message' => 'Documento eliminado correctamente.',
        ]);
    }

    /**
     * Elimina un paciente y sus archivos relacionados.
     *
     * DELETE /api/tauri/pacientes/{paciente}
     */
    public function destroy(
        Request $request,
        Paciente $paciente
    ): JsonResponse {
        $this->ensurePatientBelongsToClinic(
            $request,
            $paciente
        );

        $user = $request->user();

        $folio = $paciente->folio;

        try {
            DB::transaction(
                function () use ($paciente) {
                    media_delete($paciente->foto);

                    $paciente->documentos()
                        ->get()
                        ->each(
                            function (
                                PacienteDocumento $documento
                            ) {
                                media_delete($documento->path);

                                $documento->delete();
                            }
                        );

                    $paciente->estudios()
                        ->get()
                        ->each(function ($estudio) {
                            $estudio->archivos()
                                ->get()
                                ->each(function ($archivo) {
                                    media_delete($archivo->path);

                                    $archivo->delete();
                                });

                            media_delete(
                                $estudio->reporte_path
                            );

                            media_delete(
                                $estudio->video_path
                            );

                            $estudio->delete();
                        });

                    $paciente->delete();
                }
            );

            $this->activity->record(
                'patient_deleted',
                'patients',
                'Eliminó al paciente ' . $folio,
                $paciente,
                user: $user,
                request: $request,
            );

            return response()->json([
                'ok' => true,
                'success' => true,
                'message' => 'Paciente eliminado correctamente.',
            ]);
        } catch (Throwable $exception) {
            Log::error(
                'Error eliminando paciente desde Tauri',
                [
                    'paciente_id' => $paciente->id,
                    'user_id' => $user->id,
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ]
            );

            return response()->json([
                'ok' => false,
                'success' => false,
                'message' => 'No se pudo eliminar el paciente.',

                'error' => app()->hasDebugModeEnabled()
                    ? $exception->getMessage()
                    : null,
            ], 500);
        }
    }

    /**
     * Reglas compartidas.
     */
    private function rules(
        int $clinicId,
        ?Paciente $paciente = null,
    ): array {
        $folioRule = Rule::unique(
            'pacientes',
            'folio'
        )->where(
            fn ($query) =>
                $query->where(
                    'clinica_id',
                    $clinicId
                )
        );

        if ($paciente) {
            $folioRule->ignore($paciente->id);
        }

        return [
            'folio' => [
                'required',
                'string',
                'max:255',
                $folioRule,
            ],

            'identificacion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nombre_completo' => [
                'required',
                'string',
                'max:255',
            ],

            'fecha_nacimiento' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'edad' => [
                'nullable',
                'integer',
                'min:0',
                'max:150',
            ],

            'peso' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999.99',
            ],

            'altura' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9.99',
            ],

            'sexo' => [
                'nullable',
                'string',
                'max:50',
            ],

            'direccion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telefono' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'medico' => [
                'nullable',
                'string',
                'max:255',
            ],

            'procedimiento' => [
                'nullable',
                'string',
                'max:255',
            ],

            'anestesiologo' => [
                'nullable',
                'string',
                'max:255',
            ],

            'referido_por' => [
                'nullable',
                'string',
                'max:255',
            ],

            'equipo_utilizado' => [
                'nullable',
                'string',
                'max:255',
            ],

            'diagnostico_preliminar' => [
                'nullable',
                'string',
            ],

            'enfermedad' => [
                'nullable',
                'string',
            ],

            'alergias' => [
                'nullable',
                'string',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'estudios_archivos' => [
                'nullable',
                'array',
            ],

            'estudios_archivos.*' => [
                'nullable',
                'file',
                'max:20480',
            ],
        ];
    }

    /**
     * Genera el próximo folio dentro de la clínica.
     */
    private function nextFolio(int $clinicId): string
    {
        $lastNumber = Paciente::query()
            ->where('clinica_id', $clinicId)
            ->where('folio', 'like', 'P-%')
            ->pluck('folio')
            ->map(function ($folio) {
                if (
                    ! preg_match(
                        '/^P-(\d+)$/',
                        (string) $folio,
                        $matches
                    )
                ) {
                    return 0;
                }

                return (int) $matches[1];
            })
            ->max() ?? 0;

        return 'P-' . str_pad(
            $lastNumber + 1,
            3,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * Guarda documentos adjuntos.
     */
    private function storeDocuments(
        Request $request,
        Paciente $paciente
    ): void {
        if (! $request->hasFile('estudios_archivos')) {
            return;
        }

        $files = $request->file(
            'estudios_archivos',
            []
        );

        if (! is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if (
                ! $file ||
                ! $file->isValid()
            ) {
                continue;
            }

            $path = media_store(
                $file,
                'paciente_docs/' . $paciente->id
            );

            PacienteDocumento::create([
                'paciente_id' => $paciente->id,
                'path' => $path,
                'nombre_original' =>
                    $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size_bytes' => $file->getSize(),
            ]);
        }
    }

    /**
     * Impide consultar pacientes de otra clínica.
     */
    private function ensurePatientBelongsToClinic(
        Request $request,
        Paciente $paciente
    ): void {
        abort_unless(
            (int) $paciente->clinica_id ===
                (int) $request->user()->clinica_id,
            404
        );
    }

    /**
     * Convierte el modelo al formato de Tauri.
     */
    private function patientData(
        Paciente $paciente,
        bool $includeRelations = false,
    ): array {
        $latestStudy =
            $paciente->relationLoaded('estudios')
                ? $paciente->estudios->first()
                : null;

        $fechaNacimiento =
            $paciente->fecha_nacimiento;

        $fechaNacimientoFormateada = null;

        if ($fechaNacimiento) {
            if (
                $fechaNacimiento instanceof
                \DateTimeInterface
            ) {
                $fechaNacimientoFormateada =
                    $fechaNacimiento->format('Y-m-d');
            } else {
                $fechaNacimientoFormateada =
                    substr(
                        (string) $fechaNacimiento,
                        0,
                        10
                    );
            }
        }

        $data = [
            'id' => $paciente->id,
            'folio' => $paciente->folio,
            'identificacion' => $paciente->identificacion,
            'nombre_completo' => $paciente->nombre_completo,
            'fecha_nacimiento' => $fechaNacimientoFormateada,
            'edad' => $paciente->edad,
            'peso' => $paciente->peso,
            'altura' => $paciente->altura,
            'sexo' => $paciente->sexo,
            'direccion' => $paciente->direccion,
            'telefono' => $paciente->telefono,
            'email' => $paciente->email,
            'medico' => $paciente->medico,
            'procedimiento' => $paciente->procedimiento,
            'anestesiologo' => $paciente->anestesiologo,
            'referido_por' => $paciente->referido_por,
            'equipo_utilizado' => $paciente->equipo_utilizado,
            'diagnostico_preliminar' =>
                $paciente->diagnostico_preliminar,
            'enfermedad' => $paciente->enfermedad,
            'alergias' => $paciente->alergias,

            'foto' => $paciente->foto,

            'foto_url' => $paciente->foto
                ? media_url($paciente->foto)
                : null,

            'estudios_count' => (int) (
                $paciente->estudios_count ?? 0
            ),

            'ultimo_estudio' => $latestStudy
                ? [
                    'id' => $latestStudy->id,

                    'fecha' =>
                        $latestStudy->created_at
                            ?->toIso8601String(),

                    'procedimiento' =>
                        $latestStudy->procedimiento
                        ?? $paciente->procedimiento,

                    'estado' =>
                        $latestStudy->estado ?? null,
                ]
                : null,

            'created_at' =>
                $paciente->created_at
                    ?->toIso8601String(),

            'updated_at' =>
                $paciente->updated_at
                    ?->toIso8601String(),
        ];

        if ($includeRelations) {
            $data['documentos'] =
                $paciente->relationLoaded('documentos')
                    ? $paciente->documentos
                        ->map(
                            fn (
                                PacienteDocumento $documento
                            ) => [
                                'id' => $documento->id,
                                'nombre' =>
                                    $documento->nombre_original,
                                'mime_type' =>
                                    $documento->mime_type,
                                'size_bytes' =>
                                    $documento->size_bytes,
                                'url' =>
                                    media_url($documento->path),
                            ]
                        )
                        ->values()
                    : [];

            $data['estudios'] =
                $paciente->relationLoaded('estudios')
                    ? $paciente->estudios
                        ->map(
                            fn ($estudio) => [
                                'id' => $estudio->id,

                                'fecha' =>
                                    $estudio->created_at
                                        ?->toIso8601String(),

                                'procedimiento' =>
                                    $estudio->procedimiento
                                    ?? $paciente->procedimiento,

                                'estado' =>
                                    $estudio->estado ?? null,

                                'reporte_url' =>
                                    $estudio->reporte_path
                                        ? media_url(
                                            $estudio->reporte_path
                                        )
                                        : null,

                                'video_url' =>
                                    $estudio->video_path
                                        ? media_url(
                                            $estudio->video_path
                                        )
                                        : null,
                            ]
                        )
                        ->values()
                    : [];
        }

        return $data;
    }

    /**
     * Filtros del listado.
     */
    private function applyFilters(
        Builder $query,
        Request $request
    ): void {
        $search = trim(
            (string) $request->input('search', '')
        );

        if ($search !== '') {
            $query->where(
                function (
                    Builder $builder
                ) use ($search) {
                    $builder
                        ->where(
                            'nombre_completo',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'folio',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'telefono',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'email',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'medico',
                            'like',
                            '%' . $search . '%'
                        );
                }
            );
        }

        if ($request->filled('folio')) {
            $query->where(
                'folio',
                'like',
                '%' .
                    $request->string('folio')->toString() .
                    '%'
            );
        }

        if ($request->filled('nombre')) {
            $query->where(
                'nombre_completo',
                'like',
                '%' .
                    $request->string('nombre')->toString() .
                    '%'
            );
        }

        if ($request->filled('medico')) {
            $query->where(
                'medico',
                $request->string('medico')->toString()
            );
        }

        if ($request->filled('fecha_nacimiento')) {
            $query->whereDate(
                'fecha_nacimiento',
                $request->input('fecha_nacimiento')
            );
        }

        $sort = $request
            ->string('sort')
            ->toString();

        match ($sort) {
            'nombre-asc' => $query
                ->reorder()
                ->orderBy('nombre_completo'),

            'nombre-desc' => $query
                ->reorder()
                ->orderByDesc('nombre_completo'),

            'folio-asc' => $query
                ->reorder()
                ->orderBy('folio'),

            'folio-desc' => $query
                ->reorder()
                ->orderByDesc('folio'),

            default => null,
        };
    }
}