@if ($paginator->hasPages())
    <nav class="tw-pagination" role="navigation" aria-label="تصفح الصفحات">
        @if ($paginator->onFirstPage())
            <span aria-disabled="true">&lsaquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="السابق">&lsaquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span>{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active"><span>{{ $page }}</span></span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="التالي">&rsaquo;</a>
        @else
            <span aria-disabled="true">&rsaquo;</span>
        @endif
    </nav>
@endif
