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
      <svg class="vi-measure-fa-icon" viewBox="0 0 512 512" aria-hidden="true" focusable="false">
        <path fill="currentColor" d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"/>
      </svg>
    </button>
    <button type="button" class="vi-measure-btn icon-only" id="viMeasureArrow" title="Flecha" aria-label="Flecha">
      <svg class="vi-measure-fa-icon" viewBox="0 0 15 15" aria-hidden="true" focusable="false"><path fill="currentColor" d="M13.707 7.293a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L10.586 9H2a1 1 0 1 1 0-2h8.586L8.293 4.707a1 1 0 1 1 1.414-1.414l4 4z"/></svg>
    </button>
    <button type="button" class="vi-measure-btn icon-only" id="viMeasureLine" title="Línea" aria-label="Línea">
      <svg class="vi-measure-fa-icon" viewBox="0 0 16 16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M0 7h16v2H0z"/></svg>
    </button>
    <button type="button" class="vi-measure-btn" id="viMeasureUndo">Deshacer</button>
    <button type="button" class="vi-measure-btn" id="viMeasureRedo">Rehacer</button>
    <button type="button" class="vi-measure-btn" id="viMeasureSave">Guardar</button>
    <button type="button" class="vi-measure-btn danger" id="viMeasureClear">Limpiar</button>
  </div>
</div>
