@extends('layouts.app')

@section('title', 'Editar Video')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')
@php
  $pacienteId = request('paciente', 1);
@endphp
@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <a href="{{ route('galeria.paciente', $pacienteId) }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Maria Gonzales</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600">Video EDD-2025-001245</span>
@endsection

@push('styles')
<style>
/* ===== EDITAR VIDEO ===== */
.ev-wrap{display:grid;grid-template-columns:1fr 380px;gap:18px;align-items:start}

/* Topbar */
.ev-topbar{display:flex;align-items:center;justify-content:flex-end;gap:8px;margin-bottom:14px}
.ev-btn{
  display:flex;align-items:center;gap:7px;
  height:38px;padding:0 16px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:600;
  transition:background-color 150ms ease,transform 160ms var(--ease-out);
}
.ev-btn:active{transform:scale(.97)}
.ev-btn.save{background:var(--blue);border:none;color:#fff}
@media(hover:hover)and(pointer:fine){.ev-btn.save:hover{opacity:.88}}
.ev-btn.cancel{background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.ev-btn.cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.ev-btn.more{width:38px;padding:0;justify-content:center;background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.ev-btn.more:hover{background:rgba(110,160,255,.08)}}
/* Player */
.ev-player-box{
  background:#000;border-radius:14px;overflow:hidden;
  position:relative;aspect-ratio:16/9;
}
.ev-player-bg{
  position:absolute;inset:0;
  background:radial-gradient(ellipse at 50% 50%,#5a1a10 0%,#2a0808 40%,#060810 100%);
}
.ev-player-icon{
  position:absolute;inset:0;z-index:2;
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;
}
.ev-play-big{
  width:52px;height:52px;border-radius:50%;
  background:rgba(255,255,255,.18);backdrop-filter:blur(8px);
  display:grid;place-items:center;cursor:pointer;
  transition:background-color 150ms ease,transform 150ms ease;
}
.ev-play-big:hover{background:rgba(46,123,246,.6);transform:scale(1.08)}
.ev-play-big svg.pause-icon{display:none}

/* Controles player */
.ev-controls{
  position:absolute;bottom:0;left:0;right:0;z-index:3;
  padding:28px 14px 12px;
  background:linear-gradient(0deg,rgba(0,0,0,.82) 0%,transparent 100%);
}
.ev-prog-wrap{position:relative;height:4px;background:rgba(255,255,255,.2);border-radius:4px;cursor:pointer;margin-bottom:9px}
.ev-prog-fill{height:100%;background:var(--blue);border-radius:4px;width:15%}
.ev-prog-thumb{
  position:absolute;top:50%;translate:0 -50%;
  width:11px;height:11px;border-radius:50%;background:#fff;
  left:15%;margin-left:-5px;
}
.ev-ctrl-row{display:flex;align-items:center;gap:6px}
.ev-ctrl-btn{
  width:30px;height:30px;border-radius:7px;display:grid;place-items:center;
  color:rgba(255,255,255,.8);flex:none;transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.ev-ctrl-btn:hover{background:rgba(255,255,255,.12)}}
.ev-time{font-size:11.5px;color:rgba(255,255,255,.6);flex:none;margin:0 3px}
.ev-vol-wrap{display:flex;align-items:center;gap:5px;margin-left:auto}
.ev-vol-bar{width:60px;height:4px;background:rgba(255,255,255,.2);border-radius:4px}
.ev-vol-fill{height:100%;background:rgba(255,255,255,.7);border-radius:4px;width:70%}
.ev-speed{font-size:11.5px;font-weight:700;color:rgba(255,255,255,.8);padding:2px 7px;border-radius:6px;border:1px solid rgba(255,255,255,.2);cursor:pointer}
.ev-fs{margin-left:4px}

/* Acciones */
.ev-actions{display:flex;align-items:center;gap:7px;flex-wrap:wrap;padding:10px 0;border-bottom:1px solid var(--stroke);margin-bottom:12px}
.ev-act-btn{
  display:flex;align-items:center;gap:5px;
  height:34px;padding:0 12px;border-radius:var(--r-md);
  font:inherit;font-size:12px;font-weight:600;
  background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease;white-space:nowrap;
}
@media(hover:hover)and(pointer:fine){.ev-act-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.4);color:var(--blue)}}
.ev-act-btn.on{background:rgba(46,123,246,.15);border-color:rgba(46,123,246,.5);color:var(--blue)}
.ev-act-btn.wa{color:var(--green);border-color:rgba(61,220,151,.3);background:rgba(61,220,151,.07)}
.ev-act-btn.ia{color:var(--cyan);border-color:rgba(56,199,244,.3);background:rgba(56,199,244,.07)}

/* Miniaturas */
.ev-caps-title{font-size:13px;font-weight:600;margin-bottom:8px}
.ev-caps-strip{display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;scrollbar-width:thin;scrollbar-color:var(--stroke) transparent}
.ev-cap-item{flex:none;width:90px;cursor:pointer;border-radius:7px;overflow:hidden;border:2px solid transparent;transition:border-color 150ms ease}
.ev-cap-item.sel{border-color:var(--blue)}
.ev-cap-thumb{width:100%;aspect-ratio:4/3;display:grid;place-items:center;position:relative}
.ev-cap-num{position:absolute;top:3px;left:4px;width:17px;height:17px;border-radius:5px;background:rgba(0,0,0,.6);display:grid;place-items:center;font-size:9px;font-weight:700;color:#fff}
.ev-cap-check{position:absolute;top:3px;right:3px;width:17px;height:17px;border-radius:50%;background:var(--blue);display:none;place-items:center}
.ev-cap-item.sel .ev-cap-check{display:grid}
.ev-cap-ts{font-size:9.5px;color:var(--txt-soft);text-align:center;padding:3px 0 1px}

/* Panel derecho */
.ev-panel{display:flex;flex-direction:column;gap:14px}

/* Sección */
.ev-section{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:16px}
.ev-sec-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.ev-sec-title{font-family:'Sora',sans-serif;font-size:13px;font-weight:700}
.ev-sec-more{color:var(--txt-soft);display:flex;gap:3px;font-size:16px;font-weight:900;letter-spacing:1px;cursor:pointer;padding:2px 4px;border-radius:6px;transition:background-color 150ms ease}
@media(hover:hover)and(pointer:fine){.ev-sec-more:hover{background:rgba(110,160,255,.1)}}

/* Herramientas edición video */
.ev-tools-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.ev-tool-btn{
  display:flex;align-items:center;gap:9px;
  height:40px;padding:0 14px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:600;color:var(--txt);
  background:var(--card);border:1px solid var(--stroke);
  transition:background-color 150ms ease,border-color 150ms ease,transform 160ms var(--ease-out);
}
.ev-tool-btn:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.ev-tool-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.4);color:var(--blue)}}

