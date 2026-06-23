@extends('layouts.app')

@section('title', 'Archivos del paciente')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')
@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600">Maria Gonzales</span>
@endsection

@push('styles')
<style>
.pa-shell{display:grid;grid-template-columns:1fr 300px;gap:18px;align-items:start}
.pa-topbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap}
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
@media(hover:hover)and(pointer:fine){.pa-btn:hover{border-color:rgba(46,123,246,.45);color:var(--blue)}}
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
@media(max-width:760px){.pa-grid{grid-template-columns:1fr}.pa-hero{align-items:flex-start}.pa-stats{width:100%;margin-left:0;justify-content:flex-start}.pa-side{display:flex}}
</style>
@endpush

@section('content')
@php
$testImage = asset('images/colonoscopia.jpg');
$videos = [
  ['id'=>1,'titulo'=>'Video EDD-2025-001245','fecha'=>'15/07/2025','duracion'=>'00:15:42','tipo'=>'Endoscopia Digestiva Alta'],
  ['id'=>2,'titulo'=>'Video EDD-2025-001246','fecha'=>'15/07/2025','duracion'=>'00:08:36','tipo'=>'Revisión de antro'],
];
$imagenes = [
  ['id'=>1,'titulo'=>'Imagen 1 - Fotograma 0:01:25','fecha'=>'15/07/2025','hora'=>'0:01:25'],
  ['id'=>2,'titulo'=>'Imagen 2 - Fotograma 0:02:15','fecha'=>'15/07/2025','hora'=>'0:02:15'],
  ['id'=>3,'titulo'=>'Imagen 3 - Fotograma 0:04:32','fecha'=>'15/07/2025','hora'=>'0:04:32'],
  ['id'=>4,'titulo'=>'Imagen 4 - Fotograma 0:06:18','fecha'=>'15/07/2025','hora'=>'0:06:18'],
];
@endphp

<div class="pa-topbar rise d2">
  <a href="{{ route('galeria') }}" class="pa-back">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Volver a pacientes
  </a>
  <label class="pa-search">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" id="paSearch" placeholder="Buscar video o imagen...">
  </label>
</div>

