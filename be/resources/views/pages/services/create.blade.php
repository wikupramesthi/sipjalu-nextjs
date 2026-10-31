@extends('layouts.app')
@section('title', 'Tambah Layanan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Tambah Layanan" page="Layanan" active="Tambah" route="{{ route('services.index') }}" />
@endsection

<section class="section">
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white">{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3">
            <h6 class="mb-0">Tambah Layanan Baru</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('pages.services.form', ['service' => null])
            </form>
        </div>
    </div>
</section>
@endsection
