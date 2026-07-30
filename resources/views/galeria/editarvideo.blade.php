@extends('layouts.app')

@section('title', 'Editar Video')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')

@php
  $pacienteId = $paciente?->id ?? $archivo->paciente_id ?? request('paciente');
  $nombrePaciente = $paciente?->nombre_completo ?? $estudio?->paciente_nombre ?? 'Paciente';
  $folioEstudio = $estudio?->folio ?? ('Video #'.$archivo->id);
  $tituloVideo = $archivo->nombre_original ?? $archivo->nombre ?? 'Video del estudio';
  $tipoEstudio = $estudio?->tipo ?: 'Video del estudio';
  $videoUrl = route('galeria.video.stream', $archivo->id);
  $videoDownloadUrl = route('galeria.video.archivo', $archivo->id);
  $downloadName = $archivo->nombre_original ?: ('video-'.$archivo->id.'.webm');
  $editorConfig = array_merge([
    'brillo' => 100,
    'contraste' => 100,
    'saturacion' => 100,
    'nitidez' => 0,
    'zoom' => 100,
    'rotacion' => 0,
    'flip_h' => false,
    'flip_v' => false,
    'trim_start' => null,
    'trim_end' => null,
  ], $editorConfig ?? []);
@endphp

@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  @if($pacienteId)
    <a href="{{ route('galeria.paciente', $pacienteId) }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">{{ $nombrePaciente }}</a>
  @else
    <span style="color:var(--txt-soft);font-size:13px">{{ $nombrePaciente }}</span>
  @endif
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600">{{ $tituloVideo }}</span>
@endsection

