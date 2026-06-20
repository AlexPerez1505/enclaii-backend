{{-- ============================================================
     AGENDAR / _cita.blade.php
     Paso 2: Datos de la cita
     Duración libre en minutos
     ============================================================ --}}

<style>
.cita-icon-wrap{display:flex;align-items:center;gap:8px}
.cita-icon-wrap svg{color:var(--ag-soft);flex:none}
html[data-theme="light"] .cita-icon-wrap svg{color:#5B6A99}

.duracion-libre-wrap{display:flex;align-items:center;gap:8px}
.duracion-libre-wrap .ag-input{flex:1}
.duracion-suffix{
  font-size:12px;
  color:var(--ag-soft);
  white-space:nowrap;
}
html[data-theme="light"] .duracion-suffix{color:#5B6A99}
</style>

<div class="ag-card" id="stepCita">
  <div class="ag-card-title">
    <span class="ag-step-badge">2</span>
    Datos De la Cita
  </div>

  <div class="ag-field">
    <label class="ag-label">Especialista</label>
    <div class="ag-input-icon">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      <select class="ag-input ag-select" id="citaEspecialista">
        <option selected>Dr. Victor</option>
      </select>
    </div>
  </div>

  <div class="ag-field">
    <label class="ag-label">Procedimiento</label>
    <div class="ag-input-icon">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      <select class="ag-input ag-select" id="citaProcedimiento">
        <option>Colonoscopia</option>
        <option>Gastroscopía</option>
        <option>Dudoescopía</option>
        <option>Broncoscopia</option>
      </select>
    </div>
  </div>

  <div class="ag-row ag-field">
    <div>
      <label class="ag-label">Fecha</label>
      <div class="ag-input-icon">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <input class="ag-input" id="citaFecha" type="text" placeholder="DD/MM/AAAA" value="{{ date('d/m/Y') }}">
      </div>
    </div>

    <div>
      <label class="ag-label">Hora</label>
      <input class="ag-input" id="citaHora" type="text" placeholder="10:00 AM" value="10:00 AM">
    </div>
  </div>

  <div class="ag-row ag-field">
    <div>
      <label class="ag-label">Duración</label>
      <div class="duracion-libre-wrap">
        <input
          class="ag-input"
          id="citaDuracion"
          type="number"
          min="1"
          max="1440"
          step="1"
          value="60"
          placeholder="Ej. 45"
        >
        <span class="duracion-suffix">min</span>
      </div>
    </div>

    <div>
      <label class="ag-label">Sala</label>
      <select class="ag-input ag-select" id="citaSala">
        <option>Sala 1</option>
        <option>Sala 2</option>
        <option selected>Sala 3</option>
        <option>Sala 4</option>
      </select>
    </div>
  </div>

  <div class="ag-field">
    <label class="ag-label">Recursos</label>
    <select class="ag-input ag-select" id="citaRecurso">
      <option>Endoscopio Olympus</option>
      <option>Endoscopio Pentax</option>
      <option>Colonoscopio Fujinon</option>
    </select>
  </div>
</div>
