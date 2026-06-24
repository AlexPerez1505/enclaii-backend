@extends('layouts.app')

@section('title', 'Archivos del paciente')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')
@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600">{{ $paciente?->nombre_completo ?? 'Paciente' }}</span>
@endsection

@push('styles')
<style>
.pa-shell{display:grid;grid-template-columns:1fr;gap:18px;align-items:start}
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
$nombrePaciente = $paciente?->nombre_completo ?? 'Paciente';
$iniciales = collect(explode(' ', $nombrePaciente))->filter()->take(2)->map(fn($p)=>mb_strtoupper(mb_substr($p,0,1)))->implode('') ?: 'PX';
$totalFotos = $imagenes->count();
$totalVideos = $videos->count();
$totalEstudios = $imagenes->pluck('estudio_id')->merge($videos->pluck('estudio_id'))->filter()->unique()->count();
$ultimoArchivo = $imagenes->first() ?? $videos->first();
$ultimaFecha = optional($ultimoArchivo?->capturado_en)->format('d/m/Y') ?? '—';
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
      <div class="pa-avatar">{{ $iniciales }}</div>
      <div>
        <div class="pa-title">{{ $nombrePaciente }}</div>
        <div class="pa-sub">ID: {{ $paciente?->folio ?? $paciente?->identificacion ?? '—' }} · {{ $paciente?->sexo ?? '—' }} · {{ $paciente?->edad ? $paciente->edad.' años' : '—' }} · Último estudio: {{ $ultimaFecha }}</div>
      </div>
      <div class="pa-stats">
        <div class="pa-stat"><strong>{{ $totalEstudios }}</strong><span>Estudios</span></div>
        <div class="pa-stat"><strong>{{ $totalFotos }}</strong><span>Fotos</span></div>
        <div class="pa-stat"><strong>{{ $totalVideos }}</strong><span>Videos</span></div>
      </div>
    </section>

    <div class="pa-empty" id="paEmpty">No se encontraron archivos para este paciente.</div>

    <section class="pa-section">
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
                <a class="pa-btn primary" href="{{ asset('storage/'.$v->path) }}" target="_blank">Ver</a>
              </div>
            </div>
          </article>
        @empty
          <p style="color:var(--txt-soft);font-size:13px">No hay videos para este paciente.</p>
        @endforelse
      </div>
    </section>

    <section class="pa-section">
      <div class="pa-section-head">
        <h2 class="pa-section-title">Imágenes</h2>
        <span class="pa-section-count">{{ count($imagenes) }} archivos</span>
      </div>
      <div class="pa-grid">
        @forelse($imagenes as $img)
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
              </div>
            </div>
          </article>
        @empty
          <p style="color:var(--txt-soft);font-size:13px">No hay imágenes capturadas para este paciente.</p>
        @endforelse
      </div>
    </section>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const search = document.getElementById('paSearch');
  const cards = [...document.querySelectorAll('.pa-card')];
  const empty = document.getElementById('paEmpty');

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
})();
</script>
@endpush
