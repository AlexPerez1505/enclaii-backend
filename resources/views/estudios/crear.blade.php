@extends('layouts.app')

@section('title', 'Nuevo Estudio')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Datos nuevos
@endsection

@push('styles')
<style>
/* ============ NUEVO PACIENTE ============ */

.np-back-link {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 13.5px; font-weight: 600; color: var(--blue);
  margin-bottom: 20px; cursor: pointer; text-decoration: none;
  transition: color 150ms;
}
.np-back-link:hover { color: var(--cyan); }
.np-back-link svg { flex: none; }

/* Barra buscador + filtros */
.np-searchbar {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 18px; flex-wrap: wrap;
}
.np-search-wrap {
  position: relative; flex: 1; max-width: 360px;
}
.np-search-wrap svg {
  position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
  color: var(--txt-soft); pointer-events: none;
}
.np-search {
  width: 100%; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  padding: 10px 14px 10px 36px; font: inherit; font-size: 13.5px;
  color: var(--txt); outline: none; transition: border-color 150ms;
}
.np-search::placeholder { color: var(--off); }
.np-search:focus { border-color: var(--blue); }

/* Filtrar dropdown */
.np-filter-wrap { position: relative; }
.np-filter-btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 16px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font: inherit; font-size: 13.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; transition: background 150ms, border-color 150ms;
}
.np-filter-btn svg { color: var(--cyan); }
.np-filter-btn:hover, .np-filter-btn.open { background: var(--card); border-color: var(--blue); }

.np-filter-drop {
  display: none; position: absolute; top: calc(100% + 8px); left: 0;
  background: var(--panel); border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md); padding: 14px 16px; min-width: 240px;
  z-index: 200; box-shadow: 0 12px 32px rgba(0,0,0,.45);
}
.np-filter-drop.open { display: block; }
.np-flt-title {
  font-size: 10.5px; font-weight: 700; text-transform: uppercase;
  letter-spacing: .07em; color: var(--txt-soft); margin-bottom: 12px;
}
.np-flt-group { margin-bottom: 12px; }
.np-flt-group:last-of-type { margin-bottom: 0; }
.np-flt-lbl { font-size: 12px; font-weight: 600; color: var(--txt-soft); margin-bottom: 6px; }
.np-flt-sel {
  width: 100%; background: var(--panel-2); border: 1px solid var(--stroke-strong);
  border-radius: 8px; padding: 8px 10px; font: inherit; font-size: 13px;
  color: var(--txt); outline: none; cursor: pointer;
}
.np-flt-sel:focus { border-color: var(--blue); }
.np-flt-sel option { background: var(--panel); }
.np-flt-chks { display: flex; flex-direction: column; gap: 7px; }
.np-flt-chk { display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer; color: var(--txt); }
.np-flt-chk input { accent-color: var(--blue); cursor: pointer; }
.np-flt-actions { display: flex; gap: 8px; margin-top: 12px; padding-top: 10px; border-top: 1px solid var(--stroke); }
.np-flt-apply {
  flex: 1; padding: 8px; border-radius: 8px;
  background: linear-gradient(135deg,#1668D9,var(--blue)); color: #fff;
  font: inherit; font-size: 13px; font-weight: 700; border: none; cursor: pointer;
}
.np-flt-apply:hover { opacity: .9; }
.np-flt-clear {
  padding: 8px 12px; border-radius: 8px; border: 1px solid var(--stroke-strong);
  background: transparent; font: inherit; font-size: 13px; font-weight: 600;
  color: var(--txt-soft); cursor: pointer; transition: background 150ms;
}
.np-flt-clear:hover { background: var(--panel-2); }

/* Panel resultados */
.np-results {
  display: none; background: var(--panel);
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  margin-bottom: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,.3);
}
.np-results.open { display: block; }
.np-results-head {
  padding: 9px 16px; font-size: 11px; font-weight: 700; color: var(--txt-soft);
  border-bottom: 1px solid var(--stroke); text-transform: uppercase; letter-spacing: .05em;
}
.np-res-item {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 16px; cursor: pointer;
  border-bottom: 1px solid var(--stroke); transition: background 120ms;
}
.np-res-item:last-child { border-bottom: none; }
.np-res-item:hover { background: rgba(46,123,246,.07); }
.np-res-av {
  width: 34px; height: 34px; border-radius: 50%;
  background: linear-gradient(135deg,var(--blue),var(--cyan));
  display: grid; place-items: center; font-weight: 700; font-size: 12px; flex: none;
}
.np-res-name { font-size: 13.5px; font-weight: 600; }
.np-res-meta { font-size: 11.5px; color: var(--txt-soft); }
.np-res-empty { padding: 18px; text-align: center; font-size: 13px; color: var(--txt-soft); }

