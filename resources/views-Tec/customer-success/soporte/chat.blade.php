@extends('layouts.app')
@section('title', 'Chat — '.$conversation->user?->name)
@section('active', 'customer-success-soporte')
@section('header-title', 'Chat con '.$conversation->user?->name)
@section('header-sub', $conversation->user?->email)

@section('sidebar')
  @include('customer-success.partials.sidebar')
@endsection

@push('styles')
<style>
.ag-chat-wrap{display:grid;grid-template-rows:1fr auto;height:calc(100vh - 130px);background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);overflow:hidden}
.ag-chat-messages{overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:12px}
.ag-msg-row{display:flex;flex-direction:column;max-width:75%}
.ag-msg-row.right{align-self:flex-end;align-items:flex-end}
.ag-msg-row.left{align-self:flex-start;align-items:flex-start}
.ag-msg-row.center{align-self:center;align-items:center}
.ag-chat-msg{padding:11px 14px;border-radius:14px;font-size:13px;line-height:1.5;white-space:pre-wrap;word-break:break-word;overflow-wrap:break-word;min-width:64px}
.ag-chat-msg.user{background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;min-width:120px}
.ag-chat-msg.assistant,.ag-chat-msg.bot{background:var(--panel);border:1px solid var(--stroke);color:var(--txt);min-width:120px}
.ag-chat-msg.agent{background:linear-gradient(135deg,#16a34a,#4ade80);color:#fff;min-width:120px}
.ag-chat-msg.system{font-size:11px;color:var(--txt-soft);background:transparent;border:none;padding:4px 0;font-style:italic}
.ag-chat-label{font-size:10px;margin-bottom:3px;color:var(--txt-soft)}
.ag-chat-input-bar{display:flex;gap:10px;padding:14px 16px;border-top:1px solid var(--stroke);background:var(--panel)}
.ag-chat-input-bar textarea{flex:1;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md);padding:10px 12px;font-size:13px;color:var(--txt);outline:none;resize:none;height:42px;max-height:120px;overflow-y:auto;font-family:inherit}
.ag-chat-input-bar button{padding:10px 18px;border-radius:var(--r-md);border:none;background:var(--blue);color:#fff;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap}
.ag-chat-input-bar button:disabled{opacity:.5;cursor:not-allowed}
.ag-take-banner{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:14px 20px;background:rgba(251,191,36,.08);border-bottom:1px solid rgba(251,191,36,.2)}
.ag-take-banner p{margin:0;font-size:13px;color:var(--txt)}
.ag-take-btn{padding:9px 18px;border-radius:var(--r-md);border:none;background:linear-gradient(135deg,#f59e0b,#fbbf24);color:#fff;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap}
</style>
@endpush

@section('content')
<div class="ag-chat-wrap" id="agChatWrap">

  <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-bottom:1px solid var(--stroke);background:var(--panel);gap:12px">
    <div style="display:flex;align-items:center;gap:12px">
      <a href="{{ route('customer-success.soporte') }}" style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);font-size:12px;font-weight:600;text-decoration:none;transition:background .15s,color .15s" onmouseover="this.style.color='var(--txt)';this.style.background='var(--hover-bg)'" onmouseout="this.style.color='var(--txt-soft)';this.style.background='var(--panel-2)'">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Regresar
      </a>
      <span style="font-size:12px;color:var(--txt-soft)">Conversación #{{ $conversation->id }} · {{ $conversation->user?->email }}</span>
    </div>
    @if($conversation->isWithAgent())
    <button id="btnCloseChat" type="button" style="padding:6px 14px;border-radius:var(--r-md);border:1px solid rgba(239,68,68,.4);background:transparent;color:#ef4444;font-size:12px;font-weight:600;cursor:pointer">✓ Marcar como resuelto</button>
    @endif
  </div>

  @if($conversation->isPendingAgent())
  <div class="ag-take-banner" id="takeBanner">
    <p>⏳ Este chat está esperando un agente. Tómalo para comenzar a responder.</p>
    <button class="ag-take-btn" id="btnTakeChat" type="button">Tomar chat</button>
  </div>
  @endif

  <div class="ag-chat-messages" id="agChatMessages">
    @foreach($messages as $msg)
      @php
        $side = match($msg->role) {
          'agent'  => 'right',
          'system' => 'center',
          default  => 'left',
        };
        $label = match($msg->role) {
          'user'   => $conversation->user?->name ?? 'Usuario',
          'agent'  => 'Tú (agente)',
          'system' => '',
          default  => 'Bot IA',
        };
      @endphp
      <div class="ag-msg-row {{ $side }}">
        @if($label)<div class="ag-chat-label">{{ $label }}</div>@endif
        <div class="ag-chat-msg {{ $msg->role }}">{{ $msg->content }}</div>
      </div>
    @endforeach
  </div>

  <div class="ag-chat-input-bar" id="agInputBar" @if($conversation->isPendingAgent() || $conversation->status === 'closed') style="opacity:.4;pointer-events:none" @endif>
    <textarea id="agChatInput" placeholder="Escribe tu respuesta..." rows="1" @disabled($conversation->status === 'closed')></textarea>
    <button type="button" id="btnAgSend" @disabled($conversation->status === 'closed')>Enviar</button>
  </div>

</div>
@endsection

@push('scripts')
<script>
(function(){
  var convId = {{ $conversation->id }};
  var isPending = {{ $conversation->isPendingAgent() ? 'true' : 'false' }};
  var isClosed = {{ $conversation->status === 'closed' ? 'true' : 'false' }};
  var csrfToken = "{{ csrf_token() }}";
  var takeUrl = "{{ route('customer-success.soporte.take', $conversation) }}";
  var replyUrl = "{{ route('customer-success.soporte.reply', $conversation) }}";

  var messagesEl = document.getElementById('agChatMessages');
  var inputEl = document.getElementById('agChatInput');
  var btnSend = document.getElementById('btnAgSend');
  var btnTake = document.getElementById('btnTakeChat');
  var takeBanner = document.getElementById('takeBanner');
  var inputBar = document.getElementById('agInputBar');

  function scrollBottom(){ messagesEl.scrollTop = messagesEl.scrollHeight; }
  scrollBottom();

  function addMsg(role, content, label){
    var side = role === 'agent' ? 'right' : (role === 'system' ? 'center' : 'left');
    var wrap = document.createElement('div');
    wrap.className = 'ag-msg-row ' + side;
    if(label){
      var lbl = document.createElement('div');
      lbl.className = 'ag-chat-label';
      lbl.textContent = label;
      wrap.appendChild(lbl);
    }
    var msg = document.createElement('div');
    msg.className = 'ag-chat-msg ' + role;
    msg.textContent = content;
    wrap.appendChild(msg);
    messagesEl.appendChild(wrap);
    scrollBottom();
  }

  if(btnTake){
    btnTake.addEventListener('click', async function(){
      btnTake.disabled = true;
      btnTake.textContent = 'Tomando...';
      try {
        var r = await fetch(takeUrl, {
          method: 'POST',
          headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
        });
        var d = await r.json();
        if(d.ok){
          if(takeBanner) takeBanner.remove();
          if(inputBar){ inputBar.style.opacity=''; inputBar.style.pointerEvents=''; }
          addMsg('system', 'Has tomado el chat. Ahora puedes responder.', '');
          isPending = false;
        }
      } catch(e){ btnTake.disabled = false; btnTake.textContent = 'Tomar chat'; }
    });
  }

  async function sendReply(){
    var text = inputEl.value.trim();
    if(!text || isPending || isClosed) return;
    inputEl.value = '';
    btnSend.disabled = true;
    addMsg('agent', text, 'Tú (agente)');
    try {
      await fetch(replyUrl, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},
        body: JSON.stringify({ message: text })
      });
    } catch(e){}
    btnSend.disabled = false;
    inputEl.focus();
  }

  if(btnSend) btnSend.addEventListener('click', sendReply);
  if(inputEl) inputEl.addEventListener('keydown', function(e){
    if(e.key === 'Enter' && !e.shiftKey){ e.preventDefault(); sendReply(); }
  });

  var btnCloseChat = document.getElementById('btnCloseChat');
  var closeUrl = "{{ route('customer-success.api.soporte.close', $conversation) }}";
  if(btnCloseChat){
    btnCloseChat.addEventListener('click', async function(){
      if(!confirm('¿Marcar esta conversación como resuelta?')) return;
      btnCloseChat.disabled = true;
      try {
        var r = await fetch(closeUrl, {
          method: 'POST',
          headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
        });
        var d = await r.json();
        if(d.ok){
          isClosed = true;
          addMsg('system', 'Conversación marcada como resuelta.', '');
          if(inputBar){ inputBar.style.opacity='.4'; inputBar.style.pointerEvents='none'; }
          btnCloseChat.textContent = '✓ Resuelta';
          btnCloseChat.disabled = true;
        }
      } catch(e){ btnCloseChat.disabled = false; }
    });
  }

  var lastUserId = {{ $messages->where('role','user')->max('id') ?? 0 }};
  var agentPollUrl = "{{ route('customer-success.api.soporte.poll', $conversation) }}";

  if (!isClosed) (function schedulePoll(delay){
    setTimeout(async function(){
      try {
        var r = await fetch(agentPollUrl + '?last_id=' + lastUserId, {
          headers: { 'Accept': 'application/json' }
        });
        var data = await r.json();
        if(data.ok && data.messages.length){
          data.messages.forEach(function(m){
            if(m.id > lastUserId) lastUserId = m.id;
            addMsg('user', m.content, '{{ $conversation->user?->name ?? "Usuario" }}');
          });
        }
      } catch(e){}
      if (!isClosed) schedulePoll(1000);
    }, delay);
  })(0);
})();
</script>
@endpush
