<?php $__env->startSection('title', 'Customer Success Dashboard'); ?>
<?php $__env->startSection('active', 'customer-success-dashboard'); ?>
<?php $__env->startSection('header-title', 'Customer Success'); ?>
<?php $__env->startSection('header-sub'); ?>
  Panel de control de comunicaciones y gestión de usuarios
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sidebar'); ?>
  <?php echo $__env->make('customer-success.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('bottom-nav'); ?>
  <?php echo $__env->make('customer-success.partials.bottom-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.cs-dashboard{display:grid;gap:20px;grid-template-columns:repeat(12,1fr)}
.cs-dashboard > .cs-card{grid-column:span 12}
.cs-dashboard > .cs-card.half{grid-column:span 6}
.cs-stat{display:grid;gap:6px;padding:20px;border:1px solid var(--stroke);border-radius:16px;background:var(--panel-2)}
.cs-stat-value{font-size:28px;font-weight:800;color:var(--txt)}
.cs-stat-label{font-size:12px;color:var(--txt-soft)}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="cs-dashboard">

  <div class="cs-card" style="grid-column:span 12">
    <div class="cs-card-title">Bienvenido al panel de Customer Success</div>
    <p style="margin:0;color:var(--txt-soft);font-size:14px;line-height:1.6">
      Desde aquí puedes gestionar anuncios, administrar usuarios con rol Customer Success y revisar la auditoría de acciones.
    </p>
  </div>

  <div class="cs-card half">
    <div class="cs-card-title">Resumen</div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:14px">
      <div class="cs-stat">
        <div class="cs-stat-value"><?php echo e($anunciosCount); ?></div>
        <div class="cs-stat-label">Anuncios</div>
      </div>
      <div class="cs-stat">
        <div class="cs-stat-value"><?php echo e($usuariosCs); ?></div>
        <div class="cs-stat-label">Usuarios CS</div>
      </div>
    </div>
  </div>

  <div class="cs-card half">
    <div class="cs-card-title">Accesos directos</div>
    <div style="display:flex;flex-wrap:wrap;gap:10px">
      <a href="<?php echo e(route('customer-success.anuncios')); ?>" class="cs-btn cs-btn-primary">Ver anuncios</a>
      <a href="<?php echo e(route('customer-success.gestion-usuarios')); ?>" class="cs-btn cs-btn-secondary">Gestionar usuarios</a>
    </div>
  </div>

  <div class="cs-card">
    <div class="cs-card-title" id="auditoria">Últimas acciones de auditoría</div>
    <?php if($auditLogs->isEmpty()): ?>
      <div class="cs-empty">No hay registros de auditoría.</div>
    <?php else: ?>
      <table class="cs-table">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Acción</th>
            <th>IP</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr>
            <td><?php echo e($log->user->name ?? '—'); ?></td>
            <td><?php echo e($log->action); ?></td>
            <td><?php echo e($log->ip_address ?? '—'); ?></td>
            <td><?php echo e($log->created_at?->format('d/m/Y H:i') ?? '—'); ?></td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views-Tec/customer-success/dashboard/index.blade.php ENDPATH**/ ?>