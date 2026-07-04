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
