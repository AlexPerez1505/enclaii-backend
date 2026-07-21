<script>
(function () {
  /* ── Formulario Crear Estudio: Fecha y hora por defecto ── */
  const now = new Date();
  const pad = n => String(n).padStart(2, '0');
  const local = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
  const fechaHoraInput = document.getElementById('fecha_hora');
  const fechaNacInput = document.getElementById('fecha_nac');
  if (fechaHoraInput) fechaHoraInput.value = local;
  if (fechaNacInput) fechaNacInput.value = '1998-12-25';

  /* ── Menú foto (galería/cámara) ── */
  const btnMenu   = document.getElementById('btnFotoMenu');
  const fotoMenu  = document.getElementById('fotoMenu');
  const btnTxt    = document.getElementById('btnFotoTxt');

  btnMenu?.addEventListener('click', (e) => {
    e.stopPropagation();
    fotoMenu.style.display = fotoMenu.style.display === 'none' ? 'block' : 'none';
  });

  document.addEventListener('click', () => { if(fotoMenu) fotoMenu.style.display = 'none'; });

  document.getElementById('btnGaleria')?.addEventListener('click', () => {
    fotoMenu.style.display = 'none';
    document.getElementById('fotoInput').click();
  });

  document.getElementById('btnCamara')?.addEventListener('click', () => {
    fotoMenu.style.display = 'none';
    document.getElementById('fotoCamera').click();
  });

  function applyPreview(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById('fotoPreview');
      const ph  = document.getElementById('fotoPlaceholder');
      img.src = e.target.result;
      img.style.display = 'block';
      ph.style.display  = 'none';
      btnTxt.textContent = 'Cambiar foto';
    };
    reader.readAsDataURL(file);
  }

  document.getElementById('fotoInput')?.addEventListener('change',  function () { applyPreview(this.files[0]); });
  document.getElementById('fotoCamera')?.addEventListener('change', function () { applyPreview(this.files[0]); });

  /* ── Escape key handler ── */
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      const capturasView = document.getElementById('capturasView');
      if (capturasView && capturasView.style.display === 'block') {
        document.getElementById('btnVolverCapturas')?.click();
      }
    }
  });

  /* ── Búsqueda de Pacientes con menú desplegable ── */
  const pacienteSearchInput = document.getElementById('pacienteSearchInput');
  const btnFiltrarPacientes = document.getElementById('btnFiltrarPacientes');
  const filtrarMenu = document.getElementById('filtrarMenu');
  const filtrarOpciones = document.querySelectorAll('.filtrar-opcion');

  let currentFilter = 'todos';

  // Abrir/cerrar menú al hacer clic en el botón
  btnFiltrarPacientes?.addEventListener('click', (e) => {
    e.stopPropagation();
    filtrarMenu?.classList.toggle('active');
  });

  // Cerrar menú al hacer clic fuera
  document.addEventListener('click', () => {
    filtrarMenu?.classList.remove('active');
  });

  // Manejar selección de opción
  filtrarOpciones.forEach(opcion => {
    opcion.addEventListener('click', () => {
      currentFilter = opcion.dataset.filtro;

      // Actualizar clase activa
      filtrarOpciones.forEach(o => o.classList.remove('active'));
      opcion.classList.add('active');

      // Cerrar menú
      filtrarMenu?.classList.remove('active');

      // Aquí puedes agregar la lógica de filtrado según el filtro seleccionado
      console.log('Filtro seleccionado:', currentFilter);
    });
  });

  // Marcar "Todos" como activo por defecto
  document.querySelector('[data-filtro="todos"]')?.classList.add('active');

  /* ── Navegación a interfaz de Capturas ── */
  const crearLayout = document.querySelector('.crear-layout');
  const crearToolbar = document.querySelector('.crear-toolbar');
  const capturasView = document.getElementById('capturasView');
  const btnVolverCapturas = document.getElementById('btnVolverCapturas');


  // Al hacer clic en "Agregar Capturas" desde el panel lateral
  document.querySelectorAll('.action-btn').forEach(btn => {
    // Verificar que sea el botón de Agregar Capturas por su texto
    if (btn.textContent.includes('Agregar Capturas')) {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        // Ocultar formulario, toolbar y buscador; mostrar capturas
        if (crearLayout) crearLayout.style.display = 'none';
        if (crearToolbar) crearToolbar.style.display = 'none';
        const busquedaWrapper = document.getElementById('pacienteBusquedaWrapper');
        if (busquedaWrapper) busquedaWrapper.style.display = 'none';
        capturasView.style.display = 'block';
        // Actualizar título del header
        document.querySelector('.header-title').textContent = 'Agregar Capturas';
        document.querySelector('.header-sub').textContent = 'Gestión de imágenes del paciente';
      });
    }
  });

  /* ══════════════════════════════════════════
     PANEL EXPORTAR PDF
     ══════════════════════════════════════════ */
  const capListaView    = document.getElementById('capListaView');
  const exportPanel     = document.getElementById('exportPanel');
  const exportListaView = capListaView;

  /* Abrir exportar */
  document.getElementById('btnExportarCaptura')?.addEventListener('click', () => {
    exportListaView.style.display = 'none';
    exportPanel.classList.add('open');
  });

  /* Volver desde exportar */
  document.getElementById('btnExportarVolver')?.addEventListener('click', () => {
    exportPanel.classList.remove('open');
    exportListaView.style.display = 'block';
  });

  /* Zoom exportar */
  let exportZoom = 68;
  const exportSheetEl = document.getElementById('exportSheet');
  function applyExportZoom() {
    document.getElementById('exportZoomVal').textContent = exportZoom + '%';
    if (exportSheetEl) {
      exportSheetEl.style.transform = `scale(${exportZoom/100})`;
      const isLandscape = exportSheetEl.classList.contains('landscape');
      const naturalH = isLandscape ? 595 : 842;
      exportSheetEl.style.marginBottom = `calc(${naturalH}px * -${(100 - exportZoom) / 100})`;
    }
  }
  document.getElementById('exportZoomIn')?.addEventListener('click', () => { if (exportZoom < 150) { exportZoom += 10; applyExportZoom(); } });
  document.getElementById('exportZoomOut')?.addEventListener('click', () => { if (exportZoom > 40) { exportZoom -= 10; applyExportZoom(); } });

  /* Orientación exportar */
  document.getElementById('expOrientV')?.addEventListener('click', () => {
    document.getElementById('expOrientV').classList.add('active');
    document.getElementById('expOrientH').classList.remove('active');
    if (exportSheetEl) exportSheetEl.classList.remove('landscape');
    applyExportZoom();
  });
  document.getElementById('expOrientH')?.addEventListener('click', () => {
    document.getElementById('expOrientH').classList.add('active');
    document.getElementById('expOrientV').classList.remove('active');
    if (exportSheetEl) exportSheetEl.classList.add('landscape');
    applyExportZoom();
  });

  /* Checkboxes → actualizar documento */
  function updateExportDoc() {
    const showHeader = document.getElementById('expChkHeader')?.checked;
    const showFecha  = document.getElementById('expChkFecha')?.checked;
    const showDesc   = document.getElementById('expChkDesc')?.checked;
    const showNum    = document.getElementById('expChkNum')?.checked;

    const header = document.getElementById('exportSheetHeader');
    if (header) header.style.display = showHeader ? '' : 'none';

    document.querySelectorAll('#exportSheet .export-sheet-meta').forEach(el => {
      el.style.display = showFecha ? '' : 'none';
    });
    document.querySelectorAll('#exportSheet [data-export-desc-label], #exportSheet [data-export-desc]').forEach(el => {
      el.style.display = showDesc ? '' : 'none';
    });
    document.querySelectorAll('#exportSheet [data-export-num]').forEach(el => {
      el.style.display = showNum ? '' : 'none';
    });
  }
  ['expChkHeader','expChkFecha','expChkDesc','expChkNum'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', updateExportDoc);
  });

  /* ── Protección con contraseña ── */
  // Contraseña simulada del login (en producción vendría del backend)
  const LOGIN_PASSWORD = 'admin123';

  const chkProtect = document.getElementById('expChkProtect');
  chkProtect?.addEventListener('change', function() {
    if (this.checked) {
      // Abrir modal para verificar contraseña
      const input = document.getElementById('protectPasswordInput');
      const errEl = document.getElementById('protectPasswordError');
      if (input) input.value = '';
      if (errEl) errEl.style.display = 'none';
      document.getElementById('protectPasswordOverlay').classList.add('open');
    }
  });

  // Toggle visibilidad contraseña
  document.getElementById('protectToggleVisibility')?.addEventListener('click', () => {
    const input = document.getElementById('protectPasswordInput');
    const icon  = document.getElementById('protectEyeIcon');
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
      input.type = 'password';
      icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
  });

  // Cancelar → desmarcar checkbox
  document.getElementById('protectPasswordCancel')?.addEventListener('click', () => {
    document.getElementById('protectPasswordOverlay').classList.remove('open');
    if (chkProtect) chkProtect.checked = false;
  });

  // Confirmar → validar contraseña
  document.getElementById('protectPasswordConfirm')?.addEventListener('click', () => {
    const input = document.getElementById('protectPasswordInput');
    const errEl = document.getElementById('protectPasswordError');
    if (input?.value === LOGIN_PASSWORD) {
      // Contraseña correcta → cerrar modal, protección activa
      document.getElementById('protectPasswordOverlay').classList.remove('open');
    } else {
      // Incorrecta → mostrar error 1.5s, desmarcar y cerrar
      if (errEl) errEl.style.display = 'block';
      setTimeout(() => {
        document.getElementById('protectPasswordOverlay').classList.remove('open');
        if (chkProtect) chkProtect.checked = false;
        if (errEl) errEl.style.display = 'none';
      }, 1500);
    }
  });

  // Enter en el input → confirmar
  document.getElementById('protectPasswordInput')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') document.getElementById('protectPasswordConfirm')?.click();
  });

  /* Descargar PDF → modal */
  document.getElementById('btnDescargarPDF')?.addEventListener('click', () => {
    document.getElementById('pdfDownloadedOverlay').classList.add('open');
  });
  document.getElementById('pdfDownloadedOk')?.addEventListener('click', () => {
    document.getElementById('pdfDownloadedOverlay').classList.remove('open');
    exportPanel.classList.remove('open');
    exportListaView.style.display = 'block';
  });

  /* ── Carrusel Vista Previa ── */
  // Pool completo de capturas del sistema (con id único para rastrear)
  const allCapturas = [
    { id: 1, src: '<?php echo e(asset("images/captura1.jpg")); ?>', name: 'Imagen 1' },
    { id: 2, src: '<?php echo e(asset("images/captura1.jpg")); ?>', name: 'Imagen 2' },
    { id: 3, src: '<?php echo e(asset("images/captura1.jpg")); ?>', name: 'Imagen 3' },
  ];
  // Las que están en el carrusel (ids activos)
  let carouselImages = allCapturas.map(c => ({ ...c }));
  let carouselIdx = 0;

  function renderCarousel() {
    const img       = document.getElementById('carouselImg');
    const counter   = document.getElementById('carouselCounter');
    const dots      = document.getElementById('carouselDots');
    const prev      = document.getElementById('carouselPrev');
    const next      = document.getElementById('carouselNext');
    const removeBtn = document.getElementById('carouselRemove');
    if (!img) return;
    const cur = carouselImages[carouselIdx];
    img.src = cur.src;
    const infoName = document.getElementById('carouselInfoName');
    const infoDesc = document.getElementById('carouselInfoDesc');
    const infoDate = document.getElementById('carouselInfoDate');
    if (infoName) infoName.textContent = cur.name || '';
    if (infoDesc) infoDesc.textContent = cur.desc || 'Lesión observada en estudio';
    if (infoDate) infoDate.textContent = cur.date ? `Fecha: ${cur.date}` : 'Fecha: 05/mayo/2026';
    counter.textContent = `${carouselIdx + 1} / ${carouselImages.length}`;
    prev.disabled = carouselIdx === 0;
    next.disabled = carouselIdx === carouselImages.length - 1;
    if (removeBtn) removeBtn.disabled = carouselImages.length <= 1;
    dots.innerHTML = '';
    carouselImages.forEach((_, i) => {
      const d = document.createElement('button');
      d.className = 'export-carousel-dot' + (i === carouselIdx ? ' active' : '');
      d.addEventListener('click', () => { carouselIdx = i; renderCarousel(); });
      dots.appendChild(d);
    });
  }

  document.getElementById('btnVistaPrevia')?.addEventListener('click', () => {
    carouselIdx = 0;
    renderCarousel();
    document.getElementById('exportCarousel').classList.add('open');
  });
  document.getElementById('exportCarouselClose')?.addEventListener('click', () => {
    document.getElementById('exportCarousel').classList.remove('open');
  });
  document.getElementById('carouselPrev')?.addEventListener('click', () => {
    if (carouselIdx > 0) { carouselIdx--; renderCarousel(); }
  });
  document.getElementById('carouselNext')?.addEventListener('click', () => {
    if (carouselIdx < carouselImages.length - 1) { carouselIdx++; renderCarousel(); }
  });

  /* Quitar imagen → modal confirmación → devuelve al pool disponible */
  document.getElementById('carouselRemove')?.addEventListener('click', () => {
    if (carouselImages.length <= 1) return;
    document.getElementById('carouselRemoveOverlay').classList.add('open');
  });
  document.getElementById('carouselRemoveNo')?.addEventListener('click', () => {
    document.getElementById('carouselRemoveOverlay').classList.remove('open');
  });
  document.getElementById('carouselRemoveSi')?.addEventListener('click', () => {
    document.getElementById('carouselRemoveOverlay').classList.remove('open');
    // La eliminada queda en allCapturas (no se borra), solo se quita del carrusel activo
    carouselImages.splice(carouselIdx, 1);
    if (carouselIdx >= carouselImages.length) carouselIdx = carouselImages.length - 1;
    document.getElementById('carouselRemovedOverlay').classList.add('open');
  });
  document.getElementById('carouselRemovedOk')?.addEventListener('click', () => {
    document.getElementById('carouselRemovedOverlay').classList.remove('open');
    renderCarousel();
  });

  /* Agregar imagen → pool dinámico: muestra las que NO están en carrusel */
  document.getElementById('carouselAdd')?.addEventListener('click', () => {
    const activeIds = carouselImages.map(c => c.id);
    const available = allCapturas.filter(c => !activeIds.includes(c.id));

    if (available.length === 0) {
      // No hay disponibles → modal informativo y cierra
      document.getElementById('carouselNoCaptOverlay').classList.add('open');
      return;
    }

    const list = document.getElementById('carouselAddList');
    list.innerHTML = '';
    available.forEach((cap, i) => {
      const item = document.createElement('label');
      item.className = 'carousel-add-item';
      item.innerHTML = `
        <img src="${cap.src}" alt="${cap.name}">
        <span class="carousel-add-item-name">${cap.name}</span>
        <input type="checkbox" class="carousel-add-item-check" data-idx="${i}">
      `;
      item.querySelector('input').addEventListener('change', function() {
        item.classList.toggle('selected', this.checked);
      });
      list.appendChild(item);
    });
    const overlay = document.getElementById('carouselAddOverlay');
    overlay._available = available;
    overlay.classList.add('open');
  });

  document.getElementById('carouselNoCaptOk')?.addEventListener('click', () => {
    document.getElementById('carouselNoCaptOverlay').classList.remove('open');
    // Queda en el carrusel
  });
  document.getElementById('carouselAddCancel')?.addEventListener('click', () => {
    document.getElementById('carouselAddOverlay').classList.remove('open');
  });
  document.getElementById('carouselAddConfirm')?.addEventListener('click', () => {
    const overlay = document.getElementById('carouselAddOverlay');
    const available = overlay._available || [];
    const checks = overlay.querySelectorAll('.carousel-add-item-check:checked');
    checks.forEach(chk => {
      const idx = parseInt(chk.dataset.idx);
      if (available[idx]) carouselImages.push({ ...available[idx] });
    });
    overlay.classList.remove('open');
    carouselIdx = carouselImages.length - 1;
    renderCarousel();
  });

  document.addEventListener('keydown', e => {
    const carousel = document.getElementById('exportCarousel');
    if (!carousel?.classList.contains('open')) return;
    if (e.key === 'ArrowLeft' && carouselIdx > 0) { carouselIdx--; renderCarousel(); }
    if (e.key === 'ArrowRight' && carouselIdx < carouselImages.length - 1) { carouselIdx++; renderCarousel(); }
    if (e.key === 'Escape') carousel.classList.remove('open');
  });

  // Botón volver - regresar al formulario
  btnVolverCapturas?.addEventListener('click', () => {
    capturasView.style.display = 'none';
    if (crearLayout) crearLayout.style.display = 'grid';
    if (crearToolbar) crearToolbar.style.display = 'flex';
    const busquedaWrapper = document.getElementById('pacienteBusquedaWrapper');
    if (busquedaWrapper) busquedaWrapper.style.display = '';
    // Restaurar título original
    document.querySelector('.header-title').textContent = 'Nuevo Estudio';
    document.querySelector('.header-sub').textContent = 'Datos nuevos';
  });

  /* ── Lightbox ── */
  const lightbox = document.getElementById('capLightbox');
  const lightboxImg = document.getElementById('capLightboxImg');
  document.getElementById('capLightboxClose')?.addEventListener('click', () => lightbox.classList.remove('open'));
  lightbox?.addEventListener('click', (e) => { if (e.target === lightbox) lightbox.classList.remove('open'); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') lightbox?.classList.remove('open'); });

  document.querySelectorAll('.cap-thumb-expand').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const img = btn.closest('.cap-thumb-v').querySelector('img');
      lightboxImg.src = img.src;
      lightbox.classList.add('open');
    });
  });

  /* ── Lista de capturas: selección + vista previa ── */
  const itemsV = document.querySelectorAll('.cap-item-v');
  const capPrevImg = document.getElementById('capPrevImg');

  itemsV.forEach((item, idx) => {
    item.addEventListener('click', (e) => {
      if (e.target.closest('.cap-more-v') || e.target.closest('.cap-dropdown')) return;
      itemsV.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      const d = item.dataset;
      if (capPrevImg) capPrevImg.src = '<?php echo e(asset('images/captura1.jpg')); ?>';
      const piFecha = document.getElementById('capPiFecha');
      const piDesc = document.getElementById('capPiDesc');
      const piEstudio = document.getElementById('capPiEstudio');
      const piTipo = document.getElementById('capPiTipo');
      const piImagen = document.getElementById('capPiImagen');
      if (piFecha) piFecha.textContent = d.fecha + ' ' + d.hora;
      if (piDesc) piDesc.textContent = d.nombre;
      if (piEstudio) piEstudio.textContent = d.estudio;
      if (piTipo) piTipo.textContent = d.tipoEstudio;
      if (piImagen) piImagen.textContent = (idx + 1) + ' de ' + itemsV.length;
    });
  });
  if (itemsV.length) itemsV[0].click();

  /* ── Buscador de capturas ── */
  const capSearchV = document.getElementById('capSearchV');
  if (capSearchV) {
    capSearchV.addEventListener('input', () => {
      const q = capSearchV.value.toLowerCase().trim();
      let visible = 0;
      itemsV.forEach(item => {
        const match = item.dataset.nombre.toLowerCase().includes(q);
        item.style.display = match ? '' : 'none';
        if (match) visible++;
      });
      const footerV = document.getElementById('capFooterV');
      if (footerV) footerV.textContent = `Mostrando ${visible} de ${itemsV.length}`;
    });
  }

  /* ── Botón Editar → abrir Editor ── */
  document.getElementById('btnAbrirEditor')?.addEventListener('click', () => {
    document.getElementById('capListaView').style.display = 'none';
    document.getElementById('capEditorView').style.display = 'block';
  });

  /* ── Panel Imprimir ── */
  const printPanel = document.getElementById('printPanel');

  function volverAListaCapturas() {
    printPanel.classList.remove('open');
    capListaView.style.display = 'block';
  }

  document.getElementById('btnImprimirCaptura')?.addEventListener('click', () => {
    capListaView.style.display = 'none';
    printPanel.classList.add('open');
  });

  /* Botón Imprimir → modal éxito */
  document.querySelector('.print-confirm-btn')?.addEventListener('click', () => {
    document.getElementById('printSuccessOverlay').classList.add('open');
  });
  document.getElementById('printSuccessOk')?.addEventListener('click', () => {
    document.getElementById('printSuccessOverlay').classList.remove('open');
    volverAListaCapturas();
  });

  /* Botón Cancelar → modal confirmación */
  document.getElementById('btnCancelarImpresion')?.addEventListener('click', () => {
    document.getElementById('printCancelOverlay').classList.add('open');
  });
  document.getElementById('printCancelNo')?.addEventListener('click', () => {
    document.getElementById('printCancelOverlay').classList.remove('open');
  });
  document.getElementById('printCancelSi')?.addEventListener('click', () => {
    document.getElementById('printCancelOverlay').classList.remove('open');
    volverAListaCapturas();
  });

  /* Copias */
  let printCopies = 1;
  document.getElementById('printCopiesUp')?.addEventListener('click', () => {
    printCopies++;
    document.getElementById('printCopiesVal').textContent = printCopies;
  });
  document.getElementById('printCopiesDown')?.addEventListener('click', () => {
    if (printCopies > 1) { printCopies--; document.getElementById('printCopiesVal').textContent = printCopies; }
  });

  /* Orientación */
  const sheet = document.getElementById('printSheet');
  document.getElementById('printOrientV')?.addEventListener('click', () => {
    document.getElementById('printOrientV').classList.add('active');
    document.getElementById('printOrientH').classList.remove('active');
    if (sheet) sheet.classList.remove('landscape');
  });
  document.getElementById('printOrientH')?.addEventListener('click', () => {
    document.getElementById('printOrientH').classList.add('active');
    document.getElementById('printOrientV').classList.remove('active');
    if (sheet) sheet.classList.add('landscape');
  });

  /* Zoom */
  let printZoom = 68;
  const printSheetEl = document.getElementById('printSheet');
  function applyZoom() {
    document.getElementById('printZoomVal').textContent = printZoom + '%';
    if (printSheetEl) {
      printSheetEl.style.transform = `scale(${printZoom/100})`;
      const isLandscape = printSheetEl.classList.contains('landscape');
      const naturalH = isLandscape ? 595 : 842;
      printSheetEl.style.marginBottom = `calc(${naturalH}px * -${(100 - printZoom) / 100})`;
    }
  }
  applyZoom();
  document.getElementById('printZoomIn')?.addEventListener('click', () => {
    if (printZoom < 150) { printZoom += 10; applyZoom(); }
  });
  document.getElementById('printZoomOut')?.addEventListener('click', () => {
    if (printZoom > 40) { printZoom -= 10; applyZoom(); }
  });

  /* Fit / ajustar a pantalla */
  let fitMode = false;
  const exitFsBtn = document.getElementById('printExitFs');
  function toggleFitMode(on) {
    fitMode = on;
    const fitBtn = document.getElementById('printFitBtn');
    const wrap = document.querySelector('.print-sheet-wrap');
    if (on) {
      fitBtn?.classList.add('fullscreen');
      if (wrap) { wrap.style.position = 'fixed'; wrap.style.inset = '0'; wrap.style.zIndex = '200'; wrap.style.borderRadius = '0'; wrap.style.padding = '40px'; }
      exitFsBtn?.classList.add('visible');
    } else {
      fitBtn?.classList.remove('fullscreen');
      if (wrap) { wrap.style.position = ''; wrap.style.inset = ''; wrap.style.zIndex = ''; wrap.style.borderRadius = ''; wrap.style.padding = ''; }
      exitFsBtn?.classList.remove('visible');
    }
  }
  document.getElementById('printFitBtn')?.addEventListener('click', () => toggleFitMode(!fitMode));
  exitFsBtn?.addEventListener('click', () => toggleFitMode(false));

  /* ── Botón Volver a Capturas → cerrar Editor ── */
  document.getElementById('btnCerrarEditor')?.addEventListener('click', () => {
    document.getElementById('capEditorView').style.display = 'none';
    document.getElementById('capListaView').style.display = 'block';
  });

  /* ── Acciones de fotos en editor ── */
  document.querySelectorAll('.cap-foto-card').forEach(card => {
    card.addEventListener('click', (e) => {
      if (!e.target.closest('.cap-foto-action')) {
        card.classList.toggle('selected');
      }
    });
  });

  /* ── Tool buttons en editor ── */
  document.querySelectorAll('.cap-tool-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.cap-tool-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  /* ── Modal Agregar Nota ── */
  const notaModal = document.getElementById('notaModal');
  const notaModalOverlay = document.getElementById('notaModalOverlay');
  const btnNotaVolver = document.getElementById('btnNotaVolver');
  const btnNotaGuardar = document.getElementById('btnNotaGuardar');
  const notaTextarea = document.getElementById('notaTextarea');

  // Función para abrir modal
  function openNotaModal() {
    notaModal.classList.add('active');
    notaModalOverlay.classList.add('active');
    notaTextarea.focus();
  }

  // Función para cerrar modal
  function closeNotaModal() {
    notaModal.classList.remove('active');
    notaModalOverlay.classList.remove('active');
    notaTextarea.value = '';
  }

  // Click en botón "Agregar a informe" (documento con +) de cada foto
  document.querySelectorAll('.cap-foto-btn[title="Agregar a informe"]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      openNotaModal();
    });
  });

  // Botones del modal
  btnNotaVolver?.addEventListener('click', closeNotaModal);
  notaModalOverlay?.addEventListener('click', closeNotaModal);

  btnNotaGuardar?.addEventListener('click', () => {
    const nota = notaTextarea.value.trim();
    if (nota) {
      alert('Nota guardada exitosamente!');
      closeNotaModal();
    } else {
      alert('Por favor escribe una nota antes de guardar.');
    }
  });

  // Cerrar con Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && notaModal.classList.contains('active')) {
      closeNotaModal();
    }
  });
})();
</script>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\estudios\dashboard\dashboard-js.blade.php ENDPATH**/ ?>