<?php $__env->startPush('styles'); ?>
<style>
.int-head h2{font-family:'Sora',sans-serif;font-size:18px;font-weight:700}
.int-head p{font-size:13px;color:var(--txt-soft);margin:3px 0 18px}

/* Acciones rápidas superiores */
.int-actions{display:grid;grid-template-columns:minmax(0,2fr) minmax(250px,1fr);gap:16px;margin-bottom:18px}
@media (max-width:900px){.int-actions{grid-template-columns:1fr}}
.int-act{display:flex;flex-direction:column}
.int-act .ia-top{display:flex;gap:13px;margin-bottom:14px}
.int-act .ia-ico{width:42px;height:42px;flex:none;border-radius:11px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.1);border:1px solid rgba(56,199,244,.22)}
.int-act .ia-ico svg{width:21px;height:21px}
.int-act .ia-t{font-size:14px;font-weight:700}
.int-act .ia-d{font-size:11.5px;color:var(--txt-soft);margin-top:3px;line-height:1.45}
.int-act .ia-btn{margin-top:auto;display:block;text-align:center;padding:9px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);font-size:12.5px;font-weight:700;color:var(--cyan);transition:background-color .15s}
@media (hover:hover){.int-act .ia-btn:hover{background:rgba(56,199,244,.1)}}

