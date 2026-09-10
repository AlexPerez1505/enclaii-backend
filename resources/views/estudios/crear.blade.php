@extends('layouts.app')

@php
  $paciente = $paciente ?? null;
  $estudio = $estudio ?? null;
  $pageTitle = $paciente ? 'Estudio del paciente' : 'Nuevo Estudio';
  $pageSub = $estudio
    ? trim(($estudio->tipo ?? 'Estudio').' · '.(format_user_date($estudio->fecha) ?: 'Sin fecha'))
    : ($paciente ? 'Información del paciente' : 'Datos nuevos');
@endphp

@section('title', $pageTitle)
@section('active', 'nuevo-estudio')
@section('header-title', $pageTitle)
@section('header-sub')
  {{ $pageSub }}
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
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 12px;
  padding: 14px;
}
.np-res-item {
  display: flex; align-items: center; gap: 14px;
  padding: 14px 18px; cursor: pointer;
  border: 1px solid var(--stroke);
  border-radius: 12px;
  background: var(--card-bg);
  transition: background 120ms, border-color 120ms, transform 120ms;
}
.np-res-item:hover { background: var(--hover-bg); border-color: var(--blue); transform: translateY(-1px); }
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
.np-res-info { min-width: 0; }
.np-res-name,
.np-res-meta { overflow-wrap: anywhere; }
.np-res-empty { grid-column: 1 / -1; padding: 22px; text-align: center; font-size: 13px; color: var(--txt-soft); }

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
.np-new-study-btn:disabled {
  opacity: .55;
  cursor: not-allowed;
  transform: none;
}
.np-share-study-btn {
  background: rgba(14, 165, 233, .12);
  border-color: rgba(56, 189, 248, .45);
  color: var(--cyan);
}
.np-share-study-btn:hover {
  background: rgba(14, 165, 233, .2);
  border-color: rgba(56, 189, 248, .65);
  color: var(--cyan);
}

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
.ns-field { display: flex; flex-direction: column; gap: 6px; }
.ns-field label {
  font-size: 11px;
  font-weight: 800;
  color: var(--txt-soft);
  text-transform: uppercase;
}
.ns-field input,
.ns-field textarea {
  width: 100%;
  border: 1px solid var(--stroke);
  border-radius: 10px;
  background: var(--panel-2);
  color: var(--txt);
  font: inherit;
  font-size: 13px;
  outline: none;
  padding: 11px 12px;
}
.ns-field textarea { min-height: 112px; resize: vertical; line-height: 1.5; }
.ns-field input:focus,
.ns-field textarea:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(46, 123, 246, .14);
}
.ns-summary {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
  margin: 4px 0 2px;
}
.ns-summary span {
  border: 1px solid var(--stroke);
  border-radius: 10px;
  background: var(--panel-2);
  color: var(--txt-soft);
  font-size: 12px;
  font-weight: 700;
  padding: 10px;
  text-align: center;
}
.ns-status {
  display: none;
  border-radius: 10px;
  border: 1px solid var(--stroke);
  font-size: 12.5px;
  font-weight: 700;
  line-height: 1.45;
  padding: 10px 12px;
}
.ns-status.show { display: block; }
.ns-status.ok {
  background: rgba(34, 197, 94, .12);
  border-color: rgba(34, 197, 94, .35);
  color: var(--green);
}
.ns-status.error {
  background: rgba(239, 68, 68, .12);
  border-color: rgba(239, 68, 68, .35);
  color: #ff7a90;
}
.ns-modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 6px;
}
.ns-modal-actions button { min-width: 116px; justify-content: center; }

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
.pa-shell{display:grid;grid-template-columns:1fr;gap:18px;align-items:start}
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
.pa-report-list{display:grid;gap:10px}
.pa-report-card{
  display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:14px;
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:12px;
  padding:14px;
}
.pa-report-icon{
  width:42px;height:42px;border-radius:12px;display:grid;place-items:center;
  background:rgba(46,123,246,.14);border:1px solid rgba(46,123,246,.28);color:var(--blue);
}
.pa-report-title{font-size:13.5px;font-weight:800;color:var(--txt);margin-bottom:4px}
.pa-report-preview{font-size:12.5px;color:var(--txt-soft);line-height:1.45}
.pa-report-date{font-size:12px;color:var(--txt-soft);text-align:right}
.pa-report-badge{
  display:inline-flex;align-items:center;justify-content:center;
  border-radius:999px;background:rgba(239,68,68,.13);border:1px solid rgba(239,68,68,.35);
  color:#ff7a90;font-size:11px;font-weight:800;padding:4px 8px;margin-top:5px;
}
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
  .pa-report-card { grid-template-columns: auto 1fr; }
  .pa-report-date { grid-column: 2; text-align: left; }
  .ns-summary { grid-template-columns: 1fr; }
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
.np-study-info-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}
.np-study-info-grid .np-field-value {
  line-height: 1.35;
}
@media (max-width: 1180px) {
  .np-study-info-grid {
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 720px) {
  .np-study-info-grid {
    grid-template-columns: 1fr;
  }
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
@media print{
  @page{size:letter;margin:.4in}
  .rpt-toolbar{display:none!important;}
  .rpt-doc-wrap{padding:0;}
  .rpt-doc,.rptd-doc{box-shadow:none;border:none;border-radius:0;max-width:100%;}
  body.print-report-only{background:#fff!important;margin:0!important;padding:0!important;}
  body.print-report-only > :not(.dash),
  body.print-report-only .side,
  body.print-report-only .head,
  body.print-report-only .mobile-nav,
  body.print-report-only .app-alert,
  body.print-report-only .np-patient-toolbar,
  body.print-report-only .np-tabs,
  body.print-report-only #tab-pacientes,
  body.print-report-only #tab-galeria,
  body.print-report-only #tab-reportes > :not(.rpt-doc-wrap){display:none!important;}
  body.print-report-only .dash,
  body.print-report-only .main,
  body.print-report-only #tab-reportes,
  body.print-report-only .rpt-doc-wrap{
    display:block!important;
    width:auto!important;
    min-height:0!important;
    margin:0!important;
    padding:0!important;
    overflow:visible!important;
    background:#fff!important;
  }
  body.print-report-only #rptDoc{
    position:static!important;
    width:100%!important;
    max-width:none!important;
    margin:0!important;
    padding:0!important;
    background:#fff!important;
    color:#111!important;
  }
  body.print-report-only #rptDoc .rptd-clinic{background:#e8f4f3!important;color:#143036!important;}
  body.print-report-only #rptDoc .rptd-h4{color:#0275a8!important;}
  body.print-report-only #rptDoc .rptd-header,
  body.print-report-only #rptDoc .rptd-meta,
  body.print-report-only #rptDoc .rptd-imgs,
  body.print-report-only #rptDoc .rptd-sign{break-inside:avoid;page-break-inside:avoid;}
}

/* ===== Modal Tauri / Iniciar estudio ===== */
.tauri-modal {
  max-width: 460px !important;
}

.tauri-intro-card {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 18px;
  padding: 14px;
  background: var(--panel-2);
  border: 1px solid var(--stroke);
  border-radius: 14px;
}

.tauri-intro-icon {
  width: 52px;
  height: 52px;
  flex: none;
  display: grid;
  place-items: center;
  border-radius: 14px;
  background: rgba(46,123,246,.12);
  border: 1px solid rgba(46,123,246,.3);
  color: var(--blue);
}

.tauri-intro-title {
  font-size: 14px;
  font-weight: 800;
  color: var(--txt);
}

.tauri-intro-text {
  margin-top: 3px;
  font-size: 13px;
  line-height: 1.45;
  color: var(--txt-soft);
}

.tauri-code-box {
  display: none;
  margin-bottom: 18px;
  padding: 22px 20px;
  border-radius: 16px;
  background: var(--panel-2);
  border: 1px solid var(--stroke);
  text-align: center;
}

.tauri-code-label {
  margin-bottom: 8px;
  font-size: 11px;
  font-weight: 800;
  color: var(--txt-soft);
  text-transform: uppercase;
  letter-spacing: .09em;
}

.tauri-code-value {
  font-family: 'Sora', 'Quicksand', system-ui, sans-serif;
  font-size: 38px;
  font-weight: 900;
  color: var(--txt);
  letter-spacing: .12em;
  line-height: 1;
}

.tauri-code-expire {
  margin-top: 10px;
  font-size: 12.5px;
  color: var(--txt-soft);
}

.tauri-help-box {
  margin-bottom: 18px;
  padding: 14px;
  border-radius: 14px;
  background: rgba(245,158,45,.10);
  border: 1px solid rgba(245,158,45,.22);
  color: var(--txt-soft);
  font-size: 13px;
  line-height: 1.55;
}

.tauri-warning-box {
  margin-top: 12px;
  padding: 12px 14px;
  border-radius: 12px;
  background: rgba(239,68,68,.10);
  border: 1px solid rgba(239,68,68,.24);
  color: #ef4444;
  font-size: 13px;
  font-weight: 700;
}

.tauri-generate-btn {
  width: 100%;
  justify-content: center;
  text-decoration: none;
}

.tauri-generate-btn:disabled {
  opacity: .5;
  cursor: not-allowed;
  transform: none !important;
}

</style>
@endpush

@section('content')
@php
  $paciente = $paciente ?? null;
  $estudio = $estudio ?? null;
  $isStudyDetail = (bool) $estudio;
  $galImagenes = $galImagenes ?? collect();
  $galVideos = $galVideos ?? collect();
  $reportes = $reportes ?? collect();
  $galNombre = $paciente?->nombre_completo ?? 'Maria Gonzales';
  $galIni = $paciente
    ? (collect(explode(' ', $galNombre))->filter()->take(2)->map(fn($x)=>mb_strtoupper(mb_substr($x,0,1)))->implode('') ?: 'PX')
    : 'MG';
  $galEstudios = $isStudyDetail
    ? 1
    : ($paciente
    ? $galImagenes->pluck('estudio_id')->merge($galVideos->pluck('estudio_id'))->filter()->unique()->count()
    : 15);
  $galUltimoArchivo = $galImagenes->first() ?? $galVideos->first();
  $galUltimo = $isStudyDetail
    ? (format_user_date($estudio->fecha) ?: '—')
    : ($paciente ? (format_user_date($galUltimoArchivo?->capturado_en) ?: '—') : '15/07/2025');
  $galSexo = $paciente?->sexo ?? 'Femenino';
  $galEdad = $paciente ? ($paciente->edad ? $paciente->edad.' años' : '—') : '38 años';
  $galCodigo = $paciente ? ($paciente->folio ?? $paciente->identificacion ?? '—') : '00012345';
  $studyShareSubject = $isStudyDetail
    ? 'Estudio '.$estudio->folio.' - '.$galNombre
    : 'Estudio - '.$galNombre;
  $studyShareHasContent = $galImagenes->isNotEmpty() || $galVideos->isNotEmpty() || $reportes->isNotEmpty();
@endphp

@if($paciente)
<div class="np-patient-toolbar rise d1">
  <a class="np-back-btn" id="npBackToPatientsTop" href="{{ route('pacientes.index') }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <line x1="19" y1="12" x2="5" y2="12"/>
      <polyline points="12 19 5 12 12 5"/>
    </svg>
    Volver a pacientes
  </a>
</div>
@endif

{{-- Pestañas --}}
<div class="np-tabs rise d1">
  <button class="np-tab active" data-tab="pacientes">Pacientes</button>
  <button class="np-tab hidden np-tab-extra" data-tab="galeria">Galeria</button>
  @unless($isStudyDetail)
  <button class="np-tab hidden np-tab-extra" data-tab="reportes">Reportes</button>
  @endunless
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
      'foto' => $p->foto ? media_url($p->foto) : null,
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
    <span id="npResultsTitle">Pacientes registrados</span>
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
          @php
            $pacFoto = $paciente && $paciente->foto ? media_url($paciente->foto) : '';
          @endphp
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
            <div class="np-field-value" id="fecha_nac">{{ format_user_date($paciente?->fecha_nacimiento) ?: '—' }}</div>
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
            <div class="np-field-value" id="fecha_registro">{{ format_user_date($paciente?->created_at) ?: '—' }}</div>
          </div>
          
          <div class="np-info-box np-wide">
            <label>Diagnóstico Preliminar</label>
            <div class="np-field-value np-textarea-value" style="min-height:50px; line-height: 1.3;">Define lo que podria tener</div>
          </div>
        </div>

      </div>
    </div>

    @if($estudio)
      @php
        $duracionSegundos = (int) ($estudio->duracion_segundos ?? 0);
        $duracionTexto = $duracionSegundos > 0
          ? sprintf('%02d:%02d:%02d', intdiv($duracionSegundos, 3600), intdiv($duracionSegundos % 3600, 60), $duracionSegundos % 60)
          : '—';
      @endphp
      <div class="np-card rise d3">
        <div class="np-sec-header">Información del estudio</div>
        <div class="np-info-grid np-study-info-grid">
          <div class="np-info-box">
            <label>Folio del estudio</label>
            <div class="np-field-value">{{ $estudio->folio ?? '—' }}</div>
          </div>
          <div class="np-info-box">
            <label>Procedimiento</label>
            <div class="np-field-value">{{ $estudio->tipo ?? $paciente?->procedimiento ?? '—' }}</div>
          </div>
          <div class="np-info-box">
            <label>Fecha del estudio</label>
            <div class="np-field-value">{{ format_user_date($estudio->fecha) ?: '—' }}</div>
          </div>
          <div class="np-info-box">
            <label>Estado</label>
            <div class="np-field-value">{{ $estudio->estado_texto ?? '—' }}</div>
          </div>
          <div class="np-info-box">
            <label>Médico</label>
            <div class="np-field-value">{{ $estudio->medico ?? $paciente?->medico ?? '—' }}</div>
          </div>
          <div class="np-info-box">
            <label>Sala</label>
            <div class="np-field-value">{{ $estudio->sala ?? '—' }}</div>
          </div>
          <div class="np-info-box">
            <label>Equipo</label>
            <div class="np-field-value">{{ $estudio->equipo ?? '—' }}</div>
          </div>
          <div class="np-info-box">
            <label>Hora de inicio</label>
            <div class="np-field-value">{{ format_user_time($estudio->hora_inicio) ?: '—' }}</div>
          </div>
          <div class="np-info-box">
            <label>Hora de fin</label>
            <div class="np-field-value">{{ format_user_time($estudio->hora_fin) ?: '—' }}</div>
          </div>
          <div class="np-info-box">
            <label>Duración</label>
            <div class="np-field-value">{{ $duracionTexto }}</div>
          </div>
          <div class="np-info-box">
            <label>Archivos</label>
            <div class="np-field-value">{{ $galImagenes->count() }} imagen(es) / {{ $galVideos->count() }} video(s)</div>
          </div>
          <div class="np-info-box">
            <label>Reportes</label>
            <div class="np-field-value">{{ $reportes->count() }}</div>
          </div>
          <div class="np-info-box np-wide">
            <label>Diagnóstico</label>
            <div class="np-field-value np-textarea-value">{{ $estudio->diagnostico ?: '—' }}</div>
          </div>
          <div class="np-info-box np-wide">
            <label>Descripción</label>
            <div class="np-field-value np-textarea-value">{{ $estudio->descripcion ?: '—' }}</div>
          </div>
          <div class="np-info-box np-wide">
            <label>Observaciones</label>
            <div class="np-field-value np-textarea-value">{{ $estudio->observaciones ?: '—' }}</div>
          </div>
        </div>
      </div>
    @endif

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
      {{--
      <a class="np-action-btn" href="{{ route('nuevo-estudio.configuracion') }}">
        <span class="np-ab-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
        </span>
        Configuracion de Grabacion
      </a> 
      --}}
    </div>
  </div>

</div>

</div>

{{-- Panel Galeria --}}
<div class="np-tab-panel" id="tab-galeria">

  <div class="pa-topbar rise d2">
    @if($isStudyDetail)
    <button
      class="np-new-study-btn np-share-study-btn"
      type="button"
      id="npShareStudyBtn"
      data-study-email-open
      @unless($studyShareHasContent) disabled @endunless
      title="{{ $studyShareHasContent ? 'Enviar reportes, capturas y videos por correo' : 'Este estudio no tiene archivos ni reportes para enviar' }}"
    >
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="5" width="18" height="14" rx="2"/>
        <path d="m3 7 9 6 9-6"/>
      </svg>
      Compartir por correo
    </button>
    @endif
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
          @if($isStudyDetail)
          <div class="pa-stat"><strong>{{ $reportes->count() }}</strong><span>Reportes</span></div>
          @endif
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
              <video src="{{ media_url($v->path) }}" preload="metadata" muted style="width:100%;height:100%;object-fit:cover"></video>
              <span class="pa-badge video">VIDEO</span>
              <div class="pa-play"><span><svg width="17" height="17" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg></span></div>
            </div>
            <div class="pa-body">
              <div class="pa-name">{{ $v->nombre_original ?? 'Video del estudio' }}</div>
              <div class="pa-meta">Estudio {{ $v->estudio?->folio }}<br>{{ format_user_date($v->capturado_en) }}</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ media_url($v->path) }}" target="_blank">Ver</a>
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
              <img src="{{ media_url($img->path) }}" alt="{{ $img->nombre_original ?? 'Captura' }}">
              <span class="pa-badge image">IMG</span>
              <span class="pa-duration">{{ format_user_time($img->capturado_en) }}</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">{{ $img->nombre_original ?? 'Captura' }}</div>
              <div class="pa-meta">Captura del estudio {{ $img->estudio?->folio }}<br>{{ format_user_date($img->capturado_en) }}</div>
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

      @if($isStudyDetail)
      <section class="pa-section">
        <div class="pa-section-head">
          <h2 class="pa-section-title">Reportes</h2>
          <span class="pa-section-count">{{ $reportes->count().' reportes' }}</span>
        </div>
        @if($reportes->isNotEmpty())
        <div class="pa-report-list">
          @foreach($reportes as $reporte)
            @php
              $reportPreview = $reporte->contenido_texto ?: trim(strip_tags((string) $reporte->contenido_html));
            @endphp
            <article class="pa-report-card" data-kind="reporte" data-title="{{ strtolower($reportPreview ?: 'reporte') }}">
              <div class="pa-report-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                  <line x1="16" y1="13" x2="8" y2="13"/>
                  <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
              </div>
              <div>
                <div class="pa-report-title">Reporte #{{ $reporte->id }}</div>
                <div class="pa-report-preview">{{ \Illuminate\Support\Str::limit($reportPreview ?: 'Sin contenido registrado.', 180) }}</div>
                @if($reporte->contiene_hallazgos_criticos)
                <span class="pa-report-badge">Hallazgos críticos</span>
                @endif
              </div>
              <div class="pa-report-date">
                <div>{{ format_user_date($reporte->created_at) ?: 'Sin fecha' }}</div>
                <a class="pa-btn primary" href="{{ route('ia-reportes.ver', ['reporte' => $reporte->id]) }}" style="margin-top:8px;min-width:92px">Ver reporte</a>
              </div>
            </article>
          @endforeach
        </div>
        @else
        <div class="pa-empty" style="display:block;text-align:left;padding:16px;border:1px dashed var(--stroke);border-radius:12px">
          Este estudio no tiene reportes guardados.
        </div>
        @endif
      </section>
      @endif
    </div>

  </div>
