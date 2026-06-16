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

    Route::get('/nuevo-estudio', function () {
        return view('estudios.dashboard');
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
});