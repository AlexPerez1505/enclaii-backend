@php
  $imageStudy = $archivo?->estudio;
  $imagePatientName = $paciente?->nombre_completo ?? $imageStudy?->paciente_nombre ?? 'Paciente';
  $imageStudyType = $imageStudy?->tipo ?: 'Imagen del estudio';
  $imageFolio = $imageStudy?->folio ?? ('IMG-'.str_pad((string) $archivo->id, 4, '0', STR_PAD_LEFT));
  $imageEmailDefaultRecipient = $paciente?->email ?? '';
  $imageEmailSubject = 'Imagen de '.$imageStudyType.' - '.$imagePatientName;
  $imageEmailMessage = "Hola,\n\nTe comparto la imagen {$imageFolio} de {$imagePatientName}.\n\nSaludos.";
@endphp

<div class="gi-mail-overlay" id="giMailOverlay" data-send-url="{{ route('galeria.imagen.correo.send', $archivo->id, false) }}">
  <div class="gi-mail-modal" role="dialog" aria-modal="true" aria-labelledby="giMailTitle">
    <div class="gi-mail-head">
      <div>
        <div class="gi-mail-title" id="giMailTitle">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/></svg>
          Enviar imagen por Gmail
        </div>
        <div class="gi-mail-sub">{{ $imagePatientName }} - {{ $imageFolio }}</div>
      </div>
      <button class="gi-mail-x" id="giMailClose" type="button" aria-label="Cerrar envio por correo">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <form class="gi-mail-form" id="giMailForm">
      @csrf
      <div class="gi-mail-field">
        <label for="giMailRecipients">Destinatarios</label>
        <input id="giMailRecipients" name="recipients" type="text" value="{{ $imageEmailDefaultRecipient }}" placeholder="correo@ejemplo.com" autocomplete="email">
        <span>Hasta 10 correos separados por coma.</span>
      </div>

      <div class="gi-mail-field">
        <label for="giMailSubject">Asunto</label>
        <input id="giMailSubject" name="subject" type="text" value="{{ $imageEmailSubject }}" maxlength="180">
      </div>

      <div class="gi-mail-field">
        <label for="giMailMessage">Mensaje</label>
        <textarea id="giMailMessage" name="message" rows="6" maxlength="5000">{{ $imageEmailMessage }}</textarea>
      </div>

      <div class="gi-mail-status" id="giMailStatus" aria-live="polite"></div>

      <div class="gi-mail-footer">
        <button class="gi-mail-cancel" id="giMailCancel" type="button">Cancelar</button>
        <button class="gi-mail-send" id="giMailSend" type="submit">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
          Enviar correo
        </button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<style>
.gi-mail-overlay{
  position:fixed;inset:0;z-index:920;
  background:rgba(0,0,0,.58);backdrop-filter:blur(4px);
  display:flex;align-items:center;justify-content:center;
  opacity:0;pointer-events:none;transition:opacity 200ms ease;
}
.gi-mail-overlay.open{opacity:1;pointer-events:auto}
.gi-mail-modal{
  width:560px;max-width:95vw;max-height:92vh;overflow:auto;
  background:var(--panel);border:1px solid var(--stroke);border-radius:18px;
  box-shadow:0 24px 64px rgba(0,0,0,.48);
  transform:scale(.94);transition:transform 200ms var(--ease-out);
}
.gi-mail-overlay.open .gi-mail-modal{transform:scale(1)}
.gi-mail-head{
  display:flex;align-items:flex-start;justify-content:space-between;gap:14px;
  padding:18px 20px 0;
}
.gi-mail-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:800;display:flex;align-items:center;gap:9px;color:var(--txt)}
.gi-mail-title svg{color:var(--blue)}
.gi-mail-sub{font-size:12px;color:var(--txt-soft);margin-top:3px}
.gi-mail-x{
  width:30px;height:30px;border-radius:8px;border:1px solid var(--stroke);
  display:grid;place-items:center;color:var(--txt-soft);flex:none;cursor:pointer;
  transition:background-color 150ms ease,color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.gi-mail-x:hover{background:rgba(255,90,110,.12);color:var(--red)}}
