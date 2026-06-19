<div class="vi-measure-toolbar" id="viMeasureToolbar" aria-hidden="true">
  <div class="vi-measure-control">
    <label for="viMeasureColor">Color</label>
    <input type="color" id="viMeasureColor" value="#65a33d">
  </div>

  <div class="vi-measure-control wide">
    <label for="viMeasureWidth">Grosor</label>
    <input type="range" id="viMeasureWidth" min="1" max="16" value="5">
    <span id="viMeasureWidthVal">5</span>
  </div>

  <div class="vi-measure-actions">
    <button type="button" class="vi-measure-btn icon-only active" id="viMeasureCircle" title="Círculo" aria-label="Círculo">
      <img src="{{ asset('images/icono-circulo.png') }}" alt="" class="vi-measure-icon">
    </button>
    <button type="button" class="vi-measure-btn icon-only" id="viMeasureArrow" title="Flecha" aria-label="Flecha">
      <img src="{{ asset('images/icono-flecha.png') }}" alt="" class="vi-measure-icon">
    </button>
    <button type="button" class="vi-measure-btn icon-only" id="viMeasureLine" title="Línea" aria-label="Línea">
      <img src="{{ asset('images/icono-linea.png') }}" alt="" class="vi-measure-icon">
    </button>
    <button type="button" class="vi-measure-btn" id="viMeasureUndo">Deshacer</button>
    <button type="button" class="vi-measure-btn" id="viMeasureRedo">Rehacer</button>
    <button type="button" class="vi-measure-btn" id="viMeasureSave">Guardar</button>
    <button type="button" class="vi-measure-btn danger" id="viMeasureClear">Limpiar</button>
  </div>
</div>
