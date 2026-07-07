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

<div class="cs-card" id="csListaWrap">
  <div class="cs-card-title">Anuncios publicados</div>
  @if($anuncios->isEmpty())
    <div class="cs-empty">No hay anuncios publicados.</div>
  @else
    <table class="cs-table">
      <thead>
        <tr>
          <th>Título</th>
          <th>Tipo</th>
          <th>Público</th>
          <th>Canales</th>
          <th>Autor</th>
          <th>Publicación</th>
          <th>Estado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($anuncios as $anuncio)
        <tr data-id="{{ $anuncio->id }}"
            data-titulo="{{ $anuncio->titulo }}"
            data-tipo="{{ $anuncio->tipo }}"
            data-publico="{{ $anuncio->publico_objetivo }}"
            data-canales="{{ implode(',', is_array($anuncio->canales) ? $anuncio->canales : ['web']) }}"
            data-fecha="{{ $anuncio->fecha_publicacion?->format('Y-m-d\TH:i') ?? '' }}"
            data-contenido="{{ e($anuncio->contenido) }}">
          <td>{{ $anuncio->titulo }}</td>
          <td>{{ $tipoLabels[$anuncio->tipo] ?? $anuncio->tipo }}</td>
          <td>{{ $publicoLabels[$anuncio->publico_objetivo] ?? $anuncio->publico_objetivo }}</td>
          <td>{{ is_array($anuncio->canales) ? implode(', ', $anuncio->canales) : 'web' }}</td>
          <td>{{ $anuncio->user->name ?? '—' }}</td>
          <td>{{ $anuncio->fecha_publicacion ? $anuncio->fecha_publicacion->format('d/m/Y H:i') : 'Inmediata' }}</td>
          <td>
            @if($anuncio->activo)
              Activo
            @elseif($anuncio->fecha_publicacion && $anuncio->fecha_publicacion->isFuture())
              Programado
            @else
              Inactivo
            @endif
          </td>
          <td style="display:flex;gap:4px;align-items:center">
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
    <div id="csPagination" style="margin-top:16px">
      {{ $anuncios->links() }}
    </div>
  @endif
</div>
