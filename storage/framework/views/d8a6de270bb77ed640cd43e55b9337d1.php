
<style>
.motivo-textarea{width:100%;min-height:90px;resize:vertical;padding:11px 14px;background:#001A30;border:1.5px solid var(--ag-stroke);border-radius:9px;color:var(--ag-txt);font-size:13px;font-family:inherit;box-sizing:border-box;transition:border-color 150ms ease;outline:none;line-height:1.6}
.motivo-textarea:focus{border-color:var(--ag-blue)}
.motivo-textarea::placeholder{color:rgba(234,241,255,.3)}
.motivo-counter{font-size:11px;color:var(--ag-soft);text-align:right;margin-top:4px}

html[data-theme="light"] .motivo-textarea{background:#F0F5FF;border-color:rgba(20,50,120,.2);color:#0E1530}
html[data-theme="light"] .motivo-counter{color:#5B6A99}
</style>

<div class="ag-card" id="stepMotivo">
  <div class="ag-card-title">
    <span class="ag-step-badge">4</span>
    Motivo / Observaciones
  </div>

  <textarea class="motivo-textarea" id="motivoText" maxlength="500"
    placeholder="Describe el motivo de la cita u observación"></textarea>
  <div class="motivo-counter"><span id="motivoCount">0</span>/500</div>
</div>

<script>
(function(){
  const ta  = document.getElementById('motivoText');
  const cnt = document.getElementById('motivoCount');
  ta.addEventListener('input', () => { cnt.textContent = ta.value.length; });
})();
</script>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\agenda\agendar\_motivo.blade.php ENDPATH**/ ?>