<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\Auth\EndoCareAuthController;
use App\Http\Controllers\CapturePairingCodeController;
use App\Http\Controllers\ClinicaMemberController;
use App\Http\Controllers\ConfigurationBackupController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\CriticalSecurityController;
use App\Http\Controllers\CustomerSuccess\AnuncioDashboardController;
use App\Http\Controllers\CustomerSuccess\DashboardController;
use App\Http\Controllers\CustomerSuccess\RolesController;
use App\Http\Controllers\CustomerSuccess\TicketController as CsTicketController;
use App\Http\Controllers\CustomerSuccessController;
use App\Http\Controllers\DesktopAppDownloadController;
use App\Http\Controllers\IaReporteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NuevoEstudioController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PublicPatientPreregistrationController;
use App\Http\Controllers\QrRegistrationController;
use App\Http\Controllers\SecuritySettingsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SoporteChatController;
use App\Http\Controllers\SoporteController;
use App\Http\Controllers\StorageServeController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserSessionController;
use App\Http\Controllers\WhatsAppController;
use App\Models\Paciente;
use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorController;

Route::get('/storage/{path}', [StorageServeController::class, 'show'])
    ->where('path', '.*')
    ->name('storage.fallback');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/webhooks/whatsapp', [WhatsAppController::class, 'verifyWebhook'])
    ->name('webhooks.whatsapp.verify');
Route::post('/webhooks/whatsapp', [WhatsAppController::class, 'webhook'])
    ->middleware('throttle:120,1')
    ->name('webhooks.whatsapp.receive');

// Webhook de Stripe (ruta publica, sin CSRF: ver bootstrap/app.php)
Route::post('/webhooks/stripe', [StripeController::class, 'webhook'])
    ->name('webhooks.stripe');

// Cron endpoint para cron-job.org (protegido por header X-Cron-Token)
Route::get('/cron/notificaciones', [CronController::class, 'run'])
    ->name('cron.notificaciones');
Route::get('/cron/anuncios', [CronController::class, 'runAnuncios'])
    ->name('cron.anuncios');

Route::get('/registro-paciente/completado', [PublicPatientPreregistrationController::class, 'success'])
    ->name('qr.public.success');
Route::get('/registro-paciente/expirado', [PublicPatientPreregistrationController::class, 'expired'])
    ->name('qr.public.expired');
Route::get('/registro-paciente/{token}', [PublicPatientPreregistrationController::class, 'show'])
    ->where('token', '[A-Za-z0-9]{64}')
    ->name('qr.public.show');
Route::post('/registro-paciente/{token}', [PublicPatientPreregistrationController::class, 'store'])
    ->where('token', '[A-Za-z0-9]{64}')
    ->middleware('throttle:5,1')
    ->name('qr.public.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [EndoCareAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [EndoCareAuthController::class, 'login'])->name('login.post');

    Route::get('/registro', [EndoCareAuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [EndoCareAuthController::class, 'register'])->name('register.post');
});

