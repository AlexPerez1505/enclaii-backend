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
    
    Route::get('/pacientes', function () {
        return view('pacientes.index');
    })->name('pacientes');

    Route::get('/pacientes/nuevo', function () {
        return view('pacientes.create');
    })->name('pacientes.create');

    Route::get('/pacientes/editar', function () {
        $paciente = (object) ['folio' => 'P-125'];
        return view('pacientes.edit', compact('paciente'));
    })->name('pacientes.edit');

    Route::get('/pacientes/estudios', function () {
        return view('pacientes.estudios');
    })->name('pacientes.estudios');

    Route::get('/mensajes', function () {
        return view('mensajes.dashboard');
    })->name('mensajes');

    Route::get('/mensajes/correo', function () {
        return view('mensajes.dashboard');
    })->name('mensajes.correo');

    Route::get('/agenda', function () {
        return view('agenda.index');
    })->name('agenda');

    Route::post('/logout', [EndoCareAuthController::class, 'logout'])->name('logout');
});