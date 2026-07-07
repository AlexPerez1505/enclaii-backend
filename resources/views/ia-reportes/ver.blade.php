@extends('layouts.app')

@section('title', 'Ver Informe')
@section('active', 'ia-reportes')
@section('header-title', 'Informe de ' . ($reporte?->estudio?->tipo ?? 'Endoscopia'))
@section('header-sub')
  Visualización del informe (solo lectura)
@endsection

@push('styles')
<style>
/* ============ VER INFORME (solo lectura, mismo layout del editor) ============ */
.vw-actions{display:flex;justify-content:flex-end;gap:10px;margin-bottom:14px;flex-wrap:wrap}
.vw-btn{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:var(--r-md);font-weight:600;font-size:13.5px;border:1px solid var(--stroke-strong);background:var(--panel-2);transition:background-color .15s}
.vw-btn svg{width:16px;height:16px}
@media (hover:hover){.vw-btn:hover{background:rgba(110,160,255,.1)}}
.vw-btn.primary{border:0;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff}

.vw-status{display:flex;align-items:center;gap:10px;margin-bottom:14px;font-size:12.5px;color:var(--txt-soft);flex-wrap:wrap}
.vw-status .chip{font-size:11.5px}

/* Papel del informe */
.vw-doc{max-width:820px;margin:0 auto;background:var(--panel);border:1px solid var(--stroke);border-radius:var(--r-md);padding:34px 40px;line-height:1.55;box-shadow:0 8px 30px rgba(0,0,0,.12)}
.vw-doc .doc-h{text-align:center;margin-bottom:22px}
.vw-doc .doc-h h2{font-family:'Sora',sans-serif;font-size:21px;font-weight:800;letter-spacing:.01em}
.vw-doc .doc-h p{font-size:12.5px;color:var(--txt-soft);letter-spacing:.06em;margin-top:4px}

.vw-doc .doc-meta{display:grid;grid-template-columns:150px 1fr;gap:5px 16px;font-size:13px;margin-bottom:20px}
.vw-doc .doc-meta .k{color:var(--txt-soft)}

.vw-doc h4{font-size:13px;font-weight:700;letter-spacing:.04em;margin:18px 0 6px;color:var(--cyan)}
.vw-doc p,.vw-doc ul{font-size:13px}
.vw-doc ul{list-style:disc;padding-left:20px;display:flex;flex-direction:column;gap:4px;margin-top:4px}

