{{-- ============ PANEL: PLAN Y ALMACENAMIENTO ============ --}}
<div class="cfg-panel" data-panel="plan">
  <div class="pl-grid">

    {{-- Columna izquierda --}}
    <div class="cfg-col">
      <article class="card rise d2">
        <div class="cfg-card-head">
          <h2>Plan y almacenamiento</h2>
          <p>Administra tu plan actual, uso de almacenamiento y opciones disponibles</p>
        </div>

        <div class="pl-sub">Tu plan actual</div>
        <div class="pl-plan">
          <span class="pl-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l4 3 5-7 5 7 4-3-2 12H5L3 8z"/></svg></span>
          <div class="pl-info">
            <b>Plan {{ ucfirst(str_replace('_', ' ', auth()->user()->stripe_plan ?? 'Gratuito')) }}</b>
            <span class="badge" style="background:{{ auth()->user()->subscription_status === 'active' ? 'rgba(61,220,151,.14)' : 'rgba(255,160,0,.14)' }};color:{{ auth()->user()->subscription_status === 'active' ? 'var(--green)' : 'var(--orange)' }}">
              {{ ucfirst(auth()->user()->subscription_status ?? 'Inactivo') }}
            </span>
            @if(auth()->user()->subscription_renews_at)
              <p>Renovación {{ auth()->user()->subscription_renews_at->format('d/m/Y') }}</p>
            @endif
          </div>
          <a href="#" class="pl-btn" id="gpOpen">Gestionar plan</a>
        </div>

        <div class="pl-detail" style="margin-top:18px">
          <div class="store-top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg> Almacenamiento en la nube</div>
          <div class="store-meta" style="margin-top:11px"><span>78.5 GB de 100 GB utilizados</span></div>
          <div class="store-bar"><i data-w="78.5"></i></div>
          <div class="store-legend"><span><i class="used"></i>Usado: 78.5 GB</span><span><i class="free"></i>Disponible: 21.5 GB</span></div>

          <div class="pl-files">
            <div class="pl-file"><span class="fi c2"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></span><div class="ft">Imágenes</div><div class="fv">45.8 GB</div><div class="fp">58%</div></div>
            <div class="pl-file"><span class="fi c3"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg></span><div class="ft">Videos</div><div class="fv">18.2 GB</div><div class="fp">23%</div></div>
            <div class="pl-file"><span class="fi c4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg></span><div class="ft">Otros archivos</div><div class="fv">2.0 GB</div><div class="fp">3%</div></div>
          </div>

          <div class="pl-detail">
            <div class="t">¿Qué ocupa espacio?</div>
            <div class="d">Consulta el detalle de los archivos que más almacenamiento consumen</div>
            <a href="#" class="tbl-link"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v4H3zM3 10h18v4H3zM3 17h18v4H3z"/></svg> Ver detalle de almacenamiento</a>
          </div>
        </div>
      </article>

      <article class="card rise d4">
        <div class="cfg-card-head">
          <h2>¿Necesitas más espacio?</h2>
          <p>Actualiza tu plan y obtén más almacenamiento y beneficios adicionales</p>
        </div>

        <div class="pl-plans">
          <div class="pl-card {{ auth()->user()->stripe_plan === 'clinica' ? 'current' : '' }}" data-card="clinica">
            <div class="pc-top">
              <span class="pc-ico" style="color:var(--green)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg></span>
              @if(auth()->user()->stripe_plan === 'clinica')
                <span class="pc-badge">Plan actual</span>
              @endif
            </div>
            <h4>Clinica</h4><div class="pc-gb">50 GB</div>
            <ul class="pc-feat">
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Almacenamiento en la nube</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>IA Reportes básica</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Soporte por email</li>
            </ul>
            <div class="pc-interval" style="display:flex;gap:6px;margin:12px 0;justify-content:center">
              <button class="pc-int-btn active" data-interval="month" data-price="$10,000" data-label="/mes">Mensual</button>
              <button class="pc-int-btn" data-interval="quarter" data-price="$20,000" data-label="/3 meses">Trimestral</button>
              <button class="pc-int-btn" data-interval="year" data-price="$85,000" data-label="/año">Anual</button>
            </div>
            <div class="pc-price">$10,000<span> /mes</span></div>
            <a href="#" class="pc-cta {{ auth()->user()->stripe_plan === 'clinica' ? 'disabled' : '' }}" data-plan="clinica" data-interval="month">
              {{ auth()->user()->stripe_plan === 'clinica' ? 'Plan actual' : 'Cambiar a Clinica' }}
            </a>
          </div>

          <div class="pl-card {{ auth()->user()->stripe_plan === 'hospital' ? 'current' : '' }}" data-card="hospital">
            <div class="pc-top">
              <span class="pc-ico" style="color:#a47bff"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l4 3 5-7 5 7 4-3-2 12H5L3 8z"/></svg></span>
              @if(auth()->user()->stripe_plan === 'hospital')
                <span class="pc-badge">Plan actual</span>
              @endif
            </div>
            <h4>Hospital</h4><div class="pc-gb">100 GB</div>
            <ul class="pc-feat">
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>IA Reportes avanzada</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Almacenamiento ampliado</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Soporte prioritario</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Exportación de reportes</li>
            </ul>
            <div class="pc-interval" style="display:flex;gap:6px;margin:12px 0;justify-content:center">
              <button class="pc-int-btn active" data-interval="month" data-price="$25,000" data-label="/mes">Mensual</button>
              <button class="pc-int-btn" data-interval="quarter" data-price="$65,000" data-label="/3 meses">Trimestral</button>
              <button class="pc-int-btn" data-interval="year" data-price="$200,000" data-label="/año">Anual</button>
            </div>
            <div class="pc-price">$25,000<span> /mes</span></div>
            <a href="#" class="pc-cta {{ auth()->user()->stripe_plan === 'hospital' ? 'disabled' : '' }}" data-plan="hospital" data-interval="month">
              {{ auth()->user()->stripe_plan === 'hospital' ? 'Plan actual' : 'Cambiar a Hospital' }}
            </a>
          </div>

          <div class="pl-card {{ auth()->user()->stripe_plan === 'red_medica' ? 'current' : '' }}" data-card="red_medica">
            <div class="pc-top">
              <span class="pc-ico" style="color:var(--red)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></span>
              @if(auth()->user()->stripe_plan === 'red_medica')
                <span class="pc-badge">Plan actual</span>
              @endif
            </div>
            <h4>Red medica</h4><div class="pc-gb">250 GB</div>
            <ul class="pc-feat">
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Todo lo del plan Profesional</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Más almacenamiento</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Integraciones avanzadas</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Soporte 24/7</li>
            </ul>
            <div class="pc-interval" style="display:flex;gap:6px;margin:12px 0;justify-content:center">
              <button class="pc-int-btn active" data-interval="month" data-price="$35,000" data-label="/mes">Mensual</button>
              <button class="pc-int-btn" data-interval="quarter" data-price="$90,000" data-label="/3 meses">Trimestral</button>
              <button class="pc-int-btn" data-interval="year" data-price="$385,000" data-label="/año">Anual</button>
            </div>
            <div class="pc-price">$35,000<span> /mes</span></div>
            <a href="#" class="pc-cta {{ auth()->user()->stripe_plan === 'red_medica' ? 'disabled' : '' }}" data-plan="red_medica" data-interval="month">
              {{ auth()->user()->stripe_plan === 'red_medica' ? 'Plan actual' : 'Cambiar a Red Médica' }}
            </a>
          </div>

        </div>
      </article>
    </div>

    {{-- Columna derecha --}}
    <div class="cfg-col">
      <article class="card rise d3">
        <div class="cfg-card-head"><h2>Resumen de tu plan</h2></div>
        <div class="pl-summary-row">
          <span class="k">Plan actual</span>
          <span class="v">Plan {{ ucfirst(str_replace('_', ' ', auth()->user()->stripe_plan ?? 'Gratuito')) }}</span>
        </div>
        <div class="pl-summary-row">
          <span class="k">Estado</span>
          <span class="v" style="color:{{ auth()->user()->subscription_status === 'active' ? 'var(--green)' : 'var(--orange)' }}">
            {{ ucfirst(auth()->user()->subscription_status ?? 'Inactivo') }}
          </span>
        </div>
        @if(auth()->user()->subscription_renews_at)
        <div class="pl-summary-row">
          <span class="k">Renovación</span>
          <span class="v">{{ auth()->user()->subscription_renews_at->format('d/m/Y') }}</span>
        </div>
        @endif
        @if(auth()->user()->pm_last_four)
        <div class="pl-summary-row">
          <span class="k">Método de pago</span>
          <span class="v pl-pay">
            @if(auth()->user()->pm_brand === 'visa')
              <svg width="26" height="17" viewBox="0 0 48 32" style="display:inline-block;vertical-align:middle;margin-right:6px"><rect width="48" height="32" rx="4" fill="#1434CB"/><text x="24" y="20" fill="white" font-size="14" font-weight="bold" text-anchor="middle">VISA</text></svg>
            @elseif(auth()->user()->pm_brand === 'mastercard')
              <span class="pl-mc"></span>
            @else
              <span style="text-transform:capitalize">{{ auth()->user()->pm_brand }}</span>
            @endif
            ····{{ auth()->user()->pm_last_four }}
          </span>
        </div>
        @endif
      </article>

      <article class="card rise d4">
        <div class="cfg-card-head"><h2>Facturación y pago</h2></div>
        @if(auth()->user()->subscription_renews_at)
          <a href="#" class="pl-link">
            <span>Próxima fecha de cobro</span>
            <span class="v">{{ auth()->user()->subscription_renews_at->format('d/m/Y') }}</span>
          </a>
        @endif
        <a href="#" class="pl-link" id="gpOpenBilling">
          <span>Historial de facturas</span>
          <span class="v">Ver</span>
        </a>
        <a href="#" class="pl-wide-btn" data-pm-open="1">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
          {{ auth()->user()->pm_last_four ? 'Actualizar método de pago' : 'Agregar método de pago' }}
        </a>
      </article>

      <article class="card rise d5">
        <div class="cfg-card-head"><h2>Historial de uso</h2><p>Tu consumo de almacenamiento en los últimos 6 meses</p></div>
        <div class="pl-chart-wrap">
          <div class="pl-chart-y"><span>100 GB</span><span>75 GB</span><span>50 GB</span><span>25 GB</span><span>0 GB</span></div>
          <div class="pl-chart">
            <svg viewBox="0 0 300 130" preserveAspectRatio="none">
              <defs>
                <linearGradient id="plArea" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="rgba(56,199,244,.35)"/>
                  <stop offset="100%" stop-color="rgba(56,199,244,0)"/>
                </linearGradient>
              </defs>
              <line x1="0" y1="13" x2="300" y2="13" stroke="rgba(110,160,255,.1)" stroke-width="1"/>
              <line x1="0" y1="42" x2="300" y2="42" stroke="rgba(110,160,255,.1)" stroke-width="1"/>
              <line x1="0" y1="71" x2="300" y2="71" stroke="rgba(110,160,255,.1)" stroke-width="1"/>
              <line x1="0" y1="100" x2="300" y2="100" stroke="rgba(110,160,255,.1)" stroke-width="1"/>
              <path d="M20,92 L72,82 L124,70 L176,55 L228,42 L280,33 L280,118 L20,118 Z" fill="url(#plArea)"/>
              <polyline points="20,92 72,82 124,70 176,55 228,42 280,33" fill="none" stroke="var(--cyan)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
              <g fill="var(--cyan)">
                <circle cx="20" cy="92" r="3.5"/><circle cx="72" cy="82" r="3.5"/><circle cx="124" cy="70" r="3.5"/>
                <circle cx="176" cy="55" r="3.5"/><circle cx="228" cy="42" r="3.5"/><circle cx="280" cy="33" r="3.5"/>
              </g>
            </svg>
            <div style="display:flex;justify-content:space-between;font-size:9.5px;color:var(--txt-soft);margin-top:4px;padding:0 10px">
              <span>Nov 24</span><span>Dic 24</span><span>Ene 25</span><span>Feb 25</span>
            </div>
          </div>
        </div>
      </article>

      <article class="card rise d5">
        <div class="pl-reco">
          <span class="ri"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7V17h8v-2.3A7 7 0 0 0 12 2z"/></svg></span>
          <h2 style="font-family:'Sora',sans-serif;font-size:15px;font-weight:700">Recomendaciones</h2>
        </div>
        <p style="font-size:12px;color:var(--txt-soft);margin:6px 0 12px">Estás por llegar al 0% de tu almacenamiento. Considera liberar espacio o actualizar tu plan para evitar interrupciones.</p>
        <a href="#" class="pl-wide-btn">Ver recomendaciones <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></a>
      </article>
    </div>

  </div>
