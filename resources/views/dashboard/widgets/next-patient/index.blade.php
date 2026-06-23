{{-- Widget: Próximo Paciente --}}
<div class="widget rise d2" data-widget-id="next-patient" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-next">
    <h3>PRÓXIMO PACIENTE</h3>
    <div class="name">María<br>Gonzales</div>
    <div class="meta">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      <b>10:30 AM</b>
    </div>
    <div class="meta"><b>Endoscopia diagnóstica</b></div>
    <a class="btn-line" href="{{ route('pacientes.index') }}?folio=00045">
      Abrir expediente
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
    <div class="holo">
      <div class="lottie-brain"></div>
    </div>
  </article>
  <span class="widget-resize-handle"></span>
</div>
