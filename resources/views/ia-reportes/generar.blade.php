@extends('layouts.app')

@section('title', 'Genera reporte AI')
@section('active', 'ia-reportes')
@section('header-title', 'Genera reporte AI')
@section('header-sub')
  La AI analizará la información clínica y generará un reporte preliminar
@endsection

@push('styles')
<style>
/* ============ GENERAR REPORTE IA ============ */
.gen-top{display:flex;justify-content:flex-end;margin-bottom:16px}
.gen-back{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);background:var(--panel-2);font-weight:600;font-size:13.5px;transition:background-color .15s}
.gen-back svg{width:16px;height:16px}
@media (hover:hover){.gen-back:hover{background:rgba(110,160,255,.1)}}

.gen-body{display:grid;grid-template-columns:1fr 1.3fr;gap:18px;align-items:start}

.step{background:linear-gradient(180deg,var(--card),var(--panel-2));border:1px solid var(--stroke);border-radius:var(--r-lg);padding:15px 17px;margin-bottom:14px}
.step-head{display:flex;align-items:center;gap:10px;margin-bottom:14px}
.step-num{width:22px;height:22px;border-radius:50%;background:rgba(56,199,244,.15);border:1px solid var(--stroke-strong);color:var(--cyan);font-size:11.5px;font-weight:700;display:grid;place-items:center;flex:none}
.step-head h4{font-size:14px;font-weight:700;flex:1}
.step-check{color:var(--green);width:18px;height:18px}

.gen-field{display:block;margin-bottom:12px}
.gen-field:last-child{margin-bottom:0}
.gen-field label{display:block;font-size:11.5px;color:var(--txt-soft);margin-bottom:6px}
.gen-input,.gen-select,.gen-textarea{
  width:100%;padding:10px 12px;border-radius:10px;
  border:1px solid var(--stroke);background:var(--panel);color:var(--txt);
  font:inherit;font-size:13.5px;
}
.gen-input::placeholder{color:var(--off)}
.gen-search{position:relative}
.gen-search .gen-input{padding-right:38px}
.gen-search svg{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--txt-soft)}
.gen-row2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.gen-textarea{resize:vertical;min-height:86px;line-height:1.55}

.gen-pat{display:flex;gap:12px;align-items:center;margin-bottom:14px}
.gen-pat .av{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:grid;place-items:center;font-family:'Sora',sans-serif;font-weight:700;font-size:13px;flex:none}
.gen-pat .nm{font-weight:700;font-size:14px}
.gen-pat .mt{font-size:12px;color:var(--txt-soft)}
.gen-pat-grid{display:grid;grid-template-columns:1fr 1fr;gap:11px 18px}
.gen-pat-grid .k{color:var(--txt-soft);font-size:11px;margin-bottom:2px}
.gen-pat-grid .v{font-weight:600;font-size:12.5px}

.gen-ev-head{display:flex;align-items:center;justify-content:space-between;gap:10px}
.gen-ev-head .cnt{font-size:11.5px;color:var(--txt-soft);margin-top:2px}
.gen-ev-tools{display:flex;gap:6px}
.gen-ev-tools button{width:28px;height:28px;border:1px solid var(--stroke);border-radius:8px;display:grid;place-items:center;color:var(--txt-soft)}
.gen-ev-tools svg{width:15px;height:15px}
.gen-thumbs{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:12px}
.gen-thumb{aspect-ratio:4/3;border-radius:10px;border:1px solid var(--stroke)}
.gen-thumb.sel{border-color:var(--cyan);box-shadow:0 0 0 1.5px var(--cyan)}
.gen-dots{display:flex;justify-content:center;gap:6px;margin-top:11px}
.gen-dots i{width:7px;height:7px;border-radius:50%;background:var(--off)}
.gen-dots i.on{background:var(--cyan);width:18px;border-radius:99px}

