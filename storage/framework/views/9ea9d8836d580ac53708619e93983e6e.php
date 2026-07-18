<?php
  $planUser = $billingUser ?? auth()->user()->billingUser();
  $clinicMembers = $clinicMembers ?? collect([auth()->user()]);
  $clinicInvitations = $clinicInvitations ?? collect();
  $clinicMemberLimit = $clinicMemberLimit ?? auth()->user()->clinicMemberLimit();
  $clinicMemberUsed = $clinicMembers->count() + $clinicInvitations->count();
  $clinicMemberRemaining = max(0, $clinicMemberLimit - $clinicMemberUsed);
  $clinicMemberPercent = min(100, (int) round(($clinicMemberUsed / max(1, $clinicMemberLimit)) * 100));
  $clinicMemberUpgradeOffer = auth()->user()->clinicMemberUpgradeOffer();
  $isClinicOwner = auth()->user()->clinica_rol === 'propietario';
  $storageSummary = app(\App\Services\StorageQuotaService::class)->summaryFor(auth()->user(), $clinicMembers);
?>

<?php echo $__env->make('configuracion.sections.plan._panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('configuracion.sections.plan._modal-plan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php if($isClinicOwner): ?>
  <?php echo $__env->make('configuracion.sections.plan._modal-invitacion', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('configuracion.sections.plan._styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('configuracion.sections.plan._scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('configuracion.sections.plan._toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('configuracion.sections.plan._modal-pago', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\laragon\www\endocare\resources\views/configuracion/sections/plan.blade.php ENDPATH**/ ?>