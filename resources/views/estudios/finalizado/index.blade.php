@extends('layouts.app')

@section('title', 'Estudio Finalizado')
@section('active', 'nuevo-estudio')

@php
  $studioUserName     = auth()->check() ? trim(auth()->user()->name ?? 'Doctor') : 'Doctor';
  $studioUserParts    = preg_split('/\s+/', $studioUserName);
  $studioUserInitials = collect($studioUserParts)->take(2)->map(fn($p) => mb_substr($p, 0, 1))->join('');
  $studioUserInitials = mb_strtoupper($studioUserInitials ?: mb_substr($studioUserName, 0, 2));
  $pacienteNombre     = $estudio?->paciente?->nombre_completo ?? $estudio?->paciente_nombre ?? 'Sin paciente';
  $galeria_url        = $estudio?->paciente_id
    ? route('galeria.paciente', $estudio->paciente_id)
    : route('galeria');
@endphp

@push('styles')
@include('estudios.grabando.grabando-css')
@endpush

@section('content')

<div class="studio-finalizado-wrap active" id="studioFinalizado">

  {{-- Header --}}
  <div class="studio-finalizado-header">
    <div class="studio-finalizado-status">
      <div class="studio-status-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div>
        <div class="studio-status-text">Estudio terminado</div>
        <div class="studio-status-sub">La grabación ha finalizado correctamente</div>
      </div>
    </div>
    <div class="studio-final-right">
      <button class="studio-theme-btn" type="button" aria-label="Cambiar tema">
        <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg>
        <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
      <button class="studio-final-btn-notif">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span class="studio-notif-badge">3</span>
      </button>
      <div class="studio-final-profile">
        <div class="studio-doc-avatar">{{ $studioUserInitials }}</div>
        <div class="studio-doc-info">
          <div class="studio-doc-name">{{ $studioUserName }}</div>
          <div class="studio-doc-role">Endoscopista</div>
        </div>
      </div>
      <div class="studio-final-sep-v"></div>
      <a class="studio-btn-volver" href="{{ route('nuevo-estudio') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver
      </a>
    </div>
  </div>

  {{-- Main --}}
  <div class="studio-finalizado-main">

    {{-- Columna izquierda --}}
    <div style="display:flex;flex-direction:column;gap:16px;overflow:hidden">

      {{-- Video Player --}}
      <div class="sf-video-player" style="flex:1">
        <div class="sf-video-bg"></div>
        <video id="sfVideoEl" style="position:absolute;inset:0;width:100%;height:100%;object-fit:contain;background:#000;z-index:1;display:block" preload="metadata"@if($estudio?->video_path) src="{{ media_url($estudio->video_path) }}"@endif></video>
        <img id="sfMainImg" alt="Imagen capturada" style="display:none;position:absolute;inset:0;width:100%;height:100%;object-fit:contain;background:#000;z-index:2">
        <div class="sf-video-center" id="sfVideoCenter">
          <button class="sf-play-big" id="sfPlayBigFinal">
            <svg class="play-icon" width="20" height="20" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <svg class="pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="white" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
          </button>
        </div>
        <div class="sf-video-controls">
          <div class="sf-prog-wrap">
            <div class="sf-prog-fill"></div>
            <div class="sf-prog-thumb"></div>
          </div>
          <div class="sf-ctrl-row">
            <button class="sf-ctrl-btn" id="sfSkipStart"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polygon points="19 20 9 12 19 4 19 20"/><line x1="5" y1="19" x2="5" y2="5"/></svg></button>
            <button class="sf-ctrl-btn" id="sfRewind"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/></svg></button>
            <button class="sf-ctrl-btn" id="sfPlayBtnFinal">
              <svg class="play-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              <svg class="pause-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            </button>
            <button class="sf-ctrl-btn" id="sfForward"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-4.95"/></svg></button>
            <span class="sf-time" id="sfTimeFinal">--:-- / --:--</span>
            <div class="sf-vol-wrap">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
              <div class="sf-vol-bar"><div class="sf-vol-fill"></div></div>
            </div>
            <button class="sf-speed" id="sfSpeedFinal">1.0x</button>
            <button class="sf-ctrl-btn sf-fs" id="btnExpandirFinal"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg></button>
          </div>
        </div>
      </div>

      {{-- Acciones --}}
      <div class="studio-final-actions">
        <a class="studio-final-act-btn guardar" href="{{ $galeria_url }}" style="background:rgba(34,197,94,.14);border-color:rgba(34,197,94,.4);color:#22c55e">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          Ir a galería
        </a>
        <div class="sf-share-wrap" style="position:relative">
          <button class="studio-final-act-btn wa" id="btnCompartir" type="button">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            Compartir
          </button>
          <div class="sf-share-dropdown" id="sfShareDropdown">
            <a class="sf-share-item" href="{{ route('mensajes', ['desde' => 'estudio_terminado', 'estudio_id' => $estudio?->id, 'paciente' => $estudio?->paciente_id, 'estudio' => $estudio?->id]) }}" target="_blank">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              WhatsApp
            </a>
            <a class="sf-share-item" href="mailto:?subject=Estudio+{{ urlencode($estudio?->folio ?? '') }}&body=Adjunto+resultados+del+estudio+{{ urlencode($pacienteNombre) }}.">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              Correo electrónico
            </a>
            @if($estudio?->video_path)
            <a class="sf-share-item" href="{{ media_url($estudio->video_path) }}" download="estudio_{{ $estudio->folio ?? $estudio->id }}.{{ pathinfo($estudio->video_path, PATHINFO_EXTENSION) }}">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Descargar video
            </a>
            @endif
            <a class="sf-share-item" id="sfDownloadImgs" href="#">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              Descargar imágenes
            </a>
          </div>
        </div>
        <a class="studio-final-act-btn" style="background:rgba(56,199,244,.14);border-color:rgba(56,199,244,.4);color:#38c7f4" href="{{ route('ia-reportes.generar', ['estudio' => $estudio?->id]) }}">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="M9 15l3 3 3-3"/></svg>
          Generar reporte IA
        </a>
        <a class="studio-final-act-btn fin" href="{{ route('ia-reportes.redactar', ['paciente' => $estudio?->paciente_id, 'estudio' => $estudio?->id]) }}">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
          Finalizar estudio
        </a>
      </div>

      {{-- Miniaturas --}}
      <div>
        <div class="studio-final-caps-title">Imágenes capturadas del estudio</div>
        <div class="studio-final-caps-strip" id="sfCapsStrip">
          @forelse($capturas as $i => $cap)
          <div class="studio-final-cap-item {{ $i===0 ? 'sel' : '' }}" data-id="{{ $cap->id }}">
            <div class="studio-final-cap-thumb">
              <img src="{{ media_url($cap->path) }}" alt="captura" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
              <span class="studio-final-cap-num">{{ $i+1 }}</span>
              <span class="studio-final-cap-check"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            </div>
            @php
              if (is_numeric($cap->descripcion)) {
                $ts = gmdate('H:i:s', (int)$cap->descripcion);
              } elseif ($cap->capturado_en && $estudio?->hora_inicio) {
                $inicio = \Carbon\Carbon::parse($cap->capturado_en->toDateString().' '.$estudio->hora_inicio);
                $ts = gmdate('H:i:s', max(0, (int)$inicio->diffInSeconds($cap->capturado_en, false)));
              } else {
                $ts = optional($cap->capturado_en)->format('H:i:s') ?? '';
              }
            @endphp
            <div class="studio-final-cap-ts">{{ $ts }}</div>
          </div>
          @empty
          <div style="color:rgba(255,255,255,.4);font-size:13px;padding:8px 4px">No se capturaron fotos en este estudio.</div>
          @endforelse
        </div>
      </div>

    </div>

    {{-- Sidebar derecho --}}
    <div class="studio-resumen-sidebar">
      <div class="studio-resumen-card">
        <div class="studio-resumen-title">Resumen del estudio</div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon studio-icon-paciente" data-paciente="{{ $pacienteNombre }}">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            Estado
          </div>
          <div class="studio-resumen-value green">Completado</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            Duración
          </div>
          <div class="studio-resumen-value">
            @if($estudio?->duracion_segundos){{ gmdate('H:i:s', $estudio->duracion_segundos) }}@else--:--:--@endif
          </div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
            Fecha
          </div>
          <div class="studio-resumen-value">{{ optional($estudio?->fecha)->format('d/m/Y') ?? now()->format('d/m/Y') }}</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            Médico
          </div>
          <div class="studio-resumen-value">{{ $studioUserName }}</div>
        </div>

        <div class="studio-resumen-item">
          <div class="studio-resumen-label">
            <div class="studio-resumen-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
            Fotos Capturadas
          </div>
          <div class="studio-resumen-value">{{ $capturas->count() }}</div>
        </div>

      </div>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
  /* ── Tema ── */
  function updateThemeIcons() {
    const isLight = document.documentElement.dataset.theme === 'light';
    document.querySelectorAll('.studio-theme-btn').forEach(btn => {
      const moon = btn.querySelector('.icon-moon');
      const sun  = btn.querySelector('.icon-sun');
      if (moon) moon.style.display = isLight ? 'block' : 'none';
      if (sun)  sun.style.display  = isLight ? 'none'  : 'block';
    });
  }
  updateThemeIcons();
  document.querySelectorAll('.studio-theme-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const next = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
      document.documentElement.dataset.theme = next;
      localStorage.setItem('enclaii-theme', next);
      updateThemeIcons();
    });
  });

  /* ── Dropdown Compartir ── */
  const btnCompartir    = document.getElementById('btnCompartir');
  const sfShareDropdown = document.getElementById('sfShareDropdown');
  btnCompartir?.addEventListener('click', e => { e.stopPropagation(); sfShareDropdown?.classList.toggle('open'); });
  document.addEventListener('click', () => sfShareDropdown?.classList.remove('open'));

  /* ── Descargar imágenes ── */
  document.getElementById('sfDownloadImgs')?.addEventListener('click', e => {
    e.preventDefault();
    document.querySelectorAll('#sfCapsStrip img').forEach((img, i) => {
      const a = document.createElement('a');
      a.href = img.src; a.download = `captura_${i+1}.jpg`; a.target = '_blank'; a.click();
    });
    sfShareDropdown?.classList.remove('open');
  });

  /* ── Galería: ver imagen en grande ── */
  const sfMainImg     = document.getElementById('sfMainImg');
  const sfVideoCenter = document.getElementById('sfVideoCenter');
  const sfCapsStrip   = document.getElementById('sfCapsStrip');
  const sfPlayer      = document.querySelector('.sf-video-player');

  function showVideo() {
    if (sfMainImg) sfMainImg.style.display = 'none';
    if (sfVideoCenter) sfVideoCenter.style.display = '';
    const ctrl = sfPlayer?.querySelector('.sf-video-controls');
    if (ctrl) ctrl.style.display = '';
    const btn = document.getElementById('sfBtnVolverVideo');
    if (btn) btn.style.display = 'none';
  }

  function showMainImage(url) {
    if (!sfMainImg || !url) return;
    sfMainImg.src = url;
    sfMainImg.style.display = 'block';
    if (sfVideoCenter) sfVideoCenter.style.display = 'none';
    const ctrl = sfPlayer?.querySelector('.sf-video-controls');
    if (ctrl) ctrl.style.display = 'none';
    let btn = document.getElementById('sfBtnVolverVideo');
    if (!btn && sfPlayer) {
      btn = document.createElement('button');
      btn.id = 'sfBtnVolverVideo';
      btn.className = 'sf-btn-volver-video';
      btn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg> Ver video';
      btn.addEventListener('click', showVideo);
      sfPlayer.appendChild(btn);
    }
    if (btn) btn.style.display = 'flex';
  }

  sfCapsStrip?.addEventListener('click', e => {
    const item = e.target.closest('.studio-final-cap-item');
    if (!item) return;
    sfCapsStrip.querySelectorAll('.studio-final-cap-item').forEach(t => t.classList.remove('sel'));
    item.classList.add('sel');
    const img = item.querySelector('img');
    if (img) showMainImage(img.src);
  });

  /* Mostrar primera captura al cargar */
  const firstCap = sfCapsStrip?.querySelector('.studio-final-cap-item');
  if (firstCap) {
    const img = firstCap.querySelector('img');
    if (img) showMainImage(img.src);
  }

  /* ── Video Player ── */
  function formatTime(s) {
    if (!isFinite(s) || isNaN(s)) return '00:00';
    const h = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60), sec = Math.floor(s % 60);
    const mm = String(m).padStart(2,'0'), ss = String(sec).padStart(2,'0');
    return h > 0 ? `${h}:${mm}:${ss}` : `${mm}:${ss}`;
  }

  const videoEl      = document.getElementById('sfVideoEl');
  const playBig      = document.getElementById('sfPlayBigFinal');
  const playBtn      = document.getElementById('sfPlayBtnFinal');
  const timeDisplay  = document.getElementById('sfTimeFinal');
  const speedBtn     = document.getElementById('sfSpeedFinal');
  const fsBtn        = document.getElementById('btnExpandirFinal');
  const skipStart    = document.getElementById('sfSkipStart');
  const rewindBtn    = document.getElementById('sfRewind');
  const forwardBtn   = document.getElementById('sfForward');
  const progressBar  = document.querySelector('.sf-prog-wrap');
  const progressFill = document.querySelector('.sf-prog-fill');
  const progressThumb= document.querySelector('.sf-prog-thumb');
  const volBtn       = document.querySelector('.sf-vol-wrap svg');
  const volFill      = document.querySelector('.sf-vol-fill');

  const speeds = ['0.5x','0.75x','1.0x','1.25x','1.5x','2.0x'];
  let sIdx = 2, isMuted = false;

  function updateProgress() {
    if (!videoEl?.duration) return;
    const pct = (videoEl.currentTime / videoEl.duration) * 100;
    if (progressFill)  progressFill.style.width = pct + '%';
    if (progressThumb) progressThumb.style.left  = pct + '%';
    if (timeDisplay)   timeDisplay.textContent = formatTime(videoEl.currentTime) + ' / ' + formatTime(videoEl.duration);
  }

  function syncIcons(playing) {
    [playBig, playBtn].forEach(b => {
      if (!b) return;
      b.querySelector('.play-icon').style.display  = playing ? 'none' : '';
      b.querySelector('.pause-icon').style.display = playing ? ''     : 'none';
    });
  }

  function togglePlay() {
    if (!videoEl) return;
    if (videoEl.paused) { videoEl.play(); syncIcons(true); }
    else                { videoEl.pause(); syncIcons(false); }
  }

  videoEl?.addEventListener('timeupdate',    updateProgress);
  videoEl?.addEventListener('loadedmetadata',updateProgress);
  videoEl?.addEventListener('ended', () => syncIcons(false));
  playBig?.addEventListener('click',  togglePlay);
  playBtn?.addEventListener('click',  togglePlay);
  skipStart?.addEventListener('click',  () => { if (videoEl) { videoEl.currentTime = 0; updateProgress(); } });
  rewindBtn?.addEventListener('click',  () => { if (videoEl) { videoEl.currentTime = Math.max(0, videoEl.currentTime - 10); updateProgress(); } });
  forwardBtn?.addEventListener('click', () => { if (videoEl) { videoEl.currentTime = Math.min(videoEl.duration || 0, videoEl.currentTime + 10); updateProgress(); } });

  progressBar?.addEventListener('click', e => {
    if (!videoEl?.duration) return;
    const r = progressBar.getBoundingClientRect();
    videoEl.currentTime = ((e.clientX - r.left) / r.width) * videoEl.duration;
  });

  let dragging = false;
  progressThumb?.addEventListener('mousedown', () => dragging = true);
  document.addEventListener('mouseup', () => dragging = false);
  document.addEventListener('mousemove', e => {
    if (!dragging || !videoEl?.duration) return;
    const r = progressBar.getBoundingClientRect();
    videoEl.currentTime = Math.max(0, Math.min(1, (e.clientX - r.left) / r.width)) * videoEl.duration;
  });

  volBtn?.addEventListener('click', () => {
    if (!videoEl) return;
    isMuted = !isMuted; videoEl.muted = isMuted;
    if (volFill) volFill.style.width = isMuted ? '0%' : '70%';
  });

  speedBtn?.addEventListener('click', () => {
    sIdx = (sIdx + 1) % speeds.length;
    speedBtn.textContent = speeds[sIdx];
    if (videoEl) videoEl.playbackRate = parseFloat(speeds[sIdx]);
  });

  fsBtn?.addEventListener('click', () => {
    const player = document.querySelector('.sf-video-player');
    if (!document.fullscreenElement) player?.requestFullscreen().catch(() => {});
    else document.exitFullscreen();
  });

})();
</script>
@endpush
