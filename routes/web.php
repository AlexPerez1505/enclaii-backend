<?php

use App\Http\Controllers\Auth\EndoCareAuthController;
use App\Http\Controllers\IaReporteController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

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

    Route::get('/agenda', function () {
        return view('agenda.index');
    })->name('agenda');

    Route::get('/agendar', function () {
        return view('agenda.agendar.index');
    })->name('agendar');

    Route::get('/mensajes', function () {
        return view('mensajes.dashboard');
    })->name('mensajes');

    Route::get('/nuevo-estudio', function () {
        return view('estudios.crear');
    })->name('nuevo-estudio');

    Route::get('/nuevo-estudio/crear', function () {
        return view('estudios.crear');
    })->name('nuevo-estudio.crear');

    Route::get('/nuevo-estudio/importar', function () {
        return view('estudios.importar');
    })->name('nuevo-estudio.importar');

    Route::get('/nuevo-estudio/capturas', function () {
        return view('estudios.capturas');
    })->name('nuevo-estudio.capturas');

    Route::get('/nuevo-estudio/configuracion', function () {
        return view('estudios.configuracion');
    })->name('nuevo-estudio.configuracion');

    Route::get('/nuevo-estudio/grabando', function () {
        return view('estudios.grabando');
    })->name('nuevo-estudio.grabando');

    Route::post('/logout', [EndoCareAuthController::class, 'logout'])->name('logout');

    /* ── Galería ── */
    Route::get('/galeria', function () {
        return view('galeria.index');
    })->name('galeria');

    Route::get('/galeria/paciente/{id}', function ($id) {
        return view('galeria.paciente', ['id' => $id]);
    })->name('galeria.paciente');

    Route::get('/galeria/video/{id}', function ($id) {
        return view('galeria.vervideo', ['id' => $id]);
    })->name('galeria.video');

    Route::get('/galeria/video/{id}/editar', function ($id) {
        return view('galeria.editarvideo', ['id' => $id]);
    })->name('galeria.video.editar');

    Route::get('/galeria/imagen/{id}', function ($id) {
        return view('galeria.verimagen', ['id' => $id]);
    })->name('galeria.imagen');
});

use App\Http\Controllers\PacienteController;

Route::resource('pacientes', PacienteController::class);