</div>

@unless($isStudyDetail)
{{-- Panel Reportes --}}
<div class="np-tab-panel" id="tab-reportes">

  @php
    $rptList = $reportes ?? collect();
    $rpt = $rptList->first();
    $rptNombre = $paciente?->nombre_completo ?? $rpt?->estudio?->paciente_nombre ?? '—';
    $rptIni = collect(explode(' ', $rptNombre))->filter()->take(2)->map(fn($x) => mb_strtoupper(mb_substr($x, 0, 1)))->implode('') ?: 'NA';
    $rptIdent = $paciente?->identificacion ?? $paciente?->folio ?? '—';
    $rptCritico = $rpt ? (bool) $rpt->contiene_hallazgos_criticos : false;
  @endphp

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
      <button class="rpt-act-btn" type="button" data-print-report>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Imprimir
      </button>
      <a class="rpt-act-btn primary" href="{{ route('ia-reportes.pdf', $rpt) }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Descargar PDF
      </a>
      <a class="rpt-act-btn accent" href="{{ url('/ia-reportes') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
        Editar con IA
      </a>
    </div>
  </div>

  {{-- Documento del reporte (mismo formato que el editor / reporte real) --}}
  @php
    $rptImgs = ($galImagenes ?? collect())->where('estudio_id', $rpt->estudio_id)->take(8)->values();
    $rptFirma = $rpt->usuario?->name ?? $rpt->estudio?->medico ?? $paciente?->medico ?? 'Dr. Nombre del médico';
    $rptFechaEstudio = format_user_date($rpt->estudio?->fecha ?? $rpt->created_at) ?: '';
    $rptNac = format_user_date($paciente?->fecha_nacimiento) ?: '';
  @endphp
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
          <span class="cell"><img src="{{ media_url($img->path) }}" alt="Imagen del estudio"></span>
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
@endunless

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
      <a class="ns-option" href="{{ route('nuevo-estudio.importar', ['paciente_id' => $paciente?->id]) }}">
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

