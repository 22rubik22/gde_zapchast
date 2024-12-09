<link rel="stylesheet" href="{{ asset('css/pagination.css') }}"> <!-- Подключение стилей пагинации -->

<div class="pagination">
    @if ($adverts->onFirstPage())
        <span>&laquo;</span>
    @else
        <a href="{{ $adverts->previousPageUrl() }}">&laquo;</a>
    @endif

    @php
        $currentPage = $adverts->currentPage();
        $lastPage = $adverts->lastPage();
        $start = max(1, $currentPage - 3);
        $end = min($lastPage, $currentPage + 3);
    @endphp

    @if ($start > 1)
        <a href="{{ $adverts->appends(request()->input())->url(1) }}">1</a>
        @if ($start > 2)
            <span>...</span>
        @endif
    @endif

    @for ($i = $start; $i <= $end; $i++)
        @if ($i == $currentPage)
            <span class="active">{{ $i }}</span>
        @else
            <a href="{{ $adverts->appends(request()->input())->url($i) }}">{{ $i }}</a>
        @endif
    @endfor

    @if ($end < $lastPage)
        @if ($end < $lastPage - 1)
            <span>...</span>
        @endif
        <a href="{{ $adverts->appends(request()->input())->url($lastPage) }}">{{ $lastPage }}</a>
    @endif

    @if ($adverts->hasMorePages())
        <a href="{{ $adverts->nextPageUrl() }}">&raquo;</a>
    @else
        <span>&raquo;</span>
    @endif
</div>