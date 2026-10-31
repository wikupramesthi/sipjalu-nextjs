@extends('layouts.app')
@section('title', 'Edit Layanan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Edit Layanan" page="Layanan" active="Edit" route="{{ route('services.index') }}" />
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
            <h6 class="mb-0">Edit Layanan: {{ $service->name }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('services.update', $service->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('pages.services.form', ['service' => $service])
            </form>
        </div>
    </div>
</section>
@endsection
