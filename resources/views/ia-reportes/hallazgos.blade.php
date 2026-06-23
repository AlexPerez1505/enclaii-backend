@extends('layouts.app')

@section('title', 'Hallazgos detectados por IA')
@section('active', 'ia-reportes')
@section('header-title', 'Hallazgos detectados por IA')
@section('header-sub')
  Detalle completo de los hallazgos identificados por la IA en el estudio
@endsection

@push('styles')
<style>
/* ============ HALLAZGOS (vista completa) ============ */
.hz-top{display:flex;justify-content:flex-end;margin-bottom:16px}
.hz-back{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);background:var(--panel-2);font-weight:600;font-size:13.5px;transition:background-color .15s}
.hz-back svg{width:16px;height:16px}
@media (hover:hover){.hz-back:hover{background:rgba(110,160,255,.1)}}

.hz-grid{display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:stretch}
@media (max-width:1100px){.hz-grid{grid-template-columns:1fr}}
.hz-col{display:flex;flex-direction:column;gap:16px;min-height:0}

.hz-pat{display:flex;align-items:center;gap:12px;margin-bottom:18px}
.hz-pat .av{width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:grid;place-items:center;font-family:'Sora',sans-serif;font-weight:700;font-size:15px;flex:none}
.hz-pat .nm{font-weight:700;font-size:15px}
.hz-pat .mt{font-size:12.5px;color:var(--txt-soft)}

