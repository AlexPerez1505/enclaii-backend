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
          'medico' => $paciente->medico ?? '',
          'procedimiento' => $paciente->procedimiento ?? '',
      ];
  });

  $pacienteSeleccionadoJson = null;
  if ($pacienteSeleccionado) {
      $nombre = trim($pacienteSeleccionado->nombre_completo ?? 'Paciente sin nombre');
      $partes = preg_split('/\s+/', $nombre);
      $iniciales = '';
      if (count($partes) >= 2) {
          $iniciales = mb_strtoupper(mb_substr($partes[0], 0, 1) . mb_substr($partes[1], 0, 1));
      } else {
          $iniciales = mb_strtoupper(mb_substr($nombre, 0, 2));
      }

      $pacienteSeleccionadoJson = [
          'id' => $pacienteSeleccionado->id,
          'nombre' => $nombre,
          'folio' => $pacienteSeleccionado->folio ?? '',
          'edad' => $pacienteSeleccionado->edad ?? '',
          'genero' => $pacienteSeleccionado->sexo ? ucfirst($pacienteSeleccionado->sexo) : 'No especificado',
          'nac' => $pacienteSeleccionado->fecha_nacimiento ? $pacienteSeleccionado->fecha_nacimiento->format('d/m/Y') : '',
          'tel' => $pacienteSeleccionado->telefono ?? '',
          'email' => $pacienteSeleccionado->email ?? '',
          'dir' => $pacienteSeleccionado->direccion ?? '',
          'foto_url' => $pacienteSeleccionado->foto ? asset('storage/' . $pacienteSeleccionado->foto) : null,
          'iniciales' => $iniciales,
          'medico' => $pacienteSeleccionado->medico ?? '',
          'procedimiento' => $pacienteSeleccionado->procedimiento ?? '',
      ];
  }
@endphp