</div>

{{-- ============ MODAL: GESTIONAR PLAN ============ --}}
<div class="gp-ov" id="gpModal" aria-hidden="true">
  <div class="gp-modal" role="dialog" aria-modal="true" aria-labelledby="gpTitle">
    <button class="gp-x" id="gpClose" aria-label="Cerrar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>

    <div class="gp-head">
      <h2 id="gpTitle">Gestionar plan</h2>
      <p>Administra tu plan, integrantes, consumo de AI y almacenamiento</p>
    </div>

    <div class="gp-tabs">
      <button class="gp-tab active" data-gptab="resumen"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Resumen</button>
      <button class="gp-tab" data-gptab="integrantes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Integrantes</button>
      <button class="gp-tab" data-gptab="facturacion"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Facturacion</button>
    </div>

    <div class="gp-body">
      {{-- ===== RESUMEN ===== --}}
      <div class="gp-panel active" data-gppanel="resumen">
        <div class="gp-grid">

          <section class="gp-card">
            <h3>Resumen del plan</h3>
            <div class="gp-plan">
              <span class="gp-crown"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l4 3 5-7 5 7 4-3-2 12H5L3 8z"/></svg></span>
              <div class="gp-plan-info">
                <div class="gp-plan-name">
                  <b>Plan {{ ucfirst(str_replace('_', ' ', auth()->user()->stripe_plan ?? 'Gratuito')) }}</b>
                  <span class="gp-badge" style="background:{{ auth()->user()->subscription_status === 'active' ? 'rgba(61,220,151,.14)' : 'rgba(255,160,0,.14)' }};color:{{ auth()->user()->subscription_status === 'active' ? 'var(--green)' : 'var(--orange)' }}">
                    {{ ucfirst(auth()->user()->subscription_status ?? 'Inactivo') }}
                  </span>
                </div>
                @if(auth()->user()->subscription_renews_at)
                  <p>Renovacion {{ auth()->user()->subscription_renews_at->format('d/m/Y') }}</p>
                @endif
                @if(auth()->user()->pm_last_four)
                  <p>Tarjeta: {{ ucfirst(auth()->user()->pm_brand) }} ····{{ auth()->user()->pm_last_four }}</p>
                @endif
              </div>
              <ul class="gp-feat">
                @php
                  $planFeatures = [
                    'clinica' => ['50 GB de almacenamiento en la nube', 'IA Reportes basica', 'Soporte por email'],
                    'hospital' => ['100 GB de almacenamiento en la nube', 'IA Reportes avanzada', 'Soporte prioritario', 'Exportacion de reportes'],
                    'red_medica' => ['250 GB de almacenamiento en la nube', 'Integraciones avanzadas', 'Soporte 24/7'],
                  ];
                  $currentPlan = auth()->user()->stripe_plan;
                  $features = $planFeatures[$currentPlan] ?? ['Plan gratuito'];
                @endphp
                @foreach($features as $feat)
                  <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>{{ $feat }}</li>
                @endforeach
              </ul>
            </div>
          </section>

          <section class="gp-card">
            <h3>Consumo de AI <span class="gp-soft">(este mes)</span></h3>
            <div class="gp-ai-top">
              <span class="gp-ai-num">342</span><span class="gp-soft">de 500 reportes generados</span>
              <span class="gp-ai-pct">68%</span>
            </div>
            <div class="gp-bar"><i style="width:68%"></i></div>
            <p class="gp-soft gp-mt">Limite del plan: 500 reportes/mes &#9432;</p>
            <a href="#" class="gp-btn-ghost gp-mt2"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>Ver uso detallado de IA</a>
          </section>

          <section class="gp-card">
            <div class="gp-card-row">
              <h3>Integrantes del plan</h3>
              <a href="#" class="gp-btn-ghost sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Invitar integrante</a>
            </div>
            <p class="gp-soft">Administra los usuarios que forman parte de tu plan</p>
            <div class="gp-bar gp-mt"><i style="width:80%"></i></div>
            <div class="gp-mini">4 de 5 usuarios utilizados <span>80%</span></div>
            <table class="gp-table">
              <thead><tr><th>Usuario</th><th>Rol</th><th>Estado</th><th>Ultimo acceso</th><th>Acciones</th></tr></thead>
              <tbody>
                <tr><td><span class="gp-u">Dr. Victor <span class="gp-you">Tu</span></span></td><td>Administrador</td><td><span class="gp-st">Activo</span></td><td>Hoy, 10:30 AM</td><td><button class="gp-dots" aria-label="Opciones">&#8943;</button></td></tr>
                <tr><td><span class="gp-u">Dra. Ana Perez</span></td><td>Endoscopista</td><td><span class="gp-st">Activo</span></td><td>Hoy, 09:15 AM</td><td><button class="gp-dots" aria-label="Opciones">&#8943;</button></td></tr>
                <tr><td><span class="gp-u">Dr. Juan Lopez</span></td><td>Endoscopista</td><td><span class="gp-st">Activo</span></td><td>Ayer, 04:22 PM</td><td><button class="gp-dots" aria-label="Opciones">&#8943;</button></td></tr>
                <tr><td><span class="gp-u">Lic. Maria Gomez</span></td><td>Recepcionista</td><td><span class="gp-st">Activo</span></td><td>Ayer, 11:08 AM</td><td><button class="gp-dots" aria-label="Opciones">&#8943;</button></td></tr>
              </tbody>
            </table>
            <p class="gp-note">&#9432; Puedes agregar hasta <b>1</b> usuario mas con tu plan actual.</p>
          </section>

          <section class="gp-card">
            <h3>Comprar mas almacenamiento</h3>
            <p class="gp-soft">Aumenta tu espacio en la nube al instante</p>
            <div class="gp-store gp-mt">
              <div class="gp-store-card">
                <div class="gp-store-gb">+ 50 GB</div>
                <div class="gp-store-price">$99 MXN/mes</div>
                <a href="#" class="gp-btn-out" data-plan="storage_50">Agregar</a>
              </div>
              <div class="gp-store-card">
                <div class="gp-store-gb">+ 100 GB</div>
                <div class="gp-store-price">$179 MXN / mes</div>
                <a href="#" class="gp-btn-out" data-plan="storage_100">Agregar</a>
              </div>
            </div>
            <p class="gp-note"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg> El espacio adicional se a&ntilde;adira a tu plan actual.</p>
          </section>

        </div>

        @if(auth()->user()->subscribed() && auth()->user()->cancelScheduled())
        {{-- Suscripción ya programada para cancelarse: ofrecer reactivar --}}
        <div class="gp-cancel gp-resume" id="resumeBox">
          <span class="gp-cancel-ico gp-resume-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
          <div class="gp-cancel-txt">
            <b>Plan programado para cancelarse</b>
            <p>Tu plan permanecerá activo hasta el <b>{{ auth()->user()->subscription_cancel_at->format('d/m/Y') }}</b>. Después perderás el acceso a las funciones premium.</p>
          </div>
          <a href="#" class="gp-cancel-btn gp-resume-btn" id="resumeBtn">Reactivar plan</a>
        </div>
        @elseif(auth()->user()->subscribed())
        <div class="gp-cancel" id="cancelBox">
          <span class="gp-cancel-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
          <div class="gp-cancel-txt"><b>Cancelar plan</b><p>Si cancelas tu plan, perderas acceso a las funciones premium al finalizar el ciclo de facturacion</p></div>
          <a href="#" class="gp-cancel-btn" id="cancelBtn">Cancelar plan</a>
        </div>
        @endif
      </div>

      {{-- ===== INTEGRANTES ===== --}}
      <div class="gp-panel" data-gppanel="integrantes">
        <section class="gp-card">
          <div class="gp-card-row">
            <h3>Integrantes del plan</h3>
            <a href="#" class="gp-btn-ghost sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Invitar integrante</a>
          </div>
          <p class="gp-soft">4 de 5 usuarios utilizados. Puedes agregar hasta 1 usuario mas con tu plan actual.</p>
          <table class="gp-table gp-mt">
            <thead><tr><th>Usuario</th><th>Rol</th><th>Estado</th><th>Ultimo acceso</th><th>Acciones</th></tr></thead>
            <tbody>
              <tr><td><span class="gp-u">Dr. Victor <span class="gp-you">Tu</span></span></td><td>Administrador</td><td><span class="gp-st">Activo</span></td><td>Hoy, 10:30 AM</td><td><button class="gp-dots" aria-label="Opciones">&#8943;</button></td></tr>
              <tr><td><span class="gp-u">Dra. Ana Perez</span></td><td>Endoscopista</td><td><span class="gp-st">Activo</span></td><td>Hoy, 09:15 AM</td><td><button class="gp-dots" aria-label="Opciones">&#8943;</button></td></tr>
              <tr><td><span class="gp-u">Dr. Juan Lopez</span></td><td>Endoscopista</td><td><span class="gp-st">Activo</span></td><td>Ayer, 04:22 PM</td><td><button class="gp-dots" aria-label="Opciones">&#8943;</button></td></tr>
              <tr><td><span class="gp-u">Lic. Maria Gomez</span></td><td>Recepcionista</td><td><span class="gp-st">Activo</span></td><td>Ayer, 11:08 AM</td><td><button class="gp-dots" aria-label="Opciones">&#8943;</button></td></tr>
            </tbody>
          </table>
        </section>
      </div>

      {{-- ===== FACTURACION ===== --}}
      <div class="gp-panel" data-gppanel="facturacion">
        <section class="gp-card">
          <h3>Facturacion y pago</h3>
          <div class="gp-summary-row">
            <span class="gp-soft">Plan actual</span>
            <span>Plan {{ ucfirst(str_replace('_', ' ', auth()->user()->stripe_plan ?? 'Gratuito')) }}</span>
          </div>
          <div class="gp-summary-row">
            <span class="gp-soft">Estado</span>
            <span style="color:{{ auth()->user()->subscription_status === 'active' ? 'var(--green)' : 'var(--orange)' }}">
              {{ ucfirst(auth()->user()->subscription_status ?? 'Inactivo') }}
            </span>
          </div>
          @if(auth()->user()->subscription_renews_at)
          <div class="gp-summary-row">
            <span class="gp-soft">Proxima fecha de cobro</span>
            <span>{{ auth()->user()->subscription_renews_at->format('d/m/Y') }}</span>
          </div>
          @endif
          @if(auth()->user()->pm_last_four)
          <div class="gp-summary-row">
            <span class="gp-soft">Metodo de pago</span>
            <span>{{ ucfirst(auth()->user()->pm_brand) }} ····{{ auth()->user()->pm_last_four }}</span>
          </div>
          @endif
          <a href="#" class="gp-btn-out gp-mt2" data-pm-open="1" style="display:inline-flex;align-items:center;gap:8px">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            {{ auth()->user()->pm_last_four ? 'Actualizar metodo de pago' : 'Agregar metodo de pago' }}
          </a>
        </section>

        <section class="gp-card gp-mt2">
          <h3>Historial de pagos</h3>
          <div id="invoiceHistory" class="inv-list">
            <div class="inv-loading">Cargando historial...</div>
          </div>
        </section>
      </div>
    </div>

    <div class="gp-foot">
      <button class="gp-cerrar" id="gpClose2">Cerrar</button>
    </div>
  </div>
