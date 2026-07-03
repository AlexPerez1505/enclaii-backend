<script>
(function(){
  const caps = @json($caps);
  const pacienteId = @json((string) ($pacienteId ?? request('paciente', 1)));
  let current = {{ $current }};
  const total  = caps.length;
  const csrfToken = @json(csrf_token());
  const imageSaveBaseUrl = @json(url('/galeria/imagen'));

  /* ── helpers ── */
  function goTo(idx){
    if(idx < 0 || idx >= total) return;
    if(window.viAnnotationSetImage) window.viAnnotationSetImage(idx);
    if(window.viMeasureSetImage) window.viMeasureSetImage(idx);
    current = idx;
    const c = caps[idx];
    /* fondo viewer */
    document.getElementById('viBg').style.background = c.bg;
    const mainImage = document.getElementById('viMainImage');
    const placeholder = document.querySelector('.vi-img-placeholder');
    if(c.src){
      mainImage.src = c.src;
      mainImage.style.display = 'block';
      placeholder.style.display = 'none';
      setZoom(zoom);
      loadImageInCanvas(c.src);
    } else {
      mainImage.removeAttribute('src');
      mainImage.style.display = 'none';
      placeholder.style.display = '';
    }
    /* meta */
    document.getElementById('viMetaTs').textContent  = c.ts;
    document.getElementById('viInfoTs').textContent  = c.ts.replace(':','0:0').replace(/^(\d):/, '00:0$1:');
    /* contador */
    document.getElementById('viCounter').textContent = 'Imagen ' + (idx+1) + ' de ' + total;
    /* header label */
    document.getElementById('viHeaderLabel').textContent = 'Imagen ' + (idx+1);
    /* tira */
    document.querySelectorAll('.vi-strip-item').forEach(el => {
      el.classList.toggle('sel', parseInt(el.dataset.idx) === idx);
    });
  }

  /* Tira clic */
  document.querySelectorAll('.vi-strip-item').forEach(item => {
    item.addEventListener('click', function(e){
      if(e.target.classList.contains('vi-strip-del')) return;
      goTo(parseInt(this.dataset.idx));
    });
  });

  /* Eliminar de tira (sólo visual) */
  document.querySelectorAll('.vi-strip-del').forEach(btn => {
    btn.addEventListener('click', function(e){
      e.stopPropagation();
      const item = this.closest('.vi-strip-item');
      item.style.opacity = '0';
      item.style.transform = 'scale(.85)';
      item.style.transition = 'opacity 200ms ease,transform 200ms ease';
      setTimeout(() => item.remove(), 200);
    });
  });

  /* Flechas navegación */
  document.getElementById('viPrev').addEventListener('click', () => goTo(current - 1));
  document.getElementById('viNext').addEventListener('click', () => goTo(current + 1));
  document.addEventListener('keydown', e => {
    if(e.key === 'ArrowLeft')  goTo(current - 1);
    if(e.key === 'ArrowRight') goTo(current + 1);
  });

  /* Zoom */
  let zoom = 148;
  function setZoom(v){
    zoom = Math.min(Math.max(v, 50), 300);
    document.getElementById('viZoomPct').textContent = zoom + '%';
    const mainImage = document.getElementById('viMainImage');
    if(mainImage) mainImage.style.transform = `scale(${zoom / 100})`;
  }
  document.getElementById('viZoomPlus') .addEventListener('click', () => setZoom(zoom + 10));
  document.getElementById('viZoomMinus').addEventListener('click', () => setZoom(zoom - 10));
  document.getElementById('viZoomFit')  .addEventListener('click', () => setZoom(100));

  /* Toolbar toggle */
  document.querySelectorAll('.vi-tool-btn').forEach(btn => {
    btn.addEventListener('click', function(){
      if(this.id === 'viToolAnotar' || this.id === 'viToolFiltros' || this.id === 'viToolPrint') return;
      this.classList.toggle('on');
    });
  });

  function escapeHtml(value){
    return String(value ?? '').replace(/[&<>"']/g, char => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    }[char]));
  }

  function printCurrentImage(){
    const c = caps[current];
    const mainImage = document.getElementById('viMainImage');
    const imageSrc = mainImage.currentSrc || mainImage.src || c.src;

    if(!imageSrc){
      alert('No hay imagen cargada para imprimir.');
      return;
    }

    const imageNumber = current + 1;
    const timestamp = document.getElementById('viInfoTs').textContent || c.ts;
    const imageFilter = mainImage.style.filter || 'none';

    let printStyles = document.getElementById('viPrintStyles');
    if(!printStyles){
      printStyles = document.createElement('style');
      printStyles.id = 'viPrintStyles';
      printStyles.textContent = `
        #viPrintSheet{display:none}
        @media print{
          @page{size:A4;margin:16mm}
          body.vi-printing *{visibility:hidden!important}
          body.vi-printing #viPrintSheet,
          body.vi-printing #viPrintSheet *{visibility:visible!important}
          body.vi-printing #viPrintSheet{display:flex!important;position:absolute;inset:0;width:100%;background:#fff;color:#111827;font-family:Arial,Helvetica,sans-serif;flex-direction:column;gap:14px}
          #viPrintSheet *{box-sizing:border-box}
          #viPrintSheet .head{display:flex;justify-content:space-between;gap:20px;border-bottom:1px solid #d1d5db;padding-bottom:12px}
          #viPrintSheet .brand{font-size:22px;font-weight:800;letter-spacing:4px;color:#0f172a}
          #viPrintSheet .sub{font-size:11px;letter-spacing:2px;color:#64748b;margin-top:4px;text-transform:uppercase}
          #viPrintSheet .title{text-align:right}
          #viPrintSheet .title h1{font-size:18px;margin:0 0 5px;color:#0f172a}
          #viPrintSheet .title span{font-size:12px;color:#475569}
          #viPrintSheet .image-wrap{border:1px solid #d1d5db;background:#050505;min-height:430px;display:flex;align-items:center;justify-content:center;padding:10px}
          #viPrintSheet .image-wrap img{max-width:100%;max-height:560px;object-fit:contain}
          #viPrintSheet .meta{display:grid;grid-template-columns:repeat(2,1fr);gap:8px 18px;font-size:12px}
          #viPrintSheet .item{display:flex;justify-content:space-between;gap:12px;border-bottom:1px solid #e5e7eb;padding:6px 0}
          #viPrintSheet .label{color:#64748b}
          #viPrintSheet .value{font-weight:700;color:#111827;text-align:right}
          #viPrintSheet .findings{border:1px solid #d1d5db;padding:12px;margin-top:2px}
          #viPrintSheet .findings h2{font-size:13px;margin:0 0 8px;color:#0f172a}
          #viPrintSheet .findings ul{margin:0;padding-left:18px;font-size:12px;line-height:1.7}
          #viPrintSheet .foot{font-size:10px;color:#64748b;border-top:1px solid #e5e7eb;padding-top:8px;margin-top:4px}
        }
      `;
      document.head.appendChild(printStyles);
    }

    let printSheet = document.getElementById('viPrintSheet');
    if(!printSheet){
      printSheet = document.createElement('main');
      printSheet.id = 'viPrintSheet';
      document.body.appendChild(printSheet);
    }

    printSheet.innerHTML = `
          <section class="head">
            <div>
              <div class="brand">ENCLAII</div>
              <div class="sub">Endoscopia · Nube · IA</div>
            </div>
            <div class="title">
              <h1>Imagen ${escapeHtml(imageNumber)} del estudio</h1>
              <span>EDD-2025-001245 · IMG-${String(imageNumber).padStart(4, '0')}</span>
            </div>
          </section>
          <section class="image-wrap">
            <img src="${escapeHtml(imageSrc)}" alt="Imagen endoscopica" style="filter:${escapeHtml(imageFilter)}">
          </section>
          <section class="meta">
            <div class="item"><span class="label">Paciente</span><span class="value">Maria Gonzales</span></div>
            <div class="item"><span class="label">Fecha de captura</span><span class="value">15/07/2025 · 10:30 AM</span></div>
            <div class="item"><span class="label">Tipo de estudio</span><span class="value">Endoscopia Digestiva Alta</span></div>
            <div class="item"><span class="label">Fotograma</span><span class="value">${escapeHtml(timestamp)}</span></div>
            <div class="item"><span class="label">Equipo</span><span class="value">Pentax EPK-i7010</span></div>
            <div class="item"><span class="label">Resolucion</span><span class="value">1920 x 1080</span></div>
          </section>
          <section class="findings">
            <h2>IA Hallazgos</h2>
            <ul>
              <li>Gastritis antral leve · Confianza 92%</li>
              <li>Eritema leve · Confianza 88%</li>
              <li>Sin ulceras visibles · Confianza 95%</li>
            </ul>
          </section>
          <div class="foot">Documento generado para impresion desde ENCLAII.</div>
    `;

    document.body.classList.add('vi-printing');
    window.print();
    setTimeout(() => document.body.classList.remove('vi-printing'), 500);
  }

  const printBtn = document.getElementById('viToolPrint');
  if(printBtn) printBtn.addEventListener('click', printCurrentImage);

  function currentImageFilter(){
    const mainImage = document.getElementById('viMainImage');
    return mainImage?.style.filter || 'none';
  }

  function drawEditedImageCopy(){
    const mainImage = document.getElementById('viMainImage');
    if(!mainImage || !mainImage.currentSrc){
      throw new Error('No hay imagen cargada para guardar.');
    }

    const output = document.createElement('canvas');
    output.width = 1280;
    output.height = 720;
    const out = output.getContext('2d');

    out.fillStyle = '#050505';
    out.fillRect(0, 0, output.width, output.height);
    out.filter = currentImageFilter();
    out.drawImage(mainImage, 0, 0, output.width, output.height);
    out.filter = 'none';

    out.drawImage(annotationCanvas, 0, 0, output.width, output.height);
    out.drawImage(measureCanvas, 0, 0, output.width, output.height);

    return output.toDataURL('image/jpeg', 0.82);
  }

  function dataUrlToBlob(dataUrl){
    const parts = dataUrl.split(',');
    const mime = (parts[0].match(/:(.*?);/) || [])[1] || 'image/jpeg';
    const binary = atob(parts[1]);
    const bytes = new Uint8Array(binary.length);
    for(let i = 0; i < binary.length; i++){
      bytes[i] = binary.charCodeAt(i);
    }
    return new Blob([bytes], { type: mime });
  }

  function selectedArchivoId(){
    return caps[current]?.id;
  }

  function setButtonSaving(button, text){
    const original = button?.innerHTML || '';
    if(button){
      button.disabled = true;
      button.textContent = text;
    }
    return original;
  }

  function restoreButton(button, original){
    if(!button) return;
    button.disabled = false;
    button.innerHTML = original;
    button.style.background = '';
    button.style.borderColor = '';
  }

  function updateCurrentImageUrl(url){
    const freshUrl = `${url}${url.includes('?') ? '&' : '?'}v=${Date.now()}`;
    caps[current].src = freshUrl;

    const mainImage = document.getElementById('viMainImage');
    if(mainImage){
      mainImage.src = freshUrl;
    }

    const stripImage = document.querySelector(`.vi-strip-item[data-idx="${current}"] img`);
    if(stripImage){
      stripImage.src = freshUrl;
    }

    loadImageInCanvas(freshUrl);
  }

  async function saveEditedImage(button, mode){
    const original = setButtonSaving(button, mode === 'copy' ? 'Guardando copia...' : 'Guardando...');
    try {
      const id = selectedArchivoId();
      if(!id){
        throw new Error('No se encontró el archivo actual para guardar.');
      }

      const blob = dataUrlToBlob(drawEditedImageCopy());
      const form = new FormData();
      form.append('image', blob, `${mode === 'copy' ? 'copia_editada' : 'imagen_editada'}_${id}.jpg`);

      const criticalToken = await window.CriticalSecurity.authorize(
        'studies',
        'Confirma tu contraseña para guardar cambios en este estudio.'
      );
      if(criticalToken === null){
        restoreButton(button, original);
        return;
      }

      const response = await fetch(`${imageSaveBaseUrl}/${id}/${mode === 'copy' ? 'guardar-copia' : 'guardar'}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Critical-Authorization': criticalToken
        },
        body: form
      });

      const data = await response.json().catch(() => ({}));
      if(!response.ok || !data.ok){
        throw new Error(data.message || 'No se pudo guardar la imagen editada.');
      }

      if(mode !== 'copy' && data.archivo?.url){
        updateCurrentImageUrl(data.archivo.url);
      }

      if(button){
        button.textContent = mode === 'copy' ? 'Copia guardada' : 'Guardado';
        button.style.background = 'var(--green)';
        button.style.borderColor = 'rgba(61,220,151,.55)';
        setTimeout(() => {
          restoreButton(button, original);
        }, 1800);
      }
    } catch (error) {
      restoreButton(button, original);
      alert(error.message || 'No se pudo guardar la imagen editada.');
    }
  }

  const saveEditedOriginalBtn = document.getElementById('viSaveEditedOriginal');
  if(saveEditedOriginalBtn){
    saveEditedOriginalBtn.addEventListener('click', function(){
      saveEditedImage(this, 'original');
    });
  }

  const saveEditedCopyBtn = document.getElementById('viSaveEditedCopy');
  if(saveEditedCopyBtn){
    saveEditedCopyBtn.addEventListener('click', function(){
      saveEditedImage(this, 'copy');
    });
  }

  /* ── Mediciones ── */
  const measureCanvas = document.getElementById('viMeasureCanvas');
  const measureCtx = measureCanvas.getContext('2d');
  const measureToolbar = document.getElementById('viAnnotationToolbar');
  const measureColor = document.getElementById('viAnnoColor');
  const measureWidth = document.getElementById('viAnnoSize');
  const measureWidthVal = document.getElementById('viAnnoSizeVal');
  const measureCircle = document.getElementById('viMeasureCircle');
  const measureArrow = document.getElementById('viMeasureArrow');
  const measureLine = document.getElementById('viMeasureLine');
  const measureUndo = document.getElementById('viAnnoUndo');
  const measureRedo = document.getElementById('viAnnoRedo');
  const measureSave = document.getElementById('viAnnoSave');
  const measureClear = document.getElementById('viAnnoClear');
  const viewer = document.getElementById('viViewer');
  const measureHeart = document.getElementById('viMeasureHeart');
  let measuring = false;
  let measureDrawing = false;
  let measureTool = 'circle';
  let measureStart = null;
  let measurePreview = null;
  let measureImageIndex = current;
  const measureStates = {};
  let draggingItem = null;
  let draggingMode = 'move';
  let dragOffset = { x: 0, y: 0 };

  function emptyMeasureState(){
    return { items: [], redo: [] };
  }

  function getMeasureState(){
    if(!measureStates[measureImageIndex]){
      measureStates[measureImageIndex] = emptyMeasureState();
    }
    return measureStates[measureImageIndex];
  }

  function resizeMeasureCanvas(){
    const rect = viewer.getBoundingClientRect();
    measureCanvas.width = Math.max(1, Math.round(rect.width));
    measureCanvas.height = Math.max(1, Math.round(rect.height));
    renderMeasurements();
  }

  function measurePointerPosition(e){
    const rect = measureCanvas.getBoundingClientRect();
    const point = e.touches ? e.touches[0] : e;
    return {
      x: point.clientX - rect.left,
      y: point.clientY - rect.top
    };
  }

  function drawHeartPath(ctx, cx, cy, size){
    const s = Math.max(size, 1);

    ctx.beginPath();
    ctx.moveTo(cx, cy + s * 0.58);
    ctx.bezierCurveTo(cx - s * 0.82, cy + s * 0.08, cx - s * 0.9, cy - s * 0.48, cx - s * 0.48, cy - s * 0.68);
    ctx.bezierCurveTo(cx - s * 0.22, cy - s * 0.8, cx - s * 0.04, cy - s * 0.62, cx, cy - s * 0.4);
    ctx.bezierCurveTo(cx + s * 0.04, cy - s * 0.62, cx + s * 0.22, cy - s * 0.8, cx + s * 0.48, cy - s * 0.68);
    ctx.bezierCurveTo(cx + s * 0.9, cy - s * 0.48, cx + s * 0.82, cy + s * 0.08, cx, cy + s * 0.58);
    ctx.closePath();
  }

  function drawMeasureItem(item){
    measureCtx.save();
    measureCtx.strokeStyle = item.color;
    measureCtx.fillStyle = item.color;
    measureCtx.lineWidth = item.width;
    measureCtx.lineCap = 'round';
    measureCtx.lineJoin = 'round';

    if(item.type === 'circle'){
      measureCtx.beginPath();
      measureCtx.arc(item.x, item.y, item.radius, 0, Math.PI * 2);
      measureCtx.stroke();
    }

    if(item.type === 'heart'){
      drawHeartPath(measureCtx, item.x, item.y, item.size);
      measureCtx.stroke();
    }

    if(item.type === 'line' || item.type === 'arrow'){
      measureCtx.beginPath();
      measureCtx.moveTo(item.x1, item.y1);
      measureCtx.lineTo(item.x2, item.y2);
      measureCtx.stroke();

      if(item.type === 'arrow'){
        const angle = Math.atan2(item.y2 - item.y1, item.x2 - item.x1);
        const headLength = Math.max(12, item.width * 4);
        measureCtx.beginPath();
        measureCtx.moveTo(item.x2, item.y2);
        measureCtx.lineTo(
          item.x2 - headLength * Math.cos(angle - Math.PI / 6),
          item.y2 - headLength * Math.sin(angle - Math.PI / 6)
        );
        measureCtx.lineTo(
          item.x2 - headLength * Math.cos(angle + Math.PI / 6),
          item.y2 - headLength * Math.sin(angle + Math.PI / 6)
        );
        measureCtx.closePath();
        measureCtx.fill();
      }
    }

    measureCtx.restore();
  }

  function hitTestItem(item, pos){
    var margin = Math.max(8, item.width * 1.5);
    if(item.type === 'circle'){
      var dist = Math.hypot(pos.x - item.x, pos.y - item.y);
      return Math.abs(dist - item.radius) < margin || dist < item.radius;
    }
    if(item.type === 'heart'){
      var dist = Math.hypot(pos.x - item.x, pos.y - item.y);
      return dist < item.size * 0.6;
    }
    if(item.type === 'line' || item.type === 'arrow'){
      var dx = item.x2 - item.x1, dy = item.y2 - item.y1;
      var len = Math.hypot(dx, dy);
      if(len < 1) return Math.hypot(pos.x - item.x1, pos.y - item.y1) < margin;
      var t = Math.max(0, Math.min(1, ((pos.x - item.x1)*dx + (pos.y - item.y1)*dy) / (len*len)));
      var px = item.x1 + t*dx, py = item.y1 + t*dy;
      return Math.hypot(pos.x - px, pos.y - py) < margin;
    }
    return false;
  }

  function hitModeForItem(item, pos){
    if(item.type === 'circle'){
      var margin = Math.max(10, item.width * 2);
      var dist = Math.hypot(pos.x - item.x, pos.y - item.y);
      return Math.abs(dist - item.radius) <= margin ? 'resize' : 'move';
    }
    return 'move';
  }

  function findItemAtPos(pos){
    var state = getMeasureState();
    for(var i = state.items.length - 1; i >= 0; i--){
      if(hitTestItem(state.items[i], pos)) return state.items[i];
    }
    return null;
  }

  function updateMeasureCursor(e){
    if(!measuring || draggingItem || measureDrawing) return;
    var hit = findItemAtPos(measurePointerPosition(e));
    if(!hit){
      measureCanvas.style.cursor = 'crosshair';
      return;
    }
    measureCanvas.style.cursor = hitModeForItem(hit, measurePointerPosition(e)) === 'resize'
      ? 'nwse-resize'
      : 'grab';
  }

  function moveItem(item, dx, dy){
    if(item.type === 'circle' || item.type === 'heart'){
      item.x += dx;
      item.y += dy;
    } else {
      item.x1 += dx; item.y1 += dy;
      item.x2 += dx; item.y2 += dy;
    }
  }

  function resizeItem(item, pos){
    if(item.type === 'circle'){
      item.radius = Math.max(6, Math.hypot(pos.x - item.x, pos.y - item.y));
    }
  }

  function renderMeasurements(){
    const state = getMeasureState();
    measureCtx.clearRect(0, 0, measureCanvas.width, measureCanvas.height);
    state.items.forEach(drawMeasureItem);
    if(measurePreview) drawMeasureItem(measurePreview);
  }

  function buildMeasureItem(from, to){
    const color = measureColor.value;
    const width = parseInt(measureWidth.value, 10);

    if(measureTool === 'circle'){
      return {
        type: 'circle',
        x: from.x,
        y: from.y,
        radius: Math.hypot(to.x - from.x, to.y - from.y),
        color,
        width
      };
    }

    if(measureTool === 'heart'){
      return {
        type: 'heart',
        x: from.x,
        y: from.y,
        size: Math.hypot(to.x - from.x, to.y - from.y),
        color,
        width
      };
    }

    return {
      type: measureTool,
      x1: from.x,
      y1: from.y,
      x2: to.x,
      y2: to.y,
      color,
      width
    };
  }

  function setMeasureTool(tool){
    measureTool = tool;
    measuring = true;
    annotating = false;
    viewer.classList.add('measuring');
    viewer.classList.remove('annotating');
    annoBrush.classList.remove('active');
    annoEraser.classList.remove('active');
    measureCircle.classList.toggle('active', tool === 'circle');
    measureArrow.classList.toggle('active', tool === 'arrow');
    measureLine.classList.toggle('active', tool === 'line');
    if(measureHeart) measureHeart.classList.toggle('active', tool === 'heart');
  }

  function startMeasure(e){
    if(!measuring) return;
    e.preventDefault();
    var pos = measurePointerPosition(e);
    var hit = findItemAtPos(pos);
    if(hit){
      draggingItem = hit;
      draggingMode = hitModeForItem(hit, pos);
      dragOffset = { x: pos.x, y: pos.y };
      measureCanvas.style.cursor = draggingMode === 'resize' ? 'nwse-resize' : 'grabbing';
      return;
    }
    measureDrawing = true;
    measureStart = pos;
    measurePreview = null;
  }

  function drawMeasure(e){
    if(draggingItem){
      e.preventDefault();
      var pos = measurePointerPosition(e);
      var dx = pos.x - dragOffset.x;
      var dy = pos.y - dragOffset.y;
      if(draggingMode === 'resize'){
        resizeItem(draggingItem, pos);
      } else {
        moveItem(draggingItem, dx, dy);
      }
      dragOffset = pos;
      renderMeasurements();
      return;
    }
    if(!measureDrawing) return;
    e.preventDefault();
    measurePreview = buildMeasureItem(measureStart, measurePointerPosition(e));
    renderMeasurements();
  }

  function stopMeasure(e){
    if(draggingItem){
      e.preventDefault();
      draggingItem = null;
      draggingMode = 'move';
      measureCanvas.style.cursor = '';
      renderMeasurements();
      return;
    }
    if(!measureDrawing) return;
    e.preventDefault();
    const state = getMeasureState();
    const end = measurePointerPosition(e.changedTouches ? { touches: e.changedTouches } : e);
    const item = buildMeasureItem(measureStart, end);

    if((item.type === 'circle' || item.type === 'heart') ? (item.radius || item.size) > 2 : true){
      state.items.push(item);
      state.redo = [];
    }

    measureDrawing = false;
    measureStart = null;
    measurePreview = null;
    renderMeasurements();
  }

  window.viMeasureSetImage = function(idx){
    measureImageIndex = idx;
    resizeMeasureCanvas();
  };

  function closeAnnotationMode(){
    if(!annotating) return;
    annotating = false;
    toolAnotar.classList.remove('on');
    annotationToolbar.classList.remove('open');
    annotationToolbar.setAttribute('aria-hidden', 'true');
    viewer.classList.remove('annotating');
  }

  function closeMeasureMode(){
    if(!measuring) return;
    measuring = false;
    viewer.classList.remove('measuring');
  }

  measureWidth.addEventListener('input', function(){
    measureWidthVal.textContent = this.value;
  });
  measureCircle.addEventListener('click', () => setMeasureTool('circle'));
  measureArrow.addEventListener('click', () => setMeasureTool('arrow'));
  measureLine.addEventListener('click', () => setMeasureTool('line'));
  if(measureHeart) measureHeart.addEventListener('click', () => setMeasureTool('heart'));
  measureUndo.addEventListener('click', function(){
    if(!measuring) return;
    const state = getMeasureState();
    const item = state.items.pop();
    if(item) state.redo.push(item);
    renderMeasurements();
  });
  measureRedo.addEventListener('click', function(){
    if(!measuring) return;
    const state = getMeasureState();
    const item = state.redo.pop();
    if(item) state.items.push(item);
    renderMeasurements();
  });
  measureClear.addEventListener('click', function(){
    if(!measuring) return;
    const state = getMeasureState();
    state.redo = state.items.slice().reverse();
    state.items = [];
    renderMeasurements();
  });
  measureSave.addEventListener('click', function(){
    if(!measuring) return;
    renderMeasurements();
    saveEditedImage(this, 'original');
  });

  measureCanvas.addEventListener('mousedown', startMeasure);
  measureCanvas.addEventListener('mousemove', drawMeasure);
  measureCanvas.addEventListener('mousemove', updateMeasureCursor);
  measureCanvas.addEventListener('mouseup', stopMeasure);
  measureCanvas.addEventListener('mouseleave', stopMeasure);
  measureCanvas.addEventListener('touchstart', startMeasure, { passive: false });
  measureCanvas.addEventListener('touchmove', drawMeasure, { passive: false });
  measureCanvas.addEventListener('touchend', stopMeasure);
  window.addEventListener('resize', resizeMeasureCanvas);

  /* ── Anotaciones ── */
  const annotationCanvas = document.getElementById('viAnnotationCanvas');
  const annotationCtx = annotationCanvas.getContext('2d');
  const annotationToolbar = document.getElementById('viAnnotationToolbar');
  const toolAnotar = document.getElementById('viToolAnotar');
  const annoColor = document.getElementById('viAnnoColor');
  const annoSize = document.getElementById('viAnnoSize');
  const annoSizeVal = document.getElementById('viAnnoSizeVal');
  const annoOpacity = document.getElementById('viAnnoOpacity');
  const annoBrush = document.getElementById('viAnnoBrush');
  const annoEraser = document.getElementById('viAnnoEraser');
  const annoUndo = document.getElementById('viAnnoUndo');
  const annoRedo = document.getElementById('viAnnoRedo');
  const annoSave = document.getElementById('viAnnoSave');
  const annoClear = document.getElementById('viAnnoClear');
  let annotating = false;
  let annotationDrawing = false;
  let annotationTool = 'brush';
  let annotationImageIndex = current;
  const annotationStates = {};

  function emptyAnnotationState(){
    return { history: [], index: -1 };
  }

  function getAnnotationState(){
    if(!annotationStates[annotationImageIndex]){
      annotationStates[annotationImageIndex] = emptyAnnotationState();
    }
    return annotationStates[annotationImageIndex];
  }

  function resizeAnnotationCanvas(){
    const rect = viewer.getBoundingClientRect();
    const snapshot = annotationCanvas.width && annotationCanvas.height
      ? annotationCtx.getImageData(0, 0, annotationCanvas.width, annotationCanvas.height)
      : null;

    annotationCanvas.width = Math.max(1, Math.round(rect.width));
    annotationCanvas.height = Math.max(1, Math.round(rect.height));

    if(snapshot){
      const temp = document.createElement('canvas');
      temp.width = snapshot.width;
      temp.height = snapshot.height;
      temp.getContext('2d').putImageData(snapshot, 0, 0);
      annotationCtx.clearRect(0, 0, annotationCanvas.width, annotationCanvas.height);
      annotationCtx.drawImage(temp, 0, 0, annotationCanvas.width, annotationCanvas.height);
    }
  }

  function saveAnnotationState(){
    const state = getAnnotationState();
    state.index++;
    if(state.index < state.history.length){
      state.history.length = state.index;
    }
    state.history.push(annotationCtx.getImageData(0, 0, annotationCanvas.width, annotationCanvas.height));
  }

  function restoreAnnotationState(){
    const state = getAnnotationState();
    annotationCtx.clearRect(0, 0, annotationCanvas.width, annotationCanvas.height);
    if(state.index >= 0 && state.history[state.index]){
      annotationCtx.putImageData(state.history[state.index], 0, 0);
    }
  }

  window.viAnnotationSetImage = function(idx){
    if(annotationCanvas.width && annotationCanvas.height){
      const state = getAnnotationState();
      state.current = annotationCtx.getImageData(0, 0, annotationCanvas.width, annotationCanvas.height);
    }
    annotationImageIndex = idx;
    resizeAnnotationCanvas();
    const nextState = getAnnotationState();
    annotationCtx.clearRect(0, 0, annotationCanvas.width, annotationCanvas.height);
    if(nextState.current){
      annotationCtx.putImageData(nextState.current, 0, 0);
    } else {
      restoreAnnotationState();
    }
  };

  function pointerPosition(e){
    const rect = annotationCanvas.getBoundingClientRect();
    const point = e.touches ? e.touches[0] : e;
    return {
      x: point.clientX - rect.left,
      y: point.clientY - rect.top
    };
  }

  function startAnnotation(e){
    if(!annotating) return;
    e.preventDefault();
    annotationDrawing = true;
    const pos = pointerPosition(e);
    annotationCtx.beginPath();
    annotationCtx.moveTo(pos.x, pos.y);
  }

  function drawAnnotation(e){
    if(!annotationDrawing) return;
    e.preventDefault();
    const pos = pointerPosition(e);
    annotationCtx.lineCap = 'round';
    annotationCtx.lineJoin = 'round';
    annotationCtx.lineWidth = parseInt(annoSize.value, 10);
    annotationCtx.globalAlpha = parseFloat(annoOpacity.value);

    if(annotationTool === 'brush'){
      annotationCtx.globalCompositeOperation = 'source-over';
      annotationCtx.strokeStyle = annoColor.value;
    } else {
      annotationCtx.globalCompositeOperation = 'destination-out';
      annotationCtx.strokeStyle = 'rgba(0,0,0,1)';
    }

    annotationCtx.lineTo(pos.x, pos.y);
    annotationCtx.stroke();
  }

  function stopAnnotation(){
    if(!annotationDrawing) return;
    annotationDrawing = false;
    annotationCtx.closePath();
    annotationCtx.globalAlpha = 1;
    annotationCtx.globalCompositeOperation = 'source-over';
    saveAnnotationState();
    const state = getAnnotationState();
    state.current = annotationCtx.getImageData(0, 0, annotationCanvas.width, annotationCanvas.height);
  }

  function setAnnotationTool(tool){
    annotationTool = tool;
    annotating = true;
    measuring = false;
    viewer.classList.add('annotating');
    viewer.classList.remove('measuring');
    annoBrush.classList.toggle('active', tool === 'brush');
    annoEraser.classList.toggle('active', tool === 'eraser');
    measureCircle.classList.remove('active');
    measureArrow.classList.remove('active');
    measureLine.classList.remove('active');
  }

  toolAnotar.addEventListener('click', function(){
    const isOpen = annotationToolbar.classList.toggle('open');
    this.classList.toggle('on', isOpen);
    annotationToolbar.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    if(isOpen){
      filtersPanel.classList.remove('open');
      toolFiltros.classList.remove('on');
      setAnnotationTool('brush');
      resizeAnnotationCanvas();
      resizeMeasureCanvas();
    } else {
      annotating = false;
      measuring = false;
      viewer.classList.remove('annotating', 'measuring');
    }
  });

  annoSize.addEventListener('input', function(){
    annoSizeVal.textContent = this.value;
  });
  annoBrush.addEventListener('click', () => setAnnotationTool('brush'));
  annoEraser.addEventListener('click', () => setAnnotationTool('eraser'));
  annoUndo.addEventListener('click', function(){
    if(!annotating) return;
    const state = getAnnotationState();
    if(state.index > 0){
      state.index--;
      restoreAnnotationState();
      state.current = annotationCtx.getImageData(0, 0, annotationCanvas.width, annotationCanvas.height);
    } else if(state.index === 0){
      state.index = -1;
      restoreAnnotationState();
      state.current = annotationCtx.getImageData(0, 0, annotationCanvas.width, annotationCanvas.height);
    }
  });
  annoRedo.addEventListener('click', function(){
    if(!annotating) return;
    const state = getAnnotationState();
    if(state.index < state.history.length - 1){
      state.index++;
      restoreAnnotationState();
      state.current = annotationCtx.getImageData(0, 0, annotationCanvas.width, annotationCanvas.height);
    }
  });
  annoClear.addEventListener('click', function(){
    if(!annotating) return;
    annotationCtx.clearRect(0, 0, annotationCanvas.width, annotationCanvas.height);
    saveAnnotationState();
    getAnnotationState().current = annotationCtx.getImageData(0, 0, annotationCanvas.width, annotationCanvas.height);
  });
  annoSave.addEventListener('click', function(){
    if(!annotating) return;
    saveEditedImage(this, 'original');
  });

  annotationCanvas.addEventListener('mousedown', startAnnotation);
  annotationCanvas.addEventListener('mousemove', drawAnnotation);
  annotationCanvas.addEventListener('mouseup', stopAnnotation);
  annotationCanvas.addEventListener('mouseleave', stopAnnotation);
  annotationCanvas.addEventListener('touchstart', startAnnotation, { passive: false });
  annotationCanvas.addEventListener('touchmove', drawAnnotation, { passive: false });
  annotationCanvas.addEventListener('touchend', stopAnnotation);
  window.addEventListener('resize', resizeAnnotationCanvas);

  /* ── Panel de Filtros ── */
  const filtersPanel = document.getElementById('viFiltersPanel');
  const toolFiltros  = document.getElementById('viToolFiltros');
  const filterCanvas = document.getElementById('viFilterCanvas');
  const canvasPlaceholder = document.getElementById('viCanvasPlaceholder');
  const ctx = filterCanvas.getContext('2d');
  let currentImg = null;
  let currentFilter = 'original';

  annotationToolbar.after(filtersPanel);

  toolFiltros.addEventListener('click', function(){
    const isOpen = filtersPanel.classList.toggle('open');
    this.classList.toggle('on', isOpen);
    if(isOpen){
      annotationToolbar.classList.remove('open');
      toolAnotar.classList.remove('on');
      annotating = false;
      measuring = false;
      viewer.classList.remove('annotating', 'measuring');
    }
  });

  /* Cargar imagen en canvas cuando haya una URL real */
  function loadImageInCanvas(src){
    if(!src) return;
    const img = new Image();
    img.crossOrigin = 'anonymous';
    img.onload = function(){
      filterCanvas.width  = img.naturalWidth;
      filterCanvas.height = img.naturalHeight;
      currentImg = img;
      canvasPlaceholder.style.display = 'none';
      filterCanvas.style.display = 'block';
      applyFilter(currentFilter);
    };
    img.src = src;
  }

  /* Aplicar filtro de color */
  function applyFilter(filter){
    if(!currentImg) return;
    ctx.filter = 'none';
    ctx.drawImage(currentImg, 0, 0);
    const imageData = ctx.getImageData(0, 0, filterCanvas.width, filterCanvas.height);
    const data = imageData.data;

    if(filter === 'grayscale'){
      for(let i = 0; i < data.length; i += 4){
        const avg = (data[i] + data[i+1] + data[i+2]) / 3;
        data[i] = data[i+1] = data[i+2] = avg;
      }
    } else if(filter === 'inverted'){
      for(let i = 0; i < data.length; i += 4){
        data[i]   = 255 - data[i];
        data[i+1] = 255 - data[i+1];
        data[i+2] = 255 - data[i+2];
      }
    } else if(filter === 'sepia'){
      for(let i = 0; i < data.length; i += 4){
        const r = data[i], g = data[i+1], b = data[i+2];
        data[i]   = Math.min(255, r * 0.393 + g * 0.769 + b * 0.189);
        data[i+1] = Math.min(255, r * 0.349 + g * 0.686 + b * 0.168);
        data[i+2] = Math.min(255, r * 0.272 + g * 0.534 + b * 0.131);
      }
    }
    ctx.putImageData(imageData, 0, 0);
    applySliders();
  }

  /* Aplicar sliders (brillo, contraste, saturación) con CSS filter */
  function applySliders(){
    const b = document.getElementById('slBrillo').value;
    const c = document.getElementById('slContraste').value;
    const s = document.getElementById('slSaturacion').value;
    const colorFilter = currentFilter === 'grayscale'
      ? 'grayscale(100%)'
      : currentFilter === 'inverted'
        ? 'invert(100%)'
        : currentFilter === 'sepia'
          ? 'sepia(100%)'
          : '';
    const sliderFilter = `brightness(${b}%) contrast(${c}%) saturate(${s}%)`;
    const previewFilter = sliderFilter;
    const mainFilter = `${colorFilter} ${sliderFilter}`.trim();
    const mainImage = document.getElementById('viMainImage');
    filterCanvas.style.filter = previewFilter;
    if(mainImage) mainImage.style.filter = mainFilter;
  }

  /* Botones de filtro de color */
  document.querySelectorAll('.vi-filter-btn').forEach(btn => {
    btn.addEventListener('click', function(){
      document.querySelectorAll('.vi-filter-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      currentFilter = this.dataset.filter;
      applyFilter(currentFilter);
    });
  });

  /* Sliders */
  ['slBrillo','slContraste','slSaturacion'].forEach(id => {
    const input = document.getElementById(id);
    const val   = document.getElementById(id + 'Val');
    input.addEventListener('input', function(){
      val.textContent = this.value + '%';
      applySliders();
    });
  });

  /* Botón aplicar: guarda una copia editada en la galería del paciente */
  document.getElementById('viFilterApply').addEventListener('click', function(){
    if(!currentImg){
      this.textContent = '⚠ Sin imagen cargada';
      setTimeout(() => { this.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg> Aplicar filtros'; }, 2000);
      return;
    }
    saveEditedImage(this, 'copy');
  });

  /* Botón restablecer */
  document.getElementById('viFilterReset').addEventListener('click', function(){
    document.querySelectorAll('.vi-filter-btn').forEach(b => b.classList.remove('active'));
    document.querySelector('.vi-filter-btn[data-filter="original"]').classList.add('active');
    currentFilter = 'original';
    ['slBrillo','slContraste','slSaturacion'].forEach(id => {
      document.getElementById(id).value = 100;
      document.getElementById(id + 'Val').textContent = '100%';
    });
    filterCanvas.style.filter = 'none';
    document.getElementById('viMainImage').style.filter = 'none';
    if(currentImg) applyFilter('original');
  });

  /* ── Modal descarga ── */
  const dlOverlay = document.getElementById('viDlOverlay');
  function abrirDl(){ if(dlOverlay){ dlOverlay.classList.add('open'); document.body.style.overflow='hidden'; } }
  function cerrarDl(){ if(dlOverlay){ dlOverlay.classList.remove('open'); document.body.style.overflow=''; } }

  const dlBtn = document.querySelector('.vi-btn.dl');
  const dlCloseBtn = document.getElementById('viDlClose');
  const dlCancelBtn = document.getElementById('viDlCancel');
  if(dlBtn) dlBtn.addEventListener('click', abrirDl);
  if(dlCloseBtn) dlCloseBtn.addEventListener('click', cerrarDl);
  if(dlCancelBtn) dlCancelBtn.addEventListener('click', cerrarDl);
  if(dlOverlay) dlOverlay.addEventListener('click', function(e){ if(e.target === this) cerrarDl(); });
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') cerrarDl(); });

  /* Selección de formato */
  document.querySelectorAll('.vi-fmt-item').forEach(item => {
    item.addEventListener('click', function(){
      document.querySelectorAll('.vi-fmt-item').forEach(i => i.classList.remove('sel'));
      this.classList.add('sel');
      const fmtLabel = document.getElementById('viDlFmt');
      if(fmtLabel) fmtLabel.textContent = this.dataset.fmt;
    });
  });

  /* Toggle checkboxes incluir */
  document.querySelectorAll('.vi-inc-row').forEach(row => {
    row.addEventListener('click', function(){
      this.classList.toggle('checked');
      if(this.id === 'viIncMarca'){
        const watermark = document.getElementById('viWatermark');
        if(watermark) watermark.classList.toggle('show', this.classList.contains('checked'));
      }
    });
  });

  /* Confirmar descarga (simulado) */
  const dlConfirmBtn = document.getElementById('viDlConfirm');
  if(dlConfirmBtn) dlConfirmBtn.addEventListener('click', function(){
    const fmt = document.querySelector('.vi-fmt-item.sel').dataset.fmt;
    this.textContent = '✓ Descargando...';
    this.style.background = 'var(--green)';
    setTimeout(() => {
      this.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Descargar imagen';
      this.style.background = '';
      cerrarDl();
    }, 1800);
  });

  /* Guardar observación */
  const obsSaveBtn = document.getElementById('viObsSave');
  if(obsSaveBtn) obsSaveBtn.addEventListener('click', function(){
    const area = document.getElementById('viObsArea');
    if(!area || !area.value.trim()) return;
    this.textContent = '✓ Guardado';
    this.style.background = 'var(--green)';
    setTimeout(() => { this.textContent = 'Guardar observación'; this.style.background = ''; }, 2000);
  });

  /* Init */
  goTo(current);
})();
</script>
