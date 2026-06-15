@extends('layouts.app')

@section('title', 'IA Reportes')
@section('active', 'ia-reportes')
@section('header-title', 'IA Reportes')
@section('header-sub')
  Genera, analiza y revisa reportes inteligentes impulsados por IA
@endsection

@push('styles')
<style>
/* ============ ESTILOS SOLO DE IA REPORTES ============ */

/* Compactar tarjetas en esta pantalla para que todo entre de primera */
.main{padding-top:20px;padding-bottom:22px}
.head{margin-bottom:16px}
.card{padding:16px 18px}
.stat::after{
  content:"";
  position:absolute;top:0;bottom:0;left:-75%;width:55%;
  background:linear-gradient(100deg,transparent 0%,rgba(255,255,255,.13) 50%,transparent 100%);
  transform:skewX(-18deg);
  pointer-events:none;
  animation:cardShine 5.6s ease-in-out infinite;
}
/* En tema claro el brillo blanco no se aprecia: usar un brillo gris */
html[data-theme="light"] .stat::after{
  background:linear-gradient(100deg,transparent 0%,rgba(110,120,140,.18) 50%,transparent 100%);
}
/* El barrido ocurre solo en el primer 25% del ciclo; el resto queda fuera.
   Con los retardos escalonados, una tarjeta termina de brillar y empieza la siguiente. */
@keyframes cardShine{
  0%{left:-75%}
  25%{left:150%}
  100%{left:150%}
}
.stat.d2::after{animation-delay:0s}
.stat.d3::after{animation-delay:1.4s}
.stat.d4::after{animation-delay:2.8s}
.stat.d5::after{animation-delay:4.2s}
@media (prefers-reduced-motion: reduce){
  .stat::after{display:none}
}
.card h3{margin-bottom:10px}
.tbl-link{margin-top:6px}

/* Fila de KPIs */
.stats{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:14px;
  margin-bottom:14px;
}
.stat{
  position:relative;overflow:hidden;
  display:flex;align-items:center;gap:16px;
  padding:14px 18px;
}
.stat .ico{
  width:60px;height:60px;flex:none;
  border-radius:50%;
  display:grid;place-items:center;
}
.stat-body{min-width:0}
.stat h3{font-size:14px;font-weight:700;letter-spacing:.01em;margin-bottom:2px}
.stat .num{
  font-family:'Sora',sans-serif;
  font-size:34px;
  font-weight:800;
  line-height:1.05;
  color:var(--txt);
}
.stat .tag{font-size:13px;color:var(--txt);margin-top:2px;font-weight:600}
.stat .trend{
  display:inline-flex;align-items:center;gap:5px;
  margin-top:8px;
  font-size:12.5px;
  font-weight:700;
}
.stat .trend.up{color:var(--green)}
.stat .trend .vs{color:var(--txt-soft);font-weight:500}
/* Color por tarjeta: aro del icono + icono + título */
.stat.blue{border-color:rgba(46,123,246,.45)}
.stat.blue .ico{border:1.5px solid rgba(56,199,244,.55);background:rgba(56,199,244,.08);color:var(--cyan)}
.stat.blue h3{color:var(--cyan)}
.stat.orange{border-color:rgba(245,158,45,.5)}
.stat.orange .ico{border:1.5px solid rgba(245,158,45,.55);background:rgba(245,158,45,.08);color:var(--orange)}
.stat.orange h3{color:var(--orange)}
.stat.red{border-color:rgba(255,90,110,.5)}
.stat.red .ico{border:1.5px solid rgba(255,90,110,.55);background:rgba(255,90,110,.08);color:var(--red)}
.stat.red h3{color:var(--red)}
.stat.green{border-color:rgba(61,220,151,.5)}
.stat.green .ico{border:1.5px solid rgba(61,220,151,.55);background:rgba(61,220,151,.08);color:var(--green)}
.stat.green h3{color:var(--green)}

