@extends('layouts.app')
@section('title', 'Tambah Berita')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Tambah Berita" page="Berita" active="Tambah Berita" route="{{ route('beritas.index') }}" />
@endsection

<section class="section">
    @include('pages.beritas.partials.form', [
        'action' => route('beritas.store'),
        'method' => 'POST',
        'berita' => null,
        'categories' => $categories,
        'submitLabel' => 'Simpan Berita',
    ])
</section>
@endsection
