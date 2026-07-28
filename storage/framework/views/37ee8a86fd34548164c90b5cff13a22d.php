<?php $__env->startSection('title', 'Ver Video'); ?>
<?php $__env->startSection('active', 'galeria'); ?>
<?php $__env->startSection('header-title', 'Galería de pacientes'); ?>

<?php
  $pacienteId = $paciente?->id ?? $archivo->paciente_id ?? request('paciente');
  $nombrePaciente = $paciente?->nombre_completo ?? $estudio?->paciente_nombre ?? 'Paciente';
  $folioEstudio = $estudio?->folio ?? ('Video #'.$archivo->id);
  $tituloVideo = $archivo->nombre_original ?? $archivo->nombre ?? 'Video del estudio';
  $tipoEstudio = $estudio?->tipo ?: 'Video del estudio';
  $videoUrl = media_url($archivo->path);
  $videoExtension = strtoupper(pathinfo($archivo->nombre_original ?: $archivo->path, PATHINFO_EXTENSION) ?: 'VIDEO');
  $downloadName = $archivo->nombre_original ?: ('video-'.$archivo->id.'.'.strtolower($videoExtension));
  $videoDownloadUrl = route('galeria.video.archivo', $archivo->id);
  $editorConfig = array_merge([
    'brillo' => 100,
    'contraste' => 100,
    'saturacion' => 100,
    'nitidez' => 0,
    'zoom' => 100,
    'rotacion' => 0,
    'flip_h' => false,
    'flip_v' => false,
  ], $editorConfig ?? []);
  $videoTags = collect([$archivo->categoria, $tipoEstudio])
    ->merge($estudio?->hallazgos?->pluck('nombre') ?? collect())
    ->filter()
    ->unique()
    ->values();
?>

<?php $__env->startSection('header-sub'); ?>
  <a href="<?php echo e(route('galeria')); ?>" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <?php if($pacienteId): ?>
    <a href="<?php echo e(route('galeria.paciente', $pacienteId)); ?>" style="color:var(--txt-soft);text-decoration:none;font-size:13px"><?php echo e($nombrePaciente); ?></a>
  <?php else: ?>
    <span style="color:var(--txt-soft);font-size:13px"><?php echo e($nombrePaciente); ?></span>
  <?php endif; ?>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600"><?php echo e($tituloVideo); ?></span>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('galeria.vervideo._styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('galeria.vervideo._modal-descarga', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startSection('content'); ?>
  <div class="rise d2">
    <?php echo $__env->make('galeria.vervideo._acciones', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="vv-wrap">
      <?php echo $__env->make('galeria.vervideo._player', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <?php echo $__env->make('galeria.vervideo._sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
  <?php echo $__env->make('galeria.vervideo._scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views/galeria/vervideo.blade.php ENDPATH**/ ?>