<?php $__env->startSection('title', 'Configuración'); ?>
<?php $__env->startSection('active', 'configuracion'); ?>
<?php $__env->startSection('header-title', 'Configuracion'); ?>
<?php $__env->startSection('header-sub'); ?>
  Personaliza tu experiencia y gestiona los ajustes de tu cuenta y sistema
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ============ CONFIGURACIÓN ============ */
.cfg-tabs{display:flex;gap:28px;border-bottom:1px solid var(--stroke);margin-bottom:22px;flex-wrap:wrap}
.cfg-tab{position:relative;padding:0 2px 13px;font-size:14.5px;font-weight:600;color:var(--txt-soft);background:none;border:0;cursor:pointer;transition:color .15s}
.cfg-tab.active{color:var(--cyan)}
.cfg-tab.active::after{content:"";position:absolute;left:0;right:0;bottom:-1px;height:2px;border-radius:2px;background:var(--cyan)}
@media (hover:hover){.cfg-tab:hover{color:var(--txt)}}

.cfg-panel{display:none}
.cfg-panel.active{display:block}

/* Rejilla principal 3 columnas (legacy) */
.cfg-grid{display:grid;grid-template-columns:1.15fr 1.15fr .9fr;gap:18px;align-items:start}
@media (max-width:1200px){.cfg-grid{grid-template-columns:1fr 1fr}}
@media (max-width:900px){.cfg-grid{grid-template-columns:1fr}}

/* Rejilla General: contenido + lateral */
.cfg-grid-2{display:grid;grid-template-columns:1.8fr 1fr;gap:18px;align-items:start}
@media (max-width:980px){.cfg-grid-2{grid-template-columns:1fr}}

.cfg-col{display:flex;flex-direction:column;gap:18px;min-width:0}

.cfg-card-head{margin-bottom:6px}
.cfg-card-head h2{font-family:'Sora',sans-serif;font-size:16px;font-weight:700}
.cfg-card-head p{font-size:12.5px;color:var(--txt-soft);margin-top:3px}
.cfg-sec{margin-top:22px;padding-top:20px;border-top:1px solid var(--stroke)}

/* Fila de ajuste */
.cfg-row{display:flex;align-items:center;gap:13px;padding:13px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.cfg-row:last-child{border-bottom:0}
.cfg-ico{width:36px;height:36px;flex:none;border-radius:10px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.1);border:1px solid rgba(56,199,244,.22)}
.cfg-ico svg{width:18px;height:18px}
.cfg-info{flex:1;min-width:0}
.cfg-info .t{font-size:13.5px;font-weight:600}
.cfg-info .d{font-size:11.5px;color:var(--txt-soft);margin-top:2px}

/* Select */
.cfg-select{position:relative;flex:none}
.cfg-select select{appearance:none;-webkit-appearance:none;font:inherit;font-size:12.5px;font-weight:600;color:var(--txt);
  background:var(--panel-2);border:1px solid var(--stroke-strong);border-radius:9px;padding:9px 34px 9px 13px;cursor:pointer;min-width:150px}
.cfg-select svg{position:absolute;right:11px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--txt-soft);pointer-events:none}

