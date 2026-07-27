<?php $__env->startSection('title', 'Reportes'); ?>
<?php $__env->startSection('active', 'ia-reportes'); ?>
<?php $__env->startSection('header-title', 'Reportes'); ?>
<?php $__env->startSection('header-sub'); ?>
  Genera, analiza y revisa reportes inteligentes impulsados por IA
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ============ ESTILOS SOLO DE IA REPORTES ============ */

/* Compactar tarjetas en esta pantalla para que todo entre de primera */
.main{padding-top:20px;padding-bottom:22px}
.head{margin-bottom:16px}
.card{padding:16px 18px}
.stat::after{
  content:"";
  position:absolute;top:0;bottom:0;left:-75%;width:55%;
  background:linear-gradient(100deg,transparent 0%,rgba(255,255,255,.13) 50%,transparent 100%);
  transform:skewX(-18deg);
  pointer-events:none;
  animation:cardShine 5.6s ease-in-out infinite;
}
/* En tema claro el brillo blanco no se aprecia: usar un brillo azul clarito */
html[data-theme="light"] .stat::after{
  background:linear-gradient(100deg,transparent 0%,rgba(56,199,244,.22) 50%,transparent 100%);
}
/* El barrido ocurre solo en el primer 25% del ciclo; el resto queda fuera.
   Con los retardos escalonados, una tarjeta termina de brillar y empieza la siguiente. */
@keyframes cardShine{
  0%{left:-75%}
  25%{left:150%}
  100%{left:150%}
}
@keyframes scanner{
  from{transform:rotate(0deg)}
  to{transform:rotate(360deg)}
}
.stat.d2::after{animation-delay:0s}
.stat.d3::after{animation-delay:1.4s}
.stat.d4::after{animation-delay:2.8s}
.stat.d5::after{animation-delay:4.2s}
@media (prefers-reduced-motion: reduce){
  .stat::after{display:none}
}
.card h3{margin-bottom:10px}
.tbl-link{margin-top:6px}

/* Fila de KPIs */
.stats{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:14px;
  margin-bottom:14px;
}
.stat{
  position:relative;overflow:hidden;
  display:flex;align-items:center;gap:16px;
  padding:14px 18px;
}
.stat .ico{
  width:60px;height:60px;flex:none;
  border-radius:50%;
  display:grid;place-items:center;
}
.stat-body{min-width:0}
.stat h3{font-size:14px;font-weight:700;letter-spacing:.01em;margin-bottom:2px}
.stat .num{
  font-family:'Sora',sans-serif;
  font-size:34px;
  font-weight:800;
  line-height:1.05;
  color:var(--txt);
}
.stat .tag{font-size:13px;color:var(--txt);margin-top:2px;font-weight:600}
.stat .trend{
  display:inline-flex;align-items:center;gap:5px;
  margin-top:8px;
  font-size:12.5px;
  font-weight:700;
}
.stat .trend.up{color:var(--green)}
.stat .trend .vs{color:var(--txt-soft);font-weight:500}
/* Color por tarjeta: aro del icono + icono + título */
.stat.blue{border-color:rgba(46,123,246,.45)}
.stat.blue .ico{border:1.5px solid rgba(56,199,244,.55);background:rgba(56,199,244,.08);color:var(--cyan)}
.stat.blue h3{color:var(--cyan)}
.stat.orange{border-color:rgba(245,158,45,.5)}
.stat.orange .ico{border:1.5px solid rgba(245,158,45,.55);background:rgba(245,158,45,.08);color:var(--orange)}
.stat.orange h3{color:var(--orange)}
.stat.red{border-color:rgba(255,90,110,.5)}
.stat.red .ico{border:1.5px solid rgba(255,90,110,.55);background:rgba(255,90,110,.08);color:var(--red)}
.stat.red h3{color:var(--red)}
.stat.green{border-color:rgba(61,220,151,.5)}
.stat.green .ico{border:1.5px solid rgba(61,220,151,.55);background:rgba(61,220,151,.08);color:var(--green)}
.stat.green h3{color:var(--green)}

