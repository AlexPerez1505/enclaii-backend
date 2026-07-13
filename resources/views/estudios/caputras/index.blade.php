@extends('layouts.app')

@section('title', 'Capturas')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Capturas
@endsection

@push('styles')
@include('estudios.caputras.capturas-css')
@endpush

@section('content')

  {{-- Toolbar --}}
  <div class="cap-toolbar rise d1">
    <a class="btn-tool" href="{{ route('nuevo-estudio.crear') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nuevo paciente
    </a>
    <button class="btn-tool">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Buscar paciente
    </button>
    <div class="cap-toolbar-right">
      <a class="btn-regresar" href="{{ route('nuevo-estudio.crear') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Regresar
      </a>
      <button class="btn-filtrar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Filtrar
      </button>
      <button class="btn-agregar-cap" id="btnAgregarCap">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
        Agregar Capturas
      </button>
      <input type="file" id="capInput" accept="image/*" multiple style="display:none">
    </div>
  </div>

  {{-- Encabezado paciente --}}
  <div style="margin-bottom:18px">
    <p class="pac-label">Paciente: Maria Gonzalez</p>
    <h1 class="cap-title">Capturas</h1>
  </div>

  {{-- Layout --}}
  <div class="cap-layout">

    {{-- Lista --}}
    <div class="cap-card rise d2">

      <div class="cap-search-bar">
        <div class="cap-search-box">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="capSearch" placeholder="Buscar Capturas...">
        </div>
        <div class="sort-wrap">
          Ordenar por:
          <div class="sort-select-wrap">
            <select class="sort-select">
              <option>Fecha (más reciente)</option>
              <option>Fecha (más antigua)</option>
              <option>Nombre</option>
            </select>
            <svg class="sort-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>
      </div>

      <div class="cap-list" id="capList">

        @php
        $capturas = [
          ['id'=>1,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva','img'=>1],
          ['id'=>2,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva','img'=>2],
          ['id'=>3,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva','img'=>3],
        ];
        @endphp

        @foreach($capturas as $c)
        <div class="cap-item" data-id="{{ $c['id'] }}"
          data-nombre="{{ $c['nombre'] }}"
          data-fecha="{{ $c['fecha'] }}"
          data-hora="{{ $c['hora'] }}"
          data-estudio="{{ $c['estudio'] }}"
          data-tipo-estudio="{{ $c['tipo_estudio'] }}"
          data-img="{{ $c['img'] }}">
          <div class="cap-thumb">
            <img src="{{ asset('images/captura1.jpg') }}" alt="captura">
          </div>
          <div class="cap-info">
            <div class="cap-nombre">{{ $c['nombre'] }}</div>
            <div class="cap-date">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ $c['fecha'] }} {{ $c['hora'] }}
            </div>
            <div class="cap-tipo">{{ $c['tipo'] }}</div>
          </div>
          <button class="cap-more">⋮</button>
        </div>
        @endforeach

      </div>

      <div class="cap-footer" id="capFooter">Mostrando 3 de 3</div>

    </div>

    {{-- Vista previa --}}
    <div class="cap-preview-card rise d3" id="previewCard">

      <div class="prev-title">Vista Previa</div>

      <div class="prev-img-box">
        <img id="prevImg" src="{{ asset('images/captura1.jpg') }}" alt="Vista previa">
      </div>

      <div>
        <div class="info-section-title">Información de la captura</div>
        <div class="info-row"><span class="lbl">Fecha y hora</span>    <span class="val" id="pifecha">24/05/2026</span></div>
        <div class="info-row"><span class="lbl">Descripción</span>     <span class="val" id="pidesc">Lesion del Esófago</span></div>
        <div class="info-row"><span class="lbl">Estudio</span>         <span class="val" id="piestudio">EST-2024-0587</span></div>
        <div class="info-row"><span class="lbl">Tipo de estudio</span> <span class="val" id="pitipo">Endoscopia digestiva</span></div>
        <div class="info-row"><span class="lbl">Imagen</span>          <span class="val" id="piimagen">1 de 3</span></div>
      </div>

      <div>
        <div class="accs-title">Acciones</div>
        <div class="accs-grid">
          <button class="acc-btn" id="btnEditar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Editar
          </button>
          <button class="acc-btn" id="btnExportar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Exportar
          </button>
          <button class="acc-btn" id="btnImprimir">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Imprimir
          </button>
          <button class="acc-btn danger" id="btnEliminar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            Eliminar
          </button>
        </div>
      </div>

    </div>

  </div>

@endsection

@push('scripts')
@include('estudios.caputras.capturas-js')
@endpush
