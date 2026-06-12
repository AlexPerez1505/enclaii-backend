@extends('layouts.app')

@section('title', 'Agenda')
@section('active', 'agenda')
@section('header-title', 'Buenos días, Dr. Victor')
@section('header-sub')
  Tiene <b>8</b> pacientes el día de hoy
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
    <a href="{{ route('agendar') }}" class="btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Agendar cita
    </a>
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
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          <button aria-label="Mes siguiente">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
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

{{-- ===== SCRIPTS: JS de inicialización central ===== --}}
@push('scripts')
<script>
(function(){
  const MESES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
  const DIAS_CORTO = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
  const DIAS_ES    = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

  const EVENTS = {
    '2024-5-1':  [{t:'11:00 Erik Esquivel · Endoscopia',    cls:'ev-done',  h:11}],
    '2024-5-2':  [{t:'11:00 Habib Pérez · Endoscopia',      cls:'ev-cancel',h:11}],
    '2024-5-12': [{t:'11:00 Ricardo Martínez · Endoscopia', cls:'ev-done',  h:11}],
    '2024-5-13': [{t:'8:30 Ricardo Martínez · Endoscopia',  cls:'ev-done',  h:8}],
    '2024-5-14': [{t:'11:00 Habib Pérez · Endoscopia',cls:'ev-cancel',h:11},{t:'16:30 Grabiela Torres · Endoscopia',cls:'ev-wait',h:16},{t:'18:30 Yessica Martínez · Endoscopia',cls:'ev-cancel',h:18}],
    '2024-5-15': [{t:'9:00 Perla Martínez · Endoscopia',cls:'ev-done',h:9},{t:'11:00 Yessica · Endoscopia',cls:'ev-cancel',h:11}],
    '2024-5-16': [{t:'11:30 Dulce Martínez · Endoscopia',cls:'ev-wait',h:11},{t:'12:30 Paula Martínez · Endoscopia',cls:'ev-done',h:12},{t:'13:30 Paulina Gómez · Endoscopia',cls:'ev-done',h:13}],
    '2024-5-17': [{t:'17:00 Yukary Huerta · Endoscopia',cls:'ev-wait',h:17}],
    '2024-5-18': [{t:'11:30 Irvin Rocha · Endoscopia',cls:'ev-wait',h:11},{t:'15:00 Yukary Huerta · Endoscopia',cls:'ev-wait',h:15}],
    '2024-5-19': [{t:'11:30 Pelet Gómez · Endoscopia',cls:'ev-soon',h:11}],
    '2024-5-20': [{t:'18:00 Luis Arellano · Endoscopia',cls:'ev-soon',h:18}],
    '2024-5-23': [{t:'11:30 Pelet Gómez · Endoscopia',cls:'ev-done',h:11}],
    '2024-5-26': [{t:'11:30 Dulce Martínez · Endoscopia',cls:'ev-wait',h:11}],
    '2024-5-31': [{t:'11:30 Pelet Gómez · Endoscopia',cls:'ev-soon',h:11}],
  };
  window.__AGENDA_EVENTS = EVENTS;

  let cur = new Date(2024, 4, 13);
  let curView = 'mes';

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const counters = document.querySelectorAll('[data-target]');
  if (reduced || typeof gsap === 'undefined') {
    counters.forEach(c => { c.textContent = parseInt(c.dataset.target, 10); });
  } else {
    counters.forEach((c, i) => {
      const target = parseInt(c.dataset.target, 10);
      const obj = { v: 0 };
      gsap.to(obj, { v: target, duration: 1.2, ease: 'expo.out', delay: 0.3 + i * 0.1,
        onUpdate: () => { c.textContent = Math.round(obj.v); } });
    });
  }

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
    keys.forEach(k => { (EVENTS[k] || []).forEach(ev => { if (counts[ev.cls] !== undefined) counts[ev.cls]++; }); });
    return counts;
  }

  const filterMap = {'fi-done':'ev-done','fi-wait':'ev-wait','fi-cancel':'ev-cancel','fi-soon':'ev-soon'};
  function applyFilters() {
    document.querySelectorAll('.filter-item input[type=checkbox]').forEach(chk => {
      const label = chk.closest('.filter-item');
      const fiClass = Object.keys(filterMap).find(k => label.classList.contains(k));
      if (!fiClass) return;
      const evClass = filterMap[fiClass];
      document.querySelectorAll('.' + evClass).forEach(el => { el.style.display = chk.checked ? '' : 'none'; });
    });
  }

  function buildCal(date)  { window.__buildCal(date, EVENTS, MESES, updateSumCards, countEvents); }
  function buildWeek(date) { window.__buildWeek(date, EVENTS, MESES, DIAS_CORTO, updateSumCards, countEvents); }
  function buildDay(date)  { window.__buildDay(date, EVENTS, MESES, updateSumCards, countEvents); }

  function setView(view) {
    curView = view;
    const calGrid  = document.getElementById('calWrap');
    const weekGrid = document.getElementById('weekGrid');
    const dayView  = document.getElementById('dayView');
    const monthNav = document.querySelector('.month-nav');
    calGrid.classList.remove('active');
    weekGrid.classList.remove('active');
    dayView.classList.remove('active');
    if (view === 'mes')         { calGrid.classList.add('active');  buildCal(cur);  monthNav.style.display = ''; }
    else if (view === 'semana') { weekGrid.classList.add('active'); buildWeek(cur); monthNav.style.display = ''; }
    else if (view === 'dia')    { dayView.classList.add('active');  buildDayAndSync(cur); monthNav.style.display = 'none'; }
    applyFilters();
  }

  setView('mes');

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
    chk.addEventListener('change', applyFilters);
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

  const cur_ref = { get y(){ return cur.getFullYear(); }, get m(){ return cur.getMonth(); } };
  window.__initPopup(EVENTS, MESES, cur_ref, DIAS_ES);
  window.__initDayPicker(function(date) { buildDayAndSync(date); });
  window.__initBloqueos(EVENTS, MESES, DIAS_ES, buildDayAndSync);

})();
</script>
@endpush
