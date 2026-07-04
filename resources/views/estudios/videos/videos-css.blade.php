<style>
/* ===== GESTIÓN DE VIDEOS ===== */
.vid-shell { max-width: 1100px; }

/* Toolbar */
.vid-toolbar {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 22px; flex-wrap: wrap;
}
.vid-back {
  height: 44px; display: inline-flex; align-items: center; gap: 8px; padding: 0 16px;
  border: 1px solid var(--stroke); border-radius: 10px; background: var(--panel-2);
  color: var(--txt); font-size: 13px; font-weight: 700; cursor: pointer;
  transition: background-color 150ms, border-color 150ms; text-decoration: none;
  font-family: 'Hanken Grotesk', sans-serif;
}
.vid-back:hover { background: var(--panel); border-color: var(--blue); }
.vid-title { font-size: 18px; font-weight: 700; color: var(--txt); margin-left: auto; font-family: 'Hanken Grotesk', sans-serif; }

/* Zona de carga */
.vid-dropzone {
  border: 2px dashed rgba(46,123,246,.35); border-radius: 18px;
  background: rgba(46,123,246,.04);
  padding: 40px 24px; text-align: center; cursor: pointer;
  transition: border-color 150ms, background-color 150ms, transform 150ms;
  margin-bottom: 24px;
}
.vid-dropzone:hover, .vid-dropzone.dragover {
  border-color: var(--blue); background: rgba(46,123,246,.1); transform: translateY(-1px);
}
.vid-dropzone input[type=file] { display: none; }
.vid-dropzone-icon {
  width: 60px; height: 60px; border-radius: 16px;
  background: rgba(46,123,246,.12); color: var(--blue);
  display: grid; place-items: center; margin: 0 auto 14px;
}
.vid-dropzone-title { font-size: 16px; font-weight: 700; color: var(--txt); margin-bottom: 5px; font-family: 'Hanken Grotesk', sans-serif; }
.vid-dropzone-desc { font-size: 13px; color: var(--txt-soft); margin-bottom: 14px; font-family: 'Hanken Grotesk', sans-serif; }
.vid-dropzone-hint { font-size: 12px; color: var(--txt-soft); opacity: .7; font-family: 'Hanken Grotesk', sans-serif; }

/* Layout: reproductor + lista */
.vid-main {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 20px;
  align-items: start;
}

/* Reproductor */
.vid-player-wrap {
  background: var(--panel-2);
  border: 1px solid var(--stroke);
  border-radius: 16px;
  overflow: hidden;
}
.vid-player-screen {
  position: relative;
  background: #000;
  aspect-ratio: 16/9;
  display: flex; align-items: center; justify-content: center;
}
.vid-player-screen video {
  width: 100%; height: 100%; object-fit: contain;
}
.vid-player-empty {
  display: flex; flex-direction: column; align-items: center; gap: 12px;
  color: rgba(255,255,255,.3); font-size: 13px; font-family: 'Hanken Grotesk', sans-serif;
}
.vid-player-empty svg { opacity: .3; }

