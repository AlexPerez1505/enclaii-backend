@extends('layouts.app')

@section('title', 'Galería de pacientes')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')

@push('styles')
<style>
/* ===== GALERÍA DE PACIENTES ===== */
.gal-page-title{font-family:'Sora',sans-serif;font-size:22px;font-weight:700;margin-bottom:2px}
.gal-page-sub{font-size:13px;color:var(--txt-soft);margin-bottom:18px}

/* Barra de búsqueda + filtros */
.gal-topbar{display:flex;align-items:center;gap:12px;margin-bottom:24px}
.gal-searchbox{
  flex:1;max-width:520px;height:46px;
  display:flex;align-items:center;gap:10px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-md);padding:0 16px;
  transition:border-color 150ms ease,box-shadow 150ms ease;
}
.gal-searchbox:focus-within{border-color:var(--blue);box-shadow:0 0 0 3px rgba(46,123,246,.14)}
.gal-searchbox svg{color:var(--txt-soft);flex:none}
.gal-searchbox input{
  flex:1;background:none;border:none;outline:none;
  font:inherit;font-size:13.5px;color:var(--txt);
}
.gal-searchbox input::placeholder{color:var(--txt-soft)}
.gal-filter-btn{
  position:relative;
  display:flex;align-items:center;gap:8px;
  height:46px;padding:0 20px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-md);font:inherit;font-size:13.5px;
  font-weight:600;color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease,transform 160ms var(--ease-out);
  white-space:nowrap;
}
.gal-filter-btn:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.gal-filter-btn:hover{background:rgba(110,160,255,.08);border-color:rgba(46,123,246,.4)}}
.gal-filter-btn::after{
  content:attr(data-tooltip);
  position:absolute;left:50%;bottom:calc(100% + 6px);
  transform:translate(-50%,2px);
  width:max-content;max-width:180px;
  padding:5px 8px;border-radius:6px;
  background:var(--panel-2);border:1px solid var(--stroke);
  box-shadow:0 4px 12px rgba(0,0,0,.25);
  color:var(--txt);font-size:11px;font-weight:600;line-height:1.3;
  opacity:0;visibility:hidden;pointer-events:none;
  transition:opacity 150ms ease,transform 150ms ease,visibility 150ms ease;
  z-index:20;text-align:center;
}
.gal-filter-btn::before{
  content:"";
  position:absolute;left:50%;bottom:calc(100% + 1px);
  transform:translate(-50%,2px) rotate(45deg);
  width:6px;height:6px;
  background:var(--panel-2);border-left:1px solid var(--stroke);border-top:1px solid var(--stroke);
  opacity:0;visibility:hidden;pointer-events:none;
  transition:opacity 150ms ease,transform 150ms ease,visibility 150ms ease;
  z-index:21;
}
@media(hover:hover)and(pointer:fine){
  .gal-filter-btn:hover::after,
  .gal-filter-btn:hover::before,
  .gal-filter-btn:focus-visible::after,
  .gal-filter-btn:focus-visible::before{
    opacity:1;visibility:visible;transform:translate(-50%,0);
  }
  .gal-filter-btn:hover::before,
  .gal-filter-btn:focus-visible::before{
    transform:translate(-50%,0) rotate(45deg);
  }
}

/* Grid principal 3 columnas */
.gal-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:16px;
}

/* Tarjeta */
.gal-card{
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:14px;overflow:hidden;cursor:pointer;
  transition:border-color 180ms ease,transform 200ms var(--ease-out),box-shadow 200ms ease;
}
.gal-card:active{transform:scale(.98)}
@media(hover:hover)and(pointer:fine){
  .gal-card:hover{
    border-color:rgba(46,123,246,.5);
    transform:translateY(-3px);
    box-shadow:0 14px 36px -12px rgba(0,0,0,.5);
  }
}

