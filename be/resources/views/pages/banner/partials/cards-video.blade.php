@foreach ($items as $item)
@include('pages.banner.partials.card-video', ['item' => $item])
@endforeach