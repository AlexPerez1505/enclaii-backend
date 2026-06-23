{{-- Panel lateral de Filtros + Importar fotos --}}
{{-- Se incluye dentro de galeria/index.blade.php con @include --}}

<div class="fil-overlay" id="filOverlay"></div>

<aside class="fil-panel" id="filPanel" aria-label="Panel de filtros">

  {{-- Cabecera --}}
  <div class="fil-head">
    <span class="fil-title">Filtros</span>
    <button class="fil-close" id="filClose" aria-label="Cerrar filtros">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>

  <div class="fil-body">

    {{-- Buscar paciente --}}
    <div class="fil-group">
      <label class="fil-label">Buscar</label>
      <div class="fil-select-wrap">
        <select class="fil-select" id="filPaciente">
          <option value="">Buscar Pacientes</option>
          <option value="Maria Gonzales">Maria Gonzales</option>
          <option value="Jorge Lopez">Jorge Lopez</option>
          <option value="Ana Ramirez">Ana Ramirez</option>
          <option value="Pedro Torres">Pedro Torres</option>
          <option value="Luis Mendoza">Luis Mendoza</option>
          <option value="Carla Ortiz">Carla Ortiz</option>
        </select>
        <svg class="fil-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    {{-- Tipo de estudio --}}
    <div class="fil-group">
      <label class="fil-label">Tipo de estudio</label>
      <div class="fil-select-wrap">
        <select class="fil-select" id="filEstudio">
          <option value="">Todos los estudios</option>
          <option value="EDG Diagnostico">EDG Diagnóstico</option>
          <option value="Colonoscopia">Colonoscopia</option>
          <option value="Gastroscopia">Gastroscopia</option>
          <option value="Biopsia">Biopsia</option>
        </select>
        <svg class="fil-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    {{-- Fecha del estudio --}}
    <div class="fil-group">
      <label class="fil-label">Fecha del estudio</label>
      <div class="fil-date-row">
        <div class="fil-date-wrap">
          <input class="fil-date" type="date" id="filDesde" title="Desde">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <span class="fil-date-sep">Hasta</span>
        <div class="fil-date-wrap">
          <input class="fil-date" type="date" id="filHasta" title="Hasta">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
      </div>
    </div>

    {{-- Tipo de archivo --}}
    <div class="fil-group">
      <label class="fil-label">Tipo de archivo</label>
      <div class="fil-checks">
        <label class="fil-check">
          <input type="checkbox" value="IMG" checked> Imágenes
        </label>
        <label class="fil-check">
          <input type="checkbox" value="VID" checked> Videos
        </label>
        <label class="fil-check">
          <input type="checkbox" value="US" checked> Ultrasonido
        </label>
      </div>
    </div>

    {{-- Estado del informe --}}
    <div class="fil-group">
      <label class="fil-label">Estado del informe</label>
      <div class="fil-select-wrap">
        <select class="fil-select" id="filEstado">
          <option value="">Todos</option>
          <option value="completado">Completado</option>
          <option value="pendiente">Pendiente</option>
          <option value="revision">En revisión</option>
        </select>
        <svg class="fil-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    {{-- Divisor --}}
    <div class="fil-divider"></div>

    {{-- Importar fotos --}}
    <div class="fil-group">
      <span class="fil-section-title">Importar fotos</span>

      <label class="fil-label" style="margin-top:10px">Origen</label>
      <button class="fil-upload-btn" id="filUploadBtn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
        Seleccionar carpeta
        <input type="file" id="filInput" multiple accept=".jpg,.jpeg,.png" style="display:none">
      </button>

      <label class="fil-label" style="margin-top:12px">Formatos permitidos</label>
      <div class="fil-checks">
        <label class="fil-check"><input type="checkbox" checked> JPG</label>
        <label class="fil-check"><input type="checkbox" checked> PNG</label>
      </div>

      <label class="fil-label" style="margin-top:12px">Vista previa</label>
      <div class="fil-preview" id="filPreview">
        <div class="fil-preview-empty">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" style="opacity:.35"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
        </div>
      </div>
      <div class="fil-preview-count" id="filCount"></div>
    </div>

  </div>

  {{-- Acciones --}}
  <div class="fil-footer">
    <button class="fil-btn-import" id="filImport">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Importar archivos
    </button>
    <button class="fil-btn-clear" id="filClear">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
      Limpiar filtros
    </button>
  </div>

</aside>
