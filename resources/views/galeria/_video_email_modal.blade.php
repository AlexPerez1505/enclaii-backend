@php
  $galleryEmailDefaultRecipient = $paciente?->email ?? '';
  $galleryEmailSubject = 'Video de '.$tipoEstudio.' - '.$nombrePaciente;
  $galleryEmailMessage = "Hola,\n\nTe comparto el video del estudio {$folioEstudio} de {$nombrePaciente}.\n\nSaludos.";
@endphp

<div class="gv-mail-overlay" id="gvMailOverlay" data-send-url="{{ route('galeria.video.correo.send', $archivo->id) }}">
  <div class="gv-mail-modal" role="dialog" aria-modal="true" aria-labelledby="gvMailTitle">
    <div class="gv-mail-head">
      <div>
        <div class="gv-mail-title" id="gvMailTitle">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/></svg>
          Enviar video por Gmail
        </div>
        <div class="gv-mail-sub">{{ $nombrePaciente }} - {{ $folioEstudio }}</div>
      </div>
      <button class="gv-mail-x" id="gvMailClose" type="button" aria-label="Cerrar envio por correo">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <form class="gv-mail-form" id="gvMailForm">
      <div class="gv-mail-field">
        <label for="gvMailRecipients">Destinatarios</label>
        <input id="gvMailRecipients" name="recipients" type="text" value="{{ $galleryEmailDefaultRecipient }}" placeholder="correo@ejemplo.com" autocomplete="email">
        <span>Hasta 10 correos separados por coma.</span>
      </div>

      <div class="gv-mail-field">
        <label for="gvMailSubject">Asunto</label>
        <input id="gvMailSubject" name="subject" type="text" value="{{ $galleryEmailSubject }}" maxlength="180">
      </div>

      <div class="gv-mail-field">
        <label for="gvMailMessage">Mensaje</label>
        <textarea id="gvMailMessage" name="message" rows="6" maxlength="5000">{{ $galleryEmailMessage }}</textarea>
      </div>

      <div class="gv-mail-status" id="gvMailStatus" aria-live="polite"></div>

      <div class="gv-mail-footer">
        <button class="gv-mail-cancel" id="gvMailCancel" type="button">Cancelar</button>
        <button class="gv-mail-send" id="gvMailSend" type="submit">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
          Enviar correo
        </button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<style>
.gv-mail-overlay{
  position:fixed;inset:0;z-index:920;
  background:rgba(0,0,0,.58);backdrop-filter:blur(4px);
  display:flex;align-items:center;justify-content:center;
  opacity:0;pointer-events:none;transition:opacity 200ms ease;
}
.gv-mail-overlay.open{opacity:1;pointer-events:auto}
.gv-mail-modal{
  width:560px;max-width:95vw;max-height:92vh;overflow:auto;
  background:var(--panel);border:1px solid var(--stroke);border-radius:18px;
  box-shadow:0 24px 64px rgba(0,0,0,.48);
  transform:scale(.94);transition:transform 200ms var(--ease-out);
}
.gv-mail-overlay.open .gv-mail-modal{transform:scale(1)}
.gv-mail-head{
  display:flex;align-items:flex-start;justify-content:space-between;gap:14px;
  padding:18px 20px 0;
}
.gv-mail-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:800;display:flex;align-items:center;gap:9px;color:var(--txt)}
.gv-mail-title svg{color:var(--blue)}
.gv-mail-sub{font-size:12px;color:var(--txt-soft);margin-top:3px}
.gv-mail-x{
  width:30px;height:30px;border-radius:8px;border:1px solid var(--stroke);
  display:grid;place-items:center;color:var(--txt-soft);flex:none;cursor:pointer;
  transition:background-color 150ms ease,color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.gv-mail-x:hover{background:rgba(255,90,110,.12);color:var(--red)}}
