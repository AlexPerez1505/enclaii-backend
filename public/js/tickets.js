var __tktData = {};
var __tktOverlay, __tktBody;

function __tktValor(id, def){
  var el = document.getElementById(id);
  return el ? el.value : (def || '');
}

function __tktTexto(id, def){
  var el = document.getElementById(id);
  var v = el ? el.value.trim() : '';
  return v || (def || '-');
}

function __mostrarResumen(ticket){
  var fecha = new Date().toLocaleDateString('es-MX', {day:'2-digit', month:'short', year:'numeric'});
  var hora = new Date().toLocaleTimeString('es-MX', {hour:'2-digit', minute:'2-digit'});
  var id = '#' + ticket.id;
  var cat = __tktTexto('tktCategoria', 'Sin categoria');
  var prio = __tktTexto('tktPrioridad', 'Media');
  var asunto = __tktTexto('tktAsunto', 'Sin asunto');
  var desc = __tktTexto('tktDescripcion', 'Sin descripcion');
  var negocio = __tktTexto('tktNegocio');
  var operacion = __tktTexto('tktOperacion');
  var conceptos = __tktTexto('tktConceptos');
  var totales = __tktTexto('tktTotales');
  var metodo = __tktTexto('tktMetodoPago');

  __tktData = {id:id, fecha:fecha, hora:hora, cat:cat, prio:prio, asunto:asunto, desc:desc, negocio:negocio, operacion:operacion, conceptos:conceptos, totales:totales, metodo:metodo};

  __tktBody.innerHTML =
    '<div class="tkt-resumen">' +
    '<div class="row"><span class="lbl">ID del ticket:</span><span class="val">' + id + '</span></div>' +
    '<div class="row"><span class="lbl">Fecha:</span><span class="val">' + fecha + '</span></div>' +
    '<div class="row"><span class="lbl">Categoria:</span><span class="val">' + cat + '</span></div>' +
    '<div class="row"><span class="lbl">Prioridad:</span><span class="val">' + prio + '</span></div>' +
    '<div class="row"><span class="lbl">Asunto:</span><span class="val">' + asunto + '</span></div>' +
    '<div class="row"><span class="lbl">Descripcion:</span><span class="val">' + desc + '</span></div>' +
    '<div class="row"><span class="lbl">Datos del negocio:</span><span class="val">' + negocio + '</span></div>' +
    '<div class="row"><span class="lbl">Datos de operacion:</span><span class="val">' + operacion + '</span></div>' +
    '<div class="row"><span class="lbl">Conceptos:</span><span class="val">' + conceptos + '</span></div>' +
    '<div class="row"><span class="lbl">Totales:</span><span class="val">' + totales + '</span></div>' +
    '<div class="row"><span class="lbl">Metodo de pago:</span><span class="val">' + metodo + '</span></div>' +
    '</div>';

  __tktOverlay.style.display = 'grid';
}

window.__enviarTicket = function(){
  __tktOverlay = document.getElementById('tktModalOverlay');
  __tktBody = document.getElementById('tktModalBody');
  var btn = document.getElementById('btnEnviarTicket');
  if(!__tktOverlay || !__tktBody) { alert('Error: elementos no encontrados'); return; }

  var cat = __tktValor('tktCategoria');
  var asunto = __tktValor('tktAsunto');
  var desc = __tktValor('tktDescripcion');

  if(!cat || !asunto || !desc){
    alert('Por favor completa al menos categoria, asunto y descripcion.');
    return;
  }

  var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  var formData = new FormData();
  formData.append('category', cat);
  formData.append('priority', __tktValor('tktPrioridad', 'media'));
  formData.append('subject', asunto);
  formData.append('description', desc);
  formData.append('business_name', __tktValor('tktNegocio'));
  formData.append('operation_folio', __tktValor('tktOperacion'));
  formData.append('concepts', __tktValor('tktConceptos'));
  formData.append('totals', __tktValor('tktTotales'));
  formData.append('payment_method', __tktValor('tktMetodoPago'));

  var fileInput = document.getElementById('tktAttachment');
  if(fileInput && fileInput.files[0]){
    formData.append('attachment', fileInput.files[0]);
  }

  if(btn){
    btn.disabled = true;
    btn.innerHTML = 'Guardando...';
  }

  fetch('/soporte/tickets', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrf,
      'Accept': 'application/json'
    },
    body: formData
  })
  .then(function(r){ return r.json(); })
  .then(function(data){
    if(btn){
      btn.disabled = false;
      btn.innerHTML = 'Enviar ticket <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>';
    }
    if(data.ok){
      __mostrarResumen(data.ticket);
      // limpiar formulario
      ['tktCategoria','tktPrioridad','tktAsunto','tktDescripcion','tktNegocio','tktOperacion','tktConceptos','tktTotales','tktMetodoPago','tktAttachment'].forEach(function(id){
        var el = document.getElementById(id);
        if(el) el.value = '';
      });
    } else {
      alert('No se pudo guardar el ticket. Intenta de nuevo.');
    }
  })
  .catch(function(err){
    if(btn){
      btn.disabled = false;
      btn.innerHTML = 'Enviar ticket <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>';
    }
    alert('Error de conexion. No se pudo guardar el ticket.');
  });
};

