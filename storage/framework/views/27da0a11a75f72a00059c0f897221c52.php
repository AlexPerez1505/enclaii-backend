
<div class="int-bottom">
  

  

  <article class="card rise d5 int-sign-wide">
    <div class="int-dev-head">
      <span class="int-dev-ico" style="color:var(--orange);background:rgba(245,158,45,.12);border-color:rgba(245,158,45,.25)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17c3-1 5-5 5-9 0 4 2 8 5 9-3 0-7 0-10 0z"/><path d="M14 11l6-6a1.5 1.5 0 0 0-2-2l-6 6"/></svg></span>
      <div>
        <div class="int-dev-t">Firma digital</div>
        <div class="int-dev-meta">Firma: <?php echo e(auth()->user()->name); ?></div>
        <div class="int-sign-status <?php echo e(auth()->user()->signature_path ? 'ready' : ''); ?>">
          <?php echo e(auth()->user()->signature_path ? 'Firma configurada' : 'Sin firma registrada'); ?>

        </div>
      </div>
    </div>
    <div class="int-dev-meta">
      Actualizada:
      <?php echo e(format_user_date_time(auth()->user()->signature_updated_at) ?: 'Nunca'); ?>

    </div>
    <div class="int-dev-btns">
      <button type="button" class="int-dev-btn" id="intSignatureView" <?php if(! auth()->user()->signature_path): echo 'disabled'; endif; ?>>Ver firma</button>
      <button type="button" class="int-dev-btn" id="intSignatureEdit">
        <?php echo e(auth()->user()->signature_path ? 'Actualizar firma' : 'Crear firma'); ?>

      </button>
    </div>
  </article>
</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/configuracion/sections/integraciones/_servicios.blade.php ENDPATH**/ ?>