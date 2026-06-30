{{-- Widget: Reporte IA (minimalista) --}}
@php
  $pendientes = $estudiosSinReporte ?? 0;
@endphp
<div class="widget widget-minimal d2" data-widget-id="ia-pending-min" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-minimal card-minimal-ia" style="overflow:hidden">
    <div class="min-label" style="flex:0 0 auto">Reportes pendientes</div>
    <a class="min-icon" href="{{ route('ia-reportes.redactar') }}" style="flex:1 1 60%;width:100%;min-height:0;display:grid;place-items:center;color:var(--orange);text-decoration:none;cursor:pointer">
      <x-carbon-report style="width:100%;height:100%" />
    </a>
    <div class="min-text" style="flex:0 0 22%;display:flex;flex-direction:column;justify-content:center;gap:0.35em;text-align:center;min-height:0">
      <div class="min-value" style="font-size:clamp(0.85em,4.5cqi,1.35em);line-height:1.1">{{ $pendientes }} <span>pendiente{{ $pendientes == 1 ? '' : 's' }}</span></div>
    </div>
    <span class="widget-resize-handle"></span>
  </article>
</div>
