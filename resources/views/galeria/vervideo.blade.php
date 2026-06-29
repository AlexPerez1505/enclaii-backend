@extends('layouts.app')

@section('title', 'Ver Video')
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
/* ===== VER VIDEO ===== */
.vv-wrap{display:grid;grid-template-columns:1fr 280px;gap:18px;align-items:start}

/* Acción superior */
.vv-topbar{display:flex;align-items:center;justify-content:flex-end;gap:10px;margin-bottom:14px}
.vv-btn{
  display:flex;align-items:center;gap:7px;
  height:38px;padding:0 16px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:600;
  transition:background-color 150ms ease,transform 160ms var(--ease-out);
}
.vv-btn:active{transform:scale(.97)}
.vv-btn.cancel{background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.vv-btn.cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.vv-btn.edit{background:rgba(46,123,246,.14);border:1px solid rgba(46,123,246,.35);color:var(--blue)}
@media(hover:hover)and(pointer:fine){.vv-btn.edit:hover{background:rgba(46,123,246,.25)}}
.vv-btn.dl{background:rgba(61,220,151,.12);border:1px solid rgba(61,220,151,.35);color:var(--green)}
@media(hover:hover)and(pointer:fine){.vv-btn.dl:hover{background:rgba(61,220,151,.22)}}

/* Player */
.vv-player-box{
  background:#000;border-radius:14px;overflow:hidden;
  position:relative;aspect-ratio:16/9;
  display:flex;align-items:center;justify-content:center;
}
.vv-player-bg{
  position:absolute;inset:0;
  background:radial-gradient(ellipse at 50% 50%,#5a1a10 0%,#2a0808 40%,#060810 100%);
}
.vv-player-icon{
  position:absolute;inset:0;z-index:2;
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;
  color:rgba(255,255,255,.5);font-size:13px;
}
.vv-play-big{
  width:64px;height:64px;border-radius:50%;
  background:rgba(255,255,255,.18);backdrop-filter:blur(8px);
  display:grid;place-items:center;cursor:pointer;
  transition:background-color 150ms ease,transform 150ms ease;
}
.vv-play-big:hover{background:rgba(46,123,246,.6);transform:scale(1.08)}
.vv-play-big.playing svg.play-icon{display:none}
.vv-play-big.playing svg.pause-icon{display:block}
.vv-play-big svg.pause-icon{display:none}

/* Controles */
.vv-controls{
  position:absolute;bottom:0;left:0;right:0;
  z-index:3;padding:32px 16px 14px;
  background:linear-gradient(0deg,rgba(0,0,0,.82) 0%,transparent 100%);
}
.vv-prog-wrap{position:relative;height:4px;background:rgba(255,255,255,.2);border-radius:4px;cursor:pointer;margin-bottom:10px}
.vv-prog-fill{height:100%;background:var(--blue);border-radius:4px;width:15%}
.vv-prog-thumb{
  position:absolute;top:50%;translate:0 -50%;
  width:12px;height:12px;border-radius:50%;background:#fff;
  left:15%;margin-left:-6px;cursor:grab;
}
.vv-ctrl-row{display:flex;align-items:center;gap:8px}
.vv-ctrl-btn{
  width:32px;height:32px;border-radius:8px;display:grid;place-items:center;
  color:rgba(255,255,255,.8);flex:none;
  transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vv-ctrl-btn:hover{background:rgba(255,255,255,.12)}}
.vv-time{font-size:12px;color:rgba(255,255,255,.65);flex:none;margin:0 4px}
.vv-vol-wrap{display:flex;align-items:center;gap:6px;margin-left:auto}
.vv-vol-bar{width:72px;height:4px;background:rgba(255,255,255,.2);border-radius:4px;cursor:pointer}
.vv-vol-fill{height:100%;background:rgba(255,255,255,.7);border-radius:4px;width:70%}
.vv-speed{
  font-size:12px;font-weight:700;color:rgba(255,255,255,.8);
  padding:3px 8px;border-radius:6px;border:1px solid rgba(255,255,255,.2);
  cursor:pointer;transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vv-speed:hover{background:rgba(255,255,255,.1)}}
.vv-fullscreen{margin-left:6px}

/* Acciones bajo el player */
.vv-actions{
  display:flex;align-items:center;gap:8px;flex-wrap:wrap;
  padding:12px 0;border-bottom:1px solid var(--stroke);margin-bottom:14px;
}
.vv-act-btn{
  display:flex;align-items:center;gap:6px;
  height:36px;padding:0 14px;border-radius:var(--r-md);
  font:inherit;font-size:12.5px;font-weight:600;
  background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);
  text-decoration:none;
  transition:background-color 150ms ease,border-color 150ms ease,transform 160ms var(--ease-out);
  white-space:nowrap;
}
.vv-act-btn:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.vv-act-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.4);color:var(--blue)}}
.vv-act-btn.ia{color:var(--cyan);border-color:rgba(56,199,244,.35);background:rgba(56,199,244,.08)}
@media(hover:hover)and(pointer:fine){.vv-act-btn.ia:hover{background:rgba(56,199,244,.18)}}
.vv-act-btn.wa{color:var(--green);border-color:rgba(61,220,151,.35);background:rgba(61,220,151,.08)}
@media(hover:hover)and(pointer:fine){.vv-act-btn.wa:hover{background:rgba(61,220,151,.18)}}