function __generarTicketHTML(d){
  return '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Ticket ' + d.id + '</title>' +
    '<style>' +
    '@page{size:A4;margin:20mm}' +
    '*{box-sizing:border-box}' +
    'body{font-family:"Segoe UI","Helvetica Neue",Arial,sans-serif;margin:0;padding:40px;color:#1a1a2e;background:#fff;font-size:13px;line-height:1.5}' +
    '.ticket{max-width:600px;margin:0 auto;border:1px solid #e0e0e0;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06)}' +
    '.ticket-header{background:linear-gradient(135deg,#1e3a5f 0%,#2e7bf6 100%);padding:32px 30px 24px;text-align:center;color:#fff}' +
    '.ticket-header .logo-wrap{width:48px;height:48px;background:rgba(255,255,255,0.15);border-radius:50%;margin:0 auto 12px;display:flex;align-items:center;justify-content:center}' +
    '.ticket-header .logo-wrap svg{width:28px;height:28px}' +
    '.ticket-header h1{font-size:20px;font-weight:700;margin:0 0 4px;letter-spacing:2px}' +
    '.ticket-header p{font-size:11px;margin:0;opacity:0.8;text-transform:uppercase;letter-spacing:1.5px}' +
    '.ticket-id{text-align:center;padding:18px;background:#f8fafc;border-bottom:1px solid #e8ecf0}' +
    '.ticket-id span{font-size:28px;font-weight:800;color:#1e3a5f;letter-spacing:1px}' +
    '.ticket-id .date{font-size:11px;color:#6b7280;margin-top:4px;display:block}' +
    '.ticket-body{padding:24px 30px}' +
    '.section-title{font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:#6b7280;font-weight:700;margin:0 0 10px;padding-bottom:6px;border-bottom:2px solid #e8ecf0}' +
    '.section{margin-bottom:20px}' +
    '.row{display:flex;justify-content:space-between;align-items:flex-start;padding:7px 0;border-bottom:1px solid #f3f4f6}' +
    '.row:last-child{border-bottom:0}' +
    '.lbl{font-weight:600;color:#374151;font-size:12px}' +
    '.val{color:#1a1a2e;font-size:12px;text-align:right;max-width:280px;word-break:break-word}' +
    '.ticket-footer{background:#f8fafc;padding:16px 30px;border-top:1px solid #e8ecf0;text-align:center}' +
    '.ticket-footer .ref{font-family:monospace;font-size:10px;color:#9ca3af;letter-spacing:2px;margin-bottom:6px}' +
    '.ticket-footer p{font-size:11px;color:#6b7280;margin:2px 0}' +
    '.badge{display:inline-block;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600}' +
    '.badge-alta{background:#fee2e2;color:#dc2626}' +
    '.badge-media{background:#fef3c7;color:#d97706}' +
    '.badge-baja{background:#d1fae5;color:#059669}' +
    '</style></head><body>' +
    '<div class="ticket">' +
    '<div class="ticket-header">' +
    '<div class="logo-wrap"><svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="42" stroke="#fff" stroke-width="3"/><text x="50" y="60" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="28" fill="#fff">E</text></svg></div>' +
    '<h1>ENCLAII</h1>' +
    '<p>Ticket de Soporte</p>' +
    '</div>' +
    '<div class="ticket-id"><span>' + d.id + '</span><span class="date">' + d.fecha + ' - ' + d.hora + '</span></div>' +
    '<div class="ticket-body">' +
    '<div class="section"><div class="section-title">Informacion del Ticket</div>' +
    '<div class="row"><span class="lbl">Categoria</span><span class="val">' + d.cat + '</span></div>' +
    '<div class="row"><span class="lbl">Prioridad</span><span class="val"><span class="badge badge-' + d.prio.toLowerCase() + '">' + d.prio + '</span></span></div>' +
    '<div class="row"><span class="lbl">Asunto</span><span class="val">' + d.asunto + '</span></div>' +
    '<div class="row"><span class="lbl">Descripcion</span><span class="val">' + d.desc + '</span></div>' +
    '</div>' +
    '<div class="section"><div class="section-title">Datos de la Operacion</div>' +
    '<div class="row"><span class="lbl">Negocio</span><span class="val">' + d.negocio + '</span></div>' +
    '<div class="row"><span class="lbl">Operacion</span><span class="val">' + d.operacion + '</span></div>' +
    '<div class="row"><span class="lbl">Conceptos</span><span class="val">' + d.conceptos + '</span></div>' +
    '<div class="row"><span class="lbl">Totales</span><span class="val">' + d.totales + '</span></div>' +
    '<div class="row"><span class="lbl">Metodo de Pago</span><span class="val">' + d.metodo + '</span></div>' +
    '</div>' +
    '</div>' +
    '<div class="ticket-footer">' +
    '<div class="ref">REF: ' + d.id.replace('#','') + '-' + d.fecha.replace(/ /g,'') + '</div>' +
    '<p>Conserve este ticket como comprobante</p>' +
    '<p>Soporte ENCLAII 24/7 | www.enclaii.com</p>' +
    '</div>' +
    '</div></body></html>';
}

