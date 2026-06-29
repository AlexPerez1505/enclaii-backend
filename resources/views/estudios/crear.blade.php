@extends('layouts.app')

@section('title', request()->has('name') ? 'Estudios' : 'Nuevo Estudio')
@section('active', 'nuevo-estudio')
@section('header-title', request()->has('name') ? 'Estudios' : 'Nuevo Estudio')
@section('header-sub')
  {{ request()->has('name') ? request()->query('name') : 'Datos nuevos' }}
@endsection

@push('styles')
<style>
/* ============ NUEVO PACIENTE ============ */

/* Tabs de navegacion */
.np-tabs {
  display: flex;
  gap: 24px;
  margin-bottom: 20px;
  border-bottom: 1px solid rgba(110,160,255,.1);
  padding-bottom: 0;
}
.np-tab {
  font-size: 14px;
  font-weight: 600;
  color: var(--txt-soft);
  text-decoration: none;
  padding-bottom: 10px;
  border-bottom: 2px solid transparent;
  transition: color 150ms, border-color 150ms;
}
.np-tab:hover { color: var(--txt); }
.np-tab.active {
  color: var(--txt);
  border-bottom-color: var(--blue);
}

/* Boton volver a pacientes */
.np-volver-btn {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 12.5px; font-weight: 600; color: var(--txt);
  text-decoration: none;
  padding: 8px 14px;
  border-radius: 8px;
  border: 1px solid rgba(110,160,255,.2);
  background: rgba(110,160,255,.06);
  transition: background 150ms, border-color 150ms;
  white-space: nowrap;
}
.np-volver-btn:hover { background: rgba(110,160,255,.12); border-color: rgba(110,160,255,.35); }
.np-volver-btn svg { flex: none; }

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

/* Layout: foto + campos (legacy alias) */
.np-personal-layout {
  display: grid;
  grid-template-columns: 190px 1fr;
  gap: 24px;
  align-items: start;
}

/* Foto */
.np-foto-col {
  display: flex; flex-direction: column; align-items: center; gap: 10px;
}
.np-foto-box {
  width: 170px; height: 170px;
  background: var(--card-bg-2);
  border-radius: 12px;
  border: 1px solid var(--stroke-strong);
  display: grid; place-items: center;
  overflow: hidden; cursor: pointer;
  transition: border-color 150ms;
}
.np-foto-box:hover { border-color: var(--blue); }
.np-foto-box img { width: 100%; height: 100%; object-fit: cover; display: none; }
.np-foto-ph {
  display: flex; flex-direction: column; align-items: center; gap: 8px;
  color: var(--txt-soft);
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

/* Sub-header dentro de card */
.np-sub-header {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .08em;
  color: var(--cyan);
  margin-bottom: 12px;
}

/* Layout info paciente: foto | personal | medica */
.np-info-layout {
  display: grid;
  grid-template-columns: auto 1fr auto;
  gap: 24px;
  align-items: start;
}
@media(max-width:900px) {
  .np-info-layout { grid-template-columns: 1fr; }
}

/* Inline fields (label: valor) */
.np-inline-fields { display: flex; flex-direction: column; gap: 6px; }
.np-inline-row {
  display: grid;
  grid-template-columns: auto 1fr auto 1fr;
  gap: 4px 8px;
  align-items: baseline;
}
.np-info-medica .np-inline-row {
  grid-template-columns: auto 1fr;
}
.np-lbl {
  font-size: 12px;
  font-weight: 600;
  color: #7a8fc0;
  white-space: nowrap;
}
.np-val {
  font-size: 14px;
  font-weight: 700;
  color: var(--txt);
}

/* Tabla historial */
.np-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}
.np-table thead th {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .05em;
  color: #7a8fc0;
  padding: 10px 12px;
  text-align: left;
  border-bottom: 1px solid rgba(110,160,255,.12);
}
.np-table tbody td {
  padding: 12px;
  color: var(--txt);
  border-bottom: 1px solid rgba(110,160,255,.06);
  vertical-align: middle;
}
.np-table tbody tr:hover { background: rgba(110,160,255,.04); }
.np-archivos {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 12.5px; color: var(--txt-soft);
}
.np-btn-ver {
  padding: 5px 12px;
  border-radius: 6px;
  border: 1px solid rgba(110,160,255,.25);
  background: rgba(110,160,255,.08);
  font: inherit; font-size: 12px; font-weight: 600;
  color: var(--txt);
  cursor: pointer;
  transition: background 150ms;
}
.np-btn-ver:hover { background: rgba(110,160,255,.15); }
.np-btn-dots {
  background: none; border: none;
  font-size: 18px; color: var(--txt-soft);
  cursor: pointer; padding: 2px 6px;
  vertical-align: middle;
}
.np-btn-dots:hover { color: var(--txt); }

/* Footer tabla + paginacion */
.np-table-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 0 0;
  font-size: 12.5px;
  color: var(--txt-soft);
}
.np-pagination { display: flex; gap: 4px; }
.np-page-btn {
  width: 30px; height: 30px;
  border-radius: 6px;
  border: 1px solid rgba(110,160,255,.15);
  background: none;
  color: var(--txt-soft);
  font: inherit; font-size: 13px; font-weight: 600;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background 150ms, color 150ms;
}
.np-page-btn:hover { background: rgba(110,160,255,.1); color: var(--txt); }
.np-page-btn.active {
  background: var(--blue);
  border-color: var(--blue);
  color: #fff;
}

/* Grid de campos (legacy) */
.np-fields { display: grid; gap: 8px 16px; }
.np-row-3 { grid-template-columns: 1fr 1fr 1fr; }
.np-row-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
.np-row-3b { grid-template-columns: 1fr 2fr 1fr; }
.np-row-2 { grid-template-columns: 1fr 1fr; }
.np-row-1 { grid-template-columns: 1fr; }

.np-field { display: flex; flex-direction: column; gap: 2px; }
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

/* Campos solo lectura */
.np-readonly {
  display: block;
  background: none;
  border: none;
  padding: 0;
  font: inherit; font-size: 14px; font-weight: 600;
  color: var(--txt);
  line-height: 1.3;
}

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

/* Estado vacio */
.np-empty-state {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  gap: 12px; padding: 80px 20px;
  color: var(--txt-soft); text-align: center;
}
.np-empty-state svg { opacity: .25; }
.np-empty-state p { font-size: 16px; font-weight: 600; color: var(--txt-soft); }
.np-empty-state span { font-size: 13px; color: var(--off); }

/* Boton Agendar cita */
.np-agendar-btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 20px; border-radius: 10px;
  border: 1.5px solid var(--cyan);
  background: transparent; font: inherit; font-size: 14px; font-weight: 700;
  color: var(--cyan); cursor: pointer;
  transition: background 150ms, transform 150ms;
}
.np-agendar-btn:hover { background: rgba(56,199,244,.1); }
.np-agendar-btn:active { transform: scale(.97); }

