@extends('layouts.app')
@section('active', 'soporte')
@section('title', 'Chat con IA')
@section('header-title', 'Chat con IA')
@section('header-sub', 'Asistente virtual de soporte ENCLAII')

@push('styles')
<style>
.chat-wrap{
  display:flex;flex-direction:column;
  height:calc(100vh - 160px);min-height:400px;
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);
  overflow:hidden;position:relative;
}

/* Header */
.chat-header{
  display:flex;align-items:center;gap:12px;
  padding:16px 20px;border-bottom:1px solid var(--stroke);flex-shrink:0;
}
.chat-header .avatar{
  width:40px;height:40px;border-radius:50%;
  background:linear-gradient(135deg,var(--blue),var(--cyan));
  display:grid;place-items:center;flex-shrink:0;
}
.chat-header .info strong{font-size:14px;display:block}
.chat-header .info span{font-size:12px;color:var(--txt-soft)}
.chat-header .status-dot{
  width:8px;height:8px;border-radius:50%;background:#4ade80;
  display:inline-block;margin-left:6px;
}
.chat-header .btn-back{
  margin-left:auto;padding:8px 16px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:transparent;color:var(--txt);
  font-size:13px;font-weight:600;text-decoration:none;
  transition:background .15s;
}
.chat-header .btn-back:hover{background:rgba(110,160,255,.1)}

/* Messages area */
.chat-messages{
  flex:1;overflow-y:auto;padding:20px;
  display:flex;flex-direction:column;gap:16px;
}
.chat-messages::-webkit-scrollbar{width:4px}
.chat-messages::-webkit-scrollbar-thumb{background:var(--stroke);border-radius:4px}

.chat-msg{display:flex;gap:10px;max-width:80%}
.chat-msg.ia{align-self:flex-start}
.chat-msg.user{align-self:flex-end;flex-direction:row-reverse}