/* Centro de copias */
.int-backup-center{width:100%;min-width:0;padding:20px;box-sizing:border-box}
.int-actions:has(> .int-backup-wide:only-child){grid-template-columns:1fr}
.int-backup-head{display:flex;align-items:flex-start;gap:13px;flex-wrap:wrap}
.int-backup-head .ia-top{margin:0;flex:1;min-width:min(100%,280px)}
.int-backup-create{
  display:inline-flex;align-items:center;justify-content:center;gap:7px;flex:none;
  min-height:38px;padding:0 16px;border-radius:var(--r-md);
  color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan));
  font-size:12.5px;font-weight:700;transition:transform .15s,opacity .15s;
}
.int-backup-create:active{transform:scale(.97)}
@media (hover:hover){.int-backup-create:hover{opacity:.88}}
.int-backup-summary{
  display:flex;align-items:center;justify-content:space-between;gap:12px;
  margin:17px 0 12px;padding:11px 13px;border:1px solid var(--stroke);
  border-radius:var(--r-md);background:rgba(46,123,246,.055);
}
.int-backup-summary-main{display:flex;align-items:center;gap:9px;min-width:0}
.int-backup-ok{width:27px;height:27px;border-radius:50%;display:grid;place-items:center;flex:none;color:var(--green);background:rgba(61,220,151,.13)}
.int-backup-summary strong{display:block;font-size:12.5px}
.int-backup-summary span{display:block;font-size:10.5px;color:var(--txt-soft);margin-top:2px}
.int-backup-count{font-size:11px!important;font-weight:700;color:var(--cyan)!important;white-space:nowrap}
.int-backup-list-head{display:flex;align-items:center;justify-content:space-between;margin:0 2px 7px}
.int-backup-list-head strong{font-size:11.5px}
.int-backup-list-head span{font-size:10.5px;color:var(--txt-soft)}
.int-backup-list{display:flex;flex-direction:column;gap:6px}
.int-backup-row{
  display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:center;gap:10px;
  padding:9px 11px;border-radius:10px;background:var(--panel-2);border:1px solid rgba(110,160,255,.09);
}
.int-backup-info{display:flex;align-items:center;gap:9px;min-width:0}
.int-backup-file{width:30px;height:30px;border-radius:8px;display:grid;place-items:center;flex:none;color:var(--cyan);background:rgba(56,199,244,.09)}
.int-backup-file.auto{color:var(--orange);background:rgba(245,158,45,.1)}
.int-backup-file svg{width:15px;height:15px}
.int-backup-name{font-size:11.5px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.int-backup-meta{font-size:9.8px;color:var(--txt-soft);margin-top:2px}
.int-backup-actions{display:flex;align-items:center;gap:5px}
.int-backup-action{
  width:28px;height:28px;border-radius:8px;display:grid;place-items:center;
  color:var(--txt-soft);border:1px solid var(--stroke);transition:color .15s,background .15s;
}
.int-backup-action svg{width:13px;height:13px}
.int-backup-action.restore{color:var(--cyan);width:auto;display:inline-flex;align-items:center;gap:5px;padding:0 10px;height:28px}
.int-backup-action.delete{color:var(--red);width:auto;display:inline-flex;align-items:center;gap:5px;padding:0 10px;height:28px}
.int-backup-action-label{font-size:11px;font-weight:600;white-space:nowrap}
@media (hover:hover){.int-backup-action:hover{background:rgba(46,123,246,.1);color:var(--txt)}}
.int-backup-empty{padding:17px;text-align:center;border:1px dashed var(--stroke);border-radius:10px;color:var(--txt-soft);font-size:11.5px}
@media (max-width:620px){
  .int-backup-center{padding:16px}
  .int-backup-head{flex-direction:column;align-items:stretch}
  .int-backup-head .ia-top{min-width:0}
  .int-backup-create{width:100%;margin-top:10px}
  .int-backup-summary{flex-direction:column;align-items:flex-start;gap:8px}
  .int-backup-row{grid-template-columns:1fr}
  .int-backup-actions{justify-content:flex-end}
}

/* Modal crear copia */
.int-bk-overlay{
  position:fixed;inset:0;z-index:950;display:flex;align-items:center;justify-content:center;
  padding:20px;background:rgba(0,0,0,.66);backdrop-filter:blur(5px);
  opacity:0;visibility:hidden;transition:opacity .18s,visibility .18s;
}
.int-bk-overlay.open{opacity:1;visibility:visible}
.int-bk-modal{
  width:min(520px,100%);max-height:calc(100vh - 40px);overflow:auto;
  background:var(--card);border:1px solid var(--stroke-strong);border-radius:18px;
  box-shadow:0 26px 70px rgba(0,0,0,.55);transform:translateY(10px) scale(.98);
  transition:transform .18s var(--ease-out);
}
.int-bk-overlay.open .int-bk-modal{transform:none}
.int-bk-hdr{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;padding:20px 20px 0}
.int-bk-title{font-family:'Sora',sans-serif;font-size:16px;font-weight:700}
.int-bk-sub{font-size:11.5px;color:var(--txt-soft);margin-top:4px}
.int-bk-close{width:31px;height:31px;display:grid;place-items:center;border-radius:8px;border:1px solid var(--stroke);color:var(--txt-soft)}
.int-bk-close svg{width:14px;height:14px}
.int-bk-body{padding:18px 20px}
.int-bk-label{display:block;font-size:11.5px;font-weight:700;margin-bottom:7px}
.int-bk-input{
  width:100%;height:40px;padding:0 12px;border-radius:10px;border:1px solid var(--stroke-strong);
  color:var(--txt);background:var(--panel-2);font:inherit;font-size:12.5px;outline:none;
}
.int-bk-input:focus{border-color:var(--cyan)}
.int-bk-modes{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-top:15px}
.int-bk-mode{
  display:flex;align-items:flex-start;gap:9px;padding:11px;border-radius:11px;
  border:1px solid var(--stroke);background:var(--panel-2);cursor:pointer;
}
.int-bk-mode:has(input:checked){border-color:var(--cyan);background:rgba(56,199,244,.08)}
.int-bk-mode input{accent-color:var(--cyan);margin-top:2px}
.int-bk-mode strong{display:block;font-size:11.5px}
.int-bk-mode span{display:block;font-size:9.8px;color:var(--txt-soft);margin-top:2px}
.int-bk-scopes{display:none;margin-top:13px;padding:12px;border:1px solid var(--stroke);border-radius:11px}
.int-bk-scopes.show{display:block}
.int-bk-scope{display:flex;align-items:flex-start;gap:9px;padding:7px 2px;cursor:pointer}
.int-bk-scope input{accent-color:var(--cyan);margin-top:2px}
.int-bk-scope strong{display:block;font-size:11.5px}
.int-bk-scope span{display:block;font-size:9.8px;color:var(--txt-soft);margin-top:2px}
.int-bk-safe{display:flex;gap:8px;margin-top:14px;padding:10px 11px;border-radius:10px;color:var(--txt-soft);background:rgba(61,220,151,.07);font-size:10.5px;line-height:1.45}
.int-bk-safe svg{width:14px;height:14px;flex:none;color:var(--green);margin-top:1px}
.int-bk-footer{display:flex;justify-content:flex-end;gap:8px;padding:13px 20px 17px;border-top:1px solid var(--stroke)}
.int-bk-btn{height:38px;padding:0 16px;border-radius:10px;font:inherit;font-size:12.5px;font-weight:700}
.int-bk-btn.cancel{border:1px solid var(--stroke);color:var(--txt-soft)}
.int-bk-btn.submit{color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan))}
.int-bk-btn.danger{color:#fff;background:var(--red)}
.int-bk-btn:disabled{opacity:.55;cursor:wait}
@media (max-width:520px){.int-bk-modes{grid-template-columns:1fr}}

/* Firma digital */
.int-sign-status{display:flex;align-items:center;gap:6px;margin-top:5px;font-size:10px;color:var(--txt-soft)}
.int-sign-status::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--txt-soft)}
.int-sign-status.ready{color:var(--green)}
.int-sign-status.ready::before{background:var(--green)}
.int-dev-btn[disabled]{opacity:.45;cursor:not-allowed}
.int-sig-overlay{
  position:fixed;inset:0;z-index:960;display:flex;align-items:center;justify-content:center;
  padding:20px;background:rgba(0,0,0,.68);backdrop-filter:blur(5px);
  opacity:0;visibility:hidden;transition:opacity .18s,visibility .18s;
}
.int-sig-overlay.open{opacity:1;visibility:visible}
.int-sig-modal{
  width:min(680px,100%);max-height:calc(100vh - 40px);overflow:auto;
  border:1px solid var(--stroke-strong);border-radius:18px;background:var(--card);
  box-shadow:0 28px 75px rgba(0,0,0,.55);transform:translateY(10px) scale(.98);
  transition:transform .18s var(--ease-out);
}
.int-sig-overlay.open .int-sig-modal{transform:none}
.int-sig-head{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;padding:20px 20px 0}
.int-sig-title{font-family:'Sora',sans-serif;font-size:16px;font-weight:700}
.int-sig-sub{font-size:11.5px;color:var(--txt-soft);margin-top:4px}
.int-sig-close{width:31px;height:31px;display:grid;place-items:center;flex:none;border:1px solid var(--stroke);border-radius:8px;color:var(--txt-soft)}
.int-sig-close svg{width:14px;height:14px}
.int-sig-body{padding:18px 20px}
.int-sig-current{padding:13px;border-radius:12px;border:1px solid var(--stroke);background:var(--panel-2)}
.int-sig-current-label{font-size:10.5px;font-weight:700;color:var(--txt-soft);margin-bottom:8px}
.int-sig-preview{
  min-height:150px;display:flex;align-items:center;justify-content:center;padding:14px;
  border-radius:10px;background:#fff;overflow:hidden;
}
.int-sig-preview img{display:block;max-width:100%;max-height:145px;object-fit:contain}
.int-sig-empty{display:flex;flex-direction:column;align-items:center;gap:7px;color:#789;font-size:11.5px;text-align:center}
.int-sig-empty svg{width:34px;height:34px}
.int-sig-editor{display:none;margin-top:15px}
.int-sig-editor.open{display:block}
.int-sig-tabs{display:flex;gap:7px;margin-bottom:10px}
.int-sig-tab{
  flex:1;height:38px;border:1px solid var(--stroke);border-radius:9px;
  color:var(--txt-soft);font:inherit;font-size:11.5px;font-weight:700;
}
.int-sig-tab.active{color:var(--cyan);border-color:rgba(56,199,244,.45);background:rgba(56,199,244,.08)}
.int-sig-panel{display:none}
.int-sig-panel.active{display:block}
.int-sig-canvas-wrap{
  position:relative;border:1px dashed var(--stroke-strong);border-radius:11px;
  background:#fff;overflow:hidden;touch-action:none;
}
#intSignatureCanvas{display:block;width:100%;height:220px;cursor:crosshair;touch-action:none}
.int-sig-canvas-hint{position:absolute;left:0;right:0;bottom:10px;text-align:center;color:#94a3b8;font-size:10.5px;pointer-events:none}
.int-sig-tools{display:flex;justify-content:flex-end;margin-top:8px}
.int-sig-clear{padding:7px 11px;border:1px solid var(--stroke);border-radius:8px;color:var(--txt-soft);font-size:10.5px;font-weight:700}
.int-sig-upload{
  min-height:220px;display:flex;flex-direction:column;align-items:center;justify-content:center;
  padding:22px;border:1px dashed var(--stroke-strong);border-radius:11px;text-align:center;cursor:pointer;
}
.int-sig-upload svg{width:32px;height:32px;color:var(--cyan);margin-bottom:9px}
.int-sig-upload strong{font-size:12px}
.int-sig-upload span{font-size:10.5px;color:var(--txt-soft);margin-top:4px}
.int-sig-upload-preview{display:none;max-width:100%;max-height:150px;margin-top:12px;object-fit:contain}
.int-sig-note{margin-top:11px;font-size:10.5px;color:var(--txt-soft);line-height:1.45}
.int-sig-footer{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:13px 20px 17px;border-top:1px solid var(--stroke)}
.int-sig-footer-main{display:flex;gap:8px;margin-left:auto}
.int-sig-btn{height:38px;padding:0 15px;border-radius:10px;font:inherit;font-size:11.5px;font-weight:700}
.int-sig-btn.cancel{border:1px solid var(--stroke);color:var(--txt-soft)}
.int-sig-btn.primary{color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan))}
.int-sig-btn.delete{color:var(--red);border:1px solid rgba(255,90,110,.35);background:rgba(255,90,110,.07)}
.int-sig-btn:disabled{opacity:.5;cursor:wait}
@media(max-width:560px){
  .int-sig-footer{align-items:stretch;flex-direction:column}
  .int-sig-footer-main{width:100%;margin-left:0}
  .int-sig-footer-main .int-sig-btn{flex:1}
  .int-sig-btn.delete{width:100%}
}