</div>

@push('styles')
<style>
/* ===== Modal Gestionar plan ===== */
.gp-ov{position:fixed;inset:0;z-index:1000;display:none;align-items:flex-start;justify-content:center;padding:32px 18px;background:rgba(5,9,20,.66);backdrop-filter:blur(3px);overflow-y:auto}
.gp-ov.open{display:flex}
.gp-modal{position:relative;width:100%;max-width:960px;background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-lg);box-shadow:0 30px 80px -20px var(--shadow);padding:26px 28px 0;animation:gpIn .22s var(--ease-out)}
@keyframes gpIn{from{opacity:0;transform:translateY(14px) scale(.98)}to{opacity:1;transform:none}}
.gp-x{position:absolute;top:20px;right:20px;width:34px;height:34px;display:grid;place-items:center;border-radius:9px;color:var(--txt-soft)}
.gp-x svg{width:20px;height:20px}
.gp-x:hover{background:var(--hover-bg);color:var(--txt)}
.gp-head h2{font-family:'Sora',sans-serif;font-size:21px;font-weight:700}
.gp-head p{font-size:13px;color:var(--txt-soft);margin-top:4px}
.gp-tabs{display:flex;gap:26px;border-bottom:1px solid var(--stroke);margin:18px 0 0}
.gp-tab{display:inline-flex;align-items:center;gap:8px;padding:0 2px 12px;font-size:14px;font-weight:600;color:var(--txt-soft);border:0;background:none;cursor:pointer;position:relative}
.gp-tab svg{width:17px;height:17px}
.gp-tab.active{color:var(--cyan)}
.gp-tab.active::after{content:"";position:absolute;left:0;right:0;bottom:-1px;height:2px;border-radius:2px;background:var(--cyan)}
@media(hover:hover){.gp-tab:hover{color:var(--txt)}}
.gp-body{padding:20px 0 4px}
.gp-panel{display:none}
.gp-panel.active{display:block}
/* Botones de intervalo */
.pc-int-btn{padding:6px 12px;border-radius:8px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);font-size:11.5px;font-weight:600;cursor:pointer;transition:all .15s ease}
.pc-int-btn:hover{background:var(--hover-bg);color:var(--txt)}
.pc-int-btn.active{background:var(--cyan);color:#fff;border-color:var(--cyan)}
.gp-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:760px){.gp-grid{grid-template-columns:1fr}}
.gp-card{border:1px solid var(--stroke);border-radius:var(--r-md);background:var(--panel-2);padding:16px 17px}
.gp-card h3{font-family:'Sora',sans-serif;font-size:14.5px;font-weight:700;margin-bottom:13px}
.gp-soft{color:var(--txt-soft);font-weight:500;font-size:12.5px}
.gp-mt{margin-top:11px}
.gp-mt2{margin-top:14px}
/* Resumen del plan */
.gp-plan{display:grid;grid-template-columns:auto 1fr;gap:13px;align-items:start}
.gp-crown{width:46px;height:46px;flex:none;border-radius:12px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,#7c5cff,#a47bff)}
.gp-crown svg{width:24px;height:24px}
.gp-plan-name b{font-family:'Sora',sans-serif;font-size:15px}
.gp-badge{display:inline-block;margin-left:8px;font-size:10px;font-weight:700;color:var(--green);background:rgba(61,220,151,.14);padding:2px 8px;border-radius:6px;vertical-align:middle}
.gp-plan-info p{font-size:12px;color:var(--txt-soft);margin-top:3px}
.gp-feat{grid-column:1/-1;list-style:none;margin-top:6px;display:flex;flex-direction:column;gap:8px}
.gp-feat li{display:flex;align-items:center;gap:9px;font-size:12.5px}
.gp-feat li svg{width:16px;height:16px;color:var(--green);flex:none}
.gp-feat li.nochk{padding-left:25px;color:var(--txt-soft)}
/* Consumo AI */
.gp-ai-top{display:flex;align-items:baseline;flex-wrap:wrap;gap:7px}
.gp-ai-num{font-family:'Sora',sans-serif;font-size:26px;font-weight:800;line-height:1}
.gp-ai-pct{margin-left:auto;font-family:'Sora',sans-serif;font-size:17px;font-weight:700;color:var(--cyan)}
.gp-bar{height:8px;border-radius:99px;background:var(--stroke);overflow:hidden;margin-top:12px}
.gp-bar i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--blue),var(--cyan))}
.gp-btn-ghost{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:10px;border-radius:10px;border:1px solid var(--stroke-strong);color:var(--cyan);font-weight:600;font-size:13px}
.gp-btn-ghost svg{width:16px;height:16px}
.gp-btn-ghost.sm{width:auto;padding:7px 12px;border:0;background:rgba(46,123,246,.12)}
.gp-btn-ghost:hover{background:var(--hover-bg)}
/* Integrantes */
.gp-card-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:4px}
.gp-card-row h3{margin-bottom:0}
.gp-mini{display:flex;justify-content:space-between;font-size:11.5px;color:var(--txt-soft);margin-top:7px;margin-bottom:6px}
.gp-table{width:100%;border-collapse:collapse;font-size:12px}
.gp-table th{text-align:left;font-weight:600;color:var(--txt-soft);font-size:11px;padding:7px 8px;border-bottom:1px solid var(--stroke)}
.gp-table td{padding:9px 8px;border-bottom:1px solid rgba(110,160,255,.08)}
.gp-table tr:last-child td{border-bottom:0}
.gp-u{font-weight:600}
.gp-you{font-size:9.5px;font-weight:700;color:#fff;background:var(--blue);padding:1px 6px;border-radius:5px;margin-left:5px}
.gp-st{font-size:10px;font-weight:700;color:var(--green);background:rgba(61,220,151,.14);padding:2px 8px;border-radius:6px}
.gp-dots{color:var(--txt-soft);font-size:16px;padding:0 6px;line-height:1}
.gp-dots:hover{color:var(--txt)}
.gp-note{display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--txt-soft);margin-top:12px}
.gp-note svg{width:14px;height:14px;flex:none}
/* Comprar almacenamiento */
.gp-store{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.gp-store-card{border:1px solid var(--stroke);border-radius:var(--r-md);background:var(--card);padding:15px;text-align:center}
.gp-store-gb{font-family:'Sora',sans-serif;font-size:17px;font-weight:700}
.gp-store-price{font-size:12px;color:var(--txt-soft);margin:5px 0 12px}
.gp-btn-out{display:block;padding:9px;border-radius:9px;border:1px solid var(--stroke-strong);color:var(--cyan);font-weight:600;font-size:13px}
.gp-btn-out:hover{background:var(--hover-bg)}
/* Cancelar */
.gp-cancel{display:flex;align-items:center;gap:13px;margin-top:16px;padding:16px 18px;border:1px solid rgba(255,90,110,.4);background:rgba(255,90,110,.07);border-radius:var(--r-md)}
.gp-cancel-ico{width:40px;height:40px;flex:none;border-radius:10px;display:grid;place-items:center;color:var(--red);background:rgba(255,90,110,.14)}
.gp-cancel-ico svg{width:20px;height:20px}
.gp-cancel-txt{flex:1}
.gp-cancel-txt b{font-family:'Sora',sans-serif;font-size:14px}
.gp-cancel-txt p{font-size:11.5px;color:var(--txt-soft);margin-top:3px}
.gp-cancel-btn{flex:none;padding:9px 16px;border-radius:9px;border:1px solid var(--red);color:var(--red);font-weight:600;font-size:13px}
.gp-cancel-btn:hover{background:rgba(255,90,110,.12)}
/* Facturacion */
.gp-summary-row{display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid rgba(110,160,255,.08);font-size:13px}
.gp-summary-row:last-of-type{border-bottom:0}
.gp-link{color:var(--cyan);font-weight:600}
/* Footer */
.gp-foot{display:flex;justify-content:flex-end;padding:16px 0 22px;margin-top:6px;border-top:1px solid var(--stroke);position:sticky;bottom:0;background:var(--card)}
.gp-cerrar{padding:10px 22px;border-radius:10px;background:var(--panel-2);border:1px solid var(--stroke-strong);color:var(--txt);font-weight:600;font-size:13.5px}
.gp-cerrar:hover{background:var(--hover-bg)}

/* ===== RESPONSIVE: MODAL GESTIONAR PLAN ===== */
@media (max-width:768px){
  .gp-ov{padding:20px 12px}
  .gp-modal{padding:20px 18px 0;max-width:100%}
  .gp-x{top:16px;right:16px;width:30px;height:30px}
  .gp-x svg{width:18px;height:18px}
  .gp-head h2{font-size:18px}
  .gp-head p{font-size:12px}
  .gp-tabs{gap:18px;margin:14px 0 0;overflow-x:auto;-webkit-overflow-scrolling:touch}
  .gp-tab{font-size:13px;padding-bottom:10px;white-space:nowrap}
  .gp-tab svg{width:15px;height:15px}
  .gp-body{padding:16px 0 4px}
  .gp-grid{grid-template-columns:1fr;gap:14px}
  .gp-card{padding:14px 15px}
  .gp-card h3{font-size:13.5px;margin-bottom:11px}
  .gp-soft{font-size:11.5px}
  .gp-plan{gap:11px}
  .gp-crown{width:40px;height:40px}
  .gp-crown svg{width:20px;height:20px}
  .gp-plan-name b{font-size:14px}
  .gp-badge{font-size:9px;padding:2px 7px}
  .gp-plan-info p{font-size:11px}
  .gp-feat{gap:7px}
  .gp-feat li{font-size:11.5px}
  .gp-feat li svg{width:14px;height:14px}
  .gp-ai-num{font-size:22px}
  .gp-ai-pct{font-size:15px}
  .gp-bar{height:7px}
  .gp-btn-ghost{font-size:12px;padding:9px}
  .gp-btn-ghost svg{width:14px;height:14px}
  .gp-mini{font-size:10.5px}
  .gp-table{font-size:11px}
  .gp-table th{font-size:10px;padding:6px 7px}
  .gp-table td{padding:8px 7px}
  .gp-u{font-size:11px}
  .gp-you{font-size:8.5px;padding:1px 5px}
  .gp-st{font-size:9px;padding:2px 7px}
  .gp-note{font-size:10.5px;margin-top:10px}
  .gp-note svg{width:12px;height:12px}
  .gp-store{grid-template-columns:1fr;gap:10px}
  .gp-store-card{padding:13px}
  .gp-store-gb{font-size:15px}
  .gp-store-price{font-size:11px;margin:4px 0 10px}
  .gp-btn-out{font-size:12px;padding:8px}
  .gp-cancel{gap:11px;padding:14px 15px}
  .gp-cancel-ico{width:36px;height:36px}
  .gp-cancel-ico svg{width:18px;height:18px}
  .gp-cancel-txt b{font-size:13px}
  .gp-cancel-txt p{font-size:10.5px}
  .gp-cancel-btn{font-size:12px;padding:8px 14px}
  .gp-summary-row{font-size:12px;padding:10px 0}
  .gp-foot{padding:14px 0 18px}
  .gp-cerrar{font-size:12.5px;padding:9px 18px}
}

@media (max-width:480px){
  .gp-ov{padding:12px 8px}
  .gp-modal{padding:16px 14px 0}
  .gp-head h2{font-size:16px}
  .gp-head p{font-size:11px}
  .gp-tabs{gap:16px}
  .gp-tab{font-size:12px;padding-bottom:9px}
  .gp-card{padding:12px}
  .gp-card h3{font-size:12.5px}
  .gp-table{font-size:10px}
  .gp-table th{font-size:9px;padding:5px 6px}
  .gp-table td{padding:7px 6px}
  .gp-cancel{flex-direction:column;align-items:flex-start;padding:12px}
  .gp-cancel-btn{width:100%;text-align:center}
  .gp-foot{padding:12px 0 16px}
}
/* Historial de pagos */
.inv-list{margin-top:6px}
.inv-loading,.inv-empty{font-size:13px;color:var(--txt-soft);padding:14px 0;text-align:center}
.inv-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 0;border-bottom:1px solid var(--stroke)}
.inv-row:last-child{border-bottom:0}
.inv-info{display:flex;flex-direction:column;gap:3px}
.inv-date{font-size:13.5px;font-weight:600;color:var(--txt)}
.inv-num{font-size:11.5px;color:var(--txt-soft)}
.inv-right{display:flex;align-items:center;gap:12px}
.inv-amount{font-size:14px;font-weight:700;font-family:'Sora',sans-serif}
.inv-status{font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:6px;text-transform:capitalize}
.inv-status.paid{color:var(--green);background:rgba(61,220,151,.14)}
.inv-status.open,.inv-status.draft{color:var(--orange);background:rgba(255,160,0,.14)}
.inv-status.void,.inv-status.uncollectible{color:var(--red);background:rgba(239,68,68,.12)}
.inv-pdf{width:32px;height:32px;flex:none;border-radius:8px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);display:grid;place-items:center;transition:all .15s}
.inv-pdf:hover{background:var(--hover-bg);color:var(--cyan);border-color:var(--cyan)}
.inv-pdf svg{width:15px;height:15px}
</style>
@endpush

