{{-- Widget: Próximo Paciente (minimalista) --}}
<div class="widget widget-minimal mode-hidden d1" data-widget-id="next-patient-min" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-minimal card-minimal-next">
    <div class="min-label">Próximo paciente</div>
    <div class="min-value">María<br>Gonzales</div>
    <div class="min-meta">10:30 AM · Endoscopia diagnóstica</div>
    <a class="min-btn" href="{{ route('pacientes.index') }}?folio=00045">
      Abrir expediente
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
  </article>
  <span class="widget-resize-handle"></span>
</div>
