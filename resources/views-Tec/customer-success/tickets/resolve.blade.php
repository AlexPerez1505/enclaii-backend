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
.rv-page{max-width:680px;margin:0 auto}
.rv-back{display:inline-flex;align-items:center;gap:8px;font-size:13px;color:var(--rv-text-soft);text-decoration:none;margin-bottom:22px;padding:8px 14px;border-radius:10px;border:1px solid var(--rv-border);background:var(--rv-panel-2);transition:all 150ms}
.rv-back:hover{border-color:var(--rv-blue);color:var(--rv-blue)}
.rv-card{background:var(--rv-panel);border:1px solid var(--rv-border);border-radius:var(--rv-radius);box-shadow:0 10px 40px rgba(0,0,0,.3);position:relative;overflow:hidden}
.rv-card::before{content:'';position:absolute;inset:0;border-radius:inherit;padding:1px;background:linear-gradient(135deg,rgba(34,197,94,.3),rgba(59,130,246,.15),transparent 50%);-webkit-mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude;pointer-events:none}
.rv-header{padding:32px 32px 0;display:flex;align-items:center;gap:16px}
.rv-header-icon{width:50px;height:50px;border-radius:50%;background:rgba(34,197,94,.12);display:grid;place-items:center;box-shadow:0 0 30px rgba(34,197,94,.2)}
.rv-header-icon svg{color:var(--rv-green)}
.rv-header h1{font-size:22px;font-weight:800;color:var(--rv-text);margin:0}
.rv-sub{padding:10px 32px 0;font-size:14px;color:var(--rv-text-soft)}
.rv-ticket-info{margin:20px 32px 0;padding:16px;background:var(--rv-panel-2);border:1px solid var(--rv-border);border-radius:14px;display:flex;align-items:center;gap:16px}
.rv-ticket-info-icon{width:40px;height:40px;border-radius:10px;background:rgba(59,130,246,.12);display:grid;place-items:center;color:var(--rv-blue);flex-shrink:0}
.rv-ticket-info-text{flex:1}
.rv-ticket-info-text strong{font-size:14px;color:var(--rv-text);display:block}
.rv-ticket-info-text span{font-size:12px;color:var(--rv-text-soft)}
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
  .rv-header{padding:24px 20px 0}
  .rv-sub{padding:8px 20px 0}
  .rv-ticket-info{margin:16px 20px 0}
  .rv-body{padding:20px}
  .rv-footer{padding:0 20px 24px}
  .rv-radio-group{flex-direction:column}
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
    <div class="rv-sub">El cliente será notificado automáticamente.</div>

    <div class="rv-ticket-info">
      <div class="rv-ticket-info-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <div class="rv-ticket-info-text">
        <strong>{{ $ticket->subject }}</strong>
        <span>{{ $ticket->operation_folio }} &bull; {{ $ticket->user?->name }} {{ $ticket->user?->apellido_paterno }}</span>
      </div>
    </div>

    <form id="resolveForm" enctype="multipart/form-data">
      <div class="rv-body">
        <div id="resolveError" class="rv-error"></div>

        <label class="rv-label">Estado final</label>
        <div class="rv-radio-group">
          <label class="rv-radio">
            <input type="radio" name="status" value="resuelto" checked>
            <div class="circle"></div>
            <span class="rv-radio-lbl">Resuelto</span>
          </label>
          <label class="rv-radio">
            <input type="radio" name="status" value="cerrado">
            <div class="circle"></div>
            <span class="rv-radio-lbl">Cerrado</span>
          </label>
        </div>

        <hr class="rv-sep">

        <label class="rv-label">Tipo de solución</label>
        <select name="resolution_type" class="rv-select" required>
          <option value="" disabled selected>Selecciona una opción</option>
          <option value="problema_corregido">Problema corregido</option>
          <option value="configuracion_realizada">Configuración realizada</option>
          <option value="error_usuario">Error del usuario</option>
          <option value="capacitacion">Capacitación</option>
          <option value="incidencia_externa">Incidencia externa</option>
          <option value="otro">Otro</option>
        </select>

        <hr class="rv-sep">

        <label class="rv-label">Resumen de la solución</label>
        <textarea name="resolution_summary" class="rv-textarea" placeholder="Describe qué hiciste para resolver el problema..." required></textarea>

        <hr class="rv-sep">

        <label class="rv-checkbox" id="sendMsgToggle">
          <input type="checkbox" name="send_message" value="1" checked>
          <div class="box"></div>
          ¿Deseas enviar un mensaje al cliente?
        </label>

        <div class="rv-msg-block" id="clientMsgBlock">
          <label class="rv-label" style="margin-top:16px">Mensaje</label>
          <textarea name="client_message" class="rv-textarea" placeholder="Hola, tu solicitud fue resuelta...">Hola, tu ticket ha sido resuelto. Si necesitas más ayuda, no dudes en contactarnos.</textarea>
        </div>

        <hr class="rv-sep">

        <label class="rv-file-input">
          <input type="file" name="evidence" accept="image/*,.pdf,.doc,.docx" id="evidenceInput">
          <span class="rv-file-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
            Adjuntar evidencia
          </span>
          <span class="rv-file-name" id="evidenceFileName"></span>
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
  var errorEl = document.getElementById('resolveError');
  var sendMsgToggle = document.querySelector('#sendMsgToggle input');
  var clientMsgBlock = document.getElementById('clientMsgBlock');
  var evidenceInput = document.getElementById('evidenceInput');
  var fileNameEl = document.getElementById('evidenceFileName');

  sendMsgToggle.addEventListener('change', function(){
    clientMsgBlock.style.display = this.checked ? '' : 'none';
  });

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
    if(!sendMsgToggle.checked){
      fd.delete('client_message');
      fd.set('send_message', '0');
    } else {
      fd.set('send_message', '1');
    }

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
