{{-- ===== COLUMNA PRINCIPAL ===== --}}
<div>

  {{-- Player --}}
  <div class="vv-player-box" id="vvPlayer">
    <div class="vv-player-bg"></div>
    <video id="vvVideoEl" class="vv-video-el" src="{{ $videoUrl }}" preload="metadata" playsinline></video>

    {{-- Icono central --}}
    <div class="vv-player-icon" id="vvCenter">
      <div class="vv-play-big" id="vvPlayBig">
        <svg class="play-icon" width="24" height="24" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
        <svg class="pause-icon" width="24" height="24" viewBox="0 0 24 24" fill="white"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
      </div>
      <span id="vvCenterLabel">{{ $tipoEstudio }} · {{ $folioEstudio }}</span>
    </div>

    {{-- Controles --}}
    <div class="vv-controls">
      <div class="vv-prog-wrap" id="vvProgWrap">
        <div class="vv-prog-fill" id="vvProgFill"></div>
        <div class="vv-prog-thumb" id="vvProgThumb"></div>
      </div>
      <div class="vv-ctrl-row">
        <button class="vv-ctrl-btn" id="vvStartBtn" type="button" title="Inicio">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polygon points="19 20 9 12 19 4 19 20"/><line x1="5" y1="19" x2="5" y2="5"/></svg>
        </button>
        <button class="vv-ctrl-btn" id="vvRewindBtn" type="button" title="Retroceder 10s">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/><text x="8" y="14" font-size="5" fill="currentColor" stroke="none" font-weight="700">10</text></svg>
        </button>
        <button class="vv-ctrl-btn" id="vvPlayBtn" type="button" title="Play/Pausa">
          <svg class="play-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          <svg class="pause-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
        </button>
        <button class="vv-ctrl-btn" id="vvForwardBtn" type="button" title="Adelantar 10s">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-4.95"/><text x="8" y="14" font-size="5" fill="currentColor" stroke="none" font-weight="700">10</text></svg>
        </button>
        <span class="vv-time" id="vvTime">00:00 / 00:00</span>
        <div class="vv-vol-wrap">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
          <div class="vv-vol-bar" id="vvVolBar"><div class="vv-vol-fill" id="vvVolFill"></div></div>
        </div>
        <button class="vv-speed" id="vvSpeed" type="button">1.0x</button>
        <button class="vv-ctrl-btn vv-fullscreen" id="vvFullscreenBtn" type="button" title="Pantalla completa">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
        </button>
      </div>
    </div>
  </div>

  {{-- Acciones --}}
  <div class="vv-actions">
    <button class="vv-act-btn" id="vvExportBtn" type="button">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
      Exportar video
    </button>
    <a class="vv-act-btn wa" href="{{ route('mensajes', [
      'canal' => 'whatsapp',
      'paciente' => $nombrePaciente,
      'estudio' => $tipoEstudio,
      'video' => $folioEstudio,
      'fecha' => format_user_date_time($estudio?->fecha ?? $archivo->capturado_en),
      'diagnostico' => $estudio?->diagnostico,
    ]) }}">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
      Enviar por WhatsApp
    </a>
    <a class="vv-act-btn ia" href="{{ route('ia-reportes.generar', ['estudio' => $estudio?->id, 'paciente' => $pacienteId]) }}">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
      IA Reportes
    </a>
  </div>

  {{-- Miniaturas --}}
  <div>
    <div class="vv-caps-title">Imágenes capturadas del estudio</div>
    <div class="vv-caps-strip" id="vvStrip">
      @forelse($capturas as $i => $cap)
        <a class="vv-cap-item {{ $i === 0 ? 'sel' : '' }}" href="{{ route('galeria.imagen', ['id' => $cap->id, 'paciente' => $pacienteId]) }}" data-ts="{{ format_user_time_with_seconds($cap->capturado_en) }}">
          <div class="vv-cap-thumb">
            <img src="{{ media_url($cap->path) }}" alt="{{ $cap->nombre_original ?? 'Captura' }}">
            <span class="vv-cap-num">{{ $i + 1 }}</span>
            <span class="vv-cap-check">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
          </div>
          <div class="vv-cap-ts">{{ format_user_time_with_seconds($cap->capturado_en) }}</div>
        </a>
      @empty
        <div class="vv-empty-caps">Sin imágenes capturadas para este video.</div>
      @endforelse
    </div>
  </div>

</div>