/* Toggle switch */
.sw{position:relative;width:42px;height:23px;flex:none;cursor:pointer}
.sw input{position:absolute;opacity:0;width:100%;height:100%;margin:0;cursor:pointer}
.sw .track{position:absolute;inset:0;border-radius:99px;background:var(--off);transition:background .2s}
.sw .knob{position:absolute;top:3px;left:3px;width:17px;height:17px;border-radius:50%;background:#fff;transition:transform .2s var(--ease-out);box-shadow:0 1px 3px rgba(0,0,0,.3)}
.sw input:checked ~ .track{background:linear-gradient(135deg,var(--blue),var(--cyan))}
.sw input:checked ~ .knob{transform:translateX(19px)}

/* Opciones de notificación por canal (correo / pantalla) */
.cfg-notif-opts{display:flex;align-items:center;gap:8px;flex:none}
.cfg-notif-opt{position:relative;width:34px;height:34px;border-radius:9px;display:grid;place-items:center;cursor:pointer;transition:all .15s}
.cfg-notif-opt input{position:absolute;opacity:0;width:100%;height:100%;margin:0;cursor:pointer}
.cfg-notif-opt svg{width:16px;height:16px}
/* Estado inactivo: rojo para alto contraste visual */
.cfg-notif-opt.email,.cfg-notif-opt.screen{color:#f87171;background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.25)}
.cfg-notif-opt.email:hover,.cfg-notif-opt.screen:hover{border-color:rgba(239,68,68,.55)}
/* Correo electrónico activo: azul */
.cfg-notif-opt.email:has(input:checked){color:#2563eb;background:rgba(59,130,246,.14);border-color:rgba(59,130,246,.5)}
.cfg-notif-opt.email:has(input:checked):hover{border-color:rgba(59,130,246,.55)}
/* Aviso en pantalla activo: naranja/campana */
.cfg-notif-opt.screen:has(input:checked){color:#d97706;background:rgba(245,158,11,.14);border-color:rgba(245,158,11,.5)}
.cfg-notif-opt.screen:has(input:checked):hover{border-color:rgba(245,158,11,.55)}

/* Almacenamiento (barra reutilizable) */
.store-box{padding:14px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2);margin-bottom:14px}
.store-top{display:flex;align-items:center;gap:9px;font-size:13px;font-weight:600;margin-bottom:11px}
.store-top svg{width:17px;height:17px;color:var(--cyan)}
.store-meta{display:flex;justify-content:space-between;font-size:11.5px;color:var(--txt-soft);margin-bottom:7px}
.store-bar{height:9px;border-radius:99px;background:rgba(110,160,255,.14);overflow:hidden}
.store-bar i{display:block;height:100%;width:0;border-radius:99px;background:linear-gradient(90deg,var(--blue),var(--cyan));transition:width 1.1s var(--ease-out)}
.store-legend{display:flex;gap:16px;font-size:11px;color:var(--txt-soft);margin-top:10px}
.store-legend span{display:flex;align-items:center;gap:6px}
.store-legend i{width:8px;height:8px;border-radius:50%;display:inline-block}
.store-legend .used{background:var(--cyan)}
.store-legend .free{background:var(--off)}

/* Perfil */
.prof-card{text-align:center}
.prof-ava-wrap{position:relative;width:108px;height:108px;margin:6px auto 12px}
.prof-ava{width:108px;height:108px;border-radius:50%;object-fit:cover;border:3px solid var(--stroke-strong);background:var(--panel-2);display:block}
.prof-cam{position:absolute;right:2px;bottom:2px;width:32px;height:32px;border-radius:50%;display:grid;place-items:center;color:#fff;background:var(--blue);border:2px solid var(--card);box-shadow:0 2px 8px rgba(0,0,0,.3);transition:background .15s,transform .15s}
.prof-cam svg{width:15px;height:15px}
.prof-cam:hover{background:var(--cyan)}
.prof-cam:active{transform:scale(.92)}
.prof-name{font-family:'Sora',sans-serif;font-size:17px;font-weight:700}
.prof-role{font-size:12.5px;color:var(--txt-soft);margin-top:2px}
.prof-lines{margin:14px 0 16px;display:flex;flex-direction:column;gap:6px;font-size:12.5px;color:var(--txt-soft)}
.prof-edit{display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);font-size:13px;font-weight:700;color:var(--cyan);transition:background-color .15s}
.prof-edit svg{width:15px;height:15px}
@media (hover:hover){.prof-edit:hover{background:rgba(56,199,244,.1)}}

/* Acciones rápidas */
.qa{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid rgba(110,160,255,.08);width:100%;text-align:left;transition:opacity .15s}
.qa:last-child{border-bottom:0}
.qa .qico{width:34px;height:34px;flex:none;border-radius:9px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.1)}
.qa .qico svg{width:17px;height:17px}
.qa .t{font-size:13px;font-weight:600}
.qa .d{font-size:11px;color:var(--txt-soft);margin-top:1px}
.qa.danger .qico{color:var(--red);background:rgba(255,90,110,.1)}
.qa.danger .t{color:var(--red)}
@media (hover:hover){.qa:hover{opacity:.75}}

/* ===== Panel Plan y almacenamiento ===== */
.pl-grid{display:grid;grid-template-columns:1.55fr 1fr;gap:18px;align-items:start}
@media (max-width:1000px){.pl-grid{grid-template-columns:1fr}}

.pl-plan{display:flex;align-items:center;gap:14px;padding:16px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2)}
.pl-plan .pl-ico{width:46px;height:46px;flex:none;border-radius:12px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,#7c5cff,#a47bff)}
.pl-plan .pl-ico svg{width:24px;height:24px}
.pl-plan .pl-info{flex:1;min-width:0}
.pl-plan .pl-info b{font-family:'Sora',sans-serif;font-size:15.5px;font-weight:700}
.pl-plan .pl-info .badge{display:inline-block;margin-left:8px;font-size:10px;font-weight:700;color:var(--green);background:rgba(61,220,151,.14);padding:2px 8px;border-radius:6px;vertical-align:middle}
.pl-plan .pl-info p{font-size:11.5px;color:var(--txt-soft);margin-top:3px}
.pl-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);font-size:13px;font-weight:700;color:var(--txt);background:var(--panel-2);white-space:nowrap;transition:background-color .15s}
@media (hover:hover){.pl-btn:hover{background:rgba(110,160,255,.1)}}

