{{-- ============================================================
     AGENDA / _semana.blade.php
     Vista Semana: CSS + HTML + JS
     ============================================================ --}}

{{-- ---- CSS ---- --}}
<style>
.week-grid{display:none;width:100%;overflow-x:auto;max-height:480px;overflow-y:auto}
.week-grid.active{display:block}
.week-table{width:100%;border-collapse:collapse;table-layout:fixed}
.week-table thead tr th{
  text-align:center;font-size:10.5px;font-weight:600;color:#8FA3CF;padding:5px 3px;
  background:linear-gradient(to bottom,#001525 30%,#004F8B 100%);
  border-bottom:1px solid var(--stroke);
  position:sticky;top:0;z-index:2;
}
.week-table thead tr th:first-child{border-radius:8px 0 0 0;width:38px}
.week-table thead tr th:last-child{border-radius:0 8px 0 0}
.week-table th.wk-today{color:#EAF1FF !important;background:linear-gradient(to bottom,#0A2A5E 30%,#1668D9 100%) !important}
.week-table .hr-label{font-size:9.5px;color:var(--txt-soft);text-align:right;padding:0 5px 0 0;width:38px;vertical-align:top;padding-top:3px;border-right:1px solid rgba(110,160,255,.1);white-space:nowrap}
.week-table td.wk-cell{vertical-align:top;border:1px solid rgba(110,160,255,.06);padding:2px 3px;height:40px;position:relative}
.week-table td.wk-cell.wk-today-col{background:rgba(22,139,217,.05)}
.wk-event{border-radius:4px;padding:2px 5px;font-size:9.5px;font-weight:600;line-height:1.3;margin-bottom:1px;cursor:pointer;transition:opacity 150ms ease;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;display:flex;align-items:center;gap:3px}
.wk-event:hover{opacity:.8}
.wk-event.ev-done{background:linear-gradient(to bottom,#042226 20%,#4C9242 80%);color:#fff;border:1.38px solid #284D23}
.wk-event.ev-wait{background:linear-gradient(to bottom,#351909 29%,#9B491A 100%);color:#fff;border:1.24px solid #E75D01}
.wk-event.ev-cancel{background:linear-gradient(to bottom,#251117 38%,#D90000 100%);color:#fff;border:1.27px solid #D90000}
.wk-event.ev-soon{background:linear-gradient(to bottom,#0B1331 43%,#B263FF 100%);color:#fff;border:1.27px solid #B263FF}

/* Tema claro */
html[data-theme="light"] .week-table thead tr th{background:linear-gradient(to bottom,#DDEAF8 30%,#B3D0F0 100%);color:#2E5CAA;border-bottom-color:rgba(20,50,120,.15)}
html[data-theme="light"] .week-table th.wk-today{color:#fff !important;background:linear-gradient(to bottom,#1668D9,#2E7BF6) !important}
html[data-theme="light"] .week-table .hr-label{color:#5B6A99;border-right-color:rgba(20,50,120,.1)}
html[data-theme="light"] .week-table td.wk-cell{border-color:rgba(20,50,120,.07)}
html[data-theme="light"] .week-table td.wk-cell.wk-today-col{background:rgba(46,123,246,.05)}
html[data-theme="light"] .wk-event.ev-done{background:#EBF7EA;color:#1B4518;border-color:#4C9242}
html[data-theme="light"] .wk-event.ev-wait{background:#FEF3E7;color:#7A2F00;border-color:#E75D01}
html[data-theme="light"] .wk-event.ev-cancel{background:#FDE8E8;color:#6B0000;border-color:#D90000}
html[data-theme="light"] .wk-event.ev-soon{background:#F3ECFF;color:#4A1A8A;border-color:#B263FF}
</style>

{{-- ---- HTML ---- --}}
<div class="week-grid" id="weekGrid">
  <table class="week-table">
    <thead id="weekHead"></thead>
    <tbody id="weekBody"></tbody>
  </table>
</div>

{{-- ---- JS ---- --}}
<script>
(function(){
  window.__getMondayOf = function(date) {
    const d = new Date(date);
    const dow = d.getDay() === 0 ? 6 : d.getDay() - 1;
    d.setDate(d.getDate() - dow);
    d.setHours(0,0,0,0);
    return d;
  };

  window.__buildWeek = function(date, EVENTS, MESES, DIAS_CORTO, updateSumCards, countEvents) {
    const monday = window.__getMondayOf(date);
    const today  = new Date();
    const HOURS  = [8,9,10,11,12,13,14,15,16,17,18,19,20,21];

    const thead = document.getElementById('weekHead');
    thead.innerHTML = '';
    const headTr = document.createElement('tr');
    const thHora = document.createElement('th');
    thHora.textContent = 'Hora';
    headTr.appendChild(thHora);

    const weekDays = [];
    for (let i = 0; i < 7; i++) {
      const d = new Date(monday);
      d.setDate(monday.getDate() + i);
      weekDays.push(d);
      const th = document.createElement('th');
      if (d.toDateString() === today.toDateString()) th.classList.add('wk-today');
      th.textContent = DIAS_CORTO[i] + ' ' + d.getDate();
      headTr.appendChild(th);
    }
    thead.appendChild(headTr);

    document.getElementById('mesActual').textContent = MESES[monday.getMonth()];
    document.getElementById('anioActual').textContent = monday.getFullYear();

    const weekKeys = weekDays.map(d => `${d.getFullYear()}-${d.getMonth()+1}-${d.getDate()}`);
    updateSumCards(countEvents(weekKeys));

    const tbody = document.getElementById('weekBody');
    tbody.innerHTML = '';
    HOURS.forEach(hr => {
      const tr = document.createElement('tr');
      const tdHr = document.createElement('td');
      tdHr.className = 'hr-label';
      tdHr.textContent = hr + ':00';
      tr.appendChild(tdHr);
      weekDays.forEach(d => {
        const td = document.createElement('td');
        td.className = 'wk-cell';
        if (d.toDateString() === today.toDateString()) td.classList.add('wk-today-col');
        const key = `${d.getFullYear()}-${d.getMonth()+1}-${d.getDate()}`;
        (EVENTS[key] || []).filter(ev => ev.h === hr).forEach(ev => {
          const div = document.createElement('div');
          div.className = 'wk-event ' + ev.cls;
          div.textContent = ev.t;
          td.appendChild(div);
        });
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });
  };
})();
</script>
