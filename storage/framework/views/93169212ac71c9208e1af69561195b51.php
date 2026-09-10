
<div class="widget rise d3" data-widget-id="ia-pending" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-ia">
    <div class="brain-img brain-lottie"></div>
    <h3>REPORTES PENDIENTES</h3>
    <div class="big-num" id="numReportes" data-target="<?php echo e($estudiosSinReporte ?? 0); ?>">0</div>
    <div class="big-label">pendientes<br><span class="muted">  </span></div>
    <a class="btn-orange" href="<?php echo e(route('ia-reportes.redactar')); ?>?from=widget">
      Revisar reportes
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
  </article>
  <span class="widget-resize-handle"></span>
</div>
<?php /**PATH C:\Users\LENOVO\enclaii-backend\resources\views/dashboard/widgets/ia-pending/index.blade.php ENDPATH**/ ?>