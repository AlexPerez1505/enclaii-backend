@extends('layouts.app')

@section('title', 'Ver Imagen')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')
@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="color:var(--txt-soft);font-size:13px">Maria Gonzales</span>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <a href="{{ route('galeria.video', 1) }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Video EDD-2025-001245</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600" id="viHeaderLabel">Imagen 4</span>
@endsection

@push('styles')
<style>
/* ===== VER IMAGEN ===== */
.vi-wrap{display:grid;grid-template-columns:1fr 280px;gap:18px;align-items:start}

/* Topbar */
.vi-topbar{display:flex;align-items:center;gap:8px;margin-bottom:14px}
.vi-btn{
  display:flex;align-items:center;gap:7px;
  height:38px;padding:0 16px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:600;
  transition:background-color 150ms ease,transform 160ms var(--ease-out);
}
.vi-btn:active{transform:scale(.97)}
.vi-btn.back{background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.vi-btn.back:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.vi-topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
.vi-btn.report{background:rgba(46,123,246,.14);border:1px solid rgba(46,123,246,.35);color:var(--blue)}
@media(hover:hover)and(pointer:fine){.vi-btn.report:hover{background:rgba(46,123,246,.25)}}
.vi-btn.share{background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.vi-btn.share:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.vi-btn.dl{background:var(--blue);border:none;color:#fff}
@media(hover:hover)and(pointer:fine){.vi-btn.dl:hover{opacity:.88}}
.vi-btn.more{width:38px;padding:0;justify-content:center;background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.vi-btn.more:hover{background:rgba(110,160,255,.08)}}

/* Visor de imagen */
.vi-viewer-box{
  background:#000;border-radius:14px;overflow:hidden;
  position:relative;aspect-ratio:4/3;
  display:flex;align-items:center;justify-content:center;
}
.vi-img-bg{
  position:absolute;inset:0;
  background:radial-gradient(ellipse at 50% 45%,#6a1a10 0%,#3a0808 35%,#0a0410 70%,#06081c 100%);
}
.vi-img-placeholder{
  position:relative;z-index:2;
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;
  color:rgba(255,255,255,.25);font-size:13px;
}
/* Badge contador */
.vi-counter-badge{
  position:absolute;top:14px;left:14px;z-index:4;
  background:rgba(0,0,0,.55);backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,.12);
  border-radius:8px;padding:5px 12px;
  font-size:12.5px;font-weight:600;color:rgba(255,255,255,.85);
}
/* Thumb preview */
.vi-thumb-preview{
  position:absolute;top:14px;right:14px;z-index:4;
  width:72px;height:54px;border-radius:8px;overflow:hidden;
  border:2px solid rgba(255,255,255,.2);
  background:radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%);
  display:grid;place-items:center;
}
/* Meta overlay */
.vi-meta-overlay{
  position:absolute;bottom:10px;left:14px;z-index:4;
  background:rgba(0,0,0,.55);backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,.1);border-radius:8px;
  padding:5px 12px;
}
.vi-meta-res{font-size:11.5px;font-weight:700;color:rgba(255,255,255,.75)}
.vi-meta-ts{font-size:10.5px;color:rgba(255,255,255,.45)}
/* Controles zoom */
.vi-zoom-ctrl{
  position:absolute;left:14px;top:50%;translate:0 -50%;z-index:4;
  display:flex;flex-direction:column;gap:4px;
}
.vi-zoom-btn{
  width:30px;height:30px;border-radius:8px;
  background:rgba(0,0,0,.55);backdrop-filter:blur(6px);
  border:1px solid rgba(255,255,255,.14);
  display:grid;place-items:center;
  color:rgba(255,255,255,.8);font-size:15px;font-weight:700;
  cursor:pointer;transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vi-zoom-btn:hover{background:rgba(46,123,246,.5)}}
.vi-zoom-pct{
  width:30px;text-align:center;font-size:10px;font-weight:700;
  color:rgba(255,255,255,.6);padding:2px 0;
}

/* Barra de herramientas */
.vi-toolbar{
  display:flex;align-items:center;gap:6px;flex-wrap:wrap;
  padding:10px 0;border-bottom:1px solid var(--stroke);margin-bottom:14px;
}
.vi-tool-btn{
  display:flex;align-items:center;gap:6px;
  height:34px;padding:0 13px;border-radius:var(--r-md);
  font:inherit;font-size:12.5px;font-weight:600;color:var(--txt-soft);
  background:transparent;border:1px solid transparent;
  transition:background-color 150ms ease,border-color 150ms ease,color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vi-tool-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.35);color:var(--blue)}}
