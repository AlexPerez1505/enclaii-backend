@extends('layouts.app')

@section('title', 'Configuracion de Grabación')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Configuracion / Fuente de Video
@endsection

@push('styles')
@include('estudios.configuracion.configuracion-css')
@endpush

@section('content')

  {{-- Toolbar --}}
  <div class="cfg-toolbar rise d1">
    <a class="btn-tool" href="{{ route('nuevo-estudio.crear') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nuevo paciente
    </a>
    <button class="btn-tool">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Buscar paciente
    </button>
    <a class="btn-regresar" href="{{ route('nuevo-estudio.crear') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Regresar
    </a>
  </div>

  {{-- Título --}}
  <h1 class="cfg-main-title rise d1">Configuracion de Grabación</h1>
  <p class="cfg-pac-label rise d1">Paciente: Maria Gonzalez</p>

  {{-- Layout superior: panel info + video --}}
  <div class="cfg-top rise d2">

    {{-- Panel info --}}
    <div class="cfg-info-panel">
      <div class="cfg-info-header">Auto Importación de Fotos Activado</div>
      <label class="cfg-check-line">
        <input type="checkbox"> Pantalla completa
      </label>
      <div class="cfg-log">
        <span class="hl">Video en vivo | Paciente Maria Gonzalez | Folder de Fotos C:\ENCLAII\Patient\13\</span>
        <span>Video en Vivo |Auto Importar FotosActivado | Folder C:\ENCLAII\Temp</span>
        <span>No se encuentra la tarjeta</span>
        <span class="hl">[INFO] recording time interval set to 1800 records</span>
        <span>Buscando fotos en C:\ENCLAII\Temp</span>
        <span>Foto Capturada Maria Gonzalez-20260530-1.JPG</span>
      </div>
      <button class="btn-mas-opciones" type="button" id="btnMasOpciones">Mas Opciones</button>

      {{-- Modal de Más Opciones --}}
      <div class="mas-opciones-modal" id="masOpcionesModal">
        <div class="mas-opciones-content">
          <button class="mas-opciones-close" id="cerrarMasOpciones">&times;</button>

          <div class="mas-opciones-field">
            <label class="mas-opciones-label">Area de Captura</label>
            <select class="mas-opciones-select" id="areaCapturaSelect">
              <option value="full">Pantalla Completa</option>
              <option value="window">Ventana Activa</option>
              <option value="region">Región Personalizada</option>
            </select>
          </div>

          <div class="mas-opciones-field">
            <label class="mas-opciones-label">Canal de Video</label>
            <select class="mas-opciones-select" id="canalVideoSelect">
              <option value="1">Canal 1 - USB Video</option>
              <option value="2">Canal 2 - HDMI</option>
              <option value="3">Canal 3 - SDI</option>
              <option value="4">Canal 4 - Red</option>
            </select>
          </div>

          <div class="mas-opciones-canales">
            <label class="mas-opciones-label">Canales</label>
            <div class="canales-grid">
              <button class="canal-btn active" data-canal="1">1</button>
              <button class="canal-btn" data-canal="2">2</button>
              <button class="canal-btn" data-canal="3">3</button>
              <button class="canal-btn" data-canal="4">4</button>
              <button class="canal-btn" data-canal="5">5</button>
              <button class="canal-btn" data-canal="6">6</button>
            </div>
          </div>

          <div class="mas-opciones-iconos">
            <button class="opcion-icon-btn" id="iconStop" title="Detener">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="6" width="12" height="12" rx="2"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconVideo" title="Iniciar Grabación">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="15" height="12" rx="2"/><polygon points="17 10 22 6 22 18 17 14" fill="currentColor"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconFilm" title="Película">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="18" rx="2"/><line x1="8" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="16" y2="21"/><line x1="2" y1="9" x2="22" y2="9"/><line x1="2" y1="15" x2="22" y2="15"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconFilmStrip" title="Tira de Fotos">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 6v12"/><path d="M18 6v12"/><circle cx="6" cy="9" r="1" fill="currentColor"/><circle cx="6" cy="15" r="1" fill="currentColor"/><circle cx="18" cy="9" r="1" fill="currentColor"/><circle cx="18" cy="15" r="1" fill="currentColor"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconCrop" title="Recortar">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 2.5h11v11h-11z"/><path d="M2 6.5h4v4H2z"/><path d="M18 13.5h4v4h-4z"/><path d="M6.5 18h11v4h-11z"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconSettings" title="Configuración">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v4"/><path d="M12 18v4"/><path d="M4.93 4.93l2.83 2.83"/><path d="M16.24 16.24l2.83 2.83"/><path d="M2 12h4"/><path d="M18 12h4"/><path d="M4.93 19.07l2.83-2.83"/><path d="M16.24 7.76l2.83-2.83"/></svg>
            </button>
            <button class="opcion-icon-btn" id="iconCamera" title="Capturar Foto">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- Preview de video --}}
    <div class="cfg-video-wrap">
      <img src="{{ asset('images/captura1.jpg') }}" alt="Vista en vivo">
    </div>

  </div>

  {{-- Tabs --}}
  <div class="rise d3">
    <div class="cfg-tabs">
      <button class="cfg-tab active" data-tab="fuente">Fuente de Video</button>
      <button class="cfg-tab" data-tab="display">Display</button>
      <button class="cfg-tab" data-tab="texto">Texto e Imagen</button>
      <button class="cfg-tab" data-tab="audio">Audio</button>
      <button class="cfg-tab" data-tab="grabacion">Grabación</button>
      <button class="cfg-tab" data-tab="reproducir">Reproducir</button>
    </div>

    <div class="cfg-tab-content">

      {{-- Fuente de Video --}}
      <div class="tab-panel active" id="tab-fuente">
        <div>
          <div class="cfg-section-title">Captura de Video</div>
          <div class="cfg-field">
            <div class="cfg-field-label">Dispositivos de Captura</div>
            <select class="cfg-select">
              <option>USB Video Device</option>
              <option>Integrated Camera</option>
              <option>Endoscope Capture</option>
            </select>
          </div>
          <div class="cfg-field">
            <div class="cfg-field-label">Tamaño de Video</div>
            <select class="cfg-select">
              <option>1920 x 1080</option>
              <option>1280 x 720</option>
              <option>720 x 480</option>
            </select>
          </div>
          <div class="cfg-field">
            <div class="cfg-field-label">Subtipo de Video</div>
            <select class="cfg-select">
              <option>MJPG</option>
              <option>YUY2</option>
              <option>NV12</option>
            </select>
          </div>
          <div class="cfg-field">
            <div class="cfg-field-label">NTSC / PAL</div>
            <select class="cfg-select">
              <option>NTSC</option>
              <option>PAL</option>
            </select>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px">
          <div class="fps-badge">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            FPS
          </div>
          <div class="cfg-field" style="width:100%">
            <div class="cfg-field-label">Frames por segundo</div>
            <input class="cfg-input" type="number" value="30" min="1" max="120">
          </div>
        </div>

        <div>
          <div class="cfg-section-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:6px;color:var(--cyan)"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
            Video Render
          </div>
          <div class="cfg-checks">
            <label class="cfg-chk-item"><input type="checkbox"> Auto Select</label>
            <label class="cfg-chk-item"><input type="checkbox"> EVR</label>
            <label class="cfg-chk-item"><input type="checkbox"> VMR9</label>
            <label class="cfg-chk-item"><input type="checkbox"> VMR7</label>
            <label class="cfg-chk-item"><input type="checkbox"> Standard</label>
            <label class="cfg-chk-item"><input type="checkbox"> Overly</label>
            <label class="cfg-chk-item"><input type="checkbox"> Record Priority</label>
          </div>
          <div style="margin-top:16px">
            <div class="auto-imp-box">
              <label class="auto-imp-lbl">
                <input type="checkbox" checked> Auto Importar
              </label>
              <label class="cfg-chk-item">
                <input type="checkbox" checked> Importar Automáticamente
              </label>
            </div>
          </div>
        </div>
      </div>

      {{-- Display --}}
      <div class="tab-panel" id="tab-display" style="grid-template-columns:1fr 1fr 1fr">

        {{-- Col 1: Ventana de Video --}}
        <div>
          <div class="cfg-section-title">Ventana de Video</div>
          <label class="cfg-chk-item" style="margin-bottom:12px">
            <input type="checkbox"> Ajustar Tamaño
          </label>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Ancho</span>
            <input class="cfg-input dsp-num" type="number" value="540">
          </div>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Alto</span>
            <input class="cfg-input dsp-num" type="number" value="480">
          </div>
          <div class="cfg-field-label" style="margin-top:14px;margin-bottom:6px">Zoom</div>
          <input class="cfg-input dsp-num" type="number" value="1000">
        </div>

        {{-- Col 3: Foto Capturadora --}}
        <div>
          <div class="cfg-section-title">Foto Capturadora</div>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Ancho</span>
            <input class="cfg-input dsp-num" type="number" value="130">
          </div>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Alto</span>
            <input class="cfg-input dsp-num" type="number" value="130">
          </div>
          <div class="cfg-field-label" style="margin-top:14px">Tamaño del Contador</div>
        </div>

        {{-- Col 4: Overlays --}}
        <div>
          <div class="cfg-section-title">Overlays</div>
          <div class="cfg-field-label" style="margin-bottom:10px">Indicador de Captura de Fotos</div>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Ancho</span>
            <input class="cfg-input dsp-num" type="number" value="540">
          </div>
          <div class="dsp-row-field">
            <span class="cfg-field-label">Alto</span>
            <input class="cfg-input dsp-num" type="number" value="480">
          </div>
          <div class="cfg-field-label" style="margin-top:14px">Indicador de Grabación</div>
        </div>

      </div>

      {{-- Texto e Imagen --}}
      <div class="tab-panel" id="tab-texto" style="grid-template-columns:1fr 1.6fr 1fr;gap:20px;align-items:start">

        {{-- Col 1: Configuracion --}}
        <div style="display:flex;flex-direction:column;gap:8px">

          {{-- Activo --}}
          <label class="cfg-chk-item">
            <input type="checkbox" id="textoActivo" checked> Activo
          </label>

          {{-- Alineacion --}}
          <div class="txt-card" style="padding:12px 14px">
            <div class="cfg-section-title" style="margin-bottom:8px">Alineación</div>
            <div style="display:flex;flex-direction:column;gap:8px">
              <div style="display:flex;align-items:center;gap:12px">
                <span class="cfg-field-label" style="min-width:40px">Izq</span>
                <input class="cfg-input dsp-num" id="txtIzq" type="number" value="5" style="width:64px">
                <span class="cfg-field-label">Izquierdo</span>
              </div>
              <div style="display:flex;align-items:center;gap:12px">
                <span class="cfg-field-label" style="min-width:40px">Centro</span>
                <input class="cfg-input dsp-num" id="txtCentro" type="number" value="5" style="width:64px">
                <span class="cfg-field-label">Superior</span>
              </div>
              <div style="display:flex;align-items:center;gap:12px">
                <span class="cfg-field-label" style="min-width:40px">Der</span>
                <input class="cfg-input dsp-num" id="txtDer" type="number" value="140" style="width:64px">
                <span class="cfg-field-label">Ancho</span>
              </div>
            </div>
          </div>

          {{-- Logotipo --}}
          <label class="cfg-chk-item">
            <input type="checkbox" id="textoLogo"> Logotipo
          </label>

          {{-- % Transferencia --}}
          <div class="cfg-field-label">% de Transferencia</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:2px">
            <input class="cfg-input dsp-num" id="txtTrans1" type="number" value="500">
            <input class="cfg-input dsp-num" id="txtTrans2" type="number" value="500">
            <input class="cfg-input dsp-num" id="txtTrans3" type="number" value="100">
            <input class="cfg-input dsp-num" id="txtTrans4" type="number" value="100">
          </div>

        </div>

        {{-- Col 2: Vista Previa --}}
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="cfg-section-title">Vista Previa</div>
          <div class="txt-preview-box">
            <span id="txtPreviewContent"
              style="font-family:'Arial',sans-serif;font-size:14px;color:var(--txt);line-height:1.9">
              Paciente: Maria Gonzalez<br>
              Fecha: 30/05/2026<br>
              Medico:<br>
              Procedimiento
            </span>
          </div>
        </div>

        {{-- Col 3: Tipografia --}}
        <div style="display:flex;flex-direction:column;gap:14px;position:relative">
          <div class="cfg-section-title">Tipografía</div>

          {{-- Botón fuente --}}
          <div style="position:relative">
            <button class="btn-fuente" id="btnFuente" type="button">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex:none"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>
              Seleccionar Fuente
            </button>
            <div class="fuente-dropdown" id="fuenteDropdown">
              <div class="fuente-search-wrap">
                <input class="cfg-input" id="fuenteBuscar" type="text" placeholder="Buscar fuente..." autocomplete="off">
              </div>
              <ul class="fuente-list" id="fuenteList"></ul>
            </div>
          </div>

          {{-- Fuente seleccionada --}}
          <div class="txt-card">
            <div class="cfg-field-label">Fuente activa</div>
            <div id="fuenteSelNombre"
              style="font-size:15px;font-weight:700;color:var(--txt);margin-top:5px">Arial</div>
          </div>

          {{-- Tamaño --}}
          <div class="txt-card">
            <div class="cfg-field-label" style="margin-bottom:8px">Tamaño (px)</div>
            <div style="display:flex;align-items:center;gap:10px">
              <input class="cfg-input dsp-num" id="txtSize" type="number" value="14" min="6" max="72" style="width:64px">
              <input type="range" id="txtSizeRange" min="6" max="72" value="14"
                style="flex:1;accent-color:var(--blue)">
            </div>
          </div>

        </div>

      </div>

      {{-- Audio --}}
      <div class="tab-panel" id="tab-audio" style="grid-template-columns:1fr 1fr 1fr;gap:22px;align-items:start">

        {{-- Col 1: Dispositivo de Audio --}}
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="cfg-section-title">Dispositivo de Audio</div>

          {{-- Audio Input --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:5px">Audio Input</div>
            <select class="cfg-select">
              <option>Micrófono integrado</option>
              <option>USB Audio</option>
              <option>Entrada de línea</option>
              <option>Sin audio</option>
            </select>
          </div>

          {{-- Audio Inputs + Mono --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:5px">Audio Inputs</div>
            <div style="display:flex;align-items:center;gap:10px">
              <select class="cfg-select" style="flex:1">
                <option>1</option><option>2</option><option>4</option>
              </select>
              <label class="cfg-chk-item" style="white-space:nowrap">
                <input type="checkbox" id="audioMono"> mono
              </label>
            </div>
          </div>

          {{-- Calidad --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:5px">Calidad</div>
            <select class="cfg-select">
              <option>Alta</option>
              <option>Media</option>
              <option>Baja</option>
            </select>
          </div>

          {{-- Nivel de Audio --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:5px">Nivel de Audio</div>
            <input type="range" min="0" max="100" value="75"
              style="width:100%;accent-color:var(--blue)">
          </div>

          {{-- Balance de Audio --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:5px">Balance de Audio</div>
            <input type="range" min="-50" max="50" value="0"
              style="width:100%;accent-color:var(--blue)">
          </div>
        </div>

        {{-- Col 2: Audio Renderer --}}
        <div style="display:flex;flex-direction:column;gap:14px">
          <div class="cfg-section-title">Audio Renderer</div>

          {{-- Mute --}}
          <label class="cfg-chk-item">
            <input type="checkbox" id="audioMute"> mute
          </label>

          {{-- Volumen --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:6px">Volumen</div>
            <input type="range" id="audioVolRange" min="0" max="100" value="80"
              style="width:100%;accent-color:var(--blue)">
          </div>

          {{-- Balance --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:6px">Balance</div>
            <input type="range" id="audioBalRange" min="-50" max="50" value="0"
              style="width:100%;accent-color:var(--blue)">
          </div>
        </div>

        {{-- Col 3: Sonido al Capturar --}}
        <div style="display:flex;flex-direction:column;gap:14px">
          <div class="cfg-section-title">Sonido al Capturar</div>

          <label class="aud-radio-item">
            <input type="radio" name="audioCaptura" id="audBeep" checked>
            <span class="aud-radio-dot"></span>
            BEEP
          </label>

          <label class="aud-radio-item">
            <input type="radio" name="audioCaptura" id="audWav">
            <span class="aud-radio-dot"></span>
            Archivo de Sonido (WAV)
          </label>
        </div>

      </div>

      {{-- Grabación --}}
      <div class="tab-panel" id="tab-grabacion" style="grid-template-columns:1.6fr 1fr 1fr;gap:22px;align-items:start">

        {{-- Col 1: Comprensión + CODEC + Modos --}}
        <div style="display:flex;flex-direction:column;gap:14px">

          <div class="cfg-section-title">Comprensión de Video y Audio</div>

          {{-- CODEC --}}
          <div>
            <div class="cfg-field-label" style="margin-bottom:4px">Video</div>
            <div class="cfg-field-label" style="margin-bottom:6px;color:var(--txt)">CODEC de Video</div>
            <div style="display:flex;align-items:center;gap:10px">
              <select class="cfg-select" style="flex:1">
                <option>Datastead Multipurpose Encoder</option>
                <option>H.264 AVC</option>
                <option>H.265 HEVC</option>
                <option>MPEG-4</option>
              </select>
              <button class="btn-tool" style="padding:8px 10px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
              </button>
            </div>
          </div>

          {{-- Tres grupos de radios en fila --}}
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-top:4px">

            {{-- Modo de Conversión --}}
            <div>
              <div class="cfg-field-label" style="margin-bottom:8px">Modo de Conversión</div>
              <div style="display:flex;flex-direction:column;gap:8px">
                <label class="aud-radio-item">
                  <input type="radio" name="grabConv" value="no">
                  <span class="aud-radio-dot"></span> No
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabConv" value="momento" checked>
                  <span class="aud-radio-dot"></span> Al momento
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabConv" value="final">
                  <span class="aud-radio-dot"></span> Al final
                </label>
              </div>
            </div>

            {{-- Tamaño de Video --}}
            <div>
              <div class="cfg-field-label" style="margin-bottom:8px">Tamaño de Video</div>
              <div style="display:flex;flex-direction:column;gap:8px">
                <label class="aud-radio-item">
                  <input type="radio" name="grabSize" value="default" checked>
                  <span class="aud-radio-dot"></span> Default
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabSize" value="half">
                  <span class="aud-radio-dot"></span> Half Size
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabSize" value="quarter">
                  <span class="aud-radio-dot"></span> Quarter Size
                </label>
              </div>
            </div>

            {{-- Tipo --}}
            <div>
              <div class="cfg-field-label" style="margin-bottom:8px">Tipo</div>
              <div style="display:flex;flex-direction:column;gap:8px">
                <label class="aud-radio-item">
                  <input type="radio" name="grabTipo" value="video" checked>
                  <span class="aud-radio-dot"></span> Video
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabTipo" value="audio">
                  <span class="aud-radio-dot"></span> Audio
                </label>
                <label class="aud-radio-item">
                  <input type="radio" name="grabTipo" value="ambos">
                  <span class="aud-radio-dot"></span> Audio + Video
                </label>
              </div>
            </div>

          </div>
        </div>

        {{-- Col 2: Método de Grabación --}}
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="cfg-section-title">Metodo de Grabacion</div>
          <div style="display:flex;flex-direction:column;gap:8px">
            <label class="aud-radio-item">
              <input type="radio" name="grabMetodo" value="avi">
              <span class="aud-radio-dot"></span> AVI
            </label>
            <label class="aud-radio-item">
              <input type="radio" name="grabMetodo" value="mpg" checked>
              <span class="aud-radio-dot"></span> MPG
            </label>
            <label class="aud-radio-item">
              <input type="radio" name="grabMetodo" value="mp4">
              <span class="aud-radio-dot"></span> MP4
            </label>
            <label class="aud-radio-item">
              <input type="radio" name="grabMetodo" value="mov">
              <span class="aud-radio-dot"></span> MOV
            </label>
            <label class="aud-radio-item">
              <input type="radio" name="grabMetodo" value="flv">
              <span class="aud-radio-dot"></span> FLV
            </label>
          </div>
        </div>

        {{-- Col 3: Pausa / Timer / Opciones --}}
        <div style="display:flex;flex-direction:column;gap:12px">
          <div class="cfg-section-title">Pausa / Continuar</div>

          <div style="display:flex;flex-direction:column;gap:6px">
            <div class="cfg-field-label">Grabación con Pausa</div>
            <div class="cfg-field-label">Pausa crea nuevo archivo</div>
          </div>

          <div class="cfg-field-label" style="margin-top:4px">Timer de grabación</div>

          <div style="display:flex;flex-direction:column;gap:8px">
            <label class="aud-radio-item">
              <input type="radio" name="grabTimer" value="pausa">
              <span class="aud-radio-dot"></span> Grabación con Pausa
            </label>
            <div style="display:flex;align-items:center;gap:10px">
              <label class="aud-radio-item" style="flex:1">
                <input type="radio" name="grabTimer" value="nuevo" checked>
                <span class="aud-radio-dot"></span> Pausa crea nuevo archivo
              </label>
              <div style="display:flex;flex-direction:column;align-items:center;min-width:48px">
                <input class="cfg-input dsp-num" type="number" value="30" style="width:52px;text-align:center">
                <span class="cfg-field-label" style="margin-top:2px;font-size:11px">Minutos</span>
              </div>
            </div>
          </div>

          <div style="margin-top:4px">
            <div class="cfg-field-label" style="margin-bottom:8px">Opciones de Grabación</div>
            <label class="cfg-chk-item">
              <input type="checkbox"> record cursor
            </label>
          </div>
        </div>

      </div>

      {{-- Reproducir --}}
      <div class="tab-panel" id="tab-reproducir" style="grid-template-columns:1fr;gap:16px">

        {{-- Encabezado --}}
        <div>
          <div style="font-size:15px;font-weight:700;color:var(--txt);margin-bottom:3px">Controles de Video</div>
          <div class="cfg-field-label">Seleccione de la lista un video para ejecutarlo. Si lo desea, puede tambien capturar fotos.</div>
        </div>

        {{-- Panel principal: lista + toolbar + controles --}}
        <div class="rep-main-panel">

          {{-- Columna izquierda: lista + iconos --}}
          <div class="rep-left">
            <div class="rep-list-box" id="repList">
              <div class="rep-empty" id="repEmpty">Sin videos</div>
            </div>
            <div class="rep-sidebar-icons">
              <button class="rep-side-btn" id="repBtnPlay" title="Reproducir">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
              </button>
              <button class="rep-side-btn" id="repBtnStop" title="Detener">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><rect x="9" y="9" width="6" height="6" fill="currentColor" stroke="none"/></svg>
              </button>
              <button class="rep-side-btn" id="repBtnOpen" title="Agregar video">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
              </button>
              <button class="rep-side-btn rep-side-btn--danger" id="repBtnDel" title="Eliminar seleccionado">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </button>
            </div>
          </div>

          {{-- Panel derecho: Ejecutar-Pausa-Detener --}}
          <div class="rep-ctrl-panel">

            {{-- Título --}}
            <div class="rep-ctrl-title">Ejecutar &ndash; Pausa &ndash; Detener</div>

            {{-- Estado del video --}}
            <div class="rep-status" id="repStatus">Sin video seleccionado</div>

            {{-- Barra de progreso --}}
            <div class="rep-progress-wrap">
              <input type="range" class="rep-progress" id="repProgress" min="0" max="100" value="0">
            </div>

            {{-- Controles principales --}}
            <div class="rep-controls-row">
              {{-- Grupo izq: Stop + Retroceso rápido --}}
              <div class="rep-ctrl-group">
                <button class="rep-ctrl-btn" id="repCtrlStop" title="Detener">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><rect x="9" y="9" width="6" height="6" fill="currentColor" stroke="none"/></svg>
                </button>
                <button class="rep-ctrl-btn" id="repCtrlRew" title="Retroceso rápido">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polygon points="14 8 8 12 14 16 14 8" fill="currentColor" stroke="none"/><line x1="9" y1="8" x2="9" y2="16"/></svg>
                </button>
              </div>

              {{-- Grupo centro: Prev / Play-Pause / Next --}}
              <div class="rep-ctrl-group rep-ctrl-group--main">
                <button class="rep-ctrl-btn rep-ctrl-btn--lg" id="repCtrlPrev" title="Anterior">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polygon points="14 8 8 12 14 16 14 8" fill="currentColor" stroke="none"/></svg>
                </button>
                <button class="rep-ctrl-btn rep-ctrl-btn--play" id="repCtrlPlay" title="Reproducir / Pausar">
                  <svg id="repPlayIcon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
                </button>
                <button class="rep-ctrl-btn rep-ctrl-btn--lg" id="repCtrlNext" title="Siguiente">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
                </button>
              </div>

              {{-- Grupo der: Captura --}}
              <div class="rep-ctrl-group">
                <button class="rep-ctrl-btn" id="repCtrlCapture" title="Capturar foto">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </button>
              </div>
            </div>

          </div>

        </div>

        {{-- Input oculto --}}
        <input type="file" id="repFileInput" accept="video/*" multiple style="display:none">

      </div>

    </div>
  </div>

@endsection

@push('scripts')
@include('estudios.configuracion.configuracion-js')
@endpush
