@extends('layouts.app')
@section('active', 'soporte')
@section('title', 'Mis Tickets')
@section('header-title', 'Tickets')
@section('header-sub', '¿Cómo podemos ayudarte hoy?')

@push('styles')
<style>
/* ── Tickets layout ── */
.tkt-grid{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start}
.tkt-main{display:flex;flex-direction:column;gap:24px}

/* Card base */
.tkt-card{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:24px}
.tkt-card h2{font-size:16px;font-weight:700;margin-bottom:4px}
.tkt-card .sub{font-size:13px;color:var(--txt-soft);margin-bottom:16px}

/* Crear nuevo ticket */
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

/* Tabla tickets activos */
.tkt-table-wrap{overflow-x:auto}
.tkt-table{width:100%;border-collapse:collapse;font-size:13px}
.tkt-table th{
  text-align:left;padding:10px 12px;font-size:12px;font-weight:600;
  color:var(--txt-soft);text-transform:uppercase;letter-spacing:.04em;
  border-bottom:1px solid var(--stroke);
}
.tkt-table td{padding:10px 12px;border-bottom:1px solid var(--stroke)}
.tkt-table tr:last-child td{border-bottom:0}
.tkt-table tr:hover td{background:rgba(110,160,255,.04)}
.tkt-id{color:var(--txt-soft);font-weight:600}
.tkt-badge{
  display:inline-block;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;
}
.tkt-badge.progreso{background:rgba(96,165,250,.15);color:#60a5fa}
.tkt-badge.abierto{background:rgba(168,130,255,.15);color:#a78bfa}
.tkt-badge.resuelto{background:rgba(74,222,128,.15);color:#4ade80}
.tkt-prioridad{display:flex;align-items:center;gap:6px}
.tkt-prioridad .dot{width:8px;height:8px;border-radius:50%}
.tkt-prioridad .dot.alta{background:#f87171}
.tkt-prioridad .dot.media{background:#fbbf24}
.tkt-prioridad .dot.baja{background:#60a5fa}
.tkt-arrow{color:var(--txt-soft);font-size:14px}
.tkt-ver-todos{font-size:13px;color:var(--blue);text-decoration:none;display:inline-block;margin-top:12px}
.tkt-ver-todos:hover{text-decoration:underline}

/* ── Sidebar derecho ── */
.tkt-side{display:flex;flex-direction:column;gap:20px}

/* Buscar FAQ */
.tkt-search{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:16px;
}
.tkt-search input{
  width:100%;padding:10px 14px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--panel);color:var(--txt);font-size:14px;
}

/* Preguntas frecuentes */
.tkt-faq{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:20px}
.tkt-faq h2{font-size:15px;font-weight:700;margin-bottom:4px}
.tkt-faq .sub{font-size:12px;color:var(--txt-soft);margin-bottom:14px}
.tkt-faq-item{
  display:flex;align-items:center;justify-content:space-between;
  padding:12px 0;border-bottom:1px solid var(--stroke);cursor:pointer;
  transition:color .15s;
}
.tkt-faq-item:last-child{border-bottom:0}
.tkt-faq-item:hover{color:var(--blue)}
.tkt-faq-item span{font-size:13px}
.tkt-faq-item .chevron{color:var(--txt-soft);font-size:14px;transition:transform .15s}

/* Ayuda / Volver a soporte */
.tkt-ayuda{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:20px;
  display:flex;align-items:center;gap:14px;
}
.tkt-ayuda .info{flex:1}
.tkt-ayuda .info strong{font-size:14px;display:block}
.tkt-ayuda .info p{font-size:12px;color:var(--txt-soft);margin:0}
.tkt-ayuda .btn-volver{
  padding:9px 18px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:transparent;color:var(--txt);
  font-size:13px;font-weight:600;text-decoration:none;white-space:nowrap;
  transition:background .15s;
}
.tkt-ayuda .btn-volver:hover{background:rgba(110,160,255,.1)}

/* AI Chat sidebar */
.tkt-ai-chat{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);
  display:flex;flex-direction:column;height:calc(100vh - 180px);min-height:360px;overflow:hidden;
}
.tkt-ai-header{
  display:flex;align-items:center;gap:10px;
  padding:14px 16px;border-bottom:1px solid var(--stroke);flex-shrink:0;
}
.tkt-ai-avatar{
  width:34px;height:34px;border-radius:50%;
  background:linear-gradient(135deg,var(--blue),var(--cyan));
  display:grid;place-items:center;flex-shrink:0;
}
.tkt-ai-header strong{font-size:13px;display:block}
.tkt-ai-status{font-size:11px;color:#4ade80}
.tkt-ai-messages{
  flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;
}
.tkt-ai-messages::-webkit-scrollbar{width:3px}
.tkt-ai-messages::-webkit-scrollbar-thumb{background:var(--stroke);border-radius:3px}
.tkt-ai-msg{max-width:90%}
.tkt-ai-msg.bot{align-self:flex-start}
.tkt-ai-msg.user{align-self:flex-end}
.tkt-ai-bubble{
  padding:10px 14px;border-radius:12px;font-size:13px;line-height:1.45;
}
.tkt-ai-msg.bot .tkt-ai-bubble{
  background:var(--panel);border:1px solid var(--stroke);border-top-left-radius:4px;
}
.tkt-ai-msg.user .tkt-ai-bubble{
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;border-top-right-radius:4px;
}
.tkt-ai-input-wrap{
  display:flex;gap:8px;padding:12px 14px;border-top:1px solid var(--stroke);flex-shrink:0;
}
.tkt-ai-input-wrap input{
  flex:1;padding:9px 12px;border-radius:99px;font-size:13px;
  border:1px solid var(--stroke);background:var(--panel);color:var(--txt);
}
.tkt-ai-input-wrap input:focus{outline:none;border-color:var(--blue)}
.tkt-ai-input-wrap button{
  width:34px;height:34px;border-radius:50%;border:0;
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  display:grid;place-items:center;cursor:pointer;flex-shrink:0;
  transition:opacity .15s;
}
.tkt-ai-input-wrap button:hover{opacity:.85}

/* Tabs */
.tkt-tabs{
  display:flex;gap:0;border-bottom:1px solid var(--stroke);margin-bottom:20px;
}
.tkt-tab{
  display:flex;align-items:center;gap:8px;
  padding:12px 20px;font-size:14px;font-weight:600;
  border:0;background:transparent;color:var(--txt-soft);
  cursor:pointer;border-bottom:2px solid transparent;
  transition:color .15s,border-color .15s;
}
.tkt-tab:hover{color:var(--txt)}
.tkt-tab.active{color:var(--blue);border-bottom-color:var(--blue)}
.tkt-panel{margin-top:4px}

/* Responsive */
@media (max-width:1100px){
  .tkt-grid{grid-template-columns:1fr}
  .tkt-side{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px}
}
@media (max-width:600px){
  .tkt-form-row{grid-template-columns:1fr}
}
</style>
@endpush

@section('content')
<div class="tkt-grid">

  {{-- ============ COLUMNA PRINCIPAL ============ --}}
  <div class="tkt-main">

    {{-- Pestañas --}}
    <div class="tkt-card">
      <div class="tkt-tabs">
        <button class="tkt-tab active" data-tab="crear" type="button">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
          Crear nuevo ticket
        </button>
        <button class="tkt-tab" data-tab="activos" type="button">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          Tickets activos
        </button>
      </div>

      {{-- Panel: Crear nuevo ticket --}}
      <div class="tkt-panel" id="panelCrear">
        <p class="sub">Selecciona una categoría y proporciona los detalles de tu problema.</p>

        <div class="tkt-form-row">
          <div class="tkt-field">
            <label>Categoría</label>
            <select>
              <option value="">Selecciona una categoría</option>
              <option value="facturacion">Facturación</option>
              <option value="tecnico">Problema técnico</option>
              <option value="api">API & Integraciones</option>
              <option value="funcion">Solicitud de función</option>
              <option value="como-hacer">Cómo hacer</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <div class="tkt-field">
            <label>Prioridad</label>
            <select>
              <option value="media">Media</option>
              <option value="alta">Alta</option>
              <option value="baja">Baja</option>
            </select>
          </div>
        </div>

        <div class="tkt-form-row full">
          <div class="tkt-field">
            <label>Asunto</label>
            <input type="text" placeholder="Describe brevemente tu problema">
          </div>
        </div>

        <div class="tkt-form-row full">
          <div class="tkt-field">
            <label>Descripción</label>
            <textarea id="tktDescripcion" placeholder="Proporciona tantos detalles como sea posible..."></textarea>
          </div>
        </div>

        <div class="tkt-form-row">
          <div class="tkt-field">
            <label>Datos del negocio</label>
            <input type="text" id="tktNegocio" placeholder="Nombre o razón social, domicilio fiscal, RFC">
          </div>
          <div class="tkt-field">
            <label>Datos de la operación</label>
            <input type="text" id="tktOperacion" placeholder="Folio único, fecha y hora de la compra">
          </div>
        </div>

        <div class="tkt-form-row full">
          <div class="tkt-field">
            <label>Conceptos</label>
            <input type="text" id="tktConceptos" placeholder="Descripción de productos o servicios, cantidad y precio unitario">
          </div>
        </div>

        <div class="tkt-form-row">
          <div class="tkt-field">
            <label>Totales</label>
            <input type="text" id="tktTotales" placeholder="Subtotal, impuestos (IVA) e importe total">
          </div>
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
            <input type="file" style="display:none">
          </label>
          <button class="tkt-btn-enviar" type="button" id="btnEnviarTicket" onclick="window.__enviarTicket()">
            Enviar ticket
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
        </div>
      </div>

      {{-- Panel: Tickets activos --}}
      <div class="tkt-panel" id="panelActivos" style="display:none">
        <p class="sub">Tus tickets creados recientemente.</p>

        <div class="tkt-table-wrap">
          <table class="tkt-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Asunto</th>
                <th>Categoría</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Actualizado</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="tkt-id">#1248</td>
                <td>No puedo acceder al portal de facturación</td>
                <td>Facturación</td>
                <td><span class="tkt-prioridad"><span class="dot alta"></span> Alta</span></td>
                <td><span class="tkt-badge progreso">En progreso</span></td>
                <td>Hace 2h</td>
                <td class="tkt-arrow">›</td>
              </tr>
              <tr>
                <td class="tkt-id">#1247</td>
                <td>Error al generar factura</td>
                <td>API & Integraciones</td>
                <td><span class="tkt-prioridad"><span class="dot media"></span> Media</span></td>
                <td><span class="tkt-badge abierto">Abierto</span></td>
                <td>Hace 5h</td>
                <td class="tkt-arrow">›</td>
              </tr>
              <tr>
                <td class="tkt-id">#1246</td>
                <td>Solicitud de nueva función: Modo oscuro</td>
                <td>Solicitud de función</td>
                <td><span class="tkt-prioridad"><span class="dot baja"></span> Baja</span></td>
                <td><span class="tkt-badge abierto">Abierto</span></td>
                <td>Hace 1d</td>
                <td class="tkt-arrow">›</td>
              </tr>
              <tr>
                <td class="tkt-id">#1245</td>
                <td>Error en generación de factura</td>
                <td>Facturación</td>
                <td><span class="tkt-prioridad"><span class="dot alta"></span> Alta</span></td>
                <td><span class="tkt-badge resuelto">Resuelto</span></td>
                <td>Hace 2d</td>
                <td class="tkt-arrow">›</td>
              </tr>
              <tr>
                <td class="tkt-id">#1244</td>
                <td>¿Cómo exportar datos analíticos?</td>
                <td>Cómo hacer</td>
                <td><span class="tkt-prioridad"><span class="dot media"></span> Media</span></td>
                <td><span class="tkt-badge progreso">En progreso</span></td>
                <td>Hace 3d</td>
                <td class="tkt-arrow">›</td>
              </tr>
            </tbody>
          </table>
        </div>
        <a class="tkt-ver-todos" href="#">Ver todos los tickets →</a>
      </div>
    </div>

  </div>

  {{-- ============ SIDEBAR DERECHO ============ --}}
  <div class="tkt-side">

    {{-- Asistente IA --}}
    <div class="tkt-ai-chat">
      <div class="tkt-ai-header">
        <div class="tkt-ai-avatar">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4v4"/><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M9 13h.01"/><path d="M15 13h.01"/><path d="M10 17s1 1 2 1 2-1 2-1"/></svg>
        </div>
        <div>
          <strong>Asistente IA</strong>
          <span class="tkt-ai-status">En línea</span>
        </div>
      </div>

      <div class="tkt-ai-messages" id="tktAiMessages">
        <div class="tkt-ai-msg bot">
          <div class="tkt-ai-bubble">¡Hola! Soy tu asistente de IA. ¿Tienes alguna duda sobre cómo crear tu ticket o necesitas ayuda?</div>
        </div>
      </div>

      <div class="tkt-ai-input-wrap">
        <input type="text" id="tktAiInput" placeholder="Escribe tu pregunta...">
        <button type="button" id="tktAiSend">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
      </div>
    </div>

  </div>

</div>

@endsection

@push('styles')
<style>
/* Modal overlay */
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

/* Ver ticket button */
.tkt-modal-ver{
  position:absolute;top:16px;left:16px;
  width:32px;height:32px;border-radius:50%;border:0;
  background:var(--panel);color:var(--txt);
  cursor:pointer;display:grid;place-items:center;
  transition:background .15s;
}
.tkt-modal-ver:hover{background:rgba(110,160,255,.15)}

/* Contacts dropdown */
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
@endpush
