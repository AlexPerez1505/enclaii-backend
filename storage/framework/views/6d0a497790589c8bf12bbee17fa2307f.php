<script>
(function(){
  const video = document.getElementById('vvVideoEl');
  const player = document.getElementById('vvPlayer');
  const center = document.getElementById('vvCenter');
  const centerLabel = document.getElementById('vvCenterLabel');
  const playBig = document.getElementById('vvPlayBig');
  const playBtn = document.getElementById('vvPlayBtn');
  const startBtn = document.getElementById('vvStartBtn');
  const rewindBtn = document.getElementById('vvRewindBtn');
  const forwardBtn = document.getElementById('vvForwardBtn');
  const fullscreenBtn = document.getElementById('vvFullscreenBtn');
  const progressWrap = document.getElementById('vvProgWrap');
  const progressFill = document.getElementById('vvProgFill');
  const progressThumb = document.getElementById('vvProgThumb');
  const timeDisplay = document.getElementById('vvTime');
  const volBar = document.getElementById('vvVolBar');
  const volFill = document.getElementById('vvVolFill');
  const speedBtn = document.getElementById('vvSpeed');
  const exportBtn = document.getElementById('vvExportBtn');
  const savedConfig = <?php echo json_encode($editorConfig, 15, 512) ?>;
  const downloadUrl = <?php echo json_encode($videoUrl, 15, 512) ?>;
  const downloadName = <?php echo json_encode($downloadName, 15, 512) ?>;
  const speeds = [0.5, 0.75, 1, 1.25, 1.5, 2];
  let speedIndex = 2;
  let draggingProgress = false;

  function two(n){ return String(Math.floor(n)).padStart(2, '0'); }
  function formatTime(seconds){
    if(!Number.isFinite(seconds) || seconds < 0) return '00:00';
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = Math.floor(seconds % 60);
    return h > 0 ? `${two(h)}:${two(m)}:${two(s)}` : `${two(m)}:${two(s)}`;
  }

  function transformFromConfig(config){
    const zoom = Math.max(50, Math.min(250, Number(config.zoom || 100)));
    const rotate = Number(config.rotacion || 0);
    const sx = config.flip_h ? -1 : 1;
    const sy = config.flip_v ? -1 : 1;
    return `scale(${zoom / 100}) rotate(${rotate}deg) scaleX(${sx}) scaleY(${sy})`;
  }

  function applySavedConfig(){
    if(!video) return;
    const b = Number(savedConfig.brillo || 100);
    const c = Number(savedConfig.contraste || 100);
    const s = Number(savedConfig.saturacion || 100);
    video.style.filter = `brightness(${b}%) contrast(${c}%) saturate(${s}%)`;
    video.style.transform = transformFromConfig(savedConfig);
  }

  function syncIcons(){
    const playing = video && !video.paused;
    [playBig, playBtn].forEach(btn => {
      if(!btn) return;
      const playIcon = btn.querySelector('.play-icon');
      const pauseIcon = btn.querySelector('.pause-icon');
      if(playIcon) playIcon.style.display = playing ? 'none' : '';
      if(pauseIcon) pauseIcon.style.display = playing ? '' : 'none';
      btn.classList.toggle('playing', playing);
    });
    if(centerLabel) centerLabel.style.display = playing ? 'none' : '';
    if(center) center.style.pointerEvents = playing ? 'none' : '';
  }

  function updateProgress(){
    if(!video) return;
    const duration = video.duration || 0;
    const pct = duration ? Math.max(0, Math.min(100, (video.currentTime / duration) * 100)) : 0;
    progressFill.style.width = pct + '%';
    progressThumb.style.left = pct + '%';
    timeDisplay.textContent = `${formatTime(video.currentTime)} / ${formatTime(duration)}`;
    const dlDuration = document.getElementById('vvDlDuration');
    if(dlDuration) dlDuration.textContent = formatTime(duration);
  }

  function seekFromEvent(event){
    if(!video?.duration) return;
    const rect = progressWrap.getBoundingClientRect();
    const x = Math.max(0, Math.min(rect.width, event.clientX - rect.left));
    video.currentTime = (x / rect.width) * video.duration;
    updateProgress();
  }

  function setVolumeFromEvent(event){
    if(!video) return;
    const rect = volBar.getBoundingClientRect();
    const x = Math.max(0, Math.min(rect.width, event.clientX - rect.left));
    const volume = rect.width ? x / rect.width : 0;
    video.volume = volume;
    video.muted = volume === 0;
    volFill.style.width = Math.round(volume * 100) + '%';
  }

  function downloadOriginal(){
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = downloadName;
    document.body.appendChild(link);
    link.click();
    link.remove();
  }

  if(video){
    applySavedConfig();
    video.addEventListener('loadedmetadata', updateProgress);
    video.addEventListener('timeupdate', updateProgress);
    video.addEventListener('play', syncIcons);
    video.addEventListener('pause', syncIcons);
    video.addEventListener('ended', syncIcons);
    video.addEventListener('click', () => video.paused ? video.play() : video.pause());
  }

  function togglePlay(){
    if(!video) return;
    if(video.paused) video.play();
    else video.pause();
  }

  playBig?.addEventListener('click', togglePlay);
  playBtn?.addEventListener('click', togglePlay);
  startBtn?.addEventListener('click', () => { if(video){ video.currentTime = 0; updateProgress(); } });
  rewindBtn?.addEventListener('click', () => { if(video){ video.currentTime = Math.max(0, video.currentTime - 10); updateProgress(); } });
  forwardBtn?.addEventListener('click', () => { if(video){ video.currentTime = Math.min(video.duration || 0, video.currentTime + 10); updateProgress(); } });
  speedBtn?.addEventListener('click', function(){
    speedIndex = (speedIndex + 1) % speeds.length;
    const speed = speeds[speedIndex];
    if(video) video.playbackRate = speed;
    this.textContent = (speed === 1 ? '1.0' : String(speed)) + 'x';
  });
  fullscreenBtn?.addEventListener('click', () => {
    if(player?.requestFullscreen) player.requestFullscreen();
  });
  progressWrap?.addEventListener('mousedown', event => {
    draggingProgress = true;
    seekFromEvent(event);
  });
  document.addEventListener('mousemove', event => {
    if(draggingProgress) seekFromEvent(event);
  });
  document.addEventListener('mouseup', () => { draggingProgress = false; });
  volBar?.addEventListener('click', setVolumeFromEvent);
  exportBtn?.addEventListener('click', () => document.querySelector('.vv-btn.dl')?.click());

  /* Modal descarga video */
  const vvDlOv = document.getElementById('vvDlOverlay');
  function abrirVvDl(){ vvDlOv?.classList.add('open'); document.body.style.overflow='hidden'; }
  function cerrarVvDl(){ vvDlOv?.classList.remove('open'); document.body.style.overflow=''; }

  document.querySelector('.vv-btn.dl')?.addEventListener('click', abrirVvDl);
  document.getElementById('vvDlClose')?.addEventListener('click', cerrarVvDl);
  document.getElementById('vvDlCancel')?.addEventListener('click', cerrarVvDl);
  vvDlOv?.addEventListener('click', function(e){ if(e.target === this) cerrarVvDl(); });
  document.addEventListener('keydown', function(e){ if(e.key === 'Escape') cerrarVvDl(); });
  document.getElementById('vvDlConfirm')?.addEventListener('click', function(){
    downloadOriginal();
    cerrarVvDl();
  });

  document.querySelectorAll('.vv-cap-item').forEach(item => {
    item.addEventListener('mouseenter', function(){
      document.querySelectorAll('.vv-cap-item').forEach(i => i.classList.remove('sel'));
      this.classList.add('sel');
    });
  });

  syncIcons();
  updateProgress();
})();
</script>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\galeria\vervideo\_scripts.blade.php ENDPATH**/ ?>