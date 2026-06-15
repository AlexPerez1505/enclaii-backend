@extends('layouts.app')

@section('title', 'Editar Video')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')
@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="color:var(--txt-soft);font-size:13px">Maria Gonzales</span>
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
.ev-btn.close{width:38px;padding:0;justify-content:center;background:rgba(255,90,110,.12);border:1px solid rgba(255,90,110,.35);color:var(--red)}
@media(hover:hover)and(pointer:fine){.ev-btn.close:hover{background:rgba(255,90,110,.22)}}

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

/* Ajuste video (sliders) */
.ev-adj-row{display:flex;align-items:center;gap:10px;margin-bottom:8px}
.ev-adj-row:last-child{margin-bottom:0}
.ev-adj-ic{width:20px;flex:none;color:var(--txt-soft);display:grid;place-items:center}
.ev-adj-lbl{font-size:12.5px;color:var(--txt-soft);width:72px;flex:none}
.ev-slider{
  flex:1;height:4px;border-radius:4px;
  appearance:none;-webkit-appearance:none;
  background:linear-gradient(to right,var(--blue) 65%,rgba(255,255,255,.15) 65%);
  cursor:pointer;outline:none;
}
.ev-slider::-webkit-slider-thumb{appearance:none;width:13px;height:13px;border-radius:50%;background:#fff;cursor:grab;box-shadow:0 1px 4px rgba(0,0,0,.4)}
.ev-zoom-row{display:flex;align-items:center;gap:8px;margin-top:4px}
.ev-zoom-lbl{font-size:12.5px;color:var(--txt-soft);width:72px;flex:none;display:flex;align-items:center;gap:6px}
.ev-zoom-val{font-size:13px;font-weight:700;color:var(--txt);min-width:40px;text-align:center}
.ev-zoom-btn{
  width:28px;height:28px;border-radius:7px;
  border:1px solid var(--stroke);display:grid;place-items:center;
  color:var(--txt);font-size:16px;font-weight:700;
  transition:background-color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.ev-zoom-btn:hover{background:rgba(46,123,246,.14);border-color:rgba(46,123,246,.4);color:var(--blue)}}

/* Anotaciones */
.ev-ann-row{display:flex;align-items:center;gap:8px;margin-bottom:10px}
.ev-ann-btn{
  display:flex;align-items:center;gap:7px;
  height:36px;padding:0 14px;border-radius:var(--r-md);
  font:inherit;font-size:12.5px;font-weight:600;color:var(--txt);
  background:var(--card);border:1px solid var(--stroke);
  transition:background-color 150ms ease,border-color 150ms ease;
}
.ev-ann-btn.on{background:rgba(46,123,246,.15);border-color:rgba(46,123,246,.5);color:var(--blue)}
@media(hover:hover)and(pointer:fine){.ev-ann-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.4);color:var(--blue)}}
.ev-ann-input{
  flex:1;height:36px;background:var(--card);border:1px solid var(--stroke);
  border-radius:var(--r-md);padding:0 12px;font:inherit;font-size:12.5px;
  color:var(--txt);outline:none;transition:border-color 150ms ease;
}
.ev-ann-input::placeholder{color:var(--txt-soft)}
.ev-ann-input:focus{border-color:var(--blue)}
/* Colores */
.ev-color-row{display:flex;align-items:center;gap:7px;margin-bottom:10px}
.ev-color-lbl{font-size:12px;color:var(--txt-soft);width:40px;flex:none}
.ev-color-dot{width:22px;height:22px;border-radius:50%;cursor:pointer;border:2px solid transparent;transition:transform 150ms ease,border-color 150ms ease;flex:none}
.ev-color-dot:hover{transform:scale(1.15)}
.ev-color-dot.sel{border-color:#fff}
/* Grosor */
.ev-stroke-row{display:flex;align-items:center;gap:8px}
.ev-stroke-lbl{font-size:12px;color:var(--txt-soft);width:40px;flex:none}
.ev-stroke-opt{height:2px;border-radius:2px;background:var(--txt);cursor:pointer;transition:opacity 150ms ease;flex:none}
.ev-stroke-opt:hover{opacity:.7}
.ev-stroke-opt.s1{width:28px}
.ev-stroke-opt.s2{width:28px;height:4px}
.ev-stroke-opt.s3{width:28px;height:7px}

/* Info clínica */
.ev-clin-group{margin-bottom:12px}
.ev-clin-group:last-child{margin-bottom:0}
.ev-clin-lbl{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:6px}
.ev-toolbar{display:flex;align-items:center;gap:4px;padding:5px 8px;background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-md) var(--r-md) 0 0;border-bottom:none}
.ev-tb-btn{
  width:26px;height:26px;border-radius:6px;display:grid;place-items:center;
  font-size:13px;font-weight:700;color:var(--txt-soft);
  transition:background-color 150ms ease,color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.ev-tb-btn:hover{background:rgba(110,160,255,.1);color:var(--txt)}}