@push('styles')
<style>
.ev-wrap{display:grid;grid-template-columns:minmax(0,1fr) 380px;gap:18px;align-items:start}
.ev-topbar{display:flex;align-items:center;justify-content:flex-end;gap:8px;margin-bottom:14px;flex-wrap:wrap}
.ev-btn{height:38px;display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:0 16px;border-radius:var(--r-md);font:inherit;font-size:13px;font-weight:700;text-decoration:none;transition:background-color 150ms ease,transform 160ms var(--ease-out),border-color 150ms ease}
.ev-btn:active{transform:scale(.97)}
.ev-btn.save{background:var(--blue);border:1px solid var(--blue);color:#fff}
.ev-btn.cancel{background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
.ev-btn.close{width:38px;padding:0;background:rgba(255,90,110,.12);border:1px solid rgba(255,90,110,.35);color:var(--red)}
.ev-status{min-height:24px;font-size:12px;font-weight:700;color:var(--txt-soft);margin-right:auto}
.ev-status.ok{color:var(--green)}.ev-status.err{color:var(--red)}
@media(hover:hover)and(pointer:fine){.ev-btn.save:hover{opacity:.9}.ev-btn.cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}.ev-btn.close:hover{background:rgba(255,90,110,.22)}}
.ev-player-box{position:relative;aspect-ratio:16/9;background:#000;border-radius:14px;overflow:hidden;border:1px solid rgba(255,255,255,.06)}
.ev-video{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;background:#000;z-index:1}
.ev-draw{position:absolute;inset:0;z-index:2;pointer-events:none}
.ev-player-box.drawing .ev-draw{pointer-events:auto;cursor:crosshair}
.ev-player-icon{position:absolute;inset:0;z-index:3;display:flex;align-items:center;justify-content:center;pointer-events:none}
.ev-play-big{width:58px;height:58px;border-radius:50%;background:rgba(255,255,255,.18);backdrop-filter:blur(8px);display:grid;place-items:center;cursor:pointer;pointer-events:auto;transition:background-color 150ms ease,transform 150ms ease}
.ev-play-big:hover{background:rgba(46,123,246,.6);transform:scale(1.07)}
.ev-play-big .pause-icon{display:none}
.ev-controls{position:absolute;left:0;right:0;bottom:0;z-index:4;padding:30px 14px 12px;background:linear-gradient(0deg,rgba(0,0,0,.82) 0%,transparent 100%)}
.ev-prog-wrap{position:relative;height:4px;background:rgba(255,255,255,.22);border-radius:4px;margin-bottom:9px;cursor:pointer}
.ev-prog-fill{height:100%;width:0;background:var(--blue);border-radius:4px}
.ev-prog-thumb{position:absolute;top:50%;left:0;translate:0 -50%;width:11px;height:11px;border-radius:50%;background:#fff;margin-left:-5px}
.ev-ctrl-row{display:flex;align-items:center;gap:6px}
.ev-ctrl-btn{width:30px;height:30px;border-radius:7px;display:grid;place-items:center;color:rgba(255,255,255,.86);flex:none}
.ev-time{font-size:11.5px;color:rgba(255,255,255,.72);white-space:nowrap}
.ev-vol-wrap{display:flex;align-items:center;gap:5px;margin-left:auto}
.ev-vol-bar{width:62px;height:4px;background:rgba(255,255,255,.24);border-radius:4px;cursor:pointer}
.ev-vol-fill{height:100%;background:rgba(255,255,255,.75);border-radius:4px;width:100%}
.ev-speed{font-size:11.5px;font-weight:800;color:rgba(255,255,255,.86);padding:2px 7px;border-radius:6px;border:1px solid rgba(255,255,255,.22)}
.ev-actions{display:flex;align-items:center;gap:7px;flex-wrap:wrap;padding:10px 0;border-bottom:1px solid var(--stroke);margin-bottom:12px}
.ev-act-btn{height:34px;display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:0 12px;border-radius:var(--r-md);font:inherit;font-size:12px;font-weight:700;text-decoration:none;background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);white-space:nowrap;cursor:pointer}
.ev-act-btn.email{color:var(--blue);border-color:rgba(46,123,246,.35);background:rgba(46,123,246,.08)}
.ev-act-btn.ia{color:var(--cyan);border-color:rgba(56,199,244,.32);background:rgba(56,199,244,.07)}
@media(hover:hover)and(pointer:fine){.ev-act-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.4);color:var(--blue)}}
.ev-caps-title{font-size:13px;font-weight:700;margin-bottom:8px}
.ev-caps-strip{display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;scrollbar-width:thin;scrollbar-color:var(--stroke) transparent}
.ev-cap-item{flex:none;width:94px;border-radius:8px;overflow:hidden;border:2px solid transparent;text-decoration:none;color:inherit}
.ev-cap-item.sel{border-color:var(--blue)}
.ev-cap-thumb{position:relative;width:100%;aspect-ratio:4/3;background:radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%)}
.ev-cap-thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.ev-cap-num{position:absolute;top:4px;left:5px;width:18px;height:18px;border-radius:6px;background:rgba(0,0,0,.65);display:grid;place-items:center;font-size:9px;font-weight:800;color:#fff}
.ev-cap-ts{font-size:10px;color:var(--txt-soft);text-align:center;padding:4px 0 2px}
.ev-empty-caps{padding:15px;border:1px dashed var(--stroke);border-radius:var(--r-md);color:var(--txt-soft);font-size:12.5px}
.ev-panel{display:flex;flex-direction:column;gap:14px}
.ev-section{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:16px}
.ev-sec-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.ev-sec-title{font-family:'Sora',sans-serif;font-size:13px;font-weight:800}
.ev-tools-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.ev-tool-btn{min-height:40px;display:flex;align-items:center;justify-content:flex-start;gap:9px;padding:0 12px;border-radius:var(--r-md);font:inherit;font-size:12.5px;font-weight:700;color:var(--txt);background:var(--card);border:1px solid var(--stroke);text-align:left}
.ev-tool-btn.on{background:rgba(46,123,246,.15);border-color:rgba(46,123,246,.5);color:var(--blue)}
.ev-trim{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:10px}
.ev-trim span{display:block;font-size:11px;color:var(--txt-soft);margin-bottom:4px}
.ev-trim strong{display:block;font-size:12px;color:var(--txt)}
.ev-adj-row{display:grid;grid-template-columns:78px 1fr 44px;align-items:center;gap:9px;margin-bottom:10px}
.ev-adj-row:last-child{margin-bottom:0}
.ev-adj-lbl{font-size:12.5px;color:var(--txt-soft)}
.ev-adj-val{font-size:12px;font-weight:800;text-align:right}
.ev-slider{width:100%;height:4px;border-radius:4px;appearance:none;-webkit-appearance:none;background:linear-gradient(to right,var(--blue) 50%,rgba(255,255,255,.15) 50%);outline:none}
.ev-slider::-webkit-slider-thumb{appearance:none;width:13px;height:13px;border-radius:50%;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.4)}
.ev-zoom-row{display:flex;align-items:center;gap:8px;margin-top:6px}
.ev-zoom-btn{width:28px;height:28px;border-radius:7px;border:1px solid var(--stroke);display:grid;place-items:center;font-size:16px;font-weight:800;color:var(--txt)}
.ev-zoom-val{font-size:13px;font-weight:800;min-width:44px;text-align:center}
.ev-field{margin-bottom:12px}.ev-field:last-child{margin-bottom:0}
.ev-label{font-size:11.5px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:6px}
.ev-input,.ev-textarea{width:100%;background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-md);font:inherit;font-size:13px;color:var(--txt);outline:none;transition:border-color 150ms ease}
.ev-input{height:38px;padding:0 12px}
.ev-textarea{min-height:64px;padding:10px 12px;resize:vertical;line-height:1.5}
.ev-input:focus,.ev-textarea:focus{border-color:var(--blue)}
.ev-color-row,.ev-stroke-row{display:flex;align-items:center;gap:8px;margin-top:10px}
.ev-color-dot{width:23px;height:23px;border-radius:50%;border:2px solid transparent}
.ev-color-dot.sel{border-color:#fff}
.ev-stroke-opt{width:32px;border-radius:999px;background:var(--txt);opacity:.75}
.ev-stroke-opt.s1{height:2px}.ev-stroke-opt.s2{height:4px}.ev-stroke-opt.s3{height:7px}.ev-stroke-opt.sel{background:var(--blue);opacity:1}
@media(max-width:1100px){.ev-wrap{grid-template-columns:1fr}.ev-panel{max-width:none}}
@media(max-width:720px){.ev-tools-grid,.ev-trim{grid-template-columns:1fr}.ev-adj-row{grid-template-columns:1fr}.ev-adj-val{text-align:left}.ev-vol-wrap{display:none}}
</style>
@endpush

@section('content')
<div class="rise d2">
  <div class="ev-topbar">
    <div class="ev-status" id="evStatus"></div>
    <button class="ev-btn save" id="evSave" type="button">Guardar cambios</button>
    <a href="{{ route('galeria.video', ['id' => $archivo->id, 'paciente' => $pacienteId]) }}" class="ev-btn cancel">Cancelar</a>
    <a href="{{ $pacienteId ? route('galeria.paciente', $pacienteId) : route('galeria') }}" class="ev-btn close" aria-label="Cerrar editor">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </a>
  </div>

  <div class="ev-wrap">
    <div>
      <div class="ev-player-box" id="evPlayer">
        <video id="evVideoEl" class="ev-video" src="{{ $videoUrl }}" preload="metadata" playsinline></video>
        <canvas id="evDrawCanvas" class="ev-draw"></canvas>
        <div class="ev-player-icon" id="evCenter">
          <div class="ev-play-big" id="evPlayBig">
            <svg class="play-icon" width="21" height="21" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <svg class="pause-icon" width="21" height="21" viewBox="0 0 24 24" fill="white"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
          </div>
        </div>
        <div class="ev-controls">
          <div class="ev-prog-wrap" id="evProgWrap">
            <div class="ev-prog-fill" id="evProgFill"></div>
            <div class="ev-prog-thumb" id="evProgThumb"></div>
          </div>
          <div class="ev-ctrl-row">
            <button class="ev-ctrl-btn" id="evStartBtn" type="button"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polygon points="19 20 9 12 19 4 19 20"/><line x1="5" y1="19" x2="5" y2="5"/></svg></button>
            <button class="ev-ctrl-btn" id="evRewindBtn" type="button"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg></button>
            <button class="ev-ctrl-btn" id="evPlayBtn" type="button">
              <svg class="play-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              <svg class="pause-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            </button>
            <button class="ev-ctrl-btn" id="evForwardBtn" type="button"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-4.95"/></svg></button>
            <span class="ev-time" id="evTime">00:00 / 00:00</span>
            <div class="ev-vol-wrap">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
              <div class="ev-vol-bar" id="evVolBar"><div class="ev-vol-fill" id="evVolFill"></div></div>
            </div>
            <button class="ev-speed" id="evSpeed" type="button">1.0x</button>
            <button class="ev-ctrl-btn" id="evFsBtn" type="button"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg></button>
          </div>
        </div>
      </div>

      <div class="ev-actions">
        <button class="ev-act-btn" id="evCaptureFrame" type="button"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>Capturar imagen</button>
        <a class="ev-act-btn" href="{{ $videoDownloadUrl }}" download="{{ $downloadName }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>Exportar video</a>
        <button class="ev-act-btn" id="evPrint" type="button"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>Imprimir informe</button>
        <button class="ev-act-btn email" type="button" data-gallery-email-open><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/></svg>Enviar por correo</button>
        <a class="ev-act-btn ia" href="{{ route('ia-reportes.generar', ['estudio' => $estudio?->id, 'paciente' => $pacienteId]) }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>IA Reportes</a>
      </div>

      <div>
        <div class="ev-caps-title">Imágenes capturadas del estudio</div>
        <div class="ev-caps-strip" id="evCapsStrip">
          @forelse($capturas as $i => $cap)
            <a class="ev-cap-item {{ $i === 0 ? 'sel' : '' }}" href="{{ route('galeria.imagen', ['id' => $cap->id, 'paciente' => $pacienteId]) }}">
              <div class="ev-cap-thumb">
                <img src="{{ media_url($cap->path) }}" alt="{{ $cap->nombre_original ?? 'Captura' }}">
                <span class="ev-cap-num">{{ $i + 1 }}</span>
              </div>
              <div class="ev-cap-ts">{{ format_user_time_with_seconds($cap->capturado_en) }}</div>
            </a>
          @empty
            <div class="ev-empty-caps" id="evEmptyCaps">Sin imágenes capturadas para este estudio.</div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="ev-panel">
      <div class="ev-section">
        <div class="ev-sec-head"><span class="ev-sec-title">Edición de video</span></div>
        <div class="ev-tools-grid">
          <button class="ev-tool-btn" id="evTrimStart" type="button">Marcar inicio</button>
          <button class="ev-tool-btn" id="evTrimEnd" type="button">Marcar fin</button>
          <button class="ev-tool-btn" id="evRotate" type="button">Rotar</button>
          <button class="ev-tool-btn" id="evFlipH" type="button">Voltear horizontal</button>
          <button class="ev-tool-btn" id="evFlipV" type="button">Voltear vertical</button>
          <button class="ev-tool-btn" id="evReset" type="button">Restablecer</button>
        </div>
        <div class="ev-trim">
          <div><span>Inicio</span><strong id="evTrimStartVal">Sin marca</strong></div>
          <div><span>Fin</span><strong id="evTrimEndVal">Sin marca</strong></div>
        </div>
      </div>

      <div class="ev-section">
        <div class="ev-sec-head"><span class="ev-sec-title">Ajuste de video</span></div>
        <div class="ev-adj-row"><span class="ev-adj-lbl">Brillo</span><input type="range" class="ev-slider" min="0" max="200" value="{{ $editorConfig['brillo'] }}" id="slBrillo"><span class="ev-adj-val" id="slBrilloVal">{{ $editorConfig['brillo'] }}%</span></div>
        <div class="ev-adj-row"><span class="ev-adj-lbl">Contraste</span><input type="range" class="ev-slider" min="0" max="200" value="{{ $editorConfig['contraste'] }}" id="slContraste"><span class="ev-adj-val" id="slContrasteVal">{{ $editorConfig['contraste'] }}%</span></div>
        <div class="ev-adj-row"><span class="ev-adj-lbl">Saturación</span><input type="range" class="ev-slider" min="0" max="200" value="{{ $editorConfig['saturacion'] }}" id="slSaturacion"><span class="ev-adj-val" id="slSaturacionVal">{{ $editorConfig['saturacion'] }}%</span></div>
        <div class="ev-adj-row"><span class="ev-adj-lbl">Nitidez</span><input type="range" class="ev-slider" min="0" max="100" value="{{ $editorConfig['nitidez'] }}" id="slNitidez"><span class="ev-adj-val" id="slNitidezVal">{{ $editorConfig['nitidez'] }}%</span></div>
        <div class="ev-zoom-row">
          <span class="ev-adj-lbl">Zoom</span>
          <button class="ev-zoom-btn" id="evZoomMinus" type="button">−</button>
          <span class="ev-zoom-val" id="evZoomVal">{{ $editorConfig['zoom'] }}%</span>
          <button class="ev-zoom-btn" id="evZoomPlus" type="button">+</button>
        </div>
      </div>

      <div class="ev-section">
        <div class="ev-sec-head"><span class="ev-sec-title">Anotaciones</span></div>
        <div class="ev-tools-grid">
          <button class="ev-tool-btn" id="evDrawToggle" type="button">Dibujar</button>
          <button class="ev-tool-btn" id="evClearDraw" type="button">Limpiar</button>
        </div>
        <div class="ev-color-row">
          <span class="ev-adj-lbl">Color</span>
          <button class="ev-color-dot sel" type="button" style="background:#FF5A6E" data-color="#FF5A6E"></button>
          <button class="ev-color-dot" type="button" style="background:#F59E2D" data-color="#F59E2D"></button>
          <button class="ev-color-dot" type="button" style="background:#3DDC97" data-color="#3DDC97"></button>
          <button class="ev-color-dot" type="button" style="background:#2E7BF6" data-color="#2E7BF6"></button>
        </div>
        <div class="ev-stroke-row">
          <span class="ev-adj-lbl">Grosor</span>
          <button class="ev-stroke-opt s1 sel" type="button" data-size="2"></button>
          <button class="ev-stroke-opt s2" type="button" data-size="5"></button>
          <button class="ev-stroke-opt s3" type="button" data-size="9"></button>
        </div>
      </div>

      <div class="ev-section">
        <div class="ev-sec-head"><span class="ev-sec-title">Información clínica</span></div>
        <div class="ev-field">
          <div class="ev-label">Nombre del video</div>
          <input class="ev-input" id="evNombre" value="{{ $tituloVideo }}">
        </div>
        <div class="ev-field">
          <div class="ev-label">Hallazgos</div>
          <textarea class="ev-textarea" id="evHallazgos" rows="3">{{ $estudio?->descripcion }}</textarea>
        </div>
        <div class="ev-field">
          <div class="ev-label">Observaciones</div>
          <textarea class="ev-textarea" id="evObservaciones" rows="3">{{ $estudio?->observaciones }}</textarea>
        </div>
        <div class="ev-field">
          <div class="ev-label">Diagnóstico</div>
          <textarea class="ev-textarea" id="evDiagnostico" rows="2">{{ $estudio?->diagnostico }}</textarea>
        </div>
        <div class="ev-field">
          <div class="ev-label">Notas del archivo</div>
          <textarea class="ev-textarea" id="evDescripcion" rows="2">{{ $archivo->descripcion }}</textarea>
        </div>
      </div>
    </div>
  </div>
</div>
@include('galeria._video_email_modal')
@endsection

@push('scripts')
<script>
(function(){
  const video = document.getElementById('evVideoEl');
  const player = document.getElementById('evPlayer');
  const drawCanvas = document.getElementById('evDrawCanvas');
  const drawCtx = drawCanvas.getContext('2d');
  const csrfToken = @json(csrf_token());
  const updateUrl = @json(route('galeria.video.update', $archivo->id));
  const captureUrl = @json(route('galeria.video.capture', $archivo->id));
  const showUrl = @json(route('galeria.video', ['id' => $archivo->id, 'paciente' => $pacienteId]));
  const settings = {
    brillo: Number(@json($editorConfig['brillo'])),
    contraste: Number(@json($editorConfig['contraste'])),
    saturacion: Number(@json($editorConfig['saturacion'])),
    nitidez: Number(@json($editorConfig['nitidez'])),
    zoom: Number(@json($editorConfig['zoom'])),
    rotacion: Number(@json($editorConfig['rotacion'])),
    flip_h: Boolean(@json($editorConfig['flip_h'])),
    flip_v: Boolean(@json($editorConfig['flip_v'])),
    trim_start: @json($editorConfig['trim_start']),
    trim_end: @json($editorConfig['trim_end'])
  };
  const speeds = [0.5, 0.75, 1, 1.25, 1.5, 2];
  let speedIndex = 2;
  let draggingProgress = false;
  let drawing = false;
  let drawingMode = false;
  let drawColor = '#FF5A6E';
  let drawSize = 2;

  function two(n){ return String(Math.floor(n)).padStart(2, '0'); }
  function formatTime(seconds){
    if(!Number.isFinite(seconds) || seconds < 0) return '00:00';
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = Math.floor(seconds % 60);
    return h > 0 ? `${two(h)}:${two(m)}:${two(s)}` : `${two(m)}:${two(s)}`;
  }
  function status(text, type){
    const el = document.getElementById('evStatus');
    el.textContent = text || '';
    el.className = 'ev-status' + (type ? ' ' + type : '');
  }
  function transformValue(){
    const sx = settings.flip_h ? -1 : 1;
    const sy = settings.flip_v ? -1 : 1;
    return `scale(${settings.zoom / 100}) rotate(${settings.rotacion}deg) scaleX(${sx}) scaleY(${sy})`;
  }
  function applyVideoStyle(){
    const sharpenContrast = settings.contraste + Math.round(settings.nitidez * 0.18);
    video.style.filter = `brightness(${settings.brillo}%) contrast(${sharpenContrast}%) saturate(${settings.saturacion}%)`;
    video.style.transform = transformValue();
    ['Brillo','Contraste','Saturacion','Nitidez'].forEach(name => {
      const id = 'sl' + name;
      const input = document.getElementById(id);
      const val = document.getElementById(id + 'Val');
      if(input && val){
        const pct = input.value + '%';
        input.style.background = `linear-gradient(to right,var(--blue) ${pct},rgba(255,255,255,.15) ${pct})`;
        val.textContent = pct;
      }
    });
    document.getElementById('evZoomVal').textContent = settings.zoom + '%';
    document.getElementById('evFlipH').classList.toggle('on', settings.flip_h);
    document.getElementById('evFlipV').classList.toggle('on', settings.flip_v);
    document.getElementById('evTrimStartVal').textContent = settings.trim_start === null || settings.trim_start === '' ? 'Sin marca' : formatTime(Number(settings.trim_start));
    document.getElementById('evTrimEndVal').textContent = settings.trim_end === null || settings.trim_end === '' ? 'Sin marca' : formatTime(Number(settings.trim_end));
  }
  function syncIcons(){
    const playing = !video.paused;
    [document.getElementById('evPlayBig'), document.getElementById('evPlayBtn')].forEach(btn => {
      const play = btn.querySelector('.play-icon');
      const pause = btn.querySelector('.pause-icon');
      play.style.display = playing ? 'none' : '';
      pause.style.display = playing ? '' : 'none';
    });
    document.getElementById('evCenter').style.display = playing ? 'none' : 'flex';
  }
  function updateProgress(){
    const duration = video.duration || 0;
    const pct = duration ? Math.max(0, Math.min(100, (video.currentTime / duration) * 100)) : 0;
    document.getElementById('evProgFill').style.width = pct + '%';
    document.getElementById('evProgThumb').style.left = pct + '%';
    document.getElementById('evTime').textContent = `${formatTime(video.currentTime)} / ${formatTime(duration)}`;
    if(settings.trim_end !== null && settings.trim_end !== '' && video.currentTime >= Number(settings.trim_end)){
      video.pause();
      if(settings.trim_start !== null && settings.trim_start !== '') video.currentTime = Number(settings.trim_start);
    }
  }
  function seekFromEvent(event){
    if(!video.duration) return;
    const rect = document.getElementById('evProgWrap').getBoundingClientRect();
    const x = Math.max(0, Math.min(rect.width, event.clientX - rect.left));
    video.currentTime = (x / rect.width) * video.duration;
    updateProgress();
  }
  function resizeDrawCanvas(){
    const rect = player.getBoundingClientRect();
    const snapshot = drawCanvas.width && drawCanvas.height ? drawCtx.getImageData(0, 0, drawCanvas.width, drawCanvas.height) : null;
    drawCanvas.width = Math.max(1, Math.round(rect.width));
    drawCanvas.height = Math.max(1, Math.round(rect.height));
    if(snapshot){
      const tmp = document.createElement('canvas');
      tmp.width = snapshot.width; tmp.height = snapshot.height;
      tmp.getContext('2d').putImageData(snapshot, 0, 0);
      drawCtx.drawImage(tmp, 0, 0, drawCanvas.width, drawCanvas.height);
    }
  }
  function drawPos(event){
    const rect = drawCanvas.getBoundingClientRect();
    const point = event.touches ? event.touches[0] : event;
    return { x: point.clientX - rect.left, y: point.clientY - rect.top };
  }
  function startDraw(event){
    if(!drawingMode) return;
    event.preventDefault();
    drawing = true;
    const pos = drawPos(event);
    drawCtx.beginPath();
    drawCtx.moveTo(pos.x, pos.y);
  }
  function moveDraw(event){
    if(!drawing || !drawingMode) return;
    event.preventDefault();
    const pos = drawPos(event);
    drawCtx.lineCap = 'round';
    drawCtx.lineJoin = 'round';
    drawCtx.lineWidth = drawSize;
    drawCtx.strokeStyle = drawColor;
    drawCtx.lineTo(pos.x, pos.y);
    drawCtx.stroke();
  }
  function stopDraw(){
    if(!drawing) return;
    drawing = false;
    drawCtx.closePath();
  }
  async function criticalToken(message){
    if(window.CriticalSecurity?.authorize){
      return await window.CriticalSecurity.authorize('studies', message);
    }
    return '';
  }
  async function saveChanges(){
    status('Guardando...', '');
    const token = await criticalToken('Confirma tu contraseña para guardar cambios en este video.');
    if(token === null){ status('', ''); return; }
    const headers = {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrfToken};
    if(token) headers['X-Critical-Authorization'] = token;
    const payload = {
      nombre: document.getElementById('evNombre').value,
      descripcion: document.getElementById('evDescripcion').value,
      hallazgos: document.getElementById('evHallazgos').value,
      observaciones: document.getElementById('evObservaciones').value,
      diagnostico: document.getElementById('evDiagnostico').value,
      ajustes: settings
    };
    const response = await fetch(updateUrl, { method:'PATCH', headers, body: JSON.stringify(payload) });
    const data = await response.json().catch(() => ({}));
    if(!response.ok || !data.ok) throw new Error(data.message || 'No se pudieron guardar los cambios.');
    status('Cambios guardados', 'ok');
    setTimeout(() => { window.location.href = data.redirect || showUrl; }, 650);
  }
  function drawEditedFrame(){
    const w = video.videoWidth || 1280;
    const h = video.videoHeight || 720;
    const out = document.createElement('canvas');
    out.width = w; out.height = h;
    const ctx = out.getContext('2d');
    ctx.fillStyle = '#000';
    ctx.fillRect(0, 0, w, h);
    const sharpenContrast = settings.contraste + Math.round(settings.nitidez * 0.18);
    ctx.filter = `brightness(${settings.brillo}%) contrast(${sharpenContrast}%) saturate(${settings.saturacion}%)`;
    ctx.save();
    ctx.translate(w / 2, h / 2);
    ctx.scale(settings.flip_h ? -1 : 1, settings.flip_v ? -1 : 1);
    ctx.rotate((settings.rotacion || 0) * Math.PI / 180);
    ctx.scale(settings.zoom / 100, settings.zoom / 100);
    ctx.drawImage(video, -w / 2, -h / 2, w, h);
    ctx.restore();
    ctx.filter = 'none';
    ctx.drawImage(drawCanvas, 0, 0, w, h);
    return out;
  }
  async function captureFrame(){
    if(!video.videoWidth) throw new Error('El video todavía no está listo para capturar.');
    status('Guardando imagen...', '');
    const token = await criticalToken('Confirma tu contraseña para guardar una captura de este video.');
    if(token === null){ status('', ''); return; }
    const blob = await new Promise(resolve => drawEditedFrame().toBlob(resolve, 'image/jpeg', 0.86));
    const form = new FormData();
    form.append('image', blob, `fotograma_video_{{ $archivo->id }}_${Date.now()}.jpg`);
    form.append('capturado_en_video', String(video.currentTime || 0));
    const headers = {'Accept':'application/json','X-CSRF-TOKEN':csrfToken};
    if(token) headers['X-Critical-Authorization'] = token;
    const response = await fetch(captureUrl, { method:'POST', headers, body: form });
    const data = await response.json().catch(() => ({}));
    if(!response.ok || !data.ok) throw new Error(data.message || 'No se pudo guardar el fotograma.');
    const empty = document.getElementById('evEmptyCaps');
    if(empty) empty.remove();
    const strip = document.getElementById('evCapsStrip');
    const count = strip.querySelectorAll('.ev-cap-item').length + 1;
    const item = document.createElement('a');
    item.className = 'ev-cap-item sel';
    item.href = data.archivo.show_url;
    item.innerHTML = `<div class="ev-cap-thumb"><img src="${data.archivo.url}" alt="Fotograma guardado"><span class="ev-cap-num">${count}</span></div><div class="ev-cap-ts">${formatTime(video.currentTime)}</div>`;
    strip.querySelectorAll('.ev-cap-item').forEach(el => el.classList.remove('sel'));
    strip.appendChild(item);
    status('Fotograma guardado', 'ok');
  }

  document.getElementById('evPlayBig').addEventListener('click', () => video.paused ? video.play() : video.pause());
  document.getElementById('evPlayBtn').addEventListener('click', () => video.paused ? video.play() : video.pause());
  video.addEventListener('click', () => { if(!drawingMode) video.paused ? video.play() : video.pause(); });
  video.addEventListener('play', syncIcons);
  video.addEventListener('pause', syncIcons);
  video.addEventListener('loadedmetadata', () => {
    if(settings.trim_start !== null && settings.trim_start !== '') video.currentTime = Number(settings.trim_start);
    updateProgress();
  });
  video.addEventListener('timeupdate', updateProgress);
  video.addEventListener('ended', syncIcons);
  document.getElementById('evStartBtn').addEventListener('click', () => { video.currentTime = Number(settings.trim_start || 0); updateProgress(); });
  document.getElementById('evRewindBtn').addEventListener('click', () => { video.currentTime = Math.max(0, video.currentTime - 10); updateProgress(); });
  document.getElementById('evForwardBtn').addEventListener('click', () => { video.currentTime = Math.min(video.duration || 0, video.currentTime + 10); updateProgress(); });
  document.getElementById('evProgWrap').addEventListener('mousedown', e => { draggingProgress = true; seekFromEvent(e); });
  document.addEventListener('mousemove', e => { if(draggingProgress) seekFromEvent(e); });
  document.addEventListener('mouseup', () => draggingProgress = false);
  document.getElementById('evVolBar').addEventListener('click', e => {
    const rect = e.currentTarget.getBoundingClientRect();
    const volume = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
    video.volume = volume;
    document.getElementById('evVolFill').style.width = Math.round(volume * 100) + '%';
  });
  document.getElementById('evSpeed').addEventListener('click', function(){
    speedIndex = (speedIndex + 1) % speeds.length;
    video.playbackRate = speeds[speedIndex];
    this.textContent = (speeds[speedIndex] === 1 ? '1.0' : String(speeds[speedIndex])) + 'x';
  });
  document.getElementById('evFsBtn').addEventListener('click', () => { if(player.requestFullscreen) player.requestFullscreen(); });
  document.getElementById('evTrimStart').addEventListener('click', () => { settings.trim_start = Math.floor(video.currentTime || 0); applyVideoStyle(); });
  document.getElementById('evTrimEnd').addEventListener('click', () => { settings.trim_end = Math.floor(video.currentTime || 0); applyVideoStyle(); });
  document.getElementById('evRotate').addEventListener('click', () => { settings.rotacion = (settings.rotacion + 90) % 360; applyVideoStyle(); });
  document.getElementById('evFlipH').addEventListener('click', () => { settings.flip_h = !settings.flip_h; applyVideoStyle(); });
  document.getElementById('evFlipV').addEventListener('click', () => { settings.flip_v = !settings.flip_v; applyVideoStyle(); });
  document.getElementById('evReset').addEventListener('click', () => {
    Object.assign(settings, {brillo:100, contraste:100, saturacion:100, nitidez:0, zoom:100, rotacion:0, flip_h:false, flip_v:false, trim_start:null, trim_end:null});
    document.getElementById('slBrillo').value = 100;
    document.getElementById('slContraste').value = 100;
    document.getElementById('slSaturacion').value = 100;
    document.getElementById('slNitidez').value = 0;
    drawCtx.clearRect(0, 0, drawCanvas.width, drawCanvas.height);
    applyVideoStyle();
  });
  [['slBrillo','brillo'],['slContraste','contraste'],['slSaturacion','saturacion'],['slNitidez','nitidez']].forEach(([id,key]) => {
    document.getElementById(id).addEventListener('input', function(){
      settings[key] = Number(this.value);
      applyVideoStyle();
    });
  });
  document.getElementById('evZoomPlus').addEventListener('click', () => { settings.zoom = Math.min(250, settings.zoom + 10); applyVideoStyle(); });
  document.getElementById('evZoomMinus').addEventListener('click', () => { settings.zoom = Math.max(50, settings.zoom - 10); applyVideoStyle(); });
  document.getElementById('evDrawToggle').addEventListener('click', function(){
    drawingMode = !drawingMode;
    player.classList.toggle('drawing', drawingMode);
    this.classList.toggle('on', drawingMode);
  });
  document.getElementById('evClearDraw').addEventListener('click', () => drawCtx.clearRect(0, 0, drawCanvas.width, drawCanvas.height));
  document.querySelectorAll('.ev-color-dot').forEach(dot => dot.addEventListener('click', function(){
    document.querySelectorAll('.ev-color-dot').forEach(d => d.classList.remove('sel'));
    this.classList.add('sel');
    drawColor = this.dataset.color;
  }));
  document.querySelectorAll('.ev-stroke-opt').forEach(opt => opt.addEventListener('click', function(){
    document.querySelectorAll('.ev-stroke-opt').forEach(o => o.classList.remove('sel'));
    this.classList.add('sel');
    drawSize = Number(this.dataset.size);
  }));
  drawCanvas.addEventListener('mousedown', startDraw);
  drawCanvas.addEventListener('mousemove', moveDraw);
  drawCanvas.addEventListener('mouseup', stopDraw);
  drawCanvas.addEventListener('mouseleave', stopDraw);
  drawCanvas.addEventListener('touchstart', startDraw, { passive:false });
  drawCanvas.addEventListener('touchmove', moveDraw, { passive:false });
  drawCanvas.addEventListener('touchend', stopDraw);
  window.addEventListener('resize', resizeDrawCanvas);
  document.getElementById('evSave').addEventListener('click', () => saveChanges().catch(error => status(error.message, 'err')));
  document.getElementById('evCaptureFrame').addEventListener('click', () => captureFrame().catch(error => status(error.message, 'err')));
  document.getElementById('evPrint').addEventListener('click', () => window.print());

  resizeDrawCanvas();
  applyVideoStyle();
  syncIcons();
})();
</script>
@endpush
