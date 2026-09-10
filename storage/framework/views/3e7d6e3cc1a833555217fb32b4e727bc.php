


<style>
.day-view{display:none}
.day-view.active{display:flex;flex-direction:column;gap:0}
.day-view-body{display:grid;grid-template-columns:1fr 260px;align-items:start;gap:14px;margin-top:14px}
.agenda-left.expanded .day-view-body{grid-template-columns:1fr}
.agenda-left.expanded .day-panel{display:none}
.day-left{min-width:0;overflow:visible}
.day-nav-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.day-nav-btn{padding:7px 18px;border-radius:8px;font-size:12.5px;font-weight:700;background:#001525;border:1px solid rgba(22,139,217,.4);color:#EAF1FF;cursor:pointer;transition:background 150ms ease}
.day-nav-btn:hover{background:#002540}
.day-nav-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:700;color:#EAF1FF;text-align:center;flex:1;padding:0 8px;position:relative;overflow:visible;z-index:10}
.day-title-btn{display:inline-flex;align-items:center;gap:8px;padding:7px 20px;border-radius:10px;border:1.5px solid rgba(22,139,217,.5);background:linear-gradient(to bottom,#001525 30%,#004F8B 100%);color:#EAF1FF;font-family:'Sora',sans-serif;font-size:clamp(11px,3.5vw,15px);font-weight:700;cursor:pointer;transition:opacity 150ms ease;white-space:nowrap;min-width:0;max-width:100%}
.day-title-btn:hover{opacity:.85}
.day-title-btn svg{flex:none;opacity:.7}
.day-title-btn .ico-cal{display:inline-block}
@media(max-width:600px){.day-title-btn .ico-cal{display:none}}
.day-date-picker{position:absolute;top:calc(100% + 8px);left:50%;transform:translateX(-50%);z-index:999;background:linear-gradient(to bottom,rgba(255,255,255,.60) 0%,rgba(255,255,255,.10) 100%);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border:1.5px solid rgba(255,255,255,.25);border-radius:16px;padding:16px 14px 14px;box-shadow:0 16px 48px rgba(0,0,0,.45);width:min(320px,calc(100vw - 32px));display:none}
@media(max-width:600px){
  .day-date-picker{left:50%;transform:translateX(-50%)}
}
.day-date-picker.open{display:block}
.ddp-header{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid rgba(255,255,255,.15)}
.ddp-nav-group{display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,.1);border-radius:10px;padding:5px 8px}
.ddp-nav-btn{width:24px;height:24px;border-radius:6px;border:none;background:transparent;color:#0E1530;cursor:pointer;display:grid;place-items:center;transition:background 120ms,color 120ms}
.ddp-nav-btn:hover{background:rgba(22,139,217,.25);color:#0040A0}
.ddp-title{font-family:'Sora',sans-serif;font-size:13px;font-weight:700;color:#0E1530;text-align:center;flex:1}
.ddp-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-top:2px}
.ddp-dow{font-size:10px;font-weight:700;color:rgba(14,21,48,.5);text-align:center;padding:4px 0 8px;letter-spacing:.03em}
.ddp-day{height:36px;border-radius:8px;border:none;background:transparent;color:#0E1530;font-size:13px;font-weight:500;cursor:pointer;transition:background 120ms,color 120ms;display:grid;place-items:center}
.ddp-day:hover{background:rgba(46,100,200,.28);color:#EAF1FF}
.ddp-day.today{color:#1668D9;font-weight:700}
.ddp-day.selected{background:linear-gradient(135deg,#1668D9,#0040A0);color:#fff;font-weight:700;box-shadow:0 4px 14px -4px rgba(22,104,217,.55)}
.ddp-day.other-month{opacity:.35;color:#0E1530}
.ddp-label{font-family:'Sora',sans-serif;font-size:11px;font-weight:600;color:#0E1530;margin-bottom:10px;text-align:center;letter-spacing:.05em;text-transform:uppercase}
.day-schedule{display:flex;flex-direction:column;gap:0;max-height:408px;overflow-y:auto}
.day-row{display:grid;grid-template-columns:52px 1fr;border-bottom:1px solid rgba(110,160,255,.07);min-height:68px}
.day-hour{font-size:11px;color:var(--txt-soft);display:flex;align-items:flex-start;padding:10px 8px 0 0;justify-content:flex-end;white-space:nowrap;border-right:1px solid rgba(110,160,255,.1)}
.day-slot{padding:6px 12px 6px 10px;display:flex;flex-direction:column;gap:4px}
.day-event{display:grid;grid-template-columns:34px 1fr auto auto;align-items:center;gap:7px;background:#001525;border:1.5px solid rgba(22,139,217,.7);box-shadow:0 0 0 1px rgba(22,139,217,.18),0 2px 12px rgba(22,139,217,.1);border-radius:9px;padding:7px 10px;cursor:pointer;transition:opacity 150ms ease}
.day-event:hover{opacity:.85}
.day-event-thumb{width:34px;height:34px;border-radius:7px;flex:none}
.day-event-thumb.ev-done  {background:linear-gradient(to bottom,#042226 20%,#4C9242 80%);border:1.38px solid #284D23}
.day-event-thumb.ev-wait  {background:linear-gradient(to bottom,#351909 29%,#9B491A 100%);border:1.24px solid #E75D01}
.day-event-thumb.ev-cancel{background:linear-gradient(to bottom,#251117 38%,#D90000 100%);border:1.27px solid #D90000}
.day-event-thumb.ev-soon  {background:linear-gradient(to bottom,#0B1331 43%,#B263FF 100%);border:1.27px solid #B263FF}
.day-event-thumb.ev-block {width:34px;height:34px;border-radius:7px;background:rgba(110,160,255,.15);display:flex;align-items:center;justify-content:center;flex:none}
.day-event.ev-block{grid-template-columns:34px 1fr;border-color:rgba(110,160,255,.35);box-shadow:0 0 0 1px rgba(110,160,255,.1),0 2px 10px rgba(110,160,255,.06)}
.day-event-info strong{display:block;font-size:13px;font-weight:700;color:#EAF1FF;line-height:1.3}
.day-event-info span{display:block;font-size:11px;color:rgba(234,241,255,.55)}
.day-event-status{font-size:12px;font-weight:700;white-space:nowrap}
.day-event-status.ev-done  {color:#4C9242}
.day-event-status.ev-wait  {color:#E75D01}
.day-event-status.ev-cancel{color:#D90000}
.day-event-status.ev-soon  {color:#B263FF}
.day-event-status.ev-block {color:var(--txt-soft)}
.day-event-icon{width:24px;height:24px;flex:none;opacity:.8}

/* Botón +X más en vista día (solo cuando expandido) */
.day-more-btn{display:none;width:100%;margin-top:4px;padding:4px 8px;font-size:10px;font-weight:600;color:#8FA3CF;background:transparent;border:1.5px dashed rgba(110,160,255,.4);border-radius:6px;cursor:pointer;transition:all 150ms ease;text-align:center}
.day-more-btn:hover{color:#EAF1FF;background:rgba(110,160,255,.15);border-color:rgba(110,160,255,.6)}
.agenda-left.expanded .day-more-btn{display:block}
html[data-theme="light"] .day-more-btn{color:#5B6A99;border-color:rgba(20,50,120,.25)}
html[data-theme="light"] .day-more-btn:hover{color:#0E1530;background:rgba(20,50,120,.1);border-color:rgba(20,50,120,.4)}

.day-panel{display:flex;flex-direction:column;gap:12px;overflow-y:auto;height:408px}
.day-panel-card{background:#001525;border:1px solid rgba(22,139,217,.2);border-radius:11px;padding:14px}
.day-pc-head{display:flex;align-items:center;gap:10px;margin-bottom:10px}
.day-pc-avatar{width:42px;height:42px;border-radius:50%;flex:none;overflow:hidden;background:linear-gradient(135deg,var(--blue),var(--cyan));display:grid;place-items:center;font-family:'Sora',sans-serif;font-size:12px;font-weight:700;color:#fff;border:2px solid rgba(22,139,217,.4)}
.day-pc-name{font-size:13px;font-weight:700;color:#EAF1FF;line-height:1.3}
.day-pc-age{font-size:11px;color:rgba(234,241,255,.5)}
.day-pc-info{font-size:11.5px;color:rgba(234,241,255,.7);line-height:1.8;margin-bottom:12px}
.day-pc-info b{color:#EAF1FF;font-weight:600}
.day-panel-title{font-family:'Sora',sans-serif;font-size:13px;font-weight:700;color:#EAF1FF;margin-bottom:10px}

/* ---- Responsive día ---- */
@media(max-width:900px){
  .day-view-body{grid-template-columns:1fr}
  .day-panel{display:none}
  .day-nav-bar{gap:4px}
  .day-nav-btn{padding:7px 10px;font-size:11.5px;flex:none;white-space:nowrap}
  .day-nav-title{padding:0 2px;flex:1;min-width:0;text-align:center}
  .day-title-btn{font-size:clamp(10px,2.8vw,13px);padding:6px 10px;gap:5px;width:100%;justify-content:center}
  .day-schedule{max-height:408px}
}
@media(max-width:600px){
  .day-nav-btn{padding:6px 10px;font-size:11px}
  .day-title-btn{font-size:12px;padding:5px 8px;gap:5px}
  .day-hour{font-size:10.5px;padding:8px 6px 0 0}
  .day-schedule{max-height:408px}
  /* Evento en modo stack: thumb | (nombre + proc + status) */
  .day-row{min-height:auto}
  .day-event{
    display:grid;
    grid-template-columns:36px 1fr;
    grid-template-rows:auto auto;
    gap:4px 8px;
    padding:8px 10px;
    align-items:start;
  }
  .day-event-thumb,
  .day-event-thumb.ev-block{width:36px;height:36px;grid-row:1/3}
  .day-event-info{grid-column:2;grid-row:1}
  .day-event-info strong{font-size:12px}
  .day-event-info span{font-size:10.5px}
  .day-event-status{
    grid-column:2;grid-row:2;
    font-size:10.5px;font-weight:700;
    display:block;
  }
  .day-event-icon{display:none}
  .day-event.ev-block{grid-template-columns:36px 1fr;grid-template-rows:auto}
}
@media(max-width:420px){
  .day-nav-btn{padding:5px 8px;font-size:10.5px}
  .day-title-btn{font-size:11px;padding:4px 6px;gap:4px}
  .day-event{grid-template-columns:32px 1fr;padding:6px 8px;gap:3px 6px}
  .day-event-thumb,.day-event-thumb.ev-block{width:32px;height:32px;grid-row:1/3}
  .day-event-status{font-size:10px}
  .day-row{grid-template-columns:40px 1fr}
  .day-hour{font-size:10px;padding:6px 4px 0 0}
}

/* Tema claro */
html[data-theme="light"] .day-nav-btn{background:#EEF4FF;border-color:rgba(20,50,120,.25);color:#0E1530}
html[data-theme="light"] .day-nav-btn:hover{background:#D8E8FF}
html[data-theme="light"] .day-nav-title{color:#0E1530}
html[data-theme="light"] .day-title-btn{background:linear-gradient(135deg,#EEF4FF 30%,#C8DEFF 100%);border-color:rgba(20,50,120,.35);color:#0E1530}
html[data-theme="light"] .day-date-picker{
  background:linear-gradient(to bottom,rgba(30,80,200,.18) 0%,rgba(30,80,200,.05) 100%);
  border-color:rgba(20,50,120,.2);
  box-shadow:0 12px 40px rgba(20,50,120,.15);
}
html[data-theme="light"] .ddp-nav-group{background:rgba(20,50,120,.08)}
html[data-theme="light"] .ddp-header{border-bottom-color:rgba(20,50,120,.12)}
html[data-theme="light"] .ddp-label{color:#2E4A8A}
html[data-theme="light"] .ddp-title{color:#0E1530}
html[data-theme="light"] .ddp-nav-btn{color:#2E4A8A}
html[data-theme="light"] .ddp-nav-btn:hover{background:rgba(20,50,120,.15);color:#0E1530}
html[data-theme="light"] .ddp-dow{color:rgba(14,21,48,.5)}
html[data-theme="light"] .ddp-day{color:#0E1530}
html[data-theme="light"] .ddp-day:hover{background:rgba(20,50,120,.12);color:#0E1530}
html[data-theme="light"] .ddp-day.today{color:#1668D9}
html[data-theme="light"] .ddp-day.other-month{color:rgba(14,21,48,.35);opacity:1}
html[data-theme="light"] .day-hour{color:#5B6A99;border-right-color:rgba(20,50,120,.3)}
html[data-theme="light"] .day-row{border-bottom-color:rgba(20,50,120,.18)}
html[data-theme="light"] .day-event{background:#F6F8FE;border-color:rgba(20,50,120,.45);box-shadow:0 0 0 1px rgba(20,50,120,.08),0 2px 10px rgba(20,50,120,.06)}
html[data-theme="light"] .day-event-info strong{color:#0E1530}
html[data-theme="light"] .day-event-info span{color:rgba(14,21,48,.5)}
html[data-theme="light"] .day-event-thumb.ev-done  {background:#EBF7EA;border-color:#4C9242}
html[data-theme="light"] .day-event-thumb.ev-wait  {background:#FEF3E7;border-color:#E75D01}
html[data-theme="light"] .day-event-thumb.ev-cancel{background:#FDE8E8;border-color:#D90000}
html[data-theme="light"] .day-event-thumb.ev-soon  {background:#F3ECFF;border-color:#B263FF}
html[data-theme="light"] .day-event-thumb.ev-block {background:rgba(20,50,120,.12)}
html[data-theme="light"] .day-event.ev-block{border-color:rgba(20,50,120,.25);box-shadow:0 0 0 1px rgba(20,50,120,.06),0 2px 40px rgba(20,50,120,.05)}
html[data-theme="light"] .day-event-status.ev-block{color:#5B6A99}
html[data-theme="light"] .day-panel-card{background:#F0F5FF;border-color:rgba(20,50,120,.15)}
html[data-theme="light"] .day-panel-title{color:#0E1530}
html[data-theme="light"] .day-pc-name{color:#0E1530}
html[data-theme="light"] .day-pc-age{color:rgba(14,21,48,.5)}
html[data-theme="light"] .day-pc-info{color:rgba(14,21,48,.7)}
html[data-theme="light"] .day-pc-info b{color:#0E1530}

/* Modal de día para modo expandido */
.day-modal-overlay{position:fixed;inset:0;z-index:1000;background:rgba(0,11,30,.75);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;padding:16px}
.day-modal-overlay.open{display:flex}
.day-modal{background:#000B1E;border:1.84px solid #168BD9;box-shadow:inset 0 0 0 1.84px rgba(0,0,0,.47),0 24px 64px rgba(0,0,0,.5);border-radius:16px;width:100%;max-width:320px;max-height:80vh;overflow:hidden;display:flex;flex-direction:column}
.day-modal-header{padding:16px 18px;border-bottom:1px solid rgba(110,160,255,.2);display:flex;align-items:center;justify-content:space-between;gap:12px}
.day-modal-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:700;color:#EAF1FF}
.day-modal-close{width:28px;height:28px;border-radius:8px;border:none;background:transparent;color:#8FA3CF;cursor:pointer;display:grid;place-items:center;transition:all 150ms ease}
.day-modal-close:hover{background:rgba(110,160,255,.15);color:#EAF1FF}
.day-modal-del{width:28px;height:28px;border-radius:8px;border:none;background:transparent;color:#8FA3CF;cursor:pointer;display:grid;place-items:center;transition:all 150ms ease}
.day-modal-del:hover{background:rgba(217,0,0,.18);color:#D90000}
.day-modal-body{padding:14px 18px;overflow-y:auto}
.day-modal-body .day-panel-card{margin:0}
/* Panel de confirmación borrar */
.del-confirm-panel{position:absolute;inset:0;z-index:10;background:rgba(0,11,30,.92);backdrop-filter:blur(8px);border-radius:16px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;padding:28px 24px;text-align:center}
.del-confirm-icon{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#3a0000,#D90000 80%);display:flex;align-items:center;justify-content:center;box-shadow:0 0 32px rgba(217,0,0,.45)}
.del-confirm-title{font-family:'Sora',sans-serif;font-size:17px;font-weight:700;color:#EAF1FF}
.del-confirm-sub{font-size:12.5px;color:rgba(234,241,255,.55);line-height:1.5;max-width:220px}
.del-confirm-actions{display:flex;flex-direction:column;gap:8px;width:100%}
.del-confirm-yes{padding:11px 14px;border-radius:10px;border:none;background:linear-gradient(135deg,#7a0000,#D90000 80%);color:#fff;font-size:13px;font-weight:700;cursor:pointer;transition:opacity 150ms ease;box-shadow:0 4px 16px rgba(217,0,0,.35)}
.del-confirm-yes:hover{opacity:.85}
.del-confirm-no{padding:11px 14px;border-radius:10px;border:1.5px solid rgba(110,160,255,.25);background:transparent;color:#8FA3CF;font-size:13px;font-weight:600;cursor:pointer;transition:all 150ms ease}
.del-confirm-no:hover{background:rgba(110,160,255,.1);color:#EAF1FF}
/* Botón borrar cita — rojo explícito, responsive */
.ev-pop-btn.danger{display:flex!important;align-items:center;justify-content:center;gap:7px;width:100%;box-sizing:border-box;margin-top:8px;margin-bottom:0;padding:10px 14px;border-radius:10px!important;border:1.5px solid rgba(217,0,0,.55)!important;background:rgba(217,0,0,.08)!important;color:#D90000!important;font-size:13px;font-weight:700;cursor:pointer;transition:all 150ms ease}
.ev-pop-btn.danger:hover{background:rgba(217,0,0,.2)!important;border-color:#D90000!important;opacity:1}
.ev-pop-btn.danger svg{flex:none;stroke:#D90000}
html[data-theme="light"] .ev-pop-btn.danger{border-color:rgba(180,0,0,.4)!important;background:rgba(180,0,0,.06)!important;color:#B00000!important}
html[data-theme="light"] .ev-pop-btn.danger svg{stroke:#B00000}
html[data-theme="light"] .ev-pop-btn.danger:hover{background:rgba(180,0,0,.14)!important;border-color:#B00000!important}
@media(max-width:480px){.ev-pop-btn.danger{font-size:12px;padding:9px 10px}}

/* ============================================================
   FIX: modales de eliminar en tema claro
   ============================================================ */
html[data-theme="light"] #delGlobalOverlay{
  background:rgba(15,23,42,.28) !important;
  backdrop-filter:blur(6px) !important;
}

html[data-theme="light"] #delGlobalBox{
  background:#FFFFFF !important;
  border:1.5px solid rgba(220,38,38,.28) !important;
  box-shadow:0 22px 55px rgba(15,23,42,.18) !important;
  color:#0E1530 !important;
}

html[data-theme="light"] .del-confirm-panel{
  background:#FFFFFF !important;
  color:#0E1530 !important;
  backdrop-filter:none !important;
}

html[data-theme="light"] .del-confirm-icon{
  background:rgba(220,38,38,.10) !important;
  border:1px solid rgba(220,38,38,.22) !important;
  box-shadow:none !important;
}

html[data-theme="light"] .del-confirm-icon svg{
  stroke:#D90000 !important;
}

html[data-theme="light"] .del-confirm-title{
  color:#0E1530 !important;
}

html[data-theme="light"] .del-confirm-sub{
  color:#5B6A99 !important;
}

html[data-theme="light"] .del-confirm-no{
  background:#F4F7FF !important;
  border-color:rgba(20,50,120,.18) !important;
  color:#33456F !important;
}

html[data-theme="light"] .del-confirm-no:hover{
  background:#E8EFFD !important;
  color:#0E1530 !important;
}

html[data-theme="light"] .del-confirm-yes{
  background:linear-gradient(135deg,#B91C1C,#DC2626) !important;
  color:#FFFFFF !important;
  box-shadow:0 8px 20px rgba(220,38,38,.22) !important;
}

html[data-theme="light"] .day-modal{
  background:#FFFFFF !important;
  border-color:rgba(20,50,120,.18) !important;
  box-shadow:0 22px 55px rgba(15,23,42,.18) !important;
}

html[data-theme="light"] .day-modal-header{
  border-bottom-color:rgba(20,50,120,.12) !important;
}

html[data-theme="light"] .day-modal-title{
  color:#0E1530 !important;
}

html[data-theme="light"] .day-modal-close{
  color:#5B6A99 !important;
}

html[data-theme="light"] .day-modal-close:hover{
  background:rgba(20,50,120,.1) !important;
  color:#0E1530 !important;
}

html[data-theme="light"] .day-modal-del{
  color:#5B6A99 !important;
}

html[data-theme="light"] .day-modal-del:hover{
  background:rgba(180,0,0,.1) !important;
  color:#B00000 !important;
}

html[data-theme="light"] .day-modal-overlay{
  background:rgba(15,23,42,.28) !important;
}

</style>


<div class="day-view" id="dayView">
  <div class="day-nav-bar">
    <button class="day-nav-btn" id="dayPrev">Anterior</button>
    <div class="day-nav-title" id="dayTitle">
      <button class="day-title-btn" id="dayTitleBtn">
        <svg class="ico-cal" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <span id="dayTitleText"></span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div class="day-date-picker" id="dayDatePicker">
        <div class="ddp-label">Selecciona un día</div>
        <div class="ddp-header">
          <div class="ddp-nav-group">
            <button class="ddp-nav-btn" id="ddpYearPrev"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
            <span class="ddp-title" id="ddpYear"></span>
            <button class="ddp-nav-btn" id="ddpYearNext"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>
          </div>
          <div class="ddp-nav-group">
            <button class="ddp-nav-btn" id="ddpMonthPrev"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
            <span class="ddp-title" id="ddpMonth"></span>
            <button class="ddp-nav-btn" id="ddpMonthNext"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>
          </div>
        </div>
        <div class="ddp-grid" id="ddpGrid"></div>
      </div>
    </div>
    <button class="day-nav-btn" id="dayNext">Siguiente</button>
  </div>
  <div class="day-view-body">
    <div class="day-left">
      <div class="day-schedule" id="daySchedule"></div>
    </div>
    <div class="day-panel" id="dayPanel">
      <div class="day-panel-title">Pacientes</div>
    </div>
  </div>
</div>


<div id="delGlobalOverlay" style="display:none;position:fixed;inset:0;z-index:2000;background:rgba(0,11,30,.82);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px">
  <div id="delGlobalBox" style="background:#000B1E;border:1.84px solid #D90000;border-radius:16px;width:100%;max-width:300px;padding:28px 24px;display:flex;flex-direction:column;align-items:center;gap:16px;text-align:center;box-shadow:0 0 40px rgba(217,0,0,.3),0 24px 64px rgba(0,0,0,.6)">
    <div class="del-confirm-icon">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
    </div>
    <div class="del-confirm-title">¿Borrar esta cita?</div>
    <div class="del-confirm-sub">Esta acción eliminará la cita de la agenda. No se puede deshacer.</div>
    <div class="del-confirm-actions" style="width:100%">
      <button class="del-confirm-yes" data-no-confirm id="delGlobalYes">Sí, borrar cita</button>
      <button class="del-confirm-no" data-no-confirm id="delGlobalNo" style="margin-top:0">Cancelar</button>
    </div>
  </div>
</div>


<div class="day-modal-overlay" id="dayModalOverlay">
  <div class="day-modal" style="position:relative">
    <div class="day-modal-header">
      <div class="day-modal-title" id="dayModalTitle">Detalle de cita</div>
      <div style="display:flex;gap:4px;align-items:center">
        <button class="day-modal-del" id="dayModalDel" data-no-confirm aria-label="Borrar cita" title="Borrar cita">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
        </button>
        <button class="day-modal-close" id="dayModalClose" aria-label="Cerrar">×</button>
      </div>
    </div>
    <div class="day-modal-body" id="dayModalBody"></div>
  </div>
</div>


<script>
(function(){
  const STATUS_ICONS_SVG = {
    'ev-done':   `<svg viewBox="0 0 24 24" fill="none" stroke="#4C9242" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`,
    'ev-wait':   `<svg viewBox="0 0 24 24" fill="none" stroke="#E75D01" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
    'ev-cancel': `<svg viewBox="0 0 24 24" fill="none" stroke="#D90000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,
    'ev-soon':   `<svg viewBox="0 0 24 24" fill="none" stroke="#B263FF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>`,
  };
  const STATUS_LABEL_DAY = {
    'ev-done':'Completado','ev-wait':'En espera','ev-cancel':'Cancelado','ev-soon':'Próximos','ev-block':'',
  };
  const DAY_BUTTONS = {
    'ev-done':  [{label:'Datos del paciente',cls:'primary'},{label:'Reprogramar nueva cita',cls:'secondary'},{label:'Ver Informe',cls:'secondary'}],
    'ev-wait':  [{label:'Iniciar Estudio',cls:'primary'},{label:'Datos del Paciente',cls:'secondary'}],
    'ev-cancel':[{label:'Reprogramar Paciente',cls:'primary'},{label:'Datos del Paciente',cls:'secondary'}],
    'ev-soon':  [{label:'Reprogramar Paciente',cls:'primary'},{label:'Datos del Paciente',cls:'secondary'}],
  };

  function minutesTo12h(min) {
    const h = Math.floor(min / 60);
    const m = min % 60;
    const ampm = h >= 12 ? 'PM' : 'AM';
    const h12 = h % 12 === 0 ? 12 : h % 12;
    return `${h12}:${String(m).padStart(2,'0')} ${ampm}`;
  }
  function time24To12h(time24) {
    const [h, m] = String(time24 || '00:00').split(':').map(Number);
    return minutesTo12h((h || 0) * 60 + (m || 0));
  }

  /* ---- Modal de día ---- */
  const dayModalOverlay = document.getElementById('dayModalOverlay');
  const dayModalTitle   = document.getElementById('dayModalTitle');
  const dayModalBody    = document.getElementById('dayModalBody');
  const dayModalClose   = document.getElementById('dayModalClose');
  const dayModalDel     = document.getElementById('dayModalDel');
  const dayModalWrap    = dayModalOverlay.querySelector('.day-modal');
  let   __currentDelEv  = null;

  function showDelConfirm(ev, y, m, d) {
    __currentDelEv = { ev, y, m, d };
    const isEliminar = ev && ['cancelado', 'completado'].includes(ev.estado);
    const panel = document.createElement('div');
    panel.className = 'del-confirm-panel';
    panel.id = 'delConfirmPanel';
    panel.innerHTML = `
      <div class="del-confirm-icon">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
      </div>
      <div class="del-confirm-title">${isEliminar ? '¿Eliminar esta cita?' : '¿Cancelar esta cita?'}</div>
      <div class="del-confirm-sub">${isEliminar ? 'Esta acción eliminará la cita de la base de datos. No se puede deshacer.' : 'La cita se marcará como cancelada y seguirá visible en el historial.'}</div>
      <div class="del-confirm-actions">
        <button class="del-confirm-yes" data-no-confirm id="delConfirmYes">${isEliminar ? 'Sí, eliminar' : 'Sí, cancelar'}</button>
        <button class="del-confirm-no" data-no-confirm id="delConfirmNo">Cancelar</button>
      </div>
    `;
    dayModalWrap.appendChild(panel);
    document.getElementById('delConfirmNo').addEventListener('click', () => panel.remove());
    document.getElementById('delConfirmYes').addEventListener('click', () => {
      const current = __currentDelEv;
      const isEliminarCurrent = current.ev && ['cancelado', 'completado'].includes(current.ev.estado);
      const urlEliminar = current.ev && current.ev.delete_url;
      const urlCancelar = current.ev && current.ev.estado_url;
      const id = current.ev && current.ev.id;
      const finish = () => {
        panel.remove();
        closeDayModal();
        if (window.__rebuildAgenda) window.__rebuildAgenda();
      };
      if (isEliminarCurrent && urlEliminar) {
        window.__deleteCita(urlEliminar, {
          onSuccess: () => {
            if (id) window.__removeAgendaEventById(id);
            finish();
          },
          onError: (msg) => {
            alert(msg);
            panel.remove();
          }
        });
      } else if (!isEliminarCurrent && urlCancelar) {
        fetch(urlCancelar, {
          method: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ estado: 'cancelado' })
        })
        .then(r => r.json().catch(() => ({})))
        .then(data => {
          current.ev.estado = 'cancelado';
          current.ev.cls = 'ev-cancel';
          if (id && window.__AGENDA_EVENTS) {
            Object.keys(window.__AGENDA_EVENTS).forEach(key => {
              window.__AGENDA_EVENTS[key].forEach(evv => {
                if (String(evv.id) === String(id)) {
                  evv.estado = 'cancelado';
                  evv.cls = 'ev-cancel';
                }
              });
            });
          }
          finish();
        })
        .catch(err => {
          alert(err.message || 'No se pudo cancelar la cita');
          panel.remove();
        });
      } else {
        const key = `${current.y}-${String(current.m+1).padStart(2,'0')}-${String(current.d).padStart(2,'0')}`;
        if (window.__EVENTS_DIA && window.__EVENTS_DIA[key]) {
          window.__EVENTS_DIA[key] = window.__EVENTS_DIA[key].filter(x => x !== current.ev);
        }
        finish();
      }
    });
  }

  function closeDayModal() {
    dayModalOverlay.classList.remove('open');
    const old = document.getElementById('delConfirmPanel');
    if (old) old.remove();
  }

  /* ---- Overlay global (mes / semana) ---- */
  const delGlobalOverlay = document.getElementById('delGlobalOverlay');
  const delGlobalYes     = document.getElementById('delGlobalYes');
  const delGlobalNo      = document.getElementById('delGlobalNo');
  let   __globalDelEl    = null;
  let   __globalDelUrl   = null;

  function closeGlobalDel() {
    delGlobalOverlay.style.display = 'none';
    __globalDelEl = null;
    __globalDelUrl = null;
  }

  delGlobalNo.addEventListener('click', closeGlobalDel);
  delGlobalOverlay.addEventListener('click', e => { if (e.target === delGlobalOverlay) closeGlobalDel(); });

  function finishGlobalDelRemove() {
    const el = __globalDelEl;
    if (el) {
      el.style.transition = 'opacity 250ms ease, transform 250ms ease';
      el.style.opacity = '0';
      el.style.transform = 'scale(.85)';
      setTimeout(() => el.remove(), 260);
    }
    closeGlobalDel();
    if (window.__rebuildProximas) window.__rebuildProximas();
    if (window.__rebuildAgenda) window.__rebuildAgenda();
  }

  delGlobalYes.addEventListener('click', () => {
    const el = __globalDelEl;
    const url = __globalDelUrl || (el && el.dataset.deleteUrl) || '';
    const id = el && el.dataset.citaId;
    const isEliminar = el && ['cancelado', 'completado'].includes(el.dataset.estado);
    if (!url) {
      finishGlobalDelRemove();
      return;
    }
    if (isEliminar) {
      window.__deleteCita(url, {
        onSuccess: () => {
          if (id) window.__removeAgendaEventById(id);
          finishGlobalDelRemove();
        },
        onError: (msg) => {
          alert(msg);
          closeGlobalDel();
        }
      });
    } else {
      // Cancelar cita: PATCH al estado_url
      fetch(url, {
        method: 'PATCH',
        headers: {
          'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ estado: 'cancelado' })
      })
      .then(r => r.json().catch(() => ({})))
      .then(data => {
        if (el) {
          el.dataset.estado = 'cancelado';
          el.classList.remove('ev-soon', 'ev-wait', 'ev-done');
          el.classList.add('ev-cancel');
        }
        if (id && window.__AGENDA_EVENTS) {
          Object.keys(window.__AGENDA_EVENTS).forEach(key => {
            window.__AGENDA_EVENTS[key].forEach(ev => {
              if (String(ev.id) === String(id)) {
                ev.estado = 'cancelado';
                ev.cls = 'ev-cancel';
              }
            });
          });
        }
        closeGlobalDel();
        if (window.__rebuildAgenda) window.__rebuildAgenda();
      })
      .catch(err => {
        alert(err.message || 'No se pudo cancelar la cita');
        closeGlobalDel();
      });
    }
  });

  window.showDelConfirmGlobal = function(evEl, url) {
    __globalDelEl = evEl;
    __globalDelUrl = url || (evEl && evEl.dataset.deleteUrl) || '';
    const isEliminar = evEl && ['cancelado', 'completado'].includes(evEl.dataset.estado);
    const title = document.querySelector('#delGlobalBox .del-confirm-title');
    const sub   = document.querySelector('#delGlobalBox .del-confirm-sub');
    const yes   = document.getElementById('delGlobalYes');
    if (title) title.textContent = isEliminar ? '¿Eliminar esta cita?' : '¿Cancelar esta cita?';
    if (sub) sub.textContent = isEliminar
      ? 'Esta acción eliminará la cita de la base de datos. No se puede deshacer.'
      : 'La cita se marcará como cancelada y seguirá visible en el historial.';
    if (yes) yes.textContent = isEliminar ? 'Sí, eliminar' : 'Sí, cancelar';
    delGlobalOverlay.style.display = 'flex';
  };
  dayModalClose.addEventListener('click', closeDayModal);
  dayModalOverlay.addEventListener('click', (e) => {
    if (e.target === dayModalOverlay) closeDayModal();
  });
  dayModalDel.addEventListener('click', () => {
    if (__currentDelEv) showDelConfirm(__currentDelEv.ev, __currentDelEv.y, __currentDelEv.m, __currentDelEv.d);
  });

  const MESES_DIA = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

  const STATUS_LABELS_MODAL = {'ev-done':'Completado','ev-wait':'En espera','ev-cancel':'Cancelado','ev-soon':'Próximos'};
  const STATUS_BADGE_KEY   = {'ev-done':'done','ev-wait':'wait','ev-cancel':'cancel','ev-soon':'soon'};
  const REPROG_LABELS_DIA  = ['Reprogramar nueva cita','Reprogramar Paciente'];
  const PACIENTE_LABELS_DIA = ['Datos del paciente','Datos del Paciente'];
  const INFORME_LABELS_DIA = ['Ver Informe'];
  const INICIAR_LABELS_DIA = ['Iniciar Estudio'];

  function __parseEvData(ev) {
    const text = ev.t ? ev.t.trim() : '';
    const timeM = text.match(/^(\d+:\d+)/);
    const time = timeM ? timeM[1] : (ev.h ? String(ev.h).padStart(2, '0') + ':00' : '');
    let name = ev.name || '';
    let proc = ev.proc || '';
    if (!name && text) {
      const rest = text.replace(/^\d+:\d+\s*/, '');
      const sepIdx = rest.indexOf('·');
      name = sepIdx !== -1 ? rest.substring(0, sepIdx).trim() : rest.trim() || 'Paciente';
      proc = sepIdx !== -1 ? rest.substring(sepIdx + 1).trim() : 'Procedimiento';
    }
    return { name, proc, time };
  }

  function buildAgendarUrlDia(name, proc, time, d, m, y, citaId) {
    const params = new URLSearchParams();
    if (name) params.set('paciente', name);
    if (proc) params.set('proc', proc);
    if (time) params.set('hora', time);
    if (d)    params.set('dia', d);
    if (m >= 0) params.set('mes', m + 1);
    if (y)    params.set('anio', y);
    if (citaId) params.set('cita_id', citaId);
    return '<?php echo e(route("agendar")); ?>?' + params.toString();
  }

  window.openDayModal = function(ev, dayNames, dow, d, m, y) {
    const key = `${y}-${m+1}-${d}`;
    const liveCls = typeof window.__recomputeClass === 'function' ? window.__recomputeClass(ev, key) : (ev.cls || 'ev-done');
    const { name, proc, time } = __parseEvData(ev);
    const displayName = (window.__displayName ? window.__displayName(name) : name);
    const inits = displayName.split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();
    const duration = parseInt(ev.duracion || '60', 10) || 60;
    const startMin = (() => {
      const [h, m] = String(time || '00:00').split(':').map(Number);
      return (h || 0) * 60 + (m || 0);
    })();
    const timeRange = `${time24To12h(time)} – ${minutesTo12h(startMin + duration)}`;

    dayModalBody.innerHTML = '';

    /* ---- Bloqueo de tiempo ---- */
    if (ev.cls === 'ev-block') {
      const blockLabel = ev.name || name.replace(/^\d+:\d+\s*/, '') || 'Bloqueo de Tiempo';
      dayModalTitle.textContent = 'Bloqueo de Tiempo';
      dayModalDel.style.display = 'none';
      const card = document.createElement('div');
      card.className = 'day-panel-card';
      card.style.textAlign = 'center';
      card.innerHTML = `
        <div style="margin:8px auto 14px;width:48px;height:48px;border-radius:12px;background:rgba(110,160,255,.12);border:1.5px solid rgba(110,160,255,.25);display:flex;align-items:center;justify-content:center">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#8FA3CF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        <div style="font-size:15px;font-weight:700;color:#EAF1FF;margin-bottom:10px">${blockLabel}</div>
        <div class="day-pc-info" style="text-align:left;margin-bottom:16px">
          <b>Motivo:</b> ${blockLabel}<br>
          <b>Hora:</b> ${timeRange}<br>
          <b>Fecha:</b> ${dayNames[dow]} ${d} de ${MESES_DIA[m]}
        </div>`;
      const delBtn = document.createElement('button');
      delBtn.className = 'ev-pop-btn danger';
      delBtn.dataset.noConfirm = '';
      delBtn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg> Eliminar bloqueo`;
      delBtn.addEventListener('click', () => {
        dayModalOverlay.classList.remove('open');
        if (window.__openDeleteBloqueoConfirm) {
          window.__openDeleteBloqueoConfirm(ev.blockId, key, blockLabel);
        }
      });
      card.appendChild(delBtn);
      dayModalBody.appendChild(card);
      __currentDelEv = null;
      dayModalOverlay.classList.add('open');
      return;
    }

    /* ---- Cita normal ---- */
    dayModalDel.style.display = '';
    dayModalTitle.textContent = `${time24To12h(time)} – ${displayName}`;

    const cls = liveCls;
    const badgeKey = STATUS_BADGE_KEY[cls] || 'done';
    const badgeLabel = STATUS_LABELS_MODAL[cls] || 'Completado';

    const card = document.createElement('div');
    card.className = 'day-panel-card';
    const head = document.createElement('div');
    head.className = 'day-pc-head';
    head.innerHTML = `<div class="day-pc-avatar">${inits}</div><div><div class="day-pc-name">${displayName}</div></div>`;

    const badge = document.createElement('div');
    badge.className = 'ev-pop-badge ' + badgeKey;
    badge.textContent = badgeLabel;
    badge.style.marginBottom = '10px';

    const info = document.createElement('div');
    info.className = 'day-pc-info';
    info.innerHTML =
      `<b>Motivo:</b> ${proc}<br>` +
      `<b>Fecha:</b> ${dayNames[dow]} ${d} de ${MESES_DIA[m]}<br>` +
      `<b>Tiempo:</b> ${timeRange}<br>` +
      `<b>Habitación:</b> Sala 3`;
    card.appendChild(head);
    card.appendChild(info);
    card.appendChild(badge);
    (DAY_BUTTONS[cls] || []).forEach((b,i) => {
      const btn = document.createElement('button');
      btn.className = 'ev-pop-btn ' + b.cls;
      btn.style.marginBottom = i < (DAY_BUTTONS[cls].length-1) ? '6px' : '0';
      btn.textContent = b.label;
      if (REPROG_LABELS_DIA.includes(b.label)) {
        btn.addEventListener('click', () => {
          window.location.href = buildAgendarUrlDia(name, proc, time, d, m, y, ev.id);
        });
      }
      if (PACIENTE_LABELS_DIA.includes(b.label)) {
        btn.addEventListener('click', () => {
          const pId = ev.paciente_id || ev.pacienteId || '';
          if (pId) {
            window.location.href = '<?php echo e(route('pacientes.index')); ?>?paciente_id=' + encodeURIComponent(pId);
          } else {
            window.location.href = '<?php echo e(route('pacientes.index')); ?>?paciente=' + encodeURIComponent(name);
          }
        });
      }
      if (INFORME_LABELS_DIA.includes(b.label)) {
        btn.addEventListener('click', () => {
          window.location.href = '<?php echo e(route('ia-reportes.ver')); ?>?paciente=' + encodeURIComponent(displayName || name) + '&procedimiento=' + encodeURIComponent(proc || 'Endoscopia');
        });
      }
      if (INICIAR_LABELS_DIA.includes(b.label)) {
        btn.addEventListener('click', () => {
          const pId = ev.paciente_id || ev.pacienteId || '';
          window.location.href = '<?php echo e(route('nuevo-estudio')); ?>?paciente=' + encodeURIComponent(pId || displayName || name);
        });
      }
      card.appendChild(btn);
    });
    dayModalBody.appendChild(card);
    __currentDelEv = { ev, y, m, d };
    dayModalOverlay.classList.add('open');
  };

  window.__buildDay = function(date, EVENTS, MESES, updateSumCards, countEvents) {
    const HOURS = [8,9,10,11,12,13,14,15,16,17,18,19,20,21];
    const y = date.getFullYear(), m = date.getMonth(), d = date.getDate();
    const dow = date.getDay();
    const dayNames = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

    const pad = n => String(n).padStart(2,'0');
    const isPhone = window.innerWidth < 600;
    document.getElementById('dayTitleText').textContent = isPhone
      ? `${pad(d)}/${pad(m+1)}/${y}`
      : `${dayNames[dow]} ${d} de ${MESES[m]} del ${y}`;
    const mesActualEl = document.getElementById('mesActual');
    const anioActualEl = document.getElementById('anioActual');
    if (mesActualEl) mesActualEl.textContent = MESES[m];
    if (anioActualEl) anioActualEl.textContent = y;

    const key = `${y}-${m+1}-${d}`;
    const dayEvs = EVENTS[key] || [];
    updateSumCards(countEvents([key]));

    const sched = document.getElementById('daySchedule');
    sched.innerHTML = '';
    HOURS.forEach(hr => {
      const row = document.createElement('div');
      row.className = 'day-row';
      const hourEl = document.createElement('div');
      hourEl.className = 'day-hour';
      hourEl.textContent = time24To12h(String(hr).padStart(2, '0') + ':00');
      row.appendChild(hourEl);
      const slot = document.createElement('div');
      slot.className = 'day-slot';

      const hourEvs = dayEvs.filter(ev => ev.h === hr);
      const isExpanded = document.querySelector('.agenda-left')?.classList.contains('expanded');
      
      // Si está expandido y hay 2+ eventos, mostrar botón +X más
      if (isExpanded && hourEvs.length >= 2) {
        const moreBtn = document.createElement('button');
        moreBtn.className = 'day-more-btn';
        moreBtn.textContent = `+${hourEvs.length} citas`;
        moreBtn.addEventListener('click', () => {
          if (window.openWeekModal) {
            const dummyDate = new Date(y, m, d);
            const dayName = dayNames[dow];
            window.openWeekModal(hourEvs, dummyDate, hr, dayName);
          }
        });
        slot.appendChild(moreBtn);
      } else {
        // Mostrar eventos individualmente
        hourEvs.forEach(ev => {
          const liveCls = typeof window.__recomputeClass === 'function' ? window.__recomputeClass(ev, key) : ev.cls;
          const { name, proc, time } = __parseEvData(ev);
          const displayName = (window.__displayName ? window.__displayName(name) : name);
          const inits = displayName.split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();

          const card = document.createElement('div');
          card.className = 'day-event ' + liveCls;
          card.dataset.evcls = liveCls;
          card.dataset.name = name;
          card.dataset.proc = proc;
          card.dataset.time = time;
          card.dataset.duration = ev.duracion || '60';
          card.dataset.inits = inits;
          card.dataset.fechatxt = `${dayNames[dow]} ${d} de ${MESES[m]}`;
          card.dataset.citaId = ev.id || '';
          card.dataset.pacienteId = ev.paciente_id || '';
          card.dataset.deleteUrl = ev.delete_url || '';
          card.dataset.estado = ev.estado || '';
          card.dataset.estadoUrl = ev.estado_url || '';
          if (ev.cls === 'ev-block' && ev.blockId !== undefined) {
            card.dataset.blockid    = ev.blockId;
            card.dataset.blockkey   = key;
            card.dataset.blocklabel = ev.name || name.replace(/^\d+:\d+\s*/,'');
            card.dataset.time       = ev.hora || (ev.h ? String(ev.h).padStart(2,'0') + ':00' : '00:00');
            card.dataset.duration   = ev.duracion || '60';
          }

          const thumb = document.createElement('div');
          thumb.className = 'day-event-thumb ' + liveCls;

          if (ev.cls === 'ev-block') {
            thumb.innerHTML = `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#8FA3CF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>`;
            const info2 = document.createElement('div');
            info2.className = 'day-event-info';
            info2.innerHTML = `<strong>Bloqueo de Tiempo</strong><span>${name.replace(/^\d+:\d+\s*/,'')}</span>`;
            card.appendChild(thumb); card.appendChild(info2);
            card.addEventListener('click', e => {
              e.stopPropagation();
              if (window.openDayModal) window.openDayModal(ev, dayNames, dow, d, m, y);
            });
          } else {
            const info = document.createElement('div');
            info.className = 'day-event-info';
            info.innerHTML = `<strong>${displayName}</strong><span>${proc}</span>`;
            const status = document.createElement('div');
            status.className = 'day-event-status ' + liveCls;
            status.textContent = STATUS_LABEL_DAY[liveCls] || '';
            const icon = document.createElement('div');
            icon.className = 'day-event-icon';
            icon.innerHTML = STATUS_ICONS_SVG[liveCls] || '';
            card.appendChild(thumb); card.appendChild(info);
            card.appendChild(status); card.appendChild(icon);
            // Click handler para abrir modal de cita
            card.addEventListener('click', (e) => {
              if (window.openDayModal) {
                e.stopPropagation();
                window.openDayModal(ev, dayNames, dow, d, m, y);
              }
            });
          }
          slot.appendChild(card);
        });
      }
      row.appendChild(slot);
      sched.appendChild(row);
    });

    const panel = document.getElementById('dayPanel');
    panel.innerHTML = '<div class="day-panel-title">Pacientes</div>';
    const realEvs = dayEvs.filter(ev => ev.cls !== 'ev-block');
    if (realEvs.length === 0) {
      const empty = document.createElement('div');
      empty.style.cssText = 'font-size:12px;color:var(--txt-soft);text-align:center;padding:20px 0';
      empty.textContent = 'Sin citas para este día';
      panel.appendChild(empty);
    }
    realEvs.forEach(ev => {
      const { name, proc, time } = __parseEvData(ev);
      const displayName = (window.__displayName ? window.__displayName(name) : name);
      const inits = displayName.split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();
      const duration = parseInt(ev.duracion || '60', 10) || 60;
      const startMin = (() => {
        const [h, m] = String(time || '00:00').split(':').map(Number);
        return (h || 0) * 60 + (m || 0);
      })();
      const timeRange = `${time24To12h(time)} – ${minutesTo12h(startMin + duration)}`;
      const liveCls = typeof window.__recomputeClass === 'function' ? window.__recomputeClass(ev, key) : ev.cls;

      const card = document.createElement('div');
      card.className = 'day-panel-card';
      const head = document.createElement('div');
      head.className = 'day-pc-head';
      head.innerHTML = `<div class="day-pc-avatar">${inits}</div><div><div class="day-pc-name">${displayName}</div></div>`;
      const info = document.createElement('div');
      info.className = 'day-pc-info';
      info.innerHTML =
        `<b>Motivo:</b> ${proc}<br>` +
        `<b>Fecha:</b> ${dayNames[dow]} ${d} de ${MESES[m]}<br>` +
        `<b>Tiempo:</b> ${timeRange}<br>` +
        `<b>Habitación:</b> Sala 3`;
      const panelBadgeKey = STATUS_BADGE_KEY[liveCls] || 'done';
      const panelBadgeLabel = STATUS_LABELS_MODAL[liveCls] || 'Completado';
      const panelBadge = document.createElement('div');
      panelBadge.className = 'ev-pop-badge ' + panelBadgeKey;
      panelBadge.textContent = panelBadgeLabel;
      panelBadge.style.marginBottom = '10px';

      card.appendChild(head);
      card.appendChild(info);
      card.appendChild(panelBadge);
      (DAY_BUTTONS[liveCls] || []).forEach((b,i) => {
        const btn = document.createElement('button');
        btn.className = 'ev-pop-btn ' + b.cls;
        btn.style.marginBottom = i < (DAY_BUTTONS[liveCls].length-1) ? '6px' : '0';
        btn.textContent = b.label;
        if (REPROG_LABELS_DIA.includes(b.label)) {
          btn.addEventListener('click', () => {
            window.location.href = buildAgendarUrlDia(name, proc, time, d, m, y, ev.id);
          });
        }
        if (PACIENTE_LABELS_DIA.includes(b.label)) {
          btn.addEventListener('click', () => {
            const pId = ev.paciente_id || ev.pacienteId || '';
            if (pId) {
              window.location.href = '<?php echo e(route('pacientes.index')); ?>?paciente_id=' + encodeURIComponent(pId);
            } else {
              window.location.href = '<?php echo e(route('pacientes.index')); ?>?paciente=' + encodeURIComponent(name);
            }
          });
        }
        if (INFORME_LABELS_DIA.includes(b.label)) {
          btn.addEventListener('click', () => {
            window.location.href = '<?php echo e(route('ia-reportes.ver')); ?>?paciente=' + encodeURIComponent(displayName || name) + '&procedimiento=' + encodeURIComponent(proc || 'Endoscopia');
          });
        }
        if (INICIAR_LABELS_DIA.includes(b.label)) {
          btn.addEventListener('click', () => {
            const pId = ev.paciente_id || ev.pacienteId || '';
            window.location.href = '<?php echo e(route('nuevo-estudio')); ?>?paciente=' + encodeURIComponent(pId || displayName || name);
          });
        }
        card.appendChild(btn);
      });
      const panelDelBtn = document.createElement('button');
      panelDelBtn.className = 'ev-pop-btn danger';
      const isEliminarPanel = ['cancelado', 'completado'].includes(ev.estado);
      panelDelBtn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>${isEliminarPanel ? 'Eliminar cita' : 'Cancelar cita'}`;
      panelDelBtn.addEventListener('click', () => {
        window.openDayModal(ev, dayNames, dow, d, m, y);
        setTimeout(() => showDelConfirm(ev, y, m, d), 50);
      });
      card.appendChild(panelDelBtn);
      panel.appendChild(card);
    });
  };

  /* ---- Date Picker ---- */
  const MESES_DDP = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                     'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
  const DIAS_DDP  = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];

  const ddpPicker   = document.getElementById('dayDatePicker');
  const ddpTitleBtn = document.getElementById('dayTitleBtn');
  const ddpYearEl   = document.getElementById('ddpYear');
  const ddpMonthEl  = document.getElementById('ddpMonth');
  const ddpGrid     = document.getElementById('ddpGrid');

  let ddpY = new Date().getFullYear();
  let ddpM = new Date().getMonth();
  let ddpSelected = null;

  function renderDDP() {
    ddpYearEl.textContent  = ddpY;
    ddpMonthEl.textContent = MESES_DDP[ddpM];
    ddpGrid.innerHTML = '';

    DIAS_DDP.forEach(d => {
      const h = document.createElement('div');
      h.className = 'ddp-dow';
      h.textContent = d;
      ddpGrid.appendChild(h);
    });

    const first = new Date(ddpY, ddpM, 1);
    const startDow = (first.getDay() + 6) % 7;
    const daysInMonth = new Date(ddpY, ddpM + 1, 0).getDate();
    const prevDays = new Date(ddpY, ddpM, 0).getDate();
    const today = new Date();

    for (let i = 0; i < startDow; i++) {
      const btn = document.createElement('button');
      btn.className = 'ddp-day other-month';
      btn.textContent = prevDays - startDow + 1 + i;
      ddpGrid.appendChild(btn);
    }
    for (let d = 1; d <= daysInMonth; d++) {
      const btn = document.createElement('button');
      btn.className = 'ddp-day';
      btn.textContent = d;
      if (today.getFullYear()===ddpY && today.getMonth()===ddpM && today.getDate()===d)
        btn.classList.add('today');
      if (ddpSelected && ddpSelected.y===ddpY && ddpSelected.m===ddpM && ddpSelected.d===d)
        btn.classList.add('selected');
      btn.addEventListener('click', e => {
        e.stopPropagation();
        ddpSelected = {y: ddpY, m: ddpM, d};
        ddpPicker.classList.remove('open');
        if (window.__ddpOnSelect) window.__ddpOnSelect(new Date(ddpY, ddpM, d));
      });
      ddpGrid.appendChild(btn);
    }
    const cells = startDow + daysInMonth;
    const remaining = cells % 7 === 0 ? 0 : 7 - (cells % 7);
    for (let i = 1; i <= remaining; i++) {
      const btn = document.createElement('button');
      btn.className = 'ddp-day other-month';
      btn.textContent = i;
      ddpGrid.appendChild(btn);
    }
  }

  ddpTitleBtn.addEventListener('click', e => {
    e.stopPropagation();
    const isOpen = ddpPicker.classList.contains('open');
    ddpPicker.classList.toggle('open', !isOpen);
    if (!isOpen) renderDDP();
  });
  document.getElementById('ddpYearPrev') .addEventListener('click', e => { e.stopPropagation(); ddpY--; renderDDP(); });
  document.getElementById('ddpYearNext') .addEventListener('click', e => { e.stopPropagation(); ddpY++; renderDDP(); });
  document.getElementById('ddpMonthPrev').addEventListener('click', e => { e.stopPropagation(); if (--ddpM < 0) { ddpM=11; ddpY--; } renderDDP(); });
  document.getElementById('ddpMonthNext').addEventListener('click', e => { e.stopPropagation(); if (++ddpM > 11) { ddpM=0; ddpY++; } renderDDP(); });
  document.addEventListener('click', () => ddpPicker.classList.remove('open'));
  ddpPicker.addEventListener('click', e => e.stopPropagation());

  window.__initDayPicker = function(onSelect) {
    window.__ddpOnSelect = onSelect;
  };
  window.__syncDayPicker = function(date) {
    ddpY = date.getFullYear();
    ddpM = date.getMonth();
    ddpSelected = {y: ddpY, m: ddpM, d: date.getDate()};
  };
  window.__parseEvData = __parseEvData;
})();
</script>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/agenda/_dia.blade.php ENDPATH**/ ?>