.ev-tb-sep{width:1px;height:16px;background:var(--stroke);margin:0 2px}
.ev-textarea{
  width:100%;min-height:52px;padding:10px 12px;
  font:inherit;font-size:13px;color:var(--txt);
  background:var(--card);border:1px solid var(--stroke);
  border-radius:0 0 var(--r-md) var(--r-md);
  outline:none;resize:vertical;
  transition:border-color 150ms ease;
  line-height:1.5;
}
.ev-textarea:focus{border-color:var(--blue)}

@media(max-width:1100px){.ev-wrap{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

<div class="rise d2">

  {{-- Topbar --}}
  <div class="ev-topbar">
    <button class="ev-btn save" id="evSave">Guardar cambios</button>
    <a href="{{ route('galeria.video', $id) }}" class="ev-btn cancel">Cancelar</a>
    <button class="ev-btn more">···</button>
    <a href="{{ route('galeria') }}" class="ev-btn close">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </a>
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
        <button class="ev-act-btn"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>Exportar video</button>
        <button class="ev-act-btn"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>Imprimir informe</button>
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

      {{-- Edición de video --}}
      <div class="ev-section">
        <div class="ev-sec-head">
          <span class="ev-sec-title">Edición de video</span>
          <span class="ev-sec-more">···</span>
        </div>
        <div class="ev-tools-grid">
          <button class="ev-tool-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3z"/><line x1="4" y1="20" x2="4.01" y2="20"/><line x1="16" y1="9" x2="16.01" y2="9"/></svg>Recortar</button>
          <button class="ev-tool-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="18"/><rect x="14" y="3" width="7" height="18"/></svg>Dividir</button>
          <button class="ev-tool-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg>Rotar</button>
          <button class="ev-tool-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>Voltear horizontal</button>
          <button class="ev-tool-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 7 5 3 9 7"/><path d="M5 3v18"/><polyline points="23 17 19 21 15 17"/><path d="M19 21V3"/></svg>Voltear vertical</button>
          <button class="ev-tool-btn" id="evRestablecer"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg>Restablecer</button>
        </div>
      </div>

      {{-- Ajuste de video --}}
      <div class="ev-section">
        <div class="ev-sec-head">
          <span class="ev-sec-title">Ajuste de video</span>
        </div>
        <div class="ev-adj-row">
          <span class="ev-adj-ic"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg></span>
          <span class="ev-adj-lbl">Brillo</span>
          <input type="range" class="ev-slider" min="0" max="100" value="65" id="slBrillo">
        </div>
        <div class="ev-adj-row">
          <span class="ev-adj-ic"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20z"/></svg></span>
          <span class="ev-adj-lbl">Contraste</span>
          <input type="range" class="ev-slider" min="0" max="100" value="55" id="slContraste">
        </div>
        <div class="ev-adj-row">
          <span class="ev-adj-ic"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 2v20M2 12h20"/></svg></span>
          <span class="ev-adj-lbl">Saturación</span>
          <input type="range" class="ev-slider" min="0" max="100" value="60" id="slSaturacion">
        </div>
        <div class="ev-adj-row">
          <span class="ev-adj-ic"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg></span>
          <span class="ev-adj-lbl">Nitidez</span>
          <input type="range" class="ev-slider" min="0" max="100" value="45" id="slNitidez">
        </div>
        <div class="ev-zoom-row">
          <span class="ev-zoom-lbl"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>Zoom</span>
          <button class="ev-zoom-btn" id="evZoomMinus">−</button>
          <span class="ev-zoom-val" id="evZoomVal">100%</span>
          <button class="ev-zoom-btn" id="evZoomPlus">+</button>
        </div>
      </div>

      {{-- Anotaciones --}}
      <div class="ev-section">
        <div class="ev-sec-head">
          <span class="ev-sec-title">Anotaciones</span>
          <span class="ev-sec-more">···</span>
        </div>
        <div class="ev-ann-row">
          <button class="ev-ann-btn" id="evDibujar"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>Dibujar</button>
          <input class="ev-ann-input" type="text" placeholder="···">
          <button class="ev-ann-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>Resaltar zona</button>
          <button class="ev-ann-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>Texto</button>
        </div>
        <div class="ev-color-row">
          <span class="ev-color-lbl">Color</span>
          <div class="ev-color-dot sel" style="background:#FF5A6E" data-color="#FF5A6E"></div>
          <div class="ev-color-dot" style="background:#F59E2D" data-color="#F59E2D"></div>
          <div class="ev-color-dot" style="background:#3DDC97" data-color="#3DDC97"></div>
          <div class="ev-color-dot" style="background:#2E7BF6" data-color="#2E7BF6"></div>
          <div class="ev-color-dot" style="background:#b45ef5" data-color="#b45ef5"></div>
        </div>
        <div class="ev-stroke-row">
          <span class="ev-stroke-lbl">Grosor</span>
          <div class="ev-stroke-opt s1"></div>
          <div class="ev-stroke-opt s2"></div>
          <div class="ev-stroke-opt s3"></div>
        </div>
      </div>

      {{-- Información clínica --}}
      <div class="ev-section">
        <div class="ev-sec-head">
          <span class="ev-sec-title">Información clínica</span>
        </div>

        <div class="ev-clin-group">
          <div class="ev-clin-lbl">Hallazgos</div>
          <div class="ev-toolbar">
            <button class="ev-tb-btn" style="font-weight:900">B</button>
            <button class="ev-tb-btn" style="font-style:italic">I</button>
            <button class="ev-tb-btn" style="text-decoration:underline">U</button>
            <div class="ev-tb-sep"></div>
            <button class="ev-tb-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="12" x2="3" y2="12"/><line x1="21" y1="18" x2="3" y2="18"/></svg></button>
            <button class="ev-tb-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="5" cy="6" r="1"/><circle cx="5" cy="12" r="1"/><circle cx="5" cy="18" r="1"/></svg></button>
          </div>
          <textarea class="ev-textarea" rows="2">Mucosa eritematosa en antro gástrico, pliegues conservados.</textarea>
        </div>

        <div class="ev-clin-group">
          <div class="ev-clin-lbl">Observaciones</div>
          <div class="ev-toolbar">
            <button class="ev-tb-btn" style="font-weight:900">B</button>
            <button class="ev-tb-btn" style="font-style:italic">I</button>
            <button class="ev-tb-btn" style="text-decoration:underline">U</button>
            <div class="ev-tb-sep"></div>
            <button class="ev-tb-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="12" x2="3" y2="12"/><line x1="21" y1="18" x2="3" y2="18"/></svg></button>
            <button class="ev-tb-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="5" cy="6" r="1"/><circle cx="5" cy="12" r="1"/><circle cx="5" cy="18" r="1"/></svg></button>
          </div>
          <textarea class="ev-textarea" rows="2">Procedimiento tolerado adecuadamente.</textarea>
        </div>

        <div class="ev-clin-group">
          <div class="ev-clin-lbl">Diagnósticos</div>
          <div class="ev-toolbar">
            <button class="ev-tb-btn" style="font-weight:900">B</button>
            <button class="ev-tb-btn" style="font-style:italic">I</button>
            <button class="ev-tb-btn" style="text-decoration:underline">U</button>
            <div class="ev-tb-sep"></div>
            <button class="ev-tb-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="12" x2="3" y2="12"/><line x1="21" y1="18" x2="3" y2="18"/></svg></button>
            <button class="ev-tb-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="5" cy="6" r="1"/><circle cx="5" cy="12" r="1"/><circle cx="5" cy="18" r="1"/></svg></button>
          </div>
          <textarea class="ev-textarea" rows="2">Gastritis antral leve.</textarea>
        </div>
      </div>

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

  /* Zoom */
  let zoom = 100;
  document.getElementById('evZoomPlus').addEventListener('click', function(){
    zoom = Math.min(zoom + 10, 200);
    document.getElementById('evZoomVal').textContent = zoom + '%';
  });
  document.getElementById('evZoomMinus').addEventListener('click', function(){
    zoom = Math.max(zoom - 10, 50);
    document.getElementById('evZoomVal').textContent = zoom + '%';
  });

  /* Restablecer ajustes */
  document.getElementById('evRestablecer').addEventListener('click', function(){
    document.getElementById('slBrillo').value    = 65;
    document.getElementById('slContraste').value = 55;
    document.getElementById('slSaturacion').value= 60;
    document.getElementById('slNitidez').value   = 45;
    zoom = 100;
    document.getElementById('evZoomVal').textContent = '100%';
    updateSliders();
  });

  /* Actualizar colores de sliders */
  function updateSliders(){
    document.querySelectorAll('.ev-slider').forEach(s => {
      const pct = s.value + '%';
      s.style.background = `linear-gradient(to right,var(--blue) ${pct},rgba(255,255,255,.15) ${pct})`;
    });
  }
  document.querySelectorAll('.ev-slider').forEach(s => {
    s.addEventListener('input', updateSliders);
  });
  updateSliders();

  /* Colores anotación */
  document.querySelectorAll('.ev-color-dot').forEach(dot => {
    dot.addEventListener('click', function(){
      document.querySelectorAll('.ev-color-dot').forEach(d => d.classList.remove('sel'));
      this.classList.add('sel');
    });
  });

  /* Miniaturas */
  document.querySelectorAll('.ev-cap-item').forEach(item => {
    item.addEventListener('click', function(){
      document.querySelectorAll('.ev-cap-item').forEach(i => i.classList.remove('sel'));
      this.classList.add('sel');
      document.getElementById('evTime').textContent = this.dataset.ts + ' / 00:15:42';
    });
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
