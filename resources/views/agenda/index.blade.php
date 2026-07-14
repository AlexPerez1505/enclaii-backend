@extends('layouts.app')

@section('title', 'Agenda')
@section('active', 'agenda')
@section('header-title', 'Buenos días, '.(auth()->user()?->name ?? 'Doctor'))
@section('header-sub')
  Tiene <b>{{ $citasHoy ?? 0 }}</b> pacientes el día de hoy
@endsection

{{--
  ╔══════════════════════════════════════════════════════════╗
  ║  AGENDA — archivo principal (orquestador)                ║
  ║                                                          ║
  ║  Cada sección tiene su propio partial con CSS + HTML + JS║
  ║  en resources/views/agenda/                              ║
  ║                                                          ║
  ║  _base.blade.php     → estilos globales, layout,         ║
  ║                         tarjetas resumen, tema claro     ║
  ║  _tarjetas.blade.php → HTML de las sum-cards             ║
  ║  _mes.blade.php      → Vista Mes  (CSS + HTML + JS)      ║
  ║  _semana.blade.php   → Vista Semana (CSS + HTML + JS)    ║
  ║  _dia.blade.php      → Vista Día  (CSS + HTML + JS)      ║
  ║  _popup.blade.php    → Popup hover (CSS + HTML + JS)     ║
  ║  _bloqueos.blade.php → Modal bloqueo (CSS + HTML + JS)   ║
  ║  _sidebar.blade.php  → Filtros + Próximas citas (HTML)   ║
  ╚══════════════════════════════════════════════════════════╝
--}}

{{-- ===== ESTILOS GLOBALES ===== --}}
@push('styles')
  @include('agenda._base')
@endpush

{{-- ===== CONTENIDO ===== --}}
@section('content')

  <div class="agenda-header rise d2">
    <div class="agenda-header-left">
      <h2>Agenda</h2>
      <p>Gestiona tus citas y procedimientos</p>
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
      <button id="btnBloquearHora" type="button" class="btn-secondary" onclick="window.__openBlockModalManual && window.__openBlockModalManual()" style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-size:13px;font-weight:600;border:1.5px solid rgba(110,160,255,.35);background:rgba(110,160,255,.08);color:#8FA3CF;cursor:pointer;transition:all 150ms ease" onmouseover="this.style.background='rgba(110,160,255,.18)';this.style.color='#EAF1FF'" onmouseout="this.style.background='rgba(110,160,255,.08)';this.style.color='#8FA3CF'">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Bloquear hora
      </button>
      <a href="{{ route('agendar') }}" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Agendar cita
      </a>
    </div>
  </div>

  <div class="agenda-body rise d3">

    <div class="agenda-left">

      {{-- Toolbar: pestañas de vista + navegación mes --}}
      <div class="cal-toolbar">
        <div class="view-tabs">
          <button class="view-tab">Día</button>
          <button class="view-tab">Semana</button>
          <button class="view-tab active">Mes</button>
        </div>
        <div class="toolbar-right">
          <div class="month-nav">
          <button aria-label="Mes anterior">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <div class="month-label">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <div class="picker-wrap">
              <span id="mesActual">Mayo</span>
              <div class="picker-dropdown" id="mesPicker"><div class="picker-title">Mes</div></div>
            </div>
            <div class="picker-wrap">
              <span class="year" id="anioActual">2024</span>
              <div class="picker-dropdown" id="anioPicker"><div class="picker-title">Año</div></div>
            </div>
          </div>
          <button aria-label="Mes siguiente">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
          </div>
          <button class="toolbar-filter-btn" id="toolbarFilterBtn" title="Mostrar filtros" aria-label="Filtros">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
          </button>
          {{-- Dropdown de filtros (solo visible cuando está expandido) --}}
          <div class="filter-dropdown" id="toolbarFilterDropdown">
            <h4>Filtrar citas</h4>
            <div class="filter-list">
              <label class="filter-row" data-filter="ev-done">
                <input type="checkbox" checked>
                <span class="filter-indicator done"></span>
                <span class="filter-text">Completado</span>
              </label>
              <label class="filter-row" data-filter="ev-wait">
                <input type="checkbox" checked>
                <span class="filter-indicator wait"></span>
                <span class="filter-text">En espera</span>
              </label>
              <label class="filter-row" data-filter="ev-cancel">
                <input type="checkbox" checked>
                <span class="filter-indicator cancel"></span>
                <span class="filter-text">Cancelado</span>
              </label>
              <label class="filter-row" data-filter="ev-soon">
                <input type="checkbox" checked>
                <span class="filter-indicator soon"></span>
                <span class="filter-text">Próximo</span>
              </label>
            </div>
          </div>
          <button class="agenda-expand-btn" id="agendaExpandBtn" title="Expandir/Colapsar sidebar" aria-label="Expandir">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
            </svg>
          </button>
        </div>
      </div>

      {{-- Tarjetas resumen (Completado / En espera / Cancelado / Próximo) --}}
      @include('agenda._tarjetas')

      {{-- Vista Mes (CSS + HTML + JS) --}}
      @include('agenda._mes')

      {{-- Vista Semana (CSS + HTML + JS) --}}
      @include('agenda._semana')

      {{-- Vista Día (CSS + HTML + JS) --}}
      @include('agenda._dia')

    </div>

    {{-- Panel derecho: filtros + próximas citas --}}
    <div class="agenda-right">
      @include('agenda._sidebar')
    </div>

  </div>

  {{-- Modal bloqueo de tiempo (CSS + HTML + JS) --}}
  @include('agenda._bloqueos')

  {{-- Popup hover de eventos (CSS + HTML + JS) --}}
  @include('agenda._popup')

