<?php
$catIsClinicOwner = auth()->user()->clinica_rol === 'propietario';
$catRoleLabels = [
  'propietario' => 'Propietario',
  'administrador' => 'Administrador',
  'medico' => 'Médico',
  'recepcionista' => 'Recepcionista',
  'asistente' => 'Asistente',
  'enfermero' => 'Enfermero',
  'usuario' => 'Usuario',
];
?>

<div class="cat-table-wrap">
  <table class="cat-table">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Especialidad</th>
        <th>Rol</th>
        <th>Correo electrónico</th>
        <th>Estado</th>
        <th class="text-center">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php $__empty_1 = true; $__currentLoopData = $clinicMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
          $catSpecialty = $member->specialty ?: $member->medical_area ?: $member->position ?: '—';
          $catIsCurrentUser = $member->is(auth()->user());
          $catIsPropietario = $member->clinica_rol === 'propietario';
          $catCanRemove = $catIsClinicOwner && !$catIsCurrentUser && !$catIsPropietario;
        ?>
        <tr>
          <td class="cat-name">
            <?php echo e($member->name); ?>

            <?php if($catIsCurrentUser): ?><span class="cat-you">Tú</span><?php endif; ?>
          </td>
          <td class="cat-soft"><?php echo e($catSpecialty); ?></td>
          <td class="cat-role"><?php echo e($catRoleLabels[$member->clinica_rol] ?? ucfirst($member->clinica_rol)); ?></td>
          <td class="cat-soft"><?php echo e($member->email); ?></td>
          <td><span class="cat-badge cat-badge-on">Activo</span></td>
          <td class="cat-actions">
            <?php if($catCanRemove): ?>
              <button type="button" class="cat-del-btn cat-member-remove" data-action="<?php echo e(route('configuracion.clinic-members.destroy', $member)); ?>" data-member-name="<?php echo e($member->name); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
              </button>
            <?php else: ?>
              <?php
                $catNoActionTitle = match(true) {
                  $catIsPropietario => 'El propietario no puede eliminarse',
                  $catIsCurrentUser => 'No puedes eliminarte a ti mismo',
                  !$catIsClinicOwner => 'Solo el propietario puede retirar integrantes',
                  default => 'Acción no disponible',
                };
              ?>
              <span class="cat-no-action" title="<?php echo e($catNoActionTitle); ?>">—</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
          <td colspan="6" class="cat-empty-cell">No hay personal registrado en esta clínica.</td>
        </tr>
      <?php endif; ?>

      <?php $__currentLoopData = $clinicInvitations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td class="cat-name cat-pending-name"><?php echo e($invitation->email); ?></td>
          <td class="cat-soft">—</td>
          <td class="cat-role"><?php echo e($catRoleLabels[$invitation->rol] ?? ucfirst($invitation->rol)); ?></td>
          <td class="cat-soft"><?php echo e($invitation->email); ?></td>
          <td><span class="cat-badge cat-badge-off">Invitado</span></td>
          <td class="cat-actions">
            <?php if($catIsClinicOwner): ?>
              <button type="button" class="cat-del-btn cat-invite-revoke" data-action="<?php echo e(route('configuracion.clinic-invitations.destroy', $invitation)); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
              </button>
            <?php else: ?>
              <span class="cat-no-action">—</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
</div><?php /**PATH C:\Users\LENOVO\enclaii-backend\resources\views/configuracion/sections/integraciones/__Int_hospital_catalog/__personal/__personal_table.blade.php ENDPATH**/ ?>