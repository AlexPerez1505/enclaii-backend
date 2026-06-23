@extends('layouts.app')

@section('title', 'Galería de pacientes')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')

@push('styles')
<style>
.gp-shell{max-width:1040px}
.gp-toolbar{display:flex;align-items:center;gap:12px;margin-bottom:16px}
.gp-search{
  flex:1;height:44px;display:flex;align-items:center;gap:10px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:10px;padding:0 14px;
  transition:border-color 150ms ease,box-shadow 150ms ease;
}
.gp-search:focus-within{border-color:var(--blue);box-shadow:0 0 0 3px rgba(46,123,246,.12)}
.gp-search svg{color:var(--txt-soft);flex:none}
.gp-search input{flex:1;min-width:0;border:0;outline:0;background:transparent;color:var(--txt);font:inherit;font-size:13px}
.gp-search input::placeholder{color:var(--txt-soft)}
.gp-filter{
  height:44px;display:flex;align-items:center;gap:8px;padding:0 16px;
  border:1px solid var(--stroke);border-radius:10px;background:var(--panel-2);
  color:var(--txt);font-size:13px;font-weight:700;
}
@media(hover:hover)and(pointer:fine){.gp-filter:hover{border-color:rgba(46,123,246,.42);background:rgba(46,123,246,.08)}}
.gp-filter.on{border-color:rgba(46,123,246,.55);background:rgba(46,123,246,.14);color:var(--blue)}
.gp-list{display:flex;flex-direction:column;gap:12px}
.gp-card{
  display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:14px;
  min-height:92px;padding:16px 18px;background:var(--panel-2);
  border:1px solid var(--stroke);border-radius:10px;
  transition:transform 160ms var(--ease-out),border-color 150ms ease,box-shadow 180ms ease;
}
.gp-card:active{transform:scale(.99)}
@media(hover:hover)and(pointer:fine){
  .gp-card:hover{border-color:rgba(46,123,246,.48);box-shadow:0 12px 30px -18px rgba(46,123,246,.55);transform:translateY(-1px)}
}
.gp-avatar{
  width:48px;height:48px;border-radius:14px;display:grid;place-items:center;
  color:#fff;font-family:'Sora',sans-serif;font-size:14px;font-weight:800;
}
.gp-main{min-width:0}
.gp-name-row{display:flex;align-items:center;gap:10px;margin-bottom:5px}
.gp-name{font-size:15px;font-weight:800;color:var(--txt)}
.gp-status{margin-left:auto;font-size:11px;font-weight:800}
.gp-status.active{color:var(--green)}
.gp-status.inactive{color:var(--orange)}
.gp-id{font-size:12px;color:var(--txt-soft);margin-bottom:7px}
.gp-meta{display:flex;align-items:center;gap:10px;flex-wrap:wrap;font-size:12px;color:var(--txt-soft)}
.gp-dot{width:3px;height:3px;border-radius:50%;background:var(--txt-soft);opacity:.65}
.gp-count strong{color:var(--txt);font-weight:800}
.gp-open{
  width:38px;height:38px;border-radius:10px;border:1px solid var(--stroke);
  display:grid;place-items:center;color:var(--txt-soft);background:var(--card);
  transition:background-color 150ms ease,color 150ms ease,border-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.gp-card:hover .gp-open{color:var(--blue);border-color:rgba(46,123,246,.42);background:rgba(46,123,246,.1)}}
.gp-footer{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:14px;color:var(--txt-soft);font-size:12px}
.gp-pages{display:flex;align-items:center;gap:6px}
.gp-page{min-width:28px;height:28px;border-radius:8px;display:grid;place-items:center;border:1px solid transparent;font-weight:700}
.gp-page.active{border-color:rgba(46,123,246,.45);color:var(--blue);background:rgba(46,123,246,.1)}
.gp-empty{display:none;text-align:center;padding:46px 0;color:var(--txt-soft)}
@media(max-width:720px){
  .gp-toolbar{flex-direction:column;align-items:stretch}
  .gp-filter{justify-content:center}
  .gp-card{grid-template-columns:auto 1fr;align-items:start}
  .gp-open{grid-column:1 / -1;width:100%}
  .gp-status{margin-left:0}
}

