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
  gap: 0;
  flex: 1;
  justify-content: center;
}

/* Separador visual entre grupos */
.studio-topbar-gap {
  width: 32px;
  flex: none;
}

/* Grupo de elementos (Notif + Doctor) */
.studio-top-group {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 6px 16px;
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 10px;
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
.studio-storage-used { font-size: 12px; color: #fff; }
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
.studio-theme-btn {
  position: relative;
  width: 36px; height: 36px;
  display: grid; place-items: center;
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 8px;
  cursor: pointer;
  color: rgba(255,255,255,.7);
  transition: all 150ms;
}
.studio-theme-btn:hover { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.2); color: #fff; }
.studio-theme-btn .icon-moon { display: none; }
.studio-theme-btn .icon-sun { display: block; }
html[data-theme="light"] .studio-theme-btn .icon-sun { display: none; }
html[data-theme="light"] .studio-theme-btn .icon-moon { display: block; }
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
  gap: 24px;
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

/* Contenedor de botones en sidebar */
.studio-sidebar-actions {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: auto;
  padding-top: 20px;
  border-top: 1px solid rgba(255,255,255,.08);
}

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
.studio-thumb-inner { width: 100%; height: 100%; background: linear-gradient(135deg, #1e3a5f, #0d2137); display: flex; align-items: center; justify-content: center; }
.studio-thumb-inner svg { stroke: rgba(255,255,255,.3); }
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
/* Controles de Grabación */
.studio-rec-controls {
  display: flex;
  align-items: center;
  gap: 12px;
}

/* Separador vertical */
.studio-divider-v {
  width: 1px;
  height: 36px;
  background: rgba(14, 165, 233, .3);
  margin: 0 8px;
}

/* Botón Pausar */
.studio-pause-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 22px;
  background: rgba(10, 15, 30, .6);
  border: 1px solid rgba(14, 165, 233, .3);
  border-radius: 10px;
  color: rgba(255, 255, 255, .9);
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 150ms;
}
.studio-pause-btn svg { color: #0ea5e9; }
.studio-pause-btn:hover { background: rgba(14, 165, 233, .2); border-color: rgba(14, 165, 233, .5); }
.studio-pause-btn.paused { background: rgba(34, 197, 94, .15); border-color: rgba(34, 197, 94, .5); color: #4ade80; }
.studio-pause-btn.paused svg { color: #4ade80; }

/* Botón Terminar Estudio - Rojo */
.studio-terminar-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 24px;
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

/* Botón Capturar Foto */
.studio-captura-btn {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 20px; border-radius: 10px;
  background: rgba(10, 15, 30, .6);
  border: 1px solid rgba(14, 165, 233, .5);
  color: #e0f2fe; font-size: 14px; font-weight: 600;
  cursor: pointer; transition: all 150ms;
}
.studio-captura-btn svg { color: #0ea5e9; flex: none; }
.studio-captura-btn:hover { background: rgba(14, 165, 233, .15); border-color: #0ea5e9; }

/* Botón Detener */
.studio-detener-btn {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 20px; border-radius: 10px;
  background: rgba(220, 38, 38, .15);
  border: 1px solid rgba(220, 38, 38, .5);
  color: #fecaca; font-size: 14px; font-weight: 600;
  cursor: pointer; transition: all 150ms;
}
.studio-detener-btn svg { color: #dc2626; flex: none; }
.studio-detener-btn:hover { background: rgba(220, 38, 38, .25); border-color: #dc2626; }

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
  gap: 24px;
}

/* Sección izquierda del header */
.studio-final-left {
  display: flex;
  align-items: center;
  gap: 20px;
  flex: 1;
}

/* Separador vertical */
.studio-final-sep {
  width: 1px;
  height: 40px;
  background: rgba(255,255,255,.12);
  flex: none;
}

/* Info del paciente */
.studio-final-info {
  display: flex;
  align-items: center;
  gap: 16px;
}
.studio-info-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: rgba(255,255,255,.7);
}
.studio-info-item strong {
  color: #fff;
  font-weight: 600;
}

/* Métricas */
.studio-final-metrics {
  display: flex;
  align-items: center;
  gap: 20px;
}
.studio-metric {
  display: flex;
  align-items: center;
  gap: 10px;
}
.studio-metric svg {
  color: #0ea5e9;
}
.studio-metric-content {
  display: flex;
  flex-direction: column;
}
.studio-metric-label {
  font-size: 11px;
  color: rgba(255,255,255,.5);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.studio-metric-value {
  font-size: 15px;
  font-weight: 700;
  font-family: 'Sora', sans-serif;
  color: #fff;
}
.studio-metric-value.green { color: #4ade80; }
.studio-metric-value.blue { color: #38bdf8; }

/* Sección derecha del header */
.studio-final-right {
  display: flex;
  align-items: center;
  gap: 12px;
  flex: none;
}


/* Botón Notificaciones */
.studio-final-btn-notif {
  position: relative;
  padding: 10px;
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 8px;
  color: rgba(255,255,255,.7);
  cursor: pointer;
  transition: all 150ms;
  display: flex;
  align-items: center;
  justify-content: center;
}
.studio-final-btn-notif:hover {
  background: rgba(255,255,255,.1);
  border-color: rgba(255,255,255,.2);
}
.studio-final-btn-notif .studio-notif-badge {
  background: #dc2626;
  top: 4px;
  right: 4px;
}

/* Perfil en header */
.studio-final-profile {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 6px 12px;
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 10px;
}

/* Separador vertical derecho */
.studio-final-sep-v {
  width: 1px;
  height: 36px;
  background: rgba(255,255,255,.12);
  margin: 0 4px;
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
.studio-resumen-icon.danger { background: rgba(220,38,38,.15); color: #dc2626; }
.studio-resumen-value {
  font-size: 13px;
  font-weight: 600;
  color: #fff;
}
.studio-resumen-value.danger { color: #dc2626; }
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
.studio-stat-icon.orange { background: rgba(245,158,11,.15); color: #f59e0b; }
.studio-stat-label { font-size: 12px; color: rgba(255,255,255,.6); }
.studio-stat-value { font-size: 22px; font-weight: 700; color: #fff; font-family: 'Sora', sans-serif; }
.studio-stat-value.red { color: #ef4444; }
.studio-icon-paciente {
  position: relative;
  cursor: pointer;
}
.studio-icon-paciente::after {
  content: attr(data-paciente);
  position: absolute;
  left: 50%;
  bottom: calc(100% + 8px);
  transform: translateX(-50%) scale(0.9);
  background: #1e3a5f;
  border: 1px solid rgba(14,165,233,.35);
  color: #38bdf8;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
  padding: 5px 10px;
  border-radius: 6px;
  pointer-events: none;
  opacity: 0;
  transition: opacity 150ms ease, transform 150ms ease;
  z-index: 10;
}
.studio-icon-paciente::before {
  content: '';
  position: absolute;
  left: 50%;
  bottom: calc(100% + 2px);
  transform: translateX(-50%);
  border: 5px solid transparent;
  border-top-color: rgba(14,165,233,.35);
  pointer-events: none;
  opacity: 0;
  transition: opacity 150ms ease;
  z-index: 10;
}
.studio-icon-paciente:hover::after,
.studio-icon-paciente:hover::before {
  opacity: 1;
  transform: translateX(-50%) scale(1);
}
.studio-info-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,.06); }
.studio-info-icon { width: 32px; height: 32px; border-radius: 8px; display: grid; place-items: center; }
.studio-info-icon.blue { background: rgba(14,165,233,.15); color: #0ea5e9; }
.studio-info-icon.orange { background: rgba(245,158,11,.15); color: #f59e0b; }
.studio-info-label { font-size: 11px; color: rgba(255,255,255,.5); }
.studio-info-value { font-size: 13px; font-weight: 600; color: #fff; }

/* Acciones tipo galeria en interfaz finalizada */
.studio-final-actions {
  display: flex; align-items: center; gap: 7px; flex-wrap: wrap;
  padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,.08); margin-bottom: 12px;
}
.studio-final-act-btn {
  display: flex; align-items: center; gap: 5px;
  height: 34px; padding: 0 12px; border-radius: 10px;
  font: inherit; font-size: 12px; font-weight: 600;
  background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.12); color: rgba(255,255,255,.85);
  cursor: pointer; transition: all 150ms ease; white-space: nowrap;
}
.studio-final-act-btn:hover { background: rgba(14,165,233,.15); border-color: rgba(14,165,233,.45); color: #38bdf8; }
.studio-final-act-btn.wa { color: #4ade80; border-color: rgba(74,222,128,.25); background: rgba(74,222,128,.07); }
.studio-final-act-btn.wa:hover { background: rgba(74,222,128,.15); border-color: rgba(74,222,128,.45); }
.studio-final-act-btn.ia { color: #22d3ee; border-color: rgba(34,211,238,.25); background: rgba(34,211,238,.07); }
.studio-final-act-btn.ia:hover { background: rgba(34,211,238,.15); border-color: rgba(34,211,238,.45); }
.studio-final-act-btn.fin { color: #a78bfa; border-color: rgba(167,139,250,.25); background: rgba(167,139,250,.07); }
.studio-final-act-btn.fin:hover { background: rgba(167,139,250,.15); border-color: rgba(167,139,250,.45); }

/* Miniaturas de imagenes capturadas */
.studio-final-caps-title { font-size: 13px; font-weight: 600; color: rgba(255,255,255,.85); margin-bottom: 8px; }
.studio-final-caps-strip {
  display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px;
  scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.15) transparent;
}
.studio-final-cap-item {
  flex: none; width: 90px; cursor: pointer; border-radius: 7px; overflow: hidden;
  border: 2px solid transparent; transition: border-color 150ms ease;
}
.studio-final-cap-item.sel { border-color: #0ea5e9; }
.studio-final-cap-thumb {
  width: 100%; aspect-ratio: 4/3; display: grid; place-items: center; position: relative;
  background: radial-gradient(ellipse at 50% 50%, #3a1208 0%, #0a0610 100%);
}
html[data-theme="light"] .studio-final-cap-thumb { background: var(--panel); }
.studio-final-cap-thumb svg { stroke: rgba(255,255,255,.3); }
html[data-theme="light"] .studio-final-cap-thumb svg { stroke: var(--txt-soft); }
.studio-final-cap-num {
  position: absolute; top: 3px; left: 4px; width: 17px; height: 17px; border-radius: 5px;
  background: rgba(0,0,0,.6); display: grid; place-items: center;
  font-size: 9px; font-weight: 700; color: #fff;
}
.studio-final-cap-check {
  position: absolute; top: 3px; right: 3px; width: 17px; height: 17px; border-radius: 50%;
  background: #0ea5e9; display: none; place-items: center;
}
.studio-final-cap-item.sel .studio-final-cap-check { display: grid; }
.studio-final-cap-ts { font-size: 9.5px; color: rgba(255,255,255,.5); text-align: center; padding: 3px 0 1px; }

/* Estilos de emergencia para acciones */
.studio-emergencia-wrap .studio-final-act-btn:hover { background: rgba(220,38,38,.15); border-color: rgba(220,38,38,.45); color: #f87171; }
.studio-emergencia-wrap .studio-final-act-btn.wa:hover { background: rgba(74,222,128,.15); border-color: rgba(74,222,128,.45); color: #4ade80; }
.studio-emergencia-wrap .studio-final-act-btn.ia:hover { background: rgba(34,211,238,.15); border-color: rgba(34,211,238,.45); color: #22d3ee; }
.studio-emergencia-wrap .studio-final-act-btn.fin:hover { background: rgba(167,139,250,.15); border-color: rgba(167,139,250,.45); color: #a78bfa; }
.studio-emergencia-wrap .studio-finalizado-status .studio-status-text { color: #dc2626; }

/* Reproductor tipo galeria (ev-player) */
.sf-video-player {
  background: #000; border-radius: 14px; overflow: hidden;
  position: relative; aspect-ratio: 16/9;
  display: flex; flex-direction: column;
}
.sf-video-bg {
  position: absolute; inset: 0;
  background: radial-gradient(ellipse at 50% 50%, #5a1a10 0%, #2a0808 40%, #060810 100%);
}
.sf-video-center {
  position: absolute; inset: 0; z-index: 2;
  display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px;
}
.sf-play-big {
  width: 52px; height: 52px; border-radius: 50%;
  background: rgba(255,255,255,.18); backdrop-filter: blur(8px);
  display: grid; place-items: center; cursor: pointer;
  transition: background-color 150ms ease, transform 150ms ease;
  border: none; color: #fff;
}
.sf-play-big:hover { background: rgba(46,123,246,.6); transform: scale(1.08); }

.sf-video-controls {
  position: absolute; bottom: 0; left: 0; right: 0; z-index: 3;
  padding: 28px 14px 12px;
  background: linear-gradient(0deg, rgba(0,0,0,.82) 0%, transparent 100%);
}
.sf-prog-wrap { position: relative; height: 4px; background: rgba(255,255,255,.2); border-radius: 4px; cursor: pointer; margin-bottom: 9px; }
.sf-prog-fill { height: 100%; background: var(--blue, #2e7bf6); border-radius: 4px; width: 15%; }
.sf-prog-thumb {
  position: absolute; top: 50%; translate: 0 -50%;
  width: 11px; height: 11px; border-radius: 50%; background: #fff;
  left: 15%; margin-left: -5px;
}
.sf-ctrl-row { display: flex; align-items: center; gap: 6px; }
.sf-ctrl-btn {
  width: 30px; height: 30px; border-radius: 7px; display: grid; place-items: center;
  color: rgba(255,255,255,.8); flex: none; transition: background-color 150ms ease;
  background: transparent; border: none; cursor: pointer;
}
.sf-ctrl-btn:hover { background: rgba(255,255,255,.12); }
.sf-time { font-size: 11.5px; color: rgba(255,255,255,.6); flex: none; margin: 0 3px; }
.sf-vol-wrap { display: flex; align-items: center; gap: 5px; margin-left: auto; }
.sf-vol-bar { width: 60px; height: 4px; background: rgba(255,255,255,.2); border-radius: 4px; cursor: pointer; }
.sf-vol-fill { height: 100%; background: rgba(255,255,255,.7); border-radius: 4px; width: 70%; }
.sf-speed { font-size: 11.5px; font-weight: 700; color: rgba(255,255,255,.8); padding: 2px 7px; border-radius: 6px; border: 1px solid rgba(255,255,255,.2); cursor: pointer; background: transparent; }
.sf-fs { margin-left: 4px; }

.studio-emergencia-wrap .sf-prog-fill { background: #dc2626; }
.studio-emergencia-wrap .sf-play-big:hover { background: rgba(220,38,38,.6); }

/* ================= TEMA CLARO (overrides completos) ================= */
html[data-theme="light"] .main { background: var(--bg) !important; }
html[data-theme="light"] .studio-wrap,
html[data-theme="light"] .side { background: var(--panel); color: var(--txt); border-color: var(--stroke); }
html[data-theme="light"] .studio-topbar { background: var(--panel); border-color: var(--stroke); }
html[data-theme="light"] .studio-topbar-sep { background: var(--stroke-strong); }
html[data-theme="light"] .studio-timer { color: var(--txt); }
html[data-theme="light"] .studio-study-name { color: var(--txt-soft); }
html[data-theme="light"] .studio-storage { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-storage-icon { color: var(--blue); }
html[data-theme="light"] .studio-storage-text,
html[data-theme="light"] .studio-storage-used { color: var(--txt-soft); }
html[data-theme="light"] .studio-storage-bar { background: var(--stroke-strong); }
html[data-theme="light"] .studio-ia-btn { background: rgba(46,123,246,.1); border-color: rgba(46,123,246,.3); color: var(--blue); }
html[data-theme="light"] .studio-theme-btn,
html[data-theme="light"] .studio-notif { background: var(--panel-2); border-color: var(--stroke); color: var(--txt-soft); }
html[data-theme="light"] .studio-theme-btn:hover,
html[data-theme="light"] .studio-notif:hover { background: var(--hover-bg); color: var(--txt); }
html[data-theme="light"] .studio-notif-badge { background: var(--blue); color: #fff; }
html[data-theme="light"] .studio-doc-avatar { background: var(--panel-2); border-color: var(--blue); color: var(--txt); }
html[data-theme="light"] .studio-doc-name { color: var(--txt); }
html[data-theme="light"] .studio-doc-role { color: var(--txt-soft); }
html[data-theme="light"] .studio-btn-emergency { background: rgba(239,68,68,.12); border-color: rgba(239,68,68,.35); color: #dc2626; }
html[data-theme="light"] .studio-sidebar-actions { border-color: var(--stroke); }
html[data-theme="light"] .studio-btn-volver { background: rgba(46,123,246,.12); border-color: rgba(46,123,246,.35); color: var(--blue); }
html[data-theme="light"] .studio-btn-volver:hover { background: rgba(46,123,246,.2); border-color: rgba(46,123,246,.55); }
html[data-theme="light"] .studio-main { background: var(--bg); }
html[data-theme="light"] .studio-video-box { background: var(--panel); border-color: var(--blue); }
html[data-theme="light"] .studio-video-screen { background: linear-gradient(135deg, var(--panel-2) 0%, var(--panel) 100%); }
html[data-theme="light"] .studio-hud { background: rgba(255,255,255,.85); border-color: var(--stroke); color: var(--txt); }
html[data-theme="light"] .studio-expand-btn { background: rgba(255,255,255,.8); border-color: var(--stroke); color: var(--txt-soft); }
html[data-theme="light"] .studio-expand-btn:hover { background: var(--hover-bg); color: var(--blue); border-color: var(--blue); }
html[data-theme="light"] .studio-timeline { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-tl-title { color: var(--txt-soft); }
html[data-theme="light"] .studio-thumb,
html[data-theme="light"] .studio-thumb-inner { background: var(--panel); }
html[data-theme="light"] .studio-thumb svg { stroke: var(--txt-soft); }
html[data-theme="light"] .studio-thumb.active { border-color: var(--blue); }
html[data-theme="light"] .studio-thumb-time { color: var(--txt); background: rgba(0,0,0,.55); }
html[data-theme="light"] .studio-bottom { background: var(--panel); border-color: var(--stroke); }
html[data-theme="light"] .studio-action-btn { background: var(--panel-2); color: var(--txt); }
html[data-theme="light"] .studio-action-btn:hover { background: var(--hover-bg); }
html[data-theme="light"] .studio-action-btn svg { color: var(--blue); }
html[data-theme="light"] .studio-divider-v { background: var(--stroke-strong); }
html[data-theme="light"] .studio-pause-btn { background: var(--panel-2); border-color: var(--stroke); color: var(--txt); }
html[data-theme="light"] .studio-pause-btn:hover { background: var(--hover-bg); }
html[data-theme="light"] .studio-pause-btn.paused { background: rgba(34,197,94,.12); border-color: rgba(34,197,94,.4); color: #16a34a; }
html[data-theme="light"] .studio-terminar-btn { background: #dc2626; color: #fff; }
html[data-theme="light"] .studio-captura-btn { background: var(--panel-2); border-color: var(--blue); color: var(--blue); }
html[data-theme="light"] .studio-captura-btn:hover { background: var(--hover-bg); }
html[data-theme="light"] .studio-detener-btn { background: rgba(220,38,38,.12); border-color: rgba(220,38,38,.4); color: #dc2626; }
html[data-theme="light"] .studio-detener-btn:hover { background: rgba(220,38,38,.18); }

/* Sidebar derecho */
html[data-theme="light"] .studio-sidebar-title { color: var(--txt); border-color: var(--stroke); }
html[data-theme="light"] .studio-stat-card { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-stat-icon { background: rgba(46,123,246,.12); color: var(--blue); }
html[data-theme="light"] .studio-stat-icon.orange { background: rgba(245,158,11,.12); color: #d97706; }
html[data-theme="light"] .studio-stat-label { color: var(--txt-soft); }
html[data-theme="light"] .studio-stat-value { color: var(--txt); }
html[data-theme="light"] .studio-stat-value.red { color: #ef4444; }
html[data-theme="light"] .studio-stat-patient-hover { color: var(--txt-soft); }
html[data-theme="light"] .studio-info-icon { background: var(--panel); }
html[data-theme="light"] .studio-info-icon.blue { background: rgba(46,123,246,.12); color: var(--blue); }
html[data-theme="light"] .studio-info-icon.orange { background: rgba(245,158,11,.12); color: #d97706; }
html[data-theme="light"] .studio-info-label { color: var(--txt-soft); }
html[data-theme="light"] .studio-info-value { color: var(--txt); }

/* Interfaz finalizada / emergencia */
html[data-theme="light"] .studio-finalizado-wrap,
html[data-theme="light"] .studio-emergencia-wrap { background: var(--bg); color: var(--txt); }
html[data-theme="light"] .studio-finalizado-header { background: var(--panel); border-color: var(--stroke); }
html[data-theme="light"] .studio-final-sep,
html[data-theme="light"] .studio-final-sep-v { background: var(--stroke-strong); }
html[data-theme="light"] .studio-info-item { color: var(--txt-soft); }
html[data-theme="light"] .studio-info-item strong { color: var(--txt); }
html[data-theme="light"] .studio-metric svg { color: var(--blue); }
html[data-theme="light"] .studio-metric-label { color: var(--txt-soft); }
html[data-theme="light"] .studio-metric-value { color: var(--txt); }
html[data-theme="light"] .studio-metric-value.green { color: #16a34a; }
html[data-theme="light"] .studio-metric-value.blue { color: var(--blue); }
html[data-theme="light"] .studio-final-btn-notif { background: var(--panel-2); border-color: var(--stroke); color: var(--txt-soft); }
html[data-theme="light"] .studio-final-btn-notif:hover { background: var(--hover-bg); }
html[data-theme="light"] .studio-final-profile { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-status-icon { color: #fff; }
html[data-theme="light"] .studio-status-text { color: var(--txt); }
html[data-theme="light"] .studio-status-sub { color: var(--txt-soft); }
html[data-theme="light"] .studio-emergencia-status .studio-status-text { color: #dc2626; }
html[data-theme="light"] .studio-finalizado-main { background: var(--bg); }

/* Video player finalizado */
html[data-theme="light"] .studio-video-player { background: var(--panel); border-color: var(--stroke); }
html[data-theme="light"] .studio-video-display { background: linear-gradient(135deg, var(--panel-2) 0%, var(--panel) 100%); }
html[data-theme="light"] .studio-video-controls { background: rgba(255,255,255,.8); border-color: var(--stroke); }
html[data-theme="light"] .studio-control-btn { color: var(--txt); }
html[data-theme="light"] .studio-control-btn:hover { background: var(--hover-bg); }
html[data-theme="light"] .studio-progress-bar { background: var(--stroke-strong); }
html[data-theme="light"] .studio-progress-fill,
html[data-theme="light"] .studio-progress-thumb { background: var(--blue); }
html[data-theme="light"] .studio-time-display { color: var(--txt-soft); }

/* Timeline finalizado */
html[data-theme="light"] .studio-timeline-final { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-timeline-title { color: var(--txt-soft); }
html[data-theme="light"] .studio-timeline-thumb { background: var(--panel); }
html[data-theme="light"] .studio-timeline-thumb.active { border-color: var(--blue); }
html[data-theme="light"] .studio-timeline-dot { background: var(--blue); }
html[data-theme="light"] .studio-info-grid { background: transparent; }
html[data-theme="light"] .studio-info-card { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-info-card-title { color: var(--txt-soft); }
html[data-theme="light"] .studio-info-card-text { color: var(--txt-soft); }

/* Resumen sidebar */
html[data-theme="light"] .studio-resumen-card { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-resumen-title { color: var(--txt); border-color: var(--stroke); }
html[data-theme="light"] .studio-resumen-item { border-color: var(--stroke); }
html[data-theme="light"] .studio-resumen-label { color: var(--txt-soft); }
html[data-theme="light"] .studio-resumen-icon { background: rgba(46,123,246,.12); color: var(--blue); }
html[data-theme="light"] .studio-resumen-icon.danger { background: rgba(220,38,38,.12); color: #dc2626; }
html[data-theme="light"] .studio-resumen-value { color: var(--txt); }
html[data-theme="light"] .studio-resumen-value.danger { color: #dc2626; }
html[data-theme="light"] .studio-resumen-value.green { color: #16a34a; }
html[data-theme="light"] .studio-acciones-card { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-acciones-title { color: var(--txt-soft); }
html[data-theme="light"] .studio-accion-item { border-color: var(--stroke); }
html[data-theme="light"] .studio-accion-item:hover { background: var(--hover-bg); }
html[data-theme="light"] .studio-accion-label { color: var(--txt); }
html[data-theme="light"] .studio-accion-arrow { color: var(--txt-soft); }
html[data-theme="light"] .studio-icon-paciente::after { background: var(--panel); border-color: var(--stroke); color: var(--txt); }
html[data-theme="light"] .studio-icon-paciente::before { border-top-color: var(--stroke); }

/* Acciones tipo galeria */
html[data-theme="light"] .studio-final-actions { border-color: var(--stroke); }
html[data-theme="light"] .studio-final-act-btn { background: var(--panel-2); border-color: var(--stroke); color: var(--txt); }
html[data-theme="light"] .studio-final-act-btn:hover { background: var(--hover-bg); border-color: var(--blue); color: var(--blue); }
html[data-theme="light"] .studio-final-act-btn.wa { color: #16a34a; border-color: rgba(34,197,94,.25); background: rgba(34,197,94,.07); }
html[data-theme="light"] .studio-final-act-btn.wa:hover { background: rgba(34,197,94,.12); border-color: rgba(34,197,94,.45); color: #16a34a; }
html[data-theme="light"] .studio-final-act-btn.ia { color: var(--blue); border-color: rgba(46,123,246,.25); background: rgba(46,123,246,.07); }
html[data-theme="light"] .studio-final-act-btn.ia:hover { background: rgba(46,123,246,.12); border-color: rgba(46,123,246,.45); color: var(--blue); }
html[data-theme="light"] .studio-final-act-btn.fin { color: #7c3aed; border-color: rgba(124,58,237,.25); background: rgba(124,58,237,.07); }
html[data-theme="light"] .studio-final-act-btn.fin:hover { background: rgba(124,58,237,.12); border-color: rgba(124,58,237,.45); color: #7c3aed; }
html[data-theme="light"] .studio-emergencia-wrap .studio-final-act-btn:hover { background: rgba(220,38,38,.08); border-color: rgba(220,38,38,.35); color: #dc2626; }
html[data-theme="light"] .studio-final-caps-title { color: var(--txt); }
html[data-theme="light"] .studio-final-caps-strip { scrollbar-color: var(--stroke) transparent; }
html[data-theme="light"] .studio-final-cap-item { border-color: transparent; }
html[data-theme="light"] .studio-final-cap-item.sel { border-color: var(--blue); }
html[data-theme="light"] .studio-final-cap-thumb { background: var(--panel); }
html[data-theme="light"] .studio-final-cap-thumb svg { stroke: var(--txt-soft); }
html[data-theme="light"] .studio-final-cap-num { background: rgba(0,0,0,.55); color: #fff; }
html[data-theme="light"] .studio-final-cap-check { background: var(--blue); color: #fff; }
html[data-theme="light"] .studio-final-cap-ts { color: var(--txt-soft); }

/* Reproductor galeria */
html[data-theme="light"] .sf-video-player { background: var(--panel); }
html[data-theme="light"] .sf-video-bg { background: radial-gradient(ellipse at 50% 50%, rgba(46,123,246,.08) 0%, transparent 70%); }
html[data-theme="light"] .sf-video-center { color: var(--txt); }
html[data-theme="light"] .sf-play-big { background: rgba(255,255,255,.55); color: var(--txt); }
html[data-theme="light"] .sf-play-big:hover { background: var(--hover-bg-strong); }
html[data-theme="light"] .sf-video-controls { background: linear-gradient(0deg, rgba(255,255,255,.82) 0%, transparent 100%); }
html[data-theme="light"] .sf-prog-wrap { background: var(--stroke-strong); }
html[data-theme="light"] .sf-prog-fill { background: var(--blue); }
html[data-theme="light"] .sf-prog-thumb { background: var(--blue); }
html[data-theme="light"] .sf-ctrl-row { color: var(--txt); }
html[data-theme="light"] .sf-ctrl-btn { color: var(--txt-soft); }
html[data-theme="light"] .sf-ctrl-btn:hover { background: var(--hover-bg); color: var(--txt); }
html[data-theme="light"] .sf-time { color: var(--txt-soft); }
html[data-theme="light"] .sf-vol-bar { background: var(--stroke-strong); }
html[data-theme="light"] .sf-vol-fill { background: var(--blue); }
html[data-theme="light"] .sf-speed { color: var(--txt-soft); border-color: var(--stroke); }
html[data-theme="light"] .studio-emergencia-wrap .sf-prog-fill { background: #dc2626; }
html[data-theme="light"] .studio-emergencia-wrap .sf-play-big:hover { background: rgba(220,38,38,.15); }
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
      {{-- Centro vacío o con info adicional si se necesita --}}
    </div>

    <div class="studio-topbar-sep"></div>

    <div class="studio-top-right">
      {{-- Grupo 1: Almacenamiento --}}
      <div class="studio-storage">
        <svg class="studio-storage-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/></svg>
        <div>
          <div class="studio-storage-text">Almacenamiento</div>
          <div style="display:flex;align-items:center;gap:8px">
            <span class="studio-storage-used">3.2 GB /50 GB</span>
          </div>
          <div class="studio-storage-bar"><div class="studio-storage-fill"></div></div>
        </div>
      </div>

      <div class="studio-topbar-gap"></div>

      {{-- Grupo 3: Notificaciones + Doctor --}}
      <div class="studio-top-group">
        <button class="studio-theme-btn" id="studioThemeToggle" aria-label="Cambiar tema">
          <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg>
          <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
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
            <div class="studio-thumb-inner">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg>
            </div>
            <span class="studio-thumb-time">{{ sprintf('%02d:%02d', 0, ($i-1)*8) }}</span>
          </div>
          @endfor
        </div>
      </div>

      {{-- Bottom Bar --}}
      <div class="studio-bottom">
        {{-- Controles de Grabación --}}
        <div class="studio-rec-controls">
          <button class="studio-captura-btn" id="btnCapturarFoto">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            Capturar Foto
          </button>
          <button class="studio-detener-btn" id="btnDetener">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="6" width="12" height="12" rx="2"/></svg>
            Detener
          </button>
          <button class="studio-pause-btn" id="btnPausa">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            Pausar
          </button>
          <div class="studio-divider-v"></div>
          <a class="studio-terminar-btn" href="{{ route('nuevo-estudio') }}">
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
        <div class="studio-stat-card studio-stat-card-hover" title="Paciente: Maria Gonzalez">
          <div class="studio-stat-header">
            <div class="studio-stat-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="studio-stat-label">Duración</div>
          </div>
          <div class="studio-stat-value red" id="recTimerSide">00:00:00</div>
          <div class="studio-stat-patient-hover">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Maria Gonzalez
          </div>
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
              <div class="studio-stat-icon orange">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
              </div>
              <div class="studio-stat-label">Clips Guardados</div>
            </div>
            <div class="studio-stat-value" id="recClips">3</div>
          </div>
        </div>
      </div>


      {{-- Botones de acción en sidebar --}}
      <div class="studio-sidebar-actions">
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

  </div>{{-- /.studio-main --}}
</div>{{-- /.studio-wrap --}}

{{-- ═══════ INTERFAZ ESTUDIO TERMINADO (tipo galeria) ═══════ --}}
<div class="studio-finalizado-wrap" id="studioFinalizado">

  {{-- Header --}}
  <div class="studio-finalizado-header">
    <div class="studio-finalizado-status">
      <div class="studio-status-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div>
        <div class="studio-status-text">Estudio terminado</div>
        <div class="studio-status-sub">La grabación ha finalizado correctamente</div>
      </div>
    </div>

    <div class="studio-final-right">
      <button class="studio-theme-btn" type="button" aria-label="Cambiar tema">
        <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg>
        <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
      <button class="studio-final-btn-notif">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span class="studio-notif-badge">3</span>
      </button>
      <div class="studio-final-profile">
        <div class="studio-doc-avatar">DV</div>
        <div class="studio-doc-info">
          <div class="studio-doc-name">Dr. Víctor</div>
          <div class="studio-doc-role">Endoscopista</div>
        </div>
      </div>
      <div class="studio-final-sep-v"></div>
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
      <div class="sf-video-player" style="flex:1">
        <div class="sf-video-bg"></div>
        <div class="sf-video-center">
          <button class="sf-play-big" id="sfPlayBigFinal">
            <svg class="play-icon" width="20" height="20" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <svg class="pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="white" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
          </button>
        </div>
        <div class="sf-video-controls">
          <div class="sf-prog-wrap">
            <div class="sf-prog-fill"></div>
            <div class="sf-prog-thumb"></div>
          </div>
          <div class="sf-ctrl-row">
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polygon points="19 20 9 12 19 4 19 20"/><line x1="5" y1="19" x2="5" y2="5"/></svg></button>
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg></button>
            <button class="sf-ctrl-btn" id="sfPlayBtnFinal">
              <svg class="play-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              <svg class="pause-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            </button>
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-4.95"/></svg></button>
            <span class="sf-time" id="sfTimeFinal">00:02:15 / 00:15:42</span>
            <div class="sf-vol-wrap">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
              <div class="sf-vol-bar"><div class="sf-vol-fill"></div></div>
            </div>
            <button class="sf-speed" id="sfSpeedFinal">1.0x</button>
            <button class="sf-ctrl-btn sf-fs" id="btnExpandirFinal"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg></button>
          </div>
        </div>
      </div>

      {{-- Acciones tipo galeria --}}
      <div class="studio-final-actions">
        <button class="studio-final-act-btn btn-simular-captura"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>Capturar imagen</button>
        <a class="studio-final-act-btn wa" href="{{ route('mensajes') }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>Enviar mensaje</a>
        <a class="studio-final-act-btn ia" href="{{ route('ia-reportes.generar') }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>Iniciar reporte con IA</a>
        <a class="studio-final-act-btn fin" href="{{ route('ia-reportes.redactar') }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>Finalizar estudio</a>
      </div>

      {{-- Miniaturas capturadas --}}
      <div>
        <div class="studio-final-caps-title">Imágenes capturadas del estudio</div>
        <div class="studio-final-caps-strip">
          @php
          $caps=[['n'=>1,'ts'=>'0:01:25'],['n'=>2,'ts'=>'0:02:15'],['n'=>3,'ts'=>'0:04:32'],['n'=>4,'ts'=>'0:06:18'],['n'=>5,'ts'=>'0:08:47'],['n'=>6,'ts'=>'0:11:03']];
          @endphp
          @foreach($caps as $i => $c)
          <div class="studio-final-cap-item {{ $i===1 ? 'sel' : '' }}" data-ts="{{ $c['ts'] }}">
            <div class="studio-final-cap-thumb">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <span class="studio-final-cap-num">{{ $c['n'] }}</span>
              <span class="studio-final-cap-check"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            </div>
            <div class="studio-final-cap-ts">{{ $c['ts'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

    </div>

    {{-- Sidebar derecho --}}
    <div class="studio-resumen-sidebar">

      {{-- Resumen del estudio --}}
      <div class="studio-resumen-card">
        <div class="studio-resumen-title">Resumen del estudio</div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon studio-icon-paciente" data-paciente="María Gonzalez">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
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

    </div>

  </div>
</div>{{-- /.studio-finalizado-wrap --}}

{{-- ═══════ INTERFAZ ESTUDIO DE EMERGENCIA (tipo galeria) ═══════ --}}
<div class="studio-emergencia-wrap" id="studioEmergencia">

  {{-- Header --}}
  <div class="studio-finalizado-header">
    <div class="studio-finalizado-status studio-emergencia-status">
      <div class="studio-status-icon">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      </div>
      <div>
        <div class="studio-status-text">Estudio de emergencia</div>
        <div class="studio-status-sub">Alerta médica activada - Requiere atención inmediata</div>
      </div>
    </div>

    <div class="studio-final-right">
      <button class="studio-theme-btn" type="button" aria-label="Cambiar tema">
        <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg>
        <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
      <button class="studio-final-btn-notif">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span class="studio-notif-badge">3</span>
      </button>
      <div class="studio-final-profile">
        <div class="studio-doc-avatar">DV</div>
        <div class="studio-doc-info">
          <div class="studio-doc-name">Dr. Víctor</div>
          <div class="studio-doc-role">Endoscopista</div>
        </div>
      </div>
      <div class="studio-final-sep-v"></div>
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
      <div class="sf-video-player" style="flex:1">
        <div class="sf-video-bg"></div>
        <div class="sf-video-center">
          <button class="sf-play-big" id="sfPlayBigEm">
            <svg class="play-icon" width="20" height="20" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <svg class="pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="white" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
          </button>
        </div>
        <div class="sf-video-controls">
          <div class="sf-prog-wrap">
            <div class="sf-prog-fill" style="background:#dc2626"></div>
            <div class="sf-prog-thumb" style="background:#fff"></div>
          </div>
          <div class="sf-ctrl-row">
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polygon points="19 20 9 12 19 4 19 20"/><line x1="5" y1="19" x2="5" y2="5"/></svg></button>
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg></button>
            <button class="sf-ctrl-btn" id="sfPlayBtnEm">
              <svg class="play-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              <svg class="pause-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            </button>
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-4.95"/></svg></button>
            <span class="sf-time" id="sfTimeEm">00:02:15 / 00:15:42</span>
            <div class="sf-vol-wrap">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
              <div class="sf-vol-bar"><div class="sf-vol-fill"></div></div>
            </div>
            <button class="sf-speed" id="sfSpeedEm">1.0x</button>
            <button class="sf-ctrl-btn sf-fs" id="btnExpandirEmergencia"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg></button>
          </div>
        </div>
      </div>

      {{-- Acciones tipo galeria --}}
      <div class="studio-final-actions">
        <button class="studio-final-act-btn btn-simular-captura"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>Capturar imagen</button>
        <a class="studio-final-act-btn wa" href="{{ route('mensajes') }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>Enviar mensaje</a>
        <a class="studio-final-act-btn ia" href="{{ route('ia-reportes.generar') }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>Iniciar reporte con IA</a>
        <a class="studio-final-act-btn fin" href="{{ route('ia-reportes.redactar') }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>Finalizar estudio</a>
      </div>

      {{-- Miniaturas capturadas --}}
      <div>
        <div class="studio-final-caps-title">Imágenes capturadas del estudio</div>
        <div class="studio-final-caps-strip">
          @php
          $capsEm=[['n'=>1,'ts'=>'0:01:25'],['n'=>2,'ts'=>'0:02:15'],['n'=>3,'ts'=>'0:04:32'],['n'=>4,'ts'=>'0:06:18'],['n'=>5,'ts'=>'0:08:47'],['n'=>6,'ts'=>'0:11:03']];
          @endphp
          @foreach($capsEm as $i => $c)
          <div class="studio-final-cap-item {{ $i===1 ? 'sel' : '' }}" data-ts="{{ $c['ts'] }}">
            <div class="studio-final-cap-thumb">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <span class="studio-final-cap-num">{{ $c['n'] }}</span>
              <span class="studio-final-cap-check"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            </div>
            <div class="studio-final-cap-ts">{{ $c['ts'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

    </div>

    {{-- Sidebar derecho --}}
    <div class="studio-resumen-sidebar">

      {{-- Resumen del estudio --}}
      <div class="studio-resumen-card">
        <div class="studio-resumen-title">Resumen del estudio</div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon studio-icon-paciente" data-paciente="María Gonzalez"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Estado
          </div>
          <div class="studio-resumen-value danger">Emergencia</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon danger"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
            Paciente de Emergencia
          </div>
          <div class="studio-resumen-value">Sí</div>
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
    el.innerHTML = `<div class="studio-thumb-inner"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg></div><span class="studio-thumb-time">${ts}</span>`;
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

  /* Pausa/Continuar */
  const btnPausa = document.getElementById('btnPausa');

  function updatePauseButton() {
    if (paused) {
      // Está pausado: mostrar Continuar en verde
      btnPausa.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor" stroke="none"/></svg> Continuar';
      btnPausa.classList.add('paused');
    } else {
      // Está grabando: mostrar Pausar en azul
      btnPausa.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg> Pausar';
      btnPausa.classList.remove('paused');
    }
  }

  // Inicializar estado
  updatePauseButton();

  btnPausa?.addEventListener('click', function () {
    paused = !paused;
    updatePauseButton();
  });

  /* Capturar Foto */
  const btnCapturarFoto = document.getElementById('btnCapturarFoto');
  btnCapturarFoto?.addEventListener('click', () => {
    const videoScreen = document.getElementById('videoScreen');
    if (!videoScreen) return;
    const flash = document.createElement('div');
    flash.style.cssText = 'position:absolute;inset:0;background:#fff;opacity:0.6;z-index:20;pointer-events:none;transition:opacity 300ms ease;';
    videoScreen.style.position = 'relative';
    videoScreen.appendChild(flash);
    requestAnimationFrame(() => { flash.style.opacity = '0'; });
    setTimeout(() => flash.remove(), 350);
  });

  /* Detener grabación */
  const btnDetener = document.getElementById('btnDetener');
  btnDetener?.addEventListener('click', () => {
    clearInterval(iv);
    paused = true;
    updatePauseButton();
    const recText = document.querySelector('.studio-rec-text');
    if (recText) recText.textContent = 'DETENIDO';
    const recDot = document.querySelector('.studio-rec-dot');
    if (recDot) recDot.style.animation = 'none';
  });

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

  /* ── Controles del Video Player (diseño galeria) ── */
  function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
  }

  function setupVideoPlayer(container, playBigId, playBtnId, timeId, speedId, fsId, accent) {
    const player = document.querySelector(container);
    if (!player) return;
    const playBig = document.getElementById(playBigId);
    const playBtn = document.getElementById(playBtnId);
    const timeDisplay = document.getElementById(timeId);
    const speedBtn = document.getElementById(speedId);
    const fsBtn = document.getElementById(fsId);
    const progressBar = player.querySelector('.sf-prog-wrap');
    const progressFill = player.querySelector('.sf-prog-fill');
    const progressThumb = player.querySelector('.sf-prog-thumb');
    const rewindBtn = player.querySelector('.sf-ctrl-row .sf-ctrl-btn:nth-child(2)');
    const volBtn = player.querySelector('.sf-vol-wrap svg');
    const volFill = player.querySelector('.sf-vol-fill');

    let isPlaying = false;
    let currentTime = 135; // 00:02:15
    let duration = 942; // 00:15:42
    let isMuted = false;
    let videoInterval;
    const speeds = ['0.5x','0.75x','1.0x','1.25x','1.5x','2.0x'];
    let sIdx = 2;

    function updateProgress() {
      const percent = (currentTime / duration) * 100;
      if (progressFill) progressFill.style.width = percent + '%';
      if (progressThumb) progressThumb.style.left = percent + '%';
      if (timeDisplay) timeDisplay.textContent = formatTime(currentTime) + ' / ' + formatTime(duration);
    }
    updateProgress();

    function togglePlay() {
      isPlaying = !isPlaying;
      [playBig, playBtn].forEach(btn => {
        if (!btn) return;
        const p = btn.querySelector('.play-icon');
        const q = btn.querySelector('.pause-icon');
        if (p) p.style.display = isPlaying ? 'none' : '';
        if (q) q.style.display = isPlaying ? '' : 'none';
      });
      if (isPlaying) {
        videoInterval = setInterval(() => {
          if (currentTime < duration) {
            currentTime++;
            updateProgress();
          } else {
            isPlaying = false;
            togglePlay();
            clearInterval(videoInterval);
          }
        }, 1000);
      } else {
        clearInterval(videoInterval);
      }
    }

    playBig?.addEventListener('click', togglePlay);
    playBtn?.addEventListener('click', togglePlay);

    rewindBtn?.addEventListener('click', () => {
      currentTime = Math.max(0, currentTime - 10);
      updateProgress();
    });

    progressBar?.addEventListener('click', (e) => {
      const rect = progressBar.getBoundingClientRect();
      const percent = (e.clientX - rect.left) / rect.width;
      currentTime = Math.max(0, Math.min(duration, percent * duration));
      updateProgress();
    });

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

    volBtn?.addEventListener('click', () => {
      isMuted = !isMuted;
      if (volFill) volFill.style.width = isMuted ? '0%' : '70%';
    });

    speedBtn?.addEventListener('click', () => {
      sIdx = (sIdx + 1) % speeds.length;
      speedBtn.textContent = speeds[sIdx];
    });

    fsBtn?.addEventListener('click', () => {
      if (!document.fullscreenElement) {
        player.requestFullscreen().catch(err => console.log(err));
      } else {
        document.exitFullscreen();
      }
    });
  }

  setupVideoPlayer('.studio-finalizado-wrap .sf-video-player', 'sfPlayBigFinal', 'sfPlayBtnFinal', 'sfTimeFinal', 'sfSpeedFinal', 'btnExpandirFinal', '#2e7bf6');
  setupVideoPlayer('.studio-emergencia-wrap .sf-video-player', 'sfPlayBigEm', 'sfPlayBtnEm', 'sfTimeEm', 'sfSpeedEm', 'btnExpandirEmergencia', '#dc2626');

  /* Cambiar tema desde cualquier botón del estudio */
  function updateAllStudioThemeIcons() {
    const isLight = document.documentElement.dataset.theme === 'light';
    document.querySelectorAll('.studio-theme-btn').forEach(btn => {
      const moon = btn.querySelector('.icon-moon');
      const sun = btn.querySelector('.icon-sun');
      if (moon) moon.style.display = isLight ? 'block' : 'none';
      if (sun) sun.style.display = isLight ? 'none' : 'block';
    });
  }
  updateAllStudioThemeIcons();
  document.querySelectorAll('.studio-theme-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const html = document.documentElement;
      const next = html.dataset.theme === 'light' ? 'dark' : 'light';
      html.dataset.theme = next;
      localStorage.setItem('enclaii-theme', next);
      updateAllStudioThemeIcons();
    });
  });

  /* Simular captura de imagen en la interfaz finalizada/emergencia */
  document.querySelectorAll('.btn-simular-captura').forEach(btn => {
    btn.addEventListener('click', () => {
      const player = document.querySelector('.studio-finalizado-wrap.active .sf-video-player, .studio-emergencia-wrap.active .sf-video-player');
      if (!player) return;
      const flash = document.createElement('div');
      flash.style.cssText = 'position:absolute;inset:0;background:#fff;opacity:0.6;z-index:30;pointer-events:none;transition:opacity 300ms ease;';
      player.style.position = 'relative';
      player.appendChild(flash);
      requestAnimationFrame(() => { flash.style.opacity = '0'; });
      setTimeout(() => flash.remove(), 350);
    });
  });

  window.addEventListener('beforeunload', () => clearInterval(iv));
})();
</script>
@endpush
