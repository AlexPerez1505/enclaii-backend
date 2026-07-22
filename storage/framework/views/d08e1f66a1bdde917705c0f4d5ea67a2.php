
<?php
  $pacCita      = $proximaCita?->paciente;
  $nombreCita   = $pacCita?->nombre_completo ?? $proximaCita?->paciente_nombre ?? 'Sin citas próximas';
  $partesNombre = preg_split('/\s+/', trim($nombreCita), 3);
  $nombreCita   = trim(($partesNombre[0] ?? '') . ' ' . ($partesNombre[1] ?? ''));
?>
<div class="widget rise d2" data-widget-id="next-patient" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-next">
    <h3>PRÓXIMO PACIENTE</h3>
    <?php if($proximaCita): ?>
      <div class="name"><?php echo e($nombreCita); ?></div>
      <div class="meta">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <b><?php echo e(format_user_date($proximaCita->fecha)); ?> · <?php echo e(format_user_time(\Carbon\Carbon::parse($proximaCita->hora ?? '00:00'))); ?></b>
      </div>
      <div class="meta"><b><?php echo e($proximaCita->procedimiento ?? 'Procedimiento por definir'); ?></b></div>
      <a class="btn-line" href="<?php echo e($pacCita ? route('pacientes.index', ['paciente_id' => $pacCita->id]) : route('pacientes.index')); ?>">
        Abrir expediente
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    <?php else: ?>
      <div class="name">Sin citas<br>próximas</div>
      <div class="meta"><b>No hay citas agendadas</b></div>
    <?php endif; ?>
    <div class="holo">
      <div class="lottie-brain"></div>
    </div>
  </article>
  <span class="widget-resize-handle"></span>
</div>
<?php /**PATH C:\Users\gmedi\enclaii-backend\resources\views/dashboard/widgets/next-patient/index.blade.php ENDPATH**/ ?>