/* Thumbnail */
.gal-thumb{
  position:relative;width:100%;aspect-ratio:16/10;overflow:hidden;
  background:#0a0c1e;
}
.gal-thumb img{width:100%;height:100%;object-fit:cover;display:block;opacity:.85}
.gal-thumb-overlay{
  position:absolute;inset:0;
  background:linear-gradient(180deg,rgba(0,0,0,.18) 0%,rgba(0,0,0,.55) 100%);
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;
}
.gal-protected-icon{
  width:40px;height:40px;border-radius:50%;
  background:rgba(255,255,255,.12);backdrop-filter:blur(6px);
  display:grid;place-items:center;
}
.gal-protected-txt{
  font-size:11px;font-weight:600;color:rgba(255,255,255,.75);text-align:center;line-height:1.4;
}
/* Badge tipo: VID / IMG / US */
.gal-type-badge{
  position:absolute;top:10px;left:10px;
  padding:3px 9px;border-radius:6px;
  font-size:10.5px;font-weight:800;letter-spacing:.05em;
  backdrop-filter:blur(6px);
}
.gal-type-badge.vid{background:rgba(46,123,246,.75);color:#fff}
.gal-type-badge.img{background:rgba(245,158,45,.75);color:#fff}
.gal-type-badge.us {background:rgba(61,220,151,.75);color:#0a1a10}
/* Menú "..." */
.gal-more-btn{
  position:absolute;top:10px;right:10px;
  width:30px;height:30px;border-radius:8px;
  background:rgba(0,0,0,.45);backdrop-filter:blur(6px);
  display:grid;place-items:center;color:rgba(255,255,255,.8);
  font-size:14px;font-weight:900;letter-spacing:1px;
  transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.gal-more-btn:hover{background:rgba(46,123,246,.7)}}
/* Icono play para videos */
.gal-play{
  position:absolute;inset:0;display:grid;place-items:center;
}
.gal-play-btn{
  width:46px;height:46px;border-radius:50%;
  background:rgba(255,255,255,.18);backdrop-filter:blur(8px);
  display:grid;place-items:center;
  transition:background-color 150ms ease,transform 150ms ease;
}
@media(hover:hover)and(pointer:fine){.gal-card:hover .gal-play-btn{background:rgba(46,123,246,.7);transform:scale(1.1)}}
/* Duración en video */
.gal-duration{
  position:absolute;bottom:8px;right:10px;
  font-size:11px;font-weight:700;color:#fff;
  background:rgba(0,0,0,.55);backdrop-filter:blur(4px);
  padding:2px 7px;border-radius:6px;
}

/* Info de la tarjeta */
.gal-card-body{padding:12px 14px 14px}
.gal-card-pac{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:6px;
}
.gal-pac-label{
  display:flex;align-items:center;gap:6px;
  font-size:13px;font-weight:600;
}
.gal-pac-dot{
  width:10px;height:10px;border-radius:50%;
  background:var(--blue);flex:none;
}
.gal-card-date{font-size:11.5px;color:var(--txt-soft)}
.gal-card-meta{
  display:flex;align-items:center;justify-content:space-between;
  font-size:12px;color:var(--txt-soft);
  padding-top:8px;border-top:1px solid var(--stroke);margin-top:8px;
}
.gal-card-tipo{font-weight:600;color:var(--txt)}
.gal-card-doc{color:var(--blue);font-weight:600}

/* Paginación / carga */
.gal-load-more{
  display:flex;justify-content:center;margin-top:24px;
}
.gal-load-btn{
  display:flex;align-items:center;gap:10px;
  height:44px;padding:0 32px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-md);font:inherit;font-size:13.5px;
  font-weight:600;color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.gal-load-btn:hover{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.4);color:var(--blue)}}

/* Responsive grid */
@media(max-width:900px){.gal-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.gal-grid{grid-template-columns:1fr}}


/* ===== PANEL DE FILTROS ===== */
.fil-overlay{
  position:fixed;inset:0;z-index:1000;
  background:rgba(6,8,28,.55);backdrop-filter:blur(4px);
  opacity:0;pointer-events:none;
  transition:opacity 220ms var(--ease-out);
}
.fil-overlay.open{opacity:1;pointer-events:all}

.fil-panel{
  position:fixed;top:0;right:0;bottom:0;z-index:1001;
  width:320px;max-width:92vw;
  background:var(--panel);border-left:1px solid var(--stroke-strong);
  display:flex;flex-direction:column;
  transform:translateX(100%);
  transition:transform 260ms var(--ease-out);
  overflow:hidden;
}
.fil-panel.open{transform:translateX(0)}

.fil-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:20px 20px 16px;
  border-bottom:1px solid var(--stroke);
  flex:none;
}
.fil-title{font-family:'Sora',sans-serif;font-size:16px;font-weight:700}
.fil-close{
  width:32px;height:32px;border-radius:8px;
  border:1px solid var(--stroke);display:grid;place-items:center;
  color:var(--txt-soft);
  transition:background-color 150ms ease,color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.fil-close:hover{background:rgba(110,160,255,.1);color:var(--txt)}}

