<?php

use App\Http\Controllers\Auth\EndoCareAuthController;
use App\Http\Controllers\IaReporteController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\NuevoEstudioController;
use App\Models\Paciente;
use App\Models\Reporte;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [EndoCareAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [EndoCareAuthController::class, 'login'])->name('login.post');

    Route::get('/registro', [EndoCareAuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [EndoCareAuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $estudiosSinReporte = \App\Models\Estudio::whereDoesntHave('reportes')->count();

        // Auto-cancelar citas próximas cuya fecha/hora ya pasó
        \App\Models\Cita::query()
            ->where('estado', 'proximo')
            ->whereRaw("CONCAT(fecha, ' ', hora) <= ?", [now()->format('Y-m-d H:i:s')])
            ->update(['estado' => 'cancelado']);

        // Próximo paciente: la cita pendiente más cercana (solo futuras)
        $proximaCita = \App\Models\Cita::with('paciente')
            ->whereNotIn('estado', ['cancelado', 'completado'])
            ->whereRaw("CONCAT(fecha, ' ', hora) >= ?", [now()->format('Y-m-d H:i:s')])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        // Pacientes pendientes HOY: citas de hoy futuras y no completadas/canceladas
        $pendientesHoy = \App\Models\Cita::with('paciente')
            ->whereDate('fecha', now()->toDateString())
            ->whereNotIn('estado', ['completado', 'cancelado'])
            ->whereTime('hora', '>=', now()->format('H:i:s'))
            ->orderBy('hora')
            ->get();

        // Citas por estado para el donut del resumen
        $citasProximas = \App\Models\Cita::where('estado', 'proximo')->count();
        $citasCompletadas = \App\Models\Cita::where('estado', 'completado')->count();
        $citasCanceladas = \App\Models\Cita::where('estado', 'cancelado')->count();

        return view('dashboard.index', compact(
            'estudiosSinReporte', 'proximaCita', 'pendientesHoy',
            'citasProximas', 'citasCompletadas', 'citasCanceladas'
        ));
    })->name('dashboard');
    

    Route::get('/ia-reportes', function () {
        $reportes = Reporte::with(['estudio.paciente', 'usuario'])
            ->latest()
            ->get();

        // ===== KPIs con datos reales =====
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();
        $inicioMesPrev = now()->subMonthNoOverflow()->startOfMonth();
        $finMesPrev = now()->subMonthNoOverflow()->endOfMonth();

        // % de variación entre dos conteos
        $pct = function (int $actual, int $previo): int {
            if ($previo === 0) {
                return $actual > 0 ? 100 : 0;
            }

            return (int) round((($actual - $previo) / $previo) * 100);
        };

        // 1. Reportes generados (este mes)
        $repMes = Reporte::whereBetween('created_at', [$inicioMes, $finMes])->count();
        $repPrev = Reporte::whereBetween('created_at', [$inicioMesPrev, $finMesPrev])->count();

        // 2. Estudios sin reporte (pendientes reales)
        $estudiosSinReporte = \App\Models\Estudio::whereDoesntHave('reportes')->count();

        // 3. Evidencias (imágenes) capturadas este mes
        $evMes = \App\Models\EstudioArchivo::where('tipo', 'imagen')
            ->whereBetween('created_at', [$inicioMes, $finMes])->count();
        $evPrev = \App\Models\EstudioArchivo::where('tipo', 'imagen')
            ->whereBetween('created_at', [$inicioMesPrev, $finMesPrev])->count();

        // 4. Estudios realizados este mes
        $estMes = \App\Models\Estudio::whereBetween('created_at', [$inicioMes, $finMes])->count();
        $estPrev = \App\Models\Estudio::whereBetween('created_at', [$inicioMesPrev, $finMesPrev])->count();

        $kpis = [
            'reportes' => ['valor' => $repMes, 'trend' => $pct($repMes, $repPrev)],
            'sin_reporte' => ['valor' => $estudiosSinReporte],
            'evidencias' => ['valor' => $evMes, 'trend' => $pct($evMes, $evPrev)],
            'estudios' => ['valor' => $estMes, 'trend' => $pct($estMes, $estPrev)],
        ];

        $hallazgosData = app(\App\Http\Controllers\IaReporteController::class)->hallazgosData();
        $hallazgos = collect($hallazgosData['hallazgos'])->take(5)->all();

        return view('ia-reportes.index', compact('reportes', 'kpis', 'hallazgos'));
    })->name('ia-reportes');

    Route::get('/ia-reportes/generar', function () {
        $estudioId = request()->query('estudio');
        $pacienteId = request()->query('paciente');

        $estudio = $estudioId
            ? \App\Models\Estudio::with('paciente')->find($estudioId)
            : null;

        $paciente = $estudio?->paciente
            ?? ($pacienteId ? Paciente::find($pacienteId) : null);

        // Si llega paciente sin estudio, usar su estudio más reciente
        if ($paciente && ! $estudio) {
            $estudio = \App\Models\Estudio::where('paciente_id', $paciente->id)
                ->latest()
                ->first();
        }

        // Evidencia: fotos reales del estudio
        $evidencias = collect();
        if ($estudio) {
            $evidencias = \App\Models\EstudioArchivo::where('estudio_id', $estudio->id)
                ->where('tipo', 'imagen')
                ->orderByDesc('capturado_en')
                ->orderByDesc('id')
                ->get()
                ->map(fn ($a) => 'storage/'.$a->path)
                ->values();
        }

        // Datos para precargar el formulario
        $datos = [
            'paciente' => $paciente?->nombre_completo ?? ($estudio?->paciente_nombre ?? ''),
            'iniciales' => collect(explode(' ', $paciente?->nombre_completo ?? 'NA'))
                ->filter()->take(2)->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))->implode('') ?: 'NA',
            'edad' => $paciente?->edad ? $paciente->edad.' años' : '',
            'sexo' => $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '',
            'folio' => $paciente?->folio ?? '',
            'identificacion' => $paciente?->identificacion ?? '',
            'medico' => $estudio?->medico ?? $paciente?->medico ?? '',
            'nacimiento' => optional($paciente?->fecha_nacimiento)->format('d/m/Y') ?? '',
            'tipo' => $estudio?->tipo ?? $paciente?->procedimiento ?? '',
            'fecha' => optional($estudio?->fecha)->format('Y-m-d') ?? now()->format('Y-m-d'),
            'observaciones' => $paciente?->diagnostico_preliminar ?? '',
            'estudio_id' => $estudio?->id,
        ];

        // Lista de estudios para el selector: solo los que NO tienen reporte aún
        // (incluye el estudio actual aunque ya tuviera, para que la opción seleccionada aparezca).
        $estudiosLista = \App\Models\Estudio::with('paciente')
            ->where(function ($q) use ($estudio) {
                $q->whereDoesntHave('reportes');
                if ($estudio) {
                    $q->orWhere('id', $estudio->id);
                }
            })
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'label' => trim(($e->paciente?->nombre_completo ?? $e->paciente_nombre ?? 'Paciente')
                    .' · '.($e->tipo ?? 'Estudio')
                    .' · '.(optional($e->fecha)->format('d/m/Y') ?? '')),
            ])
            ->values();

        return view('ia-reportes.generar', [
            'estudio' => $estudio,
            'paciente' => $paciente,
            'evidencias' => $evidencias,
            'datos' => $datos,
            'estudiosLista' => $estudiosLista,
        ]);
    })->name('ia-reportes.generar');

    Route::get('/ia-reportes/redactar', function () {
        $pacienteId = request()->query('paciente');
        $estudioId = request()->query('estudio');
        $reporteId = request()->query('reporte');

        $reporte = $reporteId ? \App\Models\Reporte::with(['estudio.paciente', 'usuario'])->find($reporteId) : null;

        $paciente = $pacienteId ? Paciente::find($pacienteId) : null;

        $estudio = $estudioId
            ? \App\Models\Estudio::with('paciente')->find($estudioId)
            : null;

        // Si viene reporte, derivar estudio y paciente de él
        if ($reporte && ! $estudio) {
            $estudio = $reporte->estudio;
        }
        if ($reporte && ! $paciente) {
            $paciente = $reporte->estudio?->paciente;
        }

        // Si no llegó paciente explícito, derivarlo del estudio
        if (! $paciente && $estudio) {
            $paciente = $estudio->paciente;
        }

        // Si hay paciente pero no estudio, usar su estudio más reciente
        if ($paciente && ! $estudio) {
            $estudio = \App\Models\Estudio::where('paciente_id', $paciente->id)
                ->latest()
                ->first();
        }

        $estudioImagenes = collect();
        if ($paciente) {
            $estudioImagenes = \App\Models\EstudioArchivo::where('paciente_id', $paciente->id)
                ->where('tipo', 'imagen')
                ->when($estudio, fn ($q) => $q->where('estudio_id', $estudio->id))
                ->orderByDesc('capturado_en')
                ->orderByDesc('id')
                ->get()
                ->map(fn ($a) => [
                    'id' => $a->id,
                    'url' => asset('storage/'.$a->path),
                    'titulo' => $a->nombre_original,
                ])
                ->values();
        }

        // Datos del estudio/paciente para precargar el editor
        $datosEstudio = [
            'paciente' => $paciente?->nombre_completo ?? ($estudio?->paciente_nombre ?? ''),
            'edad' => $paciente?->edad ? $paciente->edad.' años' : '',
            'sexo' => $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '',
            'nacimiento' => optional($paciente?->fecha_nacimiento)->format('d/m/Y') ?? '',
            'fecha_estudio' => optional($estudio?->fecha)->format('d/m/Y') ?? now()->format('d/m/Y'),
            'procedimiento' => $estudio?->tipo ?? $paciente?->procedimiento ?? '',
            'tipo' => $estudio?->tipo ?? $paciente?->procedimiento ?? '',
            'medico' => $estudio?->medico ?? $paciente?->medico ?? '',
        ];

        // Plantillas guardadas (configuración persistida por clave)
        $plantillasDb = \App\Models\Plantilla::all()->mapWithKeys(fn ($p) => [
            $p->clave => [
                'id' => $p->id,
                'titulo' => $p->titulo,
                'subtitulo' => $p->subtitulo,
                'configuracion' => $p->configuracion,
                'columnas' => $p->columnas,
                'num_imagenes' => $p->num_imagenes,
            ],
        ]);

        // Selector de estudio: solo los que NO tienen reporte aún
        // (incluye el estudio actual para que la opción seleccionada aparezca).
        $estudiosLista = \App\Models\Estudio::with('paciente')
            ->where(function ($q) use ($estudio) {
                $q->whereDoesntHave('reportes');
                if ($estudio) {
                    $q->orWhere('id', $estudio->id);
                }
            })
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'label' => trim(($e->paciente?->nombre_completo ?? $e->paciente_nombre ?? 'Paciente')
                    .' · '.($e->tipo ?? 'Estudio')
                    .' · '.(optional($e->fecha)->format('d/m/Y') ?? '')),
            ])
            ->values();

        return view('ia-reportes.redactar', [
            'paciente' => $paciente,
            'estudio' => $estudio,
            'reporte' => $reporte,
            'estudioImagenes' => $estudioImagenes,
            'datosEstudio' => $datosEstudio,
            'plantillasDb' => $plantillasDb,
            'estudiosLista' => $estudiosLista,
        ]);
    })->name('ia-reportes.redactar');

    Route::post('/ia-reportes/generar', [IaReporteController::class, 'generar'])
        ->name('ia-reportes.generar.post');

    Route::post('/ia-reportes/guardar', [IaReporteController::class, 'guardar'])
        ->name('ia-reportes.guardar');

    Route::post('/plantillas/{clave}', [\App\Http\Controllers\PlantillaController::class, 'update'])
        ->name('plantillas.update');

    Route::post('/ia-reportes/chat', [IaReporteController::class, 'chat'])
        ->name('ia-reportes.chat.post');

    Route::get('/ia-reportes/hallazgos', [IaReporteController::class, 'hallazgos'])
        ->name('ia-reportes.hallazgos');

    Route::get('/ia-reportes/reportes', function () {
        $reportes = Reporte::with(['estudio.paciente', 'usuario'])
            ->latest()
            ->get();

        return view('ia-reportes.reportes', compact('reportes'));
    })->name('ia-reportes.todos');

    Route::get('/ia-reportes/editar', function () {
        $reporteId = request()->query('reporte');
        if ($reporteId) {
            return redirect()->route('ia-reportes.redactar', [
                'reporte' => $reporteId,
                'estudio' => request()->query('estudio'),
            ]);
        }
        return view('ia-reportes.editar');
    })->name('ia-reportes.editar');

    Route::get('/ia-reportes/ver', function () {
        $reporte = null;
        $estudioImagenes = collect();
        if (request()->has('reporte')) {
            $reporte = \App\Models\Reporte::with(['estudio.paciente', 'estudio.archivos', 'usuario', 'plantilla'])->find(request()->query('reporte'));
            if ($reporte && $reporte->estudio_id) {
                $estudioImagenes = \App\Models\EstudioArchivo::where('estudio_id', $reporte->estudio_id)
                    ->where('tipo', 'imagen')
                    ->orderByDesc('capturado_en')
                    ->get()
                    ->map(fn ($a) => ['url' => asset('storage/' . $a->path), 'titulo' => $a->nombre_original]);
            }
            // Si el reporte no tiene plantilla asignada, cargar la que corresponda al tipo de estudio
            if ($reporte && ! $reporte->plantilla && $reporte->estudio?->tipo) {
                $tipoKey = \Illuminate\Support\Str::lower($reporte->estudio->tipo);
                $default = \App\Models\Plantilla::where('clave', $tipoKey)->first();
                if ($default) {
                    $reporte->setRelation('plantilla', $default);
                }
            }
        }
        return view('ia-reportes.ver', compact('reporte', 'estudioImagenes'));
    })->name('ia-reportes.ver');

    Route::get('/ia-reportes/analisis', function () {
        return view('ia-reportes.analisis');
    })->name('ia-reportes.analisis');
    
    Route::get('/configuracion', function () {
        return view('configuracion.index', [
            'userSettings' => request()->user()->resolvedSettings(),
        ]);
    })->name('configuracion');

    // Route::get('/finanzas', function () {
    //     return view('finanzas.index');
    // })->name('finanzas');

    Route::patch('/configuracion/general', [SettingsController::class, 'update'])
        ->name('configuracion.general.update');

    Route::get('/mensajes/correo', function () {
        return view('mensajes.dashboard');
    })->name('mensajes.correo');


    Route::get('/mensajes', function () {
        return view('mensajes.dashboard');
    })->name('mensajes');

    Route::get('/nuevo-estudio', function (\Illuminate\Http\Request $request) {
        $paciente = $request->filled('paciente')
            ? Paciente::find($request->query('paciente'))
            : null;

        $pacientes = Paciente::select('id', 'nombre_completo', 'folio', 'edad', 'sexo', 'telefono', 'email', 'foto')
            ->orderBy('nombre_completo')
            ->get();

        $galImagenes = collect();
        $galVideos = collect();
        $reportes = collect();

        if ($paciente) {
            $archivos = \App\Models\EstudioArchivo::with('estudio')
                ->where('paciente_id', $paciente->id)
                ->orderByDesc('capturado_en')
                ->orderByDesc('id')
                ->get();

            $galImagenes = $archivos->where('tipo', 'imagen')->values();
            $galVideos = $archivos->where('tipo', 'video')->values();

            $reportes = Reporte::with(['estudio', 'usuario'])
                ->whereHas('estudio', fn ($q) => $q->where('paciente_id', $paciente->id))
                ->latest()
                ->get();
        }

        return view('estudios.crear', [
            'paciente' => $paciente,
            'pacientes' => $pacientes,
            'galImagenes' => $galImagenes,
            'galVideos' => $galVideos,
            'reportes' => $reportes,
        ]);
    })->name('nuevo-estudio');

    Route::get('/nuevo-estudio/crear', function () {
        return view('estudios.crear');
    })->name('nuevo-estudio.crear');

    Route::get('/nuevo-estudio/importar', function () {
        return view('estudios.importar');
    })->name('nuevo-estudio.importar');

    Route::post('/nuevo-estudio', [NuevoEstudioController::class, 'store'])
        ->name('nuevo-estudio.store');

    Route::get('/nuevo-estudio/capturas', function () {
        return view('estudios.capturas');
    })->name('nuevo-estudio.capturas');

    Route::post('/nuevo-estudio/capturas', [NuevoEstudioController::class, 'guardarCapturas'])
        ->name('nuevo-estudio.capturas.store');

    Route::get('/nuevo-estudio/configuracion', function () {
        return view('estudios.configuracion');
    })->name('nuevo-estudio.configuracion');

    Route::get('/nuevo-estudio/grabando', [NuevoEstudioController::class, 'grabando'])
        ->name('nuevo-estudio.grabando');

    Route::post('/nuevo-estudio/finalizar', [NuevoEstudioController::class, 'finalizarGrabacion'])
        ->name('nuevo-estudio.finalizar');

    Route::delete('/nuevo-estudio/archivos/{archivo}', [NuevoEstudioController::class, 'destroyArchivo'])
        ->name('nuevo-estudio.archivos.destroy');

    Route::post('/logout', [EndoCareAuthController::class, 'logout'])->name('logout');

    /* ── Galería ── */
    Route::get('/galeria', function () {
        $colores = [
            'linear-gradient(135deg,#c084fc,#a78bfa)',
            'linear-gradient(135deg,#7dd3fc,#60a5fa)',
            'linear-gradient(135deg,#f9a8d4,#f472b6)',
            'linear-gradient(135deg,#99f6e4,#6ee7b7)',
        ];

        $medicos = \App\Models\Estudio::query()
            ->whereNotNull('medico')
            ->where('medico', '<>', '')
            ->distinct()
            ->orderBy('medico')
            ->pluck('medico');

        $procedimientos = \App\Models\Estudio::query()
            ->whereNotNull('tipo')
            ->where('tipo', '<>', '')
            ->distinct()
            ->orderBy('tipo')
            ->pluck('tipo');

        $hallazgos = \App\Models\Hallazgo::orderBy('nombre')->get(['id', 'nombre']);

        $pacientesDb = Paciente::orderBy('nombre_completo')->get()->values();
        $pacienteIds = $pacientesDb->pluck('id');
        $estudiosPorPaciente = \App\Models\Estudio::with([
                'archivos:id,estudio_id,tipo',
                'estudioHallazgos:id,estudio_id,hallazgo_id,detectado_por',
            ])
            ->whereIn('paciente_id', $pacienteIds)
            ->get()
            ->groupBy('paciente_id');
        $archivosPorPaciente = \App\Models\EstudioArchivo::whereIn('paciente_id', $pacienteIds)
            ->get()
            ->groupBy('paciente_id');

        $pacientes = $pacientesDb->map(function ($p, $i) use ($colores, $estudiosPorPaciente, $archivosPorPaciente) {
            $archivosPaciente = $archivosPorPaciente->get($p->id, collect());
            $estudiosDetalle = $estudiosPorPaciente->get($p->id, collect());
            $fotos = $archivosPaciente->where('tipo', 'imagen')->count();
            $videos = $archivosPaciente->where('tipo', 'video')->count();
            $estudios = $estudiosDetalle->count();
            $ultimoTs = $archivosPaciente->max('capturado_en');
            $ultimo = $ultimoTs ? \Illuminate\Support\Carbon::parse($ultimoTs)->format('d/m/Y') : '—';
            $ini = collect(explode(' ', $p->nombre_completo ?? ''))
                ->filter()->take(2)
                ->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))
                ->implode('') ?: 'PX';

            return [
                'id' => $p->id,
                'nombre' => $p->nombre_completo ?? 'Paciente',
                'telefono' => $p->telefono ?? '',
                'codigo' => $p->folio ?? $p->identificacion ?? '—',
                'sexo' => $p->sexo ?? '—',
                'edad' => $p->edad ? $p->edad . ' años' : '—',
                'ultimo' => $ultimo,
                'estudios' => $estudios,
                'fotos' => $fotos,
                'videos' => $videos,
                'estado' => 'Activo',
                'ini' => $ini,
                'color' => $colores[$i % count($colores)],
                'filtros' => $estudiosDetalle->map(function ($estudio) {
                    $hallazgosEstudio = $estudio->estudioHallazgos;

                    return [
                        'medico' => $estudio->medico ?? '',
                        'procedimiento' => $estudio->tipo ?? '',
                        'fecha' => $estudio->fecha?->format('Y-m-d') ?? '',
                        'estado' => $estudio->estado ?? '',
                        'archivos' => $estudio->archivos->pluck('tipo')->unique()->values(),
                        'hallazgos' => $hallazgosEstudio->pluck('hallazgo_id')
                            ->map(fn ($id) => (string) $id)
                            ->values(),
                        'hallazgos_ia' => $hallazgosEstudio->contains(
                            fn ($hallazgo) => mb_strtolower($hallazgo->detectado_por ?? '') === 'ia'
                        ),
                    ];
                })->values(),
            ];
        });

        return view('galeria.index', compact('pacientes', 'medicos', 'procedimientos', 'hallazgos'));
    })->name('galeria');

    Route::get('/galeria/paciente/{id}', function ($id) {
        $paciente = Paciente::find($id);

        $archivos = \App\Models\EstudioArchivo::with('estudio')
            ->where('paciente_id', $id)
            ->orderByDesc('capturado_en')
            ->orderByDesc('id')
            ->get();

        $imagenes = $archivos->where('tipo', 'imagen')->values();
        $videos = $archivos->where('tipo', 'video')->values();

        return view('galeria.paciente', [
            'id' => $id,
            'paciente' => $paciente,
            'imagenes' => $imagenes,
            'videos' => $videos,
        ]);
    })->name('galeria.paciente');

    Route::get('/galeria/video/{id}', function ($id) {
        return view('galeria.vervideo', ['id' => $id]);
    })->name('galeria.video');

    Route::get('/galeria/video/{id}/editar', function ($id) {
        return view('galeria.editarvideo', ['id' => $id]);
    })->name('galeria.video.editar');

    Route::get('/galeria/imagen/{id}', function ($id) {
        $archivo = \App\Models\EstudioArchivo::with('estudio')->find($id);
        $paciente = $archivo ? Paciente::find($archivo->paciente_id) : null;

        $hermanas = collect();
        if ($archivo) {
            $hermanas = \App\Models\EstudioArchivo::where('tipo', 'imagen')
                ->when(
                    $archivo->estudio_id,
                    fn ($q) => $q->where('estudio_id', $archivo->estudio_id),
                    fn ($q) => $q->where('paciente_id', $archivo->paciente_id)
                )
                ->orderBy('capturado_en')
                ->orderBy('id')
                ->get();
        }

        $caps = $hermanas->values()->map(function ($a, $i) {
            return [
                'n' => $i + 1,
                'ts' => optional($a->capturado_en)->format('H:i:s') ?? '',
                'bg' => 'radial-gradient(ellipse at 50% 50%,#1a1208 0%,#0a0610 100%)',
                'src' => asset('storage/' . $a->path),
                'id' => $a->id,
            ];
        })->all();

        $current = $hermanas->values()->search(fn ($a) => (string) $a->id === (string) $id);
        if ($current === false) {
            $current = 0;
        }

        return view('galeria.verimagen', [
            'id' => $id,
            'archivo' => $archivo,
            'paciente' => $paciente,
            'caps' => $caps,
            'current' => $current,
        ]);
    })->name('galeria.imagen');

    Route::delete('/galeria/imagenes/{archivo}', [NuevoEstudioController::class, 'destroyImagenGaleria'])
        ->name('galeria.imagen.destroy');

    Route::post('/galeria/imagen/{id}/guardar', function ($id, \Illuminate\Http\Request $request) {
        $archivo = \App\Models\EstudioArchivo::findOrFail($id);

        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:51200'],
        ]);

        $file = $request->file('image');
        $oldPath = $archivo->path;
        $path = $file->store("estudios/{$archivo->estudio_id}/archivos", 'public');

        $archivo->update([
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'nombre_original' => $file->getClientOriginalName(),
            'nombre' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ]);

        if ($oldPath && $oldPath !== $path && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
        }

        return response()->json([
            'ok' => true,
            'archivo' => [
                'id' => $archivo->id,
                'url' => asset('storage/'.$archivo->path),
                'path' => $archivo->path,
            ],
        ]);
    })->name('galeria.imagen.guardar');

    Route::post('/galeria/imagen/{id}/guardar-copia', function ($id, \Illuminate\Http\Request $request) {
        $archivo = \App\Models\EstudioArchivo::findOrFail($id);

        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:51200'],
        ]);

        $file = $request->file('image');
        $path = $file->store("estudios/{$archivo->estudio_id}/archivos", 'public');
        $copy = \App\Models\EstudioArchivo::create([
            'estudio_id' => $archivo->estudio_id,
            'paciente_id' => $archivo->paciente_id,
            'tipo' => 'imagen',
            'categoria' => 'editada',
            'nombre_original' => $file->getClientOriginalName(),
            'nombre' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'descripcion' => 'Copia guardada por edicion',
            'capturado_en' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'archivo' => [
                'id' => $copy->id,
                'url' => asset('storage/'.$copy->path),
                'path' => $copy->path,
            ],
        ]);
    })->name('galeria.imagen.guardar-copia');

    /* ── Finanzas ── */
    // Route::get('/finanzas', function () {
    //     return view('finanzas.index');
    // })->name('finanzas');
});


Route::resource('pacientes', PacienteController::class);

Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
Route::get('/agendar', [AgendaController::class, 'create'])->name('agendar');

Route::post('/agenda/citas', [AgendaController::class, 'store'])->name('agenda.citas.store');
Route::put('/agenda/citas/{cita}', [AgendaController::class, 'update'])->name('agenda.citas.update');
Route::patch('/agenda/citas/{cita}/estado', [AgendaController::class, 'cambiarEstado'])->name('agenda.citas.estado');
Route::delete('/agenda/citas/{cita}', [AgendaController::class, 'destroy'])->name('agenda.citas.destroy');

Route::get('/finanzas', function () {
    return view('finanzas.index');
})->name('finanzas');


Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');