@extends('layouts.app')

@php $gen = request()->boolean('generating'); @endphp

@section('title', $gen ? 'IA Generando Informe' : 'Crear Nuevo Informe')
@section('active', 'ia-reportes')
@section('header-title', $gen ? 'IA Generando Informe' : 'Crear Nuevo Informe')
@section('header-sub')
  {{ $gen ? 'La IA está redactando y estructurando el informe' : 'Edita y personaliza el informe del estudio' }}
@endsection

@push('styles')
<style>
/* ============ EDITOR DE INFORME ============ */
.ed-actions{display:flex;justify-content:flex-end;gap:10px;margin-bottom:14px;flex-wrap:wrap}
.ed-btn{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:var(--r-md);font-weight:600;font-size:13.5px;border:1px solid var(--stroke-strong);background:var(--panel-2);transition:background-color .15s}
.ed-btn svg{width:16px;height:16px}
@media (hover:hover){.ed-btn:hover{background:rgba(110,160,255,.1)}}
.ed-btn.primary{border:0;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;padding-right:10px}
.ed-btn.primary .div{width:1px;height:18px;background:rgba(255,255,255,.35);margin:0 2px}

.ed-meta{display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;margin-bottom:14px}
.ed-meta .f{display:flex;flex-direction:column;gap:6px}
.ed-meta .f.grow{flex:1;min-width:220px}
.ed-meta label{font-size:11.5px;color:var(--txt-soft)}
.ed-ctrl{display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:10px;border:1px solid var(--stroke);background:var(--panel);font-size:13.5px;color:var(--txt);min-height:40px}
.ed-ctrl svg{width:15px;height:15px;color:var(--txt-soft);flex:none}
select.ed-ctrl{appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2390a0c0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");background-repeat:no-repeat;background-position:right 12px center;padding-right:34px}
.ed-status{padding:9px 14px;border-radius:10px;font-size:12.5px;font-weight:700;color:var(--orange);background:rgba(245,158,45,.14);border:1px solid rgba(245,158,45,.3);min-height:40px;display:flex;align-items:center}

.ed-toolbar{display:flex;align-items:center;gap:4px;flex-wrap:wrap;padding:8px 12px;border:1px solid var(--stroke);border-radius:var(--r-md);background:var(--panel-2);margin-bottom:16px}
.ed-toolbar .sel{display:flex;align-items:center;gap:6px;padding:6px 10px;border-radius:8px;border:1px solid var(--stroke);background:var(--panel);font-size:12.5px;color:var(--txt-soft);margin-right:4px}
.ed-toolbar .sel svg{width:13px;height:13px}
.ed-toolbar .sep{width:1px;height:22px;background:var(--stroke);margin:0 4px}
.ed-tb{width:30px;height:30px;display:grid;place-items:center;border-radius:7px;color:var(--txt-soft);font-size:13px;font-weight:700;transition:color .15s,background-color .15s}
@media (hover:hover){.ed-tb:hover{color:var(--cyan);background:rgba(56,199,244,.1)}}
.ed-tb svg{width:16px;height:16px}

.ed-body{display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start}
@media (max-width:1100px){.ed-body{grid-template-columns:1fr}}
.ed-main{display:flex;flex-direction:column;gap:16px;min-width:0}

/* Banner de generación IA */
.gen-banner{display:flex;align-items:center;gap:14px;padding:14px 18px;border-radius:var(--r-lg);border:1px solid rgba(56,199,244,.3);background:linear-gradient(180deg,rgba(56,199,244,.08),rgba(46,123,246,.05))}
.gb-orb{width:42px;height:42px;flex:none;border-radius:12px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.12);border:1px solid rgba(56,199,244,.3);animation:gbPulse 1.8s ease-in-out infinite}
.gb-orb svg{width:22px;height:22px}
@keyframes gbPulse{0%,100%{box-shadow:0 0 0 0 rgba(56,199,244,.35)}50%{box-shadow:0 0 0 7px rgba(56,199,244,0)}}
.gb-text{flex:1;min-width:0}
.gb-text h3{font-size:14px;font-weight:700;color:var(--cyan)}
.gb-text p{font-size:12px;color:var(--txt-soft);margin-top:2px}
.gb-shell{flex:1.2;max-width:360px;height:24px;border-radius:12px;border:1px solid var(--stroke-strong);background:var(--panel);overflow:hidden;position:relative}
.gb-shell canvas{position:absolute;top:0;left:0;width:100%;height:100%}
.gb-pct{font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:var(--cyan);min-width:46px;text-align:right}

