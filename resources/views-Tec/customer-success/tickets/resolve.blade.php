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
.rv-checkbox{display:flex;align-items:center;gap:12px;cursor:pointer;font-size:14px;color:var(--rv-text);padding:14px 18px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:12px;transition:all 150ms}
.rv-checkbox:hover{border-color:var(--rv-blue)}
.rv-checkbox input{display:none}
.rv-checkbox .box{width:22px;height:22px;border-radius:7px;border:2px solid #334155;display:grid;place-items:center;transition:all 150ms;flex-shrink:0}
.rv-checkbox input:checked+.box{background:var(--rv-blue);border-color:var(--rv-blue);box-shadow:0 0 12px rgba(59,130,246,.3)}
.rv-checkbox input:checked+.box::after{content:'\2713';color:#fff;font-size:13px;font-weight:700}
.rv-file-input{display:flex;align-items:center;gap:10px;margin-top:14px}
.rv-file-input input{display:none}
.rv-file-label{display:inline-flex;align-items:center;gap:8px;padding:12px 18px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:12px;cursor:pointer;transition:all 150ms;font-weight:600;font-size:13px;color:var(--rv-text-soft)}
.rv-file-label:hover{border-color:var(--rv-blue);color:var(--rv-blue)}
.rv-file-name{font-size:13px;color:var(--rv-text);font-weight:600}
.rv-footer{padding:0 32px 32px;display:flex;justify-content:flex-end;gap:14px}
.rv-btn{display:inline-flex;align-items:center;gap:10px;padding:14px 28px;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;transition:all 150ms;border:1px solid transparent;text-decoration:none}
.rv-btn-cancel{background:var(--rv-panel-2);border-color:var(--rv-border);color:var(--rv-text-soft)}
.rv-btn-cancel:hover{border-color:#475569;color:var(--rv-text)}
.rv-btn-submit{background:linear-gradient(135deg,var(--rv-green),#16a34a);color:#fff;border:none;box-shadow:0 4px 24px rgba(34,197,94,.3)}
.rv-btn-submit:hover{filter:brightness(1.1);transform:translateY(-1px)}
.rv-btn-submit:disabled{opacity:.5;cursor:not-allowed;filter:none;transform:none}
.rv-error{font-size:13px;color:var(--rv-red);margin-bottom:16px;padding:12px 16px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:12px;display:none}
.rv-msg-block{margin-top:16px}
.rv-resolved-state{padding:40px 32px;text-align:center}
.rv-resolved-state-icon{width:70px;height:70px;border-radius:50%;background:rgba(34,197,94,.12);display:grid;place-items:center;margin:0 auto 18px;box-shadow:0 0 30px rgba(34,197,94,.2)}
.rv-resolved-state-icon svg{color:var(--rv-green);width:36px;height:36px}
.rv-resolved-state h2{font-size:20px;font-weight:800;color:var(--rv-text);margin:0 0 8px}
.rv-resolved-state p{font-size:14px;color:var(--rv-text-soft);margin:0 0 24px}
.rv-resolved-state .rv-btn{justify-content:center}

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
      <button type="button" class="rv-header-close" id="btnSalirResolver" title="Salir">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        <span>Salir</span>
      </button>
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
          <input type="hidden" name="resolution_type" id="resolutionTypeInput" value="{{ $ticket->resolution_type }}" required>
        </div>

        <hr class="rv-sep">

        <label class="rv-label">Resumen de la solución</label>
        <textarea name="resolution_summary" class="rv-textarea" placeholder="Describe qué hiciste para resolver el problema..." required>{{ $ticket->resolution_summary }}</textarea>


        <hr class="rv-sep">

        <label class="rv-file-input">
          <input type="file" name="evidence" accept="image/*,.pdf,.doc,.docx" id="evidenceInput">
          <span class="rv-file-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
            Adjuntar evidencia
          </span>
          <span class="rv-file-name" id="evidenceFileName">{{ $ticket->evidence_path ? basename($ticket->evidence_path) : '' }}</span>
        </label>
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
@endsection

@push('scripts')
<script>
(function(){
  var csrfToken = "{{ csrf_token() }}";
  var resolveUrl = "{{ route('customer-success.tickets.resolve', $ticket) }}";
  var showUrl = "{{ route('customer-success.tickets.show', $ticket) }}";

  var form = document.getElementById('resolveForm');
  var btnSubmit = document.getElementById('btnSubmit');
  var btnSalir = document.getElementById('btnSalirResolver');
  var errorEl = document.getElementById('resolveError');
  var evidenceInput = document.getElementById('evidenceInput');
  var fileNameEl = document.getElementById('evidenceFileName');

  // Toggle ticket info expand
  var ticketInfoEl = document.getElementById('ticketInfoToggle');
  if(ticketInfoEl){
    ticketInfoEl.addEventListener('click', function(){
      ticketInfoEl.classList.toggle('expanded');
    });
  }

  // Close/exit button behavior
  if(btnSalir){
    btnSalir.addEventListener('click', function(e){
      e.stopPropagation();
      if(window.opener){
        window.close();
      } else {
        window.location.href = "{{ route('customer-success.tickets') }}";
      }
    });
  }

  // Resolution type dropdown
  var resDropdown = document.getElementById('resolutionTypeDropdown');
  var resTrigger = document.getElementById('resolutionTypeTrigger');
  var resMenu = document.getElementById('resolutionTypeMenu');
  var resInput = document.getElementById('resolutionTypeInput');

  if(resTrigger && resMenu && resInput){
    if(resInput.value){
      var selectedItem = resMenu.querySelector('[data-value="' + resInput.value + '"]');
      if(selectedItem){
        selectedItem.classList.add('active');
        resTrigger.querySelector('span').textContent = selectedItem.textContent.trim();
        resTrigger.classList.add('selected');
      }
    }

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
      });
    });

    document.addEventListener('click', function(){ 
      document.querySelectorAll('.rv-dropdown.open').forEach(function(d){ d.classList.remove('open'); }); 
    });
  }


  evidenceInput.addEventListener('change', function(){
    fileNameEl.textContent = this.files.length ? this.files[0].name : '';
  });

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
})();
</script>
@endpush
