
<?php
  $fmtGb = function ($value) {
    $formatted = number_format((float) $value, 2, '.', ',');
    return rtrim(rtrim($formatted, '0'), '.') . ' GB';
  };
  $storagePlans = $storageSummary['plans'];
  $currentPlan = $storageSummary['plan'];
  $storageCategories = $storageSummary['categories'];
  $storageHistory = collect($storageSummary['history']);
  $chartMaxGb = max((float) $storageSummary['quota_gb'], (float) ($storageHistory->max('gb') ?? 0), 1);
  $chartBaseY = 118;
  $chartTopY = 13;
  $chartHeight = $chartBaseY - $chartTopY;
  $chartPointList = $storageHistory->values()->map(function ($item, $index) use ($chartBaseY, $chartHeight, $chartMaxGb) {
    $x = 20 + ($index * 52);
    $y = $chartBaseY - min(1, ((float) $item['gb'] / $chartMaxGb)) * $chartHeight;
    return [
      'x' => round($x, 1),
      'y' => round($y, 1),
      'label' => $item['label'],
    ];
  });
  $chartPoints = $chartPointList->map(fn ($point) => $point['x'] . ',' . $point['y'])->implode(' ');
  $chartPath = $chartPointList->map(fn ($point) => $point['x'] . ',' . $point['y'])->implode(' L');
  $chartArea = $chartPath ? 'M' . $chartPath . ' L280,118 L20,118 Z' : '';
  $chartLabels = [
    $fmtGb($chartMaxGb),
    $fmtGb($chartMaxGb * 0.75),
    $fmtGb($chartMaxGb * 0.5),
    $fmtGb($chartMaxGb * 0.25),
    '0 GB',
  ];
