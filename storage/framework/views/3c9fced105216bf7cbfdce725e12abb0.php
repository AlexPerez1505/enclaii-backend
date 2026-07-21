<script>
(function () {

  /* ── Tabs ── */
  const tabs   = document.querySelectorAll('.cfg-tab');
  const panels = document.querySelectorAll('.tab-panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
    });
  });


  /* ── Selector de fuentes ── */
  const FUENTES = [
    'Arial','Arial Black','Arial Narrow','Calibri','Cambria','Century Gothic',
    'Comic Sans MS','Consolas','Courier New','Franklin Gothic Medium',
    'Georgia','Gill Sans','Impact','Lucida Console','Lucida Sans Unicode',
    'Microsoft Sans Serif','Palatino Linotype','Segoe UI','Tahoma',
    'Times New Roman','Trebuchet MS','Verdana',
    'Helvetica','Garamond','Book Antiqua','Bookman Old Style',
    'Candara','Constantia','Corbel','Didact Gothic','EB Garamond',
    'Open Sans','Roboto','Lato','Montserrat','Poppins','Raleway',
    'Source Sans Pro','Ubuntu','Noto Sans','Merriweather','Playfair Display'
  ].sort();

  const btnFuente      = document.getElementById('btnFuente');
  const fuenteDropdown = document.getElementById('fuenteDropdown');
  const fuenteBuscar   = document.getElementById('fuenteBuscar');
  const fuenteList     = document.getElementById('fuenteList');
  const fuenteSelNom   = document.getElementById('fuenteSelNombre');
  const txtPreview     = document.getElementById('txtPreviewContent');
  const txtSize        = document.getElementById('txtSize');

  let fuenteActual = 'Arial';

  function renderFuentes(filtro) {
    fuenteList.innerHTML = '';
    FUENTES
      .filter(f => f.toLowerCase().includes(filtro.toLowerCase()))
      .forEach(f => {
        const li = document.createElement('li');
        li.textContent = f;
        li.style.fontFamily = f;
        if (f === fuenteActual) li.classList.add('active');
        li.addEventListener('click', () => {
          fuenteActual = f;
          fuenteSelNom.textContent = f;
          fuenteSelNom.style.fontFamily = f;
          if (txtPreview) txtPreview.style.fontFamily = f;
          fuenteDropdown.classList.remove('open');
          fuenteBuscar.value = '';
          renderFuentes('');
        });
        fuenteList.appendChild(li);
      });
  }

  renderFuentes('');

  if (btnFuente) {
    btnFuente.addEventListener('click', e => {
      e.stopPropagation();
      fuenteDropdown.classList.toggle('open');
      if (fuenteDropdown.classList.contains('open')) {
        fuenteBuscar.focus();
        /* Scroll al activo */
        const act = fuenteList.querySelector('.active');
        if (act) act.scrollIntoView({ block: 'center' });
      }
    });
  }

  if (fuenteBuscar) {
    fuenteBuscar.addEventListener('input', () => renderFuentes(fuenteBuscar.value));
  }

  /* Cerrar al clic fuera */
  document.addEventListener('click', e => {
    if (fuenteDropdown && !fuenteDropdown.contains(e.target) && e.target !== btnFuente) {
      fuenteDropdown.classList.remove('open');
    }
  });

  /* ── Reproducir: reproductor completo ── */
  const repList      = document.getElementById('repList');
  const repEmpty     = document.getElementById('repEmpty');
  const repStatus    = document.getElementById('repStatus');
  const repProgress  = document.getElementById('repProgress');
  const repPlayIcon  = document.getElementById('repPlayIcon');
  const repFileInput = document.getElementById('repFileInput');

  const repBtnOpen   = document.getElementById('repBtnOpen');
  const repBtnDel    = document.getElementById('repBtnDel');
  const repBtnPlay   = document.getElementById('repBtnPlay');   /* sidebar */
  const repBtnStop   = document.getElementById('repBtnStop');   /* sidebar */
  const repCtrlPlay  = document.getElementById('repCtrlPlay');  /* centro */
  const repCtrlStop  = document.getElementById('repCtrlStop');
  const repCtrlRew   = document.getElementById('repCtrlRew');
  const repCtrlPrev  = document.getElementById('repCtrlPrev');
  const repCtrlNext  = document.getElementById('repCtrlNext');
  const repCtrlCapture = document.getElementById('repCtrlCapture');

  let repSelected  = null;   /* elemento DOM seleccionado */
  let repPlaying   = false;
  let repTimer     = null;
  let repProgressV = 0;
  const PLAY_SVG   = `<circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/>`;
  const PAUSE_SVG  = `<circle cx="12" cy="12" r="10"/><line x1="10" y1="8" x2="10" y2="16"/><line x1="14" y1="8" x2="14" y2="16"/>`;

  /* Actualiza visibilidad del placeholder */
  function updateEmpty() {
    if (repEmpty) repEmpty.style.display = repList.querySelectorAll('.rep-list-item').length ? 'none' : 'block';
  }

  /* Selecciona un item */
  function selectItem(el) {
    repList.querySelectorAll('.rep-list-item').forEach(i => i.classList.remove('sel'));
    el.classList.add('sel');
    repSelected = el;
    if (repStatus) repStatus.textContent = '▶ ' + el.dataset.name;
  }

  /* Agrega un item a la lista */
  function repAddItem(name) {
    const div = document.createElement('div');
    div.className = 'rep-list-item';
    div.dataset.name = name;
    div.innerHTML = name;
    div.addEventListener('click', () => { stopPlayback(); selectItem(div); });
    div.addEventListener('dblclick', () => { selectItem(div); startPlayback(); });
    repList.appendChild(div);
    updateEmpty();
    if (!repSelected) selectItem(div);
  }

  /* Inicia reproducción simulada */
  function startPlayback() {
    if (!repSelected) return;
    repPlaying = true;
    repList.querySelectorAll('.rep-list-item').forEach(i => i.classList.remove('playing'));
    repSelected.classList.add('playing');
    if (repPlayIcon) repPlayIcon.innerHTML = PAUSE_SVG;
    if (repStatus)  repStatus.textContent = 'Reproduciendo: ' + repSelected.dataset.name;
    clearInterval(repTimer);
    repTimer = setInterval(() => {
      repProgressV = Math.min(100, repProgressV + 0.5);
      if (repProgress) repProgress.value = repProgressV;
      if (repProgressV >= 100) { stopPlayback(); repProgressV = 0; }
    }, 100);
  }

  /* Pausa */
  function pausePlayback() {
    repPlaying = false;
    clearInterval(repTimer);
    if (repPlayIcon) repPlayIcon.innerHTML = PLAY_SVG;
    if (repStatus && repSelected) repStatus.textContent = 'Pausado: ' + repSelected.dataset.name;
  }

  /* Detiene y resetea */
  function stopPlayback() {
    repPlaying = false;
    clearInterval(repTimer);
    repProgressV = 0;
    if (repProgress) repProgress.value = 0;
    if (repPlayIcon) repPlayIcon.innerHTML = PLAY_SVG;
    repList.querySelectorAll('.rep-list-item').forEach(i => i.classList.remove('playing'));
    if (repStatus) repStatus.textContent = repSelected ? repSelected.dataset.name : 'Sin video seleccionado';
  }

  /* Navegar lista */
  function navItem(dir) {
    const items = Array.from(repList.querySelectorAll('.rep-list-item'));
    if (!items.length) return;
    const idx = repSelected ? items.indexOf(repSelected) : -1;
    const next = items[(idx + dir + items.length) % items.length];
    stopPlayback();
    selectItem(next);
  }

  /* Botones abrir / sidebar */
  if (repBtnOpen)  repBtnOpen.addEventListener('click', () => repFileInput && repFileInput.click());
  if (repFileInput) {
    repFileInput.addEventListener('change', () => {
      Array.from(repFileInput.files).forEach(f => repAddItem(f.name));
      repFileInput.value = '';
    });
  }
  if (repBtnDel) {
    repBtnDel.addEventListener('click', () => {
      if (!repSelected) return;
      const wasPlaying = repPlaying;
      stopPlayback();
      const items = Array.from(repList.querySelectorAll('.rep-list-item'));
      const idx = items.indexOf(repSelected);
      repSelected.remove();
      repSelected = null;
      updateEmpty();
      const remaining = repList.querySelectorAll('.rep-list-item');
      if (remaining.length) {
        selectItem(remaining[Math.min(idx, remaining.length - 1)]);
        if (wasPlaying) startPlayback();
      } else {
        if (repStatus) repStatus.textContent = 'Sin video seleccionado';
        if (repProgress) repProgress.value = 0;
      }
    });
  }

  /* Sidebar play/stop */
  if (repBtnPlay) repBtnPlay.addEventListener('click', () => repPlaying ? pausePlayback() : startPlayback());
  if (repBtnStop) repBtnStop.addEventListener('click', stopPlayback);

  /* Controles centrales */
  if (repCtrlPlay) repCtrlPlay.addEventListener('click', () => repPlaying ? pausePlayback() : startPlayback());
  if (repCtrlStop) repCtrlStop.addEventListener('click', stopPlayback);
  if (repCtrlRew)  repCtrlRew.addEventListener('click', () => { repProgressV = Math.max(0, repProgressV - 10); if (repProgress) repProgress.value = repProgressV; });
  if (repCtrlPrev) repCtrlPrev.addEventListener('click', () => navItem(-1));
  if (repCtrlNext) repCtrlNext.addEventListener('click', () => navItem(1));

  /* Captura foto */
  if (repCtrlCapture) {
    repCtrlCapture.addEventListener('click', () => {
      const ts = new Date().toLocaleTimeString();
      if (repStatus) repStatus.textContent = `Foto capturada ${ts}`;
      setTimeout(() => {
        if (repStatus && repSelected) repStatus.textContent = (repPlaying ? 'Reproduciendo: ' : '') + repSelected.dataset.name;
      }, 2000);
    });
  }

  /* Scrub manual */
  if (repProgress) {
    repProgress.addEventListener('input', () => { repProgressV = +repProgress.value; });
  }

  updateEmpty();

  /* Tamaño en tiempo real — sincronización bidireccional */
  const txtSizeRange = document.getElementById('txtSizeRange');
  if (txtSize && txtPreview) {
    txtSize.addEventListener('input', () => {
      txtPreview.style.fontSize = txtSize.value + 'px';
      if (txtSizeRange) txtSizeRange.value = txtSize.value;
    });
    if (txtSizeRange) {
      txtSizeRange.addEventListener('input', () => {
        txtSize.value = txtSizeRange.value;
        txtPreview.style.fontSize = txtSizeRange.value + 'px';
      });
    }
  }

  /* ═════════════════════════════════════════════════════════════
     MODAL DE MÁS OPCIONES - FUNCIONALIDAD
     ═════════════════════════════════════════════════════════════ */

  /* Elementos del modal */
  const btnMasOpciones = document.getElementById('btnMasOpciones');
  const masOpcionesModal = document.getElementById('masOpcionesModal');
  const cerrarMasOpciones = document.getElementById('cerrarMasOpciones');
  const canalBtns = document.querySelectorAll('.canal-btn');
  const areaCapturaSelect = document.getElementById('areaCapturaSelect');
  const canalVideoSelect = document.getElementById('canalVideoSelect');

  /* Iconos funcionales */
  const iconStop = document.getElementById('iconStop');
  const iconVideo = document.getElementById('iconVideo');
  const iconFilm = document.getElementById('iconFilm');
  const iconFilmStrip = document.getElementById('iconFilmStrip');
  const iconCrop = document.getElementById('iconCrop');
  const iconSettings = document.getElementById('iconSettings');
  const iconCamera = document.getElementById('iconCamera');

  let isRecording = false;

  /* Abrir modal */
  if (btnMasOpciones && masOpcionesModal) {
    btnMasOpciones.addEventListener('click', () => {
      masOpcionesModal.classList.add('active');
    });
  }

  /* Cerrar modal */
  if (cerrarMasOpciones && masOpcionesModal) {
    cerrarMasOpciones.addEventListener('click', () => {
      masOpcionesModal.classList.remove('active');
    });
  }

  /* Cerrar al hacer clic fuera del contenido */
  if (masOpcionesModal) {
    masOpcionesModal.addEventListener('click', (e) => {
      if (e.target === masOpcionesModal) {
        masOpcionesModal.classList.remove('active');
      }
    });
  }

  /* Selección de canales 1-6 */
  canalBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      canalBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const canal = btn.dataset.canal;
      console.log('Canal seleccionado:', canal);

      /* Actualizar el log con el canal seleccionado */
      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.className = 'hl';
        span.textContent = `[CANAL] Cambiado a canal ${canal}`;
        log.insertBefore(span, log.firstChild);
      }
    });
  });

  /* Cambio de área de captura */
  if (areaCapturaSelect) {
    areaCapturaSelect.addEventListener('change', (e) => {
      const area = e.target.value;
      console.log('Área de captura:', area);

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.textContent = `[AREA] ${area === 'full' ? 'Pantalla Completa' : area === 'window' ? 'Ventana Activa' : 'Región Personalizada'}`;
        log.insertBefore(span, log.firstChild);
      }
    });
  }

  /* Cambio de canal de video */
  if (canalVideoSelect) {
    canalVideoSelect.addEventListener('change', (e) => {
      const canal = e.target.value;
      console.log('Canal de video:', canal);

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.textContent = `[VIDEO] Canal ${canal} seleccionado`;
        log.insertBefore(span, log.firstChild);
      }
    });
  }

  /* Icono STOP - Detener grabación */
  if (iconStop) {
    iconStop.addEventListener('click', () => {
      isRecording = false;
      iconVideo.classList.remove('recording');
      console.log('Grabación detenida');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.className = 'hl';
        span.textContent = '[STOP] Grabación detenida';
        log.insertBefore(span, log.firstChild);
      }
    });
  }

  /* Icono VIDEO - Iniciar/Detener grabación */
  if (iconVideo) {
    iconVideo.addEventListener('click', () => {
      isRecording = !isRecording;

      if (isRecording) {
        iconVideo.classList.add('recording');
        console.log('Grabación iniciada');

        const log = document.querySelector('.cfg-log');
        if (log) {
          const span = document.createElement('span');
          span.className = 'hl';
          span.textContent = '[RECORD] Grabación iniciada';
          log.insertBefore(span, log.firstChild);
        }

        /* Simular grabación - agregar entrada al log cada 5 segundos */
        window.recordingInterval = setInterval(() => {
          if (isRecording && log) {
            const span = document.createElement('span');
            span.textContent = `[RECORD] Grabando... ${new Date().toLocaleTimeString()}`;
            log.insertBefore(span, log.firstChild);
          }
        }, 5000);

      } else {
        iconVideo.classList.remove('recording');
        console.log('Grabación detenida');

        if (window.recordingInterval) {
          clearInterval(window.recordingInterval);
        }

        const log = document.querySelector('.cfg-log');
        if (log) {
          const span = document.createElement('span');
          span.className = 'hl';
          span.textContent = '[RECORD] Grabación finalizada';
          log.insertBefore(span, log.firstChild);
        }
      }
    });
  }

  /* Icono FILM - Modo película */
  if (iconFilm) {
    iconFilm.addEventListener('click', () => {
      console.log('Modo película activado');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.className = 'hl';
        span.textContent = '[FILM] Modo película activado';
        log.insertBefore(span, log.firstChild);
      }

      /* Efecto visual en el preview */
      const videoWrap = document.querySelector('.cfg-video-wrap');
      if (videoWrap) {
        videoWrap.style.border = '2px solid var(--cyan)';
        setTimeout(() => {
          videoWrap.style.border = '2px solid var(--blue)';
        }, 1000);
      }
    });
  }

  /* Icono FILMSTRIP - Tira de fotos */
  if (iconFilmStrip) {
    iconFilmStrip.addEventListener('click', () => {
      console.log('Tira de fotos');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.className = 'hl';
        span.textContent = '[FILMSTRIP] Captura múltiple iniciada';
        log.insertBefore(span, log.firstChild);
      }

      /* Simular captura múltiple */
      let count = 0;
      const interval = setInterval(() => {
        count++;
        if (log && count <= 3) {
          const span = document.createElement('span');
          span.textContent = `[CAPTURE] Foto ${count}/3 capturada`;
          log.insertBefore(span, log.firstChild);
        }
        if (count >= 3) clearInterval(interval);
      }, 800);
    });
  }

  /* Icono CROP - Recortar área */
  if (iconCrop) {
    iconCrop.addEventListener('click', () => {
      console.log('Recortar área');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.textContent = '[CROP] Modo recorte activado - Seleccione área';
        log.insertBefore(span, log.firstChild);
      }

      /* Cambiar a tab Display para mostrar opciones de recorte */
      const displayTab = document.querySelector('[data-tab="display"]');
      if (displayTab) {
        displayTab.click();
      }

      /* Cerrar modal */
      masOpcionesModal.classList.remove('active');
    });
  }

  /* Icono SETTINGS - Configuración avanzada */
  if (iconSettings) {
    iconSettings.addEventListener('click', () => {
      console.log('Configuración avanzada');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const span = document.createElement('span');
        span.textContent = '[SETTINGS] Abriendo configuración avanzada...';
        log.insertBefore(span, log.firstChild);
      }

      /* Cambiar a tab Grabación para mostrar más opciones */
      const grabacionTab = document.querySelector('[data-tab="grabacion"]');
      if (grabacionTab) {
        grabacionTab.click();
      }

      /* Cerrar modal */
      masOpcionesModal.classList.remove('active');
    });
  }

  /* Icono CAMERA - Capturar foto */
  if (iconCamera) {
    iconCamera.addEventListener('click', () => {
      console.log('Capturar foto');

      const log = document.querySelector('.cfg-log');
      if (log) {
        const timestamp = new Date().toISOString().slice(0,19).replace(/[:T]/g,'-');
        const span = document.createElement('span');
        span.className = 'hl';
        span.textContent = `[PHOTO] Capturada: MariaGonzalez-${timestamp}.JPG`;
        log.insertBefore(span, log.firstChild);
      }

      /* Efecto flash en el preview */
      const videoWrap = document.querySelector('.cfg-video-wrap');
      if (videoWrap) {
        const flash = document.createElement('div');
        flash.style.cssText = 'position:absolute;inset:0;background:#fff;opacity:.7;pointer-events:none;z-index:100;';
        videoWrap.appendChild(flash);
        setTimeout(() => flash.remove(), 150);
      }
    });
  }

  /* Cerrar modal con tecla ESC */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && masOpcionesModal && masOpcionesModal.classList.contains('active')) {
      masOpcionesModal.classList.remove('active');
    }
  });

})();
</script>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\estudios\configuracion\configuracion-js.blade.php ENDPATH**/ ?>