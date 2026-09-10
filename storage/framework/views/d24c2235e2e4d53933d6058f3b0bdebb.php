
<?php
  $hoy = \Carbon\Carbon::now();
  $diaHoy = $hoy->format('d');
  $mesHoy = $hoy->translatedFormat('M');
  $anioHoy = $hoy->format('Y');
  $mesLargoHoy = $hoy->translatedFormat('F Y');
?>
<div class="widget widget-minimal d3" data-widget-id="agenda-today-min" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-minimal card-minimal-agenda" style="overflow:hidden">
    <div class="min-label" style="flex:0 0 auto">Hoy</div>
    <a class="min-icon" href="<?php echo e(route('agendar')); ?>" style="flex:1 1 60%;width:100%;min-height:0;display:grid;place-items:center;color:var(--blue);text-decoration:none;cursor:pointer">
      <?php if (isset($component)) { $__componentOriginal11d364b859f1df7d9f7ca031d219363c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11d364b859f1df7d9f7ca031d219363c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forkawesome-calendar','data' => ['style' => 'width:100%;height:100%']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forkawesome-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['style' => 'width:100%;height:100%']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal11d364b859f1df7d9f7ca031d219363c)): ?>
<?php $attributes = $__attributesOriginal11d364b859f1df7d9f7ca031d219363c; ?>
<?php unset($__attributesOriginal11d364b859f1df7d9f7ca031d219363c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11d364b859f1df7d9f7ca031d219363c)): ?>
<?php $component = $__componentOriginal11d364b859f1df7d9f7ca031d219363c; ?>
<?php unset($__componentOriginal11d364b859f1df7d9f7ca031d219363c); ?>
<?php endif; ?>
    </a>
    <div class="min-text" style="flex:0 0 22%;display:flex;flex-direction:column;justify-content:center;gap:0.35em;text-align:center;min-height:0">
      <div class="min-value" style="font-size:clamp(0.85em,4.5cqi,1.35em);line-height:1.1"><?php echo e($diaHoy); ?> <?php echo e($mesHoy); ?></div>
      <div class="min-meta" style="font-size:clamp(0.65em,3cqi,0.9em)"><?php echo e($mesLargoHoy); ?></div>
    </div>
    <span class="widget-resize-handle"></span>
  </article>
</div>
<?php /**PATH C:\Users\LENOVO\enclaii-backend\resources\views/dashboard/widgets/agenda-today/minimalista.blade.php ENDPATH**/ ?>