/* Información del sistema */
.int-main{display:grid;grid-template-columns:1fr;gap:18px;align-items:stretch;margin-bottom:18px}
.int-info-card{display:flex;flex-direction:column}
.int-info-card .pl-wide-btn{margin-top:auto}
.int-info-row{padding:15px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.int-info-row:last-of-type{border-bottom:0}
.int-info-row .k{font-size:12.5px;color:var(--txt-soft)}
.int-info-row .v{font-size:13px;font-weight:600;float:right}
.int-info-row .on{color:var(--green);display:inline-flex;align-items:center;gap:6px}
.int-info-row .on::before{content:"";width:8px;height:8px;border-radius:50%;background:var(--green)}

/* Tarjetas inferiores */
.int-bottom{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.int-bottom:has(> .card:only-child){grid-template-columns:repeat(2,1fr)}
.int-bottom > .card:only-child{grid-column:1 / -1}
@media (max-width:1100px){.int-bottom{grid-template-columns:repeat(2,1fr)}.int-bottom > .card:only-child{grid-column:1 / -1}}
@media (max-width:560px){.int-bottom{grid-template-columns:1fr}.int-bottom:has(> .card:only-child){grid-template-columns:1fr}}
.int-dev-head{display:flex;align-items:flex-start;gap:11px;margin-bottom:12px}
.int-dev-ico{width:40px;height:40px;flex:none;border-radius:10px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.1);border:1px solid rgba(56,199,244,.2)}
.int-dev-ico svg{width:20px;height:20px}
.int-dev-t{font-size:13.5px;font-weight:700}
.int-chip-on{display:inline-block;margin-top:5px;font-size:9.5px;font-weight:700;color:var(--green);background:rgba(61,220,151,.14);padding:2px 8px;border-radius:6px}
.int-dev-meta{font-size:11.5px;color:var(--txt-soft);margin-top:4px}
.int-dev-meta b{color:var(--txt);font-weight:600}

.int-checks{display:flex;flex-direction:column;gap:10px;margin-top:4px}
.int-check{display:flex;align-items:center;gap:9px;font-size:11.5px;color:var(--txt-soft);cursor:pointer}
.int-check input{appearance:none;-webkit-appearance:none;width:16px;height:16px;flex:none;border-radius:4px;border:1.5px solid var(--stroke-strong);background:var(--panel-2);position:relative;cursor:pointer;transition:background .15s,border-color .15s}
.int-check input:checked{background:linear-gradient(135deg,var(--blue),var(--cyan));border-color:transparent}
.int-check input:checked::after{content:"";position:absolute;left:5px;top:1.5px;width:4px;height:8px;border:solid #fff;border-width:0 2px 2px 0;transform:rotate(45deg)}

.int-dev-btns{display:flex;gap:8px;margin-top:13px}
.int-dev-btn{flex:1;text-align:center;padding:8px;border-radius:9px;border:1px solid var(--stroke-strong);font-size:11.5px;font-weight:700;color:var(--cyan);transition:background-color .15s}
@media (hover:hover){.int-dev-btn:hover{background:rgba(56,199,244,.1)}}

.int-section-divider{width:100%;height:1px;margin:24px 0;border:0;background:linear-gradient(90deg,transparent,var(--stroke-strong),transparent);opacity:.6}
@media (max-width:620px){.int-section-divider{margin:18px 0}}

/* ===== Catálogo de hospital ===== */
.cat-hospital-card{background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:20px;box-shadow:0 16px 40px rgba(0,0,0,.25)}
.cat-hospital-head{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:18px}
.cat-hospital-head h2{font-family:'Sora',sans-serif;font-size:17px;font-weight:700;color:var(--txt)}
.cat-hospital-head p{font-size:12px;color:var(--txt-soft);margin-top:3px}
.cat-add-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 15px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);font-size:12px;font-weight:700;color:var(--cyan);background:transparent;transition:background .15s,color .15s;white-space:nowrap}
.cat-add-btn svg{width:15px;height:15px}
@media (hover:hover){.cat-add-btn:hover{background:rgba(56,199,244,.1);color:var(--txt)}}

