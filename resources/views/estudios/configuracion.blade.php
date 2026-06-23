@extends('layouts.app')

@section('title', 'Configuracion de Grabación')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Configuracion / Fuente de Video
@endsection

@push('styles')
<style>
/* ===== CONFIGURACION GRABACION ===== */

/* Fuente base para toda la vista */
.cfg-toolbar, .cfg-main-title, .cfg-pac-label,
.cfg-top, .cfg-info-panel, .cfg-tabs,
.cfg-tab-content, .cfg-tab, .cfg-field,
.cfg-field-label, .cfg-section-title,
.cfg-select, .cfg-input, .cfg-chk-item,
.cfg-check-line, .cfg-log, .cfg-info-header,
.btn-mas-opciones, .btn-tool, .btn-regresar,
.fps-badge, .auto-imp-box, .auto-imp-lbl {
  font-family: 'Hanken Grotesk', sans-serif;
}
.cfg-main-title {
  font-family: 'Sora', sans-serif !important;
}

.cfg-toolbar {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 22px; flex-wrap: wrap;
}
.btn-tool {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 18px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font-size: 14px; font-weight: 600; color: var(--txt);
  cursor: pointer; text-decoration: none;
  transition: background-color 150ms ease;
}
.btn-tool svg { color: var(--cyan); }
.btn-tool:hover { background: var(--card); }
.btn-regresar {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 20px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font-size: 14px; font-weight: 600; color: var(--txt);
  cursor: pointer; text-decoration: none; margin-left: auto;
  transition: background-color 150ms ease;
}
.btn-regresar:hover { background: var(--card); }

/* Título */
.cfg-main-title {
  font-family: 'Sora', sans-serif;
  font-size: 24px; font-weight: 800; font-style: italic;
  text-align: left; margin-bottom: 4px; color: var(--txt);
}
.cfg-pac-label {
  font-size: 13.5px; font-weight: 500; color: var(--txt-soft); margin-bottom: 16px;
}

/* Layout superior */
.cfg-top {
  display: grid;
  grid-template-columns: 340px 1fr;
  gap: 18px; align-items: start; margin-bottom: 18px;
}

/* Video preview */
.cfg-video-wrap {
  border-radius: var(--r-lg);
  overflow: hidden;
  border: 2px solid var(--blue);
  background: #000;
  aspect-ratio: 16/7;
  max-height: 280px;
  position: relative;
}
.cfg-video-wrap img {
  width: 100%; height: 100%; object-fit: cover; display: block;
}

/* Panel derecho info */
.cfg-info-panel {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  padding: 16px 18px;
  display: flex; flex-direction: column; gap: 12px;
}
.cfg-info-header {
  font-size: 13px; font-weight: 700; text-align: left; color: var(--txt);
}
.cfg-check-line {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; color: var(--txt-soft); font-family: inherit;
}
.cfg-check-line input { accent-color: var(--blue); cursor: pointer; }

.cfg-log {
  background: var(--panel);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 12px 14px;
  font-size: 11.5px;
  line-height: 1.8;
  color: var(--txt-soft);
  font-family: 'Courier New', monospace;
  overflow-y: auto;
  max-height: 160px;
}
.cfg-log span { display: block; }
.cfg-log .hl { color: var(--cyan); }

.btn-mas-opciones {
  padding: 10px 22px; border-radius: var(--r-md);
  background: var(--panel-2); border: 1px solid var(--stroke-strong);
  font-family: inherit; font-size: 13.5px; font-weight: 600;
  color: var(--txt); cursor: pointer; align-self: flex-start;
  transition: background-color 150ms ease;
}
.btn-mas-opciones:hover { background: var(--card); }

/* Modal de Más Opciones */
.mas-opciones-modal {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,.65);
  z-index: 1000;
  justify-content: center;
  align-items: center;
}

.mas-opciones-modal.active {
  display: flex;
}

.mas-opciones-content {
  background: var(--panel);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-lg);
  padding: 24px;
  width: 320px;
  max-width: 90vw;
  position: relative;
  box-shadow: 0 20px 60px rgba(0,0,0,.5);
}

.mas-opciones-close {
  position: absolute;
  top: 12px;
  right: 16px;
  background: none;
  border: none;
  font-size: 24px;
  color: var(--txt-soft);
  cursor: pointer;
  transition: color 150ms ease;
}

.mas-opciones-close:hover {
  color: var(--txt);
}

.mas-opciones-field {
  margin-bottom: 16px;
}

.mas-opciones-label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: var(--txt);
  margin-bottom: 8px;
}

.mas-opciones-select {
  width: 100%;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 10px 12px;
  font-family: inherit;
  font-size: 13px;
  color: var(--txt);
  cursor: pointer;
  outline: none;
}

.mas-opciones-select:focus {
  border-color: var(--blue);
}

.mas-opciones-canales {
  margin-bottom: 20px;
}

.canales-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
}

.canal-btn {
  padding: 12px;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  font-family: inherit;
  font-size: 14px;
  font-weight: 600;
  color: var(--txt);
  cursor: pointer;
  transition: all 150ms ease;
}

.canal-btn:hover {
  background: var(--card);
  border-color: var(--cyan);
}

.canal-btn.active {
  background: var(--blue);
  border-color: var(--blue);
  color: #fff;
}

.mas-opciones-iconos {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  justify-items: center;
  margin-top: 20px;
  padding-top: 16px;
  border-top: 1px solid var(--stroke);
}

.opcion-icon-btn {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--cyan);
  cursor: pointer;
  transition: all 150ms ease;
}

.opcion-icon-btn:hover {
  background: var(--blue);
  border-color: var(--blue);
  color: #fff;
  transform: scale(1.1);
}

.opcion-icon-btn:active {
  transform: scale(.95);
}

.opcion-icon-btn.recording {
  background: var(--red);
  border-color: var(--red);
  color: #fff;
  animation: pulseRecord 1.5s infinite;
}

@keyframes pulseRecord {
  0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,.4); }
  50% { box-shadow: 0 0 0 10px rgba(239,68,68,0); }
}

/* Tabs */
.cfg-tabs {
  display: flex; gap: 0;
  background: var(--card);
  border: 1px solid var(--stroke);
  border-bottom: none;
  border-radius: var(--r-lg) var(--r-lg) 0 0;
  overflow-x: auto;
}
.cfg-tab {
  padding: 13px 22px; font-size: 13.5px; font-weight: 600;
  cursor: pointer; color: var(--txt-soft); border: none;
  background: none; border-bottom: 2px solid transparent;
  font-family: inherit;
  transition: color 150ms ease, border-color 150ms ease;
  white-space: nowrap; flex: none;
}
.cfg-tab:hover { color: var(--txt); }
.cfg-tab.active { color: var(--txt); border-bottom-color: var(--blue); }

/* Panel de tabs */
.cfg-tab-content {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke); border-top: 0;
  border-radius: 0 0 var(--r-lg) var(--r-lg);
  padding: 22px 24px; min-height: 200px;
}
.tab-panel { display: none; }
.tab-panel.active { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 28px; }

/* Secciones dentro del tab */
.cfg-section-title {
  font-size: 13px; font-weight: 700; margin-bottom: 12px;
  color: var(--txt-soft); letter-spacing: .04em; text-transform: uppercase;
}
.cfg-field { margin-bottom: 12px; }
.cfg-field-label {
  font-size: 12.5px; color: var(--txt-soft); margin-bottom: 6px; font-weight: 500;
}
.cfg-select {
  width: 100%; appearance: none;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  padding: 10px 14px; font-family: inherit; font-size: 13.5px;
  color: var(--txt); cursor: pointer; outline: none;
  transition: border-color 150ms ease;
}
.cfg-select:focus { border-color: var(--blue); }
.cfg-select option { background: var(--panel); color: var(--txt); }
.cfg-input {
  width: 100%; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  padding: 10px 14px; font-family: inherit; font-size: 13.5px;
  color: var(--txt); outline: none; box-sizing: border-box;
  transition: border-color 150ms ease;
}
.cfg-input:focus { border-color: var(--blue); }
.cfg-input[type=range] { padding: 6px 0; }

