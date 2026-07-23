<?php

use App\Http\Controllers\Api\CustomerSuccess\AnuncioController;
use App\Http\Controllers\Api\CustomerSuccess\NotificationController as CsNotificationController;
use App\Http\Controllers\Api\CustomerSuccess\UserRoleController;
use App\Http\Controllers\Api\TauriAuthController;
use App\Http\Controllers\Api\TauriCaptureController;
use App\Http\Controllers\Api\TauriConfigurationController;
use App\Http\Controllers\Api\TauriPatientController;
use App\Http\Controllers\ConfigurationBackupController;
use App\Http\Controllers\IaReporteController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\TauriFrontendController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas de Tauri
|--------------------------------------------------------------------------
*/

Route::post(
    '/tauri/pair/redeem',
    [TauriCaptureController::class, 'redeemCode']
);

Route::post(
    '/tauri/login',
    [TauriAuthController::class, 'login']
);

/*
|--------------------------------------------------------------------------
| Rutas protegidas de Tauri
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')
    ->prefix('tauri')
    ->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Captura y sesiones
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/estudios/iniciar',
            [TauriCaptureController::class, 'startSession']
        );

        Route::post(
            '/live-frame',
            [TauriCaptureController::class, 'liveFrame']
        );

        Route::post(
            '/images',
            [TauriCaptureController::class, 'storeImage']
        );

        Route::post(
            '/videos',
            [TauriCaptureController::class, 'storeVideo']
        );

        Route::post(
            '/finish-session',
            [TauriCaptureController::class, 'finishSession']
        );

        Route::post(
            '/logout',
            [TauriAuthController::class, 'logout']
        );

        /*
        |--------------------------------------------------------------------------
        | Dashboard y módulos generales
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [TauriFrontendController::class, 'dashboard']
        );

        Route::get(
            '/agenda',
            [TauriFrontendController::class, 'agenda']
        );

        Route::post(
            '/agenda/citas',
            [TauriFrontendController::class, 'storeAppointment']
        );

        Route::post(
            '/agenda/bloqueos',
            [TauriFrontendController::class, 'storeBloqueo']
        );

        Route::delete(
            '/agenda/bloqueos/{bloqueo}',
            [TauriFrontendController::class, 'destroyBloqueo']
        );

        Route::get(
            '/reportes',
            [TauriFrontendController::class, 'reports']
        );

        Route::prefix('reportes')
            ->group(function () {
                Route::get(
                    '/editor',
                    [IaReporteController::class, 'apiPreload']
                );

                Route::get(
                    '/plantillas',
                    [IaReporteController::class, 'apiPlantillas']
                );

                Route::get(
                    '/estudios-sin-reporte',
                    [IaReporteController::class, 'apiEstudiosSinReporte']
                );

                Route::get(
                    '/todos',
                    [IaReporteController::class, 'apiReportesTodos']
                );

                Route::post(
                    '/generar',
                    [IaReporteController::class, 'generar']
                );

                Route::post(
                    '/chat',
                    [IaReporteController::class, 'chat']
                );

                Route::post(
                    '/guardar',
                    [IaReporteController::class, 'guardar']
                );

                Route::get(
                    '/{reporte}',
                    [IaReporteController::class, 'apiVer']
                );
            });

        Route::get(
            '/galeria',
            [TauriFrontendController::class, 'gallery']
        );

        /*
        |--------------------------------------------------------------------------
        | Pacientes
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        | /create debe estar antes de /{paciente}.
        |
        */

        Route::prefix('pacientes')
            ->group(function () {
                Route::get(
                    '/suggestions',
                    [PacienteController::class, 'suggestions']
                );

                Route::get(
                    '/',
                    [TauriPatientController::class, 'index']
                );

                Route::get(
                    '/create',
                    [TauriPatientController::class, 'create']
                );

                Route::post(
                    '/',
                    [TauriPatientController::class, 'store']
                );

                Route::get(
                    '/{paciente}/edit',
                    [TauriPatientController::class, 'edit']
                );

                Route::get(
                    '/{paciente}',
                    [TauriPatientController::class, 'show']
                );

                Route::match(
                    ['put', 'patch'],
                    '/{paciente}',
                    [TauriPatientController::class, 'update']
                );

                Route::patch(
                    '/{paciente}/campo',
                    [TauriPatientController::class, 'updateField']
                );

                Route::delete(
                    '/{paciente}/foto',
                    [TauriPatientController::class, 'destroyPhoto']
                );

                Route::delete(
                    '/{paciente}/documentos/{documento}',
                    [TauriPatientController::class, 'destroyDocument']
                );

                Route::delete(
                    '/{paciente}',
                    [TauriPatientController::class, 'destroy']
                );
            });

        /*
        |--------------------------------------------------------------------------
        | Configuración
        |--------------------------------------------------------------------------
        */

        Route::prefix('configuracion')
            ->group(function () {
                Route::get(
                    '/',
                    [TauriConfigurationController::class, 'show']
                );

                Route::patch(
                    '/',
                    [TauriConfigurationController::class, 'update']
                );

                Route::patch(
                    '/perfil',
                    [TauriConfigurationController::class, 'updateProfile']
                );

                Route::post(
                    '/foto',
                    [TauriConfigurationController::class, 'updatePhoto']
                );

                Route::delete(
                    '/foto',
                    [TauriConfigurationController::class, 'deletePhoto']
                );

                Route::post(
                    '/constancia-fiscal',
                    [TauriConfigurationController::class, 'storeTaxDocument']
                );

                Route::delete(
                    '/constancia-fiscal',
                    [TauriConfigurationController::class, 'deleteTaxDocument']
                );

                Route::patch(
                    '/password',
                    [TauriConfigurationController::class, 'updatePassword']
                );

                Route::delete(
                    '/miembros/{member}',
                    [TauriConfigurationController::class, 'removeMember']
                );

                Route::delete(
                    '/invitaciones/{invitation}',
                    [TauriConfigurationController::class, 'revokeInvitation']
                );

                /*
                |--------------------------------------------------------------------------
                | Copias de configuración
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/copias',
                    [ConfigurationBackupController::class, 'store']
                );

                Route::post(
                    '/copias/{backup}/restaurar',
                    [ConfigurationBackupController::class, 'restore']
                );

                Route::get(
                    '/copias/{backup}/descargar',
                    [ConfigurationBackupController::class, 'download']
                );

                Route::delete(
                    '/copias/{backup}',
                    [ConfigurationBackupController::class, 'destroy']
                );
            });

        /*
        |--------------------------------------------------------------------------
        | Customer Success
        |--------------------------------------------------------------------------
        |
        | Conserva estas rutas solo si realmente ya las utilizas.
        |
        */

        Route::prefix('customer-success')
            ->group(function () {
                Route::get(
                    '/anuncios',
                    [AnuncioController::class, 'index']
                );

                Route::get(
                    '/notificaciones',
                    [CsNotificationController::class, 'index']
                );

                Route::get(
                    '/roles',
                    [UserRoleController::class, 'index']
                );
            });
    });