@extends('layouts.app')

@section('title', 'Anuncios - Customer Success')
@section('active', 'customer-success')
@section('header-title', 'Customer Success')
@section('header-sub')
  Gestión de anuncios y comunicaciones
@endsection

@section('sidebar')
  @include('customer-success.partials.sidebar')
@endsection

@section('bottom-nav')
  @include('customer-success.partials.bottom-nav')
@endsection

@push('styles')
  @include('customer-success.anuncios._styles')
@endpush

@section('content')
<div class="cs-shell rise d1">

  <div class="cs-edit-banner" id="csEditBanner">
    <span class="cs-edit-banner-icon">✏️</span>
    <div class="cs-edit-banner-text">
      Estás editando: <strong id="csEditBannerTitle">—</strong>
      <br><span style="font-size:12px;color:var(--txt-soft)">El tipo de anuncio no puede modificarse. Los cambios se guardarán sin reenviar notificaciones.</span>
    </div>
    <button class="cs-edit-banner-close" id="csCancelEdit" type="button" title="Cancelar edición">✕</button>
  </div>

  <div class="nc-card">

    {{-- Header --}}
    <div class="nc-header">
      <div class="nc-header-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2L15 22l-4-9-9-4 20-7z"/></svg>
      </div>
      <div>
        <div class="nc-header-title">Nuevo anuncio</div>
        <div class="nc-header-sub">Crea y publica tu anuncio de manera rápida y efectiva.</div>
      </div>
      <button class="nc-header-close" id="ncHeaderClose" type="button" title="Cerrar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="cs-alert" id="csAlert"></div>

    <form class="nc-body" id="csForm" autocomplete="off">
      @csrf

      {{-- Título --}}
      <div class="nc-field">
        <label class="nc-label">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 12h6M9 15h4"/></svg>
          Título
        </label>
        <div class="nc-input-wrap">
          <input class="nc-input" type="text" id="csTitulo" required maxlength="80" placeholder="Escribe un título atractivo para tu anuncio...">
          <span class="nc-input-counter"><span id="ncTituloCount">0</span>/80</span>
        </div>
      </div>

      {{-- Fila: Tipo | Público objetivo | Programar --}}
      <div class="nc-grid-3">

        {{-- Tipo de anuncio --}}
        <div class="nc-section">
          <div class="nc-section-title">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            Tipo de anuncio
          </div>
          <div class="nc-tipo-wrap">
            <div class="nc-tipo-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <div class="cs-tipo-wrap" style="flex:1">
              <select class="nc-select" id="csTipo" required>
                <option value="notificacion">Notificación</option>
                <option value="anuncios_internos">Anuncios internos</option>
                <option value="mejoras">Mejoras en Enclaii</option>
                <option value="mantenimiento">Mantenimiento</option>
                <option value="politicas">Políticas</option>
              </select>
              <button class="nc-tipo-trigger" id="csTipoTrigger" type="button" aria-haspopup="listbox" aria-expanded="false">
                <span class="nc-tipo-trigger-label" id="csTipoLabel">Notificación</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
              </button>
              <div class="nc-tipo-menu" id="csTipoMenu" role="listbox">
                <button type="button" class="nc-tipo-option is-selected" data-value="notificacion"><span class="nc-tipo-option-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></span><span><strong>Notificación</strong><small>Actualización importante para usuarios</small></span></button>
                <button type="button" class="nc-tipo-option" data-value="anuncios_internos"><span class="nc-tipo-option-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg></span><span><strong>Anuncio interno</strong><small>Comunicación para el equipo</small></span></button>
                <button type="button" class="nc-tipo-option" data-value="mejoras"><span class="nc-tipo-option-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></span><span><strong>Mejoras en Enclaii</strong><small>Nuevas funciones y cambios</small></span></button>
                <button type="button" class="nc-tipo-option" data-value="mantenimiento"><span class="nc-tipo-option-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></span><span><strong>Mantenimiento</strong><small>Aviso de servicio programado</small></span></button>
                <button type="button" class="nc-tipo-option" data-value="politicas"><span class="nc-tipo-option-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span><span><strong>Políticas</strong><small>Documentos y lineamientos</small></span></button>
              </div>
              <span class="cs-tipo-lock" id="csTipoLock" title="El tipo no puede cambiarse al editar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              </span>
            </div>
          </div>

          <div class="nc-section-title" style="margin-top:14px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            Canales de notificación
          </div>
          <div class="nc-channels">
            <label class="nc-channel-opt" id="ncChannelWeb">
              <input type="checkbox" name="csCanales" value="web" checked hidden>
              <span class="nc-channel-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
              </span>
              <span class="nc-channel-label">Web</span>
              <span class="nc-channel-check">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </label>
            <label class="nc-channel-opt" id="ncChannelEmail">
              <input type="checkbox" name="csCanales" value="email" id="csCanalesEmail" hidden>
              <span class="nc-channel-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              </span>
              <span class="nc-channel-label">Correo electrónico</span>
              <span class="nc-channel-check">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </label>
          </div>
          <div id="csEmailWarning" style="display:none;margin-top:8px;padding:8px 12px;background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.45);border-radius:8px;font-size:12px;color:#f59e0b;line-height:1.5;">
            <strong>⚠ Atención:</strong> Los correos se enviarán al publicar y <strong>no se pueden reenviar</strong> al editar.
          </div>
        </div>

        {{-- Público objetivo --}}
        <div class="nc-section">
          <div class="nc-section-title">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Público
            <svg class="nc-section-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
          </div>
          <div class="nc-radio-list" id="csPublicoList">
            <label class="nc-radio-opt nc-radio-opt--active">
              <input type="radio" name="csPublico" value="todos" checked hidden>
              <span class="nc-radio-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              </span>
              <span class="nc-radio-label">Todos</span>
              <span class="nc-radio-check">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </label>
          </div>
          <input type="hidden" id="csPublico" value="todos">
        </div>

        {{-- Programar publicación --}}
        <div class="nc-section">
          <div class="nc-section-title">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Programar publicación
          </div>
          <div class="nc-sched-list">
            <label class="nc-sched-opt nc-sched-opt--active" id="ncSchedInm">
              <input type="radio" name="csSched" value="inmediata" checked hidden>
              <span class="nc-sched-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
              </span>
              <span class="nc-sched-text">
                <span class="nc-sched-name">Inmediata</span>
                <span class="nc-sched-desc">Publicar ahora mismo</span>
              </span>
              <span class="nc-sched-radio"></span>
            </label>
            <label class="nc-sched-opt" id="ncSchedProg">
              <input type="radio" name="csSched" value="programada" hidden>
              <span class="nc-sched-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              </span>
              <span class="nc-sched-text">
                <span class="nc-sched-name">Programada</span>
                <span class="nc-sched-desc">Elegir fecha y hora</span>
              </span>
              <span class="nc-sched-radio"></span>
            </label>
          </div>
          <div class="nc-fecha-wrap" id="ncFechaWrap" style="display:none;margin-top:10px">
            <input class="nc-input" type="text" id="csFecha" placeholder="Selecciona fecha y hora">
          </div>
        </div>

      </div>{{-- /nc-grid-3 --}}

      {{-- Contenido --}}
      <div class="nc-field">
        <label class="nc-label">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Contenido del anuncio
        </label>
        <div class="cs-editor-wrap nc-editor-wrap">
          <div class="cs-editor-toolbar" id="csToolbar">
            <button type="button" data-cmd="bold" title="Negrita"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg></button>
            <button type="button" data-cmd="italic" title="Cursiva"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg></button>
            <button type="button" data-cmd="underline" title="Subrayado"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg></button>
            <span class="sep"></span>
            <button type="button" data-cmd="insertUnorderedList" title="Lista con viñetas"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></button>
            <button type="button" data-cmd="insertOrderedList" title="Lista numerada"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg></button>
            <span class="sep"></span>
            <button type="button" data-cmd="createLink" title="Enlace"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></button>
            <button type="button" data-cmd="removeFormat" title="Limpiar formato"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18"/><path d="M6 7l2.5 14h7L18 7"/></svg></button>
          </div>
          <div class="cs-editor-content" id="csContenido" contenteditable="true" data-placeholder="Escribe el contenido de tu anuncio..." required></div>
          <div class="nc-editor-footer">
            <span><span id="ncContentCount">0</span>/2000</span>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          </div>
        </div>
        <input type="hidden" id="csContenidoHtml" name="contenido">
      </div>

      {{-- Footer: botones + consejo --}}
      <div class="nc-footer">
        <div class="nc-footer-btns">
          <button class="nc-btn-primary" type="submit">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2L15 22l-4-9-9-4 20-7z"/></svg>
            Publicar anuncio
          </button>
          <button class="nc-btn-secondary" type="button" id="csPreview">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            Vista previa
          </button>
        </div>
        <div class="nc-consejo">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
          <span><strong>Consejo:</strong> Revisa la vista previa antes de publicar</span>
        </div>
      </div>

    </form>
  </div>

  @include('customer-success.anuncios._lista')

</div>

{{-- Mini-modal de confirmación --}}
<div class="cs-confirm-ov" id="csConfirmOv">
  <div class="cs-confirm-box">
    <p class="cs-confirm-msg" id="csConfirmMsg"></p>
    <div class="cs-confirm-btns">
      <button class="cs-btn cs-btn-primary" id="csConfirmOk" type="button">Aceptar</button>
      <button class="cs-btn cs-btn-secondary" id="csConfirmCancel" type="button">Cancelar</button>
    </div>
  </div>
</div>

{{-- Vista previa --}}
<div class="pv-ov" id="pvOverlay">
  <div class="pv-bar">
    <span class="pv-title">Vista previa del anuncio</span>
    <button class="cs-btn cs-btn-secondary" type="button" id="pvClose">Cerrar</button>
  </div>
  <div class="pv-scroll">
    <div class="pv-card" id="pvCard">
      <span class="pv-icon" id="pvIcon" style="display:none"></span>
      <span class="pv-badge" id="pvBadge"></span>
      <h2 id="pvTitle">Título</h2>
      <div class="meta" id="pvMeta">Tipo • Público objetivo</div>
      <div class="body" id="pvBody">Contenido...</div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  @include('customer-success.anuncios._scripts')
@endpush
