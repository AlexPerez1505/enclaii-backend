<?php

use App\Http\Controllers\Api\CustomerSuccess\AnuncioController;
use App\Http\Controllers\Api\CustomerSuccess\UserRoleController;
use Illuminate\Support\Facades\Route;

// Customer Success: gestión de anuncios y políticas
Route::middleware(['auth:sanctum', 'customer.success'])->prefix('customer-success')->group(function () {
    Route::apiResource('anuncios', AnuncioController::class)
        ->names([
            'index' => 'api.customer-success.anuncios.index',
            'store' => 'api.customer-success.anuncios.store',
            'show' => 'api.customer-success.anuncios.show',
            'update' => 'api.customer-success.anuncios.update',
            'destroy' => 'api.customer-success.anuncios.destroy',
        ]);

    Route::get('users', [UserRoleController::class, 'index'])->name('api.customer-success.users.index');
    Route::post('users/{user}/assign-role', [UserRoleController::class, 'assign'])->name('api.customer-success.users.assign-role');
    Route::post('users/{user}/remove-role', [UserRoleController::class, 'remove'])->name('api.customer-success.users.remove-role');
});
