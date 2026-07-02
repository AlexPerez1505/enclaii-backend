@extends('layouts.app')

@section('title', 'Nuevo Estudio')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Datos nuevos
@endsection

@push('styles')
@include('estudios.crear.crear-css')
@endpush

@section('content')
<div style="margin-bottom:12px">
  <a href="{{ route('dashboard') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--txt-soft);text-decoration:none;padding:6px 12px;border-radius:8px;border:1px solid var(--stroke);background:var(--panel);transition:all 150ms" onmouseover="this.style.color='var(--blue)';this.style.borderColor='var(--blue)'" onmouseout="this.style.color='var(--txt-soft)';this.style.borderColor='var(--stroke)'">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Volver
  </a>
</div>
@php
  $paciente = $paciente ?? null;
  $galImagenes = $galImagenes ?? collect();
  $galVideos = $galVideos ?? collect();
  $galNombre = $paciente?->nombre_completo ?? 'Maria Gonzales';
  $galIni = $paciente
    ? (collect(explode(' ', $galNombre))->filter()->take(2)->map(fn($x)=>mb_strtoupper(mb_substr($x,0,1)))->implode('') ?: 'PX')
    : 'MG';
  $galEstudios = $paciente
    ? $galImagenes->pluck('estudio_id')->merge($galVideos->pluck('estudio_id'))->filter()->unique()->count()
    : 15;
  $galUltimoArchivo = $galImagenes->first() ?? $galVideos->first();
  $galUltimo = $paciente ? (optional($galUltimoArchivo?->capturado_en)->format('d/m/Y') ?? '—') : '15/07/2025';
  $galSexo = $paciente?->sexo ?? 'Femenino';
  $galEdad = $paciente ? ($paciente->edad ? $paciente->edad.' años' : '—') : '38 años';
  $galCodigo = $paciente ? ($paciente->folio ?? $paciente->identificacion ?? '—') : '00012345';
  $reportes = $reportes ?? collect();
@endphp

@if($paciente)
<div class="np-patient-toolbar rise d1">
  <a class="np-back-btn" id="npBackToPatientsTop" href="{{ route('pacientes.index') }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <line x1="19" y1="12" x2="5" y2="12"/>
      <polyline points="12 19 5 12 12 5"/>
    </svg>
    Volver a pacientes
  </a>
</div>
@endif

{{-- Pestañas --}}
<div class="np-tabs rise d1">
  <button class="np-tab active" data-tab="pacientes">Pacientes</button>
  <button class="np-tab hidden np-tab-extra" data-tab="galeria">Galeria</button>
  <button class="np-tab hidden np-tab-extra" data-tab="reportes">Reportes</button>
</div>

{{-- Panel Pacientes --}}
<div class="np-tab-panel active" id="tab-pacientes">

@unless($paciente)
{{-- Buscador de pacientes: solo cuando se abre desde el boton del dashboard --}}
@php
  $npPacientes = ($pacientes ?? collect())->values()->map(function ($p) {
    $nombre = trim($p->nombre_completo ?? 'Paciente sin nombre');
    $partes = preg_split('/\s+/', $nombre);
    $iniciales = count($partes) >= 2
      ? mb_strtoupper(mb_substr($partes[0], 0, 1) . mb_substr($partes[1], 0, 1))
      : mb_strtoupper(mb_substr($nombre, 0, 2));
    return [
      'id' => $p->id,
      'nombre' => $nombre,
      'folio' => $p->folio ?? '',
      'edad' => $p->edad ?? '',
      'sexo' => $p->sexo ? ucfirst($p->sexo) : '',
      'telefono' => $p->telefono ?? '',
      'email' => $p->email ?? '',
      'foto' => $p->foto ? asset('storage/' . $p->foto) : null,
      'iniciales' => $iniciales,
    ];
  });
@endphp
<script>window.__NP_PACIENTES = @json($npPacientes);</script>

<div class="np-searchbar rise d1" id="npSearchBar">
  <div class="np-search-wrap">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" class="np-search" id="npSearch" placeholder="Buscar paciente por nombre, folio, teléfono o correo..." autocomplete="off">
  </div>