<style>
.pac-search-wrap{position:relative;margin-bottom:14px}
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
.pac-filter-results-panel{position:fixed;top:0;right:320px;width:300px;max-width:calc(95vw - 320px);height:100%;z-index:1210;background:#001A30;border-left:1.5px solid rgba(22,139,217,.3);border-right:1.5px solid var(--ag-stroke);box-shadow:-12px 0 40px rgba(0,0,0,.5);display:none;flex-direction:column;overflow:hidden}
.pac-filter-results-panel.open{display:flex}
.pac-filter-results-head{font-family:'Sora',sans-serif;font-size:18px;font-weight:700;color:#fff;padding:20px 20px 14px;border-bottom:1px solid rgba(255,255,255,.07);flex:none}
.pac-filter-results-body{flex:1;overflow-y:auto;padding:8px 0}
.pac-filter-result-item{display:flex;align-items:center;gap:12px;padding:10px 20px;cursor:pointer;border-bottom:1px solid rgba(255,255,255,.06);transition:background 150ms}
.pac-filter-result-item:last-child{border-bottom:none}
.pac-filter-result-item:hover,.pac-filter-result-item.active{background:rgba(22,139,217,.18)}
.pac-filter-result-item .res-avatar{width:36px;height:36px;border-radius:50%;flex:none;background:linear-gradient(135deg,#1668D9,#00C8C8);display:grid;place-items:center;font-family:'Sora',sans-serif;font-size:12px;font-weight:700;color:#fff;border:2px solid rgba(22,139,217,.4);overflow:hidden}
.pac-filter-result-item .res-avatar img{width:100%;height:100%;object-fit:cover}
.pac-filter-result-item .res-info{flex:1;min-width:0}
.pac-filter-result-item .res-name{font-size:13px;font-weight:600;color:var(--ag-txt);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pac-filter-result-item .res-meta{font-size:11px;color:var(--ag-soft);margin-top:2px}
.pac-filter-result-empty{padding:18px;font-size:12px;color:var(--ag-soft);text-align:center}
html[data-theme="light"] .pac-result,html[data-theme="light"] .pac-suggestions{background:#F0F5FF;border-color:rgba(20,50,120,.15)}
html[data-theme="light"] .pac-name,html[data-theme="light"] .pac-suggestion{color:#0E1530}
html[data-theme="light"] .pac-folio,html[data-theme="light"] .pac-meta-item{color:#5B6A99}
html[data-theme="light"] .pac-filter-panel{background:#F0F5FF;border-left-color:rgba(20,50,120,.2);box-shadow:-12px 0 40px rgba(20,50,120,.12)}
html[data-theme="light"] .pac-filter-title{color:#0E1530}
html[data-theme="light"] .pac-filter-input,html[data-theme="light"] .pac-filter-select,html[data-theme="light"] .pac-filter-range-input{background:rgba(20,50,120,.04);border-color:rgba(20,50,120,.2);color:#0E1530}
html[data-theme="light"] .pac-filter-lbl{color:rgba(14,21,48,.55)}
html[data-theme="light"] .pac-filter-results-panel{background:#F0F5FF;border-left-color:rgba(20,50,120,.2);border-right-color:rgba(20,50,120,.15);box-shadow:-12px 0 40px rgba(20,50,120,.12)}
html[data-theme="light"] .pac-filter-results-head{color:#0E1530;border-bottom-color:rgba(20,50,120,.1)}
html[data-theme="light"] .pac-filter-result-item{border-bottom-color:rgba(20,50,120,.08)}
html[data-theme="light"] .pac-filter-result-item:hover,.html[data-theme="light"] .pac-filter-result-item.active{background:rgba(22,104,217,.12)}
html[data-theme="light"] .pac-filter-result-item .res-name{color:#0E1530}
html[data-theme="light"] .pac-filter-result-item .res-meta{color:#5B6A99}
html[data-theme="light"] .pac-filter-result-empty{color:#5B6A99}
#pacTel[readonly],#pacEmail[readonly],#pacDir[readonly]{cursor:default;opacity:.8}
</style>

<div class="ag-card" id="stepPaciente">
  <div class="ag-card-title">
    <span class="ag-step-badge">1</span>
    Datos Del Paciente
  </div>

  <label class="ag-label">Buscar paciente</label>
  <div class="pac-search-wrap">
    <input class="ag-input" id="pacSearch" type="text" placeholder="Busca por nombre, folio, teléfono o correo" style="padding-right:40px">
    <button class="pac-filter-btn" id="pacFilterBtn" title="Filtros" type="button" style="right:10px">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
    </button>
    <div class="pac-suggestions" id="pacSuggestions"></div>
  </div>

  <div class="pac-filter-overlay" id="pacFilterOverlay"></div>
  <div class="pac-filter-panel" id="pacFilterPanel">
    <div class="pac-filter-head">
      <span class="pac-filter-title">Filtros</span>
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

  <div class="pac-filter-results-panel" id="pacFilterResultsPanel">
    <div class="pac-filter-results-head">Resultados</div>
    <div class="pac-filter-results-body" id="pacFilterResultsBody"></div>
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
      <input class="ag-input" id="pacTel" type="tel" placeholder="Teléfono" readonly>
    </div>
    <div class="ag-field">
      <label class="ag-label">Correo</label>
      <input class="ag-input" id="pacEmail" type="email" placeholder="correo@ejemplo.com" readonly>
    </div>
    <div class="ag-field">
      <label class="ag-label">Dirección</label>
      <input class="ag-input" id="pacDir" type="text" placeholder="Dirección" readonly>
    </div>
    <a class="pac-link" id="pacHistoryLink" href="{{ route('pacientes.index') }}">Ver historial completo</a>
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
  const advBtn = document.getElementById('pacFilterAdvanced');
  const advBody = document.getElementById('pacAdvBody');

  function openFilter() {
    filterPanel.classList.add('open');
    filterOverlay.classList.add('open');
    closeSuggestions();
    renderFilterResults(filterPatients());
  }

  function closeFilter() {
    filterPanel.classList.remove('open');
    filterOverlay.classList.remove('open');
    closeFilterResultsPanel();
  }

  if (filterBtn) filterBtn.addEventListener('click', openFilter);
  if (filterOverlay) filterOverlay.addEventListener('click', closeFilter);

  const filterResultsPanel = document.getElementById('pacFilterResultsPanel');
  const filterResultsBody = document.getElementById('pacFilterResultsBody');

  function filterPatients() {
    const q = normalize(document.getElementById('pfBuscar')?.value || '');
    const folio = normalize(document.getElementById('pfFolio')?.value || '');
    const sexo = normalize(document.getElementById('pfSexo')?.value || '');
    const edadDesde = parseInt(document.getElementById('pfEdadDesde')?.value || '', 10);
    const edadHasta = parseInt(document.getElementById('pfEdadHasta')?.value || '', 10);

    return PACIENTES.filter(p => {
      const matchQ = !q || normalize(p.nombre).includes(q)
        || normalize(p.folio).includes(q)
        || normalize(p.tel).includes(q)
        || normalize(p.email).includes(q);
      const matchFolio = !folio || normalize(p.folio).includes(folio);
      const matchSexo = !sexo || normalize(p.genero) === sexo;
      const edad = parseInt(p.edad, 10);
      const matchEdad = (!Number.isFinite(edadDesde) || !Number.isFinite(edad) || edad >= edadDesde)
        && (!Number.isFinite(edadHasta) || !Number.isFinite(edad) || edad <= edadHasta);
      return matchQ && matchFolio && matchSexo && matchEdad;
    });
  }

  function closeFilterResultsPanel() {
    if (filterResultsPanel) filterResultsPanel.classList.remove('open');
  }

  function renderFilterResults(list) {
    if (!filterResultsPanel || !filterResultsBody) return;
    filterResultsBody.innerHTML = '';
    if (!list.length) {
      filterResultsBody.innerHTML = '<div class="pac-filter-result-empty">No se encontraron pacientes.</div>';
      filterResultsPanel.classList.add('open');
      return;
    }
    list.forEach(p => {
      const initials = p.iniciales || String(p.nombre || 'PX').split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
      const avatar = p.foto_url
        ? `<img src="${p.foto_url}" alt="${p.nombre}">`
        : initials;
      const div = document.createElement('div');
      div.className = 'pac-filter-result-item';
      div.innerHTML = `
        <div class="res-avatar">${avatar}</div>
        <div class="res-info">
          <div class="res-name">${p.nombre}</div>
          <div class="res-meta">Folio ${p.folio || 'S/F'} · ${p.genero || 'Sin sexo'} · ${p.edad || 'Sin edad'} años</div>
        </div>`;
      div.addEventListener('click', () => {
        selectPatient(p);
        closeFilter();
        closeFilterResultsPanel();
      });
      filterResultsBody.appendChild(div);
    });
    filterResultsPanel.classList.add('open');
  }

  ['pfBuscar', 'pfFolio', 'pfEdadDesde', 'pfEdadHasta'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', () => {
      renderFilterResults(filterPatients());
    });
  });
  document.getElementById('pfSexo')?.addEventListener('change', () => {
    renderFilterResults(filterPatients());
  });

  let advOpen = false;

  if (advBtn && advBody) {
    advBtn.addEventListener('click', () => {
      advOpen = !advOpen;
      advBody.classList.toggle('open', advOpen);
      advBtn.textContent = advOpen ? '▲ Ocultar avanzados' : 'Filtros avanzados';
    });
  }

  const PACIENTES = @json($pacientesAgendarJs);
  const PACIENTE_SELECCIONADO = @json($pacienteSeleccionadoJson);

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

  function updateCitaFromPatient(pac) {
    const medico = String(pac?.medico ?? '').trim();
    const procedimiento = String(pac?.procedimiento ?? '').trim();

    const selEsp = document.getElementById('citaEspecialista');
    const selProc = document.getElementById('citaProcedimiento');

    function fillSelect(select, rawValue, fallbackLabel) {
      if (!select) return;
      select.innerHTML = '';
      const values = rawValue
        ? rawValue.split(/[,;]+/).map(v => v.trim()).filter(Boolean)
        : [];
      if (values.length === 0) {
        const opt = document.createElement('option');
        opt.textContent = fallbackLabel;
        opt.value = '';
        select.appendChild(opt);
      } else {
        values.forEach((val, i) => {
          const opt = document.createElement('option');
          opt.value = val;
          opt.textContent = val;
          if (i === 0) opt.selected = true;
          select.appendChild(opt);
        });
      }
      select.dispatchEvent(new Event('change'));
    }

    fillSelect(selEsp, medico, 'Sin médico asignado');
    fillSelect(selProc, procedimiento, 'Sin procedimiento asignado');
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

    const link = document.getElementById('pacHistoryLink');
    if (link && pac.id) {
      link.href = `{{ route('pacientes.index') }}?paciente_id=${pac.id}`;
    }

    updateCitaFromPatient(pac);
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
    closeFilterResultsPanel();

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
      closeFilterResultsPanel();
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
    if (!e.target.closest('.pac-search-wrap')) {
      closeSuggestions();
      closeFilterResultsPanel();
    }
  });

  if (PACIENTES.length > 0) {
    document.addEventListener('DOMContentLoaded', () => {
      if (PACIENTE_SELECCIONADO) {
        const fromList = PACIENTES.find(p => p.id === PACIENTE_SELECCIONADO.id);
        selectPatient(fromList || PACIENTE_SELECCIONADO);
      } else {
        selectPatient(PACIENTES[0]);
      }
    });
  }
})();
</script>