@if($isStudyDetail)
{{-- Modal Compartir Estudio --}}
<div class="ns-modal-backdrop" id="studyMailBackdrop" data-send-url="{{ route('nuevo-estudio.correo.send', $estudio) }}">
  <div class="ns-modal" id="studyMailModal">
    <form id="studyMailForm">
      <div class="ns-modal-header">
        <div>
          <div class="ns-modal-title">Compartir estudio por correo</div>
          <div class="ns-modal-subtitle">Envia reportes, capturas y videos de este estudio juntos.</div>
        </div>
        <button class="ns-modal-close" type="button" id="studyMailClose" aria-label="Cerrar">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>
      <div class="ns-modal-body">
        <div class="ns-summary">
          <span>{{ $reportes->count() }} reporte(s)</span>
          <span>{{ $galImagenes->count() }} captura(s)</span>
          <span>{{ $galVideos->count() }} video(s)</span>
        </div>

        <div class="ns-field">
          <label for="studyMailRecipients">Destinatarios</label>
          <input id="studyMailRecipients" name="recipients" type="text" value="{{ $paciente?->email }}" placeholder="correo@ejemplo.com, otro@ejemplo.com" autocomplete="email" required>
        </div>

        <div class="ns-field">
          <label for="studyMailSubject">Asunto</label>
          <input id="studyMailSubject" name="subject" type="text" value="{{ $studyShareSubject }}" required>
        </div>

        <div class="ns-field">
          <label for="studyMailMessage">Mensaje</label>
          <textarea id="studyMailMessage" name="message" required>Hola, te comparto el estudio de {{ $galNombre }} con sus reportes, capturas y videos.</textarea>
        </div>

        <div class="ns-status" id="studyMailStatus"></div>

        <div class="ns-modal-actions">
          <button class="np-back-btn" type="button" id="studyMailCancel">Cancelar</button>
          <button class="np-new-study-btn" type="submit" id="studyMailSubmit" @unless($studyShareHasContent) disabled @endunless>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="5" width="18" height="14" rx="2"/>
              <path d="m3 7 9 6 9-6"/>
            </svg>
            Enviar correo
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endif

