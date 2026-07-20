<div class="int-bk-overlay" id="catProcModal" aria-hidden="true">
  <form class="int-bk-modal" id="catProcForm" data-store-url="<?php echo e(route('procedimientos.store')); ?>" data-update-url-template="<?php echo e(url('/procedimientos/__ID__')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" id="catProcId" name="id">
    <div class="int-bk-hdr">
      <div>
        <div class="int-bk-title" id="catProcTitle">Agregar procedimiento</div>
        <div class="int-bk-sub">Escribe el nombre del procedimiento que deseas registrar.</div>
      </div>
      <button type="button" class="int-bk-close" id="catProcClose" aria-label="Cerrar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="int-bk-body">
      <label class="int-bk-label" for="catProcName">Nombre del procedimiento</label>
      <input
        class="int-bk-input"
        id="catProcName"
        name="nombre"
        type="text"
        maxlength="255"
        placeholder="Ej. Colonoscopia"
        required
      >
    </div>

    <div class="int-bk-footer">
      <button type="button" class="int-bk-btn cancel" id="catProcCancel">Cancelar</button>
      <button type="submit" class="int-bk-btn submit" id="catProcSubmit">Guardar</button>
    </div>
  </form>
</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/configuracion/sections/integraciones/__Int_hospital_catalog/__process/__crud_process.blade.php ENDPATH**/ ?>