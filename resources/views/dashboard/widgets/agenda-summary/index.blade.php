{{-- Widget: Resumen del día --}}
@php
  $proximas = $citasProximas ?? 0;
  $completados = $citasCompletadas ?? 0;
  $cancelados = $citasCanceladas ?? 0;
  $total = $proximas + $completados + $cancelados;
  $circ = 314.16;

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
@endphp
<div class="widget rise d7" data-widget-id="agenda-summary" data-w="5">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card">
    <h3>RESUMEN DE ESTUDIOS</h3>
    <div class="donut-box">
      <div class="donut">
        <svg viewBox="0 0 120 120">
          <circle class="track" cx="60" cy="60" r="50"/>
          @if($total > 0)
            <circle cx="60" cy="60" r="50" stroke="#2E7BF6" stroke-dasharray="{{ $proxSlice['dash'] }}" stroke-dashoffset="0"/>
            <circle cx="60" cy="60" r="50" stroke="#3DDC97" stroke-dasharray="{{ $compSlice['dash'] }}" stroke-dashoffset="{{ $offsetComp }}"/>
            <circle cx="60" cy="60" r="50" stroke="#FF5A6E" stroke-dasharray="{{ $cancSlice['dash'] }}" stroke-dashoffset="{{ $offsetCanc }}"/>
          @endif
        </svg>
        <div class="donut-center">
          <div>
            <div class="n" id="numEstudios" data-target="{{ $total }}">0</div>
            <div class="l">Total de<br>citas</div>
          </div>
        </div>
      </div>
      <div class="legend">
        <span class="b"><i></i>{{ $proximas }} Próximas</span>
        <span class="g"><i></i>{{ $completados }} Completadas</span>
        <span class="r"><i></i>{{ $cancelados }} Canceladas</span>
      </div>
    </div>
    <div class="next-list">
      <h4>Próximos estudios</h4>
      @forelse($pendientesHoy ?? [] as $cita)
        @php
          $hora = format_user_time(\Carbon\Carbon::parse($cita->hora));
          $chip = $cita->estado === 'proximo' ? 'wait' : 'urgent';
          $chipText = $cita->estado === 'proximo' ? 'Próxima' : 'En espera';
        @endphp
        <div class="next-item"><span class="t">{{ $hora }}</span><span class="n">{{ $cita->paciente->nombre_completo ?? 'Paciente' }}</span><span class="chip {{ $chip }}">{{ $chipText }}</span></div>
      @empty
        <div class="next-item" style="opacity:.7"><span class="t">--</span><span class="n">No hay estudios próximos hoy</span></div>
      @endforelse
    </div>
  </article>
  <span class="widget-resize-handle"></span>
</div>
