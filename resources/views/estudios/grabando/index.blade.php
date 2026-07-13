@extends('layouts.app')

@section('title', 'Nuevo Estudio/Grabando')
@section('active', 'nuevo-estudio')

@php
  $studioUserName = auth()->check() ? trim(auth()->user()->name ?? 'Doctor') : 'Doctor';
  $studioUserParts = preg_split('/\s+/', $studioUserName);
  $studioUserInitials = collect($studioUserParts)->take(2)->map(fn($p) => mb_substr($p, 0, 1))->join('');
  $studioUserInitials = mb_strtoupper($studioUserInitials ?: mb_substr($studioUserName, 0, 2));
@endphp

@push('styles')
@include('estudios.grabando.grabando-css')
@endpush

@section('content')
@php
  $estudio = $estudio ?? null;
  $capturas = $estudio ? $estudio->capturas()->latest()->get() : collect();
  $numCapturas = $capturas->count();
  $pacienteNombre = $estudio?->paciente?->nombre_completo ?? $estudio?->paciente_nombre ?? 'Sin paciente';
@endphp
<div class="studio-wrap">

  {{-- ══ TOPBAR ══ --}}
  <div class="studio-topbar">
    <div class="studio-top-left">
      <div class="studio-rec-status">
        <span class="studio-rec-dot"></span>
        <div>
          <div style="display:flex;align-items:center;gap:12px">
            <span class="studio-rec-text">GRABANDO</span>
            <span class="studio-timer" id="recTimer">00:00:00</span>
          </div>
          <div class="studio-study-name">Estudio: {{ $estudio?->tipo ?? 'Sin tipo' }} @if($estudio?->folio) · {{ $estudio->folio }} @endif</div>
        </div>
      </div>
    </div>

    <div class="studio-topbar-sep"></div>

    <div class="studio-top-center">
      {{-- Centro vacío o con info adicional si se necesita --}}
    </div>

    <div class="studio-topbar-sep"></div>

    <div class="studio-top-right">
      {{-- Grupo 1: Almacenamiento --}}
      <div class="studio-storage">
        <svg class="studio-storage-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/></svg>
        <div>
          <div class="studio-storage-text">Almacenamiento</div>
          <div style="display:flex;align-items:center;gap:8px">
            <span class="studio-storage-used">3.2 GB /50 GB</span>
          </div>
          <div class="studio-storage-bar"><div class="studio-storage-fill"></div></div>
        </div>
      </div>

      <div class="studio-topbar-gap"></div>

      {{-- Grupo 3: Notificaciones + Doctor --}}
      <div class="studio-top-group">
        <button class="studio-theme-btn" id="studioThemeToggle" aria-label="Cambiar tema">
          <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg>
          <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        </button>

        <div class="studio-notif">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <span class="studio-notif-badge">3</span>
        </div>

        <div class="studio-doctor">
          <div class="studio-doc-avatar">{{ $studioUserInitials }}</div>
          <div class="studio-doc-info">
            <div class="studio-doc-name">{{ $studioUserName }}</div>
            <div class="studio-doc-role">Endoscopista</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ══ MAIN CONTENT ══ --}}
  <div class="studio-main">

    {{-- COLUMNA CENTRAL --}}
    <div class="studio-center">

      {{-- Video --}}
      <div class="studio-video-box">
        <div class="studio-video-screen" id="videoScreen">
          <video id="studioWebcam" autoplay playsinline muted style="width:100%;height:100%;object-fit:cover;"></video>
          <div id="webcamFallback" style="display:none;position:absolute;inset:0;align-items:center;justify-content:center;text-align:center;padding:24px;color:rgba(255,255,255,.7);font-size:14px;line-height:1.5;"></div>
        </div>
        <canvas id="captureCanvas" style="display:none"></canvas>
        <div class="studio-hud">
          <span class="studio-hud-dot"></span>Rec<br>192 x 1080<br>60 FPS<br>Audio ON
        </div>
        <button class="studio-expand-btn" id="btnFullscreen" title="Pantalla completa">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
        </button>
      </div>

      {{-- Fotos capturadas --}}
      <div class="studio-timeline">
        <div class="studio-tl-header">
          <span class="studio-tl-title">Fotos capturadas</span>
        </div>
        <div class="studio-tl-scroll" id="recTimeline">
          @forelse($capturas as $cap)
          <div class="studio-thumb" data-id="{{ $cap->id }}">
            <div class="studio-thumb-inner" style="padding:0;overflow:hidden">
              <img src="{{ asset('storage/'.$cap->path) }}" alt="captura" style="width:100%;height:100%;object-fit:cover;border-radius:8px">
            </div>
            @php $tsTl = is_numeric($cap->descripcion) ? gmdate('H:i:s',(int)$cap->descripcion) : (optional($cap->capturado_en)->format('H:i:s') ?? ''); @endphp
            <span class="studio-thumb-time">{{ $tsTl }}</span>
          </div>
          @empty
          <div id="recTimelineEmpty" style="display:flex;align-items:center;color:rgba(255,255,255,.4);font-size:13px;padding:8px 4px">Aún no hay fotos. Presiona "Capturar Foto".</div>
          @endforelse
        </div>
      </div>

      {{-- Bottom Bar --}}
      <div class="studio-bottom">
        {{-- Controles de Grabación --}}
        <div class="studio-rec-controls">
          <button class="studio-captura-btn" id="btnCapturarFoto">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            Capturar Foto
          
          </button>
          <button class="studio-pause-btn" id="btnPausa">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            Pausar
          </button>
          <div class="studio-divider-v"></div>
          <a class="studio-terminar-btn" href="{{ route('nuevo-estudio') }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8" transform="rotate(45 12 12)"/></svg>
            Terminar Estudio
          </a>
        </div>
      </div>

    </div>

    {{-- SIDEBAR DERECHO --}}
    <div class="studio-sidebar">
      <div class="studio-sidebar-title">Estudio Activo</div>

      <div class="studio-stats">
        <div class="studio-stat-card studio-stat-card-hover" title="Paciente: {{ $pacienteNombre }}">
          <div class="studio-stat-header">
            <div class="studio-stat-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="studio-stat-label">Duración</div>
          </div>
          <div class="studio-stat-value red" id="recTimerSide">00:00:00</div>
          <div class="studio-stat-patient-hover">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {{ $pacienteNombre }}
          </div>
        </div>

        <div class="studio-stat-card">
          <div class="studio-stat-header">
            <div class="studio-stat-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </div>
            <div class="studio-stat-label">Fotos Capturadas</div>
          </div>
          <div class="studio-stat-value" id="recFotos">{{ $numCapturas }}</div>
        </div>
      </div>


      {{-- Botones de acción en sidebar --}}
      <div class="studio-sidebar-actions">
        <button class="studio-btn-emergency">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4M12 17h.01"/></svg>
          Emergencia
        </button>
        <a class="studio-btn-volver" href="{{ route('nuevo-estudio') }}">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          Volver
        </a>
      </div>

    </div>

  </div>{{-- /.studio-main --}}
