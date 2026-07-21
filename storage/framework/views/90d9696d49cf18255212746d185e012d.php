<style>
/* ============ ESTUDIOS — TABLA DE PACIENTES ============ */

.toolbar {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.search-box {
  display: flex;
  align-items: center;
  gap: 10px;
  flex: 1;
  min-width: 220px;
  max-width: 400px;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 10px 16px;
  transition: border-color 150ms ease, box-shadow 150ms ease;
}

.search-box:focus-within {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(46,123,246,.18);
}

.search-box svg { color: var(--txt-soft); flex: none; }

.search-box input {
  background: none;
  border: none;
  outline: none;
  font: inherit;
  font-size: 14px;
  color: var(--txt);
  width: 100%;
}

.search-box input::placeholder { color: var(--off); }

.btn-filter {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  background: var(--panel-2);
  font-size: 14px;
  font-weight: 600;
  color: var(--txt);
  cursor: pointer;
  transition: background-color 150ms ease, transform 160ms var(--ease-out);
}
.btn-filter:hover { background: var(--card); }
.btn-filter:active { transform: scale(.97); }

.btn-nuevo-estudio {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border-radius: var(--r-md);
  border: 1px solid rgba(56,199,244,.45);
  background: rgba(56,199,244,.08);
  font-size: 14px;
  font-weight: 700;
  color: var(--cyan);
  cursor: pointer;
  text-decoration: none;
  transition: background-color 150ms ease, transform 160ms var(--ease-out);
}
.btn-nuevo-estudio:hover { background: rgba(56,199,244,.16); }
.btn-nuevo-estudio:active { transform: scale(.97); }

/* Tabla */
.estudios-wrap {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  overflow: hidden;
}

.tbl-estudios {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
  min-width: 700px;
}

.tbl-estudios thead {
  background: rgba(46,123,246,.06);
  border-bottom: 1px solid var(--stroke);
}

.tbl-estudios th {
  padding: 14px 18px;
  text-align: left;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: .06em;
  color: var(--txt-soft);
  white-space: nowrap;
  user-select: none;
}

.tbl-estudios th.sortable { cursor: pointer; }
.tbl-estudios th.sortable:hover { color: var(--txt); }

.th-inner {
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.tbl-estudios td {
  padding: 14px 18px;
  border-bottom: 1px solid rgba(110,160,255,.07);
  vertical-align: middle;
}

.tbl-estudios tbody tr:last-child td { border-bottom: 0; }

.tbl-estudios tbody tr {
  transition: background-color 150ms ease;
}

@media (hover:hover) and (pointer:fine) {
  .tbl-estudios tbody tr:hover { background: rgba(110,160,255,.05); }
}

/* Celda paciente */
.pac-cell {
  display: flex;
  align-items: center;
  gap: 12px;
}

.pac-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: rgba(46,123,246,.25);
  border: 1px solid var(--stroke-strong);
  display: grid;
  place-items: center;
  font-size: 11px;
  font-weight: 700;
  color: var(--cyan);
  flex: none;
  font-family: 'Sora', sans-serif;
  letter-spacing: .03em;
}

.pac-name {
  font-weight: 700;
  font-size: 14px;
  line-height: 1.2;
}

.pac-meta {
  font-size: 12px;
  color: var(--txt-soft);
  margin-top: 2px;
}

/* Celda último estudio */
.est-fecha {
  font-size: 13.5px;
  font-weight: 600;
  line-height: 1.2;
}

.est-tipo {
  font-size: 12px;
  color: var(--txt-soft);
  margin-top: 2px;
}

/* Chips de estado */
.chip-completado {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 7px;
  font-size: 12px;
  font-weight: 700;
  color: var(--green);
  border: 1px solid rgba(61,220,151,.55);
  background: rgba(61,220,151,.1);
  white-space: nowrap;
}

.chip-espera {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 7px;
  font-size: 12px;
  font-weight: 700;
  color: var(--orange);
  border: 1px solid rgba(245,158,45,.55);
  background: rgba(245,158,45,.1);
  white-space: nowrap;
}

.chip-cancelado {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 7px;
  font-size: 12px;
  font-weight: 700;
  color: var(--red);
  border: 1px solid rgba(255,90,110,.55);
  background: rgba(255,90,110,.1);
  white-space: nowrap;
}

/* Botones de acción */
.acc-cell {
  display: flex;
  align-items: center;
  gap: 6px;
}

.btn-eye {
  width: 34px;
  height: 34px;
  display: grid;
  place-items: center;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke);
  background: var(--panel-2);
  color: var(--txt-soft);
  cursor: pointer;
  transition: border-color 150ms ease, color 150ms ease, transform 160ms var(--ease-out);
}
.btn-eye:hover { border-color: var(--stroke-strong); color: var(--cyan); }
.btn-eye:active { transform: scale(.93); }

.btn-more {
  width: 34px;
  height: 34px;
  display: grid;
  place-items: center;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke);
  background: var(--panel-2);
  color: var(--txt-soft);
  cursor: pointer;
  font-size: 18px;
  font-weight: 700;
  letter-spacing: 1px;
  transition: border-color 150ms ease, color 150ms ease, transform 160ms var(--ease-out);
}
.btn-more:hover { border-color: var(--stroke-strong); color: var(--txt); }
.btn-more:active { transform: scale(.93); }

/* Footer de tabla */
.tbl-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 18px;
  border-top: 1px solid var(--stroke);
  font-size: 13.5px;
  color: var(--txt-soft);
  flex-wrap: wrap;
  gap: 10px;
}

.tbl-footer a {
  font-weight: 700;
  color: var(--blue);
  transition: color 150ms ease;
}
.tbl-footer a:hover { color: var(--cyan); }

.tbl-scroll { overflow-x: auto; }

.tbl-estudios tbody tr.row-active {
  background: rgba(46,123,246,.12);
  outline: 1px solid rgba(46,123,246,.4);
}

.no-results td {
  text-align: center;
  padding: 32px 18px;
  color: var(--txt-soft);
  font-size: 14px;
}

/* ============ PANEL DESLIZABLE ============ */

.drawer-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.45);
  z-index: 100;
  opacity: 0;
  pointer-events: none;
  transition: opacity 300ms ease;
}
.drawer-overlay.open {
  opacity: 1;
  pointer-events: all;
}

.drawer {
  position: fixed;
  top: 0;
  right: 0;
  width: 360px;
  max-width: 95vw;
  height: 100vh;
  background: linear-gradient(180deg, var(--panel) 0%, var(--side-2) 100%);
  border-left: 1px solid var(--stroke);
  z-index: 101;
  display: flex;
  flex-direction: column;
  transform: translateX(100%);
  transition: transform 320ms cubic-bezier(0.23,1,0.32,1);
  overflow-y: auto;
}
.drawer.open {
  transform: translateX(0);
}

