<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const modal = document.getElementById('gpModal');
  const openBtn = document.getElementById('gpOpen');
  if (!modal || !openBtn) return;

  const open = (e) => { if (e) e.preventDefault(); modal.classList.add('open'); modal.setAttribute('aria-hidden','false'); document.body.style.overflow='hidden'; };
  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden','true'); document.body.style.overflow=''; };

  openBtn.addEventListener('click', open);
  document.getElementById('gpClose')?.addEventListener('click', close);
  document.getElementById('gpClose2')?.addEventListener('click', close);
  modal.addEventListener('click', (e) => { if (e.target === modal) close(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  // Pestañas internas del modal
  const tabs = modal.querySelectorAll('.gp-tab');
  const panels = modal.querySelectorAll('.gp-panel');
  tabs.forEach(t => t.addEventListener('click', () => {
    tabs.forEach(x => x.classList.remove('active'));
    panels.forEach(p => p.classList.remove('active'));
    t.classList.add('active');
    modal.querySelector(`.gp-panel[data-gppanel="${t.dataset.gptab}"]`)?.classList.add('active');
    if (t.dataset.gptab === 'facturacion') loadInvoices();
  }));

  // ===== Integrantes reales de la clínica =====
  const inviteModal = document.getElementById('gpInviteModal');
  const inviteForm = document.getElementById('gpInviteForm');
  const inviteResult = document.getElementById('gpInviteResult');
  const inviteError = document.getElementById('gpInviteError');
  const inviteLimit = document.getElementById('gpInviteLimit');
  const inviteLimitText = document.getElementById('gpInviteLimitText');
  const inviteUpgrade = document.getElementById('gpInviteUpgrade');
  const serverLimitOffer = <?php echo json_encode($clinicMemberUpgradeOffer, 15, 512) ?>;
  const memberLimit = <?php echo e($clinicMemberLimit); ?>;
  const memberUsage = <?php echo e($clinicMemberUsed); ?>;
  let inviteCreated = false;
  let activeLimitOffer = null;

  function showInviteLimit(payload){
    const offer = payload.upgrade_offer || serverLimitOffer;
    const limit = payload.member_limit || memberLimit;
    activeLimitOffer = offer;
    inviteForm?.setAttribute('hidden', '');
    inviteResult?.setAttribute('hidden', '');
    inviteError?.setAttribute('hidden', '');
    inviteLimit?.removeAttribute('hidden');

    if (offer.type === 'member_addon') {
      inviteLimitText.textContent = `Ya utilizaste ${limit} de ${limit} lugares. Agrega una cuenta por $${Number(offer.price_mxn).toLocaleString('es-MX')} MXN al mes.`;
      inviteUpgrade.textContent = `Comprar 1 cuenta adicional · $${Number(offer.price_mxn).toLocaleString('es-MX')}/mes`;
    } else {
      inviteLimitText.textContent = `Ya utilizaste ${limit} de ${limit} lugares. Cambia al Plan ${offer.target_label} para disponer de hasta ${offer.new_limit} cuentas.`;
      inviteUpgrade.textContent = `Ver Plan ${offer.target_label}`;
    }
  }

  function closeInvite(){
    inviteModal?.classList.remove('open');
    inviteModal?.setAttribute('aria-hidden', 'true');
    if (inviteCreated) window.location.reload();
  }

  document.querySelectorAll('.gp-invite-open').forEach(button => {
    button.addEventListener('click', () => {
      if (!inviteModal) return;
      inviteCreated = false;
      inviteForm?.reset();
      inviteLimit?.setAttribute('hidden', '');
      inviteResult?.setAttribute('hidden', '');
      inviteError?.setAttribute('hidden', '');
      inviteModal.classList.add('open');
      inviteModal.setAttribute('aria-hidden', 'false');
      if (memberUsage >= memberLimit) {
        showInviteLimit({
          member_limit: memberLimit,
          member_usage: memberUsage,
          upgrade_offer: serverLimitOffer,
        });
      } else {
        inviteForm?.removeAttribute('hidden');
      }
    });
  });
  document.getElementById('gpInviteClose')?.addEventListener('click', closeInvite);
  document.getElementById('gpInviteDone')?.addEventListener('click', closeInvite);
  document.getElementById('gpInviteLimitCancel')?.addEventListener('click', closeInvite);
  inviteModal?.addEventListener('click', event => {
    if (event.target === inviteModal) closeInvite();
  });

  inviteForm?.addEventListener('submit', async event => {
    event.preventDefault();
    const submit = inviteForm.querySelector('[type="submit"]');
    submit.disabled = true;
    submit.textContent = 'Enviando...';
    inviteError.setAttribute('hidden', '');

    try {
      const response = await fetch("<?php echo e(route('configuracion.clinic-invitations.store')); ?>", {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
        },
        body: JSON.stringify(Object.fromEntries(new FormData(inviteForm))),
      });
      const data = await response.json();

      if (!response.ok) {
        if (data.code === 'member_limit_reached') {
          showInviteLimit(data);
          return;
        }
        const firstError = Object.values(data.errors || {}).flat()[0];
        throw new Error(firstError || data.message || 'No se pudo crear la invitación.');
      }

      inviteCreated = true;
      inviteForm.setAttribute('hidden', '');
      inviteResult.removeAttribute('hidden');
      document.getElementById('gpInviteResultText').textContent = data.message;
    } catch (error) {
      inviteError.textContent = error.message;
      inviteError.removeAttribute('hidden');
    } finally {
      submit.disabled = false;
      submit.textContent = 'Autorizar correo';
    }
  });

  inviteUpgrade?.addEventListener('click', () => {
    if (!activeLimitOffer) return;

    if (activeLimitOffer.type === 'member_addon') {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = "<?php echo e(route('stripe.member-addon.checkout')); ?>";
      form.innerHTML = `<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">`;
      document.body.appendChild(form);
      form.submit();
      return;
    }

    const targetCard = document.querySelector(`[data-card="${activeLimitOffer.target_plan}"]`);
    closeInvite();
    close();
    targetCard?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    if (targetCard) {
      targetCard.style.boxShadow = '0 0 0 3px rgba(14,165,233,.35)';
      setTimeout(() => { targetCard.style.boxShadow = ''; }, 2500);
    }
  });

  modal.addEventListener('click', async event => {
    const memberButton = event.target.closest('.gp-member-remove');
    const invitationButton = event.target.closest('.gp-invite-revoke');
    if (!memberButton && !invitationButton) return;

    const isMember = Boolean(memberButton);
    const message = isMember
      ? `¿Retirar a ${memberButton.dataset.memberName} de la clínica?`
      : '¿Cancelar esta invitación?';
    if (!window.confirm(message)) return;

    const url = isMember
      ? `<?php echo e(url('/configuracion/clinica/integrantes')); ?>/${memberButton.dataset.memberId}`
      : `<?php echo e(url('/configuracion/clinica/invitaciones')); ?>/${invitationButton.dataset.invitationId}`;
    const response = await fetch(url, {
      method: 'DELETE',
      headers: { 'Accept':'application/json', 'X-CSRF-TOKEN':"<?php echo e(csrf_token()); ?>" },
    });
    const data = await response.json();

    if (!response.ok) {
      window.alert(data.message || 'No se pudo completar la acción.');
      return;
    }
    window.location.reload();
  });

  // ===== Historial de pagos (facturas desde Stripe) =====
  const INVOICES_URL = "<?php echo e(url('/stripe/invoices')); ?>";
  let invoicesLoaded = false;

  function fmtAmount(cents, currency){
    return new Intl.NumberFormat('es-MX', { style:'currency', currency: currency || 'MXN' }).format((cents || 0) / 100);
  }
  function fmtDate(ts){
    return new Date(ts * 1000).toLocaleDateString('es-MX', { day:'2-digit', month:'short', year:'numeric' });
  }
  function statusLabel(s){
    const map = { paid:'Pagada', open:'Pendiente', draft:'Borrador', void:'Anulada', uncollectible:'Incobrable' };
    return map[s] || s;
  }

  function loadInvoices(){
    if (invoicesLoaded) return;
    const box = document.getElementById('invoiceHistory');
    if (!box) return;
    invoicesLoaded = true;

    fetch(INVOICES_URL, { headers: { 'Accept':'application/json' } })
      .then(r => r.json())
      .then(data => {
        const list = data.invoices || [];
        if (!list.length) {
          box.innerHTML = '<div class="inv-empty">Aún no tienes pagos registrados.</div>';
          return;
        }
        box.innerHTML = list.map(inv => `
          <div class="inv-row">
            <div class="inv-info">
              <span class="inv-date">${fmtDate(inv.date)}</span>
              <span class="inv-num">${inv.number || inv.id}</span>
            </div>
            <div class="inv-right">
              <span class="inv-amount">${fmtAmount(inv.amount, inv.currency)}</span>
              <span class="inv-status ${inv.status}">${statusLabel(inv.status)}</span>
              ${inv.pdf ? `<a class="inv-pdf" href="${inv.pdf}" target="_blank" rel="noopener" title="Descargar PDF"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>` : ''}
            </div>
          </div>
        `).join('');
      })
      .catch(() => {
        box.innerHTML = '<div class="inv-empty">No se pudo cargar el historial de pagos.</div>';
        invoicesLoaded = false;
      });
  }
})();
</script>

