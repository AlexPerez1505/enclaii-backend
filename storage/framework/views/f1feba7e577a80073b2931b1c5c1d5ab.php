
<style>
.confirm-row{display:flex;flex-wrap:wrap;gap:12px 18px;align-items:center}
.confirm-item{display:flex;align-items:center;gap:8px;min-width:0}
.confirm-item svg{color:var(--ag-soft);flex:none}
.confirm-lbl{font-size:10.5px;color:var(--ag-soft);margin-bottom:1px}
.confirm-val{font-size:13px;font-weight:600;color:var(--ag-txt)}
.confirm-actions{display:flex;align-items:center;justify-content:flex-end;gap:10px;margin-top:16px}

/* Info card */
.info-card{background:rgba(22,139,217,.07);border:1.5px solid rgba(22,139,217,.3);border-radius:var(--ag-r);padding:18px 20px}
.info-card-title{display:flex;align-items:center;gap:8px;font-family:'Sora',sans-serif;font-size:13px;font-weight:700;color:var(--ag-blue);margin-bottom:12px}
.info-item{display:flex;align-items:flex-start;gap:8px;font-size:12.5px;color:var(--ag-soft);margin-bottom:8px;line-height:1.5}
.info-item svg{color:#4C9242;flex:none;margin-top:1px}
.info-item:last-child{margin-bottom:0}

.ag-grid-confirm{display:grid;grid-template-columns:1fr 300px;gap:14px;align-items:stretch}

/* Overlay éxito */
.success-overlay{position:fixed;inset:0;background:rgba(0,0,0,.7);backdrop-filter:blur(4px);z-index:2000;display:none;align-items:center;justify-content:center;padding:20px}
.success-overlay.open{display:flex}
.success-card{background:var(--ag-card);border:1.5px solid var(--ag-stroke);border-radius:var(--ag-r);padding:32px 28px;max-width:420px;width:100%;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.5)}
.success-card svg{width:56px;height:56px;color:#4C9242;margin-bottom:14px}
.success-card h3{font-family:'Sora',sans-serif;font-size:20px;font-weight:700;color:var(--ag-txt);margin-bottom:8px}
.success-card p{font-size:13px;color:var(--ag-soft);margin-bottom:22px;line-height:1.5}
.success-card .success-actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.success-card .success-actions a,.success-card .success-actions button{display:inline-flex;align-items:center;gap:6px;padding:10px 16px;border-radius:var(--ag-r);font-size:13px;font-weight:600;cursor:pointer;border:none;text-decoration:none}
.success-card .btn-primary{background:linear-gradient(135deg,var(--ag-blue),#00B4D8);color:#fff}
.success-card .btn-secondary{background:rgba(255,255,255,.08);color:var(--ag-txt);border:1px solid rgba(255,255,255,.15)}
html[data-theme="light"] .success-card{background:#fff;border-color:rgba(20,50,120,.18)}
html[data-theme="light"] .success-card h3{color:#0E1530}
html[data-theme="light"] .success-card p{color:#5B6A99}
html[data-theme="light"] .success-card .btn-secondary{background:rgba(20,50,120,.06);border-color:rgba(20,50,120,.15);color:#0E1530}
.ag-grid-confirm > div{display:flex;flex-direction:column}

/* Tema claro */
html[data-theme="light"] .confirm-val{color:#0E1530}
html[data-theme="light"] .confirm-lbl{color:#5B6A99}
html[data-theme="light"] .confirm-item svg{color:#5B6A99}
html[data-theme="light"] .info-card{background:rgba(20,50,120,.05);border-color:rgba(20,50,120,.18)}
html[data-theme="light"] .info-card-title{color:#1668D9}
html[data-theme="light"] .info-item{color:#5B6A99}

@media(max-width:720px){
  .ag-grid-confirm{grid-template-columns:1fr}
  .confirm-actions{flex-direction:column-reverse;align-items:stretch}
  .confirm-actions button{width:100%;justify-content:center}
}
@media(max-width:540px){
  .confirm-row{gap:12px}
  .confirm-item{flex-direction:column;align-items:flex-start;gap:2px}
}
</style>

<div class="ag-grid-confirm">
  <div class="ag-card" id="stepConfirmacion">
    <div class="ag-card-title">
      <span class="ag-step-badge">5</span>
      Confirmación de Cita
    </div>

    <div class="confirm-row" id="confirmRow">
      <div class="confirm-item">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <div>
          <div class="confirm-lbl">Paciente</div>
          <div class="confirm-val" id="cfmPaciente">Paciente</div>
        </div>
      </div>
      <div class="confirm-item">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <div>
          <div class="confirm-lbl">Especialista</div>
          <div class="confirm-val" id="cfmEspecialista">Especialista</div>
        </div>
      </div>
      <div class="confirm-item">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        <div>
          <div class="confirm-lbl">Procedimiento</div>
          <div class="confirm-val" id="cfmProcedimiento">Procedimiento</div>
        </div>
      </div>
      <div class="confirm-item">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <div>
          <div class="confirm-lbl">Fecha</div>
          <div class="confirm-val" id="cfmFecha">Fecha</div>
        </div>
      </div>
      <div class="confirm-item">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <div>
          <div class="confirm-lbl">Hora</div>
          <div class="confirm-val" id="cfmHora">Hora</div>
        </div>
      </div>
      <div class="confirm-item">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        <div>
          <div class="confirm-lbl">Sala</div>
          <div class="confirm-val" id="cfmSala">Sala</div>
        </div>
      </div>
    </div>

    <div class="confirm-actions">
      <button class="ag-btn-secondary" id="cfmCancelar">Cancelar</button>
      <button class="ag-btn-primary" id="cfmAgendar">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Agendar cita
      </button>
    </div>
  </div>

  <div class="info-card">
    <div class="info-card-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      Información importante
    </div>
    <div class="info-item">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      Informa al paciente que debera llegar 15 minutos antes a la cita 
    </div>
    
  </div> 
</div>


<div class="success-overlay" id="successOverlay">
  <div class="success-card">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12l3 3 5-6"/></svg>
    <h3>¡Cita agendada!</h3>
    
    <div class="success-actions">
      <a class="btn-primary" href="<?php echo e(route('agenda')); ?>">Salir</a>
      
    </div>
  </div>
</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/agenda/agendar/_confirmacion.blade.php ENDPATH**/ ?>