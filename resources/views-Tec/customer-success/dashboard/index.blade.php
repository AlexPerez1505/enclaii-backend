@extends('layouts.app')

@section('title', 'Customer Success Dashboard')
@section('active', 'customer-success-dashboard')
@section('header-title', 'Customer Success')
@section('header-sub')
  Panel de control de comunicaciones y gestión de usuarios
@endsection

@section('sidebar')
  @include('customer-success.partials.sidebar')
@endsection

@section('bottom-nav')
  @include('customer-success.partials.bottom-nav')
@endsection

@push('styles')
<style>
.cs-dashboard{display:grid;gap:20px;grid-template-columns:repeat(12,1fr)}
.cs-dashboard > .cs-card{grid-column:span 12}
.cs-dashboard > .cs-card.half{grid-column:span 6}
.cs-stat{display:grid;gap:6px;padding:20px;border:1px solid var(--stroke);border-radius:16px;background:var(--panel-2)}
.cs-stat-value{font-size:28px;font-weight:800;color:var(--txt)}
.cs-stat-label{font-size:12px;color:var(--txt-soft)}
</style>
@endpush

@section('content')
<div class="cs-dashboard">

  <div class="cs-card" style="grid-column:span 12">
    <div class="cs-card-title">Bienvenido al panel de Customer Success</div>
    <p style="margin:0;color:var(--txt-soft);font-size:14px;line-height:1.6">
      Desde aquí puedes gestionar anuncios, administrar usuarios con rol Customer Success y revisar la auditoría de acciones.
    </p>
  </div>

  <div class="cs-card half">
    <div class="cs-card-title">Resumen</div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:14px">
      <div class="cs-stat">
        <div class="cs-stat-value">{{ $anunciosCount }}</div>
        <div class="cs-stat-label">Anuncios</div>
      </div>
      <div class="cs-stat">
        <div class="cs-stat-value">{{ $usuariosCs }}</div>
        <div class="cs-stat-label">Usuarios CS</div>
      </div>
    </div>
  </div>

  <div class="cs-card half">
    <div class="cs-card-title">Accesos directos</div>
    <div style="display:flex;flex-wrap:wrap;gap:10px">
      <a href="{{ route('customer-success.anuncios') }}" class="cs-btn cs-btn-primary">Ver anuncios</a>
      <a href="{{ route('customer-success.anuncios') }}#usuarios" class="cs-btn cs-btn-secondary">Gestionar usuarios</a>
    </div>
  </div>

  <div class="cs-card">
    <div class="cs-card-title" id="auditoria">Últimas acciones de auditoría</div>
    @if($auditLogs->isEmpty())
      <div class="cs-empty">No hay registros de auditoría.</div>
    @else
      <table class="cs-table">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Acción</th>
            <th>IP</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          @foreach($auditLogs as $log)
          <tr>
            <td>{{ $log->user->name ?? '—' }}</td>
            <td>{{ $log->action }}</td>
            <td>{{ $log->ip_address ?? '—' }}</td>
            <td>{{ $log->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

</div>
@endsection
