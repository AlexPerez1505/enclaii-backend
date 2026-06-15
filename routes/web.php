<?php

use App\Http\Controllers\Auth\EndoCareAuthController;
use App\Http\Controllers\IaReporteController;
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
        return view('configuracion.index');
    })->name('configuracion');

    Route::post('/logout', [EndoCareAuthController::class, 'logout'])->name('logout');
});