.pl-sub{font-size:11px;color:var(--txt-soft);margin:4px 0 18px}

/* archivos por tipo */
.pl-files{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:16px}
@media (max-width:560px){.pl-files{grid-template-columns:repeat(2,1fr)}}
.pl-file{padding:13px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2)}
.pl-file .fi{width:32px;height:32px;border-radius:9px;display:grid;place-items:center;margin-bottom:9px}
.pl-file .fi svg{width:17px;height:17px}
.pl-file .ft{font-size:11.5px;color:var(--txt-soft)}
.pl-file .fv{font-family:'Sora',sans-serif;font-size:16px;font-weight:800;margin:4px 0 1px}
.pl-file .fp{font-size:11px;color:var(--txt-soft)}
.fi.c1{color:var(--cyan);background:rgba(56,199,244,.12)}
.fi.c2{color:#a47bff;background:rgba(124,92,255,.14)}
.fi.c3{color:var(--green);background:rgba(61,220,151,.14)}
.fi.c4{color:var(--orange);background:rgba(245,158,45,.14)}

.pl-detail{margin-top:16px;padding:15px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2)}
.pl-detail .t{font-size:13px;font-weight:600}
.pl-detail .d{font-size:11.5px;color:var(--txt-soft);margin:3px 0 11px}

