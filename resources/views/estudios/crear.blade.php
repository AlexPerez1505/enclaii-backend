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
  grid-template-columns: 80px 1fr;
  gap: 12px;
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

/* Estilos para campos de valor plano - solo texto */
.np-field-value {
  padding: 1px 0;
  background: transparent;
  border: none;
  border-radius: 0;
  font-size: 12px;
  font-weight: 500;
  color: var(--txt);
  display: block;
  line-height: 1.2;
}
html[data-theme="light"] .np-field-value {
  color: #1e293b;
}

/* Mejorar espaciado entre etiquetas y valores */
.np-field {
  margin-bottom: 4px;
}
.np-field label {
  display: block;
  font-size: 10px;
  font-weight: 600;
  color: var(--txt-soft);
  text-transform: uppercase;
  letter-spacing: 0.4px;
  margin-bottom: 1px;
  transition: color 200ms ease;
}
html[data-theme="light"] .np-field label {
  color: #64748b;
}

/* Estilos para campos tipo textarea */
.np-textarea-value {
  font-family: inherit;
  font-size: 14px;
  white-space: pre-wrap;
  word-wrap: break-word;
}

/* Estilos para foto ovalada */
.np-foto-box {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid var(--stroke);
  background: var(--panel);
  position: relative;
}
html[data-theme="light"] .np-foto-box {
  border-color: #e2e8f0;
  background: #f8fafc;
}
.np-foto-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}
.np-foto-ph {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}

