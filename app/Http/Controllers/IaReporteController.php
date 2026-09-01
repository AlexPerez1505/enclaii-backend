<?php

namespace App\Http\Controllers;

use App\Models\EstudioArchivo;
use App\Models\EstudioHallazgo;
use App\Models\Hallazgo;
use App\Models\Plantilla;
use App\Models\Reporte;
use App\Services\MediaPathService;
use App\Services\OpenAiReportService;
use App\Services\ReportPdfGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class IaReporteController extends Controller
{
    /**
     * Dashboard principal de IA reportes.
     */
    public function index(): View
    {
        $ahora = now();
        $inicioMes = $ahora->copy()->startOfMonth();
        $inicioMesAnterior = $ahora->copy()->subMonth()->startOfMonth();
        $finMesAnterior = $inicioMesAnterior->copy()->endOfMonth();

        $reportesMes = Reporte::whereBetween('created_at', [$inicioMes, $ahora])->count();
        $reportesMesAnterior = Reporte::whereBetween('created_at', [$inicioMesAnterior, $finMesAnterior])->count();

        $estudiosMes = \App\Models\Estudio::whereBetween('created_at', [$inicioMes, $ahora])->count();
        $estudiosMesAnterior = \App\Models\Estudio::whereBetween('created_at', [$inicioMesAnterior, $finMesAnterior])->count();

        $evidenciasMes = EstudioArchivo::whereBetween('created_at', [$inicioMes, $ahora])->count();
        $evidenciasMesAnterior = EstudioArchivo::whereBetween('created_at', [$inicioMesAnterior, $finMesAnterior])->count();

        $trend = function (int $actual, int $anterior): int {
            if ($anterior === 0) {
                return $actual > 0 ? 100 : 0;
            }

            return (int) round((($actual - $anterior) / $anterior) * 100);
        };

        $kpis = [
            'reportes' => [
                'valor' => $reportesMes,
                'trend' => $trend($reportesMes, $reportesMesAnterior),
            ],
            'sin_reporte' => [
                'valor' => \App\Models\Estudio::whereDoesntHave('reportes')->count(),
            ],
            'evidencias' => [
                'valor' => $evidenciasMes,
                'trend' => $trend($evidenciasMes, $evidenciasMesAnterior),
            ],
            'estudios' => [
                'valor' => $estudiosMes,
                'trend' => $trend($estudiosMes, $estudiosMesAnterior),
            ],
        ];

        $reportes = Reporte::with(['estudio.paciente'])
            ->latest()
            ->take(15)
            ->get();

        $hallazgos = $this->hallazgosData()['hallazgos'];

        $estudiosSinReporte = \App\Models\Estudio::with('paciente')
            ->whereDoesntHave('reportes')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($e) {
                $nombre = $e->paciente?->nombre_completo ?? $e->paciente_nombre ?? 'Sin paciente';
                $ini = collect(explode(' ', $nombre))->filter()->take(2)->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))->implode('') ?: 'NA';
                $fechaBase = $e->fecha ?? $e->created_at;
                $diasPendiente = max(0, $fechaBase->diffInDays(now()));

                return [
                    'id' => $e->id,
                    'ini' => $ini,
                    'paciente' => $nombre,
                    'tipo' => $e->tipo ?? 'Estudio',
                    'fecha' => optional($e->fecha)->format('d/m/Y') ?? format_user_date($e->created_at),
                    'pct' => min(100, max(15, $diasPendiente * 20)),
                ];
            })
            ->values();

        return view('ia-reportes.index', compact('kpis', 'reportes', 'hallazgos', 'estudiosSinReporte'));
    }

    public function generar(Request $request, OpenAiReportService $service): JsonResponse
    {
        $validated = $request->validate([
            'estudio_id' => [
                'required',
                Rule::exists('estudios', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'paciente' => ['nullable', 'string', 'max:255'],
            'tipo_estudio' => ['nullable', 'string', 'max:255'],
            'fecha' => ['nullable', 'string', 'max:50'],
            'observaciones' => ['required', 'string', 'max:5000'],
            'opciones' => ['nullable', 'array'],
            'imagen_ids' => ['nullable', 'array', 'max:8'],
            'imagen_ids.*' => ['integer'],
            'imagenes' => ['nullable', 'array', 'max:8'],
            'imagenes.*' => ['string', 'max:2048'],
        ]);

        $validated['paciente'] = $validated['paciente'] ?? 'No especificado';
        $validated['tipo_estudio'] = $validated['tipo_estudio'] ?? 'Estudio endoscópico';
        $validated['fecha'] = $validated['fecha'] ?? now()->toDateString();
        $validated['imagenes'] = ! empty($validated['imagen_ids'])
            ? $this->resolverImagenesPorIds($validated['estudio_id'], $validated['imagen_ids'])
            : $this->resolverImagenes($validated['imagenes'] ?? []);

        try {
            $reporte = $service->generarReporte($validated);
        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        // Persistir hallazgos detectados por la IA en estudio_hallazgos.
        $this->persistirHallazgosIa($validated['estudio_id'], $reporte);

        // Persistir el reporte preliminar en la BD para conservar formato e imágenes.
        $tipoKey = $this->tipoEstudioToKey($validated['tipo_estudio']);
        $plantillaId = Plantilla::where('clave', $tipoKey)->value('id');
        $html = $this->reporteIaToHtml($reporte);
        $nuevo = Reporte::create([
            'estudio_id' => $validated['estudio_id'],
            'usuario_id' => Auth::id(),
            'plantilla_id' => $plantillaId,
            'contenido_texto' => strip_tags($html),
            'contenido_html' => $html,
            'contiene_hallazgos_criticos' => false,
        ]);

        return response()->json([
            'ok' => true,
            'reporte' => $reporte,
            'reporte_id' => $nuevo->id,
        ]);
    }

    public function evidencia(EstudioArchivo $archivo): StreamedResponse
    {
        abort_unless($archivo->tipo === 'imagen' && filled($archivo->path), 404);

        $disk = $this->diskForPath($archivo->path);
        abort_unless($disk && $disk->exists($archivo->path), 404);

        $mime = 'image/jpeg';
        try {
            $mime = $archivo->mime_type ?: ($disk->mimeType($archivo->path) ?: $mime);
        } catch (Throwable) {
            $mime = $archivo->mime_type ?: $mime;
        }

        $headers = [
            'Content-Type' => $mime,
            'Cache-Control' => 'private, max-age=300',
        ];

        try {
            $size = $archivo->size_bytes ?: $disk->size($archivo->path);
            if ($size) {
                $headers['Content-Length'] = (string) $size;
            }
        } catch (Throwable) {
            // Algunos discos remotos no siempre exponen el tamano antes del stream.
        }

        return new StreamedResponse(function () use ($disk, $archivo): void {
            $stream = $disk->readStream($archivo->path);

            if (is_resource($stream)) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 200, $headers);
    }

    /**
     * Muestra el dashboard de hallazgos con datos reales de la base de datos.
     */
    public function hallazgos(): View
    {
        $data = $this->hallazgosData();

        return view('ia-reportes.hallazgos', [
            'hallazgos' => $data['hallazgos'],
            'totalHallazgos' => $data['totalHallazgos'],
            'totalEstudios' => $data['totalEstudios'],
            'totalCriticos' => $data['totalCriticos'],
            'hallazgoPrincipal' => $data['hallazgoPrincipal'],
        ]);
    }

    /**
     * Devuelve los hallazgos agregados para dashboards y reportes.
     */
    public function hallazgosData(): array
    {
        $raw = EstudioHallazgo::with(['hallazgo', 'estudio.paciente'])->get();

        $totalHallazgos = $raw->count();

        $hallazgos = $raw
            ->groupBy('hallazgo_id')
            ->map(function ($items) {
                $hallazgo = $items->first()->hallazgo;
                $pacientes = $items
                    ->map(fn ($item) => $item->estudio?->paciente)
                    ->filter()
                    ->unique('id')
                    ->values();

                return [
                    'id' => $hallazgo->id,
                    'nombre' => $hallazgo->nombre,
                    'cantidad' => $items->count(),
                    'porcentaje' => 0,
                    'pacientes' => $pacientes,
                    'es_critico' => (bool) $hallazgo->es_critico,
                ];
            })
            ->sortByDesc('cantidad')
            ->values()
            ->all();

        $maxCantidad = collect($hallazgos)->max('cantidad') ?: 1;
        foreach ($hallazgos as $key => $h) {
            $hallazgos[$key]['porcentaje'] = round($h['cantidad'] / $maxCantidad * 100);
        }

        $totalEstudios = \App\Models\Estudio::count();
        $totalCriticos = collect($hallazgos)->where('es_critico', true)->sum('cantidad');
        $hallazgoPrincipal = $hallazgos[0]['nombre'] ?? 'Ninguno';

        return [
            'hallazgos' => $hallazgos,
            'totalHallazgos' => $totalHallazgos,
            'totalEstudios' => $totalEstudios,
            'totalCriticos' => $totalCriticos,
            'hallazgoPrincipal' => $hallazgoPrincipal,
        ];
    }

    /**
     * Plantillas persistidas en la BD, indexadas por clave (para el editor Tauri).
     */
    public function apiPlantillas(): JsonResponse
    {
        $plantillas = Plantilla::all()->mapWithKeys(fn ($p) => [
            $p->clave => [
                'id' => $p->id,
                'clave' => $p->clave,
                'titulo' => $p->titulo,
                'subtitulo' => $p->subtitulo,
                'configuracion' => $p->configuracion,
                'columnas' => $p->columnas,
                'num_imagenes' => $p->num_imagenes,
            ],
        ]);

        return response()->json([
            'ok' => true,
            'plantillas' => $plantillas,
        ]);
    }

    /**
     * Estudios que aun no tienen reporte, para el selector del editor.
     */
    public function apiEstudiosSinReporte(Request $request): JsonResponse
    {
        $estudioId = $request->query('estudio_id');

        $estudios = \App\Models\Estudio::with('paciente')
            ->where(function ($q) use ($estudioId) {
                $q->whereDoesntHave('reportes');
                if ($estudioId) {
                    $q->orWhere('id', $estudioId);
                }
            })
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'paciente_id' => $e->paciente_id,
                'label' => trim(($e->paciente?->nombre_completo ?? $e->paciente_nombre ?? 'Paciente')
                    .' · '.($e->tipo ?? 'Estudio')
                    .' · '.(optional($e->fecha)->format('d/m/Y') ?? '')),
            ])
            ->values();

        return response()->json([
            'ok' => true,
            'estudios' => $estudios,
        ]);
    }

    /**
     * Datos para precargar el editor: paciente, estudio, imagenes y reporte existente (si aplica).
     */
    public function apiPreload(Request $request): JsonResponse
    {
        $pacienteId = $request->query('paciente_id');
        $estudioId = $request->query('estudio_id');
        $reporteId = $request->query('reporte_id');

        $reporte = $reporteId
            ? Reporte::with(['estudio.paciente', 'usuario', 'plantilla'])->find($reporteId)
            : null;

        $paciente = $pacienteId ? \App\Models\Paciente::find($pacienteId) : null;
        $estudio = $estudioId ? \App\Models\Estudio::with('paciente')->find($estudioId) : null;

        if ($reporte && ! $estudio) {
            $estudio = $reporte->estudio;
        }
        if ($reporte && ! $paciente) {
            $paciente = $reporte->estudio?->paciente;
        }
        if (! $paciente && $estudio) {
            $paciente = $estudio->paciente;
        }
        if ($paciente && ! $estudio) {
            $estudio = \App\Models\Estudio::where('paciente_id', $paciente->id)->latest()->first();
        }

        $estudioImagenes = collect();
        if ($paciente) {
            $estudioImagenes = EstudioArchivo::where('paciente_id', $paciente->id)
                ->where('tipo', 'imagen')
                ->when($estudio, fn ($q) => $q->where('estudio_id', $estudio->id))
                ->orderByDesc('capturado_en')
                ->orderByDesc('id')
                ->get()
                ->map(fn ($a) => [
                    'id' => $a->id,
                    'url' => media_url($a->path),
                    'path' => $a->path,
                    'titulo' => $a->nombre_original,
                ])
                ->values();
        }

        return response()->json([
            'ok' => true,
            'data' => [
                'paciente' => $paciente?->nombre_completo ?? ($estudio?->paciente_nombre ?? ''),
                'paciente_id' => $paciente?->id,
                'edad' => $paciente?->edad ? $paciente->edad.' años' : '',
                'sexo' => $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '',
                'nacimiento' => optional($paciente?->fecha_nacimiento)->format('d/m/Y') ?? '',
                'fecha_estudio' => optional($estudio?->fecha)->format('d/m/Y') ?? now()->format('d/m/Y'),
                'procedimiento' => $estudio?->tipo ?? $paciente?->procedimiento ?? '',
                'tipo' => $estudio?->tipo ?? $paciente?->procedimiento ?? '',
                'medico' => $estudio?->medico ?? $paciente?->medico ?? '',
                'estudio_id' => $estudio?->id,
                'imagenes' => $estudioImagenes,
                'reporte' => $reporte ? [
                    'id' => $reporte->id,
                    'estudio_id' => $reporte->estudio_id,
                    'plantilla_id' => $reporte->plantilla_id,
                    'contenido_html' => $reporte->contenido_html,
                    'contenido_texto' => $reporte->contenido_texto,
                    'imagenes_config' => $reporte->imagenes_config,
                    'contiene_hallazgos_criticos' => (bool) $reporte->contiene_hallazgos_criticos,
                ] : null,
            ],
        ]);
    }

    /**
     * Listado completo de reportes (para la vista "Todos los reportes" en Tauri).
     */
    public function apiReportesTodos(): JsonResponse
    {
        $reportes = Reporte::with(['estudio.paciente'])
            ->latest()
            ->get()
            ->map(function (Reporte $r) {
                $pacNombre = $r->estudio?->paciente?->nombre_completo ?? $r->estudio?->paciente_nombre ?? 'Sin paciente';
                $iniciales = collect(explode(' ', $pacNombre))->filter()->take(2)
                    ->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))->implode('') ?: 'NA';

                return [
                    'id' => $r->id,
                    'reporte_id' => $r->id,
                    'estudio_id' => $r->estudio_id,
                    'paciente' => $pacNombre,
                    'initials' => $iniciales,
                    'estudio' => $r->estudio?->tipo ?? 'Estudio',
                    'fecha' => $r->created_at?->format('Y-m-d'),
                    'hora' => $r->created_at?->format('H:i'),
                    'critical' => (bool) $r->contiene_hallazgos_criticos,
                    'estado_texto' => $r->contiene_hallazgos_criticos ? 'Critico' : 'Normal',
                ];
            })
            ->values();

        return response()->json([
            'ok' => true,
            'reportes' => $reportes,
        ]);
    }

    /**
     * Detalle completo de un reporte (para "Ver" dentro de la app Tauri).
     */
    public function apiVer(int $reporte): JsonResponse
    {
        $r = Reporte::with(['estudio.paciente', 'usuario', 'plantilla'])->findOrFail($reporte);
        $paciente = $r->estudio?->paciente;

        return response()->json([
            'ok' => true,
            'data' => [
                'id' => $r->id,
                'paciente' => $paciente?->nombre_completo ?? $r->estudio?->paciente_nombre ?? 'Sin paciente',
                'edad' => $paciente?->edad ? $paciente->edad.' años' : '',
                'sexo' => $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '',
                'nacimiento' => optional($paciente?->fecha_nacimiento)->format('d/m/Y') ?? '',
                'medico' => $r->usuario?->name ?? '',
                'estudio' => $r->estudio?->tipo ?? 'Estudio',
                'fecha_estudio' => optional($r->estudio?->fecha)->format('d/m/Y') ?? '',
                'creado_en' => $r->created_at?->format('d/m/Y H:i'),
                'contenido_html' => $r->contenido_html,
                'contenido_texto' => $r->contenido_texto,
                'critical' => (bool) $r->contiene_hallazgos_criticos,
                'plantilla_id' => $r->plantilla_id,
                'plantilla_clave' => $r->plantilla?->clave,
            ],
        ]);
    }

    public function descargarPdf(Reporte $reporte, ReportPdfGenerator $pdfGenerator)
    {
        $pdf = $pdfGenerator->make($reporte);

        return response($pdf['data'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$pdf['name'].'"',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    /**
     * Mapea un tipo de estudio a la clave de plantilla del editor.
     */
    private function tipoEstudioToKey(?string $tipo): string
    {
        $t = Str::lower($tipo ?? '');
        if (Str::contains($t, 'colono')) return 'colonoscopia';
        if (Str::contains($t, 'gastro')) return 'gastroscopia';
        if (Str::contains($t, 'duodeno')) return 'duodenoscopia';
        if (Str::contains($t, 'bronco')) return 'broncoscopia';
        return 'blanco';
    }

    /**
     * Convierte el reporte estructurado de la IA en HTML con secciones y anexo de imágenes.
     */
    private function reporteIaToHtml(array $reporte): string
    {
        $inf = $reporte['informe'] ?? [];
        $secciones = [
            'INDICACIÓN' => $inf['indicacion'] ?? '',
            'SEDACIÓN' => $inf['sedacion'] ?? '',
            'HALLAZGOS' => $inf['hallazgos'] ?? [],
            'IMPRESIÓN DIAGNÓSTICA' => $inf['impresion_diagnostica'] ?? '',
            'PLAN Y RECOMENDACIONES' => $inf['plan_recomendaciones'] ?? [],
            'OBSERVACIONES' => $inf['observaciones'] ?? '',
        ];

        $html = '';
        foreach ($secciones as $titulo => $contenido) {
            $html .= '<h4>' . e($titulo) . '</h4>';
            if (is_array($contenido)) {
                if (count($contenido)) {
                    $html .= '<ul>';
                    foreach ($contenido as $item) {
                        $html .= '<li>' . e($item) . '</li>';
                    }
                    $html .= '</ul>';
                }
            } elseif (trim($contenido) !== '') {
                $html .= '<p>' . e($contenido) . '</p>';
            }
        }

        $anexo = $reporte['anexo'] ?? [];
        if (count($anexo)) {
            $html .= '<h4>ANEXO DE IMÁGENES</h4>';
            foreach ($anexo as $i => $desc) {
                $html .= '<p><b>Imagen ' . ($i + 1) . ':</b> ' . e($desc) . '</p>';
            }
        }

        return $html;
    }

    public function chat(Request $request, OpenAiReportService $service): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
            'contexto' => ['nullable', 'string', 'max:8000'],
        ]);

        try {
            $resultado = $service->chat($validated['message'], $validated['contexto'] ?? '');
        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'respuesta' => $resultado['respuesta'],
            'acciones' => $resultado['acciones'],
        ]);
    }

    /**
     * Guarda (o actualiza) un reporte clínico ligado a un estudio.
     */
    public function guardar(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'estudio_id' => [
                'required',
                Rule::exists('estudios', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'reporte_id' => [
                'nullable',
                Rule::exists('reportes', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'contenido_texto' => ['nullable', 'string'],
            'contenido_html' => ['nullable', 'string'],
            'imagenes_config' => ['nullable', 'array'],
            'imagenes_config.version' => ['nullable', 'integer'],
            'imagenes_config.enabled' => ['nullable', 'boolean'],
            'imagenes_config.cols' => ['nullable', 'integer', 'min:0', 'max:8'],
            'imagenes_config.items' => ['nullable', 'array'],
            'imagenes_config.items.*.visible' => ['nullable', 'boolean'],
            'imagenes_config.items.*.size' => ['nullable', 'integer', 'min:1', 'max:8'],
            'contiene_hallazgos_criticos' => ['nullable', 'boolean'],
            'plantilla_id' => ['nullable', 'exists:plantillas,id'],
            'hallazgos' => ['nullable', 'array'],
            'hallazgos.*' => ['string', 'max:255'],
        ]);

        $data = [
            'estudio_id' => $validated['estudio_id'],
            'usuario_id' => Auth::id(),
            'plantilla_id' => $validated['plantilla_id'] ?? null,
            'contenido_texto' => $validated['contenido_texto'] ?? null,
            'contenido_html' => $validated['contenido_html'] ?? null,
            'imagenes_config' => $this->normalizarImagenesConfig($validated['imagenes_config'] ?? null),
            'contiene_hallazgos_criticos' => $validated['contiene_hallazgos_criticos'] ?? false,
        ];

        if (! empty($validated['reporte_id'])) {
            $reporte = Reporte::findOrFail($validated['reporte_id']);
            $reporte->update($data);
        } else {
            $reporte = Reporte::create($data);
        }

        try {
            $reportePath = $this->guardarReporteEnStorage($reporte);
        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'El reporte se guardo en la base de datos, pero no se pudo guardar en S3: '.$e->getMessage(),
            ], 422);
        }

        if (! empty($validated['hallazgos'])) {
            $this->persistirHallazgosDoctor($validated['estudio_id'], $validated['hallazgos']);
        }

        return response()->json([
            'ok' => true,
            'reporte_id' => $reporte->id,
            'reporte_path' => $reportePath,
            'download_url' => route('ia-reportes.ver', ['reporte' => $reporte->id, 'download' => 1]),
            'message' => 'Reporte guardado en S3 correctamente.',
        ]);
    }

    private function normalizarImagenesConfig(?array $config): ?array
    {
        if (! is_array($config)) {
            return null;
        }

        $enabled = ($config['enabled'] ?? true) !== false;
        $cols = $enabled ? max(0, min(8, (int) ($config['cols'] ?? 4))) : 0;
        $maxSize = max(1, $cols ?: 8);
        $items = [];
        foreach ($config['items'] ?? [] as $key => $item) {
            if (! is_array($item)) {
                continue;
            }

            $items[(string) $key] = [
                'visible' => (bool) ($item['visible'] ?? true),
                'size' => max(1, min($maxSize, (int) ($item['size'] ?? 1))),
            ];
        }

        return [
            'version' => 1,
            'enabled' => $enabled,
            'cols' => $cols,
            'items' => $items,
        ];
    }

    /**
     * Guarda una copia HTML del reporte en la carpeta reports del estudio.
     */
    private function guardarReporteEnStorage(Reporte $reporte): string
    {
        $reporte->loadMissing(['estudio.paciente', 'usuario', 'plantilla']);
        $estudio = $reporte->estudio;

        if (! $estudio) {
            throw new \RuntimeException('El reporte no tiene un estudio asociado.');
        }

        $folder = app(MediaPathService::class)->studyReports($estudio);
        $disk = Storage::disk(media_disk());

        $this->ensureStorageDirectory($disk, $folder);

        $path = $folder.'/reporte-'.$reporte->id.'.html';
        $stored = $disk->put($path, $this->reporteHtmlParaStorage($reporte), [
            'visibility' => 'private',
            'ContentType' => 'text/html; charset=UTF-8',
        ]);

        if (! $stored) {
            throw new \RuntimeException('El disco de almacenamiento rechazo la escritura del archivo.');
        }

        $estudio->forceFill(['reporte_path' => $path])->save();

        return $path;
    }

    private function ensureStorageDirectory(object $disk, string $folder): void
    {
        try {
            if (method_exists($disk, 'directoryExists') && $disk->directoryExists($folder)) {
                return;
            }

            if (method_exists($disk, 'makeDirectory')) {
                $disk->makeDirectory($folder);
            }
        } catch (Throwable) {
            // En S3 las carpetas son prefijos virtuales; la escritura del archivo es la verificacion real.
        }
    }

    private function reporteHtmlParaStorage(Reporte $reporte): string
    {
        $estudio = $reporte->estudio;
        $paciente = $estudio?->paciente;
        $nombrePaciente = $paciente?->nombre_completo ?? $estudio?->paciente_nombre ?? 'Paciente no registrado';
        $tipoEstudio = $estudio?->tipo ?? 'Estudio endoscopico';
        $medico = $reporte->usuario?->name ?? $estudio?->medico ?? 'Medico no especificado';
        $fechaEstudio = format_user_date($estudio?->fecha ?? $reporte->created_at) ?: '';
        $contenido = $reporte->contenido_html ?: nl2br(e($reporte->contenido_texto ?? ''));
        $imagenesConfig = $reporte->imagenes_config ?? [];
        $imagenesEnabled = ($imagenesConfig['enabled'] ?? true) !== false;
        $cols = $imagenesEnabled
            ? max(1, min(8, (int) ($imagenesConfig['cols'] ?? $reporte->plantilla?->columnas ?? 4)))
            : 1;
        $imagenes = $this->imagenesReporteParaStorage($estudio, $imagenesConfig);

        $imagenesHtml = collect($imagenes)->map(fn (array $img) => '
            <figure style="grid-column:span '.$img['size'].'">
                <img src="'.$img['src'].'" alt="'.e($img['titulo']).'">
                <figcaption>'.e($img['titulo']).'</figcaption>
            </figure>
        ')->implode('');

        return '<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Reporte '.e($nombrePaciente).'</title>
  <style>
    body{font-family:Arial,sans-serif;color:#111827;margin:32px;line-height:1.55}
    h1{font-size:22px;margin:0 0 4px;text-align:center}
    .subtitle{text-align:center;color:#4b5563;margin:0 0 24px;text-transform:uppercase;font-size:12px;letter-spacing:.08em}
    .meta{display:grid;grid-template-columns:160px 1fr;gap:5px 16px;font-size:13px;margin-bottom:22px}
    .meta .k{color:#6b7280}
    .images{display:grid;grid-template-columns:repeat('.$cols.',1fr);gap:10px;margin:18px 0 24px}
    figure{margin:0;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;background:#020714}
    figure img{display:block;width:100%;aspect-ratio:4/3;object-fit:contain;object-position:center;background:#020714}
    figcaption{font-size:11px;color:#4b5563;padding:6px 8px}
    h4{font-size:13px;margin:18px 0 6px;color:#0f66d0;text-transform:uppercase}
    p,li{font-size:13px}
  </style>
</head>
<body>
  <h1>INFORME DE '.e(mb_strtoupper($tipoEstudio)).'</h1>
  <p class="subtitle">ENCLAII</p>
  <div class="meta">
    <span class="k">Paciente:</span><span>'.e($nombrePaciente).'</span>
    <span class="k">Fecha de nacimiento:</span><span>'.e(format_user_date($paciente?->fecha_nacimiento) ?: '').'</span>
    <span class="k">Edad:</span><span>'.e($paciente?->edad ? $paciente->edad.' anos' : '').'</span>
    <span class="k">Medico:</span><span>'.e($medico).'</span>
    <span class="k">Fecha del estudio:</span><span>'.e($fechaEstudio).'</span>
    <span class="k">Tipo de estudio:</span><span>'.e($tipoEstudio).'</span>
  </div>
  '.($imagenesHtml ? '<section class="images">'.$imagenesHtml.'</section>' : '').'
  <section>'.$contenido.'</section>
</body>
</html>';
    }

    /**
     * @return array<int, array{src: string, titulo: string, size: int}>
     */
    private function imagenesReporteParaStorage($estudio, array $config = []): array
    {
        if (! $estudio) {
            return [];
        }

        if (($config['enabled'] ?? true) === false) {
            return [];
        }

        $disk = Storage::disk(media_disk());
        $items = is_array($config['items'] ?? null) ? $config['items'] : [];
        $cols = max(1, min(8, (int) ($config['cols'] ?? 4)));

        return EstudioArchivo::where('estudio_id', $estudio->id)
            ->where('tipo', 'imagen')
            ->orderByDesc('capturado_en')
            ->orderByDesc('id')
            ->take(24)
            ->get()
            ->map(function (EstudioArchivo $archivo) use ($disk, $items, $cols) {
                try {
                    if (! $archivo->path) {
                        return null;
                    }

                    $state = $items[(string) $archivo->id] ?? [];
                    if (($state['visible'] ?? true) === false) {
                        return null;
                    }

                    $sourceDisk = $disk;
                    if (! $sourceDisk->exists($archivo->path) && Storage::disk('public')->exists($archivo->path)) {
                        $sourceDisk = Storage::disk('public');
                    }

                    if (! $sourceDisk->exists($archivo->path)) {
                        return null;
                    }

                    $size = method_exists($sourceDisk, 'size') ? (int) $sourceDisk->size($archivo->path) : 0;
                    if ($size > 8 * 1024 * 1024) {
                        return null;
                    }

                    $mime = $archivo->mime_type ?: ($sourceDisk->mimeType($archivo->path) ?: 'image/jpeg');

                    return [
                        'src' => 'data:'.$mime.';base64,'.base64_encode($sourceDisk->get($archivo->path)),
                        'titulo' => $archivo->nombre_original ?: basename($archivo->path),
                        'size' => max(1, min($cols, (int) ($state['size'] ?? 1))),
                    ];
                } catch (Throwable) {
                    return null;
                }
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Lista todos los hallazgos disponibles + los del estudio actual.
     */
    public function listarHallazgos(Request $request): JsonResponse
    {
        $estudioId = $request->query('estudio_id');

        $todos = Hallazgo::orderBy('nombre')->get()->map(fn ($h) => [
            'id' => $h->id,
            'nombre' => $h->nombre,
            'es_critico' => $h->es_critico,
        ]);

        $seleccionados = [];
        if ($estudioId) {
            $seleccionados = EstudioHallazgo::where('estudio_id', $estudioId)
                ->with('hallazgo')
                ->get()
                ->map(fn ($eh) => [
                    'id' => $eh->hallazgo_id,
                    'nombre' => $eh->hallazgo?->nombre,
                    'detectado_por' => $eh->detectado_por,
                ])
                ->filter(fn ($item) => $item['nombre'] !== null)
                ->values()
                ->toArray();
        }

        return response()->json([
            'ok' => true,
            'data' => [
                'todos' => $todos,
                'seleccionados' => $seleccionados,
            ],
        ]);
    }

    /**
     * Crea un hallazgo nuevo y lo devuelve.
     */
    public function crearHallazgo(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'es_critico' => ['nullable', 'boolean'],
        ]);

        $existente = Hallazgo::where('nombre', trim($validated['nombre']))->first();
        if ($existente) {
            return response()->json([
                'ok' => true,
                'data' => [
                    'id' => $existente->id,
                    'nombre' => $existente->nombre,
                    'es_critico' => $existente->es_critico,
                    'ya_existe' => true,
                ],
            ]);
        }

        $hallazgo = Hallazgo::create([
            'nombre' => trim($validated['nombre']),
            'es_critico' => $validated['es_critico'] ?? false,
        ]);

        return response()->json([
            'ok' => true,
            'data' => [
                'id' => $hallazgo->id,
                'nombre' => $hallazgo->nombre,
                'es_critico' => $hallazgo->es_critico,
                'ya_existe' => false,
            ],
        ]);
    }

    /**
     * Persiste los hallazgos seleccionados por el doctor en estudio_hallazgos.
     */
    private function persistirHallazgosDoctor(int $estudioId, array $nombres): void
    {
        $nombres = collect($nombres)
            ->map(fn ($n) => trim((string) $n))
            ->filter()
            ->unique()
            ->values();

        if ($nombres->isEmpty()) {
            return;
        }

        foreach ($nombres as $nombre) {
            $hallazgo = Hallazgo::firstOrCreate(
                ['nombre' => $nombre],
                ['es_critico' => false]
            );

            EstudioHallazgo::updateOrCreate(
                [
                    'estudio_id' => $estudioId,
                    'hallazgo_id' => $hallazgo->id,
                ],
                [
                    'detectado_por' => 'doctor',
                ]
            );
        }
    }

    /**
     * Convierte rutas públicas de evidencia en data URLs base64 para análisis con visión.
     *
     * @param  array<int, string>  $imagenes
     * @return array<int, string>
     */
    private function resolverImagenesPorIds(int $estudioId, array $ids): array
    {
        $ids = collect($ids)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->take(8)
            ->values();

        if ($ids->isEmpty()) {
            return [];
        }

        $archivos = EstudioArchivo::query()
            ->where('estudio_id', $estudioId)
            ->where('tipo', 'imagen')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return $ids
            ->map(fn (int $id) => $archivos->get($id))
            ->filter()
            ->map(fn (EstudioArchivo $archivo) => $this->archivoImagenToDataUrl($archivo))
            ->filter()
            ->values()
            ->all();
    }

    private function archivoImagenToDataUrl(EstudioArchivo $archivo): ?string
    {
        if (! $archivo->path) {
            return null;
        }

        $disk = $this->diskForPath($archivo->path);
        if (! $disk || ! $disk->exists($archivo->path)) {
            return null;
        }

        try {
            $size = method_exists($disk, 'size') ? (int) $disk->size($archivo->path) : 0;
            if ($size > 8 * 1024 * 1024) {
                return null;
            }

            $mime = $archivo->mime_type ?: ($disk->mimeType($archivo->path) ?: 'image/jpeg');

            return 'data:'.$mime.';base64,'.base64_encode($disk->get($archivo->path));
        } catch (Throwable) {
            return null;
        }
    }

    private function diskForPath(string $path): ?object
    {
        $mediaDisk = Storage::disk(media_disk());
        if ($mediaDisk->exists($path)) {
            return $mediaDisk;
        }

        $publicDisk = Storage::disk('public');
        if ($publicDisk->exists($path)) {
            return $publicDisk;
        }

        return null;
    }

    private function resolverImagenes(array $imagenes): array
    {
        $mimes = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
        ];

        return collect($imagenes)
            ->map(function (string $rel) use ($mimes) {
                // Evita rutas peligrosas (traversal) y normaliza URLs/rutas.
                $rel = strtok(str_replace('\\', '/', $rel), '?') ?: '';
                $pathFromUrl = parse_url($rel, PHP_URL_PATH);
                if (is_string($pathFromUrl) && $pathFromUrl !== '') {
                    $rel = $pathFromUrl;
                }

                if (str_contains($rel, '..')) {
                    return null;
                }

                $rel = ltrim($rel, '/');
                $storageRel = Str::startsWith($rel, 'storage/')
                    ? Str::after($rel, 'storage/')
                    : $rel;

                $path = public_path($rel);
                $ext = strtolower(pathinfo($storageRel, PATHINFO_EXTENSION));
                if (! isset($mimes[$ext])) {
                    return null;
                }

                // Límite de seguridad: ~8 MB por imagen.
                $contents = null;

                if (Storage::disk(media_disk())->exists($storageRel)) {
                    if (Storage::disk(media_disk())->size($storageRel) > 8 * 1024 * 1024) {
                        return null;
                    }

                    $contents = Storage::disk(media_disk())->get($storageRel);
                }

                if ($contents === null) {
                    if (! is_file($path)) {
                        return null;
                    }

                    if (filesize($path) > 8 * 1024 * 1024) {
                        return null;
                    }

                    $contents = @file_get_contents($path);
                }
                if ($contents === false) {
                    return null;
                }

                return 'data:' . $mimes[$ext] . ';base64,' . base64_encode($contents);
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Persiste los hallazgos detectados por la IA en la tabla estudio_hallazgos.
     */
    private function persistirHallazgosIa(int $estudioId, array $reporte): void
    {
        $hallazgos = collect();

        // Hallazgos estructurados: { texto, confianza }
        foreach ($reporte['hallazgos'] ?? [] as $h) {
            $texto = trim((string) ($h['texto'] ?? ''));
            if ($texto !== '') {
                $hallazgos->push($texto);
            }
        }

        // Hallazgos del informe: array de strings
        foreach ($reporte['informe']['hallazgos'] ?? [] as $texto) {
            $texto = trim((string) $texto);
            if ($texto !== '') {
                $hallazgos->push($texto);
            }
        }

        $hallazgos = $hallazgos->unique()->values();
        if ($hallazgos->isEmpty()) {
            return;
        }

        foreach ($hallazgos as $nombre) {
            $hallazgo = Hallazgo::firstOrCreate(
                ['nombre' => $nombre],
                ['es_critico' => false]
            );

            EstudioHallazgo::updateOrCreate(
                [
                    'estudio_id' => $estudioId,
                    'hallazgo_id' => $hallazgo->id,
                ],
                [
                    'detectado_por' => 'ia',
                ]
            );
        }
    }
}
