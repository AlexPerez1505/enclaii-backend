
<div class="vi-dl-overlay" id="viDlOverlay">
  <div class="vi-dl-modal">
    <div class="vi-dl-header">
      <div>
        <div class="vi-dl-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Descargar imagen
        </div>
        <div class="vi-dl-sub">Selecciona el formato y las opciones de descarga</div>
      </div>
      <button class="vi-dl-close" id="viDlClose">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="vi-dl-body">

      
      <div class="vi-dl-preview">
        <div class="vi-dl-preview-lbl">Vista previa</div>
        <div class="vi-dl-thumb">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.2)" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          <div class="vi-dl-thumb-badge">IMG-0004</div>
          <div class="vi-dl-watermark" id="viWatermark">
            <div class="vi-dl-watermark-logo">ENCLA<span>II</span></div>
            <div class="vi-dl-watermark-dot"></div>
            <div class="vi-dl-watermark-sub">Endoscopia · IA</div>
          </div>
        </div>
        <div class="vi-dl-thumb-meta">
          <span>1920 x 1080</span> · <span id="viDlFmt">JPG</span> · <span>2.4 MB</span>
        </div>
      </div>

      
      <div class="vi-dl-opts">
        <div class="vi-dl-opts-lbl">Formato de archivo</div>
        <div class="vi-fmt-list">
          <div class="vi-fmt-item sel" data-fmt="JPG">
            <div class="vi-fmt-left">
              <div class="vi-fmt-ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></div>
              <div>
                <div class="vi-fmt-name">JPG</div>
                <div class="vi-fmt-desc">Imagen de alta calidad</div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
              <span class="vi-fmt-badge">Recomendado</span>
              <div class="vi-fmt-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
            </div>
          </div>
          <div class="vi-fmt-item" data-fmt="PNG">
            <div class="vi-fmt-left">
              <div class="vi-fmt-ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></div>
              <div>
                <div class="vi-fmt-name">PNG</div>
                <div class="vi-fmt-desc">Máxima calidad (sin compresión)</div>
              </div>
            </div>
            <div class="vi-fmt-check"></div>
          </div>
          <div class="vi-fmt-item" data-fmt="DICOM">
            <div class="vi-fmt-left">
              <div class="vi-fmt-ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
              <div>
                <div class="vi-fmt-name">DICOM</div>
                <div class="vi-fmt-desc">Formato médico (.dcm)</div>
              </div>
            </div>
            <div class="vi-fmt-check"></div>
          </div>
          <div class="vi-fmt-item" data-fmt="PDF">
            <div class="vi-fmt-left">
              <div class="vi-fmt-ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></div>
              <div>
                <div class="vi-fmt-name">PDF</div>
                <div class="vi-fmt-desc">Incluir en reporte</div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
              <span class="vi-fmt-tag">(1 imagen)</span>
              <div class="vi-fmt-check"></div>
            </div>
          </div>
        </div>

        <div class="vi-qual-lbl">Calidad de imagen</div>
        <select class="vi-qual-select" id="viDlQual">
          <option value="alta">Alta (1920 x 1080)</option>
          <option value="media">Media (1280 x 720)</option>
          <option value="baja">Baja (640 x 360)</option>
        </select>

        <div class="vi-inc-lbl">Qué deseas incluir</div>
        <div class="vi-inc-row checked" id="viIncEstudio">
          <div class="vi-inc-cb"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <span class="vi-inc-label">Información del estudio</span>
        </div>
        <div class="vi-inc-row checked" id="viIncPaciente">
          <div class="vi-inc-cb"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <span class="vi-inc-label">Datos del paciente</span>
        </div>
        <div class="vi-inc-row" id="viIncMarca">
          <div class="vi-inc-cb"></div>
          <span class="vi-inc-label">Marca de agua Enclaii</span>
        </div>
      </div>
    </div>

    <div class="vi-dl-footer">
      <div class="vi-dl-footer-note">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        La imagen se descargará de forma segura
      </div>
      <div class="vi-dl-footer-btns">
        <button class="vi-dl-cancel" id="viDlCancel">Cancelar</button>
        <button class="vi-dl-confirm" id="viDlConfirm">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Descargar imagen
        </button>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\galeria\verimagen\_modal_descarga.blade.php ENDPATH**/ ?>