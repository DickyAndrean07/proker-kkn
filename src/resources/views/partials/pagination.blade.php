@if ($paginator->hasPages())
    <nav class="pagination-wrap" aria-label="Navigasi halaman">
        <ul class="pagination" role="list">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li><span class="pagination-link disabled" aria-disabled="true" aria-label="Halaman sebelumnya">‹</span></li>
            @else
                <li><a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">‹</a></li>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="pagination-link disabled" aria-hidden="true">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="pagination-link active" aria-current="page" aria-label="Halaman {{ $page }}">{{ $page }}</span></li>
                        @else
                            <li><a class="pagination-link" href="{{ $url }}" aria-label="Ke halaman {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li><a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman selanjutnya">›</a></li>
            @else
                <li><span class="pagination-link disabled" aria-disabled="true" aria-label="Halaman selanjutnya">›</span></li>
            @endif

        </ul>
    </nav>
@endif