.drawer-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 22px 22px 18px;
  border-bottom: 1px solid var(--stroke);
  flex: none;
}
.drawer-header h2 {
  font-family: 'Sora', sans-serif;
  font-size: 16px;
  font-weight: 700;
}
.drawer-header p {
  font-size: 12.5px;
  color: var(--txt-soft);
  margin-top: 2px;
}
.btn-close-drawer {
  width: 34px;
  height: 34px;
  display: grid;
  place-items: center;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke);
  background: var(--panel-2);
  color: var(--txt-soft);
  cursor: pointer;
  flex: none;
  transition: color 150ms ease, border-color 150ms ease;
}
.btn-close-drawer:hover { color: var(--txt); border-color: var(--stroke-strong); }

.drawer-body {
  padding: 22px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.drawer-avatar-row {
  display: flex;
  align-items: center;
  gap: 14px;
}
.drawer-avatar {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: rgba(46,123,246,.25);
  border: 2px solid var(--stroke-strong);
  display: grid;
  place-items: center;
  font-size: 16px;
  font-weight: 700;
  color: var(--cyan);
  font-family: 'Sora', sans-serif;
  flex: none;
}
.drawer-name {
  font-size: 18px;
  font-weight: 700;
  line-height: 1.2;
}
.drawer-meta {
  font-size: 13px;
  color: var(--txt-soft);
  margin-top: 3px;
}

.drawer-section {
  border: 1px solid var(--stroke);
  border-radius: var(--r-md);
  overflow: hidden;
}
.drawer-section-title {
  padding: 10px 14px;
  font-size: 11.5px;
  font-weight: 700;
  letter-spacing: .06em;
  color: var(--txt-soft);
  background: rgba(46,123,246,.06);
  border-bottom: 1px solid var(--stroke);
}
.drawer-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 11px 14px;
  font-size: 13.5px;
  border-bottom: 1px solid rgba(110,160,255,.07);
}
.drawer-row:last-child { border-bottom: 0; }
.drawer-row .lbl { color: var(--txt-soft); font-size: 13px; }
.drawer-row .val { font-weight: 600; text-align: right; }

.drawer-actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
  flex: none;
}
.drawer-btn-primary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px;
  border-radius: var(--r-md);
  background: linear-gradient(135deg,#1668D9,var(--blue));
  color: #fff;
  font-size: 14px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  box-shadow: 0 8px 22px -8px rgba(46,123,246,.55);
  transition: opacity 150ms ease, transform 160ms var(--ease-out);
  text-decoration: none;
}
.drawer-btn-primary:hover { opacity: .9; }
.drawer-btn-primary:active { transform: scale(.97); }
.drawer-btn-secondary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 11px;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  background: transparent;
  color: var(--txt);
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 150ms ease, transform 160ms var(--ease-out);
}
.drawer-btn-secondary:hover { background: rgba(110,160,255,.08); }
.drawer-btn-secondary:active { transform: scale(.97); }

/* ═══════════════════════════════════════════════════════════
   INTERFAZ BÚSQUEDA DE PACIENTES
   ═══════════════════════════════════════════════════════════ */
.paciente-busqueda-wrapper {
  padding: 0 0 16px 0;
}

.paciente-busqueda-controls {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.paciente-search-box {
  flex: 1;
  min-width: 250px;
  position: relative;
}

.paciente-search-box svg {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--txt-soft);
  pointer-events: none;
}

.paciente-search-box input {
  width: 100%;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 12px 14px 12px 44px;
  font-family: inherit;
  font-size: 14px;
  color: var(--txt);
  outline: none;
  transition: border-color 150ms ease, box-shadow 150ms ease;
}

.paciente-search-box input::placeholder {
  color: var(--off);
}

.paciente-search-box input:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(46,123,246,.18);
}

.btn-filtrar {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  background: var(--panel-2);
  font-size: 14px;
  font-weight: 600;
  color: var(--txt);
  cursor: pointer;
  transition: background-color 150ms ease, border-color 150ms ease, transform 160ms var(--ease-out);
  white-space: nowrap;
}

.btn-filtrar svg {
  color: var(--cyan);
}

.btn-filtrar:hover {
  background: var(--card);
  border-color: var(--cyan);
}

.btn-filtrar:active {
  transform: scale(.97);
}

/* Menú desplegable de Filtrar */
.filtrar-dropdown-wrapper {
  position: relative;
}

.filtrar-menu {
  display: none;
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  background: var(--panel);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  min-width: 180px;
  z-index: 100;
  box-shadow: 0 8px 24px rgba(0,0,0,.35);
  overflow: hidden;
}

.filtrar-menu.active {
  display: block;
}

.filtrar-grupo {
  padding: 8px 0;
}

.filtrar-grupo-titulo {
  padding: 8px 16px;
  font-size: 11px;
  font-weight: 700;
  color: var(--txt-soft);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.filtrar-opcion {
  display: block;
  width: 100%;
  padding: 10px 16px;
  border: none;
  background: none;
  font-family: inherit;
  font-size: 13px;
  font-weight: 500;
  color: var(--txt);
  text-align: left;
  cursor: pointer;
  transition: background-color 150ms ease;
}

.filtrar-opcion:hover {
  background: rgba(110,160,255,.1);
}

.filtrar-opcion.active {
  background: rgba(46,123,246,.15);
  color: var(--cyan);
  font-weight: 600;
}

.filtrar-divider {
  height: 1px;
  background: var(--stroke);
  margin: 4px 0;
}

@media (max-width: 640px) {
  .paciente-busqueda-controls {
    flex-direction: column;
  }
  .paciente-search-box {
    min-width: 100%;
  }
  .btn-filtrar {
    justify-content: center;
  }
}

/* ═══════════════════════════════════════════════════════════
   FORMULARIO CREAR ESTUDIO (Interfaz Principal)
   ═══════════════════════════════════════════════════════════ */

/* Toolbar superior */
.crear-toolbar {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 0;
  flex-wrap: wrap;
}
.crear-toolbar .btn-tool {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  background: var(--panel-2);
  font-size: 14px;
  font-weight: 600;
  color: var(--txt);
  cursor: pointer;
  text-decoration: none;
  transition: background-color 150ms ease, transform 160ms var(--ease-out);
}
.crear-toolbar .btn-tool svg { color: var(--cyan); }
.crear-toolbar .btn-tool:hover { background: var(--card); }
.crear-toolbar .btn-tool:active { transform: scale(.97); }
.crear-toolbar .btn-back {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  background: var(--panel-2);
  font-size: 14px;
  font-weight: 600;
  color: var(--txt);
  cursor: pointer;
  text-decoration: none;
  margin-left: auto;
  transition: background-color 150ms ease, transform 160ms var(--ease-out);
}
.crear-toolbar .btn-back:hover { background: var(--card); }
.crear-toolbar .btn-back:active { transform: scale(.97); }

/* Layout principal */
.crear-layout {
  display: grid;
  grid-template-columns: 1fr 220px;
  gap: 22px;
  align-items: start;
}

/* Secciones del formulario */
.crear-card {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  padding: 28px 28px 24px;
}

.sec-title {
  font-family: 'Sora', sans-serif;
  font-size: 19px;
  font-weight: 700;
  margin-bottom: 22px;
  line-height: 1.2;
}

/* Grid de campos */
.fields-grid {
  display: grid;
  gap: 18px;
}
.fields-grid.cols-2 { grid-template-columns: 1fr 1fr; }
.fields-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
.fields-grid.cols-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }

.crear-card .field {
  display: flex;
  flex-direction: column;
  gap: 7px;
}
.crear-card .field label {
  font-size: 13px;
  font-weight: 600;
  color: var(--txt-soft);
}
.crear-card .field input,
.crear-card .field select,
.crear-card .field textarea {
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 11px 14px;
  font-family: inherit;
  font-size: 14px;
  color: var(--txt);
  outline: none;
  width: 100%;
  transition: border-color 150ms ease, box-shadow 150ms ease;
}
.crear-card .field input::placeholder,
.crear-card .field textarea::placeholder { color: var(--off); }
.crear-card .field input:focus,
.crear-card .field select:focus,
.crear-card .field textarea:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(46,123,246,.18);
}
.crear-card .field select option { background: var(--panel); color: var(--txt); }
.crear-card .field textarea { resize: vertical; min-height: 100px; }

/* Input con icono a la derecha */
.input-icon {
  position: relative;
}
.input-icon input { padding-right: 40px; }
.input-icon .ico {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--txt-soft);
  pointer-events: none;
}

/* Separador entre secciones */
.sec-divider {
  border: none;
  border-top: 1px solid var(--stroke);
  margin: 26px 0 22px;
}

/* Panel derecho: foto + botones */
.side-panel {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.foto-box {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  padding: 20px 16px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
}
.foto-circle {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  border: 2px dashed var(--stroke-strong);
  display: grid;
  place-items: center;
  overflow: hidden;
  cursor: pointer;
  transition: border-color 150ms ease;
  position: relative;
  background: var(--panel-2);
}
.foto-circle:hover { border-color: var(--blue); }
.foto-circle img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: none;
}
.foto-circle .foto-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  color: var(--txt-soft);
  font-size: 12px;
  text-align: center;
}
.btn-add-foto {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  font-weight: 600;
  color: var(--cyan);
  cursor: pointer;
  background: none;
  border: none;
}

.action-btns {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  overflow: hidden;
}
.action-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  padding: 13px 16px;
  border: none;
  border-bottom: 1px solid var(--stroke);
  background: none;
  font: inherit;
  font-size: 13.5px;
  font-weight: 600;
  color: var(--txt);
  cursor: pointer;
  transition: background-color 150ms ease;
  text-align: left;
  text-decoration: none;
}
.action-btn:last-child { border-bottom: 0; }
.action-btn:hover { background: rgba(110,160,255,.07); }
.action-btn .ab-icon {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  border: 1px solid var(--stroke-strong);
  display: grid;
  place-items: center;
  flex: none;
  color: var(--cyan);
  background: rgba(56,199,244,.08);
}

/* Footer guardar */
.crear-footer {
  display: flex;
  justify-content: flex-end;
  margin-top: 18px;
}
.btn-save {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 13px 28px;
  border-radius: var(--r-md);
  background: linear-gradient(135deg,#1668D9,var(--blue));
  color: #fff;
  font-size: 15px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  box-shadow: 0 8px 22px -8px rgba(46,123,246,.6);
  transition: opacity 150ms ease, transform 160ms var(--ease-out);
}
.btn-save:hover { opacity: .9; }
.btn-save:active { transform: scale(.97); }

@media (max-width:1100px) {
  .crear-layout { grid-template-columns: 1fr; }
  .fields-grid.cols-4 { grid-template-columns: 1fr 1fr; }
}
@media (max-width:640px) {
  .fields-grid.cols-2,
  .fields-grid.cols-3,
  .fields-grid.cols-4 { grid-template-columns: 1fr; }
}

/* ═══════════════════════════════════════════════════════════
   FORMULARIO DE INFORMACIÓN DEL PACIENTE
   ═══════════════════════════════════════════════════════════ */
.paciente-form-wrapper {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  padding: 28px;
}

/* Buscador de pacientes */
.paciente-search-section {
  margin-bottom: 28px;
}
.paciente-search-label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: var(--txt-soft);
  margin-bottom: 10px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.paciente-search-box {
  position: relative;
  max-width: 500px;
}
.paciente-search-box > svg {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--txt-soft);
  pointer-events: none;
}
.paciente-search-box input {
  width: 100%;
  padding: 12px 16px 12px 44px;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  color: var(--txt);
  font-size: 15px;
  outline: none;
  transition: border-color 150ms ease, box-shadow 150ms ease;
}
.paciente-search-box input:focus {
  border-color: var(--cyan);
  box-shadow: 0 0 0 3px rgba(46,123,246,.15);
}

/* Dropdown de resultados */
.paciente-search-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  margin-top: 8px;
  background: var(--card);
  border: 1px solid var(--stroke);
  border-radius: var(--r-md);
  box-shadow: 0 10px 40px -10px rgba(0,0,0,.4);
  max-height: 300px;
  overflow-y: auto;
  z-index: 100;
  display: none;
}
.paciente-search-dropdown.active {
  display: block;
}
.paciente-search-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  cursor: pointer;
  transition: background-color 150ms ease;
  border-bottom: 1px solid rgba(110,160,255,.07);
}
.paciente-search-item:last-child {
  border-bottom: none;
}
.paciente-search-item:hover {
  background: rgba(46,123,246,.08);
}
.paciente-search-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--blue), var(--cyan));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 700;
  color: #fff;
  flex-shrink: 0;
}
.paciente-search-name {
  font-size: 14px;
  font-weight: 600;
  color: var(--txt);
}
.paciente-search-meta {
  font-size: 12px;
  color: var(--txt-soft);
  margin-top: 2px;
}

/* Estado vacío */
.paciente-empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--txt-soft);
}
.paciente-empty-state svg {
  margin-bottom: 16px;
  opacity: 0.5;
}
.paciente-empty-state p {
  font-size: 15px;
}

/* Formulario de paciente */
.paciente-form-card {
  animation: fadeIn 300ms ease;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.paciente-form-title {
  font-family: 'Sora', sans-serif;
  font-size: 20px;
  font-weight: 700;
  color: var(--txt);
  margin: 0 0 24px 0;
}
.paciente-form-subtitle {
  font-family: 'Sora', sans-serif;
  font-size: 16px;
  font-weight: 700;
  color: var(--txt);
  margin: 0 0 20px 0;
}

.paciente-form-divider {
  height: 1px;
  background: var(--stroke);
  margin: 28px 0;
}

.paciente-form-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
}