/* Miniaturas */
.vv-caps-title{font-size:13px;font-weight:600;margin-bottom:10px}
.vv-caps-strip{display:flex;gap:10px;overflow-x:auto;padding-bottom:6px;scrollbar-width:thin;scrollbar-color:var(--stroke) transparent}
.vv-cap-item{
  flex:none;width:100px;cursor:pointer;
  border-radius:8px;overflow:hidden;border:2px solid transparent;
  transition:border-color 150ms ease,transform 150ms ease;
}
.vv-cap-item:active{transform:scale(.96)}
.vv-cap-item.sel{border-color:var(--blue)}
@media(hover:hover)and(pointer:fine){.vv-cap-item:hover{border-color:rgba(46,123,246,.5)}}
.vv-cap-thumb{
  width:100%;aspect-ratio:4/3;
  display:grid;place-items:center;position:relative;
  background:radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a060e 100%);
}
.vv-cap-num{
  position:absolute;top:4px;left:5px;
  width:18px;height:18px;border-radius:6px;
  background:rgba(0,0,0,.6);display:grid;place-items:center;
  font-size:9px;font-weight:700;color:#fff;
}
.vv-cap-check{
  position:absolute;top:4px;right:4px;
  width:18px;height:18px;border-radius:50%;
  background:var(--blue);display:none;place-items:center;
}
.vv-cap-item.sel .vv-cap-check{display:grid}
.vv-cap-ts{font-size:10px;color:var(--txt-soft);text-align:center;padding:4px 0 2px}

/* Sidebar info */
.vv-side{display:flex;flex-direction:column;gap:14px}
.vv-info-card{
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-lg);padding:16px;
}
.vv-info-row{margin-bottom:12px}
.vv-info-row:last-child{margin-bottom:0}
.vv-info-lbl{font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:2px}
.vv-info-val{font-size:13.5px;font-weight:600;color:var(--txt)}
.vv-status{
  display:inline-flex;align-items:center;gap:6px;
  padding:4px 12px;border-radius:99px;
  font-size:12.5px;font-weight:700;
  background:rgba(61,220,151,.14);color:var(--green);
  border:1px solid rgba(61,220,151,.35);
}
.vv-diag-row{display:flex;align-items:center;justify-content:space-between}
.vv-diag-txt{font-size:13.5px;font-weight:600}
.vv-diag-av{
  width:36px;height:36px;border-radius:50%;flex:none;
  background:var(--cyan);display:grid;place-items:center;
  font-family:'Sora',sans-serif;font-size:14px;font-weight:700;color:#06081c;
}
.vv-obs-txt{font-size:13px;color:var(--txt-soft);line-height:1.6}
.vv-edit-ic{
  color:var(--txt-soft);cursor:pointer;transition:color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vv-edit-ic:hover{color:var(--blue)}}
.vv-section-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.vv-section-lbl{font-family:'Sora',sans-serif;font-size:13px;font-weight:700}
.vv-tags{display:flex;flex-wrap:wrap;gap:7px}
.vv-tag{
  padding:4px 12px;border-radius:99px;
  font-size:12px;font-weight:600;
  background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vv-tag:hover{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.4);color:var(--blue)}}

@media(max-width:960px){.vv-wrap{grid-template-columns:1fr}}