/* planes disponibles */
.pl-plans{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-top:14px}
@media (max-width:1100px){.pl-plans{grid-template-columns:repeat(2,1fr)}}
@media (max-width:560px){.pl-plans{grid-template-columns:1fr}}
.pl-card{padding:16px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2);display:flex;flex-direction:column}
.pl-card.current{border-color:var(--cyan);box-shadow:0 0 0 1px var(--cyan) inset}
.pl-card .pc-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:4px}
.pl-card .pc-ico svg{width:22px;height:22px}
.pl-card .pc-badge{font-size:9.5px;font-weight:700;color:var(--cyan);background:rgba(56,199,244,.14);padding:2px 7px;border-radius:6px}
.pl-card h4{font-family:'Sora',sans-serif;font-size:14.5px;font-weight:700;margin-top:6px}
.pl-card .pc-gb{font-size:11.5px;color:var(--txt-soft);margin-top:1px}
.pl-card .pc-feat{list-style:none;margin:12px 0;padding:0;display:flex;flex-direction:column;gap:8px;flex:1}
.pl-card .pc-feat li{display:flex;align-items:flex-start;gap:7px;font-size:11.5px;color:var(--txt-soft)}
.pl-card .pc-feat svg{width:14px;height:14px;color:var(--green);flex:none;margin-top:1px}
.pl-card .pc-price{font-family:'Sora',sans-serif;font-size:21px;font-weight:800;margin-top:auto}
.pl-card .pc-price span{font-size:11.5px;font-weight:500;color:var(--txt-soft)}
.pl-card .pc-cta{margin-top:12px;padding:9px;border-radius:var(--r-md);font-size:12.5px;font-weight:700;text-align:center;border:1px solid var(--stroke-strong);color:var(--txt);transition:background-color .15s,opacity .15s}
.pl-card .pc-cta.disabled{opacity:.45;cursor:default}
@media (hover:hover){.pl-card .pc-cta:not(.disabled):hover{background:rgba(110,160,255,.1)}}

/* columna derecha: resumen */
.pl-summary-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:11px 0;border-bottom:1px solid rgba(110,160,255,.08);font-size:12.5px}
.pl-summary-row:last-child{border-bottom:0}
.pl-summary-row .k{color:var(--txt-soft)}
.pl-summary-row .v{font-weight:600;text-align:right}
.pl-pay{display:inline-flex;align-items:center;gap:7px}
.pl-pay .pl-mc{width:26px;height:17px;border-radius:3px;background:#eb001b;position:relative;display:inline-block}
.pl-pay .pl-mc::after{content:"";position:absolute;left:9px;top:0;width:17px;height:17px;border-radius:3px;background:#f79e1b;opacity:.85}

.pl-link{display:flex;align-items:center;justify-content:space-between;padding:11px 0;border-bottom:1px solid rgba(110,160,255,.08);font-size:12.5px;color:var(--txt)}
.pl-link:last-of-type{border-bottom:0}
.pl-link .v{color:var(--txt-soft);font-size:12px}

.pl-wide-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;margin-top:14px;padding:11px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);font-size:12.5px;font-weight:700;color:var(--cyan);transition:background-color .15s}
.pl-wide-btn svg{width:15px;height:15px}
@media (hover:hover){.pl-wide-btn:hover{background:rgba(56,199,244,.1)}}

.pl-chart-wrap{display:flex;gap:8px;margin-top:14px}
.pl-chart-y{display:flex;flex-direction:column;justify-content:space-between;font-size:9.5px;color:var(--txt-soft);text-align:right;padding:4px 0;height:130px}
.pl-chart{flex:1;min-width:0}
.pl-chart svg{width:100%;height:130px;display:block}

.pl-reco{display:flex;align-items:center;gap:10px;margin-bottom:6px}
.pl-reco .ri{width:34px;height:34px;flex:none;border-radius:9px;display:grid;place-items:center;color:var(--orange);background:rgba(245,158,45,.14)}
.pl-reco .ri svg{width:18px;height:18px}