@push('scripts')
<script>
(function(){
  const modal = document.getElementById('gpModal');
  const openBtn = document.getElementById('gpOpen');
  if (!modal || !openBtn) return;

  const open = (e) => { if (e) e.preventDefault(); modal.classList.add('open'); modal.setAttribute('aria-hidden','false'); document.body.style.overflow='hidden'; };
  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden','true'); document.body.style.overflow=''; };

  openBtn.addEventListener('click', open);
  document.getElementById('gpClose')?.addEventListener('click', close);
  document.getElementById('gpClose2')?.addEventListener('click', close);
  modal.addEventListener('click', (e) => { if (e.target === modal) close(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  // Pestañas internas del modal
  const tabs = modal.querySelectorAll('.gp-tab');
  const panels = modal.querySelectorAll('.gp-panel');
  tabs.forEach(t => t.addEventListener('click', () => {
    tabs.forEach(x => x.classList.remove('active'));
    panels.forEach(p => p.classList.remove('active'));
    t.classList.add('active');
    modal.querySelector(`.gp-panel[data-gppanel="${t.dataset.gptab}"]`)?.classList.add('active');
    if (t.dataset.gptab === 'facturacion') loadInvoices();
  }));

  // ===== Historial de pagos (facturas desde Stripe) =====
  const INVOICES_URL = "{{ url('/stripe/invoices') }}";
  let invoicesLoaded = false;

  function fmtAmount(cents, currency){
    return new Intl.NumberFormat('es-MX', { style:'currency', currency: currency || 'MXN' }).format((cents || 0) / 100);
  }
  function fmtDate(ts){
    return new Date(ts * 1000).toLocaleDateString('es-MX', { day:'2-digit', month:'short', year:'numeric' });
  }
  function statusLabel(s){
    const map = { paid:'Pagada', open:'Pendiente', draft:'Borrador', void:'Anulada', uncollectible:'Incobrable' };
    return map[s] || s;
  }

  function loadInvoices(){
    if (invoicesLoaded) return;
    const box = document.getElementById('invoiceHistory');
    if (!box) return;
    invoicesLoaded = true;

    fetch(INVOICES_URL, { headers: { 'Accept':'application/json' } })
      .then(r => r.json())
      .then(data => {
        const list = data.invoices || [];
        if (!list.length) {
          box.innerHTML = '<div class="inv-empty">Aún no tienes pagos registrados.</div>';
          return;
        }
        box.innerHTML = list.map(inv => `
          <div class="inv-row">
            <div class="inv-info">
              <span class="inv-date">${fmtDate(inv.date)}</span>
              <span class="inv-num">${inv.number || inv.id}</span>
            </div>
            <div class="inv-right">
              <span class="inv-amount">${fmtAmount(inv.amount, inv.currency)}</span>
              <span class="inv-status ${inv.status}">${statusLabel(inv.status)}</span>
              ${inv.pdf ? `<a class="inv-pdf" href="${inv.pdf}" target="_blank" rel="noopener" title="Descargar PDF"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>` : ''}
            </div>
          </div>
        `).join('');
      })
      .catch(() => {
        box.innerHTML = '<div class="inv-empty">No se pudo cargar el historial de pagos.</div>';
        invoicesLoaded = false;
      });
  }
})();
</script>

<script>
/* ===== Integración de pagos con Stripe ===== */
document.addEventListener('DOMContentLoaded', function(){
  const CSRF = "{{ csrf_token() }}";
  const CHECKOUT_URL = "{{ url('/stripe/checkout') }}";
  const CHANGE_PLAN_URL = "{{ url('/stripe/change-plan') }}";

  // Estado del usuario: ¿ya tiene suscripción?
  const hasSubscription = {{ auth()->user()->stripe_subscription_id ? 'true' : 'false' }};

  console.log('Stripe integration loaded');
  console.log('Has subscription:', hasSubscription);

  // Envía un POST clásico (redirige a Stripe) creando un form temporal.
  function postTo(action, fields){
    console.log('postTo called:', action, fields);
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = action;
    form.style.display = 'none';

    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = CSRF;
    form.appendChild(token);

    Object.entries(fields || {}).forEach(([name, value]) => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = name;
      input.value = value;
      form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
  }

  // Selectores de intervalo de facturación
  const intervalBtns = document.querySelectorAll('.pc-int-btn');
  console.log('Found interval buttons:', intervalBtns.length);

  intervalBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const card = btn.closest('[data-card]');
      if (!card) return;

      // Marcar botón activo
      card.querySelectorAll('.pc-int-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      // Actualizar precio y label
      const priceEl = card.querySelector('.pc-price');
      const ctaEl = card.querySelector('.pc-cta');
      if (priceEl) {
        priceEl.innerHTML = btn.dataset.price + '<span> ' + btn.dataset.label + '</span>';
      }
      if (ctaEl) {
        ctaEl.dataset.interval = btn.dataset.interval;
      }
    });
  });

  // Botones de plan -> Checkout o cambio de plan
  const planBtns = document.querySelectorAll('[data-plan]');
  console.log('Found plan buttons:', planBtns.length);

  planBtns.forEach(el => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      console.log('Plan button clicked:', el.dataset.plan);

      if (el.classList.contains('disabled')) {
        console.log('Button is disabled, ignoring');
        return;
      }

      const plan = el.dataset.plan;
      const interval = el.dataset.interval;

      if (hasSubscription) {
        // Usuario con suscripción: cambiar plan (AJAX, prorrateado)
        console.log('Changing plan:', { plan, interval });
        el.disabled = true;
        el.textContent = 'Cambiando...';

        fetch(CHANGE_PLAN_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
          },
          body: JSON.stringify({ plan, interval }),
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              showToast('¡Plan cambiado!', `Ahora estás en el plan ${data.planName}. ${data.message}`);
              setTimeout(() => location.reload(), 2000);
            } else {
              showToast('Error', data.error || 'No se pudo cambiar el plan');
              el.disabled = false;
              el.textContent = el.dataset.originalText || 'Cambiar plan';
            }
          })
          .catch(err => {
            console.error('Error:', err);
            showToast('Error de conexión', 'Intenta de nuevo.');
            el.disabled = false;
            el.textContent = el.dataset.originalText || 'Cambiar plan';
          });
      } else {
        // Usuario sin suscripción: checkout normal (redirección a Stripe)
        console.log('Sending to checkout:', { plan, interval });
        postTo(CHECKOUT_URL, { plan, interval });
      }
    });
  });

  // ===== Cancelar plan (in-app, sin redirección) =====
  const CANCEL_URL = "{{ url('/stripe/cancel-subscription') }}";
  const RESUME_URL = "{{ url('/stripe/resume-subscription') }}";

  function jsonPost(url){
    return fetch(url, {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
    }).then(r => r.json());
  }

  const cancelBtn = document.getElementById('cancelBtn');
  if (cancelBtn) {
    cancelBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (!confirm('¿Seguro que deseas cancelar tu plan? Conservarás el acceso hasta el final del ciclo de facturación.')) return;
      cancelBtn.classList.add('disabled');
      cancelBtn.textContent = 'Cancelando...';
      jsonPost(CANCEL_URL)
        .then(data => {
          if (data.success) {
            showToast('Plan cancelado', data.message);
            setTimeout(() => location.reload(), 2000);
          } else {
            showToast('Error', data.error || 'No se pudo cancelar el plan');
            cancelBtn.classList.remove('disabled');
            cancelBtn.textContent = 'Cancelar plan';
          }
        })
        .catch(() => {
          showToast('Error de conexión', 'Intenta de nuevo.');
          cancelBtn.classList.remove('disabled');
          cancelBtn.textContent = 'Cancelar plan';
        });
    });
  }

  const resumeBtn = document.getElementById('resumeBtn');
  if (resumeBtn) {
    resumeBtn.addEventListener('click', (e) => {
      e.preventDefault();
      resumeBtn.classList.add('disabled');
      resumeBtn.textContent = 'Reactivando...';
      jsonPost(RESUME_URL)
        .then(data => {
          if (data.success) {
            showToast('Plan reactivado', data.message);
            setTimeout(() => location.reload(), 2000);
          } else {
            showToast('Error', data.error || 'No se pudo reactivar el plan');
            resumeBtn.classList.remove('disabled');
            resumeBtn.textContent = 'Reactivar plan';
          }
        })
        .catch(() => {
          showToast('Error de conexión', 'Intenta de nuevo.');
          resumeBtn.classList.remove('disabled');
          resumeBtn.textContent = 'Reactivar plan';
        });
    });
  }

  // Ver historial -> abre modal Gestionar plan en pestaña Facturación
  document.getElementById('gpOpenBilling')?.addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('gpOpen')?.click();
    const billingTab = document.querySelector('.gp-tab[data-gptab="facturacion"]');
    if (billingTab) billingTab.click();
  });
});
</script>
@endpush

