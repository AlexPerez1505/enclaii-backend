@extends('layouts.app')

@section('title', 'Nuevo Estudio')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Datos nuevos
@endsection

@push('styles')
<style>
/* ============ NUEVO ESTUDIO - REDISENO ============ */

/* Barra superior con buscador y filtros */
.ne-topbar {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}
.ne-search-wrap {
  position: relative;
  flex: 1;
  max-width: 340px;
}
.ne-search-wrap svg {
  position: absolute;
  left: 13px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--txt-soft);
  pointer-events: none;
}
.ne-search {
  width: 100%;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 10px 14px 10px 38px;
  font: inherit;
  font-size: 13.5px;
  color: var(--txt);
  outline: none;
  transition: border-color 150ms;
}
.ne-search::placeholder { color: var(--off); }
.ne-search:focus { border-color: var(--blue); }

/* Dropdown de filtros */
.ne-filter-wrap { position: relative; }
.ne-filter-btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 18px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font: inherit; font-size: 13.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; transition: background 150ms, border-color 150ms;
}
.ne-filter-btn svg { color: var(--cyan); }
.ne-filter-btn:hover { background: var(--card); border-color: var(--blue); }
.ne-filter-btn.open { border-color: var(--blue); background: rgba(46,123,246,.1); }

.ne-filter-dropdown {
  display: none;
  position: absolute;
  top: calc(100% + 8px);
  left: 0;
  background: var(--panel);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 14px 16px;
  min-width: 240px;
  z-index: 100;
  box-shadow: 0 12px 32px rgba(0,0,0,.45);
}
.ne-filter-dropdown.open { display: block; }
.ne-filter-title {
  font-size: 11px; font-weight: 700; text-transform: uppercase;
  letter-spacing: .07em; color: var(--txt-soft); margin-bottom: 10px;
}
.ne-filter-group { margin-bottom: 12px; }
.ne-filter-group:last-child { margin-bottom: 0; }
.ne-filter-label {
  font-size: 12px; font-weight: 600; color: var(--txt-soft); margin-bottom: 6px;
}
.ne-filter-select {
  width: 100%; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: 8px;
  padding: 8px 12px; font: inherit; font-size: 13px; color: var(--txt);
  outline: none; cursor: pointer;
}
.ne-filter-select:focus { border-color: var(--blue); }
.ne-filter-select option { background: var(--panel); }
.ne-filter-chks { display: flex; flex-direction: column; gap: 7px; }
.ne-filter-chk {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; cursor: pointer; color: var(--txt);
}
.ne-filter-chk input { accent-color: var(--blue); cursor: pointer; }
.ne-filter-actions {
  display: flex; gap: 8px; margin-top: 14px; padding-top: 12px;
  border-top: 1px solid var(--stroke);
}
.ne-filter-apply {
  flex: 1; padding: 8px; border-radius: 8px;
  background: linear-gradient(135deg,#1668D9,var(--blue));
  color: #fff; font: inherit; font-size: 13px; font-weight: 700;
  border: none; cursor: pointer; transition: opacity 150ms;
}
.ne-filter-apply:hover { opacity: .9; }
.ne-filter-clear {
  padding: 8px 14px; border-radius: 8px;
  border: 1px solid var(--stroke-strong); background: transparent;
  font: inherit; font-size: 13px; font-weight: 600;
  color: var(--txt-soft); cursor: pointer; transition: background 150ms;
}
.ne-filter-clear:hover { background: var(--panel-2); }

/* Resultados del buscador */
.ne-results-panel {
  display: none;
  background: var(--panel);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  margin-bottom: 16px;
  overflow: hidden;
  box-shadow: 0 8px 24px rgba(0,0,0,.3);
}
.ne-results-panel.open { display: block; }
.ne-results-head {
  padding: 10px 16px;
  font-size: 11.5px; font-weight: 700; color: var(--txt-soft);
  border-bottom: 1px solid var(--stroke);
  text-transform: uppercase; letter-spacing: .05em;
}
.ne-result-item {
  display: flex; align-items: center; gap: 14px;
  padding: 11px 16px; cursor: pointer;
  border-bottom: 1px solid var(--stroke);
  transition: background 120ms;
}
.ne-result-item:last-child { border-bottom: none; }
.ne-result-item:hover { background: rgba(46,123,246,.07); }
.ne-result-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, var(--blue), var(--cyan));
  display: grid; place-items: center;
  font-weight: 700; font-size: 13px; flex: none;
}
.ne-result-name { font-size: 14px; font-weight: 600; }
.ne-result-meta { font-size: 12px; color: var(--txt-soft); }
.ne-results-empty {
  padding: 20px 16px;
  text-align: center; font-size: 13px; color: var(--txt-soft);
}

