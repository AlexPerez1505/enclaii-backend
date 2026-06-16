{{-- ============================================================
     AGENDA / _sidebar.blade.php
     Panel derecho: filtros rápidos + próximas citas
     (Estilos en _base.blade.php)
     ============================================================ --}}

<div class="filter-card">
  <h4>Filtros rápidos</h4>
  <label class="filter-item fi-done">
    <input type="checkbox" checked>
    <span class="filter-dot" style="background:linear-gradient(to bottom,#042226 20%,#4C9242 80%);border:1.38px solid #284D23"></span>
    Completado
  </label>
  <label class="filter-item fi-wait">
    <input type="checkbox" checked>
    <span class="filter-dot" style="background:linear-gradient(to bottom,#351909 29%,#9B491A 100%);border:1.24px solid #E75D01"></span>
    En espera
  </label>
  <label class="filter-item fi-cancel">
    <input type="checkbox" checked>
    <span class="filter-dot" style="background:linear-gradient(to bottom,#251117 38%,#D90000 100%);border:1.27px solid #D90000"></span>
    Cancelado
  </label>
  <label class="filter-item fi-soon">
    <input type="checkbox" checked>
    <span class="filter-dot" style="background:linear-gradient(to bottom,#0B1331 43%,#B263FF 100%);border:1.27px solid #B263FF"></span>
    Próximamente
  </label>
</div>

<div class="proximas-card">
  <h4>Próximas citas</h4>
  <div class="prox-item">
    <div class="prox-avatar">DM</div>
    <div class="prox-info">
      <strong>Dulce Martínez</strong>
      <span>Endoscopia Diagnóstica</span>
      <span>Hoy · 11:30 AM</span>
    </div>
  </div>
  <div class="prox-item">
    <div class="prox-avatar">YH</div>
    <div class="prox-info">
      <strong>Yukary Huerta</strong>
      <span>Endoscopia Diagnóstica</span>
      <span>Mañana · 1:00 PM</span>
    </div>
  </div>
  <div class="prox-item">
    <div class="prox-avatar">EF</div>
    <div class="prox-info">
      <strong>Evelin Fonseca</strong>
      <span>Endoscopia Diagnóstica</span>
      <span>Mañana · 11:30 AM</span>
    </div>
  </div>
  <div class="prox-item">
    <div class="prox-avatar">PG</div>
    <div class="prox-info">
      <strong>Pelet Gómez</strong>
      <span>Endoscopia Diagnóstica</span>
      <span>Mañana · 11:30 AM</span>
    </div>
  </div>
  <div class="prox-item">
    <div class="prox-avatar">RM</div>
    <div class="prox-info">
      <strong>Ricardo Martínez</strong>
      <span>Endoscopia Diagnóstica</span>
      <span>Mañana · 11:30 AM</span>
    </div>
  </div>
  <a href="#" class="more-link">+ 5 citas más</a>
</div>
