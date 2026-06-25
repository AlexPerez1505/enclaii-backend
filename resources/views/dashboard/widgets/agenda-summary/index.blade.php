{{-- Widget: Resumen del día --}}
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
          {{-- circunferencia = 314.16 | azul 8/12, verde 2/12, rojo 2/12 --}}
          <circle cx="60" cy="60" r="50" stroke="#2E7BF6" stroke-dasharray="209.4 314.16" stroke-dashoffset="0"/>
          <circle cx="60" cy="60" r="50" stroke="#3DDC97" stroke-dasharray="52.36 314.16" stroke-dashoffset="-209.4"/>
          <circle cx="60" cy="60" r="50" stroke="#FF5A6E" stroke-dasharray="52.36 314.16" stroke-dashoffset="-261.8"/>
        </svg>
        <div class="donut-center">
          <div>
            <div class="n" id="numEstudios" data-target="12">0</div>
            <div class="l">Total de<br>estudios</div>
          </div>
        </div>
      </div>
      <div class="legend">
        <span class="b"><i></i>8 Pendientes</span>
        <span class="g"><i></i>2 Completados</span>
        <span class="r"><i></i>2 Cancelados</span>
      </div>
    </div>
    <div class="next-list">
      <h4>Próximos estudios</h4>
      <div class="next-item"><span class="t">10:30 AM</span><span class="n">Ana Ramírez</span><span class="chip wait">En espera</span></div>
      <div class="next-item"><span class="t">11:15 AM</span><span class="n">Luis Mendoza</span><span class="chip wait">En espera</span></div>
      <div class="next-item"><span class="t">12:00 PM</span><span class="n">Carla Ortiz</span><span class="chip urgent">Urgente</span></div>
    </div>
  </article>
  <span class="widget-resize-handle"></span>
</div>
