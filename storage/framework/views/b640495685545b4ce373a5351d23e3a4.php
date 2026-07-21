<?php
$catIsClinicOwner = auth()->user()->clinica_rol === 'propietario';
?>

<div class="cat-table-wrap">
  <table class="cat-table">
    <thead>
      <tr>
        <th>Nombre de la sala</th>
        <th>Estado</th>
        <th class="text-center">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php $__empty_1 = true; $__currentLoopData = $salas ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sala): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td class="cat-name"><?php echo e($sala->nombre); ?></td>
          <td>
            <?php if($sala->activa): ?>
              <span class="cat-badge cat-badge-on">Activa</span>
            <?php else: ?>
              <span class="cat-badge cat-badge-off">Inactiva</span>
            <?php endif; ?>
          </td>
          <td class="text-center">
            <div class="cat-actions">
            <?php if($catIsClinicOwner): ?>
              <button type="button" class="cat-edit-btn cat-room-edit"
                data-id="<?php echo e($sala->id); ?>"
                data-nombre="<?php echo e($sala->nombre); ?>"
                data-activo="<?php echo e($sala->activa ? '1' : ''); ?>"
                title="Editar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
              </button>
              <button type="button" class="cat-del-btn cat-room-remove" data-action="<?php echo e(route('salas.destroy', $sala)); ?>" data-room-name="<?php echo e($sala->nombre); ?>" title="Eliminar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
              </button>
            <?php else: ?>
              <span class="cat-no-action" title="Solo el propietario puede eliminar salas">—</span>
            <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
          <td colspan="3" class="cat-empty-cell">No hay salas registradas.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\configuracion\sections\integraciones\__Int_hospital_catalog\__rooms\rooms_table.blade.php ENDPATH**/ ?>