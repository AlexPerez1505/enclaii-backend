<div class="int-bk-overlay" id="intBackupModal" aria-hidden="true">
  <form
    class="int-bk-modal"
    id="intBackupForm"
    data-store-url="<?php echo e(route('configuracion.backups.store')); ?>"
    data-restore-url="<?php echo e(url('/configuracion/copias/__ID__/restaurar')); ?>"
    data-delete-url="<?php echo e(url('/configuracion/copias/__ID__')); ?>"
  >
    <?php echo csrf_field(); ?>
    <div class="int-bk-hdr">
      <div>
        <div class="int-bk-title">Crear copia de configuración</div>
        <div class="int-bk-sub">Podrás restaurarla más adelante o descargarla como archivo JSON.</div>
      </div>
      <button type="button" class="int-bk-close" id="intBackupClose" aria-label="Cerrar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="int-bk-body">
      <label class="int-bk-label" for="intBackupName">Nombre de la copia</label>
      <input
        class="int-bk-input"
        id="intBackupName"
        name="name"
        type="text"
        maxlength="100"
        value="Configuración principal - <?php echo e(now()->format(user_date_format().' H:i')); ?>"
        required
      >

      <div class="int-bk-modes">
        <label class="int-bk-mode">
          <input type="radio" name="mode" value="complete" checked>
          <span><strong>Copia completa</strong><span>Preferencias y perfil profesional.</span></span>
        </label>
        <label class="int-bk-mode">
          <input type="radio" name="mode" value="custom">
          <span><strong>Personalizada</strong><span>Elige las secciones que se guardarán.</span></span>
        </label>
      </div>

      <div class="int-bk-scopes" id="intBackupScopes">
        <label class="int-bk-scope">
          <input type="checkbox" name="scope[]" value="general" checked>
          <span><strong>Preferencias generales</strong><span>Formato, vista, accesibilidad y notificaciones.</span></span>
        </label>
        <label class="int-bk-scope">
          <input type="checkbox" name="scope[]" value="profile" checked>
          <span><strong>Perfil profesional</strong><span>Teléfono, especialidad, cédula, área médica y puesto.</span></span>
        </label>
      </div>

      <div class="int-bk-safe">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        La copia se cifra en la base de datos. Nunca incluye contraseñas, información de Stripe, pacientes, estudios, archivos ni tokens secretos.
      </div>
    </div>

    <div class="int-bk-footer">
      <button type="button" class="int-bk-btn cancel" id="intBackupCancel">Cancelar</button>
      <button type="submit" class="int-bk-btn submit" id="intBackupSubmit">Crear copia</button>
    </div>
  </form>
</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\configuracion\sections\integraciones\_modal-copia.blade.php ENDPATH**/ ?>