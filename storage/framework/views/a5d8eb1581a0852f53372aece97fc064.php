<?php $__env->startSection('title', 'Selecciona tu plan'); ?>
<?php $__env->startSection('po-title', 'Selecciona tu plan'); ?>
<?php $__env->startSection('po-sub', 'Elige el plan que mejor se adapte a tus necesidades para comenzar a usar EndoCare.'); ?>

<?php $__env->startSection('content'); ?>

<div class="pl-plans">
  
  <div class="pl-card <?php echo e(auth()->user()->stripe_plan === 'clinica' ? 'current' : ''); ?>" data-card="clinica">
    <div class="pc-top">
      <span class="pc-ico" style="color:var(--green)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg></span>
      <?php if(auth()->user()->stripe_plan === 'clinica'): ?>
        <span class="pc-badge">Plan actual</span>
      <?php endif; ?>
    </div>
    <h4>Clinica</h4><div class="pc-gb">50 GB</div>
    <ul class="pc-feat">
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Almacenamiento en la nube</li>
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>IA Reportes básica</li>
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Soporte por email</li>
    </ul>
    <div class="pc-interval">
      <button class="pc-int-btn active" data-interval="month" data-price="$10,000" data-label="/mes">Mensual</button>
      <button class="pc-int-btn" data-interval="quarter" data-price="$20,000" data-label="/3 meses">Trimestral</button>
      <button class="pc-int-btn" data-interval="year" data-price="$85,000" data-label="/año">Anual</button>
    </div>
    <div class="pc-price">$10,000<span> /mes</span></div>
    <a href="#" class="pc-cta <?php echo e(auth()->user()->stripe_plan === 'clinica' ? 'disabled' : ''); ?>" data-plan="clinica" data-interval="month">
      <?php echo e(auth()->user()->stripe_plan === 'clinica' ? 'Plan actual' : 'Contratar plan de Clinica'); ?>

    </a>
  </div>

  
  <div class="pl-card <?php echo e(auth()->user()->stripe_plan === 'hospital' ? 'current' : ''); ?>" data-card="hospital">
    <div class="pc-top">
      <span class="pc-ico" style="color:#a47bff"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l4 3 5-7 5 7 4-3-2 12H5L3 8z"/></svg></span>
      <?php if(auth()->user()->stripe_plan === 'hospital'): ?>
        <span class="pc-badge">Plan actual</span>
      <?php endif; ?>
    </div>
    <h4>Hospital</h4><div class="pc-gb">100 GB</div>
    <ul class="pc-feat">
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>IA Reportes avanzada</li>
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Almacenamiento ampliado</li>
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Soporte prioritario</li>
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Exportación de reportes</li>
    </ul>
    <div class="pc-interval">
      <button class="pc-int-btn active" data-interval="month" data-price="$25,000" data-label="/mes">Mensual</button>
      <button class="pc-int-btn" data-interval="quarter" data-price="$65,000" data-label="/3 meses">Trimestral</button>
      <button class="pc-int-btn" data-interval="year" data-price="$200,000" data-label="/año">Anual</button>
    </div>
    <div class="pc-price">$25,000<span> /mes</span></div>
    <a href="#" class="pc-cta <?php echo e(auth()->user()->stripe_plan === 'hospital' ? 'disabled' : ''); ?>" data-plan="hospital" data-interval="month">
      <?php echo e(auth()->user()->stripe_plan === 'hospital' ? 'Plan actual' : 'Contratar plan de Hospital'); ?>

    </a>
  </div>

  
  <div class="pl-card <?php echo e(auth()->user()->stripe_plan === 'red_medica' ? 'current' : ''); ?>" data-card="red_medica">
    <div class="pc-top">
      <span class="pc-ico" style="color:var(--red)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></span>
      <?php if(auth()->user()->stripe_plan === 'red_medica'): ?>
        <span class="pc-badge">Plan actual</span>
      <?php endif; ?>
    </div>
    <h4>Red medica</h4><div class="pc-gb">250 GB</div>
    <ul class="pc-feat">
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Todo lo del plan Profesional</li>
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Más almacenamiento</li>
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Integraciones avanzadas</li>
      <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Soporte 24/7</li>
    </ul>
    <div class="pc-interval">
      <button class="pc-int-btn active" data-interval="month" data-price="$35,000" data-label="/mes">Mensual</button>
      <button class="pc-int-btn" data-interval="quarter" data-price="$90,000" data-label="/3 meses">Trimestral</button>
      <button class="pc-int-btn" data-interval="year" data-price="$385,000" data-label="/año">Anual</button>
    </div>
    <div class="pc-price">$35,000<span> /mes</span></div>
    <a href="#" class="pc-cta <?php echo e(auth()->user()->stripe_plan === 'red_medica' ? 'disabled' : ''); ?>" data-plan="red_medica" data-interval="month">
      <?php echo e(auth()->user()->stripe_plan === 'red_medica' ? 'Plan actual' : 'Contratar plan de Red Médica'); ?>

    </a>
  </div>