<script>
/* ===== Integración de pagos con Stripe ===== */
document.addEventListener('DOMContentLoaded', function(){
  const CSRF = "<?php echo e(csrf_token()); ?>";
  const CHECKOUT_URL = "<?php echo e(url('/stripe/checkout')); ?>";
  const CHANGE_PLAN_URL = "<?php echo e(url('/stripe/change-plan')); ?>";

  // Estado del usuario: ¿ya tiene suscripción?
  const hasSubscription = <?php echo e($planUser->stripe_subscription_id ? 'true' : 'false'); ?>;

  console.log('Stripe integration loaded');
  console.log('Has subscription:', hasSubscription);

  // Envía un POST clásico (redirige a Stripe) creando un form temporal.
  function postTo(action, fields){
    console.log('postTo called:', action, fields);
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = action;
    form.style.display = 'none';

    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = CSRF;
    form.appendChild(token);

    Object.entries(fields || {}).forEach(([name, value]) => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = name;
      input.value = value;
      form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
  }

  // Selectores de intervalo de facturación
  const intervalBtns = document.querySelectorAll('.pc-int-btn');
  console.log('Found interval buttons:', intervalBtns.length);

  intervalBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const card = btn.closest('[data-card]');
      if (!card) return;

      // Marcar botón activo
      card.querySelectorAll('.pc-int-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      // Actualizar precio y label
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

  // Botones de plan -> Checkout o cambio de plan
  const planBtns = document.querySelectorAll('[data-plan]');
  console.log('Found plan buttons:', planBtns.length);

  planBtns.forEach(el => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      console.log('Plan button clicked:', el.dataset.plan);

      if (el.classList.contains('disabled')) {
        console.log('Button is disabled, ignoring');
        return;
      }

      const plan = el.dataset.plan;
      const interval = el.dataset.interval;

      if (hasSubscription) {
        // Usuario con suscripción: cambiar plan (AJAX, prorrateado)
        console.log('Changing plan:', { plan, interval });
        el.disabled = true;
        el.textContent = 'Cambiando...';

        fetch(CHANGE_PLAN_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
          },
          body: JSON.stringify({ plan, interval }),
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              showToast('¡Plan cambiado!', `Ahora estás en el plan ${data.planName}. ${data.message}`);
              setTimeout(() => location.reload(), 2000);
            } else {
              showToast('Error', data.error || 'No se pudo cambiar el plan');
              el.disabled = false;
              el.textContent = el.dataset.originalText || 'Cambiar plan';
            }
          })
          .catch(err => {
            console.error('Error:', err);
            showToast('Error de conexión', 'Intenta de nuevo.');
            el.disabled = false;
            el.textContent = el.dataset.originalText || 'Cambiar plan';
          });
      } else {
        // Usuario sin suscripción: checkout normal (redirección a Stripe)
        console.log('Sending to checkout:', { plan, interval });
        postTo(CHECKOUT_URL, { plan, interval });
      }
    });
  });

  // ===== Cancelar plan (in-app, sin redirección) =====
  const CANCEL_URL = "<?php echo e(url('/stripe/cancel-subscription')); ?>";
  const RESUME_URL = "<?php echo e(url('/stripe/resume-subscription')); ?>";

  function jsonPost(url){
    return fetch(url, {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
    }).then(r => r.json());
  }

  const cancelBtn = document.getElementById('cancelBtn');
  if (cancelBtn) {
    cancelBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (!confirm('¿Seguro que deseas cancelar tu plan? Conservarás el acceso hasta el final del ciclo de facturación.')) return;
      cancelBtn.classList.add('disabled');
      cancelBtn.textContent = 'Cancelando...';
      jsonPost(CANCEL_URL)
        .then(data => {
          if (data.success) {
            showToast('Plan cancelado', data.message);
            setTimeout(() => location.reload(), 2000);
          } else {
            showToast('Error', data.error || 'No se pudo cancelar el plan');
            cancelBtn.classList.remove('disabled');
            cancelBtn.textContent = 'Cancelar plan';
          }
        })
        .catch(() => {
          showToast('Error de conexión', 'Intenta de nuevo.');
          cancelBtn.classList.remove('disabled');
          cancelBtn.textContent = 'Cancelar plan';
        });
    });
  }

  const resumeBtn = document.getElementById('resumeBtn');
  if (resumeBtn) {
    resumeBtn.addEventListener('click', (e) => {
      e.preventDefault();
      resumeBtn.classList.add('disabled');
      resumeBtn.textContent = 'Reactivando...';
      jsonPost(RESUME_URL)
        .then(data => {
          if (data.success) {
            showToast('Plan reactivado', data.message);
            setTimeout(() => location.reload(), 2000);
          } else {
            showToast('Error', data.error || 'No se pudo reactivar el plan');
            resumeBtn.classList.remove('disabled');
            resumeBtn.textContent = 'Reactivar plan';
          }
        })
        .catch(() => {
          showToast('Error de conexión', 'Intenta de nuevo.');
          resumeBtn.classList.remove('disabled');
          resumeBtn.textContent = 'Reactivar plan';
        });
    });
  }

  // Ver historial -> abre modal Gestionar plan en pestaña Facturación
  document.getElementById('gpOpenBilling')?.addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('gpOpen')?.click();
    const billingTab = document.querySelector('.gp-tab[data-gptab="facturacion"]');
    if (billingTab) billingTab.click();
  });
});
</script>
<?php $__env->stopPush(); ?>

<?php /**PATH C:\laragon\www\endocare\resources\views/configuracion/sections/plan/_scripts.blade.php ENDPATH**/ ?>