{{-- Notificación toast personalizada --}}
<div id="customToast" class="custom-toast" style="display:none">
  <div class="toast-content">
    <div class="toast-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <div class="toast-message">
      <h4 id="toastTitle">¡Cambio exitoso!</h4>
      <p id="toastText">Tu plan ha sido actualizado.</p>
    </div>
    <button type="button" class="toast-close" onclick="hideToast()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
</div>

@push('styles')
<style>
.custom-toast{position:fixed;bottom:24px;right:24px;z-index:10000;animation:toastIn .3s var(--ease-out)}
@keyframes toastIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
.toast-content{display:flex;align-items:flex-start;gap:14px;background:var(--card);border:1px solid var(--stroke);border-radius:14px;padding:16px 18px;box-shadow:0 20px 50px -15px rgba(0,0,0,.4);min-width:320px}
.toast-icon{width:36px;height:36px;flex:none;border-radius:10px;background:rgba(61,220,151,.12);color:var(--green);display:grid;place-items:center}
.toast-icon svg{width:20px;height:20px}
.toast-message{flex:1}
.toast-message h4{font-family:'Sora',sans-serif;font-size:14px;font-weight:700;margin:0 0 3px}
.toast-message p{font-size:13px;color:var(--txt-soft);margin:0;line-height:1.4}
.toast-close{width:28px;height:28px;flex:none;border-radius:8px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);cursor:pointer;display:grid;place-items:center;transition:all .15s}
.toast-close:hover{background:rgba(239,68,68,.1);color:var(--red);border-color:var(--red)}
.toast-close svg{width:14px;height:14px}
@media(max-width:600px){.custom-toast{bottom:16px;right:16px;left:16px}.toast-content{min-width:auto}}
</style>
@endpush

