<?php
  $pacientes = [
    ['ini'=>'MG','name'=>'María González','study'=>'Endoscopia digestiva alta','date'=>'08/05/2025','cond'=>'Gastritis','prob'=>82,'risk'=>'Moderado','sub'=>'Recomendación de seguimiento','color'=>'var(--orange)',
      'factors'=>[['Inflamación de mucosa antral',78],['Antecedentes de H. pylori',64],['Síntomas dispépticos persistentes',71],['Uso prolongado de AINEs',45]],
      'recs'=>['Iniciar inhibidor de bomba de protones (IBP) por 8 semanas','Prueba de detección de Helicobacter pylori','Control endoscópico en 3 meses','Evitar AINEs y alcohol'],
      'reason'=>'La IA concluye Gastritis porque detectó enrojecimiento difuso y edema de la mucosa del antro gástrico en varias imágenes, un patrón inflamatorio característico. La ausencia de erosiones profundas o lesiones focales descarta úlcera, y la línea Z regular descarta esofagitis. La combinación de estos hallazgos visuales con los síntomas dispépticos del paciente respalda el diagnóstico de gastritis crónica no atrófica.',
      'images'=>[
        ['c1','Antro gástrico','Mucosa eritematosa y edematosa, con enrojecimiento difuso.','El enrojecimiento difuso es el principal indicador de inflamación crónica de la mucosa.',88],
        ['c2','Cuerpo gástrico','Pliegues conservados con leve congestión vascular.','Patrón compatible con gastritis no atrófica; descarta atrofia mucosa.',72],
        ['c3','Unión esofagogástrica','Línea Z regular, sin erosiones ni úlceras.','Descarta componente erosivo y esofagitis, reforzando que es gastritis antral.',65],
      ]],
    ['ini'=>'JL','name'=>'Jorge López','study'=>'Endoscopia digestiva alta','date'=>'08/05/2025','cond'=>'Reflujo gastroesofágico','prob'=>54,'risk'=>'Bajo','sub'=>'Control en 6 meses','color'=>'var(--green)',
      'factors'=>[['Pirosis recurrente',60],['Eritema en unión esofagogástrica',48],['Sobrepeso (IMC 28)',40],['Hernia hiatal leve',33]],
      'recs'=>['Medidas posturales y dietéticas','IBP a dosis estándar por 4 semanas','Reducción de peso','Reevaluar en 6 meses'],
      'reason'=>'La IA concluye Reflujo gastroesofágico porque identificó eritema en la unión esofagogástrica y signos de exposición ácida distal, sin erosiones severas. El patrón es leve y coincide con la pirosis recurrente referida, por lo que se clasifica como riesgo bajo con manejo conservador.',
      'images'=>[
        ['c2','Unión esofagogástrica','Eritema y leve irregularidad de la línea Z.','Signo directo de exposición ácida repetida, base del diagnóstico de reflujo.',60],
        ['c1','Esófago distal','Mucosa con cambios mínimos, sin erosiones.','Confirma que el daño es leve, descartando esofatigis erosiva.',48],
        ['c4','Cardias','Cierre incompetente sugerente de hernia hiatal leve.','Factor mecánico que favorece el reflujo del contenido gástrico.',33],
      ]],
    ['ini'=>'AR','name'=>'Ana Ramírez','study'=>'Gastroscopia','date'=>'07/05/2025','cond'=>'Úlcera péptica','prob'=>91,'risk'=>'Alto','sub'=>'Requiere atención inmediata','color'=>'var(--red)',
      'factors'=>[['Lesión focal con bordes definidos',88],['Sangrado activo sospechado',74],['H. pylori positivo',82],['Dolor epigástrico intenso',69]],
      'recs'=>['Valoración por gastroenterología urgente','Biopsia para descartar malignidad','Terapia de erradicación de H. pylori','Control endoscópico en 4-6 semanas'],
      'reason'=>'La IA concluye Úlcera péptica con alta confianza porque detectó una lesión focal con bordes bien definidos y un cráter central, junto con signos de sangrado reciente. Estos hallazgos, más el H. pylori positivo, son altamente específicos de úlcera y justifican el riesgo alto y la atención inmediata.',
      'images'=>[
        ['c5','Antro / curvatura menor','Lesión focal con cráter central y bordes definidos.','Morfología clásica de úlcera péptica; principal evidencia del diagnóstico.',88],
        ['c3','Base de la lesión','Restos hemáticos y coágulo adherido.','Indica sangrado reciente, eleva el nivel de riesgo a alto.',74],
        ['c1','Mucosa circundante','Inflamación perilesional intensa.','Reacción inflamatoria que acompaña a la úlcera activa.',69],
      ]],
    ['ini'=>'PT','name'=>'Pedro Torres','study'=>'Colonoscopia','date'=>'07/05/2025','cond'=>'Pólipos','prob'=>37,'risk'=>'Bajo','sub'=>'Seguimiento de rutina','color'=>'var(--green)',
      'factors'=>[['Imagen elevada en sigmoides',42],['Antecedente familiar de pólipos',35],['Edad > 50 años',30],['Cambios en hábito intestinal',22]],
      'recs'=>['Polipectomía y análisis histológico','Vigilancia colonoscópica en 3-5 años','Dieta rica en fibra','Seguimiento de rutina'],
      'reason'=>'La IA detectó una imagen elevada en sigmoides con morfología sésil compatible con pólipo. La probabilidad es baja-moderada porque el tamaño es pequeño y no hay signos de malignidad; se recomienda polipectomía y análisis para confirmar la histología.',
      'images'=>[
        ['c4','Colon sigmoides','Lesión elevada sésil de aspecto liso.','Morfología típica de pólipo, hallazgo principal del análisis.',42],
        ['c2','Superficie del pólipo','Patrón mucoso regular, sin ulceración.','Sugiere benignidad; reduce la sospecha de malignidad.',30],
        ['c1','Mucosa adyacente','Sin lesiones satélite ni sangrado.','Apoya seguimiento de rutina en lugar de intervención urgente.',22],
      ]],
    ['ini'=>'LM','name'=>'Laura Méndez','study'=>'Endoscopia digestiva alta','date'=>'06/05/2025','cond'=>'Esofagitis','prob'=>68,'risk'=>'Moderado','sub'=>'Recomendación de seguimiento','color'=>'var(--orange)',
      'factors'=>[['Erosiones en mucosa esofágica distal',66],['Reflujo ácido frecuente',58],['Disfagia leve',44],['Consumo de irritantes',38]],
      'recs'=>['IBP a dosis alta por 8 semanas','Endoscopia de control','Modificación de estilo de vida','Evitar comidas tardías'],
      'reason'=>'La IA concluye Esofagitis porque identificó erosiones lineales en la mucosa esofágica distal, asociadas a reflujo ácido frecuente. La extensión moderada de las erosiones y la disfagia leve sitúan el caso en riesgo moderado con tratamiento con IBP.',
      'images'=>[
        ['c5','Esófago distal','Erosiones lineales y mucosa friable.','Lesiones erosivas que definen directamente la esofagitis.',66],
        ['c2','Unión esofagogástrica','Exposición ácida evidente.','Causa subyacente de las erosiones observadas.',58],
        ['c3','Tercio medio esofágico','Estrechamiento leve sin estenosis.','Explica la disfagia leve referida por la paciente.',44],
      ]],
  ];
  $i = (int) request('p', 0);
  $p = $pacientes[$i] ?? $pacientes[0];
  $off = 314.16 - (314.16 * $p['prob'] / 100);
