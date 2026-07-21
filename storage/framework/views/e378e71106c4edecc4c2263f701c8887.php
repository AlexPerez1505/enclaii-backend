<?php $__env->startSection('title', 'Importar fotos'); ?>
<?php $__env->startSection('active', 'nuevo-estudio'); ?>
<?php $__env->startSection('header-title', 'Importar fotos'); ?>
<?php $__env->startSection('header-sub'); ?>
  Sube imagenes o videos al estudio
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<?php echo $__env->make('estudios.importar.importar-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="ip-shell rise d2">
  <div class="ip-toolbar">
    <a class="ip-back" href="<?php echo e(route('nuevo-estudio')); ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver a Nuevo estudio
    </a>
    <div class="ip-title">Importar fotos</div>
    <a class="ip-btn ip-btn-secondary" href="<?php echo e(route('galeria')); ?>" style="text-decoration:none;margin-left:auto">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
      Ver capturas en galeria
    </a>
  </div>

  <div class="ip-dropzone" id="ipDropzone">
    <input type="file" id="ipFileInput" multiple accept="image/*,video/*">
    <div class="ip-dropzone-icon">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
    </div>
    <div class="ip-dropzone-title">Arrastra archivos aqui</div>
    <div class="ip-dropzone-desc">O haz clic para seleccionar imagenes y videos</div>
    <div class="ip-dropzone-hint">JPG, PNG, MP4, MOV · Maximo 50 MB por archivo</div>
  </div>

  <div class="ip-empty" id="ipEmpty">No hay archivos seleccionados.</div>

  <div class="ip-preview-grid" id="ipPreviewGrid"></div>

  <div class="ip-actions" id="ipActions" style="display:none">
    <button class="ip-btn ip-btn-secondary" type="button" id="ipClearBtn">Limpiar</button>
    <button class="ip-btn ip-btn-primary" type="button" id="ipImportBtn" disabled>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Importar <span id="ipCount">0</span> archivos
    </button>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo $__env->make('estudios.importar.importar-js', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\estudios\importar\index.blade.php ENDPATH**/ ?>