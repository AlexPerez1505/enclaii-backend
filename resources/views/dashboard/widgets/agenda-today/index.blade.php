{{-- Widget: Agenda --}}
@php
  $now = now();
  $calY = $now->year;
  $calM = $now->month;
  $calD = $now->day;
  $first = \Carbon\Carbon::create($calY, $calM, 1);
  $daysInMonth = $first->daysInMonth;
  $startDow = ($first->dayOfWeek + 6) % 7; // 0 = Lunes
  $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
@endphp
<div class="widget rise d4" data-widget-id="agenda-today" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card" style="cursor:pointer" onclick="window.location.href='{{ route('agendar') }}'">
    <h3>AGENDAR DÍA</h3>
    <div class="cal-head">
      <span>{{ $meses[$calM - 1] }} {{ $calY }}</span>
      <span class="arrows"><button aria-label="Mes anterior">‹</button><button aria-label="Mes siguiente">›</button></span>
    </div>
    <table class="cal">
      <thead>
        <tr><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th></tr>
      </thead>
      <tbody>
        @php
          $cell = 0;
          $totalCells = $startDow + $daysInMonth;
          $rows = ceil($totalCells / 7);
        @endphp
        @for ($r = 0; $r < $rows; $r++)
          <tr>
            @for ($c = 0; $c < 7; $c++)
              @php
                $idx = $r * 7 + $c;
                $dayNum = $idx - $startDow + 1;
                $isToday = $dayNum === $calD && $idx >= $startDow;
                $isValid = $idx >= $startDow && $dayNum <= $daysInMonth;
              @endphp
              <td class="{{ $isToday ? 'today' : '' }} {{ !$isValid ? 'off' : '' }}">{{ $isValid ? $dayNum : '' }}</td>
            @endfor
          </tr>
        @endfor
      </tbody>
    </table>
  </article>
  <span class="widget-resize-handle"></span>
</div>
