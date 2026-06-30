@extends('layouts.app')

@section('title', 'Dashboard')
@section('active', 'dashboard')
@section('header-title', 'Buenos dias, Dr. Victor 👋')
@section('header-sub')
  Resumen general de tu actividad clinica
@endsection

@push('styles')
<style>
/* ============ WIDGET GRID SYSTEM ============ */

/* Contenedor principal de widgets */
#widgetGrid,
#widgetGridMinimal{
  display:grid;
  grid-template-columns:repeat(13,1fr);
  grid-auto-rows:minmax(60px,auto);
  gap:18px;
  align-items:start;
}

/* Widget wrapper */
.widget{
  position:relative;
  transition:opacity .25s ease, transform .28s cubic-bezier(.4,0,.2,1), box-shadow .2s ease;
  will-change:transform;
  min-height:0;
  z-index:1;
}
.widget:hover{z-index:5}
.widget.widget-resizing{z-index:50}
.widget.widget-hidden{
  display:none !important;
}
.widget.widget-dragging{
  opacity:.55;
  z-index:100;
  transform:scale(1.02);
  box-shadow:0 24px 60px rgba(0,0,0,.6) !important;
  cursor:grabbing;
}
.widget.widget-drop-target > .card{
  border-color:rgba(178,99,255,.6) !important;
  box-shadow:0 0 0 2px rgba(178,99,255,.25) !important;
}
.widget.widget-appear{
  animation:wAppear .32s cubic-bezier(.4,0,.2,1) both;
}
.widget.widget-disappear{
  animation:wDisappear .22s cubic-bezier(.4,0,.2,1) both;
}
@keyframes wAppear{from{opacity:0;transform:scale(.94) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)}}
@keyframes wDisappear{from{opacity:1;transform:scale(1)}to{opacity:0;transform:scale(.94) translateY(8px)}}

/* Layout responsive para widgets originales */
.widget:not(.widget-minimal) > .card{
  height:100%;
  display:flex;
  flex-direction:column;
  padding:1.25em;
  font-size: clamp(12px, min(calc(var(--widget-w-px, 300) * 0.055px), calc(var(--widget-h-px, 260) * 0.065px)), 18px);
}
.widget:not(.widget-minimal) > .card h3{margin-bottom:0.75em;font-size:0.85em}