Route::middleware(['auth', 'auth.session', 'session.limit', 'subscribed'])->group(function () {

    // Ruta de configuracion: si no tiene plan, muestra vista plan-only
    Route::get('/configuracion', function () {
        if (!auth()->user()->subscribed()) {
            return view('configuracion.plan-only');
        }

        $userAgent = request()->userAgent() ?? '';
        $showDesktopAppSettings = ! preg_match('/Android|iPhone|iPad|iPod/i', $userAgent);

        return view('configuracion.index', [
            'billingUser' => request()->user()->billingUser(),
            'clinicMembers' => request()->user()->clinica
                ?->usuarios()
                ?->withMax('connectedSessions', 'last_activity')
                ?->orderByRaw("CASE WHEN clinica_rol = 'propietario' THEN 0 ELSE 1 END")
                ?->orderBy('name')
                ?->get() ?? collect(),
            'clinicInvitations' => request()->user()->clinica
                ?->invitations()
                ?->whereNull('accepted_at')
                ?->whereNull('revoked_at')
                ?->where('expires_at', '>', now())
                ?->latest()
                ?->get() ?? collect(),
            'clinicMemberLimit' => request()->user()->clinicMemberLimit(),
            'userSettings' => request()->user()->resolvedSettings(),
            'securitySettings' => request()->user()->securityPreferences(),
            'configurationBackups' => request()->user()
                ->configurationBackups()
                ->latest()
                ->limit(10)
                ->get(),
            'activityLogs' => request()->user()
                ->activityLogs()
                ->with('user')
                ->when(request('activity_search'), function ($query, $search) {
                    $query->where(function ($filter) use ($search) {
                        $filter
                            ->where('description', 'like', '%'.$search.'%')
                            ->orWhere('category', 'like', '%'.$search.'%')
                            ->orWhere('action', 'like', '%'.$search.'%')
                            ->orWhere('ip_address', 'like', '%'.$search.'%');
                    });
                })
                ->latest()
                ->paginate(8, ['*'], 'activity_page')
                ->withQueryString(),
            'connectedSessions' => request()->user()
                ->connectedSessions()
                ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
                ->orderByDesc('last_activity')
                ->get(),
            'currentSessionId' => request()->session()->getId(),
            'procedimientos' => \App\Models\Procedimiento::orderBy('nombre')->get(),
            'anestesiologos' => \App\Models\Anestesiologo::query()
                ->where('clinica_id', request()->user()->clinica_id)
                ->orderBy('apellido_paterno')
                ->orderBy('nombres')
                ->get(),
            'salas' => \App\Models\Sala::query()
                ->where('clinica_id', request()->user()->clinica_id)
                ->where('activa', true)
                ->orderBy('nombre')
                ->get(),
            'sessionLimit' => app(\App\Services\SessionLimitService::class)->limitFor(request()->user()),
            'showDesktopAppSettings' => $showDesktopAppSettings,
        ]);
    })->name('configuracion');

    // Ruta dedicada para seleccionar plan (sin sidebar ni header)
    Route::get('/seleccionar-plan', function () {
        return view('configuracion.plan-only');
    })->name('plan.only');

    Route::get('/descargas/enclaii-desktop/windows', DesktopAppDownloadController::class)
        ->name('desktop-app.download');

    Route::patch('/configuracion/general', [SettingsController::class, 'update'])
        ->name('configuracion.general.update');

    Route::patch('/configuracion/perfil', [SettingsController::class, 'updatePerfil'])
        ->name('configuracion.perfil.update');
    Route::post('/configuracion/foto', [SettingsController::class, 'updateFoto'])
        ->name('configuracion.foto.update');
    Route::delete('/configuracion/foto', [SettingsController::class, 'deleteFoto'])
        ->name('configuracion.foto.delete');
    Route::post('/configuracion/constancia', [SettingsController::class, 'uploadConstancia'])
        ->name('configuracion.constancia.upload');
    Route::delete('/configuracion/constancia', [SettingsController::class, 'deleteConstancia'])
        ->name('configuracion.constancia.delete');

    // ===== Aceptaciones legales =====
    Route::post('/legal/acceptances', [SettingsController::class, 'storeLegalAcceptances'])
        ->name('legal.acceptances.store');
    Route::post('/configuracion/copias', [ConfigurationBackupController::class, 'store'])
        ->name('configuracion.backups.store');
    Route::post('/configuracion/copias/{backup}/restaurar', [ConfigurationBackupController::class, 'restore'])
        ->name('configuracion.backups.restore');
    Route::get('/configuracion/copias/{backup}/descargar', [ConfigurationBackupController::class, 'download'])
        ->name('configuracion.backups.download');
    Route::delete('/configuracion/copias/{backup}', [ConfigurationBackupController::class, 'destroy'])
        ->name('configuracion.backups.destroy');

    Route::get('/configuracion/firma', [SignatureController::class, 'show'])
        ->name('configuracion.signature.show');
    Route::post('/configuracion/firma', [SignatureController::class, 'store'])
        ->name('configuracion.signature.store');
    Route::delete('/configuracion/firma', [SignatureController::class, 'destroy'])
        ->name('configuracion.signature.destroy');

    Route::patch('/configuracion/seguridad/contrasena', [PasswordController::class, 'update'])
        ->name('configuracion.password.update');
    Route::post('/configuracion/seguridad/autorizar', [CriticalSecurityController::class, 'authorizeAction'])
        ->middleware('throttle:6,1')
        ->name('configuracion.security.authorize');
    Route::patch('/configuracion/seguridad/permisos', [SecuritySettingsController::class, 'update'])
        ->middleware('critical.password:security_settings')
        ->name('configuracion.security-settings.update');

    // ===== Autenticación de Dos Factores (2FA Email) =====
    Route::post('/configuracion/seguridad/2fa/enviar', [TwoFactorController::class, 'enable'])
        ->name('configuracion.2fa.send');
    Route::post('/configuracion/seguridad/2fa/confirmar', [TwoFactorController::class, 'confirm'])
        ->name('configuracion.2fa.confirm');
    Route::delete('/configuracion/seguridad/2fa', [TwoFactorController::class, 'disable'])
        ->name('configuracion.2fa.disable');

    Route::middleware('clinic.owner')->group(function () {
        Route::post('/configuracion/clinica/invitaciones', [ClinicaMemberController::class, 'storeInvitation'])
            ->name('configuracion.clinic-invitations.store');
        Route::delete('/configuracion/clinica/invitaciones/{invitation}', [ClinicaMemberController::class, 'destroyInvitation'])
            ->name('configuracion.clinic-invitations.destroy');
        Route::delete('/configuracion/clinica/integrantes/{member}', [ClinicaMemberController::class, 'destroyMember'])
            ->name('configuracion.clinic-members.destroy');
    });

    Route::delete('/configuracion/seguridad/sesiones/otras', [UserSessionController::class, 'destroyOthers'])
        ->name('configuracion.sessions.destroy-others');
    Route::delete('/configuracion/seguridad/sesiones/{session}', [UserSessionController::class, 'destroy'])
        ->name('configuracion.sessions.destroy');

    // ===== Stripe (pagos y suscripciones) =====
    Route::post('/stripe/checkout', [StripeController::class, 'checkout'])
        ->name('stripe.checkout');
    Route::post('/stripe/checkout-embedded', [StripeController::class, 'checkoutEmbedded'])
        ->name('stripe.checkout.embedded');
    Route::post('/stripe/subscribe', [StripeController::class, 'createSubscriptionElement'])
        ->name('stripe.subscribe');
    Route::post('/stripe/change-plan', [StripeController::class, 'changePlan'])
        ->name('stripe.change.plan');
    Route::post('/stripe/member-addon/checkout', [StripeController::class, 'memberAddonCheckout'])
        ->middleware('clinic.owner')
        ->name('stripe.member-addon.checkout');
    Route::get('/stripe/invoices', [StripeController::class, 'invoices'])
        ->name('stripe.invoices');
    Route::post('/stripe/cancel-subscription', [StripeController::class, 'cancelSubscription'])
        ->name('stripe.cancel.subscription');
    Route::post('/stripe/resume-subscription', [StripeController::class, 'resumeSubscription'])
        ->name('stripe.resume.subscription');
    Route::get('/stripe/setup-intent', [StripeController::class, 'setupIntent'])
        ->name('stripe.setup.intent');
    Route::post('/stripe/payment-method', [StripeController::class, 'updatePaymentMethod'])
        ->name('stripe.payment.method');
    Route::get('/stripe/success', [StripeController::class, 'success'])
        ->name('stripe.success');
    Route::get('/stripe/cancel', [StripeController::class, 'cancel'])
        ->name('stripe.cancel');

    // ===== Notificaciones =====
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::post('/logout', [EndoCareAuthController::class, 'logout'])->name('logout');
    

});

// ===== 2FA challenge (sin auth) =====
Route::get('/dos-pasos', [TwoFactorController::class, 'challenge'])->name('2fa.challenge');
Route::post('/dos-pasos', [TwoFactorController::class, 'verifyChallenge'])->name('2fa.verify');
Route::post('/dos-pasos/reenviar', [TwoFactorController::class, 'resend'])->name('2fa.resend');

