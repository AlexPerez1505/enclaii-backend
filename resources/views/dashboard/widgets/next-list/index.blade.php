{{-- Widget: Pacientes Pendientes Hoy --}}
<div class="widget rise d6" data-widget-id="next-list" data-w="8">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card">
    <h3>PACIENTES PENDIENTES HOY</h3>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr><th>Paciente</th><th>Hora</th><th>Tipo de estudio</th><th>Estado</th><th>Médico</th><th>Acciones</th></tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="pat"><span class="mini">MG</span>María González</span></td>
            <td>10:30 AM</td><td>Endoscopia diagnóstica</td>
            <td><span class="chip wait">En espera</span></td>
            <td>Dr. Ricardo</td>
            <td><button class="dots" aria-label="Más opciones">⋮</button></td>
          </tr>
          <tr>
            <td><span class="pat"><span class="mini">JL</span>Jorge López</span></td>
            <td>11:15 AM</td><td>Colonoscopia</td>
            <td><span class="chip urgent">Urgente</span></td>
            <td>Dr. Ricardo</td>
            <td><button class="dots" aria-label="Más opciones">⋮</button></td>
          </tr>
          <tr>
            <td><span class="pat"><span class="mini">AR</span>Ana Ramírez</span></td>
            <td>12:00 PM</td><td>Endoscopia diagnóstica</td>
            <td><span class="chip done">Completado</span></td>
            <td>Dr. Ricardo</td>
            <td><button class="dots" aria-label="Más opciones">⋮</button></td>
          </tr>
          <tr>
            <td><span class="pat"><span class="mini">PT</span>Pedro Torres</span></td>
            <td>12:45 PM</td><td>Gastroscopia</td>
            <td><span class="chip wait">En espera</span></td>
            <td>Dr. Ricardo</td>
            <td><button class="dots" aria-label="Más opciones">⋮</button></td>
          </tr>
        </tbody>
      </table>
    </div>
    <a class="tbl-link" href="#">
      Ver agenda completa
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
  </article>
  <span class="widget-resize-handle"></span>
</div>
