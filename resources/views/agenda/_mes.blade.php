{{-- ============================================================
     AGENDA / _mes.blade.php
     Vista Mes: CSS + HTML + JS
     ============================================================ --}}

{{-- ---- CSS ---- --}}
<style>
.cal-wrap{display:none;max-height:480px;overflow-y:auto;border-radius:8px}
.cal-wrap.active{display:block}
.cal-grid{width:100%;border-collapse:separate;border-spacing:0;display:table}
.cal-grid th{
  text-align:center;font-size:12px;font-weight:600;color:#8FA3CF;padding:8px 4px;
  background:linear-gradient(to bottom,#001525 30%,#004F8B 100%);
  border-bottom:1px solid var(--stroke);
  position:sticky;top:0;z-index:2;
}
.cal-grid thead tr th:first-child{border-radius:8px 0 0 0}
.cal-grid thead tr th:last-child{border-radius:0 8px 0 0}
.cal-grid td{vertical-align:top;width:calc(100%/7);border:1px solid rgba(110,160,255,.07);padding:6px 5px;min-height:90px}
.cal-grid td.off-month .day-num{color:var(--off)}
.cal-grid td.today-cell .day-num{background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;box-shadow:0 3px 10px -2px rgba(46,123,246,.7)}
.day-num{display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;font-size:12px;font-weight:600;margin-bottom:5px;color:var(--txt-soft)}
.cal-event{border-radius:5px;padding:3px 6px;font-size:10.5px;font-weight:600;line-height:1.3;margin-bottom:3px;cursor:pointer;transition:opacity 150ms ease;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-width:0}
.cal-event:hover{opacity:.8}
.cal-event.ev-done{background:linear-gradient(to bottom,#042226 20%,#4C9242 80%);color:#fff;border:1.38px solid #284D23;box-shadow:inset 0 0 0 1.38px rgba(0,0,0,.3);display:flex;align-items:center;gap:4px}
.cal-event.ev-done::before{content:'';display:inline-block;width:10px;height:10px;flex:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234C9242' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M20 6L9 17l-5-5'/%3E%3C/svg%3E");background-size:contain;background-repeat:no-repeat;background-position:center}
.cal-event.ev-wait{background:linear-gradient(to bottom,#351909 29%,#9B491A 100%);color:#fff;border:1.24px solid #E75D01;box-shadow:inset 0 0 0 1.24px rgba(0,0,0,.3);display:flex;align-items:center;gap:4px}
.cal-event.ev-wait::before{content:'';display:inline-block;width:10px;height:10px;flex:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E75D01' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cpolyline points='12 6 12 12 16 14'/%3E%3C/svg%3E");background-size:contain;background-repeat:no-repeat;background-position:center}
.cal-event.ev-cancel{background:linear-gradient(to bottom,#251117 38%,#D90000 100%);color:#fff;border:1.27px solid #D90000;box-shadow:inset 0 0 0 1.27px rgba(6,6,6,.20);display:flex;align-items:center;gap:4px}
.cal-event.ev-cancel::before{content:'';display:inline-block;width:10px;height:10px;flex:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23D90000' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cline x1='15' y1='9' x2='9' y2='15'/%3E%3Cline x1='9' y1='9' x2='15' y2='15'/%3E%3C/svg%3E");background-size:contain;background-repeat:no-repeat;background-position:center}
.cal-event.ev-soon{background:linear-gradient(to bottom,#0B1331 43%,#B263FF 100%);color:#fff;border:1.27px solid #B263FF;box-shadow:inset 0 0 0 1.27px rgba(6,6,6,.20);display:flex;align-items:center;gap:4px}
.cal-event.ev-soon::before{content:'';display:inline-block;width:10px;height:10px;flex:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23B263FF' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");background-size:contain;background-repeat:no-repeat;background-position:center}

/* Tema claro */
html[data-theme="light"] .cal-grid th{background:linear-gradient(to bottom,#DDEAF8 30%,#B3D0F0 100%);color:#2E5CAA;border-bottom-color:rgba(20,50,120,.15)}
html[data-theme="light"] .cal-grid td{border-color:rgba(20,50,120,.08)}
html[data-theme="light"] .day-num{color:#5B6A99}
html[data-theme="light"] .cal-grid td.off-month .day-num{color:#C2CCE8}
html[data-theme="light"] .cal-event.ev-done{background:#EBF7EA;color:#1B4518;border:1.38px solid #4C9242;box-shadow:none}
html[data-theme="light"] .cal-event.ev-done::before{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234C9242' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M20 6L9 17l-5-5'/%3E%3C/svg%3E")}
html[data-theme="light"] .cal-event.ev-wait{background:#FEF3E7;color:#7A2F00;border:1.24px solid #E75D01;box-shadow:none}
html[data-theme="light"] .cal-event.ev-wait::before{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E75D01' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cpolyline points='12 6 12 12 16 14'/%3E%3C/svg%3E")}
html[data-theme="light"] .cal-event.ev-cancel{background:#FDE8E8;color:#6B0000;border:1.27px solid #D90000;box-shadow:none}
html[data-theme="light"] .cal-event.ev-cancel::before{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23D90000' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cline x1='15' y1='9' x2='9' y2='15'/%3E%3Cline x1='9' y1='9' x2='15' y2='15'/%3E%3C/svg%3E")}
html[data-theme="light"] .cal-event.ev-soon{background:#F3ECFF;color:#4A1A8A;border:1.27px solid #B263FF;box-shadow:none}
html[data-theme="light"] .cal-event.ev-soon::before{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23B263FF' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E")}

/* ---- Responsive cal-event ---- */
@media(max-width:720px){
  .cal-event{font-size:9.5px;padding:2px 5px;gap:3px}
  .cal-event::before{width:8px;height:8px}
  .day-num{font-size:11px;width:20px;height:20px}
}
@media(max-width:540px){
  .cal-event{font-size:8.5px;padding:2px 4px;gap:2px}
  .cal-event::before{display:none}
  .day-num{font-size:10px;width:18px;height:18px;margin-bottom:3px}
  .cal-grid td{padding:4px 3px;min-height:70px}
}
@media(max-width:420px){
  .cal-event{font-size:8px;padding:1px 3px}
  .cal-grid td{padding:3px 2px;min-height:60px}
}
</style>

{{-- ---- HTML ---- --}}
<div class="cal-wrap active" id="calWrap">
<table class="cal-grid">
  <thead>
    <tr>
      <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th>
    </tr>
  </thead>
  <tbody id="calBody"></tbody>
</table>
</div>

{{-- ---- JS ---- --}}
<script>
(function(){
  window.__buildCal = function(date, EVENTS, MESES, updateSumCards, countEvents) {
    const y = date.getFullYear();
    const m = date.getMonth();
    document.getElementById('mesActual').textContent = MESES[m];
    document.getElementById('anioActual').textContent = y;
    const today = new Date();
    let startDow = new Date(y, m, 1).getDay();
    startDow = startDow === 0 ? 6 : startDow - 1;
    const tbody = document.getElementById('calBody');
    tbody.innerHTML = '';
    let day = 1 - startDow;
    for (let row = 0; row < 6; row++) {
      let hasContent = false;
      const tr = document.createElement('tr');
      for (let col = 0; col < 7; col++) {
        const td = document.createElement('td');
        const cellDate = new Date(y, m, day);
        const isCurMonth = cellDate.getMonth() === m;
        const isToday = cellDate.toDateString() === today.toDateString();
        if (!isCurMonth) td.classList.add('off-month');
        if (isToday) td.classList.add('today-cell');
        const dn = document.createElement('div');
        dn.className = 'day-num';
        dn.textContent = cellDate.getDate();
        td.appendChild(dn);
        const key = `${cellDate.getFullYear()}-${cellDate.getMonth()+1}-${cellDate.getDate()}`;
        const evs = EVENTS[key] || [];
        evs.forEach(ev => {
          const div = document.createElement('div');
          div.className = 'cal-event ' + ev.cls;
          div.textContent = ev.t;
          td.appendChild(div);
        });
        if (isCurMonth || evs.length) hasContent = true;
        tr.appendChild(td);
        day++;
      }
      tbody.appendChild(tr);
      if (row >= 4 && !hasContent) { tbody.removeChild(tr); break; }
    }
    const daysInMonth = new Date(y, m + 1, 0).getDate();
    const keys = [];
    for (let d = 1; d <= daysInMonth; d++) keys.push(`${y}-${m+1}-${d}`);
    updateSumCards(countEvents(keys));
  };
})();
</script>
