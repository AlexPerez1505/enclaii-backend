<?php

use App\Http\Controllers\Api\CustomerSuccess\AnuncioController;
use App\Http\Controllers\Api\CustomerSuccess\NotificationController as CsNotificationController;
use App\Http\Controllers\Api\CustomerSuccess\UserRoleController;
use App\Http\Controllers\Api\TauriCaptureController;
use Illuminate\Support\Facades\Route;

Route::post('/tauri/pair/redeem', [TauriCaptureController::class, 'redeemCode']);

Route::middleware('auth:sanctum')->prefix('tauri')->group(function () {
    Route::post('/live-frame', [TauriCaptureController::class, 'liveFrame']);
    Route::post('/images', [TauriCaptureController::class, 'storeImage']);
    Route::post('/videos', [TauriCaptureController::class, 'storeVideo']);
    Route::post('/finish-session', [TauriCaptureController::class, 'finishSession']);
});
