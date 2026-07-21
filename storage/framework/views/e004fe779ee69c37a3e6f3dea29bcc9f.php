<?php $__env->startSection('title', 'Videos'); ?>
<?php $__env->startSection('active', 'nuevo-estudio'); ?>
<?php $__env->startSection('header-title', 'Videos'); ?>
<?php $__env->startSection('header-sub'); ?>
  Gestión y reproducción de videos del estudio
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<?php echo $__env->make('estudios.videos.videos-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="vid-shell rise d1">

  
  <div class="vid-toolbar">
    <a class="vid-back" href="<?php echo e(route('nuevo-estudio')); ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
    <div class="vid-title">Videos del Estudio</div>
  </div>

  
  <div class="vid-uploading" id="vidUploading">
    <div class="vid-upload-spinner"></div>
    <span id="vidUploadText">Cargando videos...</span>
  </div>

  
  <div class="vid-dropzone" id="vidDropzone">
    <input type="file" id="vidFileInput" multiple accept="video/*">
    <div class="vid-dropzone-icon">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
    </div>
    <div class="vid-dropzone-title">Arrastra videos aquí</div>
    <div class="vid-dropzone-desc">O haz clic para seleccionar archivos de video</div>
    <div class="vid-dropzone-hint">MP4, MOV, AVI, MKV, WEBM · Máximo 500 MB por archivo</div>
  </div>

  
  <div class="vid-main rise d2">

    
    <div class="vid-player-wrap">

      
      <div class="vid-player-screen" id="vidPlayerScreen">
        <div class="vid-player-empty" id="vidPlayerEmpty">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
          Selecciona un video de la lista
        </div>
        <video id="vidVideo" style="display:none" preload="metadata"></video>
      </div>

      
      <div class="vid-controls">

        
        <div class="vid-progress-wrap" id="vidProgWrap">
          <div class="vid-progress-fill" id="vidProgFill"></div>
          <div class="vid-progress-thumb" id="vidProgThumb"></div>
        </div>

        
        <div class="vid-ctrl-row">
          <button class="vid-ctrl-btn" id="vidRewindBtn" title="Retroceder 10s">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.27"/><text x="8" y="16" font-size="6" fill="currentColor" stroke="none" font-family="sans-serif">10</text></svg>
          </button>

          <button class="vid-ctrl-btn vid-ctrl-btn--play" id="vidPlayBtn" title="Reproducir / Pausar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor" stroke="none"/></svg>
          </button>

          <button class="vid-ctrl-btn" id="vidFwdBtn" title="Avanzar 10s">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-3.27"/></svg>
          </button>

          <span class="vid-time" id="vidTime">0:00 / 0:00</span>

          <div class="vid-vol-wrap">
            <button class="vid-ctrl-btn" id="vidMuteBtn" title="Silenciar"></button>
            <input type="range" class="vid-vol-slider" id="vidVolSlider" min="0" max="100" value="80">
          </div>

          <button class="vid-speed-btn" id="vidSpeedBtn" title="Velocidad">1.0x</button>

          <button class="vid-ctrl-btn" id="vidFsBtn" title="Pantalla completa">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          </button>
        </div>

      </div>

      
      <div class="vid-info-bar">
        <div>
          <div class="vid-info-name" id="vidInfoName">Sin video seleccionado</div>
          <div class="vid-info-meta" id="vidInfoMeta"></div>
        </div>
        <div class="vid-info-actions">
          <button class="vid-act-btn vid-act-btn--secondary" id="vidDlBtn" title="Descargar">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Descargar
          </button>
          <button class="vid-act-btn vid-act-btn--danger" id="vidDelBtn" title="Eliminar">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
            Eliminar
          </button>
        </div>
      </div>

    </div>

    
    <div class="vid-list-panel">

      <div class="vid-list-header">
        <span class="vid-list-title">Videos</span>
        <span class="vid-list-count" id="vidListCount">0</span>
      </div>

      <div class="vid-search-wrap">
        <input class="vid-search" id="vidSearch" type="text" placeholder="Buscar video..." autocomplete="off">
      </div>

      <div class="vid-list-scroll" id="vidListScroll">
        <div class="vid-list-empty" id="vidListEmpty">Aún no hay videos. Arrastra o sube archivos.</div>
      </div>

      <div class="vid-list-footer">
        <button class="vid-act-btn vid-act-btn--primary" style="flex:1;justify-content:center" onclick="document.getElementById('vidFileInput').click()">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Agregar
        </button>
        <button class="vid-act-btn vid-act-btn--secondary" id="vidClearAll" title="Limpiar lista">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
          Limpiar
        </button>
      </div>

    </div>

  </div>

  
  <div class="vid-stats rise d3">
    <div class="vid-stat-card">
      <div class="vid-stat-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
      </div>
      <div>
        <div class="vid-stat-label">Total de videos</div>
        <div class="vid-stat-value" id="statTotal">0</div>
      </div>
    </div>
    <div class="vid-stat-card">
      <div class="vid-stat-icon green">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
      </div>
      <div>
        <div class="vid-stat-label">Espacio total</div>
        <div class="vid-stat-value" id="statSize">0 MB</div>
      </div>
    </div>
    <div class="vid-stat-card">
      <div class="vid-stat-icon orange">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div>
        <div class="vid-stat-label">Duración total</div>
        <div class="vid-stat-value" id="statDur">0s</div>
      </div>
    </div>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo $__env->make('estudios.videos.videos-js', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\estudios\videos\index.blade.php ENDPATH**/ ?>