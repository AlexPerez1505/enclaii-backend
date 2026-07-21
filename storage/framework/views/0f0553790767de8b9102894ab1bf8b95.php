<?php ($criticalSecurity = auth()->user()->securityPreferences()); ?>

<style>
  .critical-password-overlay{position:fixed;inset:0;z-index:12000;display:none;align-items:center;justify-content:center;padding:20px;background:rgba(2,6,23,.72);backdrop-filter:blur(7px)}
  .critical-password-overlay.open{display:flex}
  .critical-password-dialog{width:min(420px,100%);padding:24px;border:1px solid var(--stroke-strong);border-radius:18px;background:var(--modal-bg);box-shadow:0 28px 80px rgba(0,0,0,.5)}
  .critical-password-head{display:flex;justify-content:space-between;gap:18px;margin-bottom:18px}
  .critical-password-title{font-family:'Sora',sans-serif;font-size:17px;font-weight:750;color:var(--txt)}
  .critical-password-message{margin-top:5px;font-size:12.5px;line-height:1.5;color:var(--txt-soft)}
  .critical-password-close{width:32px;height:32px;flex:none;border:1px solid var(--stroke);border-radius:9px;background:var(--panel-2);color:var(--txt-soft);font-size:20px;cursor:pointer}
  .critical-password-label{display:block;margin-bottom:7px;font-size:12px;font-weight:700;color:var(--txt)}
  .critical-password-input{width:100%;box-sizing:border-box;padding:11px 13px;border:1px solid var(--stroke-strong);border-radius:10px;outline:none;background:var(--input-bg);color:var(--txt);font:inherit}
  .critical-password-input:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(14,165,233,.12)}
  .critical-password-error{min-height:18px;margin-top:6px;font-size:11.5px;color:var(--red)}
  .critical-password-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:13px}
  .critical-password-button{padding:10px 16px;border-radius:10px;font:inherit;font-size:12.5px;font-weight:750;cursor:pointer}
  .critical-password-button.cancel{border:1px solid var(--stroke-strong);background:transparent;color:var(--txt)}
  .critical-password-button.confirm{border:0;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff}
  .critical-password-button:disabled{opacity:.6;cursor:wait}
</style>

<div class="critical-password-overlay" id="criticalPasswordOverlay" aria-hidden="true">
  <form class="critical-password-dialog" id="criticalPasswordDialog">
    <div class="critical-password-head">
      <div>
        <div class="critical-password-title">Confirma tu contraseña</div>
        <div class="critical-password-message" id="criticalPasswordMessage">Esta acción contiene información sensible.</div>
      </div>
      <button type="button" class="critical-password-close" id="criticalPasswordClose" aria-label="Cerrar">&times;</button>
    </div>
    <label class="critical-password-label" for="criticalPasswordInput">Contraseña actual</label>
    <input class="critical-password-input" id="criticalPasswordInput" type="password" autocomplete="current-password" required>
    <div class="critical-password-error" id="criticalPasswordError"></div>
    <div class="critical-password-actions">
      <button type="button" class="critical-password-button cancel" id="criticalPasswordCancel">Cancelar</button>
      <button type="submit" class="critical-password-button confirm" id="criticalPasswordConfirm">Confirmar</button>
    </div>
  </form>
</div>

<script>
(function(){
  const overlay = document.getElementById('criticalPasswordOverlay');
  const dialog = document.getElementById('criticalPasswordDialog');
  const input = document.getElementById('criticalPasswordInput');
  const message = document.getElementById('criticalPasswordMessage');
  const error = document.getElementById('criticalPasswordError');
  const confirmButton = document.getElementById('criticalPasswordConfirm');
  const closeButton = document.getElementById('criticalPasswordClose');
  const cancelButton = document.getElementById('criticalPasswordCancel');
  const csrf = <?php echo json_encode(csrf_token(), 15, 512) ?>;
  const verifyUrl = <?php echo json_encode(route('configuracion.security.authorize'), 15, 512) ?>;
  const requirements = {
    patients: <?php echo json_encode($criticalSecurity['require_password_for_patients'], 15, 512) ?>,
    studies: <?php echo json_encode($criticalSecurity['require_password_for_studies'], 15, 512) ?>,
    security_settings: true
  };
  let pending = null;

  function finish(token) {
    overlay.classList.remove('open');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    input.value = '';
    error.textContent = '';
    const resolve = pending?.resolve;
    pending = null;
    resolve?.(token);
  }

  function authorize(scope, actionMessage) {
    if (!requirements[scope]) return Promise.resolve('');
    if (pending) return Promise.resolve(null);

    return new Promise(resolve => {
      pending = { scope, resolve };
      message.textContent = actionMessage || 'Confirma tu identidad para continuar con esta acción sensible.';
      overlay.classList.add('open');
      overlay.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      setTimeout(() => input.focus(), 60);
    });
  }

  dialog.addEventListener('submit', async event => {
    event.preventDefault();
    if (!pending || !input.value) return;
    error.textContent = '';
    confirmButton.disabled = true;
    confirmButton.textContent = 'Verificando...';

    try {
      const response = await fetch(verifyUrl, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          scope: pending.scope,
          current_password: input.value
        })
      });
      const data = await response.json().catch(() => ({}));
      if (!response.ok || !data.token) {
        const validationMessage = data.errors?.current_password?.[0];
        throw new Error(validationMessage || data.message || 'No se pudo confirmar la contraseña.');
      }
      finish(data.token);
    } catch (requestError) {
      error.textContent = requestError.message;
      input.select();
    } finally {
      confirmButton.disabled = false;
      confirmButton.textContent = 'Confirmar';
    }
  });

  closeButton.addEventListener('click', () => finish(null));
  cancelButton.addEventListener('click', () => finish(null));
  overlay.addEventListener('click', event => {
    if (event.target === overlay) finish(null);
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && overlay.classList.contains('open')) finish(null);
  });

  window.CriticalSecurity = {
    authorize,
    requires: scope => !!requirements[scope],
    setRequirement: (scope, value) => { requirements[scope] = !!value; }
  };
})();
</script>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\components\critical-password-modal.blade.php ENDPATH**/ ?>