/* Checks columna */
.cfg-checks { display: flex; flex-direction: column; gap: 9px; }
.cfg-chk-item {
  display: flex; align-items: center; gap: 8px;
  font-size: 13.5px; font-family: inherit; color: var(--txt); cursor: pointer;
}
.cfg-chk-item input { accent-color: var(--blue); cursor: pointer; }

/* FPS badge */
.fps-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--panel-2); border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md); padding: 10px 18px;
  font-size: 14px; font-weight: 700; color: var(--txt);
}
.fps-badge svg { color: var(--cyan); }

/* Auto importar */
.auto-imp-box {
  background: var(--panel-2); border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md); padding: 14px;
  display: flex; flex-direction: column; gap: 8px;
}
.auto-imp-lbl {
  display: flex; align-items: center; gap: 8px;
  font-size: 13.5px; font-family: inherit; font-weight: 700;
  color: var(--txt); cursor: pointer;
}
.auto-imp-lbl input { accent-color: var(--blue); cursor: pointer; }

/* Display tab: input numérico pequeño */
.dsp-num {
  width: 72px !important;
  padding: 6px 8px !important;
  font-size: 13px !important;
  text-align: center;
}

/* Display tab: fila label + input en línea */
.dsp-row-field {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 8px;
}
.dsp-row-field .cfg-field-label {
  margin-bottom: 0;
  min-width: 36px;
}

/* Cropping zoom box */
.dsp-crop-box {
  position: relative;
  width: 160px;
  height: 120px;
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  background: var(--panel);
  margin: 4px 0;
  overflow: hidden;
  user-select: none;
}
.dsp-crop-area {
  position: absolute;
  border: 1.5px solid var(--blue);
  border-radius: 4px;
  background: rgba(46,123,246,.12);
  cursor: move;
  box-sizing: border-box;
}
.dsp-handle {
  position: absolute;
  width: 8px; height: 8px;
  background: var(--blue);
  border-radius: 2px;
  z-index: 2;
}
.dsp-handle.nw { top:-4px; left:-4px; cursor:nw-resize; }
.dsp-handle.ne { top:-4px; right:-4px; cursor:ne-resize; }
.dsp-handle.sw { bottom:-4px; left:-4px; cursor:sw-resize; }
.dsp-handle.se { bottom:-4px; right:-4px; cursor:se-resize; }
.dsp-axis {
  position: absolute;
  font-size: 10px;
  color: var(--txt-soft);
  font-family: inherit;
  pointer-events: none;
  z-index: 3;
}
.dsp-axis-y { top: 3px; right: 5px; }
.dsp-axis-x { bottom: 3px; left: 5px; }
.dsp-coords {
  display: flex; gap: 8px; margin-top: 8px; flex-wrap: wrap;
}
.dsp-coord-field {
  display: flex; align-items: center; gap: 5px;
  font-size: 11.5px; color: var(--txt-soft);
}
.dsp-coord-field input {
  width: 52px; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: 6px;
  padding: 4px 6px; font-family: inherit; font-size: 11.5px;
  color: var(--txt); outline: none; text-align: center;
}

/* ── Reproducir ── */
.rep-main-panel {
  display: flex; gap: 12px; align-items: stretch;
}
.rep-left {
  display: flex; gap: 8px; align-items: stretch; flex: none;
}
.rep-list-box {
  width: 200px; min-height: 150px; max-height: 200px;
  background: var(--panel);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 6px; overflow-y: auto; flex: none;
}
.rep-empty {
  color: var(--txt-soft); font-size: 12px;
  text-align: center; padding: 20px 0; font-family: inherit;
}
.rep-list-item {
  padding: 8px 10px; border-radius: 6px;
  font-size: 12.5px; color: var(--txt-soft);
  cursor: pointer; font-family: inherit;
  transition: background 120ms;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  display: flex; align-items: center; gap: 6px;
}
.rep-list-item::before {
  content: '';
  width: 6px; height: 6px; border-radius: 50%;
  background: var(--stroke-strong); flex: none;
  transition: background 120ms;
}
.rep-list-item:hover   { background: rgba(110,160,255,.08); color: var(--txt); }
.rep-list-item:hover::before { background: var(--blue); }
.rep-list-item.sel     { background: rgba(46,123,246,.14); color: var(--txt); }
.rep-list-item.sel::before   { background: var(--cyan); }
.rep-list-item.playing { color: var(--cyan); }
.rep-list-item.playing::before { background: var(--cyan); animation: repPulse 1s infinite; }
@keyframes repPulse { 0%,100%{opacity:1} 50%{opacity:.3} }

.rep-sidebar-icons {
  display: flex; flex-direction: column;
  justify-content: flex-start; gap: 4px; padding: 4px 0;
}
.rep-side-btn {
  width: 34px; height: 34px; border-radius: 8px;
  background: var(--panel-2); border: 1px solid var(--stroke);
  display: flex; align-items: center; justify-content: center;
  color: var(--blue); cursor: pointer;
  transition: background 150ms, color 150ms, border-color 150ms;
}
.rep-side-btn:hover           { background: var(--card); color: var(--cyan); border-color: var(--blue); }
.rep-side-btn--danger:hover   { color: var(--red); border-color: var(--red); }

