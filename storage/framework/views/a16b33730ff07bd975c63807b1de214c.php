<div class="gp-ov gp-invite-ov" id="gpInviteModal" aria-hidden="true">
  <div class="gp-modal gp-invite-modal" role="dialog" aria-modal="true" aria-labelledby="gpInviteTitle">
    <button type="button" class="gp-x" id="gpInviteClose" aria-label="Cerrar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    <div class="gp-head">
      <h2 id="gpInviteTitle">Agregar integrante por correo</h2>
      <p>Guarda el correo que la persona utilizará para crear su cuenta y entrar a <?php echo e(auth()->user()->clinica->nombre); ?>.</p>
    </div>

    <form id="gpInviteForm" class="gp-invite-form">
      <label>
        Correo electrónico
        <input type="email" name="email" placeholder="medico@clinica.com" required>
      </label>
      <label>
        Rol
        <select name="rol" required>
          <option value="medico">Médico</option>
          <option value="administrador">Administrador</option>
          <option value="recepcionista">Recepcionista</option>
          <option value="asistente">Asistente</option>
        </select>
      </label>
      <div class="gp-invite-error" id="gpInviteError" hidden></div>
      <button type="submit" class="gp-invite-submit">Autorizar correo</button>
    </form>

    <div class="gp-invite-result" id="gpInviteResult" hidden>
      <strong id="gpInviteResultTitle">Correo autorizado</strong>
      <p id="gpInviteResultText"></p>
      <button type="button" class="gp-invite-submit" id="gpInviteDone">Terminar</button>
    </div>

    <div class="gp-invite-limit" id="gpInviteLimit" hidden>
      <span class="gp-limit-icon">!</span>
      <strong>Límite de cuentas alcanzado</strong>
      <p id="gpInviteLimitText"></p>
      <button type="button" class="gp-invite-submit" id="gpInviteUpgrade"></button>
      <button type="button" class="gp-limit-cancel" id="gpInviteLimitCancel">Ahora no</button>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\configuracion\sections\plan\_modal-invitacion.blade.php ENDPATH**/ ?>