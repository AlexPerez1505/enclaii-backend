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
.gen-thumb{aspect-ratio:4/3;border-radius:10px;border:1px solid var(--stroke);overflow:hidden;background:var(--off)}
.gen-thumb img{width:100%;height:100%;object-fit:cover;display:block}
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

      {{-- Paso 1: Selección del estudio --}}
      <div class="step rise d2">
        <div class="step-head">
          <span class="step-num">1</span>
          <h4>Selección del estudio</h4>
          <svg class="step-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11.5 14.5 16 9.5"/></svg>
        </div>
        <div class="gen-field">
          <label>Estudio</label>
          <select class="gen-select" id="genEstudioSel"
                  onchange="if(this.value){ window.location.href='{{ route('ia-reportes.generar') }}?estudio='+this.value }">
            <option value="">Selecciona un estudio…</option>
            @foreach ($estudiosLista as $e)
              <option value="{{ $e['id'] }}" @selected(($datos['estudio_id'] ?? null) == $e['id'])>{{ $e['label'] }}</option>
            @endforeach
          </select>
        </div>
        <div class="gen-pat">
          <span class="av" id="genAv">{{ $datos['iniciales'] ?? 'NA' }}</span>
          <div>
            <div class="nm" id="genPat">{{ $datos['paciente'] ?: 'Sin paciente' }}</div>
            <div class="mt" id="genMeta">{{ trim(($datos['sexo'] ?? '').' · '.($datos['edad'] ?? ''), ' ·') ?: '—' }}</div>
          </div>
        </div>
        <div class="gen-pat-grid">
          <div><div class="k">Expediente</div><div class="v" id="genExp">{{ $datos['folio'] ?: '—' }}</div></div>
          <div><div class="k">Identificación</div><div class="v" id="genNss">{{ $datos['identificacion'] ?: '—' }}</div></div>
          <div><div class="k">Médico responsable</div><div class="v" id="genMedico">{{ $datos['medico'] ?: '—' }}</div></div>
          <div><div class="k">Fecha de nacimiento</div><div class="v" id="genDob">{{ $datos['nacimiento'] ?: '—' }}</div></div>
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
            <select class="gen-select" id="genTipo">
              @foreach (['Colonoscopia','Gastroscopia','Duodenoscopia','Broncoscopia'] as $opt)
                <option @selected(strtolower($datos['tipo'] ?? '') === strtolower($opt))>{{ $opt }}</option>
              @endforeach
            </select>
          </div>
          <div class="gen-field">
            <label>Fecha del estudio</label>
            <input class="gen-input" type="date" id="genFecha" value="{{ $datos['fecha'] ?? now()->format('Y-m-d') }}">
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
        <textarea class="gen-textarea" id="genObs" placeholder="Diagnóstico preliminar registrado al dar de alta al paciente...">{{ $datos['observaciones'] ?? '' }}</textarea>
      </div>

      {{-- Paso 4: Cargar evidencia (fotos reales del estudio) --}}
      <div class="step rise d5">
        <div class="step-head">
          <span class="step-num">4</span>
          <div style="flex:1">
            <div class="gen-ev-head">
              <div>
                <h4 style="margin-bottom:2px">Cargar evidencia</h4>
                <div class="cnt">{{ $evidencias->count() }} imágenes asociadas al estudio</div>
              </div>
              <div class="gen-ev-tools">
                <button type="button" aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                <button type="button" aria-label="Expandir"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg></button>
              </div>
            </div>
          </div>
        </div>
        <div class="gen-thumbs" id="genThumbs">
          @forelse ($evidencias as $i => $ev)
            <div class="gen-thumb {{ $i === 0 ? 'sel' : '' }}">
              <img src="{{ $ev }}" alt="Evidencia {{ $i + 1 }}" loading="lazy">
            </div>
          @empty
            <p style="grid-column:1/-1;color:var(--txt-soft);font-size:12.5px;margin:0">No hay imágenes asociadas a este estudio.</p>
          @endforelse
        </div>
      </div>

      {{-- Paso 5: Configuración AI --}}
      <div class="step rise d6">
        <div class="step-head">
          <span class="step-num">5</span>
          <h4>Configuración AI</h4>
          <svg class="step-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11.5 14.5 16 9.5"/></svg>
        </div>
        <div class="gen-checks" id="genOpts">
          <label class="gen-check"><input type="checkbox" data-opt="Analizar imágenes" @checked($userSettings['ai_analyze_photos'] ?? true)> Analizar imágenes</label>
          <label class="gen-check"><input type="checkbox" data-opt="Generar recomendaciones" @checked($userSettings['ai_recommend_procedures'] ?? true)> Generar recomendaciones</label>
          <label class="gen-check"><input type="checkbox" data-opt="Comparar estudios previos" checked> Comparar estudios previos</label>
          <label class="gen-check"><input type="checkbox" data-opt="Sugerir biopsias" checked> Sugerir biopsias</label>
          <label class="gen-check"><input type="checkbox" data-opt="Detectar patologías" @checked($userSettings['ai_suggest_diagnoses'] ?? true)> Detectar patologías</label>
          <label class="gen-check"><input type="checkbox" data-opt="Análisis de riesgo" checked> Análisis de riesgo</label>
        </div>
      </div>

    </div>

    {{-- Columna derecha: vista previa --}}
    <div class="gen-col-right">

      <div class="prev-card rise d4">
        <div class="prev-2col">
          <div>
            <h5>Hallazgos detectados por IA</h5>
            <ul class="prev-list" id="prevFindings">
              <li style="color:var(--txt-soft)">Genera el reporte para ver los hallazgos detectados por la IA.</li>
            </ul>
          </div>
          <div>
            <h5>Recomendaciones sugeridas por IA</h5>
            <ul class="prev-list" id="prevRecs">
              <li style="color:var(--txt-soft)">Genera el reporte para ver las recomendaciones de la IA.</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="gen-foot rise d6">
        <button class="btn-primary" type="button" id="btnGenerar">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="M9 15l3 3 3-3"/><circle cx="12" cy="12" r="1" fill="currentColor"/></svg>
          <span class="btn-label">Generar Reporte IA</span>
        </button>
      </div>

    </div>
  </div>

