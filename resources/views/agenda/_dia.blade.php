{{-- ============================================================
     AGENDA / _dia.blade.php
     Vista Día: CSS + HTML + JS
     ============================================================ --}}

{{-- ---- CSS ---- --}}
<style>
.day-view{display:none;gap:14px}
.day-view.active{display:grid;grid-template-columns:1fr 260px}
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
.ddp-nav-btn{width:24px;height:24px;border-radius:6px;border:none;background:transparent;color:#fff;cursor:pointer;display:grid;place-items:center;transition:background 120ms,color 120ms}
.ddp-nav-btn:hover{background:rgba(22,139,217,.25);color:#EAF1FF}
.ddp-title{font-family:'Sora',sans-serif;font-size:13px;font-weight:700;color:#fff;text-align:center;flex:1}
.ddp-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-top:2px}
.ddp-dow{font-size:10px;font-weight:700;color:rgba(255,255,255,.7);text-align:center;padding:4px 0 8px;letter-spacing:.03em}
.ddp-day{height:36px;border-radius:8px;border:none;background:transparent;color:#fff;font-size:13px;font-weight:500;cursor:pointer;transition:background 120ms,color 120ms;display:grid;place-items:center}
.ddp-day:hover{background:rgba(46,100,200,.28);color:#EAF1FF}
.ddp-day.today{color:#5AB4F7;font-weight:700}
.ddp-day.selected{background:linear-gradient(135deg,#1668D9,#0040A0);color:#fff;font-weight:700;box-shadow:0 4px 14px -4px rgba(22,104,217,.55)}
.ddp-day.other-month{opacity:.35;color:#fff}
.ddp-label{font-family:'Sora',sans-serif;font-size:11px;font-weight:600;color:#fff;margin-bottom:10px;text-align:center;letter-spacing:.05em;text-transform:uppercase}
.day-schedule{display:flex;flex-direction:column;gap:0;max-height:480px;overflow-y:auto}
.day-row{display:grid;grid-template-columns:52px 1fr;border-bottom:1px solid rgba(110,160,255,.07);min-height:68px}
.day-hour{font-size:11px;color:var(--txt-soft);display:flex;align-items:flex-start;padding:10px 8px 0 0;justify-content:flex-end;white-space:nowrap;border-right:1px solid rgba(110,160,255,.1)}
.day-slot{padding:6px 12px 6px 10px;display:flex;flex-direction:column;gap:4px}
.day-event{display:grid;grid-template-columns:42px 1fr auto auto;align-items:center;gap:10px;background:#001525;border:1.5px solid rgba(22,139,217,.7);box-shadow:0 0 0 1px rgba(22,139,217,.18),0 2px 12px rgba(22,139,217,.1);border-radius:9px;padding:9px 14px;cursor:pointer;transition:opacity 150ms ease}
.day-event:hover{opacity:.85}
.day-event-thumb{width:42px;height:42px;border-radius:8px;flex:none}
.day-event-thumb.ev-done  {background:linear-gradient(to bottom,#042226 20%,#4C9242 80%);border:1.38px solid #284D23}
.day-event-thumb.ev-wait  {background:linear-gradient(to bottom,#351909 29%,#9B491A 100%);border:1.24px solid #E75D01}
.day-event-thumb.ev-cancel{background:linear-gradient(to bottom,#251117 38%,#D90000 100%);border:1.27px solid #D90000}
.day-event-thumb.ev-soon  {background:linear-gradient(to bottom,#0B1331 43%,#B263FF 100%);border:1.27px solid #B263FF}
.day-event-thumb.ev-block {width:42px;height:42px;border-radius:8px;background:rgba(110,160,255,.15);display:flex;align-items:center;justify-content:center;flex:none}
.day-event.ev-block{grid-template-columns:42px 1fr;border-color:rgba(110,160,255,.35);box-shadow:0 0 0 1px rgba(110,160,255,.1),0 2px 10px rgba(110,160,255,.06)}
.day-event-info strong{display:block;font-size:13px;font-weight:700;color:#EAF1FF;line-height:1.3}
.day-event-info span{display:block;font-size:11px;color:rgba(234,241,255,.55)}
.day-event-status{font-size:12px;font-weight:700;white-space:nowrap}
.day-event-status.ev-done  {color:#4C9242}
.day-event-status.ev-wait  {color:#E75D01}
.day-event-status.ev-cancel{color:#D90000}
.day-event-status.ev-soon  {color:#B263FF}
.day-event-status.ev-block {color:var(--txt-soft)}
.day-event-icon{width:24px;height:24px;flex:none;opacity:.8}
.day-panel{display:flex;flex-direction:column;gap:12px;overflow-y:auto;max-height:600px}
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
  .day-view.active{grid-template-columns:1fr}
  .day-panel{display:none}
  .day-nav-bar{gap:4px}
  .day-nav-btn{padding:7px 10px;font-size:11.5px;flex:none;white-space:nowrap}
  .day-nav-title{padding:0 2px;flex:1;min-width:0;text-align:center}
  .day-title-btn{font-size:clamp(10px,2.8vw,13px);padding:6px 10px;gap:5px;width:100%;justify-content:center}
  .day-schedule{max-height:420px}
}
@media(max-width:600px){
  .day-nav-btn{padding:6px 10px;font-size:11px}
  .day-title-btn{font-size:12px;padding:5px 8px;gap:5px}
  .day-hour{font-size:10.5px;padding:8px 6px 0 0}
  .day-schedule{max-height:380px}
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
html[data-theme="light"] .day-hour{color:#5B6A99;border-right-color:rgba(20,50,120,.1)}
html[data-theme="light"] .day-row{border-bottom-color:rgba(20,50,120,.07)}
html[data-theme="light"] .day-event{background:#F6F8FE;border-color:rgba(20,50,120,.45);box-shadow:0 0 0 1px rgba(20,50,120,.08),0 2px 10px rgba(20,50,120,.06)}
html[data-theme="light"] .day-event-info strong{color:#0E1530}
html[data-theme="light"] .day-event-info span{color:rgba(14,21,48,.5)}
html[data-theme="light"] .day-event-thumb.ev-done  {background:#EBF7EA;border-color:#4C9242}
html[data-theme="light"] .day-event-thumb.ev-wait  {background:#FEF3E7;border-color:#E75D01}
html[data-theme="light"] .day-event-thumb.ev-cancel{background:#FDE8E8;border-color:#D90000}
html[data-theme="light"] .day-event-thumb.ev-soon  {background:#F3ECFF;border-color:#B263FF}
html[data-theme="light"] .day-event-thumb.ev-block {background:rgba(20,50,120,.12)}
html[data-theme="light"] .day-event.ev-block{border-color:rgba(20,50,120,.25);box-shadow:0 0 0 1px rgba(20,50,120,.06),0 2px 40px rgba(20,50,120,.05)}
html[data-theme="light"] .day-panel-card{background:#F0F5FF;border-color:rgba(20,50,120,.15)}
html[data-theme="light"] .day-panel-title{color:#0E1530}
html[data-theme="light"] .day-pc-name{color:#0E1530}
html[data-theme="light"] .day-pc-age{color:rgba(14,21,48,.5)}
html[data-theme="light"] .day-pc-info{color:rgba(14,21,48,.7)}
html[data-theme="light"] .day-pc-info b{color:#0E1530}
</style>

{{-- ---- HTML ---- --}}
<div class="day-view" id="dayView">
  <div class="day-left">
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
    <div class="day-schedule" id="daySchedule"></div>
  </div>
  <div class="day-panel" id="dayPanel">
    <div class="day-panel-title">Pacientes</div>
  </div>
</div>

{{-- ---- JS ---- --}}
<script>
(function(){
  const STATUS_ICONS_SVG = {
    'ev-done':   `<svg viewBox="0 0 24 24" fill="none" stroke="#4C9242" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`,
    'ev-wait':   `<svg viewBox="0 0 24 24" fill="none" stroke="#E75D01" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
    'ev-cancel': `<svg viewBox="0 0 24 24" fill="none" stroke="#D90000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,
    'ev-soon':   `<svg viewBox="0 0 24 24" fill="none" stroke="#B263FF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>`,
  };
  const STATUS_LABEL_DAY = {
    'ev-done':'Completado','ev-wait':'En espera','ev-cancel':'Cancelado','ev-soon':'Próximamente','ev-block':'',
  };
  const DAY_BUTTONS = {
    'ev-done':  [{label:'Datos del paciente',cls:'primary'},{label:'Ver Informe',cls:'secondary'},{label:'Enviar mensaje',cls:'secondary'}],
    'ev-wait':  [{label:'Iniciar Estudio',cls:'primary'},{label:'Datos del Paciente',cls:'secondary'},{label:'Enviar mensaje',cls:'secondary'}],
    'ev-cancel':[{label:'Reprogramar Paciente',cls:'primary'},{label:'Datos del Paciente',cls:'secondary'},{label:'Enviar mensaje',cls:'secondary'}],
    'ev-soon':  [{label:'Reprogramar Paciente',cls:'primary'},{label:'Datos del Paciente',cls:'secondary'},{label:'Enviar mensaje',cls:'secondary'}],
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
    document.getElementById('mesActual').textContent = MESES[m];
    document.getElementById('anioActual').textContent = y;

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
      hourEl.textContent = hr + ':00';
      row.appendChild(hourEl);
      const slot = document.createElement('div');
      slot.className = 'day-slot';

      dayEvs.filter(ev => ev.h === hr).forEach(ev => {
        const text = ev.t.trim();
        const timeM = text.match(/^(\d+:\d+)/);
        const time = timeM ? timeM[1] : '';
        const rest = text.replace(/^\d+:\d+\s*/,'');
        const parts = rest.split('·').map(s=>s.trim());
        const name = parts[0] || 'Paciente';
        const proc = parts[1] || 'Procedimiento';
        const inits = name.split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();

        const card = document.createElement('div');
        card.className = 'day-event ' + ev.cls;
        card.dataset.evcls = ev.cls;
        card.dataset.name = name;
        card.dataset.proc = proc;
        card.dataset.time = time;
        card.dataset.inits = inits;
        card.dataset.fechatxt = `${dayNames[dow]} ${d} de ${MESES[m]}`;
        if (ev.cls === 'ev-block' && ev.blockId !== undefined) {
          card.dataset.blockid    = ev.blockId;
          card.dataset.blockkey   = key;
          card.dataset.blocklabel = name;
        }

        const thumb = document.createElement('div');
        thumb.className = 'day-event-thumb ' + ev.cls;

        if (ev.cls === 'ev-block') {
          thumb.innerHTML = `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#8FA3CF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>`;
          const info2 = document.createElement('div');
          info2.className = 'day-event-info';
          info2.innerHTML = `<strong>Bloqueo de Tiempo</strong><span>${name.replace(/^\d+:\d+\s*/,'')}</span>`;
          card.appendChild(thumb); card.appendChild(info2);
        } else {
          const info = document.createElement('div');
          info.className = 'day-event-info';
          info.innerHTML = `<strong>${name}</strong><span>${proc}</span>`;
          const status = document.createElement('div');
          status.className = 'day-event-status ' + ev.cls;
          status.textContent = STATUS_LABEL_DAY[ev.cls] || '';
          const icon = document.createElement('div');
          icon.className = 'day-event-icon';
          icon.innerHTML = STATUS_ICONS_SVG[ev.cls] || '';
          card.appendChild(thumb); card.appendChild(info);
          card.appendChild(status); card.appendChild(icon);
        }
        slot.appendChild(card);
      });
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
      const text = ev.t.trim();
      const timeM = text.match(/^(\d+:\d+)/);
      const time = timeM ? timeM[1] : '';
      const rest = text.replace(/^\d+:\d+\s*/,'');
      const parts = rest.split('·').map(s=>s.trim());
      const name = parts[0] || 'Paciente';
      const proc = parts[1] || 'Procedimiento';
      const inits = name.split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();
      const endHr = parseInt(time) + 1;

      const card = document.createElement('div');
      card.className = 'day-panel-card';
      const head = document.createElement('div');
      head.className = 'day-pc-head';
      head.innerHTML = `<div class="day-pc-avatar">${inits}</div><div><div class="day-pc-name">${name}</div></div>`;
      const info = document.createElement('div');
      info.className = 'day-pc-info';
      info.innerHTML =
        `<b>Motivo:</b> ${proc}<br>` +
        `<b>Fecha:</b> ${dayNames[dow]} ${d} de ${MESES[m]}<br>` +
        `<b>Tiempo:</b> ${time} AM – ${endHr}:00 PM<br>` +
        `<b>Habitación:</b> Sala 3`;
      card.appendChild(head);
      card.appendChild(info);
      (DAY_BUTTONS[ev.cls] || []).forEach((b,i) => {
        const btn = document.createElement('button');
        btn.className = 'ev-pop-btn ' + b.cls;
        btn.style.marginBottom = i < (DAY_BUTTONS[ev.cls].length-1) ? '6px' : '0';
        btn.textContent = b.label;
        card.appendChild(btn);
      });
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
})();
</script>
