{{-- Tarjetas de dispositivos / servicios --}}
<div class="int-bottom">
  {{--<article class="card rise d3">
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
  </article>--}}

  

  <article class="card rise d5 int-sign-wide">
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
</div>
