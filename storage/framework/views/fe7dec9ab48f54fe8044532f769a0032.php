<div class="int-bk-overlay" id="catRoomModal" aria-hidden="true">
  <form class="int-bk-modal" id="catRoomForm" data-store-url="<?php echo e(route('salas.store')); ?>" data-update-url-template="<?php echo e(url('/salas/__ID__')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" id="catRoomId" name="id">
    <div class="int-bk-hdr">
      <div>
        <div class="int-bk-title" id="catRoomTitle">Agregar sala</div>
        <div class="int-bk-sub">Escribe el nombre de la sala que deseas registrar.</div>
      </div>
      <button type="button" class="int-bk-close" id="catRoomClose" aria-label="Cerrar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="int-bk-body">
      <label class="int-bk-label" for="catRoomName">Nombre de la sala</label>
      <input
        class="int-bk-input"
        id="catRoomName"
        name="nombre"
        type="text"
        maxlength="255"
        placeholder="Ej. Sala de endoscopía 1"
        required
      >

      <label class="int-check" style="margin-top:12px;display:inline-flex;">
        <input type="checkbox" id="catRoomActivo" name="activa" value="1" checked>
        Activa
      </label>
    </div>

    <div class="int-bk-footer">
      <button type="button" class="int-bk-btn cancel" id="catRoomCancel">Cancelar</button>
      <button type="submit" class="int-bk-btn submit" id="catRoomSubmit">Guardar</button>
    </div>
  </form>
</div><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\configuracion\sections\integraciones\__Int_hospital_catalog\__rooms\__crud_rooms.blade.php ENDPATH**/ ?>