</div>

<div class="np-results rise d2" id="npResults">
  <div class="np-results-head">
    <span>Resultados</span>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
  </div>
  <div class="np-res-list" id="npResList"></div>
</div>
@endunless

{{-- Formulario / informacion del paciente --}}
<div class="np-layout" id="npFormLayout" style="display:none">

  <form method="POST" action="#" id="formNuevoPaciente">
    @csrf

    {{-- Card unificada de información del paciente --}}
    <div class="np-card rise d2">
      <div class="np-sec-header">Información del paciente</div>

      <div class="np-personal-layout">

        {{-- Foto --}}
        <div class="np-foto-col">
          @php
            $pacFoto = $paciente && $paciente->foto ? asset('storage/'.$paciente->foto) : '';
          @endphp
          <div class="np-foto-box" id="npFotoBox">
            <img id="npFotoPreview" src="{{ $pacFoto }}" alt="{{ $paciente?->nombre_completo }}" @if($pacFoto) style="display:block;" @endif>
            <div class="np-foto-ph" id="npFotoPh" @if($pacFoto) style="display:none;" @endif>
              <svg width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
          </div>
          <input type="file" id="npFotoInput" accept="image/*" style="display:none">
          <input type="file" id="npFotoCamera" accept="image/*" capture="environment" style="display:none">
        </div>

        {{-- Datos del paciente en recuadros hacia la derecha --}}
        <div class="np-info-grid">
          <div class="np-info-box">
            <label>Nombre completo</label>
            <div class="np-field-value" id="nombre">{{ $paciente?->nombre_completo ?? '—' }}</div>
          </div>
          
                    
          <div class="np-info-box">
            <label>Edad</label>
            <div class="np-field-value" id="edad">{{ $paciente && $paciente->edad ? $paciente->edad.' años' : '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Sexo</label>
            <div class="np-field-value" id="sexo">{{ $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Fecha nacimiento</label>
            <div class="np-field-value" id="fecha_nac">{{ $paciente?->fecha_nacimiento?->format('Y-m-d') ?? '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Peso</label>
            <div class="np-field-value" id="peso">{{ $paciente && $paciente->peso ? $paciente->peso.' kg' : '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Altura</label>
            <div class="np-field-value" id="altura">{{ $paciente && $paciente->altura ? $paciente->altura.' m' : '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Número de Seguro Social</label>
            <div class="np-field-value" id="nss">{{ $paciente?->identificacion ?? '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Teléfono</label>
            <div class="np-field-value" id="telefono">{{ $paciente?->telefono ?? '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Correo electrónico</label>
            <div class="np-field-value" id="email">{{ $paciente?->email ?? '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Procedimiento</label>
            <div class="np-field-value">{{ $paciente?->procedimiento ?? '—' }}</div>
          </div>
          
          <div class="np-info-box">
            <label>Fecha de registro</label>
            <div class="np-field-value" id="fecha_registro">{{ $paciente?->created_at?->format('Y-m-d') ?? '—' }}</div>
          </div>
          
          <div class="np-info-box np-wide">
            <label>Diagnóstico Preliminar</label>
            <div class="np-field-value np-textarea-value" style="min-height:50px; line-height: 1.3;">Define lo que podria tener</div>
          </div>
        </div>

      </div>
    </div>

  </form>

  {{-- Sidebar acciones --}}
  <div class="np-side rise d4">
    <div class="np-action-btns">
      <button class="np-action-btn" type="button" id="btnIniciarGrabacion" onclick="window.openDispositivoModal()">
        <span class="np-ab-icon" style="background:rgba(255,59,59,.12);border-color:rgba(255,90,110,.4)">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ff5a6e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="pointer-events:none"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="#ff5a6e" stroke="none"/></svg>
        </span>
        Iniciar estudio
      </button>
      <a class="np-action-btn" href="{{ route('nuevo-estudio.configuracion') }}">
        <span class="np-ab-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
        </span>
        Configuracion de Grabacion
      </a>
    </div>
  </div>

</div>

</div>

{{-- Panel Galeria --}}
<div class="np-tab-panel" id="tab-galeria">

  <div class="pa-topbar rise d2">
    <button class="np-new-study-btn" type="button" id="npNewStudyBtnGal" style="margin-left:auto">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Agregar nuevo estudio
    </button>
  </div>

  <div class="pa-shell rise d3">
    <div>
      <section class="pa-hero">
        <div class="pa-avatar" id="npGalAvatar">{{ $galIni }}</div>
        <div>
          <div class="pa-title" id="npGalName">{{ $galNombre }}</div>
          <div class="pa-sub" id="npGalMeta">ID: {{ $galCodigo }} · {{ $galSexo }} · {{ $galEdad }} · Último estudio: {{ $galUltimo }}</div>
        </div>
        <div class="pa-stats">
          <div class="pa-stat"><strong id="npGalEstudios">{{ $galEstudios }}</strong><span>Estudios</span></div>
          <div class="pa-stat"><strong id="npGalFotos">{{ $paciente ? $galImagenes->count() : 126 }}</strong><span>Fotos</span></div>
          <div class="pa-stat"><strong id="npGalVideos">{{ $paciente ? $galVideos->count() : 12 }}</strong><span>Videos</span></div>
        </div>
      </section>

      <div class="pa-empty" id="npGalEmpty">No se encontraron archivos para este paciente.</div>

      <section class="pa-section">
        <div class="pa-section-head">
          <h2 class="pa-section-title">Videos</h2>
          <span class="pa-section-count">{{ $paciente ? $galVideos->count().' archivos' : '2 archivos' }}</span>
        </div>
        <div class="pa-grid">
          @if($paciente)
          @forelse($galVideos as $v)
          <article class="pa-card" data-kind="video" data-title="{{ strtolower($v->nombre_original ?? 'video') }}">
            <div class="pa-thumb">
              <video src="{{ asset('storage/'.$v->path) }}" preload="metadata" muted style="width:100%;height:100%;object-fit:cover"></video>
              <span class="pa-badge video">VIDEO</span>
              <div class="pa-play"><span><svg width="17" height="17" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg></span></div>
            </div>
            <div class="pa-body">
              <div class="pa-name">{{ $v->nombre_original ?? 'Video del estudio' }}</div>
              <div class="pa-meta">Estudio {{ $v->estudio?->folio }}<br>{{ optional($v->capturado_en)->format('d/m/Y H:i') }}</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ asset('storage/'.$v->path) }}" target="_blank">Ver</a>
              </div>
            </div>
          </article>
          @empty
          <p style="color:var(--txt-soft);font-size:13px">No hay videos para este paciente.</p>
          @endforelse
          @else
          <article class="pa-card" data-kind="video" data-title="video edd-2025-001245 endoscopia digestiva alta">
            <div class="pa-thumb">
              <span class="pa-badge video">VIDEO</span>
              <div class="pa-play"><span><svg width="17" height="17" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg></span></div>
              <span class="pa-duration">00:15:42</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">Video EDD-2025-001245</div>
              <div class="pa-meta">Endoscopia Digestiva Alta<br>15/07/2025</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.video', 1) }}">Ver</a>
                <a class="pa-btn" href="{{ route('galeria.video.editar', 1) }}">Editar</a>
              </div>
            </div>
          </article>
          @endif
        </div>
      </section>

      <section class="pa-section">
        <div class="pa-section-head">
          <h2 class="pa-section-title">Imagenes</h2>
          <span class="pa-section-count">{{ $paciente ? $galImagenes->count().' archivos' : '4 archivos' }}</span>
        </div>
        <div class="pa-grid">
          @if($paciente)
          @forelse($galImagenes as $img)
          <article class="pa-card" data-kind="imagen" data-title="{{ strtolower($img->nombre_original ?? 'imagen') }}">
            <div class="pa-thumb">
              <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->nombre_original ?? 'Captura' }}">
              <span class="pa-badge image">IMG</span>
              <span class="pa-duration">{{ optional($img->capturado_en)->format('H:i') }}</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">{{ $img->nombre_original ?? 'Captura' }}</div>
              <div class="pa-meta">Captura del estudio {{ $img->estudio?->folio }}<br>{{ optional($img->capturado_en)->format('d/m/Y') }}</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.imagen', $img->id) }}">Ver imagen</a>
              </div>
            </div>
          </article>
          @empty
          <p style="color:var(--txt-soft);font-size:13px">No hay imágenes capturadas para este paciente.</p>
          @endforelse
          @else
          <article class="pa-card" data-kind="imagen" data-title="imagen 1 fotograma 0:01:25">
            <div class="pa-thumb">
              <img src="{{ asset('images/colonoscopia.jpg') }}" alt="Imagen 1">
              <span class="pa-badge image">IMG</span>
              <span class="pa-duration">0:01:25</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">Imagen 1 - Fotograma 0:01:25</div>
              <div class="pa-meta">Captura del estudio<br>15/07/2025</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.imagen', 1) }}">Ver imagen</a>
              </div>
            </div>
          </article>
          @endif
        </div>
      </section>
    </div>

    <aside class="pa-side">
      <section class="pa-panel">
        <h3 class="pa-panel-title">Informacion del paciente</h3>
        <div class="pa-info">
          <div class="pa-info-row"><span>ID</span><strong id="npGalSideId">{{ $galCodigo }}</strong></div>
          <div class="pa-info-row"><span>Sexo</span><strong id="npGalSideSexo">{{ $galSexo }}</strong></div>
          <div class="pa-info-row"><span>Edad</span><strong id="npGalSideEdad">{{ $galEdad }}</strong></div>
          <div class="pa-info-row"><span>Estado</span><strong id="npGalSideEstado" style="color:var(--green)">Activo</strong></div>
          <div class="pa-info-row"><span>Ultimo estudio</span><strong id="npGalSideUltimo">{{ $galUltimo }}</strong></div>
        </div>
      </section>

      <section class="pa-panel">
        <h3 class="pa-panel-title">Etiquetas frecuentes</h3>
        <div class="pa-tag-list">
          <span class="pa-tag">Estomago</span>
          <span class="pa-tag">Antro</span>
          <span class="pa-tag">Gastritis</span>
          <span class="pa-tag">Duodeno</span>
        </div>
      </section>
    </aside>
  </div>
</div>

{{-- Panel Reportes --}}
<div class="np-tab-panel" id="tab-reportes">

  @php
    $rptList = $reportes ?? collect();
    $rpt = $rptList->first();
    $rptNombre = $paciente?->nombre_completo ?? $rpt?->estudio?->paciente_nombre ?? '—';
    $rptIni = collect(explode(' ', $rptNombre))->filter()->take(2)->map(fn($x) => mb_strtoupper(mb_substr($x, 0, 1)))->implode('') ?: 'NA';
    $rptIdent = $paciente?->identificacion ?? $paciente?->folio ?? '—';
    $rptCritico = $rpt ? (bool) $rpt->contiene_hallazgos_criticos : false;
  @endphp

  @if($rpt)
  {{-- Barra de acciones del reporte --}}
  <div class="rpt-toolbar rise d1">
    <div class="rpt-toolbar-left">
      <div class="rpt-pat-chip">
        <div class="rpt-pat-av" id="rptPatAv">{{ $rptIni }}</div>
        <div>
          <div class="rpt-pat-name" id="rptPatName">{{ $rptNombre }}</div>
          <div class="rpt-pat-id" id="rptPatId">ID: {{ $rptIdent }}</div>
        </div>
      </div>
      <span class="rpt-badge" id="rptBadge">{{ $rptCritico ? 'Crítico' : 'Normal' }}</span>
    </div>
    <div class="rpt-toolbar-right">
      <button class="rpt-act-btn" onclick="window.print()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Imprimir
      </button>
      <button class="rpt-act-btn primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Descargar PDF
      </button>
      <a class="rpt-act-btn accent" href="{{ url('/ia-reportes') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
        Editar con IA
      </a>
    </div>
  </div>

  {{-- Documento del reporte (mismo formato que el editor / reporte real) --}}
  @php
    $rptImgs = ($galImagenes ?? collect())->where('estudio_id', $rpt->estudio_id)->take(8)->values();
    $rptFirma = $rpt->usuario?->name ?? $rpt->estudio?->medico ?? $paciente?->medico ?? 'Dr. Nombre del médico';
    $rptFechaEstudio = optional($rpt->estudio?->fecha)->format('d/m/Y') ?? $rpt->created_at?->format('d/m/Y') ?? '';
    $rptNac = optional($paciente?->fecha_nacimiento)->format('d/m/Y') ?? '';
  @endphp
  <div class="rpt-doc-wrap rise d2">
    <div class="rptd-doc" id="rptDoc">

      {{-- Encabezado: logo + clínica + ilustración (igual que el editor) --}}
      <div class="rptd-header">
        <div class="rptd-logo">Logo de<br>la clínica</div>
        <div class="rptd-clinic">Nombre de la clínica</div>
        <div class="rptd-anat" aria-hidden="true">
          <svg viewBox="0 0 80 110" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M30 8c-6 6-10 14-10 22 0 6 2 11 6 16 4 5 6 9 6 15 0 10-8 14-8 24 0 8 6 13 14 13s14-6 14-15c0-12-12-16-12-26 0-7 5-11 9-17 3-5 5-10 5-16C58 22 50 12 42 8"/><path d="M30 8c4-3 8-3 12 0"/></svg>
        </div>
      </div>

      {{-- Datos del paciente / estudio --}}
      <div class="rptd-meta">
        <span class="k">Paciente:</span><span>{{ $rptNombre }}</span>
        <span class="k">Edad:</span><span>{{ $paciente && $paciente->edad ? $paciente->edad.' años' : '—' }}</span>
        <span class="k">Sexo:</span><span>{{ $paciente && $paciente->sexo ? ucfirst($paciente->sexo) : '—' }}</span>
        <span class="k">Fecha de Nac.:</span><span>{{ $rptNac ?: '—' }}</span>
        <span class="k">Fecha del Estudio:</span><span>{{ $rptFechaEstudio ?: '—' }}</span>
        <span class="k">Procedimiento:</span><span>{{ $rpt->estudio?->tipo ?? $paciente?->procedimiento ?? '—' }}</span>
      </div>

      {{-- Imágenes reales del estudio --}}
      @if($rptImgs->count())
      <div class="rptd-imgs">
        @foreach($rptImgs as $img)
          <span class="cell"><img src="{{ asset('storage/'.$img->path) }}" alt="Imagen del estudio"></span>
        @endforeach
      </div>
      @endif

      {{-- Contenido del reporte (texto guardado, conservando su formato) --}}
      <div class="rptd-h4">Contenido del Reporte</div>
      <div class="rptd-body" id="rptHallazgos">{{ $rpt->contenido_texto ?: 'Sin contenido registrado.' }}</div>

      {{-- Firma --}}
      <div class="rptd-sign" data-pos="center">
        <div class="sign-box" id="rptFirmaNombre">{{ $rptFirma }}</div>
      </div>

    </div>
  </div>
  @else
  {{-- Estado vacío: el paciente no tiene reportes --}}
  <div class="rpt-empty rise d2" style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:14px;padding:60px 24px;border:1px dashed var(--stroke);border-radius:var(--r-lg);background:var(--panel)">
    <div style="width:64px;height:64px;border-radius:50%;display:grid;place-items:center;background:rgba(46,123,246,.1);border:1px solid var(--stroke-strong);color:var(--blue)">
      <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    </div>
    <div>
      <div style="font-size:16px;font-weight:700;color:var(--txt);margin-bottom:4px">Este paciente no tiene reportes</div>
      <div style="font-size:13.5px;color:var(--txt-soft)">Genera un reporte clínico para este paciente.</div>
    </div>
    <a class="rpt-act-btn primary" href="{{ route('ia-reportes.redactar', ['paciente' => $paciente?->id]) }}" style="text-decoration:none">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Agregar reporte
    </a>
  </div>
  @endif

</div>

{{-- Modal Nuevo Estudio --}}
<div class="ns-modal-backdrop" id="nsModalBackdrop">
  <div class="ns-modal" id="nsModal">
    <div class="ns-modal-header">
      <div>
        <div class="ns-modal-title">Nuevo estudio</div>
        <div class="ns-modal-subtitle">Crea un estudio a partir de material ya capturado</div>
      </div>
      <button class="ns-modal-close" type="button" id="nsModalClose" aria-label="Cerrar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="ns-modal-body">
      <a class="ns-option" href="{{ route('nuevo-estudio.importar') }}">
        <div class="ns-option-icon purple">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        </div>
        <div class="ns-option-info">
          <div class="ns-option-title">Estudio con capturas</div>
          <div class="ns-option-desc">Sube imagenes o videos ya capturados para formar un estudio sin grabar.</div>
        </div>
        <svg class="ns-option-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
      </a>
    </div>
  </div>
</div>

{{-- Modal de verificación de dispositivo --}}
<div class="ns-modal-backdrop" id="dispositivoModalBackdrop">
  <div class="ns-modal" style="max-width:420px">
    <div class="ns-modal-header">
      <div>
        <div class="ns-modal-title">Verificar dispositivo</div>
        <div class="ns-modal-subtitle">Confirma que el dispositivo de grabación está conectado</div>
      </div>
      <button class="ns-modal-close" type="button" id="dispositivoModalClose" aria-label="Cerrar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="ns-modal-body">
      <div style="margin-bottom:20px">
        <label style="display:block;font-size:12px;font-weight:600;color:var(--txt-soft);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Dispositivo de grabación</label>
        <select id="selDispositivo" style="width:100%;padding:12px 14px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md);font-size:14px;color:var(--txt);outline:none">
          <option value="Endoscopio EG-530" selected>Endoscopio EG-530</option>
          <option value="Endoscopio CF-HQ190L">Endoscopio CF-HQ190L</option>
          <option value="Endoscopio GIF-HQ190">Endoscopio GIF-HQ190</option>
          <option value="Endoscopio Olympus EVIS EXERA III">Endoscopio Olympus EVIS EXERA III</option>
          <option value="Cámara USB HD 1080p">Cámara USB HD 1080p</option>
          <option value="USB Video Device">USB Video Device</option>
        </select>
      </div>
      <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
        <div id="dispositivoStatusIcon" style="width:52px;height:52px;border-radius:14px;background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.3);display:grid;place-items:center;color:#16a34a">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h7l2 3h6c1.1 0 2 .9 2 2v3c0 1.1-.9 2-2 2h-3"/><circle cx="18" cy="16" r="3"/><path d="M18 13v-1"/></svg>
        </div>
        <div>
          <div style="font-size:14px;font-weight:700;color:var(--txt)" id="dispositivoNombre">Endoscopio EG-530</div>
          <div style="font-size:13px;color:var(--txt-soft)" id="dispositivoStatusText">Dispositivo conectado</div>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
        <div style="padding:12px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md)">
          <div style="font-size:11px;color:var(--txt-soft);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Resolución</div>
          <div style="font-size:14px;font-weight:600;color:var(--txt)">1920 x 1080</div>
        </div>
        <div style="padding:12px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md)">
          <div style="font-size:11px;color:var(--txt-soft);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">FPS</div>
          <div style="font-size:14px;font-weight:600;color:var(--txt)">30</div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
        <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--txt);cursor:pointer">
          <input type="checkbox" id="chkDispositivoConectado" checked>
          Sí, el dispositivo está conectado
        </label>
      </div>
      <form method="POST" action="{{ route('nuevo-estudio.store') }}" style="width:100%">
        @csrf
        <input type="hidden" name="paciente_id" value="{{ $paciente?->id }}">
        <input type="hidden" name="tipo" value="{{ $paciente?->procedimiento }}">
        <button type="submit" class="np-new-study-btn" id="btnComenzarGrabar" style="width:100%;justify-content:center;text-decoration:none" @unless($paciente) disabled style="opacity:.5" @endunless>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/></svg>
          Iniciar estudio
        </button>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
@include('estudios.crear.crear-js')
@endpush
