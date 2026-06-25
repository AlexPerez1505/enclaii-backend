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
.rep-imgs .cell{aspect-ratio:4/3;background:linear-gradient(160deg,#1c2435,#10151f);border:1px solid var(--stroke);border-radius:4px}

/* Firma */
.rep-sign{margin-top:38px;display:flex;justify-content:center}
.rep-sign .sign-box{min-width:250px;text-align:center;padding-top:8px;border-top:1px solid var(--txt)}
.rep-sign .sign-box .nm{font-size:13px;font-weight:700}

/* Contenido del reporte */
.doc-content{white-space:pre-wrap;font-size:13px;line-height:1.55}
.doc-content h4{color:var(--cyan);font-size:13px;font-weight:700;margin:16px 0 6px}

/* Impresión en tamaño carta */
@media print{
  @page { size: letter; margin: 0.5in; }
  body * { visibility: hidden; }
  .vw-doc, .vw-doc * { visibility: visible; }
  .vw-doc { position: absolute; left: 0; top: 0; width: 100%; max-width: none; margin: 0; border: 0; border-radius: 0; box-shadow: none; background: #fff; color: #000; padding: 0; }
  .vw-actions, .vw-status, .widget-drag-handle, .widget-resize-handle, header, aside, nav, footer { display: none !important; }
  .rep-header, .rep-imgs, .doc-meta, .doc-content { page-break-inside: avoid; }
  .vw-doc .doc-h h2, .vw-doc h4 { color: #000; }
  .rep-clinic { background: #e8f4f3; }
  .rep-imgs .cell { background: #e5e5e5; }
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
    $contenido = $reporte?->contenido_texto ?? '';
  @endphp

  <div class="vw-actions">
    <a class="vw-btn" href="{{ route('ia-reportes.todos') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
    <button class="vw-btn primary" type="button" onclick="window.print()">
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
      <h2>INFORME DE {{ mb_strtoupper($tipoEstudio) }}</h2>
      <p>{{ mb_strtoupper($tipoEstudio) }}</p>
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

    {{-- Imágenes del estudio --}}
    <div class="rep-imgs">
      <span class="cell"></span><span class="cell"></span><span class="cell"></span><span class="cell"></span>
      <span class="cell"></span><span class="cell"></span><span class="cell"></span><span class="cell"></span>
    </div>

    <div class="doc-content">
      {{ $contenido }}
    </div>

    <div class="rep-sign" data-pos="center">
      <div class="sign-box">
        <div class="nm">{{ $medicoNombre }}</div>
      </div>
    </div>
  </article>

@endsection
