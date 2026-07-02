<style>
/* ===== CAPTURAS ===== */
.cap-toolbar {
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
  padding: 10px 18px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font-size: 14px; font-weight: 600; color: var(--txt);
  cursor: pointer; text-decoration: none;
  transition: background-color 150ms ease;
}
.btn-regresar:hover { background: var(--card); }

.btn-filtrar {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 18px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font-size: 14px; font-weight: 600; color: var(--txt);
  cursor: pointer; transition: background-color 150ms ease;
}
.btn-filtrar:hover { background: var(--card); }

.btn-agregar-cap {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 20px; border-radius: var(--r-md);
  border: 1px solid rgba(46,123,246,.5);
  background: rgba(46,123,246,.12);
  font-size: 14px; font-weight: 700; color: var(--blue);
  cursor: pointer; transition: background-color 150ms ease;
}
.btn-agregar-cap:hover { background: rgba(46,123,246,.22); }
.cap-toolbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }

/* Layout */
.cap-layout {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 18px; align-items: start;
}

/* Card izquierda */
.cap-card {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke); border-radius: var(--r-lg);
  padding: 0; overflow: hidden;
}
.pac-label { font-size: 13.5px; color: var(--txt-soft); margin-bottom: 4px; }
.cap-title  { font-family: 'Sora', sans-serif; font-size: 26px; font-weight: 700; margin-bottom: 18px; }

.cap-search-bar {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 18px; border-bottom: 1px solid var(--stroke); gap: 14px;
}
.cap-search-box {
  display: flex; align-items: center; gap: 10px;
  background: var(--panel-2); border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md); padding: 9px 14px; flex: 1; max-width: 320px;
}
.cap-search-box input {
  background: none; border: none; outline: none;
  font: inherit; font-size: 14px; color: var(--txt); width: 100%;
}
.cap-search-box input::placeholder { color: var(--off); }
.cap-search-box svg { color: var(--txt-soft); flex: none; }

.sort-wrap {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; color: var(--txt-soft);
}
.sort-select {
  appearance: none; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  padding: 8px 30px 8px 12px; font: inherit; font-size: 13px;
  color: var(--txt); cursor: pointer; outline: none;
}
.sort-select-wrap { position: relative; }
.sort-chev { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); color: var(--txt-soft); pointer-events: none; }

/* Lista capturas */
.cap-list { padding: 0; }
.cap-item {
  display: flex; align-items: center; gap: 16px;
  padding: 16px 18px; border-bottom: 1px solid rgba(110,160,255,.07);
  cursor: pointer; transition: background-color 150ms ease; position: relative;
}
.cap-item:last-child { border-bottom: 0; }
.cap-item:hover { background: rgba(46,123,246,.05); }
.cap-item.active { background: rgba(46,123,246,.10); }

.cap-thumb {
  width: 110px; height: 76px; border-radius: 8px;
  overflow: hidden; flex: none; border: 1px solid var(--stroke-strong);
}
.cap-thumb img { width: 100%; height: 100%; object-fit: cover; }

.cap-info { flex: 1; min-width: 0; }
.cap-nombre { font-size: 15px; font-weight: 700; margin-bottom: 6px; }
.cap-date {
  display: flex; align-items: center; gap: 6px;
  font-size: 12.5px; color: var(--txt-soft); margin-bottom: 5px;
}
.cap-tipo { font-size: 12px; color: var(--off); }

.cap-more {
  background: none; border: none; color: var(--txt-soft);
  font-size: 18px; font-weight: 700; cursor: pointer;
  padding: 4px 8px; border-radius: var(--r-md);
  transition: background-color 150ms ease; line-height: 1;
}
.cap-more:hover { background: rgba(110,160,255,.1); color: var(--txt); }

.cap-footer {
  padding: 14px 18px; font-size: 13px; color: var(--txt-soft);
  border-top: 1px solid var(--stroke);
}

/* Panel derecho: vista previa */
.cap-preview-card {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke); border-radius: var(--r-lg);
  padding: 20px; display: flex; flex-direction: column; gap: 16px;
}
.prev-title { font-size: 15px; font-weight: 700; margin-bottom: 2px; }
.prev-img-box {
  width: 100%; aspect-ratio: 16/9; border-radius: 10px;
  overflow: hidden; border: 1px solid var(--stroke-strong);
  background: var(--panel-2);
}
.prev-img-box img { width: 100%; height: 100%; object-fit: cover; }

.info-section-title { font-size: 12px; font-weight: 700; color: var(--txt-soft); letter-spacing: .05em; margin-bottom: 10px; }
.info-row {
  display: flex; justify-content: space-between;
  font-size: 12.5px; padding: 5px 0;
  border-bottom: 1px solid rgba(110,160,255,.07);
}
.info-row:last-child { border-bottom: 0; }
.info-row .lbl { color: var(--txt-soft); }
.info-row .val { font-weight: 600; text-align: right; }

.accs-title { font-size: 12px; font-weight: 700; color: var(--txt-soft); letter-spacing: .05em; margin-bottom: 10px; }
.accs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.acc-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  padding: 11px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font: inherit; font-size: 13.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; transition: background-color 150ms ease, transform 150ms ease;
}
.acc-btn:hover { background: var(--card); }
.acc-btn:active { transform: scale(.97); }
.acc-btn.danger { color: #f87171; border-color: rgba(248,113,113,.3); }
.acc-btn.danger:hover { background: rgba(248,113,113,.08); }
.acc-btn svg { flex: none; }

@media(max-width:900px){
  .cap-layout { grid-template-columns: 1fr; }
}

/* ================= TEMA CLARO ================= */
html[data-theme="light"] .btn-agregar-cap { background: rgba(46,123,246,.12); border-color: rgba(46,123,246,.5); }
html[data-theme="light"] .btn-agregar-cap:hover { background: rgba(46,123,246,.22); }
html[data-theme="light"] .cap-item { border-color: var(--stroke); }
html[data-theme="light"] .cap-item:hover { background: var(--hover-bg); }
html[data-theme="light"] .cap-item.active { background: var(--hover-bg-strong); }
html[data-theme="light"] .cap-more:hover { background: var(--hover-bg); }
html[data-theme="light"] .info-row { border-color: var(--stroke); }
html[data-theme="light"] .acc-btn.danger { color: #dc2626; border-color: rgba(220,38,38,.3); }
html[data-theme="light"] .acc-btn.danger:hover { background: rgba(220,38,38,.08); }
</style>
