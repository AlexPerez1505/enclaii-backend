<?php

use App\Http\Controllers\Auth\EndoCareAuthController;
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

    Route::post('/logout', [EndoCareAuthController::class, 'logout'])->name('logout');

    /* ── Galería ── */
    Route::get('/galeria', function () {
        return view('galeria.index');
    })->name('galeria');

    Route::get('/galeria/video/{id}', function ($id) {
        return view('galeria.vervideo', ['id' => $id]);
    })->name('galeria.video');

    Route::get('/galeria/video/{id}/editar', function ($id) {
        return view('galeria.editarvideo', ['id' => $id]);
    })->name('galeria.video.editar');

    Route::get('/galeria/imagen/{id}', function ($id) {
        return view('galeria.verimagen', ['id' => $id]);
    })->name('galeria.imagen');

    /* ── Informes ── */
    Route::get('/informes', function () {
        return view('informes.index');
    })->name('informes');

    Route::get('/informes/nuevo', function () {
        return view('informes.nuevo');
    })->name('informes.nuevo');
});