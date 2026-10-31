@extends('layouts.app')
@section('title', 'Edit Berita')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Edit Berita" page="Berita" active="Edit Berita" route="{{ route('beritas.index') }}" />
@endsection

<section class="section">
    @include('pages.beritas.partials.form', [
        'action' => route('beritas.update', $berita->uuid),
        'method' => 'PUT',
        'berita' => $berita,
        'categories' => $categories,
        'submitLabel' => 'Perbarui Berita',
    ])
</section>
@endsection
