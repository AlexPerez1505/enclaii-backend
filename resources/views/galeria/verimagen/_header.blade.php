@section('title', 'Ver Imagen')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')
@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  @if(isset($paciente) && $paciente)
  <a href="{{ route('galeria.paciente', $paciente->id) }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">{{ $paciente->nombre_completo }}</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  @endif
  <span style="font-size:13px;font-weight:600" id="viHeaderLabel">Imagen {{ (isset($current) ? $current + 1 : 1) }}</span>
@endsection
