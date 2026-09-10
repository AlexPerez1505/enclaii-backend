<table class="gp-table <?php echo e($tableClass ?? ''); ?>">
  <thead>
    <tr><th>Usuario</th><th>Rol</th><th>Estado</th><th>Último acceso</th><th>Acciones</th></tr>
  </thead>
  <tbody>
    <?php $__currentLoopData = $clinicMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $roleLabels = [
          'propietario' => 'Propietario',
          'administrador' => 'Administrador',
          'medico' => 'Médico',
          'recepcionista' => 'Recepcionista',
          'asistente' => 'Asistente',
        ];
        $lastActivity = $member->connected_sessions_max_last_activity
          ? \Carbon\Carbon::createFromTimestamp($member->connected_sessions_max_last_activity)->diffForHumans()
          : 'Sin acceso reciente';
      ?>
      <tr>
        <td>
          <span class="gp-u">
            <?php echo e($member->name); ?>

            <?php if($member->is(auth()->user())): ?><span class="gp-you">Tú</span><?php endif; ?>
          </span>
          <small class="gp-member-email"><?php echo e($member->email); ?></small>
        </td>
        <td><?php echo e($roleLabels[$member->clinica_rol] ?? ucfirst($member->clinica_rol)); ?></td>
        <td><span class="gp-st">Activo</span></td>
        <td><?php echo e($member->is(auth()->user()) ? 'Ahora' : $lastActivity); ?></td>
        <td>
          <?php if($isClinicOwner && !$member->is(auth()->user()) && $member->clinica_rol !== 'propietario'): ?>
            <button type="button" class="gp-member-remove" data-member-id="<?php echo e($member->id); ?>" data-member-name="<?php echo e($member->name); ?>">Retirar</button>
          <?php else: ?>
            <span class="gp-no-action">—</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php $__currentLoopData = $clinicInvitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td>
          <span class="gp-u"><?php echo e($invitation->email); ?></span>
          <small class="gp-member-email">Correo autorizado para crear cuenta</small>
        </td>
        <td><?php echo e($roleLabels[$invitation->rol] ?? ucfirst($invitation->rol)); ?></td>
        <td><span class="gp-st pending">Pendiente</span></td>
        <td>Esperando registro</td>
        <td>
          <?php if($isClinicOwner): ?>
            <button type="button" class="gp-invite-revoke" data-invitation-id="<?php echo e($invitation->id); ?>">Cancelar</button>
          <?php else: ?>
            <span class="gp-no-action">—</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>
<?php /**PATH C:\Users\LENOVO\enclaii-backend\resources\views/configuracion/partials/plan-members-table.blade.php ENDPATH**/ ?>