/* Layout principal: tabla + panel lateral */
.rep-grid{
  display:grid;
  grid-template-columns:2.4fr 1fr;
  gap:14px;
  margin-bottom:14px;
  align-items:stretch;
}
.rep-tbl{grid-column:1;grid-row:1;min-width:0}
.card-pred{grid-column:1;grid-row:2;min-width:0}
.rep-hall{grid-column:2;grid-row:1}
.rep-grid .recs{grid-column:2;grid-row:2}

/* Cabecera de la tarjeta de reportes */
.card-head{
  display:flex;align-items:center;justify-content:space-between;
  gap:12px;
  margin-bottom:8px;
  flex-wrap:wrap;
}
.card-head h3{margin-bottom:0;font-size:15px}
.btn-gen{
  display:inline-flex;align-items:center;gap:7px;
  padding:8px 13px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:linear-gradient(135deg,#1668D9,var(--blue));
  color:#fff;
  font-size:13px;
  font-weight:700;
  white-space:nowrap;
  box-shadow:0 8px 22px -8px rgba(46,123,246,.6);
  transition:filter 150ms ease, transform 160ms var(--ease-out);
}
.btn-gen:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){
  .btn-gen:hover{filter:brightness(1.1)}
}

/* Tabla de reportes */
.tbl-wrap{overflow-x:visible}
table.tbl{width:100%;border-collapse:collapse;font-size:14px}
.tbl th{
  text-align:left;
  font-size:12.5px;
  font-weight:600;
  color:var(--txt-soft);
  padding:4px 12px;
  border-bottom:1px solid var(--stroke);
}
.tbl td{padding:3px 12px;border-bottom:1px solid rgba(110,160,255,.08)}
.tbl tr:last-child td{border-bottom:0}
.tbl tbody tr{transition:background-color 150ms ease}
@media (hover:hover) and (pointer:fine){
  .tbl tbody tr:hover{background:rgba(110,160,255,.05)}
}
.pat{display:flex;align-items:center;gap:10px;font-weight:600}
.pat .mini{
  width:28px;height:28px;
  border-radius:50%;
  background:rgba(46,123,246,.2);
  border:1px solid var(--stroke-strong);
  display:grid;place-items:center;
  font-size:10.5px;font-weight:700;
  color:var(--cyan);
}
.tbl .date{line-height:1.3}
.tbl .date small{display:block;color:var(--txt-soft);font-size:11.5px}

/* Mini dona de confianza IA */
.conf{display:flex;align-items:center;gap:8px}
.conf .ring{position:relative;width:36px;height:36px;flex:none}
.conf .ring svg{width:100%;height:100%;transform:rotate(-90deg)}
.conf .ring circle{fill:none;stroke-width:5;stroke-linecap:round}
.conf .ring .track{stroke:rgba(110,160,255,.14)}
.conf .ring .val{stroke:var(--cyan)}
.conf .ring span{
  position:absolute;inset:0;
  display:grid;place-items:center;
  font-size:10.5px;font-weight:700;
}

/* Acciones de fila */
.row-actions{display:flex;align-items:center;gap:6px}
.row-actions button,.row-actions a{
  width:28px;height:28px;
  display:grid;place-items:center;
  border-radius:8px;
  color:var(--txt-soft);
  transition:color 150ms ease, background-color 150ms ease;
}
@media (hover:hover) and (pointer:fine){
  .row-actions button:hover,.row-actions a:hover{color:var(--cyan);background:rgba(56,199,244,.1)}
}
.row-actions svg{width:17px;height:17px}