/* Layout principal: tabla + panel lateral */
.rep-grid{
  display:grid;
  grid-template-columns:2.4fr 1fr;
  gap:14px;
  margin-bottom:14px;
  align-items:stretch;
}
.rep-tbl{grid-column:1;grid-row:1;min-width:0}
.card-pred{grid-column:1;grid-row:2;min-width:0}
.rep-hall{grid-column:2;grid-row:1}
.rep-grid .recs{grid-column:2;grid-row:2}

/* Cabecera de la tarjeta de reportes */
.card-head{
  display:flex;align-items:center;justify-content:space-between;
  gap:12px;
  margin-bottom:8px;
  flex-wrap:wrap;
}
.card-head h3{margin-bottom:0;font-size:15px}
.btn-gen{
  display:inline-flex;align-items:center;gap:7px;
  padding:8px 13px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:linear-gradient(135deg,#1668D9,var(--blue));
  color:#fff;
  font-size:13px;
  font-weight:700;
  white-space:nowrap;
  box-shadow:0 8px 22px -8px rgba(46,123,246,.6);
  transition:filter 150ms ease, transform 160ms var(--ease-out);
}
.btn-gen:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){
  .btn-gen:hover{filter:brightness(1.1)}
}
.card-head-actions{display:flex;align-items:center;gap:9px;flex-wrap:wrap}
.btn-gen.secondary{
  background:var(--panel-2);
  color:var(--cyan);
  border:1px solid var(--stroke-strong);
  box-shadow:none;
}
@media (hover:hover) and (pointer:fine){
  .btn-gen.secondary:hover{filter:none;background:rgba(56,199,244,.1)}
}

/* Tabla de reportes */
.tbl-wrap{overflow-x:visible}
table.tbl{width:100%;border-collapse:collapse;font-size:14px}
.tbl th{
  text-align:left;
  font-size:12.5px;
  font-weight:600;
  color:var(--txt-soft);
  padding:4px 12px;
  border-bottom:1px solid var(--stroke);
}
.tbl td{padding:3px 12px;border-bottom:1px solid rgba(110,160,255,.08)}
.tbl tr:last-child td{border-bottom:0}
.tbl tbody tr{transition:background-color 150ms ease}
@media (hover:hover) and (pointer:fine){
  .tbl tbody tr:hover{background:rgba(110,160,255,.05)}
}
.pat{display:flex;align-items:center;gap:10px;font-weight:600}
.pat .mini{
  width:28px;height:28px;
  border-radius:50%;
  background:rgba(46,123,246,.2);
  border:1px solid var(--stroke-strong);
  display:grid;place-items:center;
  font-size:10.5px;font-weight:700;
  color:var(--cyan);
}
.tbl .date{line-height:1.3}
.tbl .date small{display:block;color:var(--txt-soft);font-size:11.5px}

/* Mini dona de confianza IA */
.conf{display:flex;align-items:center;gap:8px}
.conf .ring{position:relative;width:36px;height:36px;flex:none}
.conf .ring svg{width:100%;height:100%;transform:rotate(-90deg)}
.conf .ring circle{fill:none;stroke-width:5;stroke-linecap:round}
.conf .ring .track{stroke:rgba(110,160,255,.14)}
.conf .ring .val{stroke:var(--cyan)}
.conf .ring span{
  position:absolute;inset:0;
  display:grid;place-items:center;
  font-size:10.5px;font-weight:700;
}

/* Acciones de fila */
.row-actions{display:flex;align-items:center;gap:6px}
.row-actions button,.row-actions a{
  width:28px;height:28px;
  display:grid;place-items:center;
  border-radius:8px;
  color:var(--txt-soft);
  transition:color 150ms ease, background-color 150ms ease;
}
@media (hover:hover) and (pointer:fine){
  .row-actions button:hover,.row-actions a:hover{color:var(--cyan);background:rgba(56,199,244,.1)}
}
.row-actions svg{width:17px;height:17px}

