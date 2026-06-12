{{-- ============================================================
     AGENDAR / _calendario.blade.php
     Paso 3: Selección de fecha y hora con horarios disponibles
     ============================================================ --}}
<style>
/* Mini calendario */
.cal-ag-wrap{
  display:flex;flex-direction:column;gap:14px;
  background:linear-gradient(to bottom,rgba(255,255,255,.60) 0%,rgba(255,255,255,.10) 100%);
  backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);
  border:1.5px solid rgba(255,255,255,.25);
  border-radius:16px;
  padding:16px 14px 14px;
  box-shadow:0 16px 48px rgba(0,0,0,.45);
}
.cal-ag-nav-header{
  display:grid;grid-template-columns:1fr 1fr;gap:8px;
  padding-bottom:10px;
  border-bottom:1px solid rgba(255,255,255,.15);
}
.cal-ag-nav{display:flex;align-items:center;justify-content:space-between;gap:4px}
.cal-ag-nav-group{
  display:flex;align-items:center;justify-content:space-between;
  background:rgba(255,255,255,.1);border-radius:10px;padding:5px 8px;
}
.cal-ag-nav-btn{width:24px;height:24px;border-radius:6px;border:none;background:transparent;color:#fff;cursor:pointer;display:grid;place-items:center;transition:background 120ms,color 120ms}
.cal-ag-nav-btn:hover{background:rgba(22,139,217,.25);color:#fff}
.cal-ag-nav-title{font-family:'Sora',sans-serif;font-size:13px;font-weight:700;color:#fff;text-align:center;flex:1}
.cal-ag-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-top:6px}
.cal-ag-dow{font-size:10px;font-weight:700;color:rgba(255,255,255,.7);text-align:center;padding:4px 0 8px;letter-spacing:.03em}
.cal-ag-day{height:36px;border-radius:8px;border:none;background:transparent;color:#fff;font-size:13px;font-weight:500;cursor:pointer;display:grid;place-items:center;transition:background 120ms,color 120ms}
.cal-ag-day:hover{background:rgba(46,100,200,.28);color:#fff}
.cal-ag-day.today{color:#5AB4F7;font-weight:700}
.cal-ag-day.selected{background:linear-gradient(135deg,#1668D9,#0040A0);color:#fff;font-weight:700;box-shadow:0 4px 14px -4px rgba(22,104,217,.55)}
.cal-ag-day.other-month{opacity:.35;color:#fff}
.cal-ag-day.has-events::after{content:'';display:block;width:4px;height:4px;border-radius:50%;background:var(--ag-blue);margin:1px auto 0;position:absolute;bottom:3px;left:50%;transform:translateX(-50%)}
.cal-ag-day{position:relative}

/* Horarios */
.slots-title{font-size:12px;font-weight:700;color:var(--ag-soft);margin-bottom:8px}
.slots-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:7px}
.slot-btn{padding:8px 6px;border-radius:8px;border:1.5px solid transparent;font-size:12px;font-weight:700;cursor:pointer;text-align:center;transition:opacity 150ms ease,border-color 150ms ease;font-family:inherit}
.slot-btn:hover{opacity:.85}
.slot-libre{background:linear-gradient(to bottom,#042226 20%,#4C9242 80%);border:1.38px solid #284D23;box-shadow:inset 0 0 0 1.38px rgba(0,0,0,.3);color:#fff}
.slot-espera{background:linear-gradient(to bottom,#351909 29%,#9B491A 100%);border:1.24px solid #E75D01;box-shadow:inset 0 0 0 1.24px rgba(0,0,0,.3);color:#fff}
.slot-ocupado{background:linear-gradient(to bottom,#251117 38%,#D90000 100%);border:1.27px solid #D90000;box-shadow:inset 0 0 0 1.27px rgba(6,6,6,.20);color:#fff;cursor:not-allowed;opacity:.75}
.slot-btn.active{box-shadow:0 0 0 2px #fff}
.slots-legend{display:flex;align-items:center;gap:14px;margin-top:10px;flex-wrap:wrap}
.legend-item{display:flex;align-items:center;gap:5px;font-size:11px;color:var(--ag-soft)}
.legend-dot{width:8px;height:8px;border-radius:50%;flex:none}
.legend-dot.libre{background:#4C9242}
.legend-dot.espera{background:#E75D01}
.legend-dot.ocupado{background:#D90000}

/* Tema claro */
html[data-theme="light"] .cal-ag-wrap{
  background:linear-gradient(to bottom,rgba(30,80,200,.18) 0%,rgba(30,80,200,.05) 100%);
  border-color:rgba(20,50,120,.2);
  box-shadow:0 12px 40px rgba(20,50,120,.15);
}
html[data-theme="light"] .cal-ag-nav-group{background:rgba(20,50,120,.08)}
html[data-theme="light"] .cal-ag-nav-header{border-bottom-color:rgba(20,50,120,.12)}
html[data-theme="light"] .cal-ag-nav-title{color:#0E1530}
html[data-theme="light"] .cal-ag-nav-btn{color:#2E4A8A}
html[data-theme="light"] .cal-ag-nav-btn:hover{background:rgba(20,50,120,.15);color:#0E1530}
html[data-theme="light"] .cal-ag-dow{color:rgba(14,21,48,.5)}
html[data-theme="light"] .cal-ag-day{color:#0E1530}
html[data-theme="light"] .cal-ag-day:hover{background:rgba(20,50,120,.12);color:#0E1530}
html[data-theme="light"] .cal-ag-day.today{color:#1668D9}
html[data-theme="light"] .cal-ag-day.other-month{color:rgba(14,21,48,.35);opacity:1}
html[data-theme="light"] .slot-libre{background:#EBF7EA;border-color:#4C9242;box-shadow:none;color:#2E6E27}
html[data-theme="light"] .slot-espera{background:#FEF3E7;border-color:#E75D01;box-shadow:none;color:#B84700}
html[data-theme="light"] .slot-ocupado{background:#FDE8E8;border-color:#D90000;box-shadow:none;color:#A80000}
html[data-theme="light"] .slots-title{color:#5B6A99}
html[data-theme="light"] .legend-item{color:#5B6A99}

@media(max-width:540px){
  .slots-grid{grid-template-columns:repeat(2,1fr)}
}
</style>

<div class="ag-card" id="stepCalendario">
  <div class="ag-card-title">
    <span class="ag-step-badge">3</span>
    Selección de Fecha y Hora
  </div>

  <div class="cal-ag-wrap">
    <div class="cal-ag-nav-header">
      <div class="cal-ag-nav-group">
        <button class="cal-ag-nav-btn" id="calAgYearPrev"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
        <span class="cal-ag-nav-title" id="calAgYear"></span>
        <button class="cal-ag-nav-btn" id="calAgYearNext"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>
      </div>
      <div class="cal-ag-nav-group">
        <button class="cal-ag-nav-btn" id="calAgMonthPrev"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
        <span class="cal-ag-nav-title" id="calAgMonth"></span>
        <button class="cal-ag-nav-btn" id="calAgMonthNext"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>
      </div>
    </div>
    <div class="cal-ag-grid" id="calAgGrid"></div>
  </div>

  <div style="margin-top:16px">
    <div class="slots-title" id="slotsTitle">Horarios disponibles</div>
    <div class="slots-grid" id="slotsGrid"></div>
    <div class="slots-legend">
      <div class="legend-item"><span class="legend-dot libre"></span> Disponible</div>
      <div class="legend-item"><span class="legend-dot espera"></span> En espera</div>
      <div class="legend-item"><span class="legend-dot ocupado"></span> Ocupado</div>
    </div>
  </div>
</div>

<script>
(function(){
  const MESES_AG = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
  const DIAS_AG  = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
  const HOURS_AG = [
    {label:'8:00 AM',  h:8},  {label:'9:00 AM',  h:9},  {label:'10:00 AM', h:10},
    {label:'11:00 AM', h:11}, {label:'12:00 PM', h:12}, {label:'1:00 PM',  h:13},
    {label:'2:00 PM',  h:14}, {label:'3:00 PM',  h:15}, {label:'4:00 PM',  h:16},
    {label:'5:00 PM',  h:17}, {label:'6:00 PM',  h:18}, {label:'7:00 PM',  h:19},
    {label:'8:00 PM',  h:20}, {label:'9:00 PM',  h:21}, {label:'10:00 PM', h:22},
  ];

  function getSlotStatus(y, m, d, hour) {
    const EVENTS = (typeof window.__AGENDA_EVENTS !== 'undefined') ? window.__AGENDA_EVENTS : {};
    const key = `${y}-${m+1}-${d}`;
    const dayEvs = EVENTS[key] || [];
    const match  = dayEvs.find(ev => ev.h === hour);
    if (!match) return 'libre';
    if (match.cls === 'ev-wait') return 'espera';
    return 'ocupado';
  }

  let agY = new Date().getFullYear();
  let agM = new Date().getMonth();
  let agSelected = null;
  let selectedSlot = null;

  function renderCalAg() {
    document.getElementById('calAgYear').textContent  = agY;
    document.getElementById('calAgMonth').textContent = MESES_AG[agM];
    const grid = document.getElementById('calAgGrid');
    grid.innerHTML = '';
    DIAS_AG.forEach(d => {
      const h = document.createElement('div');
      h.className = 'cal-ag-dow';
      h.textContent = d;
      grid.appendChild(h);
    });
    const first    = new Date(agY, agM, 1);
    const startDow = (first.getDay() + 6) % 7;
    const dim      = new Date(agY, agM + 1, 0).getDate();
    const prevDim  = new Date(agY, agM, 0).getDate();
    const today    = new Date();

    for (let i = 0; i < startDow; i++) {
      const b = document.createElement('button');
      b.className = 'cal-ag-day other-month';
      b.textContent = prevDim - startDow + 1 + i;
      grid.appendChild(b);
    }
    for (let d = 1; d <= dim; d++) {
      const b = document.createElement('button');
      b.className = 'cal-ag-day';
      b.textContent = d;
      if (today.getFullYear()===agY && today.getMonth()===agM && today.getDate()===d)
        b.classList.add('today');
      if (agSelected && agSelected.y===agY && agSelected.m===agM && agSelected.d===d)
        b.classList.add('selected');
      b.addEventListener('click', () => {
        agSelected = {y:agY, m:agM, d};
        renderCalAg();
        renderSlots(d);
        if (window.__agOnDateSelect) window.__agOnDateSelect(new Date(agY, agM, d));
      });
      grid.appendChild(b);
    }
    const cells = startDow + dim;
    const rem   = cells % 7 === 0 ? 0 : 7 - (cells % 7);
    for (let i = 1; i <= rem; i++) {
      const b = document.createElement('button');
      b.className = 'cal-ag-day other-month';
      b.textContent = i;
      grid.appendChild(b);
    }
  }

  function renderSlots(day) {
    const title = document.getElementById('slotsTitle');
    const grid  = document.getElementById('slotsGrid');
    const dow   = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
    title.textContent = `Horarios disponibles — ${dow[new Date(agY,agM,day).getDay()]} ${day} de ${MESES_AG[agM]}`;
    grid.innerHTML = '';
    HOURS_AG.forEach(({label, h}) => {
      const btn = document.createElement('button');
      const st  = getSlotStatus(agY, agM, day, h);
      btn.className = `slot-btn slot-${st}`;
      btn.textContent = label;
      btn.disabled = st === 'ocupado';
      btn.addEventListener('click', () => {
        document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        selectedSlot = label;
        if (window.__agOnSlotSelect) window.__agOnSlotSelect(label);
      });
      grid.appendChild(btn);
    });
  }

  document.getElementById('calAgYearPrev') .addEventListener('click', () => { agY--;       renderCalAg(); });
  document.getElementById('calAgYearNext') .addEventListener('click', () => { agY++;       renderCalAg(); });
  document.getElementById('calAgMonthPrev').addEventListener('click', () => { if(--agM<0){agM=11;agY--;} renderCalAg(); });
  document.getElementById('calAgMonthNext').addEventListener('click', () => { if(++agM>11){agM=0;agY++;} renderCalAg(); });

  renderCalAg();
  renderSlots(new Date().getDate());
})();
</script>
