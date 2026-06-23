@extends('layouts.app')

@section('title', 'Nuevo Estudio')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Datos nuevos
@endsection

@push('styles')
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
@endpush

@section('content')

  {{-- Definición de pacientes para el buscador --}}
  @php
  $pacientes = [
    ['ini'=>'MG','nombre'=>'María González','meta'=>'45 años · Femenino','folio'=>'00045','nac'=>'16/04/1979','fecha'=>'22 Mayo 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'completado'],
    ['ini'=>'JL','nombre'=>'Jorge López','meta'=>'38 años · Masculino','folio'=>'00046','nac'=>'05/11/1985','fecha'=>'18 Mayo 2024','tipo'=>'Colonoscopia','estado'=>'espera'],
    ['ini'=>'AR','nombre'=>'Ana Ramírez','meta'=>'52 años · Femenino','folio'=>'00047','nac'=>'30/07/1971','fecha'=>'10 Mayo 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'completado'],
    ['ini'=>'PT','nombre'=>'Pedro Torres','meta'=>'61 años · Masculino','folio'=>'00048','nac'=>'12/03/1963','fecha'=>'05 Mayo 2024','tipo'=>'Gastroscopia','estado'=>'completado'],
    ['ini'=>'LM','nombre'=>'Lucía Mendoza','meta'=>'29 años · Femenino','folio'=>'00049','nac'=>'22/09/1994','fecha'=>'28 Abr 2024','tipo'=>'Sigmoidoscopia','estado'=>'cancelado'],
    ['ini'=>'CR','nombre'=>'Carlos Reyes','meta'=>'47 años · Masculino','folio'=>'00050','nac'=>'14/06/1976','fecha'=>'20 Abr 2024','tipo'=>'CPRE','estado'=>'completado'],
    ['ini'=>'SO','nombre'=>'Sofía Ortega','meta'=>'33 años · Femenino','folio'=>'00051','nac'=>'08/01/1991','fecha'=>'15 Abr 2024','tipo'=>'Colonoscopia','estado'=>'espera'],
    ['ini'=>'RM','nombre'=>'Roberto Morales','meta'=>'55 años · Masculino','folio'=>'00052','nac'=>'27/12/1968','fecha'=>'08 Abr 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'completado'],
    ['ini'=>'VH','nombre'=>'Valentina Herrera','meta'=>'41 años · Femenino','folio'=>'00053','nac'=>'19/05/1982','fecha'=>'01 Abr 2024','tipo'=>'Gastroscopia','estado'=>'completado'],
    ['ini'=>'DC','nombre'=>'Diego Castillo','meta'=>'36 años · Masculino','folio'=>'00054','nac'=>'03/08/1987','fecha'=>'25 Mar 2024','tipo'=>'Colonoscopia','estado'=>'completado'],
    ['ini'=>'IF','nombre'=>'Isabella Flores','meta'=>'27 años · Femenino','folio'=>'00055','nac'=>'11/02/1997','fecha'=>'20 Mar 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'espera'],
    ['ini'=>'RA','nombre'=>'Rodrigo Aguilar','meta'=>'58 años · Masculino','folio'=>'00056','nac'=>'29/10/1965','fecha'=>'14 Mar 2024','tipo'=>'CPRE','estado'=>'completado'],
    ['ini'=>'NV','nombre'=>'Natalia Vargas','meta'=>'44 años · Femenino','folio'=>'00057','nac'=>'07/06/1979','fecha'=>'08 Mar 2024','tipo'=>'Gastroscopia','estado'=>'cancelado'],
    ['ini'=>'EM','nombre'=>'Eduardo Medina','meta'=>'50 años · Masculino','folio'=>'00058','nac'=>'25/01/1974','fecha'=>'01 Mar 2024','tipo'=>'Sigmoidoscopia','estado'=>'completado'],
    ['ini'=>'CP','nombre'=>'Camila Pedraza','meta'=>'31 años · Femenino','folio'=>'00059','nac'=>'18/07/1992','fecha'=>'22 Feb 2024','tipo'=>'Colonoscopia','estado'=>'completado'],
    ['ini'=>'HS','nombre'=>'Héctor Salinas','meta'=>'63 años · Masculino','folio'=>'00060','nac'=>'04/12/1960','fecha'=>'15 Feb 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'espera'],
    ['ini'=>'MR','nombre'=>'Mariana Ríos','meta'=>'39 años · Femenino','folio'=>'00061','nac'=>'21/04/1984','fecha'=>'10 Feb 2024','tipo'=>'Gastroscopia','estado'=>'completado'],
    ['ini'=>'FN','nombre'=>'Fernando Navarro','meta'=>'54 años · Masculino','folio'=>'00062','nac'=>'09/09/1969','fecha'=>'05 Feb 2024','tipo'=>'CPRE','estado'=>'completado'],
    ['ini'=>'GE','nombre'=>'Gabriela Espinoza','meta'=>'48 años · Femenino','folio'=>'00063','nac'=>'13/03/1976','fecha'=>'28 Ene 2024','tipo'=>'Colonoscopia','estado'=>'cancelado'],
    ['ini'=>'AJ','nombre'=>'Alejandro Jiménez','meta'=>'42 años · Masculino','folio'=>'00064','nac'=>'30/11/1981','fecha'=>'20 Ene 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'completado'],
  ];
  @endphp

  {{-- (Drawer y overlay eliminados - ya no se usa la tabla de pacientes) --}}

  {{-- Toolbar solo con botón Volver --}}
  <div class="crear-toolbar rise d1" style="justify-content:flex-end">
    <a class="btn-back" href="{{ route('dashboard') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
  </div>

  {{-- Buscador de Paciente --}}
  <div class="paciente-busqueda-wrapper" id="pacienteBusquedaWrapper">
    <div class="paciente-busqueda-controls">
      <div class="paciente-search-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="pacienteSearchInput" placeholder="Buscar paciente por nombre..." autocomplete="off">
      </div>
      <div class="filtrar-dropdown-wrapper">
        <button class="btn-filtrar" id="btnFiltrarPacientes">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          Filtrar
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:4px"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="filtrar-menu" id="filtrarMenu">
          <div class="filtrar-grupo">
            <div class="filtrar-grupo-titulo">Fecha</div>
            <button class="filtrar-opcion" data-filtro="todos">Todos</button>
            <button class="filtrar-opcion" data-filtro="hoy">Hoy</button>
            <button class="filtrar-opcion" data-filtro="semana">Esta semana</button>
            <button class="filtrar-opcion" data-filtro="mes">Este mes</button>
          </div>
          <div class="filtrar-divider"></div>
          <div class="filtrar-grupo">
            <div class="filtrar-grupo-titulo">Estado</div>
            <button class="filtrar-opcion" data-filtro="espera">En espera</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Layout principal: Formulario de Información del Paciente --}}
  <div class="crear-layout">

    {{-- Formulario --}}
    <form class="crear-card rise d2" method="POST" action="#" id="formCrear">
      @csrf

      <h2 class="sec-title">Información del siguiente</h2>

      {{-- Fila 1: Nombre + Identificación --}}
      <div class="fields-grid cols-2" style="margin-bottom:18px">
        <div class="field">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" name="nombre" placeholder="María Fernanda López Ruiz" autocomplete="off">
        </div>
        <div class="field">
          <label for="identificacion">Identificación</label>
          <input type="text" id="identificacion" name="identificacion" placeholder="0256987450" autocomplete="off">
        </div>
      </div>

      {{-- Fila 2: Fecha nac + Edad + Peso + Altura --}}
      <div class="fields-grid cols-4" style="margin-bottom:18px">
        <div class="field">
          <label for="fecha_nac">Fecha de nacimiento</label>
          <div class="input-icon">
            <input type="date" id="fecha_nac" name="fecha_nac">
            <span class="ico"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
          </div>
        </div>
        <div class="field">
          <label for="edad">Edad</label>
          <input type="text" id="edad" name="edad" placeholder="28 años" autocomplete="off">
        </div>
        <div class="field">
          <label for="peso">Peso</label>
          <input type="text" id="peso" name="peso" placeholder="30 kg" autocomplete="off">
        </div>
        <div class="field">
          <label for="altura">Altura</label>
          <input type="text" id="altura" name="altura" placeholder="1.75 m" autocomplete="off">
        </div>
      </div>

      {{-- Fila 3: Sexo + NSS + Dirección --}}
      <div class="fields-grid cols-3" style="margin-bottom:18px">
        <div class="field">
          <label for="sexo">Sexo</label>
          <select id="sexo" name="sexo">
            <option value="" disabled selected>Elegir</option>
            <option value="F">Femenino</option>
            <option value="M">Masculino</option>
          </select>
        </div>
        <div class="field">
          <label for="nss">N.S.S</label>
          <input type="text" id="nss" name="nss" placeholder="25849563-9" autocomplete="off">
        </div>
        <div class="field">
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion" placeholder="CALLE, CP" autocomplete="off">
        </div>
      </div>

      {{-- Fila 4: Teléfono + Email --}}
      <div class="fields-grid cols-2" style="margin-bottom:0">
        <div class="field">
          <label for="telefono">Teléfono</label>
          <input type="tel" id="telefono" name="telefono" placeholder="722 162 0815" autocomplete="off">
        </div>
        <div class="field">
          <label for="email">e-mail</label>
          <input type="email" id="email" name="email" placeholder="@gmail.com" autocomplete="off">
        </div>
      </div>

      <hr class="sec-divider">

      <h2 class="sec-title">Información médica</h2>

      {{-- Fila 5: Procedimiento + Fecha y hora --}}
      <div class="fields-grid cols-2" style="margin-bottom:18px">
        <div class="field">
          <label for="procedimiento">Procedimiento</label>
          <select id="procedimiento" name="procedimiento">
            <option value="" disabled selected>Seleccione</option>
            <option value="endoscopia">Endoscopia diagnóstica</option>
            <option value="colonoscopia">Colonoscopia</option>
            <option value="gastroscopia">Gastroscopia</option>
            <option value="sigmoidoscopia">Sigmoidoscopia</option>
            <option value="cpre">CPRE</option>
            <option value="ecoendoscopia">Ecoendoscopia</option>
          </select>
        </div>
        <div class="field">
          <label for="fecha_hora">Fecha y hora</label>
          <div class="input-icon">
            <input type="datetime-local" id="fecha_hora" name="fecha_hora">
            <span class="ico"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
          </div>
        </div>
      </div>

      {{-- Fila 6: Médico + Referido por --}}
      <div class="fields-grid cols-2" style="margin-bottom:18px">
        <div class="field">
          <label for="medico">Médico</label>
          <select id="medico" name="medico">
            <option value="" disabled selected>Seleccione</option>
            <option value="dr_victor" selected>Dr. Victor</option>
            <option value="dr_ricardo">Dr. Ricardo</option>
          </select>
        </div>
        <div class="field">
          <label for="referido">Referido por</label>
          <select id="referido" name="referido">
            <option value="" disabled selected>Seleccione</option>
            <option value="externo">Médico externo</option>
            <option value="propio">Médico propio</option>
            <option value="paciente">Paciente directo</option>
          </select>
        </div>
      </div>

      {{-- Diagnóstico preliminar --}}
      <div class="field" style="margin-bottom:0">
        <label for="diagnostico">Diagnostico Preliminar</label>
        <textarea id="diagnostico" name="diagnostico" placeholder="Define lo que podría tener"></textarea>
      </div>

      {{-- Footer guardar --}}
      <div class="crear-footer">
        <button type="submit" class="btn-save">
          Guardar paciente
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>
      </div>

    </form>

    {{-- Panel lateral: foto + botones --}}
    <div class="side-panel rise d3">

      <div class="foto-box">
        <div class="foto-circle" id="fotoCircle" onclick="document.getElementById('fotoInput').click()">
          <img id="fotoPreview" src="" alt="Foto del paciente">
          <div class="foto-placeholder" id="fotoPlaceholder">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Foto del<br>paciente</span>
          </div>
        </div>
        <input type="file" id="fotoInput" accept="image/*" style="display:none">
        <input type="file" id="fotoCamera" accept="image/*" capture="environment" style="display:none">

        <div style="position:relative;width:100%">
          <button class="btn-add-foto" type="button" id="btnFotoMenu">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span id="btnFotoTxt">Agregar foto</span>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:2px"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div id="fotoMenu" style="display:none;position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:var(--panel);border:1px solid var(--stroke-strong);border-radius:var(--r-md);overflow:hidden;min-width:170px;z-index:50;box-shadow:0 8px 24px rgba(0,0,0,.35)">
            <button type="button" id="btnGaleria" style="display:flex;align-items:center;gap:10px;width:100%;padding:11px 14px;background:none;border:none;border-bottom:1px solid var(--stroke);font:inherit;font-size:13.5px;font-weight:600;color:var(--txt);cursor:pointer;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              Abrir galería
            </button>
            <button type="button" id="btnCamara" style="display:flex;align-items:center;gap:10px;width:100%;padding:11px 14px;background:none;border:none;font:inherit;font-size:13.5px;font-weight:600;color:var(--txt);cursor:pointer;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              Tomar foto
            </button>
          </div>
        </div>
      </div>

      <div class="action-btns">
        <a class="action-btn" href="{{ route('nuevo-estudio.grabando') }}">
          <span class="ab-icon" style="background:rgba(255,59,59,.12);border-color:rgba(255,90,110,.4)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#ff5a6e"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="#ff5a6e" stroke="none"/></svg>
          </span>
          Iniciar Grabación
        </a>
        <a class="action-btn" href="{{ route('nuevo-estudio.capturas') }}">
          <span class="ab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          </span>
          Agregar Capturas
        </a>
        <a class="action-btn" href="{{ route('nuevo-estudio.importar') }}">
          <span class="ab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          </span>
          Importar Imágenes
        </a>
        <a class="action-btn" href="{{ route('nuevo-estudio.configuracion') }}">
          <span class="ab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
          </span>
          Configuración de Grabacion
        </a>
      </div>

    </div>

  </div>

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- INTERFAZ AGREGAR CAPTURAS (se muestra al seleccionar paciente) --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  <div id="capturasView" style="display:none">

    {{-- Header de capturas --}}
    <div class="cap-header">
      <h1 class="cap-header-title">Capturas</h1>
      <button class="btn-volver-capturas" id="btnVolverCapturas">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver
      </button>
    </div>

    {{-- Vista principal: Lista + Preview --}}
    <div id="capListaView">
      <div class="cap-view-layout">

        {{-- Lista de capturas --}}
        <div class="cap-card-list">
          <div class="cap-search-bar-v">
            <div class="cap-search-box-v">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <input type="text" id="capSearchV" placeholder="Buscar Capturas...">
            </div>
            <div class="cap-sort-wrap">
              Ordenar por:
              <div class="cap-sort-select-wrap">
                <select class="cap-sort-select">
                  <option>Fecha (más reciente)</option>
                  <option>Fecha (más antigua)</option>
                  <option>Nombre</option>
                </select>
                <svg class="cap-sort-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
              </div>
            </div>
          </div>

          <div class="cap-list-v" id="capListV">
            @php
            $capturasV = [
              ['id'=>1,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva'],
              ['id'=>2,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva'],
              ['id'=>3,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva'],
            ];
            @endphp

            @foreach($capturasV as $cv)
            <div class="cap-item-v" data-id="{{ $cv['id'] }}"
              data-nombre="{{ $cv['nombre'] }}"
              data-fecha="{{ $cv['fecha'] }}"
              data-hora="{{ $cv['hora'] }}"
              data-estudio="{{ $cv['estudio'] }}"
              data-tipo-estudio="{{ $cv['tipo_estudio'] }}"
              data-img="{{ $cv['id'] }}">
              <div class="cap-thumb-v">
                <img src="{{ asset('images/captura1.jpg') }}" alt="captura">
                <div class="cap-thumb-expand" title="Ver imagen">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
                </div>
              </div>
              <div class="cap-info-v">
                <div class="cap-nombre-v">{{ $cv['nombre'] }}</div>
                <div class="cap-date-v">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  {{ $cv['fecha'] }} {{ $cv['hora'] }}
                </div>
                <div class="cap-tipo-v">{{ $cv['tipo'] }}</div>
              </div>
            </div>
            @endforeach
          </div>

          <div class="cap-footer-v" id="capFooterV">Mostrando 3 de 3</div>
        </div>

        {{-- Vista previa --}}
        <div class="cap-preview-panel" id="capPreviewPanel">
          <div class="cap-prev-title">Vista Previa</div>
          <div class="cap-prev-img-box">
            <img id="capPrevImg" src="{{ asset('images/captura1.jpg') }}" alt="Vista previa">
          </div>
          <div>
            <div class="cap-info-sec-title">Información de la captura</div>
            <div class="cap-info-row-v"><span class="lbl">Fecha y hora</span>    <span class="val" id="capPiFecha">24/05/2026</span></div>
            <div class="cap-info-row-v"><span class="lbl">Descripción</span>     <span class="val" id="capPiDesc">Lesion del Esófago</span></div>
            <div class="cap-info-row-v"><span class="lbl">Estudio</span>         <span class="val" id="capPiEstudio">EST-2024-0587</span></div>
            <div class="cap-info-row-v"><span class="lbl">Tipo de estudio</span> <span class="val" id="capPiTipo">Endoscopia digestiva</span></div>
            <div class="cap-info-row-v"><span class="lbl">Imagen</span>          <span class="val" id="capPiImagen">1 de 3</span></div>
          </div>
          <div>
            <div class="cap-accs-title">Acciones</div>
            <div class="cap-accs-grid">
              <button class="cap-acc-btn" id="btnAbrirEditor">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar
              </button>
              <button class="cap-acc-btn" id="btnExportarCaptura">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Exportar
              </button>
              <button class="cap-acc-btn" id="btnImprimirCaptura">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Imprimir
              </button>
              <button class="cap-acc-btn danger">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                Eliminar
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- Vista Editor: Toolbar lateral + Grid de fotos (se abre al pulsar Editar) --}}
    <div id="capEditorView" style="display:none">

      <div style="margin-bottom:16px">
        <h2 style="font-family:'Sora',sans-serif;font-size:18px;font-weight:700;margin:0">Editor de Captura</h2>
      </div>

      <div class="cap-layout-wrapper">

        {{-- Toolbar lateral izquierdo --}}
        <div class="cap-toolbar-left">
          <button class="cap-tool-btn active" title="Seleccionar">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><polyline points="8 12 11 15 16 9"/></svg>
          </button>
          <button class="cap-tool-btn" title="Eliminar">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6h18M5 6v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
          </button>
          <button class="cap-tool-btn" title="Imprimir">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="12" width="16" height="8" rx="2"/><rect x="6" y="4" width="12" height="8" rx="2"/><line x1="8" y1="15" x2="8" y2="15"/><line x1="12" y1="15" x2="12" y2="15"/><line x1="16" y1="15" x2="16" y2="15"/></svg>
          </button>
          <button class="cap-tool-btn" title="Exportar PDF">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M10 13v6"/><path d="M8 17h4"/><path d="M16 17h2"/></svg>
          </button>
          <button class="cap-tool-btn" title="Exportar Imágenes">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="8" width="14" height="12" rx="2"/><rect x="7" y="4" width="14" height="12" rx="2"/><circle cx="10" cy="11" r="1.5"/><path d="M7 17l3-3 2 2"/></svg>
          </button>
          <button class="cap-tool-btn" title="Guardar">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="7" y="2" width="10" height="18" rx="2"/><circle cx="12" cy="6" r="1"/><path d="M10 14h4"/><path d="M12 14v4"/></svg>
          </button>
          <button class="cap-tool-btn" title="Adelante">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><polyline points="10 8 14 12 10 16"/></svg>
          </button>
          <button class="cap-tool-btn" title="Atrás">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><polyline points="14 8 10 12 14 16"/></svg>
          </button>
          <button class="cap-tool-btn" title="Agregar más" style="margin-top:auto">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="6" width="16" height="14" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M20 10v10"/><path d="M15 15h10"/></svg>
          </button>
        </div>

        {{-- Grid de fotos --}}
        <div class="cap-fotos-grid" id="capFotosGrid">
          @php
          $fotosCapturas = [
            ['num'=>1,'img'=>'captura1.jpg'],
            ['num'=>2,'img'=>'captura1.jpg'],
            ['num'=>3,'img'=>'captura1.jpg'],
          ];
          @endphp

          @foreach($fotosCapturas as $foto)
          <div class="cap-foto-card" data-foto="{{ $foto['num'] }}">
            <div class="cap-foto-img">
              <img src="{{ asset('images/'.$foto['img']) }}" alt="Captura {{ $foto['num'] }}">
              <div class="cap-foto-overlay">
                <button class="cap-foto-action" title="Ver">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
                <button class="cap-foto-action" title="Ampliar">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
                <button class="cap-foto-action" title="Descargar">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </button>
              </div>
            </div>
            <div class="cap-foto-footer">
              <span class="cap-foto-num">{{ $foto['num'] }}</span>
              <div class="cap-foto-actions">
                <button class="cap-foto-btn" title="Ajustes de color">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                </button>
                <button class="cap-foto-btn" title="Agregar a informe">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="4" width="16" height="20" rx="2"/><path d="M12 10v8"/><path d="M8 14h8"/></svg>
                </button>
                <button class="cap-foto-btn" title="Zoom">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                </button>
                <button class="cap-foto-btn" title="Balance de color">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/><path d="M12 12L4.93 4.93"/></svg>
                </button>
              </div>
            </div>
          </div>
          @endforeach
        </div>

      </div>
    </div>

    {{-- Botón salir fullscreen --}}
    <button class="print-exit-fullscreen" id="printExitFs">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="8 3 3 3 3 8"/><polyline points="21 8 21 3 16 3"/><polyline points="3 16 3 21 8 21"/><polyline points="16 21 21 21 21 16"/></svg>
      Salir de pantalla completa
    </button>

    {{-- Panel Imprimir Capturas --}}
    <div class="print-panel" id="printPanel">

      {{-- Área de previsualización --}}
      <div class="print-preview-area">
        <div class="print-preview-toolbar">
          <div class="print-nav">
            <button class="print-nav-btn">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <span class="print-page-label">Página 1 de 1</span>
            <button class="print-nav-btn">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>
          <div class="print-zoom">
            <button class="print-zoom-btn" id="printZoomIn">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
            <span id="printZoomVal">100%</span>
            <button class="print-zoom-btn" id="printZoomOut">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
          </div>
          <button class="print-fit-btn" id="printFitBtn" title="Ajustar a pantalla">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          </button>
        </div>

        <div class="print-sheet-wrap">
          <div class="print-sheet" id="printSheet">
            <div class="print-sheet-header">
              <div class="print-sheet-paciente">Maria Gonzalez</div>
              <div class="print-sheet-meta">
                <span>Fecha de estudio</span>
                <span>05/mayo/2026</span>
              </div>
            </div>
            <div class="print-img-row">
              <div class="print-img-thumb">
                <img src="{{ asset('images/captura1.jpg') }}" alt="Imagen 1">
              </div>
              <div class="print-img-info">
                <div class="print-img-title">Imagen 1</div>
                <div class="print-img-desc-label">Descripción:</div>
                <div class="print-img-desc">Lesión observada en estudio</div>
                <div class="print-img-date">Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="print-img-row">
              <div class="print-img-thumb">
                <img src="{{ asset('images/captura1.jpg') }}" alt="Imagen 2">
              </div>
              <div class="print-img-info">
                <div class="print-img-title">Imagen 2</div>
                <div class="print-img-desc-label">Descripción:</div>
                <div class="print-img-desc">Lesión observada en estudio</div>
                <div class="print-img-date">Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="print-img-row">
              <div class="print-img-thumb">
                <img src="{{ asset('images/captura1.jpg') }}" alt="Imagen 3">
              </div>
              <div class="print-img-info">
                <div class="print-img-title">Imagen 3</div>
                <div class="print-img-desc-label">Descripción:</div>
                <div class="print-img-desc">Lesión observada en estudio</div>
                <div class="print-img-date">Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="print-sheet-footer">Página 1 de 1</div>
          </div>
        </div>
      </div>

      {{-- Sidebar configuración --}}
      <div class="print-config-sidebar">
        <div class="print-config-title">Configuración de Impresión</div>

        <div class="print-config-group">
          <div class="print-config-label">Impresora</div>
          <div class="print-select-wrap">
            <svg class="print-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            <select class="print-config-select">
              <option>HP Laser Jet Pro M404dn</option>
              <option>PDF (Guardar como PDF)</option>
              <option>Microsoft Print to PDF</option>
            </select>
            <svg class="print-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>

        <div class="print-config-group">
          <div class="print-config-label">Copias</div>
          <div class="print-copies-row">
            <button class="print-copies-btn" id="printCopiesDown">−</button>
            <span class="print-copies-val" id="printCopiesVal">1</span>
            <button class="print-copies-btn" id="printCopiesUp">+</button>
          </div>
        </div>

        <div class="print-config-group">
          <div class="print-config-label">Orientación</div>
          <div class="print-orient-row">
            <button class="print-orient-btn active" id="printOrientV">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/></svg>
              Vertical
            </button>
            <button class="print-orient-btn" id="printOrientH">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/></svg>
              Horizontal
            </button>
          </div>
        </div>

        <div class="print-config-group">
          <div class="print-config-label">Tamaño del Papel</div>
          <div class="print-select-wrap">
            <svg class="print-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <select class="print-config-select">
              <option>A4 (210 x 297 mm)</option>
              <option>Carta (216 x 279 mm)</option>
              <option>Legal (216 x 356 mm)</option>
            </select>
            <svg class="print-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>

        <div class="print-config-group">
          <div class="print-config-label">Márgenes</div>
          <div class="print-select-wrap">
            <select class="print-config-select" style="padding-left:12px">
              <option>Normal</option>
              <option>Estrecho</option>
              <option>Amplio</option>
              <option>Sin márgenes</option>
            </select>
            <svg class="print-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>

        <div class="print-config-group">
          <div class="print-config-label">Escala</div>
          <div class="print-select-wrap">
            <select class="print-config-select" style="padding-left:12px">
              <option>Ajustar a Página</option>
              <option>100%</option>
              <option>75%</option>
              <option>50%</option>
            </select>
            <svg class="print-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>

        <div class="print-config-group">
          <div class="print-config-label">Incluir en la impresión</div>
          <label class="print-check-row"><input type="checkbox" checked> Encabezado con datos del paciente</label>
          <label class="print-check-row"><input type="checkbox" checked> Fecha del estudio</label>
          <label class="print-check-row"><input type="checkbox" checked> Descripciones</label>
          <label class="print-check-row"><input type="checkbox" checked> Numero de Imágenes</label>
        </div>

        <div class="print-divider"></div>

        <div class="print-actions">
          <button class="print-cancel-btn" id="btnCancelarImpresion">Cancelar</button>
          <button class="print-confirm-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Imprimir
          </button>
        </div>
      </div>

    </div>

    {{-- Panel Exportar PDF --}}
    <div class="export-panel" id="exportPanel">

      {{-- Área preview --}}
      <div class="export-preview-area">
        <div class="export-preview-toolbar">
          <div class="print-nav">
            <button class="print-nav-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
            <span class="print-page-label">Página 1 de 1</span>
            <button class="print-nav-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>
          </div>
          <div class="print-zoom">
            <button class="print-zoom-btn" id="exportZoomIn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
            <span id="exportZoomVal">68%</span>
            <button class="print-zoom-btn" id="exportZoomOut"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
          </div>
        </div>
        <div class="export-sheet-wrap">
          <div class="export-sheet" id="exportSheet">
            <div class="export-sheet-header" id="exportSheetHeader">
              <div class="export-sheet-paciente">Maria Gonzalez</div>
              <div class="export-sheet-meta"><span>Fecha de estudio</span><span>05/mayo/2026</span></div>
            </div>
            <div class="export-img-row" data-export-row>
              <div class="export-img-thumb"><img src="{{ asset('images/captura1.jpg') }}" alt="Imagen 1"></div>
              <div class="export-img-info">
                <div class="export-img-title"><span data-export-num>Imagen 1 — </span>Lesión del Esófago</div>
                <div class="export-img-desc-label" data-export-desc-label>Descripción:</div>
                <div class="export-img-desc" data-export-desc>Lesión observada en estudio</div>
                <div class="export-img-date" data-export-date>Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="export-img-row" data-export-row>
              <div class="export-img-thumb"><img src="{{ asset('images/captura1.jpg') }}" alt="Imagen 2"></div>
              <div class="export-img-info">
                <div class="export-img-title"><span data-export-num>Imagen 2 — </span>Lesión del Esófago</div>
                <div class="export-img-desc-label" data-export-desc-label>Descripción:</div>
                <div class="export-img-desc" data-export-desc>Lesión observada en estudio</div>
                <div class="export-img-date" data-export-date>Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="export-img-row" data-export-row>
              <div class="export-img-thumb"><img src="{{ asset('images/captura1.jpg') }}" alt="Imagen 3"></div>
              <div class="export-img-info">
                <div class="export-img-title"><span data-export-num>Imagen 3 — </span>Lesión del Esófago</div>
                <div class="export-img-desc-label" data-export-desc-label>Descripción:</div>
                <div class="export-img-desc" data-export-desc>Lesión observada en estudio</div>
                <div class="export-img-date" data-export-date>Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="export-sheet-footer" id="exportSheetFooter">Página 1 de 1</div>
          </div>
        </div>
      </div>

      {{-- Sidebar opciones PDF --}}
      <div class="export-config-sidebar">
        <div class="export-config-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Opciones del PDF
        </div>

        <div class="export-config-group">
          <div class="export-config-label">Incluir del PDF</div>
          <label class="export-check-row"><input type="checkbox" id="expChkHeader" checked> Encabezado con datos del paciente</label>
          <label class="export-check-row"><input type="checkbox" id="expChkFecha" checked> Fecha del estudio</label>
          <label class="export-check-row"><input type="checkbox" id="expChkDesc" checked> Descripciones</label>
          <label class="export-check-row"><input type="checkbox" id="expChkNum" checked> Numero de Imágenes</label>
        </div>

        <div class="export-config-group">
          <div class="export-config-label">Orientación</div>
          <div class="export-orient-row">
            <button class="export-orient-btn active" id="expOrientV">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/></svg> Vertical
            </button>
            <button class="export-orient-btn" id="expOrientH">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/></svg> Horizontal
            </button>
          </div>
        </div>

        <div class="export-config-group">
          <div class="export-config-label">Tamaño del Papel</div>
          <div class="export-select-wrap">
            <select class="export-config-select">
              <option>A4 (210 x 297 mm)</option>
              <option>Carta (216 x 279 mm)</option>
              <option>Legal (216 x 356 mm)</option>
            </select>
            <svg class="export-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>

        <div class="export-config-group">
          <div class="export-config-label">Márgenes</div>
          <div class="export-select-wrap">
            <select class="export-config-select">
              <option>Normal</option><option>Estrecho</option><option>Amplio</option><option>Sin márgenes</option>
            </select>
            <svg class="export-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>

        <div class="export-config-group">
          <div class="export-config-label">Calidad</div>
          <div class="export-select-wrap">
            <select class="export-config-select">
              <option>Alta</option><option>Media</option><option>Baja</option>
            </select>
            <svg class="export-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>

        <div class="export-config-group">
          <div class="export-config-label">Protección (Opcional)</div>
          <label class="export-protect-row">
            <input type="checkbox" id="expChkProtect">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Proteger con contraseña
          </label>
        </div>

        <div class="export-divider"></div>
        <div class="export-actions">
          <button class="export-preview-btn" id="btnVistaPrevia">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            Vista Previa
          </button>
          <button class="export-download-btn" id="btnDescargarPDF">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Descargar PDF
          </button>
        </div>
      </div>
    </div>{{-- /#exportPanel --}}

    {{-- Carrusel Vista Previa --}}
    <div class="export-carousel-overlay" id="exportCarousel">
      <div class="export-carousel-header">
        <div class="export-carousel-title">Vista Previa de Imágenes</div>
        <button class="export-carousel-close" id="exportCarouselClose">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="export-carousel-stage">
        <button class="export-carousel-arrow" id="carouselPrev">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div class="export-carousel-img-wrap">
          <img id="carouselImg" src="{{ asset('images/captura1.jpg') }}" alt="Preview">
          <div class="carousel-img-info-overlay">
            <div class="carousel-info-name" id="carouselInfoName">Imagen 1</div>
            <div class="carousel-info-desc" id="carouselInfoDesc">Lesión observada en estudio</div>
            <div class="carousel-info-date" id="carouselInfoDate">Fecha: 05/mayo/2026</div>
          </div>
        </div>
        <button class="export-carousel-arrow" id="carouselNext">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
      <div class="export-carousel-footer">
        <div class="export-carousel-dots" id="carouselDots"></div>
        <span class="export-carousel-counter" id="carouselCounter">1 / 3</span>
        <button class="export-carousel-remove" id="carouselRemove">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
          Quitar imagen
        </button>
        <button class="export-carousel-add" id="carouselAdd">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Agregar imagen
        </button>
      </div>
    </div>

    {{-- Modal: Confirmar quitar imagen del carrusel --}}
    <div class="print-modal-overlay" id="carouselRemoveOverlay" style="z-index:700">
      <div class="print-modal">
        <div class="print-modal-icon warning">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="print-modal-title">¿Desea quitar la captura?</div>
        <div class="print-modal-sub">Esta imagen será removida de la vista previa del PDF.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn secondary" id="carouselRemoveNo">No</button>
          <button class="print-modal-btn danger" id="carouselRemoveSi">Sí, quitar</button>
        </div>
      </div>
    </div>

    {{-- Modal: Captura eliminada --}}
    <div class="print-modal-overlay" id="carouselRemovedOverlay" style="z-index:700">
      <div class="print-modal">
        <div class="print-modal-icon success">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="print-modal-title">Captura eliminada</div>
        <div class="print-modal-sub">La imagen fue removida de la vista previa.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn primary" id="carouselRemovedOk">Aceptar</button>
        </div>
      </div>
    </div>

    {{-- Modal: Verificar contraseña de protección --}}
    <div class="print-modal-overlay" id="protectPasswordOverlay" style="z-index:700">
      <div class="print-modal" style="max-width:380px">
        <div class="print-modal-icon success">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2b7bf6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        <div class="print-modal-title">Verificar identidad</div>
        <div class="print-modal-sub">Ingresa tu contraseña de acceso para habilitar la protección del PDF.</div>
        <div style="width:100%;position:relative;margin-top:4px">
          <input type="password" id="protectPasswordInput"
            placeholder="Contraseña"
            style="width:100%;box-sizing:border-box;padding:11px 42px 11px 14px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--txt);font:inherit;font-size:14px;outline:none;">
          <button id="protectToggleVisibility" type="button"
            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--txt-soft);display:flex;align-items:center;">
            <svg id="protectEyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        <div id="protectPasswordError" style="width:100%;font-size:12px;color:#f87171;display:none;text-align:left;margin-top:-4px">
          Contraseña incorrecta. Protección desactivada.
        </div>
        <div class="print-modal-actions" style="margin-top:4px">
          <button class="print-modal-btn secondary" id="protectPasswordCancel">Cancelar</button>
          <button class="print-modal-btn primary" id="protectPasswordConfirm">Confirmar</button>
        </div>
      </div>
    </div>

    {{-- Modal: No hay capturas disponibles --}}
    <div class="print-modal-overlay" id="carouselNoCaptOverlay" style="z-index:700">
      <div class="print-modal">
        <div class="print-modal-icon warning">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="print-modal-title">No hay capturas disponibles</div>
        <div class="print-modal-sub">Todas las imágenes ya están incluidas en la vista previa.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn primary" id="carouselNoCaptOk">Aceptar</button>
        </div>
      </div>
    </div>

    {{-- Modal: Agregar imágenes al carrusel --}}
    <div class="print-modal-overlay" id="carouselAddOverlay" style="z-index:700">
      <div class="print-modal" style="max-width:480px">
        <div class="print-modal-title" style="width:100%;text-align:left;font-size:16px">Agregar imágenes</div>
        <div class="print-modal-sub" style="width:100%;text-align:left;margin-bottom:4px">Selecciona las imágenes que deseas agregar a la vista previa:</div>
        <div id="carouselAddList" style="width:100%;display:flex;flex-direction:column;gap:10px;max-height:260px;overflow-y:auto"></div>
        <div class="print-modal-actions" style="margin-top:8px">
          <button class="print-modal-btn secondary" id="carouselAddCancel">Cancelar</button>
          <button class="print-modal-btn primary" id="carouselAddConfirm">Agregar seleccionadas</button>
        </div>
      </div>
    </div>

    {{-- Modal: PDF Descargado --}}
    <div class="print-modal-overlay" id="pdfDownloadedOverlay">
      <div class="print-modal">
        <div class="print-modal-icon success">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        </div>
        <div class="print-modal-title">¡PDF Descargado!</div>
        <div class="print-modal-sub">El archivo se ha guardado correctamente en tu dispositivo.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn primary" id="pdfDownloadedOk">Aceptar</button>
        </div>
      </div>
    </div>

    {{-- Modal: Impresión exitosa --}}
    <div class="print-modal-overlay" id="printSuccessOverlay">
      <div class="print-modal">
        <div class="print-modal-icon success">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="print-modal-title">¡Listo!</div>
        <div class="print-modal-sub">La impresión se ha enviado correctamente.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn primary" id="printSuccessOk">Volver a Capturas</button>
        </div>
      </div>
    </div>

    {{-- Modal: Confirmar cancelar --}}
    <div class="print-modal-overlay" id="printCancelOverlay">
      <div class="print-modal">
        <div class="print-modal-icon warning">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="print-modal-title">¿Desea volver?</div>
        <div class="print-modal-sub">Si vuelve, perderá la configuración de impresión actual.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn secondary" id="printCancelNo">No</button>
          <button class="print-modal-btn danger" id="printCancelSi">Sí, volver</button>
        </div>
      </div>
    </div>

    {{-- Lightbox --}}
    <div class="cap-lightbox" id="capLightbox">
      <button class="cap-lightbox-close" id="capLightboxClose">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      <img id="capLightboxImg" src="" alt="Vista ampliada">
    </div>

  </div>{{-- /#capturasView --}}

  {{-- ═══════════════════════════════════════════════════════════
     MODAL AGREGAR NUEVA NOTA
     ═══════════════════════════════════════════════════════════ --}}
  <div class="nota-modal-overlay" id="notaModalOverlay"></div>
  <div class="nota-modal" id="notaModal">
    <div class="nota-modal-header">
      <h3 class="nota-modal-title">Agregar Nueva Nota</h3>
    </div>
    <div class="nota-modal-body">
      <textarea class="nota-modal-textarea" id="notaTextarea" placeholder="Escribe tu nota aquí..."></textarea>
    </div>
    <div class="nota-modal-footer">
      <button class="btn-nota-volver" id="btnNotaVolver">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver
      </button>
      <button class="btn-nota-guardar" id="btnNotaGuardar">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Guardar Nota
      </button>
    </div>
  </div>

@endsection

@push('scripts')
<script>
(function () {
  /* ── Formulario Crear Estudio: Fecha y hora por defecto ── */
  const now = new Date();
  const pad = n => String(n).padStart(2, '0');
  const local = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
  const fechaHoraInput = document.getElementById('fecha_hora');
  const fechaNacInput = document.getElementById('fecha_nac');
  if (fechaHoraInput) fechaHoraInput.value = local;
  if (fechaNacInput) fechaNacInput.value = '1998-12-25';

  /* ── Menú foto (galería/cámara) ── */
  const btnMenu   = document.getElementById('btnFotoMenu');
  const fotoMenu  = document.getElementById('fotoMenu');
  const btnTxt    = document.getElementById('btnFotoTxt');

  btnMenu?.addEventListener('click', (e) => {
    e.stopPropagation();
    fotoMenu.style.display = fotoMenu.style.display === 'none' ? 'block' : 'none';
  });

  document.addEventListener('click', () => { if(fotoMenu) fotoMenu.style.display = 'none'; });

  document.getElementById('btnGaleria')?.addEventListener('click', () => {
    fotoMenu.style.display = 'none';
    document.getElementById('fotoInput').click();
  });

  document.getElementById('btnCamara')?.addEventListener('click', () => {
    fotoMenu.style.display = 'none';
    document.getElementById('fotoCamera').click();
  });

  function applyPreview(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById('fotoPreview');
      const ph  = document.getElementById('fotoPlaceholder');
      img.src = e.target.result;
      img.style.display = 'block';
      ph.style.display  = 'none';
      btnTxt.textContent = 'Cambiar foto';
    };
    reader.readAsDataURL(file);
  }

  document.getElementById('fotoInput')?.addEventListener('change',  function () { applyPreview(this.files[0]); });
  document.getElementById('fotoCamera')?.addEventListener('change', function () { applyPreview(this.files[0]); });

  /* ── Escape key handler ── */
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      const capturasView = document.getElementById('capturasView');
      if (capturasView && capturasView.style.display === 'block') {
        document.getElementById('btnVolverCapturas')?.click();
      }
    }
  });

  /* ── Búsqueda de Pacientes con menú desplegable ── */
  const pacienteSearchInput = document.getElementById('pacienteSearchInput');
  const btnFiltrarPacientes = document.getElementById('btnFiltrarPacientes');
  const filtrarMenu = document.getElementById('filtrarMenu');
  const filtrarOpciones = document.querySelectorAll('.filtrar-opcion');

  let currentFilter = 'todos';

  // Abrir/cerrar menú al hacer clic en el botón
  btnFiltrarPacientes?.addEventListener('click', (e) => {
    e.stopPropagation();
    filtrarMenu?.classList.toggle('active');
  });

  // Cerrar menú al hacer clic fuera
  document.addEventListener('click', () => {
    filtrarMenu?.classList.remove('active');
  });

  // Manejar selección de opción
  filtrarOpciones.forEach(opcion => {
    opcion.addEventListener('click', () => {
      currentFilter = opcion.dataset.filtro;

      // Actualizar clase activa
      filtrarOpciones.forEach(o => o.classList.remove('active'));
      opcion.classList.add('active');

      // Cerrar menú
      filtrarMenu?.classList.remove('active');

      // Aquí puedes agregar la lógica de filtrado según el filtro seleccionado
      console.log('Filtro seleccionado:', currentFilter);
    });
  });

  // Marcar "Todos" como activo por defecto
  document.querySelector('[data-filtro="todos"]')?.classList.add('active');

  /* ── Navegación a interfaz de Capturas ── */
  const crearLayout = document.querySelector('.crear-layout');
  const crearToolbar = document.querySelector('.crear-toolbar');
  const capturasView = document.getElementById('capturasView');
  const btnVolverCapturas = document.getElementById('btnVolverCapturas');


  // Al hacer clic en "Agregar Capturas" desde el panel lateral
  document.querySelectorAll('.action-btn').forEach(btn => {
    // Verificar que sea el botón de Agregar Capturas por su texto
    if (btn.textContent.includes('Agregar Capturas')) {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        // Ocultar formulario, toolbar y buscador; mostrar capturas
        if (crearLayout) crearLayout.style.display = 'none';
        if (crearToolbar) crearToolbar.style.display = 'none';
        const busquedaWrapper = document.getElementById('pacienteBusquedaWrapper');
        if (busquedaWrapper) busquedaWrapper.style.display = 'none';
        capturasView.style.display = 'block';
        // Actualizar título del header
        document.querySelector('.header-title').textContent = 'Agregar Capturas';
        document.querySelector('.header-sub').textContent = 'Gestión de imágenes del paciente';
      });
    }
  });

  /* ══════════════════════════════════════════
     PANEL EXPORTAR PDF
     ══════════════════════════════════════════ */
  const capListaView    = document.getElementById('capListaView');
  const exportPanel     = document.getElementById('exportPanel');
  const exportListaView = capListaView;

  /* Abrir exportar */
  document.getElementById('btnExportarCaptura')?.addEventListener('click', () => {
    exportListaView.style.display = 'none';
    exportPanel.classList.add('open');
  });

  /* Volver desde exportar */
  document.getElementById('btnExportarVolver')?.addEventListener('click', () => {
    exportPanel.classList.remove('open');
    exportListaView.style.display = 'block';
  });

  /* Zoom exportar */
  let exportZoom = 68;
  const exportSheetEl = document.getElementById('exportSheet');
  function applyExportZoom() {
    document.getElementById('exportZoomVal').textContent = exportZoom + '%';
    if (exportSheetEl) {
      exportSheetEl.style.transform = `scale(${exportZoom/100})`;
      const isLandscape = exportSheetEl.classList.contains('landscape');
      const naturalH = isLandscape ? 595 : 842;
      exportSheetEl.style.marginBottom = `calc(${naturalH}px * -${(100 - exportZoom) / 100})`;
    }
  }
  document.getElementById('exportZoomIn')?.addEventListener('click', () => { if (exportZoom < 150) { exportZoom += 10; applyExportZoom(); } });
  document.getElementById('exportZoomOut')?.addEventListener('click', () => { if (exportZoom > 40) { exportZoom -= 10; applyExportZoom(); } });

  /* Orientación exportar */
  document.getElementById('expOrientV')?.addEventListener('click', () => {
    document.getElementById('expOrientV').classList.add('active');
    document.getElementById('expOrientH').classList.remove('active');
    if (exportSheetEl) exportSheetEl.classList.remove('landscape');
    applyExportZoom();
  });
  document.getElementById('expOrientH')?.addEventListener('click', () => {
    document.getElementById('expOrientH').classList.add('active');
    document.getElementById('expOrientV').classList.remove('active');
    if (exportSheetEl) exportSheetEl.classList.add('landscape');
    applyExportZoom();
  });

  /* Checkboxes → actualizar documento */
  function updateExportDoc() {
    const showHeader = document.getElementById('expChkHeader')?.checked;
    const showFecha  = document.getElementById('expChkFecha')?.checked;
    const showDesc   = document.getElementById('expChkDesc')?.checked;
    const showNum    = document.getElementById('expChkNum')?.checked;

    const header = document.getElementById('exportSheetHeader');
    if (header) header.style.display = showHeader ? '' : 'none';

    document.querySelectorAll('#exportSheet .export-sheet-meta').forEach(el => {
      el.style.display = showFecha ? '' : 'none';
    });
    document.querySelectorAll('#exportSheet [data-export-desc-label], #exportSheet [data-export-desc]').forEach(el => {
      el.style.display = showDesc ? '' : 'none';
    });
    document.querySelectorAll('#exportSheet [data-export-num]').forEach(el => {
      el.style.display = showNum ? '' : 'none';
    });
  }
  ['expChkHeader','expChkFecha','expChkDesc','expChkNum'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', updateExportDoc);
  });

  /* ── Protección con contraseña ── */
  // Contraseña simulada del login (en producción vendría del backend)
  const LOGIN_PASSWORD = 'admin123';

  const chkProtect = document.getElementById('expChkProtect');
  chkProtect?.addEventListener('change', function() {
    if (this.checked) {
      // Abrir modal para verificar contraseña
      const input = document.getElementById('protectPasswordInput');
      const errEl = document.getElementById('protectPasswordError');
      if (input) input.value = '';
      if (errEl) errEl.style.display = 'none';
      document.getElementById('protectPasswordOverlay').classList.add('open');
    }
  });

  // Toggle visibilidad contraseña
  document.getElementById('protectToggleVisibility')?.addEventListener('click', () => {
    const input = document.getElementById('protectPasswordInput');
    const icon  = document.getElementById('protectEyeIcon');
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
      input.type = 'password';
      icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
  });

  // Cancelar → desmarcar checkbox
  document.getElementById('protectPasswordCancel')?.addEventListener('click', () => {
    document.getElementById('protectPasswordOverlay').classList.remove('open');
    if (chkProtect) chkProtect.checked = false;
  });

  // Confirmar → validar contraseña
  document.getElementById('protectPasswordConfirm')?.addEventListener('click', () => {
    const input = document.getElementById('protectPasswordInput');
    const errEl = document.getElementById('protectPasswordError');
    if (input?.value === LOGIN_PASSWORD) {
      // Contraseña correcta → cerrar modal, protección activa
      document.getElementById('protectPasswordOverlay').classList.remove('open');
    } else {
      // Incorrecta → mostrar error 1.5s, desmarcar y cerrar
      if (errEl) errEl.style.display = 'block';
      setTimeout(() => {
        document.getElementById('protectPasswordOverlay').classList.remove('open');
        if (chkProtect) chkProtect.checked = false;
        if (errEl) errEl.style.display = 'none';
      }, 1500);
    }
  });

  // Enter en el input → confirmar
  document.getElementById('protectPasswordInput')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') document.getElementById('protectPasswordConfirm')?.click();
  });

  /* Descargar PDF → modal */
  document.getElementById('btnDescargarPDF')?.addEventListener('click', () => {
    document.getElementById('pdfDownloadedOverlay').classList.add('open');
  });
  document.getElementById('pdfDownloadedOk')?.addEventListener('click', () => {
    document.getElementById('pdfDownloadedOverlay').classList.remove('open');
    exportPanel.classList.remove('open');
    exportListaView.style.display = 'block';
  });

  /* ── Carrusel Vista Previa ── */
  // Pool completo de capturas del sistema (con id único para rastrear)
  const allCapturas = [
    { id: 1, src: '{{ asset("images/captura1.jpg") }}', name: 'Imagen 1' },
    { id: 2, src: '{{ asset("images/captura1.jpg") }}', name: 'Imagen 2' },
    { id: 3, src: '{{ asset("images/captura1.jpg") }}', name: 'Imagen 3' },
  ];
  // Las que están en el carrusel (ids activos)
  let carouselImages = allCapturas.map(c => ({ ...c }));
  let carouselIdx = 0;

  function renderCarousel() {
    const img       = document.getElementById('carouselImg');
    const counter   = document.getElementById('carouselCounter');
    const dots      = document.getElementById('carouselDots');
    const prev      = document.getElementById('carouselPrev');
    const next      = document.getElementById('carouselNext');
    const removeBtn = document.getElementById('carouselRemove');
    if (!img) return;
    const cur = carouselImages[carouselIdx];
    img.src = cur.src;
    const infoName = document.getElementById('carouselInfoName');
    const infoDesc = document.getElementById('carouselInfoDesc');
    const infoDate = document.getElementById('carouselInfoDate');
    if (infoName) infoName.textContent = cur.name || '';
    if (infoDesc) infoDesc.textContent = cur.desc || 'Lesión observada en estudio';
    if (infoDate) infoDate.textContent = cur.date ? `Fecha: ${cur.date}` : 'Fecha: 05/mayo/2026';
    counter.textContent = `${carouselIdx + 1} / ${carouselImages.length}`;
    prev.disabled = carouselIdx === 0;
    next.disabled = carouselIdx === carouselImages.length - 1;
    if (removeBtn) removeBtn.disabled = carouselImages.length <= 1;
    dots.innerHTML = '';
    carouselImages.forEach((_, i) => {
      const d = document.createElement('button');
      d.className = 'export-carousel-dot' + (i === carouselIdx ? ' active' : '');
      d.addEventListener('click', () => { carouselIdx = i; renderCarousel(); });
      dots.appendChild(d);
    });
  }

  document.getElementById('btnVistaPrevia')?.addEventListener('click', () => {
    carouselIdx = 0;
    renderCarousel();
    document.getElementById('exportCarousel').classList.add('open');
  });
  document.getElementById('exportCarouselClose')?.addEventListener('click', () => {
    document.getElementById('exportCarousel').classList.remove('open');
  });
  document.getElementById('carouselPrev')?.addEventListener('click', () => {
    if (carouselIdx > 0) { carouselIdx--; renderCarousel(); }
  });
  document.getElementById('carouselNext')?.addEventListener('click', () => {
    if (carouselIdx < carouselImages.length - 1) { carouselIdx++; renderCarousel(); }
  });

  /* Quitar imagen → modal confirmación → devuelve al pool disponible */
  document.getElementById('carouselRemove')?.addEventListener('click', () => {
    if (carouselImages.length <= 1) return;
    document.getElementById('carouselRemoveOverlay').classList.add('open');
  });
  document.getElementById('carouselRemoveNo')?.addEventListener('click', () => {
    document.getElementById('carouselRemoveOverlay').classList.remove('open');
  });
  document.getElementById('carouselRemoveSi')?.addEventListener('click', () => {
    document.getElementById('carouselRemoveOverlay').classList.remove('open');
    // La eliminada queda en allCapturas (no se borra), solo se quita del carrusel activo
    carouselImages.splice(carouselIdx, 1);
    if (carouselIdx >= carouselImages.length) carouselIdx = carouselImages.length - 1;
    document.getElementById('carouselRemovedOverlay').classList.add('open');
  });
  document.getElementById('carouselRemovedOk')?.addEventListener('click', () => {
    document.getElementById('carouselRemovedOverlay').classList.remove('open');
    renderCarousel();
  });

  /* Agregar imagen → pool dinámico: muestra las que NO están en carrusel */
  document.getElementById('carouselAdd')?.addEventListener('click', () => {
    const activeIds = carouselImages.map(c => c.id);
    const available = allCapturas.filter(c => !activeIds.includes(c.id));

    if (available.length === 0) {
      // No hay disponibles → modal informativo y cierra
      document.getElementById('carouselNoCaptOverlay').classList.add('open');
      return;
    }

    const list = document.getElementById('carouselAddList');
    list.innerHTML = '';
    available.forEach((cap, i) => {
      const item = document.createElement('label');
      item.className = 'carousel-add-item';
      item.innerHTML = `
        <img src="${cap.src}" alt="${cap.name}">
        <span class="carousel-add-item-name">${cap.name}</span>
        <input type="checkbox" class="carousel-add-item-check" data-idx="${i}">
      `;
      item.querySelector('input').addEventListener('change', function() {
        item.classList.toggle('selected', this.checked);
      });
      list.appendChild(item);
    });
    const overlay = document.getElementById('carouselAddOverlay');
    overlay._available = available;
    overlay.classList.add('open');
  });

  document.getElementById('carouselNoCaptOk')?.addEventListener('click', () => {
    document.getElementById('carouselNoCaptOverlay').classList.remove('open');
    // Queda en el carrusel
  });
  document.getElementById('carouselAddCancel')?.addEventListener('click', () => {
    document.getElementById('carouselAddOverlay').classList.remove('open');
  });
  document.getElementById('carouselAddConfirm')?.addEventListener('click', () => {
    const overlay = document.getElementById('carouselAddOverlay');
    const available = overlay._available || [];
    const checks = overlay.querySelectorAll('.carousel-add-item-check:checked');
    checks.forEach(chk => {
      const idx = parseInt(chk.dataset.idx);
      if (available[idx]) carouselImages.push({ ...available[idx] });
    });
    overlay.classList.remove('open');
    carouselIdx = carouselImages.length - 1;
    renderCarousel();
  });

  document.addEventListener('keydown', e => {
    const carousel = document.getElementById('exportCarousel');
    if (!carousel?.classList.contains('open')) return;
    if (e.key === 'ArrowLeft' && carouselIdx > 0) { carouselIdx--; renderCarousel(); }
    if (e.key === 'ArrowRight' && carouselIdx < carouselImages.length - 1) { carouselIdx++; renderCarousel(); }
    if (e.key === 'Escape') carousel.classList.remove('open');
  });

  // Botón volver - regresar al formulario
  btnVolverCapturas?.addEventListener('click', () => {
    capturasView.style.display = 'none';
    if (crearLayout) crearLayout.style.display = 'grid';
    if (crearToolbar) crearToolbar.style.display = 'flex';
    const busquedaWrapper = document.getElementById('pacienteBusquedaWrapper');
    if (busquedaWrapper) busquedaWrapper.style.display = '';
    // Restaurar título original
    document.querySelector('.header-title').textContent = 'Nuevo Estudio';
    document.querySelector('.header-sub').textContent = 'Datos nuevos';
  });

  /* ── Lightbox ── */
  const lightbox = document.getElementById('capLightbox');
  const lightboxImg = document.getElementById('capLightboxImg');
  document.getElementById('capLightboxClose')?.addEventListener('click', () => lightbox.classList.remove('open'));
  lightbox?.addEventListener('click', (e) => { if (e.target === lightbox) lightbox.classList.remove('open'); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') lightbox?.classList.remove('open'); });

  document.querySelectorAll('.cap-thumb-expand').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const img = btn.closest('.cap-thumb-v').querySelector('img');
      lightboxImg.src = img.src;
      lightbox.classList.add('open');
    });
  });

  /* ── Lista de capturas: selección + vista previa ── */
  const itemsV = document.querySelectorAll('.cap-item-v');
  const capPrevImg = document.getElementById('capPrevImg');

  itemsV.forEach((item, idx) => {
    item.addEventListener('click', (e) => {
      if (e.target.closest('.cap-more-v') || e.target.closest('.cap-dropdown')) return;
      itemsV.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      const d = item.dataset;
      if (capPrevImg) capPrevImg.src = '{{ asset('images/captura1.jpg') }}';
      const piFecha = document.getElementById('capPiFecha');
      const piDesc = document.getElementById('capPiDesc');
      const piEstudio = document.getElementById('capPiEstudio');
      const piTipo = document.getElementById('capPiTipo');
      const piImagen = document.getElementById('capPiImagen');
      if (piFecha) piFecha.textContent = d.fecha + ' ' + d.hora;
      if (piDesc) piDesc.textContent = d.nombre;
      if (piEstudio) piEstudio.textContent = d.estudio;
      if (piTipo) piTipo.textContent = d.tipoEstudio;
      if (piImagen) piImagen.textContent = (idx + 1) + ' de ' + itemsV.length;
    });
  });
  if (itemsV.length) itemsV[0].click();

  /* ── Buscador de capturas ── */
  const capSearchV = document.getElementById('capSearchV');
  if (capSearchV) {
    capSearchV.addEventListener('input', () => {
      const q = capSearchV.value.toLowerCase().trim();
      let visible = 0;
      itemsV.forEach(item => {
        const match = item.dataset.nombre.toLowerCase().includes(q);
        item.style.display = match ? '' : 'none';
        if (match) visible++;
      });
      const footerV = document.getElementById('capFooterV');
      if (footerV) footerV.textContent = `Mostrando ${visible} de ${itemsV.length}`;
    });
  }

  /* ── Botón Editar → abrir Editor ── */
  document.getElementById('btnAbrirEditor')?.addEventListener('click', () => {
    document.getElementById('capListaView').style.display = 'none';
    document.getElementById('capEditorView').style.display = 'block';
  });

  /* ── Panel Imprimir ── */
  const printPanel = document.getElementById('printPanel');

  function volverAListaCapturas() {
    printPanel.classList.remove('open');
    capListaView.style.display = 'block';
  }

  document.getElementById('btnImprimirCaptura')?.addEventListener('click', () => {
    capListaView.style.display = 'none';
    printPanel.classList.add('open');
  });

  /* Botón Imprimir → modal éxito */
  document.querySelector('.print-confirm-btn')?.addEventListener('click', () => {
    document.getElementById('printSuccessOverlay').classList.add('open');
  });
  document.getElementById('printSuccessOk')?.addEventListener('click', () => {
    document.getElementById('printSuccessOverlay').classList.remove('open');
    volverAListaCapturas();
  });

  /* Botón Cancelar → modal confirmación */
  document.getElementById('btnCancelarImpresion')?.addEventListener('click', () => {
    document.getElementById('printCancelOverlay').classList.add('open');
  });
  document.getElementById('printCancelNo')?.addEventListener('click', () => {
    document.getElementById('printCancelOverlay').classList.remove('open');
  });
  document.getElementById('printCancelSi')?.addEventListener('click', () => {
    document.getElementById('printCancelOverlay').classList.remove('open');
    volverAListaCapturas();
  });

  /* Copias */
  let printCopies = 1;
  document.getElementById('printCopiesUp')?.addEventListener('click', () => {
    printCopies++;
    document.getElementById('printCopiesVal').textContent = printCopies;
  });
  document.getElementById('printCopiesDown')?.addEventListener('click', () => {
    if (printCopies > 1) { printCopies--; document.getElementById('printCopiesVal').textContent = printCopies; }
  });

  /* Orientación */
  const sheet = document.getElementById('printSheet');
  document.getElementById('printOrientV')?.addEventListener('click', () => {
    document.getElementById('printOrientV').classList.add('active');
    document.getElementById('printOrientH').classList.remove('active');
    if (sheet) sheet.classList.remove('landscape');
  });
  document.getElementById('printOrientH')?.addEventListener('click', () => {
    document.getElementById('printOrientH').classList.add('active');
    document.getElementById('printOrientV').classList.remove('active');
    if (sheet) sheet.classList.add('landscape');
  });

  /* Zoom */
  let printZoom = 68;
  const printSheetEl = document.getElementById('printSheet');
  function applyZoom() {
    document.getElementById('printZoomVal').textContent = printZoom + '%';
    if (printSheetEl) {
      printSheetEl.style.transform = `scale(${printZoom/100})`;
      const isLandscape = printSheetEl.classList.contains('landscape');
      const naturalH = isLandscape ? 595 : 842;
      printSheetEl.style.marginBottom = `calc(${naturalH}px * -${(100 - printZoom) / 100})`;
    }
  }
  applyZoom();
  document.getElementById('printZoomIn')?.addEventListener('click', () => {
    if (printZoom < 150) { printZoom += 10; applyZoom(); }
  });
  document.getElementById('printZoomOut')?.addEventListener('click', () => {
    if (printZoom > 40) { printZoom -= 10; applyZoom(); }
  });

  /* Fit / ajustar a pantalla */
  let fitMode = false;
  const exitFsBtn = document.getElementById('printExitFs');
  function toggleFitMode(on) {
    fitMode = on;
    const fitBtn = document.getElementById('printFitBtn');
    const wrap = document.querySelector('.print-sheet-wrap');
    if (on) {
      fitBtn?.classList.add('fullscreen');
      if (wrap) { wrap.style.position = 'fixed'; wrap.style.inset = '0'; wrap.style.zIndex = '200'; wrap.style.borderRadius = '0'; wrap.style.padding = '40px'; }
      exitFsBtn?.classList.add('visible');
    } else {
      fitBtn?.classList.remove('fullscreen');
      if (wrap) { wrap.style.position = ''; wrap.style.inset = ''; wrap.style.zIndex = ''; wrap.style.borderRadius = ''; wrap.style.padding = ''; }
      exitFsBtn?.classList.remove('visible');
    }
  }
  document.getElementById('printFitBtn')?.addEventListener('click', () => toggleFitMode(!fitMode));
  exitFsBtn?.addEventListener('click', () => toggleFitMode(false));

  /* ── Botón Volver a Capturas → cerrar Editor ── */
  document.getElementById('btnCerrarEditor')?.addEventListener('click', () => {
    document.getElementById('capEditorView').style.display = 'none';
    document.getElementById('capListaView').style.display = 'block';
  });

  /* ── Acciones de fotos en editor ── */
  document.querySelectorAll('.cap-foto-card').forEach(card => {
    card.addEventListener('click', (e) => {
      if (!e.target.closest('.cap-foto-action')) {
        card.classList.toggle('selected');
      }
    });
  });

  /* ── Tool buttons en editor ── */
  document.querySelectorAll('.cap-tool-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.cap-tool-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  /* ── Modal Agregar Nota ── */
  const notaModal = document.getElementById('notaModal');
  const notaModalOverlay = document.getElementById('notaModalOverlay');
  const btnNotaVolver = document.getElementById('btnNotaVolver');
  const btnNotaGuardar = document.getElementById('btnNotaGuardar');
  const notaTextarea = document.getElementById('notaTextarea');

  // Función para abrir modal
  function openNotaModal() {
    notaModal.classList.add('active');
    notaModalOverlay.classList.add('active');
    notaTextarea.focus();
  }

  // Función para cerrar modal
  function closeNotaModal() {
    notaModal.classList.remove('active');
    notaModalOverlay.classList.remove('active');
    notaTextarea.value = '';
  }

  // Click en botón "Agregar a informe" (documento con +) de cada foto
  document.querySelectorAll('.cap-foto-btn[title="Agregar a informe"]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      openNotaModal();
    });
  });

  // Botones del modal
  btnNotaVolver?.addEventListener('click', closeNotaModal);
  notaModalOverlay?.addEventListener('click', closeNotaModal);

  btnNotaGuardar?.addEventListener('click', () => {
    const nota = notaTextarea.value.trim();
    if (nota) {
      alert('Nota guardada exitosamente!');
      closeNotaModal();
    } else {
      alert('Por favor escribe una nota antes de guardar.');
    }
  });

  // Cerrar con Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && notaModal.classList.contains('active')) {
      closeNotaModal();
    }
  });
})();
</script>
@endpush