{{-- ============================================================
     AGENDA / _mes.blade.php
     Vista Mes: CSS + HTML + JS
     ============================================================ --}}

<style>
.cal-wrap{display:none;max-height:480px;overflow-y:auto;border-radius:8px;min-width:0}
.cal-wrap.active{display:block;width:100%}
.agenda-left.expanded .cal-wrap{max-height:none;overflow:visible;width:100%}
.agenda-left.expanded .cal-grid{min-width:100%}
.cal-grid{width:100%;border-collapse:separate;border-spacing:0;display:table;table-layout:fixed}
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
.cal-event{border-radius:5px;padding:3px 6px;font-size:10px;font-weight:600;line-height:1.2;margin-bottom:3px;cursor:pointer;transition:opacity 150ms ease;min-width:0;word-wrap:break-word;hyphens:auto}
.cal-event:hover{opacity:.8}
.ce-line1{font-weight:700;line-height:1.2}
.ce-line2{font-size:9px;opacity:.9;line-height:1.2;margin-top:1px}
.cal-event.ev-done{background:linear-gradient(to bottom,#042226 20%,#4C9242 80%);color:#fff;border:1.38px solid #284D23}
.cal-event.ev-wait{background:linear-gradient(to bottom,#351909 29%,#9B491A 100%);color:#fff;border:1.24px solid #E75D01}
.cal-event.ev-cancel{background:linear-gradient(to bottom,#251117 38%,#D90000 100%);color:#fff;border:1.27px solid #D90000}
.cal-event.ev-soon{background:linear-gradient(to bottom,#0B1331 43%,#B263FF 100%);color:#fff;border:1.27px solid #B263FF}
.cal-more-btn{display:block;width:100%;margin-top:2px;padding:3px 5px;font-size:9px;font-weight:600;color:#8FA3CF;background:transparent;border:1.5px dashed rgba(110,160,255,.4);border-radius:4px;cursor:pointer;transition:all 150ms ease;text-align:center}
.cal-more-btn:hover{color:#EAF1FF;background:rgba(110,160,255,.15);border-color:rgba(110,160,255,.6)}
html[data-theme="light"] .cal-grid th{background:linear-gradient(to bottom,#DDEAF8 30%,#B3D0F0 100%);color:#2E5CAA;border-bottom-color:rgba(20,50,120,.15)}
html[data-theme="light"] .cal-grid td{border-color:rgba(20,50,120,.08)}
html[data-theme="light"] .day-num{color:#5B6A99}
html[data-theme="light"] .cal-grid td.off-month .day-num{color:#C2CCE8}
html[data-theme="light"] .cal-event.ev-done{background:#EBF7EA;color:#1B4518;border:1.38px solid #4C9242;box-shadow:none}
html[data-theme="light"] .cal-event.ev-wait{background:#FEF3E7;color:#7A2F00;border:1.24px solid #E75D01;box-shadow:none}
html[data-theme="light"] .cal-event.ev-cancel{background:#FDE8E8;color:#6B0000;border:1.27px solid #D90000;box-shadow:none}
html[data-theme="light"] .cal-event.ev-soon{background:#F3ECFF;color:#4A1A8A;border:1.27px solid #B263FF;box-shadow:none}
@media(max-width:720px){
  .cal-event{font-size:9.5px;padding:2px 5px}
  .day-num{font-size:11px;width:20px;height:20px}
  .ce-line2{font-size:8px}
}
</style>

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

<script>
(function(){
  function _recomputeClass(ev, dateKey) {
    const now = new Date();
    const [y, m, d] = dateKey.split('-').map(Number);
    const timeStr = ev.hora || (ev.h ? String(ev.h).padStart(2, '0') + ':00' : '00:00');
    const [h, min] = timeStr.split(':').map(Number);
    const start = new Date(y, m - 1, d, h || 0, min || 0);
    const waitStart = new Date(start.getTime() - 15 * 60000);
    const cancelStart = new Date(start.getTime() + 5 * 60000);

    if (ev.cls === 'ev-done' || ev.cls === 'ev-cancel' || ev.cls === 'ev-block') return ev.cls;
    if (now >= cancelStart) return 'ev-cancel';
    if (ev.cls === 'ev-wait') return 'ev-wait';
    if (now >= waitStart) return 'ev-wait';
    return 'ev-soon';
  }

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
        const MAX_VISIBLE = 2;
        const DIAS_MES = ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];

        evs.slice(0, MAX_VISIBLE).forEach(ev => {
          const liveCls = _recomputeClass(ev, key);
          const div = document.createElement('div');
          div.className = 'cal-event ' + liveCls;

          const name = ev.name || 'Paciente';
          const proc = ev.proc || 'Procedimiento';
          const displayName = window.__displayName ? window.__displayName(name) : name;
          const timeM = ev.t ? ev.t.match(/^(\d+:\d+)/) : null;

          div.dataset.name = name;
          div.dataset.proc = proc;
          div.dataset.cls = liveCls;
          div.dataset.time = timeM ? timeM[1] : (ev.hora || (ev.h ? String(ev.h).padStart(2,'0') + ':00' : ''));
          div.dataset.duration = ev.duracion || '60';
          div.dataset.citaId = ev.id || '';
          div.dataset.pacienteId = ev.paciente_id || '';
          div.dataset.deleteUrl = ev.delete_url || '';
          div.dataset.estado = ev.estado || '';
          div.dataset.estadoUrl = ev.estado_url || '';

          div.innerHTML = `<div class="ce-line1">${displayName}</div><div class="ce-line2">${proc}</div>`;

          td.appendChild(div);
        });

        if (evs.length > MAX_VISIBLE) {
          const moreBtn = document.createElement('button');
          moreBtn.className = 'cal-more-btn';
          moreBtn.textContent = `+${evs.length - MAX_VISIBLE} más`;
          moreBtn.addEventListener('click', e => {
            e.stopPropagation();
            if (window.openWeekModal) {
              const dayName = DIAS_MES[cellDate.getDay()];
              window.openWeekModal(evs, cellDate, '', dayName);
            }
          });
          td.appendChild(moreBtn);
        }

        if (isCurMonth || evs.length) hasContent = true;

        tr.appendChild(td);
        day++;
      }

      tbody.appendChild(tr);

      if (row >= 4 && !hasContent) {
        tbody.removeChild(tr);
        break;
      }
    }

    const daysInMonth = new Date(y, m + 1, 0).getDate();
    const keys = [];
    for (let d = 1; d <= daysInMonth; d++) {
      keys.push(`${y}-${m+1}-${d}`);
    }

    updateSumCards(countEvents(keys));
  };

  window.__recomputeClass = _recomputeClass;
})();
</script>
