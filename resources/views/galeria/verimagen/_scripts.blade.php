<script>
(function(){
  const caps = @json($caps);
  let current = {{ $current }};
  const total  = caps.length;

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
      if(this.id === 'viToolAnotar' || this.id === 'viToolFiltros') return;
      this.classList.toggle('on');
    });
  });

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
  let measuring = false;
  let measureDrawing = false;
  let measureTool = 'circle';
  let measureStart = null;
  let measurePreview = null;
  let measureImageIndex = current;
  const measureStates = {};

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
  }

  function startMeasure(e){
    if(!measuring) return;
    e.preventDefault();
    measureDrawing = true;
    measureStart = measurePointerPosition(e);
    measurePreview = null;
  }

  function drawMeasure(e){
    if(!measureDrawing) return;
    e.preventDefault();
    measurePreview = buildMeasureItem(measureStart, measurePointerPosition(e));
    renderMeasurements();
  }

  function stopMeasure(e){
    if(!measureDrawing) return;
    e.preventDefault();
    const state = getMeasureState();
    const end = measurePointerPosition(e.changedTouches ? { touches: e.changedTouches } : e);
    const item = buildMeasureItem(measureStart, end);

    if(item.type !== 'circle' || item.radius > 2){
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
    const link = document.createElement('a');
    link.href = measureCanvas.toDataURL('image/png');
    link.download = `mediciones-imagen-${measureImageIndex + 1}.png`;
    link.click();
  });

  measureCanvas.addEventListener('mousedown', startMeasure);
  measureCanvas.addEventListener('mousemove', drawMeasure);
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
    const link = document.createElement('a');
    link.href = annotationCanvas.toDataURL('image/png');
    link.download = `anotacion-imagen-${annotationImageIndex + 1}.png`;
    link.click();
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

  /* Botón aplicar (descarga canvas con filtros) */
  document.getElementById('viFilterApply').addEventListener('click', function(){
    if(!currentImg){
      this.textContent = '⚠ Sin imagen cargada';
      setTimeout(() => { this.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg> Aplicar filtros'; }, 2000);
      return;
    }
    const link = document.createElement('a');
    link.download = 'imagen-filtrada.png';
    link.href = filterCanvas.toDataURL('image/png');
    link.click();
    this.innerHTML = '✓ Descargado';
    this.style.background = 'var(--green)';
    setTimeout(() => {
      this.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg> Aplicar filtros';
      this.style.background = '';
    }, 2000);
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
  function abrirDl(){ dlOverlay.classList.add('open'); document.body.style.overflow='hidden'; }
  function cerrarDl(){ dlOverlay.classList.remove('open'); document.body.style.overflow=''; }

  document.querySelector('.vi-btn.dl').addEventListener('click', abrirDl);
  document.getElementById('viDlClose') .addEventListener('click', cerrarDl);
  document.getElementById('viDlCancel').addEventListener('click', cerrarDl);
  dlOverlay.addEventListener('click', function(e){ if(e.target === this) cerrarDl(); });
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') cerrarDl(); });

  /* Selección de formato */
  document.querySelectorAll('.vi-fmt-item').forEach(item => {
    item.addEventListener('click', function(){
      document.querySelectorAll('.vi-fmt-item').forEach(i => i.classList.remove('sel'));
      this.classList.add('sel');
      document.getElementById('viDlFmt').textContent = this.dataset.fmt;
    });
  });

  /* Toggle checkboxes incluir */
  document.querySelectorAll('.vi-inc-row').forEach(row => {
    row.addEventListener('click', function(){
      this.classList.toggle('checked');
      if(this.id === 'viIncMarca'){
        document.getElementById('viWatermark').classList.toggle('show', this.classList.contains('checked'));
      }
    });
  });

  /* Confirmar descarga (simulado) */
  document.getElementById('viDlConfirm').addEventListener('click', function(){
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
  document.getElementById('viObsSave').addEventListener('click', function(){
    const area = document.getElementById('viObsArea');
    if(!area.value.trim()) return;
    this.textContent = '✓ Guardado';
    this.style.background = 'var(--green)';
    setTimeout(() => { this.textContent = 'Guardar observación'; this.style.background = ''; }, 2000);
  });

  /* Init */
  goTo(current);
})();
</script>