.ev-side-tool{display:none}
.ev-side-tool.open{display:block}
.ev-tool-form{display:flex;flex-direction:column;gap:12px}
.ev-panel-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.ev-panel-btn{
  display:flex;align-items:center;justify-content:center;gap:7px;
  height:38px;padding:0 12px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--card);
  font:inherit;font-size:13px;font-weight:700;color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease,color 150ms ease,transform 160ms var(--ease-out);
}
.ev-panel-btn:active{transform:scale(.97)}
.ev-panel-btn.active{background:rgba(46,123,246,.18);border-color:rgba(46,123,246,.55);color:var(--blue)}
.ev-panel-btn.danger{background:rgba(255,90,110,.14);border-color:rgba(255,90,110,.35);color:var(--red)}
@media(hover:hover)and(pointer:fine){
  .ev-panel-btn:hover{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.4);color:var(--blue)}
  .ev-panel-btn.danger:hover{background:rgba(255,90,110,.22);border-color:rgba(255,90,110,.5);color:var(--red)}
}
.ev-filter-label{font-size:11.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--txt-soft)}
.ev-slider-group{display:flex;flex-direction:column;gap:6px}
.ev-slider-row{display:flex;align-items:center;justify-content:space-between}
.ev-slider-name{font-size:12px;font-weight:700;color:var(--txt-soft)}
.ev-slider-val{font-size:12px;font-weight:800;color:var(--blue)}
.ev-slider{width:100%;height:4px;accent-color:var(--blue);cursor:pointer}