.fil-body{
  flex:1;overflow-y:auto;padding:16px 20px;
  display:flex;flex-direction:column;gap:14px;
  scrollbar-width:thin;scrollbar-color:var(--stroke) transparent;
}

.fil-group{display:flex;flex-direction:column;gap:6px}
.fil-label{font-size:11.5px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--txt-soft)}
.fil-section-title{font-family:'Sora',sans-serif;font-size:14px;font-weight:700;color:var(--txt)}

.fil-select-wrap{position:relative}
.fil-select{
  width:100%;height:40px;padding:0 36px 0 12px;
  font:inherit;font-size:13px;color:var(--txt);
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-md);outline:none;
  appearance:none;-webkit-appearance:none;cursor:pointer;
  transition:border-color 150ms ease;
}
.fil-select:focus{border-color:var(--blue)}
.fil-select-icon{
  position:absolute;right:12px;top:50%;translate:0 -50%;
  color:var(--txt-soft);pointer-events:none;
}

.fil-date-row{display:flex;align-items:center;gap:8px}
.fil-date-wrap{
  flex:1;position:relative;
  display:flex;align-items:center;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-md);padding:0 10px;
  transition:border-color 150ms ease;
}
.fil-date-wrap:focus-within{border-color:var(--blue)}
.fil-date{
  flex:1;height:40px;background:none;border:none;outline:none;
  font:inherit;font-size:13px;color:var(--txt);
  min-width:0;
}
.fil-date-wrap svg{color:var(--txt-soft);flex:none}
.fil-date-sep{font-size:12px;color:var(--txt-soft);white-space:nowrap}

.fil-checks{display:flex;gap:14px;flex-wrap:wrap}
.fil-check{
  display:flex;align-items:center;gap:6px;
  font-size:13px;color:var(--txt);cursor:pointer;
}
.fil-check input[type=checkbox]{
  width:16px;height:16px;accent-color:var(--blue);cursor:pointer;
}

.fil-divider{height:1px;background:var(--stroke);margin:4px 0}

.fil-upload-btn{
  display:flex;align-items:center;gap:10px;
  height:40px;padding:0 14px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-md);font:inherit;font-size:13px;
  font-weight:600;color:var(--txt);width:100%;
  transition:background-color 150ms ease,border-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.fil-upload-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.4);color:var(--blue)}}

.fil-preview{
  display:flex;flex-wrap:wrap;gap:6px;
  min-height:60px;padding:8px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-md);
}
.fil-preview-empty{width:100%;display:grid;place-items:center;padding:8px 0}
.fil-preview img{
  width:52px;height:52px;object-fit:cover;
  border-radius:8px;border:1px solid var(--stroke);
}
.fil-preview-more{
  width:52px;height:52px;border-radius:8px;
  background:rgba(46,123,246,.15);border:1px solid rgba(46,123,246,.35);
  display:grid;place-items:center;
  font-size:12px;font-weight:700;color:var(--blue);
}
.fil-preview-count{
  font-size:12px;color:var(--txt-soft);margin-top:2px;
}