</div>


<div id="stripeCheckoutModal" class="sc-modal" style="display:none">
  <div class="sc-overlay" onclick="closeStripeModal()"></div>
  <div class="sc-content">
    <div class="sc-header">
      <h3>Elige cómo pagas</h3>
      <button type="button" onclick="closeStripeModal()" class="sc-close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="sc-summary">
      <div class="sc-summary-row">
        <span id="scPlanName">Plan</span>
        <strong id="scPlanPrice">$0</strong>
      </div>
      <p class="sc-secure">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Tu forma de pago está cifrada. Cancela cuando quieras.
      </p>
    </div>

    <form id="scPaymentForm">
      <div id="payment-element" class="sc-element"></div>
      <div id="scError" class="sc-error" style="display:none"></div>

      
      <div class="sc-legal">
        <label class="sc-legal-item">
          <input type="checkbox" class="sc-legal-cb" data-doc="Términos y Condiciones" required>
          <span>He leído y acepto los <a href="https://stripe.com/mx/privacy" target="_blank" rel="noopener">Términos y Condiciones</a></span>
        </label>
        <label class="sc-legal-item">
          <input type="checkbox" class="sc-legal-cb" data-doc="Aviso de Privacidad" required>
          <span>He leído y acepto el <a href="https://stripe.com/mx/privacy" target="_blank" rel="noopener">Aviso de Privacidad</a></span>
        </label>
        <label class="sc-legal-item">
          <input type="checkbox" class="sc-legal-cb" data-doc="Políticas del Sistema" required>
          <span>Acepto las <a href="https://stripe.com/mx/privacy" target="_blank" rel="noopener">Políticas del Sistema</a></span>
        </label>
      </div>

      <button type="submit" id="scSubmit" class="sc-pay-btn" disabled>
        <span id="scSubmitText">Suscribirme</span>
        <span id="scSpinner" class="sc-spinner" style="display:none"></span>
      </button>
    </form>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.pl-plans{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1000px;margin:0 auto}
@media(max-width:900px){.pl-plans{grid-template-columns:1fr 1fr;max-width:600px}}
@media(max-width:600px){.pl-plans{grid-template-columns:1fr;max-width:360px}}

.pl-card{display:flex;flex-direction:column;padding:28px 24px;border-radius:16px;border:1px solid var(--stroke);background:var(--card);transition:border-color .2s,transform .2s;min-height:420px}
.pl-card.current{border-color:var(--cyan);box-shadow:0 0 0 1px var(--cyan)}
.pl-card:hover{transform:translateY(-3px)}

.pl-card .pc-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
.pl-card .pc-ico{width:44px;height:44px;border-radius:12px;display:grid;place-items:center;background:rgba(110,160,255,.08)}
.pl-card .pc-ico svg{width:24px;height:24px}
.pl-card .pc-badge{font-size:10px;font-weight:700;color:var(--cyan);background:rgba(56,199,244,.14);padding:3px 10px;border-radius:6px}

.pl-card h4{font-family:'Sora',sans-serif;font-size:20px;font-weight:700;margin-top:14px}
.pl-card .pc-gb{font-size:13px;color:var(--txt-soft);margin-top:2px}

.pl-card .pc-feat{list-style:none;margin:18px 0;padding:0;display:flex;flex-direction:column;gap:10px;flex:1}
.pl-card .pc-feat li{display:flex;align-items:flex-start;gap:8px;font-size:13px;color:var(--txt-soft);line-height:1.4}
.pl-card .pc-feat svg{width:15px;height:15px;color:var(--green);flex:none;margin-top:2px}

