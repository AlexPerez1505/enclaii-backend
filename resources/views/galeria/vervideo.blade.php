@extends('layouts.app')

@section('title', 'Ver Video')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')

@php
  $pacienteId = $paciente?->id ?? $archivo->paciente_id ?? request('paciente');
  $nombrePaciente = $paciente?->nombre_completo ?? $estudio?->paciente_nombre ?? 'Paciente';
  $folioEstudio = $estudio?->folio ?? ('Video #'.$archivo->id);
  $tituloVideo = $archivo->nombre_original ?? $archivo->nombre ?? 'Video del estudio';
  $tipoEstudio = $estudio?->tipo ?: 'Video del estudio';
  $videoUrl = media_url($archivo->path);
  $videoExtension = strtoupper(pathinfo($archivo->nombre_original ?: $archivo->path, PATHINFO_EXTENSION) ?: 'VIDEO');
  $downloadName = $archivo->nombre_original ?: ('video-'.$archivo->id.'.'.strtolower($videoExtension));
  $editorConfig = array_merge([
    'brillo' => 100,
    'contraste' => 100,
    'saturacion' => 100,
    'nitidez' => 0,
    'zoom' => 100,
    'rotacion' => 0,
    'flip_h' => false,
    'flip_v' => false,
  ], $editorConfig ?? []);
  $videoTags = collect([$archivo->categoria, $tipoEstudio])
    ->merge($estudio?->hallazgos?->pluck('nombre') ?? collect())
    ->filter()
    ->unique()
    ->values();
@endphp

@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  @if($pacienteId)
    <a href="{{ route('galeria.paciente', $pacienteId) }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">{{ $nombrePaciente }}</a>
  @else
    <span style="color:var(--txt-soft);font-size:13px">{{ $nombrePaciente }}</span>
  @endif
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600">{{ $tituloVideo }}</span>
@endsection

@include('galeria.vervideo._styles')
@include('galeria.vervideo._modal-descarga')

@section('content')
  <div class="rise d2">
    @include('galeria.vervideo._acciones')

    <div class="vv-wrap">
      @include('galeria.vervideo._player')
      @include('galeria.vervideo._sidebar')
    </div>
  </div>
@endsection

@push('scripts')
  @include('galeria.vervideo._scripts')
@endpush