.form-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.form-field.col-2 {
  grid-column: span 2;
}
.form-field.full-width {
  grid-column: span 4;
}

.form-field label {
  font-size: 12px;
  font-weight: 600;
  color: var(--txt-soft);
}
.form-field input,
.form-field select,
.form-field textarea {
  padding: 12px 14px;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  color: var(--txt);
  font-size: 14px;
  outline: none;
  transition: border-color 150ms ease;
  font-family: inherit;
}
.form-field input::placeholder,
.form-field textarea::placeholder {
  color: var(--off);
}
.form-field input:focus,
.form-field select:focus,
.form-field textarea:focus {
  border-color: var(--cyan);
}
.form-field input[readonly] {
  background: rgba(46,123,246,.05);
  color: var(--txt);
}
.form-field select {
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  padding-right: 36px;
}
.form-field textarea {
  resize: vertical;
  min-height: 80px;
}

/* Botones de acción del formulario */
.paciente-form-actions {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 28px;
  padding-top: 24px;
  border-top: 1px solid var(--stroke);
}

.btn-paciente-cancelar {
  padding: 12px 24px;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  background: transparent;
  color: var(--txt);
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 150ms ease;
}
.btn-paciente-cancelar:hover {
  background: rgba(110,160,255,.1);
}

.btn-paciente-guardar {
  padding: 12px 28px;
  border-radius: var(--r-md);
  border: none;
  background: linear-gradient(135deg, var(--blue), var(--cyan));
  color: #fff;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: opacity 150ms ease, transform 160ms var(--ease-out);
}
.btn-paciente-guardar:hover {
  opacity: .9;
}
.btn-paciente-guardar:active {
  transform: scale(.97);
}

/* Responsive formulario */
@media(max-width:900px){
  .paciente-form-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .form-field.col-2,
  .form-field.full-width {
    grid-column: span 2;
  }
}
@media(max-width:600px){
  .paciente-form-grid {
    grid-template-columns: 1fr;
  }
  .form-field.col-2,
  .form-field.full-width {
    grid-column: span 1;
  }
  .paciente-form-actions {
    flex-direction: column;
  }
  .paciente-form-actions button,
  .paciente-form-actions a {
    width: 100%;
    justify-content: center;
  }
}

/* ═══════════════════════════════════════════════════════════
   INTERFAZ AGREGAR CAPTURAS — Lista + Vista Previa
   ═══════════════════════════════════════════════════════════ */

