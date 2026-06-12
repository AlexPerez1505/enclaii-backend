@extends('layouts.app')

@section('title', 'Agendar Cita')
@section('active', 'agenda')
@section('header-title', 'Buenos días, Dr. Victor')
@section('header-sub')
  Tiene <b>8</b> pacientes el día de hoy
@endsection

@push('styles')
  @include('agendar._base')
@endpush

@section('content')

{{-- ===== PARTIALS: HTML + CSS + JS inline ===== --}}
@include('agendar._paciente')
@include('agendar._cita')
@include('agendar._calendario')
@include('agendar._motivo')
@include('agendar._confirmacion')

<div class="ag-wrap" id="agendarWrap">

  {{-- Header --}}
  <div class="ag-header">
    <button class="ag-back" id="agBack" onclick="window.history.back()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Regresar
    </button>
    <h1 class="ag-title">Agendar Nueva CITA</h1>
    <div style="width:80px"></div>
  </div>

  {{-- Fila principal: Paso 1 | Paso 2 | Paso 3 --}}
  <div class="ag-grid-main">
    <div id="colPaciente"></div>
    <div id="colCita"></div>
    <div id="colCalendario"></div>
  </div>

  {{-- Paso 4: Motivo --}}
  <div id="colMotivo"></div>

  {{-- Paso 5: Confirmación + Info --}}
  <div id="colConfirmacion"></div>

</div>

@push('scripts')
<script>
(function(){
  /* Mover los partials a sus columnas */
  document.getElementById('colPaciente')   .appendChild(document.getElementById('stepPaciente'));
  document.getElementById('colCita')       .appendChild(document.getElementById('stepCita'));
  document.getElementById('colCalendario') .appendChild(document.getElementById('stepCalendario'));
  document.getElementById('colMotivo')     .appendChild(document.getElementById('stepMotivo'));
  document.getElementById('colConfirmacion').appendChild(document.getElementById('stepConfirmacion').closest('.ag-grid-confirm'));

  /* Sincronizar selección de fecha/slot con campos de cita */
  window.__agOnDateSelect = function(date) {
    const d = String(date.getDate()).padStart(2,'0');
    const m = String(date.getMonth()+1).padStart(2,'0');
    const y = date.getFullYear();
    document.getElementById('citaFecha').value = `${d}/${m}/${y}`;
    document.getElementById('cfmFecha').textContent  = `${d}/${m}/${y}`;
  };
  window.__agOnSlotSelect = function(slot) {
    document.getElementById('citaHora').value = slot;
    document.getElementById('cfmHora').textContent = slot;
  };

  /* Sincronizar nombre paciente con confirmación */
  document.getElementById('pacSearch').addEventListener('input', function() {
    document.getElementById('cfmPaciente').textContent = this.value || 'Sofía Lozano';
  });

  /* Sincronizar procedimiento con confirmación */
  document.getElementById('citaProcedimiento').addEventListener('change', function() {
    document.getElementById('cfmProcedimiento').textContent = this.value;
  });

  /* Sincronizar sala con confirmación */
  document.getElementById('citaSala').addEventListener('change', function() {
    document.getElementById('cfmSala').textContent = this.value;
  });

  /* Cancelar */
  document.getElementById('cfmCancelar').addEventListener('click', () => window.history.back());

  /* Agendar */
  document.getElementById('cfmAgendar').addEventListener('click', () => {
    alert('¡Cita agendada con éxito!');
  });
})();
</script>
@endpush

@endsection
