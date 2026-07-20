
<div id="customToast" class="custom-toast" style="display:none">
  <div class="toast-content">
    <div class="toast-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <div class="toast-message">
      <h4 id="toastTitle">¡Cambio exitoso!</h4>
      <p id="toastText">Tu plan ha sido actualizado.</p>
    </div>
    <button type="button" class="toast-close" onclick="hideToast()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.custom-toast{position:fixed;bottom:24px;right:24px;z-index:10000;animation:toastIn .3s var(--ease-out)}
@keyframes toastIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
.toast-content{display:flex;align-items:flex-start;gap:14px;background:var(--card);border:1px solid var(--stroke);border-radius:14px;padding:16px 18px;box-shadow:0 20px 50px -15px rgba(0,0,0,.4);min-width:320px}
.toast-icon{width:36px;height:36px;flex:none;border-radius:10px;background:rgba(61,220,151,.12);color:var(--green);display:grid;place-items:center}
.toast-icon svg{width:20px;height:20px}
.toast-message{flex:1}
.toast-message h4{font-family:'Sora',sans-serif;font-size:14px;font-weight:700;margin:0 0 3px}
.toast-message p{font-size:13px;color:var(--txt-soft);margin:0;line-height:1.4}
.toast-close{width:28px;height:28px;flex:none;border-radius:8px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);cursor:pointer;display:grid;place-items:center;transition:all .15s}
.toast-close:hover{background:rgba(239,68,68,.1);color:var(--red);border-color:var(--red)}
.toast-close svg{width:14px;height:14px}
@media(max-width:600px){.custom-toast{bottom:16px;right:16px;left:16px}.toast-content{min-width:auto}}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function showToast(title, text) {
  const toast = document.getElementById('customToast');
  document.getElementById('toastTitle').textContent = title;
  document.getElementById('toastText').textContent = text;
  toast.style.display = 'block';
  setTimeout(hideToast, 5000);
}
function hideToast() {
  document.getElementById('customToast').style.display = 'none';
}
</script>
<?php $__env->stopPush(); ?>

<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/configuracion/sections/plan/_toast.blade.php ENDPATH**/ ?>