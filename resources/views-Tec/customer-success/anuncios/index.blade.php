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

  <div class="cs-card">
    <div class="cs-card-title">Nuevo anuncio</div>
    <div class="cs-alert" id="csAlert"></div>
    <form class="cs-form" id="csForm">
      @csrf
      <div class="cs-field">
        <label class="cs-label">Título</label>
        <input class="cs-input" type="text" id="csTitulo" required maxlength="255">
      </div>

      <div class="cs-row">
        <div class="cs-field" style="flex:1;min-width:180px">
          <label class="cs-label">Tipo</label>
          <div class="cs-tipo-wrap">
            <select class="cs-input" id="csTipo" required>
              <option value="notificacion">Notificación</option>
              <option value="anuncios_internos">Anuncios internos</option>
              <option value="mejoras">Mejoras en Enclaii</option>
              <option value="mantenimiento">Mantenimiento de la plataforma</option>
              <option value="politicas">Políticas</option>
            </select>
            <span class="cs-tipo-lock" id="csTipoLock" title="El tipo no puede cambiarse al editar">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
            </span>
          </div>
        </div>
        <div class="cs-field" style="flex:1;min-width:180px">
          <label class="cs-label">Público objetivo</label>
          <select class="cs-input" id="csPublico" required>
            <option value="todos">Todos</option>
            <option value="doctores">Doctores</option>
            <option value="administradores">Administradores</option>
          </select>
        </div>
        <div class="cs-field" style="flex:1;min-width:180px">
          <label class="cs-label">Programar publicación</label>
          <input class="cs-input" type="text" id="csFecha" placeholder="Inmediata">
        </div>
      </div>

      <div class="cs-field">
        <label class="cs-label">Canales de notificación</label>
        <div class="cs-channels">
          <label class="cs-channel">
            <input type="checkbox" name="csCanales" value="web" checked>
            <span>Web</span>
          </label>
          <label class="cs-channel">
            <input type="checkbox" name="csCanales" value="email">
            <span>Correo electrónico</span>
          </label>
          <label class="cs-channel">
            <input type="checkbox" name="csCanales" value="push">
            <span>Push (requiere configuración)</span>
          </label>
        </div>
      </div>

      <div class="cs-field">
        <label class="cs-label">Contenido</label>
        <div class="cs-editor-wrap">
          <div class="cs-editor-toolbar" id="csToolbar">
            <button type="button" data-cmd="bold" title="Negrita"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg></button>
            <button type="button" data-cmd="italic" title="Cursiva"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg></button>
            <button type="button" data-cmd="underline" title="Subrayado"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg></button>
            <span class="sep"></span>
            <button type="button" data-cmd="insertUnorderedList" title="Lista con viñetas"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></button>
            <button type="button" data-cmd="insertOrderedList" title="Lista numerada"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg></button>
            <span class="sep"></span>
            <button type="button" data-cmd="createLink" title="Enlace"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></button>
            <button type="button" data-cmd="removeFormat" title="Limpiar formato"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18"/><path d="M6 7l2.5 14h7L18 7"/><path d="M9 7l3 12"/><path d="M15 7l-3 12"/></svg></button>
          </div>
          <div class="cs-editor-content" id="csContenido" contenteditable="true" data-placeholder="Escribe el contenido del anuncio..." required></div>
        </div>
        <input type="hidden" id="csContenidoHtml" name="contenido">
      </div>

      <div style="display:flex;gap:10px;flex-wrap:wrap">
        <button class="cs-btn cs-btn-primary" type="submit">Publicar anuncio</button>
        <button class="cs-btn cs-btn-secondary" type="button" id="csPreview">Vista previa</button>
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
