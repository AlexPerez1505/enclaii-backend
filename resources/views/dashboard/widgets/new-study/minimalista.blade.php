{{-- Widget: Acciones rápidas (minimalista) --}}
<div class="widget widget-minimal mode-hidden d4" data-widget-id="new-study-min" data-w="4">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-minimal card-minimal-actions">
    <div class="min-label">Acciones rápidas</div>
    <a class="min-btn" href="{{ route('nuevo-estudio') }}">
      Nuevo estudio
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    </a>
    <a class="min-btn" href="{{ route('mensajes') }}">
      WhatsApp
    </a>
    <a class="min-btn" href="{{ route('pacientes.index') }}">
      Buscar paciente
    </a>
  </article>
  <span class="widget-resize-handle"></span>
</div>
