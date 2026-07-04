<script>
(function () {

  /* ── Referencias DOM ── */
  const dropzone    = document.getElementById('vidDropzone');
  const fileInput   = document.getElementById('vidFileInput');
  const listScroll  = document.getElementById('vidListScroll');
  const listEmpty   = document.getElementById('vidListEmpty');
  const listCount   = document.getElementById('vidListCount');
  const searchInput = document.getElementById('vidSearch');
  const uploading   = document.getElementById('vidUploading');
  const uploadText  = document.getElementById('vidUploadText');

  const playerWrap  = document.getElementById('vidPlayerWrap');
  const playerEmpty = document.getElementById('vidPlayerEmpty');
  const videoEl     = document.getElementById('vidVideo');
  const progFill    = document.getElementById('vidProgFill');
  const progThumb   = document.getElementById('vidProgThumb');
  const progWrap    = document.getElementById('vidProgWrap');
  const timeEl      = document.getElementById('vidTime');
  const playBtn     = document.getElementById('vidPlayBtn');
  const muteBtn     = document.getElementById('vidMuteBtn');
  const volSlider   = document.getElementById('vidVolSlider');
  const speedBtn    = document.getElementById('vidSpeedBtn');
  const fsBtn       = document.getElementById('vidFsBtn');
  const rewindBtn   = document.getElementById('vidRewindBtn');
  const fwdBtn      = document.getElementById('vidFwdBtn');
  const infoName    = document.getElementById('vidInfoName');
  const infoMeta    = document.getElementById('vidInfoMeta');
  const dlBtn       = document.getElementById('vidDlBtn');
  const delBtn      = document.getElementById('vidDelBtn');
  const clearAllBtn = document.getElementById('vidClearAll');

  const statTotal   = document.getElementById('statTotal');
  const statSize    = document.getElementById('statSize');
  const statDur     = document.getElementById('statDur');

  const PLAY_SVG  = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor" stroke="none"/></svg>`;
  const PAUSE_SVG = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>`;
  const VOL_SVG   = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>`;
  const MUTE_SVG  = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/></svg>`;

  const SPEEDS = ['0.5x', '0.75x', '1.0x', '1.25x', '1.5x', '2.0x'];
  let speedIdx = 2;

  /* ── Estado ── */
  let videos      = [];   /* { name, size, url, duration, file } */
  let activeIdx   = null;
  let totalBytes  = 0;
  let totalSecs   = 0;

  /* ── Helpers ── */
  function fmtSize(bytes) {
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    if (bytes < 1024 * 1024 * 1024) return (bytes / 1024 / 1024).toFixed(1) + ' MB';
    return (bytes / 1024 / 1024 / 1024).toFixed(2) + ' GB';
  }
  function fmtTime(s) {
    if (!s || isNaN(s)) return '0:00';
    const h = Math.floor(s / 3600);
    const m = Math.floor((s % 3600) / 60);
    const sec = Math.floor(s % 60);
    return h > 0
      ? `${h}:${String(m).padStart(2,'0')}:${String(sec).padStart(2,'0')}`
      : `${m}:${String(sec).padStart(2,'0')}`;
  }
  function fmtTotalDur(secs) {
    const h = Math.floor(secs / 3600);
    const m = Math.floor((secs % 3600) / 60);
    const s = Math.floor(secs % 60);
    if (h > 0) return `${h}h ${m}m`;
    if (m > 0) return `${m}m ${s}s`;
    return `${s}s`;
  }

  /* ── Estadísticas ── */
  function updateStats() {
    if (statTotal) statTotal.textContent = videos.length;
    if (statSize)  statSize.textContent  = fmtSize(totalBytes);
    if (statDur)   statDur.textContent   = videos.length ? fmtTotalDur(totalSecs) : '0s';
    if (listCount) listCount.textContent = videos.length;
  }

  /* ── Render lista ── */
  function renderList(filtro) {
    listScroll.innerHTML = '';
    const q = (filtro || '').toLowerCase();
    const filtered = videos.filter(v => v.name.toLowerCase().includes(q));

    if (filtered.length === 0) {
      listScroll.innerHTML = `<div class="vid-list-empty">${videos.length === 0 ? 'Aún no hay videos. Arrastra o sube archivos.' : 'Sin resultados para "' + filtro + '".'}</div>`;
      return;
    }

    filtered.forEach((v, fi) => {
      const realIdx = videos.indexOf(v);
      const item = document.createElement('div');
      item.className = 'vid-item' + (realIdx === activeIdx ? ' active' : '');
      item.dataset.idx = realIdx;

      item.innerHTML = `
        <div class="vid-item-thumb">
          <div class="vid-item-thumb-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
          </div>
          <div class="vid-item-play-overlay">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </div>
        </div>
        <div class="vid-item-info">
          <div class="vid-item-name" title="${v.name}">${v.name}</div>
          <div class="vid-item-meta">${fmtSize(v.size)} · ${v.duration ? fmtTime(v.duration) : '—'}</div>
        </div>
        <button class="vid-item-del" data-idx="${realIdx}" title="Eliminar">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
        </button>`;

      item.addEventListener('click', (e) => {
        if (e.target.closest('.vid-item-del')) return;
        loadVideo(realIdx);
      });

      item.querySelector('.vid-item-del').addEventListener('click', (e) => {
        e.stopPropagation();
        removeVideo(realIdx);
      });

      listScroll.appendChild(item);
    });
  }

  /* ── Cargar video en reproductor ── */
  function loadVideo(idx) {
    if (idx < 0 || idx >= videos.length) return;
    activeIdx = idx;
    const v = videos[idx];

    if (playerEmpty) playerEmpty.style.display = 'none';
    if (videoEl) {
      videoEl.style.display = 'block';
      videoEl.src = v.url;
      videoEl.load();
    }

    if (infoName) infoName.textContent = v.name;
    if (infoMeta) infoMeta.textContent = fmtSize(v.size) + (v.duration ? ' · ' + fmtTime(v.duration) : '');

    if (dlBtn) {
      dlBtn.onclick = () => {
        const a = document.createElement('a');
        a.href = v.url;
        a.download = v.name;
        a.click();
      };
    }

    resetProgress();
    renderList(searchInput ? searchInput.value : '');
  }

  /* ── Eliminar video ── */
  function removeVideo(idx) {
    const v = videos[idx];
    totalBytes -= v.size;
    totalSecs  -= (v.duration || 0);
    URL.revokeObjectURL(v.url);
    videos.splice(idx, 1);

    if (activeIdx === idx) {
      stopPlayer();
      if (videos.length > 0) {
        loadVideo(Math.min(idx, videos.length - 1));
      } else {
        activeIdx = null;
        if (playerEmpty) playerEmpty.style.display = 'flex';
        if (videoEl) { videoEl.style.display = 'none'; videoEl.src = ''; }
        if (infoName) infoName.textContent = 'Sin video seleccionado';
        if (infoMeta) infoMeta.textContent = '';
        resetProgress();
      }
    } else if (activeIdx !== null && activeIdx > idx) {
      activeIdx--;
    }

    updateStats();
    renderList(searchInput ? searchInput.value : '');
  }

  /* ── Agregar videos ── */
  function addFiles(fileList) {
    const newFiles = Array.from(fileList).filter(f => f.type.startsWith('video/'));
    if (!newFiles.length) return;

    if (uploading) uploading.classList.add('active');
    if (uploadText) uploadText.textContent = `Cargando ${newFiles.length} video(s)...`;

    let loaded = 0;
    newFiles.forEach(file => {
      const url = URL.createObjectURL(file);
      const tmp = document.createElement('video');
      tmp.src = url;
      tmp.onloadedmetadata = () => {
        const dur = tmp.duration || 0;
        videos.push({ name: file.name, size: file.size, url, duration: dur, file });
        totalBytes += file.size;
        totalSecs  += dur;
        loaded++;
        if (loaded === newFiles.length) {
          if (uploading) uploading.classList.remove('active');
          updateStats();
          renderList(searchInput ? searchInput.value : '');
          if (activeIdx === null) loadVideo(0);
        }
      };
      tmp.onerror = () => {
        loaded++;
        if (loaded === newFiles.length) {
          if (uploading) uploading.classList.remove('active');
          updateStats();
          renderList(searchInput ? searchInput.value : '');
        }
      };
    });
  }

  /* ── Controles de reproducción ── */
  function stopPlayer() {
    if (!videoEl) return;
    videoEl.pause();
    videoEl.currentTime = 0;
    if (playBtn) playBtn.innerHTML = PLAY_SVG;
    resetProgress();
  }

  function resetProgress() {
    if (progFill)  progFill.style.width = '0%';
    if (progThumb) progThumb.style.left = '0%';
    if (timeEl)    timeEl.textContent   = '0:00 / 0:00';
  }

  function updateProgress() {
    if (!videoEl || !videoEl.duration) return;
    const pct = (videoEl.currentTime / videoEl.duration) * 100;
    if (progFill)  progFill.style.width = pct + '%';
    if (progThumb) progThumb.style.left = pct + '%';
    if (timeEl)    timeEl.textContent   = fmtTime(videoEl.currentTime) + ' / ' + fmtTime(videoEl.duration);

    /* Actualizar duración en datos si no estaba */
    if (activeIdx !== null && videos[activeIdx] && !videos[activeIdx].duration) {
      videos[activeIdx].duration = videoEl.duration;
      totalSecs += videoEl.duration;
      updateStats();
      renderList(searchInput ? searchInput.value : '');
    }
  }

  /* Eventos del video element */
  if (videoEl) {
    videoEl.addEventListener('timeupdate', updateProgress);

    videoEl.addEventListener('play', () => {
      if (playBtn) playBtn.innerHTML = PAUSE_SVG;
    });

    videoEl.addEventListener('pause', () => {
      if (playBtn) playBtn.innerHTML = PLAY_SVG;
    });

    videoEl.addEventListener('ended', () => {
      if (playBtn) playBtn.innerHTML = PLAY_SVG;
      /* Autoplay siguiente */
      if (activeIdx !== null && activeIdx < videos.length - 1) {
        loadVideo(activeIdx + 1);
        videoEl.play();
      }
    });
  }

  /* Botón play/pause */
  if (playBtn) {
    playBtn.addEventListener('click', () => {
      if (!videoEl || !videoEl.src) return;
      videoEl.paused ? videoEl.play() : videoEl.pause();
    });
  }

  /* Retroceso / avance 10s */
  if (rewindBtn) rewindBtn.addEventListener('click', () => { if (videoEl) videoEl.currentTime = Math.max(0, videoEl.currentTime - 10); });
  if (fwdBtn)    fwdBtn.addEventListener('click',    () => { if (videoEl) videoEl.currentTime = Math.min(videoEl.duration || 0, videoEl.currentTime + 10); });

  /* Barra de progreso — clic */
  if (progWrap) {
    progWrap.addEventListener('click', (e) => {
      if (!videoEl || !videoEl.duration) return;
      const rect = progWrap.getBoundingClientRect();
      const pct  = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
      videoEl.currentTime = pct * videoEl.duration;
    });

    /* Drag en progreso */
    let dragging = false;
    progWrap.addEventListener('mousedown', () => dragging = true);
    document.addEventListener('mouseup',   () => dragging = false);
    document.addEventListener('mousemove', (e) => {
      if (!dragging || !videoEl || !videoEl.duration) return;
      const rect = progWrap.getBoundingClientRect();
      const pct  = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
      videoEl.currentTime = pct * videoEl.duration;
    });
  }

  /* Volumen */
  if (volSlider) {
    volSlider.addEventListener('input', () => {
      if (videoEl) videoEl.volume = volSlider.value / 100;
      if (muteBtn) muteBtn.innerHTML = +volSlider.value === 0 ? MUTE_SVG : VOL_SVG;
    });
  }

  if (muteBtn) {
    muteBtn.innerHTML = VOL_SVG;
    muteBtn.addEventListener('click', () => {
      if (!videoEl) return;
      videoEl.muted = !videoEl.muted;
      muteBtn.innerHTML = videoEl.muted ? MUTE_SVG : VOL_SVG;
      if (volSlider) volSlider.value = videoEl.muted ? 0 : (videoEl.volume * 100);
    });
  }

  /* Velocidad */
  if (speedBtn) {
    speedBtn.addEventListener('click', () => {
      speedIdx = (speedIdx + 1) % SPEEDS.length;
      speedBtn.textContent = SPEEDS[speedIdx];
      if (videoEl) videoEl.playbackRate = parseFloat(SPEEDS[speedIdx]);
    });
  }

  /* Pantalla completa */
  if (fsBtn) {
    fsBtn.addEventListener('click', () => {
      const wrap = document.getElementById('vidPlayerScreen');
      if (!document.fullscreenElement) {
        wrap?.requestFullscreen().catch(() => {});
      } else {
        document.exitFullscreen();
      }
    });
  }

  /* ── Dropzone ── */
  if (dropzone) {
    dropzone.addEventListener('click', () => fileInput?.click());
    ['dragenter','dragover','dragleave','drop'].forEach(evt => {
      dropzone.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); });
    });
    ['dragenter','dragover'].forEach(evt => {
      dropzone.addEventListener(evt, () => dropzone.classList.add('dragover'));
    });
    ['dragleave','drop'].forEach(evt => {
      dropzone.addEventListener(evt, () => dropzone.classList.remove('dragover'));
    });
    dropzone.addEventListener('drop', e => addFiles(e.dataTransfer.files));
  }

  if (fileInput) {
    fileInput.addEventListener('change', () => { addFiles(fileInput.files); fileInput.value = ''; });
  }

  /* ── Búsqueda ── */
  if (searchInput) {
    searchInput.addEventListener('input', () => renderList(searchInput.value));
  }

  /* ── Eliminar todos ── */
  if (clearAllBtn) {
    clearAllBtn.addEventListener('click', () => {
      if (!videos.length) return;
      if (!confirm('¿Eliminar todos los videos de la lista?')) return;
      videos.forEach(v => URL.revokeObjectURL(v.url));
      videos = []; activeIdx = null; totalBytes = 0; totalSecs = 0;
      stopPlayer();
      if (playerEmpty) playerEmpty.style.display = 'flex';
      if (videoEl) { videoEl.style.display = 'none'; videoEl.src = ''; }
      if (infoName) infoName.textContent = 'Sin video seleccionado';
      if (infoMeta) infoMeta.textContent = '';
      updateStats();
      renderList('');
    });
  }

  /* ── Botón eliminar del info bar ── */
  if (delBtn) {
    delBtn.addEventListener('click', () => {
      if (activeIdx === null) return;
      removeVideo(activeIdx);
    });
  }

  /* ── Teclado ── */
  document.addEventListener('keydown', e => {
    if (['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) return;
    if (e.code === 'Space') { e.preventDefault(); playBtn?.click(); }
    if (e.code === 'ArrowLeft')  rewindBtn?.click();
    if (e.code === 'ArrowRight') fwdBtn?.click();
    if (e.code === 'KeyM')       muteBtn?.click();
    if (e.code === 'ArrowUp' && activeIdx !== null && activeIdx > 0)
      loadVideo(activeIdx - 1);
    if (e.code === 'ArrowDown' && activeIdx !== null && activeIdx < videos.length - 1)
      loadVideo(activeIdx + 1);
  });

  /* ── Init ── */
  updateStats();
  renderList('');

})();
</script>