/* Boton volver */
.ne-back {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 18px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font: inherit; font-size: 13.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; text-decoration: none; margin-left: auto;
  transition: background 150ms;
}
.ne-back:hover { background: var(--card); }

/* Layout principal */
.ne-layout {
  display: grid;
  grid-template-columns: 1fr 200px;
  gap: 20px;
  align-items: start;
}

/* Card del formulario */
.ne-card {
  background: var(--card);
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  padding: 28px 28px 24px;
}

.ne-sec-title {
  font-size: 18px; font-weight: 700;
  margin-bottom: 22px; color: var(--txt);
}

/* Grids de campos */
.ne-grid { display: grid; gap: 20px; margin-bottom: 20px; }
.ne-grid.c2 { grid-template-columns: 1fr 1fr; }
.ne-grid.c3 { grid-template-columns: 1fr 1fr 1fr; }
.ne-grid.c4 { grid-template-columns: 1fr 1fr 1fr 1fr; }

/* Campo individual */
.ne-field { display: flex; flex-direction: column; gap: 6px; }
.ne-field label {
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .07em;
  color: var(--txt-soft);
}
.ne-field input,
.ne-field select,
.ne-field textarea {
  background: transparent;
  border: 1px solid rgba(110,160,255,.25);
  border-radius: 10px;
  padding: 12px 14px;
  font: inherit; font-size: 14px; color: var(--txt);
  outline: none; width: 100%;
  transition: border-color 150ms, box-shadow 150ms;
}
.ne-field input::placeholder,
.ne-field textarea::placeholder { color: var(--off); }
.ne-field input:focus,
.ne-field select:focus,
.ne-field textarea:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(46,123,246,.15);
}
.ne-field select option { background: var(--panel); }
.ne-field textarea { resize: vertical; min-height: 120px; }

/* Separador de secciones */
.ne-divider {
  border: none;
  border-top: 1px solid var(--stroke);
  margin: 24px 0 20px;
}