/* Layout lista + preview */
.cap-view-layout {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 18px;
  align-items: start;
}
.cap-card-list {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  overflow: hidden;
}
.cap-search-bar-v {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 18px; border-bottom: 1px solid var(--stroke); gap: 14px;
}
.cap-search-box-v {
  display: flex; align-items: center; gap: 10px;
  background: var(--panel-2); border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md); padding: 9px 14px; flex: 1; max-width: 320px;
}
.cap-search-box-v input {
  background: none; border: none; outline: none;
  font: inherit; font-size: 14px; color: var(--txt); width: 100%;
}
.cap-search-box-v input::placeholder { color: var(--off); }
.cap-search-box-v svg { color: var(--txt-soft); flex: none; }
.cap-sort-wrap {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; color: var(--txt-soft);
}
.cap-sort-select-wrap { position: relative; }
.cap-sort-select {
  appearance: none; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  padding: 8px 30px 8px 12px; font: inherit; font-size: 13px;
  color: var(--txt); cursor: pointer; outline: none;
}
.cap-sort-chev { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); color: var(--txt-soft); pointer-events: none; }
.cap-list-v { padding: 0; }
.cap-item-v {
  display: flex; align-items: center; gap: 16px;
  padding: 16px 18px; border-bottom: 1px solid rgba(110,160,255,.07);
  cursor: pointer; transition: background-color 150ms ease; position: relative;
}
.cap-item-v:last-child { border-bottom: 0; }
.cap-item-v:hover { background: rgba(46,123,246,.05); }
.cap-item-v.active { background: rgba(46,123,246,.10); }
.cap-thumb-v {
  width: 110px; height: 76px; border-radius: 8px;
  overflow: hidden; flex: none; border: 1px solid var(--stroke-strong);
}
.cap-thumb-v img { width: 100%; height: 100%; object-fit: cover; }
.cap-info-v { flex: 1; min-width: 0; }
.cap-nombre-v { font-size: 15px; font-weight: 700; margin-bottom: 6px; }
.cap-date-v {
  display: flex; align-items: center; gap: 6px;
  font-size: 12.5px; color: var(--txt-soft); margin-bottom: 5px;
}
.cap-tipo-v { font-size: 12px; color: var(--off); }
.cap-thumb-v { position: relative; }
.cap-thumb-expand {
  display: none;
  position: absolute; inset: 0;
  background: rgba(0,0,0,.45);
  border-radius: 8px;
  align-items: center; justify-content: center;
  cursor: pointer;
  transition: opacity 150ms ease;
}
.cap-item-v.active .cap-thumb-expand { display: flex; }
.cap-thumb-expand svg { color: #fff; filter: drop-shadow(0 1px 3px rgba(0,0,0,.6)); }
.cap-thumb-expand:hover { background: rgba(0,0,0,.6); }
/* ══════════════════════════════════════════════════
   PANEL EXPORTAR PDF
   ══════════════════════════════════════════════════ */
.export-panel { display: none; gap: 20px; }
.export-panel.open { display: grid; grid-template-columns: 1fr 270px; align-items: stretch; gap: 16px; }

/* Área preview exportar */
.export-preview-area {
  background: var(--panel-2); border: 1px solid var(--stroke);
  border-radius: var(--r-lg); overflow: hidden; display: flex; flex-direction: column;
}
.export-preview-toolbar {
  display: flex; align-items: center; justify-content: space-between;
  padding: 10px 16px; border-bottom: 1px solid var(--stroke); gap: 12px; flex-shrink: 0;
}
.export-sheet-wrap {
  flex: 1; overflow: hidden; padding: 20px;
  display: flex; justify-content: center; align-items: flex-start;
  background: #151a27;
}
.export-sheet {
  width: 595px; min-height: 842px; background: #fff; border-radius: 3px;
  padding: 48px 52px; box-shadow: 0 4px 40px rgba(0,0,0,.55), 0 0 0 1px rgba(0,0,0,.25);
  color: #1a1a1a; display: flex; flex-direction: column;
  transform-origin: top center; transform: scale(0.68);
  transition: transform 200ms ease; margin-bottom: calc(842px * -0.32);
}
.export-sheet.landscape { width: 842px; min-height: 595px; margin-bottom: calc(595px * -0.32); }
.export-sheet-header {
  display: flex; justify-content: space-between; align-items: flex-end;
  margin-bottom: 20px; padding-bottom: 14px; border-bottom: 2px solid #1a3a6c;
}
.export-sheet-paciente { font-size: 18px; font-weight: 800; color: #0d1b3e; }
.export-sheet-meta { font-size: 10.5px; color: #6b7280; text-align: right; line-height: 1.5; }
.export-img-row { display: flex; gap: 18px; align-items: flex-start; padding: 16px 0; border-bottom: 1px solid #f0f0f0; }
.export-img-row:last-of-type { border-bottom: none; }
.export-img-thumb { width: 130px; height: 98px; border-radius: 6px; overflow: hidden; flex: none; border: 1px solid #e5e7eb; }
.export-img-thumb img { width: 100%; height: 100%; object-fit: cover; }
.export-img-info { flex: 1; padding-top: 2px; }
.export-img-title { font-size: 13.5px; font-weight: 700; color: #111827; margin-bottom: 4px; }
.export-img-desc-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #9ca3af; margin-bottom: 2px; }
.export-img-desc { font-size: 12px; color: #374151; margin-bottom: 4px; }
.export-img-date { font-size: 10.5px; color: #9ca3af; }
.export-sheet-footer { margin-top: auto; padding-top: 16px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }

/* Sidebar exportar */
.export-config-sidebar {
  box-sizing: border-box; overflow: visible;
  display: flex; flex-direction: column; height: 100%;
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke); border-radius: var(--r-lg);
  padding: 20px; gap: 14px;
  position: relative; z-index: 10;
}
.export-select-wrap {
  position: relative; display: flex; align-items: center;
  background: var(--panel-2); border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md); z-index: 20;
}
.export-config-title { font-size: 15px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
.export-config-title svg { color: var(--blue); }
.export-config-group { display: flex; flex-direction: column; gap: 6px; }
.export-config-label { font-size: 12px; font-weight: 600; color: var(--txt-soft); }
.export-check-row { display: flex; align-items: flex-start; gap: 10px; font-size: 13px; cursor: pointer; line-height: 1.4; }
.export-check-row input[type=checkbox] { accent-color: var(--blue); width: 15px; height: 15px; cursor: pointer; flex: none; margin-top: 2px; }
.export-orient-row { display: flex; gap: 8px; }
.export-orient-btn {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
  padding: 9px; border-radius: var(--r-md); border: 1px solid var(--stroke-strong);
  background: var(--panel-2); font: inherit; font-size: 12.5px; font-weight: 600;
  color: var(--txt); cursor: pointer; transition: all 150ms;
}
.export-orient-btn.active { border-color: var(--blue); background: rgba(46,123,246,.15); color: var(--blue); }
.export-orient-btn:hover:not(.active) { background: var(--card); }
.export-config-select {
  width: 100%; padding: 9px 32px 9px 12px; background: transparent;
  border: none; color: var(--txt); font: inherit; font-size: 13px; cursor: pointer;
  appearance: none; -webkit-appearance: none; outline: none;
}
.export-select-chev { position: absolute; right: 10px; pointer-events: none; color: var(--txt-soft); z-index: 21; }
.export-protect-row { display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer; }
.export-protect-row input[type=checkbox] { accent-color: var(--blue); width: 15px; height: 15px; }
.export-divider { height: 1px; background: var(--stroke); margin-top: auto; }
.export-actions { display: flex; gap: 10px; }
.export-preview-btn {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px;
  padding: 11px; border-radius: var(--r-md); border: 1px solid var(--stroke-strong);
  background: var(--panel-2); font: inherit; font-size: 13px; font-weight: 600;
  color: var(--txt); cursor: pointer; transition: background 150ms;
}
.export-preview-btn:hover { background: var(--card); }
.export-download-btn {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px;
  padding: 11px; border-radius: var(--r-md); border: none;
  background: var(--blue); font: inherit; font-size: 13px; font-weight: 700;
  color: #fff; cursor: pointer; transition: background 150ms;
}
.export-download-btn:hover { background: #1a6fe0; }

/* ── Carrusel Vista Previa ── */
.export-carousel-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.92); backdrop-filter: blur(8px);
  z-index: 600; flex-direction: column; align-items: center; justify-content: center; gap: 20px;
}
.export-carousel-overlay.open { display: flex; }
.export-carousel-header {
  display: flex; align-items: center; justify-content: space-between;
  width: 90%; max-width: 860px;
}
.export-carousel-title { font-size: 16px; font-weight: 700; color: #fff; }
.export-carousel-close {
  width: 36px; height: 36px; border-radius: 50%; border: 1px solid rgba(255,255,255,.2);
  background: rgba(255,255,255,.08); color: #fff; cursor: pointer;
  display: flex; align-items: center; justify-content: center; transition: background 150ms;
}
.export-carousel-close:hover { background: rgba(255,255,255,.2); }
.export-carousel-stage {
  position: relative; width: 90%; max-width: 860px;
  display: flex; align-items: center; justify-content: center; gap: 16px;
}
.export-carousel-arrow {
  width: 44px; height: 44px; border-radius: 50%; flex: none;
  border: 1px solid rgba(255,255,255,.25); background: rgba(255,255,255,.08);
  color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: background 150ms; flex-shrink: 0;
}
.export-carousel-arrow:hover { background: rgba(255,255,255,.2); }
.export-carousel-arrow:disabled { opacity: .3; cursor: default; }
.export-carousel-img-wrap {
  flex: 1; display: flex; justify-content: center; align-items: center;
  max-height: 56vh; overflow: hidden; border-radius: 10px;
}
.export-carousel-img-wrap img {
  max-width: 100%; max-height: 56vh; border-radius: 10px;
  box-shadow: 0 16px 48px rgba(0,0,0,.7); object-fit: contain;
}
.export-carousel-footer {
  display: flex; align-items: center; gap: 16px;
  color: rgba(255,255,255,.7); font-size: 13px;
}
.export-carousel-counter { font-weight: 600; color: #fff; }
.export-carousel-remove {
  display: flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: var(--r-md);
  border: 1px solid rgba(248,113,113,.4); background: rgba(248,113,113,.1);
  color: #f87171; font: inherit; font-size: 12.5px; font-weight: 600; cursor: pointer;
  transition: background 150ms;
}
.export-carousel-remove:hover { background: rgba(248,113,113,.22); }
.export-carousel-add {
  display: flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: var(--r-md);
  border: 1px solid rgba(46,123,246,.4); background: rgba(46,123,246,.1);
  color: var(--blue); font: inherit; font-size: 12.5px; font-weight: 600; cursor: pointer;
  transition: background 150ms;
}
.export-carousel-add:hover { background: rgba(46,123,246,.2); }
.export-carousel-dots { display: flex; gap: 6px; }
.export-carousel-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: rgba(255,255,255,.3); cursor: pointer; transition: background 150ms;
}
.export-carousel-dot.active { background: #fff; }
/* Hover overlay info en carrusel */
.export-carousel-img-wrap {
  position: relative;
  cursor: zoom-in;
}
.export-carousel-img-wrap img {
  transition: transform 300ms ease, box-shadow 300ms ease;
}
.export-carousel-img-wrap:hover img {
  transform: scale(1.06);
  box-shadow: 0 24px 64px rgba(0,0,0,.9);
}
.carousel-img-info-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,.82) 0%, rgba(0,0,0,.3) 50%, transparent 100%);
  border-radius: 10px;
  display: flex; flex-direction: column; justify-content: flex-end;
  padding: 18px 20px; gap: 4px;
  opacity: 0; transition: opacity 250ms ease;
  pointer-events: none;
}
.export-carousel-img-wrap:hover .carousel-img-info-overlay { opacity: 1; }
.carousel-info-name { font-size: 15px; font-weight: 700; color: #fff; }
.carousel-info-desc { font-size: 12.5px; color: rgba(255,255,255,.75); }
.carousel-info-date { font-size: 11px; color: rgba(255,255,255,.55); }
.carousel-add-item {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 12px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  cursor: pointer; transition: border-color 150ms;
}
.carousel-add-item.selected { border-color: var(--blue); background: rgba(46,123,246,.1); }
.carousel-add-item img { width: 60px; height: 44px; object-fit: cover; border-radius: 6px; flex: none; }
.carousel-add-item-name { font-size: 13px; font-weight: 600; flex: 1; }
.carousel-add-item-check { width: 18px; height: 18px; accent-color: var(--blue); }

/* ── Modales de impresión ── */
.print-modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.6); backdrop-filter: blur(4px);
  z-index: 500; align-items: center; justify-content: center;
}
.print-modal-overlay.open { display: flex; }
.print-modal {
  background: var(--card); border: 1px solid var(--stroke-strong);
  border-radius: 16px; padding: 36px 32px;
  display: flex; flex-direction: column; align-items: center;
  gap: 14px; max-width: 360px; width: 90%;
  box-shadow: 0 24px 60px rgba(0,0,0,.5);
  text-align: center;
}
.print-modal-icon {
  width: 64px; height: 64px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  flex: none;
}
.print-modal-icon.success { background: rgba(34,197,94,.15); }
.print-modal-icon.warning { background: rgba(251,191,36,.12); }
.print-modal-title { font-size: 18px; font-weight: 800; }
.print-modal-sub { font-size: 13.5px; color: var(--txt-soft); line-height: 1.5; }
.print-modal-actions { display: flex; gap: 10px; width: 100%; margin-top: 6px; }
.print-modal-btn {
  flex: 1; padding: 11px; border-radius: var(--r-md);
  font: inherit; font-size: 13.5px; font-weight: 700; cursor: pointer;
  transition: background 150ms; border: none;
}
.print-modal-btn.primary { background: var(--blue); color: #fff; }
.print-modal-btn.primary:hover { background: #1a6fe0; }
.print-modal-btn.secondary {
  background: var(--panel-2); color: var(--txt);
  border: 1px solid var(--stroke-strong);
}
.print-modal-btn.secondary:hover { background: var(--card); }
.print-modal-btn.danger { background: rgba(248,113,113,.15); color: #f87171; border: 1px solid rgba(248,113,113,.3); }
.print-modal-btn.danger:hover { background: rgba(248,113,113,.25); }

/* Lightbox */
.cap-lightbox {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.88); backdrop-filter: blur(6px);
  z-index: 1000; align-items: center; justify-content: center;
}
.cap-lightbox.open { display: flex; }
.cap-lightbox img {
  max-width: 90vw; max-height: 88vh;
  border-radius: 10px; box-shadow: 0 24px 60px rgba(0,0,0,.7);
  object-fit: contain;
}
.cap-lightbox-close {
  position: absolute; top: 20px; right: 24px;
  background: rgba(255,255,255,.12); border: none; border-radius: 50%;
  width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
  color: #fff; cursor: pointer; transition: background 150ms;
}
.cap-lightbox-close:hover { background: rgba(255,255,255,.25); }
.cap-footer-v {
  padding: 14px 18px; font-size: 13px; color: var(--txt-soft);
  border-top: 1px solid var(--stroke);
}
/* Panel derecho: vista previa */
.cap-preview-panel {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke); border-radius: var(--r-lg);
  padding: 20px; display: flex; flex-direction: column; gap: 16px;
}
.cap-prev-title { font-size: 15px; font-weight: 700; margin-bottom: 2px; }
.cap-prev-img-box {
  width: 100%; aspect-ratio: 16/9; border-radius: 10px;
  overflow: hidden; border: 1px solid var(--stroke-strong);
  background: var(--panel-2);
}
.cap-prev-img-box img { width: 100%; height: 100%; object-fit: cover; }
.cap-info-sec-title { font-size: 12px; font-weight: 700; color: var(--txt-soft); letter-spacing: .05em; margin-bottom: 10px; }
.cap-info-row-v {
  display: flex; justify-content: space-between;
  font-size: 12.5px; padding: 5px 0;
  border-bottom: 1px solid rgba(110,160,255,.07);
}
.cap-info-row-v:last-child { border-bottom: 0; }
.cap-info-row-v .lbl { color: var(--txt-soft); }
.cap-info-row-v .val { font-weight: 600; text-align: right; }
.cap-accs-title { font-size: 12px; font-weight: 700; color: var(--txt-soft); letter-spacing: .05em; margin-bottom: 10px; }
.cap-accs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.cap-acc-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  padding: 11px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font: inherit; font-size: 13.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; transition: background-color 150ms ease, transform 150ms ease;
}
.cap-acc-btn:hover { background: var(--card); }
.cap-acc-btn:active { transform: scale(.97); }
.cap-acc-btn.danger { color: #f87171; border-color: rgba(248,113,113,.3); }
.cap-acc-btn.danger:hover { background: rgba(248,113,113,.08); }
.cap-acc-btn svg { flex: none; }

/* ═══════════════════════════════════════════════════════════
   PANEL IMPRIMIR CAPTURAS
   ═══════════════════════════════════════════════════════════ */
.print-panel {
  display: none;
  gap: 20px;
  align-items: flex-start;
}
.print-panel.open { display: grid; grid-template-columns: 1fr 270px; align-items: stretch; gap: 16px; }
.print-preview-area {
  background: var(--panel-2);
  border: 1px solid var(--stroke); border-radius: var(--r-lg);
  overflow: hidden; display: flex; flex-direction: column;
}
.print-config-sidebar {
  box-sizing: border-box;
  overflow-y: visible;
  display: flex; flex-direction: column;
  height: 100%;
}
.print-preview-toolbar {
  display: flex; align-items: center; justify-content: space-between;
  padding: 10px 16px; border-bottom: 1px solid var(--stroke);
  gap: 12px; flex-shrink: 0;
}
.print-nav { display: flex; align-items: center; gap: 8px; }
.print-nav-btn {
  width: 28px; height: 28px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--card);
  color: var(--txt); cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: background 150ms;
}
.print-nav-btn:hover { background: rgba(46,123,246,.15); color: var(--blue); }
.print-page-label { font-size: 12.5px; color: var(--txt-soft); white-space: nowrap; }
.print-zoom { display: flex; align-items: center; gap: 6px; font-size: 12.5px; color: var(--txt); font-weight: 600; }
.print-zoom-btn {
  width: 24px; height: 24px; border-radius: 50%;
  border: 1px solid var(--stroke-strong); background: var(--card);
  color: var(--txt); cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: background 150ms;
}
.print-zoom-btn:hover { background: rgba(46,123,246,.15); color: var(--blue); }
.print-fit-btn {
  width: 28px; height: 28px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--card);
  color: var(--txt); cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: all 150ms;
}
.print-fit-btn:hover { background: rgba(46,123,246,.15); color: var(--blue); }
.print-fit-btn.fullscreen {
  background: rgba(46,123,246,.15); color: var(--blue);
  border-color: rgba(46,123,246,.4);
}
/* Área scroll de la hoja */
.print-sheet-wrap {
  flex: 1;
  overflow: hidden;
  padding: 20px;
  display: flex; justify-content: center; align-items: flex-start;
  background: #151a27;
}
/* Botón salir fullscreen */
.print-exit-fullscreen {
  display: none;
  position: fixed; top: 18px; right: 24px; z-index: 300;
  align-items: center; gap: 8px;
  padding: 10px 18px; border-radius: var(--r-md);
  background: rgba(20,25,40,.92); border: 1px solid rgba(255,255,255,.15);
  color: #fff; font: inherit; font-size: 13px; font-weight: 600;
  cursor: pointer; backdrop-filter: blur(6px);
  transition: background 150ms;
}
.print-exit-fullscreen:hover { background: rgba(46,123,246,.5); }
.print-exit-fullscreen.visible { display: flex; }
/* Hoja A4 — proporción 1:√2 escalada al 60% */
.print-sheet {
  width: 595px;
  min-height: 842px;
  background: #fff; border-radius: 3px;
  padding: 48px 52px;
  box-shadow: 0 4px 40px rgba(0,0,0,.55), 0 0 0 1px rgba(0,0,0,.25);
  color: #1a1a1a;
  display: flex; flex-direction: column;
  transform-origin: top center;
  transform: scale(0.68);
  transition: transform 200ms ease;
  margin-bottom: calc(842px * -0.32);
}
.print-sheet.landscape {
  width: 842px;
  min-height: 595px;
  margin-bottom: calc(595px * -0.32);
}
/* Header de la hoja */
.print-sheet-header {
  display: flex; justify-content: space-between; align-items: flex-end;
  margin-bottom: 20px; padding-bottom: 14px;
  border-bottom: 2px solid #1a3a6c;
}
.print-sheet-paciente { font-size: 18px; font-weight: 800; color: #0d1b3e; letter-spacing: -.01em; }
.print-sheet-meta { font-size: 10.5px; color: #6b7280; text-align: right; line-height: 1.5; }
.print-sheet-meta strong { display: block; font-size: 11px; color: #374151; font-weight: 600; }
/* Filas de imagen */
.print-img-row {
  display: flex; gap: 18px; align-items: flex-start;
  padding: 16px 0; border-bottom: 1px solid #f0f0f0;
}
.print-img-row:last-of-type { border-bottom: none; }
.print-img-thumb {
  width: 130px; height: 98px; border-radius: 6px; overflow: hidden; flex: none;
  border: 1px solid #e5e7eb; box-shadow: 0 2px 6px rgba(0,0,0,.1);
}
.print-img-thumb img { width: 100%; height: 100%; object-fit: cover; }
.print-img-info { flex: 1; padding-top: 2px; }
.print-img-title { font-size: 13.5px; font-weight: 700; color: #111827; margin-bottom: 5px; }
.print-img-desc-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #9ca3af; margin-bottom: 2px; }
.print-img-desc { font-size: 12px; color: #374151; margin-bottom: 5px; }
.print-img-date { font-size: 10.5px; color: #9ca3af; }
/* Footer hoja */
.print-sheet-footer {
  margin-top: auto; padding-top: 16px; border-top: 1px solid #e5e7eb;
  text-align: center; font-size: 10px; color: #9ca3af; letter-spacing: .04em;
}
/* Sidebar configuración */
.print-config-sidebar {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke); border-radius: var(--r-lg);
  padding: 20px; display: flex; flex-direction: column; gap: 16px;
}
.print-config-title { font-size: 15px; font-weight: 700; margin-bottom: 4px; }
.print-config-group { display: flex; flex-direction: column; gap: 6px; }
.print-config-label { font-size: 12px; font-weight: 600; color: var(--txt-soft); }
.print-config-select {
  appearance: none; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  padding: 9px 32px 9px 38px; font: inherit; font-size: 13px;
  color: var(--txt); cursor: pointer; outline: none; width: 100%;
}
.print-select-wrap { position: relative; }
.print-select-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--txt-soft); pointer-events: none; }
.print-select-chev { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: var(--txt-soft); pointer-events: none; }
.print-copies-row { display: flex; align-items: center; gap: 10px; }
.print-copies-val { font-size: 16px; font-weight: 700; min-width: 24px; text-align: center; }
.print-copies-btn {
  width: 28px; height: 28px; border-radius: 50%;
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  color: var(--txt); cursor: pointer; display: flex; align-items: center; justify-content: center;
  font-size: 16px; font-weight: 700; line-height: 1; transition: background 150ms;
}
.print-copies-btn:hover { background: var(--card); }
.print-orient-row { display: flex; gap: 8px; }
.print-orient-btn {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
  padding: 9px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font: inherit; font-size: 12.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; transition: all 150ms;
}
.print-orient-btn.active { border-color: var(--blue); background: rgba(46,123,246,.15); color: var(--blue); }
.print-orient-btn:hover:not(.active) { background: var(--card); }
.print-check-row { display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer; }
.print-check-row input[type=checkbox] { accent-color: var(--blue); width: 15px; height: 15px; cursor: pointer; }
.print-divider { height: 1px; background: var(--stroke); margin-top: auto; }
.print-actions { display: flex; gap: 10px; }
.print-cancel-btn {
  flex: 1; padding: 11px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font: inherit; font-size: 13.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; transition: background 150ms;
}
.print-cancel-btn:hover { background: var(--card); }
.print-confirm-btn {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px;
  padding: 11px; border-radius: var(--r-md);
  border: none; background: var(--blue);
  font: inherit; font-size: 13.5px; font-weight: 700; color: #fff;
  cursor: pointer; transition: background 150ms;
}
.print-confirm-btn:hover { background: #1a6fe0; }

/* ═══════════════════════════════════════════════════════════
   INTERFAZ AGREGAR CAPTURAS — Editor (toolbar + grid)
   ═══════════════════════════════════════════════════════════ */

/* Header de capturas */
.cap-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 18px;
  padding: 16px 20px;
  background: linear-gradient(135deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
}
.cap-header-title {
  font-family: 'Sora', sans-serif;
  font-size: 24px;
  font-weight: 700;
  color: var(--txt);
  margin: 0;
}
.btn-volver-capturas {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  background: var(--panel-2);
  color: var(--txt);
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 150ms ease;
}
.btn-volver-capturas:hover { background: var(--card); }

/* Info del paciente */
.cap-paciente-info {
  margin-bottom: 20px;
}
.cap-paciente-label {
  font-size: 15px;
  color: var(--txt-soft);
  margin: 0;
}
.cap-paciente-label span {
  color: var(--txt);
  font-weight: 600;
}

/* Layout wrapper */
.cap-layout-wrapper {
  display: flex;
  gap: 16px;
  align-items: flex-start;
}

/* Toolbar lateral izquierdo */
.cap-toolbar-left {
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding: 12px;
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  width: 56px;
  min-height: 400px;
}
.cap-tool-btn {
  width: 32px;
  height: 32px;
  border-radius: var(--r-md);
  border: none;
  background: transparent;
  color: var(--txt-soft);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 150ms ease;
}
.cap-tool-btn:hover {
  background: rgba(46,123,246,.12);
  color: var(--cyan);
}
.cap-tool-btn.active {
  background: rgba(46,123,246,.2);
  color: var(--blue);
}

/* Grid de fotos */
.cap-fotos-grid {
  flex: 1;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px;
  padding: 20px;
  background: linear-gradient(135deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  min-height: 400px;
}

/* Tarjeta de foto */
.cap-foto-card {
  position: relative;
  border-radius: var(--r-md);
  overflow: hidden;
  border: 2px solid transparent;
  transition: all 200ms ease;
  display: flex;
  flex-direction: column;
}
.cap-foto-card:hover {
  border-color: var(--blue);
  transform: translateY(-4px);
  box-shadow: 0 12px 30px -8px rgba(0,0,0,.35);
}
.cap-foto-card.selected {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(46,123,246,.3);
}

.cap-foto-img {
  position: relative;
  aspect-ratio: 1;
  overflow: hidden;
  border-radius: var(--r-md) var(--r-md) 0 0;
}
.cap-foto-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 300ms ease;
}
.cap-foto-card:hover .cap-foto-img img {
  transform: scale(1.05);
}

/* Overlay de acciones */
.cap-foto-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,.8) 0%, transparent 50%);
  display: flex;
  align-items: flex-end;
  justify-content: center;
  gap: 8px;
  padding: 12px;
  opacity: 0;
  transition: opacity 200ms ease;
}
.cap-foto-card:hover .cap-foto-overlay {
  opacity: 1;
}

.cap-foto-action {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  border: none;
  background: rgba(255,255,255,.15);
  backdrop-filter: blur(4px);
  color: #fff;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 150ms ease;
}
.cap-foto-action:hover {
  background: rgba(255,255,255,.3);
  transform: scale(1.1);
}

/* Número de foto */
.cap-foto-numero {
  position: absolute;
  bottom: 8px;
  left: 8px;
  font-size: 12px;
  font-weight: 600;
  color: #fff;
  background: rgba(0,0,0,.5);
  padding: 4px 8px;
  border-radius: 4px;
  backdrop-filter: blur(4px);
}

/* Footer de la foto con botones */
.cap-foto-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 12px;
  background: rgba(0,0,0,.4);
  border-radius: 0 0 var(--r-md) var(--r-md);
  backdrop-filter: blur(8px);
}
.cap-foto-num {
  font-size: 14px;
  font-weight: 700;
  color: #fff;
  min-width: 20px;
}
.cap-foto-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}
.cap-foto-btn {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  border: none;
  background: transparent;
  color: var(--cyan);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 150ms ease;
}
.cap-foto-btn:hover {
  background: rgba(46,123,246,.2);
  transform: scale(1.1);
}

/* ═══════════════════════════════════════════════════════════
   MODAL AGREGAR NUEVA NOTA
   ═══════════════════════════════════════════════════════════ */
.nota-modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.7);
  backdrop-filter: blur(4px);
  z-index: 1000;
}
.nota-modal-overlay.active { display: block; }