/* ===== MODAL DESCARGA VIDEO ===== */
.vv-dl-overlay{
  position:fixed;inset:0;z-index:900;
  background:rgba(0,0,0,.6);backdrop-filter:blur(4px);
  display:flex;align-items:center;justify-content:center;
  opacity:0;pointer-events:none;transition:opacity 200ms ease;
}
.vv-dl-overlay.open{opacity:1;pointer-events:auto}
.vv-dl-modal{
  background:var(--panel);border:1px solid var(--stroke);
  border-radius:18px;width:520px;max-width:95vw;
  box-shadow:0 24px 64px rgba(0,0,0,.5);
  transform:scale(.94);transition:transform 200ms var(--ease-out);overflow:hidden;
}
.vv-dl-overlay.open .vv-dl-modal{transform:scale(1)}
.vv-dl-hdr{
  display:flex;align-items:flex-start;justify-content:space-between;
  padding:18px 20px 0;
}
.vv-dl-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:700;display:flex;align-items:center;gap:9px}
.vv-dl-title svg{color:var(--blue)}
.vv-dl-sub{font-size:12px;color:var(--txt-soft);margin-top:2px}
.vv-dl-x{
  width:30px;height:30px;border-radius:8px;border:1px solid var(--stroke);
  display:grid;place-items:center;color:var(--txt-soft);flex:none;
  transition:background-color 150ms ease,color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vv-dl-x:hover{background:rgba(255,90,110,.12);color:var(--red)}}
.vv-dl-body{padding:16px 20px 18px;display:flex;flex-direction:column;gap:14px}
/* Sección */
.vv-dl-sec-lbl{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:8px}
/* Rango radio */
.vv-rng-list{display:flex;flex-direction:column;gap:6px}
.vv-rng-row{
  display:flex;align-items:center;gap:10px;
  padding:9px 13px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--card);
  cursor:pointer;transition:border-color 150ms ease,background-color 150ms ease;
}
.vv-rng-row.sel{border-color:var(--blue);background:rgba(46,123,246,.1)}
@media(hover:hover)and(pointer:fine){.vv-rng-row:not(.sel):hover{border-color:rgba(46,123,246,.35)}}
.vv-rng-radio{
  width:16px;height:16px;border-radius:50%;border:2px solid var(--stroke);flex:none;
  display:grid;place-items:center;
  transition:border-color 150ms ease,background-color 150ms ease;
}
.vv-rng-row.sel .vv-rng-radio{border-color:var(--blue);background:var(--blue)}
.vv-rng-row.sel .vv-rng-radio::after{content:'';width:6px;height:6px;border-radius:50%;background:#fff}
.vv-rng-label{font-size:13px;font-weight:600;flex:1}
.vv-rng-ts{font-size:12px;color:var(--txt-soft);font-weight:600}
/* Rango personalizado inputs */
.vv-rng-custom{
  display:none;align-items:center;gap:8px;
  padding:8px 13px;background:var(--card);
  border:1px solid rgba(46,123,246,.4);border-top:none;
  border-radius:0 0 var(--r-md) var(--r-md);
}
.vv-rng-custom.show{display:flex}
.vv-rng-input{
  height:32px;width:90px;padding:0 10px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:8px;font:inherit;font-size:12px;color:var(--txt);
  outline:none;transition:border-color 150ms ease;text-align:center;
}
.vv-rng-input:focus{border-color:var(--blue)}
.vv-rng-a{font-size:12px;color:var(--txt-soft)}
.vv-rng-dur{font-size:11.5px;color:var(--txt-soft);margin-left:auto}
.vv-rng-dur span{color:var(--blue);font-weight:700}
/* Calidad */
.vv-dl-qual{
  width:100%;height:38px;background:var(--card);border:1px solid var(--stroke);
  border-radius:var(--r-md);padding:0 12px;font:inherit;font-size:13px;
  color:var(--txt);outline:none;cursor:pointer;
  transition:border-color 150ms ease;
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238FA3CF' stroke-width='2.5' stroke-linecap='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 12px center;padding-right:32px;
  margin-bottom:4px;
}
.vv-dl-qual:focus{border-color:var(--blue)}
.vv-dl-qual-res{font-size:11px;color:var(--txt-soft)}
/* Formatos */
.vv-fmt-row{display:flex;gap:8px}
.vv-fmt-card{
  flex:1;padding:10px 12px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--card);
  cursor:pointer;text-align:center;
  transition:border-color 150ms ease,background-color 150ms ease;
}
.vv-fmt-card.sel{border-color:var(--blue);background:rgba(46,123,246,.1)}
@media(hover:hover)and(pointer:fine){.vv-fmt-card:not(.sel):hover{border-color:rgba(46,123,246,.35)}}
.vv-fmt-ext{font-size:13px;font-weight:700}
.vv-fmt-sub{font-size:10.5px;color:var(--txt-soft);margin-top:1px}
/* Incluir */
.vv-inc-row{
  display:flex;align-items:center;gap:9px;
  margin-bottom:7px;cursor:pointer;
}
.vv-inc-row:last-child{margin-bottom:0}
.vv-inc-cb{
  width:17px;height:17px;border-radius:5px;flex:none;
  border:2px solid var(--stroke);display:grid;place-items:center;
  transition:background-color 150ms ease,border-color 150ms ease;
}
.vv-inc-row.checked .vv-inc-cb{background:var(--blue);border-color:var(--blue)}
.vv-inc-lbl{font-size:13px;color:var(--txt)}
/* Footer */
.vv-dl-footer{
  padding:12px 20px 16px;border-top:1px solid var(--stroke);
  display:flex;align-items:center;justify-content:space-between;gap:8px;
}
.vv-dl-note{font-size:11.5px;color:var(--txt-soft);display:flex;align-items:center;gap:5px}
.vv-dl-footer-btns{display:flex;gap:8px}
.vv-dl-cancel{
  height:37px;padding:0 16px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:transparent;
  font:inherit;font-size:13px;font-weight:600;color:var(--txt-soft);
  transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vv-dl-cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.vv-dl-confirm{
  height:37px;padding:0 20px;border-radius:var(--r-md);
  border:none;background:var(--blue);
  font:inherit;font-size:13px;font-weight:700;color:#fff;
  display:flex;align-items:center;gap:7px;
  transition:opacity 150ms ease,transform 160ms var(--ease-out);
}
.vv-dl-confirm:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.vv-dl-confirm:hover{opacity:.88}}
</style>
@endpush

