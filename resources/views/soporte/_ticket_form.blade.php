@push('styles')
<style>
/* ── Ticket form ── */
.tkt-form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px}
.tkt-form-row.full{grid-template-columns:1fr}
.tkt-field label{font-size:13px;font-weight:600;color:var(--txt-soft);display:block;margin-bottom:6px}
.tkt-field select,
.tkt-field input,
.tkt-field textarea{
  width:100%;padding:10px 14px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--panel);color:var(--txt);
  font-size:14px;resize:vertical;
}
.tkt-field textarea{min-height:100px}
.tkt-form-footer{display:flex;align-items:center;justify-content:space-between;margin-top:16px}
.tkt-adjuntar{
  display:flex;align-items:center;gap:8px;
  padding:8px 14px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--panel);
  font-size:13px;color:var(--txt-soft);cursor:pointer;
}
.tkt-adjuntar span{font-size:11px;color:var(--txt-soft)}
.tkt-btn-enviar{
  padding:10px 24px;border-radius:var(--r-md);border:0;
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  font-size:14px;font-weight:600;cursor:pointer;
  display:flex;align-items:center;gap:8px;transition:opacity .15s;
}
.tkt-btn-enviar:hover{opacity:.85}
.tkt-form-actions{display:flex;align-items:center;gap:10px}
.tkt-btn-mis-tickets{
  padding:10px 24px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:transparent;color:var(--txt);
  font-size:14px;font-weight:600;text-decoration:none;
  display:flex;align-items:center;gap:8px;transition:background .15s;
}
.tkt-btn-mis-tickets:hover{background:rgba(110,160,255,.1)}

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
  width:100%;max-width:560px;max-height:80vh;overflow-y:auto;
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
  padding:16px;display:flex;flex-direction:column;gap:10px;
}
.tkt-modal-body .tkt-resumen .row{display:flex;gap:8px}
.tkt-modal-body .tkt-resumen .row .lbl{font-weight:600;color:var(--txt-soft);min-width:140px;flex-shrink:0}
.tkt-modal-body .tkt-resumen .row .val{color:var(--txt)}

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
</style>
@endpush

<div class="tkt-panel" id="panelCrear">
 

  @php
    $businessData = '';
    $operationData = '';
    if (!empty($latestTicket)) {
        $businessData = $latestTicket->business_name ?? '';
        $operationData = $latestTicket->operation_folio ?? '';
    }
  @endphp

  <div class="tkt-form-row">
    <div class="tkt-field">
      <label>Categoría</label>
      <select id="tktCategoria">
        <option value="">Selecciona una categoría</option>
        <option value="facturacion">Facturación</option>
        <option value="tecnico">Problema técnico</option>
        <option value="funcion">Solicitud de función</option>
        <option value="como-hacer">Cómo hacer</option>
        <option value="otro">Otro</option>
      </select>
    </div>
    <div class="tkt-field">
      <label>Prioridad</label>
      <select id="tktPrioridad">
        <option value="media">Media</option>
        <option value="alta">Alta</option>
        <option value="baja">Baja</option>
      </select>
    </div>
  </div>

  <div class="tkt-form-row full">
    <div class="tkt-field">
      <label>Asunto</label>
      <input type="text" id="tktAsunto" placeholder="Describe brevemente tu problema">
    </div>
  </div>

  <div class="tkt-form-row full">
    <div class="tkt-field">
      <label>Descripción</label>
      <textarea id="tktDescripcion" placeholder="Proporciona tantos detalles como sea posible..."></textarea>
    </div>
  </div>

  {{-- Datos del negocio y operación (solo lectura, vienen de la BD) --}}
  <div class="tkt-form-row">
    <div class="tkt-field">
      <label>Datos del negocio</label>
      <input type="text" id="tktNegocio" value="{{ $businessData }}" readonly placeholder="Nombre o razón social, domicilio fiscal, RFC">
    </div>
    <div class="tkt-field">
      <label>Datos de la operación</label>
      <input type="text" id="tktOperacion" value="{{ $operationData }}" readonly placeholder="Folio único, fecha y hora de la compra">
    </div>
  </div>

  <div class="tkt-form-row full" id="tktPaymentMethodRow" style="display:none">
    <div class="tkt-field">
      <label>Método de pago</label>
      <select id="tktMetodoPago">
        <option value="">Selecciona método de pago</option>
        <option value="efectivo">Efectivo</option>
        <option value="tarjeta">Tarjeta</option>
        <option value="transferencia">Transferencia</option>
      </select>
    </div>
  </div>

  <div class="tkt-form-footer">
    <label class="tkt-adjuntar">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
      Adjuntar archivo
      <span>Tamaño máx. de archivo: 10MB</span>
      <input type="file" id="tktAttachment" style="display:none">
    </label>
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
})();
</script>
@endpush