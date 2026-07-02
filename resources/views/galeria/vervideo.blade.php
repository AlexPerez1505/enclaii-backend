@extends('layouts.app')

@section('title', 'Ver Video')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')

@php
  $pacienteId = request('paciente', 1);
@endphp

@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <a href="{{ route('galeria.paciente', $pacienteId) }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Maria Gonzales</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600">Video EDD-2025-001245</span>
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