?>
<div class="cfg-panel" data-panel="plan">
  <div class="pl-grid">

    
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
            <b>Plan <?php echo e($storageSummary['plan_label']); ?></b>
            <span class="badge" style="background:<?php echo e($planUser->subscription_status === 'active' ? 'rgba(61,220,151,.14)' : 'rgba(255,160,0,.14)'); ?>;color:<?php echo e($planUser->subscription_status === 'active' ? 'var(--green)' : 'var(--orange)'); ?>">
              <?php echo e(ucfirst($planUser->subscription_status ?? 'Inactivo')); ?>

            </span>
            <?php if($planUser->subscription_renews_at): ?>
              <p>Renovación <?php echo e(format_user_date($planUser->subscription_renews_at)); ?></p>
            <?php endif; ?>
            <p><?php echo e($fmtGb($storageSummary['quota_per_person_gb'])); ?> por persona &middot; <?php echo e($storageSummary['person_count']); ?> <?php echo e($storageSummary['person_count'] === 1 ? 'persona activa' : 'personas activas'); ?></p>
          </div>
          <a href="#" class="pl-btn" id="gpOpen">Gestionar plan</a>
        </div>

        <div class="pl-detail" style="margin-top:18px">
          <div class="store-top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg> Almacenamiento en la nube</div>
          <div class="store-meta" style="margin-top:11px"><span><?php echo e($fmtGb($storageSummary['used_gb'])); ?> de <?php echo e($fmtGb($storageSummary['quota_gb'])); ?> utilizados</span></div>
          <div class="store-bar"><i data-w="<?php echo e($storageSummary['used_percent']); ?>"></i></div>
          <div class="store-legend"><span><i class="used"></i>Usado: <?php echo e($fmtGb($storageSummary['used_gb'])); ?></span><span><i class="free"></i>Disponible: <?php echo e($fmtGb($storageSummary['available_gb'])); ?></span></div>

          <div class="pl-files">
            <div class="pl-file"><span class="fi c2"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></span><div class="ft"><?php echo e($storageCategories['images']['label']); ?></div><div class="fv"><?php echo e($fmtGb($storageCategories['images']['gb'])); ?></div><div class="fp"><?php echo e($storageCategories['images']['percent']); ?>%</div></div>
            <div class="pl-file"><span class="fi c3"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg></span><div class="ft"><?php echo e($storageCategories['videos']['label']); ?></div><div class="fv"><?php echo e($fmtGb($storageCategories['videos']['gb'])); ?></div><div class="fp"><?php echo e($storageCategories['videos']['percent']); ?>%</div></div>
            <div class="pl-file"><span class="fi c4"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg></span><div class="ft"><?php echo e($storageCategories['other']['label']); ?></div><div class="fv"><?php echo e($fmtGb($storageCategories['other']['gb'])); ?></div><div class="fp"><?php echo e($storageCategories['other']['percent']); ?>%</div></div>
          </div>

          <div class="pl-detail">
            <div class="t">¿Qué ocupa espacio?</div>
            <div class="d">Consulta el detalle de los archivos que más almacenamiento consumen</div>
            <a href="#" class="tbl-link"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v4H3zM3 10h18v4H3zM3 17h18v4H3z"/></svg> Ver detalle de almacenamiento</a>
          </div>
        </div>
      </article>

      <?php if($isClinicOwner): ?>
      <article class="card rise d4">
        <div class="cfg-card-head">
          <h2>¿Necesitas más espacio?</h2>
          <p>Actualiza tu plan y obtén más almacenamiento y beneficios adicionales</p>
        </div>

        <div class="pl-plans">
          <div class="pl-card <?php echo e($currentPlan === 'clinica' ? 'current' : ''); ?>" data-card="clinica">
            <div class="pc-top">
              <span class="pc-ico" style="color:var(--green)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg></span>
              <?php if($currentPlan === 'clinica'): ?>
                <span class="pc-badge">Plan actual</span>
              <?php endif; ?>
            </div>
            <h4><?php echo e($storagePlans['clinica']['label']); ?></h4><div class="pc-gb"><?php echo e($fmtGb($storagePlans['clinica']['gb_per_person'])); ?> por persona</div>
            <div class="pc-gb"><?php echo e($fmtGb($storagePlans['clinica']['total_gb_for_current_people'])); ?> para <?php echo e($storageSummary['person_count']); ?> <?php echo e($storageSummary['person_count'] === 1 ? 'persona activa' : 'personas activas'); ?></div>
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
            <a href="#" class="pc-cta <?php echo e($currentPlan === 'clinica' || !$isClinicOwner ? 'disabled' : ''); ?>" data-plan="clinica" data-interval="month">
              <?php echo e($currentPlan === 'clinica' ? 'Plan actual' : ($isClinicOwner ? 'Cambiar a Clinica' : 'Solo el propietario')); ?>

            </a>
          </div>

          <div class="pl-card <?php echo e($currentPlan === 'hospital' ? 'current' : ''); ?>" data-card="hospital">
            <div class="pc-top">
              <span class="pc-ico" style="color:#a47bff"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l4 3 5-7 5 7 4-3-2 12H5L3 8z"/></svg></span>
              <?php if($currentPlan === 'hospital'): ?>
                <span class="pc-badge">Plan actual</span>
              <?php endif; ?>
            </div>
            <h4><?php echo e($storagePlans['hospital']['label']); ?></h4><div class="pc-gb"><?php echo e($fmtGb($storagePlans['hospital']['gb_per_person'])); ?> por persona</div>
            <div class="pc-gb"><?php echo e($fmtGb($storagePlans['hospital']['total_gb_for_current_people'])); ?> para <?php echo e($storageSummary['person_count']); ?> <?php echo e($storageSummary['person_count'] === 1 ? 'persona activa' : 'personas activas'); ?></div>
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
            <a href="#" class="pc-cta <?php echo e($currentPlan === 'hospital' || !$isClinicOwner ? 'disabled' : ''); ?>" data-plan="hospital" data-interval="month">
              <?php echo e($currentPlan === 'hospital' ? 'Plan actual' : ($isClinicOwner ? 'Cambiar a Hospital' : 'Solo el propietario')); ?>

            </a>
          </div>

          <div class="pl-card <?php echo e($currentPlan === 'red_medica' ? 'current' : ''); ?>" data-card="red_medica">
            <div class="pc-top">
              <span class="pc-ico" style="color:var(--red)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></span>
              <?php if($currentPlan === 'red_medica'): ?>
                <span class="pc-badge">Plan actual</span>
              <?php endif; ?>
            </div>
            <h4><?php echo e($storagePlans['red_medica']['label']); ?></h4><div class="pc-gb"><?php echo e($fmtGb($storagePlans['red_medica']['gb_per_person'])); ?> por persona</div>
            <div class="pc-gb"><?php echo e($fmtGb($storagePlans['red_medica']['total_gb_for_current_people'])); ?> para <?php echo e($storageSummary['person_count']); ?> <?php echo e($storageSummary['person_count'] === 1 ? 'persona activa' : 'personas activas'); ?></div>
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
            <a href="#" class="pc-cta <?php echo e($currentPlan === 'red_medica' || !$isClinicOwner ? 'disabled' : ''); ?>" data-plan="red_medica" data-interval="month">
              <?php echo e($currentPlan === 'red_medica' ? 'Plan actual' : ($isClinicOwner ? 'Cambiar a Red Médica' : 'Solo el propietario')); ?>

            </a>
          </div>

        </div>
      </article>
      <?php endif; ?>
    </div>

    
    <div class="cfg-col">
      <article class="card rise d3">
        <div class="cfg-card-head"><h2>Resumen de tu plan</h2></div>
        <div class="pl-summary-row">
          <span class="k">Plan actual</span>
          <span class="v">Plan <?php echo e($storageSummary['plan_label']); ?></span>
        </div>
        <div class="pl-summary-row">
          <span class="k">Personas activas</span>
          <span class="v"><?php echo e($storageSummary['person_count']); ?></span>
        </div>
        <div class="pl-summary-row">
          <span class="k">Almacenamiento/persona</span>
          <span class="v"><?php echo e($fmtGb($storageSummary['quota_per_person_gb'])); ?></span>
        </div>
        <div class="pl-summary-row">
          <span class="k">Almacenamiento total</span>
          <span class="v"><?php echo e($fmtGb($storageSummary['quota_gb'])); ?></span>
        </div>
        <div class="pl-summary-row">
          <span class="k">Estado</span>
          <span class="v" style="color:<?php echo e($planUser->subscription_status === 'active' ? 'var(--green)' : 'var(--orange)'); ?>">
            <?php echo e(ucfirst($planUser->subscription_status ?? 'Inactivo')); ?>

          </span>
        </div>
        <?php if($planUser->subscription_renews_at): ?>
        <div class="pl-summary-row">
          <span class="k">Renovación</span>
          <span class="v"><?php echo e(format_user_date($planUser->subscription_renews_at)); ?></span>
        </div>
        <?php endif; ?>
        <?php if($planUser->pm_last_four): ?>
        <div class="pl-summary-row">
          <span class="k">Método de pago</span>
          <span class="v pl-pay">
            <?php if($planUser->pm_brand === 'visa'): ?>
              <svg width="26" height="17" viewBox="0 0 48 32" style="display:inline-block;vertical-align:middle;margin-right:6px"><rect width="48" height="32" rx="4" fill="#1434CB"/><text x="24" y="20" fill="white" font-size="14" font-weight="bold" text-anchor="middle">VISA</text></svg>
            <?php elseif($planUser->pm_brand === 'mastercard'): ?>
              <span class="pl-mc"></span>
            <?php else: ?>
              <span style="text-transform:capitalize"><?php echo e($planUser->pm_brand); ?></span>
            <?php endif; ?>
            ····<?php echo e($planUser->pm_last_four); ?>

          </span>
        </div>
        <?php endif; ?>
      </article>

      <?php if($isClinicOwner): ?>
      <article class="card rise d4">
        <div class="cfg-card-head"><h2>Facturación y pago</h2></div>
        <?php if($planUser->subscription_renews_at): ?>
          <a href="#" class="pl-link">
            <span>Próxima fecha de cobro</span>
            <span class="v"><?php echo e(format_user_date($planUser->subscription_renews_at)); ?></span>
          </a>
        <?php endif; ?>
        <a href="#" class="pl-link" id="gpOpenBilling">
          <span>Historial de facturas</span>
          <span class="v">Ver</span>
        </a>
        <a href="#" class="pl-wide-btn" data-pm-open="1">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
          <?php echo e($planUser->pm_last_four ? 'Actualizar método de pago' : 'Agregar método de pago'); ?>

        </a>
      </article>
      <?php endif; ?>

      <article class="card rise d5">
        <div class="cfg-card-head"><h2>Historial de uso</h2><p>Tu consumo de almacenamiento en los últimos 6 meses</p></div>
        <div class="pl-chart-wrap">
          <div class="pl-chart-y">
            <?php $__currentLoopData = $chartLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <span><?php echo e($label); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
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
              <?php if($chartArea): ?>
                <path d="<?php echo e($chartArea); ?>" fill="url(#plArea)"/>
              <?php endif; ?>
              <polyline points="<?php echo e($chartPoints); ?>" fill="none" stroke="var(--cyan)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
              <g fill="var(--cyan)">
                <?php $__currentLoopData = $chartPointList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <circle cx="<?php echo e($point['x']); ?>" cy="<?php echo e($point['y']); ?>" r="3.5"/>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </g>
            </svg>
            <div style="display:flex;justify-content:space-between;font-size:9.5px;color:var(--txt-soft);margin-top:4px;padding:0 10px">
              <?php $__currentLoopData = $storageHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span><?php echo e($item['label']); ?></span>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
        </div>
      </article>

      <article class="card rise d5">
        <div class="pl-reco">
          <span class="ri"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7V17h8v-2.3A7 7 0 0 0 12 2z"/></svg></span>
          <h2 style="font-family:'Sora',sans-serif;font-size:15px;font-weight:700">Recomendaciones</h2>
        </div>
        <p style="font-size:12px;color:var(--txt-soft);margin:6px 0 12px"><?php echo e($storageSummary['recommendation']['message']); ?></p>
        <a href="#" class="pl-wide-btn">Ver recomendaciones <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></a>
      </article>
    </div>

  </div>
</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/configuracion/sections/plan/_panel.blade.php ENDPATH**/ ?>