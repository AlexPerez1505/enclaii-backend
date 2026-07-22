{{-- ============================================================
     AGENDAR / _cita.blade.php
     Paso 2: Datos de la cita
     (Duración movida al Paso 3: Fecha y Hora)
     ============================================================ --}}

<style>
.cita-icon-wrap{display:flex;align-items:center;gap:8px}
.cita-icon-wrap svg{color:var(--ag-soft);flex:none}
html[data-theme="light"] .cita-icon-wrap svg{color:#5B6A99}
#citaFecha[readonly],#citaHora[readonly]{cursor:default;opacity:.8}
</style>

<div class="ag-card" id="stepCita">
  <div class="ag-card-title">
    <span class="ag-step-badge">2</span>
    Datos De la Cita
  </div>

  <div class="ag-field">
    <label class="ag-label">Doctor</label>
    <div class="ag-input-icon">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      <select class="ag-input ag-select" id="citaEspecialista">
        <option value="" disabled selected>Seleccione a un Doctor</option>
      </select>
    </div>
  </div>

  <div class="ag-field">
    <label class="ag-label">Procedimiento</label>
    <div class="ag-input-icon">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      <select class="ag-input ag-select" id="citaProcedimiento">
        <option value="" disabled selected>Seleccione procedimiento</option>
      </select>
    </div>
  </div>

  <div class="ag-row ag-field">
    <div>
      <label class="ag-label">Fecha</label>
      <div class="ag-input-icon">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <input class="ag-input" id="citaFecha" type="text" placeholder="{{ str_replace(['d','m','Y'], ['DD','MM','AAAA'], user_date_format()) }}" value="{{ date(user_date_format()) }}" readonly>
      </div>
    </div>

    <div>
      <label class="ag-label">Hora</label>
      <input class="ag-input" id="citaHora" type="text" placeholder="08:00 AM" value="" readonly>
    </div>
  </div>

  <select class="ag-input ag-select" name="sala_id" id="citaSala">
    <option value="">Seleccione una sala</option>
    @foreach($salas as $sala)
        <option value="{{ $sala->id }}" {{ ($citaData['sala_id'] ?? null) == $sala->id ? 'selected' : '' }}>
            {{ $sala->nombre }}
        </option>
    @endforeach
</select>

  
</div>

@push('scripts')
<script>
(function(){
  const DATE_FORMAT = @json(auth()->user()?->settings['date_format'] ?? 'DD/MM/YYYY');

  function fechaToKey(fecha) {
    const value = String(fecha || '').trim();
    const ymdMatch = value.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
    if (ymdMatch) {
      return `${parseInt(ymdMatch[1], 10)}-${parseInt(ymdMatch[2], 10)}-${parseInt(ymdMatch[3], 10)}`;
    }

    const slashMatch = value.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
    if (!slashMatch) return '';

    const first = parseInt(slashMatch[1], 10);
    const second = parseInt(slashMatch[2], 10);
    const year = parseInt(slashMatch[3], 10);
    const month = DATE_FORMAT === 'MM/DD/YYYY' ? first : second;
    const day = DATE_FORMAT === 'MM/DD/YYYY' ? second : first;

    return `${year}-${month}-${day}`;
  }

  function hora12ToMinutes(hora) {
    const value = String(hora || '').trim();
    const match = value.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)?$/i);
    if (!match) return null;
    let h = parseInt(match[1], 10);
    const min = parseInt(match[2], 10);
    const ampm = match[3] ? match[3].toUpperCase() : '';
    if (ampm === 'PM' && h < 12) h += 12;
    if (ampm === 'AM' && h === 12) h = 0;
    return h * 60 + min;
  }

  function overlaps(start, end, events, salaId) {
    return events.some(ev => {
      if (!ev || String(ev.sala_id) !== String(salaId)) return false;
      if (window.__CITA_EDITAR_ID && String(ev.id || '') === String(window.__CITA_EDITAR_ID)) return false;
      const [h, m] = String(ev.hora || '00:00').split(':').map(Number);
      const evStart = (h || 0) * 60 + (m || 0);
      const evEnd = evStart + (parseInt(ev.duracion || ev.duration || 60, 10));
      return start < evEnd && end > evStart;
    });
  }

  window.__updateSalasDisponibles = function() {
    const select = document.getElementById('citaSala');
    const fechaInput = document.getElementById('citaFecha');
    const horaInput = document.getElementById('citaHora');
    const duracionInput = document.getElementById('citaDuracion');
    if (!select || !fechaInput || !horaInput) return;

    const key = fechaToKey(fechaInput.value);
    const start = hora12ToMinutes(horaInput.value);
    if (!key || start === null) return;

    const duracion = parseInt(duracionInput?.value, 10) || 60;
    const end = start + duracion;
    const events = (window.__AGENDA_EVENTS && window.__AGENDA_EVENTS[key]) || [];

    Array.from(select.options).forEach(opt => {
      if (!opt.value) return;
      const ocupada = overlaps(start, end, events, opt.value);
      if (ocupada) {
        opt.disabled = true;
        if (select.value === opt.value) select.value = '';
      } else {
        opt.disabled = false;
      }
    });
  };

  document.getElementById('citaSala')?.addEventListener('change', window.__updateSalasDisponibles);
  window.__updateSalasDisponibles();
})();
</script>
@endpush