.nota-modal {
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 90%;
  max-width: 500px;
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  box-shadow: 0 25px 50px -12px rgba(0,0,0,.5);
  z-index: 1001;
  overflow: hidden;
}
.nota-modal.active { display: flex; flex-direction: column; }

.nota-modal-header {
  padding: 20px 24px;
  border-bottom: 1px solid var(--stroke);
}
.nota-modal-title {
  font-family: 'Sora', sans-serif;
  font-size: 20px;
  font-weight: 700;
  color: var(--txt);
  margin: 0;
  text-align: center;
}

.nota-modal-body {
  padding: 24px;
}
.nota-modal-textarea {
  width: 100%;
  min-height: 150px;
  padding: 14px 16px;
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  color: var(--txt);
  font-size: 14px;
  font-family: inherit;
  resize: vertical;
  outline: none;
  transition: border-color 150ms ease;
}
.nota-modal-textarea::placeholder {
  color: var(--txt-soft);
}
.nota-modal-textarea:focus {
  border-color: var(--cyan);
}

.nota-modal-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 24px;
  border-top: 1px solid var(--stroke);
  gap: 12px;
}

.btn-nota-volver {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  background: transparent;
  color: var(--txt);
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 150ms ease;
}
.btn-nota-volver:hover {
  background: rgba(110,160,255,.1);
}

.btn-nota-guardar {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border-radius: var(--r-md);
  border: none;
  background: linear-gradient(135deg, var(--blue), var(--cyan));
  color: #fff;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: opacity 150ms ease, transform 160ms var(--ease-out);
}
.btn-nota-guardar:hover {
  opacity: .9;
}
.btn-nota-guardar:active {
  transform: scale(.97);
}

/* Responsive */
@media(max-width:768px){
  .cap-layout-wrapper { flex-direction: column; }
  .cap-toolbar-left {
    flex-direction: row;
    width: 100%;
    min-height: auto;
    justify-content: center;
    flex-wrap: wrap;
  }
  .cap-fotos-grid {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  }
}
</style>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\estudios\dashboard\dashboard-css.blade.php ENDPATH**/ ?>