.ev-mode.hidden{display:none}
.ev-trim-head{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:18px}
.ev-trim-title{display:flex;align-items:center;gap:12px;font-family:'Sora',sans-serif;font-size:22px;font-weight:800}
.ev-trim-title span{color:var(--txt-soft);font-weight:600}
.ev-trim-actions{display:flex;align-items:center;gap:10px}
.ev-trim-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:42px;padding:0 18px;border-radius:10px;border:1px solid var(--stroke);background:transparent;color:var(--txt-soft);font:inherit;font-size:13px;font-weight:700;text-decoration:none;transition:background-color 150ms ease,color 150ms ease,border-color 150ms ease}
.ev-trim-btn.primary{background:var(--blue);border-color:var(--blue);color:#fff}
.ev-trim-btn:hover{background:rgba(46,123,246,.1);color:var(--txt);border-color:rgba(46,123,246,.45)}
.ev-trim-grid{display:grid;grid-template-columns:1fr 345px;gap:20px;align-items:start}
.ev-trim-main{display:flex;flex-direction:column;gap:16px;min-width:0}
.ev-trim-player{position:relative;aspect-ratio:16/9;border-radius:10px;overflow:hidden;background:#050505}
.ev-trim-media{position:absolute;inset:0;background:linear-gradient(90deg,rgba(30,4,8,.18),rgba(0,0,0,.06)),url('{{ asset('images/colonoscopia.jpg') }}') center/cover no-repeat;filter:saturate(1.08) contrast(1.04)}
.ev-trim-badge{position:absolute;top:16px;left:16px;z-index:2;background:rgba(0,0,0,.58);border-radius:8px;padding:10px 13px;color:#fff;font-size:12px;line-height:1.7}
.ev-trim-badge strong{display:block;font-size:12px}
.ev-trim-hd{position:absolute;top:16px;right:16px;z-index:2;background:rgba(0,0,0,.62);border-radius:8px;padding:10px 15px;text-align:center;font-size:12px;color:#fff}
.ev-trim-hd strong{display:block;font-size:14px}
.ev-trim-controls{position:absolute;left:0;right:0;bottom:0;z-index:3;padding:26px 20px 16px;background:linear-gradient(0deg,rgba(0,0,0,.78),transparent)}
.ev-trim-bar{height:5px;border-radius:6px;background:rgba(255,255,255,.25);position:relative;margin-bottom:13px}
.ev-trim-bar-fill{position:absolute;left:38%;width:3%;height:100%;background:var(--blue);border-radius:6px}
.ev-trim-row{display:flex;align-items:center;gap:14px;color:#fff}
.ev-trim-icon{width:28px;height:28px;border-radius:8px;display:grid;place-items:center;color:#fff}
.ev-trim-time{font-size:13px;color:rgba(255,255,255,.82)}
.ev-trim-speed{margin-left:auto;border:1px solid rgba(255,255,255,.25);border-radius:7px;padding:5px 10px;font-weight:800;font-size:12px}
.ev-timeline-panel{background:linear-gradient(180deg,rgba(15,35,74,.6),rgba(8,14,38,.9));border:1px solid var(--stroke);border-radius:12px;overflow:hidden}
.ev-timeline-title{padding:14px 16px;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px;font-weight:800}
.ev-film{position:relative;margin:58px 18px 20px;height:82px;border-radius:8px;background:rgba(255,255,255,.04);display:flex;align-items:center;overflow:hidden}
.ev-film-cell{height:64px;flex:1;background:url('{{ asset('images/colonoscopia.jpg') }}') center/cover no-repeat;opacity:.38;border-right:1px solid rgba(255,255,255,.04)}
.ev-film-cell.active{opacity:.9}
.ev-selection{position:absolute;left:29%;right:25%;top:8px;bottom:8px;border:2px solid var(--blue);box-shadow:0 0 0 999px rgba(0,0,0,.22);border-radius:4px}
.ev-handle{position:absolute;top:-10px;width:12px;height:92px;border-radius:8px;background:#fff;border:3px solid var(--blue)}
.ev-handle.left{left:29%;transform:translateX(-50%)}.ev-handle.right{right:25%;transform:translateX(50%)}
.ev-duration-pop{position:absolute;left:50%;top:-46px;translate:-50% 0;background:rgba(68,32,110,.75);border-radius:8px;padding:9px 22px;text-align:center;color:#d7a7ff;font-size:11px;line-height:1.5}
.ev-duration-pop strong{display:block;color:#e9d5ff;font-size:13px}
.ev-time-labels{display:flex;justify-content:space-between;padding:0 18px 18px;color:var(--txt-soft);font-size:12px}
.ev-quick{display:flex;align-items:center;gap:12px;flex-wrap:wrap;padding:0 18px 18px}
.ev-quick-title{width:100%;font-size:13px;font-weight:800}
.ev-quick button,.ev-quick .ev-current-time{height:40px;min-width:90px;border-radius:8px;border:1px solid var(--stroke);background:rgba(255,255,255,.03);color:var(--txt);font:inherit;font-size:13px}
.ev-quick .ev-current-time{display:grid;place-items:center;min-width:145px}
.ev-trim-side{display:flex;flex-direction:column;gap:16px}
.ev-trim-card{background:linear-gradient(180deg,rgba(18,32,65,.9),rgba(9,16,42,.95));border:1px solid var(--stroke);border-radius:12px;padding:18px}
.ev-trim-card-title{display:flex;align-items:center;gap:10px;font-size:16px;font-weight:800;margin-bottom:12px}
.ev-trim-card p{margin:0 0 20px;color:var(--txt-soft);font-size:13px;line-height:1.6}
.ev-time-field{margin-bottom:18px}
.ev-time-field label{display:block;font-size:13px;font-weight:800;margin-bottom:9px}
.ev-time-input-row{display:flex;align-items:center;gap:8px}
.ev-time-input{flex:1;height:46px;border-radius:8px;border:1px solid var(--stroke);background:rgba(4,10,28,.7);color:var(--txt);font:inherit;font-size:14px;font-weight:700;padding:0 16px}
.ev-clock-btn{width:46px;height:46px;border-radius:8px;border:1px solid var(--stroke);display:grid;place-items:center;color:var(--txt-soft)}
.ev-help{display:block;margin-top:6px;font-size:11px;color:var(--txt-soft)}
.ev-duration-card{display:flex;align-items:center;gap:14px;border:1px solid rgba(168,85,247,.45);border-radius:10px;background:rgba(88,28,135,.18);padding:16px;margin:8px 0 22px}
.ev-duration-card svg{color:#b85cff}.ev-duration-card span{font-size:12px;color:#d8b4fe;font-weight:800}.ev-duration-card strong{display:block;color:#d78aff;font-size:24px;margin:4px 0}.ev-duration-card small{color:var(--txt-soft)}
.ev-preview-title{font-size:13px;font-weight:800;margin-bottom:10px}
.ev-preview-box{border:1px solid var(--stroke);border-radius:8px;overflow:hidden;background:rgba(0,0,0,.25)}
.ev-preview-img{height:138px;background:url('{{ asset('images/colonoscopia.jpg') }}') center/cover no-repeat;display:grid;place-items:center}
.ev-preview-play{width:52px;height:52px;border-radius:50%;background:rgba(120,53,35,.72);display:grid;place-items:center;color:#fff}
.ev-preview-controls{padding:10px 12px;color:var(--txt-soft);font-size:12px}
.ev-success{display:flex;gap:12px;align-items:flex-start;border:1px solid rgba(61,220,151,.45);background:rgba(61,220,151,.08);border-radius:10px;padding:16px;color:var(--green)}
.ev-success strong{display:block;color:var(--green);margin-bottom:5px}.ev-success span{color:var(--txt-soft);font-size:12px;line-height:1.5}

@media(max-width:1100px){.ev-wrap{grid-template-columns:1fr}}
@media(max-width:1100px){.ev-trim-grid{grid-template-columns:1fr}.ev-trim-head{align-items:flex-start;flex-direction:column}.ev-trim-actions{width:100%;flex-wrap:wrap}}
</style>
@endpush

@section('content')

<div class="rise d2">

  <div class="ev-mode" id="evEditMode">
  {{-- Topbar --}}
  <div class="ev-topbar">
    <button class="ev-btn save" id="evSave">Guardar cambios</button>
    <a href="{{ route('galeria.video', ['id' => $id, 'paciente' => $pacienteId]) }}" class="ev-btn cancel">Cancelar</a>
    <button class="ev-btn more">···</button>
  </div>

  <div class="ev-wrap">

    {{-- ===== COLUMNA IZQUIERDA ===== --}}
    <div>

      {{-- Player --}}
      <div class="ev-player-box" id="evPlayer">
        <div class="ev-player-bg"></div>
        <div class="ev-player-icon" id="evCenter">
          <div class="ev-play-big" id="evPlayBig">
            <svg class="play-icon" width="20" height="20" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <svg class="pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="white"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
          </div>
        </div>
        <div class="ev-controls">
          <div class="ev-prog-wrap">
            <div class="ev-prog-fill"></div>
            <div class="ev-prog-thumb"></div>
          </div>
          <div class="ev-ctrl-row">
            <button class="ev-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polygon points="19 20 9 12 19 4 19 20"/><line x1="5" y1="19" x2="5" y2="5"/></svg></button>
            <button class="ev-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg></button>
            <button class="ev-ctrl-btn" id="evPlayBtn">
              <svg class="play-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              <svg class="pause-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            </button>
            <button class="ev-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-4.95"/></svg></button>
            <span class="ev-time" id="evTime">00:02:15 / 00:15:42</span>
            <div class="ev-vol-wrap">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
              <div class="ev-vol-bar"><div class="ev-vol-fill"></div></div>
            </div>
            <button class="ev-speed" id="evSpeed">1.0x</button>
            <button class="ev-ctrl-btn ev-fs"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg></button>
          </div>
        </div>
      </div>

      {{-- Acciones --}}
      <div class="ev-actions">
        <button class="ev-act-btn"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>Capturar imagen</button>
        <button class="ev-act-btn" id="evExportVideo"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>Exportar video</button>
        <button class="ev-act-btn" id="evToolFiltros"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>Filtros</button>
        <button class="ev-act-btn wa"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>Enviar por WhatsApp</button>
        <button class="ev-act-btn ia"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>IA Reportes</button>
      </div>

      {{-- Miniaturas --}}
      <div>
        <div class="ev-caps-title">Imágenes capturadas del estudio</div>
        <div class="ev-caps-strip">
          @php
          $caps=[['n'=>1,'ts'=>'0:01:25'],['n'=>2,'ts'=>'0:02:15'],['n'=>3,'ts'=>'0:04:32'],['n'=>4,'ts'=>'0:06:18'],['n'=>5,'ts'=>'0:08:47'],['n'=>6,'ts'=>'0:11:03']];
          $bgs=['radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%)','radial-gradient(ellipse at 40% 60%,#4a1a0a 0%,#0c0612 100%)','radial-gradient(ellipse at 60% 40%,#2a1a3a 0%,#060814 100%)','radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%)','radial-gradient(ellipse at 45% 55%,#1a0a2a 0%,#08060e 100%)','radial-gradient(ellipse at 55% 45%,#4a0a0a 0%,#0c0608 100%)'];
          @endphp
          @foreach($caps as $i => $c)
          <div class="ev-cap-item {{ $i===1 ? 'sel' : '' }}" data-ts="{{ $c['ts'] }}">
            <div class="ev-cap-thumb" style="background:{{ $bgs[$i] }}">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <span class="ev-cap-num">{{ $c['n'] }}</span>
              <span class="ev-cap-check"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            </div>
            <div class="ev-cap-ts">{{ $c['ts'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

    </div>

    {{-- ===== PANEL DERECHO ===== --}}
    <div class="ev-panel">

      <div class="ev-section ev-side-tool" id="evFiltersPanel" aria-hidden="true">
        <div class="ev-sec-head">
          <span class="ev-sec-title">Filtros de video</span>
          <span class="ev-sec-more">···</span>
        </div>
        <div class="ev-tool-form">
          <div class="ev-filter-label">Ajustes</div>
          <div class="ev-slider-group">
            <div class="ev-slider-row"><span class="ev-slider-name">Brillo</span><span class="ev-slider-val" id="evBrilloVal">100%</span></div>
            <input type="range" class="ev-slider" id="evBrillo" min="0" max="200" value="100">
          </div>
          <div class="ev-slider-group">
            <div class="ev-slider-row"><span class="ev-slider-name">Contraste</span><span class="ev-slider-val" id="evContrasteVal">100%</span></div>
            <input type="range" class="ev-slider" id="evContraste" min="0" max="200" value="100">
          </div>
          <div class="ev-slider-group">
            <div class="ev-slider-row"><span class="ev-slider-name">Saturación</span><span class="ev-slider-val" id="evSaturacionVal">100%</span></div>
            <input type="range" class="ev-slider" id="evSaturacion" min="0" max="200" value="100">
          </div>
          <div class="ev-panel-actions">
            <button type="button" class="ev-panel-btn active" id="evFilterApply">Aplicar filtros</button>
            <button type="button" class="ev-panel-btn" id="evFilterReset">Restablecer</button>
          </div>
        </div>
      </div>

      {{-- Edición de video --}}
      <div class="ev-section">
        <div class="ev-sec-head">
          <span class="ev-sec-title">Edición de video</span>
          <span class="ev-sec-more">···</span>
        </div>
        <div class="ev-tools-grid">
          <button class="ev-tool-btn" id="evOpenTrim"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3z"/><line x1="4" y1="20" x2="4.01" y2="20"/><line x1="16" y1="9" x2="16.01" y2="9"/></svg>Recortar</button>
          <button class="ev-tool-btn" id="evRestablecer"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg>Restablecer</button>
        </div>
      </div>

    </div>
  </div>
  </div>

  <div class="ev-mode hidden" id="evTrimMode">
    <div class="ev-trim-head">
      <div class="ev-trim-title">Edición de video <span>›</span> Recortar</div>
      <div class="ev-trim-actions">
        <button type="button" class="ev-trim-btn" id="evTrimCancel">Cancelar</button>
        <a href="{{ route('galeria.paciente', $pacienteId) }}" class="ev-trim-btn" id="evTrimBack">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          Volver a galería
        </a>
        <button type="button" class="ev-trim-btn primary" id="evTrimSave">Guardar recorte</button>
      </div>
    </div>

    <div class="ev-trim-grid">
      <div class="ev-trim-main">
        <div class="ev-trim-player">
          <div class="ev-trim-media"></div>
          <div class="ev-trim-badge"><strong>Video original</strong>00:00:00 / 00:15:42</div>
          <div class="ev-trim-hd"><strong>HD</strong>1080p</div>
          <div class="ev-trim-controls">
            <div class="ev-trim-bar"><div class="ev-trim-bar-fill"></div></div>
            <div class="ev-trim-row">
              <span class="ev-trim-icon">|‹</span>
              <span class="ev-trim-icon">▶</span>
              <span class="ev-trim-icon">›|</span>
              <span class="ev-trim-icon">▮))</span>
              <span class="ev-trim-time" id="evTrimPlayerTime">00:06:18 / 00:15:42</span>
              <span class="ev-trim-speed">1.0x</span>
              <span class="ev-trim-icon">↗</span>
            </div>
          </div>
        </div>

        <div class="ev-timeline-panel">
          <div class="ev-timeline-title">Selecciona el segmento que deseas conservar</div>
          <div class="ev-film">
            <div class="ev-duration-pop">Duración del recorte<strong id="evTrimDurationPop">00:00:24</strong></div>
            @for($i = 0; $i < 8; $i++)
              <div class="ev-film-cell {{ $i >= 2 && $i <= 4 ? 'active' : '' }}"></div>
            @endfor
            <div class="ev-selection"></div>
            <div class="ev-handle left"></div>
            <div class="ev-handle right"></div>
          </div>
          <div class="ev-time-labels">
            <span>00:00:00</span>
            <span id="evTrimStartTag">00:05:54</span>
            <span id="evTrimEndTag">00:06:18</span>
            <span>00:15:42</span>
          </div>
          <div class="ev-quick">
            <div class="ev-quick-title">Ajuste rápido</div>
            <button type="button" data-delta="-5">-5 seg</button>
            <button type="button" data-delta="-1">-1 seg</button>
            <div class="ev-current-time" id="evTrimCurrent">00:06:18</div>
            <button type="button" data-delta="1">+1 seg</button>
            <button type="button" data-delta="5">+5 seg</button>
          </div>
        </div>
      </div>

      <aside class="ev-trim-side">
        <div class="ev-trim-card">
          <div class="ev-trim-card-title">
            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/></svg>
            Recortar video
          </div>
          <p>Selecciona el inicio y fin del segmento que deseas guardar.</p>

          <div class="ev-time-field">
            <label for="evTrimStart">Tiempo de inicio</label>
            <div class="ev-time-input-row">
              <input type="text" class="ev-time-input" id="evTrimStart" value="00:05:54">
              <button type="button" class="ev-clock-btn">◴</button>
            </div>
            <span class="ev-help">hh:mm:ss</span>
          </div>

          <div class="ev-time-field">
            <label for="evTrimEnd">Tiempo de fin</label>
            <div class="ev-time-input-row">
              <input type="text" class="ev-time-input" id="evTrimEnd" value="00:06:18">
              <button type="button" class="ev-clock-btn">◴</button>
            </div>
            <span class="ev-help">hh:mm:ss</span>
          </div>

          <div class="ev-duration-card">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="13" r="8"/><path d="M12 9v5l3 2"/><path d="M12 2v3"/></svg>
            <div><span>Duración del recorte</span><strong id="evTrimDuration">00:00:24</strong><small>(24 segundos)</small></div>
          </div>

          <div class="ev-preview-title">Vista previa del recorte</div>
          <div class="ev-preview-box">
            <div class="ev-preview-img"><div class="ev-preview-play">▶</div></div>
            <div class="ev-preview-controls">00:00:00 / <span id="evTrimPreviewDur">00:00:24</span></div>
          </div>
        </div>

        <div class="ev-success">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M10.3 14.7 7.8 12.2l-1.4 1.4 3.9 3.9 7.3-7.3-1.4-1.4z" fill="#052e16"/></svg>
          <div><strong>Recorte listo para guardar</strong><span>El segmento seleccionado se guardará como un nuevo video.</span></div>
        </div>
      </aside>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
  /* Play / Pausa */
  let playing = false;
  const speeds = ['0.5x','0.75x','1.0x','1.25x','1.5x','2.0x'];
  let sIdx = 2;
  function togglePlay(){
    playing = !playing;
    [document.getElementById('evPlayBig'), document.getElementById('evPlayBtn')].forEach(btn => {
      btn.querySelector('.play-icon').style.display  = playing ? 'none' : '';
      btn.querySelector('.pause-icon').style.display = playing ? ''     : 'none';
    });
  }
  document.getElementById('evPlayBig').addEventListener('click', togglePlay);
  document.getElementById('evPlayBtn').addEventListener('click', togglePlay);

  /* Velocidad */
  document.getElementById('evSpeed').addEventListener('click', function(){
    sIdx = (sIdx + 1) % speeds.length;
    this.textContent = speeds[sIdx];
  });

  /* Filtros */
  const evToolFiltros = document.getElementById('evToolFiltros');
  const evFiltersPanel = document.getElementById('evFiltersPanel');
  const evPlayerBg = document.querySelector('.ev-player-bg');

  function applyEvFilters(){
    const b = document.getElementById('evBrillo').value;
    const c = document.getElementById('evContraste').value;
    const s = document.getElementById('evSaturacion').value;
    evPlayerBg.style.filter = `brightness(${b}%) contrast(${c}%) saturate(${s}%)`;
  }

  evToolFiltros.addEventListener('click', function(){
    const isOpen = evFiltersPanel.classList.toggle('open');
    this.classList.toggle('on', isOpen);
    evFiltersPanel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
  });

  ['evBrillo','evContraste','evSaturacion'].forEach(id => {
    const input = document.getElementById(id);
    const val = document.getElementById(id + 'Val');
    input.addEventListener('input', function(){
      val.textContent = this.value + '%';
      applyEvFilters();
    });
  });
  document.getElementById('evFilterApply').addEventListener('click', function(){
    applyEvFilters();
    this.textContent = 'Aplicado';
    setTimeout(() => { this.textContent = 'Aplicar filtros'; }, 1200);
  });
  document.getElementById('evFilterReset').addEventListener('click', function(){
    ['evBrillo','evContraste','evSaturacion'].forEach(id => {
      document.getElementById(id).value = 100;
      document.getElementById(id + 'Val').textContent = '100%';
    });
    evPlayerBg.style.filter = 'none';
  });

  /* Miniaturas */
  document.querySelectorAll('.ev-cap-item').forEach(item => {
    item.addEventListener('click', function(){
      document.querySelectorAll('.ev-cap-item').forEach(i => i.classList.remove('sel'));
      this.classList.add('sel');
      document.getElementById('evTime').textContent = this.dataset.ts + ' / 00:15:42';
    });
  });

  async function buildExportVideoBlob(){
    if(!window.MediaRecorder){
      throw new Error('Tu navegador no soporta exportacion de video desde esta vista.');
    }

    const canvas = document.createElement('canvas');
    canvas.width = 1280;
    canvas.height = 720;
    const ctx = canvas.getContext('2d');
    const stream = canvas.captureStream(30);
    const chunks = [];
    const mimeType = MediaRecorder.isTypeSupported('video/webm;codecs=vp9')
      ? 'video/webm;codecs=vp9'
      : 'video/webm';
    const recorder = new MediaRecorder(stream, { mimeType });
    const selectedTs = document.querySelector('.ev-cap-item.sel .ev-cap-ts')?.textContent || '0:02:15';
    const filter = getComputedStyle(document.querySelector('.ev-player-bg')).filter;

    recorder.ondataavailable = event => {
      if(event.data && event.data.size) chunks.push(event.data);
    };

    const stopped = new Promise(resolve => {
      recorder.onstop = resolve;
    });

    function drawFrame(frame){
      const t = frame / 90;
      const glowX = 640 + Math.sin(t * 2.2) * 90;
      const glowY = 360 + Math.cos(t * 1.7) * 55;

      ctx.save();
      ctx.filter = filter === 'none' ? 'none' : filter;
      const bg = ctx.createRadialGradient(glowX, glowY, 40, 640, 360, 720);
      bg.addColorStop(0, '#6b2118');
      bg.addColorStop(0.35, '#3a0b0b');
      bg.addColorStop(1, '#060810');
      ctx.fillStyle = bg;
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      ctx.globalAlpha = 0.34;
      ctx.fillStyle = '#8b2a1e';
      ctx.beginPath();
      ctx.ellipse(glowX, glowY, 240 + Math.sin(t) * 18, 150 + Math.cos(t) * 12, t * 0.18, 0, Math.PI * 2);
      ctx.fill();
      ctx.globalAlpha = 1;
      ctx.restore();

      ctx.fillStyle = 'rgba(0,0,0,.72)';
      ctx.fillRect(0, 618, canvas.width, 102);
      ctx.fillStyle = '#2e7bf6';
      ctx.fillRect(40, 645, 310 + frame * 2.8, 5);
      ctx.fillStyle = 'rgba(255,255,255,.9)';
      ctx.beginPath();
      ctx.arc(350 + frame * 2.8, 647, 9, 0, Math.PI * 2);
      ctx.fill();

      ctx.fillStyle = 'rgba(255,255,255,.88)';
      ctx.font = '700 28px Arial';
      ctx.fillText('Endoscopia Digestiva Alta', 56, 72);
      ctx.font = '600 18px Arial';
      ctx.fillStyle = 'rgba(255,255,255,.65)';
      ctx.fillText('EDD-2025-001245 · Fotograma ' + selectedTs, 56, 104);

      ctx.fillStyle = 'rgba(255,255,255,.78)';
      ctx.font = '600 22px Arial';
      ctx.fillText('00:02:15 / 00:15:42', 56, 690);
    }

    recorder.start();
    for(let frame = 0; frame < 90; frame++){
      drawFrame(frame);
      await new Promise(resolve => setTimeout(resolve, 1000 / 30));
    }
    recorder.stop();
    stream.getTracks().forEach(track => track.stop());
    await stopped;

    return new Blob(chunks, { type: 'video/webm' });
  }

  async function exportVideo(){
    const btn = document.getElementById('evExportVideo');
    const original = btn.innerHTML;

    try {
      btn.disabled = true;
      btn.textContent = 'Exportando...';
      const blob = await buildExportVideoBlob();
      const filename = 'EDD-2025-001245-exportado.webm';

      if(window.showSaveFilePicker){
        const handle = await window.showSaveFilePicker({
          suggestedName: filename,
          types: [{
            description: 'Video WebM',
            accept: { 'video/webm': ['.webm'] }
          }]
        });
        const writable = await handle.createWritable();
        await writable.write(blob);
        await writable.close();
      } else {
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
      }

      btn.textContent = 'Exportado';
      btn.style.color = 'var(--green)';
      btn.style.borderColor = 'rgba(61,220,151,.45)';
    } catch (error) {
      if(error.name !== 'AbortError'){
        alert(error.message || 'No se pudo exportar el video.');
      }
    } finally {
      setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = original;
        btn.style.color = '';
        btn.style.borderColor = '';
      }, 1600);
    }
  }

  document.getElementById('evExportVideo').addEventListener('click', exportVideo);

  function parseTime(value){
    const parts = value.split(':').map(part => parseInt(part, 10));
    if(parts.length !== 3 || parts.some(Number.isNaN)) return 0;
    return parts[0] * 3600 + parts[1] * 60 + parts[2];
  }

  function formatTime(seconds){
    const safe = Math.max(0, Math.round(seconds));
    const h = Math.floor(safe / 3600).toString().padStart(2, '0');
    const m = Math.floor((safe % 3600) / 60).toString().padStart(2, '0');
    const s = (safe % 60).toString().padStart(2, '0');
    return `${h}:${m}:${s}`;
  }

  function updateTrimSummary(){
    const startInput = document.getElementById('evTrimStart');
    const endInput = document.getElementById('evTrimEnd');
    let start = parseTime(startInput.value);
    let end = parseTime(endInput.value);

    if(end <= start) end = start + 1;
    startInput.value = formatTime(start);
    endInput.value = formatTime(end);

    const duration = end - start;
    document.getElementById('evTrimDuration').textContent = formatTime(duration);
    document.getElementById('evTrimDurationPop').textContent = formatTime(duration);
    document.getElementById('evTrimPreviewDur').textContent = formatTime(duration);
    document.querySelector('.ev-duration-card small').textContent = `(${duration} segundos)`;
    document.getElementById('evTrimStartTag').textContent = formatTime(start);
    document.getElementById('evTrimEndTag').textContent = formatTime(end);
    document.getElementById('evTrimCurrent').textContent = formatTime(end);
    document.getElementById('evTrimPlayerTime').textContent = `${formatTime(end)} / 00:15:42`;
  }

  function showEditMode(){
    document.getElementById('evTrimMode').classList.add('hidden');
    document.getElementById('evEditMode').classList.remove('hidden');
  }

  function showTrimMode(){
    document.getElementById('evEditMode').classList.add('hidden');
    document.getElementById('evTrimMode').classList.remove('hidden');
    updateTrimSummary();
  }

  document.getElementById('evOpenTrim').addEventListener('click', showTrimMode);
  document.getElementById('evTrimCancel').addEventListener('click', showEditMode);
  document.getElementById('evTrimStart').addEventListener('change', updateTrimSummary);
  document.getElementById('evTrimEnd').addEventListener('change', updateTrimSummary);
  document.querySelectorAll('.ev-quick button[data-delta]').forEach(btn => {
    btn.addEventListener('click', function(){
      const endInput = document.getElementById('evTrimEnd');
      endInput.value = formatTime(parseTime(endInput.value) + parseInt(this.dataset.delta, 10));
      updateTrimSummary();
    });
  });
  document.getElementById('evTrimSave').addEventListener('click', function(){
    this.textContent = 'Recorte guardado';
    this.style.background = 'var(--green)';
    this.style.borderColor = 'var(--green)';
    setTimeout(() => {
      this.textContent = 'Guardar recorte';
      this.style.background = '';
      this.style.borderColor = '';
      showEditMode();
    }, 1400);
  });

  /* Guardar */
  document.getElementById('evSave').addEventListener('click', function(){
    this.textContent = 'Guardado';
    this.style.background = 'var(--green)';
    setTimeout(() => { this.textContent = 'Guardar cambios'; this.style.background = ''; }, 2000);
  });
})();
</script>
@endpush
