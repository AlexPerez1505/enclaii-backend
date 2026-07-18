
<?php
  $proximas = $citasProximasMes ?? ($citasProximas ?? 0);
  $completados = $citasCompletadasMes ?? ($citasCompletadas ?? 0);
  $cancelados = $citasCanceladasMes ?? ($citasCanceladas ?? 0);
  $total = $proximas + $completados + $cancelados;
  $circ = 314.16;
  $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
  $mesActual = $meses[($widgetMes ?? now()->month) - 1] ?? '';
  $anioActual = $widgetAnio ?? now()->year;

  function donutSlice($value, $total, $circ) {
    if ($total <= 0) return ['dash' => '0 ' . $circ, 'offset' => 0];
    $len = ($value / $total) * $circ;
    return ['dash' => number_format($len, 2) . ' ' . $circ, 'offset' => 0];
  }

  $proxSlice = donutSlice($proximas, $total, $circ);
  $compSlice = donutSlice($completados, $total, $circ);
  $cancSlice = donutSlice($cancelados, $total, $circ);

  $offsetComp = '-' . number_format($proximas / max($total, 1) * $circ, 2);
  $offsetCanc = '-' . number_format(($proximas + $completados) / max($total, 1) * $circ, 2);
?>
<div class="widget rise d7" data-widget-id="agenda-summary" data-w="5">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card">
    <h3>RESUMEN DE ESTUDIOS · <?php echo e(strtoupper($mesActual)); ?> <?php echo e($anioActual); ?></h3>
    <div class="donut-box">
      <div class="donut">
        <svg viewBox="0 0 120 120">
          <circle class="track" cx="60" cy="60" r="50"/>
          <?php if($total > 0): ?>
            <circle cx="60" cy="60" r="50" stroke="#2E7BF6" stroke-dasharray="<?php echo e($proxSlice['dash']); ?>" stroke-dashoffset="0"/>
            <circle cx="60" cy="60" r="50" stroke="#3DDC97" stroke-dasharray="<?php echo e($compSlice['dash']); ?>" stroke-dashoffset="<?php echo e($offsetComp); ?>"/>
            <circle cx="60" cy="60" r="50" stroke="#FF5A6E" stroke-dasharray="<?php echo e($cancSlice['dash']); ?>" stroke-dashoffset="<?php echo e($offsetCanc); ?>"/>
          <?php endif; ?>
        </svg>
        <div class="donut-center">
          <div>
            <div class="n" id="numEstudios" data-target="<?php echo e($total); ?>">0</div>
            <div class="l">Total de<br>citas</div>
          </div>
        </div>
      </div>
      <div class="legend">
        <span class="b"><i></i><?php echo e($proximas); ?> Próximas</span>
        <span class="g"><i></i><?php echo e($completados); ?> Completadas</span>
        <span class="r"><i></i><?php echo e($cancelados); ?> Canceladas</span>
      </div>
    </div>
    <div class="next-list">
      <h4>Próximos estudios del mes</h4>
      <?php $__empty_1 = true; $__currentLoopData = $pendientesMes ?? ($pendientesHoy ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
          $fecha = \Carbon\Carbon::parse($cita->fecha)->format('d/m');
          $hora = format_user_time(\Carbon\Carbon::parse($cita->hora));
          $chip = $cita->estado === 'proximo' ? 'wait' : 'urgent';
          $chipText = $cita->estado === 'proximo' ? 'Próxima' : 'En espera';
        ?>
        <div class="next-item"><span class="t"><?php echo e($fecha); ?> <?php echo e($hora); ?></span><span class="n"><?php echo e($cita->paciente->nombre_completo ?? 'Paciente'); ?></span><span class="chip <?php echo e($chip); ?>"><?php echo e($chipText); ?></span></div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="next-item" style="opacity:.7"><span class="t">--</span><span class="n">No hay estudios próximos en este mes</span></div>
      <?php endif; ?>
    </div>
  </article>
  <span class="widget-resize-handle"></span>
</div>
<?php /**PATH C:\Users\yessi\enclaii-backend\resources\views/dashboard/widgets/agenda-summary/index.blade.php ENDPATH**/ ?>