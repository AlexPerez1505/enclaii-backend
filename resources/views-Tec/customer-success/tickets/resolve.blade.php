@extends('layouts.app')

@section('title', 'Resolver Ticket #'.$ticket->operation_folio)
@section('active', 'customer-success-tickets')
@section('header-title', 'Resolver Ticket')
@section('header-sub', $ticket->subject)

@section('sidebar')
  @include('customer-success.partials.sidebar')
@endsection

@section('bottom-nav')
  @include('customer-success.partials.bottom-nav')
@endsection

@push('styles')
<style>
.rv-page{--rv-bg:#060b14;--rv-panel:#0c1222;--rv-panel-2:#0f1629;--rv-border:#1e293b;--rv-border-soft:#253047;--rv-text:#e2e8f0;--rv-text-soft:#94a3b8;--rv-blue:#3b82f6;--rv-green:#22c55e;--rv-red:#ef4444;--rv-amber:#f59e0b;--rv-radius:18px}
.rv-page{max-width:860px;margin:0 auto}
.rv-back{display:inline-flex;align-items:center;gap:8px;font-size:13px;color:var(--rv-text-soft);text-decoration:none;margin-bottom:22px;padding:8px 14px;border-radius:10px;border:1px solid var(--rv-border);background:var(--rv-panel-2);transition:all 150ms}
.rv-back:hover{border-color:var(--rv-blue);color:var(--rv-blue)}
.rv-attachment-link{display:inline-grid;place-items:center;width:36px;height:36px;border-radius:10px;background:var(--rv-panel);border:1px solid var(--rv-border);color:var(--rv-text-soft);text-decoration:none;transition:all 150ms}
.rv-attachment-link:hover{border-color:var(--rv-blue);color:var(--rv-blue)}
.rv-lightbox-img{max-width:90vw;max-height:90vh;object-fit:contain;border-radius:12px;box-shadow:0 24px 60px rgba(0,0,0,.5)}
.rv-card{background:var(--rv-panel);border:1px solid var(--rv-border);border-radius:var(--rv-radius);box-shadow:0 10px 40px rgba(0,0,0,.3);position:relative;overflow:hidden}
.rv-card::before{content:'';position:absolute;inset:0;border-radius:inherit;padding:1px;background:linear-gradient(135deg,rgba(34,197,94,.3),rgba(59,130,246,.15),transparent 50%);-webkit-mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude;pointer-events:none}
.rv-header{padding:32px 32px 0;display:flex;align-items:center;gap:16px;position:relative}
.rv-header-icon{width:50px;height:50px;border-radius:50%;background:rgba(34,197,94,.12);display:grid;place-items:center;box-shadow:0 0 30px rgba(34,197,94,.2)}
.rv-header-icon svg{color:var(--rv-green)}
.rv-header h1{font-size:22px;font-weight:800;color:var(--rv-text);margin:0;flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.rv-header-close{flex-shrink:0;padding:8px 14px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:10px;color:var(--rv-text-soft);cursor:pointer;display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;transition:all 150ms;box-shadow:0 4px 12px rgba(0,0,0,.25);white-space:nowrap}
.rv-header-close:hover{border-color:var(--rv-red);color:var(--rv-red);background:rgba(239,68,68,.16);box-shadow:0 4px 16px rgba(239,68,68,.15)}
html[data-theme="light"] .rv-header-close{background:#f1f5f9;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .rv-header-close:hover{border-color:#ef4444;color:#ef4444;background:#fee2e2}
.rv-sub{padding:10px 32px 0;font-size:14px;color:var(--rv-text-soft)}
.rv-ticket-info{margin:20px 32px 0;padding:16px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:14px;cursor:pointer;transition:all 150ms}
.rv-ticket-info:hover{border-color:var(--rv-blue)}
.rv-ticket-info-header{display:flex;align-items:center;gap:16px}
.rv-ticket-info-icon{width:40px;height:40px;border-radius:10px;background:rgba(59,130,246,.12);display:grid;place-items:center;color:var(--rv-blue);flex-shrink:0}
.rv-ticket-info-text{flex:1;min-width:0}
.rv-ticket-info-title{font-size:14px;font-weight:700;color:var(--rv-text);display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px}
.rv-ticket-info-meta{font-size:12px;color:var(--rv-text-soft)}
.rv-ticket-info.expanded .rv-ticket-info-title{white-space:normal;overflow:visible;word-break:break-word}
.rv-ticket-info-toggle{flex-shrink:0;color:var(--rv-text-soft);transition:transform .2s}
.rv-ticket-info.expanded .rv-ticket-info-toggle{transform:rotate(180deg)}
.rv-ticket-info-details{display:none;margin-top:16px;padding-top:16px;border-top:1px solid var(--rv-border)}
.rv-ticket-info.expanded .rv-ticket-info-details{display:block}
.rv-ticket-detail-row{margin-bottom:12px}
.rv-ticket-detail-row:last-child{margin-bottom:0}
.rv-ticket-detail-label{font-size:11px;font-weight:700;color:var(--rv-text-soft);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px}
.rv-ticket-detail-value{font-size:13px;color:var(--rv-text);line-height:1.5}
.rv-ticket-detail-value pre{white-space:pre-wrap;word-break:break-word;font-family:inherit;margin:0;font-size:13px;line-height:1.5}
.rv-ticket-category-badge{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:rgba(59,130,246,.12);border-radius:8px;font-size:12px;font-weight:600;color:var(--rv-blue)}
.rv-body{padding:32px}
.rv-sep{border:0;border-top:1px solid var(--rv-border);margin:24px 0}
.rv-label{font-size:13px;font-weight:700;color:var(--rv-text);margin-bottom:12px;display:block;letter-spacing:.02em}
.rv-radio-group{display:flex;gap:14px;flex-wrap:wrap}
.rv-radio{display:flex;align-items:center;gap:10px;cursor:pointer;padding:12px 20px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:12px;transition:all 150ms;flex:1;min-width:140px}
.rv-radio:hover{border-color:var(--rv-blue)}
.rv-radio input{display:none}
.rv-radio .circle{width:22px;height:22px;border-radius:50%;border:2px solid #334155;display:grid;place-items:center;transition:all 150ms;flex-shrink:0}
.rv-radio input:checked+.circle{border-color:var(--rv-green);background:rgba(34,197,94,.1)}
.rv-radio input:checked+.circle::after{content:'';width:10px;height:10px;border-radius:50%;background:var(--rv-green)}
.rv-radio input:checked ~ .rv-radio-lbl{color:var(--rv-green)}
.rv-radio-lbl{font-size:14px;color:var(--rv-text-soft);font-weight:600;transition:color 150ms}
.rv-select{width:100%;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:12px;padding:14px 16px;color:var(--rv-text);font-size:14px;outline:none;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:40px}
.rv-select:focus{border-color:var(--rv-blue);box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.rv-dropdown{position:relative;width:100%}
.rv-dropdown-trigger{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 16px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:12px;color:var(--rv-text-soft);font-size:14px;font-weight:500;cursor:pointer;transition:all 150ms;text-align:left}
.rv-dropdown-trigger:hover,.rv-dropdown.open .rv-dropdown-trigger{border-color:var(--rv-blue);color:var(--rv-text);box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.rv-dropdown-trigger.selected{color:var(--rv-text);font-weight:600}
.rv-dropdown-trigger svg{transition:transform .2s;flex-shrink:0}
.rv-dropdown.open .rv-dropdown-trigger svg{transform:rotate(180deg)}
.rv-dropdown-menu{display:none;position:absolute;top:calc(100% + 8px);left:0;right:0;background:var(--rv-panel);border:1px solid var(--rv-border);border-radius:14px;padding:8px;box-shadow:0 16px 40px rgba(0,0,0,.4);z-index:100;max-height:280px;overflow-y:auto}
.rv-dropdown.open .rv-dropdown-menu{display:block;animation:rvFadeIn .15s ease}
@keyframes rvFadeIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
.rv-dropdown-item{display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:10px;font-size:14px;font-weight:500;color:var(--rv-text-soft);cursor:pointer;transition:all .12s}
.rv-dropdown-item:hover{background:rgba(59,130,246,.1);color:var(--rv-text)}
.rv-dropdown-item.active{background:rgba(59,130,246,.15);color:var(--rv-blue);font-weight:700}
.rv-dropdown-item svg{flex-shrink:0;opacity:.6}
.rv-dropdown-item:hover svg,.rv-dropdown-item.active svg{opacity:1}
.rv-textarea{width:100%;min-height:110px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:12px;padding:14px 16px;color:var(--rv-text);font-size:14px;outline:none;resize:vertical;font-family:inherit;box-sizing:border-box;line-height:1.6}
.rv-textarea::placeholder{color:#475569}
.rv-textarea:focus{border-color:var(--rv-blue);box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.rv-resolution-other{width:100%;margin-top:14px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:12px;padding:14px 16px;color:var(--rv-text);font-size:14px;outline:none;transition:border-color 150ms;box-sizing:border-box}
.rv-resolution-other::placeholder{color:var(--rv-text-soft)}
.rv-resolution-other:focus{border-color:var(--rv-blue);box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.rv-checkbox{display:flex;align-items:center;gap:12px;cursor:pointer;font-size:14px;color:var(--rv-text);padding:14px 18px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:12px;transition:all 150ms}
.rv-checkbox:hover{border-color:var(--rv-blue)}
.rv-checkbox input{display:none}
.rv-checkbox .box{width:22px;height:22px;border-radius:7px;border:2px solid #334155;display:grid;place-items:center;transition:all 150ms;flex-shrink:0}
.rv-checkbox input:checked+.box{background:var(--rv-blue);border-color:var(--rv-blue);box-shadow:0 0 12px rgba(59,130,246,.3)}
.rv-checkbox input:checked+.box::after{content:'\2713';color:#fff;font-size:13px;font-weight:700}
.rv-file-input{display:flex;align-items:center;flex-wrap:wrap;gap:10px;margin-top:14px}
.rv-file-input input{display:none}
.rv-file-label{display:inline-flex;align-items:center;gap:8px;padding:12px 18px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:12px;cursor:pointer;transition:all 150ms;font-weight:600;font-size:13px;color:var(--rv-text-soft)}
.rv-file-label:hover{border-color:var(--rv-blue);color:var(--rv-blue)}
.rv-file-name{font-size:13px;color:var(--rv-text);font-weight:600;display:inline-flex;align-items:center;gap:6px;flex-wrap:wrap;max-width:100%}
.rv-file-thumb{width:40px;height:40px;object-fit:cover;border-radius:6px;display:block}
.rv-thumb-wrap{position:relative;display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px}
.rv-thumb-remove{position:absolute;top:-5px;right:-5px;width:16px;height:16px;border-radius:50%;background:var(--rv-red);color:#fff;border:1px solid var(--rv-panel);cursor:pointer;display:inline-grid;place-items:center;font-size:10px;line-height:1;padding:0;box-shadow:0 2px 6px rgba(0,0,0,.35);z-index:2}
.rv-thumb-remove:hover{background:#dc2626}
.rv-modal-card .rv-evidence-preview{display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:12px;margin-top:14px}
.rv-modal-card .rv-evidence-img{width:100%;aspect-ratio:1/1;object-fit:contain;display:block;border-radius:10px}
.rv-remove-file{display:inline-grid;place-items:center;width:30px;height:30px;border-radius:8px;background:var(--rv-panel);border:1px solid var(--rv-border);color:var(--rv-text-soft);cursor:pointer;margin-left:6px;transition:all 150ms;box-sizing:border-box}
.rv-remove-file:hover{border-color:var(--rv-red);color:var(--rv-red)}
.rv-footer{padding:0 32px 32px;display:flex;justify-content:flex-end;gap:14px}
.rv-btn{display:inline-flex;align-items:center;gap:10px;padding:14px 28px;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;transition:all 150ms;border:1px solid transparent;text-decoration:none}
.rv-btn-cancel{background:var(--rv-panel-2);border-color:var(--rv-border);color:var(--rv-text-soft)}
.rv-btn-cancel:hover{border-color:#475569;color:var(--rv-text)}
.rv-btn-submit{background:linear-gradient(135deg,var(--rv-green),#16a34a);color:#fff;border:none;box-shadow:0 4px 24px rgba(34,197,94,.3)}
.rv-btn-submit:hover{filter:brightness(1.1);transform:translateY(-1px)}
.rv-btn-submit:disabled{opacity:.5;cursor:not-allowed;filter:none;transform:none}
.rv-error{font-size:13px;color:var(--rv-red);margin-bottom:16px;padding:12px 16px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:12px;display:none}
.rv-notify-info{display:flex;align-items:flex-start;gap:10px;margin:4px 0 0 34px;padding:12px 14px;background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-radius:10px;font-size:13px;color:var(--rv-green);line-height:1.5}
.rv-notify-info svg{flex-shrink:0;color:var(--rv-green);margin-top:1px}
html[data-theme="light"] .rv-notify-info{background:#f0fdf4;border-color:#bbf7d0;color:#15803d}
#emailInfo{background:rgba(245,158,11,.12);border-color:rgba(245,158,11,.2);color:var(--rv-amber)}
#emailInfo svg{color:var(--rv-amber)}
html[data-theme="light"] #emailInfo{background:#fffbeb;border-color:#fde68a;color:#b45309}
html[data-theme="light"] #emailInfo svg{color:#b45309}
.rv-msg-block{margin-top:16px}
.rv-resolved-state{padding:40px 32px;text-align:center}
.rv-resolved-state-icon{width:70px;height:70px;border-radius:50%;background:rgba(34,197,94,.12);display:grid;place-items:center;margin:0 auto 18px;box-shadow:0 0 30px rgba(34,197,94,.2)}
.rv-resolved-state-icon svg{color:var(--rv-green);width:36px;height:36px}
.rv-resolved-state h2{font-size:20px;font-weight:800;color:var(--rv-text);margin:0 0 8px}
.rv-resolved-state p{font-size:14px;color:var(--rv-text-soft);margin:0 0 24px}
.rv-resolved-state .rv-btn{justify-content:center}

/* ===== PREVIEW BUTTON ===== */
.rv-preview-btn-wrap{padding:0 32px 24px}
.rv-preview-btn{display:inline-flex;align-items:center;gap:10px;padding:12px 20px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:12px;cursor:pointer;transition:all 150ms;font-size:14px;font-weight:600;color:var(--rv-text-soft);font-family:inherit}
.rv-preview-btn:hover{border-color:var(--rv-blue);color:var(--rv-blue);box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.rv-preview-btn svg{flex-shrink:0}
html[data-theme="light"] .rv-preview-btn{background:#f8fafc;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .rv-preview-btn:hover{border-color:#3b82f6;color:#3b82f6}
@media(max-width:640px){.rv-preview-btn-wrap{padding:0 18px 20px}.rv-preview-btn{width:100%;justify-content:center}}

/* ===== PREVIEW MODAL ===== */
.rv-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);z-index:9999;display:none;align-items:center;justify-content:center;padding:20px;animation:rvOverlayIn .2s ease}
.rv-modal-overlay.open{display:flex}
@keyframes rvOverlayIn{from{opacity:0}to{opacity:1}}
.rv-modal-card{--tk-bg:#060b14;--tk-panel:#0f1629;--tk-panel-2:#131b32;--tk-border:#1e293b;--tk-border-soft:#253047;--tk-text:#e2e8f0;--tk-text-soft:#94a3b8;--tk-blue:#3b82f6;--tk-green:#22c55e;--tk-amber:#f59e0b;--tk-radius:18px;max-width:620px;width:100%;max-height:90vh;overflow-y:auto;animation:rvModalIn .25s ease;position:relative;background:var(--tk-panel);border:1px solid var(--tk-border);border-radius:var(--tk-radius);box-shadow:0 24px 60px rgba(0,0,0,.5)}
@keyframes rvModalIn{from{opacity:0;transform:translateY(20px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}
.rv-modal-card::before{content:'';position:absolute;left:0;top:0;width:3px;height:100%;background:linear-gradient(180deg,var(--tk-green),transparent);border-radius:var(--tk-radius) 0 0 var(--tk-radius)}
.rv-modal-card .tk-card-header{padding:24px 26px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--tk-border-soft)}
.rv-modal-card .tk-card-icon{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,rgba(34,197,94,.25),rgba(22,163,74,.12));display:grid;place-items:center;color:var(--tk-green);flex-shrink:0}
.rv-modal-card .tk-card-title{font-size:17px;font-weight:700;color:var(--tk-text);margin:0}
.rv-modal-card .tk-card-body{padding:26px}
.rv-modal-card .tk-field{margin-bottom:22px}
.rv-modal-card .tk-field:last-child{margin-bottom:0}
.rv-modal-card .tk-field-label{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--tk-text-soft);margin-bottom:8px;text-transform:uppercase;letter-spacing:.03em}
.rv-modal-card .tk-field-label svg{color:var(--tk-green);width:16px;height:16px}
.rv-modal-card .tk-field-value{font-size:15px;color:var(--tk-text);line-height:1.5;word-break:break-word;overflow-wrap:break-word}
.rv-modal-card .tk-field-value.empty{color:var(--tk-text-soft);font-style:italic}
.rv-modal-card .tk-field-value pre{white-space:pre-wrap;word-break:break-word;overflow-wrap:break-word;font-family:inherit;margin:0;font-size:15px;line-height:1.5}
.rv-modal-card .tk-estado{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:99px;font-size:12px;font-weight:700;background:rgba(34,197,94,.15);color:#4ade80}
.rv-modal-card .tk-estado::before{content:'';width:7px;height:7px;border-radius:50%;background:currentColor}
.rv-modal-card .tk-attachment{display:flex;align-items:center;gap:14px;padding:16px;background:var(--tk-panel-2);border:1px solid var(--tk-border);border-radius:14px}
.rv-modal-card .tk-attachment-icon{width:44px;height:44px;border-radius:10px;background:#dc2626;display:grid;place-items:center;color:#fff;flex-shrink:0}
.rv-modal-card .tk-attachment-info{flex:1;min-width:0}
.rv-modal-card .tk-attachment-name{font-size:14px;font-weight:600;color:var(--tk-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.rv-modal-card .tk-attachment-meta{font-size:12px;color:var(--tk-text-soft);margin-top:2px}
.rv-modal-card .tk-attachment-actions{display:flex;gap:8px}
#previewEvidenceField .tk-attachment{background:transparent;border:0;padding:0}
#previewEvidenceField .rv-evidence-preview{border:0;background:transparent;max-width:none;width:100%;grid-template-columns:repeat(auto-fit,minmax(min(120px,100%),1fr))}
.rv-modal-card .tk-icon-btn{width:36px;height:36px;border-radius:10px;background:var(--tk-panel);border:1px solid var(--tk-border);color:var(--tk-text-soft);display:grid;place-items:center;cursor:pointer;transition:all 150ms;text-decoration:none}
.rv-modal-card .tk-icon-btn:hover{border-color:var(--tk-blue);color:var(--tk-blue)}
html[data-theme="light"] .rv-modal-card{--tk-bg:#f8fafc;--tk-panel:#ffffff;--tk-panel-2:#f1f5f9;--tk-border:#e2e8f0;--tk-border-soft:#e2e8f0;--tk-text:#0f172a;--tk-text-soft:#64748b;--tk-shadow:0 4px 16px rgba(15,23,42,.06);background:var(--tk-panel);border-color:var(--tk-border);box-shadow:0 24px 60px rgba(15,23,42,.2)}
html[data-theme="light"] .rv-modal-card::before{background:linear-gradient(180deg,rgba(34,197,94,.3),transparent)}
html[data-theme="light"] .rv-modal-card .tk-card-icon{background:rgba(34,197,94,.1)}
html[data-theme="light"] .rv-modal-card .tk-field-value.empty{color:#94a3b8}
html[data-theme="light"] .rv-modal-card .tk-estado{background:rgba(5,150,105,.1);color:#047857}
html[data-theme="light"] .rv-modal-card .tk-attachment{background:#f8fafc;border-color:#e2e8f0}
html[data-theme="light"] .rv-modal-card .tk-icon-btn{background:#fff;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .rv-modal-card .tk-icon-btn:hover{border-color:#3b82f6;color:#3b82f6}
@media(max-width:640px){
  .rv-modal-card .tk-card-header{padding:18px}
  .rv-modal-card .tk-card-body{padding:18px}
}

/* ===== TEMA CLARO ===== */
html[data-theme="light"] .rv-page{--rv-bg:#f8fafc;--rv-panel:#ffffff;--rv-panel-2:#f1f5f9;--rv-border:#e2e8f0;--rv-border-soft:#e2e8f0;--rv-text:#0f172a;--rv-text-soft:#64748b}
html[data-theme="light"] .rv-card{box-shadow:0 4px 16px rgba(15,23,42,.06)}
html[data-theme="light"] .rv-card::before{background:linear-gradient(135deg,rgba(34,197,94,.2),rgba(59,130,246,.1),transparent 50%)}
html[data-theme="light"] .rv-header-icon{background:rgba(34,197,94,.08);box-shadow:none}
html[data-theme="light"] .rv-radio .circle{border-color:#cbd5e1}
html[data-theme="light"] .rv-select{background-color:#f1f5f9;border-color:#e2e8f0;color:#0f172a}
html[data-theme="light"] .rv-select option{background:#fff;color:#0f172a}
html[data-theme="light"] .rv-textarea{background:#f8fafc;border-color:#e2e8f0;color:#0f172a}
html[data-theme="light"] .rv-textarea::placeholder{color:#94a3b8}
html[data-theme="light"] .rv-checkbox{background:#f8fafc;border-color:#e2e8f0}
html[data-theme="light"] .rv-checkbox .box{border-color:#cbd5e1}
html[data-theme="light"] .rv-file-label{background:#f8fafc;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .rv-back{background:#fff;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .rv-back:hover{border-color:#3b82f6;color:#3b82f6}
html[data-theme="light"] .rv-btn-cancel{background:#f8fafc;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .rv-error{background:rgba(239,68,68,.06);border-color:rgba(239,68,68,.15)}
html[data-theme="light"] .rv-ticket-info{background:#f8fafc;border-color:#e2e8f0}
html[data-theme="light"] .rv-ticket-info-icon{background:rgba(59,130,246,.08)}
html[data-theme="light"] .rv-modal-overlay{background:rgba(15,23,42,.45)}
html[data-theme="light"] .rv-modal-card{background:#ffffff;border-color:#e2e8f0;box-shadow:0 24px 60px rgba(15,23,42,.2)}
html[data-theme="light"] .rv-modal-card .rv-header-icon{background:rgba(34,197,94,.08);box-shadow:none}
html[data-theme="light"] .rv-modal-card .rv-header-close{background:#f1f5f9;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .rv-modal-card .rv-header-close:hover{border-color:#ef4444;color:#ef4444;background:#fee2e2}
html[data-theme="light"] .rv-modal-card .rv-preview-value.empty{color:#94a3b8}
html[data-theme="light"] .rv-modal-card .rv-preview-sep{border-color:#e2e8f0}

@media(max-width:640px){
  .rv-page{max-width:100%}
  .rv-back{margin-bottom:16px}
  .rv-header{padding:20px 18px 0;gap:12px}
  .rv-header-icon{width:42px;height:42px}
  .rv-header h1{font-size:19px}
  .rv-header-close{padding:8px}
  .rv-header-close span{display:none}
  .rv-sub{padding:8px 18px 0}
  .rv-ticket-info{margin:16px 18px 0;padding:14px}
  .rv-body{padding:20px 18px}
  .rv-footer{padding:0 18px 20px;flex-direction:column-reverse}
  .rv-footer .rv-btn{width:100%;justify-content:center;min-height:46px;box-sizing:border-box}
  .rv-radio-group{flex-direction:column}
  .rv-dropdown-menu{max-height:220px}
  .rv-file-input{align-items:flex-start;flex-wrap:wrap}
  .rv-file-name{max-width:100%;overflow-wrap:anywhere}
}
</style>
@endpush

@section('content')
<div class="rv-page">

  <a href="{{ route('customer-success.tickets.show', $ticket) }}" class="rv-back">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    Volver al ticket
  </a>

  <div class="rv-card">
    <div class="rv-header">
      <div class="rv-header-icon">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      </div>
      <h1>Resolver ticket</h1>
    </div>
    <div class="rv-sub">Completa los datos para registrar la resolución.</div>

    <div class="rv-ticket-info" id="ticketInfoToggle">
      <div class="rv-ticket-info-header">
        <div class="rv-ticket-info-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="rv-ticket-info-text">
          <div class="rv-ticket-info-title">{{ $ticket->subject }}</div>
          <div class="rv-ticket-info-meta">{{ $ticket->operation_folio }}</div>
        </div>
        <svg class="rv-ticket-info-toggle" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </div>
      <div class="rv-ticket-info-details">
        <div class="rv-ticket-detail-row">
          <div class="rv-ticket-detail-label">Usuario</div>
          <div class="rv-ticket-detail-value">{{ $ticket->user?->name }} {{ $ticket->user?->apellido_paterno }}</div>
        </div>
        <div class="rv-ticket-detail-row">
          <div class="rv-ticket-detail-label">Categoría</div>
          <div class="rv-ticket-detail-value">
            <span class="rv-ticket-category-badge">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              {{ ucfirst($ticket->category) }}
            </span>
          </div>
        </div>
        <div class="rv-ticket-detail-row">
          <div class="rv-ticket-detail-label">Descripción</div>
          <div class="rv-ticket-detail-value"><pre>{{ $ticket->description }}</pre></div>
        </div>
        @if($ticket->business_name)
        <div class="rv-ticket-detail-row">
          <div class="rv-ticket-detail-label">Negocio</div>
          <div class="rv-ticket-detail-value">{{ $ticket->business_name }}</div>
        </div>
        @endif
        @if($ticket->attachment_path)
        @php($attachmentExt = strtolower(pathinfo($ticket->attachment_path, PATHINFO_EXTENSION)))
        @php($isAttachmentImage = in_array($attachmentExt, ['jpg','jpeg','png','webp','gif','bmp','svg']))
        <div class="rv-ticket-detail-row">
          <div class="rv-ticket-detail-label">{{ $isAttachmentImage ? 'Foto adjunta' : 'Archivo adjunto' }}</div>
          <div class="rv-ticket-detail-value" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
            @if($isAttachmentImage)
            <a href="{{ asset('storage/'.$ticket->attachment_path) }}" class="rv-attachment-link open-attachment-lightbox" title="Ver foto">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
            </a>
            @else
            <a href="{{ asset('storage/'.$ticket->attachment_path) }}" target="_blank" class="rv-attachment-link" title="Ver archivo">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
            </a>
            @endif
          </div>
        </div>
        @endif
      </div>
    </div>

    @if(in_array($ticket->status, ['resuelto', 'cerrado']) && $ticket->resolved_at)
    <div class="rv-resolved-state">
      <div class="rv-resolved-state-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      </div>
      <h2>Ticket resuelto</h2>
      <p>Este ticket ya fue marcado como resuelto. No es necesario realizar más acciones.</p>
      <a href="{{ route('customer-success.tickets') }}" class="rv-btn rv-btn-submit">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l-6-6 6-6"/><path d="M3 12h18"/></svg>
        Salir a Tickets
      </a>
    </div>
    @else
    <form id="resolveForm" enctype="multipart/form-data">
      <div class="rv-body">
        <div id="resolveError" class="rv-error"></div>
        <input type="hidden" name="status" value="resuelto">

        <label class="rv-label">Tipo de solución</label>
        <div class="rv-dropdown" id="resolutionTypeDropdown">
          <button type="button" class="rv-dropdown-trigger" id="resolutionTypeTrigger">
            <span>Selecciona una opción</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div class="rv-dropdown-menu" id="resolutionTypeMenu">
            <div class="rv-dropdown-item" data-value="problema_corregido">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
              Problema corregido
            </div>
            <div class="rv-dropdown-item" data-value="configuracion_realizada">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
              Configuración realizada
            </div>
            <div class="rv-dropdown-item" data-value="error_usuario">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
              Error del usuario
            </div>
            <div class="rv-dropdown-item" data-value="capacitacion">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
              Capacitación
            </div>
            <div class="rv-dropdown-item" data-value="incidencia_externa">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
              Incidencia externa
            </div>
            <div class="rv-dropdown-item" data-value="otro">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
              Otro
            </div>
          </div>
          @php($knownResolutionTypes = ['problema_corregido','configuracion_realizada','error_usuario','capacitacion','incidencia_externa','otro'])
          @php($storedResolutionType = $ticket->resolution_type)
          @php($isResolutionKnown = in_array($storedResolutionType, $knownResolutionTypes))
          <input type="hidden" name="resolution_type" id="resolutionTypeInput" value="{{ $isResolutionKnown ? $storedResolutionType : 'otro' }}" required>
          <input type="text" name="resolution_type_other" id="resolutionTypeOther" class="rv-resolution-other" value="{{ $isResolutionKnown ? '' : $storedResolutionType }}" placeholder="Especifica el tipo de solución..." style="{{ ($isResolutionKnown || !$storedResolutionType) ? 'display:none' : 'display:block' }}">
        </div>

        <hr class="rv-sep">

        <label class="rv-label">Resumen de la solución</label>
        <textarea name="resolution_summary" class="rv-textarea" placeholder="Describe qué hiciste para resolver el problema..." required>{{ $ticket->resolution_summary }}</textarea>


        <hr class="rv-sep">

        <label class="rv-file-input">
          <input type="file" name="evidence[]" accept="image/*,.pdf,.doc,.docx" id="evidenceInput" multiple>
          <input type="hidden" name="remove_evidence" id="removeEvidenceInput" value="[]">
          <span class="rv-file-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
            Adjuntar evidencia
          </span>
          <span class="rv-file-name" id="evidenceFileName" data-existing="@json($ticket->evidence_paths ?? [])">
            @if(!empty($ticket->evidence_paths))
              @foreach($ticket->evidence_paths as $path)
                @php($evidenceExt = strtolower(pathinfo($path, PATHINFO_EXTENSION)))
                @if(in_array($evidenceExt, ['jpg','jpeg','png','webp','gif','bmp','svg']))
                  <img src="{{ asset('storage/'.$path) }}" alt="evidencia" class="rv-file-thumb">
                @endif
              @endforeach
            @endif
          </span>
          <button type="button" class="rv-remove-file" id="btnRemoveEvidence" title="Quitar evidencia" style="{{ !empty($ticket->evidence_paths) ? '' : 'display:none' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="M6 6l12 12"/></svg>
          </button>
        </label>

        <hr class="rv-sep">

        <label class="rv-label">Notificar al usuario</label>
        <div style="display:flex;flex-direction:column;gap:12px">
          <label class="rv-checkbox">
            <input type="checkbox" name="notify_web" value="1" id="notifyWeb">
            <span class="box"></span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--rv-blue);flex-shrink:0"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            <span>Notificación web <span style="color:var(--rv-text-soft);font-weight:400;font-size:12px">— Enviar alerta solo al usuario del ticket</span></span>
          </label>
          <label class="rv-checkbox">
            <input type="checkbox" name="notify_email" value="1" id="notifyEmail">
            <span class="box"></span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--rv-amber);flex-shrink:0"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <span>Correo electrónico <span style="color:var(--rv-text-soft);font-weight:400;font-size:12px">— Enviar email a {{ $ticket->user?->email }}</span></span>
          </label>
          <div class="rv-notify-info" id="emailInfo" style="display:none">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span>Si elijes por correo las edicciones posteriores no se veran reflejadas en correo ya entregado</span>
          </div>
        </div>
      </div>

      <div class="rv-preview-btn-wrap">
        <button type="button" class="rv-preview-btn" id="btnPreview">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
          Vista previa de la resolución
        </button>
      </div>

      <div class="rv-footer">
        <a href="{{ route('customer-success.tickets.show', $ticket) }}" class="rv-btn rv-btn-cancel">Cancelar</a>
        <button type="submit" class="rv-btn rv-btn-submit" id="btnSubmit">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          Resolver ticket
        </button>
      </div>
    </form>
    @endif
  </div>

</div>

<!-- Preview Modal -->
<div class="rv-modal-overlay" id="previewModal">
  <div class="rv-modal-card">
    <div class="tk-card-header">
      <div class="tk-card-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
      </div>
      <h2 class="tk-card-title">Vista previa de la resolución</h2>
    </div>
    <div class="tk-card-body">
      <div class="tk-field">
        <div class="tk-field-label">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Estado
        </div>
        <div class="tk-field-value"><span class="tk-estado resuelto">Resuelto</span></div>
      </div>
      <div class="tk-field">
        <div class="tk-field-label">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Resuelto por
        </div>
        <div class="tk-field-value">{{ Auth::user()?->name }} {{ Auth::user()?->apellido_paterno }}</div>
      </div>
      <div class="tk-field">
        <div class="tk-field-label">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          Fecha
        </div>
        <div class="tk-field-value" id="previewDate">{{ now()->format('d M Y') }}<br><span style="color:var(--tk-text-soft);font-size:12px">{{ now()->format('h:i A') }}</span></div>
      </div>
      <div class="tk-field">
        <div class="tk-field-label">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
          Tipo de solución
        </div>
        <div class="tk-field-value empty" id="previewType">Sin seleccionar</div>
      </div>
      <div class="tk-field">
        <div class="tk-field-label">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          Solución aplicada
        </div>
        <div class="tk-field-value"><pre id="previewSummary" style="margin:0;font-family:inherit">Escribe el resumen de la solución...</pre></div>
      </div>
      <div class="tk-field" id="previewEvidenceField" style="display:none">
        <div class="tk-field-label">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
          Evidencia adjunta
        </div>
        <div class="tk-attachment">
          <div class="tk-attachment-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          </div>
          <div class="tk-attachment-info">
            <div class="tk-attachment-name" id="previewEvidenceName"></div>
            <div class="tk-attachment-meta">Evidencia de resolución</div>
          </div>
          <div class="tk-attachment-actions">
            <span class="tk-icon-btn" title="Archivo seleccionado" style="cursor:default">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </span>
          </div>
        </div>
        <div class="rv-evidence-preview" id="previewEvidenceImgWrap" style="display:none"></div>
      </div>
      <div class="tk-field" id="previewNotifyField">
        <div class="tk-field-label">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
          Notificaciones
        </div>
        <div class="tk-field-value" id="previewNotify" style="display:flex;flex-direction:column;gap:8px">
          <span id="previewNotifyWeb" style="display:none;font-size:13px;color:var(--tk-text-soft)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;margin-right:6px;color:var(--tk-blue)"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            Notificación web
          </span>
          <span id="previewNotifyEmail" style="display:none;font-size:13px;color:var(--tk-text-soft)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;margin-right:6px;color:var(--tk-amber)"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            Correo a {{ $ticket->user?->email }}
          </span>
          <span id="previewNotifyNone" style="font-size:13px;color:var(--tk-text-soft);font-style:italic">Sin notificaciones seleccionadas</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Attachment lightbox -->
<div class="rv-modal-overlay" id="attachmentOverlay" style="padding:0">
  <img id="attachmentLightboxImg" class="rv-lightbox-img" src="" alt="Adjunto del ticket">
</div>
@endsection

@push('scripts')
<script>
(function(){
  var csrfToken = "{{ csrf_token() }}";
  var resolveUrl = "{{ route('customer-success.tickets.resolve', $ticket) }}";
  var showUrl = "{{ route('customer-success.tickets.show', $ticket) }}";

  var form = document.getElementById('resolveForm');
  var btnSubmit = document.getElementById('btnSubmit');
  var errorEl = document.getElementById('resolveError');
  var evidenceInput = document.getElementById('evidenceInput');
  var fileNameEl = document.getElementById('evidenceFileName');
  var selectedEvidenceFiles = [];
  var storageBase = "{{ asset('storage/') }}";

  // Local draft persistence
  var storageKey = 'rv_resolve_{{ $ticket->id }}';
  function getStored(key){
    try { return localStorage.getItem(storageKey + '_' + key); } catch(e){ return null; }
  }
  function setStored(key, value){
    try {
      if(value === null || value === '') localStorage.removeItem(storageKey + '_' + key);
      else localStorage.setItem(storageKey + '_' + key, value);
    } catch(e){}
  }
  function clearStored(){
    try { ['type','other','summary','evidence_name','evidence_file','evidence_files'].forEach(function(k){ localStorage.removeItem(storageKey + '_' + k); }); } catch(e){}
  }

  function storeEvidenceFiles(){
    if(!selectedEvidenceFiles.length){ setStored('evidence_files', null); return; }
    var pending = [];
    var completed = 0;
    selectedEvidenceFiles.forEach(function(file, idx){
      var reader = new FileReader();
      reader.onload = function(e){
        pending[idx] = {name: file.name, data: e.target.result};
        completed++;
        if(completed === selectedEvidenceFiles.length){
          try { setStored('evidence_files', JSON.stringify(pending)); } catch(err){}
        }
      };
      reader.readAsDataURL(file);
    });
  }

  function restoreStoredEvidence(){
    if(!evidenceInput || !fileNameEl) return;
    var storedFiles = getStored('evidence_files');
    if(!storedFiles) return;
    try {
      var items = JSON.parse(storedFiles);
      if(!items || !items.length) return;
      var files = [];
      var dt = new DataTransfer();
      items.forEach(function(item){
        var arr = item.data.split(',');
        var mimeMatch = arr[0].match(/:(.*?);/);
        if(!mimeMatch) return;
        var mime = mimeMatch[1];
        var bstr = atob(arr[1]);
        var n = bstr.length;
        var u8arr = new Uint8Array(n);
        while(n--){ u8arr[n] = bstr.charCodeAt(n); }
        var blob = new Blob([u8arr], {type: mime});
        var file = new File([blob], item.name, {type: mime, lastModified: Date.now()});
        files.push(file);
        dt.items.add(file);
      });
      evidenceInput.files = dt.files;
      selectedEvidenceFiles = files;
      if(removeEvidenceInput) removeEvidenceInput.value = '0';
      if(btnRemoveEvidence) btnRemoveEvidence.style.display = 'inline-grid';
      renderEvidenceThumbs();
    } catch(e){}
  }

  // Toggle ticket info expand
  var ticketInfoEl = document.getElementById('ticketInfoToggle');
  if(ticketInfoEl){
    ticketInfoEl.addEventListener('click', function(){
      ticketInfoEl.classList.toggle('expanded');
    });
  }

  // Resolution type dropdown
  var resDropdown = document.getElementById('resolutionTypeDropdown');
  var resTrigger = document.getElementById('resolutionTypeTrigger');
  var resMenu = document.getElementById('resolutionTypeMenu');
  var resInput = document.getElementById('resolutionTypeInput');
  var resOther = document.getElementById('resolutionTypeOther');

  function updateResOther(){
    if(resOther){
      resOther.style.display = (resInput.value === 'otro') ? 'block' : 'none';
    }
  }

  if(resTrigger && resMenu && resInput){
    var storedType = getStored('type');
    var storedOther = getStored('other');
    if(storedType !== null) resInput.value = storedType;
    if(resOther && storedOther !== null) resOther.value = storedOther;
    if(resInput.value){
      var selectedItem = resMenu.querySelector('[data-value="' + resInput.value + '"]');
      if(selectedItem){
        selectedItem.classList.add('active');
        resTrigger.querySelector('span').textContent = selectedItem.textContent.trim();
        resTrigger.classList.add('selected');
      }
    }
    updateResOther();

    resTrigger.addEventListener('click', function(e){
      e.stopPropagation();
      var isOpen = resDropdown.classList.contains('open');
      document.querySelectorAll('.rv-dropdown.open').forEach(function(d){ d.classList.remove('open'); });
      if(!isOpen) resDropdown.classList.add('open');
    });

    resMenu.querySelectorAll('.rv-dropdown-item').forEach(function(item){
      item.addEventListener('click', function(e){
        e.stopPropagation();
        resMenu.querySelectorAll('.rv-dropdown-item').forEach(function(i){ i.classList.remove('active'); });
        item.classList.add('active');
        resInput.value = item.getAttribute('data-value');
        resTrigger.querySelector('span').textContent = item.textContent.trim();
        resTrigger.classList.add('selected');
        resDropdown.classList.remove('open');
        updateResOther();
        setStored('type', resInput.value);
        if(resInput.value !== 'otro') setStored('other', null);
      });
    });

    document.addEventListener('click', function(){ 
      document.querySelectorAll('.rv-dropdown.open').forEach(function(d){ d.classList.remove('open'); }); 
    });
  }


  var btnRemoveEvidence = document.getElementById('btnRemoveEvidence');
  var removeEvidenceInput = document.getElementById('removeEvidenceInput');
  var removedExistingPaths = [];

  function renderEvidenceThumbs(){
    var existingPaths = [];
    try { existingPaths = JSON.parse(fileNameEl.dataset.existing || '[]'); } catch(e){}
    var html = '';
    existingPaths.forEach(function(p){
      var ext = p.split('.').pop().toLowerCase();
      if(['jpg','jpeg','png','webp','gif','bmp','svg'].indexOf(ext) !== -1 && removedExistingPaths.indexOf(p) === -1){
        html += '<span class="rv-thumb-wrap"><img src="'+storageBase+p+'" class="rv-file-thumb" alt="evidencia"><button type="button" class="rv-thumb-remove" data-path="'+p+'">&times;</button></span>';
      }
    });
    selectedEvidenceFiles.forEach(function(f, idx){
      if(f.type.startsWith('image/')){
        html += '<span class="rv-thumb-wrap"><img src="'+URL.createObjectURL(f)+'" class="rv-file-thumb" alt="evidencia"><button type="button" class="rv-thumb-remove" data-idx="'+idx+'">&times;</button></span>';
      }
    });
    fileNameEl.innerHTML = html;
    fileNameEl.querySelectorAll('.rv-thumb-remove').forEach(function(btn){
      btn.addEventListener('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        var path = this.getAttribute('data-path');
        var idx = this.getAttribute('data-idx');
        if(path !== null){
          if(removedExistingPaths.indexOf(path) === -1) removedExistingPaths.push(path);
        } else if(idx !== null){
          var i = parseInt(idx, 10);
          if(!isNaN(i) && i >= 0 && i < selectedEvidenceFiles.length){
            selectedEvidenceFiles.splice(i, 1);
            var dt = new DataTransfer();
            selectedEvidenceFiles.forEach(function(f){ dt.items.add(f); });
            evidenceInput.files = dt.files;
            storeEvidenceFiles();
          }
        }
        if(removeEvidenceInput) removeEvidenceInput.value = JSON.stringify(removedExistingPaths);
        renderEvidenceThumbs();
        updatePreview();
      });
    });
    var remainingExisting = existingPaths.filter(function(p){ return removedExistingPaths.indexOf(p) === -1; });
    if(removeEvidenceInput) removeEvidenceInput.value = JSON.stringify(removedExistingPaths);
    if(btnRemoveEvidence) btnRemoveEvidence.style.display = (selectedEvidenceFiles.length || remainingExisting.length) ? 'inline-grid' : 'none';
  }

  if(evidenceInput && fileNameEl){
    evidenceInput.addEventListener('change', function(){
      if(this.files.length){
        selectedEvidenceFiles = selectedEvidenceFiles.concat(Array.from(this.files));
        var dt = new DataTransfer();
        selectedEvidenceFiles.forEach(function(f){ dt.items.add(f); });
        evidenceInput.files = dt.files;
        storeEvidenceFiles();
      } else {
        selectedEvidenceFiles = [];
        setStored('evidence_files', null);
      }
      renderEvidenceThumbs();
      updatePreview();
    });
  }

  if(btnRemoveEvidence && evidenceInput && fileNameEl){
    btnRemoveEvidence.addEventListener('click', function(){
      var existingPaths = [];
      try { existingPaths = JSON.parse(fileNameEl.dataset.existing || '[]'); } catch(e){}
      selectedEvidenceFiles = [];
      evidenceInput.value = '';
      var dt = new DataTransfer();
      evidenceInput.files = dt.files;
      removedExistingPaths = existingPaths.slice();
      if(removeEvidenceInput) removeEvidenceInput.value = JSON.stringify(removedExistingPaths);
      setStored('evidence_files', null);
      renderEvidenceThumbs();
      updatePreview();
    });
  }

  // ---- Live preview ----
  var summaryTextarea = form ? form.querySelector('textarea[name="resolution_summary"]') : null;
  if(summaryTextarea){
    var storedSummary = getStored('summary');
    if(storedSummary !== null) summaryTextarea.value = storedSummary;
  }
  var previewType = document.getElementById('previewType');
  var previewSummary = document.getElementById('previewSummary');
  var previewEvidenceRow = document.getElementById('previewEvidenceRow');
  var previewEvidenceName = document.getElementById('previewEvidenceName');

  var typeLabels = {
    'problema_corregido': 'Problema corregido',
    'configuracion_realizada': 'Configuración realizada',
    'error_usuario': 'Error del usuario',
    'capacitacion': 'Capacitación',
    'incidencia_externa': 'Incidencia externa',
    'otro': 'Otro'
  };

  function updatePreview(){
    var typeVal = resInput.value;
    if(typeVal && typeLabels[typeVal]){
      var typeText = typeLabels[typeVal];
      if(typeVal === 'otro' && resOther && resOther.value.trim()){
        typeText = resOther.value.trim();
      }
      previewType.textContent = typeText;
      previewType.classList.remove('empty');
    } else {
      previewType.textContent = 'Sin seleccionar';
      previewType.classList.add('empty');
    }

    var summaryVal = summaryTextarea ? summaryTextarea.value.trim() : '';
    if(summaryVal){
      previewSummary.textContent = summaryVal;
      previewSummary.classList.remove('empty');
    } else {
      previewSummary.textContent = 'Escribe el resumen de la solución...';
      previewSummary.classList.add('empty');
    }

    var evidenceNames = [];
    var existingPaths = [];
    var imageExts = ['jpg','jpeg','png','webp','gif','bmp','svg'];
    if(fileNameEl){
      try { existingPaths = JSON.parse(fileNameEl.dataset.existing || '[]'); } catch(e){}
    }
    existingPaths.forEach(function(p){ if(removedExistingPaths.indexOf(p) === -1) evidenceNames.push(p.split('/').pop()); });
    selectedEvidenceFiles.forEach(function(f){ evidenceNames.push(f.name); });
    var previewEvidenceField = document.getElementById('previewEvidenceField');
    var previewEvidenceImgWrap = document.getElementById('previewEvidenceImgWrap');
    if(evidenceNames.length){
      previewEvidenceName.textContent = evidenceNames.length + ' archivo(s)';
      if(previewEvidenceField) previewEvidenceField.style.display = 'block';
    } else {
      if(previewEvidenceField) previewEvidenceField.style.display = 'none';
    }

    if(previewEvidenceImgWrap){
      previewEvidenceImgWrap.innerHTML = '';
      var anyImage = false;
      existingPaths.forEach(function(p){
        if(removedExistingPaths.indexOf(p) !== -1) return;
        var ext = p.split('.').pop().toLowerCase();
        if(imageExts.indexOf(ext) !== -1){
          anyImage = true;
          var img = document.createElement('img');
          img.className = 'rv-evidence-img';
          img.src = storageBase+p;
          img.alt = 'evidencia';
          previewEvidenceImgWrap.appendChild(img);
        }
      });
      selectedEvidenceFiles.forEach(function(f){
        if(f.type.startsWith('image/')){
          anyImage = true;
          var img = document.createElement('img');
          img.className = 'rv-evidence-img';
          img.src = URL.createObjectURL(f);
          img.alt = 'evidencia';
          previewEvidenceImgWrap.appendChild(img);
        }
      });
      previewEvidenceImgWrap.style.display = anyImage ? 'block' : 'none';
    }

    var notifyWeb = document.getElementById('notifyWeb');
    var notifyEmail = document.getElementById('notifyEmail');
    var previewNotifyWeb = document.getElementById('previewNotifyWeb');
    var previewNotifyEmail = document.getElementById('previewNotifyEmail');
    var previewNotifyNone = document.getElementById('previewNotifyNone');
    var webChecked = notifyWeb && notifyWeb.checked;
    var emailChecked = notifyEmail && notifyEmail.checked;
    if(previewNotifyWeb) previewNotifyWeb.style.display = webChecked ? 'block' : 'none';
    if(previewNotifyEmail) previewNotifyEmail.style.display = emailChecked ? 'block' : 'none';
    if(previewNotifyNone) previewNotifyNone.style.display = (webChecked || emailChecked) ? 'none' : 'block';
  }

  var notifyWebEl = document.getElementById('notifyWeb');
  var notifyEmailEl = document.getElementById('notifyEmail');
  var emailInfo = document.getElementById('emailInfo');

  function updateEmailInfo(){
    if(emailInfo && notifyEmailEl){
      emailInfo.style.display = notifyEmailEl.checked ? 'flex' : 'none';
    }
  }

  if(notifyWebEl) notifyWebEl.addEventListener('change', function(){ updatePreview(); updateEmailInfo(); });
  if(notifyEmailEl) notifyEmailEl.addEventListener('change', function(){ updatePreview(); updateEmailInfo(); });
  updateEmailInfo();

  if(summaryTextarea){
    summaryTextarea.addEventListener('input', function(){
      setStored('summary', summaryTextarea.value);
      updatePreview();
    });
  }
  if(resOther){
    resOther.addEventListener('input', function(){
      setStored('other', resOther.value);
      updatePreview();
    });
  }
  // Update preview when resolution type changes
  if(resMenu){
    resMenu.querySelectorAll('.rv-dropdown-item').forEach(function(item){
      item.addEventListener('click', function(){ setTimeout(updatePreview, 0); });
    });
  }
  restoreStoredEvidence();
  renderEvidenceThumbs();
  updatePreview();

  // ---- Preview modal ----
  var btnPreview = document.getElementById('btnPreview');
  var previewModal = document.getElementById('previewModal');
  var previewModalClose = document.getElementById('previewModalClose');

  if(btnPreview && previewModal){
    btnPreview.addEventListener('click', function(){
      updatePreview();
      previewModal.classList.add('open');
    });
  }
  if(previewModalClose){
    previewModalClose.addEventListener('click', function(){
      previewModal.classList.remove('open');
    });
  }
  if(previewModal){
    previewModal.addEventListener('click', function(e){
      if(e.target === previewModal) previewModal.classList.remove('open');
    });
  }
  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
      if(previewModal) previewModal.classList.remove('open');
      if(attachmentOverlay) attachmentOverlay.classList.remove('open');
    }
  });

  if(form && btnSubmit && errorEl){
  form.addEventListener('submit', function(e){
    e.preventDefault();
    errorEl.style.display = 'none';
    btnSubmit.disabled = true;
    btnSubmit.textContent = 'Resolviendo...';

    var fd = new FormData(form);
    fd.append('_token', csrfToken);

    fetch(resolveUrl, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: fd
    })
    .then(function(r){ return r.json().then(function(d){ return {ok: r.ok, data: d}; }); })
    .then(function(res){
      if(res.ok && res.data.ok){
        clearStored();
        window.location.href = showUrl;
      } else {
        var msg = 'Error al resolver.';
        if(res.data.errors){
          var errs = Object.values(res.data.errors);
          msg = errs.flat().join('. ');
        } else if(res.data.message){
          msg = res.data.message;
        }
        errorEl.textContent = msg;
        errorEl.style.display = 'block';
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg> Resolver ticket';
      }
    })
    .catch(function(){
      errorEl.textContent = 'Error de conexión.';
      errorEl.style.display = 'block';
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg> Resolver ticket';
    });
  });
  }

  // Attachment lightbox
  var attachmentOverlay = document.getElementById('attachmentOverlay');
  var attachmentLightboxImg = document.getElementById('attachmentLightboxImg');
  document.querySelectorAll('.open-attachment-lightbox').forEach(function(link){
    link.addEventListener('click', function(e){
      e.preventDefault();
      if(attachmentLightboxImg) attachmentLightboxImg.src = this.href;
      if(attachmentOverlay) attachmentOverlay.classList.add('open');
    });
  });
  if(attachmentOverlay){
    attachmentOverlay.addEventListener('click', function(e){
      if(e.target === attachmentOverlay) attachmentOverlay.classList.remove('open');
    });
  }
})();
</script>
@endpush
