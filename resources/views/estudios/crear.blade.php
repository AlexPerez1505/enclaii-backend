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

/* Pestañas superiores */
.np-tabs {
  display: flex; align-items: center; gap: 8px;
  border-bottom: 1px solid var(--stroke-strong);
  margin-bottom: 22px;
  padding-top: 6px;
}
.np-tab {
  padding: 12px 18px; cursor: pointer;
  font-size: 14px; font-weight: 600; color: var(--txt-soft);
  border-bottom: 2px solid transparent;
  transition: color 150ms, border-color 150ms;
  background: none; border: none; border-bottom: 2px solid transparent;
  font: inherit; text-decoration: none; display: inline-flex; align-items: center;
}
.np-tab:hover { color: var(--txt); }
.np-tab.active { color: var(--cyan); border-bottom-color: var(--cyan); }
.np-tab.hidden { display: none; }
.np-tab-panel { display: none; }
.np-tab-panel.active { display: block; }

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
  z-index: 200; box-shadow: 0 12px 32px var(--shadow);
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
  display: none;
  background: var(--panel);
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  margin-bottom: 16px; overflow: hidden; box-shadow: 0 8px 24px var(--shadow);
}
.np-results.open { display: block; }
.np-results-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 18px; font-size: 11px; font-weight: 700; color: var(--txt-soft);
  border-bottom: 1px solid var(--stroke); text-transform: uppercase; letter-spacing: .08em;
}
.np-results-head svg { width: 14px; height: 14px; color: var(--txt-soft); }
.np-res-list {
  display: flex; flex-direction: column;
}
.np-res-item {
  display: flex; align-items: center; gap: 14px;
  padding: 14px 18px; cursor: pointer;
  border-bottom: 1px solid var(--stroke); transition: background 120ms;
}
.np-res-item:last-child { border-bottom: none; }
.np-res-item:hover { background: var(--hover-bg); }
.np-res-item.active { background: var(--hover-bg-strong); }
html[data-theme="light"] .np-res-item:hover { background: var(--hover-bg); }
html[data-theme="light"] .np-res-item.active { background: var(--hover-bg-strong); }
.np-res-av {
  width: 44px; height: 44px; border-radius: 50%;
  background: linear-gradient(135deg,var(--blue),var(--cyan));
  display: grid; place-items: center; font-weight: 700; font-size: 13px; flex: none;
  color: #fff;
}
.np-res-name { font-size: 14.5px; font-weight: 700; color: var(--txt); margin-bottom: 3px; }
.np-res-meta { font-size: 12px; color: var(--txt-soft); }
.np-res-empty { padding: 22px; text-align: center; font-size: 13px; color: var(--txt-soft); }

/* Card principal */
.np-card {
  background: var(--card-bg);
  border: 1px solid var(--stroke);
  border-radius: 18px;
  padding: 20px 24px;
  margin-bottom: 20px;
}

/* Sección header */
.np-sec-header {
  font-size: 18px; font-weight: 700; color: var(--txt);
  margin-bottom: 16px;
}

/* Layout: foto + campos */
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
  border: 1px solid var(--stroke-strong); background: var(--card-bg-2);
  font: inherit; font-size: 12.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; width: 100%; justify-content: center;
  transition: background 150ms, border-color 150ms;
}
.np-add-foto-btn:hover { background: var(--panel-2); border-color: var(--blue); }
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
  letter-spacing: .08em; color: var(--txt-soft);
}
.np-field input,
.np-field select,
.np-field textarea {
  background: var(--input-bg);
  border: 1px solid var(--stroke-strong);
  border-radius: 10px;
  padding: 12px 14px; font: inherit; font-size: 14px; color: var(--txt);
  outline: none; width: 100%; transition: border-color 150ms, box-shadow 150ms;
}
.np-field input::placeholder,
.np-field textarea::placeholder { color: var(--off); }
.np-field input:focus,
.np-field select:focus,
.np-field textarea:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(46,123,246,.15);
}
.np-field select option { background: var(--card-bg); }
.np-field textarea { resize: vertical; min-height: 130px; }