.gi-mail-form{padding:16px 20px 18px;display:flex;flex-direction:column;gap:13px}
.gi-mail-field{display:flex;flex-direction:column;gap:6px}
.gi-mail-field label{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft)}
.gi-mail-field input,.gi-mail-field textarea{
  width:100%;background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-md);
  font:inherit;font-size:13px;color:var(--txt);outline:none;
  transition:border-color 150ms ease,box-shadow 150ms ease;
}
.gi-mail-field input{height:39px;padding:0 12px}
.gi-mail-field textarea{min-height:118px;padding:10px 12px;resize:vertical;line-height:1.5}
.gi-mail-field input:focus,.gi-mail-field textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(46,123,246,.12)}
.gi-mail-field span{font-size:11.5px;color:var(--txt-soft)}
.gi-mail-status{min-height:18px;font-size:12px;font-weight:700;color:var(--txt-soft)}
.gi-mail-status.ok{color:var(--green)}
.gi-mail-status.err{color:var(--red)}
.gi-mail-footer{display:flex;align-items:center;justify-content:flex-end;gap:8px;padding-top:2px}
.gi-mail-cancel,.gi-mail-send{
  height:38px;padding:0 16px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:700;
  display:inline-flex;align-items:center;gap:7px;
  transition:background-color 150ms ease,opacity 150ms ease,transform 160ms var(--ease-out);
}
.gi-mail-cancel{border:1px solid var(--stroke);color:var(--txt-soft);background:transparent}
.gi-mail-send{border:1px solid var(--blue);color:#fff;background:var(--blue)}
.gi-mail-cancel:active,.gi-mail-send:active{transform:scale(.97)}
.gi-mail-send:disabled{opacity:.62;cursor:not-allowed}
@media(hover:hover)and(pointer:fine){.gi-mail-cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}.gi-mail-send:not(:disabled):hover{opacity:.9}}
@media(max-width:620px){.gi-mail-modal{width:calc(100vw - 20px)}.gi-mail-footer{flex-direction:column-reverse;align-items:stretch}.gi-mail-cancel,.gi-mail-send{justify-content:center;width:100%}}
</style>
@endpush

@push('scripts')
<script>
(function(){
  const overlay = document.getElementById('giMailOverlay');
  if(!overlay) return;

  const form = document.getElementById('giMailForm');
  const recipients = document.getElementById('giMailRecipients');
  const subject = document.getElementById('giMailSubject');
  const message = document.getElementById('giMailMessage');
  const sendBtn = document.getElementById('giMailSend');
  const statusEl = document.getElementById('giMailStatus');
  const csrfToken = form?.querySelector('input[name="_token"]')?.value
    || document.querySelector('meta[name="csrf-token"]')?.content
    || @json(csrf_token());
  const sendUrl = overlay.dataset.sendUrl;
  let sending = false;

  function setStatus(text, type){
    statusEl.textContent = text || '';
    statusEl.className = 'gi-mail-status' + (type ? ' ' + type : '');
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

  document.querySelectorAll('[data-gallery-image-email-open]').forEach(button => {
    button.addEventListener('click', openMail);
  });
  document.getElementById('giMailClose')?.addEventListener('click', closeMail);
  document.getElementById('giMailCancel')?.addEventListener('click', closeMail);
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
        credentials: 'same-origin',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          _token: csrfToken,
          recipients: recipients.value.trim(),
          subject: subject.value.trim(),
          message: message.value.trim()
        })
      });
      const data = await response.json().catch(() => ({}));
      if(response.status === 419) throw new Error('Tu sesion expiro. Recarga la pagina e intenta enviar el correo otra vez.');
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
