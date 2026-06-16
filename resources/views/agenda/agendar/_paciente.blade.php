{{-- ============================================================
     AGENDAR / _paciente.blade.php
     Paso 1: Datos del paciente
     ============================================================ --}}
<style>
.pac-search-wrap{position:relative;margin-bottom:14px}
.pac-search-btn{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--ag-soft);cursor:pointer;display:grid;place-items:center;padding:4px}
.pac-search-btn:hover{color:var(--ag-txt)}
.pac-result{background:#001A30;border:1.5px solid var(--ag-stroke);border-radius:10px;padding:14px;display:flex;align-items:center;gap:12px;margin-bottom:12px}
.pac-avatar{width:46px;height:46px;border-radius:50%;flex:none;background:linear-gradient(135deg,#1668D9,#00C8C8);display:grid;place-items:center;font-family:'Sora',sans-serif;font-size:13px;font-weight:700;color:#fff;border:2px solid rgba(22,139,217,.4);overflow:hidden}
.pac-avatar img{width:100%;height:100%;object-fit:cover}
.pac-info{flex:1;min-width:0}
.pac-name{font-size:14px;font-weight:700;color:var(--ag-txt)}
.pac-folio{font-size:11px;color:var(--ag-soft)}
.pac-meta{display:flex;align-items:center;gap:14px;margin-top:6px;flex-wrap:wrap}
.pac-meta-item{display:flex;align-items:center;gap:5px;font-size:11px;color:var(--ag-soft)}
.pac-meta-item svg{flex:none;opacity:.7}
.pac-chevron{background:none;border:none;color:var(--ag-soft);cursor:pointer;display:grid;place-items:center;padding:4px;flex:none}
.pac-fields{display:flex;flex-direction:column;gap:0}
.pac-link{display:inline-flex;align-items:center;gap:6px;font-size:12.5px;color:var(--ag-blue);background:none;border:none;cursor:pointer;padding:0;font-family:inherit;margin-top:4px}
.pac-link:hover{opacity:.8}

/* Filtro panel */
.pac-filter-btn{position:absolute;right:40px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;display:grid;place-items:center;padding:4px;color:var(--ag-blue)}
.pac-filter-btn:hover{opacity:.8}
.pac-filter-panel{
  position:fixed;top:0;right:-340px;width:320px;max-width:95vw;height:100%;z-index:1200;
  background:#0A1628;border-left:1.5px solid rgba(22,139,217,.3);
  box-shadow:-12px 0 40px rgba(0,0,0,.6);
  display:flex;flex-direction:column;
  transition:right 250ms cubic-bezier(.4,0,.2,1);
  overflow-y:auto;
}
.pac-filter-panel.open{right:0}
.pac-filter-overlay{
  position:fixed;inset:0;z-index:1199;
  background:rgba(0,0,0,.45);backdrop-filter:blur(3px);
  opacity:0;pointer-events:none;
  transition:opacity 200ms ease;
}
.pac-filter-overlay.open{opacity:1;pointer-events:all}
.pac-filter-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:20px 20px 14px;
  border-bottom:1px solid rgba(255,255,255,.07);
}
.pac-filter-title{font-family:'Sora',sans-serif;font-size:18px;font-weight:700;color:#fff}
.pac-filter-apply{
  padding:8px 18px;border-radius:9px;
  border:1.5px solid rgba(22,139,217,.5);
  background:transparent;color:#EAF1FF;
  font-family:'Sora',sans-serif;font-size:13px;font-weight:700;
  cursor:pointer;transition:background 150ms;
}
.pac-filter-apply:hover{background:rgba(22,139,217,.15)}
.pac-filter-body{padding:18px 20px;display:flex;flex-direction:column;gap:14px;flex:1}
.pac-filter-lbl{font-size:12px;font-weight:700;color:rgba(255,255,255,.6);margin-bottom:5px}
.pac-filter-input{
  width:100%;padding:10px 38px 10px 14px;border-radius:10px;
  border:1.5px solid rgba(22,139,217,.3);
  background:rgba(255,255,255,.04);color:#EAF1FF;
  font-family:inherit;font-size:13px;box-sizing:border-box;
}
.pac-filter-input::placeholder{color:rgba(234,241,255,.3)}
.pac-filter-input:focus{outline:none;border-color:rgba(22,139,217,.7)}
.pac-filter-select{
  width:100%;padding:10px 14px;border-radius:10px;
  border:1.5px solid rgba(22,139,217,.3);
  background:rgba(255,255,255,.04);color:#EAF1FF;
  font-family:inherit;font-size:13px;appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238FA3CF' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 12px center;
  box-sizing:border-box;
}
.pac-filter-select:focus{outline:none;border-color:rgba(22,139,217,.7)}
.pac-filter-search-wrap{position:relative}
.pac-filter-search-icon{position:absolute;right:10px;top:50%;transform:translateY(-50%);color:rgba(234,241,255,.3);pointer-events:none}
.pac-filter-advanced{
  margin:0 20px 8px;padding:11px;border-radius:10px;
  border:1.5px solid rgba(22,139,217,.5);
  background:rgba(22,139,217,.12);color:#EAF1FF;
  font-family:inherit;font-size:13px;font-weight:700;
  cursor:pointer;transition:background 150ms;
}
.pac-filter-advanced:hover{background:rgba(22,139,217,.2)}
.pac-filter-advanced-body{
  display:none;padding:0 20px 20px;
  flex-direction:column;gap:14px;
}
.pac-filter-advanced-body.open{display:flex}
.pac-filter-date-tabs{display:flex;gap:6px}
.pac-filter-date-tab{
  flex:1;padding:9px 6px;border-radius:9px;
  border:1.5px solid rgba(22,139,217,.3);
  background:transparent;color:rgba(234,241,255,.6);
  font-family:inherit;font-size:12px;font-weight:700;
  cursor:pointer;transition:background 150ms,color 150ms,border-color 150ms;
}
.pac-filter-date-tab.active{
  background:rgba(22,139,217,.25);border-color:rgba(22,139,217,.8);color:#EAF1FF;
}
.pac-filter-range{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.pac-filter-range-item{display:flex;flex-direction:column;gap:4px}
.pac-filter-range-lbl{font-size:11px;color:rgba(255,255,255,.4)}
.pac-filter-range-input{
  width:100%;padding:9px 12px;border-radius:9px;
  border:1.5px solid rgba(22,139,217,.3);
  background:rgba(255,255,255,.04);color:#EAF1FF;
  font-family:inherit;font-size:13px;font-weight:700;
  box-sizing:border-box;text-align:center;
}
.pac-filter-range-input:focus{outline:none;border-color:rgba(22,139,217,.7)}
html[data-theme="light"] .pac-filter-advanced{background:rgba(22,104,217,.08);border-color:rgba(20,50,120,.35);color:#0E1530}
html[data-theme="light"] .pac-filter-date-tab{border-color:rgba(20,50,120,.2);color:rgba(14,21,48,.5)}
html[data-theme="light"] .pac-filter-date-tab.active{background:rgba(22,104,217,.12);border-color:#1668D9;color:#0E1530}
html[data-theme="light"] .pac-filter-range-lbl{color:rgba(14,21,48,.4)}
html[data-theme="light"] .pac-filter-range-input{background:rgba(20,50,120,.04);border-color:rgba(20,50,120,.2);color:#0E1530}
/* Tema claro */
html[data-theme="light"] .pac-filter-panel{background:#F0F5FF;border-left-color:rgba(20,50,120,.2);box-shadow:-12px 0 40px rgba(20,50,120,.12)}
html[data-theme="light"] .pac-filter-title{color:#0E1530}
html[data-theme="light"] .pac-filter-apply{border-color:rgba(20,50,120,.35);color:#0E1530}
html[data-theme="light"] .pac-filter-input{background:rgba(20,50,120,.04);border-color:rgba(20,50,120,.2);color:#0E1530}
html[data-theme="light"] .pac-filter-input::placeholder{color:rgba(14,21,48,.3)}
html[data-theme="light"] .pac-filter-select{background-color:rgba(20,50,120,.04);border-color:rgba(20,50,120,.2);color:#0E1530}
html[data-theme="light"] .pac-filter-advanced{border-color:rgba(20,50,120,.3);color:#0E1530}
html[data-theme="light"] .pac-filter-lbl{color:rgba(14,21,48,.55)}
html[data-theme="light"] .pac-filter-overlay{background:rgba(0,0,0,.25)}

/* Tema claro - botones de búsqueda */
html[data-theme="light"] .pac-filter-btn{color:#1668D9}
html[data-theme="light"] .pac-filter-btn:hover{color:#0040A0}
html[data-theme="light"] .pac-search-btn{color:#5B6A99}
html[data-theme="light"] .pac-search-btn:hover{color:#0E1530}

/* Tema claro */
html[data-theme="light"] .pac-result{background:#F0F5FF;border-color:rgba(20,50,120,.15)}
html[data-theme="light"] .pac-name{color:#0E1530}
html[data-theme="light"] .pac-folio{color:#5B6A99}
html[data-theme="light"] .pac-meta-item{color:#5B6A99}
</style>

<div class="ag-card" id="stepPaciente">
  <div class="ag-card-title">
    <span class="ag-step-badge">1</span>
    Datos Del Paciente
  </div>

  <label class="ag-label">Buscar paciente</label>
  <div class="pac-search-wrap">
    <input class="ag-input" id="pacSearch" type="text" placeholder="Busca por nombre del paciente" style="padding-right:72px">
    <button class="pac-filter-btn" id="pacFilterBtn" title="Filtros">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
    </button>
    <button class="pac-search-btn" id="pacSearchBtn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </button>
  </div>

  {{-- Panel de filtros --}}
  <div class="pac-filter-overlay" id="pacFilterOverlay"></div>
  <div class="pac-filter-panel" id="pacFilterPanel">
    <div class="pac-filter-head">
      <span class="pac-filter-title">Filtros</span>
      <button class="pac-filter-apply" id="pacFilterApply">Aplicar</button>
    </div>
    <div class="pac-filter-body">
      <div>
        <div class="pac-filter-lbl">Buscar</div>
        <div class="pac-filter-search-wrap">
          <input class="pac-filter-input" id="pfBuscar" type="text" placeholder="Buscar Pacientes">
          <span class="pac-filter-search-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </span>
        </div>
      </div>
      <div>
        <div class="pac-filter-lbl">Numero de seguro social</div>
        <input class="pac-filter-input" id="pfNSS" type="text" placeholder="Buscar Pacientes">
      </div>
      <div>
        <div class="pac-filter-lbl">Numero de Folio</div>
        <input class="pac-filter-input" id="pfFolio" type="text" placeholder="Buscar Pacientes">
      </div>
      <div>
        <div class="pac-filter-lbl">Tipo de estudio</div>
        <select class="pac-filter-select" id="pfEstudio">
          <option value="">Todos los estudios</option>
          <option>Endoscopia</option>
          <option>Colonoscopia</option>
          <option>Ultrasonido</option>
          <option>Radiografía</option>
        </select>
      </div>
      <div>
        <div class="pac-filter-lbl">Medico</div>
        <select class="pac-filter-select" id="pfMedico">
          <option>Ricardo Martinez</option>
          <option>Ana López</option>
          <option>Carlos Ruiz</option>
        </select>
      </div>
    </div>
    <button class="pac-filter-advanced" id="pacFilterAdvanced">Filtros avanzados</button>
    <div class="pac-filter-advanced-body" id="pacAdvBody">
      <div>
        <div class="pac-filter-lbl">Fecha</div>
        <div class="pac-filter-date-tabs">
          <button class="pac-filter-date-tab" data-range="hoy">Hoy</button>
          <button class="pac-filter-date-tab active" data-range="semana">Semana</button>
          <button class="pac-filter-date-tab" data-range="mes">Mes</button>
        </div>
      </div>
      <div>
        <div class="pac-filter-lbl">Sexo</div>
        <select class="pac-filter-select" id="pfSexo">
          <option>Masculino</option>
          <option>Femenino</option>
          <option value="">Todos</option>
        </select>
      </div>
      <div>
        <div class="pac-filter-lbl">Rango de edad</div>
        <div class="pac-filter-range">
          <div class="pac-filter-range-item">
            <span class="pac-filter-range-lbl">Desde</span>
            <input class="pac-filter-range-input" id="pfEdadDesde" type="number" min="0" max="120" value="19">
          </div>
          <div class="pac-filter-range-item">
            <span class="pac-filter-range-lbl">Hasta</span>
            <input class="pac-filter-range-input" id="pfEdadHasta" type="number" min="0" max="120" value="45">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="pac-result" id="pacResult">
    <div class="pac-avatar" id="pacAvatar">MG</div>
    <div class="pac-info">
      <div class="pac-name" id="pacName">María Gómez</div>
      <div class="pac-folio" id="pacFolio">Folio: 00045</div>
      <div class="pac-meta">
        <div class="pac-meta-item">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span id="pacAge">45 años</span>
        </div>
        <div class="pac-meta-item">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="pacGenero">Mujer</span>
        </div>
        <div class="pac-meta-item">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <span id="pacNac">18/05/1998</span>
        </div>
      </div>
    </div>
    <button class="pac-chevron" id="pacChevron">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </button>
  </div>

  <div class="pac-fields" id="pacFields">
    <div class="ag-field">
      <label class="ag-label">Teléfono</label>
      <input class="ag-input" id="pacTel" type="tel" placeholder="55 1234 5678" value="55 1234 5678">
    </div>
    <div class="ag-field">
      <label class="ag-label">Correo</label>
      <input class="ag-input" id="pacEmail" type="email" placeholder="correo@ejemplo.com" value="Sofia.lozanoQ@gmail.com">
    </div>
    <div class="ag-field">
      <label class="ag-label">Dirección</label>
      <input class="ag-input" id="pacDir" type="text" placeholder="Calle, número, ciudad" value="Av. Insurgentes Sur 1234, CDMX">
    </div>
    <button class="pac-link">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
      Ver historial completo
    </button>
  </div>
</div>

<script>
(function(){
  const chevron = document.getElementById('pacChevron');
  const fields  = document.getElementById('pacFields');
  let expanded  = true;

  chevron.addEventListener('click', () => {
    expanded = !expanded;
    fields.style.display = expanded ? '' : 'none';
    chevron.style.transform = expanded ? 'rotate(180deg)' : '';
  });

  /* Filtros */
  const filterBtn     = document.getElementById('pacFilterBtn');
  const filterPanel   = document.getElementById('pacFilterPanel');
  const filterOverlay = document.getElementById('pacFilterOverlay');
  const filterApply   = document.getElementById('pacFilterApply');

  function openFilter()  { filterPanel.classList.add('open'); filterOverlay.classList.add('open'); }
  function closeFilter() { filterPanel.classList.remove('open'); filterOverlay.classList.remove('open'); }

  filterBtn    .addEventListener('click', openFilter);
  filterOverlay.addEventListener('click', closeFilter);
  filterApply  .addEventListener('click', () => {
    const q = document.getElementById('pfBuscar').value.trim();
    if (q) document.getElementById('pacSearch').value = q;
    closeFilter();
  });

  const advBtn  = document.getElementById('pacFilterAdvanced');
  const advBody = document.getElementById('pacAdvBody');
  let advOpen   = false;
  advBtn.addEventListener('click', () => {
    advOpen = !advOpen;
    advBody.classList.toggle('open', advOpen);
    advBtn.textContent = advOpen ? '▲ Ocultar avanzados' : 'Filtros avanzados';
  });

  document.querySelectorAll('.pac-filter-date-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.pac-filter-date-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });
})();
</script>
