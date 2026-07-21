  <div class="vi-wrap">

    
    <div>

      
      <div class="vi-viewer-box" id="viViewer">
        <div class="vi-img-bg" id="viBg"></div>

        
        <div class="vi-counter-badge" id="viCounter">Imagen 4 de 6</div>

        
        <div class="vi-thumb-preview">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
        </div>

        
        <img src="" alt="Imagen de prueba" class="vi-main-image" id="viMainImage">
        <div class="vi-img-placeholder">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
        </div>

        <canvas id="viAnnotationCanvas" class="vi-annotation-canvas"></canvas>
        <canvas id="viMeasureCanvas" class="vi-measure-canvas"></canvas>

        
        <div class="vi-zoom-ctrl">
          <button class="vi-zoom-btn" id="viZoomPlus">+</button>
          <div class="vi-zoom-pct" id="viZoomPct">148%</div>
          <button class="vi-zoom-btn" id="viZoomMinus">−</button>
          <button class="vi-zoom-btn" id="viZoomFit" title="Ajustar">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          </button>
        </div>

        
        <div class="vi-meta-overlay" id="viMeta">
          <div class="vi-meta-res">1920 x 1080</div>
          <div class="vi-meta-ts" id="viMetaTs">00:08:47</div>
        </div>
      </div>

      
      <div class="vi-toolbar">
        <button class="vi-tool-btn" id="viToolZoom">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
          Zoom
        </button>
        <button class="vi-tool-btn" id="viToolAnotar">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/></svg>
          Anotar
        </button>
        <button class="vi-tool-btn on" id="viToolIA">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
          IA Hallazgos
        </button>
        <button class="vi-tool-btn" id="viToolFiltros">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
          Filtros
        </button>
        <button class="vi-tool-btn" id="viToolPrint">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
          Imprimir
        </button>
        <button class="vi-tool-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          Pantalla completa
        </button>
      </div>

      
      <div class="vi-filters-panel" id="viFiltersPanel">
        <div class="vi-filters-title">Ajustes de imagen</div>

        
        <div class="vi-canvas-wrap">
          <canvas id="viFilterCanvas"></canvas>
          <div class="vi-canvas-placeholder" id="viCanvasPlaceholder">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
            <span>La imagen aparecerá aquí<br>cuando se cargue una real</span>
          </div>
        </div>

        
        <div class="vi-filter-label">Filtro de color</div>
        <div class="vi-filter-btns">
          <button class="vi-filter-btn active" data-filter="original">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
            Original
          </button>
          <button class="vi-filter-btn" data-filter="grayscale">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/></svg>
            Escala de grises
          </button>
          <button class="vi-filter-btn" data-filter="inverted">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2v20"/></svg>
            Invertido
          </button>
          <button class="vi-filter-btn" data-filter="sepia">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
            Sepia
          </button>
        </div>

        
        <div class="vi-filter-label">Ajustes</div>
        <div class="vi-slider-group">
          <div class="vi-slider-row">
            <span class="vi-slider-name">Brillo</span>
            <span class="vi-slider-val" id="slBrilloVal">100%</span>
          </div>
          <input type="range" class="vi-slider" id="slBrillo" min="0" max="200" value="100">
        </div>
        <div class="vi-slider-group">
          <div class="vi-slider-row">
            <span class="vi-slider-name">Contraste</span>
            <span class="vi-slider-val" id="slContrasteVal">100%</span>
          </div>
          <input type="range" class="vi-slider" id="slContraste" min="0" max="200" value="100">
        </div>
        <div class="vi-slider-group">
          <div class="vi-slider-row">
            <span class="vi-slider-name">Saturación</span>
            <span class="vi-slider-val" id="slSaturacionVal">100%</span>
          </div>
          <input type="range" class="vi-slider" id="slSaturacion" min="0" max="200" value="100">
        </div>

        
        <div class="vi-filter-actions">
          <button class="vi-filter-apply" id="viFilterApply">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            Aplicar filtros
          </button>
          <button class="vi-filter-reset" id="viFilterReset">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4"/></svg>
            Restablecer
          </button>
        </div>
      </div>

      
      <div>
        <div class="vi-strip-head">
          <span class="vi-strip-title">Imágenes del estudio (<?php echo e(count($caps)); ?>)</span>
          <div class="vi-strip-nav">
            <button class="vi-strip-arrow" id="viPrev">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="vi-strip-arrow" id="viNext">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>
        </div>
        <div class="vi-strip" id="viStrip">
          <?php $__currentLoopData = $caps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="vi-strip-item <?php echo e($i === $current ? 'sel' : ''); ?>"
               data-idx="<?php echo e($i); ?>"
               data-ts="<?php echo e($c['ts']); ?>"
               data-bg="<?php echo e($c['bg']); ?>">
            <div class="vi-strip-thumb" style="background:<?php echo e($c['bg']); ?>;position:relative;overflow:hidden">
              <?php if(!empty($c['src'])): ?>
              <img src="<?php echo e($c['src']); ?>" alt="Captura <?php echo e($c['n']); ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
              <?php else: ?>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <?php endif; ?>
              <span class="vi-strip-num"><?php echo e($c['n']); ?></span>
              <span class="vi-strip-del" title="Eliminar">×</span>
            </div>
            <div class="vi-strip-ts"><?php echo e($c['ts']); ?></div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>

    </div>

    
    <div class="vi-side">
      <?php echo $__env->make('galeria.verimagen._anotaciones_toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

      
      <div class="vi-card">
        <div class="vi-card-head">
          <span class="vi-card-title">Información de la imagen</span>
        </div>
        <div class="vi-info-table">
          <span class="vi-it-lbl">ID de imagen</span>   <span class="vi-it-val">IMG-0004</span>
          <span class="vi-it-lbl">Fecha de captura</span><span class="vi-it-val">15/07/2025 · 10:30 AM</span>
          <span class="vi-it-lbl">Tipo de estudio</span> <span class="vi-it-val">Endoscopia Digestiva Alta</span>
          <span class="vi-it-lbl">Equipo</span>          <span class="vi-it-val">Pentax EPK-i7010</span>
          <span class="vi-it-lbl">Resolución</span>      <span class="vi-it-val">1920 x 1080</span>
          <span class="vi-it-lbl">Duración del video</span><span class="vi-it-val">00:15:42</span>
          <span class="vi-it-lbl">Fotograma</span>       <span class="vi-it-val" id="viInfoTs">00:08:47</span>
        </div>
      </div>

      
      <div class="vi-card">
        <div class="vi-card-head">
          <div style="display:flex;align-items:center">
            <span class="vi-card-title">IA Hallazgos</span>
            <span class="vi-ia-badge">Beta</span>
          </div>
          <svg class="vi-edit-ic" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="vi-ia-row">
          <span class="vi-ia-dot"></span>
          <span class="vi-ia-name">Gastritis antral leve</span>
          <span class="vi-ia-conf">Confianza: 92%</span>
        </div>
        <div class="vi-ia-row">
          <span class="vi-ia-dot" style="background:var(--orange)"></span>
          <span class="vi-ia-name">Eritema leve</span>
          <span class="vi-ia-conf">Confianza: 88%</span>
        </div>
        <div class="vi-ia-row">
          <span class="vi-ia-dot"></span>
          <span class="vi-ia-name">Sin úlceras visibles</span>
          <span class="vi-ia-conf">Confianza: 95%</span>
        </div>
        <button class="vi-ia-analyze">Ver análisis detallado</button>
      </div>

    </div>
  </div>
</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\galeria\verimagen\_imagen.blade.php ENDPATH**/ ?>