<nav role="navigation" aria-label="Pagination">
    <ul class="flex items-center justify-center space-x-1 text-sm">
        @if ($paginator->onFirstPage())
            <li class="px-3 py-1 text-slate-400 border border-slate-200 rounded">&laquo;</li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1 text-emerald-600 border border-slate-200 rounded hover:bg-emerald-50"
                    aria-label="Anterior">&laquo;</a>
            </li>
        @endif

        @for ($page = $startPage; $page <= $endPage; $page++)
            @if ($page == $paginator->currentPage())
                <li class="px-3 py-1 bg-emerald-600 text-white rounded">{{ $page }}</li>
            @else
                <li>
                    <a href="{{ $paginator->url($page) }}"
                        class="px-3 py-1 text-slate-600 border border-slate-200 rounded hover:bg-emerald-50">
                        {{ $page }}
                    </a>
                </li>
            @endif
        @endfor

        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1 text-emerald-600 border border-slate-200 rounded hover:bg-emerald-50"
                    aria-label="Próximo">&raquo;</a>
            </li>
        @else
            <li class="px-3 py-1 text-slate-400 border border-slate-200 rounded">&raquo;</li>
        @endif
    </ul>
</nav>
