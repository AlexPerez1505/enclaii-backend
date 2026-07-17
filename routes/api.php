<?php

use App\Http\Controllers\Api\CustomerSuccess\AnuncioController;
use App\Http\Controllers\Api\CustomerSuccess\NotificationController as CsNotificationController;
use App\Http\Controllers\Api\CustomerSuccess\UserRoleController;
use App\Http\Controllers\Api\TauriAuthController;
use App\Http\Controllers\Api\TauriCaptureController;
use App\Http\Controllers\TauriFrontendController;
use Illuminate\Support\Facades\Route;

Route::post('/tauri/pair/redeem', [TauriCaptureController::class, 'redeemCode']);
Route::post('/tauri/login', [TauriAuthController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('tauri')->group(function () {
    Route::post('/estudios/iniciar', [TauriCaptureController::class, 'startSession']);
    Route::post('/live-frame', [TauriCaptureController::class, 'liveFrame']);
    Route::post('/images', [TauriCaptureController::class, 'storeImage']);
    Route::post('/videos', [TauriCaptureController::class, 'storeVideo']);
    Route::post('/finish-session', [TauriCaptureController::class, 'finishSession']);
    Route::post('/logout', [TauriAuthController::class, 'logout']);
    Route::get('/dashboard', [TauriFrontendController::class, 'dashboard']);
    Route::get('/pacientes', [TauriFrontendController::class, 'patients']);
    Route::get('/agenda', [TauriFrontendController::class, 'agenda']);
    Route::get('/reportes', [TauriFrontendController::class, 'reports']);
    Route::get('/galeria', [TauriFrontendController::class, 'gallery']);
});