/* Panel: hallazgos detectados */
.find{padding:8px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.find:last-of-type{border-bottom:0}
.find .top{
  display:flex;align-items:center;justify-content:space-between;
  font-size:13.5px;font-weight:600;
  margin-bottom:7px;
}
.find .top b{font-family:'Sora',sans-serif;font-weight:700;color:var(--txt)}
.bar{height:7px;border-radius:99px;background:rgba(110,160,255,.12);overflow:hidden}
.bar i{display:block;height:100%;border-radius:99px;width:0;transition:width 1.1s var(--ease-out)}
.bar.c1 i{background:linear-gradient(90deg,var(--blue),var(--cyan))}
.bar.c2 i{background:linear-gradient(90deg,#7B5CF6,#A98BFF)}
.bar.c3 i{background:linear-gradient(90deg,var(--orange),#FFC368)}
.bar.c4 i{background:linear-gradient(90deg,var(--green),#7BF0BE)}
.bar.c5 i{background:linear-gradient(90deg,var(--red),#FF98A6)}

/* Panel: recomendaciones */
.recs ul{list-style:none}
.recs li{
  display:flex;align-items:flex-start;gap:10px;
  font-size:13.5px;line-height:1.4;
  padding:6px 0;
}
.recs li svg{width:18px;height:18px;flex:none;color:var(--green);margin-top:1px}

/* Estudios sin reporte (fila inferior) */
.card-pred{
  border-color:rgba(56,199,244,.4);
  display:grid;
  grid-template-columns:1.05fr 1.5fr 1fr;
  gap:0;
  align-items:center;
}
.card-pred > *{padding:0 22px}
.card-pred > *:first-child{padding-left:0}
.card-pred > *:last-child{padding-right:0}
.card-pred > * + *{border-left:1px solid var(--stroke)}
.pred-pat{display:flex;align-items:center;gap:10px;font-size:13.5px;margin-bottom:6px}
.pred-pat .mini{
  width:30px;height:30px;border-radius:50%;
  background:rgba(46,123,246,.2);
  border:1px solid var(--stroke-strong);
  display:grid;place-items:center;
  font-size:10.5px;font-weight:700;color:var(--cyan);
  flex:none;
}
.pred-meta{font-size:12.5px;color:var(--txt-soft);line-height:1.6}
.prob{display:flex;align-items:center;justify-content:center;gap:20px}
.prob-info{min-width:0}
.prob-info h4{margin-bottom:0}
.prob-num{
  font-family:'Sora',sans-serif;
  font-size:38px;font-weight:800;
  color:var(--cyan);
  line-height:1;
  margin:8px 0;
}
.prob-sub{font-size:12px;color:var(--txt-soft);line-height:1.45;max-width:160px}
.gauge .stomach{
  width:62px;height:62px;position:relative;overflow:hidden;
  background:#173d72;
  -webkit-mask:url('/images/Vector.png') no-repeat center/contain;
          mask:url('/images/Vector.png') no-repeat center/contain;
}
/* En tema claro se rellena la silueta del estómago de azul fuerte */
html[data-theme="light"] .gauge .stomach{background:var(--blue)}
/* Destello de luz que recorre la silueta */
.gauge .stomach::before{
  content:"";
  position:absolute;top:0;bottom:0;left:-75%;width:55%;
  background:linear-gradient(100deg,transparent 0%,rgba(255,255,255,.35) 50%,transparent 100%);
  pointer-events:none;
  animation:cardShine 4s ease-in-out infinite;
}
.gauge .stomach .water{position:absolute;inset:0;width:100%;height:100%;z-index:1}
.prob h4{
  font-family:'Sora',sans-serif;
  font-size:13.5px;font-weight:600;
  color:var(--txt-soft);
  margin-bottom:14px;
}
.gauge{position:relative;width:122px;height:122px;margin:0 auto}
.gauge::before{
  content:"";
  position:absolute;inset:0;border-radius:50%;
  background:conic-gradient(from 0deg, transparent 0deg, rgba(255,255,255,.25) 40deg, transparent 80deg);
  -webkit-mask:radial-gradient(circle, transparent 45px, rgba(0,0,0,.85) 48px, rgba(0,0,0,.85) 56px, transparent 59px);
          mask:radial-gradient(circle, transparent 45px, rgba(0,0,0,.85) 48px, rgba(0,0,0,.85) 56px, transparent 59px);
  pointer-events:none;
  animation:scanner 2.4s linear infinite;
  z-index:0;
}
html[data-theme="light"] .gauge::before{
  background:conic-gradient(from 0deg, transparent 0deg, rgba(56,199,244,.30) 40deg, transparent 80deg);
}
.gauge svg{position:relative;z-index:1;width:100%;height:100%;transform:rotate(-90deg)}
.gauge circle{fill:none;stroke-width:11;stroke-linecap:round}
.gauge .track{stroke:rgba(110,160,255,.12)}
.gauge .val{stroke:var(--cyan);transition:stroke-dashoffset 1.2s var(--ease-out)}
.gauge-center{position:absolute;inset:0;display:grid;place-items:center;text-align:center;z-index:2}
.gauge-center .pct{font-family:'Sora',sans-serif;font-size:26px;font-weight:800;line-height:1}
.gauge-center .lbl{font-size:11px;color:var(--txt-soft);margin-top:2px}
.risk{text-align:center}
.risk .lvl{
  font-family:'Sora',sans-serif;
  font-size:22px;font-weight:800;
  color:var(--cyan);
}
.risk .sub{font-size:12.5px;color:var(--txt-soft);margin:6px 0 16px}
.pred-fade{transition:opacity .35s ease}
#waterLevel{transition:transform 1.2s var(--ease-out)}

/* Aviso legal */
.disclaimer{
  display:flex;align-items:center;gap:10px;
  margin-top:14px;
  padding:11px 16px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:var(--panel-2);
  font-size:12.5px;color:var(--txt-soft);
}
.disclaimer svg{width:18px;height:18px;flex:none;color:var(--cyan)}

/* Responsive */
@media (max-width:1380px){
  .stats{grid-template-columns:1fr 1fr}
  .rep-grid{grid-template-columns:1fr}
  .rep-tbl,.card-pred,.rep-hall,.rep-grid .recs{grid-column:1;grid-row:auto}
  .card-pred{grid-template-columns:1fr;gap:14px}
  .card-pred > *{padding:0}
  .card-pred > * + *{border-left:0;border-top:1px solid var(--stroke);padding-top:14px}
}
@media (max-width:720px){
  .stats{grid-template-columns:1fr}
}
@media (prefers-reduced-motion: reduce){
  .bar i,.conf .ring .val,.gauge .val{transition:none}
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

  
  <?php
    $t = $kpis['reportes']['trend'];
    $te = $kpis['evidencias']['trend'];
    $tes = $kpis['estudios']['trend'];
  ?>
  <section class="stats">

    <article class="card stat blue rise d2">
      <div class="ico">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
      </div>
      <div class="stat-body">
        <h3>Reportes generados</h3>
        <div class="num" id="kpiReportes" data-target="<?php echo e($kpis['reportes']['valor']); ?>">0</div>
        <div class="tag">Este mes</div>
        <div class="trend up" <?php if($t < 0): ?> style="color:var(--red)" <?php endif; ?>>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php if($t < 0): ?><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/><?php else: ?><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/><?php endif; ?></svg>
          <?php echo e(abs($t)); ?>% <span class="vs">vs mes anterior</span>
        </div>
      </div>
    </article>

    <article class="card stat orange rise d3">
      <div class="ico">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div class="stat-body">
        <h3>Estudios sin reporte</h3>
        <div class="num" id="kpiPendientes" data-target="<?php echo e($kpis['sin_reporte']['valor']); ?>">0</div>
        <div class="tag">Pendientes</div>
        <div class="trend up">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          <span class="vs">Sin reporte clínico</span>
        </div>
      </div>
    </article>

    <article class="card stat red rise d4">
      <div class="ico">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
      </div>
      <div class="stat-body">
        <h3>Evidencias capturadas</h3>
        <div class="num" id="kpiCriticos" data-target="<?php echo e($kpis['evidencias']['valor']); ?>">0</div>
        <div class="tag">Este mes</div>
        <div class="trend up" <?php if($te < 0): ?> style="color:var(--red)" <?php endif; ?>>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php if($te < 0): ?><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/><?php else: ?><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/><?php endif; ?></svg>
          <?php echo e(abs($te)); ?>% <span class="vs">vs mes anterior</span>
        </div>
      </div>
    </article>

    <article class="card stat green rise d5">
      <div class="ico">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      </div>
      <div class="stat-body">
        <h3>Estudios realizados</h3>
        <div class="num" id="kpiPrecision" data-target="<?php echo e($kpis['estudios']['valor']); ?>">0</div>
        <div class="tag">Este mes</div>
        <div class="trend up" <?php if($tes < 0): ?> style="color:var(--red)" <?php endif; ?>>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php if($tes < 0): ?><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/><?php else: ?><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/><?php endif; ?></svg>
          <?php echo e(abs($tes)); ?>% <span class="vs">vs mes anterior</span>
        </div>
      </div>
    </article>

  </section>

  
  <section class="rep-grid">

    
    <article class="card rep-tbl rise d5">
      <div class="card-head">
        <h3>Reportes generados</h3>
        <div class="card-head-actions">
          <a class="btn-gen secondary" href="<?php echo e(url('/ia-reportes/redactar')); ?>">
            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
            Generar reporte
          </a>
          <a class="btn-gen" href="<?php echo e(route('ia-reportes.generar')); ?>">
            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/><path d="M15 2v6h6"/></svg>
            Generar reporte IA
          </a>
        </div>
      </div>

      <div class="tbl-wrap">
        <table class="tbl">
          <thead>
            <tr>
              <th>Pacientes</th><th>Estudio</th><th>Fecha</th><th>Estado</th><th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $reportes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <?php
                $pacNombre = $r->estudio?->paciente?->nombre_completo ?? $r->estudio?->paciente_nombre ?? 'Sin paciente';
                $pacIni = collect(explode(' ', $pacNombre))->filter()->take(2)->map(fn($x)=>mb_strtoupper(mb_substr($x,0,1)))->implode('') ?: 'NA';
                $critico = (bool) $r->contiene_hallazgos_criticos;
              ?>
              <tr>
                <td><span class="pat"><span class="mini"><?php echo e($pacIni); ?></span><?php echo e($pacNombre); ?></span></td>
                <td><?php echo e($r->estudio?->tipo ?? '—'); ?></td>
                <td class="date"><?php echo e(format_user_date($r->created_at)); ?> <small><?php echo e(format_user_time($r->created_at)); ?></small></td>
                <td><span class="chip <?php echo e($critico ? 'urgent' : 'done'); ?>"><?php echo e($critico ? 'Crítico' : 'Normal'); ?></span></td>
                <td>
                  <div class="row-actions">
                    <a href="<?php echo e(route('ia-reportes.ver', ['reporte' => $r->id])); ?>" aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                    <a href="<?php echo e(route('ia-reportes.ver', ['reporte' => $r->id, 'download' => 1])); ?>" target="_blank" aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>
                    <a href="<?php echo e(route('ia-reportes.editar', ['reporte' => $r->id])); ?>" aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0-2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></a>
                  </div>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr><td colspan="5" style="text-align:center;color:var(--txt-soft);padding:24px 12px">No hay reportes en la base de datos.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <a class="tbl-link" href="<?php echo e(route('ia-reportes.todos')); ?>">
        Ver todos los reportes
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </article>

    
    <?php $primerEstudio = $estudiosSinReporte->first(); ?>
    <article class="card card-pred rise d7">

      <div>
        <div class="pred-pat pred-fade"><span class="mini" id="predMini"><?php echo e($primerEstudio['ini'] ?? ''); ?></span><b id="predName"><?php echo e($primerEstudio['paciente'] ?? 'Sin estudios pendientes'); ?></b></div>
        <div class="pred-meta pred-fade" id="predMeta">
          Estudio: <?php echo e($primerEstudio['tipo'] ?? '—'); ?><br>
          Fecha: <?php echo e($primerEstudio['fecha'] ?? '—'); ?>

        </div>
      </div>

      <div class="prob">
        <div class="gauge">
          <svg viewBox="0 0 120 120">
            <circle class="track" cx="60" cy="60" r="50"/>
            <circle class="val" id="predGauge" cx="60" cy="60" r="50" stroke-dasharray="314.16" stroke-dashoffset="314.16" data-pct="<?php echo e($primerEstudio['pct'] ?? 0); ?>"/>
          </svg>
          <div class="gauge-center">
            <div class="stomach">
              <svg class="water" viewBox="0 0 62 62" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <linearGradient id="wg" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0" stop-color="#9fe6ff"/>
                    <stop offset="1" stop-color="#5ab4f3"/>
                  </linearGradient>
                  <filter id="bubbleGlow" x="-60%" y="-60%" width="220%" height="220%">
                    <feGaussianBlur stdDeviation=".7" result="b"/>
                    <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
                  </filter>
                </defs>
                <g id="waterLevel">
                <rect x="0" y="27" width="62" height="62" fill="url(#wg)"/>
                <path fill="#7fdcff" fill-opacity=".55" d="M-62 27 q15.5 -3.5 31 0 t31 0 t31 0 L124 64 L-62 64 Z">
                  <animateTransform attributeName="transform" type="translate" from="0 0" to="-62 0" dur="5s" repeatCount="indefinite"/>
                </path>
                <path fill="#cdf2ff" fill-opacity=".7" d="M-62 28 q15.5 3.5 31 0 t31 0 t31 0 L124 64 L-62 64 Z">
                  <animateTransform attributeName="transform" type="translate" from="-62 0" to="0 0" dur="4s" repeatCount="indefinite"/>
                </path>
                <g fill="#ffffff" filter="url(#bubbleGlow)">
                  <circle cx="22" r="2.2" opacity="0">
                    <animate attributeName="cy" values="60;28" dur="2.8s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.15;.8;1" dur="2.8s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="2.2;1.6" dur="2.8s" repeatCount="indefinite"/>
                  </circle>
                  <circle cx="36" r="1.7" opacity="0">
                    <animate attributeName="cy" values="61;30" dur="3.3s" begin=".5s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.15;.8;1" dur="3.3s" begin=".5s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="1.7;1.2" dur="3.3s" begin=".5s" repeatCount="indefinite"/>
                  </circle>
                  <circle cx="44" r="1.5" opacity="0">
                    <animate attributeName="cy" values="59;29" dur="2.5s" begin="1.1s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.15;.8;1" dur="2.5s" begin="1.1s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="1.5;1" dur="2.5s" begin="1.1s" repeatCount="indefinite"/>
                  </circle>
                  <circle cx="29" r="1.9" opacity="0">
                    <animate attributeName="cy" values="60;28" dur="3.6s" begin="1.7s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.15;.8;1" dur="3.6s" begin="1.7s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="1.9;1.3" dur="3.6s" begin="1.7s" repeatCount="indefinite"/>
                  </circle>
                  <circle cx="50" r="1.3" opacity="0">
                    <animate attributeName="cy" values="58;31" dur="3s" begin="2.2s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.15;.8;1" dur="3s" begin="2.2s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="1.3;.9" dur="3s" begin="2.2s" repeatCount="indefinite"/>
                  </circle>
                </g>
                </g>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <div class="risk">
        <div class="lvl pred-fade" id="predRisk" style="<?php echo e(($primerEstudio ?? null) ? '' : 'display:none'); ?>">Sin reporte</div>
        <div class="sub pred-fade" id="predRiskSub">Requiere elaborar el reporte clínico</div>
        <a class="btn-line pred-fade" id="predLink" href="<?php echo e(route('ia-reportes.redactar', ['estudio' => $primerEstudio['id'] ?? null])); ?>" <?php if(! $primerEstudio): ?> style="pointer-events:none;opacity:.5" <?php endif; ?>>
          Acceder a su estudio
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

    </article>

    
      <article class="card rep-hall rise d6">
        <h3>HALLAZGOS</h3>

        <?php $__empty_1 = true; $__currentLoopData = $hallazgos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <?php $colorClass = 'c' . (($i % 5) + 1); ?>
          <div class="find">
            <div class="top"><span><?php echo e($h['nombre']); ?></span><b><?php echo e($h['cantidad']); ?></b></div>
            <div class="bar <?php echo e($colorClass); ?>"><i data-w="<?php echo e($h['porcentaje']); ?>"></i></div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <div class="find">
            <div class="top"><span>Sin hallazgos registrados</span></div>
          </div>
        <?php endif; ?>

        <a class="tbl-link" href="<?php echo e(route('ia-reportes.hallazgos')); ?>">
          Ver todos los hallazgos
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </article>

      <article class="card recs rise d7">
        <h3>¿CÓMO USAR LA IA?</h3>
        <ul>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            "Resume el informe en 3 párrafos"
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            "Agrega hallazgos por segmento del estudio"
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            "Reescribe el plan de recomendaciones con lenguaje claro"
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            "Incluye la descripción clínica del paciente para llegar a una mejor conclusión"
          </li>
        </ul>
      </article>

  </section>

  <div class="disclaimer rise d7">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    Los análisis generados por IA son una herramienta de apoyo. La decisión final siempre debe ser del profesional de la salud.
  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- Anillos de confianza IA (tabla) y gauge de probabilidad ---- */
  const drawRing = (circle, circumference) => {
    const pct = parseFloat(circle.dataset.pct) / 100;
    circle.style.strokeDashoffset = circumference - (circumference * pct);
  };
  const rings = document.querySelectorAll('.conf .ring .val'); // C = 2*PI*18 = 113.1
  const gauge = document.querySelector('.gauge .val');          // C = 2*PI*50 = 314.16

  /* ---- Barras de hallazgos ---- */
  const bars = document.querySelectorAll('.bar i');

  /* ---- Contadores numéricos ---- */
  const counters = document.querySelectorAll('[data-target]');

  const drawAll = () => {
    rings.forEach(r => drawRing(r, 113.1));
    if (gauge) drawRing(gauge, 314.16);
    bars.forEach(b => { b.style.width = b.dataset.w + '%'; });
  };

  const hasGsap = typeof gsap !== 'undefined';

  if (reduced) {
    counters.forEach(c => { c.textContent = parseInt(c.dataset.target, 10).toLocaleString('es-MX'); });
    document.querySelectorAll('.conf .ring .val,.gauge .val,.bar i').forEach(el => el.style.transition = 'none');
    drawAll();
    return;
  }

  if (hasGsap) {
    counters.forEach((counter, i) => {
      const target = parseInt(counter.dataset.target, 10);
      const obj = { v: 0 };
      gsap.to(obj, {
        v: target,
        duration: 1.4,
        ease: 'expo.out',
        delay: 0.3 + i * 0.08,
        onUpdate: () => { counter.textContent = Math.round(obj.v).toLocaleString('es-MX'); }
      });
    });
  } else {
    counters.forEach(c => { c.textContent = parseInt(c.dataset.target, 10).toLocaleString('es-MX'); });
  }

  setTimeout(drawAll, 400);

  /* ============ Estudios sin reporte: rotación de pacientes ============ */
  const PRED = <?php echo json_encode($estudiosSinReporte, 15, 512) ?>;

  const elMini  = document.getElementById('predMini');
  const elName  = document.getElementById('predName');
  const elMeta  = document.getElementById('predMeta');
  const elGauge = document.getElementById('predGauge');
  const elWater = document.getElementById('waterLevel');
  const elLink  = document.getElementById('predLink');
  const fades   = document.querySelectorAll('.pred-fade');
  const redactarBase = <?php echo json_encode(route('ia-reportes.redactar'), 15, 512) ?>;
  const setLink = (id) => { if (elLink) elLink.href = redactarBase + '?estudio=' + id; };

  if (!elGauge || !PRED.length) return;

  const applyPred = (p) => {
    // Gauge (anillo)
    elGauge.dataset.pct = p.pct;
    drawRing(elGauge, 314.16);
    // Nivel de agua: sube/baja según el nivel de urgencia del estudio
    const dy = (62 * (1 - p.pct / 100)) - 27;
    elWater.style.transform = 'translateY(' + dy + 'px)';
  };

  let idx = 0;
  applyPred(PRED[0]); // sincroniza nivel inicial
  setLink(PRED[0].id);

  const cycle = () => {
    idx = (idx + 1) % PRED.length;
    const p = PRED[idx];
    fades.forEach(f => f.style.opacity = '0');
    setTimeout(() => {
      elMini.textContent = p.ini;
      elName.textContent = p.paciente;
      elMeta.innerHTML   = 'Estudio: ' + p.tipo + '<br>Fecha: ' + p.fecha;
      applyPred(p);
      setLink(p.id);
      fades.forEach(f => f.style.opacity = '1');
    }, 350);
  };

  if (PRED.length > 1) setInterval(cycle, 9000);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\gmedi\enclaii-backend\resources\views/ia-reportes/index.blade.php ENDPATH**/ ?>