

<?php
  $pacienteId = request('paciente', 1);
?>

<?php echo $__env->make('galeria.verimagen._header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('galeria.verimagen._styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startSection('content'); ?>

<?php
// Si no llegan capturas reales (acceso directo), usar datos de muestra.
if (empty($caps)) {
  $testImage = asset('images/colonoscopia.jpg');
  $caps = [
    ['n'=>1,'ts'=>'0:01:25','bg'=>'radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%)','src'=>$testImage],
    ['n'=>2,'ts'=>'0:02:15','bg'=>'radial-gradient(ellipse at 40% 60%,#4a1a0a 0%,#0c0612 100%)','src'=>$testImage],
    ['n'=>3,'ts'=>'0:04:32','bg'=>'radial-gradient(ellipse at 60% 40%,#2a1a3a 0%,#060814 100%)','src'=>$testImage],
    ['n'=>4,'ts'=>'0:06:18','bg'=>'radial-gradient(ellipse at 50% 50%,#5a1810 0%,#0a0610 100%)','src'=>$testImage],
  ];
  $current = 0;
}
$current = $current ?? 0;
?>

<div class="rise d2">
  <?php echo $__env->make('galeria.verimagen._acciones', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php echo $__env->make('galeria.verimagen._imagen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>

<?php echo $__env->make('galeria.verimagen._modal_comentarios', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('galeria.verimagen._modal_compartir', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('galeria.verimagen._modal_descarga', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo $__env->make('galeria.verimagen._scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views/galeria/verimagen.blade.php ENDPATH**/ ?>