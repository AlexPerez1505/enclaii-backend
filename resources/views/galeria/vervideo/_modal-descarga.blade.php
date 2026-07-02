{{-- ===== MODAL DESCARGA VIDEO ===== --}}
<div class="vv-dl-overlay" id="vvDlOverlay">
  <div class="vv-dl-modal">
    <div class="vv-dl-hdr">
      <div>
        <div class="vv-dl-title">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Descargar video
        </div>
        <div class="vv-dl-sub">Selecciona las opciones para descargar el video.</div>
      </div>
      <button class="vv-dl-x" id="vvDlClose">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="vv-dl-body">

      {{-- Rango --}}
      <div>
        <div class="vv-dl-sec-lbl">Rango del video</div>
        <div class="vv-rng-list">
          <div class="vv-rng-row sel" data-rng="completo">
            <div class="vv-rng-radio"></div>
            <span class="vv-rng-label">Video completo</span>
            <span class="vv-rng-ts">00:15:42</span>
          </div>
          <div class="vv-rng-row" data-rng="inicio">
            <div class="vv-rng-radio"></div>
            <span class="vv-rng-label">Desde el inicio hasta el momento actual</span>
            <span class="vv-rng-ts">00:02:15</span>
          </div>
          <div class="vv-rng-row" data-rng="custom" id="vvRngCustomRow">
            <div class="vv-rng-radio"></div>
            <span class="vv-rng-label">Rango personalizado</span>
          </div>
        </div>
        <div class="vv-rng-custom" id="vvRngCustom">
          <input class="vv-rng-input" type="text" value="00:02:15" id="vvRngFrom">
          <span class="vv-rng-a">a</span>
          <input class="vv-rng-input" type="text" value="00:08:47" id="vvRngTo">
          <span class="vv-rng-dur">Duración seleccionada: <span id="vvRngDur">00:06:32</span></span>
        </div>
      </div>

      {{-- Calidad --}}
      <div>
        <div class="vv-dl-sec-lbl">Calidad de video</div>
        <select class="vv-dl-qual" id="vvDlQual">
          <option value="1080">Alta (1080p) — Recomendado</option>
          <option value="720">Media (720p)</option>
          <option value="480">Baja (480p)</option>
        </select>
        <div class="vv-dl-qual-res" id="vvQualRes">Resolución: 1920 x 1080</div>
      </div>

      {{-- Formato --}}
      <div>
        <div class="vv-dl-sec-lbl">Formato de archivo</div>
        <div class="vv-fmt-row">
          <div class="vv-fmt-card sel" data-fmt="MP4">
            <div class="vv-fmt-ext">MP4</div>
            <div class="vv-fmt-sub">Video estándar</div>
          </div>
          <div class="vv-fmt-card" data-fmt="MOV">
            <div class="vv-fmt-ext">MOV</div>
            <div class="vv-fmt-sub">Alta compatibilidad</div>
          </div>
          <div class="vv-fmt-card" data-fmt="AVI">
            <div class="vv-fmt-ext">AVI</div>
            <div class="vv-fmt-sub">Formato universal</div>
          </div>
        </div>
      </div>

      {{-- Qué incluir --}}
      <div>
        <div class="vv-dl-sec-lbl">Qué deseas incluir</div>
        <div class="vv-inc-row checked">
          <div class="vv-inc-cb"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <span class="vv-inc-lbl">Incluir audio</span>
        </div>
        <div class="vv-inc-row checked">
          <div class="vv-inc-cb"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <span class="vv-inc-lbl">Incluir información del estudio</span>
        </div>
        <div class="vv-inc-row" id="vvIncMarca">
          <div class="vv-inc-cb"></div>
          <span class="vv-inc-lbl">Marca de agua Enclaii</span>
        </div>
      </div>

    </div>

    <div class="vv-dl-footer">
      <div class="vv-dl-note">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        El video se descargará de forma segura y confidencial.
      </div>
      <div class="vv-dl-footer-btns">
        <button class="vv-dl-cancel" id="vvDlCancel">Cancelar</button>
        <button class="vv-dl-confirm" id="vvDlConfirm">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Descargar video
        </button>
      </div>
    </div>
  </div>
</div>
