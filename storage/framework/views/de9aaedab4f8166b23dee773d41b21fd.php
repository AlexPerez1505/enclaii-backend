
<div class="vv-side">

  
  <div class="vv-info-card">
    <div class="vv-info-row">
      <div class="vv-info-lbl">Paciente</div>
      <div class="vv-info-val"><?php echo e($nombrePaciente); ?></div>
    </div>
    <div class="vv-info-row">
      <div class="vv-info-lbl">ID Paciente</div>
      <div class="vv-info-val"><?php echo e($paciente?->folio ?? $paciente?->identificacion ?? '—'); ?></div>
    </div>
    <div class="vv-info-row">
      <div class="vv-info-lbl">Fecha de estudio</div>
      <div class="vv-info-val"><?php echo e(format_user_date($estudio?->fecha ?? $archivo->capturado_en) ?: '—'); ?><?php echo e($estudio?->hora_inicio ? ' · '.format_user_time($estudio->hora_inicio) : ''); ?></div>
    </div>
    <div class="vv-info-row">
      <div class="vv-info-lbl">Tipo de estudio</div>
      <div class="vv-info-val"><?php echo e($tipoEstudio); ?></div>
    </div>
    <div class="vv-info-row">
      <div class="vv-info-lbl">Médico</div>
      <div class="vv-info-val"><?php echo e($estudio?->medico ?? $paciente?->medico ?? '—'); ?></div>
    </div>
    <div class="vv-info-row">
      <div class="vv-info-lbl">Equipo</div>
      <div class="vv-info-val"><?php echo e($estudio?->equipo ?? $paciente?->equipo_utilizado ?? '—'); ?></div>
    </div>
    <div class="vv-info-row">
      <div class="vv-info-lbl">Estado</div>
      <div class="vv-status">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
        <?php echo e($estudio?->estado_texto ?? 'Guardado'); ?>

      </div>
    </div>
  </div>

  
  <div class="vv-info-card">
    <div class="vv-section-head">
      <span class="vv-section-lbl">Diagnóstico</span>
    </div>
    <div class="vv-diag-row">
      <span class="vv-diag-txt"><?php echo e($estudio?->diagnostico ?: 'Sin diagnóstico registrado.'); ?></span>
      <div class="vv-diag-av"><?php echo e(mb_strtoupper(mb_substr($tipoEstudio, 0, 1))); ?></div>
    </div>
  </div>

  
  <div class="vv-info-card">
    <div class="vv-section-head">
      <span class="vv-section-lbl">Observaciones</span>
      <a class="vv-edit-ic" href="<?php echo e(route('galeria.video.editar', ['id' => $archivo->id, 'paciente' => $pacienteId])); ?>" aria-label="Editar observaciones">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      </a>
    </div>
    <p class="vv-obs-txt"><?php echo e($estudio?->observaciones ?: ($archivo->descripcion ?: 'Sin observaciones.')); ?></p>
  </div>

  
  <div class="vv-info-card">
    <div class="vv-section-head">
      <span class="vv-section-lbl">Etiquetas</span>
      <a class="vv-edit-ic" href="<?php echo e(route('galeria.video.editar', ['id' => $archivo->id, 'paciente' => $pacienteId])); ?>" aria-label="Editar etiquetas">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      </a>
    </div>
    <div class="vv-tags">
      <?php $__empty_1 = true; $__currentLoopData = $videoTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <span class="vv-tag"><?php echo e($tag); ?></span>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <span class="vv-tag">Video</span>
      <?php endif; ?>
    </div>
  </div>

</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\galeria\vervideo\_sidebar.blade.php ENDPATH**/ ?>