@media (max-width:1100px) {
  .np-layout { grid-template-columns: 1fr; }
  .np-info-layout { grid-template-columns: auto 1fr; }
  .np-info-medica { grid-column: 1 / -1; margin-top: 16px; }
}
@media (max-width:640px) {
  .np-info-layout { grid-template-columns: 1fr; }
  .np-inline-row { grid-template-columns: auto 1fr; }
}

/* ================= TEMA CLARO (overrides especificos) ================= */
html[data-theme="light"] .np-res-av { color: #fff; }
html[data-theme="light"] .np-field input:focus,
html[data-theme="light"] .np-field select:focus,
html[data-theme="light"] .np-field textarea:focus {
  box-shadow: 0 0 0 3px rgba(46,123,246,.12);
}
html[data-theme="light"] .np-ab-icon { background: rgba(56,199,244,.1); }
html[data-theme="light"] .np-agendar-btn:hover { background: rgba(56,199,244,.1); }
html[data-theme="light"] .np-media-card:hover { border-color: rgba(46,123,246,.45); }
html[data-theme="light"] .np-media-thumb { background: var(--panel-2); }
html[data-theme="light"] .np-media-thumb .badge { color: #fff; }
html[data-theme="light"] .np-media-thumb .duration { background: rgba(0,0,0,.55); color: #fff; }
html[data-theme="light"] .np-media-thumb .play { background: rgba(0,0,0,.18); color: #fff; }
html[data-theme="light"] .np-media-actions a:hover,
html[data-theme="light"] .np-media-actions button:hover { background: rgba(46,123,246,.12); }
html[data-theme="light"] .np-media-actions a.primary,
html[data-theme="light"] .np-media-actions button.primary { color: #fff; }
html[data-theme="light"] .np-media-actions a.primary:hover,
html[data-theme="light"] .np-media-actions button.primary:hover { background: #255fd1; }
html[data-theme="light"] .pa-back:hover { background: rgba(46,123,246,.08); }
html[data-theme="light"] .pa-avatar { color: #fff; }
html[data-theme="light"] .pa-card:hover { border-color: rgba(46,123,246,.45); }
html[data-theme="light"] .pa-thumb { background: radial-gradient(ellipse at 50% 50%,#f1e8ff 0%,#e8f0ff 64%,#eef2fb 100%); }
html[data-theme="light"] .pa-play { background: rgba(255,255,255,.22); }
html[data-theme="light"] .pa-play span { background: rgba(255,255,255,.28); }
html[data-theme="light"] .pa-badge { color: #fff; }
html[data-theme="light"] .pa-duration { background: rgba(0,0,0,.55); color: #fff; }
html[data-theme="light"] .pa-btn:hover { border-color: rgba(46,123,246,.45); }
html[data-theme="light"] .pa-btn.primary { background: rgba(46,123,246,.14); border-color: rgba(46,123,246,.35); }
html[data-theme="light"] .pa-btn.primary:hover { background: rgba(46,123,246,.22); }

/* Estilos para recuadros de información */
.np-info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 6px;
  width: 100%;
}
.np-info-box {
  background: rgba(var(--card-rgb), 0.3);
  border: 1px solid rgba(var(--stroke-rgb), 0.5);
  border-radius: 6px;
  padding: 6px;
  transition: all 200ms ease;
  cursor: default;
}
.np-info-box:hover {
  background: rgba(var(--card-rgb), 0.4);
  border-color: rgba(var(--stroke-rgb), 0.7);
}
.np-info-box.np-wide {
  grid-column: 1 / -1;
}
.np-info-box label {
  display: block;
  font-size: 9px;
  font-weight: 600;
  color: var(--txt-soft);
  text-transform: uppercase;
  letter-spacing: 0.4px;
  margin-bottom: 3px;
}
.np-info-box .np-field-value {
  font-size: 13px;
  color: var(--txt);
  line-height: 1.2;
  pointer-events: none;
}
html[data-theme="light"] .np-info-box {
  background: rgba(248, 250, 252, 0.7);
  border-color: rgba(226, 232, 240, 0.8);
}
html[data-theme="light"] .np-info-box:hover {
  background: rgba(241, 245, 249, 0.8);
  border-color: rgba(203, 213, 225, 0.9);
}
html[data-theme="light"] .np-info-box label {
  color: #64748b;
}
html[data-theme="light"] .np-info-box .np-field-value {
  color: #475569;
}
/* ===== REPORTE CLINICO ===== */
.rpt-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 0 16px;flex-wrap:wrap;}
.rpt-toolbar-left{display:flex;align-items:center;gap:12px;}
.rpt-toolbar-right{display:flex;align-items:center;gap:8px;}
.rpt-pat-chip{display:flex;align-items:center;gap:10px;}
.rpt-pat-av{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#c084fc,#a78bfa);display:grid;place-items:center;font-size:11.5px;font-weight:800;color:#fff;flex:none;}
.rpt-pat-name{font-size:13.5px;font-weight:700;color:var(--txt);}
.rpt-pat-id{font-size:11px;color:var(--txt-soft);margin-top:1px;}
.rpt-badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:rgba(245,158,45,.12);color:var(--orange);border:1px solid rgba(245,158,45,.25);}
.rpt-act-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:9px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);font:inherit;font-size:12.5px;font-weight:600;cursor:pointer;white-space:nowrap;text-decoration:none;transition:all 150ms;}
.rpt-act-btn:hover{border-color:var(--stroke-strong);color:var(--txt);}
.rpt-act-btn.primary{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.35);color:var(--blue);}
.rpt-act-btn.primary:hover{background:rgba(46,123,246,.2);}
.rpt-act-btn.accent{background:rgba(56,199,244,.1);border-color:rgba(56,199,244,.3);color:var(--cyan);}
.rpt-act-btn.accent:hover{background:rgba(56,199,244,.18);}
.rpt-doc-wrap{display:flex;justify-content:center;padding-bottom:32px;}
.rpt-doc{background:var(--panel);border:1px solid var(--stroke);border-radius:16px;width:100%;max-width:820px;padding:40px 48px;box-shadow:0 8px 40px rgba(0,0,0,.18);}
.rpt-doc-header{display:flex;align-items:flex-start;justify-content:space-between;gap:24px;margin-bottom:24px;}
.rpt-doc-logo{display:flex;align-items:center;gap:12px;}
.rpt-logo-icon{width:44px;height:44px;border-radius:12px;background:rgba(46,123,246,.12);border:1px solid rgba(46,123,246,.25);display:grid;place-items:center;flex:none;}
.rpt-logo-name{font-size:18px;font-weight:800;color:var(--txt);letter-spacing:-.02em;}
.rpt-logo-sub{font-size:11px;color:var(--txt-soft);margin-top:2px;}
.rpt-doc-meta{display:flex;flex-direction:column;gap:5px;text-align:right;}
.rpt-meta-row{display:flex;align-items:center;gap:8px;justify-content:flex-end;font-size:12px;}
.rpt-meta-row span{color:var(--txt-soft);}
.rpt-meta-row strong{color:var(--txt);}
.rpt-estado-txt{color:var(--orange);}
.rpt-divider{height:1px;background:var(--stroke);margin:22px 0;}
.rpt-section-title{font-size:10.5px;font-weight:800;color:var(--txt-soft);text-transform:uppercase;letter-spacing:.09em;margin-bottom:14px;}
.rpt-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:4px;}
.rpt-field-view{display:flex;flex-direction:column;gap:3px;}
.rpt-field-view span{font-size:10.5px;color:var(--txt-soft);}
.rpt-field-view strong{font-size:13.5px;color:var(--txt);}
.rpt-imgs-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:4px;}
.rpt-img-item{display:flex;flex-direction:column;gap:6px;}
.rpt-img-ph{aspect-ratio:4/3;border-radius:10px;background:var(--panel-2);border:1px solid var(--stroke);display:grid;place-items:center;color:var(--txt-soft);}
.rpt-img-label{font-size:10.5px;color:var(--txt-soft);text-align:center;}
.rpt-text-block{font-size:13.5px;line-height:1.8;color:var(--txt);background:var(--panel-2);border:1px solid var(--stroke);border-radius:10px;padding:14px 18px;margin-bottom:4px;}
.rpt-firma-row{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;padding-top:10px;}
.rpt-firma-box{display:flex;flex-direction:column;gap:6px;min-width:200px;}
.rpt-firma-line{height:1px;background:var(--stroke-strong);margin-bottom:2px;}
.rpt-firma-name{font-size:14px;font-weight:700;color:var(--txt);}
.rpt-firma-cargo{font-size:11px;color:var(--txt-soft);}
.rpt-sello-box{display:flex;justify-content:flex-end;}
.rpt-sello{display:flex;align-items:center;gap:8px;padding:10px 16px;border-radius:12px;border:1px solid rgba(46,123,246,.25);background:rgba(46,123,246,.06);}
.rpt-sello-txt{font-size:12px;font-weight:800;color:var(--blue);line-height:1.3;}
.rpt-sello-txt span{font-size:10px;font-weight:600;color:var(--txt-soft);}
html[data-theme="light"] .rpt-doc{background:#fff;border-color:#e2e8f0;box-shadow:0 8px 40px rgba(0,0,0,.08);}
html[data-theme="light"] .rpt-text-block{background:#f8fafc;border-color:#e2e8f0;}
html[data-theme="light"] .rpt-img-ph{background:#f1f5f9;border-color:#e2e8f0;}
html[data-theme="light"] .rpt-sello{background:rgba(46,123,246,.05);border-color:rgba(46,123,246,.2);}
/* ===== Documento estilo editor (mismo formato que el reporte real) ===== */
.rptd-doc{background:var(--panel);border:1px solid var(--stroke);border-radius:16px;width:100%;max-width:820px;padding:34px 40px;box-shadow:0 8px 40px rgba(0,0,0,.18);line-height:1.55;color:var(--txt);}
.rptd-header{display:flex;align-items:center;gap:16px;margin-bottom:20px;}
.rptd-logo{width:92px;height:64px;flex:none;display:grid;place-items:center;border:1px dashed var(--stroke-strong);border-radius:8px;color:var(--txt-soft);font-size:10px;line-height:1.25;text-align:center;padding:4px;overflow:hidden;}
.rptd-logo img{width:100%;height:100%;object-fit:contain;}
.rptd-clinic{flex:1;background:#cfe6e4;border-radius:6px;text-align:center;padding:14px 10px;font-family:'Sora',sans-serif;font-weight:700;color:#143036;}
.rptd-anat{width:56px;height:76px;flex:none;color:var(--txt-soft);display:grid;place-items:center;}
.rptd-anat svg{width:100%;height:100%;object-fit:contain;}
.rptd-meta{display:grid;grid-template-columns:150px 1fr;gap:5px 16px;font-size:13px;margin-bottom:18px;}
.rptd-meta .k{color:var(--txt-soft);}
.rptd-imgs{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin:14px 0 20px;}
.rptd-imgs .cell{aspect-ratio:4/3;background:linear-gradient(160deg,#1c2435,#10151f);border:1px solid var(--stroke);border-radius:4px;overflow:hidden;}
.rptd-imgs .cell img{width:100%;height:100%;object-fit:cover;}
.rptd-h4{font-size:13px;font-weight:700;letter-spacing:.04em;margin:18px 0 8px;color:var(--cyan);}
.rptd-body{font-size:13px;line-height:1.7;white-space:pre-wrap;color:var(--txt);}
.rptd-sign{margin-top:38px;display:flex;}
.rptd-sign[data-pos="left"]{justify-content:flex-start;}
.rptd-sign[data-pos="center"]{justify-content:center;}
.rptd-sign[data-pos="right"]{justify-content:flex-end;}
.rptd-sign .sign-box{min-width:250px;text-align:center;padding-top:8px;border-top:1px solid var(--txt);font-size:13px;}
html[data-theme="light"] .rptd-doc{background:#fff;border-color:#e2e8f0;box-shadow:0 8px 40px rgba(0,0,0,.08);}
@media print{.rpt-toolbar{display:none!important;}.rpt-doc-wrap{padding:0;}.rpt-doc,.rptd-doc{box-shadow:none;border:none;border-radius:0;max-width:100%;}}
</style>
@endpush

@section('content')
@php
  $paciente = $paciente ?? null;
  $galImagenes = $galImagenes ?? collect();
  $galVideos = $galVideos ?? collect();
  $galNombre = $paciente?->nombre_completo ?? 'Maria Gonzales';
  $galIni = $paciente
    ? (collect(explode(' ', $galNombre))->filter()->take(2)->map(fn($x)=>mb_strtoupper(mb_substr($x,0,1)))->implode('') ?: 'PX')
    : 'MG';
  $galEstudios = $paciente
    ? $galImagenes->pluck('estudio_id')->merge($galVideos->pluck('estudio_id'))->filter()->unique()->count()
    : 15;
  $galUltimoArchivo = $galImagenes->first() ?? $galVideos->first();
  $galUltimo = $paciente ? (optional($galUltimoArchivo?->capturado_en)->format('d/m/Y') ?? '—') : '15/07/2025';
  $galSexo = $paciente?->sexo ?? 'Femenino';
  $galEdad = $paciente ? ($paciente->edad ? $paciente->edad.' años' : '—') : '38 años';
  $galCodigo = $paciente ? ($paciente->folio ?? $paciente->identificacion ?? '—') : '00012345';
  $reportes = $reportes ?? collect();
@endphp

{{-- Tabs de navegacion --}}
<div class="np-tabs">
  <a class="np-tab active" href="{{ route('nuevo-estudio') }}">Pacientes</a>
  <a class="np-tab" href="{{ route('galeria') }}">Galeria</a>
  <a class="np-tab" href="{{ route('ia-reportes') }}">Reportes</a>
</div>

@php
  $npPacientes = ($pacientes ?? collect())->values()->map(function ($p) {
    $nombre = trim($p->nombre_completo ?? 'Paciente sin nombre');
    $partes = preg_split('/\s+/', $nombre);
    $iniciales = '';
    if (count($partes) >= 2) {
      $iniciales = mb_strtoupper(mb_substr($partes[0], 0, 1) . mb_substr($partes[1], 0, 1));
    } else {
      $iniciales = mb_strtoupper(mb_substr($nombre, 0, 2));
    }
    return [
      'id' => $p->id,
      'nombre' => $nombre,
      'folio' => $p->folio ?? '',
      'edad' => $p->edad ?? '',
      'sexo' => $p->sexo ? ucfirst($p->sexo) : '',
      'telefono' => $p->telefono ?? '',
      'email' => $p->email ?? '',
      'foto' => $p->foto ? asset('storage/' . $p->foto) : null,
      'iniciales' => $iniciales,
    ];
  });
@endphp

<script>window.__NP_PACIENTES = @json($npPacientes);</script>

{{-- Panel Pacientes --}}
<div class="np-tab-panel active" id="tab-pacientes">

@if(!$paciente)
{{-- Buscador de pacientes --}}
<div class="np-searchbar rise d1" id="npSearchBar">
  <div class="np-search-wrap">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" class="np-search" id="npSearch" placeholder="Buscar paciente por nombre, folio, teléfono o correo..." autocomplete="off">
  </div>
</div>

<div class="np-results rise d2" id="npResults">
  <div class="np-results-head">
    <span>Resultados</span>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
  </div>
  <div class="np-res-list" id="npResList"></div>
</div>
@endif

{{-- Layout: formulario + sidebar --}}
<div class="np-layout" id="npFormLayout" style="display:none">

  {{-- Formulario --}}
  <div id="formNuevoPaciente">

    {{-- Card informacion del paciente --}}
    <div class="np-card rise d2">
      <div class="np-sec-header" style="font-size:18px;font-weight:700;margin-bottom:20px">Informacion del paciente</div>

      <div class="np-info-layout">

            @if($fotoPaciente)
                {{-- Si el paciente tiene foto, se renderiza perfectamente adaptada al círculo --}}
                <img src="{{ asset($fotoPaciente) }}" alt="Foto del Paciente" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                {{-- Fallback: Icono SVG premium si no hay foto en la base de datos --}}
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--txt-soft)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.7;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            @endif
        </div>

        {{-- TEXTO DE LA CABECERA --}}
        <div>
            <div class="np-sec-header" style="margin-bottom: 4px; display: flex; align-items: center; gap: 8px; padding: 0;">
                Información General del Paciente
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
    </div>
    
    {{-- REJILLA DE DATOS (CAMPOS EXTRAÍDOS DE LA IMAGEN) --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px 32px;">
        
        {{-- 1. NOMBRE COMPLETO --}}
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--txt-soft); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px;">
                Nombre Completo
            </div>
            <div style="font-size: 14px; font-weight: 600; color: var(--txt);">
                @if(isset($paciente))
                    {{ is_object($paciente) ? ($paciente->nombre ?? '—') : ($paciente['nombre'] ?? '—') }}
                @else
                    —
                @endif
            </div>
        </div>

    {{-- Card historial de estudios --}}
    <div class="np-card rise d3" style="margin-top:16px">
      <div class="np-sec-header" style="font-size:16px;font-weight:700;margin-bottom:16px">Historial de estudios</div>

      <table class="np-table">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Procedimiento</th>
            <th>Diagnostico Preliminar</th>
            <th>Medico</th>
            <th>Archivos</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="npHistorialBody">
          <tr>
            <td>23/06/2026</td>
            <td>Colonoscopia</td>
            <td>Polipo intestinal</td>
            <td>Dr. Victor</td>
            <td><span class="np-archivos"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> 2 archivos</span></td>
            <td><button type="button" class="np-btn-ver">Ver estudio</button> <button type="button" class="np-btn-dots">&#8942;</button></td>
          </tr>
          <tr>
            <td>15/03/2026</td>
            <td>Endoscopia digestiva alta</td>
            <td>Gastritis erosiva</td>
            <td>Dr. Victor</td>
            <td><span class="np-archivos"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> 3 archivos</span></td>
            <td><button type="button" class="np-btn-ver">Ver estudio</button> <button type="button" class="np-btn-dots">&#8942;</button></td>
          </tr>
          <tr>
            <td>10/12/2025</td>
            <td>Colonoscopia</td>
            <td>Diverticulosis</td>
            <td>Dr. Victor</td>
            <td><span class="np-archivos"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> 4 archivos</span></td>
            <td><button type="button" class="np-btn-ver">Ver estudio</button> <button type="button" class="np-btn-dots">&#8942;</button></td>
          </tr>
          <tr>
            <td>22/08/2025</td>
            <td>Endoscopia digestiva alta</td>
            <td>Reflujo gastroesofagico</td>
            <td>Dr. Victor</td>
            <td><span class="np-archivos"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> 2 archivos</span></td>
            <td><button type="button" class="np-btn-ver">Ver estudio</button> <button type="button" class="np-btn-dots">&#8942;</button></td>
          </tr>
          <tr>
            <td>05/05/2025</td>
            <td>Colonoscopia</td>
            <td>Sin hallazgos relevantes</td>
            <td>Dr. Victor</td>
            <td><span class="np-archivos"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> 1 archivo</span></td>
            <td><button type="button" class="np-btn-ver">Ver estudio</button> <button type="button" class="np-btn-dots">&#8942;</button></td>
          </tr>
        </tbody>
      </table>

      <div class="np-table-footer">
        <span>Mostrando 1 a 5 de 12 estudios</span>
        <div class="np-pagination">
          <button type="button" class="np-page-btn">&lsaquo;</button>
          <button type="button" class="np-page-btn active">1</button>
          <button type="button" class="np-page-btn">2</button>
          <button type="button" class="np-page-btn">3</button>
          <button type="button" class="np-page-btn">&rsaquo;</button>
        </div>
      </div>
    </div>

  </div>

  {{-- Sidebar acciones --}}
  <div class="np-side rise d4">
    <div class="np-action-btns">
      <a class="np-action-btn" href="{{ route('nuevo-estudio.grabando') }}">
        <span class="np-ab-icon" style="background:rgba(255,59,59,.12);border-color:rgba(255,90,110,.4)">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ff5a6e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="#ff5a6e" stroke="none"/></svg>
        </span>
        Iniciar estudio
      </button>
      <a class="np-action-btn" href="{{ route('nuevo-estudio.configuracion') }}">
        <span class="np-ab-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
        </span>
        Configuracion de Grabacion
      </a>
    </div>
  </div>

</div>

</div>

{{-- Panel Galeria --}}
<div class="np-tab-panel" id="tab-galeria">

  <div class="pa-topbar rise d2">
    <button class="np-new-study-btn" type="button" id="npNewStudyBtnGal" style="margin-left:auto">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Agregar nuevo estudio
    </button>
  </div>

  <div class="pa-shell rise d3">
    <div>
      <section class="pa-hero">
        <div class="pa-avatar" id="npGalAvatar">{{ $galIni }}</div>
        <div>
          <div class="pa-title" id="npGalName">{{ $galNombre }}</div>
          <div class="pa-sub" id="npGalMeta">ID: {{ $galCodigo }} · {{ $galSexo }} · {{ $galEdad }} · Último estudio: {{ $galUltimo }}</div>
        </div>
        <div class="pa-stats">
          <div class="pa-stat"><strong id="npGalEstudios">{{ $galEstudios }}</strong><span>Estudios</span></div>
          <div class="pa-stat"><strong id="npGalFotos">{{ $paciente ? $galImagenes->count() : 126 }}</strong><span>Fotos</span></div>
          <div class="pa-stat"><strong id="npGalVideos">{{ $paciente ? $galVideos->count() : 12 }}</strong><span>Videos</span></div>
        </div>
      </section>

      <div class="pa-empty" id="npGalEmpty">No se encontraron archivos para este paciente.</div>

      <section class="pa-section">
        <div class="pa-section-head">
          <h2 class="pa-section-title">Videos</h2>
          <span class="pa-section-count">{{ $paciente ? $galVideos->count().' archivos' : '2 archivos' }}</span>
        </div>
        <div class="pa-grid">
          @if($paciente)
          @forelse($galVideos as $v)
          <article class="pa-card" data-kind="video" data-title="{{ strtolower($v->nombre_original ?? 'video') }}">
            <div class="pa-thumb">
              <video src="{{ asset('storage/'.$v->path) }}" preload="metadata" muted style="width:100%;height:100%;object-fit:cover"></video>
              <span class="pa-badge video">VIDEO</span>
              <div class="pa-play"><span><svg width="17" height="17" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg></span></div>
            </div>
            <div class="pa-body">
              <div class="pa-name">{{ $v->nombre_original ?? 'Video del estudio' }}</div>
              <div class="pa-meta">Estudio {{ $v->estudio?->folio }}<br>{{ optional($v->capturado_en)->format('d/m/Y H:i') }}</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ asset('storage/'.$v->path) }}" target="_blank">Ver</a>
              </div>
            </div>
          </article>
          @empty
          <p style="color:var(--txt-soft);font-size:13px">No hay videos para este paciente.</p>
          @endforelse
          @else
          <article class="pa-card" data-kind="video" data-title="video edd-2025-001245 endoscopia digestiva alta">
            <div class="pa-thumb">
              <span class="pa-badge video">VIDEO</span>
              <div class="pa-play"><span><svg width="17" height="17" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg></span></div>
              <span class="pa-duration">00:15:42</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">Video EDD-2025-001245</div>
              <div class="pa-meta">Endoscopia Digestiva Alta<br>15/07/2025</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.video', 1) }}">Ver</a>
                <a class="pa-btn" href="{{ route('galeria.video.editar', 1) }}">Editar</a>
              </div>
            </div>
          </article>
          @endif
        </div>
      </section>

      <section class="pa-section">
        <div class="pa-section-head">
          <h2 class="pa-section-title">Imagenes</h2>
          <span class="pa-section-count">{{ $paciente ? $galImagenes->count().' archivos' : '4 archivos' }}</span>
        </div>
        <div class="pa-grid">
          @if($paciente)
          @forelse($galImagenes as $img)
          <article class="pa-card" data-kind="imagen" data-title="{{ strtolower($img->nombre_original ?? 'imagen') }}">
            <div class="pa-thumb">
              <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->nombre_original ?? 'Captura' }}">
              <span class="pa-badge image">IMG</span>
              <span class="pa-duration">{{ optional($img->capturado_en)->format('H:i') }}</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">{{ $img->nombre_original ?? 'Captura' }}</div>
              <div class="pa-meta">Captura del estudio {{ $img->estudio?->folio }}<br>{{ optional($img->capturado_en)->format('d/m/Y') }}</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.imagen', $img->id) }}">Ver imagen</a>
              </div>
            </div>
          </article>
          @empty
          <p style="color:var(--txt-soft);font-size:13px">No hay imágenes capturadas para este paciente.</p>
          @endforelse
          @else
          <article class="pa-card" data-kind="imagen" data-title="imagen 1 fotograma 0:01:25">
            <div class="pa-thumb">
              <img src="{{ asset('images/colonoscopia.jpg') }}" alt="Imagen 1">
              <span class="pa-badge image">IMG</span>
              <span class="pa-duration">0:01:25</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">Imagen 1 - Fotograma 0:01:25</div>
              <div class="pa-meta">Captura del estudio<br>15/07/2025</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.imagen', 1) }}">Ver imagen</a>
              </div>
            </div>
          </article>
          @endif
        </div>
      </section>
    </div>

    <aside class="pa-side">
      <section class="pa-panel">
        <h3 class="pa-panel-title">Informacion del paciente</h3>
        <div class="pa-info">
          <div class="pa-info-row"><span>ID</span><strong id="npGalSideId">{{ $galCodigo }}</strong></div>
          <div class="pa-info-row"><span>Sexo</span><strong id="npGalSideSexo">{{ $galSexo }}</strong></div>
          <div class="pa-info-row"><span>Edad</span><strong id="npGalSideEdad">{{ $galEdad }}</strong></div>
          <div class="pa-info-row"><span>Estado</span><strong id="npGalSideEstado" style="color:var(--green)">Activo</strong></div>
          <div class="pa-info-row"><span>Ultimo estudio</span><strong id="npGalSideUltimo">{{ $galUltimo }}</strong></div>
        </div>
      </section>

      <section class="pa-panel">
        <h3 class="pa-panel-title">Etiquetas frecuentes</h3>
        <div class="pa-tag-list">
          <span class="pa-tag">Estomago</span>
          <span class="pa-tag">Antro</span>
          <span class="pa-tag">Gastritis</span>
          <span class="pa-tag">Duodeno</span>
        </div>
      </section>
    </aside>
  </div>
</div>

{{-- Panel Reportes --}}
<div class="np-tab-panel" id="tab-reportes">

  @php($rptList = $reportes ?? collect())
  @php($rpt = $rptList->first())
  @php($rptNombre = $paciente?->nombre_completo ?? $rpt?->estudio?->paciente_nombre ?? '—')
  @php($rptIni = collect(explode(' ', $rptNombre))->filter()->take(2)->map(fn($x)=>mb_strtoupper(mb_substr($x,0,1)))->implode('') ?: 'NA')
  @php($rptIdent = $paciente?->identificacion ?? $paciente?->folio ?? '—')
  @php($rptCritico = $rpt ? (bool) $rpt->contiene_hallazgos_criticos : false)

  @if($rpt)
  {{-- Barra de acciones del reporte --}}
  <div class="rpt-toolbar rise d1">
    <div class="rpt-toolbar-left">
      <div class="rpt-pat-chip">
        <div class="rpt-pat-av" id="rptPatAv">{{ $rptIni }}</div>
        <div>
          <div class="rpt-pat-name" id="rptPatName">{{ $rptNombre }}</div>
          <div class="rpt-pat-id" id="rptPatId">ID: {{ $rptIdent }}</div>
        </div>
      </div>
      <span class="rpt-badge" id="rptBadge">{{ $rptCritico ? 'Crítico' : 'Normal' }}</span>
    </div>
    <div class="rpt-toolbar-right">
      <button class="rpt-act-btn" onclick="window.print()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Imprimir
      </button>
      <button class="rpt-act-btn primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Descargar PDF
      </button>
      <a class="rpt-act-btn accent" href="{{ url('/ia-reportes') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
        Editar con IA
      </a>
    </div>
  </div>

  {{-- Documento del reporte (mismo formato que el editor / reporte real) --}}
  @php($rptImgs = ($galImagenes ?? collect())->where('estudio_id', $rpt->estudio_id)->take(8)->values())
  @php($rptFirma = $rpt->usuario?->name ?? $rpt->estudio?->medico ?? $paciente?->medico ?? 'Dr. Nombre del médico')
  @php($rptFechaEstudio = optional($rpt->estudio?->fecha)->format('d/m/Y') ?? $rpt->created_at?->format('d/m/Y') ?? '')
  @php($rptNac = optional($paciente?->fecha_nacimiento)->format('d/m/Y') ?? '')
  <div class="rpt-doc-wrap rise d2">
    <div class="rptd-doc" id="rptDoc">

      {{-- Encabezado: logo + clínica + ilustración (igual que el editor) --}}
      <div class="rptd-header">
        <div class="rptd-logo">Logo de<br>la clínica</div>
        <div class="rptd-clinic">Nombre de la clínica</div>
        <div class="rptd-anat" aria-hidden="true">
          <svg viewBox="0 0 80 110" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M30 8c-6 6-10 14-10 22 0 6 2 11 6 16 4 5 6 9 6 15 0 10-8 14-8 24 0 8 6 13 14 13s14-6 14-15c0-12-12-16-12-26 0-7 5-11 9-17 3-5 5-10 5-16C58 22 50 12 42 8"/><path d="M30 8c4-3 8-3 12 0"/></svg>
        </div>
      </div>

      {{-- Datos del paciente / estudio --}}
      <div class="rptd-meta">
        <span class="k">Paciente:</span><span>{{ $rptNombre }}</span>
        <span class="k">Edad:</span><span>{{ $paciente && $paciente->edad ? $paciente->edad.' años' : '—' }}</span>
        <span class="k">Sexo:</span><span>{{ $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '—' }}</span>
        <span class="k">Fecha de Nac.:</span><span>{{ $rptNac ?: '—' }}</span>
        <span class="k">Fecha del Estudio:</span><span>{{ $rptFechaEstudio ?: '—' }}</span>
        <span class="k">Procedimiento:</span><span>{{ $rpt->estudio?->tipo ?? $paciente?->procedimiento ?? '—' }}</span>
      </div>

      {{-- Imágenes reales del estudio --}}
      @if($rptImgs->count())
      <div class="rptd-imgs">
        @foreach($rptImgs as $img)
          <span class="cell"><img src="{{ asset('storage/'.$img->path) }}" alt="Imagen del estudio"></span>
        @endforeach
      </div>
      @endif

      {{-- Contenido del reporte (texto guardado, conservando su formato) --}}
      <div class="rptd-h4">Contenido del Reporte</div>
      <div class="rptd-body" id="rptHallazgos">{{ $rpt->contenido_texto ?: 'Sin contenido registrado.' }}</div>

      {{-- Firma --}}
      <div class="rptd-sign" data-pos="center">
        <div class="sign-box" id="rptFirmaNombre">{{ $rptFirma }}</div>
      </div>

    </div>
  </div>
  @else
  {{-- Estado vacío: el paciente no tiene reportes --}}
  <div class="rpt-empty rise d2" style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:14px;padding:60px 24px;border:1px dashed var(--stroke);border-radius:var(--r-lg);background:var(--panel)">
    <div style="width:64px;height:64px;border-radius:50%;display:grid;place-items:center;background:rgba(46,123,246,.1);border:1px solid var(--stroke-strong);color:var(--blue)">
      <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    </div>
    <div>
      <div style="font-size:16px;font-weight:700;color:var(--txt);margin-bottom:4px">Este paciente no tiene reportes</div>
      <div style="font-size:13.5px;color:var(--txt-soft)">Genera un reporte clínico para este paciente.</div>
    </div>
    <a class="rpt-act-btn primary" href="{{ route('ia-reportes.redactar', ['paciente' => $paciente?->id]) }}" style="text-decoration:none">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Agregar reporte
    </a>
  </div>
  @endif

</div>

{{-- Modal Nuevo Estudio --}}
<div class="ns-modal-backdrop" id="nsModalBackdrop">
  <div class="ns-modal" id="nsModal">
    <div class="ns-modal-header">
      <div>
        <div class="ns-modal-title">Nuevo estudio</div>
        <div class="ns-modal-subtitle">Crea un estudio a partir de material ya capturado</div>
      </div>
      <button class="ns-modal-close" type="button" id="nsModalClose" aria-label="Cerrar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="ns-modal-body">
      <a class="ns-option" href="{{ route('nuevo-estudio.importar') }}">
        <div class="ns-option-icon purple">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        </div>
        <div class="ns-option-info">
          <div class="ns-option-title">Estudio con capturas</div>
          <div class="ns-option-desc">Sube imagenes o videos ya capturados para formar un estudio sin grabar.</div>
        </div>
        <svg class="ns-option-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
      </a>
    </div>
  </div>
</div>

{{-- Modal de verificación de dispositivo --}}
<div class="ns-modal-backdrop" id="dispositivoModalBackdrop">
  <div class="ns-modal" style="max-width:420px">
    <div class="ns-modal-header">
      <div>
        <div class="ns-modal-title">Verificar dispositivo</div>
        <div class="ns-modal-subtitle">Confirma que el dispositivo de grabación está conectado</div>
      </div>
      <button class="ns-modal-close" type="button" id="dispositivoModalClose" aria-label="Cerrar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="ns-modal-body">
      <div style="margin-bottom:20px">
        <label style="display:block;font-size:12px;font-weight:600;color:var(--txt-soft);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Dispositivo de grabación</label>
        <select id="selDispositivo" style="width:100%;padding:12px 14px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md);font-size:14px;color:var(--txt);outline:none">
          <option value="Endoscopio EG-530" selected>Endoscopio EG-530</option>
          <option value="Endoscopio CF-HQ190L">Endoscopio CF-HQ190L</option>
          <option value="Endoscopio GIF-HQ190">Endoscopio GIF-HQ190</option>
          <option value="Endoscopio Olympus EVIS EXERA III">Endoscopio Olympus EVIS EXERA III</option>
          <option value="Cámara USB HD 1080p">Cámara USB HD 1080p</option>
          <option value="USB Video Device">USB Video Device</option>
        </select>
      </div>
      <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
        <div id="dispositivoStatusIcon" style="width:52px;height:52px;border-radius:14px;background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.3);display:grid;place-items:center;color:#16a34a">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h7l2 3h6c1.1 0 2 .9 2 2v3c0 1.1-.9 2-2 2h-3"/><circle cx="18" cy="16" r="3"/><path d="M18 13v-1"/></svg>
        </div>
        <div>
          <div style="font-size:14px;font-weight:700;color:var(--txt)" id="dispositivoNombre">Endoscopio EG-530</div>
          <div style="font-size:13px;color:var(--txt-soft)" id="dispositivoStatusText">Dispositivo conectado</div>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
        <div style="padding:12px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md)">
          <div style="font-size:11px;color:var(--txt-soft);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Resolución</div>
          <div style="font-size:14px;font-weight:600;color:var(--txt)">1920 x 1080</div>
        </div>
        <div style="padding:12px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md)">
          <div style="font-size:11px;color:var(--txt-soft);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">FPS</div>
          <div style="font-size:14px;font-weight:600;color:var(--txt)">30</div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
        <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--txt);cursor:pointer">
          <input type="checkbox" id="chkDispositivoConectado" checked>
          Sí, el dispositivo está conectado
        </label>
      </div>
      <form method="POST" action="{{ route('nuevo-estudio.store') }}" id="formIniciarEstudio" style="width:100%">
        @csrf
        <input type="hidden" name="paciente_id" value="{{ $paciente?->id }}">
        <input type="hidden" name="tipo" value="{{ $paciente?->procedimiento }}">
        <button type="submit" class="np-new-study-btn" id="btnComenzarGrabar" style="width:100%;justify-content:center;text-decoration:none" @unless($paciente) disabled style="opacity:.5" @endunless>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/></svg>
          Iniciar estudio
        </button>
      </form>
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
  document.getElementById('fecha_registro').textContent = pad(now.getDate())+'/'+pad(now.getMonth()+1)+'/'+now.getFullYear();

  /* Auto-fill desde query params (cuando viene de Pacientes > Iniciar estudio) */
  var urlParams = new URLSearchParams(window.location.search);
  var qName   = urlParams.get('name')   || '';
  var qAge    = urlParams.get('age')    || '';
  var qGender = urlParams.get('gender') || '';
  var qDob    = urlParams.get('dob')    || '';

  if (qName) {
    document.getElementById('nombre').textContent = qName;
    document.getElementById('npSearch').value = qName;
  }
  if (qAge) {
    document.getElementById('edad').textContent = qAge;
  }
  if (qGender) {
    var sexVal = qGender.charAt(0).toUpperCase();
    if (sexVal === 'F') {
      document.getElementById('sexo').textContent = 'Femenino';
    } else if (sexVal === 'M') {
      document.getElementById('sexo').textContent = 'Masculino';
    }
  }
  if (qDob) {
    // qDob puede venir como dd/mm/yyyy o yyyy-mm-dd
    if (qDob.includes('/')) {
      document.getElementById('fecha_nac').textContent = qDob;
    } else {
      var parts = qDob.split('-');
      document.getElementById('fecha_nac').textContent = parts[2]+'/'+parts[1]+'/'+parts[0];
    }
  }

  // Si viene con datos de paciente, mostrar formulario directamente
  if (qName) {
    showForm();
  }

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

  /* Buscador de pacientes */
  (function () {
    const PACIENTES = window.__NP_PACIENTES || [];
    const input = document.getElementById('npSearch');
    const results = document.getElementById('npResults');
    const list = document.getElementById('npResList');
    const searchBar = document.getElementById('npSearchBar');

    if (!input || !results || !list) return;

    function normalize(str) {
      return (str || '').toString().normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }

    function renderItems(items) {
      list.innerHTML = '';
      if (items.length === 0) {
        list.innerHTML = '<div class="np-res-empty">No se encontraron pacientes.</div>';
        return;
      }
      items.forEach((p, i) => {
        const el = document.createElement('div');
        el.className = 'np-res-item';
        el.dataset.index = i;
        const avatar = p.foto
          ? `<img src="${p.foto}" alt="${p.nombre}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`
          : p.iniciales;
        const meta = [p.folio ? 'Folio ' + p.folio : '', p.edad ? p.edad + ' años' : '', p.sexo, p.telefono].filter(Boolean).join(' · ');
        el.innerHTML = `
          <div class="np-res-av">${avatar}</div>
          <div class="np-res-info">
            <div class="np-res-name">${p.nombre}</div>
            <div class="np-res-meta">${meta || 'Sin información adicional'}</div>
          </div>
        `;
        el.addEventListener('click', () => {
          window.location.href = `{{ route('nuevo-estudio') }}?paciente=${encodeURIComponent(p.id)}`;
        });
        list.appendChild(el);
      });
    }

    function search(q) {
      const term = normalize(q).trim();
      if (!term) {
        results.classList.remove('open');
        return;
      }
      const filtered = PACIENTES.filter(p => {
        return normalize(p.nombre).includes(term)
          || normalize(p.folio).includes(term)
          || normalize(p.telefono).includes(term)
          || normalize(p.email).includes(term);
      });
      renderItems(filtered);
      results.classList.add('open');
    }

    let debounce;
    input.addEventListener('input', function () {
      clearTimeout(debounce);
      debounce = setTimeout(() => search(this.value), 150);
    });

    input.addEventListener('focus', function () {
      if (this.value.trim()) search(this.value);
    });

    document.addEventListener('click', function (e) {
      if (!e.target.closest('#npSearchBar') && !e.target.closest('#npResults')) {
        results.classList.remove('open');
      }
    });

    input.addEventListener('keydown', function (e) {
      const items = list.querySelectorAll('.np-res-item');
      let active = list.querySelector('.np-res-item.active');
      let idx = active ? Array.from(items).indexOf(active) : -1;
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        idx = Math.min(idx + 1, items.length - 1);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        idx = Math.max(idx - 1, 0);
      } else if (e.key === 'Enter') {
        e.preventDefault();
        if (items[idx]) {
          items[idx].click();
        } else if (items.length === 1) {
          items[0].click();
        }
        return;
      } else if (e.key === 'Escape') {
        results.classList.remove('open');
        return;
      }
      items.forEach(it => it.classList.remove('active'));
      if (items[idx]) items[idx].classList.add('active');
    });
  })();

  function showForm(){
    var emptyState = document.getElementById('npEmptyState');
    if (emptyState) emptyState.style.display = 'none';
    document.getElementById('npFormLayout').style.display = 'grid';
  }


  /* Filtro de archivos en galeria */
  function setupMediaFilter(inputId, containerSelector){
    var input = document.getElementById(inputId);
    if (!input) return;
    var container = document.querySelector(containerSelector);
    if (!container) return;
    var cards = container.querySelectorAll('.pa-card');
    var empty = document.getElementById('npGalEmpty');
    input.addEventListener('input', function(){
      var q = input.value.trim().toLowerCase();
      var shown = 0;
      cards.forEach(function(card){
        var ok = !q || (card.dataset.title || '').toLowerCase().includes(q) || (card.dataset.kind || '').toLowerCase().includes(q);
        card.style.display = ok ? '' : 'none';
        if (ok) shown++;
      });
      if (empty) empty.style.display = shown ? 'none' : 'block';
    });
    input.addEventListener('keydown', function(e){
      if (e.key === 'Escape'){ input.value = ''; input.dispatchEvent(new Event('input')); }
    });
  }
  setupMediaFilter('npGalSearch', '#tab-galeria');

  /* Mostrar formulario solo si hay paciente seleccionado */
  @if($paciente)
  showForm();
  @endif

  /* Modal Nuevo Estudio */
  const nsBackdrop = document.getElementById('nsModalBackdrop');
  const nsClose = document.getElementById('nsModalClose');
  const nsBtnTop = document.getElementById('npNewStudyBtn');
  const nsBtnGal = document.getElementById('npNewStudyBtnGal');
  const nsBtns = [nsBtnTop, nsBtnGal];

  function openNsModal() {
    if (nsBackdrop) {
      nsBackdrop.classList.add('open');
      document.body.style.overflow = 'hidden';
    }
  }
  function closeNsModal() {
    if (nsBackdrop) {
      nsBackdrop.classList.remove('open');
      document.body.style.overflow = '';
    }
  }

  nsBtns.forEach(function(btn){
    btn?.addEventListener('click', function(e){
      e.preventDefault();
      openNsModal();
    });
  });
  nsClose?.addEventListener('click', closeNsModal);
  nsBackdrop?.addEventListener('click', function(e){
    if (e.target === nsBackdrop) closeNsModal();
  });
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape' && nsBackdrop?.classList.contains('open')) closeNsModal();
  });

  /* Precarga datos del paciente (viene de Pacientes > Iniciar estudio) */
  @if($paciente)
  (function(){
    var elSearch = document.getElementById('npSearch');
    if (elSearch) elSearch.value = @json($paciente->nombre_completo);

    var emptyState = document.getElementById('npEmptyState');
    var formLayout = document.getElementById('npFormLayout');
    var npResults  = document.getElementById('npResults');
    if (emptyState) emptyState.style.display = 'none';
    if (formLayout) formLayout.style.display = 'grid';
    if (npResults)  npResults.classList.remove('open');
    document.querySelectorAll('.np-tab.hidden').forEach(function(t){ t.classList.remove('hidden'); });
    var topBack = document.getElementById('npBackToPatientsTop');
    var topNew  = document.getElementById('npNewStudyBtn');
    if (topBack) topBack.classList.add('visible');
    if (topNew)  topNew.classList.add('visible');
  })();
  @endif

  /* Pestañas */
  document.querySelectorAll('.np-tab[data-tab]').forEach(function(tab){
    tab.addEventListener('click', function(){
      document.querySelectorAll('.np-tab[data-tab]').forEach(function(t){ t.classList.remove('active'); });
      document.querySelectorAll('.np-tab-panel').forEach(function(p){ p.classList.remove('active'); });
      tab.classList.add('active');
      document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
    });
  });

  /* Modal de verificación de dispositivo */
  var dispositivoModalBackdrop = document.getElementById('dispositivoModalBackdrop');
  var dispositivoModalClose = document.getElementById('dispositivoModalClose');
  var selDispositivo = document.getElementById('selDispositivo');
  var dispositivoNombre = document.getElementById('dispositivoNombre');
  var chkDispositivoConectado = document.getElementById('chkDispositivoConectado');
  var dispositivoStatusIcon = document.getElementById('dispositivoStatusIcon');
  var dispositivoStatusText = document.getElementById('dispositivoStatusText');
  var btnComenzarGrabar = document.getElementById('btnComenzarGrabar');

  function openDispositivoModal() {
    if (dispositivoModalBackdrop) dispositivoModalBackdrop.classList.add('open');
    updateDispositivoStatus();
  }
  function closeDispositivoModal() {
    if (dispositivoModalBackdrop) dispositivoModalBackdrop.classList.remove('open');
  }
  function updateDispositivoStatus() {
    if (!chkDispositivoConectado) return;
    var conectado = chkDispositivoConectado.checked;
    if (conectado) {
      dispositivoStatusIcon.style.background = 'rgba(34,197,94,.12)';
      dispositivoStatusIcon.style.borderColor = 'rgba(34,197,94,.3)';
      dispositivoStatusIcon.style.color = '#16a34a';
      dispositivoStatusText.textContent = 'Dispositivo conectado';
      btnComenzarGrabar.style.opacity = '1';
      btnComenzarGrabar.style.pointerEvents = 'auto';
    } else {
      dispositivoStatusIcon.style.background = 'rgba(220,38,38,.12)';
      dispositivoStatusIcon.style.borderColor = 'rgba(220,38,38,.3)';
      dispositivoStatusIcon.style.color = '#dc2626';
      dispositivoStatusText.textContent = 'Dispositivo no conectado';
      btnComenzarGrabar.style.opacity = '.5';
      btnComenzarGrabar.style.pointerEvents = 'none';
    }
  }

  window.openDispositivoModal = openDispositivoModal;
  window.closeDispositivoModal = closeDispositivoModal;

  dispositivoModalClose?.addEventListener('click', closeDispositivoModal);
  dispositivoModalBackdrop?.addEventListener('click', function(e){
    if (e.target === dispositivoModalBackdrop) closeDispositivoModal();
  });
  chkDispositivoConectado?.addEventListener('change', updateDispositivoStatus);
  selDispositivo?.addEventListener('change', function(){
    if (dispositivoNombre) dispositivoNombre.textContent = selDispositivo.value;
  });

  /* Enviar inicio de estudio y redirigir a la interfaz de grabando */
  var formIniciarEstudio = document.getElementById('formIniciarEstudio');
  formIniciarEstudio?.addEventListener('submit', function(e){
    e.preventDefault();
    var btn = document.getElementById('btnComenzarGrabar');
    if (btn) { btn.disabled = true; btn.textContent = 'Iniciando...'; }
    fetch(formIniciarEstudio.action, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: new FormData(formIniciarEstudio)
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
      if (data && data.ok && data.redirect) {
        window.location.href = data.redirect;
      } else if (data && data.redirect) {
        window.location.href = data.redirect;
      } else {
        window.location.reload();
      }
    })
    .catch(function(err){
      console.error('Error iniciando estudio', err);
      formIniciarEstudio.submit();
    });
  });

})();
</script>
@endpush