/* Encabezado tipo informe */
.rep-header{position:relative;height:90px;margin-bottom:16px}
.rep-header>div{position:absolute;top:0;height:90px;box-sizing:border-box}
.rep-logo{left:0;width:90px;display:grid;place-items:center;border-radius:8px;overflow:hidden}
.rep-logo .logo-ph{width:100%;height:100%;display:grid;place-items:center;text-align:center;font-size:10px;line-height:1.25;color:var(--txt-soft);border:1px dashed var(--stroke-strong);border-radius:8px;padding:4px}
.rep-clinic{left:96px;right:96px;background:#cfe6e4;border-radius:4px;text-align:center;display:flex;align-items:center;justify-content:center;overflow:hidden}
.rep-clinic span{font-family:'Sora',sans-serif;font-weight:700;color:#143036}
.rep-anat{right:0;width:90px;color:var(--txt-soft);display:grid;place-items:center;overflow:hidden}
.rep-anat svg{width:100%;height:100%;object-fit:contain;display:block}

/* Imágenes */
.rep-imgs{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin:14px 0 20px}
.rep-imgs .cell{aspect-ratio:4/3;background:#e5e5e5;border:1px solid var(--stroke);border-radius:4px}

/* Firma */
.rep-sign{margin-top:38px;display:flex;justify-content:center}
.rep-sign .sign-box{min-width:250px;text-align:center;padding-top:8px;border-top:1px solid var(--txt)}
.rep-sign .sign-box .nm{font-size:13px;font-weight:700}

/* Contenido del reporte */
.doc-content{white-space:pre-wrap;font-size:13px;line-height:1.55}
.doc-content h4{color:var(--cyan);font-size:13px;font-weight:700;margin:16px 0 6px}
/* Ocultar botones de edición de secciones en reportes guardados (por compatibilidad) */
.vw-doc .sec-add,.vw-doc .sec-hide,.vw-doc .sec-delete{display:none!important}

/* Impresión en tamaño carta (una sola hoja) */
@media print{
  @page { size: letter; margin: 0.4in; }
  body { margin: 0; padding: 0; }
  /* Ocultar botones de edición de secciones al imprimir */
  .sec-add,.sec-hide,.sec-delete{display:none!important}
  /* Ocultar todo el layout de la app excepto el documento del informe */
  body > * { display: none !important; }
  .vw-doc { display: block !important; }
  .vw-doc, .vw-doc * { visibility: visible; }
  .vw-doc { position: absolute; left: 0; top: 0; width: 7.7in; height: 10.2in; max-width: none; margin: 0; border: 0; border-radius: 0; box-shadow: none; background: #fff; color: #000; padding: 0; overflow: hidden; font-size: 10pt; line-height: 1.35; }
  .vw-doc .doc-h { margin-bottom: 12px; }
  .vw-doc .doc-h h2 { font-size: 16pt; }
  .vw-doc .doc-h p { font-size: 9pt; }
  .vw-doc .doc-meta { font-size: 9.5pt; margin-bottom: 12px; gap: 3px 10px; }
  .vw-doc h4 { font-size: 10pt; margin: 10px 0 4px; color: #000; }
  .vw-doc p, .vw-doc ul { font-size: 9.5pt; }
  .rep-header { height: 70px; margin-bottom: 10px; }
  .rep-header > div { height: 70px; }
  .rep-logo { width: 70px; }
  .rep-anat { width: 70px; }
  .rep-clinic { left: 76px; right: 76px; }
  .rep-imgs { grid-template-columns: repeat(3, 1fr); gap: 4px; margin: 8px 0 12px; }
  .rep-imgs .cell { aspect-ratio: 4/3; max-height: 1.4in; background: #e5e5e5; }
  .rep-imgs .cell img { max-height: 1.4in; object-fit: cover; }
  .rep-sign { margin-top: 18px; }
  .rep-sign .sign-box .nm { font-size: 10pt; }
  .rep-header, .rep-imgs, .doc-meta, .doc-content { page-break-inside: avoid; }
  .vw-doc .doc-h h2, .vw-doc h4 { color: #000; }
  .rep-clinic { background: #e8f4f3; }
}
</style>
@endpush

@section('content')

  @php
    $paciente = $reporte?->estudio?->paciente;
    $nombrePaciente = $paciente?->nombre_completo ?? $reporte?->estudio?->paciente_nombre ?? 'Paciente no registrado';
    $fechaEstudio = $reporte?->estudio?->fecha?->format('d/m/Y') ?? $reporte?->created_at?->format('d/m/Y') ?? '—';
    $horaEstudio = $reporte?->estudio?->hora_inicio ?? '';
    $tipoEstudio = $reporte?->estudio?->tipo ?? 'Endoscopia';
    $medicoNombre = $reporte?->usuario?->name ?? auth()->user()?->name ?? '—';

    $tituloPlantilla = $reporte?->plantilla?->titulo ?? 'INFORME DE '.mb_strtoupper($tipoEstudio);
    $subPlantilla = $reporte?->plantilla?->subtitulo ?? mb_strtoupper($tipoEstudio);
    $firmaNombre = $reporte?->plantilla?->configuracion['signName'] ?? $reporte?->usuario?->name ?? auth()->user()?->name ?? '—';

    $contenidoHtml = $reporte?->contenido_html;
    $contenidoTexto = $reporte?->contenido_texto ?? '';

    $imagenes = $estudioImagenes->map(fn ($img) => is_array($img) ? ($img['url'] ?? null) : $img)->filter()->values();
  @endphp

  <div class="vw-actions">
    <a class="vw-btn" href="{{ route('ia-reportes.todos') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
    <button class="vw-btn primary" type="button" onclick="downloadPdf()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Descargar PDF
    </button>
  </div>

  <div class="vw-status">
    <span class="chip done">Completado</span>
    <span>Generado el {{ $reporte?->created_at?->format('d/m/Y h:i A') ?? '—' }}</span>
  </div>

  <article class="card vw-doc rise d2">
    {{-- Encabezado tipo informe clínico --}}
    <div class="rep-header">
      <div class="rep-logo">
        <span class="logo-ph">Logo de<br>la clínica</span>
      </div>
      <div class="rep-clinic">
        <span>Nombre de la clínica</span>
      </div>
      <div class="rep-anat">
        <svg viewBox="0 0 80 110" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M30 8c-6 6-10 14-10 22 0 6 2 11 6 16 4 5 6 9 6 15 0 10-8 14-8 24 0 8 6 13 14 13s14-6 14-15c0-12-12-16-12-26 0-7 5-11 9-17 3-5 5-10 5-16C58 22 50 12 42 8"/><path d="M30 8c4-3 8-3 12 0"/></svg>
      </div>
    </div>

    <div class="doc-h">
      <h2>{{ $tituloPlantilla }}</h2>
      <p>{{ $subPlantilla }}</p>
    </div>

    <div class="doc-meta">
      <span class="k">Paciente:</span><span>{{ $nombrePaciente }}</span>
      <span class="k">Fecha de nacimiento:</span><span>{{ $paciente?->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</span>
      <span class="k">Edad:</span><span>{{ $paciente?->edad ?? '—' }} años</span>
      <span class="k">Médico solicitante:</span><span>{{ $medicoNombre }}</span>
      <span class="k">Fecha del estudio:</span><span>{{ $fechaEstudio }} {{ $horaEstudio }}</span>
      <span class="k">Endoscopista:</span><span>{{ $medicoNombre }}</span>
      <span class="k">Tipo de estudio:</span><span>{{ $tipoEstudio }}</span>
    </div>

    @php
      $cols = $reporte?->plantilla?->columnas ?? 4;
      if ($cols < 1 || $cols > 8) $cols = 4;
      $imgCount = $imagenes->count();
      $repImgsStyle = $imgCount ? 'grid-template-columns:repeat(' . min($cols, $imgCount) . ',1fr);' : '';
    @endphp
    {{-- Imágenes del estudio --}}
    @if($imgCount)
      <div class="rep-imgs" style="{{ $repImgsStyle }}">
        @foreach($imagenes as $url)
          <span class="cell" style="background:none;overflow:hidden">
            <img src="{{ $url }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
          </span>
        @endforeach
      </div>
    @endif

    <div class="doc-content @if(!$contenidoHtml) pre @endif">
      @if($contenidoHtml)
        {!! $contenidoHtml !!}
      @else
        {!! nl2br(e($contenidoTexto)) !!}
      @endif
    </div>

    <div class="rep-sign" data-pos="center">
      <div class="sign-box">
        <div class="nm">{{ $firmaNombre }}</div>
      </div>
    </div>
  </article>

  @php
    $cfg = $reporte?->plantilla?->configuracion ?? null;
  @endphp
  @if($cfg)
  <script>
    (function() {
      const cfg = @json($cfg);
      const header = document.querySelector('.rep-header');
      const repLogo = document.querySelector('.rep-logo');
      const repAnat = document.querySelector('.rep-anat');
      const repClinic = document.querySelector('.rep-clinic span');
      const repClinicBox = document.querySelector('.rep-clinic');
      const PAGE_W = 794;
      const s = (header ? header.clientWidth : PAGE_W) / PAGE_W;
      const place = (el, box) => {
        if (!el || !box) return;
        el.style.position = 'absolute';
        el.style.left = (box.x * s) + 'px';
        el.style.top = (box.y * s) + 'px';
        el.style.width = (box.w * s) + 'px';
        el.style.height = (box.h * s) + 'px';
      };
      if (header && cfg.headH) header.style.height = (cfg.headH * s) + 'px';
      if (repLogo) {
        if (cfg.logoImg) repLogo.innerHTML = '<img src="' + cfg.logoImg + '" alt="Logo de la clínica" style="width:100%;height:100%;object-fit:contain">';
        place(repLogo, cfg.logo);
      }
      if (repClinicBox) place(repClinicBox, cfg.name);
      if (repClinic) {
        if (cfg.clinic) repClinic.textContent = cfg.clinic;
        if (cfg.name && cfg.name.fontSize) repClinic.style.fontSize = (cfg.name.fontSize * s) + 'px';
      }
      if (repAnat) {
        const tipo = '{{ ucfirst(strtolower($tipoEstudio)) }}';
        const STUDY_IMG = {
          'Colonoscopia': '/images/Colonoscopia.png',
          'Gastroscopia': '/images/Gastroscopia.png',
          'Duodenoscopia': '/images/Duodenoscopia.png',
          'Broncoscopia': '/images/Broncoscopia.png',
        };
        const anatSrc = cfg.anatImg || STUDY_IMG[tipo] || null;
        if (anatSrc) repAnat.innerHTML = '<img src="' + anatSrc + '" alt="Imagen lateral" style="width:100%;height:100%;object-fit:contain">';
        place(repAnat, cfg.anat);
      }
    })();
  </script>
  @endif

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    (function() {
      const doc = document.querySelector('.vw-doc');
      if (!doc) return;
      let originalTransform = '';
      let originalWidth = '';
      const fitToOnePage = () => {
        originalTransform = doc.style.transform || '';
        originalWidth = doc.style.width || '';
        doc.style.transform = '';
        const targetHeight = 10.2 * 96; // 10.2in a 96dpi
        const actualHeight = doc.scrollHeight;
        if (actualHeight > targetHeight) {
          const scale = targetHeight / actualHeight;
          doc.style.transform = 'scale(' + scale + ')';
          doc.style.transformOrigin = 'top left';
          doc.style.width = (7.7 / scale) + 'in';
        }
      };
      const restore = () => {
        doc.style.transform = originalTransform;
        doc.style.transformOrigin = '';
        doc.style.width = originalWidth;
      };
      window.addEventListener('beforeprint', fitToOnePage);
      window.addEventListener('afterprint', restore);

      window.downloadPdf = () => {
        const clone = doc.cloneNode(true);
        // Remover botones de edición de secciones (compatibilidad con reportes antiguos)
        clone.querySelectorAll('.sec-add, .sec-hide, .sec-delete, button').forEach(el => el.remove());
        clone.style.position = 'relative';
        clone.style.width = '7.7in';
        clone.style.height = 'auto';
        clone.style.transform = 'none';
        clone.style.background = '#fff';
        clone.style.color = '#000';
        clone.style.padding = '0';
        clone.style.margin = '0';
        clone.style.maxWidth = 'none';
        clone.style.boxShadow = 'none';
        clone.style.border = '0';
        const wrapper = document.createElement('div');
        wrapper.style.position = 'absolute';
        wrapper.style.left = '-9999px';
        wrapper.style.top = '0';
        wrapper.style.width = '7.7in';
        wrapper.style.background = '#fff';
        wrapper.appendChild(clone);
        document.body.appendChild(wrapper);
        const opt = {
          margin: [0.4, 0.4, 0.4, 0.4],
          filename: 'Informe_{{ \Illuminate\Support\Str::replace(' ', '_', $nombrePaciente) }}_{{ $fechaEstudio }}.pdf',
          image: { type: 'jpeg', quality: 0.98 },
          html2canvas: { scale: 2, useCORS: true, backgroundColor: '#fff' },
          jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },
          pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
        };
        html2pdf().set(opt).from(clone).save().then(() => wrapper.remove());
      };
    })();
  </script>

@endsection
