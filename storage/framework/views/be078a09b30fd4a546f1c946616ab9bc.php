
<?php
  $pacMinCita      = $proximaCita?->paciente;
  $nombreMinCita   = $pacMinCita?->nombre_completo ?? $proximaCita?->paciente_nombre ?? 'Sin citas próximas';
  $partesMinNombre = preg_split('/\s+/', trim($nombreMinCita), 3);
  $nombreMinCita   = trim(($partesMinNombre[0] ?? '') . ' ' . ($partesMinNombre[1] ?? ''));
  $horaMinCita     = $proximaCita ? format_user_time(\Carbon\Carbon::parse($proximaCita->hora ?? '00:00')) : '';
  $procMinCita     = $proximaCita?->procedimiento ?? 'Procedimiento por definir';
  $urlMinCita      = $pacMinCita ? route('pacientes.index', ['paciente_id' => $pacMinCita->id]) : route('pacientes.index');
?>
<div class="widget widget-minimal d1" data-widget-id="next-patient-min" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <div class="card card-minimal card-minimal-next" style="overflow:hidden">
    <div class="min-label" style="flex:0 0 auto">Próximo paciente</div>
    <a class="min-icon" href="<?php echo e($urlMinCita); ?>" style="flex:1 1 60%;width:100%;min-height:0;display:grid;place-items:center;color:var(--cyan);text-decoration:none;cursor:pointer">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:100%;height:100%">
        <path d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
      </svg>
    </a>
    <div class="min-text" style="flex:0 0 22%;display:flex;flex-direction:column;justify-content:center;gap:0.35em;text-align:center;min-height:0">
      <div class="min-value" style="font-size:clamp(0.85em,4.5cqi,1.35em);line-height:1.1"><?php echo e($nombreMinCita); ?></div>
      <?php if($proximaCita): ?>
        <div class="min-meta" style="font-size:clamp(0.65em,3cqi,0.9em)"><?php echo e($horaMinCita); ?> · <?php echo e($procMinCita); ?></div>
      <?php else: ?>
        <div class="min-meta" style="font-size:clamp(0.65em,3cqi,0.9em)">No hay citas agendadas</div>
      <?php endif; ?>
    </div>
    <span class="widget-resize-handle"></span>
  </div>
</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\dashboard\widgets\next-patient\minimalista.blade.php ENDPATH**/ ?>