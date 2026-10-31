@extends('layouts.app')
@section('title', 'Edit Page')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Edit Page" page="Static Pages" active="Edit Page" route="{{ route('pages.index') }}" />
@endsection

<!-- Content -->
<section class="section">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('pages.update', $page->uuid) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="title" class="mb-2">Page Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $page->title) }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="excerpt" class="mb-2">Page Content <span class="text-danger">*</span></label>
                    <input type="text" name="excerpt" id="excerpt"
                        class="form-control @error('excerpt') is-invalid @enderror"
                        value="{{ old('excerpt', $page->excerpt) }}" required>
                    @error('excerpt')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="content" class="mb-2">Summary <span class='text-danger'>*</span></label>
                    <textarea name="content" id="deskripsi" cols="30" rows="5"
                        class="form-control summernote @error('content') is-invalid @enderror">{{ old('content', $page->content) }}</textarea>
                    @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="featured_image" class="mb-2">Page Image</label>
                    @if ($page->featured_image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $page->featured_image) }}" alt="Gambar"
                            class="img-thumbnail" width="200">
                    </div>
                    @endif
                    <input type="file" name="featured_image" id="featured_image"
                        class="form-control @error('featured_image') is-invalid @enderror">
                    @error('featured_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="has_sidebar" class="mb-2">Show in Sidebar <span
                            class="text-danger">*</span></label>
                    <select name="has_sidebar" id="has_sidebar" class="form-control">
                        <option value="1" {{ old('has_sidebar', $page->has_sidebar) == 1 ? 'selected' : '' }}>Ya
                        </option>
                        <option value="0" {{ old('has_sidebar', $page->has_sidebar) == 0 ? 'selected' : '' }}>
                            Tidak</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="is_published" class="mb-2">Publish Status <span class="text-danger">*</span></label>
                    <select name="is_published" id="is_published" class="form-control">
                        <option value="1" {{ old('is_published', $page->is_published) == 1 ? 'selected' : '' }}>
                            Publish</option>
                        <option value="0" {{ old('is_published', $page->is_published) == 0 ? 'selected' : '' }}>
                            Draft</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="published_at" class="mb-2">Publish Date <span class="text-danger">*</span></label>
                    <input type="date" name="published_at" id="published_at"
                        class="form-control @error('published_at') is-invalid @enderror"
                        value="{{ old('published_at', $page->published_at ? $page->published_at->format('Y-m-d') : '') }}"
                        required>
                    @error('published_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group text-right mt-4">
                    <a href="{{ route('pages.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button class="btn btn-danger">Update</button>
                </div>
            </form>

        </div>
    </div>
</section>

@endsection