.fil-footer{
  flex:none;padding:14px 20px;
  border-top:1px solid var(--stroke);
  display:flex;flex-direction:column;gap:8px;
}
.fil-btn-import{
  display:flex;align-items:center;justify-content:center;gap:10px;
  height:44px;border-radius:var(--r-md);
  background:var(--blue);border:none;
  font:inherit;font-size:14px;font-weight:700;
  color:#fff;width:100%;
  transition:opacity 150ms ease,transform 160ms var(--ease-out);
}
.fil-btn-import:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.fil-btn-import:hover{opacity:.88}}
.fil-btn-clear{
  display:flex;align-items:center;justify-content:center;gap:8px;
  height:40px;border-radius:var(--r-md);
  background:transparent;border:1px solid var(--stroke);
  font:inherit;font-size:13.5px;font-weight:600;
  color:var(--txt-soft);width:100%;
  transition:background-color 150ms ease,color 150ms ease,border-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.fil-btn-clear:hover{background:rgba(255,90,110,.1);border-color:rgba(255,90,110,.4);color:var(--red)}}
</style>
@endpush

@section('content')

@php
$estudios = [
  ['tipo'=>'VID','pac'=>'Maria Gonzales', 'fecha'=>'15/07/2025','estudio'=>'EDG Diagnostico','medico'=>'Dr. Victor Morales', 'duracion'=>'05:12','clase'=>'vid'],
  ['tipo'=>'VID','pac'=>'Maria Gonzales', 'fecha'=>'15/07/2025','estudio'=>'EDG Diagnostico','medico'=>'Dr. Victor Morales', 'duracion'=>'02:45','clase'=>'vid'],
  ['tipo'=>'IMG','pac'=>'Jorge Lopez',    'fecha'=>'10/06/2025','estudio'=>'Colonoscopia',   'medico'=>'Dr. Alejandro Ruiz', 'duracion'=>null,   'clase'=>'img'],
  ['tipo'=>'VID','pac'=>'Jorge Lopez',    'fecha'=>'10/06/2025','estudio'=>'Colonoscopia',   'medico'=>'Dr. Alejandro Ruiz', 'duracion'=>'01:08','clase'=>'vid'],
  ['tipo'=>'IMG','pac'=>'Ana Ramirez',    'fecha'=>'22/05/2025','estudio'=>'Gastroscopia',   'medico'=>'Dr. Victor Morales', 'duracion'=>null,   'clase'=>'img'],
  ['tipo'=>'US', 'pac'=>'Ana Ramirez',    'fecha'=>'22/05/2025','estudio'=>'Gastroscopia',   'medico'=>'Dr. Victor Morales', 'duracion'=>null,   'clase'=>'us'],
  ['tipo'=>'IMG','pac'=>'Pedro Torres',   'fecha'=>'03/04/2025','estudio'=>'Biopsia',         'medico'=>'Dr. Alejandro Ruiz', 'duracion'=>null,   'clase'=>'img'],
  ['tipo'=>'IMG','pac'=>'Luis Mendoza',   'fecha'=>'18/03/2025','estudio'=>'EDG Diagnostico','medico'=>'Dr. Victor Morales', 'duracion'=>null,   'clase'=>'img'],
  ['tipo'=>'VID','pac'=>'Carla Ortiz',    'fecha'=>'05/02/2025','estudio'=>'Colonoscopia',   'medico'=>'Dr. Alejandro Ruiz', 'duracion'=>'03:20','clase'=>'vid'],
];
@endphp