@push('scripts')
<script>
function showToast(title, text) {
  const toast = document.getElementById('customToast');
  document.getElementById('toastTitle').textContent = title;
  document.getElementById('toastText').textContent = text;
  toast.style.display = 'block';
  setTimeout(hideToast, 5000);
}
function hideToast() {
  document.getElementById('customToast').style.display = 'none';
}
</script>
@endpush

{{-- Modal: actualizar método de pago (Stripe Elements, sin redirección) --}}
<div id="pmModal" class="pm-ov" aria-hidden="true">
  <div class="pm-modal">
    <button type="button" class="pm-x" id="pmClose" aria-label="Cerrar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <h3 class="pm-title">Actualizar método de pago</h3>
    <p class="pm-sub">Tu información se procesa de forma segura con Stripe.</p>
    <div id="pmElement" class="pm-element"></div>
    <div id="pmError" class="pm-error" style="display:none"></div>
    <button type="button" class="pm-submit" id="pmSubmit">
      <span id="pmSubmitText">Guardar tarjeta</span>
    </button>
  </div>
</div>

@push('styles')
<style>
.pm-ov{position:fixed;inset:0;z-index:10001;display:none;align-items:center;justify-content:center;padding:18px;background:rgba(5,9,20,.66);backdrop-filter:blur(3px)}
.pm-ov.open{display:flex}
.pm-modal{position:relative;width:100%;max-width:440px;background:var(--card);border:1px solid var(--stroke);border-radius:16px;box-shadow:0 30px 80px -20px rgba(0,0,0,.5);padding:26px 26px 24px;animation:gpIn .22s var(--ease-out)}
.pm-x{position:absolute;top:16px;right:16px;width:32px;height:32px;display:grid;place-items:center;border-radius:9px;color:var(--txt-soft);border:0;background:none;cursor:pointer}
.pm-x:hover{background:var(--hover-bg);color:var(--txt)}
.pm-x svg{width:18px;height:18px}
.pm-title{font-family:'Sora',sans-serif;font-size:18px;font-weight:700}
.pm-sub{font-size:12.5px;color:var(--txt-soft);margin:4px 0 18px}
.pm-element{padding:4px 0 8px}
.pm-error{color:var(--red);font-size:12.5px;margin:8px 0 0;padding:10px 12px;border-radius:9px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25)}
.pm-submit{width:100%;margin-top:18px;padding:12px;border-radius:11px;border:0;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;font-family:'Sora',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:opacity .15s}
.pm-submit:hover{opacity:.92}
.pm-submit:disabled{opacity:.6;cursor:default}
/* Reactivar */
.gp-resume{border-color:rgba(56,199,244,.4);background:rgba(56,199,244,.07)}
.gp-resume-ico{color:var(--cyan);background:rgba(56,199,244,.14)}
.gp-resume-btn{border-color:var(--cyan);color:var(--cyan)}
.gp-resume-btn:hover{background:rgba(56,199,244,.12)}
</style>
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const STRIPE_KEY = "{{ config('services.stripe.key') }}";
  const SETUP_INTENT_URL = "{{ url('/stripe/setup-intent') }}";
  const PM_UPDATE_URL = "{{ url('/stripe/payment-method') }}";
  const CSRF = "{{ csrf_token() }}";

  const modal = document.getElementById('pmModal');
  if (!modal || !STRIPE_KEY || typeof Stripe === 'undefined') return;

  const stripe = Stripe(STRIPE_KEY);
  let elements, paymentElement, mounted = false;

  const errorBox = document.getElementById('pmError');
  const submitBtn = document.getElementById('pmSubmit');
  const submitText = document.getElementById('pmSubmitText');

  function showError(msg){ errorBox.textContent = msg; errorBox.style.display = 'block'; }
  function clearError(){ errorBox.style.display = 'none'; }
  function setLoading(on){ submitBtn.disabled = on; submitText.textContent = on ? 'Guardando...' : 'Guardar tarjeta'; }

  function openModal(){
    modal.classList.add('open');
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
    clearError();
    if (!mounted) initElements();
  }
  function closeModal(){
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  }

  function initElements(){
    setLoading(true);
    fetch(SETUP_INTENT_URL, { headers: { 'Accept':'application/json' } })
      .then(r => r.json())
      .then(data => {
        if (!data.clientSecret) { showError(data.error || 'No se pudo iniciar.'); setLoading(false); return; }
        elements = stripe.elements({ clientSecret: data.clientSecret, appearance: { theme: 'night', variables: { colorPrimary: '#38c7f4' } } });
        paymentElement = elements.create('payment');
        paymentElement.mount('#pmElement');
        mounted = true;
        setLoading(false);
      })
      .catch(() => { showError('Error de conexión.'); setLoading(false); });
  }

  document.querySelectorAll('[data-pm-open]').forEach(btn => {
    btn.addEventListener('click', (e) => { e.preventDefault(); openModal(); });
  });
  document.getElementById('pmClose')?.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

  submitBtn.addEventListener('click', async () => {
    if (!elements) return;
    setLoading(true);
    clearError();

    const { error, setupIntent } = await stripe.confirmSetup({
      elements,
      redirect: 'if_required',
    });

    if (error) {
      showError(error.message || 'No se pudo guardar la tarjeta.');
      setLoading(false);
      return;
    }

    // Guardar como método de pago por defecto en el backend
    fetch(PM_UPDATE_URL, {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
      body: JSON.stringify({ payment_method: setupIntent.payment_method }),
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          closeModal();
          showToast('Tarjeta actualizada', `${data.brand} ····${data.last4}`);
          setTimeout(() => location.reload(), 1800);
        } else {
          showError(data.error || 'No se pudo actualizar.');
          setLoading(false);
        }
      })
      .catch(() => { showError('Error de conexión.'); setLoading(false); });
  });
});
</script>
@endpush