.vi-tool-btn.on{background:rgba(46,123,246,.15);border-color:rgba(46,123,246,.5);color:var(--blue)}

/* Tira de imágenes */
.vi-strip-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
.vi-strip-title{font-size:13px;font-weight:600}
.vi-strip-nav{display:flex;align-items:center;gap:4px}
.vi-strip-arrow{
  width:28px;height:28px;border-radius:7px;border:1px solid var(--stroke);
  display:grid;place-items:center;color:var(--txt-soft);
  transition:background-color 150ms ease,color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vi-strip-arrow:hover{background:rgba(110,160,255,.1);color:var(--txt)}}
.vi-strip{display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;scrollbar-width:thin;scrollbar-color:var(--stroke) transparent}
.vi-strip-item{
  flex:none;width:100px;cursor:pointer;
  border-radius:7px;overflow:hidden;border:2px solid transparent;
  transition:border-color 150ms ease,transform 150ms ease;position:relative;
}
.vi-strip-item:active{transform:scale(.96)}
.vi-strip-item.sel{border-color:var(--blue)}
@media(hover:hover)and(pointer:fine){.vi-strip-item:not(.sel):hover{border-color:rgba(46,123,246,.45)}}
.vi-strip-thumb{
  width:100%;aspect-ratio:4/3;display:grid;place-items:center;position:relative;
}
.vi-strip-num{
  position:absolute;top:4px;left:5px;
  width:18px;height:18px;border-radius:5px;
  background:rgba(0,0,0,.6);display:grid;place-items:center;
  font-size:9px;font-weight:700;color:#fff;
}
.vi-strip-del{
  position:absolute;top:4px;right:4px;
  width:18px;height:18px;border-radius:50%;
  background:rgba(0,0,0,.55);display:grid;place-items:center;
  color:rgba(255,255,255,.6);cursor:pointer;font-size:11px;font-weight:700;
  transition:background-color 150ms ease,color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vi-strip-del:hover{background:rgba(255,90,110,.6);color:#fff}}
.vi-strip-item.sel .vi-strip-del{background:rgba(46,123,246,.6);color:#fff}
.vi-strip-ts{font-size:9.5px;color:var(--txt-soft);text-align:center;padding:3px 0 2px}

/* Observaciones médico */
.vi-obs-section{margin-top:14px}
.vi-obs-lbl{font-size:13px;font-weight:600;margin-bottom:8px}
.vi-obs-area{
  width:100%;min-height:72px;padding:12px 14px;
  font:inherit;font-size:13px;color:var(--txt);
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-md);outline:none;resize:vertical;
  transition:border-color 150ms ease;line-height:1.5;
}
.vi-obs-area::placeholder{color:var(--txt-soft)}
.vi-obs-area:focus{border-color:var(--blue)}
.vi-obs-footer{display:flex;justify-content:flex-end;margin-top:8px}
.vi-obs-save{
  display:flex;align-items:center;gap:6px;
  height:36px;padding:0 18px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:600;
  background:var(--blue);border:none;color:#fff;
  transition:opacity 150ms ease,transform 160ms var(--ease-out);
}
.vi-obs-save:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.vi-obs-save:hover{opacity:.88}}

/* Sidebar */
.vi-side{display:flex;flex-direction:column;gap:14px}
.vi-card{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:16px}
.vi-card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.vi-card-title{font-family:'Sora',sans-serif;font-size:13px;font-weight:700}
.vi-edit-ic{color:var(--txt-soft);cursor:pointer;transition:color 150ms ease}
@media(hover:hover)and(pointer:fine){.vi-edit-ic:hover{color:var(--blue)}}

/* Info tabla */
.vi-info-table{display:grid;grid-template-columns:auto 1fr;gap:4px 14px}
.vi-it-lbl{font-size:11.5px;color:var(--txt-soft);padding:1px 0}
.vi-it-val{font-size:12.5px;font-weight:600;color:var(--txt);padding:1px 0}

/* IA Hallazgos */
.vi-ia-badge{
  display:inline-flex;align-items:center;gap:5px;
  padding:2px 9px;border-radius:99px;font-size:11px;font-weight:700;
  background:rgba(56,199,244,.12);color:var(--cyan);border:1px solid rgba(56,199,244,.3);
  margin-left:8px;
}
.vi-ia-row{display:flex;align-items:center;justify-content:space-between;padding:5px 0;border-bottom:1px solid var(--stroke)}
.vi-ia-row:last-of-type{border-bottom:none}
.vi-ia-dot{width:8px;height:8px;border-radius:50%;background:var(--green);flex:none}
.vi-ia-name{font-size:12.5px;font-weight:600;flex:1;margin-left:7px}
.vi-ia-conf{font-size:11.5px;color:var(--txt-soft)}
.vi-ia-analyze{
  width:100%;height:36px;margin-top:10px;border-radius:var(--r-md);
  border:1px solid rgba(56,199,244,.35);background:rgba(56,199,244,.08);
  font:inherit;font-size:12.5px;font-weight:600;color:var(--cyan);
  transition:background-color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vi-ia-analyze:hover{background:rgba(56,199,244,.18)}}

/* Tags */
.vi-tags{display:flex;flex-wrap:wrap;gap:7px}
.vi-tag{
  padding:4px 12px;border-radius:99px;font-size:12px;font-weight:600;
  background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vi-tag:hover{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.4);color:var(--blue)}}

