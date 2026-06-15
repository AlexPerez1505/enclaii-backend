@extends('layouts.app')

@section('title', 'Informes')
@section('active', 'informes')
@section('header-title', 'Informes')

@push('styles')
<style>
/* ===== INFORMES DE PACIENTES ===== */
.inf-shell{
  border:1px solid rgba(46,123,246,.25);
  border-radius:8px;
  background:var(--panel-2, #ffffff);
  overflow:hidden;
  box-shadow:0 4px 24px rgba(0,0,0,.08);
}
html[data-theme="dark"] .inf-shell{
  background:rgba(5,12,28,.68);
  border-color:rgba(46,123,246,.45);
  box-shadow:0 16px 48px -30px rgba(46,123,246,.55);
}
.inf-panel-head{
  padding:28px 32px 24px;
  border-bottom:1px solid rgba(0,0,0,.08);
}
html[data-theme="dark"] .inf-panel-head{
  border-bottom-color:rgba(46,123,246,.24);
}
.inf-title{
  font-family:'Sora',sans-serif;
  font-size:24px;
  font-weight:800;
  margin-bottom:8px;
}
.inf-sub{
  color:#6b7280;
  font-size:15px;
  font-weight:600;
}
html[data-theme="dark"] .inf-sub{
  color:var(--txt-soft, #8FA3CF);
}
.inf-toolbar{
  display:grid;
  grid-template-columns:minmax(260px,1fr) 150px 170px;
  gap:20px;
  padding:26px 18px 14px;
}
.inf-search{
  height:42px;
  display:flex;
  align-items:center;
  gap:12px;
  border:1px solid rgba(0,0,0,.15);
  border-radius:8px;
  background:#f9fafb;
  padding:0 16px;
}
.inf-search svg{color:#6b7280;flex:none}
.inf-search input{
  flex:1;
  min-width:0;
  border:0;
  outline:0;
  background:transparent;
  color:#1f2937;
  font:inherit;
  font-size:13px;
  font-weight:600;
}
.inf-search input::placeholder{color:#9ca3af}
html[data-theme="dark"] .inf-search{
  border-color:rgba(46,123,246,.7);
  background:#06132B;
}
html[data-theme="dark"] .inf-search svg{
  color:#DCEBFF;
}
html[data-theme="dark"] .inf-search input{
  color:var(--txt, #EAF1FF);
}
html[data-theme="dark"] .inf-search input::placeholder{
  color:#8396BF;
}
.inf-action{
  position:relative;
  height:42px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  border:1px solid rgba(0,0,0,.15);
  border-radius:8px;
  background:#ffffff;
  color:#1f2937;
  font:inherit;
  font-size:13px;
  font-weight:700;
  transition:background-color 150ms ease,border-color 150ms ease,transform 150ms ease-out;
}
.inf-action svg{color:#3b82f6}
.inf-action:active{transform:scale(.97)}
.inf-action.primary{background:#3b82f6;color:#ffffff;border-color:#3b82f6}
.inf-action.primary svg{color:#ffffff}
@media(hover:hover)and(pointer:fine){
  .inf-action:hover{background:#f9fafb;border-color:#3b82f6}
  .inf-action.primary:hover{background:#2563eb}
}
html[data-theme="dark"] .inf-action{
  border-color:rgba(46,123,246,.42);
  background:#0B1B3A;
  color:var(--txt, #EAF1FF);
}
html[data-theme="dark"] .inf-action svg{
  color:var(--cyan, #38C7F4);
}
html[data-theme="dark"] .inf-action.primary{
  background:#0A203D;
  border-color:rgba(46,123,246,.42);
}
html[data-theme="dark"] .inf-action.primary svg{
  color:var(--cyan, #38C7F4);
}
@media(hover:hover)and(pointer:fine){
  html[data-theme="dark"] .inf-action:hover{
    background:rgba(46,123,246,.18);
    border-color:rgba(56,199,244,.7);
  }
}
.inf-action[data-tooltip]::after{
  content:attr(data-tooltip);
  position:absolute;
  left:50%;
  bottom:calc(100% + 9px);
  translate:-50% 5px;
  width:max-content;
  max-width:220px;
  padding:7px 10px;
  border-radius:7px;
  border:1px solid var(--stroke-strong);
  background:var(--panel);
  color:var(--txt);
  font-size:12px;
  line-height:1.3;
  opacity:0;
  visibility:hidden;
  pointer-events:none;
  box-shadow:0 12px 26px -14px rgba(0,0,0,.75);
  transition:opacity 150ms ease,translate 150ms ease,visibility 150ms ease;
  z-index:20;
}
@media(hover:hover)and(pointer:fine){
  .inf-action[data-tooltip]:hover::after,
  .inf-action[data-tooltip]:focus-visible::after{
    opacity:1;
    visibility:visible;
    translate:-50% 0;
  }
}
.inf-filter-panel{
  display:none;
  grid-template-columns:repeat(4,minmax(130px,1fr));
  gap:12px;
  padding:0 18px 18px;
}
.inf-filter-panel.open{display:grid}
.inf-filter-control{
  height:38px;
  border:1px solid rgba(0,0,0,.15);
  border-radius:8px;
  background:#ffffff;
  color:#1f2937;
  padding:0 12px;
  font:inherit;
  font-size:13px;
  outline:0;
}
.inf-filter-control:focus{border-color:#3b82f6}
html[data-theme="dark"] .inf-filter-control{
  border-color:rgba(46,123,246,.32);
  background:#06132B;
  color:var(--txt, #EAF1FF);
}
html[data-theme="dark"] .inf-filter-control:focus{
  border-color:var(--blue, #2E7BF6);
}
.inf-table-wrap{padding:0 10px 0}
.inf-table{
  width:100%;
  border-collapse:separate;
  border-spacing:0 8px;
}
.inf-table thead th{
  padding:0 12px 6px;
  color:#6b7280;
  font-size:10.5px;
  font-weight:800;
  text-transform:uppercase;
  text-align:left;
}
html[data-theme="dark"] .inf-table thead th{
  color:#B8C7E9;
}
.inf-table tbody tr{
  background:#ffffff;
  box-shadow:0 0 0 1px rgba(0,0,0,.06) inset;
}
html[data-theme="dark"] .inf-table tbody tr{
  background:#061A31;
  box-shadow:0 0 0 1px rgba(46,123,246,.06) inset;
}
.inf-table tbody tr.hide{display:none}
.inf-table tbody td{
  padding:13px 12px;
  font-size:13px;
  font-weight:700;
  vertical-align:middle;
  color:#1f2937;
}
html[data-theme="dark"] .inf-table tbody td{
  color:var(--txt, #EAF1FF);
}
.inf-table tbody td:first-child{border-radius:8px 0 0 8px}
.inf-table tbody td:last-child{border-radius:0 8px 8px 0}
.inf-patient{
  display:flex;
  align-items:center;
  gap:12px;
}
.inf-avatar{
  width:36px;
  height:36px;
  border-radius:50%;
  display:grid;
  place-items:center;
  background:linear-gradient(135deg,#3b82f6,#2563eb);
  color:#fff;
  font-family:'Sora',sans-serif;
  font-size:12px;
  font-weight:800;
}
.inf-avatar.green{background:linear-gradient(135deg,#10b981,#059669)}
.inf-date strong{
  display:block;
  line-height:1;
  color:#1f2937;
}
.inf-date span{
  display:block;
  margin-top:2px;
  font-size:10px;
  line-height:1;
  color:#6b7280;
}
.inf-status{
  display:inline-flex;
  align-items:center;
  height:26px;
  padding:0 9px;
  border-radius:7px;
  font-size:12px;
  font-weight:800;
}
.inf-status.done{color:#00D989;border:1px solid rgba(0,217,137,.42);background:rgba(0,217,137,.08)}
.inf-status.wait{color:#FFA800;border:1px solid rgba(255,168,0,.55);background:rgba(255,168,0,.08)}
.inf-status.cancel{color:#FF243A;border:1px solid rgba(255,36,58,.55);background:rgba(255,36,58,.08)}
.inf-actions{
  display:flex;
  justify-content:flex-end;
  gap:12px;
}
.inf-row-btn{
  height:31px;
  min-width:64px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  border:1px solid rgba(0,0,0,.15);
  border-radius:7px;
  background:#ffffff;
  color:#3b82f6;
  font:inherit;
  font-size:12px;
  font-weight:800;
  transition:background-color 150ms ease,border-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){
  .inf-row-btn:hover{background:#f9fafb;border-color:#3b82f6}
}
html[data-theme="dark"] .inf-row-btn{
  border-color:rgba(46,123,246,.42);
  background:#06204A;
  color:var(--cyan, #38C7F4);
}
@media(hover:hover)and(pointer:fine){
  html[data-theme="dark"] .inf-row-btn:hover{
    background:rgba(46,123,246,.2);
    border-color:rgba(56,199,244,.7);
  }
}
.inf-footer{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:18px;
  padding:16px 18px 28px;
}
.inf-count{
  color:#6b7280;
  font-size:12px;
  font-weight:700;
}
html[data-theme="dark"] .inf-count{
  color:#6F7FA6;
}
.inf-pages{
  display:flex;
  align-items:center;
  gap:8px;
}
.inf-page{
  min-width:34px;
  height:30px;
  display:grid;
  place-items:center;
  border:1px solid rgba(0,0,0,.15);
  border-radius:8px;
  background:#ffffff;
  color:#1f2937;
  font:inherit;
  font-size:13px;
  font-weight:800;
}
.inf-page.active{
  background:#3b82f6;
  border-color:#3b82f6;
  color:#ffffff;
  box-shadow:0 4px 12px rgba(59,130,246,.3);
}
html[data-theme="dark"] .inf-page{
  border-color:rgba(46,123,246,.22);
  background:#06132B;
  color:var(--txt, #EAF1FF);
}
html[data-theme="dark"] .inf-page.active{
  background:linear-gradient(135deg,#1668D9,var(--blue, #2E7BF6));
  border-color:var(--blue, #2E7BF6);
  box-shadow:0 8px 18px -10px rgba(46,123,246,.9);
}
.inf-empty{
  display:none;
  padding:40px 20px;
  text-align:center;
  color:#6b7280;
  font-weight:700;
}
html[data-theme="dark"] .inf-empty{
  color:var(--txt-soft, #8FA3CF);
}
.inf-empty.show{display:block}

/* ===== PANEL DE FILTROS LATERAL ===== */
.inf-fil-overlay{
  position:fixed;inset:0;z-index:1000;
  background:rgba(6,8,28,.55);backdrop-filter:blur(4px);
  opacity:0;pointer-events:none;
  transition:opacity 220ms ease-out;
}
.inf-fil-overlay.open{opacity:1;pointer-events:all}
html[data-theme="dark"] .inf-fil-overlay{
  background:rgba(6,8,28,.75);
}

.inf-fil-panel{
  position:fixed;top:0;right:0;bottom:0;z-index:1001;
  width:320px;max-width:92vw;
  background:var(--panel-2, #ffffff);border-left:1px solid rgba(0,0,0,.08);
  display:flex;flex-direction:column;
  transform:translateX(100%);
  transition:transform 260ms ease-out;
  overflow:hidden;
}
.inf-fil-panel.open{transform:translateX(0)}
html[data-theme="dark"] .inf-fil-panel{
  background:var(--panel, #0A0F2E);
  border-left-color:var(--stroke-strong, rgba(255,255,255,.12));
}

.inf-fil-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:20px 20px 16px;
  border-bottom:1px solid rgba(0,0,0,.08);
  flex:none;
}
html[data-theme="dark"] .inf-fil-head{
  border-bottom-color:var(--stroke, rgba(255,255,255,.08));
}
.inf-fil-title{font-family:'Sora',sans-serif;font-size:16px;font-weight:700;color:#1f2937}
html[data-theme="dark"] .inf-fil-title{color:var(--txt, #EAF1FF)}
.inf-fil-close{
  width:32px;height:32px;border-radius:8px;
  border:1px solid rgba(0,0,0,.15);display:grid;place-items:center;
  color:#6b7280;
  transition:background-color 150ms ease,color 150ms ease;
}
html[data-theme="dark"] .inf-fil-close{
  border-color:var(--stroke, rgba(255,255,255,.08));
  color:var(--txt-soft, #8FA3CF);
}
@media(hover:hover)and(pointer:fine){
  .inf-fil-close:hover{background:rgba(59,130,246,.1);color:#3b82f6}
  html[data-theme="dark"] .inf-fil-close:hover{background:rgba(110,160,255,.1);color:var(--txt, #EAF1FF)}
}

.inf-fil-body{
  flex:1;overflow-y:auto;padding:16px 20px;
  display:flex;flex-direction:column;gap:14px;
  scrollbar-width:thin;scrollbar-color:rgba(0,0,0,.15) transparent;
}
html[data-theme="dark"] .inf-fil-body{
  scrollbar-color:var(--stroke, rgba(255,255,255,.08)) transparent;
}

.inf-fil-group{display:flex;flex-direction:column;gap:6px}
.inf-fil-label{font-size:11.5px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:#6b7280}
html[data-theme="dark"] .inf-fil-label{color:var(--txt-soft, #8FA3CF)}
.inf-fil-section-title{font-family:'Sora',sans-serif;font-size:14px;font-weight:700;color:#1f2937}
html[data-theme="dark"] .inf-fil-section-title{color:var(--txt, #EAF1FF)}

.inf-fil-select-wrap{position:relative}
.inf-fil-select{
  width:100%;height:40px;padding:0 36px 0 12px;
  font:inherit;font-size:13px;color:#1f2937;
  background:#f9fafb;border:1px solid rgba(0,0,0,.15);
  border-radius:8px;outline:none;
  appearance:none;-webkit-appearance:none;cursor:pointer;
}
html[data-theme="dark"] .inf-fil-select{
  color:var(--txt, #EAF1FF);
  background:var(--panel-2, #0E1740);
  border-color:var(--stroke, rgba(255,255,255,.08));
}
.inf-fil-select:focus{border-color:#3b82f6}
html[data-theme="dark"] .inf-fil-select:focus{border-color:var(--blue, #2E7BF6)}
.inf-fil-select-icon{
  position:absolute;right:12px;top:50%;translate:0 -50%;
  pointer-events:none;color:#6b7280
}
html[data-theme="dark"] .inf-fil-select-icon{color:var(--txt-soft, #8FA3CF)}

.inf-fil-date-row{
  display:flex;align-items:center;gap:8px;
}
.inf-fil-date-wrap{
  position:relative;flex:1
}
.inf-fil-date{
  width:100%;height:40px;padding:0 12px 0 36px;
  font:inherit;font-size:13px;color:#1f2937;
  background:#f9fafb;border:1px solid rgba(0,0,0,.15);
  border-radius:8px;outline:none;cursor:pointer;
}
html[data-theme="dark"] .inf-fil-date{
  color:var(--txt, #EAF1FF);
  background:var(--panel-2, #0E1740);
  border-color:var(--stroke, rgba(255,255,255,.08));
}
.inf-fil-date:focus{border-color:#3b82f6}
html[data-theme="dark"] .inf-fil-date:focus{border-color:var(--blue, #2E7BF6)}
.inf-fil-date-wrap svg{
  position:absolute;left:12px;top:50%;translate:0 -50%;
  pointer-events:none;color:#6b7280;width:14px;height:14px
}
html[data-theme="dark"] .inf-fil-date-wrap svg{color:var(--txt-soft, #8FA3CF)}
.inf-fil-date-sep{
  font-size:12px;color:#6b7280;font-weight:500;white-space:nowrap
}
html[data-theme="dark"] .inf-fil-date-sep{color:var(--txt-soft, #8FA3CF)}

.inf-fil-checks{
  display:flex;flex-direction:column;gap:8px
}
.inf-fil-check{
  display:flex;align-items:center;gap:8px;
  font-size:13px;font-weight:600;color:#1f2937;cursor:pointer
}
html[data-theme="dark"] .inf-fil-check{color:var(--txt, #EAF1FF)}
.inf-fil-check input[type=checkbox]{
  width:16px;height:16px;border:1px solid rgba(0,0,0,.15);
  border-radius:4px;accent-color:#3b82f6
}
html[data-theme="dark"] .inf-fil-check input[type=checkbox]{
  border-color:var(--stroke, rgba(255,255,255,.08));
  accent-color:var(--blue, #2E7BF6)
}

.inf-fil-divider{
  height:1px;background:rgba(0,0,0,.08);margin:4px 0
}
html[data-theme="dark"] .inf-fil-divider{background:var(--stroke, rgba(255,255,255,.08))}

.inf-fil-footer{
  flex:none;padding:14px 20px;
  border-top:1px solid rgba(0,0,0,.08);
  display:flex;flex-direction:column;gap:8px;
}
html[data-theme="dark"] .inf-fil-footer{
  border-top-color:var(--stroke, rgba(255,255,255,.08));
}
.inf-fil-btn-clear{
  display:flex;align-items:center;justify-content:center;gap:8px;
  height:40px;border-radius:8px;
  background:transparent;border:1px solid rgba(0,0,0,.15);
  font:inherit;font-size:13.5px;font-weight:600;
  color:#6b7280;width:100%;
  transition:background-color 150ms ease,color 150ms ease,border-color 150ms ease;
}
html[data-theme="dark"] .inf-fil-btn-clear{
  border-color:var(--stroke, rgba(255,255,255,.08));
  color:var(--txt-soft, #8FA3CF);
}
@media(hover:hover)and(pointer:fine){
  .inf-fil-btn-clear:hover{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.4);color:#ef4444}
  html[data-theme="dark"] .inf-fil-btn-clear:hover{background:rgba(255,90,110,.1);border-color:rgba(255,90,110,.4);color:var(--red, #FF5A6E)}
}

/* ===== MODAL NUEVO INFORME ===== */
.inf-modal-overlay{
  position:fixed;inset:0;z-index:2000;
  background:rgba(0,0,0,.5);backdrop-filter:blur(4px);
  opacity:0;pointer-events:none;
  transition:opacity 220ms ease-out;
  display:flex;align-items:center;justify-content:center;
  padding:20px;
}
.inf-modal-overlay.open{opacity:1;pointer-events:all}

.inf-modal{
  width:100%;max-width:600px;max-height:90vh;
  background:var(--panel-2, #ffffff);border-radius:16px;
  box-shadow:0 20px 60px rgba(0,0,0,.15);
  display:flex;flex-direction:column;
  transform:scale(.95) translateY(20px);
  transition:transform 260ms ease-out;
  overflow:hidden;
}
.inf-modal-overlay.open .inf-modal{transform:scale(1) translateY(0)}
html[data-theme="dark"] .inf-modal{
  background:var(--panel, #0A0F2E);
  box-shadow:0 20px 60px rgba(0,0,0,.4);
}

.inf-modal-head{
  padding:24px 24px 20px;
  border-bottom:1px solid rgba(0,0,0,.08);
  flex:none;
}
html[data-theme="dark"] .inf-modal-head{
  border-bottom-color:var(--stroke, rgba(255,255,255,.08));
}
.inf-modal-title{
  font-family:'Sora',sans-serif;font-size:20px;font-weight:700;color:#1f2937;margin:0
}
html[data-theme="dark"] .inf-modal-title{color:var(--txt, #EAF1FF)}
.inf-modal-close{
  position:absolute;top:20px;right:20px;
  width:32px;height:32px;border-radius:8px;
  border:1px solid rgba(0,0,0,.15);display:grid;place-items:center;
  color:#6b7280;background:transparent;
  cursor:pointer;transition:all 150ms ease;
}
html[data-theme="dark"] .inf-modal-close{
  border-color:var(--stroke, rgba(255,255,255,.08));
  color:var(--txt-soft, #8FA3CF);
}
@media(hover:hover)and(pointer:fine){
  .inf-modal-close:hover{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.4);color:#ef4444}
  html[data-theme="dark"] .inf-modal-close:hover{background:rgba(255,90,110,.1);border-color:rgba(255,90,110,.4);color:var(--red, #FF5A6E)}
}

.inf-modal-body{
  flex:1;overflow-y:auto;padding:24px;
  display:flex;flex-direction:column;gap:20px;
  scrollbar-width:thin;scrollbar-color:rgba(0,0,0,.15) transparent;
}
html[data-theme="dark"] .inf-modal-body{
  scrollbar-color:var(--stroke, rgba(255,255,255,.08)) transparent;
}

.inf-form-grid{
  display:grid;grid-template-columns:1fr 1fr;gap:16px
}
.inf-form-group{display:flex;flex-direction:column;gap:6px}
.inf-form-group.full{grid-column:1 / -1}
.inf-form-label{
  font-size:12px;font-weight:600;letter-spacing:.02em;color:#6b7280
}
html[data-theme="dark"] .inf-form-label{color:var(--txt-soft, #8FA3CF)}
.inf-form-input,
.inf-form-select,
.inf-form-textarea{
  height:40px;padding:0 12px;
  font:inherit;font-size:14px;color:#1f2937;
  background:#f9fafb;border:1px solid rgba(0,0,0,.15);
  border-radius:8px;outline:none;transition:border-color 150ms ease;
}
html[data-theme="dark"] .inf-form-input,
html[data-theme="dark"] .inf-form-select,
html[data-theme="dark"] .inf-form-textarea{
  color:var(--txt, #EAF1FF);
  background:var(--panel-2, #0E1740);
  border-color:var(--stroke, rgba(255,255,255,.08));
}
.inf-form-input:focus,
.inf-form-select:focus,
.inf-form-textarea:focus{border-color:#3b82f6}
html[data-theme="dark"] .inf-form-input:focus,
html[data-theme="dark"] .inf-form-select:focus,
html[data-theme="dark"] .inf-form-textarea:focus{border-color:var(--blue, #2E7BF6)}
.inf-form-textarea{
  min-height:100px;resize:vertical;padding:10px 12px;font-family:inherit;line-height:1.5
}

.inf-form-section{
  padding:16px;background:rgba(0,0,0,.02);border-radius:12px;border:1px solid rgba(0,0,0,.06)
}
html[data-theme="dark"] .inf-form-section{
  background:rgba(255,255,255,.02);border-color:var(--stroke, rgba(255,255,255,.06));
}
.inf-form-section-title{
  font-family:'Sora',sans-serif;font-size:14px;font-weight:700;color:#1f2937;margin:0 0 12px
}
html[data-theme="dark"] .inf-form-section-title{color:var(--txt, #EAF1FF)}

.inf-modal-footer{
  padding:20px 24px;border-top:1px solid rgba(0,0,0,.08);
  display:flex;justify-content:flex-end;gap:12px;flex:none
}
html[data-theme="dark"] .inf-modal-footer{
  border-top-color:var(--stroke, rgba(255,255,255,.08));
}
.inf-modal-btn{
  height:40px;padding:0 20px;border-radius:8px;
  font:inherit;font-size:14px;font-weight:600;cursor:pointer;
  transition:all 150ms ease;display:inline-flex;align-items:center;gap:8px
}
.inf-modal-btn.cancel{
  background:transparent;border:1px solid rgba(0,0,0,.15);color:#6b7280
}
html[data-theme="dark"] .inf-modal-btn.cancel{
  border-color:var(--stroke, rgba(255,255,255,.08));
  color:var(--txt-soft, #8FA3CF);
}
@media(hover:hover)and(pointer:fine){
  .inf-modal-btn.cancel:hover{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.4);color:#ef4444}
  html[data-theme="dark"] .inf-modal-btn.cancel:hover{background:rgba(255,90,110,.1);border-color:rgba(255,90,110,.4);color:var(--red, #FF5A6E)}
}
.inf-modal-btn.submit{
  background:#3b82f6;border:none;color:#ffffff
}
html[data-theme="dark"] .inf-modal-btn.submit{background:var(--blue, #2E7BF6)}
@media(hover:hover)and(pointer:fine){
  .inf-modal-btn.submit:hover{background:#2563eb;opacity:.9}
  html[data-theme="dark"] .inf-modal-btn.submit:hover{background:var(--blue, #2E7BF6);opacity:.9}
}
.inf-modal-btn:active{transform:scale(.97)}

/* ===== EDITOR DE TEXTO RICO ===== */
.inf-editor-toolbar{
  display:flex;flex-wrap:wrap;gap:4px;
  padding:8px;background:rgba(0,0,0,.02);border:1px solid rgba(0,0,0,.15);border-bottom:none;border-radius:8px 8px 0 0;
}
html[data-theme="dark"] .inf-editor-toolbar{
  background:rgba(255,255,255,.02);border-color:var(--stroke, rgba(255,255,255,.08));
}
.inf-editor-btn{
  width:32px;height:32px;border:none;background:transparent;border-radius:4px;
  color:#6b7280;cursor:pointer;transition:all 150ms ease;display:flex;align-items:center;justify-content:center;
}
html[data-theme="dark"] .inf-editor-btn{color:var(--txt-soft, #8FA3CF)}
@media(hover:hover)and(pointer:fine){
  .inf-editor-btn:hover{background:rgba(59,130,246,.1);color:#3b82f6}
  html[data-theme="dark"] .inf-editor-btn:hover{background:rgba(110,160,255,.1);color:var(--cyan, #38C7F4)}
}
.inf-editor-btn.active{background:#3b82f6;color:#ffffff}
html[data-theme="dark"] .inf-editor-btn.active{background:var(--blue, #2E7BF6)}
.inf-editor-separator{
  width:1px;height:24px;background:rgba(0,0,0,.15);margin:0 4px
}
html[data-theme="dark"] .inf-editor-separator{background:var(--stroke, rgba(255,255,255,.08))}

.inf-editor-content{
  min-height:150px;max-height:300px;
  padding:12px;border:1px solid rgba(0,0,0,.15);border-radius:0 0 8px 8px;
  background:#f9fafb;color:#1f2937;font:inherit;font-size:14px;line-height:1.6;
  overflow-y:auto;outline:none;
}
html[data-theme="dark"] .inf-editor-content{
  background:var(--panel-2, #0E1740);border-color:var(--stroke, rgba(255,255,255,.08));
  color:var(--txt, #EAF1FF);
}
.inf-editor-content:focus{border-color:#3b82f6}
html[data-theme="dark"] .inf-editor-content:focus{border-color:var(--blue, #2E7BF6)}

/* ===== VARIABLES AUTOMÁTICAS ===== */
.inf-variables-panel{
  border:1px solid rgba(0,0,0,.15);border-radius:8px;background:rgba(0,0,0,.02);
}
html[data-theme="dark"] .inf-variables-panel{
  border-color:var(--stroke, rgba(255,255,255,.08));
  background:rgba(255,255,255,.02);
}
.inf-variables-header{
  padding:12px;border-bottom:1px solid rgba(0,0,0,.08);display:flex;align-items:center;gap:8px;
}
html[data-theme="dark"] .inf-variables-header{
  border-bottom-color:var(--stroke, rgba(255,255,255,.08));
}
.inf-variables-title{
  font-size:13px;font-weight:700;color:#1f2937
}
html[data-theme="dark"] .inf-variables-title{color:var(--txt, #EAF1FF)}
.inf-variables-list{
  display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:8px;padding:12px;
}
.inf-variable-item{
  padding:8px 12px;border-radius:6px;background:rgba(59,130,246,.05);border:1px solid rgba(59,130,246,.15);
  font-size:12px;color:#3b82f6;cursor:pointer;transition:all 150ms ease;
}
html[data-theme="dark"] .inf-variable-item{
  background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.25);color:var(--cyan, #38C7F4);
}
@media(hover:hover)and(pointer:fine){
  .inf-variable-item:hover{background:rgba(59,130,246,.1);transform:translateY(-1px)}
  html[data-theme="dark"] .inf-variable-item:hover{background:rgba(46,123,246,.15)}
}

/* ===== PLANTILLAS MÉDICAS ===== */
.inf-templates-grid{
  display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;
}
.inf-template-card{
  padding:16px;border:1px solid rgba(0,0,0,.15);border-radius:8px;background:rgba(0,0,0,.02);
  cursor:pointer;transition:all 150ms ease;text-align:center;
}
html[data-theme="dark"] .inf-template-card{
  border-color:var(--stroke, rgba(255,255,255,.08));
  background:rgba(255,255,255,.02);
}
.inf-template-icon{
  width:40px;height:40px;margin:0 auto 8px;border-radius:8px;
  background:linear-gradient(135deg,#3b82f6,#2563eb);
  display:flex;align-items:center;justify-content:center;color:#ffffff;
}
html[data-theme="dark"] .inf-template-icon{
  background:linear-gradient(135deg,var(--blue, #2E7BF6),#1668D9);
}
.inf-template-name{
  font-size:13px;font-weight:600;color:#1f2937;margin:0
}
html[data-theme="dark"] .inf-template-name{color:var(--txt, #EAF1FF)}
.inf-template-desc{
  font-size:11px;color:#6b7280;margin:4px 0 0
}
html[data-theme="dark"] .inf-template-desc{color:var(--txt-soft, #8FA3CF)}
@media(hover:hover)and(pointer:fine){
  .inf-template-card:hover{border-color:#3b82f6;transform:translateY(-2px);box-shadow:0 4px 12px rgba(59,130,246,.2)}
  html[data-theme="dark"] .inf-template-card:hover{border-color:var(--blue, #2E7BF6);box-shadow:0 4px 12px rgba(46,123,246,.3)}
}

/* ===== GESTIÓN DE IMÁGENES ===== */
.inf-images-grid{
  display:grid;grid-template-columns:repeat(auto-fill,80px,1fr);gap:8px;
}
.inf-image-item{
  aspect-ratio:1;border-radius:8px;border:1px solid rgba(0,0,0,.15);
  background:rgba(0,0,0,.02);position:relative;overflow:hidden;cursor:pointer;
}
html[data-theme="dark"] .inf-image-item{
  border-color:var(--stroke, rgba(255,255,255,.08));
  background:rgba(255,255,255,.02);
}
.inf-image-item img{
  width:100%;height:100%;object-fit:cover
}
.inf-image-add{
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  color:#6b7280;font-size:11px;text-align:center;gap:4px;
}
html[data-theme="dark"] .inf-image-add{color:var(--txt-soft, #8FA3CF)}
@media(hover:hover)and(pointer:fine){
  .inf-image-item:hover{border-color:#3b82f6;box-shadow:0 2px 8px rgba(59,130,246,.2)}
  html[data-theme="dark"] .inf-image-item:hover{border-color:var(--blue, #2E7BF6);box-shadow:0 2px 8px rgba(46,123,246,.3)}
}

/* ===== VISTA PREVIA ===== */
.inf-preview-container{
  border:1px solid rgba(0,0,0,.15);border-radius:8px;background:#ffffff;
  max-height:400px;overflow-y:auto;
}
html[data-theme="dark"] .inf-preview-container{
  border-color:var(--stroke, rgba(255,255,255,.08));
  background:var(--panel, #0A0F2E);
}
.inf-preview-header{
  padding:16px;border-bottom:1px solid rgba(0,0,0,.08);background:rgba(0,0,0,.02);
}
html[data-theme="dark"] .inf-preview-header{
  border-bottom-color:var(--stroke, rgba(255,255,255,.08));
  background:rgba(255,255,255,.02);
}
.inf-preview-title{
  font-family:'Sora',sans-serif;font-size:16px;font-weight:700;color:#1f2937;margin:0
}
html[data-theme="dark"] .inf-preview-title{color:var(--txt, #EAF1FF)}
.inf-preview-content{
  padding:20px;color:#1f2937;line-height:1.6
}
html[data-theme="dark"] .inf-preview-content{color:var(--txt, #EAF1FF)}

/* ===== EXPORTACIÓN ===== */
.inf-export-options{
  display:grid;grid-template-columns:repeat(auto-fit,140px,1fr);gap:12px;
}
.inf-export-btn{
  padding:16px;border:1px solid rgba(0,0,0,.15);border-radius:8px;background:rgba(0,0,0,.02);
  text-align:center;cursor:pointer;transition:all 150ms ease;
}
html[data-theme="dark"] .inf-export-btn{
  border-color:var(--stroke, rgba(255,255,255,.08));
  background:rgba(255,255,255,.02);
}
.inf-export-icon{
  width:32px;height:32px;margin:0 auto 8px;border-radius:6px;
  background:linear-gradient(135deg,#10b981,#059669);
  display:flex;align-items:center;justify-content:center;color:#ffffff;
}
html[data-theme="dark"] .inf-export-icon{
  background:linear-gradient(135deg,var(--green, #3DDC97),#2ea865);
}
.inf-export-name{
  font-size:13px;font-weight:600;color:#1f2937;margin:0
}
html[data-theme="dark"] .inf-export-name{color:var(--txt, #EAF1FF)}
@media(hover:hover)and(pointer:fine){
  .inf-export-btn:hover{border-color:#10b981;transform:translateY(-2px);box-shadow:0 4px 12px rgba(16,185,129,.2)}
  html[data-theme="dark"] .inf-export-btn:hover{border-color:var(--green, #3DDC97);box-shadow:0 4px 12px rgba(61,220,151,.3)}
}

@media(max-width:640px){
  .inf-modal-overlay{padding:12px}
  .inf-modal{max-width:100%;max-height:95vh}
  .inf-form-grid{grid-template-columns:1fr}
  .inf-modal-footer{flex-direction:column}
  .inf-modal-btn{width:100%;justify-content:center}
  .inf-editor-toolbar{flex-wrap:nowrap;overflow-x:auto}
  .inf-variables-list{grid-template-columns:1fr}
  .inf-templates-grid{grid-template-columns:1fr}
  .inf-images-grid{grid-template-columns:repeat(auto-fill,60px,1fr)}
  .inf-export-options{grid-template-columns:1fr}
}
@media(max-width:980px){
  .inf-toolbar{grid-template-columns:1fr 1fr}
  .inf-search{grid-column:1 / -1}
  .inf-filter-panel{grid-template-columns:1fr 1fr}
  .inf-table-wrap{overflow-x:auto}
  .inf-table{min-width:830px}
}
@media(max-width:560px){
  .inf-panel-head{padding:22px 18px}
  .inf-toolbar{grid-template-columns:1fr;gap:10px}
  .inf-filter-panel{grid-template-columns:1fr}
  .inf-footer{align-items:flex-start;flex-direction:column}
}
</style>
@endpush

@section('content')
@php
$informes = [
  ['ini'=>'MG','paciente'=>'Maria Gonzales','folio'=>'FOL-2024-0001','nss'=>'1234 5678 9101 1122','fecha'=>'08/05/2024','hora'=>'10:30AM','estado'=>'Completado','class'=>'done'],
  ['ini'=>'FC','paciente'=>'Fernando Carrillo','folio'=>'FOL-2024-0001','nss'=>'1234 5678 9101 1122','fecha'=>'09/05/2024','hora'=>'11:30AM','estado'=>'En espera','class'=>'wait','avatar'=>'green'],
  ['ini'=>'MG','paciente'=>'Maria Gonzales','folio'=>'FOL-2024-0001','nss'=>'1234 5678 9101 1122','fecha'=>'08/05/2024','hora'=>'10:30AM','estado'=>'Completado','class'=>'done'],
  ['ini'=>'MG','paciente'=>'Maria Gonzales','folio'=>'FOL-2024-0001','nss'=>'1234 5678 9101 1122','fecha'=>'08/05/2024','hora'=>'10:30AM','estado'=>'Cancelado','class'=>'cancel'],
  ['ini'=>'MG','paciente'=>'Maria Gonzales','folio'=>'FOL-2024-0001','nss'=>'1234 5678 9101 1122','fecha'=>'08/05/2024','hora'=>'10:30AM','estado'=>'Completado','class'=>'done'],
  ['ini'=>'MG','paciente'=>'Maria Gonzales','folio'=>'FOL-2024-0001','nss'=>'1234 5678 9101 1122','fecha'=>'08/05/2024','hora'=>'10:30AM','estado'=>'Completado','class'=>'done'],
  ['ini'=>'MG','paciente'=>'Maria Gonzales','folio'=>'FOL-2024-0001','nss'=>'1234 5678 9101 1122','fecha'=>'08/05/2024','hora'=>'10:30AM','estado'=>'Completado','class'=>'done'],
  ['ini'=>'MG','paciente'=>'Maria Gonzales','folio'=>'FOL-2024-0001','nss'=>'1234 5678 9101 1122','fecha'=>'08/05/2024','hora'=>'10:30AM','estado'=>'Completado','class'=>'done'],
];
@endphp

<section class="inf-shell rise d2">
  <div class="inf-panel-head">
    <h2 class="inf-title">Informe de pacientes</h2>
    <p class="inf-sub">Consulta, edita y gestiona los informes generados para tus pacientes</p>
  </div>

  <div class="inf-toolbar">
    <label class="inf-search" for="infSearch">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="infSearch" placeholder="Buscar por nombre del paciente, folio o NSS...">
    </label>
    <button class="inf-action" id="infFilterBtn" type="button" data-tooltip="Mostrar opciones para filtrar informes">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      Filtros
    </button>
    <a href="{{ route('informes.nuevo') }}" class="inf-action primary" data-tooltip="Crear un nuevo informe de paciente">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nuevo informe
    </a>
  </div>

  <!-- Panel lateral de filtros -->
  <div class="inf-fil-overlay" id="infFilOverlay"></div>
  <aside class="inf-fil-panel" id="infFilPanel" aria-label="Panel de filtros">
    <!-- Cabecera -->
    <div class="inf-fil-head">
      <span class="inf-fil-title">Filtros</span>
      <button class="inf-fil-close" id="infFilClose" aria-label="Cerrar filtros">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="inf-fil-body">
      <!-- Buscar paciente -->
      <div class="inf-fil-group">
        <label class="inf-fil-label">Buscar</label>
        <div class="inf-fil-select-wrap">
          <select class="inf-fil-select" id="infFilPaciente">
            <option value="">Buscar Pacientes</option>
            <option value="Maria Gonzales">Maria Gonzales</option>
            <option value="Fernando Carrillo">Fernando Carrillo</option>
            <option value="Ana Ramirez">Ana Ramirez</option>
            <option value="Pedro Torres">Pedro Torres</option>
            <option value="Luis Mendoza">Luis Mendoza</option>
            <option value="Carla Ortiz">Carla Ortiz</option>
          </select>
          <svg class="inf-fil-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>

      <!-- Tipo de estudio -->
      <div class="inf-fil-group">
        <label class="inf-fil-label">Tipo de estudio</label>
        <div class="inf-fil-select-wrap">
          <select class="inf-fil-select" id="infFilEstudio">
            <option value="">Todos los estudios</option>
            <option value="EDG Diagnostico">EDG Diagnóstico</option>
            <option value="Colonoscopia">Colonoscopia</option>
            <option value="Gastroscopia">Gastroscopia</option>
            <option value="Biopsia">Biopsia</option>
          </select>
          <svg class="inf-fil-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>

      <!-- Fecha del informe -->
      <div class="inf-fil-group">
        <label class="inf-fil-label">Fecha del informe</label>
        <div class="inf-fil-date-row">
          <div class="inf-fil-date-wrap">
            <input class="inf-fil-date" type="date" id="infFilDesde" title="Desde">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </div>
          <span class="inf-fil-date-sep">Hasta</span>
          <div class="inf-fil-date-wrap">
            <input class="inf-fil-date" type="date" id="infFilHasta" title="Hasta">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </div>
        </div>
      </div>

      <!-- Estado del informe -->
      <div class="inf-fil-group">
        <label class="inf-fil-label">Estado del informe</label>
        <div class="inf-fil-select-wrap">
          <select class="inf-fil-select" id="infFilEstado">
            <option value="">Todos los estados</option>
            <option value="Completado">Completado</option>
            <option value="En espera">En espera</option>
            <option value="Cancelado">Cancelado</option>
          </select>
          <svg class="inf-fil-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>

      <!-- Médico -->
      <div class="inf-fil-group">
        <label class="inf-fil-label">Médico</label>
        <div class="inf-fil-select-wrap">
          <select class="inf-fil-select" id="infFilMedico">
            <option value="">Todos los médicos</option>
            <option value="Dr. Victor Morales">Dr. Victor Morales</option>
            <option value="Dr. Alejandro Ruiz">Dr. Alejandro Ruiz</option>
            <option value="Dra. Maria Silva">Dra. Maria Silva</option>
          </select>
          <svg class="inf-fil-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>

    </div>

    <!-- Acciones -->
    <div class="inf-fil-footer">
      <button class="inf-fil-btn-clear" id="infFilClear">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
        Limpiar filtros
      </button>
    </div>

  </aside>

  <div class="inf-table-wrap">
    <table class="inf-table">
      <thead>
        <tr>
          <th>Nombre del paciente</th>
          <th>Numero folio</th>
          <th>NSS</th>
          <th>Fecha del informe</th>
          <th>Estado</th>
          <th style="text-align:right">Acciones</th>
        </tr>
      </thead>
      <tbody id="infRows">
        @foreach($informes as $inf)
        <tr data-search="{{ strtolower($inf['paciente'].' '.$inf['folio'].' '.$inf['nss'].' '.$inf['estado']) }}" data-state="{{ strtolower($inf['estado']) }}">
          <td>
            <div class="inf-patient">
              <span class="inf-avatar {{ $inf['avatar'] ?? '' }}">{{ $inf['ini'] }}</span>
              <span>{{ $inf['paciente'] }}</span>
            </div>
          </td>
          <td>{{ $inf['folio'] }}</td>
          <td>{{ $inf['nss'] }}</td>
          <td>
            <span class="inf-date">
              <strong>{{ $inf['fecha'] }}</strong>
              <span>{{ $inf['hora'] }}</span>
            </span>
          </td>
          <td><span class="inf-status {{ $inf['class'] }}">{{ $inf['estado'] }}</span></td>
          <td>
            <div class="inf-actions">
              <button class="inf-row-btn" type="button" data-tooltip="Ver informe">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Ver
              </button>
              <button class="inf-row-btn" type="button" data-tooltip="Editar informe">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar
              </button>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="inf-empty" id="infEmpty">No se encontraron informes con esos datos.</div>
  </div>

  <div class="inf-footer">
    <span class="inf-count" id="infCount">Mostrando 1 a 8 de 24 informes</span>
    <div class="inf-pages">
      <button class="inf-page" type="button" aria-label="Pagina anterior">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      </button>
      <button class="inf-page active" type="button">1</button>
      <button class="inf-page" type="button">2</button>
      <button class="inf-page" type="button">3</button>
      <button class="inf-page" type="button">4</button>
      <button class="inf-page" type="button" aria-label="Pagina siguiente">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </button>
    </div>
  </div>
</section>

<!-- Modal Nuevo Informe -->
<div class="inf-modal-overlay" id="infModalOverlay">
  <div class="inf-modal">
    <!-- Cabecera -->
    <div class="inf-modal-head">
      <h3 class="inf-modal-title">Nuevo Informe Médico</h3>
      <button class="inf-modal-close" id="infModalClose" aria-label="Cerrar modal">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <!-- Cuerpo del formulario -->
    <div class="inf-modal-body">
      <!-- Datos del Paciente -->
      <div class="inf-form-section">
        <h4 class="inf-form-section-title">Datos del Paciente</h4>
        <div class="inf-form-grid">
          <div class="inf-form-group">
            <label class="inf-form-label">Nombre del paciente *</label>
            <input type="text" class="inf-form-input" placeholder="Ej: Maria Gonzales" required>
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label">NSS *</label>
            <input type="text" class="inf-form-input" placeholder="1234 5678 9101 1122" required>
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label">Edad</label>
            <input type="number" class="inf-form-input" placeholder="35" min="0" max="150">
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label">Género</label>
            <select class="inf-form-select">
              <option value="">Seleccionar</option>
              <option value="F">Femenino</option>
              <option value="M">Masculino</option>
              <option value="O">Otro</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Datos del Estudio -->
      <div class="inf-form-section">
        <h4 class="inf-form-section-title">Datos del Estudio</h4>
        <div class="inf-form-grid">
          <div class="inf-form-group">
            <label class="inf-form-label">Tipo de estudio *</label>
            <select class="inf-form-select" id="tipoEstudio" required>
              <option value="">Seleccionar</option>
              <option value="endoscopia">Endoscopia Digestiva Alta</option>
              <option value="colonoscopia">Colonoscopia</option>
              <option value="cpre">CPRE (Colangiopancreatografía Retrógrada)</option>
              <option value="enteroscopia">Enteroscopia</option>
              <option value="ecoendoscopia">Ecoendoscopia</option>
              <option value="gastroscopia">Gastroscopia</option>
              <option value="biopsia">Biopsia</option>
              <option value="ultrasonido">Ultrasonido Endoscópico</option>
              <option value="otros">Otros procedimientos</option>
            </select>
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label">Fecha del estudio *</label>
            <input type="date" class="inf-form-input" required>
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label">Hora del estudio</label>
            <input type="time" class="inf-form-input">
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label">Médico tratante *</label>
            <select class="inf-form-select" required>
              <option value="">Seleccionar</option>
              <option value="Dr. Victor Morales">Dr. Victor Morales</option>
              <option value="Dr. Alejandro Ruiz">Dr. Alejandro Ruiz</option>
              <option value="Dra. Maria Silva">Dra. Maria Silva</option>
              <option value="Dr. Carlos Lopez">Dr. Carlos Lopez</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Editor de Contenido Médico -->
      <div class="inf-form-section">
        <h4 class="inf-form-section-title">Editor de Contenido Médico</h4>
        
        <!-- Variables Automáticas -->
        <div class="inf-variables-panel" style="margin-bottom:16px">
          <div class="inf-variables-header">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span class="inf-variables-title">Variables Automáticas</span>
          </div>
          <div class="inf-variables-list">
            <div class="inf-variable-item" data-variable="@{{paciente.nombre}}">Nombre del Paciente</div>
            <div class="inf-variable-item" data-variable="@{{paciente.edad}}">Edad</div>
            <div class="inf-variable-item" data-variable="@{{paciente.nss}}">NSS</div>
            <div class="inf-variable-item" data-variable="@{{estudio.tipo}}">Tipo de Estudio</div>
            <div class="inf-variable-item" data-variable="@{{estudio.fecha}}">Fecha</div>
            <div class="inf-variable-item" data-variable="@{{medico.nombre}}">Médico Tratante</div>
            <div class="inf-variable-item" data-variable="@{{estudio.hora}}">Hora del Estudio</div>
            <div class="inf-variable-item" data-variable="@{{estudio.folio}}">Número de Folio</div>
          </div>
        </div>

        <!-- Plantillas Médicas -->
        <div style="margin-bottom:16px">
          <label class="inf-form-label" style="margin-bottom:8px;display:block">Plantillas Médicas Predefinidas</label>
          <div class="inf-templates-grid">
            <div class="inf-template-card" data-template="endoscopia-normal">
              <div class="inf-template-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11H3v2h6v-2zm0-4H3v2h6V7zm0 8H3v2h6v-2zm12-8h-6v2h6V7zm0 4h-6v2h6v-2zm0 4h-6v2h6v-2z"/></svg>
              </div>
              <h5 class="inf-template-name">Endoscopia Normal</h5>
              <p class="inf-template-desc">Plantilla estándar para resultados normales</p>
            </div>
            <div class="inf-template-card" data-template="gastritis">
              <div class="inf-template-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/></svg>
              </div>
              <h5 class="inf-template-name">Gastritis</h5>
              <p class="inf-template-desc">Hallazgos inflamatorios gástricos</p>
            </div>
            <div class="inf-template-card" data-template="ulcera">
              <div class="inf-template-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
              </div>
              <h5 class="inf-template-name">Úlcera Péptica</h5>
              <p class="inf-template-desc">Lesiones ulcerosas detectadas</p>
            </div>
            <div class="inf-template-card" data-template="polipos">
              <div class="inf-template-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
              </div>
              <h5 class="inf-template-name">Pólipos</h5>
              <p class="inf-template-desc">Formación de pólipos detectados</p>
            </div>
          </div>
        </div>

        <!-- Editor de Texto Rico -->
        <div class="inf-form-group full">
          <label class="inf-form-label">Hallazgos y Diagnóstico (Editor de Texto)</label>
          <div class="inf-editor-toolbar">
            <button class="inf-editor-btn" data-command="bold" title="Negrita">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg>
            </button>
            <button class="inf-editor-btn" data-command="italic" title="Cursiva">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
            </button>
            <button class="inf-editor-btn" data-command="underline" title="Subrayado">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg>
            </button>
            <div class="inf-editor-separator"></div>
            <button class="inf-editor-btn" data-command="insertUnorderedList" title="Lista desordenada">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </button>
            <button class="inf-editor-btn" data-command="insertOrderedList" title="Lista ordenada">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>
            </button>
            <div class="inf-editor-separator"></div>
            <button class="inf-editor-btn" data-command="justifyLeft" title="Alinear izquierda">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
            </button>
            <button class="inf-editor-btn" data-command="justifyCenter" title="Centrar">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="10" x2="6" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="18" y1="18" x2="6" y2="18"/></svg>
            </button>
            <button class="inf-editor-btn" data-command="justifyRight" title="Alinear derecha">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
            </button>
          </div>
          <div class="inf-editor-content" contenteditable="true" id="editorContenido">
            <p><strong>Paciente:</strong> @{{paciente.nombre}} - <strong>Edad:</strong> @{{paciente.edad}} años</p>
            <p><strong>Tipo de estudio:</strong> @{{estudio.tipo}} - <strong>Fecha:</strong> @{{estudio.fecha}}</p>
            <p><strong>Hallazgos principales:</strong></p>
            <p>Describir los hallazgos observados durante el procedimiento...</p>
            <p><strong>Impresión diagnóstica:</strong></p>
            <p>Diagnóstico basado en los hallazgos endoscópicos...</p>
            <p><strong>Recomendaciones:</strong></p>
            <p>Plan de seguimiento y tratamiento recomendado...</p>
          </div>
        </div>
      </div>

      <!-- Gestión de Imágenes y Videos -->
      <div class="inf-form-section">
        <h4 class="inf-form-section-title">Imágenes y Videos del Estudio</h4>
        <div class="inf-images-grid">
          <div class="inf-image-item inf-image-add">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <span>Agregar imagen</span>
          </div>
          <div class="inf-image-item">
            <img src="https://picsum.photos/seed/endoscopia1/80/80.jpg" alt="Imagen endoscópica">
          </div>
          <div class="inf-image-item">
            <img src="https://picsum.photos/seed/endoscopia2/80/80.jpg" alt="Imagen endoscópica">
          </div>
          <div class="inf-image-item">
            <img src="https://picsum.photos/seed/endoscopia3/80/80.jpg" alt="Imagen endoscópica">
          </div>
        </div>
      </div>

      <!-- Vista Previa del Informe -->
      <div class="inf-form-section">
        <h4 class="inf-form-section-title">Vista Previa del Informe</h4>
        <div class="inf-preview-container">
          <div class="inf-preview-header">
            <h3 class="inf-preview-title">Informe Médico - @{{estudio.folio}}</h3>
          </div>
          <div class="inf-preview-content" id="vistaPrevia">
            <div id="previewContent">
              <!-- El contenido se actualizará en tiempo real -->
            </div>
          </div>
        </div>
      </div>

      <!-- Información Adicional -->
      <div class="inf-form-section">
        <h4 class="inf-form-section-title">Información Adicional</h4>
        <div class="inf-form-grid">
          <div class="inf-form-group">
            <label class="inf-form-label">Número de folio</label>
            <input type="text" class="inf-form-input" placeholder="FOL-2024-0001" readonly>
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label">Estado del informe</label>
            <select class="inf-form-select">
              <option value="borrador">Borrador</option>
              <option value="revision">En revisión</option>
              <option value="completado">Completado</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer del modal -->
    <div class="inf-modal-footer">
      <button class="inf-modal-btn cancel" id="infModalCancel">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        Cancelar
      </button>
      <button class="inf-modal-btn submit" type="submit">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Guardar Informe
      </button>
    </div>
  </div>
</div>

<!-- Modal de Opciones de Exportación -->
<div class="inf-modal-overlay" id="exportModalOverlay">
  <div class="inf-modal" style="max-width:500px">
    <div class="inf-modal-head">
      <h3 class="inf-modal-title">Exportar Informe</h3>
      <button class="inf-modal-close" id="exportModalClose" aria-label="Cerrar modal">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="inf-modal-body">
      <div class="inf-export-options">
        <div class="inf-export-btn" data-export="pdf">
          <div class="inf-export-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          </div>
          <h5 class="inf-export-name">Exportar PDF</h5>
        </div>
        <div class="inf-export-btn" data-export="print">
          <div class="inf-export-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
          </div>
          <h5 class="inf-export-name">Imprimir</h5>
        </div>
        <div class="inf-export-btn" data-export="email">
          <div class="inf-export-icon" style="background:linear-gradient(135deg,#3b82f6,#2563eb)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <h5 class="inf-export-name">Enviar Email</h5>
        </div>
        <div class="inf-export-btn" data-export="cloud">
          <div class="inf-export-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg>
          </div>
          <h5 class="inf-export-name">Guardar en Nube</h5>
        </div>
        <div class="inf-export-btn" data-export="expediente">
          <div class="inf-export-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          </div>
          <h5 class="inf-export-name">Expediente Digital</h5>
        </div>
        <div class="inf-export-btn" data-export="share">
          <div class="inf-export-icon" style="background:linear-gradient(135deg,#10b981,#059669)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
          </div>
          <h5 class="inf-export-name">Compartir</h5>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
  const search = document.getElementById('infSearch');
  const estado = document.getElementById('infEstado');
  const rows = Array.from(document.querySelectorAll('#infRows tr'));
  const empty = document.getElementById('infEmpty');
  const count = document.getElementById('infCount');
  const filterPanel = document.getElementById('infFilterPanel');

  function applyFilters(){
    const q = search.value.trim().toLowerCase();
    const paciente = document.getElementById('infFilPaciente').value.toLowerCase();
    const estudio = document.getElementById('infFilEstudio').value.toLowerCase();
    const desde = document.getElementById('infFilDesde').value;
    const hasta = document.getElementById('infFilHasta').value;
    const selectedState = document.getElementById('infFilEstado').value.toLowerCase();
    const medico = document.getElementById('infFilMedico').value.toLowerCase();
    let visible = 0;

    rows.forEach(row => {
      const matchesText = !q || row.dataset.search.includes(q);
      const matchesPaciente = !paciente || row.dataset.search.includes(paciente);
      const matchesEstudio = !estudio || row.dataset.search.includes(estudio);
      const matchesState = !selectedState || row.dataset.state === selectedState;
      const matchesMedico = !medico || row.dataset.search.includes(medico);
      
      // Validar fechas
      let matchesFecha = true;
      if(desde || hasta){
        const rowDateText = row.querySelector('.inf-date strong')?.textContent || '';
        const [day, month, year] = rowDateText.split('/');
        const rowDate = new Date(`${year}-${month}-${day}`);
        
        if(desde && rowDate < new Date(desde)) matchesFecha = false;
        if(hasta && rowDate > new Date(hasta)) matchesFecha = false;
      }
      
      const show = matchesText && matchesPaciente && matchesEstudio && matchesState && matchesMedico && matchesFecha;
      row.classList.toggle('hide', !show);
      if(show) visible++;
    });

    empty.classList.toggle('show', visible === 0);
    count.textContent = visible
      ? 'Mostrando 1 a ' + visible + ' de 24 informes'
      : 'No hay informes para mostrar';
  }

  /* Panel de filtros lateral */
  const filPanel = document.getElementById('infFilPanel');
  const filOverlay = document.getElementById('infFilOverlay');

  function abrirFiltros(){
    filPanel.classList.add('open');
    filOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function cerrarFiltros(){
    filPanel.classList.remove('open');
    filOverlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  document.getElementById('infFilterBtn').addEventListener('click', abrirFiltros);
  document.getElementById('infFilClose').addEventListener('click', cerrarFiltros);
  filOverlay.addEventListener('click', cerrarFiltros);
  document.addEventListener('keydown', e => { if(e.key === 'Escape') cerrarFiltros(); });

  /* Filtros del panel */
  document.getElementById('infFilPaciente').addEventListener('change', function(){
    search.value = this.options[this.selectedIndex].text !== 'Buscar Pacientes'
      ? this.options[this.selectedIndex].text
      : '';
    applyFilters();
  });
  document.getElementById('infFilEstudio').addEventListener('change', applyFilters);
  document.getElementById('infFilDesde').addEventListener('change', applyFilters);
  document.getElementById('infFilHasta').addEventListener('change', applyFilters);
  document.getElementById('infFilEstado').addEventListener('change', applyFilters);
  document.getElementById('infFilMedico').addEventListener('change', applyFilters);

  /* Limpiar filtros */
  document.getElementById('infFilClear').addEventListener('click', function(){
    search.value = '';
    document.getElementById('infFilPaciente').value = '';
    document.getElementById('infFilEstudio').value = '';
    document.getElementById('infFilDesde').value = '';
    document.getElementById('infFilHasta').value = '';
    document.getElementById('infFilEstado').value = '';
    document.getElementById('infFilMedico').value = '';
    applyFilters();
  });
  search.addEventListener('input', applyFilters);
  estado.addEventListener('change', applyFilters);

  /* Modal Nuevo Informe */
  const modalOverlay = document.getElementById('infModalOverlay');
  const modalClose = document.getElementById('infModalClose');
  const modalCancel = document.getElementById('infModalCancel');
  const nuevoInformeBtn = document.querySelector('.inf-action.primary');

  function abrirModal(){
    modalOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
    // Generar número de folio automático
    const folioInput = document.querySelector('.inf-form-input[readonly]');
    const fecha = new Date();
    const año = fecha.getFullYear();
    const random = Math.floor(Math.random() * 9999).toString().padStart(4, '0');
    folioInput.value = `FOL-${año}-${random}`;
  }

  function cerrarModal(){
    modalOverlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  // Conectar botón Nuevo informe
  nuevoInformeBtn.addEventListener('click', abrirModal);
  modalClose.addEventListener('click', cerrarModal);
  modalCancel.addEventListener('click', cerrarModal);
  modalOverlay.addEventListener('click', function(e){
    if(e.target === modalOverlay) cerrarModal();
  });
  document.addEventListener('keydown', e => {
    if(e.key === 'Escape' && modalOverlay.classList.contains('open')) cerrarModal();
  });

  // Editor de texto rico
  const editor = document.getElementById('editorContenido');
  const editorButtons = document.querySelectorAll('.inf-editor-btn');
  
  editorButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const command = this.dataset.command;
      document.execCommand(command, false, null);
      editor.focus();
      
      // Actualizar estado activo
      if(['bold', 'italic', 'underline'].includes(command)) {
        this.classList.toggle('active', document.queryCommandState(command));
      }
    });
  });
  
  // Variables automáticas
  document.querySelectorAll('.inf-variable-item').forEach(item => {
    item.addEventListener('click', function() {
      const variable = this.dataset.variable;
      document.execCommand('insertText', false, variable);
      editor.focus();
      actualizarVistaPrevia();
    });
  });
  
  // Plantillas médicas
  const plantillas = {
    'endoscopia-normal': {
      contenido: `<p><strong>Paciente:</strong> @{{paciente.nombre}} - <strong>Edad:</strong> @{{paciente.edad}} años</p>
      <p><strong>Estudio:</strong> Endoscopia Digestiva Alta</p>
      <p><strong>Hallazgos:</strong></p>
      <p>Esófago: Mucosa de aspecto normal, peristalsis conservada.</p>
      <p>Estómago: Mucosa gástrica de coloración rosada, sin lesiones evidentes. Píloro permeable.</p>
      <p>Duodeno: Ampolla de Vater y papila duodenal de aspecto normal.</p>
      <p><strong>Impresión diagnóstica:</strong> Endoscopia digestiva alta normal.</p>
      <p><strong>Recomendaciones:</strong> Continuar con controles médicos periódicos.</p>`
    },
    'gastritis': {
      contenido: `<p><strong>Paciente:</strong> @{{paciente.nombre}} - <strong>Edad:</strong> @{{paciente.edad}} años</p>
      <p><strong>Estudio:</strong> Endoscopia Digestiva Alta</p>
      <p><strong>Hallazgos:</strong></p>
      <p>Estómago: Eritema y edema difuso de la mucosa antral con múltiples erosiones superficiales. Hallazgos compatibles con gastritis crónica activa.</p>
      <p><strong>Impresión diagnóstica:</strong> Gastritis crónica activa.</p>
      <p><strong>Recomendaciones:</strong> Tratamiento con inhibidores de bomba de protones y dieta blanda. Control endoscópico en 6 meses.</p>`
    },
    'ulcera': {
      contenido: `<p><strong>Paciente:</strong> @{{paciente.nombre}} - <strong>Edad:</strong> @{{paciente.edad}} años</p>
      <p><strong>Estudio:</strong> Endoscopia Digestiva Alta</p>
      <p><strong>Hallazgos:</strong></p>
      <p>Estómago: Úlcera gástrica de aproximadamente 1.5 cm de diámetro en curvatura menor, con bordes definidos y base fibrinosa. No se observan signos de sangrado activo.</p>
      <p><strong>Impresión diagnóstica:</strong> Úlcera gástrica benigna.</p>
      <p><strong>Recomendaciones:</strong> Biopsia de bordes ulcerosos. Tratamiento médico intensivo. Control endoscópico en 8 semanas.</p>`
    },
    'polipos': {
      contenido: `<p><strong>Paciente:</strong> @{{paciente.nombre}} - <strong>Edad:</strong> @{{paciente.edad}} años</p>
      <p><strong>Estudio:</strong> Endoscopia Digestiva Alta</p>
      <p><strong>Hallazgos:</strong></p>
      <p>Estómago: Múltiples pólipos hiperplásicos de 3-8 mm en cuerpo gástrico. Se realiza polipectomía con asa fría de 2 pólipos mayores.</p>
      <p><strong>Impresión diagnóstica:</strong> Pólipos gástricos hiperplásicos.</p>
      <p><strong>Recomendaciones:</strong> Estudio histopatológico. Control endoscópico en 12 meses.</p>`
    }
  };
  
  document.querySelectorAll('.inf-template-card').forEach(card => {
    card.addEventListener('click', function() {
      const template = this.dataset.template;
      if(plantillas[template]) {
        editor.innerHTML = plantillas[template].contenido;
        actualizarVistaPrevia();
      }
    });
  });
  
  // Vista previa en tiempo real
  function actualizarVistaPrevia() {
    const previewContent = document.getElementById('previewContent');
    const pacienteNombre = document.querySelector('.inf-form-input[placeholder="Ej: Maria Gonzales"]').value || 'Nombre del paciente';
    const pacienteEdad = document.querySelector('.inf-form-input[placeholder="35"]').value || 'XX';
    const estudioTipo = document.getElementById('tipoEstudio').options[document.getElementById('tipoEstudio').selectedIndex]?.text || 'Tipo de estudio';
    const estudioFecha = document.querySelector('.inf-form-input[type="date"]').value || new Date().toLocaleDateString('es-MX');
    const folio = document.querySelector('.inf-form-input[readonly]').value || 'FOL-2024-XXXX';
    
    let contenido = editor.innerHTML;
    contenido = contenido.replace(/@{{paciente.nombre}}/g, pacienteNombre);
    contenido = contenido.replace(/@{{paciente.edad}}/g, pacienteEdad);
    contenido = contenido.replace(/@{{estudio.tipo}}/g, estudioTipo);
    contenido = contenido.replace(/@{{estudio.fecha}}/g, estudioFecha);
    contenido = contenido.replace(/@{{estudio.folio}}/g, folio);
    contenido = contenido.replace(/@{{medico.nombre}}/g, document.querySelector('.inf-form-select').value || 'Médico tratante');
    contenido = contenido.replace(/@{{estudio.hora}}/g, document.querySelector('.inf-form-input[type="time"]').value || 'HH:MM');
    
    previewContent.innerHTML = contenido;
    
    // Actualizar título
    document.querySelector('.inf-preview-title').textContent = `Informe Médico - ${folio}`;
  }
  
  editor.addEventListener('input', actualizarVistaPrevia);
  
  // Actualizar vista previa cuando cambian los campos
  document.querySelectorAll('.inf-form-input, .inf-form-select').forEach(field => {
    field.addEventListener('change', actualizarVistaPrevia);
  });
  
  // Modal de exportación
  const exportModalOverlay = document.getElementById('exportModalOverlay');
  const exportModalClose = document.getElementById('exportModalClose');
  
  function abrirExportModal() {
    exportModalOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  
  function cerrarExportModal() {
    exportModalOverlay.classList.remove('open');
    document.body.style.overflow = '';
  }
  
  // Botón de exportación en el footer principal
  const exportBtn = document.createElement('button');
  exportBtn.className = 'inf-modal-btn';
  exportBtn.style.cssText = 'background:#10b981;border:none;color:#ffffff';
  exportBtn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Exportar';
  document.querySelector('.inf-modal-footer').insertBefore(exportBtn, document.querySelector('.inf-modal-btn.submit'));
  
  exportBtn.addEventListener('click', function(e) {
    e.preventDefault();
    abrirExportModal();
  });
  
  exportModalClose.addEventListener('click', cerrarExportModal);
  exportModalOverlay.addEventListener('click', function(e) {
    if(e.target === exportModalOverlay) cerrarExportModal();
  });
  
  // Opciones de exportación
  document.querySelectorAll('.inf-export-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const exportType = this.dataset.export;
      const folio = document.querySelector('.inf-form-input[readonly]').value;
      
      switch(exportType) {
        case 'pdf':
          alert(`Generando PDF del informe ${folio}...`);
          break;
        case 'print':
          window.print();
          break;
        case 'email':
          alert(`Preparando envío por email del informe ${folio}...`);
          break;
        case 'cloud':
          alert(`Guardando informe ${folio} en la nube de ENCLAII...`);
          break;
        case 'expediente':
          alert(`Integrando informe ${folio} al expediente digital del paciente...`);
          break;
        case 'share':
          alert(`Generando enlace seguro para compartir informe ${folio}...`);
          break;
      }
      
      cerrarExportModal();
    });
  });
  
  // Manejar envío del formulario
  document.querySelector('.inf-modal-btn.submit').addEventListener('click', function(e){
    e.preventDefault();
    
    // Validar campos requeridos
    const requiredFields = document.querySelectorAll('.inf-form-input[required], .inf-form-select[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
      if(!field.value.trim()){
        field.style.borderColor = '#ef4444';
        isValid = false;
      } else {
        field.style.borderColor = '';
      }
    });
    
    if(!isValid){
      alert('Por favor completa todos los campos requeridos (*)');
      return;
    }
    
    // Simular guardado
    this.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Guardando...';
    this.disabled = true;
    
    setTimeout(() => {
      alert('¡Informe guardado correctamente!');
      cerrarModal();
      
      // Resetear formulario
      document.querySelectorAll('.inf-form-input, .inf-form-select, .inf-form-textarea').forEach(field => {
        field.value = '';
        field.style.borderColor = '';
      });
      editor.innerHTML = '<p><strong>Paciente:</strong> @{{paciente.nombre}} - <strong>Edad:</strong> @{{paciente.edad}} años</p><p><strong>Tipo de estudio:</strong> @{{estudio.tipo}} - <strong>Fecha:</strong> @{{estudio.fecha}}</p><p><strong>Hallazgos principales:</strong></p><p>Describir los hallazgos observados durante el procedimiento...</p><p><strong>Impresión diagnóstica:</strong></p><p>Diagnóstico basado en los hallazgos endoscópicos...</p><p><strong>Recomendaciones:</strong></p><p>Plan de seguimiento y tratamiento recomendado...</p>';
      
      this.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Guardar Informe';
      this.disabled = false;
    }, 1500);
  });
  
  // Inicializar vista previa
  setTimeout(actualizarVistaPrevia, 100);

  document.querySelectorAll('.inf-page').forEach(page => {
    page.addEventListener('click', function(){
      if(this.querySelector('svg')) return;
      document.querySelectorAll('.inf-page').forEach(p => p.classList.remove('active'));
      this.classList.add('active');
    });
  });
})();
</script>
@endpush
