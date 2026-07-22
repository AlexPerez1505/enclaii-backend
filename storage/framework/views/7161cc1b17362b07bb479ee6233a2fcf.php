
<div id="pmModal" class="pm-ov" aria-hidden="true">
  <div class="pm-modal">
    <button type="button" class="pm-x" id="pmClose" aria-label="Cerrar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <h3 class="pm-title">Actualizar método de pago</h3>
    <p class="pm-sub">Tu información se procesa de forma segura con Stripe.</p>
    <div id="pmElement" class="pm-element"></div>
    <div id="pmError" class="pm-error" style="display:none"></div>
    <button type="button" class="pm-submit" id="pmSubmit">
      <span id="pmSubmitText">Guardar tarjeta</span>
    </button>
  </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.pm-ov{position:fixed;inset:0;z-index:10001;display:none;align-items:center;justify-content:center;padding:18px;background:rgba(5,9,20,.66);backdrop-filter:blur(3px)}
.pm-ov.open{display:flex}
.pm-modal{position:relative;width:100%;max-width:440px;background:var(--card);border:1px solid var(--stroke);border-radius:16px;box-shadow:0 30px 80px -20px rgba(0,0,0,.5);padding:26px 26px 24px;animation:gpIn .22s var(--ease-out)}
.pm-x{position:absolute;top:16px;right:16px;width:32px;height:32px;display:grid;place-items:center;border-radius:9px;color:var(--txt-soft);border:0;background:none;cursor:pointer}
.pm-x:hover{background:var(--hover-bg);color:var(--txt)}
.pm-x svg{width:18px;height:18px}
.pm-title{font-family:'Sora',sans-serif;font-size:18px;font-weight:700}
.pm-sub{font-size:12.5px;color:var(--txt-soft);margin:4px 0 18px}
.pm-element{padding:4px 0 8px}
.pm-error{color:var(--red);font-size:12.5px;margin:8px 0 0;padding:10px 12px;border-radius:9px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25)}
.pm-submit{width:100%;margin-top:18px;padding:12px;border-radius:11px;border:0;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;font-family:'Sora',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:opacity .15s}
.pm-submit:hover{opacity:.92}
.pm-submit:disabled{opacity:.6;cursor:default}
/* Reactivar */
.gp-resume{border-color:rgba(56,199,244,.4);background:rgba(56,199,244,.07)}
.gp-resume-ico{color:var(--cyan);background:rgba(56,199,244,.14)}
.gp-resume-btn{border-color:var(--cyan);color:var(--cyan)}
.gp-resume-btn:hover{background:rgba(56,199,244,.12)}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const STRIPE_KEY = "<?php echo e(config('services.stripe.key')); ?>";
  const SETUP_INTENT_URL = "<?php echo e(url('/stripe/setup-intent')); ?>";
  const PM_UPDATE_URL = "<?php echo e(url('/stripe/payment-method')); ?>";
  const CSRF = "<?php echo e(csrf_token()); ?>";

  const modal = document.getElementById('pmModal');
  if (!modal || !STRIPE_KEY || typeof Stripe === 'undefined') return;

  const stripe = Stripe(STRIPE_KEY);
  let elements, paymentElement, mounted = false;

  const errorBox = document.getElementById('pmError');
  const submitBtn = document.getElementById('pmSubmit');
  const submitText = document.getElementById('pmSubmitText');

  function showError(msg){ errorBox.textContent = msg; errorBox.style.display = 'block'; }
  function clearError(){ errorBox.style.display = 'none'; }
  function setLoading(on){ submitBtn.disabled = on; submitText.textContent = on ? 'Guardando...' : 'Guardar tarjeta'; }

  function openModal(){
    modal.classList.add('open');
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
    clearError();
    if (!mounted) initElements();
  }
  function closeModal(){
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  }

  function initElements(){
    setLoading(true);
    fetch(SETUP_INTENT_URL, { headers: { 'Accept':'application/json' } })
      .then(r => r.json())
      .then(data => {
        if (!data.clientSecret) { showError(data.error || 'No se pudo iniciar.'); setLoading(false); return; }
        elements = stripe.elements({ clientSecret: data.clientSecret, appearance: { theme: 'night', variables: { colorPrimary: '#38c7f4' } } });
        paymentElement = elements.create('payment');
        paymentElement.mount('#pmElement');
        mounted = true;
        setLoading(false);
      })
      .catch(() => { showError('Error de conexión.'); setLoading(false); });
  }

  document.querySelectorAll('[data-pm-open]').forEach(btn => {
    btn.addEventListener('click', (e) => { e.preventDefault(); openModal(); });
  });
  document.getElementById('pmClose')?.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

  submitBtn.addEventListener('click', async () => {
    if (!elements) return;
    setLoading(true);
    clearError();

    const { error, setupIntent } = await stripe.confirmSetup({
      elements,
      redirect: 'if_required',
    });

    if (error) {
      showError(error.message || 'No se pudo guardar la tarjeta.');
      setLoading(false);
      return;
    }

    // Guardar como método de pago por defecto en el backend
    fetch(PM_UPDATE_URL, {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
      body: JSON.stringify({ payment_method: setupIntent.payment_method }),
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          closeModal();
          showToast('Tarjeta actualizada', `${data.brand} ····${data.last4}`);
          setTimeout(() => location.reload(), 1800);
        } else {
          showError(data.error || 'No se pudo actualizar.');
          setLoading(false);
        }
      })
      .catch(() => { showError('Error de conexión.'); setLoading(false); });
  });
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\LENOVO\enclaii-backend\resources\views/configuracion/sections/plan/_modal-pago.blade.php ENDPATH**/ ?>