@endsection

{{-- Datos de eventos compartidos --}}
@include('agenda._events')

{{-- ===== SCRIPTS: JS de inicialización central ===== --}}
@push('scripts')
<script>
(function(){
  const MESES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
  const DIAS_CORTO = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
  const DIAS_ES    = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

  let cur = new Date();
  let curView = 'mes';

  function updateSumCards(counts) {
    const map = {
      'ev-done':   {el: document.querySelector('.sum-done'),  cnt: document.getElementById('cntDone')},
      'ev-wait':   {el: document.querySelector('.sum-wait'),  cnt: document.getElementById('cntWait')},
      'ev-cancel': {el: document.querySelector('.sum-cancel'),cnt: document.getElementById('cntCancel')},
      'ev-soon':   {el: document.querySelector('.sum-soon'),  cnt: document.getElementById('cntSoon')},
    };
    Object.entries(map).forEach(([cls, {el, cnt}]) => {
      if (!el) return;
      const n = counts[cls] || 0;
      if (cnt) cnt.textContent = n;
      el.style.display = n > 0 ? '' : 'none';
    });
  }
  function countEvents(keys) {
    const counts = {'ev-done':0,'ev-wait':0,'ev-cancel':0,'ev-soon':0};
    const recompute = window.__recomputeClass;
    keys.forEach(k => {
      (window.__AGENDA_EVENTS[k] || []).forEach(ev => {
        const liveCls = typeof recompute === 'function' ? recompute(ev, k) : ev.cls;
        if (counts[liveCls] !== undefined) counts[liveCls]++;
      });
    });
    return counts;
  }

  const filterMap = {'fi-done':'ev-done','fi-wait':'ev-wait','fi-cancel':'ev-cancel','fi-soon':'ev-soon'};
  const reverseMap = {'ev-done':'fi-done','ev-wait':'fi-wait','ev-cancel':'fi-cancel','ev-soon':'fi-soon'};

  function getFilterState(evClass) {
    const sidebarChk = document.querySelector('.' + reverseMap[evClass] + ' input[type=checkbox]');
    const toolbarChk = document.querySelector('.filter-row[data-filter="' + evClass + '"] input[type=checkbox]');
    // Prefer sidebar value; fallback to toolbar
    if (sidebarChk) return sidebarChk.checked;
    if (toolbarChk) return toolbarChk.checked;
    return true;
  }

  function setFilterState(evClass, checked) {
    const sidebarChk = document.querySelector('.' + reverseMap[evClass] + ' input[type=checkbox]');
    const toolbarChk = document.querySelector('.filter-row[data-filter="' + evClass + '"] input[type=checkbox]');
    if (sidebarChk) sidebarChk.checked = checked;
    if (toolbarChk) toolbarChk.checked = checked;
    document.querySelectorAll('.' + evClass).forEach(el => { el.style.display = checked ? '' : 'none'; });
  }

  function applyFilters() {
    Object.values(filterMap).forEach(evClass => {
      setFilterState(evClass, getFilterState(evClass));
    });
  }

  window.__EVENTS_DIA = window.__AGENDA_EVENTS;
  function buildCal(date)  { window.__buildCal(date, window.__AGENDA_EVENTS, MESES, updateSumCards, countEvents); }
  function buildWeek(date) { window.__buildWeek(date, window.__AGENDA_EVENTS, MESES, DIAS_CORTO, updateSumCards, countEvents); }
  function buildDay(date)  { window.__buildDay(date, window.__AGENDA_EVENTS, MESES, updateSumCards, countEvents); }

  function setView(view) {
    curView = view;
    const calGrid    = document.getElementById('calWrap');
    const weekGrid   = document.getElementById('weekGrid');
    const dayView    = document.getElementById('dayView');
    const monthNav   = document.querySelector('.month-nav');
    const agLeft     = document.querySelector('.agenda-left');
    calGrid.classList.remove('active');
    weekGrid.classList.remove('active');
    dayView.classList.remove('active');
    agLeft.classList.toggle('day-view-active', view === 'dia');
    if (view === 'mes')         { calGrid.classList.add('active');  buildCal(cur);  monthNav.style.display = ''; }
    else if (view === 'semana') { weekGrid.classList.add('active'); buildWeek(cur); monthNav.style.display = ''; }
    else if (view === 'dia')    { dayView.classList.add('active');  buildDayAndSync(cur); monthNav.style.display = 'none'; }
    applyFilters();
  }

  window.__rebuildCurrentView = function() {
    if (curView === 'mes')        buildCal(cur);
    else if (curView === 'semana') buildWeek(cur);
    else if (curView === 'dia')    buildDayAndSync(cur);
  };

  setView('mes');

  setInterval(() => {
    if (curView === 'mes') buildCal(cur);
    else if (curView === 'semana') buildWeek(cur);
    else if (curView === 'dia') buildDayAndSync(cur);
    if (typeof window.__rebuildProximas === 'function') window.__rebuildProximas();
  }, 30000);

  document.querySelectorAll('.month-nav button').forEach((btn, i) => {
    btn.addEventListener('click', () => {
      if (curView === 'mes') {
        cur = new Date(cur.getFullYear(), cur.getMonth() + (i === 0 ? -1 : 1), 1);
        buildCal(cur);
      } else if (curView === 'semana') {
        cur = new Date(cur.getFullYear(), cur.getMonth(), cur.getDate() + (i === 0 ? -7 : 7));
        buildWeek(cur);
      }
      applyFilters();
    });
  });

  document.querySelectorAll('.view-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.view-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      const v = tab.textContent.trim().toLowerCase();
      if (v === 'mes') setView('mes');
      else if (v === 'semana') setView('semana');
      else setView('dia');
    });
  });

  document.getElementById('dayPrev').addEventListener('click', () => {
    cur = new Date(cur.getFullYear(), cur.getMonth(), cur.getDate() - 1);
    buildDayAndSync(cur);
  });
  document.getElementById('dayNext').addEventListener('click', () => {
    cur = new Date(cur.getFullYear(), cur.getMonth(), cur.getDate() + 1);
    buildDayAndSync(cur);
  });

  document.querySelectorAll('.filter-item input[type=checkbox]').forEach(chk => {
    chk.addEventListener('change', () => {
      const label = chk.closest('.filter-item');
      const fiClass = Object.keys(filterMap).find(k => label.classList.contains(k));
      if (fiClass) setFilterState(filterMap[fiClass], chk.checked);
    });
  });
  document.querySelectorAll('.filter-row input[type=checkbox]').forEach(chk => {
    chk.addEventListener('change', () => {
      const label = chk.closest('.filter-row');
      const evClass = label && label.dataset.filter;
      if (evClass) setFilterState(evClass, chk.checked);
    });
  });

  const mesPicker = document.getElementById('mesPicker');
  const anioPicker= document.getElementById('anioPicker');
  const mesSpan   = document.getElementById('mesActual');
  const anioSpan  = document.getElementById('anioActual');

  function closePickers() { mesPicker.classList.remove('open'); anioPicker.classList.remove('open'); }

  function buildMesPicker() {
    mesPicker.querySelectorAll('.picker-item').forEach(el => el.remove());
    MESES.forEach((nombre, idx) => {
      const btn = document.createElement('button');
      btn.className = 'picker-item' + (cur.getMonth() === idx ? ' active' : '');
      btn.textContent = nombre;
      btn.addEventListener('click', () => { cur = new Date(cur.getFullYear(), idx, 1); buildCal(cur); applyFilters(); closePickers(); });
      mesPicker.appendChild(btn);
    });
  }
  function buildAnioPicker() {
    anioPicker.querySelectorAll('.picker-item').forEach(el => el.remove());
    const currentY = cur.getFullYear();
    for (let y = currentY + 5; y >= currentY - 5; y--) {
      const btn = document.createElement('button');
      btn.className = 'picker-item' + (y === currentY ? ' active' : '');
      btn.textContent = y;
      btn.addEventListener('click', () => { cur = new Date(y, cur.getMonth(), 1); buildCal(cur); applyFilters(); closePickers(); });
      anioPicker.appendChild(btn);
    }
  }

  mesSpan.addEventListener('click', e => { e.stopPropagation(); const o = mesPicker.classList.contains('open'); closePickers(); if (!o) { buildMesPicker(); mesPicker.classList.add('open'); } });
  anioSpan.addEventListener('click', e => { e.stopPropagation(); const o = anioPicker.classList.contains('open'); closePickers(); if (!o) { buildAnioPicker(); anioPicker.classList.add('open'); } });
  document.addEventListener('click', closePickers);

  function buildDayAndSync(date) {
    cur = date;
    buildDay(date);
    if (window.__setBloqueoDate) window.__setBloqueoDate(date);
    if (window.__syncDayPicker) window.__syncDayPicker(date);
  }

  window.__rebuildAgenda = function() {
    if (curView === 'mes') buildCal(cur);
    else if (curView === 'semana') buildWeek(cur);
    else if (curView === 'dia') buildDayAndSync(cur);
    applyFilters();
    if (typeof window.__rebuildProximas === 'function') window.__rebuildProximas();
  };

  const cur_ref = { get y(){ return cur.getFullYear(); }, get m(){ return cur.getMonth(); } };
  window.__initPopup(window.__AGENDA_EVENTS, MESES, cur_ref, DIAS_ES);
  window.__initDayPicker(function(date) { buildDayAndSync(date); });
  window.__initBloqueos(window.__AGENDA_EVENTS, MESES, DIAS_ES, buildDayAndSync);

  /* ---- Botón y dropdown de filtros ---- */
  const toolbarFilterBtn = document.getElementById('toolbarFilterBtn');
  const toolbarFilterDropdown = document.getElementById('toolbarFilterDropdown');
  if (toolbarFilterBtn && toolbarFilterDropdown) {
    toolbarFilterBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      toolbarFilterDropdown.classList.toggle('open');
    });
    // Cerrar al hacer clic fuera
    document.addEventListener('click', () => {
      toolbarFilterDropdown.classList.remove('open');
    });
    toolbarFilterDropdown.addEventListener('click', (e) => {
      e.stopPropagation();
    });
  }

  /* ---- Botón expandir sidebar ---- */
  const agendaExpandBtn = document.getElementById('agendaExpandBtn');
  const agLeft = document.querySelector('.agenda-left');
  if (agendaExpandBtn && agLeft) {
    agendaExpandBtn.addEventListener('click', () => {
      agLeft.classList.toggle('expanded');
      // Cerrar dropdown de filtros al colapsar
      if (!agLeft.classList.contains('expanded') && toolbarFilterDropdown) {
        toolbarFilterDropdown.classList.remove('open');
      }
      // Re-renderizar vista día si está activa para mostrar/ocultar botón +X citas
      const dayView = document.getElementById('dayView');
      if (dayView && dayView.classList.contains('active') && typeof buildDay === 'function') {
        buildDay(cur);
      }
      applyFilters();
    });
  }


})();
</script>
@endpush
