@if ($paginator->hasPages())
<nav class="paginacion-laravel" role="navigation">

  {{-- Mobile --}}
  <div class="paginacion-mobile">
    @if ($paginator->onFirstPage())
      <span class="paginacion-item disabled">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
      </span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="paginacion-item">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
      </a>
    @endif

    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="paginacion-item">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
      </a>
    @else
      <span class="paginacion-item disabled">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
      </span>
    @endif
  </div>

  {{-- Desktop --}}
  <div class="paginacion-desktop">
    <div class="paginacion-info">
      @if ($paginator->firstItem())
        Mostrando <strong>{{ $paginator->firstItem() }}</strong> – <strong>{{ $paginator->lastItem() }}</strong> de <strong>{{ $paginator->total() }}</strong> registros
      @else
        {{ $paginator->count() }} registros
      @endif
    </div>

    <div class="paginacion-links">
      {{-- Anterior --}}
      @if ($paginator->onFirstPage())
        <span class="paginacion-item disabled" aria-disabled="true">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="paginacion-item" aria-label="Anterior">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </a>
      @endif

      {{-- Páginas --}}
      @foreach ($elements as $element)
        @if (is_string($element))
          <span class="paginacion-item dots">…</span>
        @endif
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span class="paginacion-item active" aria-current="page">{{ $page }}</span>
            @else
              <a href="{{ $url }}" class="paginacion-item" aria-label="Ir a página {{ $page }}">{{ $page }}</a>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Siguiente --}}
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="paginacion-item" aria-label="Siguiente">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        </a>
      @else
        <span class="paginacion-item disabled" aria-disabled="true">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        </span>
      @endif
    </div>
  </div>

</nav>
@endif
