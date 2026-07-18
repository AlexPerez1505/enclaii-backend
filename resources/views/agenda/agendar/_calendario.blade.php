{{-- ============================================================
     AGENDAR / _calendario.blade.php
     Paso 3: Selección de fecha y hora con horarios disponibles
     ============================================================ --}}
<style>
/* Mini calendario */
.cal-ag-wrap{
  display:flex;flex-direction:column;gap:10px;
  background:linear-gradient(to bottom,rgba(255,255,255,.60) 0%,rgba(255,255,255,.10) 100%);
  backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);
  border:1.5px solid rgba(255,255,255,.25);
  border-radius:14px;
  padding:12px 12px 10px;
  box-shadow:0 16px 48px rgba(0,0,0,.45);
  flex:1;
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
.cal-ag-day.past{opacity:.45;cursor:not-allowed;color:#fff}
.cal-ag-day.past:disabled{pointer-events:none;background:transparent}
.cal-ag-day.has-events::after{content:'';display:block;width:4px;height:4px;border-radius:50%;background:var(--ag-blue);margin:1px auto 0;position:absolute;bottom:3px;left:50%;transform:translateX(-50%)}
.cal-ag-day{position:relative}

/* Horarios arbitrarios */
.time-section{margin-top:16px;min-width:0;overflow:hidden}
.time-section-title{font-size:12px;font-weight:700;color:var(--ag-soft);margin-bottom:8px}

/* Línea de tiempo */
.timeline-wrap{
  margin-bottom:12px;
  width:100%;
  overflow-x:auto;
  overflow-y:hidden;
  -webkit-overflow-scrolling:touch;
  scrollbar-width:thin;
  scrollbar-color:rgba(255,255,255,.2) transparent;
  box-sizing:border-box;
}
.timeline-wrap::-webkit-scrollbar{height:4px}
.timeline-wrap::-webkit-scrollbar-track{background:transparent}
.timeline-wrap::-webkit-scrollbar-thumb{background:rgba(255,255,255,.2);border-radius:99px}
.timeline-inner{width:720px}
.timeline-labels{display:flex;justify-content:space-between;font-size:9.5px;color:var(--ag-soft);margin-bottom:5px;overflow:visible}
.timeline-labels span{flex:0 0 auto;text-align:center;white-space:nowrap}
.timeline-bar{position:relative;height:22px;border-radius:11px;background:rgba(255,255,255,.12);overflow:hidden;display:flex;align-items:stretch;transition:height 200ms ease}
.timeline-wrap:hover .timeline-bar{height:44px}
.timeline-segment{flex:1;height:100%;border-right:1.5px solid rgba(255,255,255,.25);position:relative;z-index:0}
.timeline-segment:last-child{border-right:none}
.timeline-segment.free{background:#4C9242}
.timeline-segment.busy{background:#D90000}
.timeline-segment.blocked{background:rgba(100,110,140,.7);cursor:not-allowed}
.timeline-segment.blocked::after{content:'';position:absolute;inset:0;background:repeating-linear-gradient(45deg,transparent,transparent 4px,rgba(255,255,255,.12) 4px,rgba(255,255,255,.12) 8px)}
.timeline-segment.past{background:rgba(120,120,120,.55);cursor:not-allowed}
.timeline-segment.past::after{content:'';position:absolute;inset:0;background:repeating-linear-gradient(45deg,transparent,transparent 4px,rgba(255,255,255,.15) 4px,rgba(255,255,255,.15) 8px)}
.timeline-cursor{position:absolute;top:50%;width:2px;height:140%;background:#fff;transform:translate(-50%,-50%);box-shadow:0 0 6px rgba(0,0,0,.5);z-index:2;pointer-events:none;transition:height 200ms ease}
.timeline-selection{position:absolute;top:0;height:100%;background:linear-gradient(90deg,rgba(217,0,0,.45),rgba(217,0,0,.25));border-radius:6px;z-index:1;pointer-events:none;transition:left 150ms ease,width 150ms ease}
/* Selector de hora */
.time-picker-wrap{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:12px}
.time-picker-col{display:flex;flex-direction:column;gap:4px}
.time-picker-col label{font-size:10px;color:var(--ag-soft);font-weight:600;letter-spacing:.02em}
.time-picker-col input[type="text"]{
  width:56px;min-width:56px;height:38px;border-radius:8px;border:1.5px solid rgba(255,255,255,.25);
  background:rgba(0,0,0,.25);color:#fff;font-size:15px;font-weight:700;cursor:pointer;
  padding:0 10px;font-family:inherit;appearance:auto;text-align:center
}
.time-picker-col input[type="text"]:focus{outline:none;border-color:var(--ag-blue)}
.time-picker-col input[type="text"]::selection{background:var(--ag-blue);color:#fff}
.time-separator{font-size:18px;font-weight:800;color:#fff;align-self:flex-end;padding-bottom:6px}

/* Duración */
.duration-picker-wrap{display:flex;flex-direction:column;gap:4px;margin-bottom:12px}
.duration-label{font-size:10px;color:var(--ag-soft);font-weight:600;letter-spacing:.02em}
.duration-input-group{display:flex;align-items:center;gap:6px}
.duration-input{
  width:72px;height:36px;border-radius:8px;border:1.5px solid rgba(255,255,255,.25);
  background:rgba(0,0,0,.25);color:#fff;font-size:15px;font-weight:700;text-align:center;
  font-family:inherit;appearance:textfield
}
.duration-input::-webkit-outer-spin-button,
.duration-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
.duration-input:focus{outline:none;border-color:var(--ag-blue)}
.duration-suffix{font-size:12px;color:var(--ag-soft);white-space:nowrap}
.dur-btn{width:34px;height:36px;border-radius:8px;border:1.5px solid rgba(255,255,255,.25);background:rgba(0,0,0,.25);color:#fff;font-size:18px;font-weight:700;cursor:pointer;display:grid;place-items:center;transition:background 120ms,border-color 120ms}
.dur-btn:hover{background:var(--ag-blue);border-color:var(--ag-blue);color:#fff}

/* Fin calculado + estado */
.time-meta{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:12px}
.time-end{font-size:12px;color:var(--ag-soft)}
.time-end b{color:#fff;font-weight:700}
.time-status{padding:8px 12px;border-radius:8px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:6px;transition:background 150ms,color 150ms}
.time-status.ok{background:rgba(76,146,66,.18);color:#7BD96E;border:1px solid rgba(76,146,66,.35)}
.time-status.bad{background:rgba(217,0,0,.18);color:#FF6B6B;border:1px solid rgba(217,0,0,.35)}
.time-status svg{width:14px;height:14px}

/* Leyenda */
.slots-legend{display:flex;align-items:center;gap:14px;margin-top:12px;flex-wrap:wrap}
.legend-item{display:flex;align-items:center;gap:5px;font-size:11px;color:var(--ag-soft)}
.legend-dot{width:8px;height:8px;border-radius:50%;flex:none}
.legend-dot.libre{background:#4C9242}
.legend-dot.ocupado{background:#D90000}
.legend-dot.bloqueado{background:rgba(100,110,140,.8);border:1px solid rgba(180,190,210,.5)}

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
html[data-theme="light"] .time-section-title{color:#5B6A99}
html[data-theme="light"] .timeline-labels{color:#5B6A99}
html[data-theme="light"] .timeline-bar{background:rgba(20,50,120,.12)}
html[data-theme="light"] .timeline-segment{border-right-color:rgba(20,50,120,.25)}
html[data-theme="light"] .timeline-cursor{background:#0E1530;box-shadow:0 0 6px rgba(20,50,120,.25)}
html[data-theme="light"] .timeline-selection{background:linear-gradient(90deg,rgba(217,0,0,.25),rgba(217,0,0,.12))}
html[data-theme="light"] .timeline-segment.past{background:rgba(100,100,100,.35)}
html[data-theme="light"] .timeline-segment.blocked{background:rgba(90,100,130,.35)}
html[data-theme="light"] .time-picker-col input[type="text"]{background:#fff;border-color:rgba(20,50,120,.25);color:#0E1530}
html[data-theme="light"] .time-picker-col input[type="text"]::selection{background:#1668D9;color:#fff}
html[data-theme="light"] .time-separator{color:#0E1530}
html[data-theme="light"] .time-picker-col label{color:#5B6A99}
html[data-theme="light"] .duration-label{color:#5B6A99}
html[data-theme="light"] .time-end{color:#5B6A99}
html[data-theme="light"] .duration-input{background:#fff;border-color:rgba(20,50,120,.25);color:#0E1530}
html[data-theme="light"] .duration-suffix{color:#5B6A99}
html[data-theme="light"] .dur-btn{background:#fff;border-color:rgba(20,50,120,.25);color:#0E1530}
html[data-theme="light"] .dur-btn:hover{background:#1668D9;border-color:#1668D9;color:#fff}
html[data-theme="light"] .time-end b{color:#0E1530}
html[data-theme="light"] .time-status.ok{background:rgba(76,146,66,.12);color:#2E6E27;border-color:rgba(76,146,66,.25)}
html[data-theme="light"] .time-status.bad{background:rgba(217,0,0,.12);color:#A80000;border-color:rgba(217,0,0,.25)}
html[data-theme="light"] .legend-item{color:#5B6A99}

@media(max-width:540px){
  .time-picker-wrap{gap:6px}
  .time-picker-col input[type="text"]{width:58px;min-width:58px;height:36px;font-size:14px;padding:0 8px}
  .time-ampm button{width:38px;height:34px;font-size:12px}
  .duration-input{width:64px;height:34px;font-size:14px}
  .dur-btn{width:30px;height:34px;font-size:16px}
}

.reprogram-info{
  display:none;
  margin-bottom:12px;
  padding:10px 12px;
  border-radius:10px;
  border:1.5px solid rgba(22,139,217,.35);
  background:rgba(22,139,217,.08);
  color:var(--ag-txt);
  font-size:12.5px;
  line-height:1.45;
}
.reprogram-info.open{display:block}
.reprogram-info strong{color:var(--ag-blue)}
html[data-theme="light"] .reprogram-info{
  background:rgba(22,104,217,.08);
  border-color:rgba(20,50,120,.22);
  color:#0E1530;
}
</style>

<div class="ag-card" id="stepCalendario">
  <div class="ag-card-title">
    <span class="ag-step-badge">3</span>
    Selección de Fecha y Hora
  </div>

  <div class="reprogram-info" id="reprogramInfo"></div>

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

  <div class="time-section">
    <div class="time-section-title" id="timeSectionTitle">Horarios disponibles</div>

    <div class="timeline-wrap">
      <div class="timeline-inner">
        <div class="timeline-labels" id="timelineLabels"></div>
        <div class="timeline-bar" id="timelineBar">
          <div class="timeline-cursor" id="timelineCursor" style="left:0"></div>
          <div class="timeline-selection" id="timelineSelection"></div>
        </div>
      </div>
    </div>

    <div class="time-picker-wrap">
      <div class="time-picker-col">
        <label>Hora</label>
        <input type="text" id="timeHour" inputmode="numeric" pattern="[0-9]{1,2}" maxlength="2" value="08">
      </div>
      <div class="time-separator">:</div>
      <div class="time-picker-col">
        <label>Min</label>
        <input type="text" id="timeMin" inputmode="numeric" pattern="[0-9]{1,2}" maxlength="2" value="00">
      </div>
    </div>

    <div class="duration-picker-wrap">
      <label class="duration-label">Duración</label>
      <div class="duration-input-group">
        <button type="button" class="dur-btn" id="durMinus" aria-label="Disminuir duración">−</button>
        <input
          class="duration-input"
          id="citaDuracion"
          type="number"
          min="1"
          max="1440"
          step="1"
          value="60"
          placeholder="Ej. 45"
        >
        <span class="duration-suffix">min</span>
        <button type="button" class="dur-btn" id="durPlus" aria-label="Aumentar duración">+</button>
      </div>
    </div>

    <div class="time-meta">
      <div class="time-end">Termina a las <b id="timeEnd">--:--</b></div>
      <div class="time-status ok" id="timeStatus">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Horario disponible
      </div>
    </div>

    <div class="slots-legend">
      <div class="legend-item"><span class="legend-dot libre"></span> Libre</div>
      <div class="legend-item"><span class="legend-dot ocupado"></span> Ocupado</div>
      <div class="legend-item"><span class="legend-dot bloqueado"></span> Bloqueado</div>
    </div>
  </div>
</div>

<script>
(function(){
  const MESES_AG = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
  const DIAS_AG  = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
  const DIAS_FULL = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

  // Horario operativo del recurso
  const DAY_START = 8;    // 8:00 AM
  const DAY_END   = 24;   // 23:59 (medianoche)
  const TIMELINE_SEGMENTS = 32; // 30 min × 32 = 16h (8:00–24:00)

  function getEvents() {
    return (typeof window.__AGENDA_EVENTS !== 'undefined') ? window.__AGENDA_EVENTS : {};
  }

  function getDayEvents(y, m, d) {
    const key = `${y}-${m+1}-${d}`;
    const dayEvs = getEvents()[key] || [];
    const currentId = window.__CITA_EDITAR_ID ? String(window.__CITA_EDITAR_ID) : null;
    return dayEvs.filter(ev => {
      if (!ev) return false;
      if (currentId && String(ev.id || '') === currentId) return false;
      return true;
    });
  }

  function parseEventRange(ev) {
    const [h, m] = String(ev.hora || '00:00').split(':').map(Number);
    const start = (h || 0) * 60 + (m || 0);
    const duration = parseInt(ev.duracion || ev.duration || 60, 10);
    return { start, end: start + duration, cls: ev.cls };
  }

  function formatTime24(minutes) {
    const h = Math.floor(minutes / 60);
    const m = minutes % 60;
    return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}`;
  }

  function formatTime12(minutes) {
    let h = Math.floor(minutes / 60);
    const m = minutes % 60;
    const ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12;
    if (h === 0) h = 12;
    return `${h}:${String(m).padStart(2,'0')} ${ampm}`;
  }

  function getSelectedMinutes() {
    let hh = parseInt(document.getElementById('timeHour').value, 10) || DAY_START;
    let mm = parseInt(document.getElementById('timeMin').value, 10) || 0;
    hh = Math.max(DAY_START, Math.min(23, hh));
    mm = Math.max(0, Math.min(59, mm));
    return Math.max(hh * 60 + mm, getMinSelectableMinutes());
  }

  function setSelectedMinutes(minutes) {
    const min = getMinSelectableMinutes();
    const max = 23 * 60 + 59;
    minutes = Math.max(min, Math.min(max, minutes));
    const h24 = Math.floor(minutes / 60);
    const mm = minutes % 60;
    document.getElementById('timeHour').value = String(h24).padStart(2, '0');
    document.getElementById('timeMin').value = String(mm).padStart(2, '0');
  }

  function getDuration() {
    const start = getSelectedMinutes();
    const d = parseInt(document.getElementById('citaDuracion')?.value, 10);
    const raw = Number.isFinite(d) && d > 0 ? d : 60;
    const maxDur = DAY_END * 60 - start;
    return Math.max(1, Math.min(maxDur, raw));
  }

  function changeDuration(delta) {
    const input = document.getElementById('citaDuracion');
    if (!input) return;
    const start = getSelectedMinutes();
    const maxDur = DAY_END * 60 - start;
    let val = parseInt(input.value, 10) || 60;
    val = Math.max(1, Math.min(maxDur, val + delta));
    input.value = val;
    updateEndTime();
    updateTimelineSelection();
    renderTimeline(agY, agM, agSelected ? agSelected.d : new Date().getDate());
    validateTime();
  }

  function overlaps(start, end, ranges) {
    return ranges.some(r => start < r.end && end > r.start);
  }

  function validateTime() {
    const start = getSelectedMinutes();
    const end = start + getDuration();
    const ranges = getDayEvents(agY, agM, agSelected ? agSelected.d : new Date().getDate()).map(parseEventRange);

    const statusEl = document.getElementById('timeStatus');
    const isBusy = overlaps(start, end, ranges);
    const minStart = getMinSelectableMinutes();

    if (start < minStart) {
      statusEl.className = 'time-status bad';
      statusEl.innerHTML = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        Horario pasado`;
      return false;
    }
    if (isBusy) {
      statusEl.className = 'time-status bad';
      statusEl.innerHTML = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        Horario ocupado`;
      return false;
    }
    statusEl.className = 'time-status ok';
    statusEl.innerHTML = `
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      ¡Horario disponible!`;
    return true;
  }

  function updateEndTime() {
    const start = getSelectedMinutes();
    const end = start + getDuration();
    document.getElementById('timeEnd').textContent = formatTime24(end);
  }

  function updateTimelineCursor() {
    const start = getSelectedMinutes();
    const total = (DAY_END - DAY_START) * 60;
    const left = Math.max(0, Math.min(100, ((start - DAY_START * 60) / total) * 100));
    document.getElementById('timelineCursor').style.left = left + '%';
  }

  function updateTimelineSelection() {
    const sel = document.getElementById('timelineSelection');
    if (!sel) return;
    const start = getSelectedMinutes();
    const end = start + getDuration();
    const dayStart = DAY_START * 60;
    const dayEnd = DAY_END * 60;
    const visibleStart = Math.max(dayStart, start);
    const visibleEnd = Math.min(dayEnd, end);
    if (visibleStart >= visibleEnd) {
      sel.style.display = 'none';
      return;
    }
    sel.style.display = 'block';
    const total = dayEnd - dayStart;
    sel.style.left = ((visibleStart - dayStart) / total) * 100 + '%';
    sel.style.width = ((visibleEnd - visibleStart) / total) * 100 + '%';
  }

  function getSegmentStatus(midMinutes, ranges) {
    const hit = ranges.find(r => midMinutes >= r.start && midMinutes < r.end);
    if (!hit) return 'free';
    return hit.cls === 'ev-block' ? 'blocked' : 'busy';
  }

  function renderTimelineLabels() {
    const el = document.getElementById('timelineLabels');
    if (!el) return;
    el.innerHTML = '';
    el.style.position = '';
    el.style.height = '';
    for (let h = DAY_START; h <= DAY_END; h += 2) {
      const span = document.createElement('span');
      span.textContent = h === 24 ? '0:00' : `${h}:00`;
      el.appendChild(span);
    }
  }

  function renderTimeline(y, m, d) {
    const bar = document.getElementById('timelineBar');
    const cursor = document.getElementById('timelineCursor');
    if (!bar) return;
    bar.querySelectorAll('.timeline-segment').forEach(el => el.remove());

    renderTimelineLabels();

    const ranges = getDayEvents(y, m, d).map(parseEventRange);
    const segmentMinutes = (DAY_END - DAY_START) * 60 / TIMELINE_SEGMENTS;

    const minMinutes = getMinSelectableMinutes();
    for (let i = 0; i < TIMELINE_SEGMENTS; i++) {
      const segStart = (DAY_START * 60) + (i * segmentMinutes);
      const mid = segStart + segmentMinutes / 2;
      let status = getSegmentStatus(mid, ranges);
      if (segStart < minMinutes) status = 'past';
      const seg = document.createElement('div');
      seg.className = 'timeline-segment ' + status;
      seg.title = formatTime24(segStart) + ' – ' + formatTime24(segStart + segmentMinutes);
      bar.insertBefore(seg, cursor);
    }
  }

  const __reprogramFecha = window.__CITA_EDITAR_FECHA || null;
  const urlParams = new URLSearchParams(window.location.search);
  const urlDia = parseInt(urlParams.get('dia'), 10);
  const urlMes = parseInt(urlParams.get('mes'), 10);
  const urlAnio = parseInt(urlParams.get('anio'), 10);
  const hasUrlDate = urlDia && urlMes && urlAnio;
  const __reprogramDate = __reprogramFecha
    ? new Date(__reprogramFecha + 'T00:00:00')
    : (hasUrlDate ? new Date(urlAnio, urlMes - 1, urlDia) : new Date());

  let agY = __reprogramDate.getFullYear();
  let agM = __reprogramDate.getMonth();
  let agSelected = (window.__CITA_EDITAR_FECHA || hasUrlDate)
    ? { y: __reprogramDate.getFullYear(), m: __reprogramDate.getMonth(), d: __reprogramDate.getDate() }
    : null;
  let selectedTime = null;

  function getNowMinutes() {
    const now = new Date();
    return now.getHours() * 60 + now.getMinutes();
  }

  function isSelectedToday() {
    const today = new Date();
    return agSelected && agSelected.y === today.getFullYear() && agSelected.m === today.getMonth() && agSelected.d === today.getDate();
  }

  function getMinSelectableMinutes() {
    const today = new Date();
    const isToday = agSelected
      ? agSelected.y === today.getFullYear() && agSelected.m === today.getMonth() && agSelected.d === today.getDate()
      : true;
    if (!isToday) return DAY_START * 60;
    return Math.max(DAY_START * 60, getNowMinutes());
  }

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
      const isToday = today.getFullYear()===agY && today.getMonth()===agM && today.getDate()===d;
      const isPast = new Date(agY, agM, d) < new Date(today.getFullYear(), today.getMonth(), today.getDate());
      if (isToday) b.classList.add('today');
      if (isPast) {
        b.classList.add('past');
        b.disabled = true;
        b.title = 'No puedes agendar en días pasados';
      }
      if (agSelected && agSelected.y===agY && agSelected.m===agM && agSelected.d===d)
        b.classList.add('selected');
      b.addEventListener('click', () => {
        agSelected = {y:agY, m:agM, d};
        renderCalAg();
        renderTimeSection(d);
        if (window.__agOnDateSelect) window.__agOnDateSelect(new Date(agY, agM, d));
        // Reflejar fecha seleccionada en la URL para conservarla al recargar
        const url = new URL(window.location.href);
        url.searchParams.set('dia', d);
        url.searchParams.set('mes', agM + 1);
        url.searchParams.set('anio', agY);
        window.history.replaceState({}, '', url);
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

  function renderTimeSection(day) {
    const title = document.getElementById('timeSectionTitle');
    if (title) title.textContent = `Horarios disponibles — ${DIAS_FULL[new Date(agY,agM,day).getDay()]} ${day} de ${MESES_AG[agM]}`;

    renderTimeline(agY, agM, day);

    // Si hay una hora previa (reprogramación), respetarla; si no, default 10:00 AM
    if (!selectedTime) {
      const horaInput = document.getElementById('citaHora');
      if (horaInput && horaInput.value) {
        const m = parseTime12ToMinutes(horaInput.value);
        if (m !== null) selectedTime = m;
      }
    }
    const defaultTime = Math.max(10 * 60, getMinSelectableMinutes());
    setSelectedMinutes(selectedTime || defaultTime);
    selectedTime = getSelectedMinutes();
    updateEndTime();
    updateTimelineCursor();
    updateTimelineSelection();
    validateTime();
    syncTimeToForm();
  }

  function parseTime12ToMinutes(text) {
    const m = String(text || '').trim().match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i);
    if (!m) return null;
    let h = parseInt(m[1], 10);
    const min = parseInt(m[2], 10);
    const ampm = m[3].toUpperCase();
    if (h < 1 || h > 12 || min < 0 || min > 59) return null;
    let h24 = h % 12;
    if (ampm === 'PM') h24 += 12;
    return h24 * 60 + min;
  }

  function onTimeChanged() {
    const hInput = document.getElementById('timeHour');
    const mInput = document.getElementById('timeMin');
    if (hInput) hInput.value = hInput.value.replace(/[^0-9]/g, '');
    if (mInput) mInput.value = mInput.value.replace(/[^0-9]/g, '');
    selectedTime = getSelectedMinutes();
    updateEndTime();
    updateTimelineCursor();
    updateTimelineSelection();
    renderTimeline(agY, agM, agSelected ? agSelected.d : new Date().getDate());
    validateTime();
    syncTimeToForm();
  }

  function syncTimeToForm() {
    const label = formatTime12(selectedTime);
    if (window.__agOnSlotSelect) window.__agOnSlotSelect(label);
  }

  function clampTimeInputs() {
    const hInput = document.getElementById('timeHour');
    const mInput = document.getElementById('timeMin');
    const minMinutes = getMinSelectableMinutes();
    const minH = Math.floor(minMinutes / 60);
    const minM = minMinutes % 60;
    if (hInput) {
      let h = parseInt(hInput.value, 10);
      if (Number.isNaN(h)) h = minH;
      hInput.value = String(Math.max(minH, Math.min(23, h))).padStart(2, '0');
    }
    if (mInput) {
      let m = parseInt(mInput.value, 10);
      if (Number.isNaN(m)) m = 0;
      const currentH = parseInt(hInput.value, 10) || minH;
      const minMin = currentH === minH ? minM : 0;
      mInput.value = String(Math.max(minMin, Math.min(59, m))).padStart(2, '0');
    }
  }

  document.getElementById('calAgYearPrev') .addEventListener('click', () => { agY--;       renderCalAg(); });
  document.getElementById('calAgYearNext') .addEventListener('click', () => { agY++;       renderCalAg(); });
  document.getElementById('calAgMonthPrev').addEventListener('click', () => { if(--agM<0){agM=11;agY--;} renderCalAg(); });
  document.getElementById('calAgMonthNext').addEventListener('click', () => { if(++agM>11){agM=0;agY++;} renderCalAg(); });

  // Listeners del selector arbitrario
  document.getElementById('timeHour')?.addEventListener('input', onTimeChanged);
  document.getElementById('timeMin')?.addEventListener('input', onTimeChanged);
  document.getElementById('timeHour')?.addEventListener('blur', () => { clampTimeInputs(); onTimeChanged(); });
  document.getElementById('timeMin')?.addEventListener('blur', () => { clampTimeInputs(); onTimeChanged(); });
  document.getElementById('citaHora')?.addEventListener('input', () => {
    const m = parseTime12ToMinutes(document.getElementById('citaHora')?.value);
    if (m !== null) {
      selectedTime = m;
      setSelectedMinutes(m);
      updateEndTime();
      updateTimelineCursor();
      updateTimelineSelection();
      renderTimeline(agY, agM, agSelected ? agSelected.d : new Date().getDate());
      validateTime();
    }
  });
  document.getElementById('citaDuracion')?.addEventListener('input', () => {
    updateEndTime();
    updateTimelineSelection();
    renderTimeline(agY, agM, agSelected ? agSelected.d : new Date().getDate());
    validateTime();
  });
  document.getElementById('durMinus')?.addEventListener('click', () => changeDuration(-5));
  document.getElementById('durPlus')?.addEventListener('click', () => changeDuration(5));

  // Scroll horizontal con rueda del ratón en el timeline
  document.querySelector('.timeline-wrap')?.addEventListener('wheel', (e) => {
    if (e.deltaY === 0) return;
    e.preventDefault();
    e.currentTarget.scrollLeft += e.deltaY * 1.5;
  }, { passive: false });

  function renderReprogramInfo() {
    const box = document.getElementById('reprogramInfo');
    if (!box || !window.__CITA_EDITAR_ID) return;
    const fecha = window.__CITA_EDITAR_FECHA_TEXTO || '';
    const hora = window.__CITA_EDITAR_HORA_TEXTO || '';
    box.classList.add('open');
    box.innerHTML = `<strong>Estaba programada:</strong> ${fecha} a las ${hora}. Selecciona otra fecha y otro horario disponible.`;
  }

  renderCalAg();
  renderTimeSection(agSelected ? agSelected.d : new Date().getDate());
  renderReprogramInfo();

  if (agSelected && window.__agOnDateSelect) {
    window.__agOnDateSelect(new Date(agSelected.y, agSelected.m, agSelected.d));
  }

  // Exponer funciones para sincronización externa (paciente -> calendario)
  window.__renderSlots = renderTimeSection;
  window.__renderCalAg = renderCalAg;

  // Actualizar hora mínima cada minuto cuando la fecha seleccionada es hoy
  setInterval(() => {
    if (!isSelectedToday()) return;
    const min = getMinSelectableMinutes();
    const current = getSelectedMinutes();
    if (current < min) {
      selectedTime = min;
      setSelectedMinutes(min);
      updateEndTime();
      updateTimelineCursor();
      updateTimelineSelection();
      validateTime();
      syncTimeToForm();
    }
    renderTimeline(agY, agM, agSelected ? agSelected.d : new Date().getDate());
  }, 60000);
})();
</script>