.cat-tabs-bar{border-bottom:1px solid var(--stroke)}
.cat-tabs{display:flex;flex-wrap:wrap;gap:22px}
.cat-tab{display:flex;align-items:center;gap:7px;padding:0 2px 11px;font:inherit;font-size:13px;font-weight:600;color:var(--txt-soft);background:none;border:0;border-bottom:2px solid transparent;cursor:pointer;transition:color .15s,border-color .15s}
.cat-tab svg{width:18px;height:18px}
.cat-tab.active{color:var(--cyan);border-bottom-color:var(--cyan)}
@media (hover:hover){.cat-tab:hover{color:var(--txt)}}

.cat-panels{margin-top:16px}
.cat-panel.hidden{display:none}
.cat-empty{min-height:180px;display:flex;align-items:center;justify-content:center;border:1px dashed var(--stroke);border-radius:var(--r-md);color:var(--txt-soft);font-size:12.5px}

.cat-table-wrap{overflow-x:auto}
.cat-table{width:100%;border-collapse:collapse;font-size:12.5px}
.cat-table thead th{padding:0 0 11px;font-weight:700;color:var(--txt);border-bottom:1px solid var(--stroke-strong);text-align:center;white-space:nowrap}
.cat-table tbody tr{border-bottom:1px solid var(--stroke);transition:background .12s}
.cat-table tbody tr:last-child{border-bottom:0}
@media (hover:hover){.cat-table tbody tr:hover{background:rgba(110,160,255,.06)}}
.cat-table td{padding:13px 0;vertical-align:middle;text-align:center}
.cat-name{font-weight:700;color:var(--txt)}
.cat-soft{color:var(--txt-soft)}
.cat-role{font-weight:600;color:var(--txt)}
.cat-actions{text-align:center;white-space:nowrap}
.cat-no-action{color:var(--txt-soft);font-size:12px}
.cat-you{display:inline-block;margin-left:6px;padding:1px 6px;font-size:9px;font-weight:700;color:var(--cyan);background:rgba(56,199,244,.1);border:1px solid rgba(56,199,244,.25);border-radius:4px;vertical-align:middle}
.cat-pending-name{font-weight:500;color:var(--txt-soft)}
.cat-empty-cell{padding:40px 0;text-align:center;color:var(--txt-soft);font-size:12.5px}
.cat-badge{display:inline-flex;align-items:center;padding:3px 11px;font-size:10.5px;font-weight:700;border-radius:99px;border:1px solid}
.cat-badge-on{color:var(--green);background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.3)}
.cat-badge-off{color:var(--orange);background:rgba(245,158,45,.1);border-color:rgba(245,158,45,.3)}
.cat-actions{display:inline-flex;align-items:center;justify-content:center;gap:6px}
.cat-edit-btn{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;color:var(--cyan);border:1px solid rgba(56,199,244,.25);background:rgba(56,199,244,.07);transition:background .15s,border-color .15s}
.cat-edit-btn svg{width:15px;height:15px}
@media (hover:hover){.cat-edit-btn:hover{background:rgba(56,199,244,.15);border-color:rgba(56,199,244,.45)}}
.cat-del-btn{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;color:var(--red);border:1px solid rgba(239,68,68,.2);background:rgba(239,68,68,.07);transition:background .15s,border-color .15s}
.cat-del-btn svg{width:15px;height:15px}
@media (hover:hover){.cat-del-btn:hover{background:rgba(239,68,68,.15);border-color:rgba(239,68,68,.4)}}

