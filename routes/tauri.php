<?php

use App\Http\Controllers\TauriFrontendController;
use Illuminate\Support\Facades\Route;

Route::prefix('tauri')->middleware('auth:sanctum')->controller(TauriFrontendController::class)->group(function () {
    Route::get('/dashboard', 'dashboard');
    Route::get('/dashboard/layout', 'dashboardLayout');
    Route::post('/dashboard/layout', 'updateDashboardLayout');
    Route::get('/estudio-activo', 'activeStudy');
    Route::post('/estudios/iniciar', 'startStudy');

    Route::get('/pacientes', 'patients');
    Route::delete('/pacientes/{paciente}', 'deletePatient');
    Route::post('/pacientes/eliminar', 'deletePatientByPayload');

    Route::get('/agenda', 'agenda');
    Route::get('/galeria', 'gallery');
    Route::get('/reportes', 'reports');

    Route::match(['get', 'post'], '/configuracion', 'settings');
    Route::post('/configuracion/plan/portal', 'planPortal');
    Route::get('/configuracion/plan/almacenamiento', 'planStorage');
    Route::get('/configuracion/plan/facturas', 'planInvoices');
    Route::post('/configuracion/plan/metodo-pago', 'planPaymentMethod');
    Route::get('/configuracion/plan/recomendaciones', 'planRecommendations');
    Route::post('/configuracion/plan/cambiar', 'changePlan');

    Route::get('/qr', 'qr');
    Route::post('/qr/enlaces', 'createQr');
    Route::post('/qr/preregistros/{preregistration}/{action}', 'reviewPreregistration')
        ->whereIn('action', ['aceptar', 'rechazar']);

    Route::post('/capturas', 'storeCapture');
});
