@extends('layouts.app')

@section('title', 'Editar Video')
@section('active', 'galeria')
@section('header-title', 'Galería de pacientes')
@section('header-sub')
  <a href="{{ route('galeria') }}" style="color:var(--txt-soft);text-decoration:none;font-size:13px">Galería de pacientes</a>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="color:var(--txt-soft);font-size:13px">Maria Gonzales</span>
  <span style="color:var(--txt-soft);font-size:13px;margin:0 4px">›</span>
  <span style="font-size:13px;font-weight:600">Video EDD-2025-001245</span>
@endsection

@push('styles')
<style>
/* ===== EDITAR VIDEO ===== */
.ev-wrap{display:grid;grid-template-columns:1fr 380px;gap:18px;align-items:start}

/* Topbar */
.ev-topbar{display:flex;align-items:center;justify-content:flex-end;gap:8px;margin-bottom:14px}
.ev-btn{
  display:flex;align-items:center;gap:7px;
  height:38px;padding:0 16px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:600;
  transition:background-color 150ms ease,transform 160ms var(--ease-out);
}
.ev-btn:active{transform:scale(.97)}
.ev-btn.save{background:var(--blue);border:none;color:#fff}
@media(hover:hover)and(pointer:fine){.ev-btn.save:hover{opacity:.88}}
.ev-btn.cancel{background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.ev-btn.cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.ev-btn.more{width:38px;padding:0;justify-content:center;background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.ev-btn.more:hover{background:rgba(110,160,255,.08)}}
/* Player */
.ev-player-box{
  background:#000;border-radius:14px;overflow:hidden;
  position:relative;aspect-ratio:16/9;
}
.ev-player-bg{
  position:absolute;inset:0;
  background:radial-gradient(ellipse at 50% 50%,#5a1a10 0%,#2a0808 40%,#060810 100%);
}
.ev-player-icon{
  position:absolute;inset:0;z-index:2;
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;
}
.ev-play-big{
  width:52px;height:52px;border-radius:50%;
  background:rgba(255,255,255,.18);backdrop-filter:blur(8px);
  display:grid;place-items:center;cursor:pointer;
  transition:background-color 150ms ease,transform 150ms ease;
}
.ev-play-big:hover{background:rgba(46,123,246,.6);transform:scale(1.08)}
.ev-play-big svg.pause-icon{display:none}

/* Controles player */
.ev-controls{
  position:absolute;bottom:0;left:0;right:0;z-index:3;
  padding:28px 14px 12px;
  background:linear-gradient(0deg,rgba(0,0,0,.82) 0%,transparent 100%);
}
.ev-prog-wrap{position:relative;height:4px;background:rgba(255,255,255,.2);border-radius:4px;cursor:pointer;margin-bottom:9px}
.ev-prog-fill{height:100%;background:var(--blue);border-radius:4px;width:15%}
.ev-prog-thumb{
  position:absolute;top:50%;translate:0 -50%;
  width:11px;height:11px;border-radius:50%;background:#fff;
  left:15%;margin-left:-5px;
}
.ev-ctrl-row{display:flex;align-items:center;gap:6px}
.ev-ctrl-btn{
  width:30px;height:30px;border-radius:7px;display:grid;place-items:center;
  color:rgba(255,255,255,.8);flex:none;transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.ev-ctrl-btn:hover{background:rgba(255,255,255,.12)}}
.ev-time{font-size:11.5px;color:rgba(255,255,255,.6);flex:none;margin:0 3px}
.ev-vol-wrap{display:flex;align-items:center;gap:5px;margin-left:auto}
.ev-vol-bar{width:60px;height:4px;background:rgba(255,255,255,.2);border-radius:4px}
.ev-vol-fill{height:100%;background:rgba(255,255,255,.7);border-radius:4px;width:70%}
.ev-speed{font-size:11.5px;font-weight:700;color:rgba(255,255,255,.8);padding:2px 7px;border-radius:6px;border:1px solid rgba(255,255,255,.2);cursor:pointer}
.ev-fs{margin-left:4px}

/* Acciones */
.ev-actions{display:flex;align-items:center;gap:7px;flex-wrap:wrap;padding:10px 0;border-bottom:1px solid var(--stroke);margin-bottom:12px}
.ev-act-btn{
  display:flex;align-items:center;gap:5px;
  height:34px;padding:0 12px;border-radius:var(--r-md);
  font:inherit;font-size:12px;font-weight:600;
  background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease;white-space:nowrap;
}
@media(hover:hover)and(pointer:fine){.ev-act-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.4);color:var(--blue)}}
.ev-act-btn.on{background:rgba(46,123,246,.15);border-color:rgba(46,123,246,.5);color:var(--blue)}
.ev-act-btn.wa{color:var(--green);border-color:rgba(61,220,151,.3);background:rgba(61,220,151,.07)}
.ev-act-btn.ia{color:var(--cyan);border-color:rgba(56,199,244,.3);background:rgba(56,199,244,.07)}

/* Miniaturas */
.ev-caps-title{font-size:13px;font-weight:600;margin-bottom:8px}
.ev-caps-strip{display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;scrollbar-width:thin;scrollbar-color:var(--stroke) transparent}
.ev-cap-item{flex:none;width:90px;cursor:pointer;border-radius:7px;overflow:hidden;border:2px solid transparent;transition:border-color 150ms ease}
.ev-cap-item.sel{border-color:var(--blue)}
.ev-cap-thumb{width:100%;aspect-ratio:4/3;display:grid;place-items:center;position:relative}
.ev-cap-num{position:absolute;top:3px;left:4px;width:17px;height:17px;border-radius:5px;background:rgba(0,0,0,.6);display:grid;place-items:center;font-size:9px;font-weight:700;color:#fff}
.ev-cap-check{position:absolute;top:3px;right:3px;width:17px;height:17px;border-radius:50%;background:var(--blue);display:none;place-items:center}
.ev-cap-item.sel .ev-cap-check{display:grid}
.ev-cap-ts{font-size:9.5px;color:var(--txt-soft);text-align:center;padding:3px 0 1px}

/* Panel derecho */
.ev-panel{display:flex;flex-direction:column;gap:14px}

/* Sección */
.ev-section{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:16px}
.ev-sec-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.ev-sec-title{font-family:'Sora',sans-serif;font-size:13px;font-weight:700}
.ev-sec-more{color:var(--txt-soft);display:flex;gap:3px;font-size:16px;font-weight:900;letter-spacing:1px;cursor:pointer;padding:2px 4px;border-radius:6px;transition:background-color 150ms ease}
@media(hover:hover)and(pointer:fine){.ev-sec-more:hover{background:rgba(110,160,255,.1)}}

/* Herramientas edición video */
.ev-tools-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.ev-tool-btn{
  display:flex;align-items:center;gap:9px;
  height:40px;padding:0 14px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:600;color:var(--txt);
  background:var(--card);border:1px solid var(--stroke);
  transition:background-color 150ms ease,border-color 150ms ease,transform 160ms var(--ease-out);
}
.ev-tool-btn:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.ev-tool-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.4);color:var(--blue)}}

