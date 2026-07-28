
<div class="vv-topbar">
  <a href="<?php echo e($pacienteId ? route('galeria.paciente', $pacienteId) : route('galeria')); ?>" class="vv-btn cancel">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Volver a la galería
  </a>
  <a href="<?php echo e(route('galeria.video.editar', ['id' => $archivo->id, 'paciente' => $pacienteId])); ?>" class="vv-btn edit">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
    Editar
  </a>
  <button class="vv-btn dl" type="button" data-download-url="<?php echo e($videoDownloadUrl); ?>" data-download-name="<?php echo e($downloadName); ?>">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
    Descargar video
  </button>
</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/galeria/vervideo/_acciones.blade.php ENDPATH**/ ?>