Route::middleware(['auth', 'auth.session', 'session.limit', 'subscribed'])->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/widget/{widget}', function ($widget) {
        $allowed = ['agenda-today', 'agenda-summary'];
        if (!in_array($widget, $allowed)) abort(404);

        $widgetMes = (int) request()->query('widget_mes', now()->month);
        $widgetAnio = (int) request()->query('widget_anio', now()->year);
        $inicioMes = \Carbon\Carbon::create($widgetAnio, $widgetMes, 1)->startOfDay();
        $finMes = $inicioMes->copy()->endOfMonth();

        $citasProximasMes = \App\Models\Cita::where('estado', 'proximo')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();
        $citasCompletadasMes = \App\Models\Cita::where('estado', 'completado')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();
        $citasCanceladasMes = \App\Models\Cita::where('estado', 'cancelado')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();

        $pendientesMes = \App\Models\Cita::with('paciente')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->whereNotIn('estado', ['completado', 'cancelado'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('dashboard.widgets.' . $widget . '.index', compact(
            'citasProximasMes', 'citasCompletadasMes', 'citasCanceladasMes',
            'pendientesMes', 'widgetMes', 'widgetAnio'
        ));
    })->name('dashboard.widget');


    Route::get('/ia-reportes', [IaReporteController::class, 'index'])->name('ia-reportes');

    Route::get('/ia-reportes/generar', function () {
        $estudioId = request()->query('estudio');
        $pacienteId = request()->query('paciente');

        $estudio = $estudioId
            ? \App\Models\Estudio::with('paciente')->find($estudioId)
            : null;

        $paciente = $estudio?->paciente
            ?? ($pacienteId ? Paciente::find($pacienteId) : null);

        // Si llega paciente sin estudio, usar su estudio más reciente
        if ($paciente && ! $estudio) {
            $estudio = \App\Models\Estudio::where('paciente_id', $paciente->id)
                ->latest()
                ->first();
        }

        // Evidencia: fotos reales del estudio
        $evidencias = collect();
        if ($estudio) {
            $evidencias = \App\Models\EstudioArchivo::where('estudio_id', $estudio->id)
                ->where('tipo', 'imagen')
                ->orderByDesc('capturado_en')
                ->orderByDesc('id')
                ->get()
                ->map(function ($a) {
                    $existsOnMediaDisk = media_exists($a->path);
                    $existsOnPublicDisk = ! $existsOnMediaDisk
                        && \Illuminate\Support\Facades\Storage::disk('public')->exists($a->path);

                    if (! $existsOnMediaDisk && ! $existsOnPublicDisk) {
                        // El archivo ya no existe en ningún disco: es un registro huérfano.
                        $a->delete();

                        return null;
                    }

                    $version = '?v='.($a->updated_at?->timestamp ?? $a->id);

                    return $existsOnPublicDisk
                        ? url('storage/'.$a->path).$version
                        : media_url($a->path).$version;
                })
                ->filter()
                ->values();
        }

        // Datos para precargar el formulario
        $datos = [
            'paciente' => $paciente?->nombre_completo ?? ($estudio?->paciente_nombre ?? ''),
            'iniciales' => collect(explode(' ', $paciente?->nombre_completo ?? 'NA'))
                ->filter()->take(2)->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))->implode('') ?: 'NA',
            'edad' => $paciente?->edad ? $paciente->edad.' años' : '',
            'sexo' => $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '',
            'folio' => $paciente?->folio ?? '',
            'identificacion' => $paciente?->identificacion ?? '',
            'medico' => $estudio?->medico ?? $paciente?->medico ?? '',
            'nacimiento' => optional($paciente?->fecha_nacimiento)->format('d/m/Y') ?? '',
            'tipo' => $estudio?->tipo ?? $paciente?->procedimiento ?? '',
            'fecha' => optional($estudio?->fecha)->format('Y-m-d') ?? now()->format('Y-m-d'),
            'observaciones' => $paciente?->diagnostico_preliminar ?? '',
            'estudio_id' => $estudio?->id,
        ];

        // Lista de estudios para el selector: solo los que NO tienen reporte aún
        // (incluye el estudio actual aunque ya tuviera, para que la opción seleccionada aparezca).
        $estudiosLista = \App\Models\Estudio::with('paciente')
            ->where(function ($q) use ($estudio) {
                $q->whereDoesntHave('reportes');
                if ($estudio) {
                    $q->orWhere('id', $estudio->id);
                }
            })
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'label' => trim(($e->paciente?->nombre_completo ?? $e->paciente_nombre ?? 'Paciente')
                    .' · '.($e->tipo ?? 'Estudio')
                    .' · '.(optional($e->fecha)->format('d/m/Y') ?? '')),
            ])
            ->values();

        return view('ia-reportes.generar', [
            'estudio' => $estudio,
            'paciente' => $paciente,
            'evidencias' => $evidencias,
            'datos' => $datos,
            'estudiosLista' => $estudiosLista,
            'userSettings' => request()->user()->resolvedSettings(),
        ]);
    })->name('ia-reportes.generar');

    Route::get('/ia-reportes/redactar', function () {
        $pacienteId = request()->query('paciente');
        $estudioId = request()->query('estudio');
        $reporteId = request()->query('reporte');

        $reporte = $reporteId ? \App\Models\Reporte::with(['estudio.paciente', 'usuario'])->find($reporteId) : null;

        $paciente = $pacienteId ? Paciente::find($pacienteId) : null;

        $estudio = $estudioId
            ? \App\Models\Estudio::with('paciente')->find($estudioId)
            : null;

        // Si viene reporte, derivar estudio y paciente de él
        if ($reporte && ! $estudio) {
            $estudio = $reporte->estudio;
        }
        if ($reporte && ! $paciente) {
            $paciente = $reporte->estudio?->paciente;
        }

        // Si no llegó paciente explícito, derivarlo del estudio
        if (! $paciente && $estudio) {
            $paciente = $estudio->paciente;
        }

        // Si hay paciente pero no estudio, usar su estudio más reciente
        if ($paciente && ! $estudio) {
            $estudio = \App\Models\Estudio::where('paciente_id', $paciente->id)
                ->latest()
                ->first();
        }

        $estudioImagenes = collect();
        if ($paciente) {
            $estudioImagenes = \App\Models\EstudioArchivo::where('paciente_id', $paciente->id)
                ->where('tipo', 'imagen')
                ->when($estudio, fn ($q) => $q->where('estudio_id', $estudio->id))
                ->orderByDesc('capturado_en')
                ->orderByDesc('id')
                ->get()
                ->map(function ($a) {
                    $existsOnMediaDisk = media_exists($a->path);
                    $existsOnPublicDisk = ! $existsOnMediaDisk
                        && \Illuminate\Support\Facades\Storage::disk('public')->exists($a->path);

                    if (! $existsOnMediaDisk && ! $existsOnPublicDisk) {
                        // El archivo ya no existe en ningún disco: es un registro huérfano.
                        // Se elimina de nuestros registros para que no vuelva a intentarse mostrar.
                        $a->delete();

                        return null;
                    }

                    $url = $existsOnPublicDisk ? url('storage/'.$a->path) : media_url($a->path);

                    return [
                        'id' => $a->id,
                        'url' => $url,
                        'titulo' => $a->nombre_original,
                        'show_url' => route('galeria.imagen', ['id' => $a->id, 'paciente' => $a->paciente_id]),
                    ];
                })
                ->filter()
                ->values();
        }

        // Datos del estudio/paciente para precargar el editor
        $datosEstudio = [
            'paciente' => $paciente?->nombre_completo ?? ($estudio?->paciente_nombre ?? ''),
            'edad' => $paciente?->edad ? $paciente->edad.' años' : '',
            'sexo' => $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '',
            'nacimiento' => optional($paciente?->fecha_nacimiento)->format('d/m/Y') ?? '',
            'fecha_estudio' => optional($estudio?->fecha)->format('d/m/Y') ?? now()->format('d/m/Y'),
            'procedimiento' => $estudio?->tipo ?? $paciente?->procedimiento ?? '',
            'tipo' => $estudio?->tipo ?? $paciente?->procedimiento ?? '',
            'medico' => $estudio?->medico ?? $paciente?->medico ?? '',
        ];

        // Plantillas guardadas (configuración persistida por clave)
        $plantillasDb = \App\Models\Plantilla::all()->mapWithKeys(fn ($p) => [
            $p->clave => [
                'id' => $p->id,
                'titulo' => $p->titulo,
                'subtitulo' => $p->subtitulo,
                'configuracion' => $p->configuracion,
                'columnas' => $p->columnas,
                'num_imagenes' => $p->num_imagenes,
            ],
        ]);

        // Selector de estudio: solo los que NO tienen reporte aún
        // (incluye el estudio actual para que la opción seleccionada aparezca).
        $estudiosLista = \App\Models\Estudio::with('paciente')
            ->where(function ($q) use ($estudio) {
                $q->whereDoesntHave('reportes');
                if ($estudio) {
                    $q->orWhere('id', $estudio->id);
                }
            })
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'label' => trim(($e->paciente?->nombre_completo ?? $e->paciente_nombre ?? 'Paciente')
                    .' · '.($e->tipo ?? 'Estudio')
                    .' · '.(optional($e->fecha)->format('d/m/Y') ?? '')),
            ])
            ->values();

        return view('ia-reportes.redactar', [
            'paciente' => $paciente,
            'estudio' => $estudio,
            'reporte' => $reporte,
            'estudioImagenes' => $estudioImagenes,
            'datosEstudio' => $datosEstudio,
            'plantillasDb' => $plantillasDb,
            'estudiosLista' => $estudiosLista,
        ]);
    })->name('ia-reportes.redactar');

    Route::post('/ia-reportes/generar', [IaReporteController::class, 'generar'])
        ->name('ia-reportes.generar.post');

    Route::post('/ia-reportes/guardar', [IaReporteController::class, 'guardar'])
        ->name('ia-reportes.guardar');

    Route::get('/ia-reportes/hallazgos-lista', [IaReporteController::class, 'listarHallazgos'])
        ->name('ia-reportes.hallazgos-lista');

    Route::post('/ia-reportes/hallazgos-crear', [IaReporteController::class, 'crearHallazgo'])
        ->name('ia-reportes.hallazgos-crear');

    Route::post('/plantillas/{clave}', [\App\Http\Controllers\PlantillaController::class, 'update'])
        ->name('plantillas.update');

    Route::post('/ia-reportes/chat', [IaReporteController::class, 'chat'])
        ->name('ia-reportes.chat.post');

    Route::get('/ia-reportes/hallazgos', [IaReporteController::class, 'hallazgos'])
        ->name('ia-reportes.hallazgos');

    Route::get('/ia-reportes/reportes', function () {
        $reportes = Reporte::with(['estudio.paciente', 'usuario'])
            ->latest()
            ->get();

        return view('ia-reportes.reportes', compact('reportes'));
    })->name('ia-reportes.todos');

    Route::get('/ia-reportes/editar', function () {
        $reporteId = request()->query('reporte');
        if ($reporteId) {
            return redirect()->route('ia-reportes.redactar', [
                'reporte' => $reporteId,
                'estudio' => request()->query('estudio'),
            ]);
        }
        return view('ia-reportes.editar');
    })->name('ia-reportes.editar');

    Route::get('/ia-reportes/ver', function () {
        $reporte = null;
        $estudioImagenes = collect();
        if (request()->has('reporte')) {
            $reporte = \App\Models\Reporte::with(['estudio.paciente', 'estudio.archivos', 'usuario', 'plantilla'])->find(request()->query('reporte'));
            if ($reporte && request()->boolean('download')) {
                $path = $reporte->estudio?->reporte_path;
                abort_unless($path && media_exists($path), 404, 'El archivo del reporte no esta disponible.');

                $paciente = $reporte->estudio?->paciente?->nombre_completo
                    ?? $reporte->estudio?->paciente_nombre
                    ?? 'paciente';
                $fecha = $reporte->created_at?->format('Ymd') ?? now()->format('Ymd');
                $filename = 'Reporte-'.\Illuminate\Support\Str::slug($paciente).'-'.$fecha.'.html';

                return \Illuminate\Support\Facades\Storage::disk(media_disk())->download($path, $filename, [
                    'Content-Type' => 'text/html; charset=UTF-8',
                ]);
            }
            if ($reporte && $reporte->estudio_id) {
                $estudioImagenes = \App\Models\EstudioArchivo::where('estudio_id', $reporte->estudio_id)
                    ->where('tipo', 'imagen')
                    ->orderByDesc('capturado_en')
                    ->get()
                    ->map(function ($a) {
                        $url = media_url($a->path);
                        if (! media_exists($a->path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($a->path)) {
                            $url = url('storage/'.$a->path);
                        }

                        return ['url' => $url, 'titulo' => $a->nombre_original];
                    });
            }
            // Si el reporte no tiene plantilla asignada, cargar la que corresponda al tipo de estudio
            if ($reporte && ! $reporte->plantilla && $reporte->estudio?->tipo) {
                $tipoKey = \Illuminate\Support\Str::lower($reporte->estudio->tipo);
                $default = \App\Models\Plantilla::where('clave', $tipoKey)->first();
                if ($default) {
                    $reporte->setRelation('plantilla', $default);
                }
            }
        }
        return view('ia-reportes.ver', compact('reporte', 'estudioImagenes'));
    })->name('ia-reportes.ver');

    Route::get('/ia-reportes/analisis', function () {
        return view('ia-reportes.analisis');
    })->name('ia-reportes.analisis');
    
    Route::get('/mensajes/correo', [WhatsAppController::class, 'index'])
        ->name('mensajes.correo');


    Route::get('/mensajes', [WhatsAppController::class, 'index'])
        ->name('mensajes');
    Route::get('/mensajes/whatsapp/{paciente}', [WhatsAppController::class, 'messages'])
        ->name('mensajes.whatsapp.messages');
    Route::post('/mensajes/whatsapp/enviar', [WhatsAppController::class, 'send'])
        ->middleware('throttle:30,1')
        ->name('mensajes.whatsapp.send');

    Route::get('/nuevo-estudio', function (\Illuminate\Http\Request $request) {
        /* Limpiar sesión de estudio al volver al dashboard */
        session()->forget(['estudio_activo_id', 'ultimo_estudio_completado_id']);

        $paciente = $request->filled('paciente')
            ? Paciente::find($request->query('paciente'))
            : null;

        $pacientes = Paciente::select('id', 'nombre_completo', 'folio', 'edad', 'sexo', 'telefono', 'email', 'foto')
            ->orderBy('nombre_completo')
            ->get();

        $galImagenes = collect();
        $galVideos = collect();
        $reportes = collect();

        if ($paciente) {
            $archivos = \App\Models\EstudioArchivo::with('estudio')
                ->where('paciente_id', $paciente->id)
                ->orderByDesc('capturado_en')
                ->orderByDesc('id')
                ->get();

            $galImagenes = $archivos->where('tipo', 'imagen')->values();
            $galVideos = $archivos->where('tipo', 'video')->values();

            $reportes = Reporte::with(['estudio', 'usuario'])
                ->whereHas('estudio', fn ($q) => $q->where('paciente_id', $paciente->id))
                ->latest()
                ->get();
        }

        return view('estudios.crear', [
            'paciente' => $paciente,
            'pacientes' => $pacientes,
            'galImagenes' => $galImagenes,
            'galVideos' => $galVideos,
            'reportes' => $reportes,
        ]);
    })->name('nuevo-estudio');

    Route::get('/nuevo-estudio/crear', function () {
        return view('estudios.crear');
    })->name('nuevo-estudio.crear');

    Route::post('/capture/pairing-code', [\App\Http\Controllers\CapturePairingCodeController::class, 'store'])
        ->name('capture.pairing-code.store');

    Route::get('/nuevo-estudio/importar', [NuevoEstudioController::class, 'importar'])
        ->name('nuevo-estudio.importar');

    Route::post('/nuevo-estudio/importar', [NuevoEstudioController::class, 'importarStore'])
        ->name('nuevo-estudio.importar.store');

    Route::post('/nuevo-estudio', [NuevoEstudioController::class, 'store'])
        ->name('nuevo-estudio.store');

    Route::get('/nuevo-estudio/capturas', [NuevoEstudioController::class, 'capturas'])
        ->name('nuevo-estudio.capturas');

    Route::post('/nuevo-estudio/capturas', [NuevoEstudioController::class, 'guardarCapturas'])
        ->name('nuevo-estudio.capturas.store');

    Route::get('/nuevo-estudio/configuracion', function () {
        return view('estudios.configuracion.index');
    })->name('nuevo-estudio.configuracion');

    Route::get('/nuevo-estudio/videos', function () {
        return view('estudios.videos.index');
    })->name('nuevo-estudio.videos');

    Route::get('/nuevo-estudio/grabando', [NuevoEstudioController::class, 'grabando'])
        ->name('nuevo-estudio.grabando');

    Route::get('/nuevo-estudio/finalizado', [NuevoEstudioController::class, 'finalizado'])
        ->name('nuevo-estudio.finalizado');

    Route::post('/nuevo-estudio/finalizar', [NuevoEstudioController::class, 'finalizarGrabacion'])
        ->name('nuevo-estudio.finalizar');

    Route::delete('/nuevo-estudio/archivos/{archivo}', [NuevoEstudioController::class, 'destroyArchivo'])
        ->middleware('critical.password:studies')
        ->name('nuevo-estudio.archivos.destroy');

    Route::patch('/nuevo-estudio/configuracion', [NuevoEstudioController::class, 'guardarConfiguracion'])
        ->middleware('critical.password:studies')
        ->name('nuevo-estudio.configuracion.update');

    /* ── Galería ── */
    Route::get('/galeria', [\App\Http\Controllers\GaleriaController::class, 'index'])->name('galeria');

    Route::get('/galeria/paciente/{id}', function ($id) {
        $paciente = Paciente::find($id);

        $archivos = \App\Models\EstudioArchivo::with('estudio')
            ->where('paciente_id', $id)
            ->orderByDesc('capturado_en')
            ->orderByDesc('id')
            ->get();

        $imagenes = $archivos->where('tipo', 'imagen')->values();
        $videos = $archivos->where('tipo', 'video')->values();

        return view('galeria.paciente', [
            'id' => $id,
            'paciente' => $paciente,
            'imagenes' => $imagenes,
            'videos' => $videos,
        ]);
    })->name('galeria.paciente');

    Route::get('/galeria/video/{id}', function ($id) {
        $archivo = \App\Models\EstudioArchivo::with(['estudio.paciente', 'estudio.hallazgos'])
            ->where('tipo', 'video')
            ->findOrFail($id);
        $estudio = $archivo->estudio;
        $paciente = $estudio?->paciente ?? Paciente::find($archivo->paciente_id);
        $capturas = \App\Models\EstudioArchivo::query()
            ->where('tipo', 'imagen')
            ->when(
                $archivo->estudio_id,
                fn ($q) => $q->where('estudio_id', $archivo->estudio_id),
                fn ($q) => $q->where('paciente_id', $archivo->paciente_id)
            )
            ->orderBy('capturado_en')
            ->orderBy('id')
            ->get();
        $editorConfig = data_get($estudio?->configuracion_video ?? [], 'editor.'.$archivo->id, []);

        return view('galeria.vervideo', compact('archivo', 'estudio', 'paciente', 'capturas', 'editorConfig'));
    })->name('galeria.video');

    Route::get('/galeria/video/{id}/editar', function ($id) {
        $archivo = \App\Models\EstudioArchivo::with(['estudio.paciente', 'estudio.hallazgos'])
            ->where('tipo', 'video')
            ->findOrFail($id);
        $estudio = $archivo->estudio;
        $paciente = $estudio?->paciente ?? Paciente::find($archivo->paciente_id);
        $capturas = \App\Models\EstudioArchivo::query()
            ->where('tipo', 'imagen')
            ->when(
                $archivo->estudio_id,
                fn ($q) => $q->where('estudio_id', $archivo->estudio_id),
                fn ($q) => $q->where('paciente_id', $archivo->paciente_id)
            )
            ->orderBy('capturado_en')
            ->orderBy('id')
            ->get();
        $editorConfig = data_get($estudio?->configuracion_video ?? [], 'editor.'.$archivo->id, []);

        return view('galeria.editarvideo', compact('archivo', 'estudio', 'paciente', 'capturas', 'editorConfig'));
    })->name('galeria.video.editar');

    Route::patch('/galeria/video/{id}', function ($id, \Illuminate\Http\Request $request) {
        $archivo = \App\Models\EstudioArchivo::with('estudio')
            ->where('tipo', 'video')
            ->findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'hallazgos' => ['nullable', 'string', 'max:5000'],
            'observaciones' => ['nullable', 'string', 'max:5000'],
            'diagnostico' => ['nullable', 'string', 'max:5000'],
            'ajustes' => ['nullable', 'array'],
            'ajustes.brillo' => ['nullable', 'integer', 'min:0', 'max:200'],
            'ajustes.contraste' => ['nullable', 'integer', 'min:0', 'max:200'],
            'ajustes.saturacion' => ['nullable', 'integer', 'min:0', 'max:200'],
            'ajustes.nitidez' => ['nullable', 'integer', 'min:0', 'max:100'],
            'ajustes.zoom' => ['nullable', 'integer', 'min:50', 'max:250'],
            'ajustes.rotacion' => ['nullable', 'integer', 'min:0', 'max:270'],
            'ajustes.flip_h' => ['nullable', 'boolean'],
            'ajustes.flip_v' => ['nullable', 'boolean'],
            'ajustes.trim_start' => ['nullable', 'numeric', 'min:0'],
            'ajustes.trim_end' => ['nullable', 'numeric', 'min:0'],
        ]);

        $archivoUpdates = [];
        if ($request->has('nombre')) {
            $displayName = trim($validated['nombre'] ?? '');
            if ($displayName !== '') {
                $extension = pathinfo($archivo->nombre_original ?: $archivo->path, PATHINFO_EXTENSION);
                $archivoUpdates['nombre'] = pathinfo($displayName, PATHINFO_FILENAME) ?: $displayName;
                $archivoUpdates['nombre_original'] = str_contains($displayName, '.') || ! $extension
                    ? $displayName
                    : $displayName.'.'.$extension;
            }
        }
        foreach (['descripcion', 'categoria'] as $field) {
            if ($request->has($field)) {
                $archivoUpdates[$field] = $validated[$field] ?? null;
            }
        }
        if ($archivoUpdates) {
            $archivo->update($archivoUpdates);
        }

        if ($estudio = $archivo->estudio) {
            $estudioUpdates = [];
            if ($request->has('hallazgos')) {
                $estudioUpdates['descripcion'] = $validated['hallazgos'] ?? null;
            }
            if ($request->has('observaciones')) {
                $estudioUpdates['observaciones'] = $validated['observaciones'] ?? null;
            }
            if ($request->has('diagnostico')) {
                $estudioUpdates['diagnostico'] = $validated['diagnostico'] ?? null;
            }
            if ($request->has('ajustes')) {
                $ajustes = [
                    'brillo' => (int) data_get($validated, 'ajustes.brillo', 100),
                    'contraste' => (int) data_get($validated, 'ajustes.contraste', 100),
                    'saturacion' => (int) data_get($validated, 'ajustes.saturacion', 100),
                    'nitidez' => (int) data_get($validated, 'ajustes.nitidez', 0),
                    'zoom' => (int) data_get($validated, 'ajustes.zoom', 100),
                    'rotacion' => (int) data_get($validated, 'ajustes.rotacion', 0),
                    'flip_h' => (bool) data_get($validated, 'ajustes.flip_h', false),
                    'flip_v' => (bool) data_get($validated, 'ajustes.flip_v', false),
                    'trim_start' => data_get($validated, 'ajustes.trim_start'),
                    'trim_end' => data_get($validated, 'ajustes.trim_end'),
                    'updated_at' => now()->toIso8601String(),
                ];
                $config = $estudio->configuracion_video ?? [];
                $config['editor'] ??= [];
                $config['editor'][(string) $archivo->id] = $ajustes;
                $estudioUpdates['configuracion_video'] = $config;
            }
            if ($estudioUpdates) {
                $estudio->update($estudioUpdates);
            }
        }

        return response()->json([
            'ok' => true,
            'message' => 'Video actualizado correctamente.',
            'redirect' => route('galeria.video', ['id' => $archivo->id, 'paciente' => $archivo->paciente_id]),
        ]);
    })->middleware('critical.password:studies')
        ->name('galeria.video.update');

    Route::post('/galeria/video/{id}/captura', function ($id, \Illuminate\Http\Request $request) {
        $archivo = \App\Models\EstudioArchivo::with('estudio')
            ->where('tipo', 'video')
            ->findOrFail($id);
        $estudio = $archivo->estudio;
        abort_unless($estudio, 422, 'El video necesita un estudio asociado para guardar capturas.');

        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:51200'],
            'capturado_en_video' => ['nullable', 'numeric', 'min:0'],
        ]);

        $file = $request->file('image');
        $path = media_store($file, app(\App\Services\MediaPathService::class)->studyImages($estudio));
        $seconds = (float) $request->input('capturado_en_video', 0);
        $copy = \App\Models\EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $archivo->paciente_id,
            'tipo' => 'imagen',
            'categoria' => 'fotograma-video',
            'nombre_original' => $file->getClientOriginalName(),
            'nombre' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'descripcion' => 'Fotograma capturado desde el video '.$archivo->nombre_original.' en '.gmdate('H:i:s', (int) $seconds),
            'capturado_en' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Fotograma guardado en la galería.',
            'archivo' => [
                'id' => $copy->id,
                'url' => media_url($copy->path),
                'path' => $copy->path,
                'show_url' => route('galeria.imagen', ['id' => $copy->id, 'paciente' => $copy->paciente_id]),
            ],
        ]);
    })->middleware('critical.password:studies')
        ->name('galeria.video.capture');

    Route::get('/galeria/imagen/{id}/archivo', function ($id) {
        $archivo = \App\Models\EstudioArchivo::where('tipo', 'imagen')->findOrFail($id);
        abort_unless($archivo->path && media_exists($archivo->path), 404);

        $disk = \Illuminate\Support\Facades\Storage::disk(media_disk());
        $filename = $archivo->nombre_original ?: basename((string) $archivo->path);

        return new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($disk, $archivo) {
            fpassthru($disk->readStream($archivo->path));
        }, 200, [
            'Content-Type' => $archivo->mime_type ?: ($disk->mimeType($archivo->path) ?: 'image/jpeg'),
            'Content-Length' => (string) ($archivo->size_bytes ?: $disk->size($archivo->path)),
            'Content-Disposition' => 'inline; filename="'.str_replace('"', '', $filename).'"',
            'Cache-Control' => 'private, max-age=300',
        ]);
    })->name('galeria.imagen.archivo');

    Route::get('/galeria/imagen/{id}', function ($id) {
        $archivo = \App\Models\EstudioArchivo::with('estudio')->find($id);
        $paciente = $archivo ? Paciente::find($archivo->paciente_id) : null;

        $hermanas = collect();
        if ($archivo) {
            $hermanas = \App\Models\EstudioArchivo::with('estudio', 'paciente')
                ->where('tipo', 'imagen')
                ->when(
                    $archivo->estudio_id,
                    fn ($q) => $q->where('estudio_id', $archivo->estudio_id),
                    fn ($q) => $q->where('paciente_id', $archivo->paciente_id)
                )
                ->orderBy('capturado_en')
                ->orderBy('id')
                ->get();
        }

        $formatDuration = function (?int $seconds): string {
            return $seconds && $seconds > 0 ? gmdate('H:i:s', $seconds) : '—';
        };

        $imageResolution = function ($a): string {
            if (! $a->path || ! media_exists($a->path)) {
                return '—';
            }

            try {
                $contents = \Illuminate\Support\Facades\Storage::disk(media_disk())->get($a->path);
                $size = @getimagesizefromstring($contents);

                return $size ? "{$size[0]} x {$size[1]}" : '—';
            } catch (\Throwable $e) {
                return '—';
            }
        };

        $caps = $hermanas->values()->map(function ($a, $i) use ($formatDuration, $imageResolution) {
            $study = $a->estudio;
            $patient = $a->paciente;
            $capturedAt = $a->capturado_en ?? $a->created_at;
            $frame = is_numeric($a->descripcion)
                ? gmdate('H:i:s', (int) $a->descripcion)
                : optional($a->capturado_en)->format('H:i:s');

            return [
                'n' => $i + 1,
                'ts' => optional($a->capturado_en)->format('H:i:s') ?? '',
                'bg' => 'radial-gradient(ellipse at 50% 50%,#1a1208 0%,#0a0610 100%)',
                'src' => route('galeria.imagen.archivo', $a->id),
                'id' => $a->id,
                'filename' => $a->nombre_original ?: basename((string) $a->path),
                'mime_type' => $a->mime_type,
                'size_bytes' => $a->size_bytes,
                'info' => [
                    'image_id' => 'IMG-'.str_pad((string) $a->id, 4, '0', STR_PAD_LEFT),
                    'patient_name' => $patient?->nombre_completo ?? $study?->paciente_nombre ?? '—',
                    'captured_at' => $capturedAt ? format_user_date($capturedAt).' · '.format_user_time($capturedAt) : '—',
                    'study_type' => $study?->tipo ?? $patient?->procedimiento ?? '—',
                    'equipment' => $study?->equipo ?? $patient?->equipo_utilizado ?? '—',
                    'resolution' => $imageResolution($a),
                    'duration' => $formatDuration($study?->duracion_segundos),
                    'frame' => $frame ?: '—',
                ],
            ];
        })->all();

        $current = $hermanas->values()->search(fn ($a) => (string) $a->id === (string) $id);
        if ($current === false) {
            $current = 0;
        }

        return view('galeria.verimagen', [
            'id' => $id,
            'archivo' => $archivo,
            'paciente' => $paciente,
            'caps' => $caps,
            'current' => $current,
        ]);
    })->name('galeria.imagen');

    Route::delete('/galeria/imagenes/{archivo}', [NuevoEstudioController::class, 'destroyImagenGaleria'])
        ->middleware('critical.password:studies')
        ->name('galeria.imagen.destroy');

    Route::post('/galeria/imagen/{id}/guardar', function ($id, \Illuminate\Http\Request $request) {
        $archivo = \App\Models\EstudioArchivo::with('estudio')->findOrFail($id);

        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:51200'],
        ]);

        $file = $request->file('image');
        $oldPath = $archivo->path;
        $path = media_store(
            $file,
            app(\App\Services\MediaPathService::class)->studyImages($archivo->estudio ?? $archivo->estudio_id, $archivo->paciente_id)
        );

        $archivo->update([
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'nombre_original' => $file->getClientOriginalName(),
            'nombre' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ]);

        if ($oldPath && $oldPath !== $path) {
            media_delete($oldPath);
        }

        return response()->json([
            'ok' => true,
            'archivo' => [
                'id' => $archivo->id,
                'url' => route('galeria.imagen.archivo', $archivo->id),
                'path' => $archivo->path,
            ],
        ]);
    })->middleware('critical.password:studies')
        ->name('galeria.imagen.guardar');

    Route::post('/galeria/imagen/{id}/guardar-copia', function ($id, \Illuminate\Http\Request $request) {
        $archivo = \App\Models\EstudioArchivo::with('estudio')->findOrFail($id);

        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:51200'],
        ]);

        $file = $request->file('image');
        $path = media_store(
            $file,
            app(\App\Services\MediaPathService::class)->studyImages($archivo->estudio ?? $archivo->estudio_id, $archivo->paciente_id)
        );
        $copy = \App\Models\EstudioArchivo::create([
            'estudio_id' => $archivo->estudio_id,
            'paciente_id' => $archivo->paciente_id,
            'tipo' => 'imagen',
            'categoria' => 'editada',
            'nombre_original' => $file->getClientOriginalName(),
            'nombre' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'descripcion' => 'Copia guardada por edicion',
            'capturado_en' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'archivo' => [
                'id' => $copy->id,
                'url' => route('galeria.imagen.archivo', $copy->id),
                'path' => $copy->path,
            ],
        ]);
    })->middleware('critical.password:studies')
        ->name('galeria.imagen.guardar-copia');

    /* ── Finanzas ── */
    Route::get('/finanzas', function () {
        return view('finanzas.index');
    })->name('finanzas');
});