/* ===== RESPONSIVE: MÓVILES Y TABLETS ===== */
@media (max-width:768px){
  /* Tabs más compactos */
  .cfg-tabs{gap:16px;margin-bottom:16px}
  .cfg-tab{font-size:13px;padding-bottom:10px}
  
  /* Plan actual */
  .pl-plan{flex-direction:column;align-items:flex-start;gap:12px;padding:14px}
  .pl-plan .pl-ico{width:40px;height:40px}
  .pl-plan .pl-ico svg{width:20px;height:20px}
  .pl-plan .pl-info b{font-size:14px}
  .pl-plan .pl-info p{font-size:11px}
  .pl-btn{width:100%;justify-content:center;padding:9px 14px;font-size:12px}
  
  /* Archivos por tipo */
  .pl-files{grid-template-columns:1fr;gap:10px}
  .pl-file{padding:11px}
  .pl-file .fi{width:28px;height:28px;margin-bottom:7px}
  .pl-file .fi svg{width:15px;height:15px}
  .pl-file .fv{font-size:14px}
  
  /* Planes disponibles */
  .pl-plans{gap:12px}
  .pl-card{padding:14px}
  .pl-card h4{font-size:13.5px}
  .pl-card .pc-price{font-size:18px}
  .pl-card .pc-feat{gap:6px;margin:10px 0}
  .pl-card .pc-feat li{font-size:11px}
  
  /* Botones de intervalo más compactos */
  .pc-interval{flex-wrap:wrap;gap:4px!important}
  .pc-int-btn{padding:5px 10px;font-size:10.5px}
  
  /* Resumen del plan */
  .pl-summary-row{font-size:11.5px;padding:9px 0}
  .pl-link{font-size:11.5px;padding:9px 0}
  .pl-wide-btn{font-size:11.5px;padding:9px;margin-top:12px}
  
  /* Gráfico */
  .pl-chart-wrap{margin-top:12px}
  .pl-chart-y{font-size:8.5px;height:110px}
  .pl-chart svg{height:110px}
  
  /* Cards de configuración */
  .cfg-card-head h2{font-size:14.5px}
  .cfg-card-head p{font-size:11.5px}
  
  /* Filas de configuración */
  .cfg-row{gap:10px;padding:11px 0}
  .cfg-ico{width:32px;height:32px}
  .cfg-ico svg{width:16px;height:16px}
  .cfg-info .t{font-size:12.5px}
  .cfg-info .d{font-size:10.5px}
  .cfg-select select{font-size:11.5px;padding:8px 30px 8px 11px;min-width:130px}
  
  /* Perfil */
  .prof-ava-wrap{width:90px;height:90px}
  .prof-ava{width:90px;height:90px}
  .prof-cam{width:28px;height:28px}
  .prof-cam svg{width:13px;height:13px}
  .prof-name{font-size:15px}
  .prof-role{font-size:11.5px}
  .prof-lines{font-size:11.5px;margin:12px 0 14px}
  .prof-edit{font-size:12px;padding:9px 15px}
  
  /* Acciones rápidas */
  .qa{gap:10px;padding:10px 0}
  .qa .qico{width:30px;height:30px}
  .qa .qico svg{width:15px;height:15px}
  .qa .t{font-size:12px}
  .qa .d{font-size:10px}
}

