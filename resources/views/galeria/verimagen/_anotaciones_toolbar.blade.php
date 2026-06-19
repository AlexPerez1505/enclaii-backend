<div class="vi-annotation-toolbar" id="viAnnotationToolbar" aria-hidden="true">
  <div class="vi-side-tool-title">Herramientas de dibujo</div>

  <div class="vi-annotation-control">
    <label for="viAnnoColor">Color</label>
    <input type="color" id="viAnnoColor" value="#65a33d">
  </div>

  <div class="vi-annotation-control wide">
    <label for="viAnnoSize">Tamaño</label>
    <input type="range" id="viAnnoSize" min="1" max="40" value="12">
    <span id="viAnnoSizeVal">12</span>
  </div>

  <div class="vi-annotation-control wide">
    <label for="viAnnoOpacity">Opacidad</label>
    <input type="range" id="viAnnoOpacity" min="0.1" max="1" step="0.1" value="1">
  </div>

  <div class="vi-annotation-actions">
    <button type="button" class="vi-annotation-btn active" id="viAnnoBrush">Pincel</button>
    <button type="button" class="vi-annotation-btn" id="viAnnoEraser">Borrador</button>
    <button type="button" class="vi-measure-btn icon-only" id="viMeasureLine" title="Línea" aria-label="Línea">
      <img src="{{ asset('images/icono-linea.png') }}" alt="" class="vi-measure-icon">
    </button>
    <button type="button" class="vi-measure-btn icon-only" id="viMeasureCircle" title="Círculo" aria-label="Círculo">
      <img src="{{ asset('images/icono-circulo.png') }}" alt="" class="vi-measure-icon">
    </button>
    <button type="button" class="vi-measure-btn icon-only" id="viMeasureArrow" title="Flecha" aria-label="Flecha">
      <img src="{{ asset('images/icono-flecha.png') }}" alt="" class="vi-measure-icon">
    </button>
    <button type="button" class="vi-annotation-btn" id="viAnnoUndo">Deshacer</button>
    <button type="button" class="vi-annotation-btn" id="viAnnoRedo">Rehacer</button>
    <button type="button" class="vi-annotation-btn" id="viAnnoSave">Guardar</button>
    <button type="button" class="vi-annotation-btn danger" id="viAnnoClear">Limpiar</button>
  </div>
</div>