{{-- Modal de conexión con Tauri --}}
<div class="ns-modal-backdrop" id="dispositivoModalBackdrop">
  <div class="ns-modal tauri-modal">
    <div class="ns-modal-header">
      <div>
        <div class="ns-modal-title">Conectar Tauri</div>
        <div class="ns-modal-subtitle">
          Genera un código temporal para vincular esta computadora con el estudio del paciente.
        </div>
      </div>

      <button class="ns-modal-close" type="button" id="dispositivoModalClose" aria-label="Cerrar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    <div class="ns-modal-body">

      <div class="tauri-intro-card">
        <div class="tauri-intro-icon">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="12" rx="2"/>
            <path d="M8 20h8"/>
            <path d="M12 16v4"/>
          </svg>
        </div>

        <div>
          <div class="tauri-intro-title">Aplicación de captura Tauri</div>
          <div class="tauri-intro-text">
            Abre la app de escritorio, pega el código y selecciona el capturador USB.
          </div>
        </div>
      </div>

      <div id="tauriCodeBox" class="tauri-code-box">
        <div class="tauri-code-label">Código de conexión</div>
        <div id="tauriCodeValue" class="tauri-code-value">------</div>
        <div id="tauriCodeExpire" class="tauri-code-expire">
          Vence en 10 minutos
        </div>
      </div>

      <div class="tauri-help-box">
        Este código es de un solo uso. Queda ligado al usuario, tenant y paciente actual.
        La app Tauri podrá enviar vista en vivo, capturas y video a Laravel.
      </div>

      <button
        type="button"
        class="np-new-study-btn tauri-generate-btn"
        id="btnGenerarCodigoTauri"
        @unless($paciente) disabled @endunless
      >
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 5v14"/>
          <path d="M5 12h14"/>
        </svg>
        Generar código para Tauri
      </button>

      @unless($paciente)
        <div class="tauri-warning-box">
          Primero selecciona un paciente para poder generar el código de conexión.
        </div>
      @endunless

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
    var title   = document.getElementById('npResultsTitle');
    if (!input || !results || !list) return;

    function normalize(str){
      return (str || '').toString().normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }

    function openPaciente(p) {
      window.location.href = '{{ route('nuevo-estudio') }}?paciente=' + encodeURIComponent(p.id);
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
        el.setAttribute('role', 'button');
        el.setAttribute('tabindex', '0');

        var avatar = document.createElement('div');
        avatar.className = 'np-res-av';
        if (p.foto) {
          var img = document.createElement('img');
          img.src = p.foto;
          img.alt = p.nombre;
          img.style.width = '100%';
          img.style.height = '100%';
          img.style.objectFit = 'cover';
          img.style.borderRadius = '50%';
          avatar.appendChild(img);
        } else {
          avatar.textContent = p.iniciales;
        }

        var info = document.createElement('div');
        info.className = 'np-res-info';
        var name = document.createElement('div');
        name.className = 'np-res-name';
        name.textContent = p.nombre;
        var metaText = [p.folio ? 'Folio ' + p.folio : '', p.edad ? p.edad + ' años' : '', p.sexo, p.telefono].filter(Boolean).join(' · ');
        var meta = document.createElement('div');
        meta.className = 'np-res-meta';
        meta.textContent = metaText || 'Sin información adicional';
        info.appendChild(name);
        info.appendChild(meta);

        el.appendChild(avatar);
        el.appendChild(info);
        el.addEventListener('click', function(){ openPaciente(p); });
        el.addEventListener('keydown', function(e){
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            openPaciente(p);
          }
        });
        list.appendChild(el);
      });
    }

    function search(q){
      var term = normalize(q).trim();
      var filtered = term
        ? PACIENTES.filter(function(p){
            return normalize(p.nombre).includes(term)
              || normalize(p.folio).includes(term)
              || normalize(p.telefono).includes(term)
              || normalize(p.email).includes(term);
          })
        : PACIENTES;
      if (title) {
        title.textContent = term ? 'Resultados' : 'Pacientes registrados';
      }
      renderItems(filtered);
      results.classList.add('open');
    }

    var debounce;
    input.addEventListener('input', function(){
      clearTimeout(debounce);
      var val = this.value;
      debounce = setTimeout(function(){ search(val); }, 150);
    });
    input.addEventListener('focus', function(){ search(this.value); });
    search('');
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

  function setupReportPrint(){
    var btn = document.querySelector('[data-print-report]');
    if (!btn) return;

    var restorePrintMode = function(){
      document.body.classList.remove('print-report-only');
      window.removeEventListener('afterprint', restorePrintMode);
    };

    btn.addEventListener('click', function(){
      if (!document.getElementById('rptDoc')) {
        window.print();
        return;
      }

      document.body.classList.add('print-report-only');
      window.removeEventListener('afterprint', restorePrintMode);
      window.addEventListener('afterprint', restorePrintMode);
      window.print();
    });
  }
  setupReportPrint();

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

  /* Modal Compartir Estudio */
  const studyMailBackdrop = document.getElementById('studyMailBackdrop');
  const studyMailBtn = document.getElementById('npShareStudyBtn');
  const studyMailClose = document.getElementById('studyMailClose');
  const studyMailCancel = document.getElementById('studyMailCancel');
  const studyMailForm = document.getElementById('studyMailForm');
  const studyMailSubmit = document.getElementById('studyMailSubmit');
  const studyMailStatus = document.getElementById('studyMailStatus');
  const studyMailCanSubmit = studyMailSubmit ? !studyMailSubmit.hasAttribute('disabled') : false;

  function openStudyMailModal() {
    if (!studyMailBackdrop) return;
    studyMailBackdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
    studyMailStatus?.classList.remove('show', 'ok', 'error');
    setTimeout(function(){
      document.getElementById('studyMailRecipients')?.focus();
    }, 80);
  }

  function closeStudyMailModal() {
    if (!studyMailBackdrop) return;
    studyMailBackdrop.classList.remove('open');
    document.body.style.overflow = '';
  }

  function setStudyMailLoading(isLoading) {
    if (!studyMailSubmit) return;
    studyMailSubmit.disabled = isLoading || !studyMailCanSubmit;
    studyMailSubmit.style.opacity = isLoading ? '.72' : '';
    studyMailSubmit.innerHTML = isLoading
      ? 'Enviando...'
      : `
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="5" width="18" height="14" rx="2"/>
          <path d="m3 7 9 6 9-6"/>
        </svg>
        Enviar correo
      `;
  }

  function showStudyMailStatus(message, type) {
    if (!studyMailStatus) return;
    studyMailStatus.textContent = message;
    studyMailStatus.classList.remove('ok', 'error');
    studyMailStatus.classList.add('show', type === 'ok' ? 'ok' : 'error');
  }

  studyMailBtn?.addEventListener('click', function(e){
    e.preventDefault();
    if (studyMailBtn.disabled) return;
    openStudyMailModal();
  });
  studyMailClose?.addEventListener('click', closeStudyMailModal);
  studyMailCancel?.addEventListener('click', closeStudyMailModal);
  studyMailBackdrop?.addEventListener('click', function(e){
    if (e.target === studyMailBackdrop) closeStudyMailModal();
  });
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape' && studyMailBackdrop?.classList.contains('open')) closeStudyMailModal();
  });
  studyMailForm?.addEventListener('submit', async function(e){
    e.preventDefault();
    const sendUrl = studyMailBackdrop?.dataset.sendUrl;
    if (!sendUrl) return;

    const formData = new FormData(studyMailForm);
    setStudyMailLoading(true);
    studyMailStatus?.classList.remove('show', 'ok', 'error');

    try {
      const response = await fetch(sendUrl, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({
          recipients: formData.get('recipients'),
          subject: formData.get('subject'),
          message: formData.get('message')
        })
      });

      const data = await response.json().catch(function(){ return {}; });
      if (!response.ok) {
        throw new Error(data.message || 'No se pudo enviar el estudio.');
      }

      showStudyMailStatus(data.message || 'Estudio enviado correctamente.', 'ok');
    } catch (error) {
      showStudyMailStatus(error.message || 'No se pudo enviar el estudio.', 'error');
    } finally {
      setStudyMailLoading(false);
    }
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

  /* Modal de conexión con Tauri */
  var dispositivoModalBackdrop = document.getElementById('dispositivoModalBackdrop');
  var dispositivoModalClose = document.getElementById('dispositivoModalClose');
  var btnGenerarCodigoTauri = document.getElementById('btnGenerarCodigoTauri');
  var tauriCodeBox = document.getElementById('tauriCodeBox');
  var tauriCodeValue = document.getElementById('tauriCodeValue');
  var tauriCodeExpire = document.getElementById('tauriCodeExpire');

  function openDispositivoModal() {
    if (dispositivoModalBackdrop) {
      dispositivoModalBackdrop.classList.add('open');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeDispositivoModal() {
    if (dispositivoModalBackdrop) {
      dispositivoModalBackdrop.classList.remove('open');
      document.body.style.overflow = '';
    }
  }

  function setTauriGenerateButtonLoading(isLoading) {
    if (!btnGenerarCodigoTauri) return;

    if (isLoading) {
      btnGenerarCodigoTauri.disabled = true;
      btnGenerarCodigoTauri.style.opacity = '.7';
      btnGenerarCodigoTauri.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2v4"/>
          <path d="M12 18v4"/>
          <path d="m4.93 4.93 2.83 2.83"/>
          <path d="m16.24 16.24 2.83 2.83"/>
          <path d="M2 12h4"/>
          <path d="M18 12h4"/>
          <path d="m4.93 19.07 2.83-2.83"/>
          <path d="m16.24 7.76 2.83-2.83"/>
        </svg>
        Generando código...
      `;
      return;
    }

    btnGenerarCodigoTauri.disabled = false;
    btnGenerarCodigoTauri.style.opacity = '1';
    btnGenerarCodigoTauri.innerHTML = `
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 5v14"/>
        <path d="M5 12h14"/>
      </svg>
      Generar nuevo código
    `;
  }

  async function generarCodigoTauri() {
    try {
      if (!btnGenerarCodigoTauri) return;

      setTauriGenerateButtonLoading(true);

      const response = await fetch("{{ route('capture.pairing-code.store') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({
          paciente_id: "{{ $paciente?->id }}",
          estudio_id: @json($estudio?->id)
        })
      });

      const data = await response.json();

      if (!response.ok || !data.ok) {
        throw new Error(data.message || 'No se pudo generar el código.');
      }

      if (tauriCodeBox) {
        tauriCodeBox.style.display = 'block';
      }

      if (tauriCodeValue) {
        tauriCodeValue.textContent = data.data.code;
      }

      if (tauriCodeExpire) {
        tauriCodeExpire.textContent = 'Vence: ' + data.data.expires_at;
      }

      setTauriGenerateButtonLoading(false);
    } catch (error) {
      alert(error.message || 'Error generando código para Tauri.');

      if (btnGenerarCodigoTauri) {
        btnGenerarCodigoTauri.disabled = false;
        btnGenerarCodigoTauri.style.opacity = '1';
        btnGenerarCodigoTauri.innerHTML = `
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14"/>
            <path d="M5 12h14"/>
          </svg>
          Generar código para Tauri
        `;
      }
    }
  }

  window.openDispositivoModal = openDispositivoModal;
  window.closeDispositivoModal = closeDispositivoModal;

  dispositivoModalClose?.addEventListener('click', closeDispositivoModal);

  dispositivoModalBackdrop?.addEventListener('click', function(e){
    if (e.target === dispositivoModalBackdrop) closeDispositivoModal();
  });

  btnGenerarCodigoTauri?.addEventListener('click', generarCodigoTauri);

})();
</script>
@endpush
