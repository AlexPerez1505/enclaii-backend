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
          'nac' => $paciente->fecha_nacimiento ? format_user_date($paciente->fecha_nacimiento) : '',
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
          'nac' => $pacienteSeleccionado->fecha_nacimiento ? format_user_date($pacienteSeleccionado->fecha_nacimiento) : '',
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
html[data-theme="light"] .pac-result,html[data-theme="light"] .pac-suggestions{background:#F0F5FF;border-color:rgba(20,50,120,.15)}
html[data-theme="light"] .pac-name,html[data-theme="light"] .pac-suggestion{color:#0E1530}
html[data-theme="light"] .pac-folio,html[data-theme="light"] .pac-meta-item{color:#5B6A99}
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
    <div class="pac-suggestions" id="pacSuggestions"></div>
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
    if (!e.target.closest('.pac-search-wrap')) {
      closeSuggestions();
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
