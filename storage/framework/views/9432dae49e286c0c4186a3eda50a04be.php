<?php $__env->startSection('title', 'Hallazgos'); ?>
<?php $__env->startSection('active', 'ia-reportes'); ?>
<?php $__env->startSection('header-title', 'Hallazgos'); ?>
<?php $__env->startSection('header-sub'); ?>
  Detalle completo de los hallazgos encontrados en los estudios
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ============ HALLAZGOS (vista completa) ============ */
.hz-top{display:flex;justify-content:flex-end;margin-bottom:16px}
.hz-back{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);background:var(--panel-2);font-weight:600;font-size:13.5px;transition:background-color .15s}
.hz-back svg{width:16px;height:16px}
@media (hover:hover){.hz-back:hover{background:rgba(110,160,255,.1)}}

.hz-grid{display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:stretch}
@media (max-width:1100px){.hz-grid{grid-template-columns:1fr}}
.hz-col{display:flex;flex-direction:column;gap:16px;min-height:0}

.hz-pat{display:flex;align-items:center;gap:12px;margin-bottom:18px}
.hz-pat .av{width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:grid;place-items:center;font-family:'Sora',sans-serif;font-weight:700;font-size:15px;flex:none}
.hz-pat .nm{font-weight:700;font-size:15px}
.hz-pat .mt{font-size:12.5px;color:var(--txt-soft)}

