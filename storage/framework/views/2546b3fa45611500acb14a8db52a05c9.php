<?php $__env->startSection('title', 'Galería de pacientes'); ?>
<?php $__env->startSection('active', 'galeria'); ?>
<?php $__env->startSection('header-title', 'Galería de pacientes'); ?>
<?php $__env->startSection('header-sub', 'Consulta y administra imágenes y videos de estudios'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.gp-workspace{
  display:grid;
  grid-template-columns:minmax(0,1fr);
  gap:24px;
  align-items:stretch;
  min-height:calc(100vh - 134px);
}
.gp-workspace.filters-open{grid-template-columns:minmax(0,1fr) 390px}
.gp-shell{min-width:0;display:flex;flex-direction:column}
.gp-toolbar{display:flex;align-items:center;gap:12px;margin-bottom:16px}
.gp-search{
  flex:1;height:52px;display:flex;align-items:center;gap:11px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:11px;padding:0 16px;
  transition:border-color 150ms ease,box-shadow 150ms ease;
}
.gp-search:focus-within{border-color:var(--blue);box-shadow:0 0 0 3px rgba(46,123,246,.12)}
.gp-search svg{color:var(--txt-soft);flex:none}
.gp-search input{flex:1;min-width:0;border:0;outline:0;background:transparent;color:var(--txt);font:inherit;font-size:13.5px}
.gp-search input::placeholder{color:var(--txt-soft)}
.gp-filter{
  position:relative;height:52px;display:flex;align-items:center;gap:9px;padding:0 18px;
  border:1px solid var(--stroke);border-radius:11px;background:var(--panel-2);
  color:var(--blue);font-size:13.5px;font-weight:700;
  transition:border-color 150ms ease,background-color 150ms ease,transform 150ms ease;
}
.gp-filter:active{transform:scale(.97)}
.gp-filter.on{border-color:rgba(46,123,246,.62);background:rgba(46,123,246,.1)}
.gp-filter.has-filters::after{
  content:"";position:absolute;top:8px;right:8px;width:8px;height:8px;border-radius:50%;
  background:var(--blue);box-shadow:0 0 8px rgba(46,123,246,.8);
}
@media(hover:hover)and(pointer:fine){.gp-filter:hover{border-color:rgba(46,123,246,.52);background:rgba(46,123,246,.08)}}

.gp-list{display:flex;flex-direction:column;gap:12px}
.gp-card{
  display:grid;grid-template-columns:auto minmax(0,1fr) auto;align-items:center;gap:16px;
  min-height:104px;padding:17px 18px;background:var(--panel-2);
  border:1px solid var(--stroke);border-radius:11px;
  transition:transform 160ms var(--ease-out),border-color 150ms ease,box-shadow 180ms ease;
}
.gp-card:active{transform:scale(.99)}
@media(hover:hover)and(pointer:fine){
  .gp-card:hover{border-color:rgba(46,123,246,.48);box-shadow:0 12px 30px -18px rgba(46,123,246,.55);transform:translateY(-1px)}
}
.gp-avatar{
  width:54px;height:54px;border-radius:14px;display:grid;place-items:center;
  color:#fff;font-family:'Sora',sans-serif;font-size:15px;font-weight:800;
}
.gp-main{min-width:0}
.gp-name-row{display:flex;align-items:center;gap:10px;margin-bottom:5px}
.gp-name{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:15px;font-weight:800;color:var(--txt)}
.gp-status{margin-left:auto;flex:none;font-size:11.5px;font-weight:800}
.gp-status.active{color:var(--green)}
.gp-status.inactive{color:var(--orange)}
.gp-id{font-size:12.5px;color:var(--txt-soft);margin-bottom:7px}
.gp-meta{display:flex;align-items:center;gap:10px;flex-wrap:wrap;font-size:12.5px;color:var(--txt-soft)}
.gp-dot{width:3px;height:3px;border-radius:50%;background:var(--txt-soft);opacity:.65}
.gp-count strong{color:var(--txt);font-weight:800}
.gp-open{
  width:42px;height:42px;border-radius:11px;border:1px solid var(--stroke);
  display:grid;place-items:center;color:var(--txt-soft);background:var(--card);
  transition:background-color 150ms ease,color 150ms ease,border-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.gp-card:hover .gp-open{color:var(--blue);border-color:rgba(46,123,246,.42);background:rgba(46,123,246,.1)}}
.gp-empty{display:none;text-align:center;padding:52px 20px;color:var(--txt-soft)}
.gp-empty svg{display:block;margin:0 auto 12px;opacity:.5}
.gp-footer{
  display:flex;align-items:center;justify-content:space-between;gap:16px;
  margin-top:auto;padding-top:24px;color:var(--txt-soft);font-size:12.5px;
}
.gp-pagination{display:flex;align-items:center;gap:10px}
.gp-per-page{
  height:36px;padding:0 31px 0 12px;border:1px solid var(--stroke);border-radius:9px;
  color:var(--txt-soft);background:var(--panel-2);font:inherit;appearance:auto;outline:none;
}
.gp-pages{display:flex;align-items:center;gap:6px}
.gp-page{
  width:32px;height:32px;border-radius:8px;display:grid;place-items:center;
  color:var(--txt-soft);border:1px solid transparent;font-weight:700;
}
.gp-page.active{border-color:rgba(46,123,246,.65);color:var(--txt);background:rgba(46,123,246,.1)}
.gp-page:disabled{opacity:.35;cursor:not-allowed}

/* Panel lateral */
.fil-overlay{display:none}
.fil-panel{
  display:none;min-width:0;height:calc(100vh - 134px);min-height:650px;position:sticky;top:28px;
  background:linear-gradient(180deg,var(--card),var(--panel-2));border:1px solid var(--stroke);
  border-radius:13px;overflow:hidden;flex-direction:column;box-shadow:0 18px 55px rgba(0,0,0,.14);
}
.gp-workspace.filters-open .fil-panel{display:flex}
.fil-head{display:flex;align-items:center;justify-content:space-between;padding:19px 21px 12px;flex:none}
.fil-head h2{font-family:'Sora',sans-serif;font-size:16px;font-weight:700}
.fil-close{width:32px;height:32px;display:grid;place-items:center;border-radius:8px;color:var(--txt-soft)}
.fil-close:hover{color:var(--txt);background:rgba(110,160,255,.08)}
.fil-body{flex:1;min-height:0;overflow-y:auto;padding:7px 21px 17px;scrollbar-width:thin;scrollbar-color:rgba(110,160,255,.25) transparent}
.fil-group{margin-bottom:13px}
.fil-label{display:block;margin-bottom:6px;color:var(--txt);font-size:11.5px;font-weight:600}
.fil-select-wrap,.fil-date-wrap{position:relative;display:block}
.fil-select-wrap>svg,.fil-date-wrap>svg{
  position:absolute;right:12px;top:50%;width:13px;height:13px;transform:translateY(-50%);
  fill:none;stroke:var(--txt-soft);stroke-width:2;stroke-linecap:round;pointer-events:none;
}
.fil-control{
  width:100%;height:37px;border:1px solid var(--stroke-strong);border-radius:7px;
  background:rgba(6,8,28,.18);color:var(--txt);padding:0 34px 0 11px;
  font:inherit;font-size:12px;outline:none;transition:border-color .15s,box-shadow .15s;
}
.fil-control:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(46,123,246,.1)}
.fil-select-wrap select{appearance:none}
.fil-control option{background:var(--panel);color:var(--txt)}
.fil-section{border:0;border-top:1px solid var(--stroke);margin:14px 0 0;padding:14px 0 0}
.fil-periods{display:grid;grid-template-columns:.62fr 1fr .82fr 1.55fr;gap:5px}
.fil-periods button{
  min-width:0;height:37px;padding:0 7px;border:1px solid var(--stroke-strong);border-radius:7px;
  color:var(--txt);font-size:10.5px;white-space:nowrap;background:rgba(6,8,28,.12);
}
.fil-periods button.active{color:#fff;border-color:var(--blue);background:linear-gradient(135deg,#1668D9,var(--blue));box-shadow:0 5px 16px -9px var(--blue)}
.fil-date-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:13px}
.fil-date-grid label>span:first-child{display:block;margin-bottom:6px;font-size:11.5px;color:var(--txt)}
.fil-date-wrap input{padding-right:33px}
.fil-date-wrap input::-webkit-calendar-picker-indicator{opacity:0;position:absolute;right:0;width:34px;cursor:pointer}
.fil-options{display:flex;align-items:center;gap:24px;min-height:26px}
.fil-options-wrap{flex-wrap:wrap;row-gap:12px;column-gap:18px}
.fil-options label{display:flex;align-items:center;gap:6px;color:var(--txt-soft);font-size:11.5px;cursor:pointer;white-space:nowrap}
.fil-options input{
  appearance:none;width:15px;height:15px;border:1.5px solid rgba(110,160,255,.5);
  border-radius:50%;display:grid;place-items:center;flex:none;
}
.fil-options input::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--blue);transform:scale(0);transition:transform .12s ease}
.fil-options input:checked{border-color:var(--blue)}
.fil-options input:checked::before{transform:scale(1)}
.fil-options input:checked+span{color:var(--txt)}
.fil-tags{padding-bottom:1px}
.fil-footer{display:grid;grid-template-columns:1fr 1.12fr;gap:12px;padding:14px 20px 18px;border-top:1px solid var(--stroke);flex:none}
.fil-btn{height:44px;border-radius:8px;display:flex;align-items:center;justify-content:center;gap:8px;font-size:12.5px;font-weight:700}
.fil-btn-clear{border:1px solid var(--stroke-strong);background:rgba(6,8,28,.14)}
.fil-btn-clear:hover{background:rgba(110,160,255,.08)}
.fil-btn-apply{color:#fff;background:linear-gradient(135deg,#1668D9,var(--blue));box-shadow:0 8px 20px -12px var(--blue)}
.fil-btn-apply:hover{filter:brightness(1.08)}

html[data-theme="light"] .fil-control,
html[data-theme="light"] .fil-periods button,
html[data-theme="light"] .fil-btn-clear{background:rgba(255,255,255,.55)}
html[data-theme="light"] .fil-control option{background:#fff}

@media(max-width:1250px){
  .gp-workspace.filters-open{grid-template-columns:minmax(0,1fr)}
  .fil-overlay{
    position:fixed;inset:0;z-index:1490;background:rgba(2,6,23,.62);backdrop-filter:blur(3px);
  }
  .gp-workspace.filters-open .fil-overlay{display:block}
  .fil-panel{
    position:fixed;z-index:1500;top:0;right:0;width:min(410px,100vw);height:100vh;min-height:0;
    border-radius:0;border-top:0;border-right:0;border-bottom:0;box-shadow:-18px 0 48px rgba(0,0,0,.35);
  }
}
@media(max-width:720px){
  .gp-toolbar{flex-direction:column;align-items:stretch}
  .gp-filter{justify-content:center}
  .gp-card{grid-template-columns:auto minmax(0,1fr);align-items:start}
  .gp-open{grid-column:1/-1;width:100%;height:36px}
  .gp-status{margin-left:0}
  .gp-footer{align-items:flex-start;flex-direction:column}
  .gp-pagination{width:100%;justify-content:space-between;flex-wrap:wrap}
  .fil-periods{grid-template-columns:1fr 1fr}
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="gp-workspace rise d2" id="gpWorkspace">
  <section class="gp-shell">
    <div class="gp-toolbar">
      <label class="gp-search">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <circle cx="11" cy="11" r="8"/>
          <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="search" id="gpSearch" placeholder="Buscar paciente por nombre, ID o teléfono..." autocomplete="off">
      </label>
      <button class="gp-filter" id="gpFilter" type="button" aria-expanded="false" aria-controls="filPanel">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
        Filtros
      </button>
    </div>

    <div class="gp-empty" id="gpEmpty">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      No se encontraron pacientes con los filtros seleccionados.
    </div>

    <div class="gp-list" id="gpList">
      <?php $__currentLoopData = $pacientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('galeria.paciente', $p['id'])); ?>"
           class="gp-card"
           data-patient="<?php echo e($p['id']); ?>"
           data-name="<?php echo e(mb_strtolower($p['nombre'])); ?>"
           data-code="<?php echo e(mb_strtolower($p['codigo'])); ?>"
           data-phone="<?php echo e(mb_strtolower($p['telefono'])); ?>"
           data-studies="<?php echo e(rawurlencode($p['filtros']->toJson())); ?>">
          <div class="gp-avatar" style="background:<?php echo e($p['color']); ?>"><?php echo e($p['ini']); ?></div>
          <div class="gp-main">
            <div class="gp-name-row">
              <div class="gp-name"><?php echo e($p['nombre']); ?></div>
              <span class="gp-status <?php echo e($p['estado'] === 'Activo' ? 'active' : 'inactive'); ?>">• <?php echo e($p['estado']); ?></span>
            </div>
            <div class="gp-id">
              ID: <?php echo e($p['codigo']); ?> <span style="margin:0 8px">•</span>
              <?php echo e($p['edad']); ?> <span style="margin:0 8px">•</span> <?php echo e($p['sexo']); ?>

            </div>
            <div class="gp-meta">
              <span>Último estudio: <?php echo e($p['ultimo']); ?></span>
              <span class="gp-dot"></span>
              <span class="gp-count">Estudios: <strong><?php echo e($p['estudios']); ?></strong></span>
              <span class="gp-dot"></span>
              <span class="gp-count">Fotos: <strong><?php echo e($p['fotos']); ?></strong></span>
              <span class="gp-dot"></span>
              <span class="gp-count">Videos: <strong><?php echo e($p['videos']); ?></strong></span>
            </div>
          </div>
          <span class="gp-open" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </span>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="gp-footer">
      <span id="gpResultCount"><?php echo e($pacientes->count()); ?> paciente(s) encontrado(s)</span>
      <div class="gp-pagination" id="gpPagination">
        <label>Mostrar:
          <select class="gp-per-page" id="gpPerPage">
            <option value="10">10 por página</option>
            <option value="20">20 por página</option>
            <option value="50">50 por página</option>
          </select>
        </label>
        <div class="gp-pages">
          <button class="gp-page" id="gpPrev" type="button" aria-label="Página anterior">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <span class="gp-page active" id="gpPage">1</span>
          <button class="gp-page" id="gpNext" type="button" aria-label="Página siguiente">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </div>
      </div>
    </div>
  </section>

  <?php echo $__env->make('galeria.filtros', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const workspace = document.getElementById('gpWorkspace');
  const panel = document.getElementById('filPanel');
  const overlay = document.getElementById('filOverlay');
  const filterButton = document.getElementById('gpFilter');
  const closeButton = document.getElementById('filClose');
  const search = document.getElementById('gpSearch');
  const cards = [...document.querySelectorAll('.gp-card')];
  const empty = document.getElementById('gpEmpty');
  const resultCount = document.getElementById('gpResultCount');
  const perPage = document.getElementById('gpPerPage');
  const pageLabel = document.getElementById('gpPage');
  const prev = document.getElementById('gpPrev');
  const next = document.getElementById('gpNext');
  const periodButtons = [...document.querySelectorAll('[data-period]')];
  let currentPage = 1;
  let filteredCards = cards;
  let applied = emptyFilters();

  cards.forEach(card => {
    try { card.studyFilters = JSON.parse(decodeURIComponent(card.dataset.studies || '%5B%5D')); }
    catch (e) { card.studyFilters = []; }
  });

  function emptyFilters(){
    return {patient:'', doctor:'', procedure:'', from:'', to:'', file:'', status:'', ai:'', finding:''};
  }

  function openFilters(){
    workspace.classList.add('filters-open');
    filterButton.classList.add('on');
    filterButton.setAttribute('aria-expanded', 'true');
    panel.setAttribute('aria-hidden', 'false');
    overlay.setAttribute('aria-hidden', 'false');
  }

  function closeFilters(){
    workspace.classList.remove('filters-open');
    filterButton.classList.remove('on');
    filterButton.setAttribute('aria-expanded', 'false');
    panel.setAttribute('aria-hidden', 'true');
    overlay.setAttribute('aria-hidden', 'true');
  }

  function localDate(date){
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  function choosePeriod(period){
    const now = new Date();
    let from = new Date(now);
    let to = new Date(now);
    if(period === 'week'){
      const mondayOffset = (now.getDay() + 6) % 7;
      from.setDate(now.getDate() - mondayOffset);
      to.setDate(from.getDate() + 6);
    } else if(period === 'month'){
      from = new Date(now.getFullYear(), now.getMonth(), 1);
      to = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    }
    if(period !== 'custom'){
      document.getElementById('filDesde').value = localDate(from);
      document.getElementById('filHasta').value = localDate(to);
    }
    periodButtons.forEach(button => button.classList.toggle('active', button.dataset.period === period));
  }

  function selectedValue(name){
    return document.querySelector(`input[name="${name}"]:checked`)?.value || '';
  }

  function readFilters(){
    return {
      patient: document.getElementById('filPaciente').value,
      doctor: document.getElementById('filMedico').value,
      procedure: document.getElementById('filProcedimiento').value,
      from: document.getElementById('filDesde').value,
      to: document.getElementById('filHasta').value,
      file: selectedValue('filArchivo'),
      status: selectedValue('filEstado'),
      ai: selectedValue('filIa'),
      finding: document.getElementById('filHallazgo').value
    };
  }

  function hasAppliedFilters(){
    return Object.values(applied).some(Boolean);
  }

  function studyMatches(study){
    if(applied.doctor && study.medico !== applied.doctor) return false;
    if(applied.procedure && study.procedimiento !== applied.procedure) return false;
    if(applied.from && (!study.fecha || study.fecha < applied.from)) return false;
    if(applied.to && (!study.fecha || study.fecha > applied.to)) return false;
    if(applied.file && !(study.archivos || []).includes(applied.file)) return false;
    if(applied.status && study.estado !== applied.status) return false;
    if(applied.ai === 'con' && !study.hallazgos_ia) return false;
    if(applied.ai === 'sin' && study.hallazgos_ia) return false;
    if(applied.finding && !(study.hallazgos || []).map(String).includes(applied.finding)) return false;
    return true;
  }

  function filterCards(){
    const query = search.value.trim().toLocaleLowerCase('es');
    const needsStudy = [
      applied.doctor, applied.procedure, applied.from, applied.to,
      applied.file, applied.status, applied.ai, applied.finding
    ].some(Boolean);

    filteredCards = cards.filter(card => {
      const matchesText = !query ||
        card.dataset.name.includes(query) ||
        card.dataset.code.includes(query) ||
        card.dataset.phone.includes(query);
      const matchesPatient = !applied.patient || card.dataset.patient === applied.patient;
      const matchesStudy = !needsStudy || card.studyFilters.some(studyMatches);
      return matchesText && matchesPatient && matchesStudy;
    });

    currentPage = 1;
    filterButton.classList.toggle('has-filters', hasAppliedFilters());
    renderPage();
  }

  function renderPage(){
    const size = Number(perPage.value);
    const pages = Math.max(1, Math.ceil(filteredCards.length / size));
    currentPage = Math.min(currentPage, pages);
    const start = (currentPage - 1) * size;
    const visible = new Set(filteredCards.slice(start, start + size));

    cards.forEach(card => { card.style.display = visible.has(card) ? '' : 'none'; });
    empty.style.display = filteredCards.length ? 'none' : 'block';
    resultCount.textContent = `${filteredCards.length} paciente(s) encontrado(s)`;
    pageLabel.textContent = currentPage;
    prev.disabled = currentPage <= 1;
    next.disabled = currentPage >= pages;
    document.getElementById('gpPagination').style.display = filteredCards.length ? '' : 'none';
  }

  function clearFilters(){
    document.getElementById('filPaciente').value = '';
    document.getElementById('filMedico').value = '';
    document.getElementById('filProcedimiento').value = '';
    document.getElementById('filDesde').value = '';
    document.getElementById('filHasta').value = '';
    document.getElementById('filHallazgo').value = '';
    panel.querySelectorAll('input[type="radio"]').forEach(input => {
      input.checked = input.value === '';
    });
    periodButtons.forEach(button => button.classList.remove('active'));
    applied = emptyFilters();
    filterCards();
  }

  filterButton.addEventListener('click', () => {
    workspace.classList.contains('filters-open') ? closeFilters() : openFilters();
  });
  closeButton.addEventListener('click', closeFilters);
  overlay.addEventListener('click', closeFilters);
  periodButtons.forEach(button => button.addEventListener('click', () => choosePeriod(button.dataset.period)));
  document.getElementById('filDesde').addEventListener('change', () => choosePeriod('custom'));
  document.getElementById('filHasta').addEventListener('change', () => choosePeriod('custom'));
  document.getElementById('filApply').addEventListener('click', () => {
    applied = readFilters();
    filterCards();
    if(window.matchMedia('(max-width: 1250px)').matches) closeFilters();
  });
  document.getElementById('filClear').addEventListener('click', clearFilters);
  search.addEventListener('input', filterCards);
  search.addEventListener('keydown', event => {
    if(event.key === 'Escape'){ search.value = ''; filterCards(); }
  });
  perPage.addEventListener('change', () => { currentPage = 1; renderPage(); });
  prev.addEventListener('click', () => { if(currentPage > 1){ currentPage--; renderPage(); } });
  next.addEventListener('click', () => {
    if(currentPage < Math.ceil(filteredCards.length / Number(perPage.value))){ currentPage++; renderPage(); }
  });
  document.addEventListener('keydown', event => {
    if(event.key === 'Escape' && workspace.classList.contains('filters-open')) closeFilters();
  });

  choosePeriod('month');
  renderPage();
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views/galeria/index.blade.php ENDPATH**/ ?>