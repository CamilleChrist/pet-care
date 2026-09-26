@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="admin-pagination" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="btn btn--ghost" aria-disabled="true">
                <x-ui.icon name="chevron-left" class="btn__icon"/>
                Précédent
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn--ghost" rel="prev">
                <x-ui.icon name="chevron-left" class="btn__icon"/>
                Précédent
            </a>
        @endif

        <span class="admin-pagination__status">
            Page {{ $paginator->currentPage() }} sur {{ $paginator->lastPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn--ghost" rel="next">
                Suivant
                <x-ui.icon name="chevron-right" class="btn__icon"/>
            </a>
        @else
            <span class="btn btn--ghost" aria-disabled="true">
                Suivant
                <x-ui.icon name="chevron-right" class="btn__icon"/>
            </span>
        @endif
    </nav>
@endif
