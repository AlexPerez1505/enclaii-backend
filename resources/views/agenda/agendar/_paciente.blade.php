{{-- ============================================================
     AGENDAR / _paciente.blade.php
     Paso 1: Datos del paciente
     Consulta pacientes reales desde la tabla `pacientes`
     ============================================================ --}}

@php
  $pacientesAgendarJs = ($pacientes ?? collect())->values()->map(function ($paciente) {
      $nombre = trim($paciente->nombre_completo ?? 'Paciente sin nombre');
      $partes = preg_split('/\s+/', $nombre);
      $iniciales = '';

      if (count($partes) >= 2) {
          $iniciales = mb_substr($partes[0], 0, 1) . mb_substr($partes[1], 0, 1);
      } else {
          $iniciales = mb_substr($nombre, 0, 2);
      }

      return [
          'id' => $paciente->id,
          'nombre' => $nombre,
          'folio' => $paciente->folio ?? '',
          'edad' => $paciente->edad ?? '',
          'genero' => $paciente->sexo ? ucfirst($paciente->sexo) : 'No especificado',
          'nac' => $paciente->fecha_nacimiento ? $paciente->fecha_nacimiento->format('d/m/Y') : '',
          'tel' => $paciente->telefono ?? '',
          'email' => $paciente->email ?? '',
          'dir' => $paciente->direccion ?? '',
          'foto_url' => $paciente->foto ? asset('storage/' . $paciente->foto) : null,
          'iniciales' => mb_strtoupper($iniciales),
      ];
  });