{{-- ===== MODAL DESCARGA VIDEO ===== --}}
<div class="vv-dl-overlay" id="vvDlOverlay">
  <div class="vv-dl-modal">
    <div class="vv-dl-hdr">
      <div>
        <div class="vv-dl-title">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Descargar video
        </div>
        <div class="vv-dl-sub">Selecciona las opciones para descargar el video.</div>
      </div>
      <button class="vv-dl-x" id="vvDlClose">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="vv-dl-body">

      {{-- Rango --}}
      <div>
        <div class="vv-dl-sec-lbl">Rango del video</div>
        <div class="vv-rng-list">
          <div class="vv-rng-row sel" data-rng="completo">
            <div class="vv-rng-radio"></div>
            <span class="vv-rng-label">Video completo</span>
            <span class="vv-rng-ts">00:15:42</span>
          </div>
          <div class="vv-rng-row" data-rng="inicio">
            <div class="vv-rng-radio"></div>
            <span class="vv-rng-label">Desde el inicio hasta el momento actual</span>
            <span class="vv-rng-ts">00:02:15</span>
          </div>
          <div class="vv-rng-row" data-rng="custom" id="vvRngCustomRow">
            <div class="vv-rng-radio"></div>
            <span class="vv-rng-label">Rango personalizado</span>
          </div>
        </div>
        <div class="vv-rng-custom" id="vvRngCustom">
          <input class="vv-rng-input" type="text" value="00:02:15" id="vvRngFrom">
          <span class="vv-rng-a">a</span>
          <input class="vv-rng-input" type="text" value="00:08:47" id="vvRngTo">
          <span class="vv-rng-dur">Duración seleccionada: <span id="vvRngDur">00:06:32</span></span>
        </div>
      </div>

      {{-- Calidad --}}
      <div>
        <div class="vv-dl-sec-lbl">Calidad de video</div>
        <select class="vv-dl-qual" id="vvDlQual">
          <option value="1080">Alta (1080p) — Recomendado</option>
          <option value="720">Media (720p)</option>
          <option value="480">Baja (480p)</option>
        </select>
        <div class="vv-dl-qual-res" id="vvQualRes">Resolución: 1920 x 1080</div>
      </div>

      {{-- Formato --}}
      <div>
        <div class="vv-dl-sec-lbl">Formato de archivo</div>
        <div class="vv-fmt-row">
          <div class="vv-fmt-card sel" data-fmt="MP4">
            <div class="vv-fmt-ext">MP4</div>
            <div class="vv-fmt-sub">Video estándar</div>
          </div>
          <div class="vv-fmt-card" data-fmt="MOV">
            <div class="vv-fmt-ext">MOV</div>
            <div class="vv-fmt-sub">Alta compatibilidad</div>
          </div>
          <div class="vv-fmt-card" data-fmt="AVI">
            <div class="vv-fmt-ext">AVI</div>
            <div class="vv-fmt-sub">Formato universal</div>
          </div>
        </div>
      </div>

      {{-- Qué incluir --}}
      <div>
        <div class="vv-dl-sec-lbl">Qué deseas incluir</div>
        <div class="vv-inc-row checked">
          <div class="vv-inc-cb"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <span class="vv-inc-lbl">Incluir audio</span>
        </div>
        <div class="vv-inc-row checked">
          <div class="vv-inc-cb"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <span class="vv-inc-lbl">Incluir información del estudio</span>
        </div>
        <div class="vv-inc-row" id="vvIncMarca">
          <div class="vv-inc-cb"></div>
          <span class="vv-inc-lbl">Marca de agua Enclaii</span>
        </div>
      </div>

    </div>

    <div class="vv-dl-footer">
      <div class="vv-dl-note">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        El video se descargará de forma segura y confidencial.
      </div>
      <div class="vv-dl-footer-btns">
        <button class="vv-dl-cancel" id="vvDlCancel">Cancelar</button>
        <button class="vv-dl-confirm" id="vvDlConfirm">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Descargar video
        </button>
      </div>
    </div>
  </div>
