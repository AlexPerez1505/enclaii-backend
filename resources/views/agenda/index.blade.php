@extends('layouts.app')

@section('title', 'Agenda')
@section('active', 'agenda')
@section('header-title', 'Agenda')
@section('header-sub')
  Gestiona tus citas y horarios
@endsection

@section('content')
<div class="agenda-container">
  <h2 style="color:var(--txt);margin-bottom:20px;">Agenda de citas</h2>
  <p style="color:var(--txt-soft);">Aquí podrás ver y gestionar las citas de tus pacientes.</p>
</div>
@endsection

@push('styles')
<style>
.agenda-container{
  background:var(--panel);
  border:1px solid var(--stroke);
  border-radius:14px;
  padding:28px 32px;
}
</style>
@endpush
