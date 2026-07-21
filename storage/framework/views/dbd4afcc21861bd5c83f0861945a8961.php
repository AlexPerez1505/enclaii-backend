<style>
/* ═══════════════════════════════════════════════
   DISEÑO SEGÚN REFERENCIA - Ocultar head, mantener sidebar
════════════════════════════════════════════════ */
.head { display: none !important; }
.main { padding: 0 !important; overflow: hidden !important; background: #0a0f1e !important; }
.dash { grid-template-columns: 260px 1fr; }
.dash.sidebar-collapsed { grid-template-columns: 84px 1fr !important; }

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
  height: 0;
  min-height: 0;
}

/* ═════ COLUMNA CENTRAL ═════ */
.studio-center {
  display: flex;
  flex-direction: column;
  gap: 16px;
  overflow: hidden;
  min-height: 0;
  height: 100%;
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

/* Video HUD */
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

/* Modo expandido */
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
  min-height: 0;
  flex: 1;
}
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

/* Bottom Bar */
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

/* Botón Terminar Estudio */
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
  position: fixed;
  inset: 0;
  z-index: 200;
  background: #0a0f1e;
  font-family: 'Hanken Grotesk', sans-serif;
  color: #fff;
  overflow-y: auto;
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

.studio-final-left {
  display: flex;
  align-items: center;
  gap: 20px;
  flex: 1;
}

