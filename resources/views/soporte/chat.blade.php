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
  overflow:hidden;
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

  <div class="chat-messages" id="chatMessages">
    <div class="chat-msg ia">
      <div class="msg-avatar">IA</div>
      <div>
        <div class="msg-bubble">¡Hola! Soy el asistente de IA de ENCLAII. Estoy aquí para ayudarte con cualquier duda o problema que tengas. ¿En qué puedo ayudarte hoy?</div>
        <div class="msg-time">Ahora</div>
      </div>
    </div>
  </div>

  <div class="chat-typing" id="chatTyping">
    <div class="msg-avatar">IA</div>
    <div class="dots"><span></span><span></span><span></span></div>
  </div>

  <div class="chat-suggestions" id="chatSuggestions">
    <span class="sug">No puedo subir archivos</span>
    <span class="sug">Error al iniciar sesión</span>
    <span class="sug">¿Cómo exporto datos?</span>
    <span class="sug">Problemas de conexión</span>
    <span class="sug">¿Cómo genero un reporte?</span>
  </div>

  <div class="chat-input">
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
  const messages = document.getElementById('chatMessages');
  const input = document.getElementById('chatInput');
  const btnSend = document.getElementById('btnSend');
  const typing = document.getElementById('chatTyping');
  const suggestions = document.getElementById('chatSuggestions');

  const respuestas = {
    'subir archivos': 'Para subir archivos, ve a la sección de Estudios y usa el botón "Adjuntar archivo". Asegúrate de que el archivo no supere los 10MB y sea de un formato compatible (JPG, PNG, PDF, MP4).',
    'iniciar sesión': 'Si tienes problemas para iniciar sesión, prueba lo siguiente:\n1. Verifica que tu correo y contraseña sean correctos.\n2. Limpia la caché de tu navegador.\n3. Si olvidaste tu contraseña, usa la opción "Recuperar contraseña".\nSi el problema persiste, contacta a soporte técnico.',
    'exporto datos': 'Para exportar datos, dirígete a la sección de Reportes y haz clic en el botón de exportar. Puedes elegir formato PDF o Excel. También puedes exportar desde la Galería seleccionando las imágenes que desees.',
    'conexión': 'Si experimentas problemas de conexión:\n1. Verifica tu conexión a internet.\n2. Intenta recargar la página.\n3. Limpia la caché del navegador.\n4. Si usas VPN, intenta desactivarla temporalmente.',
    'reporte': 'Para generar un reporte:\n1. Ve a la sección "Reportes" en el menú lateral.\n2. Selecciona "Generar reporte" y elige el estudio.\n3. Nuestro asistente de IA te ayudará a redactar el informe.\n4. Puedes editar y personalizar el reporte antes de guardarlo.',
  };

  function addMessage(text, isUser){
    const div = document.createElement('div');
    div.className = 'chat-msg ' + (isUser ? 'user' : 'ia');
    const initials = isUser ? (document.querySelector('.profile strong')?.textContent?.trim()?.split(' ').map(w=>w[0]).join('').slice(0,2) || 'TU') : 'IA';
    div.innerHTML =
      '<div class="msg-avatar">' + initials + '</div>' +
      '<div><div class="msg-bubble">' + text.replace(/\n/g,'<br>') + '</div>' +
      '<div class="msg-time">Ahora</div></div>';
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  function getResponse(text){
    const lower = text.toLowerCase();
    for (const [key, val] of Object.entries(respuestas)){
      if (lower.includes(key)) return val;
    }
    return 'Gracias por tu mensaje. He registrado tu consulta sobre "' + text + '". Un especialista revisará tu caso. Mientras tanto, ¿puedo ayudarte con algo más?\n\nTambién puedes consultar nuestra sección de preguntas frecuentes o crear un ticket de soporte para seguimiento.';
  }

  function sendMessage(text){
    if (!text.trim()) return;
    addMessage(text, true);
    input.value = '';
    btnSend.disabled = true;
    suggestions.style.display = 'none';
    typing.style.display = 'flex';
    messages.scrollTop = messages.scrollHeight;

    setTimeout(function(){
      typing.style.display = 'none';
      addMessage(getResponse(text), false);
      btnSend.disabled = false;
    }, 1000 + Math.random() * 1000);
  }

  btnSend.addEventListener('click', function(){ sendMessage(input.value); });
  input.addEventListener('keydown', function(e){
    if (e.key === 'Enter') sendMessage(input.value);
  });

  suggestions.querySelectorAll('.sug').forEach(function(s){
    s.addEventListener('click', function(){ sendMessage(s.textContent); });
  });
})();
</script>
@endpush
