
<?php
  $now = now();
  $calY = (int) request()->query('widget_anio', $now->year);
  $calM = (int) request()->query('widget_mes', $now->month);
  $calD = $now->day;
  $first = \Carbon\Carbon::create($calY, $calM, 1);
  $daysInMonth = $first->daysInMonth;
  $startDow = ($first->dayOfWeek + 6) % 7; // 0 = Lunes
  $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

  $prev = \Carbon\Carbon::create($calY, $calM, 1)->subMonth();
  $next = \Carbon\Carbon::create($calY, $calM, 1)->addMonth();
  $urlPrev = request()->fullUrlWithQuery(['widget_mes' => $prev->month, 'widget_anio' => $prev->year]);
  $urlNext = request()->fullUrlWithQuery(['widget_mes' => $next->month, 'widget_anio' => $next->year]);
?>
<div class="widget rise d4" data-widget-id="agenda-today" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card" style="cursor:pointer" onclick="if(!event.target.closest('.cal-nav-btn')) window.location.href='<?php echo e(route('agendar')); ?>'">
    <h3>AGENDAR DÍA</h3>
    <div class="cal-head">
      <span><?php echo e($meses[$calM - 1]); ?> <?php echo e($calY); ?></span>
      <span class="arrows">
        <button type="button" class="cal-nav-btn" data-dir="prev" data-url="<?php echo e($urlPrev); ?>" aria-label="Mes anterior">‹</button>
        <button type="button" class="cal-nav-btn" data-dir="next" data-url="<?php echo e($urlNext); ?>" aria-label="Mes siguiente">›</button>
      </span>
    </div>
    <table class="cal">
      <thead>
        <tr><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th></tr>
      </thead>
      <tbody>
        <?php
          $cell = 0;
          $totalCells = $startDow + $daysInMonth;
          $rows = ceil($totalCells / 7);
        ?>
        <?php for($r = 0; $r < $rows; $r++): ?>
          <tr>
            <?php for($c = 0; $c < 7; $c++): ?>
              <?php
                $idx = $r * 7 + $c;
                $dayNum = $idx - $startDow + 1;
                $isCurrentMonth = $calY === (int) $now->year && $calM === (int) $now->month;
                $isToday = $isCurrentMonth && $dayNum === $calD && $idx >= $startDow;
                $isValid = $idx >= $startDow && $dayNum <= $daysInMonth;
                $dayUrl = $isValid ? route('agendar', ['dia' => $dayNum, 'mes' => $calM, 'anio' => $calY]) : '#';
                $isPast = $isValid && \Carbon\Carbon::create($calY, $calM, $dayNum)->isBefore(today());
              ?>
              <?php if($isValid && !$isPast): ?>
                <td class="<?php echo e($isToday ? 'today' : ''); ?>" onclick="event.stopPropagation(); window.location.href='<?php echo e($dayUrl); ?>'"><?php echo e($dayNum); ?></td>
              <?php elseif($isValid && $isPast): ?>
                <td class="past <?php echo e($isToday ? 'today' : ''); ?>"><?php echo e($dayNum); ?></td>
              <?php elseif($idx < $startDow): ?>
                
                <?php $prevDayNum = $prev->daysInMonth - ($startDow - $idx - 1); ?>
                <td class="off"><?php echo e($prevDayNum); ?></td>
              <?php else: ?>
                
                <td class="off"><?php echo e($dayNum - $daysInMonth); ?></td>
              <?php endif; ?>
            <?php endfor; ?>
          </tr>
        <?php endfor; ?>
      </tbody>
    </table>
  </article>
  <span class="widget-resize-handle"></span>
</div>
<?php /**PATH C:\laragon\www\endocare\resources\views/dashboard/widgets/agenda-today/index.blade.php ENDPATH**/ ?>