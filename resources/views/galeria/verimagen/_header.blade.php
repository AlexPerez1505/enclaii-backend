@section('title', 'Ver imagen')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')

@section('header-sub')
  @php
    $patientName = $paciente?->nombre_completo ?? 'Paciente';
    $imageLabel = $archivo?->nombre
      ?? $archivo?->nombre_original
      ?? ('Imagen #'.($id ?? request()->route('id')));
  @endphp

  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <a href="{{ route('galeria.paciente', $pacienteId) }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">{{ $patientName }}</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600">{{ $imageLabel }}</span>
@endsection
