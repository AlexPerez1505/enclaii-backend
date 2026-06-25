@extends('layouts.app')

@section('title', 'Dashboard')
@section('active', 'dashboard')
@php
  $nombreUsuario = auth()->check() ? preg_split('/\s+/', trim(auth()->user()->name ?? 'Doctor'), 2)[0] : 'Doctor';
@endphp
@section('header-title', 'Bienvenido, ' . $nombreUsuario . ' 👋')
@section('header-sub')
  Resumen general de tu actividad clinica
@endsection

@push('styles')
<style>
/* ============ WIDGET GRID SYSTEM ============ */

/* Contenedor principal de widgets */
#widgetGrid{
  display:grid;
  grid-template-columns:repeat(12,1fr);
  grid-auto-rows:minmax(60px,auto);
  gap:18px;
  align-items:start;
}

/* Widget wrapper */
.widget{
  position:relative;
  transition:opacity .25s ease, transform .28s cubic-bezier(.4,0,.2,1), box-shadow .2s ease;
  will-change:transform;
}
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
  bottom:6px;right:6px;
  width:16px;height:16px;
  cursor:se-resize;
  opacity:0;
  transition:opacity .15s;
  z-index:10;
}
.widget:hover .widget-resize-handle{opacity:1}
.widget-resize-handle::after{
  content:'';
  position:absolute;
  bottom:3px;right:3px;
  width:8px;height:8px;
  border-right:2px solid rgba(178,99,255,.5);
  border-bottom:2px solid rgba(178,99,255,.5);
  border-radius:1px;
}

