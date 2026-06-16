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

    Route::get('/agenda', function () {
        return view('agenda.index');
    })->name('agenda');

    Route::get('/agendar', function () {
        return view('agenda.agendar.index');
    })->name('agendar');

    Route::post('/logout', [EndoCareAuthController::class, 'logout'])->name('logout');
});