/* Span full width */
.np-full { grid-column: 1 / -1; }

/* Separador */
.np-divider {
  border: none; border-top: 1px solid var(--stroke);
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
  background: var(--card-bg);
  border: 1px solid var(--stroke);
  border-radius: 18px; overflow: hidden;
}
.np-action-btn {
  display: flex; align-items: center; gap: 12px;
  width: 100%; padding: 14px 16px;
  border: none; border-bottom: 1px solid var(--stroke);
  background: none; font: inherit; font-size: 13px; font-weight: 600;
  color: var(--txt); cursor: pointer; text-align: left; text-decoration: none;
  transition: background 150ms;
}
.np-action-btn:last-child { border-bottom: none; }
.np-action-btn:hover { background: var(--hover-bg); }
.np-ab-icon {
  width: 34px; height: 34px; border-radius: 9px;
  border: 1px solid var(--stroke-strong);
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

/* Tema claro: campos de solo lectura */
html[data-theme="light"] .np-field input:read-only,
html[data-theme="light"] .np-field textarea:read-only,
html[data-theme="light"] .np-field select:disabled {
  background: var(--panel-2);
  color: var(--txt);
  border-color: var(--stroke-strong);
  opacity: 1;
}
html[data-theme="light"] .np-field select:disabled {
  color: var(--txt-soft);
}

/* Tema claro: dropdown de filtros */
html[data-theme="light"] .np-filter-drop {
  background: var(--panel);
  border-color: var(--stroke-strong);
  box-shadow: 0 16px 40px rgba(0,0,0,.12);
}
html[data-theme="light"] .np-filter-btn {
  background: var(--panel-2);
  border-color: var(--stroke-strong);
  color: var(--txt);
}
html[data-theme="light"] .np-filter-btn:hover,
html[data-theme="light"] .np-filter-btn.open {
  background: var(--card);
  border-color: var(--blue);
}

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

/* Vista detalle del paciente */
.np-detail-toolbar {
  display: flex; align-items: center; justify-content: space-between; gap: 14px;
  margin-bottom: 18px; flex-wrap: wrap;
}
.np-back-btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 16px; border-radius: 10px;
  border: 1px solid var(--stroke); background: var(--panel-2);
  font: inherit; font-size: 13px; font-weight: 700; color: var(--txt);
  cursor: pointer; transition: background 150ms, border-color 150ms;
}
.np-back-btn:hover { background: var(--panel); border-color: var(--blue); }