/* Borde degradado para widgets originales (excepto Próximo Paciente y Reporte IA) */
.widget:not(.widget-minimal):not([data-widget-id="next-patient"]):not([data-widget-id="ia-pending"]):not([data-widget-id="agenda-today"]) > .card{
  position:relative;
  border:1.5px solid transparent;
  background-clip:padding-box;
}
.widget:not(.widget-minimal):not([data-widget-id="next-patient"]):not([data-widget-id="ia-pending"]):not([data-widget-id="agenda-today"]) > .card::before{
  content:"";
  position:absolute;
  inset:-1.5px;
  z-index:-1;
  border-radius:var(--r-lg);
  background:linear-gradient(180deg, rgba(0,0,0,.47) 0%, #168BD9 100%);
  pointer-events:none;
}

/* Drag handle en la cabecera */
.widget-drag-handle{
  position:absolute;
  top:10px;right:10px;
  width:22px;height:22px;
  border-radius:6px;
  display:grid;place-items:center;
  color:rgba(234,241,255,.25);
  cursor:grab;
  opacity:0;
  transition:opacity .15s, background .15s;
  z-index:10;
}
.widget:hover .widget-drag-handle{opacity:1}
.widget-drag-handle:hover{background:rgba(178,99,255,.18);color:rgba(234,241,255,.7)}
.widget-drag-handle svg{width:14px;height:14px;pointer-events:none}

/* Resize handle (esquina inferior derecha) */
.widget-resize-handle{
  position:absolute;
  bottom:0;right:0;
  width:44px;height:44px;
  cursor:se-resize;
  opacity:0;
  transition:opacity .15s;
  z-index:10;
  display:grid;
  place-items:end;
  padding:0 8px 8px 0;
}
.widget:hover .widget-resize-handle{opacity:1}
.widget-resize-handle::after{
  content:'';
  display:block;
  width:20px;height:20px;
  background-color:rgba(178,99,255,.6);
  -webkit-mask-image:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15L15 21"/><path d="M21 8L8 21"/></svg>');
  mask-image:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15L15 21"/><path d="M21 8L8 21"/></svg>');
  -webkit-mask-size:contain;
  mask-size:contain;
  -webkit-mask-repeat:no-repeat;
  mask-repeat:no-repeat;
  -webkit-mask-position:center;
  mask-position:center;
  transition:background-color .15s;
}
.widget-resize-handle:hover::after{
  background-color:#B263FF;
}
.widget-resizing{user-select:none !important}
.widget-resizing *{user-select:none !important}

/* Span de columnas por widget */
.widget[data-w="3"]{grid-column:span 3}
.widget[data-w="4"]{grid-column:span 4}
.widget[data-w="5"]{grid-column:span 5}
.widget[data-w="6"]{grid-column:span 6}
.widget[data-w="8"]{grid-column:span 8}
.widget[data-w="12"]{grid-column:span 12}
.widget[data-w="13"]{grid-column:span 13}

/* Ghost placeholder al arrastrar */
.widget-ghost{
  border-radius:var(--r-lg);
  background:rgba(178,99,255,.06);
  border:2px dashed rgba(178,99,255,.35);
  grid-column:span 3;
  pointer-events:none;
  transition:all .18s;
}

/* Próximo paciente */
.card-next{
  background:linear-gradient(135deg,#021B44 0%, #0F2E7A 100%);
  border-color:#2563EB;
  color:#fff;
  position:relative;
  overflow:visible
}
html[data-theme="light"] .card-next{
  background:linear-gradient(135deg,#FFFFFF 0%, #EAF1FF 100%);
  border-color:#2563EB;
  color:#1E3A8A;
}
html[data-theme="light"] .card-next h3{color:#2563EB}
.card-next h3{color:#60A5FA;font-size:0.85em}
.card-next .name{
  font-family:'Sora',sans-serif;
  font-size:1.65em;
  font-weight:700;
  line-height:1.15;
  margin-bottom:0.75em;
}
html[data-theme="light"] .card-next .name{color:#1E3A8A}
.card-next .meta{display:flex;align-items:center;gap:0.5em;font-size:0.85em;color:#9BB8E8;margin-bottom:0.25em}
.card-next .meta b{color:#fff;font-weight:600}
html[data-theme="light"] .card-next .meta{color:#5B7DB1}
html[data-theme="light"] .card-next .meta b{color:#1E3A8A}
.card-next .btn-line{
  margin-top:auto;
  flex:1 0 auto;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:0.5em;
  padding:0.6em 1em;
  font-size:0.85em;
  min-height:2.8em;
  border-color:#2563EB;
  color:#60A5FA;
  background:rgba(37,99,235,.12);
}
@media (hover:hover) and (pointer:fine){ .card-next .btn-line:hover{background:rgba(37,99,235,.22)} }
html[data-theme="light"] .card-next .btn-line{
  border-color:#2563EB;
  color:#2563EB;
  background:rgba(37,99,235,.10);
}
@media (hover:hover) and (pointer:fine){ html[data-theme="light"] .card-next .btn-line:hover{background:rgba(37,99,235,.18)} }
.card-next .btn-line svg{width:1em;height:1em}
.holo{
  position:absolute;
  right:0.9em;top:50%;
  translate:0 -50%;
  width:7.5em;height:7.5em;
  pointer-events:none;
}
.holo svg{width:100%;height:100%;stroke:#2563EB;filter:drop-shadow(0 0 14px rgba(37,99,235,.55))}
.holo .lottie-brain{width:100%;height:100%;mix-blend-mode:screen;filter:drop-shadow(0 0 14px rgba(37,99,235,.55))}
html[data-theme="light"] .holo svg{stroke:#2563EB;filter:drop-shadow(0 0 10px rgba(37,99,235,.45))}
html[data-theme="light"] .holo .lottie-brain{filter:drop-shadow(0 0 10px rgba(37,99,235,.45))}
.holo::after{
  content:'';
  position:absolute;
  left:50%;bottom:-0.25em;
  translate:-50% 0;
  width:5.2em;height:0.9em;
  border-radius:50%;
  background:radial-gradient(ellipse, rgba(37,99,235,.5), transparent 70%);
  animation:holo-base 2.6s var(--ease-in-out) infinite;
}
@keyframes holo-base{0%,100%{opacity:.45}50%{opacity:1}}

/* Reporte IA */
.card-ia{
  background:linear-gradient(135deg,#090807 0%, #1A0F00 100%);
  border-color:#E75D01;
  color:#fff;
  position:relative;
  overflow:visible
}
html[data-theme="light"] .card-ia{background:linear-gradient(135deg,#FFF8F0 0%, #FDE8D0 100%);color:#5C2A0A}
html[data-theme="light"] .card-ia .muted{color:#9A5B25}
.card-ia h3{color:#E75D01;font-size:0.85em}
html[data-theme="light"] .card-ia h3{color:#E75D01}
.card-ia .big-num{font-family:'Sora',sans-serif;font-size:2.85em;font-weight:800;line-height:1;color:#fff}
html[data-theme="light"] .card-ia .big-num{color:#E75D01}
.card-ia .big-label{font-size:0.95em;font-weight:600;line-height:1.25;margin:0.25em 0 0.9em;color:#F2AC3A}
html[data-theme="light"] .card-ia .big-label{color:#B85600}
.card-ia .brain-img{
  position:absolute;right:-1.6em;top:-0.9em;width:10.6em;height:10.6em;
  pointer-events:none;
  animation:brain-pulse 3s var(--ease-in-out) infinite;
  filter:drop-shadow(0 0 14px rgba(56,199,244,.5));
}
.card-ia .brain-img svg{width:100%;height:100%;}
@keyframes brain-pulse{0%,100%{opacity:.7}50%{opacity:1}}
.btn-orange{
  margin-top:auto;
  flex:1 0 auto;
  display:flex;align-items:center;justify-content:center;gap:0.5em;
  padding:0.6em 1em;
  border-radius:var(--r-md);
  border:1px solid #E75D01;
  font-size:0.85em;font-weight:700;color:#F2AC3A;
  background:rgba(231,93,1,.12);
  transition:background-color 150ms ease, transform 160ms var(--ease-out);
  min-height:2.8em;
}
.btn-orange svg{width:1em;height:1em}
.btn-orange:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){.btn-orange:hover{background:rgba(231,93,1,.22)}}
html[data-theme="light"] .btn-orange{border-color:#E75D01;color:#E75D01;background:rgba(231,93,1,.10)}
@media (hover:hover) and (pointer:fine){html[data-theme="light"] .btn-orange:hover{background:rgba(231,93,1,.18)}}

/* Calendario widget */
.cal-head{display:flex;align-items:center;justify-content:space-between;font-size:0.85em;font-weight:700;background:rgba(46,123,246,.15);border:1px solid var(--stroke);border-radius:10px;padding:0.5em 0.75em;margin-bottom:0.75em}
.cal-head .arrows{display:flex;gap:0.4em}
.cal-head .arrows button{color:var(--txt-soft);padding:0 0.25em;transition:color 150ms ease}
.cal{width:100%;border-collapse:collapse;font-size:0.75em;text-align:center;flex:1;display:grid;grid-template-columns:repeat(7,1fr);grid-template-rows:auto repeat(5,1fr)}
.cal thead,
.cal tbody,
.cal tr{display:contents}
.cal th{color:var(--txt-soft);font-weight:600;padding:0.3em 0}
.cal td{padding:0;color:var(--txt);border-radius:8px;display:grid;place-items:center}
.cal td.off{color:var(--off)}
.cal td.today{background:linear-gradient(135deg,var(--blue),var(--cyan));font-weight:700;box-shadow:0 4px 14px -4px rgba(46,123,246,.8)}

/* Agenda - estilo day-date-picker */
.widget[data-widget-id="agenda-today"] .card{
  background:linear-gradient(to bottom, rgba(255,255,255,.70) 0%, rgba(255,255,255,.40) 50%, rgba(255,255,255,.10) 100%);
  backdrop-filter:blur(18px);
  -webkit-backdrop-filter:blur(18px);
  border:1.5px solid rgba(255,255,255,.45);
  border-radius:16px;
  padding:12px 10px 10px;
  box-shadow:0 16px 48px rgba(0,0,0,.45);
  color:#0E1530;
  font-size:13px;
}
.widget[data-widget-id="agenda-today"] .card h3{
  font-family:'Sora',sans-serif;
  font-size:10px;
  font-weight:700;
  color:#000000;
  margin-bottom:6px;
  text-align:center;
  letter-spacing:.05em;
  text-transform:uppercase;
}
.widget[data-widget-id="agenda-today"] .cal-head{
  display:flex;
  align-items:center;
  justify-content:space-between;
  background:rgba(255,255,255,.1);
  border:0;
  border-radius:10px;
  padding:4px 6px;
  margin-bottom:6px;
  font-family:'Sora',sans-serif;
  font-size:12px;
  font-weight:700;
  color:#000000;
}
.widget[data-widget-id="agenda-today"] .cal-head .arrows{display:flex;gap:0.2em}
.widget[data-widget-id="agenda-today"] .cal-head .arrows button{
  width:20px;
  height:20px;
  border-radius:6px;
  border:none;
  background:transparent;
  color:#000000;
  display:grid;
  place-items:center;
  transition:background 120ms,color 120ms;
  font-weight:700;
  font-size:14px;
  line-height:1;
}
.widget[data-widget-id="agenda-today"] .cal-head .arrows button:hover{
  background:rgba(0,0,0,.12);
  color:#000000;
}
.widget[data-widget-id="agenda-today"] .cal{
  width:100%;
  border-collapse:collapse;
  font-size:13px;
  text-align:center;
  flex:1;
  display:grid;
  grid-template-columns:repeat(7,1fr);
  grid-template-rows:auto repeat(5,1fr);
  gap:2px;
  min-height:0;
}
.widget[data-widget-id="agenda-today"] .cal thead,
.widget[data-widget-id="agenda-today"] .cal tbody,
.widget[data-widget-id="agenda-today"] .cal tr{display:contents}
.widget[data-widget-id="agenda-today"] .cal th{
  font-size:9px;
  font-weight:700;
  color:#000000;
  padding:2px 0 4px;
  letter-spacing:.03em;
}
.widget[data-widget-id="agenda-today"] .cal td{
  padding:0;
  height:100%;
  min-height:24px;
  border-radius:6px;
  color:#000000;
  display:grid;
  place-items:center;
  background:transparent;
  transition:background 120ms,color 120ms;
  font-weight:700;
  font-size:13px;
  line-height:1;
}
.widget[data-widget-id="agenda-today"] .cal td:hover{
  background:rgba(0,0,0,.18);
  color:#000000;
}
.widget[data-widget-id="agenda-today"] .cal td.off{
  opacity:.35;
  color:#000000;
  cursor:default;
  pointer-events:none;
}
.widget[data-widget-id="agenda-today"] .cal td.past{
  opacity:.45;
  color:#000000;
  cursor:not-allowed;
  pointer-events:none;
  text-decoration:line-through;
}
.widget[data-widget-id="agenda-today"] .cal td.past:hover,
.widget[data-widget-id="agenda-today"] .cal td.off:hover{
  background:transparent;
}
.widget[data-widget-id="agenda-today"] .cal td.today{
  background:#000000;
  color:#fff;
  font-weight:800;
  box-shadow:0 2px 8px rgba(0,0,0,.25);
}
html[data-theme="light"] .widget[data-widget-id="agenda-today"] .card{
  background:linear-gradient(to bottom, rgba(255,255,255,.95) 0%, rgba(255,255,255,.80) 50%, rgba(245,245,245,.65) 100%);
  border-color:rgba(0,0,0,.15);
  box-shadow:0 12px 40px rgba(0,0,0,.15);
  color:#000000;
}
html[data-theme="light"] .widget[data-widget-id="agenda-today"] .card h3{color:#000000}

/* Acciones rápidas */
.quick{display:flex;flex-direction:column;gap:0.75em;flex:1}
.quick .qbtn{display:flex;align-items:center;gap:0.75em;padding:0.8em 1em;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2);font-size:0.9em;font-weight:600;transition:border-color 150ms ease, background-color 150ms ease, transform 160ms var(--ease-out);flex:1;min-height:3em}
.quick .qbtn:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){.quick .qbtn:hover{border-color:var(--stroke-strong);background:var(--card)}}
.quick .qbtn svg{width:1.2em;height:1.2em;color:var(--cyan)}
.quick .qbtn.wa svg{color:#25D366}

/* Tabla */
.tbl-wrap{flex:1;overflow-x:auto;min-height:0}
table.tbl{width:100%;border-collapse:collapse;font-size:0.9em;min-width:540px;height:100%}
.tbl th{text-align:left;font-size:0.85em;font-weight:600;color:var(--txt-soft);padding:0.6em 0.75em;border-bottom:1px solid var(--stroke)}
.tbl td{padding:0.85em 0.75em;border-bottom:1px solid rgba(110,160,255,.08)}
.tbl tbody tr{height:1%;transition:background-color 150ms ease}
@media (hover:hover) and (pointer:fine){.tbl tbody tr:hover{background:rgba(110,160,255,.05)}}
.tbl tr:last-child td{border-bottom:0}
.pat{display:flex;align-items:center;gap:0.6em;font-weight:600}
.pat .mini{width:2em;height:2em;border-radius:50%;background:rgba(46,123,246,.2);border:1px solid var(--stroke-strong);display:grid;place-items:center;font-size:0.7em;font-weight:700;color:var(--cyan)}
.tbl .dots{color:var(--txt-soft);font-weight:700;letter-spacing:0.12em}
.tbl-link{
  display:flex;align-items:center;justify-content:center;gap:0.5em;
  margin-top:auto;padding:0.5em 0;font-size:0.85em;font-weight:700;color:var(--blue);text-decoration:none;
  min-height:2.5em;
}
.tbl-link svg{width:1em;height:1em}

/* IA Predictiva */
.widget:not(.widget-minimal) > .card.card-pred{
  background:linear-gradient(180deg, #011026 0%, #041C3B 50%, #021939 75%, #021A3A 100%);
  border-color:rgba(56,199,244,.4);
  display:grid;
  grid-template-columns:1.2fr .65fr 1fr 1.15fr;
  gap:1.6em;
  align-items:stretch;
  flex:1;
  min-height:0;
  width:100%;
  overflow:hidden;
  container-type:inline-size;
}
html[data-theme="light"] .widget:not(.widget-minimal) > .card.card-pred{
  background:linear-gradient(180deg, #011026 0%, #041C3B 50%, #021939 75%, #021A3A 100%);
}
.widget:not(.widget-minimal) > .card.card-pred > div{
  display:flex;
  flex-direction:column;
  min-width:0;
  min-height:0;
  overflow-wrap:break-word;
}
.pred-head{display:flex;align-items:flex-start;gap:0.85em;margin-bottom:0.85em}
.pred-head .orb{width:2.75em;height:2.75em;flex:none;border-radius:12px;border:1px solid var(--stroke-strong);display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.08)}
.pred-head .orb svg{width:1.4em;height:1.4em}
.pred-head h3{margin-bottom:0.12em;font-size:clamp(0.82em,2.6cqi,1.05em)}
.pred-head p{font-size:clamp(0.68em,2cqi,0.82em);color:var(--txt-soft);line-height:1.4}
.pred-note{border:1px solid var(--stroke-strong);border-radius:var(--r-md);background:rgba(46,123,246,.08);padding:0.9em 1em;font-size:clamp(0.72em,2.1cqi,0.88em);line-height:1.5}
.pred-note b{color:var(--cyan)}
.gauge-box{text-align:center}
.gauge-box h4,.recs h4,.hist h4{font-family:'Sora',sans-serif;font-size:clamp(0.72em,2.1cqi,0.88em);font-weight:600;margin-bottom:0.85em}
.gauge{position:relative;width:clamp(5.2em,18cqi,7.6em);height:clamp(5.2em,18cqi,7.6em);margin:0 auto}
.gauge svg{width:100%;height:100%;transform:rotate(-90deg)}
.gauge circle{fill:none;stroke-width:11;stroke-linecap:round}
.gauge .track{stroke:rgba(110,160,255,.12)}
.gauge .val{stroke:var(--orange);transition:stroke-dashoffset 1.2s var(--ease-out)}
.gauge-center{position:absolute;inset:0;display:grid;place-items:center;text-align:center}
.gauge-center .lvl{font-size:clamp(0.62em,1.8cqi,0.78em);font-weight:700;color:var(--orange)}
.gauge-center .pct{font-family:'Sora',sans-serif;font-size:clamp(1.05em,4.2cqi,1.55em);font-weight:800;line-height:1.05}
.recs ul{list-style:none;flex:1;overflow:auto;min-height:0}
.recs li{display:flex;align-items:flex-start;gap:0.6em;font-size:clamp(0.72em,2.1cqi,0.88em);line-height:1.4;padding:0.4em 0}
.recs li svg{width:clamp(0.9em,2.8cqi,1.1em);height:clamp(0.9em,2.8cqi,1.1em);flex:none;color:var(--green);margin-top:0.06em}
.hist h4{color:var(--green)}
.hist-item{display:flex;align-items:center;justify-content:space-between;gap:0.75em;padding:0.55em 0;font-size:clamp(0.72em,2.1cqi,0.88em);border-bottom:1px solid rgba(110,160,255,.08)}
.hist-item span{color:var(--txt-soft);font-size:0.85em}
.hist .tbl-link{justify-content:flex-start;margin-top:auto}

/* Ajustes para que ciertas listas llenen el alto */
.hist{
  display:flex;
  flex-direction:column;
  min-height:0;
  overflow:auto;
}
.hist-item{
  flex:1;
  display:flex;
  align-items:center;
}

/* Responsive */
@media (max-width:1280px){
  .widget[data-w="3"]{grid-column:span 6}
  .widget[data-w="4"]{grid-column:span 6}
  .widget[data-w="5"]{grid-column:span 6}
  .widget[data-w="6"]{grid-column:span 13}
  .widget[data-w="8"]{grid-column:span 13}
  .widget:not(.widget-minimal) > .card.card-pred{grid-template-columns:1fr 1fr}
}
@media (max-width:720px){
  .widget[data-w]{grid-column:span 13 !important}
  .widget:not(.widget-minimal) > .card.card-pred{grid-template-columns:1fr}
}
@media (prefers-reduced-motion: reduce){
  .holo::after,.card-ia .brain-img{animation:none}
  .widget{transition:none}
}

/* Minimalista widgets */
.widget.mode-hidden{display:none !important}

/* Visibilidad de grids según modo */
#widgetGridMinimal{display:none}
#widgetGrid.dashboard-mode-min{display:none}
#widgetGridMinimal:not(.dashboard-mode-min){display:grid}

/* Dashboard mode switch */
.db-mode-bar{
  display:flex;align-items:center;justify-content:space-between;gap:16px;
  margin-bottom:20px;padding:14px 18px;
  background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-lg);
  color:var(--txt)
}
.db-mode-bar h2{font-family:'Sora',sans-serif;font-size:20px;font-weight:700;margin:0}
.db-mode-switch{
  display:flex;background:var(--panel-2);border:1px solid var(--stroke-strong);
  border-radius:var(--r-md);padding:3px;gap:3px
}
.db-mode-switch button{
  border:none;background:transparent;color:var(--txt-soft);font-weight:600;font-size:13px;
  padding:6px 12px;border-radius:var(--r-md);cursor:pointer;transition:background .15s, color .15s
}
.db-mode-switch button.active{background:var(--blue);color:#fff}

.widget-minimal .card-minimal{
  background:var(--card);
  border:1px solid var(--stroke);
  border-radius:var(--r-lg);
  padding:1.5em;
  display:flex;
  flex-direction:column;
  gap:0.75em;
  min-height:260px;
  height:100%;
  justify-content:center;
  transition:transform .2s ease, box-shadow .2s ease;
  font-size: clamp(12px, min(calc(var(--widget-w-px, 300) * 0.055px), calc(var(--widget-h-px, 260) * 0.065px)), 22px);
  position:relative;
}
.widget-minimal .card-minimal:hover{transform:translateY(-3px);box-shadow:0 12px 30px rgba(0,0,0,.15)}
.widget-minimal .min-label{
  font-size:0.85em;
  text-transform:uppercase;
  letter-spacing:.06em;
  color:var(--txt-soft);
  font-weight:700;
}
.widget-minimal .min-value{
  font-family:'Sora', sans-serif;
  font-size:2em;
  font-weight:800;
  line-height:1.1;
  color:var(--txt);
}
.widget-minimal .min-value span{
  font-size:0.5em;
  font-weight:600;
  color:var(--txt-soft);
}
.widget-minimal .min-meta{
  font-size:0.95em;
  color:var(--txt-soft);
  line-height:1.3;
}
.widget-minimal .min-list{
  list-style:none;
  padding:0;
  margin:0;
  display:flex;
  flex-direction:column;
  gap:0.5em;
  max-height:9em;
  overflow-y:auto;
}
.widget-minimal .min-list li{
  display:flex;
  gap:0.65em;
  font-size:0.95em;
  color:var(--txt);
}
.widget-minimal .min-list li span{
  color:var(--txt-soft);
  font-size:0.85em;
  flex:none;
}
.widget-minimal .min-btn{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:0.4em;
  padding:0.65em 0.9em;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:var(--panel-2);
  color:var(--txt);
  font-size:0.95em;
  font-weight:600;
  text-decoration:none;
  transition:background .15s ease, transform .16s ease;
  flex:1 0 auto;
  min-height:2.8em;
}
.widget-minimal .min-btn svg{
  width:1em;
  height:1em;
}
.widget-minimal .min-btn:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){ .widget-minimal .min-btn:hover{background:var(--card-hover);} }
.widget-minimal .min-note{
  font-size:0.85em;
  color:var(--txt-soft);
  line-height:1.4;
}
/* Entrada animada con escalado */
.widget-minimal:not(.mode-hidden) .card-minimal{animation:minPop 0.5s var(--ease-out) both}
.widget-minimal.d1 .card-minimal{animation-delay:0ms}
.widget-minimal.d2 .card-minimal{animation-delay:60ms}
.widget-minimal.d3 .card-minimal{animation-delay:120ms}
.widget-minimal.d4 .card-minimal{animation-delay:180ms}
.widget-minimal.d5 .card-minimal{animation-delay:240ms}
.widget-minimal.d6 .card-minimal{animation-delay:300ms}
.widget-minimal.d7 .card-minimal{animation-delay:360ms}
@keyframes minPop{from{opacity:0;transform:scale(0.92) translateY(18px)}to{opacity:1;transform:scale(1) translateY(0)}}
@media (prefers-reduced-motion: reduce){ .widget-minimal:not(.mode-hidden) .card-minimal{animation:none} }
</style>
@include('dashboard.widgets.next-patient.styles')
@include('dashboard.widgets.ia-pending.styles')
@include('dashboard.widgets.agenda-today.styles')
@include('dashboard.widgets.new-study.styles')
@include('dashboard.widgets.next-list.styles')
@include('dashboard.widgets.agenda-summary.styles')
@include('dashboard.widgets.ia-risk.styles')
@endpush

@section('content')

  {{-- Grid original --}}
  <div id="widgetGrid">
    @include('dashboard.widgets.next-patient.index')
    @include('dashboard.widgets.ia-pending.index')
    @include('dashboard.widgets.agenda-today.index')
    @include('dashboard.widgets.new-study.index')
    @include('dashboard.widgets.next-list.index')
    @include('dashboard.widgets.agenda-summary.index')
    @include('dashboard.widgets.ia-risk.index')
  </div>{{-- /#widgetGrid --}}

  {{-- Grid minimalista --}}
  <div id="widgetGridMinimal" class="dashboard-mode-min">
    @include('dashboard.widgets.next-patient.minimalista')
    @include('dashboard.widgets.ia-pending.minimalista')
    @include('dashboard.widgets.agenda-today.minimalista')
    @include('dashboard.widgets.new-study.minimalista')
    @include('dashboard.widgets.next-list.minimalista')
    @include('dashboard.widgets.agenda-summary.minimalista')
    @include('dashboard.widgets.ia-risk.minimalista')
  </div>{{-- /#widgetGridMinimal --}}

@endsection

@push('scripts')
<script src="https://unpkg.com/lottie-web@5.12.2/build/player/lottie.min.js"></script>
<script>
(function(){
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- Lottie cerebro en Próximo Paciente ---- */
  const brainNextContainer = document.querySelector('.lottie-brain');
  if (brainNextContainer && typeof lottie !== 'undefined') {
    const brainNext = lottie.loadAnimation({
      container: brainNextContainer,
      renderer: 'svg',
      loop: true,
      autoplay: !reduced,
      path: '{{ asset('animations/brain.json') }}?v=1'
    });
  } else if (brainNextContainer) {
    // Lottie no cargado
  }

  /* ---- Lottie medico2 en Reporte IA ---- */
  const brainContainer = document.querySelector('.brain-lottie');
  if (brainContainer && typeof lottie !== 'undefined') {
    const brain = lottie.loadAnimation({
      container: brainContainer,
      renderer: 'svg',
      loop: true,
      autoplay: !reduced,
      path: '{{ asset('animations/robot.json') }}?v=1'
    });
  } else if (brainContainer) {
    // Lottie no cargado
  }

  /* ---- Gauge de riesgo ---- */
  const gauge = document.querySelector('.gauge .val');
  const drawGauge = () => {
    const pct = parseFloat(gauge.dataset.pct) / 100;
    const C = 314.16;
    gauge.style.strokeDashoffset = C - (C * pct);
  };

  /* ---- Contadores ---- */
  const counters = document.querySelectorAll('[data-target]');
  if (reduced || typeof gsap === 'undefined') {
    counters.forEach(c => { if (c.dataset.target) c.textContent = parseInt(c.dataset.target, 10).toLocaleString('es-MX'); });
    if (gauge) { gauge.style.transition = 'none'; drawGauge(); }
  } else {
    counters.forEach((counter, i) => {
      if (!counter.id) return;
      const target = parseInt(counter.dataset.target, 10);
      const obj = { v: 0 };
      gsap.to(obj, { v: target, duration: 1.4, ease: 'expo.out', delay: 0.4 + i * 0.12,
        onUpdate: () => { counter.textContent = Math.round(obj.v).toLocaleString('es-MX'); }
      });
    });
    setTimeout(drawGauge, 550);
  }

  /* ---- Límites de tamaño de widgets ---- */
  function getWidgetLimits(w) {
    const id = w.dataset.widgetId;
    const initialW = parseInt(w.dataset.w, 10);
    const baseH = parseInt(w.dataset.baseH, 10) || w.offsetHeight;
    if (id === 'ia-risk') {
      return {
        minW: 9,
        maxW: 13,
        minH: Math.max(280, Math.round(baseH * 0.75)),
        maxH: Math.round(baseH * 2)
      };
    }
    if (id === 'next-list') {
      const baseW = parseInt(w.dataset.baseW, 10) || initialW;
      return {
        minW: baseW,
        maxW: 13,
        minH: Math.max(220, Math.round(baseH * 0.6)),
        maxH: Math.round(baseH * 3)
      };
    }
    if (id === 'agenda-summary') {
      const baseHStored = parseInt(w.dataset.baseH, 10) || w.offsetHeight;
      return {
        minW: Math.max(3, Math.floor(initialW * 0.5)),
        maxW: 13,
        minH: baseHStored,
        maxH: Math.round(baseHStored * 3)
      };
    }
    // Widgets con tamaño predeterminado fijo en el dashboard original
    if (['next-patient', 'ia-pending', 'new-study'].includes(id)) {
      const baseHStored = parseInt(w.dataset.baseH, 10) || w.offsetHeight;
      return {
        minW: Math.max(3, Math.floor(initialW * 0.5)),
        maxW: 13,
        minH: Math.round(baseHStored * 0.85),
        maxH: Math.round(baseHStored * 1.5)
      };
    }
    return {
      minW: Math.max(3, Math.floor(initialW * 0.5)),
      maxW: 13,
      minH: Math.max(220, Math.round(baseH * 0.6)),
      maxH: Math.round(baseH * 3)
    };
  }

  function applySizeLimits(grid) {
    if (!grid) {
      applySizeLimits(document.getElementById('widgetGrid'));
      applySizeLimits(document.getElementById('widgetGridMinimal'));
      return;
    }
    if (grid.offsetParent === null) return; // grid oculto, no aplicar
    const visible = Array.from(grid.querySelectorAll('.widget:not(.widget-ghost):not(.widget-hidden):not(.mode-hidden)'));
    let needsRetry = false;
    visible.forEach(w => {
      const isMinimal = w.classList.contains('widget-minimal');
      const minRendered = isMinimal ? 260 : 120;
      if (w.offsetHeight < minRendered && !w.style.height) { needsRetry = true; return; }
      if (!w.dataset.baseW) w.dataset.baseW = parseInt(w.dataset.w, 10);
      const storedBaseH = parseInt(w.dataset.baseH, 10) || 0;
      if (!w.dataset.baseH || storedBaseH <= 0) w.dataset.baseH = w.offsetHeight;
      const limits = getWidgetLimits(w);
      const currentW = parseInt(w.dataset.w, 10);
      const currentH = parseInt(w.style.height, 10) || w.offsetHeight;
      const newW = Math.max(limits.minW, Math.min(limits.maxW, currentW));
      let newH = Math.max(limits.minH, Math.min(limits.maxH, currentH));
      if (newH <= 0) newH = w.offsetHeight || limits.minH || 220;
      w.dataset.w = newW;
      w.style.gridColumn = 'span ' + newW;
      w.style.height = newH + 'px';
    });
    if (needsRetry) {
      const retries = parseInt(grid.dataset.applyRetries, 10) || 0;
      if (retries < 10) {
        grid.dataset.applyRetries = retries + 1;
        requestAnimationFrame(() => applySizeLimits(grid));
      } else {
        grid.dataset.applyRetries = 0;
      }
    } else {
      grid.dataset.applyRetries = 0;
    }
  }

  /* ============ WIDGET DRAG & REORDER ============ */
  function initGrid(grid){
    if (!grid) return;
    const storageKey = grid.id === 'widgetGridMinimal' ? 'dbWidgetOrderV2Minimal' : 'dbWidgetOrderV2';

    let dragging = null, ghost = null, originNext = null;

    function getWidgets() {
      return Array.from(grid.querySelectorAll('.widget:not(.widget-ghost):not(.widget-hidden):not(.mode-hidden)'));
    }

    function createGhost(w) {
      const g = document.createElement('div');
      g.className = 'widget-ghost';
      g.dataset.w = w.dataset.w;
      g.style.gridColumn = 'span ' + w.dataset.w;
      g.style.minHeight = w.offsetHeight + 'px';
      return g;
    }

    function closest(x, y) {
      const widgets = getWidgets();
      let best = null, bestDist = Infinity;
      widgets.forEach(w => {
        if (w === dragging) return;
        const r = w.getBoundingClientRect();
        const cx = r.left + r.width / 2;
        const cy = r.top + r.height / 2;
        const dist = Math.hypot(x - cx, y - cy);
        if (dist < bestDist) { bestDist = dist; best = w; }
      });
      return best;
    }

    grid.addEventListener('mousedown', e => {
      const handle = e.target.closest('.widget-drag-handle');
      if (!handle) return;
      e.preventDefault();
      dragging = handle.closest('.widget');
      originNext = dragging.nextElementSibling;
      ghost = createGhost(dragging);
      dragging.classList.add('widget-dragging');
      dragging.after(ghost);
    });

    document.addEventListener('mousemove', e => {
      if (!dragging) return;
      const target = closest(e.clientX, e.clientY);
      if (!target) return;
      const r = target.getBoundingClientRect();
      const before = e.clientX < r.left + r.width / 2;
      grid.querySelectorAll('.widget-drop-target').forEach(el => el.classList.remove('widget-drop-target'));
      target.classList.add('widget-drop-target');
      ghost.remove();
      if (before) target.before(ghost);
      else target.after(ghost);
    });

    document.addEventListener('mouseup', e => {
      if (!dragging) return;
      dragging.classList.remove('widget-dragging');
      grid.querySelectorAll('.widget-drop-target').forEach(el => el.classList.remove('widget-drop-target'));
      ghost.replaceWith(dragging);
      ghost = null; dragging = null;
      saveOrder();
    });

    /* Touch support */
    grid.addEventListener('touchstart', e => {
      const handle = e.target.closest('.widget-drag-handle');
      if (!handle) return;
      dragging = handle.closest('.widget');
      ghost = createGhost(dragging);
      dragging.classList.add('widget-dragging');
      dragging.after(ghost);
    }, {passive:true});

    document.addEventListener('touchmove', e => {
      if (!dragging) return;
      const t = e.touches[0];
      const target = closest(t.clientX, t.clientY);
      if (!target) return;
      const r = target.getBoundingClientRect();
      const before = t.clientX < r.left + r.width / 2;
      ghost.remove();
      if (before) target.before(ghost); else target.after(ghost);
    }, {passive:true});

    document.addEventListener('touchend', () => {
      if (!dragging) return;
      dragging.classList.remove('widget-dragging');
      ghost.replaceWith(dragging);
      ghost = null; dragging = null;
      saveOrder();
    });

    /* ---- Resize (ancho + alto) ---- */
    let resizing = null, resizeStartX = 0, resizeStartY = 0, resizeW0 = 0, resizeH0 = 0;
    function startResize(e, widget) {
      e.preventDefault(); e.stopPropagation();
      resizing = widget;
      resizing.classList.add('widget-resizing');
      const point = e.touches ? e.touches[0] : e;
      resizeStartX = point.clientX;
      resizeStartY = point.clientY;
      resizeW0 = parseInt(resizing.dataset.w, 10);
      resizeH0 = resizing.offsetHeight;
    }
    function moveResize(e) {
      if (!resizing) return;
      const point = e.touches ? e.touches[0] : e;
      const colW = grid.offsetWidth / 13;
      const deltaX = Math.round((point.clientX - resizeStartX) / colW);
      const deltaY = point.clientY - resizeStartY;
      const limits = getWidgetLimits(resizing);
      const newW = Math.max(limits.minW, Math.min(limits.maxW, resizeW0 + deltaX));
      const newH = Math.max(limits.minH, Math.min(limits.maxH, resizeH0 + deltaY));
      resizing.dataset.w = newW;
      resizing.style.gridColumn = 'span ' + newW;
      resizing.style.height = newH + 'px';
    }
    function endResize() {
      if (resizing) {
        resizing.classList.remove('widget-resizing');
        saveOrder();
        resizing = null;
      }
    }
    grid.addEventListener('mousedown', e => {
      const handle = e.target.closest('.widget-resize-handle');
      if (!handle) return;
      startResize(e, handle.closest('.widget'));
    });
    grid.addEventListener('touchstart', e => {
      const handle = e.target.closest('.widget-resize-handle');
      if (!handle) return;
      startResize(e, handle.closest('.widget'));
    }, { passive: false });
    document.addEventListener('mousemove', moveResize);
    document.addEventListener('touchmove', moveResize, { passive: false });
    document.addEventListener('mouseup', endResize);
    document.addEventListener('touchend', endResize);

    /* ---- Persistencia orden + tamaño ---- */
    function saveOrder() {
      const order = getWidgets().map(w => {
        const h = w.style.height;
        return { id: w.dataset.widgetId, w: w.dataset.w, h: h || null };
      });
      try { localStorage.setItem(storageKey, JSON.stringify(order)); } catch(e) {}
    }

    function restoreOrder() {
      try {
        const saved = JSON.parse(localStorage.getItem(storageKey) || 'null');
        if (!saved) return;
        saved.forEach(({ id, w, h }) => {
          const el = grid.querySelector(`[data-widget-id="${id}"]`);
          if (!el) return;
          const baseW = parseInt(el.dataset.w, 10);
          const baseH = el.offsetHeight;
          el.dataset.baseW = baseW;
          el.dataset.baseH = baseH;
          const limits = getWidgetLimits(el);
          const newW = Math.max(limits.minW, Math.min(limits.maxW, parseInt(w, 10)));
          el.dataset.w = newW;
          el.style.gridColumn = 'span ' + newW;
          if (h) {
            const savedH = parseInt(h, 10);
            el.style.height = Math.max(limits.minH, Math.min(limits.maxH, savedH)) + 'px';
          }
          grid.appendChild(el);
        });
      } catch(e) {}
    }

    restoreOrder();

    /* ---- Escuchar cambios del editor de widgets ---- */
    window.addEventListener('dbWidgetsChanged', e => {
      const prefs = e.detail;
      grid.querySelectorAll('.widget').forEach(w => {
        const id = w.dataset.widgetId;
        if (!id) return;
        const baseId = id.replace(/-min$/, '');
        const visible = prefs[baseId] !== undefined ? prefs[baseId] : true;
        if (visible) {
          w.classList.remove('widget-hidden');
          w.classList.add('widget-appear');
          setTimeout(() => w.classList.remove('widget-appear'), 350);
        } else {
          w.classList.add('widget-disappear');
          setTimeout(() => { w.classList.remove('widget-disappear'); w.classList.add('widget-hidden'); }, 230);
        }
      });
    });

    /* Aplicar estado inicial guardado */
    (function applyInitial() {
      try {
        const prefs = JSON.parse(localStorage.getItem('dbWidgetPrefs') || '{}');
        getWidgets().forEach(w => {
          const id = w.dataset.widgetId;
          if (!id) return;
          const baseId = id.replace(/-min$/, '');
          if (prefs[baseId] === false) w.classList.add('widget-hidden');
        });
      } catch(e) {}
    })();

    /* Aplicar límites de tamaño al cargar */
    applySizeLimits(grid);

    /* Capturar altura inicial de cada widget como base */
    (function captureInitialHeights() {
      grid.querySelectorAll('.widget').forEach(w => {
        if (!w.dataset.baseH) {
          w.dataset.baseH = Math.round(w.offsetHeight);
        }
      });
    })();

    /* Tooltips de handles */
    (function setHandleTitles() {
      grid.querySelectorAll('.widget-drag-handle').forEach(el => el.title = 'Arrastre para mover');
      grid.querySelectorAll('.widget-resize-handle').forEach(el => el.title = 'Arrastrar para cambiar tamaño');
    })();

    /* Escala del contenido según tamaño del widget */
    (function(){
      if (!window.ResizeObserver) return;
      const ro = new ResizeObserver(entries => {
        entries.forEach(entry => {
          const w = entry.borderBoxSize && entry.borderBoxSize[0] ? entry.borderBoxSize[0].inlineSize : entry.contentRect.width;
          const h = entry.borderBoxSize && entry.borderBoxSize[0] ? entry.borderBoxSize[0].blockSize : entry.contentRect.height;
          const t = entry.target;
          t.style.setProperty('--widget-w-px', Math.round(w));
          t.style.setProperty('--widget-h-px', Math.round(h));
          if (!t.dataset.baseH) t.dataset.baseH = Math.round(h);
        });
      });
      grid.querySelectorAll('.widget').forEach(w => ro.observe(w));
    })();

    /* ---- Actualizar calendario y resumen al cambiar de mes ---- */
    (function(){
      const calWidget = grid.querySelector('[data-widget-id="agenda-today"]');
      if (!calWidget) return;
      const summaryWidget = grid.querySelector('[data-widget-id="agenda-summary"]');
      const CSRF = @json(csrf_token());

      async function refreshWidget(widget, url, targetSelector) {
        try {
          const current = grid.querySelector('[data-widget-id="' + widget + '"]');
          if (!current) return;
          current.style.transition = 'opacity .25s ease, transform .25s ease';
          current.style.opacity = '0.6';
          current.style.transform = 'scale(0.98)';
          const res = await fetch(url, {
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
          });
          if (!res.ok) return;
          const html = await res.text();
          const temp = document.createElement('div');
          temp.innerHTML = html;
          const newWidget = temp.querySelector('[data-widget-id="' + widget + '"]');
          if (!newWidget) return;
          const ro = current._resizeObserver;
          if (ro && typeof ro.disconnect === 'function') ro.disconnect();
          newWidget.style.opacity = '0';
          newWidget.style.transform = 'scale(0.98)';
          newWidget.style.transition = 'opacity .25s ease, transform .25s ease';
          current.replaceWith(newWidget);
          requestAnimationFrame(() => {
            newWidget.style.opacity = '1';
            newWidget.style.transform = 'scale(1)';
          });
          const newRo = new ResizeObserver(entries => {
            entries.forEach(entry => {
              const w = entry.borderBoxSize && entry.borderBoxSize[0] ? entry.borderBoxSize[0].inlineSize : entry.contentRect.width;
              const h = entry.borderBoxSize && entry.borderBoxSize[0] ? entry.borderBoxSize[0].blockSize : entry.contentRect.height;
              entry.target.style.setProperty('--widget-w-px', Math.round(w));
              entry.target.style.setProperty('--widget-h-px', Math.round(h));
              if (!entry.target.dataset.baseH) entry.target.dataset.baseH = Math.round(h);
            });
          });
          newWidget._resizeObserver = newRo;
          newRo.observe(newWidget);
          applySizeLimits();
          bindCalendarNav();
          if (window.gsap) {
            const counters = newWidget.querySelectorAll('[data-target]');
            counters.forEach((counter, i) => {
              if (!counter.id) return;
              const target = parseInt(counter.dataset.target, 10);
              const obj = { v: 0 };
              gsap.to(obj, { v: target, duration: 1.4, ease: 'expo.out', delay: 0.4 + i * 0.12,
                onUpdate: () => { counter.textContent = Math.round(obj.v).toLocaleString('es-MX'); }
              });
            });
          } else {
            newWidget.querySelectorAll('[data-target]').forEach(c => { if (c.dataset.target) c.textContent = parseInt(c.dataset.target, 10).toLocaleString('es-MX'); });
          }
        } catch(e) {
          console.error('Error actualizando widget', widget, e);
          window.location.href = url;
        }
      }

      function bindCalendarNav() {
        const cal = grid.querySelector('[data-widget-id="agenda-today"]');
        if (!cal) return;
        cal.querySelectorAll('.cal-nav-btn').forEach(btn => {
          btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const url = this.dataset.url;
            if (!url) return;
            const params = new URL(url, window.location.origin).searchParams;
            const mes = params.get('widget_mes');
            const anio = params.get('widget_anio');
            const baseUrl = new URL(window.location.href);
            if (mes) baseUrl.searchParams.set('widget_mes', mes);
            if (anio) baseUrl.searchParams.set('widget_anio', anio);
            window.history.replaceState({}, '', baseUrl.toString());
            refreshWidget('agenda-today', url, '[data-widget-id="agenda-today"]');
            if (summaryWidget) {
              const summaryUrl = baseUrl.toString().replace('/dashboard', '/dashboard/widget/agenda-summary');
              refreshWidget('agenda-summary', summaryUrl, '[data-widget-id="agenda-summary"]');
            }
          });
        });
      }

      bindCalendarNav();
    })();
  }

  initGrid(document.getElementById('widgetGrid'));
  initGrid(document.getElementById('widgetGridMinimal'));

  window.applyWidgetSizeLimits = applySizeLimits;
})();
</script>
@endpush