/* ================= TEMA CLARO ================= */
html[data-theme="light"] .gp-search:focus-within{box-shadow:0 0 0 3px rgba(46,123,246,.12)}
html[data-theme="light"] .gp-filter:hover{border-color:rgba(46,123,246,.42);background:rgba(46,123,246,.08)}
html[data-theme="light"] .gp-filter.on{border-color:rgba(46,123,246,.55);background:rgba(46,123,246,.14)}
html[data-theme="light"] .gp-card:hover{border-color:rgba(46,123,246,.45);box-shadow:0 12px 30px -18px rgba(46,123,246,.35)}
html[data-theme="light"] .gp-avatar{color:#fff}
html[data-theme="light"] .gp-card:hover .gp-open{color:var(--blue);border-color:rgba(46,123,246,.42);background:rgba(46,123,246,.1)}
html[data-theme="light"] .gp-page.active{border-color:rgba(46,123,246,.45);background:rgba(46,123,246,.1)}
</style>
@endpush

@section('content')
@php
$pacientes = [
  ['id'=>1,'nombre'=>'Maria Gonzales','codigo'=>'00012345','sexo'=>'Femenino','edad'=>'38 años','ultimo'=>'15/07/2025','estudios'=>15,'fotos'=>126,'videos'=>12,'estado'=>'Activo','ini'=>'MG','color'=>'linear-gradient(135deg,#c084fc,#a78bfa)'],
  ['id'=>2,'nombre'=>'Jorge Lopez','codigo'=>'00012346','sexo'=>'Masculino','edad'=>'52 años','ultimo'=>'10/06/2025','estudios'=>8,'fotos'=>74,'videos'=>6,'estado'=>'Activo','ini'=>'JL','color'=>'linear-gradient(135deg,#7dd3fc,#60a5fa)'],
  ['id'=>3,'nombre'=>'Ana Perez','codigo'=>'00012347','sexo'=>'Femenino','edad'=>'45 años','ultimo'=>'06/07/2025','estudios'=>12,'fotos'=>102,'videos'=>9,'estado'=>'Activo','ini'=>'AP','color'=>'linear-gradient(135deg,#f9a8d4,#f472b6)'],
  ['id'=>4,'nombre'=>'Carlos Ruiz','codigo'=>'00012348','sexo'=>'Masculino','edad'=>'60 años','ultimo'=>'22/05/2025','estudios'=>4,'fotos'=>37,'videos'=>3,'estado'=>'Inactivo','ini'=>'CR','color'=>'linear-gradient(135deg,#99f6e4,#6ee7b7)'],
];
@endphp

<div class="gp-shell rise d2">
  <div class="gp-toolbar">
    <label class="gp-search">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="gpSearch" placeholder="Buscar paciente por nombre, ID o teléfono...">
    </label>
    <button class="gp-filter" id="gpFilter" type="button" aria-pressed="false">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      Filtros
    </button>
  </div>

  <div class="gp-empty" id="gpEmpty">No se encontraron pacientes con esa búsqueda.</div>

  <div class="gp-list" id="gpList">
    @foreach($pacientes as $p)
      <a href="{{ route('galeria.paciente', $p['id']) }}"
         class="gp-card"
         data-name="{{ strtolower($p['nombre']) }}"
         data-code="{{ strtolower($p['codigo']) }}"
         data-status="{{ strtolower($p['estado']) }}">
        <div class="gp-avatar" style="background:{{ $p['color'] }}">{{ $p['ini'] }}</div>
        <div class="gp-main">
          <div class="gp-name-row">
            <div class="gp-name">{{ $p['nombre'] }}</div>
            <span class="gp-status {{ $p['estado'] === 'Activo' ? 'active' : 'inactive' }}">• {{ $p['estado'] }}</span>
          </div>
          <div class="gp-id">ID: {{ $p['codigo'] }} <span style="margin:0 8px">•</span> {{ $p['sexo'] }} <span style="margin:0 8px">•</span> {{ $p['edad'] }}</div>
          <div class="gp-meta">
            <span>Último estudio: {{ $p['ultimo'] }}</span>
            <span class="gp-dot"></span>
            <span class="gp-count">Estudios: <strong>{{ $p['estudios'] }}</strong></span>
            <span class="gp-dot"></span>
            <span class="gp-count">Fotos: <strong>{{ $p['fotos'] }}</strong></span>
            <span class="gp-dot"></span>
            <span class="gp-count">Videos: <strong>{{ $p['videos'] }}</strong></span>
          </div>
        </div>
        <span class="gp-open" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
      </a>
    @endforeach
  </div>

  <div class="gp-footer">
    <span>Mostrando 1 a 4 de 120 pacientes</span>
    <div class="gp-pages">
      <span class="gp-page active">1</span>
      <span class="gp-page">2</span>
      <span class="gp-page">3</span>
      <span style="padding:0 4px">...</span>
      <span class="gp-page">30</span>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const search = document.getElementById('gpSearch');
  const cards = [...document.querySelectorAll('.gp-card')];
  const empty = document.getElementById('gpEmpty');
  const filter = document.getElementById('gpFilter');
  let onlyActive = false;

  function apply(){
    const q = search.value.trim().toLowerCase();
    let shown = 0;
    cards.forEach(card => {
      const matchesText = !q || card.dataset.name.includes(q) || card.dataset.code.includes(q);
      const matchesStatus = !onlyActive || card.dataset.status === 'activo';
      const ok = matchesText && matchesStatus;
      card.style.display = ok ? '' : 'none';
      if(ok) shown++;
    });
    empty.style.display = shown ? 'none' : 'block';
  }

  search.addEventListener('input', apply);
  search.addEventListener('keydown', e => {
    if(e.key === 'Escape'){
      search.value = '';
      apply();
    }
  });
  filter.addEventListener('click', function(){
    onlyActive = !onlyActive;
    this.classList.toggle('on', onlyActive);
    this.setAttribute('aria-pressed', onlyActive ? 'true' : 'false');
    apply();
  });
})();
</script>
@endpush
