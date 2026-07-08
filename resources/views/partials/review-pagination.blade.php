@if ($reviews->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($reviews->onFirstPage())
            <a href="javascript:void(0)" class="page-link disabled">&laquo;</a>
        @else
            <a href="javascript:void(0)" class="page-link" data-page="{{ $reviews->currentPage() - 1 }}">&laquo;</a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
            @if ($page == $reviews->currentPage())
                <a href="javascript:void(0)" class="page-link active" data-page="{{ $page }}">{{ $page }}</a>
            @else
                <a href="javascript:void(0)" class="page-link" data-page="{{ $page }}">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($reviews->hasMorePages())
            <a href="javascript:void(0)" class="page-link" data-page="{{ $reviews->currentPage() + 1 }}">&raquo;</a>
        @else
            <a href="javascript:void(0)" class="page-link disabled">&raquo;</a>
        @endif
    </div>
@endif