/* Observaciones side */
.vi-side-obs{font-size:13px;color:var(--txt-soft);line-height:1.6}

@media(max-width:960px){.vi-wrap{grid-template-columns:1fr}}

/* ===== MODAL DESCARGA ===== */
.vi-dl-overlay{
  position:fixed;inset:0;z-index:900;
  background:rgba(0,0,0,.6);backdrop-filter:blur(4px);
  display:flex;align-items:center;justify-content:center;
  opacity:0;pointer-events:none;
  transition:opacity 200ms ease;
}
.vi-dl-overlay.open{opacity:1;pointer-events:auto}
.vi-dl-modal{
  background:var(--panel);border:1px solid var(--stroke);
  border-radius:18px;width:760px;max-width:95vw;
  box-shadow:0 24px 64px rgba(0,0,0,.5);
  transform:scale(.94);transition:transform 200ms var(--ease-out);
  overflow:hidden;
}
.vi-dl-overlay.open .vi-dl-modal{transform:scale(1)}
.vi-dl-header{
  display:flex;align-items:center;justify-content:space-between;
  padding:18px 22px 0;
}
.vi-dl-title{font-family:'Sora',sans-serif;font-size:16px;font-weight:700;display:flex;align-items:center;gap:10px}
.vi-dl-title svg{color:var(--blue)}
.vi-dl-sub{font-size:12.5px;color:var(--txt-soft);margin-top:2px}
.vi-dl-close{
  width:32px;height:32px;border-radius:8px;border:1px solid var(--stroke);
  display:grid;place-items:center;color:var(--txt-soft);
  transition:background-color 150ms ease,color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vi-dl-close:hover{background:rgba(255,90,110,.12);color:var(--red)}}