Route::middleware(['auth', 'auth.session', 'session.limit', 'subscribed'])->group(function () {
    Route::resource('pacientes', PacienteController::class)
        ->middlewareFor(['update', 'destroy'], 'critical.password:patients');
    Route::post('/pacientes/{paciente}/add-medico', [PacienteController::class, 'addMedico'])
        ->name('pacientes.add-medico');
    Route::post('/pacientes/{paciente}/update-campo', [PacienteController::class, 'updateCampo'])
        ->name('pacientes.update-campo');


    Route::get('/qr', [QrRegistrationController::class, 'index'])->name('qr.index');
    Route::post('/qr/enlaces', [QrRegistrationController::class, 'store'])->name('qr.links.store');
    Route::get('/qr/enlaces/{link}/imagen', [QrRegistrationController::class, 'image'])->name('qr.links.image');
    Route::delete('/qr/enlaces/{link}', [QrRegistrationController::class, 'destroy'])->name('qr.links.destroy');
    Route::delete('/qr/enlaces/{link}/eliminar', [QrRegistrationController::class, 'archive'])->name('qr.links.archive');
    Route::post('/qr/preregistros/{preregistration}/aceptar', [QrRegistrationController::class, 'accept'])
        ->name('qr.preregistrations.accept');
    Route::post('/qr/preregistros/{preregistration}/rechazar', [QrRegistrationController::class, 'reject'])
        ->name('qr.preregistrations.reject');

    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
    Route::get('/agendar', [AgendaController::class, 'create'])->name('agendar');

    Route::post('/agenda/citas', [AgendaController::class, 'store'])->name('agenda.citas.store');
    Route::put('/agenda/citas/{cita}', [AgendaController::class, 'update'])->name('agenda.citas.update');
    Route::patch('/agenda/citas/{cita}/estado', [AgendaController::class, 'cambiarEstado'])->name('agenda.citas.estado');
    Route::delete('/agenda/citas/{cita}', [AgendaController::class, 'destroy'])->name('agenda.citas.destroy');

    Route::post('/agenda/bloqueos', [AgendaController::class, 'storeBloqueo'])->name('agenda.bloqueos.store');
    Route::delete('/agenda/bloqueos/{bloqueo}', [AgendaController::class, 'destroyBloqueo'])->name('agenda.bloqueos.destroy');

    Route::get('/finanzas', function () {
        return view('finanzas.index');
    })->name('finanzas');
});


Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token, Request $request) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->query('email'),
    ]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => \Illuminate\Support\Facades\Hash::make($password),
            ])->setRememberToken(\Illuminate\Support\Str::random(60));

            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.update');