.find{padding:13px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.find:last-of-type{border-bottom:0}
.find .top{display:flex;align-items:center;gap:10px;font-size:14px;font-weight:600;margin-bottom:8px}
.find .top b{font-family:'Sora',sans-serif;font-weight:700;color:var(--txt);margin-left:auto}
.find .desc{font-size:12.5px;color:var(--txt-soft);margin-top:7px;line-height:1.5}
.bar{height:8px;border-radius:99px;background:rgba(110,160,255,.12);overflow:hidden}
.bar i{display:block;height:100%;border-radius:99px;width:0;transition:width 1.1s var(--ease-out)}
.bar.c1 i{background:linear-gradient(90deg,var(--blue),var(--cyan))}
.bar.c2 i{background:linear-gradient(90deg,#7B5CF6,#A98BFF)}
.bar.c3 i{background:linear-gradient(90deg,var(--orange),#FFC368)}
.bar.c4 i{background:linear-gradient(90deg,var(--green),#7BF0BE)}
.bar.c5 i{background:linear-gradient(90deg,var(--red),#FF98A6)}

.tag-conf{font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:6px;white-space:nowrap}
.tag-conf.hi{color:var(--green);background:rgba(61,220,151,.12)}
.tag-conf.mid{color:var(--orange);background:rgba(245,158,45,.12)}
.tag-conf.low{color:var(--txt-soft);background:rgba(110,160,255,.1)}

.hz-people-lbl{font-size:11px;color:var(--txt-soft);margin:10px 0 7px;font-weight:600;letter-spacing:.02em}
.hz-people{display:flex;flex-wrap:wrap;gap:8px}
.hz-person{display:flex;align-items:center;gap:7px;padding:4px 11px 4px 4px;border:1px solid var(--stroke);border-radius:99px;font-size:12px;font-weight:600;background:var(--panel-2)}
.hz-person .mini{width:24px;height:24px;border-radius:50%;background:rgba(46,123,246,.2);border:1px solid var(--stroke-strong);display:grid;place-items:center;font-size:9.5px;font-weight:700;color:var(--cyan)}
.hz-person small{color:var(--txt-soft);font-weight:500;margin-left:2px}

.hz-side h3{margin-bottom:14px}
.hz-stat{display:flex;align-items:center;justify-content:space-between;padding:11px 0;border-bottom:1px solid rgba(110,160,255,.08);font-size:13.5px}
.hz-stat:last-child{border-bottom:0}
.hz-stat b{font-family:'Sora',sans-serif;font-size:18px}
.hz-stat .crit{color:var(--red)}
.hz-stat .warn{color:var(--orange)}
.hz-stat .ok{color:var(--green)}
.hz-note{display:flex;align-items:center;gap:9px;margin-top:8px;padding:11px 14px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2);font-size:12px;color:var(--txt-soft)}
.hz-note svg{width:16px;height:16px;flex:none;color:var(--cyan)}

/* Gráfica de barras verticales: hallazgos detectados */
.hz-chart-card{flex:1;display:flex;flex-direction:column;min-height:260px}
.hz-chart-card h3{margin-bottom:14px}
.hz-bars{flex:1;display:flex;align-items:flex-end;justify-content:space-between;gap:10px;padding-top:6px}
.hz-bar{flex:1;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;gap:7px;min-width:0}
.hz-bar .col{width:100%;max-width:42px;border-radius:7px 7px 0 0;height:0;transition:height 1.1s var(--ease-out)}
.hz-bar .col.c1{background:linear-gradient(180deg,var(--cyan),var(--blue))}
.hz-bar .col.c2{background:linear-gradient(180deg,#A98BFF,#7B5CF6)}
.hz-bar .col.c3{background:linear-gradient(180deg,#FFC368,var(--orange))}
.hz-bar .col.c4{background:linear-gradient(180deg,#7BF0BE,var(--green))}
.hz-bar .col.c5{background:linear-gradient(180deg,#FF98A6,var(--red))}
.hz-bar .val{font-family:'Sora',sans-serif;font-weight:800;font-size:13px;order:-1}
.hz-bar .lbl{font-size:10.5px;color:var(--txt-soft);font-weight:600;text-align:center;line-height:1.2}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

  <div class="hz-top">
    <a class="hz-back" href="<?php echo e(route('ia-reportes')); ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
  </div>

  <div class="hz-grid">

    
    <article class="card rise d2">
      <div class="hz-pat">
        <span class="av">HZ</span>
        <div>
          <div class="nm">Hallazgos por síntoma</div>
          <div class="mt">Desglose de pacientes que presentan cada hallazgo</div>
        </div>
      </div>

      <?php $__empty_1 = true; $__currentLoopData = $hallazgos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
          $colorClass = 'c' . (($i % 5) + 1);
          $tagClass = $h['es_critico'] ? 'hi' : 'mid';
          $tagText = $h['es_critico'] ? 'Crítico' : 'No crítico';
        ?>
        <div class="find">
          <div class="top">
            <span><?php echo e($h['nombre']); ?></span>
            <span class="tag-conf <?php echo e($tagClass); ?>"><?php echo e($tagText); ?></span>
            <b><?php echo e($h['cantidad']); ?></b>
          </div>
          <div class="bar <?php echo e($colorClass); ?>"><i data-w="<?php echo e($h['porcentaje']); ?>"></i></div>
          <div class="hz-people-lbl">PACIENTES CON ESTE HALLAZGO (<?php echo e($h['pacientes']->count()); ?>)</div>
          <div class="hz-people">
            <?php $__currentLoopData = $h['pacientes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paciente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php
                $iniciales = collect(explode(' ', $paciente->nombre_completo ?? 'N/A'))
                  ->map(fn($p) => mb_substr($p, 0, 1))
                  ->take(2)
                  ->join('');
              ?>
              <span class="hz-person">
                <span class="mini"><?php echo e($iniciales); ?></span>
                <?php echo e($paciente->nombre_completo); ?>

              </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="find">
          <div class="top"><span>Sin hallazgos registrados</span></div>
          <p class="desc">No se han encontrado hallazgos en los estudios todavía.</p>
        </div>
      <?php endif; ?>
    </article>

    
    <div class="hz-col">

      <article class="card hz-side rise d3">
        <h3>RESUMEN DE HALLAZGOS</h3>
        <div class="hz-stat"><span>Total de hallazgos</span><b><?php echo e($totalHallazgos); ?></b></div>
        <div class="hz-stat"><span>Total de estudios</span><b><?php echo e($totalEstudios); ?></b></div>
        <div class="hz-stat"><span>Hallazgos críticos</span><b class="crit"><?php echo e($totalCriticos); ?></b></div>
        <div class="hz-stat"><span>Hallazgos no críticos</span><b class="ok"><?php echo e($totalHallazgos - $totalCriticos); ?></b></div>
        <div class="hz-stat"><span>Hallazgo principal</span><b class="warn" style="font-size:14px"><?php echo e($hallazgoPrincipal); ?></b></div>

        <div class="hz-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
          Los hallazgos mostrados provienen de los estudios registrados en el sistema.
        </div>
      </article>

      
      <article class="card hz-chart-card rise d4">
        <h3>HALLAZGOS DETECTADOS</h3>
        <div class="hz-bars">
          <?php $__empty_1 = true; $__currentLoopData = $hallazgos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $colorClass = 'c' . (($i % 5) + 1); ?>
            <div class="hz-bar">
              <div class="col <?php echo e($colorClass); ?>" data-v="<?php echo e($h['cantidad']); ?>" data-max="<?php echo e(collect($hallazgos)->max('cantidad') ?: 1); ?>"></div>
              <span class="val"><?php echo e($h['cantidad']); ?></span>
              <span class="lbl"><?php echo e($h['nombre']); ?></span>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="hz-bar"><span class="lbl">Sin datos</span></div>
          <?php endif; ?>
        </div>
      </article>

    </div>

  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const bars = document.querySelectorAll('.bar i');
  const cols = document.querySelectorAll('.hz-bar .col');

  const draw = () => {
    bars.forEach(b => { b.style.width = b.dataset.w + '%'; });
    cols.forEach(c => {
      const v = +c.dataset.v, max = +c.dataset.max || 1;
      c.style.height = Math.round(v / max * 100) + '%';
    });
  };
  if (reduced) {
    bars.forEach(b => b.style.transition = 'none');
    cols.forEach(c => c.style.transition = 'none');
    draw();
    return;
  }
  setTimeout(draw, 250);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\ia-reportes\hallazgos.blade.php ENDPATH**/ ?>