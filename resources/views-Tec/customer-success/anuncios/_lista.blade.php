@php
  $tipoLabels = [
      'notificacion'     => 'Notificación',
      'anuncios_internos' => 'Anuncios internos',
      'mejoras' => 'Mejoras en Enclaii',
      'mantenimiento' => 'Mantenimiento de la plataforma',
      'politicas' => 'Políticas',
  ];
  $publicoLabels = [
      'todos' => 'Todos',
      'doctores' => 'Doctores',
      'administradores' => 'Administradores',
  ];
@endphp

@php
  $tipoIconos = [
    'notificacion'      => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
    'anuncios_internos' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>',
    'mejoras'           => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
    'mantenimiento'     => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
    'politicas'         => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
  ];
@endphp

<div class="cs-card" id="csListaWrap">
  <div class="cs-card-title">Anuncios publicados</div>

  <div class="cs-filter-bar" id="csFilterBar">
    <div class="cs-filter-search">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="csFilterQ" placeholder="Buscar por título, autor o tipo..." autocomplete="off">
    </div>
    <div class="cs-filter-select-wrap">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      <select id="csFilterTipo">
        <option value="">Todos los tipos</option>
        <option value="notificacion">Notificación</option>
        <option value="anuncios_internos">Anuncios internos</option>
        <option value="mejoras">Mejoras en Enclaii</option>
        <option value="mantenimiento">Mantenimiento</option>
        <option value="politicas">Políticas</option>
      </select>
      <svg class="cs-filter-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </div>
    <div class="cs-filter-select-wrap">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
      <select id="csFilterCanal">
        <option value="">Todos los canales</option>
        <option value="web">Web</option>
        <option value="email">Email</option>
      </select>
      <svg class="cs-filter-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </div>
    <div class="cs-filter-select-wrap">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      <select id="csFilterEstado">
        <option value="">Todos los estados</option>
        <option value="activo">Activo</option>
        <option value="inactivo">Inactivo</option>
        <option value="programado">Programado</option>
      </select>
      <svg class="cs-filter-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </div>
    <button class="cs-filter-clear" id="csFilterClear" type="button">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.02"/></svg>
      Limpiar filtros
    </button>
  </div>

  @if($anuncios->isEmpty())
    <div class="cs-empty">No hay anuncios publicados.</div>
  @else
    <table class="cs-table">
      <thead>
        <tr>
          <th><span class="th-inner"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Título</span></th>
          <th><span class="th-inner"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>Tipo</span></th>
          <th><span class="th-inner"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Público</span></th>
          <th><span class="th-inner"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>Canales</span></th>
          <th><span class="th-inner"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Autor</span></th>
          <th><span class="th-inner"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Publicación</span></th>
          <th><span class="th-inner"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Estado</span></th>
          <th><span class="th-inner"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>Acciones</span></th>
        </tr>
      </thead>
      <tbody>
        @foreach($anuncios as $anuncio)
        @php
          $initials = collect(explode(' ', $anuncio->user->name ?? 'U'))->take(2)->map(fn($w)=>strtoupper($w[0]))->implode('');
          $tipoKey = $anuncio->tipo;
        @endphp
        <tr data-id="{{ $anuncio->id }}"
            data-titulo="{{ $anuncio->titulo }}"
            data-tipo="{{ $anuncio->tipo }}"
            data-publico="{{ $anuncio->publico_objetivo ?? 'todos' }}"
            data-canales="{{ implode(',', is_array($anuncio->canales) ? $anuncio->canales : ['web']) }}"
            data-fecha="{{ $anuncio->fecha_publicacion?->format('Y-m-d\TH:i') ?? '' }}"
            data-contenido="{{ json_encode($anuncio->contenido) }}">
          <td>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span class="cs-title-text" title="{{ $anuncio->titulo }}">{{ $anuncio->titulo }}</span>
          </td>
          <td>
            <span class="cs-badge cs-badge-{{ $tipoKey }}">
              {!! $tipoIconos[$tipoKey] ?? '' !!}
              {{ $tipoLabels[$tipoKey] ?? $tipoKey }}
            </span>
          </td>
          <td>
            <span class="cs-badge" style="background:rgba(99,102,241,.12);color:#a5b4fc;border:1px solid rgba(99,102,241,.22)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:11px;height:11px"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              {{ $publicoLabels[$anuncio->publico_objetivo ?? 'todos'] ?? 'Todos' }}
            </span>
          </td>
          <td>
            <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:#94a3b8">
              <svg viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
              {{ is_array($anuncio->canales) ? implode(', ', $anuncio->canales) : 'web' }}
            </span>
          </td>
          <td>
            <span style="display:inline-flex;align-items:center;gap:7px">
              <span class="cs-avatar">{{ $initials }}</span>
              {{ $anuncio->user->name ?? '—' }}
            </span>
          </td>
          <td style="font-size:12px;color:#94a3b8">
            @if($anuncio->fecha_publicacion && $anuncio->fecha_publicacion->isFuture())
              <span class="cs-badge cs-badge-programado" style="margin-bottom:3px">Programado</span><br>
              {{ $anuncio->fecha_publicacion->format('d/m/Y H:i') }}
            @elseif($anuncio->fecha_publicacion)
              {{ $anuncio->fecha_publicacion->format('d/m/Y H:i') }}
            @else
              {{ $anuncio->created_at->format('d/m/Y H:i') }}
            @endif
          </td>
          <td>
            @if($anuncio->activo)
              <span class="cs-badge cs-badge-activo"><span class="cs-badge-dot"></span>Activo</span>
            @elseif($anuncio->fecha_publicacion && $anuncio->fecha_publicacion->isFuture())
              <span class="cs-badge cs-badge-programado"><span class="cs-badge-dot"></span>Programado</span>
            @else
              <span class="cs-badge cs-badge-inactivo"><span class="cs-badge-dot"></span>Inactivo</span>
            @endif
          </td>
          <td style="white-space:nowrap">
            <button class="cs-action-btn cs-action-view cs-view" type="button" data-tip="Ver anuncio">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
            <button class="cs-action-btn cs-action-edit cs-edit" type="button" data-tip="Editar">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <button class="cs-action-btn cs-action-delete cs-delete" type="button" data-tip="Eliminar">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div id="csPagination">
      {{ $anuncios->links() }}
    </div>
  @endif
</div>