.chat-msg .msg-avatar{
  width:32px;height:32px;border-radius:50%;flex-shrink:0;
  display:grid;place-items:center;font-size:12px;font-weight:700;
}
.chat-msg.ia .msg-avatar{background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff}
.chat-msg.user .msg-avatar{background:rgba(168,130,255,.2);color:#a78bfa}

.chat-msg .msg-bubble{
  padding:12px 16px;border-radius:14px;font-size:14px;line-height:1.5;
}
.chat-msg.ia .msg-bubble{
  background:var(--panel);border:1px solid var(--stroke);
  border-top-left-radius:4px;
}
.chat-msg.user .msg-bubble{
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  border-top-right-radius:4px;
}
.chat-msg .msg-time{font-size:11px;color:var(--txt-soft);margin-top:4px}

/* Typing indicator */
.chat-typing{
  display:none;align-self:flex-start;align-items:center;gap:10px;
  padding:0 4px;
}
.chat-typing .msg-avatar{
  width:32px;height:32px;border-radius:50%;
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  display:grid;place-items:center;font-size:12px;font-weight:700;
}
.chat-typing .dots{
  display:flex;gap:4px;padding:12px 16px;
  background:var(--panel);border:1px solid var(--stroke);
  border-radius:14px;border-top-left-radius:4px;
}
.chat-typing .dots span{
  width:6px;height:6px;border-radius:50%;background:var(--txt-soft);
  animation:chatBounce .6s infinite alternate;
}
.chat-typing .dots span:nth-child(2){animation-delay:.15s}
.chat-typing .dots span:nth-child(3){animation-delay:.3s}
@keyframes chatBounce{0%{opacity:.3;transform:translateY(0)}100%{opacity:1;transform:translateY(-4px)}}

/* Input */
.chat-input{
  display:flex;align-items:center;gap:10px;
  padding:14px 20px;border-top:1px solid var(--stroke);flex-shrink:0;
}
.chat-input input{
  flex:1;padding:12px 16px;border-radius:99px;
  border:1px solid var(--stroke);background:var(--panel);color:var(--txt);
  font-size:14px;
}
.chat-input input:focus{outline:none;border-color:var(--blue)}
.chat-input .btn-send{
  width:42px;height:42px;border-radius:50%;border:0;
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  display:grid;place-items:center;cursor:pointer;transition:opacity .15s;flex-shrink:0;
}
.chat-input .btn-send:hover{opacity:.85}
.chat-input .btn-send:disabled{opacity:.4;cursor:default}

/* Suggestions */
.chat-suggestions{
  display:flex;flex-wrap:wrap;gap:8px;
  padding:0 20px 14px;flex-shrink:0;
}
.chat-suggestions .sug{
  padding:7px 14px;border-radius:99px;font-size:12px;
  border:1px solid var(--stroke);color:var(--txt-soft);cursor:pointer;
  transition:background .15s,color .15s;
}
.chat-suggestions .sug:hover{background:rgba(110,160,255,.1);color:var(--txt)}

/* Welcome / landing screen */
.chat-welcome{
  position:absolute;inset:0;z-index:10;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  background:var(--panel-2);padding:40px 20px;
  transition:opacity .3s ease;
}
.chat-welcome.hidden{opacity:0;pointer-events:none}
.chat-welcome .welcome-icon{
  width:64px;height:64px;border-radius:50%;margin-bottom:20px;
  background:linear-gradient(135deg,var(--blue),var(--cyan));
  display:grid;place-items:center;
}
.chat-welcome h2{
  font-size:26px;font-weight:700;color:var(--txt);margin:0 0 6px;
}
.chat-welcome .welcome-sub{
  font-size:14px;color:var(--txt-soft);margin:0 0 32px;
}
.chat-welcome .welcome-input-wrap{
  display:flex;align-items:center;gap:10px;
  width:100%;max-width:520px;
  padding:6px 6px 6px 20px;
  border:1px solid var(--stroke);border-radius:99px;
  background:var(--panel);transition:border-color .15s;
}
.chat-welcome .welcome-input-wrap:focus-within{border-color:var(--blue)}
.chat-welcome .welcome-input-wrap input{
  flex:1;border:none;background:transparent;color:var(--txt);
  font-size:15px;outline:none;
}
.chat-welcome .welcome-input-wrap input::placeholder{color:var(--txt-soft)}
.chat-welcome .welcome-input-wrap .btn-go{
  width:40px;height:40px;border-radius:50%;border:0;
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  display:grid;place-items:center;cursor:pointer;flex-shrink:0;
  transition:opacity .15s;
}
.chat-welcome .welcome-input-wrap .btn-go:hover{opacity:.85}
.chat-welcome .welcome-chips{
  display:flex;flex-wrap:wrap;gap:10px;margin-top:18px;justify-content:center;
}
.chat-welcome .welcome-chips .chip{
  display:inline-flex;align-items:center;gap:6px;
  padding:9px 18px;border-radius:99px;font-size:13px;
  border:1px solid var(--stroke);color:var(--txt-soft);cursor:pointer;
  background:transparent;transition:all .15s;
}
.chat-welcome .welcome-chips .chip:hover{
  background:rgba(110,160,255,.1);color:var(--txt);border-color:var(--blue);
}
.chat-welcome .welcome-chips .chip svg{opacity:.5}
</style>
@endpush

@section('content')
<div class="chat-wrap">

  <div class="chat-header">
    <div class="avatar">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4v4"/><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M9 13h.01"/><path d="M15 13h.01"/><path d="M10 17s1 1 2 1 2-1 2-1"/></svg>
    </div>
    <div class="info">
      <strong>Asistente IA ENCLAII <span class="status-dot"></span></strong>
      <span>Siempre en línea · Respuestas al instante</span>
    </div>
    <a href="{{ route('soporte') }}" class="btn-back">← Volver a soporte</a>
  </div>

  {{-- Welcome landing screen --}}
  <div class="chat-welcome" id="chatWelcome">
    <div class="welcome-icon">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4v4"/><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M9 13h.01"/><path d="M15 13h.01"/><path d="M10 17s1 1 2 1 2-1 2-1"/></svg>
    </div>
    <h2>¿En que puedo ayudarte?</h2>
    <p class="welcome-sub">Asistente de soporte ENCLAII · Siempre en linea</p>
    <div class="welcome-input-wrap">
      <input type="text" id="welcomeInput" placeholder="Pregunta lo que quieras..." autocomplete="off">
      <button class="btn-go" id="btnWelcomeSend" type="button">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      </button>
    </div>
    <div class="welcome-chips">
      <span class="chip"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>No puedo subir archivos</span>
      <span class="chip"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Error al iniciar sesion</span>
      <span class="chip"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>¿Como exporto datos?</span>
      <span class="chip"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>¿Como genero un reporte?</span>
    </div>
  </div>

  <div class="chat-messages" id="chatMessages" style="display:none"></div>

  <div class="chat-typing" id="chatTyping">
    <div class="msg-avatar">IA</div>
    <div class="dots"><span></span><span></span><span></span></div>
  </div>

  <div class="chat-suggestions" id="chatSuggestions" style="display:none"></div>

  <div class="chat-input" id="chatInputBar" style="display:none">
    <input type="text" id="chatInput" placeholder="Escribe tu mensaje..." autocomplete="off">
    <button class="btn-send" id="btnSend" type="button">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
    </button>
  </div>

</div>
@endsection

@push('scripts')
<script>
(function(){
  var CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
  var messages = document.getElementById('chatMessages');
  var input = document.getElementById('chatInput');
  var btnSend = document.getElementById('btnSend');
  var typing = document.getElementById('chatTyping');
  var suggestions = document.getElementById('chatSuggestions');
  var chatHistory = [];
  var welcome = document.getElementById('chatWelcome');
  var welcomeInput = document.getElementById('welcomeInput');
  var btnWelcomeSend = document.getElementById('btnWelcomeSend');
  var chatInputBar = document.getElementById('chatInputBar');
  var started = false;

  function startChat(){
    if(started) return;
    started = true;
    welcome.classList.add('hidden');
    messages.style.display = 'flex';
    chatInputBar.style.display = 'flex';
    setTimeout(function(){ welcome.style.display = 'none'; }, 300);
    input.focus();
  }

  function addMessage(text, isUser){
    var div = document.createElement('div');
    div.className = 'chat-msg ' + (isUser ? 'user' : 'ia');
    var initials = isUser ? 'TU' : 'IA';
    try {
      var profileEl = document.querySelector('.profile strong');
      if(isUser && profileEl) initials = profileEl.textContent.trim().split(' ').map(function(w){return w[0]}).join('').slice(0,2) || 'TU';
    } catch(e){}
    div.innerHTML =
      '<div class="msg-avatar">' + initials + '</div>' +
      '<div><div class="msg-bubble">' + text.replace(/\n/g,'<br>') + '</div>' +
      '<div class="msg-time">Ahora</div></div>';
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  function sendMessage(text){
    if (!text.trim()) return;
    addMessage(text, true);
    input.value = '';
    btnSend.disabled = true;
    suggestions.style.display = 'none';
    typing.style.display = 'flex';
    messages.scrollTop = messages.scrollHeight;

    chatHistory.push({ role: 'user', content: text });

    fetch('/soporte/chat', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        message: text,
        history: chatHistory.slice(-16)
      })
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
      typing.style.display = 'none';
      btnSend.disabled = false;
      if(data.ok){
        addMessage(data.reply, false);
        chatHistory.push({ role: 'assistant', content: data.reply });
      } else {
        addMessage('Lo siento, hubo un error al procesar tu mensaje. Por favor intenta de nuevo en unos momentos.', false);
      }
    })
    .catch(function(){
      typing.style.display = 'none';
      btnSend.disabled = false;
      addMessage('Error de conexion. Verifica tu internet e intenta de nuevo.', false);
    });
  }

  btnSend.addEventListener('click', function(){ sendMessage(input.value); });
  input.addEventListener('keydown', function(e){
    if (e.key === 'Enter') sendMessage(input.value);
  });

  // Welcome screen handlers
  btnWelcomeSend.addEventListener('click', function(){
    var text = welcomeInput.value.trim();
    if(!text) return;
    startChat();
    sendMessage(text);
  });
  welcomeInput.addEventListener('keydown', function(e){
    if(e.key === 'Enter'){
      var text = welcomeInput.value.trim();
      if(!text) return;
      startChat();
      sendMessage(text);
    }
  });
  document.querySelectorAll('.chat-welcome .chip').forEach(function(c){
    c.addEventListener('click', function(){
      startChat();
      sendMessage(c.textContent.trim());
    });
  });
})();
</script>
@endpush