.vi-dl-body{
  display:grid;grid-template-columns:1fr 1fr;gap:0;
  padding:18px 22px 20px;
}
.vi-dl-preview{
  padding-right:18px;border-right:1px solid var(--stroke);
}
.vi-dl-preview-lbl{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:10px}
.vi-dl-thumb{
  width:100%;aspect-ratio:4/3;border-radius:10px;overflow:hidden;
  position:relative;
  background:radial-gradient(ellipse at 50% 45%,#6a1a10 0%,#3a0808 35%,#0a0410 70%,#06081c 100%);
  display:grid;place-items:center;margin-bottom:10px;
}
.vi-dl-thumb-badge{
  position:absolute;top:8px;right:8px;
  background:rgba(0,0,0,.6);backdrop-filter:blur(6px);
  border:1px solid rgba(255,255,255,.12);border-radius:6px;
  padding:3px 8px;font-size:10.5px;font-weight:700;color:rgba(255,255,255,.8);
}
.vi-dl-watermark{
  position:absolute;bottom:10px;left:50%;translate:-50% 0;
  display:none;align-items:center;gap:6px;
  background:rgba(0,0,0,.5);backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,.15);border-radius:8px;
  padding:5px 12px;
  pointer-events:none;
}
.vi-dl-watermark.show{display:flex}
.vi-dl-watermark-logo{
  font-family:'Sora',sans-serif;font-size:11px;font-weight:900;
  letter-spacing:.08em;color:#fff;
}
.vi-dl-watermark-logo span{color:var(--blue)}
.vi-dl-watermark-dot{width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.35)}
.vi-dl-watermark-sub{font-size:9.5px;color:rgba(255,255,255,.5);font-weight:600;letter-spacing:.04em}
.vi-dl-thumb-meta{
  font-size:11.5px;color:var(--txt-soft);
  display:flex;align-items:center;gap:8px;
}
.vi-dl-thumb-meta span{color:var(--txt);font-weight:600}
.vi-dl-opts{padding-left:18px}
.vi-dl-opts-lbl{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:10px}
/* Formatos */
.vi-fmt-list{display:flex;flex-direction:column;gap:7px;margin-bottom:16px}
.vi-fmt-item{
  display:flex;align-items:center;justify-content:space-between;
  padding:10px 14px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--card);
  cursor:pointer;transition:border-color 150ms ease,background-color 150ms ease;
}
.vi-fmt-item.sel{border-color:var(--blue);background:rgba(46,123,246,.1)}
@media(hover:hover)and(pointer:fine){.vi-fmt-item:not(.sel):hover{border-color:rgba(46,123,246,.4);background:rgba(46,123,246,.06)}}
.vi-fmt-left{display:flex;align-items:center;gap:10px}
.vi-fmt-ic{width:30px;height:30px;border-radius:7px;background:rgba(255,255,255,.07);display:grid;place-items:center;color:var(--txt-soft);flex:none}
.vi-fmt-name{font-size:13px;font-weight:700}
.vi-fmt-desc{font-size:11px;color:var(--txt-soft)}
.vi-fmt-badge{
  font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;
  background:rgba(61,220,151,.12);color:var(--green);border:1px solid rgba(61,220,151,.3);
}
.vi-fmt-tag{
  font-size:10px;font-weight:600;color:var(--txt-soft);
  padding:2px 8px;border-radius:99px;
  border:1px solid var(--stroke);background:var(--panel-2);
}
.vi-fmt-check{width:18px;height:18px;border-radius:50%;border:2px solid var(--stroke);display:grid;place-items:center;flex:none;transition:background-color 150ms ease,border-color 150ms ease}
.vi-fmt-item.sel .vi-fmt-check{background:var(--blue);border-color:var(--blue)}
/* Calidad */
.vi-qual-lbl{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:7px}
.vi-qual-select{
  width:100%;height:38px;background:var(--card);border:1px solid var(--stroke);
  border-radius:var(--r-md);padding:0 12px;font:inherit;font-size:13px;
  color:var(--txt);outline:none;cursor:pointer;margin-bottom:14px;
  transition:border-color 150ms ease;
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238FA3CF' stroke-width='2.5' stroke-linecap='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 12px center;
  padding-right:32px;
}
.vi-qual-select:focus{border-color:var(--blue)}
/* Incluir */
.vi-inc-lbl{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:8px}
.vi-inc-row{display:flex;align-items:center;gap:9px;margin-bottom:7px;cursor:pointer}
.vi-inc-row:last-child{margin-bottom:0}
.vi-inc-cb{
  width:18px;height:18px;border-radius:5px;flex:none;
  border:2px solid var(--stroke);display:grid;place-items:center;
  transition:background-color 150ms ease,border-color 150ms ease;
}
.vi-inc-row.checked .vi-inc-cb{background:var(--blue);border-color:var(--blue)}
.vi-inc-label{font-size:13px;color:var(--txt)}
/* Footer */
.vi-dl-footer{
  padding:14px 22px 18px;
  border-top:1px solid var(--stroke);
  display:flex;align-items:center;justify-content:space-between;
  gap:10px;
}
.vi-dl-footer-note{font-size:12px;color:var(--txt-soft);display:flex;align-items:center;gap:6px}
.vi-dl-footer-btns{display:flex;align-items:center;gap:8px}
.vi-dl-cancel{
  height:38px;padding:0 18px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:transparent;
  font:inherit;font-size:13px;font-weight:600;color:var(--txt-soft);
  transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vi-dl-cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.vi-dl-confirm{
  height:38px;padding:0 22px;border-radius:var(--r-md);
  border:none;background:var(--blue);
  font:inherit;font-size:13px;font-weight:700;color:#fff;
  display:flex;align-items:center;gap:8px;
  transition:opacity 150ms ease,transform 160ms var(--ease-out);
}
.vi-dl-confirm:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.vi-dl-confirm:hover{opacity:.88}}
</style>
@endpush

