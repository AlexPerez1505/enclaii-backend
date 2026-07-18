<style>
/* ============================================================
   AGENDAR CITA — Estilos globales
   ============================================================ */
:root{
  --ag-bg:#000B1E;--ag-card:#001525;--ag-blue:#168BD9;--ag-stroke:rgba(22,139,217,.35);
  --ag-txt:#EAF1FF;--ag-soft:rgba(234,241,255,.55);--ag-r:12px;
}
.ag-wrap{display:flex;flex-direction:column;gap:20px}

/* Back button */
.ag-back{display:inline-flex;align-items:center;gap:8px;background:none;border:none;color:var(--ag-soft);font-size:13px;cursor:pointer;padding:0;transition:color 150ms ease}
.ag-back:hover{color:var(--ag-txt)}
.ag-back svg{flex:none}
.ag-header{display:flex;align-items:center;gap:16px;margin-bottom:4px}
.ag-title{font-family:'Sora',sans-serif;font-size:22px;font-weight:800;color:var(--ag-txt);flex:1;text-align:center}

/* Step badge */
.ag-step-badge{width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#1668D9,#004F8B);display:grid;place-items:center;font-family:'Sora',sans-serif;font-size:12px;font-weight:800;color:#fff;flex:none}

/* Card */
.ag-card{background:var(--ag-card);border:1.5px solid var(--ag-stroke);border-radius:var(--ag-r);padding:20px 22px}
.ag-card-title{display:flex;align-items:center;gap:10px;font-family:'Sora',sans-serif;font-size:14px;font-weight:700;color:var(--ag-txt);margin-bottom:16px}

/* Grid layout - más compacto y sin espacios vacíos */
.ag-grid-main{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;align-items:stretch}
.ag-grid-main > div{display:flex;flex-direction:column;min-width:0}
.ag-grid-main .ag-card{flex:1;display:flex;flex-direction:column;min-width:0;overflow:hidden}
.ag-grid-bottom{display:grid;grid-template-columns:1fr 320px;gap:14px;align-items:stretch}

/* Input */
.ag-label{font-size:11.5px;color:var(--ag-soft);margin-bottom:5px;display:block}
.ag-input{width:100%;padding:10px 14px;background:#001A30;border:1.5px solid var(--ag-stroke);border-radius:9px;color:var(--ag-txt);font-size:13px;font-family:inherit;box-sizing:border-box;transition:border-color 150ms ease;outline:none}
.ag-input:focus{border-color:var(--ag-blue)}
.ag-input::placeholder{color:rgba(234,241,255,.3)}
.ag-select{appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238FA3CF' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:34px;cursor:pointer}
.ag-input-icon{position:relative}
.ag-input-icon .ag-input{padding-left:38px}
.ag-input-icon svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--ag-soft);pointer-events:none;flex:none}
.ag-field{margin-bottom:12px}
.ag-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.ag-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px}

/* Buttons */
.ag-btn-primary{display:inline-flex;align-items:center;gap:8px;padding:11px 24px;border-radius:9px;background:linear-gradient(135deg,#1668D9,#004F8B);color:#fff;font-size:14px;font-weight:700;border:none;cursor:pointer;transition:opacity 150ms ease;font-family:inherit}
.ag-btn-primary:hover{opacity:.88}
.ag-btn-secondary{display:inline-flex;align-items:center;gap:8px;padding:11px 24px;border-radius:9px;background:transparent;color:var(--ag-txt);font-size:14px;font-weight:600;border:1.5px solid var(--ag-stroke);cursor:pointer;transition:border-color 150ms ease;font-family:inherit}
.ag-btn-secondary:hover{border-color:var(--ag-blue)}

/* Tema claro */
html[data-theme="light"] .ag-card{background:#FFFFFF;border-color:rgba(20,50,120,.2)}
html[data-theme="light"] .ag-card-title{color:#0E1530}
html[data-theme="light"] .ag-title{color:#0E1530}
html[data-theme="light"] .ag-input{background:#F0F5FF;border-color:rgba(20,50,120,.2);color:#0E1530}
html[data-theme="light"] .ag-label{color:#5B6A99}
html[data-theme="light"] .ag-back{color:#5B6A99}

/* ---- Responsive ---- */
@media(max-width:1200px){
  .ag-grid-main{grid-template-columns:repeat(2,1fr)}
  .ag-grid-main > div:last-child{grid-column:1/-1}
  .ag-grid-bottom{grid-template-columns:1fr}
}
@media(max-width:900px){
  .ag-grid-main{grid-template-columns:1fr}
  .ag-grid-main > div:last-child{grid-column:auto}
  .ag-grid-bottom{grid-template-columns:1fr}
  .ag-header{flex-wrap:wrap;gap:12px}
  .ag-title{font-size:18px;order:-1;width:100%;text-align:left}
  .ag-back{order:0}
}
@media(max-width:600px){
  .ag-wrap{gap:12px}
  .ag-card{padding:14px 16px}
  .ag-card-title{font-size:13px;margin-bottom:12px}
  .ag-title{font-size:16px}
  .ag-row{grid-template-columns:1fr}
  .ag-row-3{grid-template-columns:1fr}
  .ag-field{margin-bottom:10px}
  .ag-input{padding:9px 12px;font-size:12px}
  .ag-btn-primary,.ag-btn-secondary{padding:10px 18px;font-size:13px}
}
</style>
<?php /**PATH C:\Users\gmedi\enclaii-backend\resources\views/agenda/agendar/_base.blade.php ENDPATH**/ ?>