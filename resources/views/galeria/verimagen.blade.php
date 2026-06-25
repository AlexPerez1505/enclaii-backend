@extends('layouts.app')

@include('galeria.verimagen._header')
@include('galeria.verimagen._styles')

@section('content')

@php
// Si no llegan capturas reales (acceso directo), usar datos de muestra.
if (empty($caps)) {
  $testImage = asset('images/colonoscopia.jpg');
  $caps = [
    ['n'=>1,'ts'=>'0:01:25','bg'=>'radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%)','src'=>$testImage],
    ['n'=>2,'ts'=>'0:02:15','bg'=>'radial-gradient(ellipse at 40% 60%,#4a1a0a 0%,#0c0612 100%)','src'=>$testImage],
    ['n'=>3,'ts'=>'0:04:32','bg'=>'radial-gradient(ellipse at 60% 40%,#2a1a3a 0%,#060814 100%)','src'=>$testImage],
    ['n'=>4,'ts'=>'0:06:18','bg'=>'radial-gradient(ellipse at 50% 50%,#5a1810 0%,#0a0610 100%)','src'=>$testImage],
  ];
  $current = 0;
}
$current = $current ?? 0;
@endphp

<div class="rise d2">
  @include('galeria.verimagen._acciones')
  @include('galeria.verimagen._imagen')
</div>

@include('galeria.verimagen._modal_comentarios')
@include('galeria.verimagen._modal_compartir')
@include('galeria.verimagen._modal_descarga')

@endsection

@push('scripts')
@include('galeria.verimagen._scripts')
@endpush
