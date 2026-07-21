
<?php
  $fmtGb = $fmtGb ?? function ($value) {
    $formatted = number_format((float) $value, 2, '.', ',');
    return rtrim(rtrim($formatted, '0'), '.') . ' GB';
  };
  $storagePlans = $storagePlans ?? $storageSummary['plans'];
?>
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
      <?php if($isClinicOwner): ?>
        <button class="gp-tab" data-gptab="facturacion"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Facturacion</button>
      <?php endif; ?>
    </div>

    <div class="gp-body">
      
      <div class="gp-panel active" data-gppanel="resumen">
        <div class="gp-grid">

          <section class="gp-card">
            <h3>Resumen del plan</h3>
            <div class="gp-plan">
              <span class="gp-crown"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l4 3 5-7 5 7 4-3-2 12H5L3 8z"/></svg></span>
              <div class="gp-plan-info">
                <div class="gp-plan-name">
                  <b>Plan <?php echo e($storageSummary['plan_label']); ?></b>
                  <span class="gp-badge" style="background:<?php echo e($planUser->subscription_status === 'active' ? 'rgba(61,220,151,.14)' : 'rgba(255,160,0,.14)'); ?>;color:<?php echo e($planUser->subscription_status === 'active' ? 'var(--green)' : 'var(--orange)'); ?>">
                    <?php echo e(ucfirst($planUser->subscription_status ?? 'Inactivo')); ?>

                  </span>
                </div>
                <?php if($planUser->subscription_renews_at): ?>
                  <p>Renovacion <?php echo e(format_user_date($planUser->subscription_renews_at)); ?></p>
                <?php endif; ?>
                <?php if($planUser->pm_last_four): ?>
                  <p>Tarjeta: <?php echo e(ucfirst($planUser->pm_brand)); ?> ····<?php echo e($planUser->pm_last_four); ?></p>
                <?php endif; ?>
                <p><?php echo e($fmtGb($storageSummary['quota_gb'])); ?> totales &middot; <?php echo e($fmtGb($storageSummary['quota_per_person_gb'])); ?> por persona</p>
              </div>
              <ul class="gp-feat">
                <?php
                  $planFeatures = [
                    'clinica' => [$fmtGb($storagePlans['clinica']['gb_per_person']) . ' por persona en almacenamiento en la nube', 'IA Reportes basica', 'Soporte por email'],
                    'hospital' => [$fmtGb($storagePlans['hospital']['gb_per_person']) . ' por persona en almacenamiento en la nube', 'IA Reportes avanzada', 'Soporte prioritario', 'Exportacion de reportes'],
                    'red_medica' => [$fmtGb($storagePlans['red_medica']['gb_per_person']) . ' por persona en almacenamiento en la nube', 'Integraciones avanzadas', 'Soporte 24/7'],
                  ];
                  $currentPlan = str_replace('-', '_', $planUser->stripe_plan ?? 'clinica');
                  $features = $planFeatures[$currentPlan] ?? ['Plan gratuito'];
                ?>
                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><?php echo e($feat); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
              <?php if($isClinicOwner): ?>
                <button type="button" class="gp-btn-ghost sm gp-invite-open"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Agregar correo</button>
              <?php endif; ?>
            </div>
            <p class="gp-soft">Administra los usuarios que forman parte de tu plan</p>
            <div class="gp-bar gp-mt"><i style="width:<?php echo e($clinicMemberPercent); ?>%"></i></div>
            <div class="gp-mini"><?php echo e($clinicMemberUsed); ?> de <?php echo e($clinicMemberLimit); ?> lugares utilizados <span><?php echo e($clinicMemberPercent); ?>%</span></div>
            <?php echo $__env->make('configuracion.partials.plan-members-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <p class="gp-note">&#9432; Puedes agregar <b><?php echo e($clinicMemberRemaining); ?></b> integrante(s) más con tu plan actual.</p>
          </section>

          <?php if($isClinicOwner): ?>
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
          <?php endif; ?>

        </div>

        <?php if($isClinicOwner && $planUser->subscribed() && $planUser->cancelScheduled()): ?>
        
        <div class="gp-cancel gp-resume" id="resumeBox">
          <span class="gp-cancel-ico gp-resume-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
          <div class="gp-cancel-txt">
            <b>Plan programado para cancelarse</b>
            <p>Tu plan permanecerá activo hasta el <b><?php echo e(format_user_date($planUser->subscription_cancel_at)); ?></b>. Después perderás el acceso a las funciones premium.</p>
          </div>
          <a href="#" class="gp-cancel-btn gp-resume-btn" id="resumeBtn">Reactivar plan</a>
        </div>
        <?php elseif($isClinicOwner && $planUser->subscribed()): ?>
        <div class="gp-cancel" id="cancelBox">
          <span class="gp-cancel-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
          <div class="gp-cancel-txt"><b>Cancelar plan</b><p>Si cancelas tu plan, perderas acceso a las funciones premium al finalizar el ciclo de facturacion</p></div>
          <a href="#" class="gp-cancel-btn" id="cancelBtn">Cancelar plan</a>
        </div>
        <?php endif; ?>
      </div>

      
      <div class="gp-panel" data-gppanel="integrantes">
        <section class="gp-card">
          <div class="gp-card-row">
            <h3>Integrantes del plan</h3>
            <?php if($isClinicOwner): ?>
              <button type="button" class="gp-btn-ghost sm gp-invite-open"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Agregar correo</button>
            <?php endif; ?>
          </div>
          <p class="gp-soft"><?php echo e($clinicMemberUsed); ?> de <?php echo e($clinicMemberLimit); ?> lugares utilizados. Puedes agregar <?php echo e($clinicMemberRemaining); ?> integrante(s).</p>
          <?php echo $__env->make('configuracion.partials.plan-members-table', ['tableClass' => 'gp-mt'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </section>
      </div>

      
      <?php if($isClinicOwner): ?>
      <div class="gp-panel" data-gppanel="facturacion">
        <section class="gp-card">
          <h3>Facturacion y pago</h3>
          <div class="gp-summary-row">
            <span class="gp-soft">Plan actual</span>
            <span>Plan <?php echo e($storageSummary['plan_label']); ?></span>
          </div>
          <div class="gp-summary-row">
            <span class="gp-soft">Estado</span>
            <span style="color:<?php echo e($planUser->subscription_status === 'active' ? 'var(--green)' : 'var(--orange)'); ?>">
              <?php echo e(ucfirst($planUser->subscription_status ?? 'Inactivo')); ?>

            </span>
          </div>
          <?php if($planUser->subscription_renews_at): ?>
          <div class="gp-summary-row">
            <span class="gp-soft">Proxima fecha de cobro</span>
            <span><?php echo e(format_user_date($planUser->subscription_renews_at)); ?></span>
          </div>
          <?php endif; ?>
          <?php if($planUser->pm_last_four): ?>
          <div class="gp-summary-row">
            <span class="gp-soft">Metodo de pago</span>
            <span><?php echo e(ucfirst($planUser->pm_brand)); ?> ····<?php echo e($planUser->pm_last_four); ?></span>
          </div>
          <?php endif; ?>
          <a href="#" class="gp-btn-out gp-mt2" data-pm-open="1" style="display:inline-flex;align-items:center;gap:8px">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            <?php echo e($planUser->pm_last_four ? 'Actualizar metodo de pago' : 'Agregar metodo de pago'); ?>

          </a>
        </section>

        <section class="gp-card gp-mt2">
          <h3>Historial de pagos</h3>
          <div id="invoiceHistory" class="inv-list">
            <div class="inv-loading">Cargando historial...</div>
          </div>
        </section>
      </div>
      <?php endif; ?>
    </div>

    <div class="gp-foot">
      <button class="gp-cerrar" id="gpClose2">Cerrar</button>
    </div>
  </div>
</div>
<?php /**PATH C:\laragon\www\endocare\resources\views/configuracion/sections/plan/_modal-plan.blade.php ENDPATH**/ ?>