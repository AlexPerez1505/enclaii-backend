
<div class="widget widget-minimal d5" data-widget-id="next-list-min" data-w="8">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-minimal card-minimal-list" style="overflow:hidden">
    <div class="min-label" style="flex:0 0 auto">Pacientes pendientes hoy</div>
    <ul class="min-list" style="flex:1;min-height:0;overflow-y:auto;display:flex;flex-direction:column;gap:0.5em">
      <?php $__empty_1 = true; $__currentLoopData = ($pendientesHoy ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
          $nombreList = $cita->paciente?->nombre_completo ?? $cita->paciente_nombre ?? 'Paciente';
          $urlList = $cita->paciente ? route('pacientes.index', ['paciente_id' => $cita->paciente->id]) : route('agenda');
          $horaList = format_user_time(\Carbon\Carbon::parse($cita->hora ?? '00:00'));
          $procList = $cita->procedimiento ?? 'Sin procedimiento';
        ?>
        <li style="flex:none">
          <a class="min-list-row" href="<?php echo e($urlList); ?>" style="display:flex;align-items:center;gap:0.65em;text-decoration:none;color:inherit">
            <span class="min-list-avatar" style="flex:none;display:grid;place-items:center;width:1.8em;height:1.8em;border-radius:50%;background:rgba(46,123,246,.18);color:var(--blue)">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:1em;height:1em">
                <path d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
              </svg>
            </span>
            <span class="min-list-time" style="flex:none;font-size:clamp(0.7em,3cqi,0.9em);color:var(--txt-soft)"><?php echo e($horaList); ?></span>
            <span class="min-list-name" style="flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:clamp(0.8em,3.5cqi,1em);font-weight:600"><?php echo e($nombreList); ?></span>
            <span class="min-list-proc" style="flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:clamp(0.7em,3cqi,0.9em);color:var(--txt-soft)"><?php echo e($procList); ?></span>
            <svg class="min-list-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex:none;width:1.1em;height:1.1em;color:var(--blue)">
              <line x1="5" y1="12" x2="19" y2="12"/>
              <polyline points="12 5 19 12 12 19"/>
            </svg>
          </a>
        </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <li class="min-list-empty" style="flex:1;display:grid;place-items:center;text-align:center;color:var(--txt-soft);font-size:clamp(0.8em,3.5cqi,1em)">No hay pacientes pendientes hoy.</li>
      <?php endif; ?>
    </ul>
    <a class="min-btn" href="<?php echo e(route('agenda')); ?>" style="flex:0 0 auto">
      Ver agenda
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
    <span class="widget-resize-handle"></span>
  </article>
</div>
<?php /**PATH C:\Users\gmedi\enclaii-backend\resources\views/dashboard/widgets/next-list/minimalista.blade.php ENDPATH**/ ?>