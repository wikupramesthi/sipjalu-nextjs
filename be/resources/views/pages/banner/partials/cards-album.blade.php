@foreach ($albums as $album)
@include('pages.banner.partials.card-album', ['album' => $album])
@endforeach