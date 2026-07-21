<div
  class="int-sig-overlay"
  id="intSignatureModal"
  aria-hidden="true"
  data-show-url="<?php echo e(route('configuracion.signature.show')); ?>"
  data-store-url="<?php echo e(route('configuracion.signature.store')); ?>"
  data-delete-url="<?php echo e(route('configuracion.signature.destroy')); ?>"
  data-has-signature="<?php echo e(auth()->user()->signature_path ? '1' : '0'); ?>"
>
  <div class="int-sig-modal" role="dialog" aria-modal="true" aria-labelledby="intSignatureTitle">
    <div class="int-sig-head">
      <div>
        <div class="int-sig-title" id="intSignatureTitle">Firma digital</div>
        <div class="int-sig-sub">Dibuja tu firma o sube una imagen para utilizarla en tus documentos.</div>
      </div>
      <button type="button" class="int-sig-close" id="intSignatureClose" aria-label="Cerrar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="int-sig-body">
      <div class="int-sig-current">
        <div class="int-sig-current-label">Vista previa de la firma guardada</div>
        <div class="int-sig-preview">
          <?php if(auth()->user()->signature_path): ?>
            <img
              id="intSignatureCurrent"
              src="<?php echo e(route('configuracion.signature.show', ['v' => auth()->user()->signature_updated_at?->timestamp])); ?>"
              alt="Firma digital de <?php echo e(auth()->user()->name); ?>"
            >
          <?php else: ?>
            <div class="int-sig-empty" id="intSignatureEmpty">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M3 17c3-1 5-5 5-9 0 4 2 8 5 9-3 0-7 0-10 0z"/><path d="M14 11l6-6a1.5 1.5 0 0 0-2-2l-6 6"/></svg>
              Aún no has registrado una firma.
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="int-sig-editor" id="intSignatureEditor">
        <div class="int-sig-tabs">
          <button type="button" class="int-sig-tab active" data-signature-tab="draw">Dibujar firma</button>
          <button type="button" class="int-sig-tab" data-signature-tab="upload">Subir imagen</button>
        </div>

        <div class="int-sig-panel active" data-signature-panel="draw">
          <div class="int-sig-canvas-wrap">
            <canvas id="intSignatureCanvas" width="1200" height="440"></canvas>
            <div class="int-sig-canvas-hint" id="intSignatureHint">Firma dentro de este espacio</div>
          </div>
          <div class="int-sig-tools">
            <button type="button" class="int-sig-clear" id="intSignatureClear">Limpiar dibujo</button>
          </div>
        </div>

        <div class="int-sig-panel" data-signature-panel="upload">
          <label class="int-sig-upload" for="intSignatureFile">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <strong>Seleccionar imagen de firma</strong>
            <span>PNG, JPG o WEBP · máximo 2 MB</span>
            <img class="int-sig-upload-preview" id="intSignatureUploadPreview" alt="Vista previa de la nueva firma">
          </label>
          <input type="file" id="intSignatureFile" accept="image/png,image/jpeg,image/webp" hidden>
        </div>

        <div class="int-sig-note">
          Para obtener mejores resultados utiliza fondo blanco o transparente. La firma se almacena en un espacio privado.
        </div>
      </div>
    </div>

    <div class="int-sig-footer">
      <button type="button" class="int-sig-btn delete" id="intSignatureDelete" <?php if(! auth()->user()->signature_path): echo 'disabled'; endif; ?>>Eliminar firma</button>
      <div class="int-sig-footer-main">
        <button type="button" class="int-sig-btn cancel" id="intSignatureCancel">Cerrar</button>
        <button type="button" class="int-sig-btn primary" id="intSignatureStartEdit">
          <?php echo e(auth()->user()->signature_path ? 'Actualizar firma' : 'Crear firma'); ?>

        </button>
        <button type="button" class="int-sig-btn primary" id="intSignatureSave" style="display:none">Guardar firma</button>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\laragon\www\endocare\resources\views/configuracion/sections/integraciones/_modal-firma.blade.php ENDPATH**/ ?>