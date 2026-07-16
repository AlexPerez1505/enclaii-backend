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

Route::middleware(['auth', 'auth.session', 'subscribed'])->group(function () {

    // Ruta de configuracion: si no tiene plan, muestra vista plan-only
    Route::get('/configuracion', function () {
        if (!auth()->user()->subscribed()) {
            return view('configuracion.plan-only');
        }
        return view('configuracion.index', [
            'billingUser' => request()->user()->billingUser(),
            'clinicMembers' => request()->user()->clinica
                ->usuarios()
                ->withMax('connectedSessions', 'last_activity')
                ->orderByRaw("CASE WHEN clinica_rol = 'propietario' THEN 0 ELSE 1 END")
                ->orderBy('name')
                ->get(),
            'clinicInvitations' => request()->user()->clinica
                ->invitations()
                ->whereNull('accepted_at')
                ->whereNull('revoked_at')
                ->where('expires_at', '>', now())
                ->latest()
                ->get(),
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
        ]);
    })->name('configuracion');

    // Ruta dedicada para seleccionar plan (sin sidebar ni header)
    Route::get('/seleccionar-plan', function () {
        return view('configuracion.plan-only');
    })->name('plan.only');

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

Route::middleware(['auth', 'auth.session', 'subscribed'])->group(function () {

    Route::get('/dashboard', function () {
        $estudiosSinReporte = \App\Models\Estudio::whereDoesntHave('reportes')->count();

        // Auto-cancelar citas próximas cuya fecha/hora ya pasó
        \App\Models\Cita::query()
            ->where('estado', 'proximo')
            ->whereRaw("CONCAT(fecha, ' ', hora) <= ?", [now()->format('Y-m-d H:i:s')])
            ->update(['estado' => 'cancelado']);

        // Próximo paciente: la cita pendiente más cercana (solo futuras)
        $proximaCita = \App\Models\Cita::with('paciente')
            ->whereNotIn('estado', ['cancelado', 'completado'])
            ->whereRaw("CONCAT(fecha, ' ', hora) >= ?", [now()->format('Y-m-d H:i:s')])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        // Pacientes pendientes HOY: citas de hoy futuras y no completadas/canceladas
        $pendientesHoy = \App\Models\Cita::with('paciente')
            ->whereDate('fecha', now()->toDateString())
            ->whereNotIn('estado', ['completado', 'cancelado'])
            ->whereTime('hora', '>=', now()->format('H:i:s'))
            ->orderBy('hora')
            ->get();

        // Citas por estado para el donut del resumen
        $citasProximas = \App\Models\Cita::where('estado', 'proximo')->count();
        $citasCompletadas = \App\Models\Cita::where('estado', 'completado')->count();
        $citasCanceladas = \App\Models\Cita::where('estado', 'cancelado')->count();

        // Resumen del mes (coincide con el mes mostrado en el widget de agenda)
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

        return view('dashboard.index', compact(
            'estudiosSinReporte', 'proximaCita', 'pendientesHoy',
            'citasProximas', 'citasCompletadas', 'citasCanceladas',
            'citasProximasMes', 'citasCompletadasMes', 'citasCanceladasMes',
            'pendientesMes', 'widgetMes', 'widgetAnio'
        ));
    })->name('dashboard');

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


    Route::get('/ia-reportes', function () {
        $reportes = Reporte::with(['estudio.paciente', 'usuario'])
            ->latest()
            ->get();

        // ===== KPIs con datos reales =====
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();
        $inicioMesPrev = now()->subMonthNoOverflow()->startOfMonth();
        $finMesPrev = now()->subMonthNoOverflow()->endOfMonth();

        // % de variación entre dos conteos
        $pct = function (int $actual, int $previo): int {
            if ($previo === 0) {
                return $actual > 0 ? 100 : 0;
            }

            return (int) round((($actual - $previo) / $previo) * 100);
        };

        // 1. Reportes generados (este mes)
        $repMes = Reporte::whereBetween('created_at', [$inicioMes, $finMes])->count();
        $repPrev = Reporte::whereBetween('created_at', [$inicioMesPrev, $finMesPrev])->count();

        // 2. Estudios sin reporte (pendientes reales)
        $estudiosSinReporte = \App\Models\Estudio::whereDoesntHave('reportes')->count();

        // 3. Evidencias (imágenes) capturadas este mes
        $evMes = \App\Models\EstudioArchivo::where('tipo', 'imagen')
            ->whereBetween('created_at', [$inicioMes, $finMes])->count();
        $evPrev = \App\Models\EstudioArchivo::where('tipo', 'imagen')
            ->whereBetween('created_at', [$inicioMesPrev, $finMesPrev])->count();

        // 4. Estudios realizados este mes
        $estMes = \App\Models\Estudio::whereBetween('created_at', [$inicioMes, $finMes])->count();
        $estPrev = \App\Models\Estudio::whereBetween('created_at', [$inicioMesPrev, $finMesPrev])->count();

        $kpis = [
            'reportes' => ['valor' => $repMes, 'trend' => $pct($repMes, $repPrev)],
            'sin_reporte' => ['valor' => $estudiosSinReporte],
            'evidencias' => ['valor' => $evMes, 'trend' => $pct($evMes, $evPrev)],
            'estudios' => ['valor' => $estMes, 'trend' => $pct($estMes, $estPrev)],
        ];

        $hallazgosData = app(\App\Http\Controllers\IaReporteController::class)->hallazgosData();
        $hallazgos = collect($hallazgosData['hallazgos'])->take(5)->all();

        return view('ia-reportes.index', compact('reportes', 'kpis', 'hallazgos'));
    })->name('ia-reportes');

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
                ->map(fn ($a) => 'storage/'.$a->path.'?v='.($a->updated_at?->timestamp ?? $a->id))
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
                ->map(fn ($a) => [
                    'id' => $a->id,
                    'url' => media_url($a->path),
                    'titulo' => $a->nombre_original,
                ])
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
            if ($reporte && $reporte->estudio_id) {
                $estudioImagenes = \App\Models\EstudioArchivo::where('estudio_id', $reporte->estudio_id)
                    ->where('tipo', 'imagen')
                    ->orderByDesc('capturado_en')
                    ->get()
                    ->map(fn ($a) => ['url' => media_url($a->path), 'titulo' => $a->nombre_original]);
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

    Route::get('/nuevo-estudio/importar', function () {
        return view('estudios.importar.index');
    })->name('nuevo-estudio.importar');

    Route::post('/nuevo-estudio', [NuevoEstudioController::class, 'store'])
        ->name('nuevo-estudio.store');

    Route::get('/nuevo-estudio/capturas', function () {
        return view('estudios.caputras.index');
    })->name('nuevo-estudio.capturas');

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
    Route::get('/galeria', function () {
        $colores = [
            'linear-gradient(135deg,#c084fc,#a78bfa)',
            'linear-gradient(135deg,#7dd3fc,#60a5fa)',
            'linear-gradient(135deg,#f9a8d4,#f472b6)',
            'linear-gradient(135deg,#99f6e4,#6ee7b7)',
        ];

        $medicos = \App\Models\Estudio::query()
            ->whereNotNull('medico')
            ->where('medico', '<>', '')
            ->distinct()
            ->orderBy('medico')
            ->pluck('medico');

        $procedimientos = \App\Models\Estudio::query()
            ->whereNotNull('tipo')
            ->where('tipo', '<>', '')
            ->distinct()
            ->orderBy('tipo')
            ->pluck('tipo');

        $hallazgos = \App\Models\Hallazgo::orderBy('nombre')->get(['id', 'nombre']);

        $pacientesDb = Paciente::orderBy('nombre_completo')->get()->values();
        $pacienteIds = $pacientesDb->pluck('id');
        $estudiosPorPaciente = \App\Models\Estudio::with([
                'archivos:id,estudio_id,tipo',
                'estudioHallazgos:id,estudio_id,hallazgo_id,detectado_por',
            ])
            ->whereIn('paciente_id', $pacienteIds)
            ->get()
            ->groupBy('paciente_id');
        $archivosPorPaciente = \App\Models\EstudioArchivo::whereIn('paciente_id', $pacienteIds)
            ->get()
            ->groupBy('paciente_id');

        $pacientes = $pacientesDb->map(function ($p, $i) use ($colores, $estudiosPorPaciente, $archivosPorPaciente) {
            $archivosPaciente = $archivosPorPaciente->get($p->id, collect());
            $estudiosDetalle = $estudiosPorPaciente->get($p->id, collect());
            $fotos = $archivosPaciente->where('tipo', 'imagen')->count();
            $videos = $archivosPaciente->where('tipo', 'video')->count();
            $estudios = $estudiosDetalle->count();
            $ultimoTs = $archivosPaciente->max('capturado_en');
            $ultimo = $ultimoTs ? \Illuminate\Support\Carbon::parse($ultimoTs)->format('d/m/Y') : '—';
            $ini = collect(explode(' ', $p->nombre_completo ?? ''))
                ->filter()->take(2)
                ->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))
                ->implode('') ?: 'PX';

            return [
                'id' => $p->id,
                'nombre' => $p->nombre_completo ?? 'Paciente',
                'telefono' => $p->telefono ?? '',
                'codigo' => $p->folio ?? $p->identificacion ?? '—',
                'sexo' => $p->sexo ?? '—',
                'edad' => $p->edad ? $p->edad . ' años' : '—',
                'ultimo' => $ultimo,
                'estudios' => $estudios,
                'fotos' => $fotos,
                'videos' => $videos,
                'estado' => 'Activo',
                'ini' => $ini,
                'color' => $colores[$i % count($colores)],
                'filtros' => $estudiosDetalle->map(function ($estudio) {
                    $hallazgosEstudio = $estudio->estudioHallazgos;

                    return [
                        'medico' => $estudio->medico ?? '',
                        'procedimiento' => $estudio->tipo ?? '',
                        'fecha' => $estudio->fecha?->format('Y-m-d') ?? '',
                        'estado' => $estudio->estado ?? '',
                        'archivos' => $estudio->archivos->pluck('tipo')->unique()->values(),
                        'hallazgos' => $hallazgosEstudio->pluck('hallazgo_id')
                            ->map(fn ($id) => (string) $id)
                            ->values(),
                        'hallazgos_ia' => $hallazgosEstudio->contains(
                            fn ($hallazgo) => mb_strtolower($hallazgo->detectado_por ?? '') === 'ia'
                        ),
                    ];
                })->values(),
            ];
        });

        return view('galeria.index', compact('pacientes', 'medicos', 'procedimientos', 'hallazgos'));
    })->name('galeria');

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
        return view('galeria.vervideo', ['id' => $id]);
    })->name('galeria.video');

    Route::get('/galeria/video/{id}/editar', function ($id) {
        return view('galeria.editarvideo', ['id' => $id]);
    })->name('galeria.video.editar');

    Route::get('/galeria/imagen/{id}', function ($id) {
        $archivo = \App\Models\EstudioArchivo::with('estudio')->find($id);
        $paciente = $archivo ? Paciente::find($archivo->paciente_id) : null;

        $hermanas = collect();
        if ($archivo) {
            $hermanas = \App\Models\EstudioArchivo::where('tipo', 'imagen')
                ->when(
                    $archivo->estudio_id,
                    fn ($q) => $q->where('estudio_id', $archivo->estudio_id),
                    fn ($q) => $q->where('paciente_id', $archivo->paciente_id)
                )
                ->orderBy('capturado_en')
                ->orderBy('id')
                ->get();
        }

        $caps = $hermanas->values()->map(function ($a, $i) {
            return [
                'n' => $i + 1,
                'ts' => optional($a->capturado_en)->format('H:i:s') ?? '',
                'bg' => 'radial-gradient(ellipse at 50% 50%,#1a1208 0%,#0a0610 100%)',
                'src' => media_url($a->path),
                'id' => $a->id,
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
        $archivo = \App\Models\EstudioArchivo::findOrFail($id);

        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:51200'],
        ]);

        $file = $request->file('image');
        $oldPath = $archivo->path;
        $path = media_store($file, "estudios/{$archivo->estudio_id}/archivos");

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
                'url' => media_url($archivo->path),
                'path' => $archivo->path,
            ],
        ]);
    })->middleware('critical.password:studies')
        ->name('galeria.imagen.guardar');

    Route::post('/galeria/imagen/{id}/guardar-copia', function ($id, \Illuminate\Http\Request $request) {
        $archivo = \App\Models\EstudioArchivo::findOrFail($id);

        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:51200'],
        ]);

        $file = $request->file('image');
        $path = media_store($file, "estudios/{$archivo->estudio_id}/archivos");
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
                'url' => media_url($copy->path),
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


Route::middleware(['auth', 'auth.session', 'subscribed'])->group(function () {
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