@media (max-width:480px){
  /* Tabs en scroll horizontal */
  .cfg-tabs{overflow-x:auto;-webkit-overflow-scrolling:touch;gap:20px;padding-bottom:2px}
  .cfg-tab{white-space:nowrap;font-size:12.5px}
  
  /* Planes en una sola columna siempre */
  .pl-plans{grid-template-columns:1fr!important}
  
  /* Almacenamiento más compacto */
  .store-top{font-size:12px}
  .store-top svg{width:15px;height:15px}
  .store-meta{font-size:10.5px}
  .store-bar{height:7px}
  .store-legend{font-size:10px;gap:12px;flex-wrap:wrap}
  
  /* Botones de intervalo en columna */
  .pc-interval{flex-direction:column!important;gap:6px!important}
  .pc-int-btn{width:100%;text-align:center}
  
  /* Cards más compactos */
  .card{padding:14px!important}
  .cfg-card-head h2{font-size:13.5px}
  .cfg-card-head p{font-size:10.5px}
  
  /* Select más pequeño */
  .cfg-select select{min-width:110px;font-size:11px;padding:7px 28px 7px 10px}
  .cfg-select svg{width:13px;height:13px;right:9px}
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

  
  <div class="cfg-tabs rise d1">
    <button type="button" class="cfg-tab active" data-tab="general">General</button>
    <button type="button" class="cfg-tab" data-tab="qr-preregistro">QR y Pre-registro</button>
    <button type="button" class="cfg-tab" data-tab="plan">Plan y almacenamiento</button>
    <button type="button" class="cfg-tab" data-tab="integraciones">Integraciones</button>
    <button type="button" class="cfg-tab" data-tab="seguridad">Seguridad</button>
    <button type="button" class="cfg-tab" data-tab="perfil">Perfil</button>
  </div>

  <?php echo $__env->make('configuracion.sections.general', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php echo $__env->make('configuracion.sections.qr-preregistro', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php echo $__env->make('configuracion.sections.plan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php echo $__env->make('configuracion.sections.integraciones', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php echo $__env->make('configuracion.sections.seguridad', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php echo $__env->make('configuracion.sections.perfil', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  /* Configuración del usuario (desde la base de datos) */
  const SETTINGS = <?php echo json_encode($userSettings ?? [], 15, 512) ?>;
  const SAVE_URL = "<?php echo e(route('configuracion.general.update')); ?>";
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content
    || "<?php echo e(csrf_token()); ?>";

  /* Pestañas */
  const tabs = document.querySelectorAll('.cfg-tab');
  const panels = document.querySelectorAll('.cfg-panel');
  function activateTab(tabName, updateUrl = false){
    const panel = document.querySelector(`.cfg-panel[data-panel="${tabName}"]`);
    if (!panel) tabName = 'general';

    tabs.forEach(x => x.classList.remove('active'));
    panels.forEach(p => p.classList.remove('active'));
    const tab = document.querySelector(`.cfg-tab[data-tab="${tabName}"]`);
    const activePanel = document.querySelector(`.cfg-panel[data-panel="${tabName}"]`);
    if (tab) tab.classList.add('active');
    if (activePanel) activePanel.classList.add('active');

    if (updateUrl) {
      const url = new URL(window.location.href);
      if (tabName === 'general') {
        url.searchParams.delete('tab');
      } else {
        url.searchParams.set('tab', tabName);
      }
      window.history.replaceState({}, '', url);
    }
  }
  tabs.forEach(t => t.addEventListener('click', () => activateTab(t.dataset.tab, true)));

  const urlParams = new URLSearchParams(window.location.search);
  const initialTab = urlParams.get('tab');
  if (initialTab) activateTab(initialTab);

  /* Barras de almacenamiento */
  const bars = document.querySelectorAll('.store-bar i');
  setTimeout(() => bars.forEach(b => { b.style.width = b.dataset.w + '%'; }), 250);

  /* Foto de perfil: cambiar y previsualizar */
  const cam = document.getElementById('profCam');
  const fileInput = document.getElementById('profPhoto');
  const ava = document.getElementById('profAva');
  if (cam && fileInput && ava) {
    const saved = localStorage.getItem('enclaii-profile-photo');
    if (saved) ava.src = saved;
    cam.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', () => {
      const file = fileInput.files && fileInput.files[0];
      if (!file || !file.type.startsWith('image/')) return;
      const reader = new FileReader();
      reader.onload = e => {
        ava.src = e.target.result;
        try { localStorage.setItem('enclaii-profile-photo', e.target.result); } catch (err) {}
      };
      reader.readAsDataURL(file);
    });
  }

  /* Selector de idioma: aplica traducción global ES/EN */
  const langSel = document.getElementById('cfgLang');
  if (langSel) {
    const current = (window.enclaiiI18n && window.enclaiiI18n.get())
      || localStorage.getItem('enclaii-lang') || 'es';
    langSel.value = current;
  }

  /* ===== Preferencias generales persistentes (base de datos) ===== */
  function applyEffect(effect, on){
    if (effect === 'animations') {
      document.documentElement.dataset.animations = on ? 'on' : 'off';
      // Espejo en localStorage para el render temprano del layout en otras páginas.
      try { localStorage.setItem('enclaii-pref-animations', on ? '1' : '0'); } catch (e) {}
    } else if (effect === 'compact') {
      document.documentElement.dataset.compact = on ? 'on' : 'off';
      try { localStorage.setItem('enclaii-pref-compact', on ? '1' : '0'); } catch (e) {}
    } else if (effect === 'reading') {
      document.documentElement.dataset.reading = on ? 'on' : 'off';
      try { localStorage.setItem('enclaii-pref-reading_mode', on ? '1' : '0'); } catch (e) {}
    } else if (effect === 'push' && on && 'Notification' in window) {
      if (Notification.permission === 'default') Notification.requestPermission();
    }
  }

  /* Cola de guardado con debounce: agrupa cambios y los manda al backend */
  let pending = {};
  let saveTimer = null;
  function queueSave(key, value){
    pending[key] = value;
    clearTimeout(saveTimer);
    saveTimer = setTimeout(flushSave, 450);
  }
  async function flushSave(){
    if (Object.keys(pending).length === 0) return;
    const payload = pending; pending = {};
    try {
      const res = await fetch(SAVE_URL, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
      });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      toast('Configuración guardada');
      Object.entries(payload).forEach(([key, value]) => {
        document.dispatchEvent(new CustomEvent('enclaiiSettingChanged', { detail: { key, value } }));
      });
    } catch (err) {
      toast('No se pudo guardar la configuración');
    }
  }

  function toast(msg){
    let t = document.getElementById('cfgToast');
    if (!t) {
      t = document.createElement('div');
      t.id = 'cfgToast';
      t.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(10px);'
        + 'background:var(--card);color:var(--txt);border:1px solid var(--stroke-strong);'
        + 'padding:10px 18px;border-radius:12px;font-size:13.5px;font-weight:600;z-index:9999;'
        + 'box-shadow:0 12px 30px -10px rgba(0,0,0,.5);opacity:0;transition:opacity .2s,transform .2s;pointer-events:none';
      document.body.appendChild(t);
    }
    t.textContent = msg;
    requestAnimationFrame(() => { t.style.opacity = '1'; t.style.transform = 'translateX(-50%) translateY(0)'; });
    clearTimeout(t._h);
    t._h = setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateX(-50%) translateY(10px)'; }, 1600);
  }

  const listGroups = {};
  document.querySelectorAll('[data-setting-list]').forEach(el => {
    const key = el.dataset.settingList;
    if (!listGroups[key]) listGroups[key] = [];
    listGroups[key].push(el);
  });

  Object.entries(listGroups).forEach(([key, items]) => {
    const saved = Array.isArray(SETTINGS[key]) ? SETTINGS[key] : [];
    items.forEach(item => {
      item.checked = saved.includes(item.value);
      item.addEventListener('change', () => {
        queueSave(key, items.filter(input => input.checked).map(input => input.value));
      });
    });
  });

  document.querySelectorAll('[data-setting]').forEach(el => {
    if (el.id === 'cfgTheme' || el.id === 'cfgLang') return; // ya tienen manejo propio
    const key = el.dataset.setting;
    const saved = SETTINGS[key];

    if (el.type === 'checkbox') {
      if (saved !== undefined) el.checked = !!saved;
      if (el.dataset.effect) applyEffect(el.dataset.effect, el.checked);
      el.addEventListener('change', () => {
        if (el.dataset.effect) applyEffect(el.dataset.effect, el.checked);
        queueSave(key, el.checked);
      });
    } else if (el.tagName === 'SELECT') { // select: el value de cada opción es su texto
      if (saved !== undefined && saved !== null) {
        const match = Array.from(el.options).find(o => o.value === saved || o.text === saved);
        if (match) el.value = match.value;
      }
      el.addEventListener('change', () => {
        queueSave(key, el.value);
      });
    } else {
      if (saved !== undefined && saved !== null) el.value = saved;
      const eventName = el.tagName === 'TEXTAREA' || el.type === 'text' ? 'input' : 'change';
      el.addEventListener(eventName, () => {
        queueSave(key, el.value);
      });
    }
  });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\gmedi\enclaii-backend\resources\views/configuracion/index.blade.php ENDPATH**/ ?>