/* Toolbar superior de pacientes (volver + agregar estudio) */
.np-patient-toolbar {
  display: flex; align-items: center; justify-content: space-between; gap: 14px;
  margin-bottom: 14px; flex-wrap: wrap;
}
.np-searchbar > .np-back-btn { display: none !important; }
.np-searchbar > .np-back-btn.visible { display: inline-flex !important; }
.np-searchbar > .np-new-study-btn { display: none !important; }
.np-searchbar > .np-new-study-btn.visible { display: inline-flex !important; }
.np-new-study-btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 16px; border-radius: 10px;
  background: var(--blue); border: 1px solid var(--blue);
  font: inherit; font-size: 13px; font-weight: 700; color: #fff;
  cursor: pointer; transition: background 150ms, border-color 150ms, transform 150ms;
  text-decoration: none;
}
.np-new-study-btn:hover { background: #2563eb; border-color: #2563eb; transform: translateY(-1px); }

/* Modal Nuevo Estudio */
.ns-modal-backdrop {
  position: fixed; inset: 0; z-index: 1000;
  display: flex; align-items: center; justify-content: center;
  visibility: hidden; opacity: 0;
  background: rgba(2, 6, 23, .72); backdrop-filter: blur(8px);
  padding: 20px;
  transition: opacity 200ms ease, visibility 200ms ease;
}
html[data-theme="light"] .ns-modal-backdrop {
  background: rgba(240, 244, 250, .72);
}
.ns-modal-backdrop.open { visibility: visible; opacity: 1; }
.ns-modal {
  width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto;
  background: var(--modal-bg);
  border: 1px solid var(--stroke-strong); border-radius: 18px;
  box-shadow: 0 24px 60px var(--shadow);
  transform: translateY(12px) scale(.98); transition: transform 220ms ease;
}
.ns-modal-backdrop.open .ns-modal { transform: translateY(0) scale(1); }
.ns-modal-header {
  display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;
  padding: 22px 24px 16px; border-bottom: 1px solid var(--stroke);
}
.ns-modal-title { font-size: 20px; font-weight: 700; color: var(--txt); }
.ns-modal-subtitle { font-size: 13px; color: var(--txt-soft); margin-top: 4px; }
.ns-modal-close {
  width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center;
  background: var(--panel-2); border: 1px solid var(--stroke);
  color: var(--txt-soft); cursor: pointer; transition: all 150ms; flex: none;
}
.ns-modal-close:hover { background: var(--hover-bg); color: var(--txt); }
.ns-modal-body { padding: 18px 24px 24px; display: flex; flex-direction: column; gap: 10px; }
.ns-option {
  display: flex; align-items: center; gap: 14px;
  padding: 16px; border-radius: 14px;
  background: var(--panel-2); border: 1px solid var(--stroke);
  color: var(--txt); text-decoration: none; cursor: pointer;
  transition: background 150ms, border-color 150ms, transform 150ms;
}
.ns-option:hover { background: var(--hover-bg); border-color: var(--blue); transform: translateY(-1px); }
.ns-option-icon {
  width: 44px; height: 44px; border-radius: 12px; flex: none;
  display: grid; place-items: center;
}
.ns-option-icon.blue { background: rgba(14, 165, 233, .12); color: #38bdf8; }
.ns-option-icon.red { background: rgba(220, 38, 38, .12); color: #f87171; }
.ns-option-icon.purple { background: rgba(139, 92, 246, .12); color: #a78bfa; }
.ns-option-info { flex: 1; min-width: 0; }
.ns-option-title { font-size: 14px; font-weight: 700; color: var(--txt); }
.ns-option-desc { font-size: 12.5px; color: var(--txt-soft); margin-top: 3px; }
.ns-option-arrow { color: var(--txt-soft); flex: none; transition: color 150ms, transform 150ms; }
.ns-option:hover .ns-option-arrow { color: var(--txt); transform: translateX(3px); }

.np-detail-search {
  flex: 1; max-width: 360px; height: 42px; display: flex; align-items: center; gap: 10px;
  padding: 0 14px; background: var(--panel-2); border: 1px solid var(--stroke); border-radius: 10px;
}
.np-detail-search svg { color: var(--txt-soft); flex: none; }
.np-detail-search input { flex: 1; border: 0; outline: 0; background: transparent; color: var(--txt); font: inherit; font-size: 13px; }
.np-detail-search input::placeholder { color: var(--txt-soft); }

.np-detail-card {
  display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 20px;
  background: var(--panel-2); border: 1px solid var(--stroke); border-radius: 18px;
  padding: 24px; margin-bottom: 20px;
}
.np-detail-avatar {
  width: 72px; height: 72px; border-radius: 18px; display: grid; place-items: center;
  color: #fff; font-size: 22px; font-weight: 800; font-family: 'Sora', sans-serif;
}
.np-detail-info h2 { font-size: 20px; font-weight: 800; color: var(--txt); margin: 0 0 8px; }
.np-detail-info p { font-size: 13px; color: var(--txt-soft); margin: 0; }
.np-detail-stats { display: flex; align-items: center; gap: 14px; }
.np-stat {
  min-width: 76px; text-align: center; padding: 12px 14px;
  background: var(--panel); border: 1px solid var(--stroke); border-radius: 12px;
}
.np-stat strong { display: block; font-size: 20px; font-weight: 800; color: var(--txt); line-height: 1; }
.np-stat span { display: block; font-size: 11px; color: var(--txt-soft); margin-top: 4px; }

.np-section { margin-bottom: 22px; }
.np-section-header {
  display: flex; align-items: center; justify-content: space-between; gap: 12px;
  margin-bottom: 14px;
}
.np-section-header h3 { font-size: 16px; font-weight: 700; color: var(--txt); margin: 0; }
.np-section-header span { font-size: 12px; color: var(--txt-soft); }
.np-media-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
.np-media-card {
  background: var(--panel-2); border: 1px solid var(--stroke); border-radius: 14px; overflow: hidden;
  transition: border-color 150ms, transform 160ms;
}
.np-media-card:hover { border-color: rgba(46,123,246,.45); transform: translateY(-2px); }
.np-media-thumb {
  position: relative; aspect-ratio: 16/10; background: #0a0f28;
  display: grid; place-items: center;
}
.np-media-thumb img, .np-media-thumb video { width: 100%; height: 100%; object-fit: cover; }
.np-media-thumb .badge {
  position: absolute; top: 10px; left: 10px;
  padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: 800; text-transform: uppercase;
  background: var(--blue); color: #fff;
}
.np-media-thumb .duration {
  position: absolute; bottom: 10px; right: 10px;
  padding: 3px 6px; border-radius: 4px; font-size: 11px; font-weight: 700;
  background: rgba(0,0,0,.65); color: #fff;
}
.np-media-thumb .play {
  width: 44px; height: 44px; border-radius: 50%;
  background: rgba(0,0,0,.55); backdrop-filter: blur(2px);
  display: grid; place-items: center; color: #fff;
}
.np-media-info { padding: 14px; }
.np-media-info h4 { font-size: 13px; font-weight: 700; color: var(--txt); margin: 0 0 4px; }
.np-media-info p { font-size: 12px; color: var(--txt-soft); margin: 0 0 12px; }
.np-media-actions { display: flex; gap: 8px; }
.np-media-actions a, .np-media-actions button {
  flex: 1; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--stroke);
  background: var(--panel); font: inherit; font-size: 12px; font-weight: 700; color: var(--txt);
  cursor: pointer; transition: background 150ms, border-color 150ms;
  text-align: center; text-decoration: none;
}
.np-media-actions a:hover, .np-media-actions button:hover { background: rgba(46,123,246,.12); border-color: var(--blue); }
.np-media-actions a.primary, .np-media-actions button.primary { background: var(--blue); border-color: var(--blue); color: #fff; }
.np-media-actions a.primary:hover, .np-media-actions button.primary:hover { background: #255fd1; }

.np-info-card {
  background: var(--panel-2); border: 1px solid var(--stroke); border-radius: 18px;
  padding: 20px; margin-bottom: 16px;
}
.np-info-card h3 { font-size: 15px; font-weight: 800; color: var(--txt); margin: 0 0 14px; }
.np-info-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 10px; }
.np-info-row span { font-size: 12px; color: var(--txt-soft); }
.np-info-row strong { font-size: 13px; font-weight: 700; color: var(--txt); }
.np-info-row .status.active { color: var(--green); }
.np-info-row .status.inactive { color: var(--orange); }
.np-tags { display: flex; flex-wrap: wrap; gap: 8px; }
.np-tag {
  padding: 6px 12px; border-radius: 8px; border: 1px solid var(--stroke);
  background: var(--panel); font-size: 12px; font-weight: 700; color: var(--txt);
}

/* Interfaz de galeria paciente (dentro de Nuevo Estudio) */
.pa-shell{display:grid;grid-template-columns:1fr 300px;gap:18px;align-items:start}
.pa-topbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap}
.pa-back{
  height:40px;display:inline-flex;align-items:center;gap:8px;padding:0 16px;
  border:1px solid var(--stroke);border-radius:var(--r-md);
  background:transparent;color:var(--txt-soft);font-size:13px;font-weight:700;
  cursor:pointer;transition:background 150ms,color 150ms;border:none;
}
.pa-back:hover{background:rgba(110,160,255,.08);color:var(--txt)}
.pa-search{
  width:min(360px,100%);height:40px;display:flex;align-items:center;gap:9px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-md);padding:0 13px;
}
.pa-search svg{color:var(--txt-soft);flex:none}
.pa-search input{flex:1;min-width:0;border:0;outline:0;background:transparent;color:var(--txt);font:inherit;font-size:13px}
.pa-hero{
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-lg);padding:18px;margin-bottom:16px;
  display:flex;align-items:center;gap:16px;
}
.pa-avatar{
  width:58px;height:58px;border-radius:16px;display:grid;place-items:center;
  background:linear-gradient(135deg,#c084fc,#7c3aed);color:#fff;
  font-family:'Sora',sans-serif;font-weight:800;
}
.pa-title{font-family:'Sora',sans-serif;font-size:18px;font-weight:800;margin-bottom:4px}
.pa-sub{font-size:13px;color:var(--txt-soft)}
.pa-stats{margin-left:auto;display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end}
.pa-stat{
  min-width:86px;padding:10px 12px;border-radius:12px;
  background:var(--card);border:1px solid var(--stroke);text-align:center;
}
.pa-stat strong{display:block;font-family:'Sora',sans-serif;font-size:17px}
.pa-stat span{font-size:11.5px;color:var(--txt-soft)}
.pa-section{margin-bottom:18px}
.pa-section-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:10px}
.pa-section-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:800}
.pa-section-count{font-size:12px;color:var(--txt-soft)}
.pa-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.pa-card{
  display:flex;flex-direction:column;overflow:hidden;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:12px;transition:border-color 150ms ease,transform 160ms var(--ease-out);
}
.pa-card:hover{border-color:rgba(46,123,246,.48);transform:translateY(-1px)}
.pa-thumb{
  position:relative;aspect-ratio:16/10;overflow:hidden;
  background:radial-gradient(ellipse at 50% 50%,#5a1810 0%,#120711 64%,#050712 100%);
}
.pa-thumb img{width:100%;height:100%;object-fit:cover;display:block}
.pa-play{position:absolute;inset:0;display:grid;place-items:center;background:rgba(0,0,0,.18)}
.pa-play span{width:42px;height:42px;border-radius:50%;display:grid;place-items:center;background:rgba(255,255,255,.18);backdrop-filter:blur(8px)}
.pa-badge{
  position:absolute;top:9px;left:9px;padding:3px 8px;border-radius:7px;
  font-size:10.5px;font-weight:900;letter-spacing:.04em;color:#fff;
}
.pa-badge.video{background:rgba(46,123,246,.82)}
.pa-badge.image{background:rgba(245,158,45,.86)}
.pa-duration{
  position:absolute;right:9px;bottom:8px;padding:2px 7px;border-radius:7px;
  background:rgba(0,0,0,.58);color:#fff;font-size:11px;font-weight:800;
}
.pa-body{padding:12px}
.pa-name{font-size:13px;font-weight:800;margin-bottom:5px}
.pa-meta{font-size:12px;color:var(--txt-soft);line-height:1.45}
.pa-actions{display:flex;gap:8px;margin-top:11px}
.pa-btn{
  flex:1;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;gap:6px;
  border:1px solid var(--stroke);font-size:12px;font-weight:800;background:var(--card);color:var(--txt);
  text-decoration:none;cursor:pointer;transition:background 150ms,border-color 150ms,color 150ms;
}
.pa-btn:hover{border-color:rgba(46,123,246,.45);color:var(--blue)}
.pa-btn.primary{background:rgba(46,123,246,.14);border-color:rgba(46,123,246,.35);color:var(--blue)}
.pa-btn.primary:hover{background:rgba(46,123,246,.22)}
.pa-side{display:flex;flex-direction:column;gap:14px}
.pa-panel{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:16px}
.pa-panel-title{font-family:'Sora',sans-serif;font-size:14px;font-weight:800;margin-bottom:12px}
.pa-info{display:grid;gap:10px}
.pa-info-row{display:flex;justify-content:space-between;gap:10px;font-size:13px}
.pa-info-row span{color:var(--txt-soft)}
.pa-info-row strong{text-align:right}
.pa-tag-list{display:flex;flex-wrap:wrap;gap:8px}
.pa-tag{padding:6px 10px;border-radius:999px;border:1px solid var(--stroke);background:var(--card);font-size:12px;font-weight:700}
.pa-empty{display:none;padding:34px 0;text-align:center;color:var(--txt-soft)}

@media (max-width:1100px) {
  .np-layout { grid-template-columns: 1fr; }
  .np-personal-layout { grid-template-columns: 1fr; }
  .np-row-4 { grid-template-columns: 1fr 1fr; }
  .np-row-3, .np-row-3b { grid-template-columns: 1fr 1fr; }
  .np-detail-card { grid-template-columns: auto 1fr; }
  .np-detail-stats { grid-column: 1 / -1; justify-content: flex-start; }
  .np-media-grid { grid-template-columns: repeat(2, 1fr); }
  .pa-shell { grid-template-columns: 1fr; }
  .pa-side { display: grid; grid-template-columns: 1fr 1fr; }
  .pa-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width:640px) {
  .np-row-2,.np-row-3,.np-row-3b,.np-row-4 { grid-template-columns: 1fr; }
  .np-detail-card { grid-template-columns: 1fr; }
  .np-detail-stats { grid-column: auto; }
  .np-media-grid { grid-template-columns: 1fr; }
  .pa-grid { grid-template-columns: 1fr; }
  .pa-hero { align-items: flex-start; }
  .pa-stats { width: 100%; margin-left: 0; justify-content: flex-start; }
  .pa-side { display: flex; }
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

{{-- Pestañas --}}
<div class="np-tabs rise d1">
  <button class="np-tab active" data-tab="pacientes">Pacientes</button>
  <button class="np-tab hidden np-tab-extra" data-tab="galeria">Galeria</button>
  <button class="np-tab hidden np-tab-extra" data-tab="reportes">Reportes</button>
</div>

{{-- Panel Pacientes --}}
<div class="np-tab-panel active" id="tab-pacientes">

@unless($paciente)
{{-- Buscador de pacientes: solo cuando se abre desde el boton del dashboard --}}
@php
  $npPacientes = ($pacientes ?? collect())->values()->map(function ($p) {
    $nombre = trim($p->nombre_completo ?? 'Paciente sin nombre');
    $partes = preg_split('/\s+/', $nombre);
    $iniciales = count($partes) >= 2
      ? mb_strtoupper(mb_substr($partes[0], 0, 1) . mb_substr($partes[1], 0, 1))
      : mb_strtoupper(mb_substr($nombre, 0, 2));
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
@endunless

{{-- Formulario / informacion del paciente --}}
<div class="np-layout" id="npFormLayout" style="display:none">

  <form method="POST" action="#" id="formNuevoPaciente">
    @csrf

    {{-- Card unificada de información del paciente --}}
    <div class="np-card rise d2">
      <div class="np-sec-header">Información del paciente</div>

      <div class="np-personal-layout">

        {{-- Foto --}}
        <div class="np-foto-col">
          @php($pacFoto = $paciente && $paciente->foto ? asset('storage/'.$paciente->foto) : '')
          <div class="np-foto-box" id="npFotoBox">
            <img id="npFotoPreview" src="{{ $pacFoto }}" alt="{{ $paciente?->nombre_completo }}" @if($pacFoto) style="display:block;" @endif>
            <div class="np-foto-ph" id="npFotoPh" @if($pacFoto) style="display:none;" @endif>
              <svg width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
          </div>
          <input type="file" id="npFotoInput" accept="image/*" style="display:none">
          <input type="file" id="npFotoCamera" accept="image/*" capture="environment" style="display:none">
        </div>

        {{-- Datos del paciente en recuadros hacia la derecha --}}
        <div class="np-info-grid">
          <div class="np-info-box">
            <label>Nombre completo</label>
            <div class="np-field-value" id="nombre">{{ $paciente?->nombre_completo ?? '—' }}</div>
          </div>
          
                    
          <div class="np-info-box">
            <label>Edad</label>
            <div class="np-field-value" id="edad">{{ $paciente && $paciente->edad ? $paciente->edad.' años' : '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Sexo</label>
            <div class="np-field-value" id="sexo">{{ $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Fecha nacimiento</label>
            <div class="np-field-value" id="fecha_nac">{{ $paciente?->fecha_nacimiento?->format('Y-m-d') ?? '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Peso</label>
            <div class="np-field-value" id="peso">{{ $paciente && $paciente->peso ? $paciente->peso.' kg' : '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Altura</label>
            <div class="np-field-value" id="altura">{{ $paciente && $paciente->altura ? $paciente->altura.' m' : '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Número de Seguro Social</label>
            <div class="np-field-value" id="nss">{{ $paciente?->identificacion ?? '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Teléfono</label>
            <div class="np-field-value" id="telefono">{{ $paciente?->telefono ?? '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Correo electrónico</label>
            <div class="np-field-value" id="email">{{ $paciente?->email ?? '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Procedimiento</label>
            <div class="np-field-value">{{ $paciente?->procedimiento ?? '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Fecha de registro</label>
            <div class="np-field-value" id="fecha_registro">{{ $paciente?->created_at?->format('Y-m-d') ?? '—' }}</div>
          </div>
          
          <div class="np-info-box np-wide">
            <label>Diagnóstico Preliminar</label>
            <div class="np-field-value np-textarea-value" style="min-height:50px; line-height: 1.3;">Define lo que podria tener</div>
          </div>
        </div>

      </div>
    </div>

  </form>

  {{-- Sidebar acciones --}}
  <div class="np-side rise d4">
    <div class="np-action-btns">
      <button class="np-action-btn" type="button" id="btnIniciarGrabacion" onclick="window.openDispositivoModal()">
        <span class="np-ab-icon" style="background:rgba(255,59,59,.12);border-color:rgba(255,90,110,.4)">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ff5a6e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="pointer-events:none"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="#ff5a6e" stroke="none"/></svg>
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
      <form method="POST" action="{{ route('nuevo-estudio.store') }}" style="width:100%">
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
  var fechaNac = document.getElementById('fecha_nac');
  var fechaReg = document.getElementById('fecha_registro');
  if (fechaNac && !fechaNac.value) fechaNac.value = '1998-12-25';
  if (fechaReg) fechaReg.value = now.getFullYear()+'-'+pad(now.getMonth()+1)+'-'+pad(now.getDate());

  /* Foto menu (formulario oculto, solo por seguridad) */
  var fotoMenu   = document.getElementById('npFotoMenu');
  var btnFotoMenu= document.getElementById('npBtnFotoMenu');
  var btnFotoTxt = document.getElementById('npBtnFotoTxt');
  var btnGaleria = document.getElementById('npBtnGaleria');
  var btnCamara  = document.getElementById('npBtnCamara');
  var fotoInput  = document.getElementById('npFotoInput');
  var fotoCamera = document.getElementById('npFotoCamera');

  if (btnFotoMenu && fotoMenu){
    btnFotoMenu.addEventListener('click', function(e){
      e.stopPropagation();
      fotoMenu.style.display = fotoMenu.style.display === 'none' ? 'block' : 'none';
    });
    document.addEventListener('click', function(){ fotoMenu.style.display = 'none'; });
  }
  if (btnGaleria && fotoInput){
    btnGaleria.addEventListener('click', function(){ fotoMenu.style.display = 'none'; fotoInput.click(); });
  }
  if (btnCamara && fotoCamera){
    btnCamara.addEventListener('click', function(){ fotoMenu.style.display = 'none'; fotoCamera.click(); });
  }

  function applyPreview(file){
    if (!file) return;
    var img = document.getElementById('npFotoPreview');
    var ph  = document.getElementById('npFotoPh');
    if (!img || !ph) return;
    var r = new FileReader();
    r.onload = function(e){
      img.src = e.target.result;
      img.style.display = 'block';
      ph.style.display  = 'none';
      if (btnFotoTxt) btnFotoTxt.textContent = 'Cambiar foto';
    };
    r.readAsDataURL(file);
  }
  if (fotoInput) fotoInput.addEventListener('change', function(){ applyPreview(this.files[0]); });
  if (fotoCamera) fotoCamera.addEventListener('change', function(){ applyPreview(this.files[0]); });

  /* Buscador de pacientes (solo al abrir desde el dashboard) */
  function setupPacienteSearch(){
    var PACIENTES = window.__NP_PACIENTES || [];
    var input   = document.getElementById('npSearch');
    var results = document.getElementById('npResults');
    var list    = document.getElementById('npResList');
    if (!input || !results || !list) return;

    function normalize(str){
      return (str || '').toString().normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }

    function renderItems(items){
      list.innerHTML = '';
      if (items.length === 0){
        list.innerHTML = '<div class="np-res-empty">No se encontraron pacientes.</div>';
        return;
      }
      items.forEach(function(p){
        var el = document.createElement('div');
        el.className = 'np-res-item';
        var avatar = p.foto
          ? '<img src="' + p.foto + '" alt="' + p.nombre + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%">'
          : p.iniciales;
        var meta = [p.folio ? 'Folio ' + p.folio : '', p.edad ? p.edad + ' años' : '', p.sexo, p.telefono].filter(Boolean).join(' · ');
        el.innerHTML = '<div class="np-res-av">' + avatar + '</div>'
          + '<div class="np-res-info"><div class="np-res-name">' + p.nombre + '</div>'
          + '<div class="np-res-meta">' + (meta || 'Sin información adicional') + '</div></div>';
        el.addEventListener('click', function(){
          window.location.href = '{{ route('nuevo-estudio') }}?paciente=' + encodeURIComponent(p.id);
        });
        list.appendChild(el);
      });
    }

    function search(q){
      var term = normalize(q).trim();
      if (!term){ results.classList.remove('open'); return; }
      var filtered = PACIENTES.filter(function(p){
        return normalize(p.nombre).includes(term)
          || normalize(p.folio).includes(term)
          || normalize(p.telefono).includes(term)
          || normalize(p.email).includes(term);
      });
      renderItems(filtered);
      results.classList.add('open');
    }

    var debounce;
    input.addEventListener('input', function(){
      clearTimeout(debounce);
      var val = this.value;
      debounce = setTimeout(function(){ search(val); }, 150);
    });
    input.addEventListener('focus', function(){ if (this.value.trim()) search(this.value); });
    document.addEventListener('click', function(e){
      if (!e.target.closest('#npSearchBar') && !e.target.closest('#npResults')){
        results.classList.remove('open');
      }
    });
  }

  function showForm(){
    var emptyState = document.getElementById('npEmptyState');
    if (emptyState) emptyState.style.display = 'none';
    document.getElementById('npFormLayout').style.display = 'grid';
    document.querySelectorAll('.np-tab.hidden').forEach(function(t){ t.classList.remove('hidden'); });
    const topBack = document.getElementById('npBackToPatientsTop');
    const topNew = document.getElementById('npNewStudyBtn');
    if (topBack) topBack.classList.add('visible');
    if (topNew) topNew.classList.add('visible');
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

  /* Si hay paciente (abierto desde la seccion del paciente) se cargan sus datos.
     Si no (abierto desde el boton del dashboard) se muestra el buscador. */
  @if($paciente)
  showForm();
  @else
  setupPacienteSearch();
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

})();
</script>
@endpush