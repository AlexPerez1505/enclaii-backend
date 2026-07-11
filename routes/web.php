<?php

use App\Http\Controllers\Auth\EndoCareAuthController;
use App\Http\Controllers\IaReporteController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\NuevoEstudioController;
use App\Models\Paciente;
use App\Models\Reporte;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ConfigurationBackupController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserSessionController;
use App\Http\Controllers\ClinicaMemberController;
use App\Http\Controllers\CriticalSecurityController;
use App\Http\Controllers\SecuritySettingsController;
use App\Http\Controllers\QrRegistrationController;
use App\Http\Controllers\PublicPatientPreregistrationController;

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

        return view('dashboard.index', compact(
            'estudiosSinReporte', 'proximaCita', 'pendientesHoy',
            'citasProximas', 'citasCompletadas', 'citasCanceladas'
        ));
    })->name('dashboard');
    

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
                ->map(fn ($a) => media_url($a->path))
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
    
    // Route::get('/finanzas', function () {
    //     return view('finanzas.index');
    // })->name('finanzas');

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

    Route::get('/nuevo-estudio/importar', function () {
        return view('estudios.importar');
    })->name('nuevo-estudio.importar');

    Route::post('/nuevo-estudio', [NuevoEstudioController::class, 'store'])
        ->name('nuevo-estudio.store');

    Route::get('/nuevo-estudio/capturas', function () {
        return view('estudios.capturas');
    })->name('nuevo-estudio.capturas');

    Route::post('/nuevo-estudio/capturas', [NuevoEstudioController::class, 'guardarCapturas'])
        ->name('nuevo-estudio.capturas.store');

    Route::get('/nuevo-estudio/configuracion', function () {
        return view('estudios.configuracion');
    })->name('nuevo-estudio.configuracion');

    Route::get('/nuevo-estudio/grabando', [NuevoEstudioController::class, 'grabando'])
        ->name('nuevo-estudio.grabando');

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
    // Route::get('/finanzas', function () {
    //     return view('finanzas.index');
    // })->name('finanzas');
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
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
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
                'message' => 'El usuario no tiene una suscripción activa.',
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
                'message' => 'El usuario no tiene una suscripción activa.',
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
                'age' => $paciente->edad ? $paciente->edad.' años' : '',
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

    Route::resource('pacientes', PacienteController::class)
        ->middlewareFor(['update', 'destroy'], 'critical.password:patients');

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

Route::post('/ia/chat', [AiAssistantController::class, 'chat'])
    ->name('ia.chat');



Route::middleware(['auth'])->group(function () {
    Route::post('/ia/conversations/start', [AiAssistantController::class, 'start'])->name('ia.conversations.start');
    Route::get('/ia/conversations', [AiAssistantController::class, 'conversations'])->name('ia.conversations');
    Route::get('/ia/conversations/{conversation}', [AiAssistantController::class, 'show'])->name('ia.conversations.show');

    Route::post('/ia/chat', [AiAssistantController::class, 'chat'])->name('ia.chat');
    Route::get('/ia/history', [AiAssistantController::class, 'history'])->name('ia.history');
    Route::post('/ia/reset', [AiAssistantController::class, 'reset'])->name('ia.reset');
});
