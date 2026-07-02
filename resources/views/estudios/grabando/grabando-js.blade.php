<script>
(function () {
  const ESTUDIO_ID    = @json($estudio?->id);
  const CSRF          = @json(csrf_token());
  const CAPTURAS_URL  = @json(route('nuevo-estudio.capturas.store'));
  const FINALIZAR_URL = @json(route('nuevo-estudio.finalizar'));
  const FINALIZADO_URL = @json(route('nuevo-estudio.finalizado', ['estudio_id' => $estudio?->id]));
  const MOSTRAR_FINALIZADO = false;

  let secs = 0, paused = false, fotos = {{ $numCapturas }};

  function pad(n) { return String(n).padStart(2,'0'); }
  function fmt(s) { return pad(Math.floor(s/3600))+':'+pad(Math.floor((s%3600)/60))+':'+pad(s%60); }

  const timerEl = document.getElementById('recTimer');
  const sideEl  = document.getElementById('recTimerSide');
  const fotosEl = document.getElementById('recFotos');
  const tl      = document.getElementById('recTimeline');

  const iv = setInterval(() => {
    if (!paused) { secs++; const t = fmt(secs); timerEl && (timerEl.textContent = t); sideEl && (sideEl.textContent = t); }
  }, 1000);

  /* Thumb genérico */
  function addThumb() {
    if (!tl) return;
    const el = document.createElement('div');
    el.className = 'studio-thumb';
    const ts = fmt(secs);
    el.innerHTML = `<div class="studio-thumb-inner"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg></div><span class="studio-thumb-time">${ts}</span>`;
    el.addEventListener('click', () => { document.querySelectorAll('.studio-thumb').forEach(t => t.classList.remove('active')); el.classList.add('active'); });
    tl.appendChild(el);
    tl.scrollLeft = tl.scrollWidth;
  }

  /* ── Botón Modo Expandido ── */
  const btnFullscreen = document.getElementById('btnFullscreen');

  function toggleExpanded() {
    document.body.classList.toggle('studio-expanded');
  }

  // Click en botón para modo expandido
  btnFullscreen?.addEventListener('click', toggleExpanded);

  /* Pausa/Continuar */
  const btnPausa = document.getElementById('btnPausa');

  function updatePauseButton() {
    if (paused) {
      btnPausa.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor" stroke="none"/></svg> Continuar';
      btnPausa.classList.add('paused');
    } else {
      btnPausa.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg> Pausar';
      btnPausa.classList.remove('paused');
    }
  }

  updatePauseButton();

  btnPausa?.addEventListener('click', function () {
    paused = !paused;
    updatePauseButton();
    if (paused) { pauseMediaRecorder(); } else { resumeMediaRecorder(); }
  });

  /* ── Cámara web en vivo ── */
  const webcam = document.getElementById('studioWebcam');
  const captureCanvas = document.getElementById('captureCanvas');
  const webcamFallback = document.getElementById('webcamFallback');
  let webcamStream = null;
  let mediaRecorder = null;
  let recordedChunks = [];
  let recordedBlob = null;

  /* Elige el mejor formato soportado */
  function getBestMimeType() {
    const types = ['video/webm;codecs=vp9,opus','video/webm;codecs=vp8,opus','video/webm','video/mp4'];
    return types.find(t => MediaRecorder.isTypeSupported(t)) || '';
  }

  function startMediaRecorder(stream) {
    if (!window.MediaRecorder) return;
    recordedChunks = [];
    const mimeType = getBestMimeType();
    const opts = mimeType ? { mimeType } : {};
    try {
      mediaRecorder = new MediaRecorder(stream, opts);
    } catch(e) {
      mediaRecorder = new MediaRecorder(stream);
    }
    mediaRecorder.addEventListener('dataavailable', e => {
      if (e.data && e.data.size > 0) recordedChunks.push(e.data);
    });
    mediaRecorder.addEventListener('stop', () => {
      const mime = (mediaRecorder.mimeType || 'video/webm').split(';')[0];
      recordedBlob = new Blob(recordedChunks, { type: mime });
    });
    mediaRecorder.start(1000);
  }

  function pauseMediaRecorder() {
    if (mediaRecorder && mediaRecorder.state === 'recording') mediaRecorder.pause();
  }

  function resumeMediaRecorder() {
    if (mediaRecorder && mediaRecorder.state === 'paused') mediaRecorder.resume();
  }

  function stopMediaRecorder() {
    return new Promise(resolve => {
      if (!mediaRecorder || mediaRecorder.state === 'inactive') { resolve(); return; }
      mediaRecorder.addEventListener('stop', resolve, { once: true });
      mediaRecorder.stop();
    });
  }

  async function initWebcam() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      showWebcamError('Tu navegador no permite acceder a la cámara.');
      return;
    }
    try {
      webcamStream = await navigator.mediaDevices.getUserMedia({
        video: { width: { ideal: 1920 }, height: { ideal: 1080 } },
        audio: true
      });
      if (webcam) { webcam.srcObject = webcamStream; }
      if (webcamFallback) webcamFallback.style.display = 'none';
      startMediaRecorder(webcamStream);
    } catch (e) {
      showWebcamError('No se pudo acceder a la cámara: ' + (e && e.message ? e.message : e));
    }
  }
  function showWebcamError(msg) {
    if (webcam) webcam.style.display = 'none';
    if (webcamFallback) { webcamFallback.style.display = 'flex'; webcamFallback.textContent = msg; }
  }
  function stopWebcam() {
    if (webcamStream) { webcamStream.getTracks().forEach(t => t.stop()); webcamStream = null; }
  }
  if (!MOSTRAR_FINALIZADO) initWebcam();

  /* Quitar mensaje "aún no hay fotos" al agregar la primera */
  function removeEmptyHint() {
    const hint = document.getElementById('recTimelineEmpty');
    if (hint) hint.remove();
  }

  /* Agrega una miniatura de foto a la galería en vivo */
  function addPhotoThumb(url) {
    if (!tl) return;
    removeEmptyHint();
    document.querySelectorAll('.studio-thumb.active').forEach(t => t.classList.remove('active'));
    const el = document.createElement('div');
    el.className = 'studio-thumb active';
    el.innerHTML = `<div class="studio-thumb-inner" style="padding:0;overflow:hidden"><img src="${url}" alt="captura" style="width:100%;height:100%;object-fit:cover;border-radius:8px"></div><span class="studio-thumb-time">${fmt(secs)}</span>`;
    el.addEventListener('click', () => { document.querySelectorAll('.studio-thumb').forEach(t => t.classList.remove('active')); el.classList.add('active'); });
    tl.appendChild(el);
    tl.scrollLeft = tl.scrollWidth;
  }

  /* Agrega la foto a la galería del estudio terminado */
  function addFinalCap(url) {
    const strip = document.getElementById('sfCapsStrip');
    if (!strip) return;
    const empty = document.getElementById('sfCapsEmpty');
    if (empty) empty.remove();
    const n = strip.querySelectorAll('.studio-final-cap-item').length + 1;
    const item = document.createElement('div');
    item.className = 'studio-final-cap-item';
    item.innerHTML = `<div class="studio-final-cap-thumb"><img src="${url}" alt="captura" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover"><span class="studio-final-cap-num">${n}</span><span class="studio-final-cap-check"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></span></div><div class="studio-final-cap-ts">${fmt(secs)}</div>`;
    item.addEventListener('click', () => { strip.querySelectorAll('.studio-final-cap-item').forEach(t => t.classList.remove('sel')); item.classList.add('sel'); });
    strip.appendChild(item);
  }

  /* ── Galería del estudio terminado: ver imagen en grande ── */
  const sfMainImg = document.getElementById('sfMainImg');
  const sfVideoCenter = document.getElementById('sfVideoCenter');
  const sfCapsStrip = document.getElementById('sfCapsStrip');

  const sfPlayer = document.querySelector('.studio-finalizado-wrap .sf-video-player');

  function showVideo() {
    if (sfMainImg) sfMainImg.style.display = 'none';
    if (sfVideoCenter) sfVideoCenter.style.display = '';
    const sfControls = sfPlayer ? sfPlayer.querySelector('.sf-video-controls') : null;
    if (sfControls) sfControls.style.display = '';
    const btnVolver = document.getElementById('sfBtnVolverVideo');
    if (btnVolver) btnVolver.style.display = 'none';
  }

  function showMainImage(url) {
    if (!sfMainImg || !url) return;
    sfMainImg.src = url;
    sfMainImg.style.display = 'block';
    if (sfVideoCenter) sfVideoCenter.style.display = 'none';
    const sfControls = sfPlayer ? sfPlayer.querySelector('.sf-video-controls') : null;
    if (sfControls) sfControls.style.display = 'none';
    /* Mostrar botón "Ver video" */
    let btnVolver = document.getElementById('sfBtnVolverVideo');
    if (!btnVolver && sfPlayer) {
      btnVolver = document.createElement('button');
      btnVolver.id = 'sfBtnVolverVideo';
      btnVolver.className = 'sf-btn-volver-video';
      btnVolver.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg> Ver video';
      btnVolver.addEventListener('click', showVideo);
      sfPlayer.appendChild(btnVolver);
    }
    if (btnVolver) btnVolver.style.display = 'flex';
  }

  function selectCapItem(item) {
    if (!item || !sfCapsStrip) return;
    sfCapsStrip.querySelectorAll('.studio-final-cap-item').forEach(t => t.classList.remove('sel'));
    item.classList.add('sel');
    const img = item.querySelector('img');
    if (img) showMainImage(img.src);
  }

  sfCapsStrip?.addEventListener('click', (e) => {
    const item = e.target.closest('.studio-final-cap-item');
    if (item) selectCapItem(item);
  });

  function showFirstCapture() {
    if (!sfCapsStrip) return;
    const sel = sfCapsStrip.querySelector('.studio-final-cap-item.sel') || sfCapsStrip.querySelector('.studio-final-cap-item');
    if (sel) selectCapItem(sel);
  }

  /* ── Guardar estudio — el estudio ya fue persistido al pulsar Terminar ── */
  const btnGuardarEstudio = document.getElementById('btnGuardarEstudio');
  btnGuardarEstudio?.addEventListener('click', () => {
    window.location.href = GALERIA_URL;
  });

  /* Sube la captura al servidor */
  function uploadCapture(blob) {
    const fd = new FormData();
    fd.append('files[]', blob, 'captura_' + Date.now() + '.jpg');
    if (ESTUDIO_ID) fd.append('estudio_id', ESTUDIO_ID);
    fd.append('categoria', 'captura');
    fetch(CAPTURAS_URL, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: fd
    })
    .then(r => r.json())
    .then(data => {
      if (data && data.ok && Array.isArray(data.archivos)) {
        data.archivos.forEach(a => { addPhotoThumb(a.url); addFinalCap(a.url); });
        fotos += data.archivos.length;
        if (fotosEl) fotosEl.textContent = fotos;
        const sfCount = document.getElementById('sfFotosCount');
        if (sfCount) sfCount.textContent = fotos;
      }
    })
    .catch(err => console.error('Error guardando captura', err));
  }

  /* Capturar Foto */
  const btnCapturarFoto = document.getElementById('btnCapturarFoto');
  btnCapturarFoto?.addEventListener('click', () => {
    const videoScreen = document.getElementById('videoScreen');
    if (videoScreen) {
      const flash = document.createElement('div');
      flash.style.cssText = 'position:absolute;inset:0;background:#fff;opacity:0.6;z-index:20;pointer-events:none;transition:opacity 300ms ease;';
      videoScreen.style.position = 'relative';
      videoScreen.appendChild(flash);
      requestAnimationFrame(() => { flash.style.opacity = '0'; });
      setTimeout(() => flash.remove(), 350);
    }
    if (!webcam || !webcam.videoWidth || !captureCanvas) {
      console.warn('La cámara no está lista todavía.');
      return;
    }
    const w = webcam.videoWidth, h = webcam.videoHeight;
    captureCanvas.width = w;
    captureCanvas.height = h;
    captureCanvas.getContext('2d').drawImage(webcam, 0, 0, w, h);
    captureCanvas.toBlob((blob) => { if (blob) uploadCapture(blob); }, 'image/jpeg', 0.92);
  });

  /* Detener grabación */
  const btnDetener = document.getElementById('btnDetener');
  btnDetener?.addEventListener('click', async () => {
    clearInterval(iv);
    paused = true;
    updatePauseButton();
    await stopMediaRecorder();
    stopWebcam();
    const recText = document.querySelector('.studio-rec-text');
    if (recText) recText.textContent = 'DETENIDO';
    const recDot = document.querySelector('.studio-rec-dot');
    if (recDot) recDot.style.animation = 'none';
  });

  /* ── Terminar Estudio ── */
  const btnTerminar = document.querySelector('.studio-terminar-btn');
  const wrapPrincipal = document.querySelector('.studio-wrap');
  const wrapFinalizado = document.getElementById('studioFinalizado');

  if (MOSTRAR_FINALIZADO) {
    clearInterval(iv);
    stopWebcam();
    /* Duración real desde el servidor */
    const durSrvSecs = @json($estudio?->duracion_segundos ?? 0);
    if (durSrvSecs > 0) {
      const durSrvStr = fmt(durSrvSecs);
      const elDur = document.getElementById('sfResumenDuracion');
      if (elDur && elDur.textContent === '--:--:--') elDur.textContent = durSrvStr;
      const elDurEm = document.getElementById('sfResumenDuracionEm');
      if (elDurEm && elDurEm.textContent === '--:--:--') elDurEm.textContent = durSrvStr;
    }
    /* Inicializar player — el src ya viene del HTML si hay video_path */
    initFinalPlayer();
    showFirstCapture();
  }

  btnTerminar?.addEventListener('click', async (e) => {
    e.preventDefault();
    clearInterval(iv);
    await stopMediaRecorder();
    stopWebcam();

    /* Poblar duración real en resumen */
    const durStr = fmt(secs);
    const elDur = document.getElementById('sfResumenDuracion');
    if (elDur) elDur.textContent = durStr;
    const elDurEm = document.getElementById('sfResumenDuracionEm');
    if (elDurEm) elDurEm.textContent = durStr;

    /* Persistir en servidor y redirigir a página dedicada */
    const fd = new FormData();
    if (ESTUDIO_ID) fd.append('estudio_id', ESTUDIO_ID);
    fd.append('duracion_segundos', secs);
    if (recordedBlob && recordedBlob.size > 0) {
      const ext = recordedBlob.type.includes('mp4') ? 'mp4' : 'webm';
      fd.append('video', recordedBlob, `estudio_${ESTUDIO_ID}_${Date.now()}.${ext}`);
    }
    fetch(FINALIZAR_URL, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: fd
    })
    .then(() => { window.location.href = FINALIZADO_URL; })
    .catch(err => {
      console.error('Error al finalizar estudio:', err);
      window.location.href = FINALIZADO_URL;
    });
  });

  /* ── Dropdown Compartir ── */
  const btnCompartir   = document.getElementById('btnCompartir');
  const sfShareDropdown = document.getElementById('sfShareDropdown');

  btnCompartir?.addEventListener('click', (e) => {
    e.stopPropagation();
    sfShareDropdown?.classList.toggle('open');
  });
  document.addEventListener('click', () => sfShareDropdown?.classList.remove('open'));

  /* Descargar video grabado */
  document.getElementById('sfDownloadVideo')?.addEventListener('click', () => {
    if (!recordedBlob || recordedBlob.size === 0) return;
    const ext = recordedBlob.type.includes('mp4') ? 'mp4' : 'webm';
    const a = document.createElement('a');
    a.href = URL.createObjectURL(recordedBlob);
    a.download = `estudio_{{ $estudio?->folio ?? 'video' }}_${Date.now()}.${ext}`;
    a.click();
    sfShareDropdown?.classList.remove('open');
  });

  /* Descargar imágenes — crea un zip descargando una a una */
  document.getElementById('sfDownloadImgs')?.addEventListener('click', () => {
    const imgs = document.querySelectorAll('#sfCapsStrip img');
    imgs.forEach((img, i) => {
      const a = document.createElement('a');
      a.href = img.src;
      a.download = `captura_${i + 1}.jpg`;
      a.target = '_blank';
      a.click();
    });
    sfShareDropdown?.classList.remove('open');
  });

  /* ── Botón Emergencia ── */
  const btnEmergencia = document.querySelector('.studio-btn-emergency');
  const wrapEmergencia = document.getElementById('studioEmergencia');

  btnEmergencia?.addEventListener('click', () => {
    wrapPrincipal.style.display = 'none';
    wrapEmergencia.classList.add('active');
    clearInterval(iv);
  });

  /* ── Controles del Video Player ── */
  function formatTime(seconds) {
    if (!isFinite(seconds) || isNaN(seconds)) return '00:00';
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = Math.floor(seconds % 60);
    const mm = String(m).padStart(2, '0');
    const ss = String(s).padStart(2, '0');
    return h > 0 ? `${h}:${mm}:${ss}` : `${mm}:${ss}`;
  }

  function setupVideoPlayer(container, playBigId, playBtnId, timeId, speedId, fsId, accent) {
    const player = document.querySelector(container);
    if (!player) return;
    const playBig = document.getElementById(playBigId);
    const playBtn = document.getElementById(playBtnId);
    const timeDisplay = document.getElementById(timeId);
    const speedBtn = document.getElementById(speedId);
    const fsBtn = document.getElementById(fsId);
    const progressBar = player.querySelector('.sf-prog-wrap');
    const progressFill = player.querySelector('.sf-prog-fill');
    const progressThumb = player.querySelector('.sf-prog-thumb');
    const rewindBtn = player.querySelector('.sf-ctrl-row .sf-ctrl-btn:nth-child(2)');
    const volBtn = player.querySelector('.sf-vol-wrap svg');
    const volFill = player.querySelector('.sf-vol-fill');

      /* Busca el <video> real dentro del player */
    const videoEl = player.querySelector('video') || document.getElementById('sfVideoEl');
    const speeds = ['0.5x','0.75x','1.0x','1.25x','1.5x','2.0x'];
    let sIdx = 2;
    let isMuted = false;

    function updateProgress() {
      if (!videoEl || !videoEl.duration) return;
      const percent = (videoEl.currentTime / videoEl.duration) * 100;
      if (progressFill) progressFill.style.width = percent + '%';
      if (progressThumb) progressThumb.style.left = percent + '%';
      if (timeDisplay) timeDisplay.textContent = formatTime(videoEl.currentTime) + ' / ' + formatTime(videoEl.duration);
    }

    if (videoEl) {
      videoEl.addEventListener('timeupdate', updateProgress);
      videoEl.addEventListener('loadedmetadata', updateProgress);
    }

    function syncPlayIcons(playing) {
      [playBig, playBtn].forEach(btn => {
        if (!btn) return;
        const p = btn.querySelector('.play-icon');
        const q = btn.querySelector('.pause-icon');
        if (p) p.style.display = playing ? 'none' : '';
        if (q) q.style.display = playing ? '' : 'none';
      });
    }

    function togglePlay() {
      if (!videoEl) return;
      if (videoEl.paused) { videoEl.play(); syncPlayIcons(true); }
      else { videoEl.pause(); syncPlayIcons(false); }
    }

    videoEl?.addEventListener('ended', () => syncPlayIcons(false));

    playBig?.addEventListener('click', togglePlay);
    playBtn?.addEventListener('click', togglePlay);

    rewindBtn?.addEventListener('click', () => {
      if (videoEl) { videoEl.currentTime = Math.max(0, videoEl.currentTime - 10); updateProgress(); }
    });

    progressBar?.addEventListener('click', (e) => {
      if (!videoEl || !videoEl.duration) return;
      const rect = progressBar.getBoundingClientRect();
      const percent = (e.clientX - rect.left) / rect.width;
      videoEl.currentTime = Math.max(0, Math.min(videoEl.duration, percent * videoEl.duration));
    });

    let isDragging = false;
    progressThumb?.addEventListener('mousedown', () => isDragging = true);
    document.addEventListener('mouseup', () => isDragging = false);
    document.addEventListener('mousemove', (e) => {
      if (isDragging && progressBar && videoEl && videoEl.duration) {
        const rect = progressBar.getBoundingClientRect();
        const percent = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
        videoEl.currentTime = percent * videoEl.duration;
      }
    });

    volBtn?.addEventListener('click', () => {
      if (!videoEl) return;
      isMuted = !isMuted;
      videoEl.muted = isMuted;
      if (volFill) volFill.style.width = isMuted ? '0%' : '70%';
    });

    speedBtn?.addEventListener('click', () => {
      sIdx = (sIdx + 1) % speeds.length;
      speedBtn.textContent = speeds[sIdx];
      if (videoEl) videoEl.playbackRate = parseFloat(speeds[sIdx]);
    });

    fsBtn?.addEventListener('click', () => {
      if (!document.fullscreenElement) {
        player.requestFullscreen().catch(err => console.log(err));
      } else {
        document.exitFullscreen();
      }
    });
  }

  /* El player del estudio finalizado se inicializa lazy, cuando el blob ya está cargado */
  let finalPlayerReady = false;
  function initFinalPlayer() {
    if (finalPlayerReady) return;
    finalPlayerReady = true;
    setupVideoPlayer('.studio-finalizado-wrap .sf-video-player', 'sfPlayBigFinal', 'sfPlayBtnFinal', 'sfTimeFinal', 'sfSpeedFinal', 'btnExpandirFinal', '#2e7bf6');
  }
  if (MOSTRAR_FINALIZADO) initFinalPlayer();
  setupVideoPlayer('.studio-emergencia-wrap .sf-video-player', 'sfPlayBigEm', 'sfPlayBtnEm', 'sfTimeEm', 'sfSpeedEm', 'btnExpandirEmergencia', '#dc2626');

  /* Cambiar tema desde cualquier botón del estudio */
  function updateAllStudioThemeIcons() {
    const isLight = document.documentElement.dataset.theme === 'light';
    document.querySelectorAll('.studio-theme-btn').forEach(btn => {
      const moon = btn.querySelector('.icon-moon');
      const sun = btn.querySelector('.icon-sun');
      if (moon) moon.style.display = isLight ? 'block' : 'none';
      if (sun) sun.style.display = isLight ? 'none' : 'block';
    });
  }
  updateAllStudioThemeIcons();
  document.querySelectorAll('.studio-theme-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const html = document.documentElement;
      const next = html.dataset.theme === 'light' ? 'dark' : 'light';
      html.dataset.theme = next;
      localStorage.setItem('enclaii-theme', next);
      updateAllStudioThemeIcons();
    });
  });

  /* Simular captura de imagen en la interfaz finalizada/emergencia */
  document.querySelectorAll('.btn-simular-captura').forEach(btn => {
    btn.addEventListener('click', () => {
      const player = document.querySelector('.studio-finalizado-wrap.active .sf-video-player, .studio-emergencia-wrap.active .sf-video-player');
      if (!player) return;
      const flash = document.createElement('div');
      flash.style.cssText = 'position:absolute;inset:0;background:#fff;opacity:0.6;z-index:30;pointer-events:none;transition:opacity 300ms ease;';
      player.style.position = 'relative';
      player.appendChild(flash);
      requestAnimationFrame(() => { flash.style.opacity = '0'; });
      setTimeout(() => flash.remove(), 350);
    });
  });

  window.addEventListener('beforeunload', () => { clearInterval(iv); stopMediaRecorder(); stopWebcam(); });
})();
</script>
