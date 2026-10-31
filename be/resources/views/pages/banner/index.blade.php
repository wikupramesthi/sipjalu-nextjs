@extends('layouts.app')
@section('title', 'Media Library')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Media Library" page="Media Library" active="Semua Media" route="{{ route('banner.index') }}" />
@endsection

@php
    $perPage = 6;
    $fotoRemain = ($counts['foto'] ?? $fotos->count()) - $perPage;
    $videoRemain = ($counts['video'] ?? $videos->count()) - $perPage;
    $albumRemain = ($counts['album'] ?? $albums->count()) - $perPage;
@endphp

<section class="section">
    <div class="row mb-4 g-3 ml-stats">
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm"><div class="card-body py-2 px-3"><div class="ml-stat">
                <div class="ml-stat-icon ic-primary"><i class="bx bx-image-alt"></i></div>
                <div><div class="ml-stat-value">{{ number_format($counts['foto'] ?? $fotos->count()) }}</div><div class="ml-stat-label">Total Foto</div></div>
            </div></div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm"><div class="card-body py-2 px-3"><div class="ml-stat">
                <div class="ml-stat-icon ic-danger"><i class="bx bx-video-recording"></i></div>
                <div><div class="ml-stat-value">{{ number_format($counts['video'] ?? $videos->count()) }}</div><div class="ml-stat-label">Total Video</div></div>
            </div></div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm"><div class="card-body py-2 px-3"><div class="ml-stat">
                <div class="ml-stat-icon ic-violet"><i class="bx bx-collection"></i></div>
                <div><div class="ml-stat-value">{{ number_format($counts['album'] ?? $albums->count()) }}</div><div class="ml-stat-label">Total Album</div></div>
            </div></div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm"><div class="card-body py-2 px-3"><div class="ml-stat">
                <div class="ml-stat-icon ic-success"><i class="bx bx-check-shield"></i></div>
                <div><div class="ml-stat-value">{{ number_format($counts['foto_active'] ?? $fotos->where('status','active')->count()) }}</div><div class="ml-stat-label">Foto Aktif</div></div>
            </div></div></div>
        </div>
    </div>

    <div class="card ml-toolbar mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <ul class="nav nav-pills media-tabs" id="mediaTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="tab-all-link" data-bs-toggle="pill" href="#tab-all" role="tab">
                            <i class="bi bi-grid"></i> Semua
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-foto-link" data-bs-toggle="pill" href="#tab-foto" role="tab">
                            <i class="bx bx-image-alt"></i> Foto
                            <span class="count-pill">{{ $fotos->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-video-link" data-bs-toggle="pill" href="#tab-video" role="tab">
                            <i class="bx bx-video"></i> Video
                            <span class="count-pill">{{ $videos->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-album-link" data-bs-toggle="pill" href="#tab-album" role="tab">
                            <i class="bx bx-photo-album"></i> Album
                            <span class="count-pill">{{ $albums->count() }}</span>
                        </a>
                    </li>
                </ul>

                <div class="media-search">
                    <input type="text" id="mediaSearch" class="form-control" placeholder="Cari media..." autocomplete="off">
                </div>
                @can('banner.store')
                <div class="d-flex flex-wrap gap-2 ms-add-btns">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-add-album">
                        <i class="bi bi-folder-plus"></i> Album
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-add-video">
                        <i class="bi bi-camera-video"></i> Video
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-foto">
                        <i class="bi bi-plus-lg"></i> Foto
                    </button>
                </div>
                @endcan
            </div>
        </div>
    </div>

    <div class="tab-content" id="mediaTabsContent">
        {{-- ==================== SEMUA ==================== --}}
        <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
            <div class="media-section-title">
                <span class="ms-label"><i class="bx bx-image-alt text-primary"></i> Foto</span>
                <span class="ms-bar"></span>
                @if ($fotoRemain > 0)<span class="badge bg-primary ms-count-hint">{{ $fotoRemain }} lainnya</span>@endif
            </div>
            <div class="media-grid" id="grid-foto-all">
                @forelse ($fotos->take($perPage) as $item)
                @include('pages.banner.partials.card-foto')
                @empty
                <div class="media-empty">
                    <i class="bi bi-inbox"></i>
                    <h6>Belum ada foto</h6>
                    <p class="mb-0">Klik tombol <strong>Foto</strong> untuk mengupload foto pertama.</p>
                </div>
                @endforelse
            </div>
            @if ($fotoRemain > 0)
            <div class="text-center mt-3">
                <button type="button" class="btn btn-light btn-load-more" data-type="foto" data-offset="{{ $perPage }}" data-target="#grid-foto-all">
                    <i class="bx bx-plus-circle"></i> <span class="lbl">Muat Lebih Banyak Foto</span>
                    <span class="badge bg-primary ms-1 count">{{ $fotoRemain }}</span>
                </button>
            </div>
            @endif

            <div class="media-section-title">
                <span class="ms-label"><i class="bx bx-video text-danger"></i> Video</span>
                <span class="ms-bar"></span>
                @if ($videoRemain > 0)<span class="badge bg-primary ms-count-hint">{{ $videoRemain }} lainnya</span>@endif
            </div>
            <div class="media-grid" id="grid-video-all">
                @forelse ($videos->take($perPage) as $item)
                @include('pages.banner.partials.card-video')
                @empty
                <div class="media-empty">
                    <i class="bx bx-video-recording"></i>
                    <h6>Belum ada video</h6>
                    <p class="mb-0">Klik tombol <strong>Video</strong> untuk menambahkan video dari link YouTube / Vimeo.</p>
                </div>
                @endforelse
            </div>
            @if ($videoRemain > 0)
            <div class="text-center mt-3">
                <button type="button" class="btn btn-light btn-load-more" data-type="video" data-offset="{{ $perPage }}" data-target="#grid-video-all">
                    <i class="bx bx-plus-circle"></i> <span class="lbl">Muat Lebih Banyak Video</span>
                    <span class="badge bg-primary ms-1 count">{{ $videoRemain }}</span>
                </button>
            </div>
            @endif

            <div class="media-section-title">
                <span class="ms-label"><i class="bx bx-photo-album text-success"></i> Album</span>
                <span class="ms-bar"></span>
                @if ($albumRemain > 0)<span class="badge bg-primary ms-count-hint">{{ $albumRemain }} lainnya</span>@endif
            </div>
            <div class="media-grid" id="grid-album-all">
                @forelse ($albums->take($perPage) as $album)
                @include('pages.banner.partials.card-album')
                @empty
                <div class="media-empty">
                    <i class="bx bx-photo-album"></i>
                    <h6>Belum ada album</h6>
                    <p class="mb-0">Klik tombol <strong>Album</strong> untuk membuat album dari foto yang sudah ada.</p>
                </div>
                @endforelse
            </div>
            @if ($albumRemain > 0)
            <div class="text-center mt-3">
                <button type="button" class="btn btn-light btn-load-more" data-type="album" data-offset="{{ $perPage }}" data-target="#grid-album-all">
                    <i class="bx bx-plus-circle"></i> <span class="lbl">Muat Lebih Banyak Album</span>
                    <span class="badge bg-primary ms-1 count">{{ $albumRemain }}</span>
                </button>
            </div>
            @endif
        </div>

        {{-- ==================== FOTO ==================== --}}
        <div class="tab-pane fade" id="tab-foto" role="tabpanel">
            <div class="media-grid" id="grid-foto-tab">
                @forelse ($fotos->take($perPage) as $item)
                @include('pages.banner.partials.card-foto')
                @empty
                <div class="media-empty">
                    <i class="bi bi-inbox"></i>
                    <h6>Belum ada foto</h6>
                    <p class="mb-0">Klik tombol <strong>Foto</strong> untuk mengupload foto pertama.</p>
                </div>
                @endforelse
            </div>
            @if ($fotoRemain > 0)
            <div class="text-center mt-3">
                <button type="button" class="btn btn-light btn-load-more" data-type="foto" data-offset="{{ $perPage }}" data-target="#grid-foto-tab">
                    <i class="bx bx-plus-circle"></i> <span class="lbl">Muat Lebih Banyak Foto</span>
                    <span class="badge bg-primary ms-1 count">{{ $fotoRemain }}</span>
                </button>
            </div>
            @endif
        </div>

        {{-- ==================== VIDEO ==================== --}}
        <div class="tab-pane fade" id="tab-video" role="tabpanel">
            <div class="media-grid" id="grid-video-tab">
                @forelse ($videos->take($perPage) as $item)
                @include('pages.banner.partials.card-video')
                @empty
                <div class="media-empty">
                    <i class="bx bx-video-recording"></i>
                    <h6>Belum ada video</h6>
                    <p class="mb-0">Klik tombol <strong>Video</strong> untuk menambahkan video dari link YouTube / Vimeo.</p>
                </div>
                @endforelse
            </div>
            @if ($videoRemain > 0)
            <div class="text-center mt-3">
                <button type="button" class="btn btn-light btn-load-more" data-type="video" data-offset="{{ $perPage }}" data-target="#grid-video-tab">
                    <i class="bx bx-plus-circle"></i> <span class="lbl">Muat Lebih Banyak Video</span>
                    <span class="badge bg-primary ms-1 count">{{ $videoRemain }}</span>
                </button>
            </div>
            @endif
        </div>

        {{-- ==================== ALBUM ==================== --}}
        <div class="tab-pane fade" id="tab-album" role="tabpanel">
            <div class="media-grid" id="grid-album-tab">
                @forelse ($albums->take($perPage) as $album)
                @include('pages.banner.partials.card-album')
                @empty
                <div class="media-empty">
                    <i class="bx bx-photo-album"></i>
                    <h6>Belum ada album</h6>
                    <p class="mb-0">Klik tombol <strong>Album</strong> untuk membuat album dari foto yang sudah ada.</p>
                </div>
                @endforelse
            </div>
            @if ($albumRemain > 0)
            <div class="text-center mt-3">
                <button type="button" class="btn btn-light btn-load-more" data-type="album" data-offset="{{ $perPage }}" data-target="#grid-album-tab">
                    <i class="bx bx-plus-circle"></i> <span class="lbl">Muat Lebih Banyak Album</span>
                    <span class="badge bg-primary ms-1 count">{{ $albumRemain }}</span>
                </button>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Ringan: 1 form hapus generik + wadah modal dinamis (edit/view diambil on-demand via AJAX) --}}
<form id="deleteFotoForm" action="" method="POST" class="d-none">
    @method('DELETE') @csrf
</form>
<form id="deleteAlbumForm" action="" method="POST" class="d-none">
    @method('DELETE') @csrf
</form>
<div id="dynamicModals"></div>

{{-- ============ MODALS TAMBAH (ringan, picker foto via AJAX) ============ --}}
@include('pages.banner.modal-add-foto')
@include('pages.banner.modal-add-video')
@include('pages.banner.modal-add-album')

{{-- Preview video --}}
<div id="modal-video-preview" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-video-preview-title">Preview Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2">
                <iframe id="modal-video-preview-frame" class="video-preview show" src="" title="Preview" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script>
    var MIL_LOADMORE_URL = "{{ route('banner.loadMore') }}";
    var MIL_PICKER_URL = "{{ route('banner.fotoPicker') }}";
    var MIL_MODAL_BASE = "{{ url('backend/banner/modal') }}";
    if (window.jQuery) { try { jQuery._milBound = true; } catch (err) {} }

    function mil_modalUrl(tipe, uuid) {
        return MIL_MODAL_BASE + '/' + tipe + '/' + uuid;
    }

    function showSweetAlert(uuid, name) {
        Swal.fire({
            title: 'Hapus media ini?',
            text: (name ? '"' + name + '" ' : '') + 'Media akan dihapus permanen dan tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f43f5e'
        }).then((result) => {
            if (result.isConfirmed) {
                var $f = $('#deleteFotoForm');
                $f.attr('action', "{{ url('backend/banner') }}/" + uuid);
                $f.submit();
            }
        });
    }

    function showSweetAlertAlbum(uuid, name) {
        Swal.fire({
            title: 'Hapus album ini?',
            text: (name ? '"' + name + '" ' : '') + 'Album akan dihapus permanen. Foto di dalamnya tidak ikut terhapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f43f5e'
        }).then((result) => {
            if (result.isConfirmed) {
                var $f = $('#deleteAlbumForm');
                $f.attr('action', "{{ url('backend/banner/album') }}/" + uuid);
                $f.submit();
            }
        });
    }

    $(document).on('click', '.js-delete-foto', function () {
        showSweetAlert($(this).data('uuid'), $(this).data('name'));
    });
    $(document).on('click', '.js-delete-album', function () {
        showSweetAlertAlbum($(this).data('uuid'), $(this).data('name'));
    });

    // Buka modal edit on-demand (ringan)
    function mil_openModal(tipe, uuid, $btn) {
        if ($btn) $btn.prop('disabled', true);
        $.ajax({ url: mil_modalUrl(tipe, uuid), dataType: 'html' })
            .done(function (html) {
                $('#dynamicModals').append(html);
                var $modal = $('#dynamicModals').children().last();
                mil_initAlbumSelects($modal);
                if (tipe === 'album') mil_loadPicker($modal, '');
                var el = $modal.get(0);
                (bootstrap.Modal.getOrCreateInstance(el)).show();
                el.addEventListener('hidden.bs.modal', function () { $modal.remove(); }, { once: true });
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat form. Silakan coba lagi.' });
            })
            .always(function () { if ($btn) $btn.prop('disabled', false); });
    }

    $(document).on('click', '.js-edit-foto', function () { mil_openModal('foto', $(this).data('uuid'), $(this)); });
    $(document).on('click', '.js-edit-video', function () { mil_openModal('video', $(this).data('uuid'), $(this)); });
    $(document).on('click', '.js-edit-album', function (e) { e.stopPropagation(); mil_openModal('album', $(this).data('uuid'), $(this)); });

    // Lihat isi album: ambil modal view on-demand
    $(document).on('click', '.js-view-album', function (e) {
        if ($(e.target).closest('.js-edit-album, .js-delete-album').length) return;
        var uuid = $(this).data('uuid');
        $.ajax({ url: mil_modalUrl('view-album', uuid), dataType: 'html' })
            .done(function (html) {
                $('#dynamicModals').append(html);
                var $modal = $('#dynamicModals').children().last();
                var el = $modal.get(0);
                (bootstrap.Modal.getOrCreateInstance(el)).show();
                el.addEventListener('hidden.bs.modal', function () { $modal.remove(); }, { once: true });
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat isi album.' });
            });
    });

    // Dari modal view -> buka edit album
    $(document).on('click', '.js-edit-album-from-view', function () {
        var uuid = $(this).data('uuid');
        var $viewModal = $(this).closest('.modal');
        $viewModal.modal('hide');
        setTimeout(function () { mil_openModal('album', uuid, null); }, 300);
    });

    // Picker foto album via AJAX (dipakai modal tambah & edit)
    function mil_renderPicker($grid, fotos, selected) {
        selected = selected || [];
        var selSet = {};
        selected.forEach(function (id) { selSet[id] = true; });
        if (!fotos.length) {
            $grid.html('<p class="text-muted small mb-0">Tidak ada foto yang cocok.</p>');
            $grid.closest('.modal').find('.media-picker-count').text('0 foto dipilih');
            return;
        }
        var html = fotos.map(function (f) {
            var checked = selSet[f.uuid] ? ' checked' : '';
            var cls = selSet[f.uuid] ? ' checked' : '';
            var escName = $('<div>').text(f.nama).html();
            return '<label class="media-photo-pick' + cls + '" title="' + escName + '" data-name="' + escName.toLowerCase() + '">'
                + '<img src="' + f.thumb + '" alt="' + escName + '" loading="lazy">'
                + '<span class="pick-check"><i class="bi bi-check"></i></span>'
                + '<input type="checkbox" name="foto_ids[]" value="' + f.uuid + '"' + checked + '>'
                + '</label>';
        }).join('');
        $grid.html(html);
        $grid.closest('.modal').find('.media-picker-count').text(selected.length + ' foto dipilih');
    }

    function mil_loadPicker($modal, search) {
        var $grid = $modal.find('.media-modal-photos').first();
        if (!$grid.length) return;
        var selected = [];
        var raw = $grid.data('selected');
        if (raw) selected = String(raw).split(',').filter(Boolean);
        var checkedNow = $grid.find('input:checked').map(function () { return this.value; }).get();
        if (checkedNow.length) selected = checkedNow;
        $grid.html('<p class="text-muted small mb-0">Memuat foto...</p>');
        $.ajax({ url: MIL_PICKER_URL, data: { search: search || '', limit: 30 }, dataType: 'json' })
            .done(function (res) {
                var fotos = (res.data || []).map(function (f) { return { uuid: f.uuid, nama: f.nama, thumb: f.thumb }; });
                mil_renderPicker($grid, fotos, selected);
            })
            .fail(function () {
                $grid.html('<p class="text-danger small mb-0">Gagal memuat foto.</p>');
            });
    }

    // Saat modal tambah/edit album dibuka -> muat picker
    $(document).on('shown.bs.modal', '#modal-add-album', function () {
        mil_loadPicker($(this), '');
        $(this).find('.media-picker-search').val('');
    });
    $(document).on('shown.bs.modal', '[id^="modal-edit-album-"]', function () {
        var $grid = $(this).find('.media-modal-photos').first();
        if ($grid.length && $grid.text().indexOf('Memuat foto') > -1) mil_loadPicker($(this), '');
        mil_initAlbumSelects($(this));
    });

    // Search picker dengan debounce -> fetch server (bisa cari foto lama, bukan cuma yg tampil)
    var mil_searchTimer = null;
    $(document).on('input', '.media-picker-search', function () {
        var $input = $(this);
        var $modal = $input.closest('.modal');
        var q = $.trim($input.val());
        var target = $input.data('target');
        var $grid = $(target);
        if ($grid.length && $grid.find('.media-photo-pick').length) {
            var ql = q.toLowerCase();
            $grid.find('.media-photo-pick').each(function () {
                var name = ($(this).data('name') || '').toLowerCase();
                $(this).toggle(name.indexOf(ql) > -1 || q === '');
            });
        }
        clearTimeout(mil_searchTimer);
        mil_searchTimer = setTimeout(function () {
            if (q.length >= 2) mil_loadPicker($modal, q);
            else if (q.length === 0) mil_loadPicker($modal, '');
        }, 500);
    });

    // Preview video
    $(document).on('click', '.media-play', function (e) {
        var embed = $(this).data('embed');
        if (!embed) return;
        $('#modal-video-preview-title').text($(this).data('title') || 'Preview Video');
        $('#modal-video-preview-frame').attr('src', embed);
        new bootstrap.Modal(document.getElementById('modal-video-preview')).show();
    });

    $('#modal-video-preview').on('hidden.bs.modal', function () {
        $('#modal-video-preview-frame').attr('src', '');
    });

    // Pencarian media
    $('#mediaSearch').on('input', function () {
        var q = $.trim($(this).val()).toLowerCase();
        $('.media-grid .media-card').each(function () {
            var name = ($(this).data('name') || '').toLowerCase();
            $(this).toggle(name.indexOf(q) > -1 || q === '');
        });
    });

    // Muat lebih banyak — ringan, hanya cards (modal diambil on-demand)
    $(document).on('click', '.btn-load-more', function (e) {
        e.preventDefault();
        var $btn = $(this);
        if ($btn.prop('disabled')) return;
        $btn.prop('disabled', true);
        var type = $btn.data('type');
        var offset = parseInt($btn.data('offset'), 10) || 0;
        var $grid = $($btn.data('target'));
        if (!$grid.length) { $btn.prop('disabled', false); return; }

        var $icon = $btn.find('> i');
        var iconClass = $icon.attr('class');
        $icon.attr('class', 'bx bx-loader-circle bx-spin');

        $.ajax({ url: MIL_LOADMORE_URL, data: { tipe: type, offset: offset }, dataType: 'json' })
            .done(function (res) {
                if (res.html) $grid.append(res.html);
                var q = $.trim($('#mediaSearch').val()).toLowerCase();
                if (q) {
                    $grid.find('.media-card').each(function () {
                        var name = ($(this).data('name') || '').toLowerCase();
                        $(this).toggle(name.indexOf(q) > -1);
                    });
                }
                if (res.hasMore && res.remaining > 0) {
                    $btn.data('offset', res.offset);
                    $btn.find('.count').text(res.remaining);
                    $btn.prop('disabled', false);
                } else {
                    $btn.closest('.text-center').fadeOut(150, function () { $(this).remove(); });
                }
                $icon.attr('class', iconClass);
            })
            .fail(function (xhr) {
                $icon.attr('class', iconClass);
                $btn.prop('disabled', false);
                var msg = 'Gagal memuat media lainnya. Silakan coba lagi.';
                if (xhr.status === 419) msg = 'Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.';
                Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
            });
    });

    // Preview upload foto (delegasi agar jalan di modal dinamis)
    $(document).on('click', '.upload-preview', function () {
        $(this).next('input[type=file]').trigger('click');
    });

    $(document).on('change', '.media-image-input', function () {
        var file = this.files && this.files[0];
        if (!file) return;
        var $preview = $($(this).data('preview'));
        var reader = new FileReader();
        reader.onload = function (e) {
            $preview.find('img').attr('src', e.target.result);
            $preview.addClass('has-img');
        };
        reader.readAsDataURL(file);
    });

    // Preview link video di form
    $(document).on('input', '.media-video-input', function () {
        var embed = mil_getEmbed($(this).val());
        var $preview = $($(this).data('preview'));
        var $empty = $($(this).data('empty'));

        if (embed) {
            $preview.attr('src', embed).addClass('show');
            $empty.hide();
        } else {
            $preview.removeClass('show').attr('src', '');
            $empty.show();
        }
    });

    // Picker foto pada album (delegasi)
    $(document).on('change', '.media-photo-pick input', function () {
        var $grid = $(this).closest('.media-modal-photos');
        $grid.find('.media-photo-pick').each(function () {
            var inp = this.querySelector('input');
            if (inp) $(this).toggleClass('checked', inp.checked);
        });
        var count = $grid.find('input:checked').length;
        $grid.closest('.modal').find('.media-picker-count').text(count + ' foto dipilih');
        var vals = $grid.find('input:checked').map(function () { return this.value; }).get();
        $grid.data('selected', vals.join(','));
    });

    // Select2 album pada form foto
    function mil_initAlbumSelects($root) {
        if (typeof $.fn.select2 !== 'function') return;
        $root.find('.media-album-select').each(function () {
            if ($(this).hasClass('select2-hidden-accessible')) return;
            $(this).select2({
                multiple: true,
                width: '100%',
                placeholder: 'Pilih album (opsional)',
                dropdownParent: $(this).closest('.modal-content')
            });
        });
    }

    $(document).ready(function () {
        mil_initAlbumSelects($(document));
        window.setTimeout(function () { mil_initAlbumSelects($(document)); }, 800);
    });

    function mil_getEmbed(url) {
        if (!url) return null;
        var m;
        m = url.match(/(?:youtube\.com\/(?:watch\?.*v=|embed\/|shorts\/|live\/|v\/)|youtu\.be\/)([\w-]{6,})/);
        if (m) return 'https://www.youtube.com/embed/' + m[1];
        m = url.match(/vimeo\.com\/(?:video\/)?(\d+)/);
        if (m) return 'https://player.vimeo.com/video/' + m[1];
        return null;
    }

    // Fallback vanilla (tanpa jQuery) agar tombol load-more tetap bisa diklik
    // walau jQuery dimuat ulang di layout.
    document.addEventListener('click', function (e) {
        var btn = e.target.closest ? e.target.closest('.btn-load-more') : null;
        if (!btn || btn.disabled) return;
        // kalau handler jQuery masih hidup, biarkan jQuery yang tangani (hindari double)
        if (window.jQuery && jQuery._milBound) return;
        e.preventDefault();
        btn.disabled = true;
        var type = btn.getAttribute('data-type');
        var offset = parseInt(btn.getAttribute('data-offset'), 10) || 0;
        var grid = document.querySelector(btn.getAttribute('data-target'));
        if (!grid) { btn.disabled = false; return; }
        var icon = btn.querySelector(':scope > i');
        var iconClass = icon ? icon.getAttribute('class') : '';
        if (icon) icon.setAttribute('class', 'bx bx-loader-circle bx-spin');
        fetch(MIL_LOADMORE_URL + '?tipe=' + encodeURIComponent(type) + '&offset=' + offset, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        }).then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        }).then(function (res) {
            if (res.html) grid.insertAdjacentHTML('beforeend', res.html);
            if (res.hasMore && res.remaining > 0) {
                btn.setAttribute('data-offset', res.offset);
                var c = btn.querySelector('.count');
                if (c) c.textContent = res.remaining;
                btn.disabled = false;
            } else if (btn.parentElement) {
                btn.parentElement.remove();
            }
            if (icon) icon.setAttribute('class', iconClass);
        }).catch(function () {
            if (icon) icon.setAttribute('class', iconClass);
            btn.disabled = false;
            if (window.Swal) Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat media lainnya.' });
        });
    });
</script>
@endpush

@endsection