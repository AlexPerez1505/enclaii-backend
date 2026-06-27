{{-- Widget: Próximo Paciente --}}
@php
  $pacCita      = $proximaCita?->paciente;
  $nombreCita   = $pacCita?->nombre_completo ?? $proximaCita?->paciente_nombre ?? 'Sin citas próximas';
  $partesNombre = preg_split('/\s+/', trim($nombreCita), 3);
  $nombreCita   = trim(($partesNombre[0] ?? '') . ' ' . ($partesNombre[1] ?? ''));
@endphp
<div class="widget rise d2" data-widget-id="next-patient" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-next">
    <h3>PRÓXIMO PACIENTE</h3>
    @if ($proximaCita)
      <div class="name">{{ $nombreCita }}</div>
      <div class="meta">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <b>{{ $proximaCita->fecha?->format('d/m/Y') }} · {{ \Carbon\Carbon::parse($proximaCita->hora ?? '00:00')->format('g:i A') }}</b>
      </div>
      <div class="meta"><b>{{ $proximaCita->procedimiento ?? 'Procedimiento por definir' }}</b></div>
      <a class="btn-line" href="{{ $pacCita ? route('pacientes.index', ['paciente_id' => $pacCita->id]) : route('pacientes.index') }}">
        Abrir expediente
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    @else
      <div class="name">Sin citas<br>próximas</div>
      <div class="meta"><b>No hay citas agendadas</b></div>
    @endif
    <div class="holo">
      <div class="lottie-brain"></div>
    </div>
  </article>
  <span class="widget-resize-handle"></span>
</div>