@section('content')

@php
$caps = [
  ['n'=>1,'ts'=>'0:01:25','bg'=>'radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%)'],
  ['n'=>2,'ts'=>'0:02:15','bg'=>'radial-gradient(ellipse at 40% 60%,#4a1a0a 0%,#0c0612 100%)'],
  ['n'=>3,'ts'=>'0:04:32','bg'=>'radial-gradient(ellipse at 60% 40%,#2a1a3a 0%,#060814 100%)'],
  ['n'=>4,'ts'=>'0:06:18','bg'=>'radial-gradient(ellipse at 50% 50%,#5a1810 0%,#0a0610 100%)'],
  ['n'=>5,'ts'=>'0:08:47','bg'=>'radial-gradient(ellipse at 45% 55%,#1a0a2a 0%,#08060e 100%)'],
  ['n'=>6,'ts'=>'0:11:03','bg'=>'radial-gradient(ellipse at 55% 45%,#4a0a0a 0%,#0c0608 100%)'],
];
$current = 3; // índice 0-based, imagen 4
@endphp

<div class="rise d2">

  {{-- Topbar --}}
  <div class="vi-topbar">
    <a href="{{ route('galeria') }}" class="vi-btn back">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver a la galería
    </a>
    <div class="vi-topbar-right">
      <button class="vi-btn report">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Agregar al reporte
      </button>
      <button class="vi-btn share">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
        Compartir
      </button>
      <button class="vi-btn dl">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Descargar
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <button class="vi-btn more">···</button>
    </div>
  </div>

  <div class="vi-wrap">

    {{-- ===== COLUMNA PRINCIPAL ===== --}}
    <div>

      {{-- Visor --}}
      <div class="vi-viewer-box" id="viViewer">
        <div class="vi-img-bg" id="viBg"></div>

        {{-- Badge contador --}}
        <div class="vi-counter-badge" id="viCounter">Imagen 4 de 6</div>

        {{-- Thumbnail preview --}}
        <div class="vi-thumb-preview">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
        </div>

        {{-- Placeholder imagen --}}
        <div class="vi-img-placeholder">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
        </div>

        {{-- Controles zoom --}}
        <div class="vi-zoom-ctrl">
          <button class="vi-zoom-btn" id="viZoomPlus">+</button>
          <div class="vi-zoom-pct" id="viZoomPct">148%</div>
          <button class="vi-zoom-btn" id="viZoomMinus">−</button>
          <button class="vi-zoom-btn" id="viZoomFit" title="Ajustar">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          </button>
        </div>

        {{-- Meta overlay --}}
        <div class="vi-meta-overlay" id="viMeta">
          <div class="vi-meta-res">1920 x 1080</div>
          <div class="vi-meta-ts" id="viMetaTs">00:08:47</div>
        </div>
      </div>

      {{-- Toolbar --}}
      <div class="vi-toolbar">
        <button class="vi-tool-btn" id="viToolZoom">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
          Zoom
        </button>
        <button class="vi-tool-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a10 10 0 0 1 0 20"/></svg>
          Medir
        </button>
        <button class="vi-tool-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/></svg>
          Anotar
        </button>
        <button class="vi-tool-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          Comparar
        </button>
        <button class="vi-tool-btn on" id="viToolIA">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
          IA Hallazgos
        </button>
        <button class="vi-tool-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
          Imprimir
        </button>
        <button class="vi-tool-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          Pantalla completa
        </button>
      </div>

      {{-- Tira de imágenes --}}
      <div>
        <div class="vi-strip-head">
          <span class="vi-strip-title">Imágenes del estudio ({{ count($caps) }})</span>
          <div class="vi-strip-nav">
            <button class="vi-strip-arrow" id="viPrev">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="vi-strip-arrow" id="viNext">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>
        </div>
        <div class="vi-strip" id="viStrip">
          @foreach($caps as $i => $c)
          <div class="vi-strip-item {{ $i === $current ? 'sel' : '' }}"
               data-idx="{{ $i }}"
               data-ts="{{ $c['ts'] }}"
               data-bg="{{ $c['bg'] }}">
            <div class="vi-strip-thumb" style="background:{{ $c['bg'] }}">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <span class="vi-strip-num">{{ $c['n'] }}</span>
              <span class="vi-strip-del" title="Eliminar">×</span>
            </div>
            <div class="vi-strip-ts">{{ $c['ts'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Observaciones médico --}}
      <div class="vi-obs-section">
        <div class="vi-obs-lbl">Observaciones del médico</div>
        <textarea class="vi-obs-area" id="viObsArea" placeholder="Escribe tus observaciones aquí..."></textarea>
        <div class="vi-obs-footer">
          <button class="vi-obs-save" id="viObsSave">Guardar observación</button>
        </div>
      </div>

    </div>

    {{-- ===== SIDEBAR ===== --}}
    <div class="vi-side">

      {{-- Información de la imagen --}}
      <div class="vi-card">
        <div class="vi-card-head">
          <span class="vi-card-title">Información de la imagen</span>
        </div>
        <div class="vi-info-table">
          <span class="vi-it-lbl">ID de imagen</span>   <span class="vi-it-val">IMG-0004</span>
          <span class="vi-it-lbl">Fecha de captura</span><span class="vi-it-val">15/07/2025 · 10:30 AM</span>
          <span class="vi-it-lbl">Tipo de estudio</span> <span class="vi-it-val">Endoscopia Digestiva Alta</span>
          <span class="vi-it-lbl">Equipo</span>          <span class="vi-it-val">Pentax EPK-i7010</span>
          <span class="vi-it-lbl">Resolución</span>      <span class="vi-it-val">1920 x 1080</span>
          <span class="vi-it-lbl">Duración del video</span><span class="vi-it-val">00:15:42</span>
          <span class="vi-it-lbl">Fotograma</span>       <span class="vi-it-val" id="viInfoTs">00:08:47</span>
        </div>
      </div>

      {{-- IA Hallazgos --}}
      <div class="vi-card">
        <div class="vi-card-head">
          <div style="display:flex;align-items:center">
            <span class="vi-card-title">IA Hallazgos</span>
            <span class="vi-ia-badge">Beta</span>
          </div>
          <svg class="vi-edit-ic" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="vi-ia-row">
          <span class="vi-ia-dot"></span>
          <span class="vi-ia-name">Gastritis antral leve</span>
          <span class="vi-ia-conf">Confianza: 92%</span>
        </div>
        <div class="vi-ia-row">
          <span class="vi-ia-dot" style="background:var(--orange)"></span>
          <span class="vi-ia-name">Eritema leve</span>
          <span class="vi-ia-conf">Confianza: 88%</span>
        </div>
        <div class="vi-ia-row">
          <span class="vi-ia-dot"></span>
          <span class="vi-ia-name">Sin úlceras visibles</span>
          <span class="vi-ia-conf">Confianza: 95%</span>
        </div>
        <button class="vi-ia-analyze">Ver análisis detallado</button>
      </div>

      {{-- Etiquetas --}}
      <div class="vi-card">
        <div class="vi-card-head">
          <span class="vi-card-title">Etiquetas</span>
          <svg class="vi-edit-ic" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <div class="vi-tags">
          <span class="vi-tag">Estómago</span>
          <span class="vi-tag">Antro</span>
          <span class="vi-tag">Gastritis</span>
          <span class="vi-tag">Piloro</span>
          <span class="vi-tag">Duodeno</span>
        </div>
      </div>

      {{-- Observaciones --}}
      <div class="vi-card">
        <div class="vi-card-head">
          <span class="vi-card-title">Observaciones</span>
          <svg class="vi-edit-ic" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <p class="vi-side-obs">Sin complicaciones.<br>Buena tolerancia al procedimiento.</p>
      </div>

    </div>
  </div>
</div>

{{-- ===== MODAL DESCARGA ===== --}}
<div class="vi-dl-overlay" id="viDlOverlay">
  <div class="vi-dl-modal">
    <div class="vi-dl-header">
      <div>
        <div class="vi-dl-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Descargar imagen
        </div>
        <div class="vi-dl-sub">Selecciona el formato y las opciones de descarga</div>
      </div>
      <button class="vi-dl-close" id="viDlClose">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="vi-dl-body">

      {{-- Vista previa --}}
      <div class="vi-dl-preview">
        <div class="vi-dl-preview-lbl">Vista previa</div>
        <div class="vi-dl-thumb">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.2)" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          <div class="vi-dl-thumb-badge">IMG-0004</div>
          <div class="vi-dl-watermark" id="viWatermark">
            <div class="vi-dl-watermark-logo">ENCLA<span>II</span></div>
            <div class="vi-dl-watermark-dot"></div>
            <div class="vi-dl-watermark-sub">Endoscopia · IA</div>
          </div>
        </div>
        <div class="vi-dl-thumb-meta">
          <span>1920 x 1080</span> · <span id="viDlFmt">JPG</span> · <span>2.4 MB</span>
        </div>
      </div>

      {{-- Opciones --}}
      <div class="vi-dl-opts">
        <div class="vi-dl-opts-lbl">Formato de archivo</div>
        <div class="vi-fmt-list">
          <div class="vi-fmt-item sel" data-fmt="JPG">
            <div class="vi-fmt-left">
              <div class="vi-fmt-ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></div>
              <div>
                <div class="vi-fmt-name">JPG</div>
                <div class="vi-fmt-desc">Imagen de alta calidad</div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
              <span class="vi-fmt-badge">Recomendado</span>
              <div class="vi-fmt-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
            </div>
          </div>
          <div class="vi-fmt-item" data-fmt="PNG">
            <div class="vi-fmt-left">
              <div class="vi-fmt-ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></div>
              <div>
                <div class="vi-fmt-name">PNG</div>
                <div class="vi-fmt-desc">Máxima calidad (sin compresión)</div>
              </div>
            </div>
            <div class="vi-fmt-check"></div>
          </div>
          <div class="vi-fmt-item" data-fmt="DICOM">
            <div class="vi-fmt-left">
              <div class="vi-fmt-ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
              <div>
                <div class="vi-fmt-name">DICOM</div>
                <div class="vi-fmt-desc">Formato médico (.dcm)</div>
              </div>
            </div>
            <div class="vi-fmt-check"></div>
          </div>
          <div class="vi-fmt-item" data-fmt="PDF">
            <div class="vi-fmt-left">
              <div class="vi-fmt-ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></div>
              <div>
                <div class="vi-fmt-name">PDF</div>
                <div class="vi-fmt-desc">Incluir en reporte</div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
              <span class="vi-fmt-tag">(1 imagen)</span>
              <div class="vi-fmt-check"></div>
            </div>
          </div>
        </div>

        <div class="vi-qual-lbl">Calidad de imagen</div>
        <select class="vi-qual-select" id="viDlQual">
          <option value="alta">Alta (1920 x 1080)</option>
          <option value="media">Media (1280 x 720)</option>
          <option value="baja">Baja (640 x 360)</option>
        </select>

        <div class="vi-inc-lbl">Qué deseas incluir</div>
        <div class="vi-inc-row checked" id="viIncEstudio">
          <div class="vi-inc-cb"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <span class="vi-inc-label">Información del estudio</span>
        </div>
        <div class="vi-inc-row checked" id="viIncPaciente">
          <div class="vi-inc-cb"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <span class="vi-inc-label">Datos del paciente</span>
        </div>
        <div class="vi-inc-row" id="viIncMarca">
          <div class="vi-inc-cb"></div>
          <span class="vi-inc-label">Marca de agua Enclaii</span>
        </div>
      </div>
    </div>

    <div class="vi-dl-footer">
      <div class="vi-dl-footer-note">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        La imagen se descargará de forma segura
      </div>
      <div class="vi-dl-footer-btns">
        <button class="vi-dl-cancel" id="viDlCancel">Cancelar</button>
        <button class="vi-dl-confirm" id="viDlConfirm">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Descargar imagen
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
  const caps = @json($caps);
  let current = {{ $current }};
  const total  = caps.length;

  /* ── helpers ── */
  function goTo(idx){
    if(idx < 0 || idx >= total) return;
    current = idx;
    const c = caps[idx];
    /* fondo viewer */
    document.getElementById('viBg').style.background = c.bg;
    /* meta */
    document.getElementById('viMetaTs').textContent  = c.ts;
    document.getElementById('viInfoTs').textContent  = c.ts.replace(':','0:0').replace(/^(\d):/, '00:0$1:');
    /* contador */
    document.getElementById('viCounter').textContent = 'Imagen ' + (idx+1) + ' de ' + total;
    /* header label */
    document.getElementById('viHeaderLabel').textContent = 'Imagen ' + (idx+1);
    /* tira */
    document.querySelectorAll('.vi-strip-item').forEach(el => {
      el.classList.toggle('sel', parseInt(el.dataset.idx) === idx);
    });
  }

  /* Tira clic */
  document.querySelectorAll('.vi-strip-item').forEach(item => {
    item.addEventListener('click', function(e){
      if(e.target.classList.contains('vi-strip-del')) return;
      goTo(parseInt(this.dataset.idx));
    });
  });

  /* Eliminar de tira (sólo visual) */
  document.querySelectorAll('.vi-strip-del').forEach(btn => {
    btn.addEventListener('click', function(e){
      e.stopPropagation();
      const item = this.closest('.vi-strip-item');
      item.style.opacity = '0';
      item.style.transform = 'scale(.85)';
      item.style.transition = 'opacity 200ms ease,transform 200ms ease';
      setTimeout(() => item.remove(), 200);
    });
  });

  /* Flechas navegación */
  document.getElementById('viPrev').addEventListener('click', () => goTo(current - 1));
  document.getElementById('viNext').addEventListener('click', () => goTo(current + 1));
  document.addEventListener('keydown', e => {
    if(e.key === 'ArrowLeft')  goTo(current - 1);
    if(e.key === 'ArrowRight') goTo(current + 1);
  });

  /* Zoom */
  let zoom = 148;
  function setZoom(v){
    zoom = Math.min(Math.max(v, 50), 300);
    document.getElementById('viZoomPct').textContent = zoom + '%';
  }
  document.getElementById('viZoomPlus') .addEventListener('click', () => setZoom(zoom + 10));
  document.getElementById('viZoomMinus').addEventListener('click', () => setZoom(zoom - 10));
  document.getElementById('viZoomFit')  .addEventListener('click', () => setZoom(100));

  /* Toolbar toggle */
  document.querySelectorAll('.vi-tool-btn').forEach(btn => {
    btn.addEventListener('click', function(){
      this.classList.toggle('on');
    });
  });

  /* ── Modal descarga ── */
  const dlOverlay = document.getElementById('viDlOverlay');
  function abrirDl(){ dlOverlay.classList.add('open'); document.body.style.overflow='hidden'; }
  function cerrarDl(){ dlOverlay.classList.remove('open'); document.body.style.overflow=''; }

  document.querySelector('.vi-btn.dl').addEventListener('click', abrirDl);
  document.getElementById('viDlClose') .addEventListener('click', cerrarDl);
  document.getElementById('viDlCancel').addEventListener('click', cerrarDl);
  dlOverlay.addEventListener('click', function(e){ if(e.target === this) cerrarDl(); });
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') cerrarDl(); });

  /* Selección de formato */
  document.querySelectorAll('.vi-fmt-item').forEach(item => {
    item.addEventListener('click', function(){
      document.querySelectorAll('.vi-fmt-item').forEach(i => i.classList.remove('sel'));
      this.classList.add('sel');
      document.getElementById('viDlFmt').textContent = this.dataset.fmt;
    });
  });

  /* Toggle checkboxes incluir */
  document.querySelectorAll('.vi-inc-row').forEach(row => {
    row.addEventListener('click', function(){
      this.classList.toggle('checked');
      if(this.id === 'viIncMarca'){
        document.getElementById('viWatermark').classList.toggle('show', this.classList.contains('checked'));
      }
    });
  });

  /* Confirmar descarga (simulado) */
  document.getElementById('viDlConfirm').addEventListener('click', function(){
    const fmt = document.querySelector('.vi-fmt-item.sel').dataset.fmt;
    this.textContent = '✓ Descargando...';
    this.style.background = 'var(--green)';
    setTimeout(() => {
      this.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Descargar imagen';
      this.style.background = '';
      cerrarDl();
    }, 1800);
  });

  /* Guardar observación */
  document.getElementById('viObsSave').addEventListener('click', function(){
    const area = document.getElementById('viObsArea');
    if(!area.value.trim()) return;
    this.textContent = '✓ Guardado';
    this.style.background = 'var(--green)';
    setTimeout(() => { this.textContent = 'Guardar observación'; this.style.background = ''; }, 2000);
  });

  /* Init */
  goTo(current);
})();
</script>
@endpush