.gv-mail-form{padding:16px 20px 18px;display:flex;flex-direction:column;gap:13px}
.gv-mail-field{display:flex;flex-direction:column;gap:6px}
.gv-mail-field label{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft)}
.gv-mail-field input,.gv-mail-field textarea{
  width:100%;background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-md);
  font:inherit;font-size:13px;color:var(--txt);outline:none;
  transition:border-color 150ms ease,box-shadow 150ms ease;
}
.gv-mail-field input{height:39px;padding:0 12px}
.gv-mail-field textarea{min-height:118px;padding:10px 12px;resize:vertical;line-height:1.5}
.gv-mail-field input:focus,.gv-mail-field textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(46,123,246,.12)}
.gv-mail-field span{font-size:11.5px;color:var(--txt-soft)}
.gv-mail-status{min-height:18px;font-size:12px;font-weight:700;color:var(--txt-soft)}
.gv-mail-status.ok{color:var(--green)}
.gv-mail-status.err{color:var(--red)}
.gv-mail-footer{
  display:flex;align-items:center;justify-content:flex-end;gap:8px;
  padding-top:2px;
}
.gv-mail-cancel,.gv-mail-send{
  height:38px;padding:0 16px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:700;
  display:inline-flex;align-items:center;gap:7px;
  transition:background-color 150ms ease,opacity 150ms ease,transform 160ms var(--ease-out);
}
.gv-mail-cancel{border:1px solid var(--stroke);color:var(--txt-soft);background:transparent}
.gv-mail-send{border:1px solid var(--blue);color:#fff;background:var(--blue)}
.gv-mail-cancel:active,.gv-mail-send:active{transform:scale(.97)}
.gv-mail-send:disabled{opacity:.62;cursor:not-allowed}
@media(hover:hover)and(pointer:fine){.gv-mail-cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}.gv-mail-send:not(:disabled):hover{opacity:.9}}
@media(max-width:620px){.gv-mail-modal{width:calc(100vw - 20px)}.gv-mail-footer{flex-direction:column-reverse;align-items:stretch}.gv-mail-cancel,.gv-mail-send{justify-content:center;width:100%}}
</style>
@endpush

@push('scripts')
<script>
(function(){
  const overlay = document.getElementById('gvMailOverlay');
  if(!overlay) return;

  const form = document.getElementById('gvMailForm');
  const recipients = document.getElementById('gvMailRecipients');
  const subject = document.getElementById('gvMailSubject');
  const message = document.getElementById('gvMailMessage');
  const sendBtn = document.getElementById('gvMailSend');
  const statusEl = document.getElementById('gvMailStatus');
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const sendUrl = overlay.dataset.sendUrl;
  let sending = false;

  function setStatus(text, type){
    statusEl.textContent = text || '';
    statusEl.className = 'gv-mail-status' + (type ? ' ' + type : '');
  }

  function openMail(){
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
    setStatus('', '');
    setTimeout(() => recipients?.focus(), 50);
  }

  function closeMail(){
    if(sending) return;
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  document.querySelectorAll('[data-gallery-email-open]').forEach(button => {
    button.addEventListener('click', openMail);
  });
  document.getElementById('gvMailClose')?.addEventListener('click', closeMail);
  document.getElementById('gvMailCancel')?.addEventListener('click', closeMail);
  overlay.addEventListener('click', event => {
    if(event.target === overlay) closeMail();
  });
  document.addEventListener('keydown', event => {
    if(event.key === 'Escape' && overlay.classList.contains('open')) closeMail();
  });

  form?.addEventListener('submit', async event => {
    event.preventDefault();
    if(sending) return;

    sending = true;
    sendBtn.disabled = true;
    setStatus('Enviando correo...', '');

    try {
      const response = await fetch(sendUrl, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          recipients: recipients.value.trim(),
          subject: subject.value.trim(),
          message: message.value.trim()
        })
      });
      const data = await response.json().catch(() => ({}));
      if(!response.ok) throw new Error(data.message || 'No se pudo enviar el correo.');

      setStatus(data.message || 'Correo enviado correctamente.', 'ok');
      setTimeout(closeMail, 900);
    } catch (error) {
      setStatus(error.message || 'No se pudo enviar el correo.', 'err');
    } finally {
      sending = false;
      sendBtn.disabled = false;
    }
  });
})();
</script>
@endpush
