{{-- Tarjetas de dispositivos / servicios --}}
<div class="int-bottom">
  <article class="card rise d3">
    <div class="int-dev-head">
      <span class="int-dev-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/></svg></span>
      <div><div class="int-dev-t">Procesador de endoscopia</div><span class="int-chip-on">Conectado</span></div>
    </div>
    <div class="int-dev-meta"><b>Olympus EVIS X1</b></div>
    <div class="int-dev-meta">Número de serie: X1-24567</div>
  </article>

  <article class="card rise d3">
    <div class="int-dev-head">
      <span class="int-dev-ico" style="color:#a47bff;background:rgba(124,92,255,.12);border-color:rgba(124,92,255,.25)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></span>
      <div class="int-dev-t">Captura Multimedia</div>
    </div>
    <div class="int-checks">
      <label class="int-check"><input type="checkbox" data-setting="capture_auto_capture" checked> Autocapturas de imágenes</label>
      <label class="int-check"><input type="checkbox" data-setting="capture_auto_save" checked> Guardar imágenes automáticamente</label>
      <label class="int-check"><input type="checkbox" checked> Capturar imagen con pedal</label>
    </div>
    <div class="int-dev-meta" style="margin-top:10px">
      Intervalo de autocaptura:
      <select data-setting="capture_auto_interval" style="background:var(--panel-2);border:1px solid var(--stroke-strong);border-radius:8px;color:var(--txt);font:inherit;font-size:12px;padding:4px 8px;margin-left:4px">
        <option value="10">10 segundos</option>
        <option value="30">30 segundos</option>
        <option value="60">1 minuto</option>
        <option value="120">2 minutos</option>
      </select>
    </div>
  </article>

  <article class="card rise d4">
    <div class="int-dev-head">
      <span class="int-dev-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg></span>
      <div><div class="int-dev-t">Correo electrónico <span class="int-chip-on">Conectado</span></div></div>
    </div>
    <div class="int-dev-meta">Usuario: David1212@gmail.com</div>
    <div class="int-dev-meta">Contacto: ENCLAII@gmail.com</div>
    <div class="int-dev-btns"><a href="#" class="int-dev-btn">Configurar correo</a></div>
  </article>

  <article class="card rise d4">
    <div class="int-dev-head">
      <span class="int-dev-ico" style="color:#25d366;background:rgba(37,211,102,.12);border-color:rgba(37,211,102,.25)"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.5A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.8.9.9-2.7-.2-.3A8 8 0 1 1 12 20zm4.4-5.9c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2a.5.5 0 0 0 0-.5c0-.1-.5-1.3-.7-1.8s-.4-.4-.5-.4h-.5a.9.9 0 0 0-.7.3 2.8 2.8 0 0 0-.9 2.1 5 5 0 0 0 1 2.6 11 11 0 0 0 4.3 3.8c1.6.7 1.9.6 2.3.5a2.3 2.3 0 0 0 1.5-1.1 1.9 1.9 0 0 0 .1-1.1c0-.1-.2-.2-.4-.3z"/></svg></span>
      <div><div class="int-dev-t">WhatsApp Business <span class="int-chip-on">Conectado</span></div></div>
    </div>
    <div class="int-checks">
      <label class="int-check"><input type="checkbox" checked> Enviar informe PDF</label>
      <label class="int-check"><input type="checkbox" checked> Responder con AI</label>
      <label class="int-check"><input type="checkbox" checked> Enviar recordatorio de cita</label>
    </div>
    <div class="int-dev-btns"><a href="#" class="int-dev-btn">Configurar</a></div>
  </article>

  <article class="card rise d5">
    <div class="int-dev-head">
      <span class="int-dev-ico" style="color:var(--orange);background:rgba(245,158,45,.12);border-color:rgba(245,158,45,.25)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17c3-1 5-5 5-9 0 4 2 8 5 9-3 0-7 0-10 0z"/><path d="M14 11l6-6a1.5 1.5 0 0 0-2-2l-6 6"/></svg></span>
      <div>
        <div class="int-dev-t">Firma digital</div>
        <div class="int-dev-meta">Firma: {{ auth()->user()->name }}</div>
        <div class="int-sign-status {{ auth()->user()->signature_path ? 'ready' : '' }}">
          {{ auth()->user()->signature_path ? 'Firma configurada' : 'Sin firma registrada' }}
        </div>
      </div>
    </div>
    <div class="int-dev-meta">
      Actualizada:
      {{ format_user_date_time(auth()->user()->signature_updated_at) ?: 'Nunca' }}
    </div>
    <div class="int-dev-btns">
      <button type="button" class="int-dev-btn" id="intSignatureView" @disabled(! auth()->user()->signature_path)>Ver firma</button>
      <button type="button" class="int-dev-btn" id="intSignatureEdit">
        {{ auth()->user()->signature_path ? 'Actualizar firma' : 'Crear firma' }}
      </button>
    </div>
  </article>

  <article class="card rise d5">
    <div class="int-dev-head">
      <span class="int-dev-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a4 4 0 0 0-4 4 4 4 0 0 0 0 8 4 4 0 0 0 8 0 4 4 0 0 0 0-8 4 4 0 0 0-4-4z"/><path d="M12 2v20"/></svg></span>
      <div class="int-dev-t">Integración de AI</div>
    </div>
    <div class="int-checks">
      <label class="int-check"><input type="checkbox" checked> Generar borradores automáticos</label>
      <label class="int-check"><input type="checkbox" checked> Analizar fotos</label>
      <label class="int-check"><input type="checkbox" checked> Sugerir diagnósticos</label>
      <label class="int-check"><input type="checkbox" checked> Recomendar procedimientos</label>
    </div>
  </article>
</div>