.find{padding:13px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.find:last-of-type{border-bottom:0}
.find .top{display:flex;align-items:center;gap:10px;font-size:14px;font-weight:600;margin-bottom:8px}
.find .top b{font-family:'Sora',sans-serif;font-weight:700;color:var(--txt);margin-left:auto}
.find .desc{font-size:12.5px;color:var(--txt-soft);margin-top:7px;line-height:1.5}
.bar{height:8px;border-radius:99px;background:rgba(110,160,255,.12);overflow:hidden}
.bar i{display:block;height:100%;border-radius:99px;width:0;transition:width 1.1s var(--ease-out)}
.bar.c1 i{background:linear-gradient(90deg,var(--blue),var(--cyan))}
.bar.c2 i{background:linear-gradient(90deg,#7B5CF6,#A98BFF)}
.bar.c3 i{background:linear-gradient(90deg,var(--orange),#FFC368)}
.bar.c4 i{background:linear-gradient(90deg,var(--green),#7BF0BE)}
.bar.c5 i{background:linear-gradient(90deg,var(--red),#FF98A6)}

.tag-conf{font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:6px;white-space:nowrap}
.tag-conf.hi{color:var(--green);background:rgba(61,220,151,.12)}
.tag-conf.mid{color:var(--orange);background:rgba(245,158,45,.12)}
.tag-conf.low{color:var(--txt-soft);background:rgba(110,160,255,.1)}

.hz-people-lbl{font-size:11px;color:var(--txt-soft);margin:10px 0 7px;font-weight:600;letter-spacing:.02em}
.hz-people{display:flex;flex-wrap:wrap;gap:8px}
.hz-person{display:flex;align-items:center;gap:7px;padding:4px 11px 4px 4px;border:1px solid var(--stroke);border-radius:99px;font-size:12px;font-weight:600;background:var(--panel-2)}
.hz-person .mini{width:24px;height:24px;border-radius:50%;background:rgba(46,123,246,.2);border:1px solid var(--stroke-strong);display:grid;place-items:center;font-size:9.5px;font-weight:700;color:var(--cyan)}
.hz-person small{color:var(--txt-soft);font-weight:500;margin-left:2px}

.hz-side h3{margin-bottom:14px}
.hz-stat{display:flex;align-items:center;justify-content:space-between;padding:11px 0;border-bottom:1px solid rgba(110,160,255,.08);font-size:13.5px}
.hz-stat:last-child{border-bottom:0}
.hz-stat b{font-family:'Sora',sans-serif;font-size:18px}
.hz-stat .crit{color:var(--red)}
.hz-stat .warn{color:var(--orange)}
.hz-stat .ok{color:var(--green)}
.hz-note{display:flex;align-items:center;gap:9px;margin-top:8px;padding:11px 14px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2);font-size:12px;color:var(--txt-soft)}
.hz-note svg{width:16px;height:16px;flex:none;color:var(--cyan)}

/* Gráfica de barras verticales: hallazgos detectados */
.hz-chart-card{flex:1;display:flex;flex-direction:column;min-height:260px}
.hz-chart-card h3{margin-bottom:14px}
.hz-bars{flex:1;display:flex;align-items:flex-end;justify-content:space-between;gap:10px;padding-top:6px}
.hz-bar{flex:1;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;gap:7px;min-width:0}
.hz-bar .col{width:100%;max-width:42px;border-radius:7px 7px 0 0;height:0;transition:height 1.1s var(--ease-out)}
.hz-bar .col.c1{background:linear-gradient(180deg,var(--cyan),var(--blue))}
.hz-bar .col.c2{background:linear-gradient(180deg,#A98BFF,#7B5CF6)}
.hz-bar .col.c3{background:linear-gradient(180deg,#FFC368,var(--orange))}
.hz-bar .col.c4{background:linear-gradient(180deg,#7BF0BE,var(--green))}
.hz-bar .col.c5{background:linear-gradient(180deg,#FF98A6,var(--red))}
.hz-bar .val{font-family:'Sora',sans-serif;font-weight:800;font-size:13px;order:-1}
.hz-bar .lbl{font-size:10.5px;color:var(--txt-soft);font-weight:600;text-align:center;line-height:1.2}
</style>
@endpush

@section('content')

  <div class="hz-top">
    <a class="hz-back" href="{{ route('ia-reportes') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
  </div>

  <div class="hz-grid">

    {{-- Lista completa de hallazgos --}}
    <article class="card rise d2">
      <div class="hz-pat">
        <span class="av">IA</span>
        <div>
          <div class="nm">Hallazgos por síntoma</div>
          <div class="mt">Desglose de pacientes que presentan cada hallazgo</div>
        </div>
      </div>

      <div class="find">
        <div class="top"><span>Gastritis crónica</span><span class="tag-conf hi">Alta confianza</span><b>68%</b></div>
        <div class="bar c1"><i data-w="68"></i></div>
        <p class="desc">Inflamación difusa de la mucosa gástrica compatible con proceso crónico, predominante en antro.</p>
        <div class="hz-people-lbl">PACIENTES CON ESTE HALLAZGO (4)</div>
        <div class="hz-people">
          <span class="hz-person"><span class="mini">MG</span>María González <small>92%</small></span>
          <span class="hz-person"><span class="mini">JL</span>Jorge López <small>85%</small></span>
          <span class="hz-person"><span class="mini">AR</span>Ana Ramírez <small>78%</small></span>
          <span class="hz-person"><span class="mini">CS</span>Carlos Sánchez <small>71%</small></span>
        </div>
      </div>
      <div class="find">
        <div class="top"><span>Reflujo gastroesofágico</span><span class="tag-conf mid">Media confianza</span><b>42%</b></div>
        <div class="bar c2"><i data-w="42"></i></div>
        <p class="desc">Signos de exposición ácida en la unión esofagogástrica con eritema distal.</p>
        <div class="hz-people-lbl">PACIENTES CON ESTE HALLAZGO (3)</div>
        <div class="hz-people">
          <span class="hz-person"><span class="mini">MG</span>María González <small>64%</small></span>
          <span class="hz-person"><span class="mini">PT</span>Pedro Torres <small>58%</small></span>
          <span class="hz-person"><span class="mini">LM</span>Laura Méndez <small>49%</small></span>
        </div>
      </div>
      <div class="find">
        <div class="top"><span>Úlcera péptica</span><span class="tag-conf mid">Media confianza</span><b>18%</b></div>
        <div class="bar c3"><i data-w="18"></i></div>
        <p class="desc">Lesión focal sugerente; se recomienda confirmación y descartar sangrado activo.</p>
        <div class="hz-people-lbl">PACIENTES CON ESTE HALLAZGO (2)</div>
        <div class="hz-people">
          <span class="hz-person"><span class="mini">JL</span>Jorge López <small>31%</small></span>
          <span class="hz-person"><span class="mini">CS</span>Carlos Sánchez <small>22%</small></span>
        </div>
      </div>
      <div class="find">
        <div class="top"><span>Pólipos</span><span class="tag-conf low">Baja confianza</span><b>11%</b></div>
        <div class="bar c4"><i data-w="11"></i></div>
        <p class="desc">Imágenes elevadas no concluyentes; valorar en seguimiento endoscópico.</p>
        <div class="hz-people-lbl">PACIENTES CON ESTE HALLAZGO (2)</div>
        <div class="hz-people">
          <span class="hz-person"><span class="mini">AR</span>Ana Ramírez <small>17%</small></span>
          <span class="hz-person"><span class="mini">PT</span>Pedro Torres <small>13%</small></span>
        </div>
      </div>
      <div class="find">
        <div class="top"><span>Esofagitis</span><span class="tag-conf low">Baja confianza</span><b>9%</b></div>
        <div class="bar c5"><i data-w="9"></i></div>
        <p class="desc">Cambios mínimos en la mucosa esofágica distal sin erosiones evidentes.</p>
        <div class="hz-people-lbl">PACIENTES CON ESTE HALLAZGO (1)</div>
        <div class="hz-people">
          <span class="hz-person"><span class="mini">LM</span>Laura Méndez <small>12%</small></span>
        </div>
      </div>
      <div class="find">
        <div class="top"><span>Metaplasia intestinal</span><span class="tag-conf low">Baja confianza</span><b>6%</b></div>
        <div class="bar c2"><i data-w="6"></i></div>
        <p class="desc">Probabilidad baja; relevante para vigilancia por riesgo de progresión.</p>
        <div class="hz-people-lbl">PACIENTES CON ESTE HALLAZGO (1)</div>
        <div class="hz-people">
          <span class="hz-person"><span class="mini">CS</span>Carlos Sánchez <small>8%</small></span>
        </div>
      </div>
    </article>

    {{-- Columna derecha: resumen + gráfica --}}
    <div class="hz-col">

      <article class="card hz-side rise d3">
        <h3>RESUMEN DE HALLAZGOS</h3>
        <div class="hz-stat"><span>Total de hallazgos</span><b>6</b></div>
        <div class="hz-stat"><span>Alta confianza</span><b class="ok">1</b></div>
        <div class="hz-stat"><span>Media confianza</span><b class="warn">2</b></div>
        <div class="hz-stat"><span>Baja confianza</span><b>3</b></div>
        <div class="hz-stat"><span>Hallazgo principal</span><b class="warn" style="font-size:14px">Gastritis</b></div>

        <div class="hz-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
          Los hallazgos son sugerencias generadas por IA. La decisión final corresponde al profesional de la salud.
        </div>
      </article>

      {{-- Gráfica de barras: hallazgos detectados --}}
      <article class="card hz-chart-card rise d4">
        <h3>HALLAZGOS DETECTADOS</h3>
        <div class="hz-bars">
          <div class="hz-bar"><div class="col c1" data-v="68" data-max="68"></div><span class="val">68%</span><span class="lbl">Gastritis</span></div>
          <div class="hz-bar"><div class="col c2" data-v="42" data-max="68"></div><span class="val">42%</span><span class="lbl">Reflujo</span></div>
          <div class="hz-bar"><div class="col c3" data-v="18" data-max="68"></div><span class="val">18%</span><span class="lbl">Úlcera</span></div>
          <div class="hz-bar"><div class="col c4" data-v="11" data-max="68"></div><span class="val">11%</span><span class="lbl">Pólipos</span></div>
          <div class="hz-bar"><div class="col c5" data-v="9" data-max="68"></div><span class="val">9%</span><span class="lbl">Esofagitis</span></div>
          <div class="hz-bar"><div class="col c2" data-v="6" data-max="68"></div><span class="val">6%</span><span class="lbl">Metaplasia</span></div>
        </div>
      </article>

    </div>

  </div>

@endsection

@push('scripts')
<script>
(function(){
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const bars = document.querySelectorAll('.bar i');
  const cols = document.querySelectorAll('.hz-bar .col');

  const draw = () => {
    bars.forEach(b => { b.style.width = b.dataset.w + '%'; });
    cols.forEach(c => {
      const v = +c.dataset.v, max = +c.dataset.max || 1;
      c.style.height = Math.round(v / max * 100) + '%';
    });
  };
  if (reduced) {
    bars.forEach(b => b.style.transition = 'none');
    cols.forEach(c => c.style.transition = 'none');
    draw();
    return;
  }
  setTimeout(draw, 250);
})();
</script>
@endpush
