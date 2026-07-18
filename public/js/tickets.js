var __tktData = {};
var __tktOverlay, __tktBody;

function __tktToast(msg){
  var el = document.getElementById('__tktToast');
  if(!el){
    el = document.createElement('div');
    el.id = '__tktToast';
    el.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(10px);background:#1e293b;color:#f87171;border:1px solid #f87171;padding:12px 22px;border-radius:12px;font-size:13px;font-weight:600;z-index:99999;box-shadow:0 12px 30px rgba(0,0,0,.5);opacity:0;transition:opacity .2s,transform .2s;pointer-events:none';
    document.body.appendChild(el);
  }
  el.textContent = msg;
  el.style.opacity = '1'; el.style.transform = 'translateX(-50%) translateY(0)';
  clearTimeout(el._h);
  el._h = setTimeout(function(){ el.style.opacity = '0'; el.style.transform = 'translateX(-50%) translateY(10px)'; }, 3500);
}

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
  var prio = ticket.priority ? ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1) : 'Media';
  var asunto = __tktTexto('tktAsunto', 'Sin asunto');
  var desc = __tktTexto('tktDescripcion', 'Sin descripcion');
  var negocio = __tktTexto('tktNegocio');
  var operacion = __tktTexto('tktOperacion');
  var metodo = __tktTexto('tktMetodoPago');

  __tktData = {
    id:id, fecha:fecha, hora:hora, cat:cat, prio:prio, asunto:asunto, desc:desc,
    negocio:negocio, operacion:operacion, metodo:metodo
  };

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
    '<div class="row"><span class="lbl">Metodo de pago:</span><span class="val">' + metodo + '</span></div>' +
    '</div>';

  __tktOverlay.style.display = 'grid';
}

window.__enviarTicket = function(){
  __tktOverlay = document.getElementById('tktModalOverlay');
  __tktBody = document.getElementById('tktModalBody');
  var btn = document.getElementById('btnEnviarTicket');
  if(!__tktOverlay || !__tktBody) { alert('Error: elementos no encontrados'); return; }

  var negocioInput = document.getElementById('tktNegocio');
  if(!negocioInput || !negocioInput.value.trim()){
    var perfilOverlay = document.getElementById('tktPerfilModalOverlay');
    if(perfilOverlay) perfilOverlay.style.display = 'grid';
    return;
  }

  var cat = __tktValor('tktCategoria');
  var asunto = __tktValor('tktAsunto');
  var desc = __tktValor('tktDescripcion');

  if(!cat || !asunto || !desc){
    var missing = [];
    if(!cat) missing.push('categoría');
    if(!asunto) missing.push('asunto');
    if(!desc) missing.push('descripción');
    __tktToast('Completa: ' + missing.join(', '));
    return;
  }

  if(cat === 'facturacion' && !__tktValor('tktMetodoPago')){
    __tktToast('Selecciona un método de pago para Facturación.');
    return;
  }

  var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  var formData = new FormData();
  formData.append('category', cat);
  formData.append('subject', asunto);
  formData.append('description', desc);
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
  .then(function(r){
    if(!r.ok){
      return r.json().then(function(errData){
        if(btn){ btn.disabled = false; btn.innerHTML = 'Enviar ticket <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>'; }
        var msgs = errData.errors ? Object.values(errData.errors).flat() : [errData.message || 'Error desconocido'];
        __tktToast(msgs[0]);
        throw new Error('validation');
      });
    }
    return r.json();
  })
  .then(function(data){
    if(!data) return;
    if(btn){
      btn.disabled = false;
      btn.innerHTML = 'Enviar ticket <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>';
    }
    if(data.ok){
      __mostrarResumen(data.ticket);
      // limpiar formulario
      ['tktCategoria','tktAsunto','tktDescripcion','tktMetodoPago','tktAttachment'].forEach(function(id){
        var el = document.getElementById(id);
        if(el) el.value = '';
      });
      // resetear custom dropdown visual
      var catLabel = document.getElementById('tktCategoriaLabel');
      if(catLabel) catLabel.textContent = 'Selecciona una categoría';
      var catOptions = document.getElementById('tktCategoriaOptions');
      if(catOptions) catOptions.querySelectorAll('.tkt-option').forEach(function(o){ o.classList.remove('selected'); });
      var paymentRow = document.getElementById('tktPaymentMethodRow');
      if(paymentRow) paymentRow.style.display = 'none';
      var attachmentName = document.getElementById('tktAttachmentName');
      if(attachmentName){ attachmentName.textContent = ''; attachmentName.style.display = 'none'; }
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
    '.ticket-header .logo-wrap{width:150px;height:auto;margin:0 auto 10px;display:flex;align-items:center;justify-content:center}' +
    '.ticket-header .logo-wrap img{width:100%;height:auto;display:block}' +
    '.ticket-header .brand-name{font-size:20px;font-weight:700;margin:0;letter-spacing:2px}' +
    '.ticket-header p{font-size:11px;margin:6px 0 0;opacity:0.8;text-transform:uppercase;letter-spacing:1.5px}' +
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
    '<div class="logo-wrap"><img src="' + window.location.origin + '/images/logo-dark.png" alt="ENCLAII"></div>' +
    '<div class="brand-name">ENCLAII</div>' +
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
    var win = window.open('', '_blank', 'width=700,height=900');
    win.document.write(html);
    win.document.close();

    function doDownload(){
      win.html2pdf().set({
        margin: 10,
        filename: 'ticket-' + __tktData.id.replace('#','') + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
      }).from(win.document.body).save();
    }

    if(typeof win.html2pdf === 'undefined'){
      var script = win.document.createElement('script');
      script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
      script.onload = doDownload;
      win.document.head.appendChild(script);
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

  var attachmentInput = document.getElementById('tktAttachment');
  var attachmentName = document.getElementById('tktAttachmentName');
  if(attachmentInput && attachmentName){
    attachmentInput.addEventListener('change', function(){
      if(attachmentInput.files && attachmentInput.files[0]){
        attachmentName.textContent = 'Archivo: ' + attachmentInput.files[0].name;
        attachmentName.style.display = '';
      } else {
        attachmentName.textContent = '';
        attachmentName.style.display = 'none';
      }
    });
  }

  var perfilOverlay = document.getElementById('tktPerfilModalOverlay');
  var perfilClose = document.getElementById('tktPerfilModalClose');
  var perfilCerrar = document.getElementById('btnPerfilModalCerrar');
  function closePerfilModal(){ if(perfilOverlay) perfilOverlay.style.display = 'none'; }
  if(perfilClose) perfilClose.addEventListener('click', closePerfilModal);
  if(perfilCerrar) perfilCerrar.addEventListener('click', closePerfilModal);
  if(perfilOverlay) perfilOverlay.addEventListener('click', function(e){ if(e.target === perfilOverlay) closePerfilModal(); });

})();