(function(){
  var tabs = document.querySelectorAll('.tkt-tab');
  var panelCrear = document.getElementById('panelCrear');
  var panelActivos = document.getElementById('panelActivos');

  tabs.forEach(function(tab){
    tab.addEventListener('click', function(){
      tabs.forEach(function(t){ t.classList.remove('active'); });
      tab.classList.add('active');
      if(tab.dataset.tab === 'crear'){
        panelCrear.style.display = '';
        panelActivos.style.display = 'none';
      } else {
        panelCrear.style.display = 'none';
        panelActivos.style.display = '';
      }
    });
  });

  var overlay = document.getElementById('tktModalOverlay');
  var closeBtn = document.getElementById('tktModalClose');
  var guardarBtn = document.getElementById('btnGuardarDispositivo');
  var imprimirBtn = document.getElementById('btnImprimirTicket');
  var verBtn = document.getElementById('btnVerTicket');
  var msgBtn = document.getElementById('btnEnviarMensajes');
  var contactos = document.getElementById('tktContactos');

  function closeModal(){
    if(overlay) overlay.style.display = 'none';
    if(contactos) contactos.style.display = 'none';
  }
  if(closeBtn) closeBtn.addEventListener('click', closeModal);
  if(overlay) overlay.addEventListener('click', function(e){ if(e.target === overlay) closeModal(); });

  if(msgBtn) msgBtn.addEventListener('click', function(e){
    e.stopPropagation();
    if(contactos) contactos.style.display = contactos.style.display === 'none' ? 'block' : 'none';
  });

  if(contactos) contactos.querySelectorAll('.tkt-contacto').forEach(function(c){
    c.addEventListener('click', function(){
      window.location.href = '/mensajes?to=' + c.dataset.id;
    });
  });

  document.addEventListener('click', function(){ if(contactos) contactos.style.display = 'none'; });

  if(verBtn) verBtn.addEventListener('click', function(){
    if (!__tktData.id) return;
    var html = __generarTicketHTML(__tktData);
    var win = window.open('', '_blank', 'width=700,height=900');
    win.document.write(html);
    win.document.close();
  });

  if(guardarBtn) guardarBtn.addEventListener('click', function(){
    if (!__tktData.id) return;
    var html = __generarTicketHTML(__tktData);

    function doDownload(){
      var container = document.createElement('div');
      container.style.position = 'fixed';
      container.style.left = '-9999px';
      container.style.top = '0';
      container.style.width = '700px';
      container.innerHTML = html.replace(/^.*<body>/,'').replace(/<\/body>.*$/,'');
      document.body.appendChild(container);

      var style = document.createElement('style');
      var cssMatch = html.match(/<style>([\s\S]*?)<\/style>/);
      if(cssMatch) style.textContent = cssMatch[1];
      container.prepend(style);

      html2pdf().set({
        margin: 10,
        filename: 'ticket-' + __tktData.id.replace('#','') + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
      }).from(container).save().then(function(){
        document.body.removeChild(container);
      });
    }

    if(typeof html2pdf === 'undefined'){
      var script = document.createElement('script');
      script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
      script.onload = doDownload;
      document.head.appendChild(script);
    } else {
      doDownload();
    }
  });

  if(imprimirBtn) imprimirBtn.addEventListener('click', function(){
    if (!__tktData.id) return;
    var html = __generarTicketHTML(__tktData);
    var win = window.open('', '_blank', 'width=700,height=900');
    win.document.write(html);
    win.document.close();
    setTimeout(function(){ win.print(); }, 500);
  });

  var aiMessages = document.getElementById('tktAiMessages');
  var aiInput = document.getElementById('tktAiInput');
  var aiSend = document.getElementById('tktAiSend');

  var aiResponses = {
    'categoria': 'Selecciona la categoria que mejor describa tu problema: Facturacion, Problema tecnico, API & Integraciones, Solicitud de funcion, Como hacer, u Otro.',
    'prioridad': 'Usa prioridad Alta si tu problema impide trabajar, Media si afecta parcialmente, y Baja para sugerencias o mejoras.',
    'archivo': 'Puedes adjuntar archivos de hasta 10MB. Formatos admitidos: JPG, PNG, PDF, ZIP.',
    'tiempo': 'El tiempo promedio de respuesta es de 2 a 4 horas para tickets de prioridad alta, y hasta 24 horas para prioridad media o baja.',
    'estado': 'Los estados posibles son: Abierto (recibido), En progreso (siendo atendido) y Resuelto (solucionado).',
    'datos': 'En "Datos del negocio" incluye nombre o razon social, domicilio fiscal y RFC. En "Datos de la operacion" incluye el numero de folio, fecha y hora.',
    'conceptos': 'En Conceptos describe los productos o servicios, la cantidad y el precio unitario de cada uno.',
    'totales': 'En Totales indica el subtotal, los impuestos desglosados (IVA) y el importe total a pagar.',
    'pago': 'Selecciona el metodo de pago utilizado: efectivo, tarjeta o transferencia.',
  };

  function addAiMsg(text, isUser){
    var div = document.createElement('div');
    div.className = 'tkt-ai-msg ' + (isUser ? 'user' : 'bot');
    div.innerHTML = '<div class="tkt-ai-bubble">' + text.replace(/\n/g,'<br>') + '</div>';
    aiMessages.appendChild(div);
    aiMessages.scrollTop = aiMessages.scrollHeight;
  }

  function getAiResponse(text){
    var lower = text.toLowerCase();
    for (var key in aiResponses){
      if (lower.includes(key)) return aiResponses[key];
    }
    return 'Puedo ayudarte con informacion sobre categorias, prioridades, archivos adjuntos, tiempos de respuesta, estados de tickets, datos del negocio, conceptos, totales y metodos de pago. Sobre que necesitas ayuda?';
  }

  function sendAiMsg(){
    var text = aiInput.value.trim();
    if (!text) return;
    addAiMsg(text, true);
    aiInput.value = '';
    setTimeout(function(){
      addAiMsg(getAiResponse(text), false);
    }, 600 + Math.random() * 600);
  }

  if(aiSend) aiSend.addEventListener('click', sendAiMsg);
  if(aiInput) aiInput.addEventListener('keydown', function(e){ if(e.key==='Enter') sendAiMsg(); });
})();
