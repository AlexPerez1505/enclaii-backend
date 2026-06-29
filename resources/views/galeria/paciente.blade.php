@extends('layouts.app')

@section('title', 'Archivos del paciente')
@section('active', 'galeria')
@section('header-title', 'Galer&iacute;a de pacientes')
@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galer&iacute;a de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">&rsaquo;</span>
  <span style="font-size:13px;font-weight:600">{{ $paciente?->nombre_completo ?? 'Paciente' }}</span>
@endsection

@push('styles')
<style>
.pa-shell{display:grid;grid-template-columns:1fr;gap:18px;align-items:start}
.pa-topbar{
  position:relative;z-index:40;display:flex;align-items:center;
  justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;
}
.pa-back{
  height:40px;display:inline-flex;align-items:center;gap:8px;padding:0 16px;
  border:1px solid var(--stroke);border-radius:var(--r-md);
  background:transparent;color:var(--txt-soft);font-size:13px;font-weight:700;
}
@media(hover:hover)and(pointer:fine){.pa-back:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.pa-search{
  width:min(360px,100%);height:40px;display:flex;align-items:center;gap:9px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-md);padding:0 13px;
}
.pa-search svg{color:var(--txt-soft);flex:none}
.pa-search input{flex:1;min-width:0;border:0;outline:0;background:transparent;color:var(--txt);font:inherit;font-size:13px}
.pa-tools{display:flex;align-items:center;gap:10px;margin-left:auto}
.pa-filter-wrap{position:relative}
.pa-filter{
  position:relative;height:40px;display:flex;align-items:center;gap:8px;padding:0 15px;
  border:1px solid var(--stroke);border-radius:var(--r-md);
  background:var(--panel-2);color:var(--blue);font-size:13px;font-weight:700;
}
.pa-filter:hover,.pa-filter.open{border-color:rgba(46,123,246,.55);background:rgba(46,123,246,.09)}
.pa-filter.active::after{
  content:"";position:absolute;top:7px;right:7px;width:7px;height:7px;
  border-radius:50%;background:var(--blue);box-shadow:0 0 7px rgba(46,123,246,.8);
}
.pa-filter-menu{
  position:absolute;z-index:100;top:calc(100% + 8px);right:0;width:170px;padding:7px;
  border:1px solid var(--stroke);border-radius:11px;background:var(--card);
  box-shadow:0 16px 35px rgba(0,0,0,.32);
  opacity:0;visibility:hidden;transform:translateY(-5px);
  transition:opacity .15s ease,transform .15s ease,visibility .15s ease;
}
.pa-filter-menu.open{opacity:1;visibility:visible;transform:translateY(0)}
.pa-filter-option{
  width:100%;padding:9px 10px;border-radius:8px;text-align:left;
  color:var(--txt-soft);font-size:12.5px;font-weight:600;
}
.pa-filter-option:hover{background:rgba(110,160,255,.08);color:var(--txt)}
.pa-filter-option.selected{background:rgba(46,123,246,.13);color:var(--blue)}
.pa-hero{
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-lg);padding:18px;margin-bottom:16px;
  display:flex;align-items:center;gap:16px;
}
.pa-avatar{
  width:58px;height:58px;border-radius:16px;display:grid;place-items:center;
  background:linear-gradient(135deg,#c084fc,#7c3aed);color:#fff;
  font-family:'Sora',sans-serif;font-weight:800;
}
.pa-title{font-family:'Sora',sans-serif;font-size:18px;font-weight:800;margin-bottom:4px}
.pa-sub{font-size:13px;color:var(--txt-soft)}
.pa-stats{margin-left:auto;display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end}
.pa-stat{
  min-width:86px;padding:10px 12px;border-radius:12px;
  background:var(--card);border:1px solid var(--stroke);text-align:center;
}
.pa-stat strong{display:block;font-family:'Sora',sans-serif;font-size:17px}
.pa-stat span{font-size:11.5px;color:var(--txt-soft)}
.pa-section{margin-bottom:18px}
.pa-section-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:10px}
.pa-section-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:800}
.pa-section-count{font-size:12px;color:var(--txt-soft)}
.pa-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.pa-card{
  display:flex;flex-direction:column;overflow:hidden;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:12px;transition:border-color 150ms ease,transform 160ms var(--ease-out);
}
@media(hover:hover)and(pointer:fine){.pa-card:hover{border-color:rgba(46,123,246,.48);transform:translateY(-1px)}}
.pa-thumb{
  position:relative;aspect-ratio:16/10;overflow:hidden;
  background:radial-gradient(ellipse at 50% 50%,#5a1810 0%,#120711 64%,#050712 100%);
}
.pa-thumb img{width:100%;height:100%;object-fit:cover;display:block}
.pa-play{position:absolute;inset:0;display:grid;place-items:center;background:rgba(0,0,0,.18)}
.pa-play span{width:42px;height:42px;border-radius:50%;display:grid;place-items:center;background:rgba(255,255,255,.18);backdrop-filter:blur(8px)}
.pa-badge{
  position:absolute;top:9px;left:9px;padding:3px 8px;border-radius:7px;
  font-size:10.5px;font-weight:900;letter-spacing:.04em;color:#fff;
}
.pa-badge.video{background:rgba(46,123,246,.82)}
.pa-badge.image{background:rgba(245,158,45,.86)}
.pa-duration{
  position:absolute;right:9px;bottom:8px;padding:2px 7px;border-radius:7px;
  background:rgba(0,0,0,.58);color:#fff;font-size:11px;font-weight:800;
}
.pa-body{padding:12px}
.pa-name{font-size:13px;font-weight:800;margin-bottom:5px}
.pa-meta{font-size:12px;color:var(--txt-soft);line-height:1.45}
.pa-actions{display:flex;gap:8px;margin-top:11px}
.pa-btn{
  flex:1;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;gap:6px;
  border:1px solid var(--stroke);font-size:12px;font-weight:800;background:var(--card);color:var(--txt);
}
.pa-btn.primary{background:rgba(46,123,246,.14);border-color:rgba(46,123,246,.35);color:var(--blue)}
.pa-btn.danger{flex:0 0 38px;color:var(--red);border-color:rgba(255,90,110,.3);background:rgba(255,90,110,.07)}
.pa-btn.danger:disabled{opacity:.55;cursor:wait}
@media(hover:hover)and(pointer:fine){.pa-btn:hover{border-color:rgba(46,123,246,.45);color:var(--blue)}}
@media(hover:hover)and(pointer:fine){.pa-btn.danger:hover{border-color:rgba(255,90,110,.55);background:rgba(255,90,110,.13);color:var(--red)}}
.pa-side{display:flex;flex-direction:column;gap:14px}
.pa-panel{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:16px}
.pa-panel-title{font-family:'Sora',sans-serif;font-size:14px;font-weight:800;margin-bottom:12px}
.pa-info{display:grid;gap:10px}
.pa-info-row{display:flex;justify-content:space-between;gap:10px;font-size:13px}
.pa-info-row span{color:var(--txt-soft)}
.pa-info-row strong{text-align:right}
.pa-tag-list{display:flex;flex-wrap:wrap;gap:8px}
.pa-tag{padding:6px 10px;border-radius:999px;border:1px solid var(--stroke);background:var(--card);font-size:12px;font-weight:700}
.pa-empty{display:none;padding:34px 0;text-align:center;color:var(--txt-soft)}
@media(max-width:1120px){.pa-shell{grid-template-columns:1fr}.pa-side{display:grid;grid-template-columns:1fr 1fr}}
@media(max-width:760px){
  .pa-grid{grid-template-columns:1fr}
  .pa-hero{align-items:flex-start}
  .pa-stats{width:100%;margin-left:0;justify-content:flex-start}
  .pa-side{display:flex}
  .pa-topbar{align-items:stretch}
  .pa-tools{width:100%;margin-left:0}
  .pa-search{width:auto;flex:1}
}
</style>
@endpush

@section('content')
@php
$nombrePaciente = $paciente?->nombre_completo ?? 'Paciente';
$iniciales = collect(explode(' ', $nombrePaciente))->filter()->take(2)->map(fn($p)=>mb_strtoupper(mb_substr($p,0,1)))->implode('') ?: 'PX';
$totalFotos = $imagenes->count();
$totalVideos = $videos->count();
$totalEstudios = $imagenes->pluck('estudio_id')->merge($videos->pluck('estudio_id'))->filter()->unique()->count();
$ultimoArchivo = $imagenes->first() ?? $videos->first();
$ultimaFecha = optional($ultimoArchivo?->capturado_en)->format('d/m/Y') ?? '-';
@endphp

<div class="pa-topbar rise d2">
  <a href="{{ route('galeria') }}" class="pa-back">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Volver a pacientes
  </a>
  <div class="pa-tools">
    <label class="pa-search">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="paSearch" placeholder="Buscar video o imagen...">
    </label>
    <div class="pa-filter-wrap" id="paFilterWrap">
      <button class="pa-filter" id="paFilter" type="button" aria-expanded="false" aria-controls="paFilterMenu">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Filtros
      </button>
      <div class="pa-filter-menu" id="paFilterMenu">
        <button class="pa-filter-option selected" type="button" data-kind-filter="">Todos los archivos</button>
        <button class="pa-filter-option" type="button" data-kind-filter="imagen">Imágenes</button>
        <button class="pa-filter-option" type="button" data-kind-filter="video">Videos</button>
      </div>
    </div>
  </div>
</div>

<div class="pa-shell rise d3">
  <div>
    <section class="pa-hero">
      <div class="pa-avatar">{{ $iniciales }}</div>
      <div>
        <div class="pa-title">{{ $nombrePaciente }}</div>
        <div class="pa-sub">ID: {{ $paciente?->folio ?? $paciente?->identificacion ?? '-' }} &middot; {{ $paciente?->sexo ?? '-' }} &middot; {{ $paciente?->edad ? $paciente->edad.' a&ntilde;os' : '-' }} &middot; &Uacute;ltimo estudio: {{ $ultimaFecha }}</div>
      </div>
      <div class="pa-stats">
        <div class="pa-stat"><strong>{{ $totalEstudios }}</strong><span>Estudios</span></div>
        <div class="pa-stat"><strong id="paPhotoStat">{{ $totalFotos }}</strong><span>Fotos</span></div>
        <div class="pa-stat"><strong>{{ $totalVideos }}</strong><span>Videos</span></div>
      </div>
    </section>

    <div class="pa-empty" id="paEmpty">No se encontraron archivos para este paciente.</div>

    <section class="pa-section" data-kind-section="video">
      <div class="pa-section-head">
        <h2 class="pa-section-title">Videos</h2>
        <span class="pa-section-count">{{ count($videos) }} archivos</span>
      </div>
      <div class="pa-grid">
        @forelse($videos as $v)
          <article class="pa-card" data-kind="video" data-title="{{ strtolower($v->nombre_original ?? 'video') }}">
            <div class="pa-thumb">
              <video src="{{ asset('storage/'.$v->path) }}" preload="metadata" muted style="width:100%;height:100%;object-fit:cover"></video>
              <span class="pa-badge video">VIDEO</span>
              <div class="pa-play"><span><svg width="17" height="17" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg></span></div>
            </div>
            <div class="pa-body">
              <div class="pa-name">{{ $v->nombre_original ?? 'Video del estudio' }}</div>
              <div class="pa-meta">Estudio {{ $v->estudio?->folio }}<br>{{ optional($v->capturado_en)->format('d/m/Y H:i') }}</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.video', $v->id) }}">Ver</a>
                <a class="pa-btn" href="{{ route('galeria.video.editar', $v->id) }}">Editar</a>
              </div>
            </div>
          </article>
        @empty
          <p style="color:var(--txt-soft);font-size:13px">No hay videos para este paciente.</p>
        @endforelse
      </div>
    </section>

    <section class="pa-section" data-kind-section="imagen">
      <div class="pa-section-head">
        <h2 class="pa-section-title">Im&aacute;genes</h2>
        <span class="pa-section-count" id="paImagesCount">{{ count($imagenes) }} archivos</span>
      </div>
      <div class="pa-grid" id="paImagesGrid">
        @foreach($imagenes as $img)
          <article class="pa-card" data-kind="imagen" data-title="{{ strtolower($img->nombre_original ?? 'imagen') }}">
            <div class="pa-thumb">
              <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->nombre_original ?? 'Captura' }}">
              <span class="pa-badge image">IMG</span>
              <span class="pa-duration">{{ optional($img->capturado_en)->format('H:i') }}</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">{{ $img->nombre_original ?? 'Captura' }}</div>
              <div class="pa-meta">Captura del estudio {{ $img->estudio?->folio }}<br>{{ optional($img->capturado_en)->format('d/m/Y') }}</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.imagen', $img->id) }}">Ver imagen</a>
                <button
                  class="pa-btn danger pa-delete-image"
                  type="button"
                  data-delete-url="{{ route('galeria.imagen.destroy', $img) }}"
                  data-image-name="{{ $img->nombre_original ?? 'esta imagen' }}"
                  aria-label="Eliminar {{ $img->nombre_original ?? 'imagen' }}"
                  title="Eliminar imagen">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>
        @endforeach
        <p id="paImagesEmpty" style="color:var(--txt-soft);font-size:13px;{{ $imagenes->isNotEmpty() ? 'display:none' : '' }}">No hay im&aacute;genes capturadas para este paciente.</p>
      </div>
    </section>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const search = document.getElementById('paSearch');
  let cards = [...document.querySelectorAll('.pa-card')];
  const empty = document.getElementById('paEmpty');
  const filterWrap = document.getElementById('paFilterWrap');
  const filterButton = document.getElementById('paFilter');
  const filterMenu = document.getElementById('paFilterMenu');
  const filterOptions = [...document.querySelectorAll('[data-kind-filter]')];
  const sections = [...document.querySelectorAll('[data-kind-section]')];
  let kindFilter = '';

  function apply(){
    const q = search.value.trim().toLowerCase();
    let shown = 0;
    cards.forEach(card => {
      const matchesSearch = !q || card.dataset.title.includes(q) || card.dataset.kind.includes(q);
      const matchesKind = !kindFilter || card.dataset.kind === kindFilter;
      const ok = matchesSearch && matchesKind;
      card.style.display = ok ? '' : 'none';
      if(ok) shown++;
    });
    sections.forEach(section => {
      section.style.display = kindFilter && section.dataset.kindSection !== kindFilter ? 'none' : '';
    });
    empty.style.display = shown ? 'none' : 'block';
  }

  function closeFilterMenu(){
    filterMenu.classList.remove('open');
    filterButton.classList.remove('open');
    filterButton.setAttribute('aria-expanded', 'false');
  }

  search.addEventListener('input', apply);
  search.addEventListener('keydown', e => {
    if(e.key === 'Escape'){
      search.value = '';
      apply();
    }
  });
  filterButton.addEventListener('click', event => {
    event.stopPropagation();
    const isOpen = filterMenu.classList.toggle('open');
    filterButton.classList.toggle('open', isOpen);
    filterButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });
  filterOptions.forEach(option => {
    option.addEventListener('click', event => {
      event.stopPropagation();
      kindFilter = option.dataset.kindFilter;
      filterOptions.forEach(item => item.classList.toggle('selected', item === option));
      filterButton.classList.toggle('active', Boolean(kindFilter));
      closeFilterMenu();
      apply();
    });
  });
  document.addEventListener('click', event => {
    if(!filterWrap.contains(event.target)) closeFilterMenu();
  });
  document.addEventListener('keydown', event => {
    if(event.key === 'Escape') closeFilterMenu();
  });
  document.addEventListener('click', async event => {
    const button = event.target.closest('.pa-delete-image');
    if(!button) return;

    const imageName = button.dataset.imageName || 'esta imagen';
    if(!window.confirm(`¿Eliminar "${imageName}"?\n\nEsta acción no se puede deshacer.`)) return;

    button.disabled = true;
    try {
      const response = await fetch(button.dataset.deleteUrl, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': @json(csrf_token()),
          'Accept': 'application/json'
        }
      });
      const payload = await response.json().catch(() => ({}));
      if(!response.ok) throw new Error(payload.message || 'No fue posible eliminar la imagen.');

      const card = button.closest('.pa-card');
      card.remove();
      cards = cards.filter(item => item !== card);

      const imageCount = cards.filter(item => item.dataset.kind === 'imagen').length;
      document.getElementById('paImagesCount').textContent = `${imageCount} ${imageCount === 1 ? 'archivo' : 'archivos'}`;
      document.getElementById('paPhotoStat').textContent = imageCount;
      document.getElementById('paImagesEmpty').style.display = imageCount ? 'none' : '';
      apply();
    } catch(error) {
      button.disabled = false;
      window.alert(error.message);
    }
  });

  apply();
})();
</script>
@endpush