.pl-card .pc-interval{display:flex;gap:6px;margin:14px 0;justify-content:center}
.pc-int-btn{padding:7px 14px;border-radius:8px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);font-size:12px;font-weight:600;cursor:pointer;transition:all .15s ease}
.pc-int-btn:hover{background:rgba(59,130,246,.1);color:var(--txt)}
.pc-int-btn.active{background:var(--cyan);color:#fff;border-color:var(--cyan)}

.pl-card .pc-price{font-family:'Sora',sans-serif;font-size:26px;font-weight:800;margin-top:auto;padding-top:14px}
.pl-card .pc-price span{font-size:13px;font-weight:500;color:var(--txt-soft)}

.pl-card .pc-cta{margin-top:14px;padding:12px;border-radius:12px;font-size:14px;font-weight:700;text-align:center;border:0;color:#fff;background:linear-gradient(135deg,#0B1A4A 0%,#12266B 55%,#1E5AE8 130%);box-shadow:0 10px 24px -10px rgba(11,26,74,.55);transition:opacity .15s,transform .1s;cursor:pointer;text-decoration:none;display:block}
.pl-card .pc-cta.disabled{opacity:.45;cursor:default}
.pl-card .pc-cta:not(.disabled):active{transform:scale(.97)}
@media(hover:hover){.pl-card .pc-cta:not(.disabled):hover{opacity:.9}}

/* Modal de pago (Payment Element) */
.sc-modal{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center}
.sc-overlay{position:absolute;inset:0;background:rgba(5,9,20,.7);backdrop-filter:blur(4px)}
.sc-content{position:relative;width:100%;max-width:480px;background:var(--card);border:1px solid var(--stroke);border-radius:20px;box-shadow:0 30px 80px -20px rgba(0,0,0,.5);padding:24px;max-height:92vh;overflow-y:auto}
.sc-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.sc-header h3{font-family:'Sora',sans-serif;font-size:20px;font-weight:800;margin:0}
.sc-close{width:36px;height:36px;border-radius:10px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);cursor:pointer;display:grid;place-items:center;transition:all .15s}
.sc-close:hover{background:rgba(239,68,68,.1);color:var(--red);border-color:var(--red)}
.sc-close svg{width:18px;height:18px}

.sc-summary{background:var(--panel-2);border:1px solid var(--stroke);border-radius:12px;padding:14px 16px;margin-bottom:18px}
.sc-summary-row{display:flex;align-items:center;justify-content:space-between;font-size:15px}
.sc-summary-row strong{font-family:'Sora',sans-serif;font-size:18px;font-weight:800}
.sc-secure{display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--txt-soft);margin-top:10px}
.sc-secure svg{width:14px;height:14px;color:var(--green);flex:none}

