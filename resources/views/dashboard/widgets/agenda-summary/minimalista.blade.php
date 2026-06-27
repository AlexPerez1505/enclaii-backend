{{-- Widget: Resumen de estudios (minimalista) --}}
@php
  $proxSum = $citasProximas ?? 0;
  $compSum = $citasCompletadas ?? 0;
  $cancSum = $citasCanceladas ?? 0;
  $totalSum = $proxSum + $compSum + $cancSum;
@endphp
<div class="widget widget-minimal mode-hidden d6" data-widget-id="agenda-summary-min" data-w="5">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-minimal card-minimal-summary" style="overflow:hidden">
    <div class="min-label" style="flex:0 0 auto">Estudios hoy</div>
    <div class="summary-grid" style="flex:1;display:grid;grid-template-columns:repeat(4,1fr);gap:0.6em;min-height:0">
      <div class="summary-cell summary-total" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.4em;padding:0.6em;border-radius:12px;background:rgba(46,123,246,.1);border:1px solid rgba(46,123,246,.2)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="width:1.8em;height:1.8em;color:var(--blue)">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="8" y1="13" x2="16" y2="13"/>
          <line x1="8" y1="17" x2="13" y2="17"/>
        </svg>
        <div class="summary-num" style="font-size:clamp(1.2em,5cqi,2em);font-weight:700;color:var(--blue)">{{ $totalSum }}</div>
        <div class="summary-label" style="font-size:clamp(0.6em,2.5cqi,0.8em);color:var(--txt-soft)">Total</div>
      </div>
      <div class="summary-cell summary-prox" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.4em;padding:0.6em;border-radius:12px;background:rgba(178,99,255,.1);border:1px solid rgba(178,99,255,.2)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="width:1.8em;height:1.8em;color:#B263FF">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
        <div class="summary-num" style="font-size:clamp(1.2em,5cqi,2em);font-weight:700;color:#B263FF">{{ $proxSum }}</div>
        <div class="summary-label" style="font-size:clamp(0.6em,2.5cqi,0.8em);color:var(--txt-soft)">Próximos</div>
      </div>
      <div class="summary-cell summary-comp" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.4em;padding:0.6em;border-radius:12px;background:rgba(61,220,151,.1);border:1px solid rgba(61,220,151,.2)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="width:1.8em;height:1.8em;color:#3DDC97">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="8 12 11 15 16 10"/>
        </svg>
        <div class="summary-num" style="font-size:clamp(1.2em,5cqi,2em);font-weight:700;color:#3DDC97">{{ $compSum }}</div>
        <div class="summary-label" style="font-size:clamp(0.6em,2.5cqi,0.8em);color:var(--txt-soft)">Completados</div>
      </div>
      <div class="summary-cell summary-canc" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.4em;padding:0.6em;border-radius:12px;background:rgba(255,90,110,.1);border:1px solid rgba(255,90,110,.2)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="width:1.8em;height:1.8em;color:#FF5A6E">
          <circle cx="12" cy="12" r="10"/>
          <line x1="8" y1="12" x2="16" y2="12"/>
        </svg>
        <div class="summary-num" style="font-size:clamp(1.2em,5cqi,2em);font-weight:700;color:#FF5A6E">{{ $cancSum }}</div>
        <div class="summary-label" style="font-size:clamp(0.6em,2.5cqi,0.8em);color:var(--txt-soft)">Cancelados</div>
      </div>
    </div>
  </article>
  <span class="widget-resize-handle"></span>
</div>
