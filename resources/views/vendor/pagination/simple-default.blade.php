@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();

        // how many pages before & after current
        $start = max(1, $current - 2);
        $end   = min($last, $current + 2);
    @endphp

    <ul class="pagination">

        {{-- Previous --}}
        @if ($current == 1)
            <li><span class="page-link">&laquo;</span></li>
        @else
            <li>
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                    &laquo;
                </a>
            </li>
        @endif

        {{-- First page --}}
        @if ($start > 1)
            <li>
                <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
            </li>

            @if ($start > 2)
                <li><span class="page-link">…</span></li>
            @endif
        @endif

        {{-- Page window --}}
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <li>
                    <span class="page-link active">{{ $page }}</span>
                </li>
            @else
                <li>
                    <a class="page-link" href="{{ $paginator->url($page) }}">
                        {{ $page }}
                    </a>
                </li>
            @endif
        @endfor

        {{-- Last page --}}
        @if ($end < $last)
            @if ($end < $last - 1)
                <li><span class="page-link">…</span></li>
            @endif

            <li>
                <a class="page-link" href="{{ $paginator->url($last) }}">
                    {{ $last }}
                </a>
            </li>
        @endif

        {{-- Next --}}
        @if ($current < $last)
            <li>
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                    &raquo;
                </a>
            </li>
        @else
            <li><span class="page-link">&raquo;</span></li>
        @endif

    </ul>
@endif