/* Controles del reproductor */
.vid-controls {
  padding: 14px 18px;
  display: flex; flex-direction: column; gap: 10px;
  background: var(--panel-2);
}
.vid-progress-wrap {
  position: relative; height: 5px;
  background: var(--stroke-strong); border-radius: 4px; cursor: pointer;
}
.vid-progress-fill {
  height: 100%; background: var(--blue); border-radius: 4px;
  width: 0%; transition: width 100ms linear;
}
.vid-progress-thumb {
  position: absolute; top: 50%; translate: 0 -50%;
  width: 13px; height: 13px; border-radius: 50%;
  background: var(--blue); left: 0%; margin-left: -6px;
  transition: left 100ms linear;
}
.vid-ctrl-row {
  display: flex; align-items: center; gap: 8px;
}
.vid-ctrl-btn {
  width: 34px; height: 34px; border-radius: 8px;
  background: none; border: none; cursor: pointer;
  color: var(--txt-soft); display: grid; place-items: center;
  transition: background 150ms, color 150ms;
  flex: none;
}
.vid-ctrl-btn:hover { background: var(--hover-bg); color: var(--txt); }
.vid-ctrl-btn--play {
  width: 40px; height: 40px; border-radius: 50%;
  background: var(--blue); color: #fff;
  box-shadow: 0 0 12px rgba(46,123,246,.4);
  transition: background 150ms, transform 100ms;
}
.vid-ctrl-btn--play:hover { background: var(--cyan); transform: scale(1.06); }
.vid-ctrl-btn--play svg { color: #fff; }
.vid-time {
  font-size: 12px; color: var(--txt-soft); font-family: 'Courier New', monospace;
  margin: 0 4px; flex: none;
}
.vid-vol-wrap { display: flex; align-items: center; gap: 6px; margin-left: auto; }
.vid-vol-slider { width: 70px; accent-color: var(--blue); cursor: pointer; }
.vid-speed-btn {
  font-size: 11.5px; font-weight: 700; color: var(--txt-soft);
  padding: 3px 8px; border-radius: 6px;
  border: 1px solid var(--stroke); background: transparent; cursor: pointer;
  font-family: 'Hanken Grotesk', sans-serif;
  transition: all 150ms;
}
.vid-speed-btn:hover { border-color: var(--blue); color: var(--blue); }

/* Info del video activo */
.vid-info-bar {
  padding: 12px 18px;
  border-top: 1px solid var(--stroke);
  display: flex; align-items: center; justify-content: space-between; gap: 12px;
  background: var(--panel-2);
}
.vid-info-name {
  font-size: 13px; font-weight: 700; color: var(--txt);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  max-width: 300px; font-family: 'Hanken Grotesk', sans-serif;
}
.vid-info-meta {
  font-size: 11.5px; color: var(--txt-soft); font-family: 'Hanken Grotesk', sans-serif;
}
.vid-info-actions { display: flex; align-items: center; gap: 6px; flex: none; }
.vid-act-btn {
  display: inline-flex; align-items: center; gap: 6px;
  height: 34px; padding: 0 14px; border-radius: 8px;
  font-size: 12px; font-weight: 600; cursor: pointer;
  transition: all 150ms; font-family: 'Hanken Grotesk', sans-serif;
}
.vid-act-btn--secondary {
  background: var(--panel); border: 1px solid var(--stroke); color: var(--txt-soft);
}
.vid-act-btn--secondary:hover { border-color: var(--blue); color: var(--blue); background: var(--hover-bg); }
.vid-act-btn--danger {
  background: rgba(220,38,38,.08); border: 1px solid rgba(220,38,38,.3); color: #ef4444;
}
.vid-act-btn--danger:hover { background: rgba(220,38,38,.15); border-color: #ef4444; }
.vid-act-btn--primary {
  background: var(--blue); border: 1px solid var(--blue); color: #fff;
}
.vid-act-btn--primary:hover { background: var(--cyan); border-color: var(--cyan); }

/* Panel lateral - lista de videos */
.vid-list-panel {
  background: var(--panel-2);
  border: 1px solid var(--stroke);
  border-radius: 16px;
  overflow: hidden;
  display: flex; flex-direction: column;
}
.vid-list-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 16px;
  border-bottom: 1px solid var(--stroke);
}
.vid-list-title {
  font-size: 13px; font-weight: 700; color: var(--txt);
  font-family: 'Hanken Grotesk', sans-serif;
}
.vid-list-count {
  font-size: 11.5px; color: var(--txt-soft);
  background: var(--panel); border: 1px solid var(--stroke);
  border-radius: 20px; padding: 2px 10px;
  font-family: 'Hanken Grotesk', sans-serif;
}

/* Buscador */
.vid-search-wrap {
  padding: 10px 12px;
  border-bottom: 1px solid var(--stroke);
}
.vid-search {
  width: 100%; background: var(--panel);
  border: 1px solid var(--stroke-strong); border-radius: 8px;
  padding: 8px 12px 8px 34px; font-family: 'Hanken Grotesk', sans-serif;
  font-size: 13px; color: var(--txt); outline: none; box-sizing: border-box;
  transition: border-color 150ms;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: 10px center;
}
.vid-search:focus { border-color: var(--blue); }

/* Lista de items */
.vid-list-scroll {
  overflow-y: auto;
  max-height: 460px;
  flex: 1;
}
.vid-list-empty {
  padding: 40px 20px; text-align: center;
  color: var(--txt-soft); font-size: 13px;
  font-family: 'Hanken Grotesk', sans-serif;
}
.vid-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 14px; cursor: pointer;
  border-bottom: 1px solid var(--stroke);
  transition: background 150ms;
  position: relative;
}
.vid-item:last-child { border-bottom: none; }
.vid-item:hover { background: var(--hover-bg); }
.vid-item.active { background: rgba(46,123,246,.1); }
.vid-item.active::before {
  content: '';
  position: absolute; left: 0; top: 0; bottom: 0;
  width: 3px; background: var(--blue); border-radius: 0 2px 2px 0;
}
.vid-item-thumb {
  width: 60px; height: 42px; border-radius: 8px;
  background: var(--panel); border: 1px solid var(--stroke);
  display: grid; place-items: center; flex: none; overflow: hidden;
  position: relative;
}
.vid-item-thumb video {
  width: 100%; height: 100%; object-fit: cover;
}
.vid-item-thumb-icon { color: var(--blue); }
.vid-item-play-overlay {
  position: absolute; inset: 0;
  background: rgba(0,0,0,.35);
  display: grid; place-items: center;
  opacity: 0; transition: opacity 150ms;
  border-radius: 7px;
}
.vid-item:hover .vid-item-play-overlay { opacity: 1; }
.vid-item.active .vid-item-play-overlay { opacity: 1; background: rgba(46,123,246,.4); }
.vid-item-info { flex: 1; min-width: 0; }
.vid-item-name {
  font-size: 12.5px; font-weight: 600; color: var(--txt);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  font-family: 'Hanken Grotesk', sans-serif;
}
.vid-item-meta {
  font-size: 11px; color: var(--txt-soft); margin-top: 2px;
  font-family: 'Hanken Grotesk', sans-serif;
}
.vid-item-del {
  width: 26px; height: 26px; border-radius: 6px;
  background: none; border: none; color: var(--txt-soft);
  display: grid; place-items: center; cursor: pointer;
  opacity: 0; transition: opacity 150ms, background 150ms, color 150ms;
  flex: none;
}
.vid-item:hover .vid-item-del { opacity: 1; }
.vid-item-del:hover { background: rgba(220,38,38,.1); color: #ef4444; }

/* Acciones bottom de lista */
.vid-list-footer {
  padding: 12px 14px;
  border-top: 1px solid var(--stroke);
  display: flex; gap: 8px;
}

/* Estadísticas */
.vid-stats {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;
  margin-top: 20px;
}
.vid-stat-card {
  background: var(--panel-2); border: 1px solid var(--stroke);
  border-radius: 12px; padding: 14px 16px;
  display: flex; align-items: center; gap: 12px;
}
.vid-stat-icon {
  width: 36px; height: 36px; border-radius: 10px;
  background: rgba(46,123,246,.12); color: var(--blue);
  display: grid; place-items: center; flex: none;
}
.vid-stat-icon.green { background: rgba(34,197,94,.12); color: #22c55e; }
.vid-stat-icon.orange { background: rgba(245,158,11,.12); color: #f59e0b; }
.vid-stat-label { font-size: 11.5px; color: var(--txt-soft); font-family: 'Hanken Grotesk', sans-serif; }
.vid-stat-value { font-size: 20px; font-weight: 700; color: var(--txt); font-family: 'Sora', sans-serif; }

/* Cargando */
.vid-uploading {
  display: none;
  align-items: center; gap: 10px;
  padding: 10px 16px;
  background: rgba(46,123,246,.08);
  border: 1px solid rgba(46,123,246,.25);
  border-radius: 10px;
  margin-bottom: 14px;
  font-size: 13px; color: var(--blue); font-family: 'Hanken Grotesk', sans-serif;
}
.vid-uploading.active { display: flex; }
.vid-upload-spinner {
  width: 16px; height: 16px; border-radius: 50%;
  border: 2px solid rgba(46,123,246,.2);
  border-top-color: var(--blue);
  animation: vidSpin 700ms linear infinite; flex: none;
}
@keyframes vidSpin { to { transform: rotate(360deg); } }

/* ================= TEMA CLARO ================= */
html[data-theme="light"] .vid-player-screen { background: #111; }
html[data-theme="light"] .vid-ctrl-btn--play { color: #fff; }
html[data-theme="light"] .vid-ctrl-btn--play svg { color: #fff; }
html[data-theme="light"] .vid-act-btn--primary { color: #fff; }

@media(max-width:860px) {
  .vid-main { grid-template-columns: 1fr; }
  .vid-list-scroll { max-height: 280px; }
  .vid-stats { grid-template-columns: 1fr 1fr; }
}
</style>