</div>{{-- /.studio-wrap --}}

{{-- NOTA: La interfaz de estudio terminado está en estudios/finalizado/index.blade.php --}}

{{-- ═══════ INTERFAZ ESTUDIO DE EMERGENCIA (tipo galeria) ═══════ --}}
<div class="studio-emergencia-wrap" id="studioEmergencia">

  {{-- Header --}}
  <div class="studio-finalizado-header">
    <div class="studio-finalizado-status">
      <div class="studio-status-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div>
        <div class="studio-status-text">Estudio terminado</div>
        <div class="studio-status-sub">La grabación ha finalizado correctamente</div>
      </div>
    </div>

    <div class="studio-final-right">
      <button class="studio-theme-btn" type="button" aria-label="Cambiar tema">
        <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg>
        <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
      <button class="studio-final-btn-notif">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span class="studio-notif-badge">3</span>
      </button>
      <div class="studio-final-profile">
        <div class="studio-doc-avatar">{{ $studioUserInitials }}</div>
        <div class="studio-doc-info">
          <div class="studio-doc-name">{{ $studioUserName }}</div>
          <div class="studio-doc-role">Endoscopista</div>
        </div>
      </div>
      <div class="studio-final-sep-v"></div>
      <a class="studio-btn-volver" href="{{ route('nuevo-estudio') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver
      </a>
    </div>
  </div>

  {{-- Main content --}}
  <div class="studio-finalizado-main">

    {{-- Columna izquierda --}}
    <div style="display:flex;flex-direction:column;gap:16px;overflow:hidden">

      {{-- Video Player --}}
      <div class="sf-video-player" style="flex:1">
        <div class="sf-video-bg"></div>
        <video id="sfVideoEl" style="position:absolute;inset:0;width:100%;height:100%;object-fit:contain;background:#000;z-index:1;display:block" preload="metadata"@if($estudio?->video_path) src="{{ asset('storage/'.$estudio->video_path) }}"@endif></video>
        <img id="sfMainImg" alt="Imagen capturada" style="display:none;position:absolute;inset:0;width:100%;height:100%;object-fit:contain;background:#000;z-index:2">
        <div class="sf-video-center" id="sfVideoCenter">
          <button class="sf-play-big" id="sfPlayBigFinal">
            <svg class="play-icon" width="20" height="20" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <svg class="pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="white" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
          </button>
        </div>
        <div class="sf-video-controls">
          <div class="sf-prog-wrap">
            <div class="sf-prog-fill"></div>
            <div class="sf-prog-thumb"></div>
          </div>
          <div class="sf-ctrl-row">
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polygon points="19 20 9 12 19 4 19 20"/><line x1="5" y1="19" x2="5" y2="5"/></svg></button>
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg></button>
            <button class="sf-ctrl-btn" id="sfPlayBtnFinal">
              <svg class="play-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              <svg class="pause-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            </button>
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-4.95"/></svg></button>
            <span class="sf-time" id="sfTimeFinal">--:-- / --:--</span>
            <div class="sf-vol-wrap">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
              <div class="sf-vol-bar"><div class="sf-vol-fill"></div></div>
            </div>
            <button class="sf-speed" id="sfSpeedFinal">1.0x</button>
            <button class="sf-ctrl-btn sf-fs" id="btnExpandirFinal"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg></button>
          </div>
        </div>
      </div>

      {{-- Acciones tipo galeria --}}
      <div class="studio-final-actions">
        <button class="studio-final-act-btn guardar" id="btnGuardarEstudio" style="background:rgba(34,197,94,.14);border-color:rgba(34,197,94,.4);color:#22c55e"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>Guardar</button>
        <div class="sf-share-wrap" style="position:relative">
          <button class="studio-final-act-btn wa" id="btnCompartir" type="button"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>Compartir</button>
          <div class="sf-share-dropdown" id="sfShareDropdown">
            <a class="sf-share-item" id="sfShareWa" href="{{ route('mensajes', ['desde' => 'estudio_terminado', 'estudio_id' => $estudio?->id, 'paciente' => $estudio?->paciente_id, 'estudio' => $estudio?->id]) }}" target="_blank">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              WhatsApp
            </a>
            <a class="sf-share-item" id="sfShareEmail" href="mailto:?subject=Estudio+{{ urlencode($estudio?->folio ?? '') }}&body=Adjunto+resultados+del+estudio+{{ urlencode($pacienteNombre) }}.">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              Correo electrónico
            </a>
            <button class="sf-share-item" id="sfDownloadVideo" type="button">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Descargar video
            </button>
            <button class="sf-share-item" id="sfDownloadImgs" type="button">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              Descargar imágenes
            </button>
          </div>
        </div>
        <a class="studio-final-act-btn" style="background:rgba(56,199,244,.14);border-color:rgba(56,199,244,.4);color:#38c7f4" href="{{ route('ia-reportes.generar', ['estudio' => $estudio?->id]) }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="M9 15l3 3 3-3"/></svg>Generar reporte IA</a>
        <a class="studio-final-act-btn fin" href="{{ route('ia-reportes.redactar', ['paciente' => $estudio?->paciente_id, 'estudio' => $estudio?->id]) }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>Finalizar estudio</a>
      </div>

      {{-- Miniaturas capturadas --}}
      <div>
        <div class="studio-final-caps-title">Imágenes capturadas del estudio</div>
        <div class="studio-final-caps-strip" id="sfCapsStrip">
          @forelse($capturas as $i => $cap)
          <div class="studio-final-cap-item {{ $i===0 ? 'sel' : '' }}" data-id="{{ $cap->id }}">
            <div class="studio-final-cap-thumb">
              <img src="{{ asset('storage/'.$cap->path) }}" alt="captura" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
              <span class="studio-final-cap-num">{{ $i+1 }}</span>
              <span class="studio-final-cap-check"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            </div>
            @php $tsCap = is_numeric($cap->descripcion) ? gmdate('H:i:s',(int)$cap->descripcion) : (optional($cap->capturado_en)->format('H:i:s') ?? ''); @endphp
            <div class="studio-final-cap-ts">{{ $tsCap }}</div>
          </div>
          @empty
          <div id="sfCapsEmpty" style="color:rgba(255,255,255,.4);font-size:13px;padding:8px 4px">No se capturaron fotos en este estudio.</div>
          @endforelse
        </div>
      </div>

    </div>

    {{-- Sidebar derecho --}}
    <div class="studio-resumen-sidebar">

      {{-- Resumen del estudio --}}
      <div class="studio-resumen-card">
        <div class="studio-resumen-title">Resumen del estudio</div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon studio-icon-paciente" data-paciente="{{ $pacienteNombre }}">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            Estado
          </div>
          <div class="studio-resumen-value green">Completado</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            Duración
          </div>
          <div class="studio-resumen-value" id="sfResumenDuracion">@if($estudio?->duracion_segundos){{ gmdate('H:i:s', $estudio->duracion_segundos) }}@else--:--:--@endif</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
            Fecha
          </div>
          <div class="studio-resumen-value">{{ optional($estudio?->fecha)->format('d/m/Y') ?? now()->format('d/m/Y') }}</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Médico
          </div>
          <div class="studio-resumen-value">{{ $studioUserName }}</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
            Fotos Capturadas
          </div>
          <div class="studio-resumen-value" id="sfResumenFotos">{{ $numCapturas }}</div>
        </div>

      </div>

    </div>

  </div>
