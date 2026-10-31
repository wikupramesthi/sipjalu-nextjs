@php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
    $range = 3;
    $start = max(1, $current - $range);
    $end = min($last, $current + $range);
@endphp

@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            {{-- First --}}
            @if ($current > 1)
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">&laquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link" aria-hidden="true">&laquo;</span></li>
            @endif

            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-hidden="true">&lsaquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
            @endif

            {{-- Page Numbers --}}
            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
                @if ($start > 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor

            @if ($end < $last)
                @if ($end < $last - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a></li>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
            @else
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-hidden="true">&rsaquo;</span></li>
            @endif

            {{-- Last --}}
            @if ($current < $last)
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($last) }}">&raquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link" aria-hidden="true">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