.gen-checks{display:grid;grid-template-columns:1fr 1fr;gap:11px 16px}
.gen-check{display:flex;align-items:center;gap:9px;font-size:13px;cursor:pointer}
.gen-check input{appearance:none;-webkit-appearance:none;width:18px;height:18px;border-radius:5px;border:1px solid var(--stroke-strong);background:var(--panel);flex:none;position:relative;cursor:pointer;transition:.15s}
.gen-check input:checked{background:var(--blue);border-color:var(--blue)}
.gen-check input:checked::after{content:"";position:absolute;left:5px;top:2px;width:5px;height:9px;border:solid #fff;border-width:0 2px 2px 0;transform:rotate(45deg)}

.prev-card{background:linear-gradient(180deg,var(--card),var(--panel-2));border:1px solid var(--stroke);border-radius:var(--r-lg);padding:15px 17px;margin-bottom:14px}
.prev-title{font-size:13px;font-weight:600;letter-spacing:.04em;color:var(--txt-soft);margin-bottom:13px}
.prev-top{display:grid;grid-template-columns:1.6fr 1fr;gap:14px;align-items:stretch}
.prev-diag{display:flex;gap:14px;align-items:center}
.prev-img{width:120px;height:92px;flex:none;border-radius:10px;border:1px solid var(--stroke);background:radial-gradient(circle at 45% 38%,#c0565a,#5c1d22)}
.prev-diag .dx small{font-size:11.5px;color:var(--txt-soft)}
.prev-diag .dx h3{font-family:'Sora',sans-serif;font-size:18px;font-weight:700;margin:4px 0 12px;letter-spacing:-.01em}
.prev-conf .lbl{font-size:11.5px;color:var(--txt-soft);margin-bottom:6px}
.prev-bar{height:7px;border-radius:99px;background:rgba(110,160,255,.14);overflow:hidden}
.prev-bar i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--blue),var(--cyan))}
.prev-conf .pc{font-size:12.5px;font-weight:700;text-align:right;margin-top:5px}
.prev-risk{border-left:1px solid var(--stroke);padding-left:14px;text-align:center;display:flex;flex-direction:column;justify-content:center}
.prev-risk .rt{font-size:11.5px;color:var(--txt-soft);margin-bottom:8px}
.prev-risk .donut{width:64px;height:64px;margin:0 auto 8px;border-radius:50%;background:conic-gradient(var(--orange) 0 62%,rgba(245,158,45,.18) 62% 100%);-webkit-mask:radial-gradient(circle,transparent 56%,#000 57%);mask:radial-gradient(circle,transparent 56%,#000 57%)}
.prev-risk .lv{font-family:'Sora',sans-serif;font-weight:800;font-size:17px;color:var(--orange)}
.prev-risk small{display:block;font-size:10.5px;color:var(--txt-soft);margin-top:4px;line-height:1.4}

.prev-2col{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.prev-2col h5{font-size:13px;font-weight:700;margin-bottom:12px}
.prev-list{list-style:none;display:flex;flex-direction:column;gap:11px}
.prev-list li{display:flex;align-items:center;gap:9px;font-size:12.5px}
.prev-list .ck{color:var(--green);flex:none;width:16px;height:16px}
.prev-list .rc{color:#A98BFF;flex:none;width:16px;height:16px}
.tag-conf{margin-left:auto;font-size:10px;font-weight:700;padding:3px 9px;border-radius:6px;white-space:nowrap}
.tag-conf.hi{color:var(--green);background:rgba(61,220,151,.12)}
.tag-conf.mid{color:var(--orange);background:rgba(245,158,45,.12)}
.prev-sum p{font-size:12.5px;line-height:1.65;color:var(--txt-soft)}
.prev-note{display:flex;align-items:center;gap:9px;margin-top:14px;padding-top:13px;border-top:1px solid var(--stroke);font-size:11.5px;color:var(--txt-soft)}
.prev-note svg{width:16px;height:16px;flex:none;color:var(--cyan)}

.gen-foot{display:flex;gap:12px;margin-top:4px}
.gen-foot button{flex:1;display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:13px;border-radius:var(--r-md);font-weight:700;font-size:14px;transition:filter .15s,transform .16s var(--ease-out)}
.gen-foot button svg{width:17px;height:17px}
.gen-foot .btn-primary{background:linear-gradient(135deg,#1668D9,var(--blue));color:#fff;box-shadow:0 10px 26px -10px rgba(46,123,246,.7)}
.gen-foot .btn-out{border:1px solid var(--stroke-strong);background:var(--panel-2)}
.gen-foot button:active{transform:scale(.98)}
@media (hover:hover){.gen-foot .btn-primary:hover{filter:brightness(1.1)}}

@media (max-width:980px){
  .gen-body{grid-template-columns:1fr}
  .prev-top{grid-template-columns:1fr}
  .prev-risk{border-left:0;border-top:1px solid var(--stroke);padding-left:0;padding-top:12px}
  .prev-2col{grid-template-columns:1fr}
}
</style>
@endpush

@section('content')

  <div class="gen-top">
    <a class="gen-back" href="{{ route('ia-reportes') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
  </div>

  <div class="gen-body">

    {{-- Columna izquierda: entrada --}}
    <div class="gen-col-left">

      {{-- Paso 1: Selección del paciente --}}
      <div class="step rise d2">
        <div class="step-head">
          <span class="step-num">1</span>
          <h4>Selección del paciente</h4>
          <svg class="step-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11.5 14.5 16 9.5"/></svg>
        </div>
        <div class="gen-field gen-search">
          <input class="gen-input" type="text" placeholder="Buscar paciente...">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <div class="gen-pat">
          <span class="av">MG</span>
          <div>
            <div class="nm">María Gonzales</div>
            <div class="mt">Femenino · 45 años</div>
          </div>
        </div>
        <div class="gen-pat-grid">
          <div><div class="k">Expediente</div><div class="v">EXP-2024-0001</div></div>
          <div><div class="k">NSS</div><div class="v">1234 5678 9101 1122</div></div>
          <div><div class="k">Médico responsable</div><div class="v">Dr. Victor</div></div>
          <div><div class="k">Fecha de nacimiento</div><div class="v">12/04/1979</div></div>
        </div>
      </div>

      {{-- Paso 2: Información del estudio --}}
      <div class="step rise d3">
        <div class="step-head">
          <span class="step-num">2</span>
          <h4>Información del estudio</h4>
          <svg class="step-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11.5 14.5 16 9.5"/></svg>
        </div>
        <div class="gen-row2">
          <div class="gen-field">
            <label>Tipo de estudio</label>
            <select class="gen-select">
              <option>Endoscopia alta</option>
              <option>Endoscopia baja</option>
              <option>Colonoscopia</option>
            </select>
          </div>
          <div class="gen-field">
            <label>Fecha del estudio</label>
            <input class="gen-input" type="date" value="2025-05-08">
          </div>
        </div>
      </div>

      {{-- Paso 3: Observaciones clínicas --}}
      <div class="step rise d4">
        <div class="step-head">
          <span class="step-num">3</span>
          <h4>Observaciones clínicas</h4>
          <svg class="step-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11.5 14.5 16 9.5"/></svg>
        </div>
        <textarea class="gen-textarea">· Inflamación moderada del antro gástrico
· Presencia de erosiones superficiales
· Reflujo gastroesofágico leve.</textarea>
      </div>

      {{-- Paso 4: Cargar evidencia --}}
      <div class="step rise d5">
        <div class="step-head">
          <span class="step-num">4</span>
          <div style="flex:1">
            <div class="gen-ev-head">
              <div>
                <h4 style="margin-bottom:2px">Cargar evidencia</h4>
                <div class="cnt">6 imágenes asociadas al estudio</div>
              </div>
              <div class="gen-ev-tools">
                <button type="button" aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                <button type="button" aria-label="Expandir"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg></button>
              </div>
            </div>
          </div>
        </div>
        <div class="gen-thumbs">
          <div class="gen-thumb sel" style="background:radial-gradient(circle at 42% 38%,#c25b58,#6c2026)"></div>
          <div class="gen-thumb" style="background:radial-gradient(circle at 55% 45%,#b65a66,#6e2230)"></div>
          <div class="gen-thumb" style="background:linear-gradient(135deg,#d9905a,#8a4a2a)"></div>
          <div class="gen-thumb" style="background:radial-gradient(circle at 48% 42%,#c46a7a,#7a2f44)"></div>
          <div class="gen-thumb" style="background:radial-gradient(circle at 45% 55%,#b04e4e,#5e1f24)"></div>
          <div class="gen-thumb" style="background:radial-gradient(circle at 55% 45%,#cf7b5e,#80392a)"></div>
        </div>
        <div class="gen-dots"><i class="on"></i><i></i><i></i><i></i></div>
      </div>

      {{-- Paso 5: Configuración AI --}}
      <div class="step rise d6">
        <div class="step-head">
          <span class="step-num">5</span>
          <h4>Configuración AI</h4>
          <svg class="step-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11.5 14.5 16 9.5"/></svg>
        </div>
        <div class="gen-checks">
          <label class="gen-check"><input type="checkbox" checked> Analizar imágenes</label>
          <label class="gen-check"><input type="checkbox" checked> Generar recomendaciones</label>
          <label class="gen-check"><input type="checkbox" checked> Comparar estudios previos</label>
          <label class="gen-check"><input type="checkbox" checked> Sugerir biopsias</label>
          <label class="gen-check"><input type="checkbox" checked> Detectar patologías</label>
          <label class="gen-check"><input type="checkbox" checked> Análisis de riesgo</label>
        </div>
      </div>

    </div>

    {{-- Columna derecha: vista previa --}}
    <div class="gen-col-right">

      <div class="prev-card rise d3">
        <div class="prev-title">Vista previa del reporte generado por AI</div>
        <div class="prev-top">
          <div class="prev-diag">
            <div class="prev-img"></div>
            <div class="dx">
              <small>Diagnóstico preliminar</small>
              <h3>Gastritis crónica moderada</h3>
              <div class="prev-conf">
                <div class="lbl">Nivel de confianza de la AI</div>
                <div class="prev-bar"><i style="width:96%"></i></div>
                <div class="pc">96%</div>
              </div>
            </div>
          </div>
          <div class="prev-risk">
            <div class="rt">Nivel de riesgo</div>
            <div class="donut"></div>
            <div class="lv">Moderado</div>
            <small>Basado en hallazgos y antecedentes</small>
          </div>
        </div>
      </div>

      <div class="prev-card rise d4">
        <div class="prev-2col">
          <div>
            <h5>Hallazgos detectados por AI</h5>
            <ul class="prev-list">
              <li><svg class="ck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Gastritis crónica <span class="tag-conf hi">Alta confianza</span></li>
              <li><svg class="ck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Inflamación antral <span class="tag-conf hi">Alta confianza</span></li>
              <li><svg class="ck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Reflujo gastroesofágico leve <span class="tag-conf mid">Media confianza</span></li>
              <li><svg class="ck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Erosiones superficiales <span class="tag-conf mid">Media confianza</span></li>
            </ul>
          </div>
          <div>
            <h5>Recomendaciones sugeridas por AI</h5>
            <ul class="prev-list">
              <li><svg class="rc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg> Tomar biopsia del antro gástrico</li>
              <li><svg class="rc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg> Seguimiento en 3 meses</li>
              <li><svg class="rc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg> Revisar antecedentes gástricos del paciente</li>
              <li><svg class="rc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg> Considerar prueba para H. pylori</li>
              <li><svg class="rc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg> Iniciar tratamiento con IBP por 8 semanas</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="prev-card prev-sum rise d5">
        <div class="prev-title">Resumen generado por AI</div>
        <p>La evaluación endoscópica muestra signos compatibles con gastritis crónica moderada, con inflamación del antro gástrico y presencia de erosiones superficiales. Se recomienda confirmación histopatológica y prueba para Helicobacter pylori. Se sugiere seguimiento clínico y tratamiento con inhibidores de bomba de protones.</p>
        <div class="prev-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
          Este reporte es una sugerencia generada por AI. La decisión final siempre debe ser del profesional de la salud.
        </div>
      </div>

      <div class="gen-foot rise d6">
        <button class="btn-primary" type="button" onclick="window.location.href='{{ route('ia-reportes.editar', ['generating' => 1]) }}'">
          <x-hugeicons-ai-file width="17" height="17" />
          Generar Reporte IA
        </button>
        <button class="btn-out" type="button">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          Guardar borrador
        </button>
        <button class="btn-out" type="button">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Exportar PDF
        </button>
      </div>

    </div>
  </div>

@endsection