.ev-side-tool{display:none}
.ev-side-tool.open{display:block}
.ev-tool-form{display:flex;flex-direction:column;gap:12px}
.ev-panel-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.ev-panel-btn{
  display:flex;align-items:center;justify-content:center;gap:7px;
  height:38px;padding:0 12px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--card);
  font:inherit;font-size:13px;font-weight:700;color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease,color 150ms ease,transform 160ms var(--ease-out);
}
.ev-panel-btn:active{transform:scale(.97)}
.ev-panel-btn.active{background:rgba(46,123,246,.18);border-color:rgba(46,123,246,.55);color:var(--blue)}
.ev-panel-btn.danger{background:rgba(255,90,110,.14);border-color:rgba(255,90,110,.35);color:var(--red)}
@media(hover:hover)and(pointer:fine){
  .ev-panel-btn:hover{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.4);color:var(--blue)}
  .ev-panel-btn.danger:hover{background:rgba(255,90,110,.22);border-color:rgba(255,90,110,.5);color:var(--red)}
}
.ev-filter-label{font-size:11.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--txt-soft)}
.ev-slider-group{display:flex;flex-direction:column;gap:6px}
.ev-slider-row{display:flex;align-items:center;justify-content:space-between}
.ev-slider-name{font-size:12px;font-weight:700;color:var(--txt-soft)}
.ev-slider-val{font-size:12px;font-weight:800;color:var(--blue)}
.ev-slider{width:100%;height:4px;accent-color:var(--blue);cursor:pointer}