@media (max-width:620px){
  .cat-hospital-head{flex-direction:column;align-items:flex-start}
  .cat-add-btn{width:100%;justify-content:center}
  .cat-tabs{gap:14px}
  .cat-tab{font-size:12px}
}

/* Modal de confirmación de eliminación */
.cat-delete-overlay{position:fixed;inset:0;z-index:970;display:flex;align-items:center;justify-content:center;padding:20px;background:rgba(0,0,0,.66);backdrop-filter:blur(5px);opacity:0;visibility:hidden;transition:opacity .18s,visibility .18s}
.cat-delete-overlay.open{opacity:1;visibility:visible}
.cat-delete-modal{width:min(400px,100%);background:var(--card);border:1px solid var(--stroke-strong);border-radius:18px;box-shadow:0 26px 70px rgba(0,0,0,.55);padding:26px 24px 22px;text-align:center;transform:translateY(10px) scale(.98);transition:transform .18s var(--ease-out)}
.cat-delete-overlay.open .cat-delete-modal{transform:none}
.cat-delete-ico{width:56px;height:56px;border-radius:50%;display:grid;place-items:center;margin:0 auto 16px;color:var(--red);background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25)}
.cat-delete-ico svg{width:28px;height:28px}
.cat-delete-title{font-family:'Sora',sans-serif;font-size:16px;font-weight:700;margin-bottom:8px}
.cat-delete-text{font-size:13px;color:var(--txt-soft);line-height:1.5;margin-bottom:22px}
.cat-delete-actions{display:flex;gap:10px;justify-content:center}
.cat-delete-btn{height:40px;padding:0 18px;border-radius:10px;font:inherit;font-size:13px;font-weight:700;cursor:pointer;transition:background-color .15s,opacity .15s}
.cat-delete-btn.cancel{border:1px solid var(--stroke-strong);color:var(--txt);background:var(--panel-2)}
.cat-delete-btn.cancel:hover{background:rgba(110,160,255,.1)}
.cat-delete-btn.danger{color:#fff;background:var(--red);border:1px solid var(--red)}
.cat-delete-btn.danger:hover{opacity:.88}
@media (max-width:480px){.cat-delete-actions{flex-direction:column}.cat-delete-btn{width:100%}}
</style>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/configuracion/sections/integraciones/_styles.blade.php ENDPATH**/ ?>