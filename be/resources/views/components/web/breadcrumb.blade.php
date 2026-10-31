<nav aria-label="breadcrumb" class="argon-breadcrumb">
    <ol class="breadcrumb mb-0 pb-0 pt-1 px-0">
        <li class="breadcrumb-item">
            <a href="{{ $route ?? url('/') }}"><i class="bi bi-house-door"></i></a>
        </li>
        @isset($page)
            @if ($page)
                <li class="breadcrumb-item">
                    <a href="{{ $route ?? url('/') }}" class="opacity-5">{{ $page }}</a>
                </li>
            @endif
        @endisset
        @isset($active)
            @if ($active)
                <li class="breadcrumb-item active" aria-current="page">{{ $active }}</li>
            @endif
        @endisset
    </ol>
    <h5 class="font-weight-bolder mb-0">{{ $title ?? '' }}</h5>
</nav>