.rep-ctrl-panel {
  flex: 1;
  background: var(--panel);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 16px 22px;
  display: flex; flex-direction: column; gap: 12px;
  justify-content: center;
}
.rep-ctrl-title {
  font-size: 13px; font-weight: 700; text-align: center;
  color: var(--txt); font-family: inherit; letter-spacing:.02em;
}
.rep-status {
  font-size: 11.5px; color: var(--txt-soft);
  text-align: center; font-family: 'Courier New', monospace;
  min-height: 16px;
}
.rep-progress-wrap { width: 100%; }
.rep-progress {
  width: 100%; accent-color: var(--blue); cursor: pointer;
  height: 4px;
}
.rep-controls-row {
  display: flex; align-items: center; justify-content: space-between;
}
.rep-ctrl-group {
  display: flex; align-items: center; gap: 6px;
}
.rep-ctrl-group--main { gap: 12px; }
.rep-ctrl-btn {
  background: none; border: none; cursor: pointer;
  color: var(--blue); padding: 4px;
  transition: color 150ms, transform 100ms;
  display: flex; align-items: center; justify-content: center;
  border-radius: 50%;
}
.rep-ctrl-btn:hover          { color: var(--cyan); transform: scale(1.14); }
.rep-ctrl-btn--lg            { color: var(--blue); }
.rep-ctrl-btn--play {
  width: 44px; height: 44px;
  background: var(--blue);
  border-radius: 50%; color: #fff;
  box-shadow: 0 0 14px rgba(46,123,246,.4);
  transition: background 150ms, transform 100ms, box-shadow 150ms;
}
.rep-ctrl-btn--play:hover {
  background: var(--cyan); transform: scale(1.08);
  box-shadow: 0 0 20px rgba(56,199,244,.5);
}
.rep-ctrl-btn--play svg { color: #fff; }

/* ── Audio: radio buttons personalizados ── */
.aud-radio-item {
  display: flex; align-items: center; gap: 10px;
  font-size: 13.5px; font-family: inherit;
  color: var(--txt); cursor: pointer;
}
.aud-radio-item input[type="radio"] { display: none; }
.aud-radio-dot {
  width: 18px; height: 18px; border-radius: 50%;
  border: 2px solid var(--stroke-strong);
  background: var(--panel-2);
  flex: none; position: relative;
  transition: border-color 150ms ease;
}
.aud-radio-item input[type="radio"]:checked + .aud-radio-dot {
  border-color: var(--blue);
  background: var(--blue);
  box-shadow: 0 0 0 3px rgba(46,123,246,.25);
}
.aud-radio-item input[type="radio"]:checked + .aud-radio-dot::after {
  content: '';
  position: absolute; inset: 3px;
  border-radius: 50%; background: #fff;
}

/* ── Texto e Imagen: preview y selector de fuente ── */

/* Card interno con borde suave */
.txt-card {
  background: var(--panel-2);
  border: 1px solid var(--stroke);
  border-radius: var(--r-md);
  padding: 14px 16px;
}

/* Grid label + input de 2 columnas */
.txt-align-grid {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 8px 12px;
  align-items: center;
  margin-top: 10px;
}
.txt-align-grid .cfg-field-label { margin-bottom: 0; }

.txt-preview-box {
  background: var(--panel);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 16px 18px;
  min-height: 110px;
  line-height: 1.8;
}

.btn-fuente {
  width: 100%;
  padding: 10px 14px;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  font-family: inherit; font-size: 14px; font-weight: 700;
  color: var(--txt); cursor: pointer;
  transition: background-color 150ms ease;
}
.btn-fuente:hover { background: var(--card); }

.fuente-dropdown {
  display: none;
  position: absolute;
  top: calc(100% + 6px); right: 0;
  width: 260px;
  background: var(--panel);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  z-index: 999;
  box-shadow: 0 12px 32px rgba(0,0,0,.45);
  overflow: hidden;
}
.fuente-dropdown.open { display: block; }
.fuente-search-wrap {
  padding: 10px;
  border-bottom: 1px solid var(--stroke);
}
.fuente-search-wrap .cfg-input {
  font-size: 13px; padding: 8px 12px;
}
.fuente-list {
  list-style: none;
  max-height: 220px;
  overflow-y: auto;
  padding: 4px 0;
}
.fuente-list li {
  padding: 9px 16px;
  font-size: 14px;
  color: var(--txt);
  cursor: pointer;
  transition: background-color 120ms ease;
}
.fuente-list li:hover  { background: rgba(110,160,255,.1); }
.fuente-list li.active { background: rgba(46,123,246,.2); color: var(--cyan); }

@media(max-width:900px){
  .cfg-top { grid-template-columns: 1fr; }
  .tab-panel.active { grid-template-columns: 1fr 1fr; }
}
@media(max-width:600px){
  .tab-panel.active { grid-template-columns: 1fr; }
  .cfg-tabs { overflow-x: auto; }
}

/* ================= TEMA CLARO ================= */
html[data-theme="light"] .cfg-video-wrap { background: #000; }
html[data-theme="light"] .mas-opciones-backdrop { background: rgba(240,244,250,.65); }
html[data-theme="light"] .mas-opciones-modal { box-shadow: 0 20px 60px rgba(20,50,120,.18); }
html[data-theme="light"] .canal-btn.active { color: #fff; }
html[data-theme="light"] .opcion-icon-btn:hover { color: #fff; }
html[data-theme="light"] .opcion-icon-btn.recording { color: #fff; }
html[data-theme="light"] .rep-list-item:hover { background: var(--hover-bg); }
html[data-theme="light"] .rep-list-item.sel { background: var(--hover-bg-strong); }
html[data-theme="light"] .rep-ctrl-btn--play { color: #fff; }
html[data-theme="light"] .rep-ctrl-btn--play svg { color: #fff; }
html[data-theme="light"] .aud-radio-item input[type="radio"]:checked + .aud-radio-dot::after { background: #fff; }
html[data-theme="light"] .fuente-dropdown { box-shadow: 0 12px 32px rgba(20,50,120,.15); }
html[data-theme="light"] .fuente-list li:hover { background: var(--hover-bg); }
html[data-theme="light"] .fuente-list li.active { background: var(--hover-bg-strong); }
</style>
@endpush

@section('content')

  {{-- Toolbar --}}
  <div class="cfg-toolbar rise d1">
    <a class="btn-tool" href="{{ route('nuevo-estudio.crear') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nuevo paciente
    </a>
    <button class="btn-tool">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Buscar paciente
    </button>
    <a class="btn-regresar" href="{{ route('nuevo-estudio.crear') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Regresar
    </a>
  </div>

  {{-- Título --}}
  <h1 class="cfg-main-title rise d1">Configuracion de Grabación</h1>
  <p class="cfg-pac-label rise d1">Paciente: Maria Gonzalez</p>

  {{-- Layout superior: panel info + video --}}
  <div class="cfg-top rise d2">

    {{-- Panel info --}}
    <div class="cfg-info-panel">
      <div class="cfg-info-header">Auto Importación de Fotos Activado</div>
      <label class="cfg-check-line">
        <input type="checkbox"> Pantalla completa
      </label>
      <div class="cfg-log">
        <span class="hl">Video en vivo | Paciente Maria Gonzalez | Folder de Fotos C:\ENCLAII\Patient\13\</span>
        <span>Video en Vivo |Auto Importar FotosActivado | Folder C:\ENCLAII\Temp</span>
        <span>No se encuentra la tarjeta</span>
        <span class="hl">[INFO] recording time interval set to 1800 records</span>
        <span>Buscando fotos en C:\ENCLAII\Temp</span>
        <span>Foto Capturada Maria Gonzalez-20260530-1.JPG</span>
      </div>
      <button class="btn-mas-opciones" type="button" id="btnMasOpciones">Mas Opciones</button>

      {{-- Modal de Más Opciones --}}
      <div class="mas-opciones-modal" id="masOpcionesModal">
        <div class="mas-opciones-content">
          <button class="mas-opciones-close" id="cerrarMasOpciones">&times;</button>

          <div class="mas-opciones-field">
            <label class="mas-opciones-label">Area de Captura</label>
            <select class="mas-opciones-select" id="areaCapturaSelect">
              <option value="full">Pantalla Completa</option>
              <option value="window">Ventana Activa</option>
              <option value="region">Región Personalizada</option>
            </select>
          </div>

          <div class="mas-opciones-field">
            <label class="mas-opciones-label">Canal de Video</label>
            <select class="mas-opciones-select" id="canalVideoSelect">
              <option value="1">Canal 1 - USB Video</option>
              <option value="2">Canal 2 - HDMI</option>
              <option value="3">Canal 3 - SDI</option>
              <option value="4">Canal 4 - Red</option>
            </select>
          </div>

          <div class="mas-opciones-canales">
            <label class="mas-opciones-label">Canales</label>
            <div class="canales-grid">
              <button class="canal-btn active" data-canal="1">1</button>
              <button class="canal-btn" data-canal="2">2</button>
              <button class="canal-btn" data-canal="3">3</button>
              <button class="canal-btn" data-canal="4">4</button>
              <button class="canal-btn" data-canal="5">5</button>
              <button class="canal-btn" data-canal="6">6</button>
            </div>
          </div>

          <div class="mas-opciones-iconos">
            <button class="opcion-icon-btn" id="iconStop" title="Detener">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="6" width="12" height="12" rx="2"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconVideo" title="Iniciar Grabación">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="15" height="12" rx="2"/><polygon points="17 10 22 6 22 18 17 14" fill="currentColor"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconFilm" title="Película">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="18" rx="2"/><line x1="8" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="16" y2="21"/><line x1="2" y1="9" x2="22" y2="9"/><line x1="2" y1="15" x2="22" y2="15"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconFilmStrip" title="Tira de Fotos">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 6v12"/><path d="M18 6v12"/><circle cx="6" cy="9" r="1" fill="currentColor"/><circle cx="6" cy="15" r="1" fill="currentColor"/><circle cx="18" cy="9" r="1" fill="currentColor"/><circle cx="18" cy="15" r="1" fill="currentColor"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconCrop" title="Recortar">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 2.5h11v11h-11z"/><path d="M2 6.5h4v4H2z"/><path d="M18 13.5h4v4h-4z"/><path d="M6.5 18h11v4h-11z"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconSettings" title="Configuración">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v4"/><path d="M12 18v4"/><path d="M4.93 4.93l2.83 2.83"/><path d="M16.24 16.24l2.83 2.83"/><path d="M2 12h4"/><path d="M18 12h4"/><path d="M4.93 19.07l2.83-2.83"/><path d="M16.24 7.76l2.83-2.83"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconCamera" title="Capturar Foto">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- Preview de video --}}
    <div class="cfg-video-wrap">
      <img src="{{ asset('images/captura1.jpg') }}" alt="Vista en vivo">
    </div>

  </div>

  {{-- Tabs --}}
  <div class="rise d3">
    <div class="cfg-tabs">
      <button class="cfg-tab active" data-tab="fuente">Fuente de Video</button>
      <button class="cfg-tab" data-tab="display">Display</button>
      <button class="cfg-tab" data-tab="texto">Texto e Imagen</button>
      <button class="cfg-tab" data-tab="audio">Audio</button>
      <button class="cfg-tab" data-tab="grabacion">Grabación</button>
      <button class="cfg-tab" data-tab="reproducir">Reproducir</button>
    </div>

    <div class="cfg-tab-content">

      {{-- Fuente de Video --}}
      <div class="tab-panel active" id="tab-fuente">
        <div>
          <div class="cfg-section-title">Captura de Video</div>
          <div class="cfg-field">
            <div class="cfg-field-label">Dispositivos de Captura</div>
            <select class="cfg-select">
              <option>USB Video Device</option>
              <option>Integrated Camera</option>
              <option>Endoscope Capture</option>
            </select>
          </div>
          <div class="cfg-field">
            <div class="cfg-field-label">Tamaño de Video</div>
            <select class="cfg-select">
              <option>1920 x 1080</option>
              <option>1280 x 720</option>
              <option>720 x 480</option>
            </select>
          </div>
          <div class="cfg-field">
            <div class="cfg-field-label">Subtipo de Video</div>
            <select class="cfg-select">
              <option>MJPG</option>
              <option>YUY2</option>
              <option>NV12</option>
            </select>
          </div>
          <div class="cfg-field">
            <div class="cfg-field-label">NTSC / PAL</div>
            <select class="cfg-select">
              <option>NTSC</option>
              <option>PAL</option>
            </select>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px">
          <div class="fps-badge">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            FPS
          </div>
          <div class="cfg-field" style="width:100%">
            <div class="cfg-field-label">Frames por segundo</div>
            <input class="cfg-input" type="number" value="30" min="1" max="120">
          </div>
        </div>

        <div>
          <div class="cfg-section-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:6px;color:var(--cyan)"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
            Video Render
          </div>
          <div class="cfg-checks">
            <label class="cfg-chk-item"><input type="checkbox"> Auto Select</label>
            <label class="cfg-chk-item"><input type="checkbox"> EVR</label>
            <label class="cfg-chk-item"><input type="checkbox"> VMR9</label>
            <label class="cfg-chk-item"><input type="checkbox"> VMR7</label>
            <label class="cfg-chk-item"><input type="checkbox"> Standard</label>
            <label class="cfg-chk-item"><input type="checkbox"> Overly</label>
            <label class="cfg-chk-item"><input type="checkbox"> Record Priority</label>
          </div>
          <div style="margin-top:16px">
            <div class="auto-imp-box">
              <label class="auto-imp-lbl">
                <input type="checkbox" checked> Auto Importar
              </label>
              <label class="cfg-chk-item">
                <input type="checkbox" checked> Importar Automáticamente
              </label>
            </div>
          </div>
        </div>
      </div>

      {{-- Display --}}
      <div class="tab-panel" id="tab-display" style="grid-template-columns:1fr 1fr 1fr">

        {{-- Col 1: Ventana de Video --}}
        <div>
          <div class="cfg-section-title">Ventana de Video</div>
          <label class="cfg-chk-item" style="margin-bottom:12px">
            <input type="checkbox"> Ajustar Tamaño
          </label>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Ancho</span>
            <input class="cfg-input dsp-num" type="number" value="540">
          </div>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Alto</span>
            <input class="cfg-input dsp-num" type="number" value="480">
          </div>
          <div class="cfg-field-label" style="margin-top:14px;margin-bottom:6px">Zoom</div>
          <input class="cfg-input dsp-num" type="number" value="1000">
        </div>

        {{-- Col 3: Foto Capturadora --}}
        <div>
          <div class="cfg-section-title">Foto Capturadora</div>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Ancho</span>
            <input class="cfg-input dsp-num" type="number" value="130">
          </div>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Alto</span>
            <input class="cfg-input dsp-num" type="number" value="130">
          </div>
          <div class="cfg-field-label" style="margin-top:14px">Tamaño del Contador</div>
        </div>

        {{-- Col 4: Overlays --}}
        <div>
          <div class="cfg-section-title">Overlays</div>
          <div class="cfg-field-label" style="margin-bottom:10px">Indicador de Captura de Fotos</div>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Ancho</span>
            <input class="cfg-input dsp-num" type="number" value="540">
          </div>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Alto</span>
            <input class="cfg-input dsp-num" type="number" value="480">
          </div>
          <div class="cfg-field-label" style="margin-top:14px">Indicador de Grabación</div>
        </div>

      </div>

      {{-- Texto e Imagen --}}
      <div class="tab-panel" id="tab-texto" style="grid-template-columns:1fr 1.6fr 1fr;gap:20px;align-items:start">

        {{-- Col 1: Configuracion --}}
        <div style="display:flex;flex-direction:column;gap:8px">

          {{-- Activo --}}
          <label class="cfg-chk-item">
            <input type="checkbox" id="textoActivo" checked> Activo
          </label>

          {{-- Alineacion --}}
          <div class="txt-card" style="padding:12px 14px">
            <div class="cfg-section-title" style="margin-bottom:8px">Alineación</div>
            <div style="display:flex;flex-direction:column;gap:8px">
              <div style="display:flex;align-items:center;gap:12px">
                <span class="cfg-field-label" style="min-width:40px">Izq</span>
                <input class="cfg-input dsp-num" id="txtIzq" type="number" value="5" style="width:64px">
                <span class="cfg-field-label">Izquierdo</span>
              </div>
              <div style="display:flex;align-items:center;gap:12px">
                <span class="cfg-field-label" style="min-width:40px">Centro</span>
                <input class="cfg-input dsp-num" id="txtCentro" type="number" value="5" style="width:64px">
                <span class="cfg-field-label">Superior</span>
              </div>
              <div style="display:flex;align-items:center;gap:12px">
                <span class="cfg-field-label" style="min-width:40px">Der</span>
                <input class="cfg-input dsp-num" id="txtDer" type="number" value="140" style="width:64px">
                <span class="cfg-field-label">Ancho</span>
              </div>
            </div>
          </div>

          {{-- Logotipo --}}
          <label class="cfg-chk-item">
            <input type="checkbox" id="textoLogo"> Logotipo
          </label>

          {{-- % Transferencia --}}
          <div class="cfg-field-label">% de Transferencia</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:2px">
            <input class="cfg-input dsp-num" id="txtTrans1" type="number" value="500">
            <input class="cfg-input dsp-num" id="txtTrans2" type="number" value="500">
            <input class="cfg-input dsp-num" id="txtTrans3" type="number" value="100">
            <input class="cfg-input dsp-num" id="txtTrans4" type="number" value="100">
          </div>

        </div>

        {{-- Col 2: Vista Previa --}}
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="cfg-section-title">Vista Previa</div>
          <div class="txt-preview-box">
            <span id="txtPreviewContent"
              style="font-family:'Arial',sans-serif;font-size:14px;color:var(--txt);line-height:1.9">
              Paciente: Maria Gonzalez<br>
              Fecha: 30/05/2026<br>
              Medico:<br>
              Procedimiento
            </span>
          </div>
        </div>

        {{-- Col 3: Tipografia --}}
        <div style="display:flex;flex-direction:column;gap:14px;position:relative">
          <div class="cfg-section-title">Tipografía</div>

          {{-- Botón fuente --}}
          <div style="position:relative">
            <button class="btn-fuente" id="btnFuente" type="button">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex:none"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>
              Seleccionar Fuente
            </button>
            <div class="fuente-dropdown" id="fuenteDropdown">
              <div class="fuente-search-wrap">
                <input class="cfg-input" id="fuenteBuscar" type="text" placeholder="Buscar fuente..." autocomplete="off">
              </div>
              <ul class="fuente-list" id="fuenteList"></ul>
            </div>
          </div>

          {{-- Fuente seleccionada --}}
          <div class="txt-card">
            <div class="cfg-field-label">Fuente activa</div>
            <div id="fuenteSelNombre"
              style="font-size:15px;font-weight:700;color:var(--txt);margin-top:5px">Arial</div>
          </div>

          {{-- Tamaño --}}
          <div class="txt-card">
            <div class="cfg-field-label" style="margin-bottom:8px">Tamaño (px)</div>
            <div style="display:flex;align-items:center;gap:10px">
              <input class="cfg-input dsp-num" id="txtSize" type="number" value="14" min="6" max="72" style="width:64px">
              <input type="range" id="txtSizeRange" min="6" max="72" value="14"
                style="flex:1;accent-color:var(--blue)">
            </div>
          </div>

        </div>

      </div>

      {{-- Audio --}}
      <div class="tab-panel" id="tab-audio" style="grid-template-columns:1fr 1fr 1fr;gap:22px;align-items:start">

        {{-- Col 1: Dispositivo de Audio --}}
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="cfg-section-title">Dispositivo de Audio</div>

          {{-- Audio Input --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:5px">Audio Input</div>
            <select class="cfg-select">
              <option>Micrófono integrado</option>
              <option>USB Audio</option>
              <option>Entrada de línea</option>
              <option>Sin audio</option>
            </select>
          </div>

          {{-- Audio Inputs + Mono --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:5px">Audio Inputs</div>
            <div style="display:flex;align-items:center;gap:10px">
              <select class="cfg-select" style="flex:1">
                <option>1</option><option>2</option><option>4</option>
              </select>
              <label class="cfg-chk-item" style="white-space:nowrap">
                <input type="checkbox" id="audioMono"> mono
              </label>
            </div>
          </div>

          {{-- Calidad --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:5px">Calidad</div>
            <select class="cfg-select">
              <option>Alta</option>
              <option>Media</option>
              <option>Baja</option>
            </select>
          </div>

          {{-- Nivel de Audio --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:5px">Nivel de Audio</div>
            <input type="range" min="0" max="100" value="75"
              style="width:100%;accent-color:var(--blue)">
          </div>

          {{-- Balance de Audio --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:5px">Balance de Audio</div>
            <input type="range" min="-50" max="50" value="0"
              style="width:100%;accent-color:var(--blue)">
          </div>
        </div>

        {{-- Col 2: Audio Renderer --}}
        <div style="display:flex;flex-direction:column;gap:14px">
          <div class="cfg-section-title">Audio Renderer</div>

          {{-- Mute --}}
          <label class="cfg-chk-item">
            <input type="checkbox" id="audioMute"> mute
          </label>

          {{-- Volumen --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:6px">Volumen</div>
            <input type="range" id="audioVolRange" min="0" max="100" value="80"
              style="width:100%;accent-color:var(--blue)">
          </div>

          {{-- Balance --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:6px">Balance</div>
            <input type="range" id="audioBalRange" min="-50" max="50" value="0"
              style="width:100%;accent-color:var(--blue)">
          </div>
        </div>

        {{-- Col 3: Sonido al Capturar --}}
        <div style="display:flex;flex-direction:column;gap:14px">
          <div class="cfg-section-title">Sonido al Capturar</div>

          <label class="aud-radio-item">
            <input type="radio" name="audioCaptura" id="audBeep" checked>
            <span class="aud-radio-dot"></span>
            BEEP
          </label>

          <label class="aud-radio-item">
            <input type="radio" name="audioCaptura" id="audWav">
            <span class="aud-radio-dot"></span>
            Archivo de Sonido (WAV)
          </label>
        </div>

      </div>

      {{-- Grabación --}}
      <div class="tab-panel" id="tab-grabacion" style="grid-template-columns:1.6fr 1fr 1fr;gap:22px;align-items:start">

        {{-- Col 1: Comprensión + CODEC + Modos --}}
        <div style="display:flex;flex-direction:column;gap:14px">

          <div class="cfg-section-title">Comprensión de Video y Audio</div>

          {{-- CODEC --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:4px">Video</div>
            <div class="cfg-field-label" style="margin-bottom:6px;color:var(--txt)">CODEC de Video</div>
            <div style="display:flex;align-items:center;gap:10px">
              <select class="cfg-select" style="flex:1">
                <option>Datastead Multipurpose Encoder</option>
                <option>H.264 AVC</option>
                <option>H.265 HEVC</option>
                <option>MPEG-4</option>
              </select>
              <button class="btn-tool" style="padding:8px 10px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
              </button>
            </div>
          </div>

          {{-- Tres grupos de radios en fila --}}
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-top:4px">

            {{-- Modo de Conversión --}}
            <div>
              <div class="cfg-field-label" style="margin-bottom:8px">Modo de Conversión</div>
              <div style="display:flex;flex-direction:column;gap:8px">
                <label class="aud-radio-item">
                  <input type="radio" name="grabConv" value="no">
                  <span class="aud-radio-dot"></span> No
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabConv" value="momento" checked>
                  <span class="aud-radio-dot"></span> Al momento
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabConv" value="final">
                  <span class="aud-radio-dot"></span> Al final
                </label>
              </div>
            </div>

            {{-- Tamaño de Video --}}
            <div>
              <div class="cfg-field-label" style="margin-bottom:8px">Tamaño de Video</div>
              <div style="display:flex;flex-direction:column;gap:8px">
                <label class="aud-radio-item">
                  <input type="radio" name="grabSize" value="default" checked>
                  <span class="aud-radio-dot"></span> Default
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabSize" value="half">
                  <span class="aud-radio-dot"></span> Half Size
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabSize" value="quarter">
                  <span class="aud-radio-dot"></span> Quarter Size
                </label>
              </div>
            </div>

            {{-- Tipo --}}
            <div>
              <div class="cfg-field-label" style="margin-bottom:8px">Tipo</div>
              <div style="display:flex;flex-direction:column;gap:8px">
                <label class="aud-radio-item">
                  <input type="radio" name="grabTipo" value="video" checked>
                  <span class="aud-radio-dot"></span> Video
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabTipo" value="audio">
                  <span class="aud-radio-dot"></span> Audio
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabTipo" value="ambos">
                  <span class="aud-radio-dot"></span> Audio + Video
                </label>
              </div>
            </div>

          </div>
        </div>

        {{-- Col 2: Método de Grabación --}}
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="cfg-section-title">Metodo de Grabacion</div>
          <div style="display:flex;flex-direction:column;gap:8px">
            <label class="aud-radio-item">
              <input type="radio" name="grabMetodo" value="avi">
              <span class="aud-radio-dot"></span> AVI
            </label>
            <label class="aud-radio-item">
              <input type="radio" name="grabMetodo" value="mpg" checked>
              <span class="aud-radio-dot"></span> MPG
            </label>
            <label class="aud-radio-item">
              <input type="radio" name="grabMetodo" value="mp4">
              <span class="aud-radio-dot"></span> MP4
            </label>
            <label class="aud-radio-item">
              <input type="radio" name="grabMetodo" value="mov">
              <span class="aud-radio-dot"></span> MOV
            </label>
            <label class="aud-radio-item">
              <input type="radio" name="grabMetodo" value="flv">
              <span class="aud-radio-dot"></span> FLV
            </label>
          </div>
        </div>

        {{-- Col 3: Pausa / Timer / Opciones --}}
        <div style="display:flex;flex-direction:column;gap:12px">
          <div class="cfg-section-title">Pausa / Continuar</div>

          <div style="display:flex;flex-direction:column;gap:6px">
            <div class="cfg-field-label">Grabación con Pausa</div>
            <div class="cfg-field-label">Pausa crea nuevo archivo</div>
          </div>

          <div class="cfg-field-label" style="margin-top:4px">Timer de grabación</div>

          <div style="display:flex;flex-direction:column;gap:8px">
            <label class="aud-radio-item">
              <input type="radio" name="grabTimer" value="pausa">
              <span class="aud-radio-dot"></span> Grabación con Pausa
            </label>
            <div style="display:flex;align-items:center;gap:10px">
              <label class="aud-radio-item" style="flex:1">
                <input type="radio" name="grabTimer" value="nuevo" checked>
                <span class="aud-radio-dot"></span> Pausa crea nuevo archivo
              </label>
              <div style="display:flex;flex-direction:column;align-items:center;min-width:48px">
                <input class="cfg-input dsp-num" type="number" value="30" style="width:52px;text-align:center">
                <span class="cfg-field-label" style="margin-top:2px;font-size:11px">Minutos</span>
              </div>
            </div>
          </div>

          <div style="margin-top:4px">
            <div class="cfg-field-label" style="margin-bottom:8px">Opciones de Grabación</div>
            <label class="cfg-chk-item">
              <input type="checkbox"> record cursor
            </label>
          </div>
        </div>

      </div>

      {{-- Reproducir --}}
      <div class="tab-panel" id="tab-reproducir" style="grid-template-columns:1fr;gap:16px">

        {{-- Encabezado --}}
        <div>
          <div style="font-size:15px;font-weight:700;color:var(--txt);margin-bottom:3px">Controles de Video</div>
          <div class="cfg-field-label">Seleccione de la lista un video para ejecutarlo. Si lo desea, puede tambien capturar fotos.</div>
        </div>

        {{-- Panel principal: lista + toolbar + controles --}}
        <div class="rep-main-panel">

          {{-- Columna izquierda: lista + iconos --}}
          <div class="rep-left">
            <div class="rep-list-box" id="repList">
              <div class="rep-empty" id="repEmpty">Sin videos</div>
            </div>
            <div class="rep-sidebar-icons">
              <button class="rep-side-btn" id="repBtnPlay" title="Reproducir">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
              </button>
              <button class="rep-side-btn" id="repBtnStop" title="Detener">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><rect x="9" y="9" width="6" height="6" fill="currentColor" stroke="none"/></svg>
              </button>
              <button class="rep-side-btn" id="repBtnOpen" title="Agregar video">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
              </button>
              <button class="rep-side-btn rep-side-btn--danger" id="repBtnDel" title="Eliminar seleccionado">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </button>
            </div>
          </div>

          {{-- Panel derecho: Ejecutar-Pausa-Detener --}}
          <div class="rep-ctrl-panel">

            {{-- Título --}}
            <div class="rep-ctrl-title">Ejecutar &ndash; Pausa &ndash; Detener</div>

            {{-- Estado del video --}}
            <div class="rep-status" id="repStatus">Sin video seleccionado</div>

            {{-- Barra de progreso --}}
            <div class="rep-progress-wrap">
              <input type="range" class="rep-progress" id="repProgress" min="0" max="100" value="0">
            </div>

            {{-- Controles principales --}}
            <div class="rep-controls-row">
              {{-- Grupo izq: Stop + Retroceso rápido --}}
              <div class="rep-ctrl-group">
                <button class="rep-ctrl-btn" id="repCtrlStop" title="Detener">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><rect x="9" y="9" width="6" height="6" fill="currentColor" stroke="none"/></svg>
                </button>
                <button class="rep-ctrl-btn" id="repCtrlRew" title="Retroceso rápido">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polygon points="14 8 8 12 14 16 14 8" fill="currentColor" stroke="none"/><line x1="9" y1="8" x2="9" y2="16"/></svg>
                </button>
              </div>

              {{-- Grupo centro: Prev / Play-Pause / Next --}}
              <div class="rep-ctrl-group rep-ctrl-group--main">
                <button class="rep-ctrl-btn rep-ctrl-btn--lg" id="repCtrlPrev" title="Anterior">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polygon points="14 8 8 12 14 16 14 8" fill="currentColor" stroke="none"/></svg>
                </button>
                <button class="rep-ctrl-btn rep-ctrl-btn--play" id="repCtrlPlay" title="Reproducir / Pausar">
                  <svg id="repPlayIcon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
                </button>
                <button class="rep-ctrl-btn rep-ctrl-btn--lg" id="repCtrlNext" title="Siguiente">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
                </button>
              </div>

              {{-- Grupo der: Captura --}}
              <div class="rep-ctrl-group">
                <button class="rep-ctrl-btn" id="repCtrlCapture" title="Capturar foto">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </button>
              </div>
            </div>

          </div>

        </div>

        {{-- Input oculto --}}
        <input type="file" id="repFileInput" accept="video/*" multiple style="display:none">

      </div>

    </div>
  </div>

@endsection

@push('scripts')
<script>
(function () {

  /* ── Tabs ── */
  const tabs   = document.querySelectorAll('.cfg-tab');
  const panels = document.querySelectorAll('.tab-panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
    });
  });


  /* ── Selector de fuentes ── */
  const FUENTES = [
    'Arial','Arial Black','Arial Narrow','Calibri','Cambria','Century Gothic',
    'Comic Sans MS','Consolas','Courier New','Franklin Gothic Medium',
    'Georgia','Gill Sans','Impact','Lucida Console','Lucida Sans Unicode',
    'Microsoft Sans Serif','Palatino Linotype','Segoe UI','Tahoma',
    'Times New Roman','Trebuchet MS','Verdana',
    'Helvetica','Garamond','Book Antiqua','Bookman Old Style',
    'Candara','Constantia','Corbel','Didact Gothic','EB Garamond',
    'Open Sans','Roboto','Lato','Montserrat','Poppins','Raleway',
    'Source Sans Pro','Ubuntu','Noto Sans','Merriweather','Playfair Display'
  ].sort();

  const btnFuente      = document.getElementById('btnFuente');
  const fuenteDropdown = document.getElementById('fuenteDropdown');
  const fuenteBuscar   = document.getElementById('fuenteBuscar');
  const fuenteList     = document.getElementById('fuenteList');
  const fuenteSelNom   = document.getElementById('fuenteSelNombre');
  const txtPreview     = document.getElementById('txtPreviewContent');
  const txtSize        = document.getElementById('txtSize');

  let fuenteActual = 'Arial';

  function renderFuentes(filtro) {
    fuenteList.innerHTML = '';
    FUENTES
      .filter(f => f.toLowerCase().includes(filtro.toLowerCase()))
      .forEach(f => {
        const li = document.createElement('li');
        li.textContent = f;
        li.style.fontFamily = f;
        if (f === fuenteActual) li.classList.add('active');
        li.addEventListener('click', () => {
          fuenteActual = f;
          fuenteSelNom.textContent = f;
          fuenteSelNom.style.fontFamily = f;
          if (txtPreview) txtPreview.style.fontFamily = f;
          fuenteDropdown.classList.remove('open');
          fuenteBuscar.value = '';
          renderFuentes('');
        });
        fuenteList.appendChild(li);
      });
  }

  renderFuentes('');

  if (btnFuente) {
    btnFuente.addEventListener('click', e => {
      e.stopPropagation();
      fuenteDropdown.classList.toggle('open');
      if (fuenteDropdown.classList.contains('open')) {
        fuenteBuscar.focus();
        /* Scroll al activo */
        const act = fuenteList.querySelector('.active');
        if (act) act.scrollIntoView({ block: 'center' });
      }
    });
  }

  if (fuenteBuscar) {
    fuenteBuscar.addEventListener('input', () => renderFuentes(fuenteBuscar.value));
  }

  /* Cerrar al clic fuera */
  document.addEventListener('click', e => {
    if (fuenteDropdown && !fuenteDropdown.contains(e.target) && e.target !== btnFuente) {
      fuenteDropdown.classList.remove('open');
    }
  });

  /* ── Reproducir: reproductor completo ── */
  const repList      = document.getElementById('repList');
  const repEmpty     = document.getElementById('repEmpty');
  const repStatus    = document.getElementById('repStatus');
  const repProgress  = document.getElementById('repProgress');
  const repPlayIcon  = document.getElementById('repPlayIcon');
  const repFileInput = document.getElementById('repFileInput');

  const repBtnOpen   = document.getElementById('repBtnOpen');
  const repBtnDel    = document.getElementById('repBtnDel');
  const repBtnPlay   = document.getElementById('repBtnPlay');   /* sidebar */
  const repBtnStop   = document.getElementById('repBtnStop');   /* sidebar */
  const repCtrlPlay  = document.getElementById('repCtrlPlay');  /* centro */
  const repCtrlStop  = document.getElementById('repCtrlStop');
  const repCtrlRew   = document.getElementById('repCtrlRew');
  const repCtrlPrev  = document.getElementById('repCtrlPrev');
  const repCtrlNext  = document.getElementById('repCtrlNext');
  const repCtrlCapture = document.getElementById('repCtrlCapture');

  let repSelected  = null;   /* elemento DOM seleccionado */
  let repPlaying   = false;
  let repTimer     = null;
  let repProgressV = 0;
  const PLAY_SVG   = `<circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/>`;
  const PAUSE_SVG  = `<circle cx="12" cy="12" r="10"/><line x1="10" y1="8" x2="10" y2="16"/><line x1="14" y1="8" x2="14" y2="16"/>`;

  /* Actualiza visibilidad del placeholder */
  function updateEmpty() {
    if (repEmpty) repEmpty.style.display = repList.querySelectorAll('.rep-list-item').length ? 'none' : 'block';
  }

  /* Selecciona un item */
  function selectItem(el) {
    repList.querySelectorAll('.rep-list-item').forEach(i => i.classList.remove('sel'));
    el.classList.add('sel');
    repSelected = el;
    if (repStatus) repStatus.textContent = '▶ ' + el.dataset.name;
  }

  /* Agrega un item a la lista */
  function repAddItem(name) {
    const div = document.createElement('div');
    div.className = 'rep-list-item';
    div.dataset.name = name;
    div.innerHTML = name;
    div.addEventListener('click', () => { stopPlayback(); selectItem(div); });
    div.addEventListener('dblclick', () => { selectItem(div); startPlayback(); });
    repList.appendChild(div);
    updateEmpty();
    if (!repSelected) selectItem(div);
  }

  /* Inicia reproducción simulada */
  function startPlayback() {
    if (!repSelected) return;
    repPlaying = true;
    repList.querySelectorAll('.rep-list-item').forEach(i => i.classList.remove('playing'));
    repSelected.classList.add('playing');
    if (repPlayIcon) repPlayIcon.innerHTML = PAUSE_SVG;
    if (repStatus)  repStatus.textContent = '⏵ Reproduciendo: ' + repSelected.dataset.name;
    clearInterval(repTimer);
    repTimer = setInterval(() => {
      repProgressV = Math.min(100, repProgressV + 0.5);
      if (repProgress) repProgress.value = repProgressV;
      if (repProgressV >= 100) { stopPlayback(); repProgressV = 0; }
    }, 100);
  }

  /* Pausa */
  function pausePlayback() {
    repPlaying = false;
    clearInterval(repTimer);
    if (repPlayIcon) repPlayIcon.innerHTML = PLAY_SVG;
    if (repStatus && repSelected) repStatus.textContent = '⏸ Pausado: ' + repSelected.dataset.name;
  }

  /* Detiene y resetea */
  function stopPlayback() {
    repPlaying = false;
    clearInterval(repTimer);
    repProgressV = 0;
    if (repProgress) repProgress.value = 0;
    if (repPlayIcon) repPlayIcon.innerHTML = PLAY_SVG;
    repList.querySelectorAll('.rep-list-item').forEach(i => i.classList.remove('playing'));
    if (repStatus) repStatus.textContent = repSelected ? repSelected.dataset.name : 'Sin video seleccionado';
  }

  /* Navegar lista */
  function navItem(dir) {
    const items = Array.from(repList.querySelectorAll('.rep-list-item'));
    if (!items.length) return;
    const idx = repSelected ? items.indexOf(repSelected) : -1;
    const next = items[(idx + dir + items.length) % items.length];
    stopPlayback();
    selectItem(next);
  }

  /* Botones abrir / sidebar */
  if (repBtnOpen)  repBtnOpen.addEventListener('click', () => repFileInput && repFileInput.click());
  if (repFileInput) {
    repFileInput.addEventListener('change', () => {
      Array.from(repFileInput.files).forEach(f => repAddItem(f.name));
      repFileInput.value = '';
    });
  }
  if (repBtnDel) {
    repBtnDel.addEventListener('click', () => {
      if (!repSelected) return;
      const wasPlaying = repPlaying;
      stopPlayback();
      const items = Array.from(repList.querySelectorAll('.rep-list-item'));
      const idx = items.indexOf(repSelected);
      repSelected.remove();
      repSelected = null;
      updateEmpty();
      const remaining = repList.querySelectorAll('.rep-list-item');
      if (remaining.length) {
        selectItem(remaining[Math.min(idx, remaining.length - 1)]);
        if (wasPlaying) startPlayback();
      } else {
        if (repStatus) repStatus.textContent = 'Sin video seleccionado';
        if (repProgress) repProgress.value = 0;
      }
    });
  }

  /* Sidebar play/stop */
  if (repBtnPlay) repBtnPlay.addEventListener('click', () => repPlaying ? pausePlayback() : startPlayback());
  if (repBtnStop) repBtnStop.addEventListener('click', stopPlayback);

  /* Controles centrales */
  if (repCtrlPlay) repCtrlPlay.addEventListener('click', () => repPlaying ? pausePlayback() : startPlayback());
  if (repCtrlStop) repCtrlStop.addEventListener('click', stopPlayback);
  if (repCtrlRew)  repCtrlRew.addEventListener('click', () => { repProgressV = Math.max(0, repProgressV - 10); if (repProgress) repProgress.value = repProgressV; });
  if (repCtrlPrev) repCtrlPrev.addEventListener('click', () => navItem(-1));
  if (repCtrlNext) repCtrlNext.addEventListener('click', () => navItem(1));

  /* Captura foto */
  if (repCtrlCapture) {
    repCtrlCapture.addEventListener('click', () => {
      const ts = new Date().toLocaleTimeString();
      if (repStatus) repStatus.textContent = `📷 Foto capturada ${ts}`;
      setTimeout(() => {
        if (repStatus && repSelected) repStatus.textContent = (repPlaying ? '⏵ Reproduciendo: ' : '') + repSelected.dataset.name;
      }, 2000);
    });
  }

  /* Scrub manual */
  if (repProgress) {
    repProgress.addEventListener('input', () => { repProgressV = +repProgress.value; });
  }

  updateEmpty();

  /* Tamaño en tiempo real — sincronización bidireccional */
  const txtSizeRange = document.getElementById('txtSizeRange');
  if (txtSize && txtPreview) {
    txtSize.addEventListener('input', () => {
      txtPreview.style.fontSize = txtSize.value + 'px';
      if (txtSizeRange) txtSizeRange.value = txtSize.value;
    });
    if (txtSizeRange) {
      txtSizeRange.addEventListener('input', () => {
        txtSize.value = txtSizeRange.value;
        txtPreview.style.fontSize = txtSizeRange.value + 'px';
      });
    }
  }

  /* ═════════════════════════════════════════════════════════════
     MODAL DE MÁS OPCIONES - FUNCIONALIDAD
     ═════════════════════════════════════════════════════════════ */

  /* Elementos del modal */
  const btnMasOpciones = document.getElementById('btnMasOpciones');
  const masOpcionesModal = document.getElementById('masOpcionesModal');
  const cerrarMasOpciones = document.getElementById('cerrarMasOpciones');
  const canalBtns = document.querySelectorAll('.canal-btn');
  const areaCapturaSelect = document.getElementById('areaCapturaSelect');
  const canalVideoSelect = document.getElementById('canalVideoSelect');

  /* Iconos funcionales */
  const iconStop = document.getElementById('iconStop');
  const iconVideo = document.getElementById('iconVideo');
  const iconFilm = document.getElementById('iconFilm');
  const iconFilmStrip = document.getElementById('iconFilmStrip');
  const iconCrop = document.getElementById('iconCrop');
  const iconSettings = document.getElementById('iconSettings');
  const iconCamera = document.getElementById('iconCamera');

  let isRecording = false;

  /* Abrir modal */
  if (btnMasOpciones && masOpcionesModal) {
    btnMasOpciones.addEventListener('click', () => {
      masOpcionesModal.classList.add('active');
    });
  }

  /* Cerrar modal */
  if (cerrarMasOpciones && masOpcionesModal) {
    cerrarMasOpciones.addEventListener('click', () => {
      masOpcionesModal.classList.remove('active');
    });
  }

  /* Cerrar al hacer clic fuera del contenido */
  if (masOpcionesModal) {
    masOpcionesModal.addEventListener('click', (e) => {
      if (e.target === masOpcionesModal) {
        masOpcionesModal.classList.remove('active');
      }
    });
  }

  /* Selección de canales 1-6 */
  canalBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      canalBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const canal = btn.dataset.canal;
      console.log('Canal seleccionado:', canal);

      /* Actualizar el log con el canal seleccionado */
      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.className = 'hl';
        span.textContent = `[CANAL] Cambiado a canal ${canal}`;
        log.insertBefore(span, log.firstChild);
      }
    });
  });

  /* Cambio de área de captura */
  if (areaCapturaSelect) {
    areaCapturaSelect.addEventListener('change', (e) => {
      const area = e.target.value;
      console.log('Área de captura:', area);

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.textContent = `[AREA] ${area === 'full' ? 'Pantalla Completa' : area === 'window' ? 'Ventana Activa' : 'Región Personalizada'}`;
        log.insertBefore(span, log.firstChild);
      }
    });
  }

  /* Cambio de canal de video */
  if (canalVideoSelect) {
    canalVideoSelect.addEventListener('change', (e) => {
      const canal = e.target.value;
      console.log('Canal de video:', canal);

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.textContent = `[VIDEO] Canal ${canal} seleccionado`;
        log.insertBefore(span, log.firstChild);
      }
    });
  }

  /* Icono STOP - Detener grabación */
  if (iconStop) {
    iconStop.addEventListener('click', () => {
      isRecording = false;
      iconVideo.classList.remove('recording');
      console.log('Grabación detenida');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.className = 'hl';
        span.textContent = '[STOP] Grabación detenida';
        log.insertBefore(span, log.firstChild);
      }
    });
  }

  /* Icono VIDEO - Iniciar/Detener grabación */
  if (iconVideo) {
    iconVideo.addEventListener('click', () => {
      isRecording = !isRecording;

      if (isRecording) {
        iconVideo.classList.add('recording');
        console.log('Grabación iniciada');

        const log = document.querySelector('.cfg-log');
        if (log) {
          const span = document.createElement('span');
          span.className = 'hl';
          span.textContent = '[RECORD] Grabación iniciada';
          log.insertBefore(span, log.firstChild);
        }

        /* Simular grabación - agregar entrada al log cada 5 segundos */
        window.recordingInterval = setInterval(() => {
          if (isRecording && log) {
            const span = document.createElement('span');
            span.textContent = `[RECORD] Grabando... ${new Date().toLocaleTimeString()}`;
            log.insertBefore(span, log.firstChild);
          }
        }, 5000);

      } else {
        iconVideo.classList.remove('recording');
        console.log('Grabación detenida');

        if (window.recordingInterval) {
          clearInterval(window.recordingInterval);
        }

        const log = document.querySelector('.cfg-log');
        if (log) {
          const span = document.createElement('span');
          span.className = 'hl';
          span.textContent = '[RECORD] Grabación finalizada';
          log.insertBefore(span, log.firstChild);
        }
      }
    });
  }

  /* Icono FILM - Modo película */
  if (iconFilm) {
    iconFilm.addEventListener('click', () => {
      console.log('Modo película activado');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.className = 'hl';
        span.textContent = '[FILM] Modo película activado';
        log.insertBefore(span, log.firstChild);
      }

      /* Efecto visual en el preview */
      const videoWrap = document.querySelector('.cfg-video-wrap');
      if (videoWrap) {
        videoWrap.style.border = '2px solid var(--cyan)';
        setTimeout(() => {
          videoWrap.style.border = '2px solid var(--blue)';
        }, 1000);
      }
    });
  }

  /* Icono FILMSTRIP - Tira de fotos */
  if (iconFilmStrip) {
    iconFilmStrip.addEventListener('click', () => {
      console.log('Tira de fotos');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.className = 'hl';
        span.textContent = '[FILMSTRIP] Captura múltiple iniciada';
        log.insertBefore(span, log.firstChild);
      }

      /* Simular captura múltiple */
      let count = 0;
      const interval = setInterval(() => {
        count++;
        if (log && count <= 3) {
          const span = document.createElement('span');
          span.textContent = `[CAPTURE] Foto ${count}/3 capturada`;
          log.insertBefore(span, log.firstChild);
        }
        if (count >= 3) clearInterval(interval);
      }, 800);
    });
  }

  /* Icono CROP - Recortar área */
  if (iconCrop) {
    iconCrop.addEventListener('click', () => {
      console.log('Recortar área');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.textContent = '[CROP] Modo recorte activado - Seleccione área';
        log.insertBefore(span, log.firstChild);
      }

      /* Cambiar a tab Display para mostrar opciones de recorte */
      const displayTab = document.querySelector('[data-tab="display"]');
      if (displayTab) {
        displayTab.click();
      }

      /* Cerrar modal */
      masOpcionesModal.classList.remove('active');
    });
  }

  /* Icono SETTINGS - Configuración avanzada */
  if (iconSettings) {
    iconSettings.addEventListener('click', () => {
      console.log('Configuración avanzada');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.textContent = '[SETTINGS] Abriendo configuración avanzada...';
        log.insertBefore(span, log.firstChild);
      }

      /* Cambiar a tab Grabación para mostrar más opciones */
      const grabacionTab = document.querySelector('[data-tab="grabacion"]');
      if (grabacionTab) {
        grabacionTab.click();
      }

      /* Cerrar modal */
      masOpcionesModal.classList.remove('active');
    });
  }

  /* Icono CAMERA - Capturar foto */
  if (iconCamera) {
    iconCamera.addEventListener('click', () => {
      console.log('Capturar foto');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const timestamp = new Date().toISOString().slice(0,19).replace(/[:T]/g,'-');
        const span = document.createElement('span');
        span.className = 'hl';
        span.textContent = `[PHOTO] Capturada: MariaGonzalez-${timestamp}.JPG`;
        log.insertBefore(span, log.firstChild);
      }

      /* Efecto flash en el preview */
      const videoWrap = document.querySelector('.cfg-video-wrap');
      if (videoWrap) {
        const flash = document.createElement('div');
        flash.style.cssText = 'position:absolute;inset:0;background:#fff;opacity:.7;pointer-events:none;z-index:100;';
        videoWrap.appendChild(flash);
        setTimeout(() => flash.remove(), 150);
      }
    });
  }

  /* Cerrar modal con tecla ESC */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && masOpcionesModal && masOpcionesModal.classList.contains('active')) {
      masOpcionesModal.classList.remove('active');
    }
  });

})();
</script>
@endpush
