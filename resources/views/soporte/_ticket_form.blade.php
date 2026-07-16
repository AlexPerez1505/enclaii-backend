@push('styles')
<style>
/* ── Ticket form ── */
.sop-card-ticket{
  background:linear-gradient(145deg,#050914,#0a0f1c);
  border:1px solid rgba(59,130,246,.18);
  border-radius:var(--r-lg);
  box-shadow:0 8px 32px rgba(0,0,0,.35),inset 0 1px 0 rgba(255,255,255,.03);
  transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease;
}
.sop-card-ticket:hover{
  transform:translateY(-2px);
  border-color:rgba(59,130,246,.32);
  box-shadow:0 12px 40px rgba(0,0,0,.42),0 0 24px rgba(59,130,246,.08),inset 0 1px 0 rgba(255,255,255,.04);
}
.sop-card-ticket h2{color:#e2e8f0;font-size:18px;font-weight:700;margin:0 0 4px}
.sop-card-ticket .sub{color:#94a3b8;font-size:13px;margin-bottom:20px}

.tkt-form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px}
.tkt-form-row.full{grid-template-columns:1fr}
.tkt-field label{
  font-size:13px;font-weight:600;color:#cbd5e1;display:flex;align-items:center;gap:8px;margin-bottom:6px;
}
.tkt-field label svg{
  width:16px;height:16px;color:#60a5fa;flex-shrink:0;
  filter:drop-shadow(0 0 4px rgba(96,165,250,.25));
}
.tkt-field select,
.tkt-field input,
.tkt-field textarea{
  width:100%;padding:10px 14px;border-radius:var(--r-md);
  border:1px solid rgba(59,130,246,.22);
  background:rgba(15,23,42,.65);
  color:#e2e8f0;
  font-size:14px;resize:vertical;
  transition:border-color .2s ease,box-shadow .2s ease,background .2s ease;
}

/* Custom select - Categoría */
.tkt-select-wrap{position:relative}
.tkt-select-trigger{
  width:100%;padding:10px 14px;border-radius:var(--r-md);
  border:1px solid rgba(59,130,246,.22);
  background:rgba(15,23,42,.65);
  color:#e2e8f0;
  font-size:14px;
  display:flex;align-items:center;justify-content:space-between;
  cursor:pointer;
  transition:border-color .2s ease,background .2s ease,box-shadow .2s ease;
}
.tkt-select-trigger:hover{
  background:rgba(15,23,42,.85);
  border-color:rgba(96,165,250,.45);
}
.tkt-select-trigger:focus{outline:none}
.tkt-select-trigger.open{
  border-color:rgba(139,92,246,.65);
  background:rgba(15,23,42,.85);
  box-shadow:0 0 0 3px rgba(139,92,246,.15),0 0 12px rgba(139,92,246,.12);
}
.tkt-select-trigger svg{
  color:#60a5fa;
  transition:transform .2s ease,color .2s ease;
}
.tkt-select-trigger.open svg{transform:rotate(180deg);color:#a78bfa}
.tkt-select-options{
  position:absolute;top:calc(100% + 6px);left:0;right:0;
  background:linear-gradient(180deg,#0f172a,#0b1221);
  border:1px solid rgba(59,130,246,.35);
  border-radius:var(--r-md);
  box-shadow:0 8px 24px rgba(0,0,0,.45),0 0 16px rgba(59,130,246,.10);
  max-height:0;overflow:hidden;opacity:0;visibility:hidden;
  transition:max-height .25s ease,opacity .2s ease,visibility .2s ease;
  z-index:100;
}
.tkt-select-options.open{
  max-height:240px;opacity:1;visibility:visible;
  overflow-y:auto;
}
.tkt-option{
  padding:10px 14px;color:#cbd5e1;font-size:14px;cursor:pointer;
  display:flex;align-items:center;gap:8px;
  transition:background .15s ease,color .15s ease;
  border-bottom:1px solid rgba(59,130,246,.08);
}
.tkt-option:last-child{border-bottom:0}
.tkt-option:hover{
  background:rgba(59,130,246,.14);
  color:#e2e8f0;
}
.tkt-option.selected{
  background:rgba(139,92,246,.18);
  color:#fff;
}
.tkt-option.selected::before{
  content:'';width:6px;height:6px;border-radius:50%;
  background:#a78bfa;box-shadow:0 0 6px rgba(167,139,250,.5);
}
.tkt-field select::placeholder,
.tkt-field input::placeholder,
.tkt-field textarea::placeholder{color:#64748b}
.tkt-field select:focus,
.tkt-field input:focus,
.tkt-field textarea:focus{
  outline:none;
  border-color:rgba(139,92,246,.65);
  background:rgba(15,23,42,.85);
  box-shadow:0 0 0 3px rgba(139,92,246,.15),0 0 12px rgba(139,92,246,.12);
}
.tkt-field textarea{min-height:100px}
.tkt-form-footer{display:flex;align-items:center;justify-content:space-between;margin-top:18px;padding-top:18px;border-top:1px solid rgba(59,130,246,.12)}
.tkt-adjuntar{
  display:flex;align-items:center;gap:8px;
  padding:10px 16px;border-radius:var(--r-md);
  border:1px dashed rgba(59,130,246,.35);
  background:rgba(15,23,42,.45);
  font-size:13px;color:#94a3b8;cursor:pointer;
  transition:background .2s ease,border-color .2s ease,color .2s ease;
}
.tkt-adjuntar:hover{
  background:rgba(59,130,246,.10);
  border-color:rgba(96,165,250,.55);
  color:#cbd5e1;
}
.tkt-adjuntar svg{color:#60a5fa}
.tkt-adjuntar span{font-size:11px;color:#64748b}
.tkt-btn-enviar{
  padding:11px 26px;border-radius:var(--r-md);border:0;
  background:linear-gradient(135deg,#3b82f6,#8b5cf6);color:#fff;
  font-size:14px;font-weight:600;cursor:pointer;
  display:flex;align-items:center;gap:8px;
  transition:transform .15s ease,box-shadow .15s ease,opacity .15s ease;
  box-shadow:0 4px 14px rgba(59,130,246,.28);
}
.tkt-btn-enviar:hover{
  opacity:.95;
  transform:translateY(-1px);
  box-shadow:0 6px 20px rgba(59,130,246,.38);
}
.tkt-btn-enviar svg{stroke-width:2.2}
.tkt-form-actions{display:flex;align-items:center;gap:10px}
.tkt-btn-mis-tickets{
  padding:10px 24px;border-radius:var(--r-md);
  border:1px solid rgba(59,130,246,.30);background:transparent;color:#94a3b8;
  font-size:14px;font-weight:600;text-decoration:none;
  display:flex;align-items:center;gap:8px;transition:background .15s ease,color .15s ease,border-color .15s ease;
}
.tkt-btn-mis-tickets:hover{
  background:rgba(59,130,246,.10);
  border-color:rgba(96,165,250,.50);
  color:#cbd5e1;
}

@media (max-width:600px){
  .tkt-form-row{grid-template-columns:1fr}
}

/* Modal */
.tkt-modal-overlay{
  position:fixed;inset:0;z-index:9999;
  background:rgba(0,0,0,.6);backdrop-filter:blur(4px);
  display:grid;place-items:center;padding:20px;
}
.tkt-modal{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);
  width:100%;max-width:560px;max-height:80vh;overflow-y:auto;overflow-x:hidden;
  box-shadow:0 20px 60px rgba(0,0,0,.4);
}
.tkt-modal-header{
  padding:24px 24px 16px;text-align:center;position:relative;
}
.tkt-modal-header .tkt-modal-icon{margin-bottom:12px}
.tkt-modal-header h2{font-size:18px;font-weight:700;margin-bottom:4px}
.tkt-modal-header p{font-size:13px;color:var(--txt-soft);margin:0}
.tkt-modal-close{
  position:absolute;top:16px;right:16px;
  width:32px;height:32px;border-radius:50%;border:0;
  background:var(--panel);color:var(--txt);font-size:18px;
  cursor:pointer;display:grid;place-items:center;
  transition:background .15s;
}
.tkt-modal-close:hover{background:rgba(110,160,255,.1)}

.tkt-modal-body{
  padding:0 24px 16px;font-size:13px;
}
.tkt-modal-body .tkt-resumen{
  background:var(--panel);border:1px solid var(--stroke);border-radius:var(--r-md);
  padding:20px 22px;display:flex;flex-direction:column;gap:0;overflow:hidden;
}
.tkt-modal-body .tkt-resumen .row{display:flex;gap:12px;overflow:hidden;max-width:100%;padding:10px 0;border-bottom:1px solid rgba(148,163,184,.08)}
.tkt-modal-body .tkt-resumen .row:last-child{border-bottom:none}
.tkt-modal-body .tkt-resumen .row .lbl{font-weight:600;color:var(--txt-soft);min-width:130px;max-width:130px;flex-shrink:0;font-size:12px}
.tkt-modal-body .tkt-resumen .row .val{color:var(--txt);flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:13px}

.tkt-modal-actions{
  padding:16px 24px 24px;display:flex;gap:10px;flex-wrap:wrap;
}
.tkt-modal-btn{
  flex:1;min-width:140px;padding:11px 16px;border-radius:var(--r-md);
  font-size:13px;font-weight:600;cursor:pointer;text-align:center;
  display:flex;align-items:center;justify-content:center;gap:8px;
  text-decoration:none;transition:opacity .15s;
}
.tkt-modal-btn.mensajes{
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;border:0;
}
.tkt-modal-btn.mensajes:hover{opacity:.85}
.tkt-modal-btn.guardar{
  border:1px solid var(--stroke);background:transparent;color:var(--txt);
}
.tkt-modal-btn.guardar:hover{background:rgba(110,160,255,.1)}
.tkt-modal-btn.imprimir{
  border:1px solid var(--stroke);background:transparent;color:var(--txt);
}
.tkt-modal-btn.imprimir:hover{background:rgba(110,160,255,.1)}

.tkt-modal-ver{
  position:absolute;top:16px;left:16px;
  width:32px;height:32px;border-radius:50%;border:0;
  background:var(--panel);color:var(--txt);
  cursor:pointer;display:grid;place-items:center;
  transition:background .15s;
}
.tkt-modal-ver:hover{background:rgba(110,160,255,.15)}

.tkt-msg-wrap{position:relative;flex:1;min-width:140px}
.tkt-contactos{
  position:absolute;bottom:calc(100% + 8px);left:0;right:0;
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md);
  padding:8px;box-shadow:0 8px 24px rgba(0,0,0,.3);z-index:10;
}
.tkt-contacto{
  display:flex;align-items:center;gap:10px;
  padding:8px 10px;border-radius:var(--r-md);cursor:pointer;
  transition:background .15s;
}
.tkt-contacto:hover{background:rgba(110,160,255,.1)}
.tkt-contacto-avatar{
  width:32px;height:32px;border-radius:50%;
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  display:grid;place-items:center;font-size:11px;font-weight:700;flex-shrink:0;
}
.tkt-contacto strong{font-size:13px;display:block}
.tkt-contacto span{font-size:11px;color:var(--txt-soft)}

html[data-theme="light"] .sop-card-ticket{
  background:linear-gradient(145deg,#fff 0%,#f8fbff 100%);border-color:#bfdbfe;box-shadow:0 10px 30px rgba(30,64,175,.08),inset 0 1px 0 #fff
}
html[data-theme="light"] .sop-card-ticket:hover{
  border-color:#60a5fa;box-shadow:0 16px 36px rgba(30,64,175,.12),0 0 20px rgba(59,130,246,.07)
}
html[data-theme="light"] .sop-card-ticket h2{color:#172554}
html[data-theme="light"] .sop-card-ticket .sub{color:#64748b}
html[data-theme="light"] .tkt-field label{color:#334155}
html[data-theme="light"] .tkt-field label svg{color:#2563eb;filter:none}
html[data-theme="light"] .tkt-field select,
html[data-theme="light"] .tkt-field input,
html[data-theme="light"] .tkt-field textarea,
html[data-theme="light"] .tkt-select-trigger{
  background:#fff;border-color:#cbd5e1;color:#0f172a;box-shadow:0 1px 2px rgba(15,23,42,.03)
}
html[data-theme="light"] .tkt-select-trigger:hover{
  background:#f8fbff;border-color:#60a5fa
}
html[data-theme="light"] .tkt-select-trigger.open,
html[data-theme="light"] .tkt-field select:focus,
html[data-theme="light"] .tkt-field input:focus,
html[data-theme="light"] .tkt-field textarea:focus{
  background:#fff;border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.12),0 2px 8px rgba(59,130,246,.08)
}
html[data-theme="light"] .tkt-field select::placeholder,
html[data-theme="light"] .tkt-field input::placeholder,
html[data-theme="light"] .tkt-field textarea::placeholder{color:#94a3b8}
html[data-theme="light"] .tkt-select-options{
  background:#fff;border-color:#bfdbfe;box-shadow:0 10px 26px rgba(15,23,42,.12)
}
html[data-theme="light"] .tkt-option{color:#334155;border-color:#e2e8f0}
html[data-theme="light"] .tkt-option:hover{background:#eff6ff;color:#1d4ed8}
html[data-theme="light"] .tkt-option.selected{background:#eef2ff;color:#4338ca}
html[data-theme="light"] .tkt-form-footer{border-color:#dbe5f5}
html[data-theme="light"] .tkt-adjuntar{background:#f8fbff;border-color:#93c5fd;color:#475569}
html[data-theme="light"] .tkt-adjuntar:hover{background:#eff6ff;border-color:#3b82f6;color:#1e3a8a}
html[data-theme="light"] .tkt-adjuntar span{color:#64748b}
html[data-theme="light"] .tkt-btn-mis-tickets{border-color:#93c5fd;color:#2563eb;background:#fff}
html[data-theme="light"] .tkt-btn-mis-tickets:hover{background:#eff6ff;border-color:#3b82f6;color:#1d4ed8}
html[data-theme="light"] .tkt-modal{background:#fff;border-color:#dbe5f5;box-shadow:0 20px 60px rgba(15,23,42,.22)}
html[data-theme="light"] .tkt-modal-close,
html[data-theme="light"] .tkt-modal-ver{background:#f1f5f9;color:#334155}
html[data-theme="light"] .tkt-modal-body .tkt-resumen{background:#f8fafc;border-color:#dbe5f5}
html[data-theme="light"] .tkt-modal-btn.guardar,
html[data-theme="light"] .tkt-modal-btn.imprimir{background:#fff;border-color:#cbd5e1;color:#334155}
html[data-theme="light"] .tkt-contactos{background:#fff;border-color:#dbe5f5;box-shadow:0 10px 26px rgba(15,23,42,.13)}
</style>
@endpush

<div class="tkt-panel" id="panelCrear">
 

  @php
    $businessData = $clinicaData ?? '';
    $operationData = ($operationFolio ?? '') . ' | ' . ($operationDate ?? '');
  @endphp

  <div class="tkt-form-row">
    <div class="tkt-field">
      <label>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        Categoría
      </label>
      <div class="tkt-select-wrap">
        <select id="tktCategoria" style="display:none">
          <option value="">Selecciona una categoría</option>
          <option value="facturacion">Facturación</option>
          <option value="tecnico">Problema técnico</option>
          <option value="funcion">Solicitud de función</option>
          <option value="como-hacer">Cómo hacer</option>
          <option value="otro">Otro</option>
        </select>
        <button type="button" class="tkt-select-trigger" id="tktCategoriaTrigger">
          <span id="tktCategoriaLabel">Selecciona una categoría</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="tkt-select-options" id="tktCategoriaOptions">
          <div class="tkt-option" data-value="">Selecciona una categoría</div>
          <div class="tkt-option" data-value="facturacion">Facturación</div>
          <div class="tkt-option" data-value="tecnico">Problema técnico</div>
          <div class="tkt-option" data-value="funcion">Solicitud de función</div>
          <div class="tkt-option" data-value="como-hacer">Cómo hacer</div>
          <div class="tkt-option" data-value="otro">Otro</div>
        </div>
      </div>
    </div>
    <div class="tkt-field">
      <label>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Datos de la operación
      </label>
      <input type="text" id="tktOperacion" value="{{ $operationData }}" readonly placeholder="Folio único, fecha y hora de la compra">
    </div>
  </div>

  <div class="tkt-form-row full">
    <div class="tkt-field">
      <label>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
        Asunto
      </label>
      <input type="text" id="tktAsunto" maxlength="255" placeholder="Describe brevemente tu problema">
    </div>
  </div>

  <div class="tkt-form-row full">
    <div class="tkt-field">
      <label>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
        Descripción
      </label>
      <textarea id="tktDescripcion" maxlength="4000" placeholder="Proporciona tantos detalles como sea posible..."></textarea>
      <span id="tktDescCount" style="font-size:11px;color:var(--txt-soft,#64748b);text-align:right;display:block;margin-top:4px">0 / 4000</span>
    </div>
  </div>

  {{-- Datos del negocio (solo lectura, vienen de la BD) --}}
  <div class="tkt-form-row full">
    <div class="tkt-field">
      <label>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l8-4 8 4v14"/><path d="M9 21v-10"/><path d="M15 21v-10"/><path d="M9 7h6"/></svg>
        Datos del negocio
      </label>
      <input type="text" id="tktNegocio" value="{{ $businessData }}" readonly placeholder="Nombre o razón social, domicilio fiscal, RFC">
    </div>
  </div>

  <div class="tkt-form-row full" id="tktPaymentMethodRow" style="display:none">
    <div class="tkt-field">
      <label>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        Método de pago
      </label>
      <select id="tktMetodoPago">
        <option value="">Selecciona método de pago</option>
        <option value="efectivo">Efectivo</option>
        <option value="tarjeta">Tarjeta</option>
        <option value="transferencia">Transferencia</option>
      </select>
    </div>
  </div>

  <div class="tkt-form-footer">
    <div style="display:flex;flex-direction:column;gap:8px">
      <label class="tkt-adjuntar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
        Adjuntar archivo
        <span>Tamaño máx. de archivo: 10MB</span>
        <input type="file" id="tktAttachment" style="display:none">
      </label>
      <span id="tktAttachmentName" style="font-size:12px;color:var(--txt-soft);display:none"></span>
    </div>
    <div class="tkt-form-actions">
      <button class="tkt-btn-enviar" type="button" id="btnEnviarTicket" onclick="window.__enviarTicket()">
        Enviar ticket
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      </button>
    </div>
  </div>
</div>

@push('scripts')
{{-- Modal: Ticket creado --}}
{{-- Modal: Datos de clínica incompletos --}}
<div class="tkt-modal-overlay" id="tktPerfilModalOverlay" style="display:none">
  <div class="tkt-modal">
    <div class="tkt-modal-header">
      <div class="tkt-modal-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      </div>
      <h2>Datos de la clínica incompletos</h2>
      <p>No se puede crear el ticket porque aún no has registrado el nombre de tu clínica en el perfil.</p>
      <button class="tkt-modal-close" id="tktPerfilModalClose" type="button">×</button>
    </div>
    <div class="tkt-modal-body">
      <div class="tkt-resumen">
        <div class="row"><span class="val">Completa los datos de tu clínica para que el campo "Datos del negocio" se llene automáticamente y puedas enviar el ticket.</span></div>
      </div>
    </div>
    <div class="tkt-modal-actions">
      <a href="{{ route('configuracion') }}?tab=perfil" class="tkt-modal-btn mensajes" style="text-decoration:none">Completar perfil →</a>
      <button class="tkt-modal-btn guardar" id="btnPerfilModalCerrar" type="button">Cerrar</button>
    </div>
  </div>
</div>

{{-- Modal: Ticket creado --}}
<div class="tkt-modal-overlay" id="tktModalOverlay" style="display:none">
  <div class="tkt-modal">
    <div class="tkt-modal-header">
      <div class="tkt-modal-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
      <h2>Ticket creado exitosamente</h2>
      <p>Tu ticket ha sido registrado. A continuación puedes ver el resumen.</p>
      <button class="tkt-modal-close" id="tktModalClose" type="button">×</button>
      <button class="tkt-modal-ver" id="btnVerTicket" type="button" title="Ver ticket">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
      </button>
    </div>

    <div class="tkt-modal-body" id="tktModalBody">
    </div>

    <div class="tkt-modal-actions">
      <div class="tkt-msg-wrap">
        <button class="tkt-modal-btn mensajes" id="btnEnviarMensajes" type="button">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          Enviar a Mensajes
        </button>
        <div class="tkt-contactos" id="tktContactos" style="display:none">
          <div class="tkt-contacto" data-id="1">
            <div class="tkt-contacto-avatar">JR</div>
            <div><strong>Juan Rodríguez</strong><span>Soporte técnico</span></div>
          </div>
          <div class="tkt-contacto" data-id="2">
            <div class="tkt-contacto-avatar">ML</div>
            <div><strong>María López</strong><span>Facturación</span></div>
          </div>
          <div class="tkt-contacto" data-id="3">
            <div class="tkt-contacto-avatar">CP</div>
            <div><strong>Carlos Pérez</strong><span>Administrador</span></div>
          </div>
          <div class="tkt-contacto" data-id="4">
            <div class="tkt-contacto-avatar">AS</div>
            <div><strong>Ana Sánchez</strong><span>Atención al cliente</span></div>
          </div>
        </div>
      </div>
      <button class="tkt-modal-btn guardar" id="btnGuardarDispositivo" type="button">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Descargar PDF
      </button>
      <button class="tkt-modal-btn imprimir" id="btnImprimirTicket" type="button">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Imprimir
      </button>
    </div>
  </div>
</div>

<script src="/js/tickets.js"></script>
<script>
(function(){
  var cat = document.getElementById('tktCategoria');
  var paymentRow = document.getElementById('tktPaymentMethodRow');
  function togglePayment(){
    if(!cat || !paymentRow) return;
    if(cat.value === 'facturacion'){
      paymentRow.style.display = '';
    } else {
      paymentRow.style.display = 'none';
      var pm = document.getElementById('tktMetodoPago');
      if(pm) pm.value = '';
    }
  }
  if(cat) cat.addEventListener('change', togglePayment);
  togglePayment();

  // Contador de caracteres - Descripción
  var descArea = document.getElementById('tktDescripcion');
  var descCount = document.getElementById('tktDescCount');
  if(descArea && descCount){
    descArea.addEventListener('input', function(){
      var len = descArea.value.length;
      descCount.textContent = len + ' / 4000';
      descCount.style.color = len >= 3800 ? '#f87171' : '';
    });
  }

  // Custom dropdown - Categoría
  var catTrigger = document.getElementById('tktCategoriaTrigger');
  var catOptions = document.getElementById('tktCategoriaOptions');
  var catLabel = document.getElementById('tktCategoriaLabel');

  function closeCatDropdown(){
    if(catTrigger) catTrigger.classList.remove('open');
    if(catOptions) catOptions.classList.remove('open');
  }

  function openCatDropdown(){
    if(catTrigger) catTrigger.classList.add('open');
    if(catOptions) catOptions.classList.add('open');
  }

  function setCatValue(value, text){
    if(!cat) return;
    cat.value = value;
    if(catLabel) catLabel.textContent = text || (value === '' ? 'Selecciona una categoría' : value);
    cat.dispatchEvent(new Event('change'));
  }

  if(catTrigger && catOptions){
    catTrigger.addEventListener('click', function(e){
      e.stopPropagation();
      var isOpen = catTrigger.classList.contains('open');
      if(isOpen) closeCatDropdown(); else openCatDropdown();
    });

    catOptions.querySelectorAll('.tkt-option').forEach(function(opt){
      opt.addEventListener('click', function(e){
        e.stopPropagation();
        catOptions.querySelectorAll('.tkt-option').forEach(function(o){ o.classList.remove('selected'); });
        opt.classList.add('selected');
        setCatValue(opt.dataset.value, opt.textContent.trim());
        closeCatDropdown();
      });
    });

    document.addEventListener('click', function(e){
      if(!catTrigger.contains(e.target) && !catOptions.contains(e.target)){
        closeCatDropdown();
      }
    });
  }
})();
</script>
@endpush