/* Panel lateral derecho */
.ne-side {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

/* Foto del paciente */
.ne-foto-box {
  background: var(--card);
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  padding: 20px 14px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}
.ne-foto-circle {
  width: 100px; height: 100px; border-radius: 50%;
  border: 2px dashed rgba(110,160,255,.4);
  display: grid; place-items: center;
  overflow: hidden; cursor: pointer;
  background: var(--panel-2);
  transition: border-color 150ms;
}
.ne-foto-circle:hover { border-color: var(--blue); }
.ne-foto-circle img { width: 100%; height: 100%; object-fit: cover; display: none; }
.ne-foto-ph {
  display: flex; flex-direction: column; align-items: center;
  gap: 5px; color: var(--txt-soft); font-size: 11px; text-align: center;
}
.ne-foto-ph svg { opacity: .6; }
.ne-add-foto {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 12.5px; font-weight: 600; color: var(--cyan);
  background: none; border: none; cursor: pointer;
}

/* Botones de accion */
.ne-action-btns {
  background: var(--card);
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  overflow: hidden;
}
.ne-action-btn {
  display: flex; align-items: center; gap: 12px;
  width: 100%; padding: 13px 14px;
  border: none; border-bottom: 1px solid var(--stroke);
  background: none; font: inherit; font-size: 13px; font-weight: 600;
  color: var(--txt); cursor: pointer; text-align: left;
  text-decoration: none;
  transition: background 150ms;
}
.ne-action-btn:last-child { border-bottom: none; }
.ne-action-btn:hover { background: rgba(110,160,255,.07); }
.ne-ab-icon {
  width: 32px; height: 32px; border-radius: 8px;
  border: 1px solid var(--stroke-strong);
  display: grid; place-items: center; flex: none;
  color: var(--cyan); background: rgba(56,199,244,.08);
}

@media (max-width:1100px) {
  .ne-layout { grid-template-columns: 1fr; }
  .ne-grid.c4 { grid-template-columns: 1fr 1fr; }
}
@media (max-width:640px) {
  .ne-grid.c2,.ne-grid.c3,.ne-grid.c4 { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- Barra superior --}}
<div class="ne-topbar rise d1">

  {{-- Buscador --}}
  <div class="ne-search-wrap">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input class="ne-search" type="text" id="neSearch" placeholder="Buscar paciente por nombre..." autocomplete="off">
  </div>

  {{-- Filtros --}}
  <div class="ne-filter-wrap">
    <button class="ne-filter-btn" type="button" id="neFilterBtn">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      Filtrar
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </button>
    <div class="ne-filter-dropdown" id="neFilterDropdown">
      <div class="ne-filter-title">Filtros de busqueda</div>

      <div class="ne-filter-group">
        <div class="ne-filter-label">Procedimiento</div>
        <select class="ne-filter-select" id="fltProcedimiento">
          <option value="">Todos</option>
          <option value="endoscopia">Endoscopia diagnostica</option>
          <option value="colonoscopia">Colonoscopia</option>
          <option value="gastroscopia">Gastroscopia</option>
          <option value="sigmoidoscopia">Sigmoidoscopia</option>
          <option value="cpre">CPRE</option>
          <option value="ecoendoscopia">Ecoendoscopia</option>
        </select>
      </div>

      <div class="ne-filter-group">
        <div class="ne-filter-label">Sexo</div>
        <div class="ne-filter-chks">
          <label class="ne-filter-chk"><input type="checkbox" id="fltF" checked> Femenino</label>
          <label class="ne-filter-chk"><input type="checkbox" id="fltM" checked> Masculino</label>
        </div>
      </div>

      <div class="ne-filter-group">
        <div class="ne-filter-label">Medico</div>
        <select class="ne-filter-select" id="fltMedico">
          <option value="">Todos</option>
          <option value="dr_victor">Dr. Victor</option>
          <option value="dr_ricardo">Dr. Ricardo</option>
        </select>
      </div>

      <div class="ne-filter-actions">
        <button class="ne-filter-apply" id="neFilterApply">Aplicar</button>
        <button class="ne-filter-clear" id="neFilterClear">Limpiar</button>
      </div>
    </div>
  </div>

  {{-- Volver --}}
  <a class="ne-back" href="{{ route('nuevo-estudio') }}">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Volver
  </a>
</div>

{{-- Panel de resultados de busqueda --}}
<div class="ne-results-panel rise d1" id="neResultsPanel">
  <div class="ne-results-head">Resultados de busqueda</div>
  <div id="neResultsList"></div>
</div>

{{-- Layout principal --}}
<div class="ne-layout">

  {{-- Formulario --}}
  <form class="ne-card rise d2" method="POST" action="#" id="formCrear">
    @csrf

    <h2 class="ne-sec-title">Informacion personal</h2>

    {{-- Nombre + Identificacion + Fecha nac --}}
    <div class="ne-grid c3">
      <div class="ne-field" style="grid-column: span 2">
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" placeholder="Maria Fernanda Lopez Ruiz" autocomplete="off">
      </div>
      <div class="ne-field">
        <label for="identificacion">Identificacion</label>
        <input type="text" id="identificacion" name="identificacion" placeholder="0256987450" autocomplete="off">
      </div>
    </div>

    {{-- Fecha nac + Edad + Peso + Altura --}}
    <div class="ne-grid c4">
      <div class="ne-field">
        <label for="fecha_nac">Fecha de nacimiento</label>
        <input type="date" id="fecha_nac" name="fecha_nac">
      </div>
      <div class="ne-field">
        <label for="edad">Edad</label>
        <input type="text" id="edad" name="edad" placeholder="28 años" autocomplete="off">
      </div>
      <div class="ne-field">
        <label for="peso">Peso</label>
        <input type="text" id="peso" name="peso" placeholder="30 kg" autocomplete="off">
      </div>
      <div class="ne-field">
        <label for="altura">Altura</label>
        <input type="text" id="altura" name="altura" placeholder="1.75 m" autocomplete="off">
      </div>
    </div>

    {{-- Sexo + NSS + Direccion --}}
    <div class="ne-grid c3">
      <div class="ne-field">
        <label for="sexo">Sexo</label>
        <select id="sexo" name="sexo">
          <option value="" disabled selected>Elegir</option>
          <option value="F">Femenino</option>
          <option value="M">Masculino</option>
        </select>
      </div>
      <div class="ne-field">
        <label for="nss">N.S.S</label>
        <input type="text" id="nss" name="nss" placeholder="25849563-9" autocomplete="off">
      </div>
      <div class="ne-field">
        <label for="direccion">Direccion</label>
        <input type="text" id="direccion" name="direccion" placeholder="CALLE, CP" autocomplete="off">
      </div>
    </div>

    {{-- Telefono + Email --}}
    <div class="ne-grid c2" style="margin-bottom:0">
      <div class="ne-field">
        <label for="telefono">Telefono</label>
        <input type="tel" id="telefono" name="telefono" placeholder="722 162 0815" autocomplete="off">
      </div>
      <div class="ne-field">
        <label for="email">E-MAIL</label>
        <input type="email" id="email" name="email" placeholder="@gmail.com" autocomplete="off">
      </div>
    </div>

    <hr class="ne-divider">

    <h2 class="ne-sec-title">Informacion medica</h2>

    {{-- Procedimiento + Fecha y hora --}}
    <div class="ne-grid c2">
      <div class="ne-field">
        <label for="procedimiento">Procedimiento</label>
        <select id="procedimiento" name="procedimiento">
          <option value="" disabled selected>Seleccione</option>
          <option value="endoscopia">Endoscopia diagnostica</option>
          <option value="colonoscopia">Colonoscopia</option>
          <option value="gastroscopia">Gastroscopia</option>
          <option value="sigmoidoscopia">Sigmoidoscopia</option>
          <option value="cpre">CPRE</option>
          <option value="ecoendoscopia">Ecoendoscopia</option>
        </select>
      </div>
      <div class="ne-field">
        <label for="fecha_hora">Fecha y hora</label>
        <input type="datetime-local" id="fecha_hora" name="fecha_hora">
      </div>
    </div>

    {{-- Medico + Referido por --}}
    <div class="ne-grid c2">
      <div class="ne-field">
        <label for="medico">Medico</label>
        <select id="medico" name="medico">
          <option value="" disabled selected>Seleccione</option>
          <option value="dr_victor" selected>Dr. Victor</option>
          <option value="dr_ricardo">Dr. Ricardo</option>
        </select>
      </div>
      <div class="ne-field">
        <label for="referido">Referido por</label>
        <select id="referido" name="referido">
          <option value="" disabled selected>Seleccione</option>
          <option value="externo">Medico externo</option>
          <option value="propio">Medico propio</option>
          <option value="paciente">Paciente directo</option>
        </select>
      </div>
    </div>

    {{-- Diagnostico --}}
    <div class="ne-field" style="margin-bottom:0">
      <label for="diagnostico">Diagnostico Preliminar</label>
      <textarea id="diagnostico" name="diagnostico" placeholder="Define lo que podria tener"></textarea>
    </div>

  </form>

  {{-- Panel lateral --}}
  <div class="ne-side rise d3">

    {{-- Foto --}}
    <div class="ne-foto-box">
      <div class="ne-foto-circle" id="neFotoCircle" onclick="document.getElementById('neFotoInput').click()">
        <img id="neFotoPreview" src="" alt="Foto del paciente">
        <div class="ne-foto-ph" id="neFotoPh">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>Foto del<br>paciente</span>
        </div>
      </div>
      <input type="file" id="neFotoInput" accept="image/*" style="display:none">
      <input type="file" id="neFotoCamera" accept="image/*" capture="environment" style="display:none">

      <div style="position:relative;width:100%">
        <button class="ne-add-foto" type="button" id="neBtnFotoMenu">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <span id="neBtnFotoTxt">Agregar foto</span>
        </button>
        <div id="neFotoMenu" style="display:none;position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:var(--panel);border:1px solid var(--stroke-strong);border-radius:var(--r-md);overflow:hidden;min-width:160px;z-index:50;box-shadow:0 8px 24px rgba(0,0,0,.35)">
          <button type="button" id="neBtnGaleria" style="display:flex;align-items:center;gap:10px;width:100%;padding:11px 14px;background:none;border:none;border-bottom:1px solid var(--stroke);font:inherit;font-size:13px;font-weight:600;color:var(--txt);cursor:pointer;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
            Abrir galeria
          </button>
          <button type="button" id="neBtnCamara" style="display:flex;align-items:center;gap:10px;width:100%;padding:11px 14px;background:none;border:none;font:inherit;font-size:13px;font-weight:600;color:var(--txt);cursor:pointer;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            Tomar foto
          </button>
        </div>
      </div>
    </div>

    {{-- Botones de accion --}}
    <div class="ne-action-btns">
      <a class="ne-action-btn" href="{{ route('nuevo-estudio.grabando') }}">
        <span class="ne-ab-icon" style="background:rgba(255,59,59,.12);border-color:rgba(255,90,110,.4)">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#ff5a6e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="#ff5a6e" stroke="none"/></svg>
        </span>
        Iniciar Grabacion
      </a>
      <a class="ne-action-btn" href="{{ route('nuevo-estudio.capturas') }}">
        <span class="ne-ab-icon">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
        </span>
        Agregar Capturas
      </a>
      <a class="ne-action-btn" href="{{ route('nuevo-estudio.importar') }}">
        <span class="ne-ab-icon">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        </span>
        Importar Imagenes
      </a>
      <a class="ne-action-btn" href="{{ route('nuevo-estudio.configuracion') }}">
        <span class="ne-ab-icon">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
        </span>
        Configuracion de Grabacion
      </a>
    </div>

  </div>

</div>

@endsection

@push('scripts')
<script>
(function () {

  /* Fecha y hora actual */
  var now = new Date();
  var pad = function(n) { return String(n).padStart(2, '0'); };
  var local = now.getFullYear() + '-' + pad(now.getMonth()+1) + '-' + pad(now.getDate()) + 'T' + pad(now.getHours()) + ':' + pad(now.getMinutes());
  document.getElementById('fecha_hora').value = local;
  document.getElementById('fecha_nac').value  = '1998-12-25';

  /* ---- Foto menu ---- */
  var btnFotoMenu = document.getElementById('neBtnFotoMenu');
  var fotoMenu    = document.getElementById('neFotoMenu');
  var btnFotoTxt  = document.getElementById('neBtnFotoTxt');

  btnFotoMenu.addEventListener('click', function(e) {
    e.stopPropagation();
    fotoMenu.style.display = fotoMenu.style.display === 'none' ? 'block' : 'none';
  });
  document.addEventListener('click', function() { fotoMenu.style.display = 'none'; });

  document.getElementById('neBtnGaleria').addEventListener('click', function() {
    fotoMenu.style.display = 'none';
    document.getElementById('neFotoInput').click();
  });
  document.getElementById('neBtnCamara').addEventListener('click', function() {
    fotoMenu.style.display = 'none';
    document.getElementById('neFotoCamera').click();
  });

  function applyPreview(file) {
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
      var img = document.getElementById('neFotoPreview');
      var ph  = document.getElementById('neFotoPh');
      img.src = e.target.result;
      img.style.display = 'block';
      ph.style.display  = 'none';
      btnFotoTxt.textContent = 'Cambiar foto';
    };
    reader.readAsDataURL(file);
  }
  document.getElementById('neFotoInput').addEventListener('change',  function() { applyPreview(this.files[0]); });
  document.getElementById('neFotoCamera').addEventListener('change', function() { applyPreview(this.files[0]); });

  /* ---- Filtros dropdown ---- */
  var filterBtn   = document.getElementById('neFilterBtn');
  var filterDrop  = document.getElementById('neFilterDropdown');

  filterBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    filterDrop.classList.toggle('open');
    filterBtn.classList.toggle('open');
  });
  document.addEventListener('click', function() {
    filterDrop.classList.remove('open');
    filterBtn.classList.remove('open');
  });
  filterDrop.addEventListener('click', function(e) { e.stopPropagation(); });

  document.getElementById('neFilterApply').addEventListener('click', function() {
    filterDrop.classList.remove('open');
    filterBtn.classList.remove('open');
    doSearch();
  });
  document.getElementById('neFilterClear').addEventListener('click', function() {
    document.getElementById('fltProcedimiento').value = '';
    document.getElementById('fltF').checked  = true;
    document.getElementById('fltM').checked  = true;
    document.getElementById('fltMedico').value = '';
    document.getElementById('neSearch').value  = '';
    hideResults();
  });

  /* ---- Pacientes simulados (demo) ---- */
  var PACIENTES = [
    { nombre:'Maria Gonzalez',    id:'025698745', sexo:'F', medico:'dr_victor',  proc:'colonoscopia',  edad:28 },
    { nombre:'Jose Ramirez',      id:'031456789', sexo:'M', medico:'dr_victor',  proc:'endoscopia',    edad:45 },
    { nombre:'Ana Torres',        id:'012345678', sexo:'F', medico:'dr_ricardo', proc:'gastroscopia',  edad:33 },
    { nombre:'Carlos Mendez',     id:'098765432', sexo:'M', medico:'dr_ricardo', proc:'colonoscopia',  edad:52 },
    { nombre:'Laura Perez',       id:'087654321', sexo:'F', medico:'dr_victor',  proc:'ecoendoscopia', edad:41 },
    { nombre:'Roberto Flores',    id:'076543210', sexo:'M', medico:'dr_victor',  proc:'cpre',          edad:60 },
    { nombre:'Sofia Martinez',    id:'065432109', sexo:'F', medico:'dr_ricardo', proc:'sigmoidoscopia',edad:29 },
    { nombre:'Miguel Hernandez',  id:'054321098', sexo:'M', medico:'dr_victor',  proc:'endoscopia',    edad:38 },
  ];

  function doSearch() {
    var q      = document.getElementById('neSearch').value.trim().toLowerCase();
    var proc   = document.getElementById('fltProcedimiento').value;
    var sexoF  = document.getElementById('fltF').checked;
    var sexoM  = document.getElementById('fltM').checked;
    var medico = document.getElementById('fltMedico').value;

    if (!q && !proc && sexoF && sexoM && !medico) { hideResults(); return; }

    var results = PACIENTES.filter(function(p) {
      var matchQ      = !q      || p.nombre.toLowerCase().includes(q) || p.id.includes(q);
      var matchProc   = !proc   || p.proc === proc;
      var matchSexo   = (p.sexo === 'F' && sexoF) || (p.sexo === 'M' && sexoM);
      var matchMedico = !medico || p.medico === medico;
      return matchQ && matchProc && matchSexo && matchMedico;
    });

    showResults(results);
  }

  function showResults(results) {
    var panel = document.getElementById('neResultsPanel');
    var list  = document.getElementById('neResultsList');
    panel.classList.add('open');

    if (results.length === 0) {
      list.innerHTML = '<div class="ne-results-empty">No se encontraron pacientes</div>';
      return;
    }

    list.innerHTML = results.map(function(p) {
      var initials = p.nombre.split(' ').slice(0,2).map(function(w){ return w[0]; }).join('');
      return '<div class="ne-result-item" data-nombre="' + p.nombre + '" data-id="' + p.id + '" data-sexo="' + p.sexo + '">' +
        '<div class="ne-result-avatar">' + initials + '</div>' +
        '<div>' +
          '<div class="ne-result-name">' + p.nombre + '</div>' +
          '<div class="ne-result-meta">ID: ' + p.id + ' &middot; ' + p.edad + ' anos &middot; ' + p.proc.charAt(0).toUpperCase() + p.proc.slice(1) + '</div>' +
        '</div>' +
        '</div>';
    }).join('');

    list.querySelectorAll('.ne-result-item').forEach(function(el) {
      el.addEventListener('click', function() {
        document.getElementById('nombre').value       = el.dataset.nombre;
        document.getElementById('identificacion').value = el.dataset.id;
        document.getElementById('sexo').value         = el.dataset.sexo;
        hideResults();
        document.getElementById('neSearch').value = el.dataset.nombre;
      });
    });
  }

  function hideResults() {
    document.getElementById('neResultsPanel').classList.remove('open');
  }

  document.getElementById('neSearch').addEventListener('input', doSearch);

})();
</script>
@endpush