@endsection

@push('scripts')
<script>
(function(){
  const btn = document.getElementById('btnGenerar');
  if (!btn) return;
  const label = btn.querySelector('.btn-label');
  const url = "{{ route('ia-reportes.generar.post') }}";
  const redactarUrl = "{{ route('ia-reportes.redactar') }}";
  const csrf = "{{ csrf_token() }}";
  const ESTUDIO_ID = @json($datos['estudio_id'] ?? null);

  const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  // Imagen de vista previa según el tipo de estudio seleccionado
  const STUDY_IMG = {
    'Colonoscopia':  '/images/Colonoscopia.png',
    'Gastroscopia':  '/images/Gastroscopia.png',
    'Duodenoscopia': '/images/Duodenoscopia.png',
    'Broncoscopia':  '/images/Broncoscopia.png',
  };
  const tipoSel = document.getElementById('genTipo');
  const prevImg = document.getElementById('prevImg');
  const syncStudyImg = () => {
    if (!tipoSel || !prevImg) return;
    const src = STUDY_IMG[tipoSel.value];
    if (src) {
      prevImg.style.background = 'center/cover no-repeat url("' + src + '")';
    } else {
      prevImg.style.background = '';
    }
  };
  if (tipoSel) tipoSel.addEventListener('change', syncStudyImg);
  syncStudyImg();

  const ckSvg = '<svg class="ck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
  const rcSvg = '<svg class="rc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>';

  const confTag = c => {
    const v = (c || '').toLowerCase();
    if (v.startsWith('alta')) return '<span class="tag-conf hi">Alta confianza</span>';
    if (v.startsWith('baja')) return '<span class="tag-conf mid">Baja confianza</span>';
    return '<span class="tag-conf mid">Media confianza</span>';
  };

  function render(r){
    const findings = document.getElementById('prevFindings');
    if (findings) findings.innerHTML = (r.hallazgos || []).map(h =>
      `<li>${ckSvg} ${esc(h.texto)} ${confTag(h.confianza)}</li>`).join('') || '<li>Sin hallazgos</li>';

    const recs = document.getElementById('prevRecs');
    if (recs) recs.innerHTML = (r.recomendaciones || []).map(t =>
      `<li>${rcSvg} ${esc(t)}</li>`).join('') || '<li>Sin recomendaciones</li>';
  }

  btn.addEventListener('click', async () => {
    const opciones = {};
    document.querySelectorAll('#genOpts input[type=checkbox]').forEach(c => {
      if (c.checked) opciones[c.dataset.opt] = true;
    });

    const payload = {
      estudio_id: ESTUDIO_ID,
      paciente: (document.getElementById('genPat')?.textContent || '').trim(),
      tipo_estudio: document.getElementById('genTipo')?.value || '',
      fecha: document.getElementById('genFecha')?.value || '',
      observaciones: (document.getElementById('genObs')?.value || '').trim(),
      opciones,
      imagenes: @json($evidencias),
    };

    if (!ESTUDIO_ID) {
      alert('Selecciona un estudio antes de generar el reporte.');
      return;
    }
    if (!payload.observaciones) {
      alert('Escribe las observaciones clínicas antes de generar el reporte.');
      return;
    }

    const original = label.textContent;
    btn.disabled = true;
    btn.style.opacity = '.7';
    label.textContent = 'Generando...';

    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify(payload),
      });
      const data = await res.json();
      if (!res.ok || !data.ok) {
        throw new Error(data.message || 'No se pudo generar el reporte.');
      }
      // Muestra en la vista previa las propuestas de la IA (diagnóstico, hallazgos
      // y recomendaciones) obtenidas tras analizar las imágenes y observaciones.
      render(data.reporte);
      // Guarda el reporte + datos del estudio y abre el editor para redactarlo
      sessionStorage.setItem('iaReporte', JSON.stringify({
        reporte: data.reporte,
        meta: {
          estudio_id: ESTUDIO_ID,
          paciente: payload.paciente,
          tipo_estudio: payload.tipo_estudio,
          fecha: payload.fecha,
        },
        imagenes: @json($evidencias->values()),
      }));
      label.textContent = 'Abriendo editor...';
      // Pausa breve para que el profesional vea la propuesta de la IA antes de editar.
      const nextUrl = new URL(redactarUrl, window.location.origin);
      nextUrl.searchParams.set('reporte', data.reporte_id);
      nextUrl.searchParams.set('estudio', ESTUDIO_ID);
      setTimeout(() => { window.location.href = nextUrl.toString(); }, 1400);
    } catch (e) {
      alert('Error: ' + e.message);
      btn.disabled = false;
      btn.style.opacity = '';
      label.textContent = original;
    }
  });
})();
</script>
@endpush