</div>{{-- /.studio-finalizado-wrap --}}

{{-- ═══════ INTERFAZ ESTUDIO DE EMERGENCIA (tipo galeria) ═══════ --}}
<div class="studio-emergencia-wrap" id="studioEmergencia">

  {{-- Header --}}
  <div class="studio-finalizado-header">
    <div class="studio-finalizado-status studio-emergencia-status">
      <div class="studio-status-icon">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      </div>
      <div>
        <div class="studio-status-text">Estudio de emergencia</div>
        <div class="studio-status-sub">Alerta médica activada - Requiere atención inmediata</div>
      </div>
    </div>

    <div class="studio-final-right">
      <button class="studio-theme-btn" type="button" aria-label="Cambiar tema">
        <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg>
        <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
      <button class="studio-final-btn-notif">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span class="studio-notif-badge">3</span>
      </button>
      <div class="studio-final-profile">
        <div class="studio-doc-avatar">{{ $studioUserInitials }}</div>
        <div class="studio-doc-info">
          <div class="studio-doc-name">{{ $studioUserName }}</div>
          <div class="studio-doc-role">Endoscopista</div>
        </div>
      </div>
      <div class="studio-final-sep-v"></div>
      <a class="studio-btn-volver" href="{{ route('nuevo-estudio') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver
      </a>
    </div>
  </div>

  {{-- Main content --}}
  <div class="studio-finalizado-main">

    {{-- Columna izquierda --}}
    <div style="display:flex;flex-direction:column;gap:16px;overflow:hidden">

      {{-- Video Player --}}
      <div class="sf-video-player" style="flex:1">
        <div class="sf-video-bg"></div>
        <div class="sf-video-center">
          <button class="sf-play-big" id="sfPlayBigEm">
            <svg class="play-icon" width="20" height="20" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <svg class="pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="white" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
          </button>
        </div>
        <div class="sf-video-controls">
          <div class="sf-prog-wrap">
            <div class="sf-prog-fill" style="background:#dc2626"></div>
            <div class="sf-prog-thumb" style="background:#fff"></div>
          </div>
          <div class="sf-ctrl-row">
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polygon points="19 20 9 12 19 4 19 20"/><line x1="5" y1="19" x2="5" y2="5"/></svg></button>
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg></button>
            <button class="sf-ctrl-btn" id="sfPlayBtnEm">
              <svg class="play-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              <svg class="pause-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            </button>
            <button class="sf-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-4.95"/></svg></button>
            <span class="sf-time" id="sfTimeEm">00:02:15 / 00:15:42</span>
            <div class="sf-vol-wrap">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
              <div class="sf-vol-bar"><div class="sf-vol-fill"></div></div>
            </div>
            <button class="sf-speed" id="sfSpeedEm">1.0x</button>
            <button class="sf-ctrl-btn sf-fs" id="btnExpandirEmergencia"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg></button>
          </div>
        </div>
      </div>

      {{-- Acciones tipo galeria --}}
      <div class="studio-final-actions">
        <a class="studio-final-act-btn wa" href="{{ route('mensajes') }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>Enviar mensaje</a>
        <a class="studio-final-act-btn" style="background:rgba(56,199,244,.14);border-color:rgba(56,199,244,.4);color:#38c7f4" href="{{ route('ia-reportes.generar', ['estudio' => $estudio?->id]) }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="M9 15l3 3 3-3"/></svg>Generar reporte IA</a>
        <a class="studio-final-act-btn fin" href="{{ route('ia-reportes.redactar', ['paciente' => $estudio?->paciente_id, 'estudio' => $estudio?->id]) }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>Finalizar estudio</a>
      </div>

      {{-- Miniaturas capturadas --}}
      <div>
        <div class="studio-final-caps-title">Imágenes capturadas del estudio</div>
        <div class="studio-final-caps-strip">
          @php
          $capsEm=[['n'=>1,'ts'=>'0:01:25'],['n'=>2,'ts'=>'0:02:15'],['n'=>3,'ts'=>'0:04:32'],['n'=>4,'ts'=>'0:06:18'],['n'=>5,'ts'=>'0:08:47'],['n'=>6,'ts'=>'0:11:03']];
          @endphp
          @foreach($capsEm as $i => $c)
          <div class="studio-final-cap-item {{ $i===1 ? 'sel' : '' }}" data-ts="{{ $c['ts'] }}">
            <div class="studio-final-cap-thumb">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <span class="studio-final-cap-num">{{ $c['n'] }}</span>
              <span class="studio-final-cap-check"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            </div>
            <div class="studio-final-cap-ts">{{ $c['ts'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

    </div>

    {{-- Sidebar derecho --}}
    <div class="studio-resumen-sidebar">

      {{-- Resumen del estudio --}}
      <div class="studio-resumen-card">
        <div class="studio-resumen-title">Resumen del estudio</div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon studio-icon-paciente" data-paciente="{{ $pacienteNombre }}"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Estado
          </div>
          <div class="studio-resumen-value danger">Emergencia</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon danger"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
            Paciente de Emergencia
          </div>
          <div class="studio-resumen-value">Sí</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            Duración
          </div>
          <div class="studio-resumen-value" id="sfResumenDuracionEm">--:--:--</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
            Fecha
          </div>
          <div class="studio-resumen-value">{{ optional($estudio?->fecha)->format('d/m/Y') ?? now()->format('d/m/Y') }}</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Médico
          </div>
          <div class="studio-resumen-value">{{ $studioUserName }}</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
            Fotos Capturadas
          </div>
          <div class="studio-resumen-value">30</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></div>

      </div>

    </div>

  </div>
</div>{{-- /.studio-emergencia-wrap --}}
@endsection

@push('scripts')
@include('estudios.grabando.grabando-js')
@endpush