.studio-final-sep {
  width: 1px;
  height: 40px;
  background: rgba(255,255,255,.12);
  flex: none;
}

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
.studio-metric svg { color: #0ea5e9; }
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

.studio-final-right {
  display: flex;
  align-items: center;
  gap: 12px;
  flex: none;
}

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

.studio-final-profile {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 6px 12px;
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 10px;
}

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
.studio-timeline-thumb.active { border-color: #0ea5e9; }
.studio-timeline-thumb img { width: 100%; height: 100%; object-fit: cover; }
.studio-timeline-dot {
  position: absolute;
  bottom: 4px;
  left: 50%;
  transform: translateX(-50%);
  width: 6px; height: 6px;
  background: #0ea5e9;
  border-radius: 50%;
}

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
.studio-accion-arrow { color: rgba(255,255,255,.4); }

/* Sidebar */
.studio-sidebar { display: flex; flex-direction: column; gap: 16px; }
.studio-sidebar-title { font-size: 16px; font-weight: 700; color: #fff; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,.1); }
.studio-stats { display: flex; flex-direction: column; gap: 8px; }
.studio-stat-card { background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.08); border-radius: 10px; padding: 10px 12px; }
.studio-stat-header { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
.studio-stat-icon { width: 24px; height: 24px; background: rgba(14,165,233,.15); border-radius: 6px; display: grid; place-items: center; color: #0ea5e9; flex-shrink: 0; }
.studio-stat-icon.orange { background: rgba(245,158,11,.15); color: #f59e0b; }
.studio-stat-label { font-size: 11px; color: rgba(255,255,255,.6); }
.studio-stat-value { font-size: 18px; font-weight: 700; color: #fff; font-family: 'Sora', sans-serif; }
.studio-stat-value.red { color: #ef4444; }
.studio-stat-patient-hover { display: flex; align-items: center; gap: 5px; margin-top: 6px; padding-top: 6px; border-top: 1px solid rgba(255,255,255,.07); font-size: 11px; color: rgba(255,255,255,.5); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
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

/* Botón Ver video */
.sf-btn-volver-video {
  display: none; align-items: center; gap: 6px;
  position: absolute; top: 10px; right: 10px; z-index: 10;
  background: rgba(0,0,0,.65); backdrop-filter: blur(6px);
  border: 1px solid rgba(255,255,255,.2); border-radius: 8px;
  padding: 6px 10px; color: #fff; font: inherit; font-size: 11.5px;
  font-weight: 600; cursor: pointer; transition: background 150ms ease;
}
.sf-btn-volver-video:hover { background: rgba(14,165,233,.55); border-color: rgba(14,165,233,.6); }
html[data-theme="light"] .sf-btn-volver-video { background: rgba(255,255,255,.85); color: var(--txt); border-color: var(--stroke); }
html[data-theme="light"] .sf-btn-volver-video:hover { background: var(--blue); color: #fff; border-color: var(--blue); }

/* Dropdown compartir */
.sf-share-dropdown {
  display: none; flex-direction: column;
  position: absolute; bottom: calc(100% + 6px); left: 0;
  min-width: 190px;
  background: #131929; border: 1px solid rgba(255,255,255,.12);
  border-radius: 10px; padding: 5px;
  box-shadow: 0 8px 24px rgba(0,0,0,.45);
  z-index: 100;
}
.sf-share-dropdown.open { display: flex; }
.sf-share-item {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 10px; border-radius: 7px;
  font: inherit; font-size: 12.5px; font-weight: 500;
  color: rgba(255,255,255,.8); background: none; border: none;
  cursor: pointer; text-decoration: none; white-space: nowrap;
  transition: background 130ms ease;
}
.sf-share-item:hover { background: rgba(255,255,255,.07); color: #fff; }
html[data-theme="light"] .sf-share-dropdown { background: #fff; border-color: var(--stroke); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
html[data-theme="light"] .sf-share-item { color: var(--txt); }
html[data-theme="light"] .sf-share-item:hover { background: var(--hover-bg); }

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

/* Estilos de emergencia */
.studio-emergencia-wrap .studio-final-act-btn:hover { background: rgba(220,38,38,.15); border-color: rgba(220,38,38,.45); color: #f87171; }
.studio-emergencia-wrap .studio-final-act-btn.wa:hover { background: rgba(74,222,128,.15); border-color: rgba(74,222,128,.45); color: #4ade80; }
.studio-emergencia-wrap .studio-final-act-btn.ia:hover { background: rgba(34,211,238,.15); border-color: rgba(34,211,238,.45); color: #22d3ee; }
.studio-emergencia-wrap .studio-final-act-btn.fin:hover { background: rgba(167,139,250,.15); border-color: rgba(167,139,250,.45); color: #a78bfa; }
.studio-emergencia-wrap .studio-finalizado-status .studio-status-text { color: #dc2626; }

/* Reproductor tipo galeria */
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

/* ================= TEMA CLARO ================= */
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
html[data-theme="light"] .studio-sidebar-title { color: var(--txt); border-color: var(--stroke); }
html[data-theme="light"] .studio-stat-card { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-stat-icon { background: rgba(46,123,246,.12); color: var(--blue); }
html[data-theme="light"] .studio-stat-icon.orange { background: rgba(245,158,11,.12); color: #d97706; }
html[data-theme="light"] .studio-stat-label { color: var(--txt-soft); }
html[data-theme="light"] .studio-stat-value { color: var(--txt); }
html[data-theme="light"] .studio-stat-value.red { color: #ef4444; }
html[data-theme="light"] .studio-stat-patient-hover { color: var(--txt-soft); border-top-color: var(--stroke); }
html[data-theme="light"] .studio-info-icon { background: var(--panel); }
html[data-theme="light"] .studio-info-icon.blue { background: rgba(46,123,246,.12); color: var(--blue); }
html[data-theme="light"] .studio-info-icon.orange { background: rgba(245,158,11,.12); color: #d97706; }
html[data-theme="light"] .studio-info-label { color: var(--txt-soft); }
html[data-theme="light"] .studio-info-value { color: var(--txt); }
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
html[data-theme="light"] .studio-video-player { background: var(--panel); border-color: var(--stroke); }
html[data-theme="light"] .studio-video-display { background: linear-gradient(135deg, var(--panel-2) 0%, var(--panel) 100%); }
html[data-theme="light"] .studio-video-controls { background: rgba(255,255,255,.8); border-color: var(--stroke); }
html[data-theme="light"] .studio-control-btn { color: var(--txt); }
html[data-theme="light"] .studio-control-btn:hover { background: var(--hover-bg); }
html[data-theme="light"] .studio-progress-bar { background: var(--stroke-strong); }
html[data-theme="light"] .studio-progress-fill,
html[data-theme="light"] .studio-progress-thumb { background: var(--blue); }
html[data-theme="light"] .studio-time-display { color: var(--txt-soft); }
html[data-theme="light"] .studio-timeline-final { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-timeline-title { color: var(--txt-soft); }
html[data-theme="light"] .studio-timeline-thumb { background: var(--panel); }
html[data-theme="light"] .studio-timeline-thumb.active { border-color: var(--blue); }
html[data-theme="light"] .studio-timeline-dot { background: var(--blue); }
html[data-theme="light"] .studio-info-grid { background: transparent; }
html[data-theme="light"] .studio-info-card { background: var(--panel-2); border-color: var(--stroke); }
html[data-theme="light"] .studio-info-card-title { color: var(--txt-soft); }
html[data-theme="light"] .studio-info-card-text { color: var(--txt-soft); }
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
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\estudios\grabando\grabando-css.blade.php ENDPATH**/ ?>