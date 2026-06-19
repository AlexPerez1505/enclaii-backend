@section('title', 'Ver Imagen')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')
@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="color:var(--txt-soft);font-size:13px">Maria Gonzales</span>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <a href="{{ route('galeria.video', 1) }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Video EDD-2025-001245</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600" id="viHeaderLabel">Imagen 4</span>
@endsection
