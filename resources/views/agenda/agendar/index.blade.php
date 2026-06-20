@extends('layouts.app')

@section('title', 'Agendar Cita')
@section('active', 'agenda')
@section('header-title', 'Buenos días, Dr. Victor')
@section('header-sub')
  Tiene <b>8</b> pacientes el día de hoy
@endsection

@push('styles')
  @include('agenda.agendar._base')
@endpush

@section('content')

{{-- ===== PARTIALS: HTML + CSS + JS inline ===== --}}
@include('agenda.agendar._paciente')
@include('agenda.agendar._cita')
@include('agenda.agendar._calendario')
@include('agenda.agendar._motivo')
@include('agenda.agendar._confirmacion')

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

  /* ---- Precarga desde query params (Reprogramar) ---- */
  (function prefillFromUrl() {
    const p = new URLSearchParams(window.location.search);
    const paciente = p.get('paciente');
    const proc     = p.get('proc');
    const hora     = p.get('hora');
    const dia      = p.get('dia');
    const mes      = p.get('mes');
    const anio     = p.get('anio');

    if (paciente) {
      const inp = document.getElementById('pacSearch');
      if (inp) {
        inp.value = paciente;
        inp.dispatchEvent(new Event('input'));
      }
      const cfm = document.getElementById('cfmPaciente');
      if (cfm) cfm.textContent = paciente;
      if (window.__findPatient && window.__updatePacResult) {
        const pac = window.__findPatient(paciente);
        if (pac) window.__updatePacResult(pac);
      }
    }
    if (proc) {
      const sel = document.getElementById('citaProcedimiento');
      if (sel) {
        sel.value = proc;
        sel.dispatchEvent(new Event('change'));
      }
    }
    if (hora) {
      const inp = document.getElementById('citaHora');
      if (inp) { inp.value = hora; inp.dispatchEvent(new Event('change')); }
      const cfm = document.getElementById('cfmHora');
      if (cfm) cfm.textContent = hora;
    }
    if (dia && mes && anio) {
      const d = String(dia).padStart(2,'0');
      const m = String(mes).padStart(2,'0');
      const fmtFecha = `${d}/${m}/${anio}`;
      const inp = document.getElementById('citaFecha');
      if (inp) inp.value = fmtFecha;
      const cfm = document.getElementById('cfmFecha');
      if (cfm) cfm.textContent = fmtFecha;
      if (window.__agOnDateSelect) {
        window.__agOnDateSelect(new Date(Number(anio), Number(mes)-1, Number(dia)));
      }
    }
  })();

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
