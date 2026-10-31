@foreach ($items as $item)
@include('pages.banner.partials.card-foto', ['item' => $item])
@endforeach