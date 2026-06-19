@extends('layouts.app')

@section('title', 'Ver Informe')
@section('active', 'ia-reportes')
@section('header-title', 'Informe de Endoscopia')
@section('header-sub')
  Visualización del informe (solo lectura)
@endsection

@push('styles')
<style>
/* ============ VER INFORME (solo lectura) ============ */
.vw-actions{display:flex;justify-content:flex-end;gap:10px;margin-bottom:14px;flex-wrap:wrap}
.vw-btn{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:var(--r-md);font-weight:600;font-size:13.5px;border:1px solid var(--stroke-strong);background:var(--panel-2);transition:background-color .15s}
.vw-btn svg{width:16px;height:16px}
@media (hover:hover){.vw-btn:hover{background:rgba(110,160,255,.1)}}
.vw-btn.primary{border:0;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff}

.vw-status{display:flex;align-items:center;gap:10px;margin-bottom:14px;font-size:12.5px;color:var(--txt-soft);flex-wrap:wrap}
.vw-status .chip{font-size:11.5px}

.vw-doc{max-width:840px;margin:0 auto;padding:34px 40px;line-height:1.6}
.vw-doc .doc-h{text-align:center;margin-bottom:24px}
.vw-doc .doc-h h2{font-family:'Sora',sans-serif;font-size:22px;font-weight:800;letter-spacing:.01em}
.vw-doc .doc-h p{font-size:12.5px;color:var(--txt-soft);letter-spacing:.06em;margin-top:4px}
.vw-doc .doc-meta{display:grid;grid-template-columns:150px 1fr;gap:6px 16px;font-size:13.5px;margin-bottom:22px;padding-bottom:20px;border-bottom:1px solid var(--stroke)}
.vw-doc .doc-meta .k{color:var(--txt-soft)}
.vw-doc h4{font-size:13.5px;font-weight:700;letter-spacing:.04em;margin:20px 0 6px;color:var(--cyan)}
.vw-doc p,.vw-doc ul{font-size:13.5px}
.vw-doc ul{list-style:disc;padding-left:22px;display:flex;flex-direction:column;gap:5px;margin-top:4px}
.vw-annex{display:flex;gap:14px;margin-top:12px;flex-wrap:wrap}
.vw-annex .ph{width:160px;height:110px;border-radius:10px;border:1px solid var(--stroke)}
.vw-annex .ph:nth-child(1){background:radial-gradient(circle at 45% 40%,#c0565a,#5c1d22)}
.vw-annex .ph:nth-child(2){background:radial-gradient(circle at 50% 45%,#c97a52,#5c2d1d)}
.vw-annex .ph:nth-child(3){background:radial-gradient(circle at 50% 50%,#2a2f45,#0b0e1a)}
.vw-sign{display:flex;justify-content:flex-end;margin-top:40px}
.vw-sign .box{text-align:center;border-top:1px solid var(--stroke-strong);padding-top:8px;min-width:220px}
.vw-sign .nm{font-weight:700;font-size:13.5px}
.vw-sign .rl{font-size:12px;color:var(--txt-soft)}
</style>
@endpush

@section('content')

  <div class="vw-actions">
    <a class="vw-btn" href="{{ route('ia-reportes.todos') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
    <button class="vw-btn" type="button" onclick="window.print()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
      Imprimir
    </button>
    <button class="vw-btn primary" type="button">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Descargar PDF
    </button>
  </div>

  <div class="vw-status">
    <span class="chip done">Completado</span>
    <span>Confianza IA: <b style="color:var(--txt)">92%</b></span>
    <span>·</span>
    <span>Generado el 08/05/2024 10:30 AM</span>
  </div>

  <article class="card vw-doc rise d2">
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

    <h4>INDICACIÓN</h4>
    <p>Dolor abdominal epigástrico, náusea y pirosis.</p>

    <h4>SEDACIÓN</h4>
    <p>Sedación consciente con midazolam y fentanilo.</p>

    <h4>HALLAZGOS</h4>
    <ul>
      <li>Esófago: Se observa mucosa de aspecto normal, línea Z a 38 cm de arcada dentaria.</li>
      <li>Estómago: Mucosa eritematosa en antro gástrico. Pliegues gástricos conservados.</li>
      <li>Píloro: Permeable.</li>
      <li>Duodeno: Bulbo y segunda porción con mucosa de aspecto normal.</li>
    </ul>

    <h4>IMPRESIÓN DIAGNÓSTICA</h4>
    <p>Gastritis antral leve.</p>

    <h4>PLAN Y RECOMENDACIONES</h4>
    <ul>
      <li>Omeprazol 20 mg cada 24 horas por 8 semanas.</li>
      <li>Dieta y medidas generales.</li>
    </ul>

    <h4>OBSERVACIONES</h4>
    <p>Se toman biopsias de antro para estudio histopatológico y detección de Helicobacter pylori.</p>

    <h4>ANEXOS</h4>
    <div class="vw-annex">
      <span class="ph"></span><span class="ph"></span><span class="ph"></span>
    </div>

    <div class="vw-sign">
      <div class="box">
        <div class="nm">Dr. Víctor</div>
        <div class="rl">Endoscopista</div>
      </div>
    </div>
  </article>

@endsection

@push('scripts')
<script>
(function(){
  const params = new URLSearchParams(window.location.search);
  const paciente = params.get('paciente');
  const procedimiento = params.get('procedimiento');

  // Ejemplo de datos por procedimiento
  const plantillas = {
    'Endoscopia': {
      titulo: 'INFORME DE ENDOSCOPIA',
      subtitulo: 'ENDOSCOPIA DIGESTIVA ALTA',
      indicacion: 'Dolor abdominal epigástrico, náusea y pirosis.',
      sedacion: 'Sedación consciente con midazolam y fentanilo.',
      hallazgos: [
        'Esófago: Se observa mucosa de aspecto normal, línea Z a 38 cm de arcada dentaria.',
        'Estómago: Mucosa eritematosa en antro gástrico. Pliegues gástricos conservados.',
        'Píloro: Permeable.',
        'Duodeno: Bulbo y segunda porción con mucosa de aspecto normal.'
      ],
      impresion: 'Gastritis antral leve.',
      plan: ['Omeprazol 20 mg cada 24 horas por 8 semanas.', 'Dieta y medidas generales.'],
      observaciones: 'Se toman biopsias de antro para estudio histopatológico y detección de Helicobacter pylori.'
    },
    'Colonoscopia': {
      titulo: 'INFORME DE COLONOSCOPIA',
      subtitulo: 'COLONOSCOPIA DIAGNÓSTICA',
      indicacion: 'Revisión de programa de tamizaje colorectal.',
      sedacion: 'Sedación consciente con propofol.',
      hallazgos: [
        'Recto: Mucosa sana, sin lesiones.',
        'Colon sigmoides: Un pólipo hiperplásico de 4 mm.',
        'Colon descendente: Mucosa normal.',
        'Colon transverso y ascendente: Sin hallazgos relevantes.'
      ],
      impresion: 'Pólipo hiperplásico colon sigmoides.',
      plan: ['Repetir colonoscopia en 5 años.', 'Biopsia de seguimiento según histopatología.'],
      observaciones: 'Se toma biopsia del pólipo para estudio histopatológico.'
    },
    'Gastroscopía': {
      titulo: 'INFORME DE GASTROSCOPÍA',
      subtitulo: 'GASTROSCOPÍA DIAGNÓSTICA',
      indicacion: 'Pirosis y regurgitación frecuente.',
      sedacion: 'Sedación consciente con midazolam.',
      hallazgos: [
        'Esófago: Esofagitis leve a nivel distal.',
        'Estómago: Mucosa de aspecto normal.',
        'Píloro: Permeable.',
        'Duodeno: Mucosa normal.'
      ],
      impresion: 'Esofagitis leve por reflujo gastroesofágico.',
      plan: ['IBP 40 mg cada 24 horas por 4 semanas.', 'Evitar alimentos irritantes.'],
      observaciones: 'Recomendación de manometría y pHmetría si persisten síntomas.'
    },
    'Dudoescopía': {
      titulo: 'INFORME DE DUODENOScOPÍA',
      subtitulo: 'ESTUDIO ENDOSCÓPICO DEL DUODENO',
      indicacion: 'Dolor abdominal y esteatorrea.',
      sedacion: 'Sedación consciente con midazolam y fentanilo.',
      hallazgos: [
        'Bulbo duodenal: Mucosa normal.',
        'Segunda porción: Pápila ampollar normal.',
        'Tercera porción: Mucosa de aspecto normal.',
        'No se observan estenosis ni masas.'
      ],
      impresion: 'Duodeno de aspecto normal.',
      plan: ['Continuar estudio con laboratorios de malabsorción.', 'Control en 3 meses.'],
      observaciones: 'Se toman biopsias duodenales para estudio histopatológico.'
    },
    'Broncoscopia': {
      titulo: 'INFORME DE BRONCOSCOPIA',
      subtitulo: 'BRONCOSCOPIA DIAGNÓSTICA',
      indicacion: 'Tos persistente y disnea leve.',
      sedacion: 'Sedación consciente con midazolam y fentanilo.',
      hallazgos: [
        'Vía aérea superior: Mucosa laringea de aspecto normal.',
        'Tráquea: Mucosa de aspecto normal, luz permeable.',
        'Bronquios principales: Mucosa de aspecto normal.',
        'No se observan secreciones ni masas.'
      ],
      impresion: 'Broncoscopia sin hallazgos relevantes.',
      plan: ['Manejo sintomático.', 'Control según evolución clínica.'],
      observaciones: 'No se toman biopsias.'
    }
  };

  function render(data) {
    const p = data.paciente || 'María González';
    const proc = data.procedimiento || 'Endoscopia';
    const t = plantillas[proc] || plantillas['Endoscopia'];
    const now = new Date();
    const fecha = now.toLocaleDateString('es-ES') + ' ' + now.toLocaleTimeString('es-ES', {hour:'2-digit', minute:'2-digit'});
    const edad = data.edad || '38 años';
    const nac = data.nacimiento || '12/06/1985';

    document.querySelector('.doc-h h2').textContent = t.titulo;
    document.querySelector('.doc-h p').textContent = t.subtitulo;

    const rows = document.querySelectorAll('.doc-meta span:nth-child(2)');
    const keys = [
      p, nac, edad, 'Dr. Víctor', fecha, 'Dr. Víctor', proc
    ];
    rows.forEach((span, i) => { if (keys[i] !== undefined) span.textContent = keys[i]; });

    const h4s = document.querySelectorAll('.vw-doc h4');
    const listas = document.querySelectorAll('.vw-doc ul');
    const parrafos = document.querySelectorAll('.vw-doc > p');

    h4s.forEach(h => {
      const txt = h.textContent.trim();
      if (txt === 'INDICACIÓN') h.nextElementSibling.textContent = t.indicacion;
      if (txt === 'SEDACIÓN') h.nextElementSibling.textContent = t.sedacion;
      if (txt === 'HALLAZGOS') {
        const ul = h.nextElementSibling;
        ul.innerHTML = t.hallazgos.map(x => `<li>${x}</li>`).join('');
      }
      if (txt === 'IMPRESIÓN DIAGNÓSTICA') h.nextElementSibling.textContent = t.impresion;
      if (txt === 'PLAN Y RECOMENDACIONES') {
        const ul = h.nextElementSibling;
        ul.innerHTML = t.plan.map(x => `<li>${x}</li>`).join('');
      }
      if (txt === 'OBSERVACIONES') h.nextElementSibling.textContent = t.observaciones;
    });

    document.title = 'Ver Informe - ' + p;
  }

  // Si vienen datos por URL, guardar y renderizar
  if (paciente) {
    const data = { paciente, procedimiento: procedimiento || 'Endoscopia', timestamp: Date.now() };
    localStorage.setItem('lastReport', JSON.stringify(data));
    render(data);
  } else {
    // Intentar cargar el último informe guardado
    const saved = localStorage.getItem('lastReport');
    if (saved) {
      try { render(JSON.parse(saved)); } catch(e) {}
    }
  }
})();
</script>
@endpush