?>

<?php $__env->startSection('title', 'Análisis completo'); ?>
<?php $__env->startSection('active', 'ia-reportes'); ?>
<?php $__env->startSection('header-title', 'Análisis predictivo completo'); ?>
<?php $__env->startSection('header-sub'); ?>
  Detalle del análisis generado por IA para el paciente
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ============ ANÁLISIS COMPLETO ============ */
.an-top{display:flex;justify-content:flex-end;margin-bottom:16px}
.an-back{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);background:var(--panel-2);font-weight:600;font-size:13.5px;transition:background-color .15s}
.an-back svg{width:16px;height:16px}
@media (hover:hover){.an-back:hover{background:rgba(110,160,255,.1)}}

.an-grid{display:grid;grid-template-columns:1fr;gap:16px;align-items:start}

.an-pat{display:flex;align-items:center;gap:14px;margin-bottom:18px}
.an-pat .av{width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:grid;place-items:center;font-family:'Sora',sans-serif;font-weight:700;font-size:17px;flex:none}
.an-pat .nm{font-weight:700;font-size:16px}
.an-pat .mt{font-size:12.5px;color:var(--txt-soft)}

.an-hero{display:flex;align-items:center;gap:24px;flex-wrap:wrap}
.an-gauge{position:relative;width:150px;height:150px;flex:none}
.an-gauge svg{width:100%;height:100%;transform:rotate(-90deg)}
.an-gauge circle{fill:none;stroke-width:12;stroke-linecap:round}
.an-gauge .track{stroke:rgba(110,160,255,.12)}
.an-gauge .val{stroke:var(--cyan);transition:stroke-dashoffset 1.2s var(--ease-out)}
.an-gauge .ctr{position:absolute;inset:0;display:grid;place-items:center;text-align:center}
.an-gauge .ctr b{font-family:'Sora',sans-serif;font-size:34px;font-weight:800;line-height:1}
.an-gauge .ctr small{font-size:11px;color:var(--txt-soft)}
.an-hero-info h2{font-family:'Sora',sans-serif;font-size:22px;font-weight:800;margin-bottom:6px}
.an-hero-info p{font-size:13px;color:var(--txt-soft);max-width:280px;line-height:1.5}
.an-risk{display:inline-flex;align-items:center;gap:8px;margin-top:12px;padding:6px 14px;border-radius:99px;font-weight:700;font-size:13px;border:1px solid currentColor}