/* Panel: hallazgos detectados */
.find{padding:8px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.find:last-of-type{border-bottom:0}
.find .top{
  display:flex;align-items:center;justify-content:space-between;
  font-size:13.5px;font-weight:600;
  margin-bottom:7px;
}
.find .top b{font-family:'Sora',sans-serif;font-weight:700;color:var(--txt)}
.bar{height:7px;border-radius:99px;background:rgba(110,160,255,.12);overflow:hidden}
.bar i{display:block;height:100%;border-radius:99px;width:0;transition:width 1.1s var(--ease-out)}
.bar.c1 i{background:linear-gradient(90deg,var(--blue),var(--cyan))}
.bar.c2 i{background:linear-gradient(90deg,#7B5CF6,#A98BFF)}
.bar.c3 i{background:linear-gradient(90deg,var(--orange),#FFC368)}
.bar.c4 i{background:linear-gradient(90deg,var(--green),#7BF0BE)}
.bar.c5 i{background:linear-gradient(90deg,var(--red),#FF98A6)}

/* Panel: recomendaciones */
.recs ul{list-style:none}
.recs li{
  display:flex;align-items:flex-start;gap:10px;
  font-size:13.5px;line-height:1.4;
  padding:6px 0;
}
.recs li svg{width:18px;height:18px;flex:none;color:var(--green);margin-top:1px}

/* Análisis predictivo (fila inferior) */
.card-pred{
  border-color:rgba(56,199,244,.4);
  display:grid;
  grid-template-columns:1.05fr 1.5fr 1fr;
  gap:0;
  align-items:center;
}
.card-pred > *{padding:0 22px}
.card-pred > *:first-child{padding-left:0}
.card-pred > *:last-child{padding-right:0}
.card-pred > * + *{border-left:1px solid var(--stroke)}
.pred-head{display:flex;align-items:flex-start;gap:14px;margin-bottom:14px}
.pred-head .orb{
  width:44px;height:44px;flex:none;
  border-radius:12px;
  border:1px solid var(--stroke-strong);
  display:grid;place-items:center;
  color:var(--cyan);
  background:rgba(56,199,244,.08);
}
.pred-head h3{margin-bottom:2px;font-size:16px}
.pred-head p{font-size:12.5px;color:var(--txt-soft);line-height:1.4}
.pred-pat{display:flex;align-items:center;gap:10px;font-size:13.5px;margin-bottom:6px}
.pred-pat .mini{
  width:30px;height:30px;border-radius:50%;
  background:rgba(46,123,246,.2);
  border:1px solid var(--stroke-strong);
  display:grid;place-items:center;
  font-size:10.5px;font-weight:700;color:var(--cyan);
}
.pred-meta{font-size:12.5px;color:var(--txt-soft);line-height:1.6}
.prob{display:flex;align-items:center;justify-content:center;gap:20px}
.prob-info{min-width:0}
.prob-info h4{margin-bottom:0}
.prob-num{
  font-family:'Sora',sans-serif;
  font-size:38px;font-weight:800;
  color:var(--cyan);
  line-height:1;
  margin:8px 0;
}
.prob-sub{font-size:12px;color:var(--txt-soft);line-height:1.45;max-width:160px}
.gauge .stomach{
  width:62px;height:62px;position:relative;overflow:hidden;
  background:#173d72;
  -webkit-mask:url('/images/Vector.png') no-repeat center/contain;
          mask:url('/images/Vector.png') no-repeat center/contain;
}
/* En tema claro se borra el fondo azul oscuro del estómago */
html[data-theme="light"] .gauge .stomach{background:transparent}
.gauge .stomach .water{position:absolute;inset:0;width:100%;height:100%}
.prob h4,.risk h4{
  font-family:'Sora',sans-serif;
  font-size:13.5px;font-weight:600;
  color:var(--txt-soft);
  margin-bottom:14px;
}
.gauge{position:relative;width:122px;height:122px;margin:0 auto}
.gauge svg{width:100%;height:100%;transform:rotate(-90deg)}
.gauge circle{fill:none;stroke-width:11;stroke-linecap:round}
.gauge .track{stroke:rgba(110,160,255,.12)}
.gauge .val{stroke:var(--cyan);transition:stroke-dashoffset 1.2s var(--ease-out)}
.gauge-center{position:absolute;inset:0;display:grid;place-items:center;text-align:center}
.gauge-center .pct{font-family:'Sora',sans-serif;font-size:26px;font-weight:800;line-height:1}
.gauge-center .lbl{font-size:11px;color:var(--txt-soft);margin-top:2px}
.risk{text-align:center}
.risk .lvl{
  font-family:'Sora',sans-serif;
  font-size:28px;font-weight:800;
  color:var(--orange);
}
.risk .sub{font-size:12.5px;color:var(--txt-soft);margin:6px 0 16px}
.pred-fade{transition:opacity .35s ease}
#waterLevel{transition:transform 1.2s var(--ease-out)}

/* Aviso legal */
.disclaimer{
  display:flex;align-items:center;gap:10px;
  margin-top:14px;
  padding:11px 16px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:var(--panel-2);
  font-size:12.5px;color:var(--txt-soft);
}
.disclaimer svg{width:18px;height:18px;flex:none;color:var(--cyan)}

/* Responsive */
@media (max-width:1380px){
  .stats{grid-template-columns:1fr 1fr}
  .rep-grid{grid-template-columns:1fr}
  .rep-tbl,.card-pred,.rep-hall,.rep-grid .recs{grid-column:1;grid-row:auto}
  .card-pred{grid-template-columns:1fr;gap:14px}
  .card-pred > *{padding:0}
  .card-pred > * + *{border-left:0;border-top:1px solid var(--stroke);padding-top:14px}
}
@media (max-width:720px){
  .stats{grid-template-columns:1fr}
}
@media (prefers-reduced-motion: reduce){
  .bar i,.conf .ring .val,.gauge .val{transition:none}
}
</style>
@endpush

@section('content')

  {{-- ============ KPIs ============ --}}
  <section class="stats">

    <article class="card stat blue rise d2">
      <div class="ico">
        <x-hugeicons-note-04 width="26" height="26" />
      </div>
      <div class="stat-body">
        <h3>Reportes generados</h3>
        <div class="num" id="kpiReportes" data-target="156">0</div>
        <div class="tag">Este mes</div>
        <div class="trend up">
          <x-fluentui-arrow-trending-lines-20 width="14" height="14" />
          18% <span class="vs">vs mes anterior</span>
        </div>
      </div>
    </article>

    <article class="card stat orange rise d3">
      <div class="ico">
        <x-fluentui-clock-20 width="26" height="26" />
      </div>
      <div class="stat-body">
        <h3>Pendientes de revisión</h3>
        <div class="num" id="kpiPendientes" data-target="12">0</div>
        <div class="tag">Este mes</div>
        <div class="trend up">
          <x-fluentui-arrow-trending-lines-20 width="14" height="14" />
          5% <span class="vs">vs mes anterior</span>
        </div>
      </div>
    </article>

    <article class="card stat red rise d4">
      <div class="ico">
        <x-fluentui-warning-20 width="26" height="26" />
      </div>
      <div class="stat-body">
        <h3>Hallazgos críticos</h3>
        <div class="num" id="kpiCriticos" data-target="8">0</div>
        <div class="tag">Detectados</div>
        <div class="trend up">
          <x-fluentui-arrow-trending-lines-20 width="14" height="14" />
          3% <span class="vs">vs ayer</span>
        </div>
      </div>
    </article>

    <article class="card stat green rise d5">
      <div class="ico">
        <x-fluentui-target-arrow-20 width="26" height="26" />
      </div>
      <div class="stat-body">
        <h3>Precisión IA</h3>
        <div class="num"><span id="kpiPrecision" data-target="98">0</span>%</div>
        <div class="tag">Este mes</div>
        <div class="trend up">
          <x-fluentui-arrow-trending-lines-20 width="14" height="14" />
          18% <span class="vs">vs mes anterior</span>
        </div>
      </div>
    </article>

  </section>

  {{-- ============ Tabla + panel lateral ============ --}}
  <section class="rep-grid">

    {{-- Tabla de reportes --}}
    <article class="card rep-tbl rise d5">
      <div class="card-head">
        <h3>Reportes generados por IA</h3>
        <a class="btn-gen" href="{{ route('ia-reportes.generar') }}">
          <x-hugeicons-ai-file width="17" height="17" />
          Generar reporte IA
        </a>
      </div>

      <div class="tbl-wrap">
        <table class="tbl">
          <thead>
            <tr>
              <th>Pacientes</th><th>Estudio</th><th>Fecha</th><th>Estado</th><th>Confianza IA</th><th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><span class="pat"><span class="mini">MG</span>María González</span></td>
              <td>Colonoscopia</td>
              <td class="date">08/05/2024 <small>10:30 AM</small></td>
              <td><span class="chip wait">Pendiente</span></td>
              <td>
                <span class="conf">
                  <span class="ring">
                    <svg viewBox="0 0 44 44"><circle class="track" cx="22" cy="22" r="18"/><circle class="val" cx="22" cy="22" r="18" stroke-dasharray="113.1" stroke-dashoffset="113.1" data-pct="92"/></svg>
                    <span>92%</span>
                  </span>
                </span>
              </td>
              <td>
                <div class="row-actions">
                  <a href="{{ route('ia-reportes.ver') }}" aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                  <button aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button>
                  <a href="{{ route('ia-reportes.editar') }}" aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></a>
                </div>
              </td>
            </tr>
            <tr>
              <td><span class="pat"><span class="mini">JL</span>Jorge López</span></td>
              <td>Endoscopia</td>
              <td class="date">08/05/2024 <small>09:15 AM</small></td>
              <td><span class="chip wait">Pendiente</span></td>
              <td>
                <span class="conf">
                  <span class="ring">
                    <svg viewBox="0 0 44 44"><circle class="track" cx="22" cy="22" r="18"/><circle class="val" cx="22" cy="22" r="18" stroke-dasharray="113.1" stroke-dashoffset="113.1" data-pct="88"/></svg>
                    <span>88%</span>
                  </span>
                </span>
              </td>
              <td>
                <div class="row-actions">
                  <a href="{{ route('ia-reportes.ver') }}" aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                  <button aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button>
                  <a href="{{ route('ia-reportes.editar') }}" aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></a>
                </div>
              </td>
            </tr>
            <tr>
              <td><span class="pat"><span class="mini">AR</span>Ana Ramírez</span></td>
              <td>Gastroscopia</td>
              <td class="date">07/05/2024 <small>16:40 PM</small></td>
              <td><span class="chip wait">Pendiente</span></td>
              <td>
                <span class="conf">
                  <span class="ring">
                    <svg viewBox="0 0 44 44"><circle class="track" cx="22" cy="22" r="18"/><circle class="val" cx="22" cy="22" r="18" stroke-dasharray="113.1" stroke-dashoffset="113.1" data-pct="95"/></svg>
                    <span>95%</span>
                  </span>
                </span>
              </td>
              <td>
                <div class="row-actions">
                  <a href="{{ route('ia-reportes.ver') }}" aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                  <button aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button>
                  <a href="{{ route('ia-reportes.editar') }}" aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></a>
                </div>
              </td>
            </tr>
            <tr>
              <td><span class="pat"><span class="mini">PT</span>Pedro Torres</span></td>
              <td>Colonoscopia</td>
              <td class="date">07/05/2024 <small>11:05 AM</small></td>
              <td><span class="chip done">Completado</span></td>
              <td>
                <span class="conf">
                  <span class="ring">
                    <svg viewBox="0 0 44 44"><circle class="track" cx="22" cy="22" r="18"/><circle class="val" cx="22" cy="22" r="18" stroke-dasharray="113.1" stroke-dashoffset="113.1" data-pct="99"/></svg>
                    <span>99%</span>
                  </span>
                </span>
              </td>
              <td>
                <div class="row-actions">
                  <a href="{{ route('ia-reportes.ver') }}" aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                  <button aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button>
                  <a href="{{ route('ia-reportes.editar') }}" aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></a>
                </div>
              </td>
            </tr>
            <tr>
              <td><span class="pat"><span class="mini">LM</span>Laura Méndez</span></td>
              <td>Endoscopia</td>
              <td class="date">06/05/2024 <small>14:20 PM</small></td>
              <td><span class="chip done">Completado</span></td>
              <td>
                <span class="conf">
                  <span class="ring">
                    <svg viewBox="0 0 44 44"><circle class="track" cx="22" cy="22" r="18"/><circle class="val" cx="22" cy="22" r="18" stroke-dasharray="113.1" stroke-dashoffset="113.1" data-pct="91"/></svg>
                    <span>91%</span>
                  </span>
                </span>
              </td>
              <td>
                <div class="row-actions">
                  <a href="{{ route('ia-reportes.ver') }}" aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                  <button aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button>
                  <a href="{{ route('ia-reportes.editar') }}" aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <a class="tbl-link" href="{{ route('ia-reportes.todos') }}">
        Ver todos los reportes
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </article>

    {{-- ============ Análisis predictivo IA ============ --}}
    <article class="card card-pred rise d7">

      <div>
        <div class="pred-head">
          <div class="orb">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
          </div>
          <div>
            <h3>Análisis predictivo IA</h3>
          </div>
        </div>
        <div class="pred-pat pred-fade"><span class="mini" id="predMini">MG</span><b id="predName">María González</b></div>
        <div class="pred-meta pred-fade" id="predMeta">
          Estudio: Endoscopia digestiva alta<br>
          Fecha: 08/05/2025
        </div>
      </div>

      <div class="prob">
        <div class="prob-info">
          <h4 class="pred-fade"><span id="predProbTitle">Probabilidad de gastritis</span></h4>
          <div class="prob-num"><span id="probGastritis" data-target="82">0</span>%</div>
          <p class="prob-sub">Basado en patrones detectados por IA</p>
        </div>
        <div class="gauge">
          <svg viewBox="0 0 120 120">
            <circle class="track" cx="60" cy="60" r="50"/>
            <circle class="val" id="predGauge" cx="60" cy="60" r="50" stroke-dasharray="314.16" stroke-dashoffset="314.16" data-pct="82"/>
          </svg>
          <div class="gauge-center">
            <div class="stomach">
              <svg class="water" viewBox="0 0 62 62" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <linearGradient id="wg" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0" stop-color="#9fe6ff"/>
                    <stop offset="1" stop-color="#5ab4f3"/>
                  </linearGradient>
                  <filter id="bubbleGlow" x="-60%" y="-60%" width="220%" height="220%">
                    <feGaussianBlur stdDeviation=".7" result="b"/>
                    <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
                  </filter>
                </defs>
                <g id="waterLevel">
                <rect x="0" y="27" width="62" height="62" fill="url(#wg)"/>
                <path fill="#7fdcff" fill-opacity=".55" d="M-62 27 q15.5 -3.5 31 0 t31 0 t31 0 L124 64 L-62 64 Z">
                  <animateTransform attributeName="transform" type="translate" from="0 0" to="-62 0" dur="5s" repeatCount="indefinite"/>
                </path>
                <path fill="#cdf2ff" fill-opacity=".7" d="M-62 28 q15.5 3.5 31 0 t31 0 t31 0 L124 64 L-62 64 Z">
                  <animateTransform attributeName="transform" type="translate" from="-62 0" to="0 0" dur="4s" repeatCount="indefinite"/>
                </path>
                <g fill="#ffffff" filter="url(#bubbleGlow)">
                  <circle cx="22" r="2.2" opacity="0">
                    <animate attributeName="cy" values="60;28" dur="2.8s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.15;.8;1" dur="2.8s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="2.2;1.6" dur="2.8s" repeatCount="indefinite"/>
                  </circle>
                  <circle cx="36" r="1.7" opacity="0">
                    <animate attributeName="cy" values="61;30" dur="3.3s" begin=".5s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.15;.8;1" dur="3.3s" begin=".5s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="1.7;1.2" dur="3.3s" begin=".5s" repeatCount="indefinite"/>
                  </circle>
                  <circle cx="44" r="1.5" opacity="0">
                    <animate attributeName="cy" values="59;29" dur="2.5s" begin="1.1s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.15;.8;1" dur="2.5s" begin="1.1s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="1.5;1" dur="2.5s" begin="1.1s" repeatCount="indefinite"/>
                  </circle>
                  <circle cx="29" r="1.9" opacity="0">
                    <animate attributeName="cy" values="60;28" dur="3.6s" begin="1.7s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.15;.8;1" dur="3.6s" begin="1.7s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="1.9;1.3" dur="3.6s" begin="1.7s" repeatCount="indefinite"/>
                  </circle>
                  <circle cx="50" r="1.3" opacity="0">
                    <animate attributeName="cy" values="58;31" dur="3s" begin="2.2s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.15;.8;1" dur="3s" begin="2.2s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="1.3;.9" dur="3s" begin="2.2s" repeatCount="indefinite"/>
                  </circle>
                </g>
                </g>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <div class="risk">
        <h4>Nivel de riesgo</h4>
        <div class="lvl pred-fade" id="predRisk">Moderado</div>
        <div class="sub pred-fade" id="predRiskSub">Recomendación de seguimiento</div>
        <a class="btn-line" id="predLink" href="{{ route('ia-reportes.analisis', ['p' => 0]) }}">
          Ver análisis completo
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

    </article>

    {{-- Hallazgos detectados --}}
      <article class="card rep-hall rise d6">
        <h3>HALLAZGOS DETECTADOS POR IA</h3>

        <div class="find">
          <div class="top"><span>Gastritis crónica</span><b>68%</b></div>
          <div class="bar c1"><i data-w="68"></i></div>
        </div>
        <div class="find">
          <div class="top"><span>Reflujo gastroesofágico</span><b>42%</b></div>
          <div class="bar c2"><i data-w="42"></i></div>
        </div>
        <div class="find">
          <div class="top"><span>Úlcera péptica</span><b>18%</b></div>
          <div class="bar c3"><i data-w="18"></i></div>
        </div>
        <div class="find">
          <div class="top"><span>Pólipos</span><b>11%</b></div>
          <div class="bar c4"><i data-w="11"></i></div>
        </div>
        <div class="find">
          <div class="top"><span>Esofagitis</span><b>9%</b></div>
          <div class="bar c5"><i data-w="9"></i></div>
        </div>

        <a class="tbl-link" href="{{ route('ia-reportes.hallazgos') }}">
          Ver todos los hallazgos
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </article>

      <article class="card recs rise d7">
        <h3>RECOMENDACIONES IA</h3>
        <ul>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Comparar con estudio previo del 2024
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Seguimiento en 3 meses
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Revisar antecedentes gástricos del paciente
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Considerar prueba para H. pylori
          </li>
        </ul>
      </article>

  </section>

  <div class="disclaimer rise d7">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    Los análisis generados por IA son una herramienta de apoyo. La decisión final siempre debe ser del profesional de la salud.
  </div>

@endsection

@push('scripts')
<script>
(function(){
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- Anillos de confianza IA (tabla) y gauge de probabilidad ---- */
  const drawRing = (circle, circumference) => {
    const pct = parseFloat(circle.dataset.pct) / 100;
    circle.style.strokeDashoffset = circumference - (circumference * pct);
  };
  const rings = document.querySelectorAll('.conf .ring .val'); // C = 2*PI*18 = 113.1
  const gauge = document.querySelector('.gauge .val');          // C = 2*PI*50 = 314.16

  /* ---- Barras de hallazgos ---- */
  const bars = document.querySelectorAll('.bar i');

  /* ---- Contadores numéricos ---- */
  const counters = document.querySelectorAll('[data-target]');

  const drawAll = () => {
    rings.forEach(r => drawRing(r, 113.1));
    if (gauge) drawRing(gauge, 314.16);
    bars.forEach(b => { b.style.width = b.dataset.w + '%'; });
  };

  const hasGsap = typeof gsap !== 'undefined';

  if (reduced) {
    counters.forEach(c => { c.textContent = parseInt(c.dataset.target, 10).toLocaleString('es-MX'); });
    document.querySelectorAll('.conf .ring .val,.gauge .val,.bar i').forEach(el => el.style.transition = 'none');
    drawAll();
    return;
  }

  if (hasGsap) {
    counters.forEach((counter, i) => {
      const target = parseInt(counter.dataset.target, 10);
      const obj = { v: 0 };
      gsap.to(obj, {
        v: target,
        duration: 1.4,
        ease: 'expo.out',
        delay: 0.3 + i * 0.08,
        onUpdate: () => { counter.textContent = Math.round(obj.v).toLocaleString('es-MX'); }
      });
    });
  } else {
    counters.forEach(c => { c.textContent = parseInt(c.dataset.target, 10).toLocaleString('es-MX'); });
  }

  setTimeout(drawAll, 400);

  /* ============ Análisis predictivo: rotación de pacientes ============ */
  const PRED = [
    {ini:'MG', name:'María González', study:'Endoscopia digestiva alta', date:'08/05/2025', cond:'gastritis',      prob:82, risk:'Moderado', sub:'Recomendación de seguimiento',  color:'var(--orange)'},
    {ini:'JL', name:'Jorge López',    study:'Endoscopia digestiva alta', date:'08/05/2025', cond:'reflujo',        prob:54, risk:'Bajo',     sub:'Control en 6 meses',           color:'var(--green)'},
    {ini:'AR', name:'Ana Ramírez',    study:'Gastroscopia',              date:'07/05/2025', cond:'úlcera péptica', prob:91, risk:'Alto',     sub:'Requiere atención inmediata',  color:'var(--red)'},
    {ini:'PT', name:'Pedro Torres',   study:'Colonoscopia',              date:'07/05/2025', cond:'pólipos',        prob:37, risk:'Bajo',     sub:'Seguimiento de rutina',        color:'var(--green)'},
    {ini:'LM', name:'Laura Méndez',   study:'Endoscopia digestiva alta', date:'06/05/2025', cond:'esofagitis',     prob:68, risk:'Moderado', sub:'Recomendación de seguimiento', color:'var(--orange)'},
  ];

  const elMini  = document.getElementById('predMini');
  const elName  = document.getElementById('predName');
  const elMeta  = document.getElementById('predMeta');
  const elTitle = document.getElementById('predProbTitle');
  const elNum   = document.getElementById('probGastritis');
  const elGauge = document.getElementById('predGauge');
  const elWater = document.getElementById('waterLevel');
  const elRisk  = document.getElementById('predRisk');
  const elSub   = document.getElementById('predRiskSub');
  const elLink  = document.getElementById('predLink');
  const fades   = document.querySelectorAll('.pred-fade');
  const analisisBase = @json(route('ia-reportes.analisis'));
  const setLink = (i) => { if (elLink) elLink.href = analisisBase + '?p=' + i; };

  if (!elGauge) return;

  let numTween = null;

  const setNum = (to) => {
    const from = parseInt(elNum.textContent, 10) || 0;
    if (hasGsap) {
      if (numTween) numTween.kill();
      const o = { v: from };
      numTween = gsap.to(o, { v: to, duration: 1.2, ease: 'expo.out',
        onUpdate: () => { elNum.textContent = Math.round(o.v); } });
    } else {
      elNum.textContent = to;
    }
  };

  const applyPred = (p) => {
    // Gauge (anillo)
    elGauge.dataset.pct = p.prob;
    drawRing(elGauge, 314.16);
    // Nivel de agua: sube/baja según probabilidad
    const dy = (62 * (1 - p.prob / 100)) - 27;
    elWater.style.transform = 'translateY(' + dy + 'px)';
    // Número
    setNum(p.prob);
  };

  let idx = 0;
  applyPred(PRED[0]); // sincroniza nivel inicial
  setLink(0);

  const cycle = () => {
    idx = (idx + 1) % PRED.length;
    const p = PRED[idx];
    fades.forEach(f => f.style.opacity = '0');
    setTimeout(() => {
      elMini.textContent  = p.ini;
      elName.textContent  = p.name;
      elMeta.innerHTML    = 'Estudio: ' + p.study + '<br>Fecha: ' + p.date;
      elTitle.textContent = 'Probabilidad de ' + p.cond;
      elRisk.textContent  = p.risk;
      elRisk.style.color  = p.color;
      elSub.textContent   = p.sub;
      applyPred(p);
      setLink(idx);
      fades.forEach(f => f.style.opacity = '1');
    }, 350);
  };

  setInterval(cycle, 9000);
})();
</script>
@endpush