/* Span de columnas por widget */
.widget[data-w="3"]{grid-column:span 3}
.widget[data-w="4"]{grid-column:span 4}
.widget[data-w="5"]{grid-column:span 5}
.widget[data-w="6"]{grid-column:span 6}
.widget[data-w="8"]{grid-column:span 8}
.widget[data-w="12"]{grid-column:span 12}

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
.card-next{border-color:rgba(46,123,246,.45);position:relative;overflow:hidden}
.card-next h3{color:var(--cyan)}
.card-next .name{
  font-family:'Sora',sans-serif;
  font-size:26px;
  font-weight:700;
  line-height:1.15;
  margin-bottom:12px;
}
.card-next .meta{display:flex;align-items:center;gap:8px;font-size:13.5px;color:var(--txt-soft);margin-bottom:4px}
.card-next .meta b{color:var(--txt);font-weight:600}
.holo{
  position:absolute;
  right:14px;top:50%;
  translate:0 -50%;
  width:96px;height:150px;
  pointer-events:none;
}
.holo svg{width:100%;height:100%;filter:drop-shadow(0 0 14px rgba(56,199,244,.55))}
html[data-theme="light"] .holo svg{stroke:#1E5AE8;filter:drop-shadow(0 0 10px rgba(46,123,246,.45))}
.holo::after{
  content:'';
  position:absolute;
  left:50%;bottom:-4px;
  translate:-50% 0;
  width:84px;height:14px;
  border-radius:50%;
  background:radial-gradient(ellipse, rgba(56,199,244,.5), transparent 70%);
  animation:holo-base 2.6s var(--ease-in-out) infinite;
}
@keyframes holo-base{0%,100%{opacity:.45}50%{opacity:1}}

/* Reporte IA */
.card-ia{border-color:rgba(245,158,45,.55);position:relative;overflow:hidden}
html[data-theme="light"] .card-ia{background:linear-gradient(180deg,#0E1740,#0D1438);color:#EAF1FF}
html[data-theme="light"] .card-ia .muted{color:#8FA3CF}
.card-ia h3{color:var(--orange)}
.card-ia .big-num{font-family:'Sora',sans-serif;font-size:46px;font-weight:800;line-height:1;color:#fff}
.card-ia .big-label{font-size:15px;font-weight:600;line-height:1.25;margin:4px 0 14px}
.card-ia .brain-img{
  position:absolute;right:-26px;top:-14px;width:170px;height:auto;
  mix-blend-mode:screen;opacity:.9;pointer-events:none;
  animation:brain-pulse 3s var(--ease-in-out) infinite;
  -webkit-mask-image:radial-gradient(circle at 60% 40%, #000 55%, transparent 78%);
          mask-image:radial-gradient(circle at 60% 40%, #000 55%, transparent 78%);
}
@keyframes brain-pulse{0%,100%{opacity:.7}50%{opacity:1}}
.btn-orange{
  display:inline-flex;align-items:center;gap:8px;
  padding:10px 16px;
  border-radius:var(--r-md);
  border:1px solid rgba(245,158,45,.6);
  font-size:13.5px;font-weight:700;color:var(--orange);
  transition:background-color 150ms ease, transform 160ms var(--ease-out);
}
.btn-orange:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){.btn-orange:hover{background:rgba(245,158,45,.12)}}

/* Calendario widget */
.cal-head{display:flex;align-items:center;justify-content:space-between;font-size:13px;font-weight:700;background:rgba(46,123,246,.15);border:1px solid var(--stroke);border-radius:10px;padding:8px 12px;margin-bottom:10px}
.cal-head .arrows{display:flex;gap:6px}
.cal-head .arrows button{color:var(--txt-soft);padding:0 4px;transition:color 150ms ease}
.cal{width:100%;border-collapse:collapse;font-size:11.5px;text-align:center}
.cal th{color:var(--txt-soft);font-weight:600;padding:5px 0}
.cal td{padding:5px 0;color:var(--txt);border-radius:8px}
.cal td.off{color:var(--off)}
.cal td.today{background:linear-gradient(135deg,var(--blue),var(--cyan));font-weight:700;box-shadow:0 4px 14px -4px rgba(46,123,246,.8)}

/* Acciones rápidas */
.quick{display:flex;flex-direction:column;gap:12px}
.quick .qbtn{display:flex;align-items:center;gap:12px;padding:13px 16px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2);font-size:14px;font-weight:600;transition:border-color 150ms ease, background-color 150ms ease, transform 160ms var(--ease-out)}
.quick .qbtn:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){.quick .qbtn:hover{border-color:var(--stroke-strong);background:var(--card)}}
.quick .qbtn svg{width:19px;height:19px;color:var(--cyan)}
.quick .qbtn.wa svg{color:#25D366}

/* Tabla */
.tbl-wrap{overflow-x:auto}
table.tbl{width:100%;border-collapse:collapse;font-size:14px;min-width:540px}
.tbl th{text-align:left;font-size:12.5px;font-weight:600;color:var(--txt-soft);padding:10px 12px;border-bottom:1px solid var(--stroke)}
.tbl td{padding:14px 12px;border-bottom:1px solid rgba(110,160,255,.08)}
.tbl tr:last-child td{border-bottom:0}
.tbl tbody tr{transition:background-color 150ms ease}
@media (hover:hover) and (pointer:fine){.tbl tbody tr:hover{background:rgba(110,160,255,.05)}}
.pat{display:flex;align-items:center;gap:10px;font-weight:600}
.pat .mini{width:32px;height:32px;border-radius:50%;background:rgba(46,123,246,.2);border:1px solid var(--stroke-strong);display:grid;place-items:center;font-size:11px;font-weight:700;color:var(--cyan)}
.tbl .dots{color:var(--txt-soft);font-weight:700;letter-spacing:2px}

/* Dona de estudios */
.donut-box{display:flex;align-items:center;gap:18px;margin-bottom:18px}
.donut{position:relative;width:128px;height:128px;flex:none}
.donut svg{width:100%;height:100%;transform:rotate(-90deg)}
.donut circle{fill:none;stroke-width:14;stroke-linecap:round}
.donut .track{stroke:rgba(110,160,255,.12)}
.donut-center{position:absolute;inset:0;display:grid;place-items:center;text-align:center}
.donut-center .n{font-family:'Sora',sans-serif;font-size:28px;font-weight:800;line-height:1}
.donut-center .l{font-size:10.5px;color:var(--txt-soft);line-height:1.2;margin-top:3px}
.legend{display:flex;flex-direction:column;gap:9px;font-size:13px}
.legend i{display:inline-block;width:9px;height:9px;border-radius:50%;margin-right:8px}
.legend .b i{background:var(--blue)}
.legend .g i{background:var(--green)}
.legend .r i{background:var(--red)}
.next-list h4{font-size:12.5px;font-weight:600;color:var(--txt-soft);margin-bottom:10px}
.next-item{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:8px 0;font-size:13px;border-bottom:1px solid rgba(110,160,255,.08)}
.next-item:last-child{border-bottom:0}
.next-item .t{color:var(--txt-soft);font-size:12px;flex:none}
.next-item .n{font-weight:600;flex:1}

/* IA Predictiva */
.card-pred{
  border-color:rgba(56,199,244,.4);
  display:grid;
  grid-template-columns:1.2fr .65fr 1fr 1.15fr;
  gap:26px;
  align-items:start;
}
.pred-head{display:flex;align-items:flex-start;gap:14px;margin-bottom:14px}
.pred-head .orb{width:44px;height:44px;flex:none;border-radius:12px;border:1px solid var(--stroke-strong);display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.08)}
.pred-head h3{margin-bottom:2px;font-size:16px}
.pred-head p{font-size:12.5px;color:var(--txt-soft);line-height:1.4}
.pred-note{border:1px solid var(--stroke-strong);border-radius:var(--r-md);background:rgba(46,123,246,.08);padding:14px 16px;font-size:13.5px;line-height:1.5}
.pred-note b{color:var(--cyan)}
.gauge-box{text-align:center}
.gauge-box h4,.recs h4,.hist h4{font-family:'Sora',sans-serif;font-size:13.5px;font-weight:600;margin-bottom:14px}
.gauge{position:relative;width:122px;height:122px;margin:0 auto}
.gauge svg{width:100%;height:100%;transform:rotate(-90deg)}
.gauge circle{fill:none;stroke-width:11;stroke-linecap:round}
.gauge .track{stroke:rgba(110,160,255,.12)}
.gauge .val{stroke:var(--orange);transition:stroke-dashoffset 1.2s var(--ease-out)}
.gauge-center{position:absolute;inset:0;display:grid;place-items:center;text-align:center}
.gauge-center .lvl{font-size:12px;font-weight:700;color:var(--orange)}
.gauge-center .pct{font-family:'Sora',sans-serif;font-size:25px;font-weight:800;line-height:1.05}
.recs ul{list-style:none}
.recs li{display:flex;align-items:flex-start;gap:10px;font-size:13.5px;line-height:1.4;padding:7px 0}
.recs li svg{width:18px;height:18px;flex:none;color:var(--green);margin-top:1px}
.hist h4{color:var(--green)}
.hist-item{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:9px 0;font-size:14px;border-bottom:1px solid rgba(110,160,255,.08)}
.hist-item span{color:var(--txt-soft);font-size:13px}
.hist .tbl-link{justify-content:flex-start;margin-top:12px}

/* Responsive */
@media (max-width:1280px){
  .widget[data-w="3"]{grid-column:span 6}
  .widget[data-w="4"]{grid-column:span 6}
  .widget[data-w="5"]{grid-column:span 6}
  .widget[data-w="6"]{grid-column:span 12}
  .widget[data-w="8"]{grid-column:span 12}
  .card-pred{grid-template-columns:1fr 1fr}
}
@media (max-width:720px){
  .widget[data-w]{grid-column:span 12 !important}
  .card-pred{grid-template-columns:1fr}
}
@media (prefers-reduced-motion: reduce){
  .holo::after,.card-ia .brain-img{animation:none}
  .widget{transition:none}
}
</style>
@endpush

@section('content')

  <div id="widgetGrid">

    {{-- Widget: Próximo Paciente --}}
    <div class="widget rise d2" data-widget-id="next-patient" data-w="3">
      <span class="widget-drag-handle" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
      </span>
      <article class="card card-next">
        <h3>PRÓXIMO PACIENTE</h3>
        @php
          $pacCita = $proximaCita?->paciente;
          $nombreCita = $pacCita?->nombre_completo ?? $proximaCita?->paciente_nombre ?? 'Sin citas próximas';
          $partesNombre = preg_split('/\s+/', trim($nombreCita), 2);
        @endphp
        @if ($proximaCita)
          <div class="name">{{ $partesNombre[0] }}</div>
          <div class="meta">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <b>{{ \Illuminate\Support\Str::of($proximaCita->fecha?->format('d/m/Y').' · '.($proximaCita->hora ?? ''))->trim(' ·') }}</b>
          </div>
          <div class="meta"><b>{{ $proximaCita->procedimiento ?? 'Procedimiento por definir' }}</b></div>
          <a class="btn-line" href="{{ $pacCita ? route('pacientes.index', ['paciente' => $pacCita->id]) : route('pacientes.index') }}" style="margin-top:16px">
            Abrir expediente
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        @else
          <div class="name">Sin citas<br>próximas</div>
          <div class="meta"><b>No hay citas agendadas</b></div>
          <a class="btn-line" href="{{ route('agendar') }}" style="margin-top:16px">
            Agendar cita
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        @endif
        <div class="holo">
          <svg viewBox="0 0 60 100" fill="none" stroke="#38C7F4" stroke-width="1.6" stroke-linecap="round">
            <circle cx="30" cy="14" r="8"/>
            <path d="M30 24v30M30 30 14 44M30 30l16 14M30 54 18 86M30 54l12 32"/>
            <path d="M20 38h20" opacity=".5"/>
            <ellipse cx="30" cy="44" rx="6" ry="8" opacity=".6"/>
          </svg>
        </div>
      </article>
      <span class="widget-resize-handle"></span>
    </div>

    {{-- Widget: Reporte IA --}}
    <div class="widget rise d3" data-widget-id="ia-pending" data-w="3">
      <span class="widget-drag-handle" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
      </span>
      <article class="card card-ia">
        <img class="brain-img" src="{{ asset('images/brain-ia.png') }}" alt="">
        <h3>ESTUDIOS SIN REPORTES</h3>
        <div class="big-num" id="numReportes" data-target="{{ $estudiosSinReporte ?? 0 }}">0</div>
        <div class="big-label">estudios sin reporte<br><span class="muted">pendientes de generar</span></div>
        <a class="btn-orange" href="{{ route('ia-reportes.redactar') }}">
          Generar reportes
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </article>
      <span class="widget-resize-handle"></span>
    </div>

    {{-- Widget: Agenda --}}
    <div class="widget rise d4" data-widget-id="agenda-today" data-w="3">
      <span class="widget-drag-handle" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
      </span>
      <article class="card" style="cursor:pointer" onclick="window.location.href='{{ route('agendar') }}'">
        <h3>AGENDAR DÍA</h3>
        <div class="cal-head">
          <span>Junio 2026</span>
          <span class="arrows"><button aria-label="Mes anterior">‹</button><button aria-label="Mes siguiente">›</button></span>
        </div>
        <table class="cal">
          <thead>
            <tr><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td class="today">6</td><td>7</td></tr>
            <tr><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td><td>13</td><td>14</td></tr>
            <tr><td>15</td><td>16</td><td>17</td><td>18</td><td>19</td><td>20</td><td>21</td></tr>
            <tr><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td><td>28</td></tr>
            <tr><td>29</td><td>30</td><td class="off">1</td><td class="off">2</td><td class="off">3</td><td class="off">4</td><td class="off">5</td></tr>
          </tbody>
        </table>
      </article>
      <span class="widget-resize-handle"></span>
    </div>

    {{-- Widget: Acciones Rápidas --}}
    <div class="widget rise d5" data-widget-id="new-study" data-w="3">
      <span class="widget-drag-handle" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
      </span>
      <article class="card">
        <h3>ACCIONES RÁPIDAS</h3>
        <div class="quick">
          <a class="qbtn" href="{{ route('nuevo-estudio') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nuevo estudio
          </a>
          <a class="qbtn wa" href="{{ route('mensajes') }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2a9.9 9.9 0 0 0-8.5 14.9L2 22l5.25-1.5A9.9 9.9 0 1 0 12.04 2zm5.8 14.1c-.25.7-1.45 1.35-2 1.4-.5.05-1.15.07-1.85-.12a16 16 0 0 1-1.7-.62c-3-1.3-4.95-4.3-5.1-4.5-.15-.2-1.2-1.6-1.2-3.05 0-1.45.75-2.15 1-2.45.25-.3.55-.37.75-.37h.55c.17 0 .4-.06.62.48.25.6.8 2.05.87 2.2.07.15.12.32.02.52-.1.2-.15.32-.3.5l-.45.52c-.15.15-.3.32-.13.62.17.3.77 1.27 1.65 2.06 1.13 1 2.1 1.32 2.4 1.47.3.15.47.12.65-.07.17-.2.75-.87.95-1.17.2-.3.4-.25.67-.15.27.1 1.7.8 2 .95.3.15.5.22.57.35.07.12.07.7-.18 1.43z"/></svg>
            Enviar WhatsApp
          </a>
          <a class="qbtn" href="{{ route('pacientes.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Buscar paciente
          </a>
        </div>
      </article>
      <span class="widget-resize-handle"></span>
    </div>

    {{-- Widget: Pacientes Pendientes Hoy --}}
    <div class="widget rise d6" data-widget-id="next-list" data-w="8">
      <span class="widget-drag-handle" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
      </span>
      <article class="card">
        <h3>PACIENTES PENDIENTES HOY</h3>
        <div class="tbl-wrap">
          <table class="tbl">
            <thead>
              <tr><th>Paciente</th><th>Hora</th><th>Tipo de estudio</th><th>Estado</th><th>Médico</th><th>Acciones</th></tr>
            </thead>
            <tbody>
              @forelse (($pendientesHoy ?? []) as $cita)
                @php
                  $nombreCita = $cita->paciente?->nombre_completo ?? $cita->paciente_nombre ?? 'Paciente';
                  $partes = preg_split('/\s+/', trim($nombreCita));
                  $mini = count($partes) >= 2
                    ? mb_strtoupper(mb_substr($partes[0], 0, 1).mb_substr($partes[1], 0, 1))
                    : mb_strtoupper(mb_substr($nombreCita, 0, 2));
                  $chip = $cita->estado === 'en_espera'
                    ? ['wait', 'En espera']
                    : ['wait', 'Próximo'];
                  $medico = $cita->paciente?->medico ?: '—';
                @endphp
                <tr>
                  <td><span class="pat"><span class="mini">{{ $mini }}</span>{{ $nombreCita }}</span></td>
                  <td>{{ $cita->hora ?? '—' }}</td>
                  <td>{{ $cita->procedimiento ?? 'Sin procedimiento' }}</td>
                  <td><span class="chip {{ $chip[0] }}">{{ $chip[1] }}</span></td>
                  <td>{{ $medico }}</td>
                  <td><button class="dots" aria-label="Más opciones">⋮</button></td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" style="text-align:center;padding:24px;color:var(--txt-soft)">No hay pacientes pendientes para hoy.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <a class="tbl-link" href="{{ route('agenda') }}">
          Ver agenda completa
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </article>
      <span class="widget-resize-handle"></span>
    </div>

    {{-- Widget: Resumen del día --}}
    <div class="widget rise d7" data-widget-id="agenda-summary" data-w="4">
      <span class="widget-drag-handle" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
      </span>
      <article class="card">
        <h3>RESUMEN DE ESTUDIOS</h3>
        <div class="donut-box">
          <div class="donut">
            <svg viewBox="0 0 120 120">
              <circle class="track" cx="60" cy="60" r="50"/>
              {{-- circunferencia = 314.16 | azul 8/12, verde 2/12, rojo 2/12 --}}
              <circle cx="60" cy="60" r="50" stroke="#2E7BF6" stroke-dasharray="209.4 314.16" stroke-dashoffset="0"/>
              <circle cx="60" cy="60" r="50" stroke="#3DDC97" stroke-dasharray="52.36 314.16" stroke-dashoffset="-209.4"/>
              <circle cx="60" cy="60" r="50" stroke="#FF5A6E" stroke-dasharray="52.36 314.16" stroke-dashoffset="-261.8"/>
            </svg>
            <div class="donut-center">
              <div>
                <div class="n" id="numEstudios" data-target="12">0</div>
                <div class="l">Total de<br>estudios</div>
              </div>
            </div>
          </div>
          <div class="legend">
            <span class="b"><i></i>8 Pendientes</span>
            <span class="g"><i></i>2 Completados</span>
            <span class="r"><i></i>2 Cancelados</span>
          </div>
        </div>
        <div class="next-list">
          <h4>Próximos estudios</h4>
          <div class="next-item"><span class="t">10:30 AM</span><span class="n">Ana Ramírez</span><span class="chip wait">En espera</span></div>
          <div class="next-item"><span class="t">11:15 AM</span><span class="n">Luis Mendoza</span><span class="chip wait">En espera</span></div>
          <div class="next-item"><span class="t">12:00 PM</span><span class="n">Carla Ortiz</span><span class="chip urgent">Urgente</span></div>
        </div>
      </article>
      <span class="widget-resize-handle"></span>
    </div>

    {{-- Widget: IA Predictiva --}}
    <div class="widget rise d8" data-widget-id="ia-risk" data-w="12">
      <span class="widget-drag-handle" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
      </span>
      <article class="card card-pred">
        <div>
          <div class="pred-head">
            <div class="orb">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
            </div>
            <div>
              <h3>IA Predictiva</h3>
              <p>Análisis inteligente basado en el historial clínico</p>
            </div>
          </div>
          <div class="pred-note">
            Tu próximo paciente presenta antecedentes de <b>gastritis crónica</b> y <b>riesgo moderado</b> de úlceras pépticas.
          </div>
        </div>
        <div class="gauge-box">
          <h4>Nivel de riesgo</h4>
          <div class="gauge">
            <svg viewBox="0 0 120 120">
              <circle class="track" cx="60" cy="60" r="50"/>
              <circle class="val" cx="60" cy="60" r="50" stroke-dasharray="314.16" stroke-dashoffset="314.16" data-pct="65"/>
            </svg>
            <div class="gauge-center">
              <div>
                <div class="lvl">Moderado</div>
                <div class="pct"><span id="numRiesgo" data-target="65">0</span>%</div>
              </div>
            </div>
          </div>
        </div>
        <div class="recs">
          <h4>Recomendaciones IA</h4>
          <ul>
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              Revisar historial de biopsias previas
            </li>
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              Considerar toma de muestra
            </li>
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              Monitorear signos vitales
            </li>
          </ul>
        </div>
        <div class="hist">
          <h4>Historial relevante</h4>
          <div class="hist-item">Gastritis crónica <span>2024</span></div>
          <div class="hist-item">Reflujo gastroesofágico <span>2023</span></div>
          <div class="hist-item">Colonoscopia normal <span>2022</span></div>
          <a class="tbl-link" href="#">
            Ver historial completo
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
      </article>
      <span class="widget-resize-handle"></span>
    </div>

  </div>{{-- /#widgetGrid --}}

@endsection

@push('scripts')
<script>
(function(){
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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

  /* ============ WIDGET DRAG & REORDER ============ */
  (function(){
    const grid = document.getElementById('widgetGrid');
    if (!grid) return;

    let dragging = null, ghost = null, originNext = null;

    function getWidgets() {
      return Array.from(grid.querySelectorAll('.widget:not(.widget-ghost)'));
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

    /* ---- Resize (horizontal – cambia data-w) ---- */
    let resizing = null, resizeStart = 0, resizeW0 = 0;
    grid.addEventListener('mousedown', e => {
      if (!e.target.closest('.widget-resize-handle')) return;
      e.preventDefault(); e.stopPropagation();
      resizing = e.target.closest('.widget');
      resizeStart = e.clientX;
      resizeW0 = parseInt(resizing.dataset.w, 10);
    });
    document.addEventListener('mousemove', e => {
      if (!resizing) return;
      const colW = grid.offsetWidth / 12;
      const delta = Math.round((e.clientX - resizeStart) / colW);
      const newW = Math.max(3, Math.min(12, resizeW0 + delta));
      resizing.dataset.w = newW;
      resizing.style.gridColumn = 'span ' + newW;
    });
    document.addEventListener('mouseup', () => {
      if (resizing) { saveOrder(); resizing = null; }
    });

    /* ---- Persistencia orden + tamaño ---- */
    function saveOrder() {
      const order = getWidgets().map(w => ({ id: w.dataset.widgetId, w: w.dataset.w }));
      try { localStorage.setItem('dbWidgetOrder', JSON.stringify(order)); } catch(e) {}
    }

    function restoreOrder() {
      try {
        const saved = JSON.parse(localStorage.getItem('dbWidgetOrder') || 'null');
        if (!saved) return;
        saved.forEach(({ id, w }) => {
          const el = grid.querySelector(`[data-widget-id="${id}"]`);
          if (!el) return;
          el.dataset.w = w;
          el.style.gridColumn = 'span ' + w;
          grid.appendChild(el);
        });
      } catch(e) {}
    }

    restoreOrder();

    /* ---- Escuchar cambios del editor de widgets ---- */
    window.addEventListener('dbWidgetsChanged', e => {
      const prefs = e.detail;
      getWidgets().forEach(w => {
        const id = w.dataset.widgetId;
        if (!id) return;
        const visible = prefs[id] !== undefined ? prefs[id] : true;
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
          if (prefs[id] === false) w.classList.add('widget-hidden');
        });
      } catch(e) {}
    })();
  })();

})();
</script>
@endpush