<div class="pa-shell rise d3">
  <div>
    <section class="pa-hero">
      <div class="pa-avatar">MG</div>
      <div>
        <div class="pa-title">Maria Gonzales</div>
        <div class="pa-sub">ID: 00012345 · Femenino · 38 años · Último estudio: 15/07/2025</div>
      </div>
      <div class="pa-stats">
        <div class="pa-stat"><strong>15</strong><span>Estudios</span></div>
        <div class="pa-stat"><strong>126</strong><span>Fotos</span></div>
        <div class="pa-stat"><strong>12</strong><span>Videos</span></div>
      </div>
    </section>

    <div class="pa-empty" id="paEmpty">No se encontraron archivos para este paciente.</div>

    <section class="pa-section">
      <div class="pa-section-head">
        <h2 class="pa-section-title">Videos</h2>
        <span class="pa-section-count">{{ count($videos) }} archivos</span>
      </div>
      <div class="pa-grid">
        @foreach($videos as $v)
          <article class="pa-card" data-kind="video" data-title="{{ strtolower($v['titulo'].' '.$v['tipo']) }}">
            <div class="pa-thumb">
              <span class="pa-badge video">VIDEO</span>
              <div class="pa-play"><span><svg width="17" height="17" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg></span></div>
              <span class="pa-duration">{{ $v['duracion'] }}</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">{{ $v['titulo'] }}</div>
              <div class="pa-meta">{{ $v['tipo'] }}<br>{{ $v['fecha'] }}</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.video', ['id' => $v['id'], 'paciente' => $id]) }}">Ver</a>
                <a class="pa-btn" href="{{ route('galeria.video.editar', ['id' => $v['id'], 'paciente' => $id]) }}">Editar</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </section>

    <section class="pa-section">
      <div class="pa-section-head">
        <h2 class="pa-section-title">Imágenes</h2>
        <span class="pa-section-count" id="paImagesCount">{{ count($imagenes) }} archivos</span>
      </div>
      <div class="pa-grid" id="paImagesGrid">
        @foreach($imagenes as $img)
          <article class="pa-card" data-kind="imagen" data-title="{{ strtolower($img['titulo']) }}">
            <div class="pa-thumb">
              <img src="{{ $testImage }}" alt="{{ $img['titulo'] }}">
              <span class="pa-badge image">IMG</span>
              <span class="pa-duration">{{ $img['hora'] }}</span>
            </div>
            <div class="pa-body">
              <div class="pa-name">{{ $img['titulo'] }}</div>
              <div class="pa-meta">Captura del estudio<br>{{ $img['fecha'] }}</div>
              <div class="pa-actions">
                <a class="pa-btn primary" href="{{ route('galeria.imagen', ['id' => $img['id'], 'paciente' => $id]) }}">Ver imagen</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </section>
  </div>

  <aside class="pa-side">
    <section class="pa-panel">
      <h3 class="pa-panel-title">Información del paciente</h3>
      <div class="pa-info">
        <div class="pa-info-row"><span>ID</span><strong>00012345</strong></div>
        <div class="pa-info-row"><span>Sexo</span><strong>Femenino</strong></div>
        <div class="pa-info-row"><span>Edad</span><strong>38 años</strong></div>
        <div class="pa-info-row"><span>Estado</span><strong style="color:var(--green)">Activo</strong></div>
        <div class="pa-info-row"><span>Último estudio</span><strong>15/07/2025</strong></div>
      </div>
    </section>

    <section class="pa-panel">
      <h3 class="pa-panel-title">Etiquetas frecuentes</h3>
      <div class="pa-tag-list">
        <span class="pa-tag">Estómago</span>
        <span class="pa-tag">Antro</span>
        <span class="pa-tag">Gastritis</span>
        <span class="pa-tag">Duodeno</span>
      </div>
    </section>
  </aside>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const pacienteId = @json((string) $id);
  const editedImagesKey = `galeria:paciente:${pacienteId}:imagenes-editadas`;
  const search = document.getElementById('paSearch');
  const cards = [...document.querySelectorAll('.pa-card')];
  const empty = document.getElementById('paEmpty');
  const imagesGrid = document.getElementById('paImagesGrid');
  const imagesCount = document.getElementById('paImagesCount');

  function escapeHtml(value){
    return String(value ?? '').replace(/[&<>"']/g, char => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    }[char]));
  }

  function editedImages(){
    try {
      return JSON.parse(localStorage.getItem(editedImagesKey) || '[]');
    } catch (error) {
      return [];
    }
  }

  function renderEditedImages(){
    if(!imagesGrid) return;

    const copies = editedImages();
    copies.forEach((copy, index) => {
      const title = copy.title || `Copia editada ${index + 1}`;
      const article = document.createElement('article');
      article.className = 'pa-card pa-card-copy';
      article.dataset.kind = 'imagen editada copia';
      article.dataset.title = `${title} ${copy.date || ''} ${copy.time || ''}`.toLowerCase();
      article.innerHTML = `
        <div class="pa-thumb">
          <img src="${escapeHtml(copy.src)}" alt="${escapeHtml(title)}">
          <span class="pa-badge image">EDITADA</span>
          <span class="pa-duration">${escapeHtml(copy.time || 'Copia')}</span>
        </div>
        <div class="pa-body">
          <div class="pa-name">${escapeHtml(title)}</div>
          <div class="pa-meta">Copia guardada por edición<br>${escapeHtml(copy.date || '')}</div>
        </div>
      `;
      imagesGrid.prepend(article);
      cards.push(article);
    });

    if(imagesCount){
      const totalImages = {{ count($imagenes) }} + copies.length;
      imagesCount.textContent = totalImages + ' archivos';
    }
  }

  function apply(){
    const q = search.value.trim().toLowerCase();
    let shown = 0;
    cards.forEach(card => {
      const ok = !q || card.dataset.title.includes(q) || card.dataset.kind.includes(q);
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

  renderEditedImages();
})();
</script>
@endpush