Route::post('/ia/chat', [AiAssistantController::class, 'chat'])
    ->name('ia.chat');



Route::middleware(['auth'])->group(function () {
    Route::get('/soporte', [SoporteController::class, 'index'])->name('soporte');
    Route::get('/soporte/tickets', [TicketController::class, 'tickets'])->name('soporte.tickets');
    Route::get('/soporte/tickets/{ticket}', [TicketController::class, 'show'])->name('soporte.tickets.show');
    Route::post('/soporte/tickets', [TicketController::class, 'store'])->name('soporte.tickets.store');
    Route::get('/soporte/chat/history', [SoporteChatController::class, 'history'])->name('soporte.chat.history');
    Route::post('/soporte/chat', [SoporteChatController::class, 'chat'])->name('soporte.chat');
    Route::get('/soporte/chat/poll', [SoporteChatController::class, 'poll'])->name('soporte.chat.poll');
    Route::post('/soporte/chat/new', [SoporteChatController::class, 'newConversation'])->name('soporte.chat.new');

    Route::post('/ia/conversations/start', [AiAssistantController::class, 'start'])->name('ia.conversations.start');
    Route::get('/ia/conversations', [AiAssistantController::class, 'conversations'])->name('ia.conversations');
    Route::get('/ia/conversations/{conversation}', [AiAssistantController::class, 'show'])->name('ia.conversations.show');

    Route::post('/ia/chat', [AiAssistantController::class, 'chat'])->name('ia.chat');
    Route::get('/ia/history', [AiAssistantController::class, 'history'])->name('ia.history');
    Route::post('/ia/reset', [AiAssistantController::class, 'reset'])->name('ia.reset');
});