@media(max-width:1100px){.ev-wrap{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

<div class="rise d2">

  {{-- Topbar --}}
  <div class="ev-topbar">
    <button class="ev-btn save" id="evSave">Guardar cambios</button>
    <a href="{{ route('galeria.video', $id) }}" class="ev-btn cancel">Cancelar</a>
    <button class="ev-btn more">···</button>
  </div>

  <div class="ev-wrap">

    {{-- ===== COLUMNA IZQUIERDA ===== --}}
    <div>

      {{-- Player --}}
      <div class="ev-player-box" id="evPlayer">
        <div class="ev-player-bg"></div>
        <div class="ev-player-icon" id="evCenter">
          <div class="ev-play-big" id="evPlayBig">
            <svg class="play-icon" width="20" height="20" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <svg class="pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="white"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
          </div>
        </div>
        <div class="ev-controls">
          <div class="ev-prog-wrap">
            <div class="ev-prog-fill"></div>
            <div class="ev-prog-thumb"></div>
          </div>
          <div class="ev-ctrl-row">
            <button class="ev-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polygon points="19 20 9 12 19 4 19 20"/><line x1="5" y1="19" x2="5" y2="5"/></svg></button>
            <button class="ev-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg></button>
            <button class="ev-ctrl-btn" id="evPlayBtn">
              <svg class="play-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              <svg class="pause-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            </button>
            <button class="ev-ctrl-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-4.95"/></svg></button>
            <span class="ev-time" id="evTime">00:02:15 / 00:15:42</span>
            <div class="ev-vol-wrap">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
              <div class="ev-vol-bar"><div class="ev-vol-fill"></div></div>
            </div>
            <button class="ev-speed" id="evSpeed">1.0x</button>
            <button class="ev-ctrl-btn ev-fs"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg></button>
          </div>
        </div>
      </div>

      {{-- Acciones --}}
      <div class="ev-actions">
        <button class="ev-act-btn"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>Capturar imagen</button>
        <button class="ev-act-btn"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>Exportar video</button>
        <button class="ev-act-btn" id="evToolFiltros"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>Filtros</button>
        <button class="ev-act-btn wa"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>Enviar por WhatsApp</button>
        <button class="ev-act-btn ia"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>IA Reportes</button>
      </div>

      {{-- Miniaturas --}}
      <div>
        <div class="ev-caps-title">Imágenes capturadas del estudio</div>
        <div class="ev-caps-strip">
          @php
          $caps=[['n'=>1,'ts'=>'0:01:25'],['n'=>2,'ts'=>'0:02:15'],['n'=>3,'ts'=>'0:04:32'],['n'=>4,'ts'=>'0:06:18'],['n'=>5,'ts'=>'0:08:47'],['n'=>6,'ts'=>'0:11:03']];
          $bgs=['radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%)','radial-gradient(ellipse at 40% 60%,#4a1a0a 0%,#0c0612 100%)','radial-gradient(ellipse at 60% 40%,#2a1a3a 0%,#060814 100%)','radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%)','radial-gradient(ellipse at 45% 55%,#1a0a2a 0%,#08060e 100%)','radial-gradient(ellipse at 55% 45%,#4a0a0a 0%,#0c0608 100%)'];
          @endphp
          @foreach($caps as $i => $c)
          <div class="ev-cap-item {{ $i===1 ? 'sel' : '' }}" data-ts="{{ $c['ts'] }}">
            <div class="ev-cap-thumb" style="background:{{ $bgs[$i] }}">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <span class="ev-cap-num">{{ $c['n'] }}</span>
              <span class="ev-cap-check"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            </div>
            <div class="ev-cap-ts">{{ $c['ts'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

    </div>

    {{-- ===== PANEL DERECHO ===== --}}
    <div class="ev-panel">

      <div class="ev-section ev-side-tool" id="evFiltersPanel" aria-hidden="true">
        <div class="ev-sec-head">
          <span class="ev-sec-title">Filtros de video</span>
          <span class="ev-sec-more">···</span>
        </div>
        <div class="ev-tool-form">
          <div class="ev-filter-label">Ajustes</div>
          <div class="ev-slider-group">
            <div class="ev-slider-row"><span class="ev-slider-name">Brillo</span><span class="ev-slider-val" id="evBrilloVal">100%</span></div>
            <input type="range" class="ev-slider" id="evBrillo" min="0" max="200" value="100">
          </div>
          <div class="ev-slider-group">
            <div class="ev-slider-row"><span class="ev-slider-name">Contraste</span><span class="ev-slider-val" id="evContrasteVal">100%</span></div>
            <input type="range" class="ev-slider" id="evContraste" min="0" max="200" value="100">
          </div>
          <div class="ev-slider-group">
            <div class="ev-slider-row"><span class="ev-slider-name">Saturación</span><span class="ev-slider-val" id="evSaturacionVal">100%</span></div>
            <input type="range" class="ev-slider" id="evSaturacion" min="0" max="200" value="100">
          </div>
          <div class="ev-panel-actions">
            <button type="button" class="ev-panel-btn active" id="evFilterApply">Aplicar filtros</button>
            <button type="button" class="ev-panel-btn" id="evFilterReset">Restablecer</button>
          </div>
        </div>
      </div>

      {{-- Edición de video --}}
      <div class="ev-section">
        <div class="ev-sec-head">
          <span class="ev-sec-title">Edición de video</span>
          <span class="ev-sec-more">···</span>
        </div>
        <div class="ev-tools-grid">
          <button class="ev-tool-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3z"/><line x1="4" y1="20" x2="4.01" y2="20"/><line x1="16" y1="9" x2="16.01" y2="9"/></svg>Recortar</button>
          <button class="ev-tool-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="18"/><rect x="14" y="3" width="7" height="18"/></svg>Dividir</button>
          <button class="ev-tool-btn" id="evRestablecer"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg>Restablecer</button>
        </div>
      </div>

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
  /* Play / Pausa */
  let playing = false;
  const speeds = ['0.5x','0.75x','1.0x','1.25x','1.5x','2.0x'];
  let sIdx = 2;
  function togglePlay(){
    playing = !playing;
    [document.getElementById('evPlayBig'), document.getElementById('evPlayBtn')].forEach(btn => {
      btn.querySelector('.play-icon').style.display  = playing ? 'none' : '';
      btn.querySelector('.pause-icon').style.display = playing ? ''     : 'none';
    });
  }
  document.getElementById('evPlayBig').addEventListener('click', togglePlay);
  document.getElementById('evPlayBtn').addEventListener('click', togglePlay);

  /* Velocidad */
  document.getElementById('evSpeed').addEventListener('click', function(){
    sIdx = (sIdx + 1) % speeds.length;
    this.textContent = speeds[sIdx];
  });

  /* Filtros */
  const evToolFiltros = document.getElementById('evToolFiltros');
  const evFiltersPanel = document.getElementById('evFiltersPanel');
  const evPlayerBg = document.querySelector('.ev-player-bg');

  function applyEvFilters(){
    const b = document.getElementById('evBrillo').value;
    const c = document.getElementById('evContraste').value;
    const s = document.getElementById('evSaturacion').value;
    evPlayerBg.style.filter = `brightness(${b}%) contrast(${c}%) saturate(${s}%)`;
  }

  evToolFiltros.addEventListener('click', function(){
    const isOpen = evFiltersPanel.classList.toggle('open');
    this.classList.toggle('on', isOpen);
    evFiltersPanel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
  });

  ['evBrillo','evContraste','evSaturacion'].forEach(id => {
    const input = document.getElementById(id);
    const val = document.getElementById(id + 'Val');
    input.addEventListener('input', function(){
      val.textContent = this.value + '%';
      applyEvFilters();
    });
  });
  document.getElementById('evFilterApply').addEventListener('click', function(){
    applyEvFilters();
    this.textContent = 'Aplicado';
    setTimeout(() => { this.textContent = 'Aplicar filtros'; }, 1200);
  });
  document.getElementById('evFilterReset').addEventListener('click', function(){
    ['evBrillo','evContraste','evSaturacion'].forEach(id => {
      document.getElementById(id).value = 100;
      document.getElementById(id + 'Val').textContent = '100%';
    });
    evPlayerBg.style.filter = 'none';
  });

  /* Miniaturas */
  document.querySelectorAll('.ev-cap-item').forEach(item => {
    item.addEventListener('click', function(){
      document.querySelectorAll('.ev-cap-item').forEach(i => i.classList.remove('sel'));
      this.classList.add('sel');
      document.getElementById('evTime').textContent = this.dataset.ts + ' / 00:15:42';
    });
  });

  /* Guardar */
  document.getElementById('evSave').addEventListener('click', function(){
    this.textContent = 'Guardado';
    this.style.background = 'var(--green)';
    setTimeout(() => { this.textContent = 'Guardar cambios'; this.style.background = ''; }, 2000);
  });
})();
</script>
@endpush