<div class="rise d2">
  
  <p class="gal-page-sub">Revisa pacientes</p>

  {{-- Búsqueda + filtros --}}
  <div class="gal-topbar">
    <div class="gal-searchbox">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="galSearch" placeholder="Busca por nombre del paciente">
    </div>
    <button class="gal-filter-btn" id="filtrosBtn" type="button" data-tooltip="Abre los filtros para buscar por paciente, estudio o tipo de archivo">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      Filtros
    </button>
  </div>

  {{-- Grid de estudios --}}
  <div id="galEmpty" style="display:none;padding:48px 0;text-align:center;color:var(--txt-soft)">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" style="opacity:.35;margin-bottom:10px"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <p style="font-size:14px">No se encontraron estudios para <strong id="galEmptyQ"></strong></p>
  </div>
  <div class="gal-grid" id="galGrid">
    @foreach($estudios as $e)
    @if($e['tipo'] === 'VID')
    <a href="{{ route('galeria.video', $loop->iteration) }}"
       class="gal-card rise d{{ min($loop->iteration+1,7) }}"
       style="text-decoration:none;display:block"
       data-pac="{{ strtolower($e['pac']) }}"
       data-estudio="{{ strtolower($e['estudio']) }}"
       data-tipo="{{ $e['tipo'] }}">
    @elseif($e['tipo'] === 'IMG')
    <a href="{{ route('galeria.imagen', $loop->iteration) }}"
       class="gal-card rise d{{ min($loop->iteration+1,7) }}"
       style="text-decoration:none;display:block"
       data-pac="{{ strtolower($e['pac']) }}"
       data-estudio="{{ strtolower($e['estudio']) }}"
       data-tipo="{{ $e['tipo'] }}">
    @else
    <div class="gal-card rise d{{ min($loop->iteration+1,7) }}"
         data-pac="{{ strtolower($e['pac']) }}"
         data-estudio="{{ strtolower($e['estudio']) }}"
         data-tipo="{{ $e['tipo'] }}">
    @endif

      {{-- Thumbnail --}}
      <div class="gal-thumb">
        {{-- Fondo de imagen endoscópica simulado con gradiente de color --}}
        <div style="position:absolute;inset:0;background:
          {{ $loop->iteration % 3 === 0 ? 'radial-gradient(ellipse at 40% 50%,#3a1a0a 0%,#0a0612 60%,#06081c 100%)' :
             ($loop->iteration % 3 === 1 ? 'radial-gradient(ellipse at 55% 45%,#4a1208 0%,#1a0808 50%,#06081c 100%)' :
             'radial-gradient(ellipse at 50% 50%,#1a1a2e 0%,#0a0c24 60%,#06081c 100%)') }};">
        </div>

        <div class="gal-thumb-overlay">
          <div class="gal-protected-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="1.8"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
          </div>
          <div class="gal-protected-txt">Imagen protegida<br>Visible solo para personal autorizado</div>
        </div>

        {{-- Badge tipo --}}
        <span class="gal-type-badge {{ $e['clase'] }}">{{ $e['tipo'] }}</span>

        {{-- Botón "..." --}}
        <button class="gal-more-btn" onclick="event.stopPropagation()">···</button>

        {{-- Play para videos --}}
        @if($e['tipo'] === 'VID')
        <div class="gal-play">
          <div class="gal-play-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </div>
        </div>
        <span class="gal-duration">{{ $e['duracion'] }}</span>
        @endif
      </div>

      {{-- Info --}}
      <div class="gal-card-body">
        <div class="gal-card-pac">
          <div class="gal-pac-label">
            <span class="gal-pac-dot"></span>
            <span><strong>Paciente:</strong> {{ $e['pac'] }}</span>
          </div>
          <span class="gal-card-date">{{ $e['fecha'] }}</span>
        </div>
        <div class="gal-card-meta">
          <span class="gal-card-tipo">{{ $e['estudio'] }}</span>
          <span class="gal-card-doc">{{ $e['medico'] }}</span>
        </div>
      </div>
    @if($e['tipo'] === 'VID' || $e['tipo'] === 'IMG')</a>@else</div>@endif
    @endforeach
  </div>

  <div class="gal-load-more">
    <button class="gal-load-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4"/></svg>
      Cargar más estudios
    </button>
  </div>
</div>

@include('galeria.filtros')

@endsection

