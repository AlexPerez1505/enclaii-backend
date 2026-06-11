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

/* Fila de KPIs */
.stats{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:18px;
  margin-bottom:22px;
}
.stat{position:relative;overflow:hidden}
.stat .ico{
  width:46px;height:46px;
  border-radius:12px;
  display:grid;place-items:center;
  margin-bottom:14px;
}
.stat h3{font-size:12.5px;letter-spacing:.04em;margin-bottom:8px;color:var(--txt-soft)}
.stat .num{
  font-family:'Sora',sans-serif;
  font-size:38px;
  font-weight:800;
  line-height:1;
}
.stat .tag{font-size:12.5px;color:var(--txt-soft);margin-top:6px}
.stat .trend{
  display:inline-flex;align-items:center;gap:5px;
  margin-top:12px;
  font-size:12.5px;
  font-weight:700;
}
.stat .trend.up{color:var(--green)}
.stat .trend .vs{color:var(--txt-soft);font-weight:500}
/* Modificadores de color por tarjeta */
.stat.blue{border-color:rgba(46,123,246,.45)}
.stat.blue .ico{background:rgba(46,123,246,.12);color:var(--blue)}
.stat.blue .num{color:var(--cyan)}
.stat.orange{border-color:rgba(245,158,45,.5)}
.stat.orange .ico{background:rgba(245,158,45,.12);color:var(--orange)}
.stat.orange .num{color:var(--orange)}
.stat.red{border-color:rgba(255,90,110,.5)}
.stat.red .ico{background:rgba(255,90,110,.12);color:var(--red)}
.stat.red .num{color:var(--red)}
.stat.green{border-color:rgba(61,220,151,.5)}
.stat.green .ico{background:rgba(61,220,151,.12);color:var(--green)}
.stat.green .num{color:var(--green)}

/* Layout principal: tabla + panel lateral */
.rep-grid{
  display:grid;
  grid-template-columns:2.4fr 1fr;
  gap:18px;
  margin-bottom:22px;
}
.rep-side{display:flex;flex-direction:column;gap:18px}

/* Cabecera de la tarjeta de reportes */
.card-head{
  display:flex;align-items:center;justify-content:space-between;
  gap:14px;
  margin-bottom:18px;
  flex-wrap:wrap;
}
.card-head h3{margin-bottom:0;font-size:15px}
.btn-gen{
  display:inline-flex;align-items:center;gap:8px;
  padding:11px 18px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:linear-gradient(135deg,#1668D9,var(--blue));
  color:#fff;
  font-size:13.5px;
  font-weight:700;
  box-shadow:0 8px 22px -8px rgba(46,123,246,.6);
  transition:filter 150ms ease, transform 160ms var(--ease-out);
}
.btn-gen:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){
  .btn-gen:hover{filter:brightness(1.1)}
}

/* Tabla de reportes */
.tbl-wrap{overflow-x:auto}
table.tbl{width:100%;border-collapse:collapse;font-size:14px;min-width:640px}
.tbl th{
  text-align:left;
  font-size:12.5px;
  font-weight:600;
  color:var(--txt-soft);
  padding:10px 12px;
  border-bottom:1px solid var(--stroke);
}
.tbl td{padding:14px 12px;border-bottom:1px solid rgba(110,160,255,.08)}
.tbl tr:last-child td{border-bottom:0}
.tbl tbody tr{transition:background-color 150ms ease}
@media (hover:hover) and (pointer:fine){
  .tbl tbody tr:hover{background:rgba(110,160,255,.05)}
}
.pat{display:flex;align-items:center;gap:10px;font-weight:600}
.pat .mini{
  width:32px;height:32px;
  border-radius:50%;
  background:rgba(46,123,246,.2);
  border:1px solid var(--stroke-strong);
  display:grid;place-items:center;
  font-size:11px;font-weight:700;
  color:var(--cyan);
}
.tbl .date{line-height:1.3}
.tbl .date small{display:block;color:var(--txt-soft);font-size:11.5px}

/* Mini dona de confianza IA */
.conf{display:flex;align-items:center;gap:8px}
.conf .ring{position:relative;width:42px;height:42px;flex:none}
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
.row-actions button{
  width:32px;height:32px;
  display:grid;place-items:center;
  border-radius:8px;
  color:var(--txt-soft);
  transition:color 150ms ease, background-color 150ms ease;
}
@media (hover:hover) and (pointer:fine){
  .row-actions button:hover{color:var(--cyan);background:rgba(56,199,244,.1)}
}
.row-actions svg{width:17px;height:17px}

/* Panel: hallazgos detectados */
.find{padding:11px 0;border-bottom:1px solid rgba(110,160,255,.08)}
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
  padding:8px 0;
}
.recs li svg{width:18px;height:18px;flex:none;color:var(--green);margin-top:1px}