/* Mejorar diseño de cards */
.np-card {
  background: var(--panel);
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  padding: 12px;
  margin-bottom: 12px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
}
html[data-theme="light"] .np-card {
  background: #ffffff;
  border-color: #e2e8f0;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

/* Mejorar diseño del header de sección */
.np-sec-header {
  font-size: 14px;
  font-weight: 700;
  color: var(--txt);
  margin-bottom: 12px;
  padding-bottom: 6px;
  border-bottom: 1px solid var(--stroke);
  display: flex;
  align-items: center;
  gap: 6px;
}
html[data-theme="light"] .np-sec-header {
  color: #1e293b;
  border-color: #e2e8f0;
}

/* Iconos para headers */
.np-sec-header::before {
  content: '';
  width: 4px;
  height: 20px;
  background: linear-gradient(135deg, #0ea5e9, #3b82f6);
  border-radius: 2px;
}

/* Estilos para secciones de información */
.np-info-section {
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 1px solid var(--stroke);
}
.np-info-section:last-child {
  margin-bottom: 0;
  padding-bottom: 0;
  border-bottom: none;
}
.np-section-title {
  font-size: 12px;
  font-weight: 700;
  color: var(--txt-soft);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
  padding-left: 8px;
  border-left: 3px solid var(--blue);
}
html[data-theme="light"] .np-section-title {
  color: #64748b;
  border-color: #3b82f6;
}
html[data-theme="light"] .np-info-section {
  border-color: #e2e8f0;
}

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
</style>
@endpush

@section('content')

{{-- Pestañas --}}
<div class="np-tabs rise d1">
  <button class="np-tab active" data-tab="pacientes">Pacientes</button>
  <button class="np-tab hidden np-tab-extra" data-tab="galeria">Galeria</button>
  <a class="np-tab hidden np-tab-extra" href="{{ url('/ia-reportes') }}">Reportes</a>
</div>

{{-- Panel Pacientes --}}
<div class="np-tab-panel active" id="tab-pacientes">

{{-- Buscador + Filtros + Acciones --}}
<div class="np-searchbar rise d1">
  <button class="np-back-btn" type="button" id="npBackToPatientsTop">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Volver a pacientes
  </button>
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
  <div class="np-results-head">
    <span>Paciente</span>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
  </div>
  <div id="npResultsList" class="np-res-list"></div>
</div>

{{-- Estado vacio: ningun paciente seleccionado --}}
<div id="npEmptyState" class="np-empty-state rise d2">
  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
  <p>Busca un paciente para ver su informacion</p>
  <span>Usa el buscador o los filtros de arriba</span>
</div>

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
          <div class="np-foto-box" id="npFotoBox">
            <img id="npFotoPreview" src="" alt="">
            <div class="np-foto-ph" id="npFotoPh">
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
            <div class="np-field-value" id="nombre">Maria Fernanda Lopez Ruiz</div>
          </div>
          
                    
          <div class="np-info-box">
            <label>Edad</label>
            <div class="np-field-value" id="edad">28 años</div>
          </div>
          
          <div class="np-info-box">
            <label>Sexo</label>
            <div class="np-field-value" id="sexo">Femenino</div>
          </div>
          
          <div class="np-info-box">
            <label>Fecha nacimiento</label>
            <div class="np-field-value" id="fecha_nac">1998-12-25</div>
          </div>
          
          <div class="np-info-box">
            <label>Peso</label>
            <div class="np-field-value" id="peso">30 kg</div>
          </div>
          
          <div class="np-info-box">
            <label>Altura</label>
            <div class="np-field-value" id="altura">1.75 m</div>
          </div>
          
          <div class="np-info-box">
            <label>Número de Seguro Social</label>
            <div class="np-field-value" id="nss">25849563-9</div>
          </div>
          
          <div class="np-info-box">
            <label>Teléfono</label>
            <div class="np-field-value" id="telefono">722 162 0815</div>
          </div>
          
          <div class="np-info-box">
            <label>Correo electrónico</label>
            <div class="np-field-value" id="email">@gmail.com</div>
          </div>
          
          <div class="np-info-box">
            <label>Procedimiento</label>
            <div class="np-field-value">Colonoscopia</div>
          </div>
          
          <div class="np-info-box">
            <label>Fecha de registro</label>
            <div class="np-field-value" id="fecha_registro">2025-06-20</div>
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
        Iniciar Grabacion
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
    <button class="pa-back" type="button" id="npBackToPatientsGal">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver a pacientes
    </button>
    <label class="pa-search">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="npGalSearch" placeholder="Buscar video o imagen...">
    </label>
    <button class="np-new-study-btn" type="button" id="npNewStudyBtnGal" style="margin-left:auto">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Agregar nuevo estudio
    </button>
  </div>

  <div class="pa-shell rise d3">
    <div>
      <section class="pa-hero">
        <div class="pa-avatar" id="npGalAvatar">MG</div>
        <div>
          <div class="pa-title" id="npGalName">Maria Gonzales</div>
          <div class="pa-sub" id="npGalMeta">ID: 00012345 · Femenino · 38 años · Último estudio: 15/07/2025</div>
        </div>
        <div class="pa-stats">
          <div class="pa-stat"><strong id="npGalEstudios">15</strong><span>Estudios</span></div>
          <div class="pa-stat"><strong id="npGalFotos">126</strong><span>Fotos</span></div>
          <div class="pa-stat"><strong id="npGalVideos">12</strong><span>Videos</span></div>
        </div>
      </section>

      <div class="pa-empty" id="npGalEmpty">No se encontraron archivos para este paciente.</div>

      <section class="pa-section">
        <div class="pa-section-head">
          <h2 class="pa-section-title">Videos</h2>
          <span class="pa-section-count">2 archivos</span>
        </div>
        <div class="pa-grid">
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
          <article class="pa-card" data-kind="video" data-title="video edd-2025-001246 revision de antro">
            <div class="pa-thumb">
              <span class="pa-badge video">VIDEO</span>
              <div class="pa-play"><span><svg width="17" height="17" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg></span></div>
              <span class="pa-duration">00:08:36</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">Video EDD-2025-001246</div>
              <div class="pa-meta">Revision de antro<br>15/07/2025</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.video', 2) }}">Ver</a>
                <a class="pa-btn" href="{{ route('galeria.video.editar', 2) }}">Editar</a>
              </div>
            </div>
          </article>
        </div>
      </section>

      <section class="pa-section">
        <div class="pa-section-head">
          <h2 class="pa-section-title">Imagenes</h2>
          <span class="pa-section-count">4 archivos</span>
        </div>
        <div class="pa-grid">
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
          <article class="pa-card" data-kind="imagen" data-title="imagen 2 fotograma 0:02:15">
            <div class="pa-thumb">
              <img src="{{ asset('images/colonoscopia.jpg') }}" alt="Imagen 2">
              <span class="pa-badge image">IMG</span>
              <span class="pa-duration">0:02:15</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">Imagen 2 - Fotograma 0:02:15</div>
              <div class="pa-meta">Captura del estudio<br>15/07/2025</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.imagen', 2) }}">Ver imagen</a>
              </div>
            </div>
          </article>
          <article class="pa-card" data-kind="imagen" data-title="imagen 3 fotograma 0:04:32">
            <div class="pa-thumb">
              <img src="{{ asset('images/colonoscopia.jpg') }}" alt="Imagen 3">
              <span class="pa-badge image">IMG</span>
              <span class="pa-duration">0:04:32</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">Imagen 3 - Fotograma 0:04:32</div>
              <div class="pa-meta">Captura del estudio<br>15/07/2025</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.imagen', 3) }}">Ver imagen</a>
              </div>
            </div>
          </article>
        </div>
      </section>
    </div>

    <aside class="pa-side">
      <section class="pa-panel">
        <h3 class="pa-panel-title">Informacion del paciente</h3>
        <div class="pa-info">
          <div class="pa-info-row"><span>ID</span><strong id="npGalSideId">00012345</strong></div>
          <div class="pa-info-row"><span>Sexo</span><strong id="npGalSideSexo">Femenino</strong></div>
          <div class="pa-info-row"><span>Edad</span><strong id="npGalSideEdad">38 años</strong></div>
          <div class="pa-info-row"><span>Estado</span><strong id="npGalSideEstado" style="color:var(--green)">Activo</strong></div>
          <div class="pa-info-row"><span>Ultimo estudio</span><strong id="npGalSideUltimo">15/07/2025</strong></div>
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
  <div class="np-empty-state rise d2">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
    <p>Reportes medicos</p>
    <span>Selecciona un paciente para generar o ver reportes</span>
  </div>
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
      <a href="{{ route('nuevo-estudio.grabando') }}" class="np-new-study-btn" id="btnComenzarGrabar" style="width:100%;justify-content:center;text-decoration:none">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/></svg>
        Comenzar a grabar
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

  /* Filtros */
  var filterBtn  = document.getElementById('npFilterBtn');
  var filterDrop = document.getElementById('npFilterDrop');

  filterBtn.addEventListener('click', function(e){
    e.stopPropagation();
    var isOpen = filterDrop.classList.contains('open');
    filterDrop.classList.toggle('open');
    filterBtn.classList.toggle('open');
    if (!isOpen) {
      doSearch();
    }
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
    showResults(PACS);
  });

  /* Pacientes demo */
  var PACS = [
    { nombre:'Maria Gonzalez',    id:'00012345', sexo:'F', medico:'dr_victor',  proc:'colonoscopia',   edad:38, ultimo:'15/07/2025', estudios:15, fotos:126, videos:12, estado:'Activo',   color:'linear-gradient(135deg,#c084fc,#a78bfa)' },
    { nombre:'Jose Ramirez',      id:'00012346', sexo:'M', medico:'dr_victor',  proc:'endoscopia',     edad:45, ultimo:'10/06/2025', estudios:8,  fotos:74,  videos:6,  estado:'Activo',   color:'linear-gradient(135deg,#7dd3fc,#60a5fa)' },
    { nombre:'Ana Torres',        id:'00012347', sexo:'F', medico:'dr_ricardo', proc:'gastroscopia',   edad:33, ultimo:'06/07/2025', estudios:12, fotos:102, videos:9,  estado:'Activo',   color:'linear-gradient(135deg,#f9a8d4,#f472b6)' },
    { nombre:'Carlos Mendez',     id:'00012348', sexo:'M', medico:'dr_ricardo', proc:'colonoscopia',   edad:52, ultimo:'22/05/2025', estudios:4,  fotos:37,  videos:3,  estado:'Inactivo', color:'linear-gradient(135deg,#99f6e4,#6ee7b7)' },
    { nombre:'Laura Perez',       id:'00012349', sexo:'F', medico:'dr_victor',  proc:'ecoendoscopia',  edad:41, ultimo:'18/06/2025', estudios:9,  fotos:81,  videos:7,  estado:'Activo',   color:'linear-gradient(135deg,#c084fc,#a78bfa)' },
    { nombre:'Roberto Flores',    id:'00012350', sexo:'M', medico:'dr_victor',  proc:'cpre',           edad:60, ultimo:'01/05/2025', estudios:3,  fotos:29,  videos:2,  estado:'Inactivo', color:'linear-gradient(135deg,#7dd3fc,#60a5fa)' },
    { nombre:'Sofia Martinez',    id:'00012351', sexo:'F', medico:'dr_ricardo', proc:'sigmoidoscopia', edad:29, ultimo:'12/07/2025', estudios:11, fotos:95,  videos:8,  estado:'Activo',   color:'linear-gradient(135deg,#f9a8d4,#f472b6)' },
    { nombre:'Miguel Hernandez',  id:'00012352', sexo:'M', medico:'dr_victor',  proc:'endoscopia',     edad:38, ultimo:'30/06/2025', estudios:7,  fotos:58,  videos:5,  estado:'Activo',   color:'linear-gradient(135deg,#7dd3fc,#60a5fa)' },
  ];

  function doSearch(){
    var q    = document.getElementById('npSearch').value.trim().toLowerCase();
    var proc = document.getElementById('fltProc').value;
    var sexF = document.getElementById('fltSexoF').checked;
    var sexM = document.getElementById('fltSexoM').checked;
    var med  = document.getElementById('fltMed').value;

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
    var empty = document.getElementById('npEmptyState');
    var form  = document.getElementById('npFormLayout');
    var topBack = document.getElementById('npBackToPatientsTop');
    if (topBack) topBack.classList.remove('visible');
    panel.classList.add('open');
    empty.style.display = 'none';
    if (!res.length){
      list.innerHTML = '<div class="np-res-empty">No se encontraron pacientes</div>';
      return;
    }
    list.innerHTML = res.map(function(p){
      var ini = p.nombre.split(' ').slice(0,2).map(function(w){ return w[0]; }).join('');
      var sexoTxt = p.sexo === 'F' ? 'Femenino' : 'Masculino';
      return '<div class="np-res-item" data-nombre="'+p.nombre+'" data-id="'+p.id+'" data-sexo="'+p.sexo+'">'
        +'<div class="np-res-av">'+ini+'</div>'
        +'<div><div class="np-res-name">'+p.nombre+'</div>'
        +'<div class="np-res-meta">'+p.edad+' anos · '+sexoTxt+'</div></div>'
        +'</div>';
    }).join('');
    list.querySelectorAll('.np-res-item').forEach(function(el){
      el.addEventListener('click', function(){
        var p = PACS.find(function(x){ return x.id === el.dataset.id; });
        if (!p) return;
        list.querySelectorAll('.np-res-item').forEach(function(i){ i.classList.remove('active'); });
        el.classList.add('active');
        document.getElementById('nombre').value         = p.nombre;
        document.getElementById('identificacion').value = p.id;
        document.getElementById('sexo').value           = p.sexo;
        document.getElementById('edad').value           = p.edad + ' años';
        document.getElementById('npSearch').value       = p.nombre;
        populateGallery(p);
        hideResults();
        showForm();
      });
    });
  }

  function hideResults(){
    document.getElementById('npResults').classList.remove('open');
    if (document.getElementById('npFormLayout').style.display !== 'grid'){
      document.getElementById('npEmptyState').style.display = 'flex';
    }
  }

  function populateGallery(p){
    var sexoTxt = p.sexo === 'F' ? 'Femenino' : 'Masculino';
    var ini = p.nombre.split(' ').slice(0,2).map(function(w){ return w[0]; }).join('');
    var av = document.getElementById('npGalAvatar');
    if (av) { av.textContent = ini; av.style.background = p.color; }
    var nameEl = document.getElementById('npGalName');
    if (nameEl) nameEl.textContent = p.nombre;
    var metaEl = document.getElementById('npGalMeta');
    if (metaEl) metaEl.textContent = 'ID: ' + p.id + ' · ' + sexoTxt + ' · ' + p.edad + ' años · Último estudio: ' + p.ultimo;
    var estEl = document.getElementById('npGalEstudios');
    if (estEl) estEl.textContent = p.estudios;
    var fotEl = document.getElementById('npGalFotos');
    if (fotEl) fotEl.textContent = p.fotos;
    var vidEl = document.getElementById('npGalVideos');
    if (vidEl) vidEl.textContent = p.videos;
    var sideId = document.getElementById('npGalSideId');
    if (sideId) sideId.textContent = p.id;
    var sideSexo = document.getElementById('npGalSideSexo');
    if (sideSexo) sideSexo.textContent = sexoTxt;
    var sideEdad = document.getElementById('npGalSideEdad');
    if (sideEdad) sideEdad.textContent = p.edad + ' años';
    var sideEstado = document.getElementById('npGalSideEstado');
    if (sideEstado) {
      sideEstado.textContent = p.estado;
      sideEstado.style.color = p.estado === 'Activo' ? 'var(--green)' : 'var(--orange)';
    }
    var sideUltimo = document.getElementById('npGalSideUltimo');
    if (sideUltimo) sideUltimo.textContent = p.ultimo;
  }

  function showForm(){
    document.getElementById('npEmptyState').style.display = 'none';
    document.getElementById('npFormLayout').style.display = 'grid';
    document.querySelectorAll('.np-tab.hidden').forEach(function(t){ t.classList.remove('hidden'); });
    const topBack = document.getElementById('npBackToPatientsTop');
    const topNew = document.getElementById('npNewStudyBtn');
    if (topBack) topBack.classList.add('visible');
    if (topNew) topNew.classList.add('visible');
  }

  function showPatientList(){
    document.getElementById('npFormLayout').style.display = 'none';
    document.getElementById('npResults').classList.add('open');
    document.getElementById('npEmptyState').style.display = 'none';
    document.querySelectorAll('.np-tab[data-tab]').forEach(function(t){ t.classList.remove('active'); });
    document.querySelectorAll('.np-tab-panel').forEach(function(p){ p.classList.remove('active'); });
    document.querySelector('.np-tab[data-tab="pacientes"]').classList.add('active');
    document.getElementById('tab-pacientes').classList.add('active');
    document.querySelectorAll('.np-tab-extra').forEach(function(t){ t.classList.add('hidden'); });
    const topBack = document.getElementById('npBackToPatientsTop');
    const topNew = document.getElementById('npNewStudyBtn');
    if (topBack) topBack.classList.remove('visible');
    if (topNew) topNew.classList.remove('visible');
  }

  document.getElementById('npBackToPatientsTop')?.addEventListener('click', function(){
    showPatientList();
  });
  document.getElementById('npBackToPatientsGal')?.addEventListener('click', function(){
    showPatientList();
  });

  document.getElementById('npSearch').addEventListener('input', doSearch);

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

  /* Mostrar todos los pacientes por defecto */
  showResults(PACS);

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

  /* Precarga datos del paciente desde URL (viene de Pacientes > Iniciar estudio) */
  (function(){
    var params = new URLSearchParams(window.location.search);
    var name   = params.get('name');
    if (!name) return;

    var age    = params.get('age')    || '';
    var gender = params.get('gender') || '';
    var dob    = params.get('dob')    || '';

    var elNombre = document.getElementById('nombre');
    if (elNombre) elNombre.value = name;

    var elEdad = document.getElementById('edad');
    if (elEdad) elEdad.value = age;

    var elSexo = document.getElementById('sexo');
    if (elSexo){
      var g = gender.toLowerCase();
      if (g === 'femenino' || g === 'f') elSexo.value = 'F';
      else if (g === 'masculino' || g === 'm') elSexo.value = 'M';
    }

    var elFecha = document.getElementById('fecha_nac');
    if (elFecha && dob) elFecha.value = dob;

    var elSearch = document.getElementById('npSearch');
    if (elSearch) elSearch.value = name;

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