.an-sect{margin-top:22px}
.an-sect h3{font-size:14px;font-weight:700;margin-bottom:14px}
.factor{margin-bottom:13px}
.factor:last-child{margin-bottom:0}
.factor .top{display:flex;justify-content:space-between;font-size:13px;font-weight:600;margin-bottom:6px}
.factor .top b{font-family:'Sora',sans-serif;color:var(--txt)}
.fbar{height:8px;border-radius:99px;background:rgba(110,160,255,.12);overflow:hidden}
.fbar i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--blue),var(--cyan));transition:width 1.1s var(--ease-out)}

.an-recs{list-style:none;display:flex;flex-direction:column;gap:12px}
.an-recs li{display:flex;align-items:flex-start;gap:10px;font-size:13px;line-height:1.45}
.an-recs svg{width:17px;height:17px;color:var(--green);flex:none;margin-top:1px}
.an-note{display:flex;align-items:center;gap:9px;margin-top:16px;padding:11px 14px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2);font-size:12px;color:var(--txt-soft)}
.an-note svg{width:16px;height:16px;flex:none;color:var(--cyan)}

/* Razonamiento */
.an-reason{display:flex;gap:12px;padding:14px 16px;border-radius:var(--r-md);border:1px solid rgba(56,199,244,.25);background:rgba(56,199,244,.06);font-size:13px;line-height:1.55}
.an-reason svg{width:20px;height:20px;flex:none;color:var(--cyan);margin-top:1px}
.an-reason b{color:var(--cyan)}