@push('scripts')
<script>
(function(){
  /* ── Buscador principal ── */
  const search  = document.getElementById('galSearch');
  const cards   = document.querySelectorAll('.gal-card');
  const empty   = document.getElementById('galEmpty');
  const emptyQ  = document.getElementById('galEmptyQ');
  const grid    = document.getElementById('galGrid');

  function aplicarFiltros(){
    const q       = search.value.trim().toLowerCase();
    const pac     = document.getElementById('filPaciente').value.toLowerCase();
    const estudio = document.getElementById('filEstudio').value.toLowerCase();
    const checks  = [...document.querySelectorAll('.fil-checks input[type=checkbox]')]
                      .filter(cb => cb.value && cb.checked).map(cb => cb.value.toLowerCase());
    let visible = 0;

    cards.forEach(c => {
      const matchQ   = !q       || c.dataset.pac.toLowerCase().includes(q);
      const matchPac = !pac     || c.dataset.pac.toLowerCase().includes(pac);
      const matchEst = !estudio || c.dataset.estudio.toLowerCase().includes(estudio);
      const matchTip = !checks.length || checks.includes(c.dataset.tipo.toLowerCase());
      const ok = matchQ && matchPac && matchEst && matchTip;
      c.style.display = ok ? '' : 'none';
      if(ok) visible++;
    });

    if(visible === 0 && (q || pac || estudio)){
      grid.style.display  = 'none';
      empty.style.display = 'block';
      emptyQ.textContent  = '"' + (search.value.trim() || 'los filtros aplicados') + '"';
    } else {
      grid.style.display  = '';
      empty.style.display = 'none';
    }
  }

  search.addEventListener('input', aplicarFiltros);
  search.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){ this.value = ''; aplicarFiltros(); }
  });

  /* ── Sync dropdown paciente → buscador principal ── */
  document.getElementById('filPaciente').addEventListener('change', function(){
    search.value = this.options[this.selectedIndex].text !== 'Buscar Pacientes'
      ? this.options[this.selectedIndex].text
      : '';
    aplicarFiltros();
  });

  /* ── Panel de filtros: abrir / cerrar ── */
  const panel   = document.getElementById('filPanel');
  const overlay = document.getElementById('filOverlay');

  function abrirFiltros(){
    panel.classList.add('open');
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function cerrarFiltros(){
    panel.classList.remove('open');
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  document.getElementById('filtrosBtn').addEventListener('click', abrirFiltros);
  document.getElementById('filClose').addEventListener('click', cerrarFiltros);
  overlay.addEventListener('click', cerrarFiltros);
  document.addEventListener('keydown', e => { if(e.key === 'Escape') cerrarFiltros(); });

  /* ── Filtros del panel ── */
  document.getElementById('filPaciente').addEventListener('change', aplicarFiltros);
  document.getElementById('filEstudio').addEventListener('change', aplicarFiltros);
  document.querySelectorAll('.fil-checks input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', aplicarFiltros);
  });

  /* ── Limpiar filtros ── */
  document.getElementById('filClear').addEventListener('click', function(){
    document.getElementById('filPaciente').value = '';
    document.getElementById('filEstudio').value  = '';
    document.getElementById('filDesde').value    = '';
    document.getElementById('filHasta').value    = '';
    document.querySelectorAll('.fil-checks input[type=checkbox]').forEach(cb => cb.checked = true);
    search.value = '';
    aplicarFiltros();
  });

  /* ── Vista previa de archivos al importar ── */
  document.getElementById('filUploadBtn').addEventListener('click', function(){
    document.getElementById('filInput').click();
  });
  document.getElementById('filInput').addEventListener('change', function(){
    const files   = [...this.files];
    const preview = document.getElementById('filPreview');
    const count   = document.getElementById('filCount');
    preview.innerHTML = '';
    const max = 3;
    files.slice(0, max).forEach(file => {
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      preview.appendChild(img);
    });
    if(files.length > max){
      const more = document.createElement('div');
      more.className = 'fil-preview-more';
      more.textContent = '+' + (files.length - max);
      preview.appendChild(more);
    }
    count.textContent = files.length ? files.length + ' archivo(s) seleccionado(s)' : '';
  });
})();
</script>
@endpush








