<?php $__env->startSection('title', 'Ver imagen'); ?>
<?php $__env->startSection('active', 'galeria'); ?>
<?php $__env->startSection('header-title', 'Galería de pacientes'); ?>

<?php $__env->startSection('header-sub'); ?>
  <?php
    $patientName = $paciente?->nombre_completo ?? 'Paciente';
    $imageLabel = $archivo?->nombre
      ?? $archivo?->nombre_original
      ?? ('Imagen #'.($id ?? request()->route('id')));
  ?>

  <a href="<?php echo e(route('galeria')); ?>" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <a href="<?php echo e(route('galeria.paciente', $pacienteId)); ?>" style="color:var(--txt-soft);text-decoration:none;font-size:13px"><?php echo e($patientName); ?></a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600"><?php echo e($imageLabel); ?></span>
<?php $__env->stopSection(); ?>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\galeria\verimagen\_header.blade.php ENDPATH**/ ?>