/* Estado por sección */
.sec-st{display:none;align-items:center;gap:5px;margin-left:9px;font-size:10px;font-weight:700;padding:2px 8px;border-radius:6px;vertical-align:middle;text-transform:none;letter-spacing:0}
.is-generating .sec-st{display:inline-flex}
.sec-st.wait{color:var(--txt-soft);background:rgba(110,160,255,.1)}
.sec-st.gen{color:var(--cyan);background:rgba(56,199,244,.14)}
.sec-st.done{color:var(--green);background:rgba(61,220,151,.14)}
.sec-st svg{width:12px;height:12px}
.sec-st.gen svg{animation:gbSpin .8s linear infinite}
@keyframes gbSpin{to{transform:rotate(360deg)}}
.ed-doc .caret{display:inline-block;width:7px;height:13px;background:var(--cyan);margin-left:1px;vertical-align:-2px;animation:chBlink .8s steps(1) infinite}

/* Documento */
.ed-doc{padding:30px 34px;line-height:1.55}
.ed-doc .doc-h{text-align:center;margin-bottom:22px}
.ed-doc .doc-h h2{font-family:'Sora',sans-serif;font-size:21px;font-weight:800;letter-spacing:.01em}
.ed-doc .doc-h p{font-size:12.5px;color:var(--txt-soft);letter-spacing:.06em;margin-top:4px}
.ed-doc .doc-meta{display:grid;grid-template-columns:130px 1fr;gap:5px 16px;font-size:13px;margin-bottom:20px}
.ed-doc .doc-meta .k{color:var(--txt-soft)}
.ed-doc h4{font-size:13px;font-weight:700;letter-spacing:.04em;margin:18px 0 6px}
.ed-doc p,.ed-doc ul{font-size:13px}
.ed-doc ul{list-style:disc;padding-left:20px;display:flex;flex-direction:column;gap:4px;margin-top:4px}
.ed-annex{display:flex;gap:12px;margin-top:10px;flex-wrap:wrap}
.ed-annex .ph{width:140px;height:96px;border-radius:10px;border:1px solid var(--stroke)}
.ed-annex .ph:nth-child(1){background:radial-gradient(circle at 45% 40%,#c0565a,#5c1d22)}
.ed-annex .ph:nth-child(2){background:radial-gradient(circle at 50% 45%,#c97a52,#5c2d1d)}
.ed-annex .ph:nth-child(3){background:radial-gradient(circle at 50% 50%,#2a2f45,#0b0e1a)}

/* Panel lateral */
.ed-side{display:flex;flex-direction:column;gap:16px}
.ed-panel{padding:15px 16px}
.ed-panel h3{font-size:14px;font-weight:700;margin-bottom:3px}
.ed-panel .ph-sub{font-size:11.5px;color:var(--txt-soft);margin-bottom:12px}
/* Chat IA */
.ed-chat{display:flex;flex-direction:column;padding:0;overflow:hidden}
.chat-head{display:flex;align-items:center;gap:11px;padding:14px 16px;border-bottom:1px solid var(--stroke)}
.chat-orb{width:38px;height:38px;flex:none;border-radius:11px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.12);border:1px solid rgba(56,199,244,.3)}
.chat-orb svg{width:19px;height:19px}
.chat-head h3{font-size:14px;font-weight:700}
.chat-on{font-size:11px;color:var(--green);display:flex;align-items:center;gap:5px}
.chat-on::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 0 0 rgba(61,220,151,.5);animation:chOn 1.6s ease-in-out infinite}
@keyframes chOn{0%,100%{box-shadow:0 0 0 0 rgba(61,220,151,.5)}50%{box-shadow:0 0 0 5px rgba(61,220,151,0)}}
.chat-msgs{display:flex;flex-direction:column;gap:10px;padding:16px;height:340px;overflow-y:auto}
.chat-msg{max-width:88%;padding:10px 13px;border-radius:14px;font-size:12.8px;line-height:1.5;white-space:pre-wrap;word-wrap:break-word}
.chat-msg.ai{align-self:flex-start;background:var(--panel-2);border:1px solid var(--stroke);border-bottom-left-radius:5px}
.chat-msg.me{align-self:flex-end;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;border-bottom-right-radius:5px}
.chat-msg .caret{display:inline-block;width:7px;height:14px;background:var(--cyan);margin-left:2px;vertical-align:-2px;animation:chBlink .8s steps(1) infinite}
@keyframes chBlink{50%{opacity:0}}
.chat-typing{display:inline-flex;gap:4px;align-items:center}
.chat-typing i{width:6px;height:6px;border-radius:50%;background:var(--txt-soft);animation:chDot 1.2s ease-in-out infinite}
.chat-typing i:nth-child(2){animation-delay:.2s}
.chat-typing i:nth-child(3){animation-delay:.4s}
@keyframes chDot{0%,60%,100%{opacity:.3;transform:translateY(0)}30%{opacity:1;transform:translateY(-3px)}}
.chat-chips{display:flex;flex-wrap:wrap;gap:7px;padding:0 16px 12px}
.chat-chip{font-size:11.5px;padding:6px 11px;border-radius:99px;border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--txt-soft);transition:.15s}
@media (hover:hover){.chat-chip:hover{color:var(--cyan);border-color:rgba(56,199,244,.4);background:rgba(56,199,244,.08)}}
.chat-input{display:flex;gap:8px;padding:12px 14px;border-top:1px solid var(--stroke)}
.chat-input input{flex:1;min-width:0;padding:10px 13px;border-radius:99px;border:1px solid var(--stroke);background:var(--panel);color:var(--txt);font:inherit;font-size:13px}
.chat-input input::placeholder{color:var(--off)}
.chat-input button{width:40px;height:40px;flex:none;border-radius:50%;border:0;display:grid;place-items:center;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;transition:filter .15s,transform .15s}
.chat-input button svg{width:17px;height:17px}
@media (hover:hover){.chat-input button:hover{filter:brightness(1.1)}}
.chat-input button:active{transform:scale(.94)}
.ed-cta{display:block;width:100%;text-align:center;margin-top:12px;padding:9px;border-radius:9px;border:1px solid var(--stroke-strong);background:var(--panel-2);font-size:12.5px;font-weight:600;color:var(--cyan)}
.ed-thumbs{display:grid;grid-template-columns:repeat(4,1fr);gap:8px}
.ed-thumb{aspect-ratio:1;border-radius:9px;border:1px solid var(--stroke)}
.ed-thumb:nth-child(1){background:radial-gradient(circle at 45% 40%,#c0565a,#5c1d22)}
.ed-thumb:nth-child(2){background:radial-gradient(circle at 50% 45%,#c97a52,#5c2d1d)}
.ed-thumb:nth-child(3){background:radial-gradient(circle at 50% 50%,#2a2f45,#0b0e1a)}
.ed-thumb.more{display:grid;place-items:center;background:var(--panel-2);font-family:'Sora',sans-serif;font-weight:700;font-size:14px;color:var(--txt-soft)}
</style>
@endpush

@section('content')

  {{-- Acciones --}}
  <div class="ed-actions">
    <a class="ed-btn" href="{{ route('ia-reportes.todos') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Salir
    </a>
    <button class="ed-btn" type="button">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Vista Previa
    </button>
    <button class="ed-btn primary" type="button">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      Guardar Informe
      <span class="div"></span>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </button>
  </div>

  {{-- Meta --}}
  <div class="ed-meta">
    <div class="f grow">
      <label>Tipo de estudio</label>
      <select class="ed-ctrl">
        <option>Endoscopia Diagnóstica</option>
        <option>Colonoscopia</option>
        <option>Gastroscopia</option>
        <option>CPRE</option>
        <option>Enteroscopia</option>
      </select>
    </div>
    <div class="f">
      <label>Fecha de Informe</label>
      <div class="ed-ctrl">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        05/11/2025
      </div>
    </div>
    <div class="f">
      <label>&nbsp;</label>
      <div class="ed-ctrl">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        10:30 AM
      </div>
    </div>
    <div class="f">
      <label>Estado</label>
      <span class="ed-status">En borrador</span>
    </div>
  </div>

  {{-- Toolbar --}}
  <div class="ed-toolbar">
    <span class="sel">Párrafo
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </span>
    <span class="sep"></span>
    <button class="ed-tb" aria-label="Deshacer"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg></button>
    <button class="ed-tb" aria-label="Rehacer"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7v6h-6"/><path d="M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3L21 13"/></svg></button>
    <span class="sep"></span>
    <button class="ed-tb"><b>B</b></button>
    <button class="ed-tb"><i>I</i></button>
    <button class="ed-tb"><u>U</u></button>
    <button class="ed-tb"><s>S</s></button>
    <button class="ed-tb">X<sub>2</sub></button>
    <button class="ed-tb">X<sup>2</sup></button>
    <span class="sep"></span>
    <button class="ed-tb" aria-label="Alinear izquierda"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg></button>
    <button class="ed-tb" aria-label="Centrar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="10" x2="6" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="18" y1="18" x2="6" y2="18"/></svg></button>
    <button class="ed-tb" aria-label="Justificar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="3" y2="18"/></svg></button>
    <span class="sep"></span>
    <button class="ed-tb" aria-label="Lista"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></button>
    <button class="ed-tb" aria-label="Lista numerada"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg></button>
    <button class="ed-tb" aria-label="Adjuntar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg></button>
  </div>

  {{-- Cuerpo --}}
  <div class="ed-body">

   <div class="ed-main">

    @if($gen)
    {{-- Banner de generación IA --}}
    <div class="gen-banner" id="genBanner">
      <div class="gb-orb">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
      </div>
      <div class="gb-text">
        <h3 id="gbTitle">La IA está generando el informe...</h3>
        <p id="gbSub">Analizando hallazgos, redactando contenido y estructurando el informe</p>
      </div>
      <div class="gb-shell"><canvas id="gbCanvas"></canvas></div>
      <div class="gb-pct" id="gbPct">0%</div>
    </div>
    @endif

    {{-- Documento --}}
    <article class="card ed-doc rise d2 {{ $gen ? 'is-generating' : '' }}">
      <div class="doc-h">
        <h2>INFORME DE ENDOSCOPIA</h2>
        <p>ENDOSCOPIA DIGESTIVA ALTA</p>
      </div>

      <div class="doc-meta">
        <span class="k">Paciente:</span><span>María González</span>
        <span class="k">Fecha de nacimiento:</span><span>12/06/1985</span>
        <span class="k">Edad:</span><span>38 años</span>
        <span class="k">Médico solicitante:</span><span>Dr. Víctor</span>
        <span class="k">Fecha del estudio:</span><span>08/05/2024 10:30 AM</span>
        <span class="k">Endoscopista:</span><span>Dr. Víctor</span>
        <span class="k">Tipo de estudio:</span><span>Endoscopia diagnóstica</span>
      </div>

      <h4>INDICACIÓN @if($gen)<span class="sec-st wait">Pendiente</span>@endif</h4>
      <p>Dolor abdominal epigástrico, náusea y pirosis.</p>

      <h4>SEDACIÓN @if($gen)<span class="sec-st wait">Pendiente</span>@endif</h4>
      <p>Sedación consciente con midazolam y fentanilo.</p>

      <h4>HALLAZGOS @if($gen)<span class="sec-st wait">Pendiente</span>@endif</h4>
      <ul>
        <li>Esófago: Se observa mucosa de aspecto normal, línea Z a 38 cm de arcada dentaria.</li>
        <li>Estómago: Mucosa eritematosa en antro gástrico. Pliegues gástricos conservados.</li>
        <li>Píloro: Permeable.</li>
        <li>Duodeno: Bulbo y segunda porción con mucosa de aspecto normal.</li>
      </ul>

      <h4>IMPRESIÓN DIAGNÓSTICA @if($gen)<span class="sec-st wait">Pendiente</span>@endif</h4>
      <p>Gastritis antral leve.</p>

      <h4>PLAN Y RECOMENDACIONES @if($gen)<span class="sec-st wait">Pendiente</span>@endif</h4>
      <ul>
        <li>Omeprazol 20 mg cada 24 horas por 8 semanas.</li>
        <li>Dieta y medidas generales.</li>
      </ul>

      <h4>OBSERVACIONES @if($gen)<span class="sec-st wait">Pendiente</span>@endif</h4>
      <p>Se toman biopsias de antro para estudio histopatológico y detección de Helicobacter pylori.</p>

      <h4>ANEXOS @if($gen)<span class="sec-st wait">Pendiente</span>@endif</h4>
      <div class="ed-annex">
        <span class="ph"></span><span class="ph"></span><span class="ph"></span>
      </div>
    </article>

   </div>

    {{-- Panel lateral --}}
    <aside class="ed-side">

      <article class="card ed-chat rise d3">
        <div class="chat-head">
          <span class="chat-orb">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
          </span>
          <div>
            <h3>ENCLAII</h3>
            <span class="chat-on">En línea</span>
          </div>
        </div>

        <div class="chat-msgs" id="chatMsgs"></div>

        <div class="chat-chips" id="chatChips">
          <button type="button" class="chat-chip">Resume los hallazgos</button>
          <button type="button" class="chat-chip">Sugiere recomendaciones</button>
          <button type="button" class="chat-chip">Insertar datos del paciente</button>
          <button type="button" class="chat-chip">Cambiar a Colonoscopia</button>
        </div>

        <form class="chat-input" id="chatForm">
          <input type="text" id="chatText" placeholder="Escribe un mensaje a la IA..." autocomplete="off">
          <button type="submit" aria-label="Enviar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
        </form>
      </article>

      <article class="card ed-panel rise d5">
        <h3>Anexos del estudio</h3>
        <div class="ph-sub">Imágenes y videos asociados</div>
        <div class="ed-thumbs">
          <span class="ed-thumb"></span>
          <span class="ed-thumb"></span>
          <span class="ed-thumb"></span>
          <span class="ed-thumb more">+12</span>
        </div>
        <a class="ed-cta" href="#">Ver galería completa</a>
      </article>

    </aside>

  </div>

@endsection

@if($gen)
@push('scripts')
<script>
(function(){
  const pct   = document.getElementById('gbPct');
  const title = document.getElementById('gbTitle');
  const sub   = document.getElementById('gbSub');
  const banner = document.getElementById('genBanner');
  const canvas = document.getElementById('gbCanvas');
  const chips = Array.from(document.querySelectorAll('.ed-doc .sec-st'));
  if (!canvas || !chips.length) return;

  /* ===== Barra de agua con canvas (olas + burbujas) ===== */
  const ctx = canvas.getContext('2d');
  const shell = canvas.parentElement;
  let W, H, level = 0, target = 0, hue = 198, tWave = 0;
  const maxBubbles = 14;
  const resize = () => {
    const r = shell.getBoundingClientRect();
    W = canvas.width  = r.width  * devicePixelRatio;
    H = canvas.height = r.height * devicePixelRatio;
    canvas.style.width = r.width + 'px';
    canvas.style.height = r.height + 'px';
  };
  resize();
  window.addEventListener('resize', resize);

  class Bubble {
    constructor(){ this.reset(true); }
    reset(born){
      const fillX = (level / 100) * W;
      this.x = born ? Math.random() * fillX : -10;
      this.y = Math.random() * H;
      this.r = (1.5 + Math.random() * 3.5) * devicePixelRatio;
      this.vx = (0.4 + Math.random() * 0.8) * devicePixelRatio;
      this.vy = (Math.random() - 0.5) * 0.3 * devicePixelRatio;
      this.alpha = 0.15 + Math.random() * 0.25;
      this.wobble = Math.random() * Math.PI * 2;
      this.wobbleSpeed = 0.02 + Math.random() * 0.03;
    }
    update(){
      this.wobble += this.wobbleSpeed;
      this.x += this.vx;
      this.y += this.vy + Math.sin(this.wobble) * 0.4 * devicePixelRatio;
      const edgeX = (level / 100) * W;
      if (this.x > edgeX + this.r || this.y < 0 || this.y > H) this.reset(false);
    }
    draw(){
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
      ctx.strokeStyle = `hsla(${hue},70%,90%,${this.alpha + 0.1})`;
      ctx.lineWidth = devicePixelRatio;
      ctx.stroke();
      ctx.fillStyle = `hsla(${hue},60%,95%,${this.alpha * 0.4})`;
      ctx.fill();
      ctx.beginPath();
      ctx.arc(this.x - this.r * 0.3, this.y - this.r * 0.3, this.r * 0.25, 0, Math.PI * 2);
      ctx.fillStyle = 'hsla(0,0%,100%,0.5)';
      ctx.fill();
    }
  }
  const bubbles = Array.from({length: maxBubbles}, () => new Bubble());

  const wavePath = (offsetT, amp, phase) => {
    const edgeX = (level / 100) * W;
    ctx.beginPath();
    ctx.moveTo(0, 0);
    ctx.lineTo(edgeX, 0);
    const segs = 24;
    for (let i = 0; i <= segs; i++) {
      const py = (i / segs) * H;
      const px = edgeX
        + Math.sin((py / H) * Math.PI * 4 + offsetT + phase) * amp
        + Math.sin((py / H) * Math.PI * 7 + offsetT * 1.4 + phase * 0.7) * amp * 0.35;
      ctx.lineTo(px, py);
    }
    ctx.lineTo(edgeX, H);
    ctx.lineTo(0, H);
    ctx.closePath();
  };

  const drawWater = () => {
    tWave += 0.022;
    level += (target - level) * 0.08;
    ctx.clearRect(0, 0, W, H);
    if (level > 0.4) {
      const amp = (3 + (100 - level) * 0.05) * devicePixelRatio;
      ctx.save(); wavePath(tWave * 1.1, amp, 0);       ctx.fillStyle = `hsla(${hue},70%,52%,0.55)`; ctx.fill(); ctx.restore();
      ctx.save(); wavePath(-tWave * 0.8, amp * 0.7, 1.8); ctx.fillStyle = `hsla(${hue},65%,58%,0.5)`;  ctx.fill(); ctx.restore();
      ctx.save(); wavePath(tWave * 0.5, amp * 0.5, 3.5);  ctx.fillStyle = `hsla(${hue},60%,62%,0.85)`; ctx.fill(); ctx.restore();
      ctx.save(); wavePath(-tWave * 1.3, amp * 0.3, 5.2); ctx.fillStyle = `hsla(${hue},55%,65%,1)`;    ctx.fill(); ctx.restore();
      const edgeX = (level / 100) * W;
      ctx.save(); ctx.beginPath(); ctx.rect(0, 0, edgeX, H); ctx.clip();
      bubbles.forEach(b => { b.update(); b.draw(); });
      ctx.restore();
      const sg = ctx.createLinearGradient(0, 0, edgeX, 0);
      sg.addColorStop(0, `hsla(${hue},80%,90%,0.1)`);
      sg.addColorStop(0.5, `hsla(${hue},70%,80%,0.04)`);
      sg.addColorStop(1, `hsla(${hue},60%,40%,0.07)`);
      ctx.fillStyle = sg; ctx.fillRect(0, 0, edgeX, H);
    }
    requestAnimationFrame(drawWater);
  };
  drawWater();

  const spinner = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M21 12a9 9 0 1 1-6.2-8.5"/></svg>';
  const check   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
  const total = chips.length;
  const doc = document.querySelector('.ed-doc');

  const setPct = (v) => { target = v; pct.textContent = Math.round(v) + '%'; };

  // Construye las secciones: para cada h4, sus párrafos/li hasta el siguiente h4
  const sections = chips.map((chip) => {
    const h4 = chip.parentElement;
    const targets = [];
    let n = h4.nextElementSibling;
    while (n && n.tagName !== 'H4') {
      if (n.tagName === 'P') targets.push(n);
      else if (n.tagName === 'UL') n.querySelectorAll('li').forEach(li => targets.push(li));
      n = n.nextElementSibling;
    }
    return { chip, h4, targets };
  });
  // Vacía el contenido para escribirlo después
  sections.forEach(s => s.targets.forEach(t => { t.dataset.full = t.textContent; t.textContent = ''; }));

  // Efecto máquina de escribir en un elemento
  const typeInto = (el, text, done) => {
    el.textContent = '';
    const caret = document.createElement('span');
    caret.className = 'caret';
    el.appendChild(caret);
    let k = 0;
    const tick = () => {
      if (k < text.length) {
        caret.insertAdjacentText('beforebegin', text.charAt(k));
        k++;
        setTimeout(tick, 8 + Math.random() * 16);
      } else { caret.remove(); done && done(); }
    };
    tick();
  };

  // Escribe en serie todos los targets de una sección
  const typeTargets = (targets, done) => {
    let k = 0;
    const next = () => {
      if (k >= targets.length) { done && done(); return; }
      typeInto(targets[k], targets[k].dataset.full, () => { k++; next(); });
    };
    next();
  };

  let i = 0;
  const startSection = () => {
    if (i >= total) { finish(); return; }
    const chip = chips[i];
    chip.className = 'sec-st gen';
    chip.innerHTML = spinner + 'Generando...';
    sub.textContent = 'Redactando: ' + chip.parentElement.firstChild.textContent.trim();
    setPct((i + 0.5) / total * 100);
    const complete = () => {
      chip.className = 'sec-st done';
      chip.innerHTML = check + 'Completado';
      setPct((i + 1) / total * 100);
      i++;
      setTimeout(startSection, 300);
    };
    if (sections[i].targets.length) typeTargets(sections[i].targets, complete);
    else setTimeout(complete, 500);
  };

  const finish = () => {
    setPct(100);
    title.textContent = 'Informe generado correctamente';
    sub.textContent = 'Puedes revisar y editar el contenido a continuación';
    banner.style.borderColor = 'rgba(61,220,151,.4)';
    setTimeout(() => {
      banner.style.transition = 'opacity .5s, transform .5s';
      banner.style.opacity = '0';
      banner.style.transform = 'translateY(-6px)';
      setTimeout(() => banner.remove(), 550);
    }, 1200);
  };

  setTimeout(startSection, 500);
})();
</script>
@endpush
@endif

@push('scripts')
<script>
(function(){
  const form = document.getElementById('chatForm');
  const input = document.getElementById('chatText');
  const msgs = document.getElementById('chatMsgs');
  const chips = document.getElementById('chatChips');
  if (!form || !msgs) return;

  const scrollDown = () => { msgs.scrollTop = msgs.scrollHeight; };

  const addMsg = (text, who) => {
    const el = document.createElement('div');
    el.className = 'chat-msg ' + who;
    el.textContent = text;
    msgs.appendChild(el);
    scrollDown();
    return el;
  };

  // Indicador "escribiendo..."
  const showTyping = () => {
    const el = document.createElement('div');
    el.className = 'chat-msg ai';
    el.innerHTML = '<span class="chat-typing"><i></i><i></i><i></i></span>';
    msgs.appendChild(el);
    scrollDown();
    return el;
  };

  // Efecto máquina de escribir
  const typeInto = (el, text, done) => {
    el.textContent = '';
    const caret = document.createElement('span');
    caret.className = 'caret';
    el.appendChild(caret);
    let i = 0;
    const tick = () => {
      if (i < text.length) {
        caret.insertAdjacentText('beforebegin', text.charAt(i));
        i++;
        scrollDown();
        setTimeout(tick, 14 + Math.random() * 28);
      } else {
        caret.remove();
        if (done) done();
      }
    };
    tick();
  };

  const replyFor = (q) => {
    const t = q.toLowerCase();
    if (t.includes('resume') || t.includes('hallazgo'))
      return 'Resumen de hallazgos: mucosa eritematosa en antro gástrico con pliegues conservados; esófago, píloro y duodeno sin alteraciones relevantes. Patrón compatible con gastritis antral leve.';
    if (t.includes('recomend'))
      return 'Recomendaciones sugeridas: 1) Omeprazol 20 mg c/24 h por 8 semanas. 2) Prueba de H. pylori. 3) Dieta y evitar AINEs. 4) Control endoscópico en 3 meses.';
    if (t.includes('paciente') || t.includes('datos'))
      return 'He insertado los datos del paciente: María González, 38 años, estudio del 08/05/2024, médico Dr. Víctor.';
    if (t.includes('colonoscop'))
      return 'Listo, cambié la plantilla a Colonoscopia y adapté las secciones del informe a ese tipo de estudio.';
    if (t.includes('diagn'))
      return 'Con base en los hallazgos, la impresión diagnóstica más probable es gastritis antral leve (confianza alta). ¿Deseas que lo agregue al informe?';
    return 'Entendido. He preparado una propuesta para "' + q + '" y la integré en el borrador del informe. ¿Quieres ajustarla?';
  };

  const send = (text) => {
    if (!text.trim()) return;
    addMsg(text, 'me');
    const typingEl = showTyping();
    setTimeout(() => {
      typingEl.remove();
      const aiEl = addMsg('', 'ai');
      typeInto(aiEl, replyFor(text));
    }, 700);
  };

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const v = input.value;
    input.value = '';
    send(v);
  });

  if (chips) {
    chips.addEventListener('click', (e) => {
      const b = e.target.closest('.chat-chip');
      if (b) send(b.textContent.trim());
    });
  }

  // Saludo inicial escribiéndose solo
  const greeting = 'Hola, soy ENCLAII. Puedo redactar secciones, sugerir diagnósticos, cambiar la plantilla o insertar variables. ¿Qué necesitas?';
  const greetEl = addMsg('', 'ai');
  setTimeout(() => typeInto(greetEl, greeting), 400);
})();
</script>
@endpush
