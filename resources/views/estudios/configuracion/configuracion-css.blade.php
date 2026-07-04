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
