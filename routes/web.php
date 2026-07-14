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

        // Auto-cancelar citas prÃƒÆ’Ã‚Â³ximas cuya fecha/hora ya pasÃƒÆ’Ã‚Â³
        \App\Models\Cita::query()
            ->where('estado', 'proximo')
            ->whereRaw("CONCAT(fecha, ' ', hora) <= ?", [now()->format('Y-m-d H:i:s')])
            ->update(['estado' => 'cancelado']);

        // PrÃƒÆ’Ã‚Â³ximo paciente: la cita pendiente mÃƒÆ’Ã‚Â¡s cercana (solo futuras)
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

        // % de variaciÃƒÆ’Ã‚Â³n entre dos conteos
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

        // 3. Evidencias (imÃƒÆ’Ã‚Â¡genes) capturadas este mes
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

        // Si llega paciente sin estudio, usar su estudio mÃƒÆ’Ã‚Â¡s reciente
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
                ->map(fn ($a) => media_url($a->path))
                ->values();
        }

        // Datos para precargar el formulario
        $datos = [
            'paciente' => $paciente?->nombre_completo ?? ($estudio?->paciente_nombre ?? ''),
            'iniciales' => collect(explode(' ', $paciente?->nombre_completo ?? 'NA'))
                ->filter()->take(2)->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))->implode('') ?: 'NA',
            'edad' => $paciente?->edad ? $paciente->edad.' aÃƒÆ’Ã‚Â±os' : '',
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

        // Lista de estudios para el selector: solo los que NO tienen reporte aÃƒÆ’Ã‚Âºn
        // (incluye el estudio actual aunque ya tuviera, para que la opciÃƒÆ’Ã‚Â³n seleccionada aparezca).
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
                    .' Ãƒâ€šÃ‚Â· '.($e->tipo ?? 'Estudio')
                    .' Ãƒâ€šÃ‚Â· '.(optional($e->fecha)->format('d/m/Y') ?? '')),
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

        // Si viene reporte, derivar estudio y paciente de ÃƒÆ’Ã‚Â©l
        if ($reporte && ! $estudio) {
            $estudio = $reporte->estudio;
        }
        if ($reporte && ! $paciente) {
            $paciente = $reporte->estudio?->paciente;
        }

        // Si no llegÃƒÆ’Ã‚Â³ paciente explÃƒÆ’Ã‚Â­cito, derivarlo del estudio
        if (! $paciente && $estudio) {
            $paciente = $estudio->paciente;
        }

        // Si hay paciente pero no estudio, usar su estudio mÃƒÆ’Ã‚Â¡s reciente
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
            'edad' => $paciente?->edad ? $paciente->edad.' aÃƒÆ’Ã‚Â±os' : '',
            'sexo' => $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '',
            'nacimiento' => optional($paciente?->fecha_nacimiento)->format('d/m/Y') ?? '',
            'fecha_estudio' => optional($estudio?->fecha)->format('d/m/Y') ?? now()->format('d/m/Y'),
            'procedimiento' => $estudio?->tipo ?? $paciente?->procedimiento ?? '',
            'tipo' => $estudio?->tipo ?? $paciente?->procedimiento ?? '',
            'medico' => $estudio?->medico ?? $paciente?->medico ?? '',
        ];

        // Plantillas guardadas (configuraciÃƒÆ’Ã‚Â³n persistida por clave)
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

        // Selector de estudio: solo los que NO tienen reporte aÃƒÆ’Ã‚Âºn
        // (incluye el estudio actual para que la opciÃƒÆ’Ã‚Â³n seleccionada aparezca).
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
                    .' Ãƒâ€šÃ‚Â· '.($e->tipo ?? 'Estudio')
                    .' Ãƒâ€šÃ‚Â· '.(optional($e->fecha)->format('d/m/Y') ?? '')),
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
            $ultimo = $ultimoTs ? \Illuminate\Support\Carbon::parse($ultimoTs)->format('d/m/Y') : 'ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â';
            $ini = collect(explode(' ', $p->nombre_completo ?? ''))
                ->filter()->take(2)
                ->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))
                ->implode('') ?: 'PX';

            return [
                'id' => $p->id,
                'nombre' => $p->nombre_completo ?? 'Paciente',
                'telefono' => $p->telefono ?? '',
                'codigo' => $p->folio ?? $p->identificacion ?? 'ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â',
                'sexo' => $p->sexo ?? 'ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â',
                'edad' => $p->edad ? $p->edad . ' aÃƒÆ’Ã‚Â±os' : 'ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â',
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

});


Route::options('/tauri/{any}', function (\Illuminate\Http\Request $request) {
    $origin = $request->headers->get('Origin');
    $allowedOrigin = $origin && (
        $origin === 'null'
        || preg_match('#^tauri://localhost$#', $origin)
        || preg_match('#^https?://tauri\.localhost$#', $origin)
        || preg_match('#^https?://localhost(:\d+)?$#', $origin)
        || preg_match('#^https?://127\.0\.0\.1(:\d+)?$#', $origin)
    );

    $response = response('', $allowedOrigin ? 204 : 403)
        ->header('Access-Control-Allow-Credentials', 'true')
        ->header('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type, X-Requested-With')
        ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->header('Vary', 'Origin');

    if ($allowedOrigin) {
        $response->headers->set('Access-Control-Allow-Origin', $origin);
    }

    return $response;
})->where('any', '.*');

