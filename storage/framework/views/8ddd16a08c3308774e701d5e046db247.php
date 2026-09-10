
<div class="widget rise d6" data-widget-id="next-list" data-w="8">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card">
    <h3>PACIENTES PENDIENTES HOY</h3>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr><th>Paciente</th><th>Hora</th><th>Tipo de estudio</th><th>Estado</th><th>Médico</th><th>Acciones</th></tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = ($pendientesHoy ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
              $nombreCita = $cita->paciente?->nombre_completo ?? $cita->paciente_nombre ?? 'Paciente';
              $partes = preg_split('/\s+/', trim($nombreCita));
              $mini = count($partes) >= 2
                ? mb_strtoupper(mb_substr($partes[0],0,1).mb_substr($partes[1],0,1))
                : mb_strtoupper(mb_substr($nombreCita,0,2));
              $estado = $cita->estado ?? 'en_espera';
              $chipCls = match($estado) {
                'completado' => 'done',
                'cancelado'  => 'cancel',
                'proximo'    => 'wait',
                default      => 'wait',
              };
              $chipLabel = match($estado) {
                'completado' => 'Completado',
                'cancelado'  => 'Cancelado',
                'proximo'    => 'Próxima',
                default      => 'En espera',
              };
              $medico = $cita->paciente?->medico ?? '—';
            ?>
            <tr>
              <td><span class="pat"><span class="mini"><?php echo e($mini); ?></span><?php echo e($nombreCita); ?></span></td>
              <td><?php echo e(format_user_time(\Carbon\Carbon::parse($cita->hora ?? '00:00'))); ?></td>
              <td><?php echo e($cita->procedimiento ?? 'Sin procedimiento'); ?></td>
              <td><span class="chip <?php echo e($chipCls); ?>"><?php echo e($chipLabel); ?></span></td>
              <td><?php echo e($medico); ?></td>
              <td><button class="dots" aria-label="Más opciones">⋮</button></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="6" style="text-align:center;padding:24px;color:var(--txt-soft)">No hay pacientes pendientes para hoy.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <a class="tbl-link" href="<?php echo e(route('agenda')); ?>">
      Ver agenda completa
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
  </article>
  <span class="widget-resize-handle"></span>
</div>
<?php /**PATH C:\Users\LENOVO\enclaii-backend\resources\views/dashboard/widgets/next-list/index.blade.php ENDPATH**/ ?>