{{-- Widget: Agenda hoy (minimalista) --}}
@php
  $hoy = \Carbon\Carbon::now();
  $diaHoy = $hoy->format('d');
  $mesHoy = $hoy->translatedFormat('M');
  $anioHoy = $hoy->format('Y');
  $mesLargoHoy = $hoy->translatedFormat('F Y');
@endphp
<div class="widget widget-minimal d3" data-widget-id="agenda-today-min" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-minimal card-minimal-agenda" style="overflow:hidden">
    <div class="min-label" style="flex:0 0 auto">Hoy</div>
    <a class="min-icon" href="{{ route('agendar') }}" style="flex:1 1 60%;width:100%;min-height:0;display:grid;place-items:center;color:var(--blue);text-decoration:none;cursor:pointer">
      <x-forkawesome-calendar style="width:100%;height:100%" />
    </a>
    <div class="min-text" style="flex:0 0 22%;display:flex;flex-direction:column;justify-content:center;gap:0.35em;text-align:center;min-height:0">
      <div class="min-value" style="font-size:clamp(0.85em,4.5cqi,1.35em);line-height:1.1">{{ $diaHoy }} {{ $mesHoy }}</div>
      <div class="min-meta" style="font-size:clamp(0.65em,3cqi,0.9em)">{{ $mesLargoHoy }}</div>
    </div>
    <span class="widget-resize-handle"></span>
  </article>
</div>