/* Análisis por imagen */
.img-card{display:flex;gap:14px;padding:14px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.img-card:last-child{border-bottom:0}
.img-thumb{width:128px;height:96px;flex:none;border-radius:10px;border:1px solid var(--stroke);position:relative;overflow:hidden}
.img-thumb.c1{background:radial-gradient(circle at 45% 40%,#c0565a,#5c1d22)}
.img-thumb.c2{background:radial-gradient(circle at 50% 45%,#c97a52,#5c2d1d)}
.img-thumb.c3{background:radial-gradient(circle at 50% 50%,#7a3036,#3a1216)}
.img-thumb.c4{background:radial-gradient(circle at 50% 50%,#2a6f6a,#0d2e2c)}
.img-thumb.c5{background:radial-gradient(circle at 48% 42%,#b14a50,#4a1418)}
.img-thumb .ai-box{position:absolute;border:2px solid var(--cyan);border-radius:6px;box-shadow:0 0 0 9999px rgba(11,14,26,.12) inset;left:28%;top:26%;width:44%;height:44%}
.img-thumb .ai-tag{position:absolute;left:6px;top:6px;font-size:9px;font-weight:700;padding:2px 6px;border-radius:5px;background:var(--cyan);color:#04121f}
.img-body{flex:1;min-width:0}
.img-body .ihead{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:4px}
.img-body .iregion{font-weight:700;font-size:13.5px}
.img-body .iconf{font-size:11px;font-weight:700;color:var(--cyan);background:rgba(56,199,244,.12);padding:3px 9px;border-radius:6px;white-space:nowrap}
.img-body .idet{font-size:12.5px;margin-bottom:6px}
.img-body .iwhy{font-size:12px;color:var(--txt-soft);line-height:1.5}
.img-body .iwhy b{color:var(--txt)}

/* Generar reporte */
.an-generate{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-top:16px;padding:18px 20px}
.an-generate .gt h3{font-size:15px;margin-bottom:3px}
.an-generate .gt p{font-size:12.5px;color:var(--txt-soft)}
.an-gen-btn{display:inline-flex;align-items:center;gap:9px;padding:12px 22px;border-radius:var(--r-md);border:0;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;font-weight:700;font-size:14px;transition:filter .15s,transform .15s}
.an-gen-btn svg{width:18px;height:18px}
@media (hover:hover){.an-gen-btn:hover{filter:brightness(1.08);transform:translateY(-1px)}}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

  <div class="an-top">
    <a class="an-back" href="<?php echo e(route('ia-reportes')); ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
  </div>

  <div class="an-grid">

    
    <article class="card rise d2">
      <div class="an-pat">
        <span class="av"><?php echo e($p['ini']); ?></span>
        <div>
          <div class="nm"><?php echo e($p['name']); ?></div>
          <div class="mt"><?php echo e($p['study']); ?> · <?php echo e($p['date']); ?></div>
        </div>
      </div>

      <div class="an-hero">
        <div class="an-gauge">
          <svg viewBox="0 0 120 120">
            <circle class="track" cx="60" cy="60" r="50"/>
            <circle class="val" cx="60" cy="60" r="50" stroke-dasharray="314.16" stroke-dashoffset="314.16" data-off="<?php echo e($off); ?>"/>
          </svg>
          <div class="ctr">
            <div>
              <b><?php echo e($p['prob']); ?>%</b><br>
              <small>probabilidad</small>
            </div>
          </div>
        </div>
        <div class="an-hero-info">
          <h2><?php echo e($p['cond']); ?></h2>
          <p>Estimación de la IA basada en patrones detectados en el estudio y el historial clínico del paciente.</p>
          <span class="an-risk" style="color:<?php echo e($p['color']); ?>">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Riesgo <?php echo e($p['risk']); ?>

          </span>
          <div class="mt" style="font-size:12.5px;color:var(--txt-soft);margin-top:8px"><?php echo e($p['sub']); ?></div>
        </div>
      </div>

      <div class="an-sect">
        <h3>¿POR QUÉ ESTE DIAGNÓSTICO?</h3>
        <div class="an-reason">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
          <div><?php echo e($p['reason']); ?></div>
        </div>
      </div>

      <div class="an-sect">
        <h3>FACTORES CONTRIBUYENTES</h3>
        <?php $__currentLoopData = $p['factors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="factor">
            <div class="top"><span><?php echo e($f[0]); ?></span><b><?php echo e($f[1]); ?>%</b></div>
            <div class="fbar"><i data-w="<?php echo e($f[1]); ?>"></i></div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <div class="an-note">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        Este análisis es una herramienta de apoyo. La decisión final corresponde al profesional de la salud.
      </div>
    </article>

  </div>

  
  <article class="card rise d4" style="margin-top:16px">
    <div class="an-sect" style="margin-top:0">
      <h3>ANÁLISIS POR IMAGEN (<?php echo e(count($p['images'])); ?>)</h3>
      <p style="font-size:12.5px;color:var(--txt-soft);margin:-8px 0 14px">Qué detectó la IA en cada imagen y por qué sustenta el diagnóstico de <?php echo e($p['cond']); ?>.</p>
      <?php $__currentLoopData = $p['images']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="img-card">
          <div class="img-thumb <?php echo e($img[0]); ?>">
            <span class="ai-tag">IA</span>
            <span class="ai-box"></span>
          </div>
          <div class="img-body">
            <div class="ihead">
              <span class="iregion"><?php echo e($img[1]); ?></span>
              <span class="iconf"><?php echo e($img[4]); ?>% relevancia</span>
            </div>
            <div class="idet"><?php echo e($img[2]); ?></div>
            <div class="iwhy"><b>Por qué importa:</b> <?php echo e($img[3]); ?></div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </article>

  
  <article class="card an-generate rise d5">
    <div class="gt">
      <h3>¿Conforme con el análisis?</h3>
      <p>Genera un reporte de IA con este diagnóstico, factores y recomendaciones.</p>
    </div>
    <a class="an-gen-btn" href="<?php echo e(route('ia-reportes.generar')); ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
      Generar reporte de IA
    </a>
  </article>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const gauge = document.querySelector('.an-gauge .val');
  const bars = document.querySelectorAll('.fbar i');
  const draw = () => {
    if (gauge) gauge.style.strokeDashoffset = gauge.dataset.off;
    bars.forEach(b => b.style.width = b.dataset.w + '%');
  };
  if (reduced) {
    if (gauge) gauge.style.transition = 'none';
    bars.forEach(b => b.style.transition = 'none');
    draw();
    return;
  }
  setTimeout(draw, 250);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\ia-reportes\analisis.blade.php ENDPATH**/ ?>