// Customer Success API (sesión web, usado por el JS de las vistas CS)
Route::middleware(['auth', 'customer.success'])->prefix('api/customer-success')->group(function () {
    Route::apiResource('anuncios', \App\Http\Controllers\Api\CustomerSuccess\AnuncioController::class);
    Route::get('/users', [\App\Http\Controllers\Api\CustomerSuccess\UserRoleController::class, 'index']);
    Route::post('/users/{user}/assign-role', [\App\Http\Controllers\Api\CustomerSuccess\UserRoleController::class, 'assign']);
    Route::post('/users/{user}/remove-role', [\App\Http\Controllers\Api\CustomerSuccess\UserRoleController::class, 'remove']);
    Route::get('/notifications', [\App\Http\Controllers\Api\CustomerSuccess\NotificationController::class, 'index']);
    Route::patch('/notifications/read-all', [\App\Http\Controllers\Api\CustomerSuccess\NotificationController::class, 'markAllRead']);
});

// Customer Success panel
Route::middleware(['auth', 'customer.success'])->prefix('customer-success')->name('customer-success.')->group(function () {
    Route::get('/dashboard', [CustomerSuccessController::class, 'dashboard'])->name('dashboard');
    Route::get('/anuncios', [CustomerSuccessController::class, 'anuncios'])->name('anuncios');
    Route::get('/gestion-usuarios', [CustomerSuccessController::class, 'gestionUsuarios'])->name('gestion-usuarios');
    Route::post('/logout', [EndoCareAuthController::class, 'logoutCs'])->name('logout');

    Route::get('/api/users', [CustomerSuccessController::class, 'users'])->name('api.users');
    Route::post('/api/users/{user}/assign-role', [CustomerSuccessController::class, 'assignRole'])->name('api.users.assign-role');
    Route::post('/api/users/{user}/remove-role', [CustomerSuccessController::class, 'removeRole'])->name('api.users.remove-role');

    Route::get('/tickets', [CsTicketController::class, 'index'])->name('tickets');
    Route::get('/api/tickets/poll', [CsTicketController::class, 'poll'])->name('api.tickets.poll');
    Route::get('/tickets/{ticket}', [CsTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}', [CsTicketController::class, 'update'])->name('tickets.update');
    Route::get('/tickets/{ticket}/resolve', [CsTicketController::class, 'resolveForm'])->name('tickets.resolve.form');
    Route::post('/tickets/{ticket}/resolve', [CsTicketController::class, 'resolve'])->name('tickets.resolve');
    Route::post('/tickets/{ticket}/reopen', [CsTicketController::class, 'reopen'])->name('tickets.reopen');

    Route::get('/soporte', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'index'])->name('soporte');
    Route::get('/soporte/{conversation}', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'show'])->name('soporte.chat');
    Route::post('/soporte/{conversation}/tomar', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'take'])->name('soporte.take');
    Route::post('/soporte/{conversation}/responder', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'reply'])->name('soporte.reply');
    Route::get('/api/soporte/pendientes', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'pending'])->name('api.soporte.pending');
    Route::get('/api/soporte/{conversation}/poll', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'poll'])->name('api.soporte.poll');
    Route::post('/api/soporte/{conversation}/cerrar', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'close'])->name('api.soporte.close');
    Route::delete('/api/soporte/{conversation}', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'destroy'])->name('api.soporte.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/capture/pairing-code', [CapturePairingCodeController::class, 'store'])
        ->name('capture.pairing-code.store');
});


