<?php

use App\Http\Controllers\Auth\EndoCareAuthController;
use App\Http\Controllers\IaReporteController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\NuevoEstudioController;
use App\Models\Paciente;

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
        return view('dashboard');
    })->name('dashboard');
    


    Route::get('/ia-reportes', function () {
        return view('ia-reportes.index');
    })->name('ia-reportes');

    Route::get('/ia-reportes/generar', function () {
        return view('ia-reportes.generar');
    })->name('ia-reportes.generar');

    Route::get('/ia-reportes/redactar', function () {
        return view('ia-reportes.redactar');
    })->name('ia-reportes.redactar');

    Route::post('/ia-reportes/generar', [IaReporteController::class, 'generar'])
        ->name('ia-reportes.generar.post');

    Route::post('/ia-reportes/chat', [IaReporteController::class, 'chat'])
        ->name('ia-reportes.chat.post');

    Route::get('/ia-reportes/hallazgos', function () {
        return view('ia-reportes.hallazgos');
    })->name('ia-reportes.hallazgos');

    Route::get('/ia-reportes/reportes', function () {
        return view('ia-reportes.reportes');
    })->name('ia-reportes.todos');

    Route::get('/ia-reportes/editar', function () {
        return view('ia-reportes.editar');
    })->name('ia-reportes.editar');

    Route::get('/ia-reportes/ver', function () {
        return view('ia-reportes.ver');
    })->name('ia-reportes.ver');

    Route::get('/ia-reportes/analisis', function () {
        return view('ia-reportes.analisis');
    })->name('ia-reportes.analisis');
    
    Route::get('/configuracion', function () {
        return view('configuracion.index', [
            'userSettings' => request()->user()->resolvedSettings(),
        ]);
    })->name('configuracion');

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

        $galImagenes = collect();
        $galVideos = collect();

        if ($paciente) {
            $archivos = \App\Models\EstudioArchivo::with('estudio')
                ->where('paciente_id', $paciente->id)
                ->orderByDesc('capturado_en')
                ->orderByDesc('id')
                ->get();

            $galImagenes = $archivos->where('tipo', 'imagen')->values();
            $galVideos = $archivos->where('tipo', 'video')->values();
        }

        return view('estudios.crear', [
            'paciente' => $paciente,
            'galImagenes' => $galImagenes,
            'galVideos' => $galVideos,
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

        $pacientes = Paciente::orderBy('nombre_completo')->get()->values()->map(function ($p, $i) use ($colores) {
            $base = \App\Models\EstudioArchivo::where('paciente_id', $p->id);
            $fotos = (clone $base)->where('tipo', 'imagen')->count();
            $videos = (clone $base)->where('tipo', 'video')->count();
            $estudios = \App\Models\Estudio::where('paciente_id', $p->id)->count();
            $ultimoTs = (clone $base)->max('capturado_en');
            $ultimo = $ultimoTs ? \Illuminate\Support\Carbon::parse($ultimoTs)->format('d/m/Y') : '—';
            $ini = collect(explode(' ', $p->nombre_completo ?? ''))
                ->filter()->take(2)
                ->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))
                ->implode('') ?: 'PX';

            return [
                'id' => $p->id,
                'nombre' => $p->nombre_completo ?? 'Paciente',
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
            ];
        });

        return view('galeria.index', compact('pacientes'));
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

    /* ── Finanzas ── */
    Route::get('/finanzas', function () {
        return view('finanzas.index');
    })->name('finanzas');
});


Route::resource('pacientes', PacienteController::class);

Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
Route::get('/agendar', [AgendaController::class, 'create'])->name('agendar');

Route::post('/agenda/citas', [AgendaController::class, 'store'])->name('agenda.citas.store');
Route::put('/agenda/citas/{cita}', [AgendaController::class, 'update'])->name('agenda.citas.update');
Route::patch('/agenda/citas/{cita}/estado', [AgendaController::class, 'cambiarEstado'])->name('agenda.citas.estado');
Route::delete('/agenda/citas/{cita}', [AgendaController::class, 'destroy'])->name('agenda.citas.destroy');