@endphp

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
.pac-suggestions{position:absolute;top:calc(100% + 6px);left:0;right:0;background:#001A30;border:1.5px solid var(--ag-stroke);border-radius:10px;max-height:220px;overflow-y:auto;z-index:1100;display:none;box-shadow:0 12px 40px rgba(0,0,0,.5)}
.pac-suggestions.open{display:block}
.pac-suggestion{padding:10px 14px;cursor:pointer;font-size:13px;color:var(--ag-txt);border-bottom:1px solid rgba(255,255,255,.06)}
.pac-suggestion:last-child{border-bottom:none}
.pac-suggestion:hover,.pac-suggestion.active{background:rgba(22,139,217,.18)}
.pac-suggestion .sug-folio{font-size:11px;color:var(--ag-soft);margin-left:6px}
.pac-filter-btn{position:absolute;right:40px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;display:grid;place-items:center;padding:4px;color:var(--ag-blue)}
.pac-filter-btn:hover{opacity:.8}
.pac-filter-panel{position:fixed;top:0;right:-340px;width:320px;max-width:95vw;height:100%;z-index:1200;background:#0A1628;border-left:1.5px solid rgba(22,139,217,.3);box-shadow:-12px 0 40px rgba(0,0,0,.6);display:flex;flex-direction:column;transition:right 250ms cubic-bezier(.4,0,.2,1);overflow-y:auto}
.pac-filter-panel.open{right:0}
.pac-filter-overlay{position:fixed;inset:0;z-index:1199;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);opacity:0;pointer-events:none;transition:opacity 200ms ease}
.pac-filter-overlay.open{opacity:1;pointer-events:all}
.pac-filter-head{display:flex;align-items:center;justify-content:space-between;padding:20px 20px 14px;border-bottom:1px solid rgba(255,255,255,.07)}
.pac-filter-title{font-family:'Sora',sans-serif;font-size:18px;font-weight:700;color:#fff}
.pac-filter-apply{padding:8px 18px;border-radius:9px;border:1.5px solid rgba(22,139,217,.5);background:transparent;color:#EAF1FF;font-family:'Sora',sans-serif;font-size:13px;font-weight:700;cursor:pointer;transition:background 150ms}
.pac-filter-apply:hover{background:rgba(22,139,217,.15)}
.pac-filter-body{padding:18px 20px;display:flex;flex-direction:column;gap:14px;flex:1}
.pac-filter-lbl{font-size:12px;font-weight:700;color:rgba(255,255,255,.6);margin-bottom:5px}
.pac-filter-input,.pac-filter-select{width:100%;padding:10px 14px;border-radius:10px;border:1.5px solid rgba(22,139,217,.3);background:rgba(255,255,255,.04);color:#EAF1FF;font-family:inherit;font-size:13px;box-sizing:border-box}
.pac-filter-input::placeholder{color:rgba(234,241,255,.3)}
.pac-filter-input:focus,.pac-filter-select:focus{outline:none;border-color:rgba(22,139,217,.7)}
.pac-filter-search-wrap{position:relative}
.pac-filter-search-icon{position:absolute;right:10px;top:50%;transform:translateY(-50%);color:rgba(234,241,255,.3);pointer-events:none}
.pac-filter-advanced{margin:0 20px 8px;padding:11px;border-radius:10px;border:1.5px solid rgba(22,139,217,.5);background:rgba(22,139,217,.12);color:#EAF1FF;font-family:inherit;font-size:13px;font-weight:700;cursor:pointer;transition:background 150ms}
.pac-filter-advanced:hover{background:rgba(22,139,217,.2)}
.pac-filter-advanced-body{display:none;padding:0 20px 20px;flex-direction:column;gap:14px}
.pac-filter-advanced-body.open{display:flex}
.pac-filter-range{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.pac-filter-range-item{display:flex;flex-direction:column;gap:4px}
.pac-filter-range-lbl{font-size:11px;color:rgba(255,255,255,.4)}
.pac-filter-range-input{width:100%;padding:9px 12px;border-radius:9px;border:1.5px solid rgba(22,139,217,.3);background:rgba(255,255,255,.04);color:#EAF1FF;font-family:inherit;font-size:13px;font-weight:700;box-sizing:border-box;text-align:center}
.pac-filter-range-input:focus{outline:none;border-color:rgba(22,139,217,.7)}
html[data-theme="light"] .pac-result,html[data-theme="light"] .pac-suggestions{background:#F0F5FF;border-color:rgba(20,50,120,.15)}
html[data-theme="light"] .pac-name,html[data-theme="light"] .pac-suggestion{color:#0E1530}
html[data-theme="light"] .pac-folio,html[data-theme="light"] .pac-meta-item{color:#5B6A99}
html[data-theme="light"] .pac-filter-panel{background:#F0F5FF;border-left-color:rgba(20,50,120,.2);box-shadow:-12px 0 40px rgba(20,50,120,.12)}
html[data-theme="light"] .pac-filter-title{color:#0E1530}
html[data-theme="light"] .pac-filter-apply{border-color:rgba(20,50,120,.35);color:#0E1530}
html[data-theme="light"] .pac-filter-input,html[data-theme="light"] .pac-filter-select,html[data-theme="light"] .pac-filter-range-input{background:rgba(20,50,120,.04);border-color:rgba(20,50,120,.2);color:#0E1530}
html[data-theme="light"] .pac-filter-lbl{color:rgba(14,21,48,.55)}
</style>

<div class="ag-card" id="stepPaciente">
  <div class="ag-card-title">
    <span class="ag-step-badge">1</span>
    Datos Del Paciente
  </div>

  <label class="ag-label">Buscar paciente</label>
  <div class="pac-search-wrap">
    <input class="ag-input" id="pacSearch" type="text" placeholder="Busca por nombre, folio, teléfono o correo" style="padding-right:72px">
    <button class="pac-filter-btn" id="pacFilterBtn" title="Filtros" type="button">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
    </button>
    <button class="pac-search-btn" id="pacSearchBtn" type="button">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </button>
    <div class="pac-suggestions" id="pacSuggestions"></div>
  </div>

  <div class="pac-filter-overlay" id="pacFilterOverlay"></div>
  <div class="pac-filter-panel" id="pacFilterPanel">
    <div class="pac-filter-head">
      <span class="pac-filter-title">Filtros</span>
      <button class="pac-filter-apply" id="pacFilterApply" type="button">Aplicar</button>
    </div>
    <div class="pac-filter-body">
      <div>
        <div class="pac-filter-lbl">Buscar</div>
        <div class="pac-filter-search-wrap">
          <input class="pac-filter-input" id="pfBuscar" type="text" placeholder="Buscar pacientes">
          <span class="pac-filter-search-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </span>
        </div>
      </div>
      <div>
        <div class="pac-filter-lbl">Número de folio</div>
        <input class="pac-filter-input" id="pfFolio" type="text" placeholder="Buscar por folio">
      </div>
      <div>
        <div class="pac-filter-lbl">Sexo</div>
        <select class="pac-filter-select" id="pfSexo">
          <option value="">Todos</option>
          <option>Masculino</option>
          <option>Femenino</option>
          <option>Otro</option>
        </select>
      </div>
    </div>

    <button class="pac-filter-advanced" id="pacFilterAdvanced" type="button">Filtros avanzados</button>
    <div class="pac-filter-advanced-body" id="pacAdvBody">
      <div>
        <div class="pac-filter-lbl">Rango de edad</div>
        <div class="pac-filter-range">
          <div class="pac-filter-range-item">
            <span class="pac-filter-range-lbl">Desde</span>
            <input class="pac-filter-range-input" id="pfEdadDesde" type="number" min="0" max="120">
          </div>
          <div class="pac-filter-range-item">
            <span class="pac-filter-range-lbl">Hasta</span>
            <input class="pac-filter-range-input" id="pfEdadHasta" type="number" min="0" max="120">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="pac-result" id="pacResult">
    <div class="pac-avatar" id="pacAvatar">PX</div>
    <div class="pac-info">
      <div class="pac-name" id="pacName">Selecciona un paciente</div>
      <div class="pac-folio" id="pacFolio">Folio: --</div>
      <div class="pac-meta">
        <div class="pac-meta-item">
          <span id="pacAge">Sin edad</span>
        </div>
        <div class="pac-meta-item">
          <span id="pacGenero">No especificado</span>
        </div>
        <div class="pac-meta-item">
          <span id="pacNac">Sin fecha</span>
        </div>
      </div>
    </div>
    <button class="pac-chevron" id="pacChevron" type="button">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
    </button>
  </div>

  <div class="pac-fields" id="pacFields">
    <div class="ag-field">
      <label class="ag-label">Teléfono</label>
      <input class="ag-input" id="pacTel" type="tel" placeholder="Teléfono">
    </div>
    <div class="ag-field">
      <label class="ag-label">Correo</label>
      <input class="ag-input" id="pacEmail" type="email" placeholder="correo@ejemplo.com">
    </div>
    <div class="ag-field">
      <label class="ag-label">Dirección</label>
      <input class="ag-input" id="pacDir" type="text" placeholder="Dirección">
    </div>
    <button class="pac-link" type="button">
      Ver historial completo
    </button>
  </div>
</div>

<script>
(function(){
  const chevron = document.getElementById('pacChevron');
  const fields = document.getElementById('pacFields');
  let expanded = true;

  if (chevron && fields) {
    chevron.addEventListener('click', () => {
      expanded = !expanded;
      fields.style.display = expanded ? '' : 'none';
      chevron.style.transform = expanded ? 'rotate(180deg)' : '';
    });
  }

  const filterBtn = document.getElementById('pacFilterBtn');
  const filterPanel = document.getElementById('pacFilterPanel');
  const filterOverlay = document.getElementById('pacFilterOverlay');
  const filterApply = document.getElementById('pacFilterApply');
  const advBtn = document.getElementById('pacFilterAdvanced');
  const advBody = document.getElementById('pacAdvBody');

  function openFilter() {
    filterPanel.classList.add('open');
    filterOverlay.classList.add('open');
  }

  function closeFilter() {
    filterPanel.classList.remove('open');
    filterOverlay.classList.remove('open');
  }

  if (filterBtn) filterBtn.addEventListener('click', openFilter);
  if (filterOverlay) filterOverlay.addEventListener('click', closeFilter);

  if (filterApply) {
    filterApply.addEventListener('click', () => {
      const q = document.getElementById('pfBuscar').value.trim();
      const folio = document.getElementById('pfFolio').value.trim();

      if (q) document.getElementById('pacSearch').value = q;
      else if (folio) document.getElementById('pacSearch').value = folio;

      document.getElementById('pacSearch').dispatchEvent(new Event('input'));
      closeFilter();
    });
  }

  let advOpen = false;

  if (advBtn && advBody) {
    advBtn.addEventListener('click', () => {
      advOpen = !advOpen;
      advBody.classList.toggle('open', advOpen);
      advBtn.textContent = advOpen ? '▲ Ocultar avanzados' : 'Filtros avanzados';
    });
  }

  const PACIENTES = @json($pacientesAgendarJs);

  const searchInput = document.getElementById('pacSearch');
  const suggestions = document.getElementById('pacSuggestions');
  let sugIndex = -1;

  function normalize(value) {
    return String(value || '')
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '');
  }

  function findPatients(query) {
    if (!query || query.trim().length < 1) return [];

    const q = normalize(query.trim());

    return PACIENTES.filter(p => {
      const nombre = normalize(p.nombre);
      const folio = normalize(p.folio);
      const tel = normalize(p.tel);
      const email = normalize(p.email);

      return nombre.includes(q)
        || folio.includes(q)
        || tel.includes(q)
        || email.includes(q);
    });
  }

  function findPatient(query) {
    const list = findPatients(query);

    return list[0] || null;
  }

  function updatePacResult(pac) {
    if (!pac) return;

    window.__selectedPacienteId = pac.id || null;

    const avatar = document.getElementById('pacAvatar');
    const inits = pac.iniciales || String(pac.nombre || 'PX').split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();

    if (avatar) {
      if (pac.foto_url) {
        avatar.innerHTML = `<img src="${pac.foto_url}" alt="${pac.nombre}">`;
      } else {
        avatar.textContent = inits;
      }
    }

    document.getElementById('pacName').textContent = pac.nombre || 'Paciente';
    document.getElementById('pacFolio').textContent = 'Folio: ' + (pac.folio || 'Sin folio');
    document.getElementById('pacAge').textContent = pac.edad ? pac.edad + ' años' : 'Sin edad';
    document.getElementById('pacGenero').textContent = pac.genero || 'No especificado';
    document.getElementById('pacNac').textContent = pac.nac || 'Sin fecha';

    const telEl = document.getElementById('pacTel');
    const emailEl = document.getElementById('pacEmail');
    const dirEl = document.getElementById('pacDir');

    if (telEl) telEl.value = pac.tel || '';
    if (emailEl) emailEl.value = pac.email || '';
    if (dirEl) dirEl.value = pac.dir || '';
  }

  function renderSuggestions(list) {
    suggestions.innerHTML = '';
    sugIndex = -1;

    if (!list.length) {
      suggestions.classList.remove('open');
      return;
    }

    list.forEach((p, i) => {
      const div = document.createElement('div');
      div.className = 'pac-suggestion';
      div.dataset.index = i;
      div.innerHTML = `<span>${p.nombre}</span><span class="sug-folio">Folio ${p.folio || 'S/F'}</span>`;
      div.addEventListener('click', () => selectPatient(p));
      suggestions.appendChild(div);
    });

    suggestions.classList.add('open');
  }

  function closeSuggestions() {
    suggestions.classList.remove('open');
    sugIndex = -1;
  }

  function selectPatient(pac) {
    updatePacResult(pac);
    searchInput.value = pac.nombre;
    closeSuggestions();

    const cfmPaciente = document.getElementById('cfmPaciente');
    if (cfmPaciente) cfmPaciente.textContent = pac.nombre;

    try {
      localStorage.setItem('lastPatient', JSON.stringify(pac));
    } catch(e) {}
  }

  window.__updatePacResult = updatePacResult;
  window.__findPatient = findPatient;
  window.__selectPatient = selectPatient;

  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const list = findPatients(this.value);
      renderSuggestions(list);
    });

    searchInput.addEventListener('keydown', function(e) {
      const items = suggestions.querySelectorAll('.pac-suggestion');

      if (!items.length) return;

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        sugIndex = Math.min(sugIndex + 1, items.length - 1);
        items.forEach((it, i) => it.classList.toggle('active', i === sugIndex));
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        sugIndex = Math.max(sugIndex - 1, 0);
        items.forEach((it, i) => it.classList.toggle('active', i === sugIndex));
      } else if (e.key === 'Enter') {
        e.preventDefault();

        if (sugIndex >= 0 && items[sugIndex]) {
          items[sugIndex].click();
        } else if (items.length === 1) {
          items[0].click();
        }
      } else if (e.key === 'Escape') {
        closeSuggestions();
      }
    });
  }

  document.addEventListener('click', e => {
    if (!e.target.closest('.pac-search-wrap')) closeSuggestions();
  });

  const searchBtn = document.getElementById('pacSearchBtn');

  if (searchBtn) {
    searchBtn.addEventListener('click', () => {
      const list = findPatients(searchInput.value);

      if (list.length === 1) selectPatient(list[0]);
      else renderSuggestions(list);
    });
  }

  if (PACIENTES.length > 0) {
    selectPatient(PACIENTES[0]);
  }
})();
</script>
