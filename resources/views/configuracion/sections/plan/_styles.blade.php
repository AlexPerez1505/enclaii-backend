@push('styles')
<style>
/* ===== Modal Gestionar plan ===== */
.gp-ov{position:fixed;inset:0;z-index:1000;display:none;align-items:flex-start;justify-content:center;padding:32px 18px;background:rgba(5,9,20,.66);backdrop-filter:blur(3px);overflow-y:auto}
.gp-ov.open{display:flex}
.gp-modal{position:relative;width:100%;max-width:960px;background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-lg);box-shadow:0 30px 80px -20px var(--shadow);padding:26px 28px 0;animation:gpIn .22s var(--ease-out)}
@keyframes gpIn{from{opacity:0;transform:translateY(14px) scale(.98)}to{opacity:1;transform:none}}
.gp-x{position:absolute;top:20px;right:20px;width:34px;height:34px;display:grid;place-items:center;border-radius:9px;color:var(--txt-soft)}
.gp-x svg{width:20px;height:20px}
.gp-x:hover{background:var(--hover-bg);color:var(--txt)}
.gp-head h2{font-family:'Sora',sans-serif;font-size:21px;font-weight:700}
.gp-head p{font-size:13px;color:var(--txt-soft);margin-top:4px}
.gp-tabs{display:flex;gap:26px;border-bottom:1px solid var(--stroke);margin:18px 0 0}
.gp-tab{display:inline-flex;align-items:center;gap:8px;padding:0 2px 12px;font-size:14px;font-weight:600;color:var(--txt-soft);border:0;background:none;cursor:pointer;position:relative}
.gp-tab svg{width:17px;height:17px}
.gp-tab.active{color:var(--cyan)}
.gp-tab.active::after{content:"";position:absolute;left:0;right:0;bottom:-1px;height:2px;border-radius:2px;background:var(--cyan)}
@media(hover:hover){.gp-tab:hover{color:var(--txt)}}
.gp-body{padding:20px 0 4px}
.gp-panel{display:none}
.gp-panel.active{display:block}
/* Botones de intervalo */
.pc-int-btn{padding:6px 12px;border-radius:8px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);font-size:11.5px;font-weight:600;cursor:pointer;transition:all .15s ease}
.pc-int-btn:hover{background:var(--hover-bg);color:var(--txt)}
.pc-int-btn.active{background:var(--cyan);color:#fff;border-color:var(--cyan)}
.gp-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:760px){.gp-grid{grid-template-columns:1fr}}
.gp-card{border:1px solid var(--stroke);border-radius:var(--r-md);background:var(--panel-2);padding:16px 17px}
.gp-card h3{font-family:'Sora',sans-serif;font-size:14.5px;font-weight:700;margin-bottom:13px}
.gp-soft{color:var(--txt-soft);font-weight:500;font-size:12.5px}
.gp-mt{margin-top:11px}
.gp-mt2{margin-top:14px}
/* Resumen del plan */
.gp-plan{display:grid;grid-template-columns:auto 1fr;gap:13px;align-items:start}
.gp-crown{width:46px;height:46px;flex:none;border-radius:12px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,#7c5cff,#a47bff)}
.gp-crown svg{width:24px;height:24px}
.gp-plan-name b{font-family:'Sora',sans-serif;font-size:15px}
.gp-badge{display:inline-block;margin-left:8px;font-size:10px;font-weight:700;color:var(--green);background:rgba(61,220,151,.14);padding:2px 8px;border-radius:6px;vertical-align:middle}
.gp-plan-info p{font-size:12px;color:var(--txt-soft);margin-top:3px}
.gp-feat{grid-column:1/-1;list-style:none;margin-top:6px;display:flex;flex-direction:column;gap:8px}
.gp-feat li{display:flex;align-items:center;gap:9px;font-size:12.5px}
.gp-feat li svg{width:16px;height:16px;color:var(--green);flex:none}
.gp-feat li.nochk{padding-left:25px;color:var(--txt-soft)}
/* Consumo AI */
.gp-ai-top{display:flex;align-items:baseline;flex-wrap:wrap;gap:7px}
.gp-ai-num{font-family:'Sora',sans-serif;font-size:26px;font-weight:800;line-height:1}
.gp-ai-pct{margin-left:auto;font-family:'Sora',sans-serif;font-size:17px;font-weight:700;color:var(--cyan)}
.gp-bar{height:8px;border-radius:99px;background:var(--stroke);overflow:hidden;margin-top:12px}
.gp-bar i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--blue),var(--cyan))}
.gp-btn-ghost{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:10px;border-radius:10px;border:1px solid var(--stroke-strong);color:var(--cyan);font-weight:600;font-size:13px}
.gp-btn-ghost svg{width:16px;height:16px}
.gp-btn-ghost.sm{width:auto;padding:7px 12px;border:0;background:rgba(46,123,246,.12)}
.gp-btn-ghost:hover{background:var(--hover-bg)}
/* Integrantes */
.gp-card-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:4px}
.gp-card-row h3{margin-bottom:0}
.gp-mini{display:flex;justify-content:space-between;font-size:11.5px;color:var(--txt-soft);margin-top:7px;margin-bottom:6px}
.gp-table{width:100%;border-collapse:collapse;font-size:12px}
.gp-table th{text-align:left;font-weight:600;color:var(--txt-soft);font-size:11px;padding:7px 8px;border-bottom:1px solid var(--stroke)}
.gp-table td{padding:9px 8px;border-bottom:1px solid rgba(110,160,255,.08)}
.gp-table tr:last-child td{border-bottom:0}
.gp-u{font-weight:600}
.gp-you{font-size:9.5px;font-weight:700;color:#fff;background:var(--blue);padding:1px 6px;border-radius:5px;margin-left:5px}
.gp-st{font-size:10px;font-weight:700;color:var(--green);background:rgba(61,220,151,.14);padding:2px 8px;border-radius:6px}
.gp-st.pending{color:var(--orange);background:rgba(245,158,45,.14)}
.gp-dots{color:var(--txt-soft);font-size:16px;padding:0 6px;line-height:1}
.gp-dots:hover{color:var(--txt)}
.gp-member-email{display:block;color:var(--txt-soft);font-size:10px;margin-top:3px}
.gp-member-remove,.gp-invite-revoke{color:var(--red);font-size:11px;font-weight:700;padding:5px 8px;border-radius:7px}
.gp-member-remove:hover,.gp-invite-revoke:hover{background:rgba(255,90,110,.1)}
.gp-no-action{color:var(--txt-soft)}
.gp-note{display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--txt-soft);margin-top:12px}
.gp-note svg{width:14px;height:14px;flex:none}
/* Comprar almacenamiento */
.gp-store{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.gp-store-card{border:1px solid var(--stroke);border-radius:var(--r-md);background:var(--card);padding:15px;text-align:center}
.gp-store-gb{font-family:'Sora',sans-serif;font-size:17px;font-weight:700}
.gp-store-price{font-size:12px;color:var(--txt-soft);margin:5px 0 12px}
.gp-btn-out{display:block;padding:9px;border-radius:9px;border:1px solid var(--stroke-strong);color:var(--cyan);font-weight:600;font-size:13px}
.gp-btn-out:hover{background:var(--hover-bg)}
/* Cancelar */
.gp-cancel{display:flex;align-items:center;gap:13px;margin-top:16px;padding:16px 18px;border:1px solid rgba(255,90,110,.4);background:rgba(255,90,110,.07);border-radius:var(--r-md)}
.gp-cancel-ico{width:40px;height:40px;flex:none;border-radius:10px;display:grid;place-items:center;color:var(--red);background:rgba(255,90,110,.14)}
.gp-cancel-ico svg{width:20px;height:20px}
.gp-cancel-txt{flex:1}
.gp-cancel-txt b{font-family:'Sora',sans-serif;font-size:14px}
.gp-cancel-txt p{font-size:11.5px;color:var(--txt-soft);margin-top:3px}
.gp-cancel-btn{flex:none;padding:9px 16px;border-radius:9px;border:1px solid var(--red);color:var(--red);font-weight:600;font-size:13px}
.gp-cancel-btn:hover{background:rgba(255,90,110,.12)}
/* Facturacion */
.gp-summary-row{display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid rgba(110,160,255,.08);font-size:13px}
.gp-summary-row:last-of-type{border-bottom:0}
.gp-link{color:var(--cyan);font-weight:600}
/* Footer */
.gp-foot{display:flex;justify-content:flex-end;padding:16px 0 22px;margin-top:6px;border-top:1px solid var(--stroke);position:sticky;bottom:0;background:var(--card)}
.gp-cerrar{padding:10px 22px;border-radius:10px;background:var(--panel-2);border:1px solid var(--stroke-strong);color:var(--txt);font-weight:600;font-size:13.5px}
.gp-cerrar:hover{background:var(--hover-bg)}

/* Invitación de integrantes */
.gp-invite-ov{z-index:1200;align-items:center}
.gp-invite-modal{max-width:520px;padding-bottom:26px}
.gp-invite-form{display:grid;gap:16px;margin-top:22px}
.gp-invite-form label,.gp-invite-result label{display:grid;gap:7px;color:var(--txt-soft);font-size:12px;font-weight:600}
.gp-invite-form input,.gp-invite-form select,.gp-invite-result input{width:100%;border:1px solid var(--stroke-strong);border-radius:10px;background:var(--panel-2);color:var(--txt);font:inherit;padding:11px 12px;outline:none}
.gp-invite-form input:focus,.gp-invite-form select:focus{border-color:var(--cyan)}
.gp-invite-submit{width:100%;padding:11px 16px;border-radius:10px;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;font-weight:700}
.gp-invite-submit:disabled{opacity:.6;cursor:not-allowed}
.gp-invite-error{padding:10px 12px;border-radius:9px;background:rgba(255,90,110,.1);color:var(--red);font-size:12px}
.gp-invite-result{display:grid;gap:15px;margin-top:22px}
.gp-invite-result strong{font-family:'Sora',sans-serif;font-size:16px}
.gp-invite-result p{font-size:12.5px;line-height:1.5;color:var(--txt-soft)}
.gp-invite-limit{display:grid;gap:13px;margin-top:22px;text-align:center}
.gp-invite-limit[hidden]{display:none}
.gp-limit-icon{width:44px;height:44px;margin:0 auto;border-radius:50%;display:grid;place-items:center;background:rgba(245,158,45,.14);border:1px solid rgba(245,158,45,.35);color:var(--orange);font-size:22px;font-weight:800}
.gp-invite-limit strong{font-family:'Sora',sans-serif;font-size:17px}
.gp-invite-limit p{font-size:12.5px;line-height:1.6;color:var(--txt-soft)}
.gp-limit-cancel{padding:9px;border:1px solid var(--stroke-strong);border-radius:10px;color:var(--txt-soft);font-size:12px;font-weight:700}

/* ===== RESPONSIVE: MODAL GESTIONAR PLAN ===== */
@media (max-width:768px){
  .gp-ov{padding:20px 12px}
  .gp-modal{padding:20px 18px 0;max-width:100%}
  .gp-x{top:16px;right:16px;width:30px;height:30px}
  .gp-x svg{width:18px;height:18px}
  .gp-head h2{font-size:18px}
  .gp-head p{font-size:12px}
  .gp-tabs{gap:18px;margin:14px 0 0;overflow-x:auto;-webkit-overflow-scrolling:touch}
  .gp-tab{font-size:13px;padding-bottom:10px;white-space:nowrap}
  .gp-tab svg{width:15px;height:15px}
  .gp-body{padding:16px 0 4px}
  .gp-grid{grid-template-columns:1fr;gap:14px}
  .gp-card{padding:14px 15px}
  .gp-card h3{font-size:13.5px;margin-bottom:11px}
  .gp-soft{font-size:11.5px}
  .gp-plan{gap:11px}
  .gp-crown{width:40px;height:40px}
  .gp-crown svg{width:20px;height:20px}
  .gp-plan-name b{font-size:14px}
  .gp-badge{font-size:9px;padding:2px 7px}
  .gp-plan-info p{font-size:11px}
  .gp-feat{gap:7px}
  .gp-feat li{font-size:11.5px}
  .gp-feat li svg{width:14px;height:14px}
  .gp-ai-num{font-size:22px}
  .gp-ai-pct{font-size:15px}
  .gp-bar{height:7px}
  .gp-btn-ghost{font-size:12px;padding:9px}
  .gp-btn-ghost svg{width:14px;height:14px}
  .gp-mini{font-size:10.5px}
  .gp-table{font-size:11px}
  .gp-table th{font-size:10px;padding:6px 7px}
  .gp-table td{padding:8px 7px}
  .gp-u{font-size:11px}
  .gp-you{font-size:8.5px;padding:1px 5px}
  .gp-st{font-size:9px;padding:2px 7px}
  .gp-note{font-size:10.5px;margin-top:10px}
  .gp-note svg{width:12px;height:12px}
  .gp-store{grid-template-columns:1fr;gap:10px}
  .gp-store-card{padding:13px}
  .gp-store-gb{font-size:15px}
  .gp-store-price{font-size:11px;margin:4px 0 10px}
  .gp-btn-out{font-size:12px;padding:8px}
  .gp-cancel{gap:11px;padding:14px 15px}
  .gp-cancel-ico{width:36px;height:36px}
  .gp-cancel-ico svg{width:18px;height:18px}
  .gp-cancel-txt b{font-size:13px}
  .gp-cancel-txt p{font-size:10.5px}
  .gp-cancel-btn{font-size:12px;padding:8px 14px}
  .gp-summary-row{font-size:12px;padding:10px 0}
  .gp-foot{padding:14px 0 18px}
  .gp-cerrar{font-size:12.5px;padding:9px 18px}
}

@media (max-width:480px){
  .gp-ov{padding:12px 8px}
  .gp-modal{padding:16px 14px 0}
  .gp-head h2{font-size:16px}
  .gp-head p{font-size:11px}
  .gp-tabs{gap:16px}
  .gp-tab{font-size:12px;padding-bottom:9px}
  .gp-card{padding:12px}
  .gp-card h3{font-size:12.5px}
  .gp-table{font-size:10px}
  .gp-table th{font-size:9px;padding:5px 6px}
  .gp-table td{padding:7px 6px}
  .gp-cancel{flex-direction:column;align-items:flex-start;padding:12px}
  .gp-cancel-btn{width:100%;text-align:center}
  .gp-foot{padding:12px 0 16px}
}
/* Historial de pagos */
.inv-list{margin-top:6px}
.inv-loading,.inv-empty{font-size:13px;color:var(--txt-soft);padding:14px 0;text-align:center}
.inv-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 0;border-bottom:1px solid var(--stroke)}
.inv-row:last-child{border-bottom:0}
.inv-info{display:flex;flex-direction:column;gap:3px}
.inv-date{font-size:13.5px;font-weight:600;color:var(--txt)}
.inv-num{font-size:11.5px;color:var(--txt-soft)}
.inv-right{display:flex;align-items:center;gap:12px}
.inv-amount{font-size:14px;font-weight:700;font-family:'Sora',sans-serif}
.inv-status{font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:6px;text-transform:capitalize}
.inv-status.paid{color:var(--green);background:rgba(61,220,151,.14)}
.inv-status.open,.inv-status.draft{color:var(--orange);background:rgba(255,160,0,.14)}
.inv-status.void,.inv-status.uncollectible{color:var(--red);background:rgba(239,68,68,.12)}
.inv-pdf{width:32px;height:32px;flex:none;border-radius:8px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);display:grid;place-items:center;transition:all .15s}
.inv-pdf:hover{background:var(--hover-bg);color:var(--cyan);border-color:var(--cyan)}
.inv-pdf svg{width:15px;height:15px}
</style>
@endpush