Route::post('/procedimientos/store', [App\Http\Controllers\PacienteController::class, 'storeProcedimiento'])->name('procedimientos.store');
Route::put('/procedimientos/{procedimiento}', [App\Http\Controllers\PacienteController::class, 'updateProcedimiento'])->name('procedimientos.update');
Route::delete('/procedimientos/{procedimiento}', [App\Http\Controllers\PacienteController::class, 'destroyProcedimiento'])->name('procedimientos.destroy');

Route::post('/anestesiologos/store', [App\Http\Controllers\PacienteController::class, 'storeAnestesiologo'])->name('anestesiologos.store');
Route::put('/anestesiologos/{anestesiologo}', [App\Http\Controllers\PacienteController::class, 'updateAnestesiologo'])->name('anestesiologos.update');
Route::delete('/anestesiologos/{anestesiologo}', [App\Http\Controllers\PacienteController::class, 'destroyAnestesiologo'])->name('anestesiologos.destroy');

Route::post('/medicos/store', [App\Http\Controllers\PacienteController::class, 'storeMedico'])->name('medicos.store');
Route::put('/medicos/{medico}', [App\Http\Controllers\PacienteController::class, 'updateMedico'])->name('medicos.update');
Route::delete('/medicos/{medico}', [App\Http\Controllers\PacienteController::class, 'destroyMedico'])->name('medicos.destroy');

Route::post('/salas/store', [App\Http\Controllers\PacienteController::class, 'storeSala'])->name('salas.store');
Route::put('/salas/{sala}', [App\Http\Controllers\PacienteController::class, 'updateSala'])->name('salas.update');
Route::delete('/salas/{sala}', [App\Http\Controllers\PacienteController::class, 'destroySala'])->name('salas.destroy');
