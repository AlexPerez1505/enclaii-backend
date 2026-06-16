@extends('layouts.app')

@section('title', 'Nuevo Estudio/Grabando')
@section('active', 'nuevo-estudio')

@push('styles')
<style>
/* ═══════════════════════════════════════════════
   DISEÑO SEGÚN REFERENCIA - Ocultar head, mantener sidebar
════════════════════════════════════════════════ */
.head { display: none !important; }
.main { padding: 0 !important; overflow: hidden !important; background: #0a0f1e !important; }
.dash { grid-template-columns: 260px 1fr !important; }

/* Sidebar siempre visible en ambos modos */
.side {
  display: flex !important;
  flex-direction: column;
  background: #0a0f1e;
  border-right: 1px solid rgba(255,255,255,.08);
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.studio-wrap {
  display: flex;
  flex-direction: column;
  height: 100vh;
  background: #0a0f1e;
  font-family: 'Hanken Grotesk', sans-serif;
  color: #fff;
}

/* ═══════ TOPBAR ═══════ */
.studio-topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 24px;
  background: #0d1320;
  border-bottom: 1px solid rgba(255,255,255,.06);
  flex: none;
}

/* Separador vertical del topbar */
.studio-topbar-sep {
  width: 1px;
  height: 40px;
  background: rgba(255,255,255,.15);
  margin: 0 20px;
}

.studio-top-left {
  display: flex;
  align-items: center;
  gap: 24px;
}

.studio-rec-status {
  display: flex;
  align-items: center;
  gap: 12px;
}
.studio-rec-dot {
  width: 14px; height: 14px;
  border-radius: 50%;
  background: #ff0000;
  box-shadow: 0 0 0 4px rgba(255,0,0,.3);
  animation: pulseRec 1.5s ease-in-out infinite;
}
@keyframes pulseRec {
  0%, 100% { opacity: 1; box-shadow: 0 0 0 4px rgba(255,0,0,.3); }
  50% { opacity: .6; box-shadow: 0 0 0 8px rgba(255,0,0,.1); }
}
.studio-rec-text {
  font-family: 'Sora', sans-serif;
  font-size: 18px;
  font-weight: 800;
  color: #ff0000;
  letter-spacing: .05em;
}
.studio-timer {
  font-family: 'Sora', sans-serif;
  font-size: 24px;
  font-weight: 700;
  color: #fff;
  letter-spacing: .05em;
  min-width: 90px;
}
.studio-study-name {
  font-size: 13px;
  color: rgba(255,255,255,.6);
  margin-top: 2px;
}

.studio-top-center {
  display: flex;
  align-items: center;
  gap: 20px;
}

.studio-storage {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 16px;
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 8px;
}
.studio-storage-icon { color: #0ea5e9; }
.studio-storage-text { font-size: 12px; color: rgba(255,255,255,.7); }
.studio-storage-bar {
  width: 80px; height: 4px;
  background: rgba(255,255,255,.15);
  border-radius: 2px;
  overflow: hidden;
  margin-top: 4px;
}
.studio-storage-fill {
  height: 100%; width: 64%;
  background: linear-gradient(90deg, #22c55e, #4ade80);
  border-radius: 2px;
}

.studio-ia-btn {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 16px;
  background: rgba(14,165,233,.1);
  border: 1px solid rgba(14,165,233,.3);
  border-radius: 8px;
  color: #38bdf8;
  font-size: 13px; font-weight: 600;
  cursor: pointer;
}

.studio-notif {
  position: relative;
  width: 36px; height: 36px;
  display: grid; place-items: center;
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 8px;
  cursor: pointer;
}
.studio-notif-badge {
  position: absolute; top: -4px; right: -4px;
  width: 18px; height: 18px;
  background: #0ea5e9;
  border-radius: 50%;
  font-size: 10px; font-weight: 700;
  display: grid; place-items: center;
}

.studio-doctor {
  display: flex; align-items: center; gap: 10px;
}
.studio-doc-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: #1e293b;
  border: 2px solid #0ea5e9;
  display: grid; place-items: center;
  font-size: 12px; font-weight: 700; color: #fff;
}
.studio-doc-name { font-size: 13px; font-weight: 600; }
.studio-doc-role { font-size: 11px; color: rgba(255,255,255,.5); }

.studio-top-right {
  display: flex;
  align-items: center;
  gap: 12px;
}

.studio-btn-emergency {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 18px;
  background: rgba(239,68,68,.15);
  border: 1px solid rgba(239,68,68,.4);
  border-radius: 8px;
  color: #f87171;
  font-size: 13px; font-weight: 600;
  cursor: pointer;
  transition: all 150ms;
}
.studio-btn-emergency:hover { background: rgba(239,68,68,.25); }

.studio-btn-volver {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 18px;
  background: rgba(14, 165, 233, .15);
  border: 1px solid rgba(14, 165, 233, .4);
  border-radius: 8px;
  color: #38bdf8;
  font-size: 13px; font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 150ms;
}
.studio-btn-volver:hover { background: rgba(14, 165, 233, .25); border-color: rgba(14, 165, 233, .6); }
.studio-btn-volver svg { color: #38bdf8; }

/* ═════ MAIN CONTENT ═════ */
.studio-main {
  flex: 1;
  display: grid;
  grid-template-columns: 1fr 260px;
  gap: 16px;
  padding: 16px;
  overflow: hidden;
}

/* ═════ COLUMNA CENTRAL ═════ */
.studio-center {
  display: flex;
  flex-direction: column;
  gap: 16px;
  overflow: hidden;
}

/* Video Box */
.studio-video-box {
  flex: 1;
  position: relative;
  border: 2px solid #0ea5e9;
  border-radius: 12px;
  overflow: hidden;
  background: #000;
  min-height: 0;
}
.studio-video-screen {
  width: 100%; height: 100%;
  display: flex; align-items: center; justify-content: center;
  background: linear-gradient(135deg, #1a1f35 0%, #0d1320 100%);
}
.studio-video-screen img, .studio-video-screen video {
  width: 100%; height: 100%;
  object-fit: cover;
}

/* Video HUD - Estilo exacto referencia */
.studio-hud {
  position: absolute; top: 12px; left: 12px;
  background: rgba(0,0,0,.75);
  border: 1px solid rgba(255,255,255,.15);
  border-radius: 6px;
  padding: 8px 12px;
  font-size: 11px;
  font-weight: 500;
  color: #fff;
  line-height: 1.5;
  font-family: 'Hanken Grotesk', sans-serif;
}
.studio-hud-dot {
  display: inline-block;
  width: 8px; height: 8px; border-radius: 50%;
  background: #ff0000; margin-right: 4px;
  animation: pulseRec 1.5s infinite;
  vertical-align: middle;
}
/* Botón pantalla completa */
.studio-expand-btn {
  position: absolute; bottom: 12px; right: 12px;
  width: 32px; height: 32px;
  background: rgba(0,0,0,.6);
  border: 1px solid rgba(255,255,255,.2);
  border-radius: 8px;
  display: grid; place-items: center;
  color: rgba(255,255,255,.7);
  cursor: pointer;
  transition: all 150ms;
  z-index: 10;
}
.studio-expand-btn:hover { background: rgba(14,165,233,.3); color: #38bdf8; border-color: #0ea5e9; }

/* Modo expandido - mantiene sidebar izquierda, oculta sidebar derecho y línea de tiempo, muestra botones */
body.studio-expanded .studio-main {
  grid-template-columns: 1fr !important;
}
body.studio-expanded .studio-sidebar {
  display: none !important;
}
body.studio-expanded .studio-timeline {
  display: none !important;
}
body.studio-expanded .studio-center {
  gap: 16px;
}
body.studio-expanded .studio-video-box {
  min-height: 70vh;
  flex: 1;
}
/* En modo expandido, el sidebar izquierdo permanece visible */
body.studio-expanded .side {
  display: flex !important;
}

/* Timeline */
.studio-timeline {
  background: rgba(255,255,255,.02);
  border: 1px solid rgba(255,255,255,.06);
  border-radius: 12px;
  padding: 14px;
}
.studio-tl-header { display: flex; justify-content: space-between; margin-bottom: 12px; }
.studio-tl-title { font-size: 13px; font-weight: 600; color: rgba(255,255,255,.8); }
.studio-tl-scroll { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 4px; }
.studio-thumb { width: 80px; height: 56px; border-radius: 8px; overflow: hidden; border: 2px solid transparent; background: #1e293b; flex: none; cursor: pointer; position: relative; }
.studio-thumb.active { border-color: #0ea5e9; }
.studio-thumb-time { position: absolute; bottom: 4px; right: 6px; font-size: 10px; font-weight: 600; color: rgba(255,255,255,.8); background: rgba(0,0,0,.5); padding: 1px 4px; border-radius: 3px; }

/* Bottom Bar - Estilo exacto referencia - Botones centrados */
.studio-bottom {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 24px;
  padding: 16px 20px;
  background: rgba(13, 19, 32, .8);
  border: 1px solid rgba(14, 165, 233, .3);
  border-radius: 10px;
}
.studio-actions {
  display: flex;
  flex-direction: row;
  gap: 10px;
}
.studio-action-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 18px;
  background: rgba(10, 15, 30, .6);
  border: none;
  border-radius: 8px;
  color: rgba(255, 255, 255, .85);
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: all 150ms;
}
.studio-action-btn:hover { background: rgba(14, 165, 233, .15); }
.studio-action-btn svg { color: #0ea5e9; flex: none; }

/* Separador vertical */
.studio-divider {
  width: 1px;
  height: 40px;
  background: rgba(14, 165, 233, .3);
  margin: 0 16px;
}

/* Botón Pausar */
.studio-pause-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 20px;
  background: rgba(10, 15, 30, .6);
  border: none;
  border-radius: 8px;
  color: rgba(255, 255, 255, .85);
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: all 150ms;
}
.studio-pause-btn svg { color: #0ea5e9; }
.studio-pause-btn:hover { background: rgba(14, 165, 233, .15); }

/* Botón Terminar Estudio - Rojo */
.studio-terminar-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 14px 22px;
  background: #dc2626;
  border: none;
  border-radius: 10px;
  color: #fff;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  box-shadow: 0 4px 20px rgba(220, 38, 38, .4);
  transition: all 150ms;
}
.studio-terminar-btn:hover { background: #b91c1c; box-shadow: 0 6px 25px rgba(220, 38, 38, .5); }
.studio-terminar-btn svg { flex: none; }

/* ═════ INTERFAZ ESTUDIO FINALIZADO ═════ */
.studio-emergencia-wrap {
  display: none;
  flex-direction: column;
  height: 100vh;
  background: #0a0f1e;
  font-family: 'Hanken Grotesk', sans-serif;
  color: #fff;
}
.studio-emergencia-wrap.active { display: flex; }

.studio-emergencia-status .studio-status-icon {
  background: #dc2626;
  animation: pulseEmergency 1.5s infinite;
}
@keyframes pulseEmergency {
  0%, 100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
  50% { box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
}
.studio-emergencia-status .studio-status-text {
  color: #dc2626;
}

.studio-finalizado-wrap {
  display: none;
  flex-direction: column;
  height: 100vh;
  background: #0a0f1e;
  font-family: 'Hanken Grotesk', sans-serif;
  color: #fff;
}
.studio-finalizado-wrap.active { display: flex; }

.studio-finalizado-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 24px;
  background: #0d1320;
  border-bottom: 1px solid rgba(255,255,255,.06);
}

.studio-finalizado-status {
  display: flex;
  align-items: center;
  gap: 10px;
}
.studio-status-icon {
  width: 24px; height: 24px;
  border-radius: 50%;
  background: #22c55e;
  display: grid; place-items: center;
  color: #fff;
}
.studio-status-text {
  font-size: 18px;
  font-weight: 700;
  color: #22c55e;
}
.studio-status-sub {
  font-size: 13px;
  color: rgba(255,255,255,.6);
  margin-top: 2px;
}

.studio-finalizado-main {
  flex: 1;
  display: grid;
  grid-template-columns: 1fr 320px;
  gap: 20px;
  padding: 20px;
  overflow: hidden;
}

/* Video player con controles */
.studio-video-player {
  background: #0d1320;
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 12px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}
.studio-video-display {
  flex: 1;
  background: linear-gradient(135deg, #1a1f35 0%, #0d1320 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}
.studio-video-controls {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 12px 20px;
  background: rgba(0,0,0,.5);
  border-top: 1px solid rgba(255,255,255,.1);
}
.studio-control-btn {
  background: transparent;
  border: none;
  color: #fff;
  cursor: pointer;
  padding: 8px;
  border-radius: 6px;
  transition: all 150ms;
}
.studio-control-btn:hover { background: rgba(255,255,255,.1); }
.studio-progress-bar {
  flex: 1;
  height: 6px;
  background: rgba(255,255,255,.2);
  border-radius: 3px;
  position: relative;
}
.studio-progress-fill {
  position: absolute;
  left: 0; top: 0;
  height: 100%;
  width: 35%;
  background: #0ea5e9;
  border-radius: 3px;
}
.studio-progress-thumb {
  position: absolute;
  left: 35%; top: 50%;
  transform: translate(-50%, -50%);
  width: 14px; height: 14px;
  background: #0ea5e9;
  border-radius: 50%;
  box-shadow: 0 0 10px rgba(14,165,233,.5);
}
.studio-time-display {
  font-size: 13px;
  color: rgba(255,255,255,.8);
  font-family: 'Sora', monospace;
}

/* Timeline finalizado */
.studio-timeline-final {
  background: rgba(255,255,255,.02);
  border: 1px solid rgba(255,255,255,.06);
  border-radius: 12px;
  padding: 16px;
  margin-top: 16px;
}
.studio-timeline-title {
  font-size: 14px;
  font-weight: 600;
  color: rgba(255,255,255,.8);
  margin-bottom: 12px;
}
.studio-timeline-track {
  display: flex;
  gap: 8px;
  overflow-x: auto;
  padding-bottom: 8px;
}
.studio-timeline-thumb {
  width: 100px;
  height: 70px;
  border-radius: 8px;
  border: 2px solid transparent;
  overflow: hidden;
  flex: none;
  cursor: pointer;
  position: relative;
}
.studio-timeline-thumb.active {
  border-color: #0ea5e9;
}
.studio-timeline-thumb img {
  width: 100%; height: 100%;
  object-fit: cover;
}
.studio-timeline-dot {
  position: absolute;
  bottom: 4px;
  left: 50%;
  transform: translateX(-50%);
  width: 6px; height: 6px;
  background: #0ea5e9;
  border-radius: 50%;
}

/* Info grid */
.studio-info-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin-top: 16px;
}
.studio-info-card {
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 10px;
  padding: 14px;
}
.studio-info-card-title {
  font-size: 12px;
  font-weight: 600;
  color: rgba(255,255,255,.7);
  margin-bottom: 8px;
}
.studio-info-card-text {
  font-size: 12px;
  color: rgba(255,255,255,.5);
  line-height: 1.5;
}

/* Sidebar resumen */
.studio-resumen-sidebar {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.studio-resumen-card {
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 12px;
  padding: 16px;
}
.studio-resumen-title {
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 1px solid rgba(255,255,255,.1);
}
.studio-resumen-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid rgba(255,255,255,.06);
}
.studio-resumen-item:last-child { border-bottom: none; }
.studio-resumen-label {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
  color: rgba(255,255,255,.7);
}
.studio-resumen-icon {
  width: 28px; height: 28px;
  background: rgba(14,165,233,.15);
  border-radius: 6px;
  display: grid; place-items: center;
  color: #0ea5e9;
}
.studio-resumen-value {
  font-size: 13px;
  font-weight: 600;
  color: #fff;
}
.studio-resumen-value.green { color: #22c55e; }

/* Acciones */
.studio-acciones-card {
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 12px;
  padding: 16px;
}
.studio-acciones-title {
  font-size: 13px;
  font-weight: 600;
  color: rgba(255,255,255,.6);
  margin-bottom: 12px;
}
.studio-accion-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid rgba(255,255,255,.06);
  cursor: pointer;
  transition: all 150ms;
}
.studio-accion-item:hover { background: rgba(255,255,255,.03); margin: 0 -16px; padding: 12px 16px; }
.studio-accion-item:last-child { border-bottom: none; }
.studio-accion-label {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
  color: rgba(255,255,255,.8);
}
.studio-accion-arrow {
  color: rgba(255,255,255,.4);
}

/* Sidebar */
.studio-sidebar { display: flex; flex-direction: column; gap: 16px; }
.studio-sidebar-title { font-size: 16px; font-weight: 700; color: #fff; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,.1); }
.studio-stat-card { background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.08); border-radius: 10px; padding: 14px; }
.studio-stat-header { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.studio-stat-icon { width: 28px; height: 28px; background: rgba(14,165,233,.15); border-radius: 6px; display: grid; place-items: center; color: #0ea5e9; }
.studio-stat-label { font-size: 12px; color: rgba(255,255,255,.6); }
.studio-stat-value { font-size: 22px; font-weight: 700; color: #fff; font-family: 'Sora', sans-serif; }
.studio-stat-value.red { color: #ef4444; }
.studio-info-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,.06); }
.studio-info-icon { width: 32px; height: 32px; border-radius: 8px; display: grid; place-items: center; }
.studio-info-icon.blue { background: rgba(14,165,233,.15); color: #0ea5e9; }
.studio-info-icon.orange { background: rgba(245,158,11,.15); color: #f59e0b; }
.studio-info-label { font-size: 11px; color: rgba(255,255,255,.5); }
.studio-info-value { font-size: 13px; font-weight: 600; color: #fff; }
.studio-ia-active { display: flex; align-items: center; gap: 10px; padding: 12px; background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.25); border-radius: 8px; margin-top: auto; }
.studio-ia-pulse { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; animation: pulseRec 2s infinite; }
</style>
@endpush

@section('content')
<div class="studio-wrap">

  {{-- ══ TOPBAR ══ --}}
  <div class="studio-topbar">
    <div class="studio-top-left">
      <div class="studio-rec-status">
        <span class="studio-rec-dot"></span>
        <div>
          <div style="display:flex;align-items:center;gap:12px">
            <span class="studio-rec-text">GRABANDO</span>
            <span class="studio-timer" id="recTimer">00:00:00</span>
          </div>
          <div class="studio-study-name">Estudio: Endoscópico Digestiva Alta</div>
        </div>
      </div>
    </div>

    <div class="studio-topbar-sep"></div>

    <div class="studio-top-center">
      <div class="studio-storage">
        <svg class="studio-storage-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/></svg>
        <div>
          <div class="studio-storage-text">Almacenamiento</div>
          <div style="display:flex;align-items:center;gap:8px">
            <span style="font-size:12px;color:#fff">3.2 GB /50 GB</span>
          </div>
          <div class="studio-storage-bar"><div class="studio-storage-fill"></div></div>
        </div>
      </div>

      <button class="studio-ia-btn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        Asistente IA
      </button>

      <div class="studio-notif">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span class="studio-notif-badge">3</span>
      </div>

      <div class="studio-doctor">
        <div class="studio-doc-avatar">DV</div>
        <div class="studio-doc-info">
          <div class="studio-doc-name">Dr. Víctor</div>
          <div class="studio-doc-role">Endoscopista</div>
        </div>
      </div>
    </div>

    <div class="studio-topbar-sep"></div>

    <div class="studio-top-right">
      <button class="studio-btn-emergency">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4M12 17h.01"/></svg>
        Emergencia
      </button>
      <a class="studio-btn-volver" href="{{ route('nuevo-estudio') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver
      </a>
    </div>
  </div>

  {{-- ══ MAIN CONTENT ══ --}}
  <div class="studio-main">

    {{-- COLUMNA CENTRAL --}}
    <div class="studio-center">

      {{-- Video --}}
      <div class="studio-video-box">
        <div class="studio-video-screen" id="videoScreen">
          {{-- Aquí va el video real --}}
        </div>
        <div class="studio-hud">
          <span class="studio-hud-dot"></span>Rec<br>192 x 1080<br>60 FPS<br>Audio ON
        </div>
        <button class="studio-expand-btn" id="btnFullscreen" title="Pantalla completa">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
        </button>
      </div>

      {{-- Timeline --}}
      <div class="studio-timeline">
        <div class="studio-tl-header">
          <span class="studio-tl-title">Línea de Tiempo</span>
        </div>
        <div class="studio-tl-scroll" id="recTimeline">
          @for($i = 1; $i <= 8; $i++)
          <div class="studio-thumb {{ $i === 1 ? 'active' : '' }}">
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#1e3a5f,#0d2137);display:flex;align-items:center;justify-content:center">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg>
            </div>
            <span class="studio-thumb-time">{{ sprintf('%02d:%02d', 0, ($i-1)*8) }}</span>
          </div>
          @endfor
        </div>
      </div>

      {{-- Bottom Bar --}}
      <div class="studio-bottom">
        <div class="studio-actions">
          <button class="studio-action-btn" id="btnCapturar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            Capturar Foto
          </button>
          <button class="studio-action-btn" id="btnClip">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
            Marcar Clip
          </button>
          <button class="studio-action-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            Agregar Nota
          </button>
          <button class="studio-action-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
            IA Analizar
          </button>
        </div>

        <div style="display:flex;align-items:center">
          <div class="studio-divider"></div>
          <button class="studio-pause-btn" id="btnPausa">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            Pausar
          </button>
          <a class="studio-terminar-btn" href="{{ route('nuevo-estudio') }}" style="margin-left:12px">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8" transform="rotate(45 12 12)"/></svg>
            Terminar Estudio
          </a>
        </div>
      </div>

    </div>

    {{-- SIDEBAR DERECHO --}}
    <div class="studio-sidebar">
      <div class="studio-sidebar-title">Estudio Activo</div>

      <div class="studio-stats">
        <div class="studio-stat-card">
          <div class="studio-stat-header">
            <div class="studio-stat-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="studio-stat-label">Duración</div>
          </div>
          <div class="studio-stat-value red" id="recTimerSide">00:00:00</div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
          <div class="studio-stat-card">
            <div class="studio-stat-header">
              <div class="studio-stat-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              </div>
              <div class="studio-stat-label">Fotos Capturadas</div>
            </div>
            <div class="studio-stat-value" id="recFotos">12</div>
          </div>
          <div class="studio-stat-card">
            <div class="studio-stat-header">
              <div class="studio-stat-icon" style="background:rgba(245,158,11,.15);color:#f59e0b">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
              </div>
              <div class="studio-stat-label">Clips Guardados</div>
            </div>
            <div class="studio-stat-value" id="recClips">3</div>
          </div>
        </div>
      </div>

      <div class="studio-info-row">
        <div class="studio-info-icon blue">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div>
          <div class="studio-info-label">Paciente</div>
          <div class="studio-info-value">Maria Gonzalez</div>
        </div>
      </div>

      <div class="studio-info-row">
        <div class="studio-info-icon blue">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div>
          <div class="studio-info-label">Procedimiento</div>
          <div class="studio-info-value">Endoscopia Digestiva</div>
        </div>
      </div>

      <div class="studio-info-row">
        <div class="studio-info-icon orange">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div>
          <div class="studio-info-label">Médico</div>
          <div class="studio-info-value">Dr. Víctor</div>
        </div>
      </div>

      <div class="studio-ia-active">
        <span class="studio-ia-pulse"></span>
        <div>
          <div style="font-size:13px;font-weight:600;color:#22c55e">IA en monitoreo</div>
          <div style="font-size:11px;color:rgba(255,255,255,.5)">Analizando en tiempo real</div>
        </div>
        <svg style="margin-left:auto" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      </div>

    </div>

  </div>{{-- /.studio-main --}}
</div>{{-- /.studio-wrap --}}

{{-- ═══════ INTERFAZ ESTUDIO FINALIZADO ═══════ --}}
<div class="studio-finalizado-wrap" id="studioFinalizado">

  {{-- Header --}}
  <div class="studio-finalizado-header">
    <div style="display:flex;align-items:center;gap:30px">
      {{-- Status --}}
      <div class="studio-finalizado-status">
        <div class="studio-status-icon">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
          <div class="studio-status-text">Estudio Finalizado</div>
          <div class="studio-status-sub">La grabación ha finalizado correctamente</div>
        </div>
      </div>

      {{-- Info estudio --}}
      <div style="display:flex;align-items:center;gap:20px;font-size:13px;color:rgba(255,255,255,.7)">
        <div style="display:flex;align-items:center;gap:6px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Paciente: Maria Gonzalez</div>
        <div style="display:flex;align-items:center;gap:6px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Fecha: 24/05/2026</div>
        <div style="display:flex;align-items:center;gap:6px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Hora: 10:45 AM</div>
      </div>

      {{-- Separador vertical --}}
      <div style="width:1px;height:50px;background:rgba(255,255,255,.15)"></div>

      {{-- Duración y Almacenamiento --}}
      <div style="display:flex;align-items:center;gap:20px">
        {{-- Duración Total --}}
        <div>
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span style="font-size:12px;color:rgba(255,255,255,.6)">Duración Total</span>
          </div>
          <div style="font-size:14px;font-weight:600;color:#fff;font-family:'Sora',monospace">00:18:47</div>
          <div style="width:120px;height:4px;background:rgba(255,255,255,.1);border-radius:2px;margin-top:6px;overflow:hidden">
            <div style="width:35%;height:100%;background:#22c55e;border-radius:2px"></div>
          </div>
        </div>

        {{-- Almacenamiento --}}
        <div>
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
            <span style="font-size:12px;color:rgba(255,255,255,.6)">Almacenamiento</span>
          </div>
          <div style="font-size:14px;font-weight:600;color:#fff;font-family:'Sora',monospace">4.1 GB / 50 GB</div>
          <div style="width:120px;height:4px;background:rgba(255,255,255,.1);border-radius:2px;margin-top:6px;overflow:hidden">
            <div style="width:8%;height:100%;background:#0ea5e9;border-radius:2px"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- Asistente IA, Notificaciones y Perfil --}}
    <div style="display:flex;align-items:center;gap:16px;margin-left:auto;margin-right:16px">
      {{-- Asistente IA --}}
      <button style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:rgba(14,165,233,.15);border:1px solid rgba(14,165,233,.3);border-radius:8px;color:#38bdf8;font-size:13px;font-weight:500;cursor:pointer;transition:all 150ms">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/><path d="M8.5 8.5A4 4 0 0 0 12 12a4 4 0 0 1 3.5 3.5"/><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
        Asistente IA
      </button>

      {{-- Notificaciones --}}
      <button style="position:relative;padding:10px;background:transparent;border:none;border-radius:8px;color:rgba(255,255,255,.7);cursor:pointer;transition:all 150ms">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span style="position:absolute;top:4px;right:4px;width:16px;height:16px;background:#dc2626;border-radius:50%;font-size:10px;font-weight:600;color:#fff;display:flex;align-items:center;justify-content:center">3</span>
      </button>

      {{-- Perfil Doctor --}}
      <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.1);border-radius:10px">
        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#0ea5e9,#1e3a5f);display:grid;place-items:center;font-size:14px;font-weight:600;color:#fff">DV</div>
        <div>
          <div style="font-size:13px;font-weight:600;color:#fff">Dr. Víctor</div>
          <div style="font-size:11px;color:rgba(255,255,255,.5)">Endoscopista</div>
        </div>
      </div>
    </div>

    {{-- Top right buttons --}}
    <div style="display:flex;align-items:center;gap:12px">
      <a class="studio-btn-volver" href="{{ route('nuevo-estudio') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver
      </a>
    </div>
  </div>

  {{-- Main content --}}
  <div class="studio-finalizado-main">

    {{-- Columna izquierda --}}
    <div style="display:flex;flex-direction:column;gap:16px;overflow:hidden">

      {{-- Video Player --}}
      <div class="studio-video-player" style="flex:1">
        <div class="studio-video-display">
          {{-- Video placeholder --}}
        </div>
        <div class="studio-video-controls">
          <button class="studio-control-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </button>
          <button class="studio-control-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 2 21 22 21 22 3"/><path d="M10 10l4 4"/><path d="M10 14l4-4"/></svg>
          </button>
          <span class="studio-time-display">00:45 / 02:35</span>
          <div class="studio-progress-bar">
            <div class="studio-progress-fill"></div>
            <div class="studio-progress-thumb"></div>
          </div>
          <button class="studio-control-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 5 6 3 18 3 18 19 6 19 6 17 11 17 11 5"/><polygon points="6 21 18 21 18 23 6 23 6 21"/></svg>
          </button>
          <button class="studio-control-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
          </button>
          <button class="studio-control-btn" id="btnExpandirFinal">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          </button>
        </div>
      </div>

      {{-- Timeline --}}
      <div class="studio-timeline-final">
        <div class="studio-timeline-title">Línea de Tiempo</div>
        <div class="studio-timeline-track">
          @for($i = 1; $i <= 8; $i++)
          <div class="studio-timeline-thumb {{ $i === 1 ? 'active' : '' }}">
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#1e3a5f,#0d2137);display:flex;align-items:center;justify-content:center">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg>
            </div>
            <div class="studio-timeline-dot"></div>
          </div>
          @endfor
        </div>
      </div>

      {{-- Info Grid --}}
      <div class="studio-info-grid">
        <div class="studio-info-card">
          <div class="studio-info-card-title">Información del estudio</div>
          <div class="studio-info-card-text">Descripción<br>Evaluación por dolor y reflujo</div>
        </div>
        <div class="studio-info-card">
          <div class="studio-info-card-title">Dispositivos Utilizados</div>
          <div class="studio-info-card-text">Endoscopio Olympus GIF-HQ190<br>Procesador EVIS EXERA</div>
        </div>
        <div class="studio-info-card">
          <div class="studio-info-card-title">Notas</div>
          <div class="studio-info-card-text">Sin complicaciones</div>
        </div>
      </div>

    </div>

    {{-- Sidebar derecho --}}
    <div class="studio-resumen-sidebar">

      {{-- Resumen --}}
      <div class="studio-resumen-card">
        <div class="studio-resumen-title">Resumen del estudio</div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Estado
          </div>
          <div class="studio-resumen-value green">Completado</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            Duración
          </div>
          <div class="studio-resumen-value">00:18:47</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
            Fecha
          </div>
          <div class="studio-resumen-value">26/05/2026</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Médico
          </div>
          <div class="studio-resumen-value">Dr. Víctor</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
            Fotos Capturadas
          </div>
          <div class="studio-resumen-value">30</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg></div>
            Clips Guardados
          </div>
          <div class="studio-resumen-value">4</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></div>
            IA en Monitoreo
          </div>
          <div class="studio-resumen-value green">Activo</div>
        </div>
      </div>

      {{-- Acciones --}}
      <div class="studio-acciones-card">
        <div class="studio-acciones-title">Estudio</div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            Finalizar Informe
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            Editar Imágenes
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
            Editar Video
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Exportar Imágenes (PDF)
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Imprimir Imágenes
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22 6 12 13 2 6"/></svg>
            Exportar Imágenes/Video
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>
      </div>

    </div>

  </div>
</div>{{-- /.studio-finalizado-wrap --}}

{{-- ═══════ INTERFAZ EMERGENCIA ═══════ --}}
<div class="studio-emergencia-wrap" id="studioEmergencia">

  {{-- Header --}}
  <div class="studio-finalizado-header">
    <div style="display:flex;align-items:center;gap:30px">
      {{-- Status Emergencia --}}
      <div class="studio-finalizado-status studio-emergencia-status">
        <div class="studio-status-icon">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div>
          <div class="studio-status-text">Estudio de Emergencia</div>
          <div class="studio-status-sub">Alerta médica activada - Requiere atención inmediata</div>
        </div>
      </div>

      {{-- Info estudio --}}
      <div style="display:flex;align-items:center;gap:20px;font-size:13px;color:rgba(255,255,255,.7)">
        <div style="display:flex;align-items:center;gap:6px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Paciente: Maria Gonzalez</div>
        <div style="display:flex;align-items:center;gap:6px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Fecha: 24/05/2026</div>
        <div style="display:flex;align-items:center;gap:6px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Hora: 10:45 AM</div>
      </div>

      {{-- Separador vertical --}}
      <div style="width:1px;height:50px;background:rgba(255,255,255,.15)"></div>

      {{-- Duración y Almacenamiento --}}
      <div style="display:flex;align-items:center;gap:20px">
        {{-- Duración Total --}}
        <div>
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span style="font-size:12px;color:rgba(255,255,255,.6)">Duración Total</span>
          </div>
          <div style="font-size:14px;font-weight:600;color:#fff;font-family:'Sora',monospace">00:18:47</div>
          <div style="width:120px;height:4px;background:rgba(255,255,255,.1);border-radius:2px;margin-top:6px;overflow:hidden">
            <div style="width:35%;height:100%;background:#dc2626;border-radius:2px"></div>
          </div>
        </div>

        {{-- Almacenamiento --}}
        <div>
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
            <span style="font-size:12px;color:rgba(255,255,255,.6)">Almacenamiento</span>
          </div>
          <div style="font-size:14px;font-weight:600;color:#fff;font-family:'Sora',monospace">4.1 GB / 50 GB</div>
          <div style="width:120px;height:4px;background:rgba(255,255,255,.1);border-radius:2px;margin-top:6px;overflow:hidden">
            <div style="width:8%;height:100%;background:#0ea5e9;border-radius:2px"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- Asistente IA, Notificaciones y Perfil --}}
    <div style="display:flex;align-items:center;gap:16px;margin-left:auto;margin-right:16px">
      {{-- Asistente IA --}}
      <button style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:rgba(14,165,233,.15);border:1px solid rgba(14,165,233,.3);border-radius:8px;color:#38bdf8;font-size:13px;font-weight:500;cursor:pointer;transition:all 150ms">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/><path d="M8.5 8.5A4 4 0 0 0 12 12a4 4 0 0 1 3.5 3.5"/><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
        Asistente IA
      </button>

      {{-- Notificaciones --}}
      <button style="position:relative;padding:10px;background:transparent;border:none;border-radius:8px;color:rgba(255,255,255,.7);cursor:pointer;transition:all 150ms">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span style="position:absolute;top:4px;right:4px;width:16px;height:16px;background:#dc2626;border-radius:50%;font-size:10px;font-weight:600;color:#fff;display:flex;align-items:center;justify-content:center">3</span>
      </button>

      {{-- Perfil Doctor --}}
      <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.1);border-radius:10px">
        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#0ea5e9,#1e3a5f);display:grid;place-items:center;font-size:14px;font-weight:600;color:#fff">DV</div>
        <div>
          <div style="font-size:13px;font-weight:600;color:#fff">Dr. Víctor</div>
          <div style="font-size:11px;color:rgba(255,255,255,.5)">Endoscopista</div>
        </div>
      </div>
    </div>

    {{-- Top right button --}}
    <div style="display:flex;align-items:center;gap:12px">
      <a class="studio-btn-volver" href="{{ route('nuevo-estudio') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver
      </a>
    </div>
  </div>

  {{-- Main content --}}
  <div class="studio-finalizado-main">

    {{-- Columna izquierda --}}
    <div style="display:flex;flex-direction:column;gap:16px;overflow:hidden">

      {{-- Video Player --}}
      <div class="studio-video-player" style="flex:1">
        <div class="studio-video-display">
          {{-- Video placeholder --}}
        </div>
        <div class="studio-video-controls">
          <button class="studio-control-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </button>
          <button class="studio-control-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 2 21 22 21 22 3"/><path d="M10 10l4 4"/><path d="M10 14l4-4"/></svg>
          </button>
          <span class="studio-time-display">00:45 / 02:35</span>
          <div class="studio-progress-bar">
            <div class="studio-progress-fill" style="background:#dc2626"></div>
            <div class="studio-progress-thumb" style="background:#dc2626"></div>
          </div>
          <button class="studio-control-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 5 6 3 18 3 18 19 6 19 6 17 11 17 11 5"/><polygon points="6 21 18 21 18 23 6 23 6 21"/></svg>
          </button>
          <button class="studio-control-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
          </button>
          <button class="studio-control-btn" id="btnExpandirEmergencia">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          </button>
        </div>
      </div>

      {{-- Timeline --}}
      <div class="studio-timeline-final">
        <div class="studio-timeline-title">Línea de Tiempo</div>
        <div class="studio-timeline-track">
          @for($i = 1; $i <= 8; $i++)
          <div class="studio-timeline-thumb {{ $i === 1 ? 'active' : '' }}">
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#1e3a5f,#0d2137);display:flex;align-items:center;justify-content:center">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg>
            </div>
            <div class="studio-timeline-dot" style="background:#dc2626"></div>
          </div>
          @endfor
        </div>
      </div>

      {{-- Info Grid --}}
      <div class="studio-info-grid">
        <div class="studio-info-card">
          <div class="studio-info-card-title">Información del estudio</div>
          <div class="studio-info-card-text">Descripción<br>Evaluación por dolor y reflujo</div>
        </div>
        <div class="studio-info-card">
          <div class="studio-info-card-title">Dispositivos Utilizados</div>
          <div class="studio-info-card-text">Endoscopio Olympus GIF-HQ190<br>Procesador EVIS EXERA</div>
        </div>
        <div class="studio-info-card">
          <div class="studio-info-card-title">Notas</div>
          <div class="studio-info-card-text">Sin complicaciones</div>
        </div>
      </div>

    </div>

    {{-- Sidebar derecho --}}
    <div class="studio-resumen-sidebar">

      {{-- Resumen --}}
      <div class="studio-resumen-card">
        <div class="studio-resumen-title">Resumen del estudio</div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Estado
          </div>
          <div class="studio-resumen-value" style="color:#dc2626">Emergencia</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon" style="background:rgba(220,38,38,.15);color:#dc2626"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
            Paciente de Emergencia
          </div>
          <div class="studio-resumen-value">Si</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            Duración
          </div>
          <div class="studio-resumen-value">00:18:47</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
            Fecha
          </div>
          <div class="studio-resumen-value">26/05/2026</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Médico
          </div>
          <div class="studio-resumen-value">Dr. Víctor</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
            Fotos Capturadas
          </div>
          <div class="studio-resumen-value">30</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg></div>
            Clips Guardados
          </div>
          <div class="studio-resumen-value">4</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></div>
            IA en Monitoreo
          </div>
          <div class="studio-resumen-value green">Activo</div>
        </div>
      </div>

      {{-- Acciones --}}
      <div class="studio-acciones-card">
        <div class="studio-acciones-title">Estudio</div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            Finalizar Informe
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            Editar Imágenes
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
            Editar Video
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Exportar Imágenes (PDF)
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Imprimir Imágenes
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <div class="studio-accion-item">
          <div class="studio-accion-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22 6 12 13 2 6"/></svg>
            Exportar Imágenes/Video
          </div>
          <svg class="studio-accion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </div>
      </div>

    </div>

  </div>
</div>{{-- /.studio-emergencia-wrap --}}
@endsection

@push('scripts')
<script>
(function () {
  let secs = 0, paused = false, fotos = 12, clips = 3;

  function pad(n) { return String(n).padStart(2,'0'); }
  function fmt(s) { return pad(Math.floor(s/3600))+':'+pad(Math.floor((s%3600)/60))+':'+pad(s%60); }

  const timerEl = document.getElementById('recTimer');
  const sideEl  = document.getElementById('recTimerSide');
  const fotosEl = document.getElementById('recFotos');
  const clipsEl = document.getElementById('recClips');
  const tl      = document.getElementById('recTimeline');

  const iv = setInterval(() => {
    if (!paused) { secs++; const t = fmt(secs); timerEl && (timerEl.textContent = t); sideEl && (sideEl.textContent = t); }
  }, 1000);

  /* Thumb genérico */
  function addThumb() {
    if (!tl) return;
    const el = document.createElement('div');
    el.className = 'studio-thumb';
    const ts = fmt(secs);
    el.innerHTML = `<div style="width:100%;height:100%;background:linear-gradient(135deg,#1e3a5f,#0d2137);display:flex;align-items:center;justify-content:center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg></div><span class="studio-thumb-time">${ts}</span>`;
    el.addEventListener('click', () => { document.querySelectorAll('.studio-thumb').forEach(t => t.classList.remove('active')); el.classList.add('active'); });
    tl.appendChild(el);
    tl.scrollLeft = tl.scrollWidth;
  }

  /* ── Botón Modo Expandido ── */
  const btnFullscreen = document.getElementById('btnFullscreen');

  function toggleExpanded() {
    document.body.classList.toggle('studio-expanded');
  }

  // Click en botón para modo expandido
  btnFullscreen?.addEventListener('click', toggleExpanded);

  /* Pausa */
  document.getElementById('btnPausa')?.addEventListener('click', function () {
    paused = !paused;
    const svgPause = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg> Pausar';
    const svgPlay = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor" stroke="none"/></svg> Continuar';
    this.innerHTML = paused ? svgPlay : svgPause;
  });

  /* Captura y Clip */
  document.getElementById('btnCapturar')?.addEventListener('click', () => { fotos++; fotosEl && (fotosEl.textContent = fotos); addThumb(); });
  document.getElementById('btnClip')?.addEventListener('click', () => { clips++; clipsEl && (clipsEl.textContent = clips); addThumb(); });

  /* ── Terminar Estudio ── */
  const btnTerminar = document.querySelector('.studio-terminar-btn');
  const wrapPrincipal = document.querySelector('.studio-wrap');
  const wrapFinalizado = document.getElementById('studioFinalizado');

  btnTerminar?.addEventListener('click', (e) => {
    e.preventDefault();
    // Ocultar interfaz de grabación y mostrar interfaz finalizada
    wrapPrincipal.style.display = 'none';
    wrapFinalizado.classList.add('active');
    // Detener timer
    clearInterval(iv);
  });

  /* ── Botón Emergencia ── */
  const btnEmergencia = document.querySelector('.studio-btn-emergency');
  const wrapEmergencia = document.getElementById('studioEmergencia');

  btnEmergencia?.addEventListener('click', () => {
    // Ocultar interfaz de grabación y mostrar interfaz de emergencia
    wrapPrincipal.style.display = 'none';
    wrapEmergencia.classList.add('active');
    // Detener timer
    clearInterval(iv);
  });

  /* ── Controles del Video Player (Interfaz Finalizada) ── */
  const videoPlayer = document.querySelector('.studio-video-player');
  const btnPlay = videoPlayer?.querySelector('.studio-control-btn:nth-child(1)');
  const btnRewind = videoPlayer?.querySelector('.studio-control-btn:nth-child(2)');
  const btnVolume = videoPlayer?.querySelector('.studio-control-btn:nth-child(6)');
  const btnSettings = videoPlayer?.querySelector('.studio-control-btn:nth-child(7)');
  const btnExpandirFinal = document.getElementById('btnExpandirFinal');
  const progressBar = videoPlayer?.querySelector('.studio-progress-bar');
  const progressFill = videoPlayer?.querySelector('.studio-progress-fill');
  const progressThumb = videoPlayer?.querySelector('.studio-progress-thumb');
  const timeDisplay = videoPlayer?.querySelector('.studio-time-display');

  let isPlaying = false;
  let currentTime = 45; // segundos
  let duration = 155; // 2:35 = 155 segundos
  let isMuted = false;

  function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
  }

  function updateProgress() {
    const percent = (currentTime / duration) * 100;
    if (progressFill) progressFill.style.width = percent + '%';
    if (progressThumb) progressThumb.style.left = percent + '%';
    if (timeDisplay) timeDisplay.textContent = formatTime(currentTime) + ' / ' + formatTime(duration);
  }

  // Play/Pause
  btnPlay?.addEventListener('click', () => {
    isPlaying = !isPlaying;
    btnPlay.innerHTML = isPlaying
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>'
      : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>';
  });

  // Rewind 10 segundos
  btnRewind?.addEventListener('click', () => {
    currentTime = Math.max(0, currentTime - 10);
    updateProgress();
  });

  // Progress bar click
  progressBar?.addEventListener('click', (e) => {
    const rect = progressBar.getBoundingClientRect();
    const percent = (e.clientX - rect.left) / rect.width;
    currentTime = percent * duration;
    updateProgress();
  });

  // Volume toggle
  btnVolume?.addEventListener('click', () => {
    isMuted = !isMuted;
    btnVolume.innerHTML = isMuted
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 5 6 3 18 3 18 19 6 19 6 17 11 17 11 5"/><line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/></svg>'
      : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 5 6 3 18 3 18 19 6 19 6 17 11 17 11 5"/><polygon points="6 21 18 21 18 23 6 23 6 21"/></svg>';
  });

  // Pantalla completa
  btnExpandirFinal?.addEventListener('click', () => {
    if (!document.fullscreenElement) {
      videoPlayer?.requestFullscreen().catch(err => console.log(err));
    } else {
      document.exitFullscreen();
    }
  });

  // Simular progreso del video
  let videoInterval;
  btnPlay?.addEventListener('click', () => {
    if (isPlaying) {
      videoInterval = setInterval(() => {
        if (currentTime < duration) {
          currentTime++;
          updateProgress();
        } else {
          isPlaying = false;
          btnPlay.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>';
          clearInterval(videoInterval);
        }
      }, 1000);
    } else {
      clearInterval(videoInterval);
    }
  });

  // Drag del progress thumb
  let isDragging = false;
  progressThumb?.addEventListener('mousedown', () => isDragging = true);
  document.addEventListener('mouseup', () => isDragging = false);
  document.addEventListener('mousemove', (e) => {
    if (isDragging && progressBar) {
      const rect = progressBar.getBoundingClientRect();
      const percent = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
      currentTime = percent * duration;
      updateProgress();
    }
  });

  /* ── Controles del Video Player (Interfaz Emergencia) ── */
  const videoPlayerEmergencia = document.querySelector('.studio-emergencia-wrap .studio-video-player');
  const btnPlayEm = videoPlayerEmergencia?.querySelector('.studio-control-btn:nth-child(1)');
  const btnRewindEm = videoPlayerEmergencia?.querySelector('.studio-control-btn:nth-child(2)');
  const btnVolumeEm = videoPlayerEmergencia?.querySelector('.studio-control-btn:nth-child(6)');
  const btnSettingsEm = videoPlayerEmergencia?.querySelector('.studio-control-btn:nth-child(7)');
  const btnExpandirEm = document.getElementById('btnExpandirEmergencia');
  const progressBarEm = videoPlayerEmergencia?.querySelector('.studio-progress-bar');
  const progressFillEm = videoPlayerEmergencia?.querySelector('.studio-progress-fill');
  const progressThumbEm = videoPlayerEmergencia?.querySelector('.studio-progress-thumb');
  const timeDisplayEm = videoPlayerEmergencia?.querySelector('.studio-time-display');

  let isPlayingEm = false;
  let currentTimeEm = 45;
  let durationEm = 155;
  let isMutedEm = false;

  function updateProgressEm() {
    const percent = (currentTimeEm / durationEm) * 100;
    if (progressFillEm) progressFillEm.style.width = percent + '%';
    if (progressThumbEm) progressThumbEm.style.left = percent + '%';
    if (timeDisplayEm) timeDisplayEm.textContent = formatTime(currentTimeEm) + ' / ' + formatTime(durationEm);
  }

  // Play/Pause Emergencia
  btnPlayEm?.addEventListener('click', () => {
    isPlayingEm = !isPlayingEm;
    btnPlayEm.innerHTML = isPlayingEm
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>'
      : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>';

    if (isPlayingEm) {
      videoInterval = setInterval(() => {
        if (currentTimeEm < durationEm) {
          currentTimeEm++;
          updateProgressEm();
        } else {
          isPlayingEm = false;
          btnPlayEm.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>';
          clearInterval(videoInterval);
        }
      }, 1000);
    } else {
      clearInterval(videoInterval);
    }
  });

  // Rewind 10 segundos Emergencia
  btnRewindEm?.addEventListener('click', () => {
    currentTimeEm = Math.max(0, currentTimeEm - 10);
    updateProgressEm();
  });

  // Progress bar click Emergencia
  progressBarEm?.addEventListener('click', (e) => {
    const rect = progressBarEm.getBoundingClientRect();
    const percent = (e.clientX - rect.left) / rect.width;
    currentTimeEm = percent * durationEm;
    updateProgressEm();
  });

  // Volume toggle Emergencia
  btnVolumeEm?.addEventListener('click', () => {
    isMutedEm = !isMutedEm;
    btnVolumeEm.innerHTML = isMutedEm
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 5 6 3 18 3 18 19 6 19 6 17 11 17 11 5"/><line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/></svg>'
      : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 5 6 3 18 3 18 19 6 19 6 17 11 17 11 5"/><polygon points="6 21 18 21 18 23 6 23 6 21"/></svg>';
  });

  // Pantalla completa Emergencia
  btnExpandirEm?.addEventListener('click', () => {
    if (!document.fullscreenElement) {
      videoPlayerEmergencia?.requestFullscreen().catch(err => console.log(err));
    } else {
      document.exitFullscreen();
    }
  });

  // Drag del progress thumb Emergencia
  let isDraggingEm = false;
  progressThumbEm?.addEventListener('mousedown', () => isDraggingEm = true);
  document.addEventListener('mouseup', () => isDraggingEm = false);
  document.addEventListener('mousemove', (e) => {
    if (isDraggingEm && progressBarEm) {
      const rect = progressBarEm.getBoundingClientRect();
      const percent = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
      currentTimeEm = percent * durationEm;
      updateProgressEm();
    }
  });

  window.addEventListener('beforeunload', () => clearInterval(iv));
})();
</script>
@endpush