.sc-element{margin-bottom:16px;min-height:80px}
.sc-element .p-LinkMoreInfoText,.sc-element .p-LinkTerms,.sc-element [class*="LinkTerms"]{display:none !important}
.sc-error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:var(--red);font-size:13px;padding:10px 14px;border-radius:10px;margin-bottom:14px}
.sc-pay-btn{width:100%;padding:14px;border-radius:12px;border:0;background:linear-gradient(135deg,#0B1A4A 0%,#12266B 55%,#1E5AE8 130%);color:#fff;font-family:'Sora',sans-serif;font-size:15px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;transition:opacity .15s,transform .1s;box-shadow:0 10px 24px -10px rgba(11,26,74,.55)}
.sc-pay-btn:hover{opacity:.9}
.sc-pay-btn:active{transform:scale(.97)}
.sc-pay-btn:disabled{opacity:.5;cursor:not-allowed}
.sc-spinner{width:18px;height:18px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:scSpin .6s linear infinite}
@keyframes scSpin{to{transform:rotate(360deg)}}
@media(max-width:600px){.sc-content{width:95%;padding:18px}}

/* Checkboxes legales */
.sc-legal{display:flex;flex-direction:column;gap:10px;margin-bottom:16px;padding:14px 16px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:12px}
.sc-legal-item{display:flex;align-items:flex-start;gap:10px;font-size:13px;color:var(--txt-soft);cursor:pointer;line-height:1.45}
.sc-legal-item input[type="checkbox"]{width:16px;height:16px;margin-top:2px;accent-color:#2E7BF6;cursor:pointer;flex-shrink:0}
.sc-legal-item a{color:#2E7BF6;font-weight:600;text-decoration:none}
.sc-legal-item a:hover{text-decoration:underline}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const CSRF = "<?php echo e(csrf_token()); ?>";
  const SUBSCRIBE_URL = "<?php echo e(url('/stripe/subscribe')); ?>";
  const RETURN_URL = "<?php echo e(route('stripe.success')); ?>";
  const STRIPE_PUBLISHABLE_KEY = "<?php echo e(config('services.stripe.key')); ?>";

  let stripe;
  let elements;

  if (STRIPE_PUBLISHABLE_KEY) {
    stripe = Stripe(STRIPE_PUBLISHABLE_KEY);
  }

  const modal = document.getElementById('stripeCheckoutModal');
  const paymentForm = document.getElementById('scPaymentForm');
  const submitBtn = document.getElementById('scSubmit');
  const submitText = document.getElementById('scSubmitText');
  const spinner = document.getElementById('scSpinner');
  const errorBox = document.getElementById('scError');
  const planNameEl = document.getElementById('scPlanName');
  const planPriceEl = document.getElementById('scPlanPrice');

  function showError(msg) {
    errorBox.textContent = msg;
    errorBox.style.display = 'block';
  }
  function clearError() {
    errorBox.textContent = '';
    errorBox.style.display = 'none';
  }
  function setLoading(loading) {
    submitBtn.disabled = loading;
    spinner.style.display = loading ? 'inline-block' : 'none';
    submitText.style.display = loading ? 'none' : 'inline';
  }

  // Abre el modal y monta el Payment Element
  async function openPayment(plan, interval, planLabel, priceLabel) {
    if (!stripe) {
      alert('Error: Stripe no está configurado correctamente.');
      return;
    }

    clearError();
    setLoading(true);
    planNameEl.textContent = planLabel;
    planPriceEl.textContent = priceLabel;
    modal.style.display = 'flex';

    // Limpiar Payment Element previo
    document.getElementById('payment-element').innerHTML =
      '<div style="display:flex;align-items:center;justify-content:center;height:100px;color:var(--txt-soft)">Cargando…</div>';

    try {
      const response = await fetch(SUBSCRIBE_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
        },
        body: JSON.stringify({ plan, interval }),
      });

      const data = await response.json();
      if (!response.ok) {
        throw new Error(data.error || 'Error al iniciar la suscripción');
      }

      const appearance = {
        theme: document.documentElement.dataset.theme === 'light' ? 'stripe' : 'night',
        variables: { colorPrimary: '#0EA5E9', borderRadius: '10px' },
      };

      elements = stripe.elements({ clientSecret: data.clientSecret, appearance });
      const paymentElement = elements.create('payment', { layout: 'tabs' });

      document.getElementById('payment-element').innerHTML = '';
      paymentElement.mount('#payment-element');

      setLoading(false);
    } catch (error) {
      console.error('Error:', error);
      document.getElementById('payment-element').innerHTML = '';
      showError(error.message);
      setLoading(false);
    }
  }

  // --- Checkboxes legales: habilitar/deshabilitar botón ---
  const legalCheckboxes = document.querySelectorAll('.sc-legal-cb');
  function updateSubmitState() {
    const allChecked = [...legalCheckboxes].every(cb => cb.checked);
    submitBtn.disabled = !allChecked;
  }
  legalCheckboxes.forEach(cb => cb.addEventListener('change', updateSubmitState));

  // Enviar el pago
  paymentForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!elements) return;

    // Verificar checkboxes
    const allChecked = [...legalCheckboxes].every(cb => cb.checked);
    if (!allChecked) {
      showError('Debes aceptar todos los términos y políticas para continuar.');
      return;
    }

    clearError();
    setLoading(true);

    // Guardar evidencia legal antes de confirmar pago
    try {
      const documentos = [...legalCheckboxes].map(cb => cb.dataset.doc);
      await fetch("<?php echo e(route('legal.acceptances.store')); ?>", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ documentos })
      });
    } catch(err) {
      console.error('Error guardando aceptaciones legales:', err);
    }

    const { error } = await stripe.confirmPayment({
      elements,
      confirmParams: { return_url: RETURN_URL },
    });

    // Si llegamos aquí, hubo un error (si tiene éxito, redirige a return_url)
    if (error) {
      showError(error.message || 'No se pudo procesar el pago.');
      setLoading(false);
    }
  });

  // Cerrar el modal
  window.closeStripeModal = function() {
    modal.style.display = 'none';
    elements = null;
    clearError();
  };

  // Botones de intervalo
  const intervalBtns = document.querySelectorAll('.pc-int-btn');
  intervalBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const card = btn.closest('[data-card]');
      if (!card) return;
      card.querySelectorAll('.pc-int-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const priceEl = card.querySelector('.pc-price');
      const ctaEl = card.querySelector('.pc-cta');
      if (priceEl) {
        priceEl.innerHTML = btn.dataset.price + '<span> ' + btn.dataset.label + '</span>';
      }
      if (ctaEl) {
        ctaEl.dataset.interval = btn.dataset.interval;
      }
    });
  });

  // Botones de plan -> abrir modal de pago
  const planBtns = document.querySelectorAll('[data-plan]');
  planBtns.forEach(el => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      if (el.classList.contains('disabled')) return;
      const plan = el.dataset.plan;
      const interval = el.dataset.interval;
      const card = el.closest('[data-card]');
      const planLabel = card?.querySelector('h4')?.textContent?.trim() || 'Plan';
      const activeInt = card?.querySelector('.pc-int-btn.active');
      const priceLabel = activeInt
        ? (activeInt.dataset.price + ' ' + activeInt.dataset.label)
        : (card?.querySelector('.pc-price')?.textContent || '');
      openPayment(plan, interval, planLabel, priceLabel);
    });
  });

  // Cerrar modal con ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.style.display === 'flex') {
      window.closeStripeModal();
    }
  });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.plan-only', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views/configuracion/plan-only.blade.php ENDPATH**/ ?>