/* Card principal */
.np-card {
  background: #0d1433;
  border: 1px solid rgba(110,160,255,.18);
  border-radius: 18px;
  padding: 30px 32px;
  margin-bottom: 20px;
}

/* Sección header */
.np-sec-header {
  font-size: 20px; font-weight: 700; color: var(--txt);
  margin-bottom: 24px;
}

/* Layout: foto + campos */
.np-personal-layout {
  display: grid;
  grid-template-columns: 180px 1fr;
  gap: 28px;
  align-items: start;
}

/* Foto */
.np-foto-col {
  display: flex; flex-direction: column; align-items: center; gap: 10px;
}
.np-foto-box {
  width: 170px; height: 170px;
  background: #161d3f;
  border-radius: 12px;
  border: 1px solid rgba(110,160,255,.2);
  display: grid; place-items: center;
  overflow: hidden; cursor: pointer;
  transition: border-color 150ms;
}
.np-foto-box:hover { border-color: var(--blue); }
.np-foto-box img { width: 100%; height: 100%; object-fit: cover; display: none; }
.np-foto-ph {
  display: flex; flex-direction: column; align-items: center; gap: 8px;
  color: #6b7ab5;
}
.np-foto-ph svg { opacity: .7; }
.np-add-foto-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: 8px;
  border: 1px solid rgba(110,160,255,.3); background: #161d3f;
  font: inherit; font-size: 12.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; width: 100%; justify-content: center;
  transition: background 150ms, border-color 150ms;
}
.np-add-foto-btn:hover { background: #1a2347; border-color: var(--blue); }
.np-add-foto-btn svg { color: var(--cyan); }

/* Grid de campos */
.np-fields { display: grid; gap: 16px; }
.np-row-3 { grid-template-columns: 2fr 1fr 1fr; }
.np-row-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
.np-row-3b { grid-template-columns: 1fr 2fr 1fr; }
.np-row-2 { grid-template-columns: 1fr 1fr; }
.np-row-1 { grid-template-columns: 1fr; }

.np-field { display: flex; flex-direction: column; gap: 7px; }
.np-field label {
  font-size: 10.5px; font-weight: 700; text-transform: uppercase;
  letter-spacing: .08em; color: #7a8fc0;
}
.np-field input,
.np-field select,
.np-field textarea {
  background: #111830;
  border: 1px solid rgba(110,160,255,.2);
  border-radius: 10px;
  padding: 12px 14px; font: inherit; font-size: 14px; color: var(--txt);
  outline: none; width: 100%; transition: border-color 150ms, box-shadow 150ms;
}
.np-field input::placeholder,
.np-field textarea::placeholder { color: #3d4a75; }
.np-field input:focus,
.np-field select:focus,
.np-field textarea:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(46,123,246,.15);
}
.np-field select option { background: #0d1433; }
.np-field textarea { resize: vertical; min-height: 130px; }

/* Span full width */
.np-full { grid-column: 1 / -1; }

/* Separador */
.np-divider {
  border: none; border-top: 1px solid rgba(110,160,255,.12);
  margin: 8px 0 24px;
}

/* Panel lateral derecho */
.np-layout {
  display: grid;
  grid-template-columns: 1fr 200px;
  gap: 20px;
  align-items: start;
}
.np-side {
  display: flex; flex-direction: column; gap: 14px;
}
.np-action-btns {
  background: #0d1433;
  border: 1px solid rgba(110,160,255,.18);
  border-radius: 18px; overflow: hidden;
}
.np-action-btn {
  display: flex; align-items: center; gap: 12px;
  width: 100%; padding: 14px 16px;
  border: none; border-bottom: 1px solid rgba(110,160,255,.12);
  background: none; font: inherit; font-size: 13px; font-weight: 600;
  color: var(--txt); cursor: pointer; text-align: left; text-decoration: none;
  transition: background 150ms;
}
.np-action-btn:last-child { border-bottom: none; }
.np-action-btn:hover { background: rgba(110,160,255,.06); }
.np-ab-icon {
  width: 34px; height: 34px; border-radius: 9px;
  border: 1px solid rgba(110,160,255,.3);
  display: grid; place-items: center; flex: none;
  color: var(--cyan); background: rgba(56,199,244,.08);
}

@media (max-width:1100px) {
  .np-layout { grid-template-columns: 1fr; }
  .np-personal-layout { grid-template-columns: 1fr; }
  .np-row-4 { grid-template-columns: 1fr 1fr; }
  .np-row-3, .np-row-3b { grid-template-columns: 1fr 1fr; }
}
@media (max-width:640px) {
  .np-row-2,.np-row-3,.np-row-3b,.np-row-4 { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- Buscador + Filtros --}}
<div class="np-searchbar rise d1">
  <div class="np-search-wrap">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input class="np-search" type="text" id="npSearch" placeholder="Buscar paciente por nombre..." autocomplete="off">
  </div>
  <div class="np-filter-wrap">
    <button class="np-filter-btn" type="button" id="npFilterBtn">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      Filtrar
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </button>
    <div class="np-filter-drop" id="npFilterDrop">
      <div class="np-flt-title">Filtros de busqueda</div>
      <div class="np-flt-group">
        <div class="np-flt-lbl">Procedimiento</div>
        <select class="np-flt-sel" id="fltProc">
          <option value="">Todos</option>
          <option value="endoscopia">Endoscopia diagnostica</option>
          <option value="colonoscopia">Colonoscopia</option>
          <option value="gastroscopia">Gastroscopia</option>
          <option value="sigmoidoscopia">Sigmoidoscopia</option>
          <option value="cpre">CPRE</option>
          <option value="ecoendoscopia">Ecoendoscopia</option>
        </select>
      </div>
      <div class="np-flt-group">
        <div class="np-flt-lbl">Sexo</div>
        <div class="np-flt-chks">
          <label class="np-flt-chk"><input type="checkbox" id="fltSexoF" checked> Femenino</label>
          <label class="np-flt-chk"><input type="checkbox" id="fltSexoM" checked> Masculino</label>
        </div>
      </div>
      <div class="np-flt-group">
        <div class="np-flt-lbl">Medico</div>
        <select class="np-flt-sel" id="fltMed">
          <option value="">Todos</option>
          <option value="dr_victor">Dr. Victor</option>
          <option value="dr_ricardo">Dr. Ricardo</option>
        </select>
      </div>
      <div class="np-flt-actions">
        <button class="np-flt-apply" id="npFltApply">Aplicar</button>
        <button class="np-flt-clear" id="npFltClear">Limpiar</button>
      </div>
    </div>
  </div>
</div>

{{-- Resultados --}}
<div class="np-results rise d1" id="npResults">
  <div class="np-results-head">Resultados</div>
  <div id="npResultsList"></div>
</div>

{{-- Layout: formulario + sidebar --}}
<div class="np-layout">

  {{-- Formulario --}}
  <form method="POST" action="#" id="formNuevoPaciente">
    @csrf

    {{-- Card informacion personal --}}
    <div class="np-card rise d2">
      <div class="np-sec-header">Informacion personal</div>

      <div class="np-personal-layout">

        {{-- Foto --}}
        <div class="np-foto-col">
          <div class="np-foto-box" id="npFotoBox" onclick="document.getElementById('npFotoInput').click()">
            <img id="npFotoPreview" src="" alt="">
            <div class="np-foto-ph" id="npFotoPh">
              <svg width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
          </div>
          <input type="file" id="npFotoInput" accept="image/*" style="display:none">
          <input type="file" id="npFotoCamera" accept="image/*" capture="environment" style="display:none">
          <div style="position:relative;width:100%">
            <button class="np-add-foto-btn" type="button" id="npBtnFotoMenu">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              <span id="npBtnFotoTxt">Agregar foto</span>
            </button>
            <div id="npFotoMenu" style="display:none;position:absolute;bottom:calc(100% + 6px);left:0;right:0;background:var(--panel);border:1px solid var(--stroke-strong);border-radius:var(--r-md);overflow:hidden;z-index:50;box-shadow:0 8px 24px rgba(0,0,0,.4)">
              <button type="button" id="npBtnGaleria" style="display:flex;align-items:center;gap:8px;width:100%;padding:10px 12px;background:none;border:none;border-bottom:1px solid var(--stroke);font:inherit;font-size:13px;font-weight:600;color:var(--txt);cursor:pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                Abrir galeria
              </button>
              <button type="button" id="npBtnCamara" style="display:flex;align-items:center;gap:8px;width:100%;padding:10px 12px;background:none;border:none;font:inherit;font-size:13px;font-weight:600;color:var(--txt);cursor:pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                Tomar foto
              </button>
            </div>
          </div>
        </div>

        {{-- Campos personales --}}
        <div>
          <div class="np-fields np-row-3" style="margin-bottom:16px">
            <div class="np-field" style="grid-column:span 2">
              <label>Nombre completo</label>
              <input type="text" id="nombre" name="nombre" placeholder="Maria Fernanda Lopez Ruiz" autocomplete="off">
            </div>
            <div class="np-field">
              <label>Identificacion</label>
              <input type="text" id="identificacion" name="identificacion" placeholder="0256987450" autocomplete="off">
            </div>
          </div>

          <div class="np-fields np-row-4" style="margin-bottom:16px">
            <div class="np-field">
              <label>Fecha de nacimiento</label>
              <input type="date" id="fecha_nac" name="fecha_nac">
            </div>
            <div class="np-field">
              <label>Edad</label>
              <input type="text" id="edad" name="edad" placeholder="28 años" autocomplete="off">
            </div>
            <div class="np-field">
              <label>Peso</label>
              <input type="text" id="peso" name="peso" placeholder="30 kg" autocomplete="off">
            </div>
            <div class="np-field">
              <label>Altura</label>
              <input type="text" id="altura" name="altura" placeholder="1.75 m" autocomplete="off">
            </div>
          </div>

          <div class="np-fields np-row-3b" style="margin-bottom:16px">
            <div class="np-field">
              <label>Sexo</label>
              <select id="sexo" name="sexo">
                <option value="" disabled selected>Elegir</option>
                <option value="F">Femenino</option>
                <option value="M">Masculino</option>
              </select>
            </div>
            <div class="np-field">
              <label>N.S.S</label>
              <input type="text" id="nss" name="nss" placeholder="25849563-9" autocomplete="off">
            </div>
            <div class="np-field">
              <label>Telefono</label>
              <input type="tel" id="telefono" name="telefono" placeholder="722 162 0815" autocomplete="off">
            </div>
          </div>

          <div class="np-fields np-row-2" style="margin-bottom:0">
            <div class="np-field">
              <label>Direccion</label>
              <input type="text" id="direccion" name="direccion" placeholder="CALLE, CP" autocomplete="off">
            </div>
            <div class="np-field">
              <label>E-MAIL</label>
              <input type="email" id="email" name="email" placeholder="@gmail.com" autocomplete="off">
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- Card informacion medica --}}
    <div class="np-card rise d3" style="margin-top:16px">
      <div class="np-sec-header">Informacion medica</div>

      <div class="np-fields np-row-2" style="margin-bottom:16px">
        <div class="np-field">
          <label>Procedimiento</label>
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
        <div class="np-field">
          <label>Diagnostico Preliminar</label>
          <input type="text" id="diagnostico_short" name="diagnostico_short" placeholder="Define lo que podria tener" autocomplete="off">
        </div>
      </div>

      <div class="np-fields np-row-3" style="margin-bottom:0">
        <div class="np-field">
          <label>Fecha de registro</label>
          <input type="date" id="fecha_registro" name="fecha_registro">
        </div>
        <div class="np-field">
          <label>Medico</label>
          <select id="medico" name="medico">
            <option value="" disabled selected>Seleccione</option>
            <option value="dr_victor" selected>Dr. Victor</option>
            <option value="dr_ricardo">Dr. Ricardo</option>
          </select>
        </div>
        <div class="np-field">
          <label>Referido por</label>
          <select id="referido" name="referido">
            <option value="" disabled selected>Seleccione</option>
            <option value="externo">Medico externo</option>
            <option value="propio">Medico propio</option>
            <option value="paciente">Paciente directo</option>
          </select>
        </div>
      </div>
    </div>

  </form>

  {{-- Sidebar acciones --}}
  <div class="np-side rise d4">
    <div class="np-action-btns">
      <a class="np-action-btn" href="{{ route('nuevo-estudio.grabando') }}">
        <span class="np-ab-icon" style="background:rgba(255,59,59,.12);border-color:rgba(255,90,110,.4)">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ff5a6e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="#ff5a6e" stroke="none"/></svg>
        </span>
        Iniciar Grabacion
      </a>
      <a class="np-action-btn" href="{{ route('nuevo-estudio.capturas') }}">
        <span class="np-ab-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
        </span>
        Agregar Capturas
      </a>
      <a class="np-action-btn" href="{{ route('nuevo-estudio.importar') }}">
        <span class="np-ab-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        </span>
        Importar Imagenes
      </a>
      <a class="np-action-btn" href="{{ route('nuevo-estudio.configuracion') }}">
        <span class="np-ab-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
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

  /* Fecha por defecto */
  var now  = new Date();
  var pad  = function(n){ return String(n).padStart(2,'0'); };
  document.getElementById('fecha_nac').value      = '1998-12-25';
  document.getElementById('fecha_registro').value = now.getFullYear()+'-'+pad(now.getMonth()+1)+'-'+pad(now.getDate());

  /* Foto menu */
  var fotoMenu   = document.getElementById('npFotoMenu');
  var btnFotoTxt = document.getElementById('npBtnFotoTxt');

  document.getElementById('npBtnFotoMenu').addEventListener('click', function(e){
    e.stopPropagation();
    fotoMenu.style.display = fotoMenu.style.display === 'none' ? 'block' : 'none';
  });
  document.addEventListener('click', function(){ fotoMenu.style.display = 'none'; });
  document.getElementById('npBtnGaleria').addEventListener('click', function(){
    fotoMenu.style.display = 'none';
    document.getElementById('npFotoInput').click();
  });
  document.getElementById('npBtnCamara').addEventListener('click', function(){
    fotoMenu.style.display = 'none';
    document.getElementById('npFotoCamera').click();
  });

  function applyPreview(file){
    if (!file) return;
    var r = new FileReader();
    r.onload = function(e){
      var img = document.getElementById('npFotoPreview');
      var ph  = document.getElementById('npFotoPh');
      img.src = e.target.result;
      img.style.display = 'block';
      ph.style.display  = 'none';
      btnFotoTxt.textContent = 'Cambiar foto';
    };
    r.readAsDataURL(file);
  }
  document.getElementById('npFotoInput').addEventListener('change',  function(){ applyPreview(this.files[0]); });
  document.getElementById('npFotoCamera').addEventListener('change', function(){ applyPreview(this.files[0]); });

  /* Filtros */
  var filterBtn  = document.getElementById('npFilterBtn');
  var filterDrop = document.getElementById('npFilterDrop');

  filterBtn.addEventListener('click', function(e){
    e.stopPropagation();
    filterDrop.classList.toggle('open');
    filterBtn.classList.toggle('open');
  });
  document.addEventListener('click', function(){
    filterDrop.classList.remove('open');
    filterBtn.classList.remove('open');
  });
  filterDrop.addEventListener('click', function(e){ e.stopPropagation(); });

  document.getElementById('npFltApply').addEventListener('click', function(){
    filterDrop.classList.remove('open');
    filterBtn.classList.remove('open');
    doSearch();
  });
  document.getElementById('npFltClear').addEventListener('click', function(){
    document.getElementById('fltProc').value   = '';
    document.getElementById('fltSexoF').checked = true;
    document.getElementById('fltSexoM').checked = true;
    document.getElementById('fltMed').value    = '';
    document.getElementById('npSearch').value  = '';
    hideResults();
  });

  /* Pacientes demo */
  var PACS = [
    { nombre:'Maria Gonzalez',    id:'025698745', sexo:'F', medico:'dr_victor',  proc:'colonoscopia',   edad:28 },
    { nombre:'Jose Ramirez',      id:'031456789', sexo:'M', medico:'dr_victor',  proc:'endoscopia',     edad:45 },
    { nombre:'Ana Torres',        id:'012345678', sexo:'F', medico:'dr_ricardo', proc:'gastroscopia',   edad:33 },
    { nombre:'Carlos Mendez',     id:'098765432', sexo:'M', medico:'dr_ricardo', proc:'colonoscopia',   edad:52 },
    { nombre:'Laura Perez',       id:'087654321', sexo:'F', medico:'dr_victor',  proc:'ecoendoscopia',  edad:41 },
    { nombre:'Roberto Flores',    id:'076543210', sexo:'M', medico:'dr_victor',  proc:'cpre',           edad:60 },
    { nombre:'Sofia Martinez',    id:'065432109', sexo:'F', medico:'dr_ricardo', proc:'sigmoidoscopia', edad:29 },
    { nombre:'Miguel Hernandez',  id:'054321098', sexo:'M', medico:'dr_victor',  proc:'endoscopia',     edad:38 },
  ];

  function doSearch(){
    var q    = document.getElementById('npSearch').value.trim().toLowerCase();
    var proc = document.getElementById('fltProc').value;
    var sexF = document.getElementById('fltSexoF').checked;
    var sexM = document.getElementById('fltSexoM').checked;
    var med  = document.getElementById('fltMed').value;

    if (!q && !proc && sexF && sexM && !med){ hideResults(); return; }

    var res = PACS.filter(function(p){
      return (!q    || p.nombre.toLowerCase().includes(q) || p.id.includes(q))
          && (!proc  || p.proc   === proc)
          && ((p.sexo==='F'&&sexF)||(p.sexo==='M'&&sexM))
          && (!med   || p.medico === med);
    });
    showResults(res);
  }

  function showResults(res){
    var panel = document.getElementById('npResults');
    var list  = document.getElementById('npResultsList');
    panel.classList.add('open');
    if (!res.length){
      list.innerHTML = '<div class="np-res-empty">No se encontraron pacientes</div>';
      return;
    }
    list.innerHTML = res.map(function(p){
      var ini = p.nombre.split(' ').slice(0,2).map(function(w){ return w[0]; }).join('');
      return '<div class="np-res-item" data-nombre="'+p.nombre+'" data-id="'+p.id+'" data-sexo="'+p.sexo+'">'
        +'<div class="np-res-av">'+ini+'</div>'
        +'<div><div class="np-res-name">'+p.nombre+'</div>'
        +'<div class="np-res-meta">ID: '+p.id+' &middot; '+p.edad+' anos &middot; '+p.proc.charAt(0).toUpperCase()+p.proc.slice(1)+'</div></div>'
        +'</div>';
    }).join('');
    list.querySelectorAll('.np-res-item').forEach(function(el){
      el.addEventListener('click', function(){
        document.getElementById('nombre').value        = el.dataset.nombre;
        document.getElementById('identificacion').value = el.dataset.id;
        document.getElementById('sexo').value          = el.dataset.sexo;
        document.getElementById('npSearch').value      = el.dataset.nombre;
        hideResults();
      });
    });
  }

  function hideResults(){
    document.getElementById('npResults').classList.remove('open');
  }

  document.getElementById('npSearch').addEventListener('input', doSearch);

})();
</script>
@endpush