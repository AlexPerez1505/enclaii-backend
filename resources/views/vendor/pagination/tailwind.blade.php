@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginación" class="paginacion-laravel">

        <div class="paginacion-mobile">
            @if ($paginator->onFirstPage())
                <span class="paginacion-boton disabled">Anterior</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="paginacion-boton">Anterior</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="paginacion-boton">Siguiente</a>
            @else
                <span class="paginacion-boton disabled">Siguiente</span>
            @endif
        </div>

        <div class="paginacion-desktop">
            <p class="paginacion-info">
                Mostrando <strong>{{ $paginator->firstItem() ?? 0 }}</strong> a <strong>{{ $paginator->lastItem() ?? 0 }}</strong> de <strong>{{ $paginator->total() }}</strong> resultados
            </p>

            <span class="paginacion-links">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="paginacion-item disabled" aria-hidden="true">
                        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="paginacion-item" aria-label="Anterior">
                        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="paginacion-item dots">{{ $element }}</span>
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

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="paginacion-item" aria-label="Siguiente">
                        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    </a>
                @else
                    <span class="paginacion-item disabled" aria-hidden="true">
                        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    </span>
                @endif
            </span>
        </div>
    </nav>
@endif