Route::middleware(['auth', 'auth.session', 'subscribed'])->group(function () {
    Route::get('/tauri/dashboard', function (\Illuminate\Http\Request $request) {
        $withCors = function ($response) use ($request) {
            $origin = $request->headers->get('Origin');
            $allowedOrigin = $origin && (
                $origin === 'null'
                || preg_match('#^tauri://localhost$#', $origin)
                || preg_match('#^https?://tauri\.localhost$#', $origin)
                || preg_match('#^https?://localhost(:\d+)?$#', $origin)
                || preg_match('#^https?://127\.0\.0\.1(:\d+)?$#', $origin)
            );

            if ($allowedOrigin) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
                $response->headers->set('Vary', 'Origin');
            }

            return $response;
        };

        if (! auth()->check()) {
            $authorization = (string) $request->header('Authorization');

            if (str_starts_with($authorization, 'Basic ')) {
                $decoded = base64_decode(substr($authorization, 6), true);

                if ($decoded && str_contains($decoded, ':')) {
                    [$email, $password] = explode(':', $decoded, 2);

                    auth()->once([
                        'email' => $email,
                        'password' => $password,
                    ]);
                }
            }
        }

        $user = auth()->user();

        if (! $user) {
            return $withCors(response()->json([
                'ok' => false,
                'message' => 'Credenciales de Laravel requeridas.',
            ], 401));
        }

        if (! $user->subscribed()) {
            return $withCors(response()->json([
                'ok' => false,
                'message' => 'El usuario no tiene una suscripciÃƒÆ’Ã‚Â³n activa.',
            ], 403));
        }

        \App\Models\Cita::query()
            ->where('estado', 'proximo')
            ->whereRaw("CONCAT(fecha, ' ', hora) <= ?", [now()->format('Y-m-d H:i:s')])
            ->update(['estado' => 'cancelado']);

        $estudiosSinReporte = \App\Models\Estudio::whereDoesntHave('reportes')->count();

        $proximaCita = \App\Models\Cita::with('paciente')
            ->whereNotIn('estado', ['cancelado', 'completado'])
            ->whereRaw("CONCAT(fecha, ' ', hora) >= ?", [now()->format('Y-m-d H:i:s')])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        $pendientesHoy = \App\Models\Cita::with('paciente')
            ->whereDate('fecha', now()->toDateString())
            ->whereNotIn('estado', ['completado', 'cancelado'])
            ->whereTime('hora', '>=', now()->format('H:i:s'))
            ->orderBy('hora')
            ->get();

        $proximosEstudios = \App\Models\Cita::with('paciente')
            ->whereNotIn('estado', ['cancelado', 'completado'])
            ->whereRaw("CONCAT(fecha, ' ', hora) >= ?", [now()->format('Y-m-d H:i:s')])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(5)
            ->get();

        $citasProximas = \App\Models\Cita::where('estado', 'proximo')->count();
        $citasCompletadas = \App\Models\Cita::where('estado', 'completado')->count();
        $citasCanceladas = \App\Models\Cita::where('estado', 'cancelado')->count();

        $citaPayload = function (\App\Models\Cita $cita) {
            $hora = \Carbon\Carbon::parse($cita->hora)->format('H:i');
            $pacienteNombre = $cita->paciente?->nombre_completo
                ?? $cita->paciente_nombre
                ?? 'Paciente sin nombre';

            return [
                'id' => $cita->id,
                'paciente_id' => $cita->paciente_id,
                'paciente' => $pacienteNombre,
                'hora' => $hora,
                'fecha' => optional($cita->fecha)->format('d/m/Y') ?? '',
                'procedimiento' => $cita->procedimiento ?? 'Procedimiento',
                'estado' => $cita->estado,
                'estado_texto' => $cita->estado_texto,
                'medico' => $cita->paciente?->medico ?? '',
            ];
        };

        $nextPatient = $proximaCita ? $citaPayload($proximaCita) : null;

        return $withCors(response()->json([
            'ok' => true,
            'dashboard' => [
                'reportes_pendientes' => $estudiosSinReporte,
                'next_patient' => $nextPatient,
                'pendientes_hoy' => $pendientesHoy->map($citaPayload)->values(),
                'summary' => [
                    'total_citas' => $citasProximas + $citasCompletadas + $citasCanceladas,
                    'citas_proximas' => $citasProximas,
                    'citas_completadas' => $citasCompletadas,
                    'citas_canceladas' => $citasCanceladas,
                ],
                'proximos_estudios' => $proximosEstudios->map($citaPayload)->values(),
            ],
        ]));
    })->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.dashboard');

    Route::get('/tauri/pacientes', function (\Illuminate\Http\Request $request) {
        $withCors = function ($response) use ($request) {
            $origin = $request->headers->get('Origin');
            $allowedOrigin = $origin && (
                $origin === 'null'
                || preg_match('#^tauri://localhost$#', $origin)
                || preg_match('#^https?://tauri\.localhost$#', $origin)
                || preg_match('#^https?://localhost(:\d+)?$#', $origin)
                || preg_match('#^https?://127\.0\.0\.1(:\d+)?$#', $origin)
            );

            if ($allowedOrigin) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
                $response->headers->set('Vary', 'Origin');
            }

            return $response;
        };

        if (! auth()->check()) {
            $authorization = (string) $request->header('Authorization');

            if (str_starts_with($authorization, 'Basic ')) {
                $decoded = base64_decode(substr($authorization, 6), true);

                if ($decoded && str_contains($decoded, ':')) {
                    [$email, $password] = explode(':', $decoded, 2);

                    auth()->once([
                        'email' => $email,
                        'password' => $password,
                    ]);
                }
            }
        }

        $user = auth()->user();

        if (! $user) {
            return $withCors(response()->json([
                'ok' => false,
                'message' => 'Credenciales de Laravel requeridas.',
            ], 401));
        }

        if (! $user->subscribed()) {
            return $withCors(response()->json([
                'ok' => false,
                'message' => 'El usuario no tiene una suscripciÃƒÆ’Ã‚Â³n activa.',
            ], 403));
        }

        $pacientes = Paciente::query()
            ->orderBy('nombre_completo')
            ->get();

        $pacienteIds = $pacientes->pluck('id');

        $estudiosPorPaciente = \App\Models\Estudio::query()
            ->whereIn('paciente_id', $pacienteIds)
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get()
            ->groupBy('paciente_id');

        $proximasCitas = \App\Models\Cita::query()
            ->whereIn('paciente_id', $pacienteIds)
            ->whereNotIn('estado', ['cancelado', 'completado'])
            ->whereRaw("CONCAT(fecha, ' ', hora) >= ?", [now()->format('Y-m-d H:i:s')])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get()
            ->groupBy('paciente_id')
            ->map(fn ($citas) => $citas->first());

        $patients = $pacientes->map(function (Paciente $paciente) use ($estudiosPorPaciente, $proximasCitas) {
            $estudios = $estudiosPorPaciente->get($paciente->id, collect());
            $ultimoEstudio = $estudios->first();
            $proximaCita = $proximasCitas->get($paciente->id);
            $nombre = trim((string) $paciente->nombre_completo);
            $initials = collect(explode(' ', $nombre))
                ->filter()
                ->take(2)
                ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                ->implode('') ?: 'PX';

            return [
                'id' => $paciente->id,
                'name' => $paciente->nombre_completo,
                'initials' => $initials,
                'age' => $paciente->edad ? $paciente->edad.' aÃƒÆ’Ã‚Â±os' : '',
                'gender' => $paciente->sexo ? ucfirst($paciente->sexo) : '',
                'folio' => $paciente->folio,
                'dob' => optional($paciente->fecha_nacimiento)->format('d/m/Y') ?? '',
                'phone' => $paciente->telefono,
                'email' => $paciente->email,
                'address' => $paciente->direccion,
                'medico' => $paciente->medico,
                'foto_url' => $paciente->foto ? media_url($paciente->foto) : null,
                'study_date' => optional($ultimoEstudio?->fecha)->format('d/m/Y') ?? '',
                'study_type' => $ultimoEstudio?->tipo ?? $paciente->procedimiento ?? '',
                'status' => $ultimoEstudio?->estado ?? '',
                'tiene_estudios' => $estudios->isNotEmpty(),
                'estudios' => $estudios->map(fn ($estudio) => [
                    'id' => $estudio->id,
                    'tipo' => $estudio->tipo ?? 'Estudio',
                    'fecha' => optional($estudio->fecha)->format('d/m/Y') ?? '',
                ])->values(),
                'proxima_cita' => $proximaCita ? [
                    'fecha' => optional($proximaCita->fecha)->format('d/m/Y') ?? '',
                    'hora' => \Carbon\Carbon::parse($proximaCita->hora)->format('H:i'),
                ] : null,
            ];
        })->values();

        $response = response()->json([
            'ok' => true,
            'patients' => $patients,
        ]);

        return $withCors($response);
    })->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.pacientes');

    Route::get('/tauri/agenda', function (\Illuminate\Http\Request $request) {
        $withCors = function ($response) use ($request) {
            $origin = $request->headers->get('Origin');
            $allowedOrigin = $origin && (
                $origin === 'null'
                || preg_match('#^tauri://localhost$#', $origin)
                || preg_match('#^https?://tauri\.localhost$#', $origin)
                || preg_match('#^https?://localhost(:\d+)?$#', $origin)
                || preg_match('#^https?://127\.0\.0\.1(:\d+)?$#', $origin)
            );

            if ($allowedOrigin) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
                $response->headers->set('Vary', 'Origin');
            }

            return $response;
        };

        if (! auth()->check()) {
            $authorization = (string) $request->header('Authorization');

            if (str_starts_with($authorization, 'Basic ')) {
                $decoded = base64_decode(substr($authorization, 6), true);

                if ($decoded && str_contains($decoded, ':')) {
                    [$email, $password] = explode(':', $decoded, 2);

                    auth()->once([
                        'email' => $email,
                        'password' => $password,
                    ]);
                }
            }
        }

        $user = auth()->user();

        if (! $user) {
            return $withCors(response()->json([
                'ok' => false,
                'message' => 'Credenciales de Laravel requeridas.',
            ], 401));
        }

        if (! $user->subscribed()) {
            return $withCors(response()->json([
                'ok' => false,
                'message' => 'El usuario no tiene una suscripcion activa.',
            ], 403));
        }

        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);

        if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
            return $withCors(response()->json([
                'ok' => false,
                'message' => 'Mes de agenda invalido.',
            ], 422));
        }

        \App\Models\Cita::query()
            ->where('estado', 'proximo')
            ->whereRaw("CONCAT(fecha, ' ', hora) <= ?", [now()->format('Y-m-d H:i:s')])
            ->update(['estado' => 'cancelado']);

        $citas = \App\Models\Cita::with('paciente')
            ->whereYear('fecha', $year)
            ->whereMonth('fecha', $month)
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get()
            ->map(function (\App\Models\Cita $cita) {
                $hora = \Carbon\Carbon::parse($cita->hora)->format('H:i');

                return [
                    'id' => $cita->id,
                    'fecha' => optional($cita->fecha)->format('Y-m-d'),
                    'hora' => $hora,
                    'paciente_id' => $cita->paciente_id,
                    'paciente' => $cita->paciente?->nombre_completo
                        ?? $cita->paciente_nombre
                        ?? 'Paciente sin nombre',
                    'procedimiento' => $cita->procedimiento ?? 'Procedimiento',
                    'estado' => $cita->estado,
                    'estado_texto' => $cita->estado_texto,
                    'cls' => $cita->estado_clase,
                    'sala' => $cita->sala,
                    'notas' => $cita->notas,
                ];
            })
            ->values();

        return $withCors(response()->json([
            'ok' => true,
            'citas' => $citas,
        ]));
    })->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.agenda');

    Route::get('/tauri/reportes', function (\Illuminate\Http\Request $request) {
        $withCors = function ($response) use ($request) {
            $origin = $request->headers->get('Origin');
            $allowedOrigin = $origin && (
                $origin === 'null'
                || preg_match('#^tauri://localhost$#', $origin)
                || preg_match('#^https?://tauri\.localhost$#', $origin)
                || preg_match('#^https?://localhost(:\d+)?$#', $origin)
                || preg_match('#^https?://127\.0\.0\.1(:\d+)?$#', $origin)
            );

            if ($allowedOrigin) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
                $response->headers->set('Vary', 'Origin');
            }

            return $response;
        };

        if (! auth()->check()) {
            $authorization = (string) $request->header('Authorization');

            if (str_starts_with($authorization, 'Basic ')) {
                $decoded = base64_decode(substr($authorization, 6), true);

                if ($decoded && str_contains($decoded, ':')) {
                    [$email, $password] = explode(':', $decoded, 2);

                    auth()->once([
                        'email' => $email,
                        'password' => $password,
                    ]);
                }
            }
        }

        $user = auth()->user();

        if (! $user) {
            return $withCors(response()->json([
                'ok' => false,
                'message' => 'Credenciales de Laravel requeridas.',
            ], 401));
        }

        if (! $user->subscribed()) {
            return $withCors(response()->json([
                'ok' => false,
                'message' => 'El usuario no tiene una suscripcion activa.',
            ], 403));
        }

        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();
        $inicioMesPrev = now()->subMonthNoOverflow()->startOfMonth();
        $finMesPrev = now()->subMonthNoOverflow()->endOfMonth();

        $pct = function (int $actual, int $previo): int {
            if ($previo === 0) {
                return $actual > 0 ? 100 : 0;
            }

            return (int) round((($actual - $previo) / $previo) * 100);
        };

        $repMes = Reporte::whereBetween('created_at', [$inicioMes, $finMes])->count();
        $repPrev = Reporte::whereBetween('created_at', [$inicioMesPrev, $finMesPrev])->count();
        $estudiosSinReporte = \App\Models\Estudio::whereDoesntHave('reportes')->count();

        $evMes = \App\Models\EstudioArchivo::where('tipo', 'imagen')
            ->whereBetween('created_at', [$inicioMes, $finMes])
            ->count();
        $evPrev = \App\Models\EstudioArchivo::where('tipo', 'imagen')
            ->whereBetween('created_at', [$inicioMesPrev, $finMesPrev])
            ->count();

        $estMes = \App\Models\Estudio::whereBetween('created_at', [$inicioMes, $finMes])->count();
        $estPrev = \App\Models\Estudio::whereBetween('created_at', [$inicioMesPrev, $finMesPrev])->count();

        $reportes = Reporte::with(['estudio.paciente', 'usuario'])
            ->latest()
            ->limit(100)
            ->get()
            ->map(function (Reporte $reporte) {
                $paciente = $reporte->estudio?->paciente;
                $pacienteNombre = $paciente?->nombre_completo
                    ?? $reporte->estudio?->paciente_nombre
                    ?? 'Paciente sin nombre';
                $initials = collect(explode(' ', trim($pacienteNombre)))
                    ->filter()
                    ->take(2)
                    ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                    ->implode('') ?: 'RP';

                return [
                    'id' => $reporte->id,
                    'paciente' => $pacienteNombre,
                    'initials' => $initials,
                    'estudio' => $reporte->estudio?->tipo ?? 'Estudio',
                    'fecha' => optional($reporte->created_at)->format('d/m/Y') ?? '',
                    'hora' => optional($reporte->created_at)->format('H:i') ?? '',
                    'estado_texto' => $reporte->contiene_hallazgos_criticos ? 'Critico' : 'Normal',
                    'contiene_hallazgos_criticos' => (bool) $reporte->contiene_hallazgos_criticos,
                    'view_url' => route('ia-reportes.ver', ['reporte' => $reporte->id]),
                    'edit_url' => route('ia-reportes.redactar', [
                        'reporte' => $reporte->id,
                        'estudio' => $reporte->estudio_id,
                    ]),
                    'download_url' => route('ia-reportes.ver', ['reporte' => $reporte->id]),
                ];
            })
            ->values();

        $hallazgosData = app(\App\Http\Controllers\IaReporteController::class)->hallazgosData();
        $hallazgos = collect($hallazgosData['hallazgos'] ?? [])
            ->take(5)
            ->map(fn ($hallazgo) => [
                'id' => $hallazgo['id'] ?? null,
                'nombre' => $hallazgo['nombre'] ?? 'Hallazgo',
                'porcentaje' => $hallazgo['porcentaje'] ?? 0,
                'es_critico' => (bool) ($hallazgo['es_critico'] ?? false),
            ])
            ->values();

        return $withCors(response()->json([
            'ok' => true,
            'kpis' => [
                'reportes' => ['valor' => $repMes, 'trend' => $pct($repMes, $repPrev)],
                'sin_reporte' => ['valor' => $estudiosSinReporte],
                'evidencias' => ['valor' => $evMes, 'trend' => $pct($evMes, $evPrev)],
                'estudios' => ['valor' => $estMes, 'trend' => $pct($estMes, $estPrev)],
            ],
            'reportes' => $reportes,
            'hallazgos' => $hallazgos,
        ]));
    })->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.reportes');

    $tauriQrCors = function ($response, \Illuminate\Http\Request $request) {
        $origin = $request->headers->get('Origin');
        $allowedOrigin = $origin && (
            $origin === 'null'
            || preg_match('#^tauri://localhost$#', $origin)
            || preg_match('#^https?://tauri\.localhost$#', $origin)
            || preg_match('#^https?://localhost(:\d+)?$#', $origin)
            || preg_match('#^https?://127\.0\.0\.1(:\d+)?$#', $origin)
        );

        if ($allowedOrigin) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type, Accept, X-Requested-With');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $response->headers->set('Vary', 'Origin');
        }

        return $response;
    };

    $tauriQrUser = function (\Illuminate\Http\Request $request) {
        if (! auth()->check()) {
            $authorization = (string) $request->header('Authorization');

            if (str_starts_with($authorization, 'Basic ')) {
                $decoded = base64_decode(substr($authorization, 6), true);

                if ($decoded && str_contains($decoded, ':')) {
                    [$email, $password] = explode(':', $decoded, 2);

                    auth()->once([
                        'email' => $email,
                        'password' => $password,
                    ]);
                }
            }
        }

        return auth()->user();
    };

    $tauriQrStatusLabels = [
        'active' => 'Activo',
        'submitted' => 'Utilizado',
        'expired' => 'Vencido',
        'revoked' => 'Cancelado',
        'pending' => 'Pendiente',
        'accepted' => 'Aceptado',
        'rejected' => 'Rechazado',
    ];

    $tauriQrHistoryStatus = function (\App\Models\PatientRegistrationLink $link): string {
        return $link->status === 'active' && $link->expires_at->isPast()
            ? 'expired'
            : $link->status;
    };

    $tauriQrCode = fn (\App\Models\PatientRegistrationLink $link): string => 'QR-'.$link->created_at->format('Y').'-'.str_pad((string) $link->id, 4, '0', STR_PAD_LEFT);

    $tauriQrDate = fn ($date): string => $date ? (function_exists('format_user_date') ? format_user_date($date) : $date->format('d/m/Y')) : '';
    $tauriQrTime = fn ($date): string => $date ? (function_exists('format_user_time') ? format_user_time($date) : $date->format('H:i')) : '';

    $tauriQrInitials = function (?string $name): string {
        $parts = collect(explode(' ', trim((string) $name)))->filter()->take(2);

        return $parts
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('') ?: 'P';
    };

    $tauriQrSvg = function (\App\Models\PatientRegistrationLink $link): string {
        $url = route('qr.public.show', ['token' => $link->token]);
        $qrCode = new \Endroid\QrCode\QrCode(
            data: $url,
            encoding: new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
            errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::High,
            size: 360,
            margin: 18,
            roundBlockSizeMode: \Endroid\QrCode\RoundBlockSizeMode::Margin,
            foregroundColor: new \Endroid\QrCode\Color\Color(6, 16, 50),
            backgroundColor: new \Endroid\QrCode\Color\Color(255, 255, 255),
        );

        return (new \Endroid\QrCode\Writer\SvgWriter)->write($qrCode)->getString();
    };

    $tauriQrDuplicate = function (\App\Models\PatientPreregistration $preregistration): bool {
        return Paciente::query()
            ->where(function ($filter) use ($preregistration): void {
                if ($preregistration->email) {
                    $filter->orWhereRaw('LOWER(email) = ?', [\Illuminate\Support\Str::lower($preregistration->email)]);
                }
                if ($preregistration->telefono) {
                    $filter->orWhere('telefono', $preregistration->telefono);
                }
            })
            ->exists();
    };

    $tauriQrValidityLabel = function (\App\Models\PatientRegistrationLink $link): string {
        $hours = (int) round($link->created_at->diffInHours($link->expires_at));

        return $hours === 168 ? '7 dias' : $hours.' horas';
    };

    $tauriQrLinkPayload = function (\App\Models\PatientRegistrationLink $link, bool $includeSvg = false) use ($tauriQrCode, $tauriQrDate, $tauriQrTime, $tauriQrHistoryStatus, $tauriQrStatusLabels, $tauriQrValidityLabel, $tauriQrSvg) {
        $status = $tauriQrHistoryStatus($link);
        $publicUrl = route('qr.public.show', ['token' => $link->token]);
        $settings = auth()->user()?->resolvedSettings() ?? [];
        $template = $settings['qr_whatsapp_template'] ?? 'Hola, te comparto tu enlace de pre-registro de ENCLAII: {enlace}';
        $shareText = strtr($template, [
            '{enlace}' => $publicUrl,
            '{codigo}' => $tauriQrCode($link),
            '{mensaje}' => $link->patient_message ?: '',
            '{clinica}' => auth()->user()?->clinica?->nombre ?? 'ENCLAII',
        ]);

        if (! str_contains($shareText, $publicUrl)) {
            $shareText = trim($shareText.' '.$publicUrl);
        }

        return [
            'id' => $link->id,
            'code' => $tauriQrCode($link),
            'status' => $status,
            'status_text' => $tauriQrStatusLabels[$status] ?? ucfirst($status),
            'is_available' => $link->isAvailable(),
            'validity_label' => $tauriQrValidityLabel($link),
            'created_date' => $tauriQrDate($link->created_at),
            'expires_date' => $tauriQrDate($link->expires_at),
            'created_label' => trim($tauriQrDate($link->created_at).' Ãƒâ€šÃ‚Â· '.$tauriQrTime($link->created_at)),
            'expires_label' => trim($tauriQrDate($link->expires_at).' Ãƒâ€šÃ‚Â· '.$tauriQrTime($link->expires_at)),
            'registrations' => $link->preregistration ? 1 : 0,
            'public_url' => $publicUrl,
            'share_text' => $shareText,
            'patient_message' => $link->patient_message,
            'qr_svg' => $includeSvg ? $tauriQrSvg($link) : null,
        ];
    };

    $tauriQrPreregPayload = function (\App\Models\PatientPreregistration $item, array $duplicates) use ($tauriQrInitials, $tauriQrDate, $tauriQrStatusLabels) {
        return [
            'id' => $item->id,
            'name' => $item->nombre_completo,
            'initials' => $tauriQrInitials($item->nombre_completo),
            'phone' => $item->telefono,
            'email' => $item->email,
            'status' => $item->status,
            'status_text' => $tauriQrStatusLabels[$item->status] ?? ucfirst($item->status),
            'received_label' => 'Recibido '.$item->created_at->diffForHumans(),
            'photo_url' => $item->foto ? media_url($item->foto) : null,
            'possible_duplicate' => (bool) ($duplicates[$item->id] ?? false),
            'birth_date' => $tauriQrDate($item->fecha_nacimiento),
            'age' => $item->edad,
            'sex' => $item->sexo,
            'weight' => $item->peso,
            'height' => $item->altura,
            'address' => $item->direccion,
            'procedure' => $item->procedimiento,
            'identification' => $item->identificacion,
            'consent_label' => $tauriQrDate($item->consent_accepted_at),
            'reason' => $item->motivo_consulta,
            'allergies' => $item->alergias,
            'conditions' => $item->enfermedades,
            'medications' => $item->medicamentos_actuales,
            'medical_history' => $item->antecedentes_medicos,
            'observations' => $item->observaciones,
            'patient_folio' => $item->patient?->folio,
        ];
    };

    $tauriQrPayload = function (\Illuminate\Http\Request $request, ?int $selectedLinkId = null) use ($tauriQrHistoryStatus, $tauriQrLinkPayload, $tauriQrPreregPayload, $tauriQrDuplicate) {
        $user = auth()->user();
        $qrSettings = $user->resolvedSettings();
        $links = \App\Models\PatientRegistrationLink::query()
            ->with(['creator', 'preregistration'])
            ->whereNull('archived_at')
            ->latest()
            ->limit(50)
            ->get();

        $preregistrations = \App\Models\PatientPreregistration::query()
            ->with(['registrationLink.creator', 'patient', 'reviewer'])
            ->latest()
            ->limit(30)
            ->get();

        $duplicates = ($qrSettings['qr_duplicate_check'] ?? true)
            ? $preregistrations
                ->filter(fn (\App\Models\PatientPreregistration $item) => $item->status === 'pending')
                ->mapWithKeys(fn (\App\Models\PatientPreregistration $item): array => [$item->id => $tauriQrDuplicate($item)])
                ->all()
            : [];

        $selected = $selectedLinkId ?: (int) $request->query('qr');
        $currentLink = $links->firstWhere('id', $selected)
            ?? $links->first(fn (\App\Models\PatientRegistrationLink $link) => $link->isAvailable())
            ?? $links->first();

        $historyCounts = [
            'active' => 0,
            'submitted' => 0,
            'expired' => 0,
            'revoked' => 0,
        ];

        foreach ($links as $link) {
            $status = $tauriQrHistoryStatus($link);
            $historyCounts[$status] = ($historyCounts[$status] ?? 0) + 1;
        }

        $defaultHistory = $historyCounts['active'] > 0
            ? 'active'
            : ($historyCounts['submitted'] > 0 ? 'submitted' : ($historyCounts['expired'] > 0 ? 'expired' : 'revoked'));

        return [
            'ok' => true,
            'kpis' => [
                'active' => $historyCounts['active'],
                'pending' => $preregistrations->where('status', 'pending')->count(),
                'accepted' => $preregistrations->where('status', 'accepted')->count(),
            ],
            'settings' => [
                'default_expiration_hours' => (string) ($qrSettings['qr_default_expiration_hours'] ?? '48'),
                'default_patient_message' => (string) ($qrSettings['qr_default_patient_message'] ?? ''),
            ],
            'history_counts' => $historyCounts,
            'default_history_status' => $defaultHistory,
            'current_link' => $currentLink ? $tauriQrLinkPayload($currentLink, true) : null,
            'links' => $links->map(fn (\App\Models\PatientRegistrationLink $link) => $tauriQrLinkPayload($link))->values(),
            'preregistrations' => $preregistrations->map(fn (\App\Models\PatientPreregistration $item) => $tauriQrPreregPayload($item, $duplicates))->values(),
        ];
    };

    Route::options('/tauri/qr/{any?}', function (\Illuminate\Http\Request $request) use ($tauriQrCors) {
        return $tauriQrCors(response('', 204), $request);
    })->where('any', '.*')->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.qr.options');

    Route::get('/tauri/qr', function (\Illuminate\Http\Request $request) use ($tauriQrCors, $tauriQrUser, $tauriQrPayload) {
        $user = $tauriQrUser($request);

        if (! $user) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'Credenciales de Laravel requeridas.'], 401), $request);
        }

        if (! $user->subscribed()) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'El usuario no tiene una suscripcion activa.'], 403), $request);
        }

        return $tauriQrCors(response()->json($tauriQrPayload($request)), $request);
    })->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.qr.index');

    Route::post('/tauri/qr/enlaces', function (\Illuminate\Http\Request $request) use ($tauriQrCors, $tauriQrUser, $tauriQrPayload) {
        $user = $tauriQrUser($request);

        if (! $user) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'Credenciales de Laravel requeridas.'], 401), $request);
        }

        if (! $user->subscribed()) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'El usuario no tiene una suscripcion activa.'], 403), $request);
        }

        $validator = validator($request->all(), [
            'expires_in_hours' => ['nullable', 'integer', 'in:24,48,168'],
            'patient_message' => ['nullable', 'string', 'max:150'],
        ]);

        if ($validator->fails()) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422), $request);
        }

        $qrSettings = $user->resolvedSettings();
        $validated = $validator->validated();
        $expiresInHours = (int) ($validated['expires_in_hours'] ?? $qrSettings['qr_default_expiration_hours'] ?? 48);
        $patientMessage = array_key_exists('patient_message', $validated)
            ? $validated['patient_message']
            : ($qrSettings['qr_default_patient_message'] ?? null);
        $token = \Illuminate\Support\Str::random(64);
        $link = \App\Models\PatientRegistrationLink::create([
            'clinica_id' => $user->clinica_id,
            'created_by' => $user->id,
            'token' => $token,
            'token_hash' => hash('sha256', $token),
            'status' => 'active',
            'patient_message' => $patientMessage ?: null,
            'expires_at' => now()->addHours($expiresInHours),
        ]);

        return $tauriQrCors(response()->json($tauriQrPayload($request, $link->id)), $request);
    })->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.qr.links.store');

    Route::post('/tauri/qr/preregistros/{preregistration}/aceptar', function (\Illuminate\Http\Request $request, int $preregistration) use ($tauriQrCors, $tauriQrUser, $tauriQrPayload, $tauriQrDuplicate) {
        $user = $tauriQrUser($request);

        if (! $user) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'Credenciales de Laravel requeridas.'], 401), $request);
        }

        if (! $user->subscribed()) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'El usuario no tiene una suscripcion activa.'], 403), $request);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($user, $preregistration, $request, $tauriQrDuplicate): void {
                $record = \App\Models\PatientPreregistration::query()
                    ->whereKey($preregistration)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($record->status !== 'pending') {
                    throw new \RuntimeException('Este pre-registro ya fue revisado.');
                }

                $qrSettings = $user->resolvedSettings();
                if (($qrSettings['qr_duplicate_check'] ?? true) && ($qrSettings['qr_duplicate_action'] ?? 'warn') === 'block_acceptance' && $tauriQrDuplicate($record)) {
                    throw new \RuntimeException('Existe un paciente con el mismo telefono o correo.');
                }

                $patient = Paciente::create([
                    'clinica_id' => $user->clinica_id,
                    'folio' => app(\App\Services\PatientFolioGenerator::class)->next($user->clinica_id),
                    'nombre_completo' => $record->nombre_completo,
                    'identificacion' => $record->identificacion,
                    'fecha_nacimiento' => $record->fecha_nacimiento,
                    'edad' => $record->edad,
                    'peso' => $record->peso,
                    'altura' => $record->altura,
                    'sexo' => $record->sexo,
                    'direccion' => $record->direccion,
                    'telefono' => $record->telefono,
                    'email' => $record->email,
                    'medico' => $user->name,
                    'procedimiento' => $record->procedimiento,
                    'diagnostico_preliminar' => $record->motivo_consulta,
                    'alergias' => $record->alergias,
                    'enfermedades' => $record->enfermedades,
                    'medicamentos_actuales' => $record->medicamentos_actuales,
                    'antecedentes_medicos' => $record->antecedentes_medicos,
                    'foto' => $record->foto,
                ]);

                $record->update([
                    'status' => 'accepted',
                    'patient_id' => $patient->id,
                    'reviewed_by' => $user->id,
                    'reviewed_at' => now(),
                ]);
            });
        } catch (\Throwable $exception) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => $exception->getMessage()], 422), $request);
        }

        return $tauriQrCors(response()->json($tauriQrPayload($request)), $request);
    })->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.qr.preregistrations.accept');

    Route::post('/tauri/qr/preregistros/{preregistration}/rechazar', function (\Illuminate\Http\Request $request, int $preregistration) use ($tauriQrCors, $tauriQrUser, $tauriQrPayload) {
        $user = $tauriQrUser($request);

        if (! $user) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'Credenciales de Laravel requeridas.'], 401), $request);
        }

        if (! $user->subscribed()) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'El usuario no tiene una suscripcion activa.'], 403), $request);
        }

        try {
            $photoToDelete = \Illuminate\Support\Facades\DB::transaction(function () use ($user, $preregistration): ?string {
                $record = \App\Models\PatientPreregistration::query()
                    ->whereKey($preregistration)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($record->status !== 'pending') {
                    throw new \RuntimeException('Este pre-registro ya fue revisado.');
                }

                $photo = $record->foto;
                $record->update([
                    'status' => 'rejected',
                    'reviewed_by' => $user->id,
                    'reviewed_at' => now(),
                    'foto' => null,
                ]);

                return $photo;
            });

            if ($photoToDelete && function_exists('media_delete')) {
                media_delete($photoToDelete);
            }
        } catch (\Throwable $exception) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => $exception->getMessage()], 422), $request);
        }

        return $tauriQrCors(response()->json($tauriQrPayload($request)), $request);
    })->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.qr.preregistrations.reject');


    $tauriConfigPayload = function (\App\Models\User $user): array {
        $settings = $user->resolvedSettings();
        $security = $user->securityPreferences();
        $billingUser = $user->billingUser();
        $planLabels = [
            'clinica' => 'Clinica',
            'hospital' => 'Hospital',
            'red_medica' => 'Red Medica',
        ];
        $statusLabels = [
            'active' => 'Activo',
            'trialing' => 'Prueba',
            'past_due' => 'Pago pendiente',
            'canceled' => 'Cancelado',
        ];
        $parts = collect(preg_split('/\s+/', trim((string) $user->name)) ?: [])
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');

        return [
            'ok' => true,
            'settings' => $settings,
            'security' => $security,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'initials' => $parts ?: 'DR',
                'role' => $user->specialty ?: ($user->position ?: 'Medico'),
                'specialty' => $user->specialty,
                'professional_license' => $user->professional_license,
                'clinic' => $user->clinica?->nombre ?? 'ENCLAII',
                'has_signature' => (bool) $user->signature_path,
                'signature_updated_at' => optional($user->signature_updated_at)->format('d/m/Y H:i'),
            ],
            'plan' => [
                'label' => $planLabels[$billingUser->stripe_plan] ?? 'Basico',
                'status' => $billingUser->subscription_status,
                'status_label' => $statusLabels[$billingUser->subscription_status] ?? ucfirst((string) ($billingUser->subscription_status ?: 'activo')),
                'member_limit' => $billingUser->clinicMemberLimit(),
                'renews_at' => optional($billingUser->subscription_renews_at)->format('d/m/Y'),
            ],
        ];
    };

    Route::get('/tauri/configuracion', function (\Illuminate\Http\Request $request) use ($tauriQrCors, $tauriQrUser, $tauriConfigPayload) {
        $user = $tauriQrUser($request);

        if (! $user) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'Credenciales de Laravel requeridas.'], 401), $request);
        }

        if (! $user->subscribed()) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'El usuario no tiene una suscripcion activa.'], 403), $request);
        }

        return $tauriQrCors(response()->json($tauriConfigPayload($user)), $request);
    })->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.configuracion.index');

    Route::post('/tauri/configuracion', function (\Illuminate\Http\Request $request) use ($tauriQrCors, $tauriQrUser, $tauriConfigPayload) {
        $user = $tauriQrUser($request);

        if (! $user) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'Credenciales de Laravel requeridas.'], 401), $request);
        }

        if (! $user->subscribed()) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => 'El usuario no tiene una suscripcion activa.'], 403), $request);
        }

        $validator = validator($request->all(), [
            'timezone' => ['sometimes', 'timezone', 'max:50'],
            'date_format' => ['sometimes', 'string', 'max:20'],
            'time_format' => ['sometimes', 'in:12 horas (AM/PM),24 horas', 'max:30'],
            'autosave' => ['sometimes', 'boolean'],
            'confirm_delete' => ['sometimes', 'boolean'],
            'default_view' => ['sometimes', 'string', 'max:50'],
            'items_per_page' => ['sometimes', 'string', 'max:10'],
            'animations' => ['sometimes', 'boolean'],
            'compact' => ['sometimes', 'boolean'],
            'reading_mode' => ['sometimes', 'boolean'],
            'notif_email' => ['sometimes', 'boolean'],
            'notif_push' => ['sometimes', 'boolean'],
            'notif_new_studies' => ['sometimes', 'boolean'],
            'notif_reports' => ['sometimes', 'boolean'],
            'notif_reminders' => ['sometimes', 'boolean'],
            'qr_default_expiration_hours' => ['sometimes', 'string', \Illuminate\Validation\Rule::in(['24', '48', '168'])],
            'qr_default_patient_message' => ['sometimes', 'nullable', 'string', 'max:150'],
            'qr_whatsapp_template' => ['sometimes', 'nullable', 'string', 'max:500'],
            'qr_patient_photo_enabled' => ['sometimes', 'boolean'],
            'qr_patient_photo_required' => ['sometimes', 'boolean'],
            'qr_allow_camera_photo' => ['sometimes', 'boolean'],
            'qr_allow_gallery_photo' => ['sometimes', 'boolean'],
            'qr_required_fields' => ['sometimes', 'array'],
            'qr_required_fields.*' => [
                'string',
                \Illuminate\Validation\Rule::in([
                    'identificacion',
                    'sexo',
                    'email',
                    'direccion',
                    'peso',
                    'altura',
                    'procedimiento',
                    'motivo_consulta',
                    'alergias',
                    'enfermedades',
                    'medicamentos_actuales',
                    'antecedentes_medicos',
                    'observaciones',
                ]),
            ],
            'qr_consent_text' => ['sometimes', 'nullable', 'string', 'max:700'],
            'qr_duplicate_check' => ['sometimes', 'boolean'],
            'qr_duplicate_action' => ['sometimes', 'string', \Illuminate\Validation\Rule::in(['warn', 'block_acceptance'])],
            'capture_auto_capture' => ['sometimes', 'boolean'],
            'capture_auto_save' => ['sometimes', 'boolean'],
            'capture_auto_interval' => ['sometimes', 'integer', 'min:5', 'max:300'],
            'require_password_for_studies' => ['sometimes', 'boolean'],
            'require_password_for_patients' => ['sometimes', 'boolean'],
            'audit_sensitive_actions' => ['sometimes', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $tauriQrCors(response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422), $request);
        }

        $validated = $validator->validated();
        $allowedSettings = array_keys(\App\Models\User::defaultSettings());
        $incomingSettings = array_intersect_key($validated, array_flip($allowedSettings));

        if ($incomingSettings !== []) {
            $user->settings = array_merge($user->settings ?? [], $incomingSettings);
            $user->save();
        }

        $securityKeys = [
            'require_password_for_studies',
            'require_password_for_patients',
            'audit_sensitive_actions',
        ];
        $incomingSecurity = array_intersect_key($validated, array_flip($securityKeys));

        if ($incomingSecurity !== []) {
            $security = $user->securitySetting()->updateOrCreate(
                ['user_id' => $user->id],
                array_merge($user->securityPreferences(), $incomingSecurity)
            );
            $user->setRelation('securitySetting', $security);
        }

        return $tauriQrCors(response()->json($tauriConfigPayload($user->fresh() ?? $user)), $request);
    })->withoutMiddleware(['auth', 'auth.session', 'subscribed'])->name('tauri.configuracion.update');
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

    Route::get('/soporte', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'index'])->name('soporte');
    Route::get('/soporte/{conversation}', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'show'])->name('soporte.chat');
    Route::post('/soporte/{conversation}/tomar', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'take'])->name('soporte.take');
    Route::post('/soporte/{conversation}/responder', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'reply'])->name('soporte.reply');
    Route::get('/api/soporte/pendientes', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'pending'])->name('api.soporte.pending');
    Route::get('/api/soporte/{conversation}/poll', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'poll'])->name('api.soporte.poll');
    Route::post('/api/soporte/{conversation}/cerrar', [\App\Http\Controllers\CustomerSuccess\SoporteAgentController::class, 'close'])->name('api.soporte.close');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/capture/pairing-code', [CapturePairingCodeController::class, 'store'])
        ->name('capture.pairing-code.store');
});