</div>

@section('content')

<div class="rise d2">

  {{-- Botones superiores --}}
  <div class="vv-topbar">
    <a href="{{ route('galeria.paciente', $pacienteId) }}" class="vv-btn cancel">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver a la galería
    </a>
    <a href="{{ route('galeria.video.editar', ['id' => $id, 'paciente' => $pacienteId]) }}" class="vv-btn edit">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      Editar
    </a>
    <button class="vv-btn dl">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Descargar video
    </button>
  </div>

  <div class="vv-wrap">

    {{-- ===== COLUMNA PRINCIPAL ===== --}}
    <div>

      {{-- Player --}}
      <div class="vv-player-box" id="vvPlayer">
        <div class="vv-player-bg"></div>

        {{-- Icono central (cuando no está reproduciendo) --}}
        <div class="vv-player-icon" id="vvCenter">
          <div class="vv-play-big" id="vvPlayBig">
            <svg class="play-icon" width="24" height="24" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <svg class="pause-icon" width="24" height="24" viewBox="0 0 24 24" fill="white"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
          </div>
          <span id="vvCenterLabel">Endoscopia Digestiva Alta · EDD-2025-001245</span>
        </div>

        {{-- Controles --}}
        <div class="vv-controls">
          <div class="vv-prog-wrap" id="vvProgWrap">
            <div class="vv-prog-fill" id="vvProgFill"></div>
            <div class="vv-prog-thumb" id="vvProgThumb"></div>
          </div>
          <div class="vv-ctrl-row">
            <button class="vv-ctrl-btn" title="Inicio">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polygon points="19 20 9 12 19 4 19 20"/><line x1="5" y1="19" x2="5" y2="5"/></svg>
            </button>
            <button class="vv-ctrl-btn" title="Retroceder 10s">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/><text x="8" y="14" font-size="5" fill="currentColor" stroke="none" font-weight="700">10</text></svg>
            </button>
            <button class="vv-ctrl-btn" id="vvPlayBtn" title="Play/Pausa">
              <svg class="play-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              <svg class="pause-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            </button>
            <button class="vv-ctrl-btn" title="Adelantar 10s">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-4.95"/></svg>
            </button>
            <span class="vv-time" id="vvTime">00:02:15 / 00:15:42</span>
            <div class="vv-vol-wrap">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
              <div class="vv-vol-bar"><div class="vv-vol-fill"></div></div>
            </div>
            <button class="vv-speed" id="vvSpeed">1.0x</button>
            <button class="vv-ctrl-btn vv-fullscreen" title="Pantalla completa">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
            </button>
          </div>
        </div>
      </div>

      {{-- Acciones --}}
      <div class="vv-actions">
        <button class="vv-act-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
          Exportar video
        </button>
        <a class="vv-act-btn wa" href="{{ route('mensajes', [
          'canal' => 'whatsapp',
          'paciente' => 'Maria Gonzales',
          'estudio' => 'Endoscopia Digestiva Alta',
          'video' => 'EDD-2025-001245',
          'fecha' => '15/07/2025 10:30 AM',
          'diagnostico' => 'Gastritis antral leve',
        ]) }}">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          Enviar por WhatsApp
        </a>
        <button class="vv-act-btn ia">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
          IA Reportes
        </button>
      </div>

      {{-- Miniaturas --}}
      <div>
        <div class="vv-caps-title">Imágenes capturadas del estudio</div>
        <div class="vv-caps-strip" id="vvStrip">
          @php
          $caps = [
            ['n'=>1,'ts'=>'0:01:25'],['n'=>2,'ts'=>'0:02:15'],['n'=>3,'ts'=>'0:04:32'],
            ['n'=>4,'ts'=>'0:06:18'],['n'=>5,'ts'=>'0:08:47'],['n'=>6,'ts'=>'0:11:03'],
          ];
          $bgs = [
            'radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%)',
            'radial-gradient(ellipse at 40% 60%,#4a1a0a 0%,#0c0612 100%)',
            'radial-gradient(ellipse at 60% 40%,#2a1a3a 0%,#060814 100%)',
            'radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%)',
            'radial-gradient(ellipse at 45% 55%,#1a0a2a 0%,#08060e 100%)',
            'radial-gradient(ellipse at 55% 45%,#4a0a0a 0%,#0c0608 100%)',
          ];
          @endphp
          @foreach($caps as $i => $c)
          <div class="vv-cap-item {{ $i === 1 ? 'sel' : '' }}" data-ts="{{ $c['ts'] }}">
            <div class="vv-cap-thumb" style="background:{{ $bgs[$i] }}">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <span class="vv-cap-num">{{ $c['n'] }}</span>
              <span class="vv-cap-check">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
            <div class="vv-cap-ts">{{ $c['ts'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

    </div>

    {{-- ===== SIDEBAR INFO ===== --}}
    <div class="vv-side">

      {{-- Datos del paciente --}}
      <div class="vv-info-card">
        <div class="vv-info-row">
          <div class="vv-info-lbl">Paciente</div>
          <div class="vv-info-val">Maria Gonzales</div>
        </div>
        <div class="vv-info-row">
          <div class="vv-info-lbl">ID Paciente</div>
          <div class="vv-info-val">00012345</div>
        </div>
        <div class="vv-info-row">
          <div class="vv-info-lbl">Fecha de estudio</div>
          <div class="vv-info-val">15/07/2025 · 10:30 AM</div>
        </div>
        <div class="vv-info-row">
          <div class="vv-info-lbl">Tipo de estudio</div>
          <div class="vv-info-val">Endoscopia Digestiva Alta</div>
        </div>
        <div class="vv-info-row">
          <div class="vv-info-lbl">Médico</div>
          <div class="vv-info-val">Dr. Victor</div>
        </div>
        <div class="vv-info-row">
          <div class="vv-info-lbl">Equipo</div>
          <div class="vv-info-val">Pentax EPK-i7010</div>
        </div>
        <div class="vv-info-row">
          <div class="vv-info-lbl">Estado</div>
          <div class="vv-status">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            Completado
          </div>
        </div>
      </div>

      {{-- Diagnóstico --}}
      <div class="vv-info-card">
        <div class="vv-section-head">
          <span class="vv-section-lbl">Diagnostico</span>
        </div>
        <div class="vv-diag-row">
          <span class="vv-diag-txt">Gastritis antral leve</span>
          <div class="vv-diag-av">C</div>
        </div>
      </div>

      {{-- Observaciones --}}
      <div class="vv-info-card">
        <div class="vv-section-head">
          <span class="vv-section-lbl">Observaciones</span>
          <svg class="vv-edit-ic" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <p class="vv-obs-txt">Sin complicaciones.<br>Buena tolerancia al procedimiento.</p>
      </div>

      {{-- Etiquetas --}}
      <div class="vv-info-card">
        <div class="vv-section-head">
          <span class="vv-section-lbl">Etiquetas</span>
          <svg class="vv-edit-ic" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <div class="vv-tags">
          <span class="vv-tag">Estomago</span>
          <span class="vv-tag">Prancirias
          <span class="vv-tag">Antro</span>
          <span class="vv-tag">Gastritis</span>
          <span class="vv-tag">Piloro</span>
          <span class="vv-tag">Duodeno</span>
        </div>
      </div>

    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
  /* Play / Pausa toggle (simulado) */
  let playing = false;
  const speeds = ['0.5x','0.75x','1.0x','1.25x','1.5x','2.0x'];
  let sIdx = 2;

  function togglePlay(){
    playing = !playing;
    [document.getElementById('vvPlayBig'), document.getElementById('vvPlayBtn')]
      .forEach(btn => {
        btn.querySelector('.play-icon').style.display  = playing ? 'none'  : '';
        btn.querySelector('.pause-icon').style.display = playing ? ''      : 'none';
      });
    document.getElementById('vvCenterLabel').style.display = playing ? 'none' : '';
  }

  document.getElementById('vvPlayBig').addEventListener('click', togglePlay);
  document.getElementById('vvPlayBtn').addEventListener('click', togglePlay);

  /* Velocidad */
  document.getElementById('vvSpeed').addEventListener('click', function(){
    sIdx = (sIdx + 1) % speeds.length;
    this.textContent = speeds[sIdx];
  });

  /* Selección de miniatura */
  document.querySelectorAll('.vv-cap-item').forEach(item => {
    item.addEventListener('click', function(){
      document.querySelectorAll('.vv-cap-item').forEach(i => i.classList.remove('sel'));
      this.classList.add('sel');
      document.getElementById('vvTime').textContent = this.dataset.ts + ' / 00:15:42';
    });
  });
  /* ── Modal descarga video ── */
  const vvDlOv = document.getElementById('vvDlOverlay');
  function abrirVvDl(){ vvDlOv.classList.add('open'); document.body.style.overflow='hidden'; }
  function cerrarVvDl(){ vvDlOv.classList.remove('open'); document.body.style.overflow=''; }

  document.querySelector('.vv-btn.dl').addEventListener('click', abrirVvDl);
  document.getElementById('vvDlClose') .addEventListener('click', cerrarVvDl);
  document.getElementById('vvDlCancel').addEventListener('click', cerrarVvDl);
  vvDlOv.addEventListener('click', function(e){ if(e.target===this) cerrarVvDl(); });
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') cerrarVvDl(); });

  /* Rango */
  document.querySelectorAll('.vv-rng-row').forEach(row => {
    row.addEventListener('click', function(){
      document.querySelectorAll('.vv-rng-row').forEach(r => r.classList.remove('sel'));
      this.classList.add('sel');
      const custom = document.getElementById('vvRngCustom');
      custom.classList.toggle('show', this.dataset.rng === 'custom');
    });
  });

  /* Calidad → resolución */
  const qualRes = {'1080':'Resolución: 1920 x 1080','720':'Resolución: 1280 x 720','480':'Resolución: 854 x 480'};
  document.getElementById('vvDlQual').addEventListener('change', function(){
    document.getElementById('vvQualRes').textContent = qualRes[this.value];
  });

  /* Formato */
  document.querySelectorAll('.vv-fmt-card').forEach(card => {
    card.addEventListener('click', function(){
      document.querySelectorAll('.vv-fmt-card').forEach(c => c.classList.remove('sel'));
      this.classList.add('sel');
    });
  });

  /* Checkboxes incluir */
  document.querySelectorAll('.vv-inc-row').forEach(row => {
    row.addEventListener('click', function(){ this.classList.toggle('checked'); });
  });

  /* Confirmar (simulado) */
  document.getElementById('vvDlConfirm').addEventListener('click', function(){
    this.textContent = '✓ Descargando...';
    this.style.background = 'var(--green)';
    setTimeout(() => {
      this.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Descargar video';
      this.style.background = '';
      cerrarVvDl();
    }, 2000);
  });

})();
</script>
@endpush