/* Análisis predictivo (fila inferior) */
.card-pred{
  border-color:rgba(56,199,244,.4);
  display:grid;
  grid-template-columns:1.3fr 1fr 1fr;
  gap:26px;
  align-items:center;
}
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
.prob{text-align:center}
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

/* Aviso legal */
.disclaimer{
  display:flex;align-items:center;gap:10px;
  margin-top:18px;
  padding:14px 18px;
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
  .card-pred{grid-template-columns:1fr}
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
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="13" y2="17"/></svg>
      </div>
      <h3>Reportes generados</h3>
      <div class="num" id="kpiReportes" data-target="156">0</div>
      <div class="tag">Este mes</div>
      <div class="trend up">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
        18% <span class="vs">vs mes anterior</span>
      </div>
    </article>

    <article class="card stat orange rise d3">
      <div class="ico">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <h3>Pendientes de revisión</h3>
      <div class="num" id="kpiPendientes" data-target="12">0</div>
      <div class="tag">Este mes</div>
      <div class="trend up">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
        5% <span class="vs">vs mes anterior</span>
      </div>
    </article>

    <article class="card stat red rise d4">
      <div class="ico">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      </div>
      <h3>Hallazgos críticos</h3>
      <div class="num" id="kpiCriticos" data-target="8">0</div>
      <div class="tag">Detectados</div>
      <div class="trend up">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
        3% <span class="vs">vs ayer</span>
      </div>
    </article>

    <article class="card stat green rise d5">
      <div class="ico">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11.5 14.5 16 9"/></svg>
      </div>
      <h3>Precisión IA</h3>
      <div class="num"><span id="kpiPrecision" data-target="98">0</span>%</div>
      <div class="tag">Este mes</div>
      <div class="trend up">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
        18% <span class="vs">vs mes anterior</span>
      </div>
    </article>

  </section>

  {{-- ============ Tabla + panel lateral ============ --}}
  <section class="rep-grid">

    {{-- Tabla de reportes --}}
    <article class="card rise d5">
      <div class="card-head">
        <h3>Reportes generados por IA</h3>
        <button class="btn-gen">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
          Generar reporte IA
        </button>
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
                  <button aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                  <button aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button>
                  <button aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></button>
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
                  <button aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                  <button aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button>
                  <button aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></button>
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
                  <button aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                  <button aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button>
                  <button aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></button>
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
                  <button aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                  <button aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button>
                  <button aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <a class="tbl-link" href="#">
        Ver todos los reportes
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </article>

    {{-- Panel lateral --}}
    <div class="rep-side">

      <article class="card rise d6">
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

        <a class="tbl-link" href="#">
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
        <a class="tbl-link" href="#">
          Ver todas las recomendaciones
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </article>

    </div>

  </section>

  {{-- ============ Análisis predictivo IA ============ --}}
  <section>
    <article class="card card-pred rise d7">

      <div>
        <div class="pred-head">
          <div class="orb">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
          </div>
          <div>
            <h3>Análisis predictivo IA</h3>
            <p>Basado en patrones detectados por IA</p>
          </div>
        </div>
        <div class="pred-pat"><span class="mini">MG</span><b>María González</b></div>
        <div class="pred-meta">
          Estudio: Endoscopia digestiva alta<br>
          Fecha: 08/05/2025
        </div>
      </div>

      <div class="prob">
        <h4>Probabilidad de gastritis</h4>
        <div class="gauge">
          <svg viewBox="0 0 120 120">
            <circle class="track" cx="60" cy="60" r="50"/>
            <circle class="val" cx="60" cy="60" r="50" stroke-dasharray="314.16" stroke-dashoffset="314.16" data-pct="82"/>
          </svg>
          <div class="gauge-center">
            <div>
              <div class="pct"><span id="probGastritis" data-target="82">0</span>%</div>
              <div class="lbl">Probabilidad</div>
            </div>
          </div>
        </div>
      </div>

      <div class="risk">
        <h4>Nivel de riesgo</h4>
        <div class="lvl">Moderado</div>
        <div class="sub">Recomendación de seguimiento</div>
        <a class="btn-line" href="#">
          Ver análisis completo
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

    </article>

    <div class="disclaimer rise d7">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
      Los análisis generados por IA son una herramienta de apoyo. La decisión final siempre debe ser del profesional de la salud.
    </div>
  </section>

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

  if (reduced || typeof gsap === 'undefined') {
    counters.forEach(c => { c.textContent = parseInt(c.dataset.target, 10).toLocaleString('es-MX'); });
    document.querySelectorAll('.conf .ring .val,.gauge .val,.bar i').forEach(el => el.style.transition = 'none');
    drawAll();
    return;
  }

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

  setTimeout(drawAll, 400);
})();
</script>
@endpush
