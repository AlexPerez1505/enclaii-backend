@php
  $tipoLabels = [
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

<div class="cs-card">
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
        <tr data-id="{{ $anuncio->id }}">
          <td>{{ $anuncio->titulo }}</td>
          <td>{{ $tipoLabels[$anuncio->tipo] ?? $anuncio->tipo }}</td>
          <td>{{ $publicoLabels[$anuncio->publico_objetivo] ?? $anuncio->publico_objetivo }}</td>
          <td>{{ is_array($anuncio->canales) ? implode(', ', $anuncio->canales) : 'web' }}</td>
          <td>{{ $anuncio->user->name ?? '—' }}</td>
          <td>{{ $anuncio->fecha_publicacion?->format('d/m/Y H:i') ?? '—' }}</td>
          <td>
            @if($anuncio->activo)
              Activo
            @elseif($anuncio->fecha_publicacion && $anuncio->fecha_publicacion->isFuture())
              Programado
            @else
              Inactivo
            @endif
          </td>
          <td>
            <button class="cs-btn cs-btn-danger cs-delete" type="button">Eliminar</button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div style="margin-top:16px">
      {{ $anuncios->links() }}
    </div>
  @endif
</div>
