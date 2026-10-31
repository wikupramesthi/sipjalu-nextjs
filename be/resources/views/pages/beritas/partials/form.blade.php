@php
    $berita = $berita ?? null;
    $featuredImage = $berita->featured_image ?? null;
    $scheduledAt = old('scheduled_at', optional($berita->scheduled_at ?? null)->format('Y-m-d\TH:i') ?? '');
@endphp

<style>
    .berita-info-card {
        border-radius: 1.25rem;
        padding: 1.25rem;
        color: #fff;
        background: linear-gradient(135deg, #3b5bdb 0%, #748ffc 45%, #9775fa 100%);
        box-shadow: 0 10px 30px rgba(59, 91, 219, 0.25);
    }
    .berita-info-head { display: flex; align-items: center; gap: .75rem; margin-bottom: 1rem; }
    .berita-info-head strong { display: block; font-size: 1.05rem; line-height: 1.2; }
    .berita-info-head small { opacity: .85; font-size: .85rem; }
    .berita-info-icon {
        width: 44px; height: 44px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.25rem; border-radius: .9rem;
        background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.35);
    }
    .berita-info-item {
        display: flex; gap: .75rem; align-items: flex-start;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.3);
        border-radius: 1rem; padding: .8rem .9rem;
    }
    .berita-info-item + .berita-info-item { margin-top: .7rem; }
    .berita-info-item-icon {
        width: 38px; height: 38px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.1rem; color: #3b5bdb; background: #fff; border-radius: .75rem;
    }
    .berita-info-item strong { display: block; font-size: .95rem; }
    .berita-info-item p { margin: 0; font-size: .83rem; line-height: 1.5; opacity: .92; }
    @media (max-width: 767.98px) { .berita-info-card { padding: 1rem; border-radius: 1rem; } }
</style>

<form action="{{ $action }}" method="post" enctype="multipart/form-data" id="beritaForm">
    @csrf
    @if (($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div class="row g-4">
        <div class="col-lg-8 d-flex flex-column gap-4">
            <div class="card shadow-sm mb-0">
                <div class="card-header bg-transparent py-3 d-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary rounded-circle p-2"><i class="bi bi-newspaper"></i></span>
                    <div>
                        <h5 class="card-title mb-0">Konten Berita</h5>
                        <small class="text-muted">Judul, ringkasan, dan isi berita</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $berita->title ?? '') }}"
                            placeholder="Masukkan judul berita" maxlength="255" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="excerpt" class="form-label fw-semibold">Ringkasan Singkat</label>
                        <input type="text" name="excerpt" id="excerpt"
                            class="form-control @error('excerpt') is-invalid @enderror"
                            value="{{ old('excerpt', $berita->excerpt ?? '') }}"
                            placeholder="Tampil pada kartu berita (opsional, maks 255 karakter)" maxlength="255">
                        @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="video" class="form-label fw-semibold">Link Video</label>
                        <input type="url" name="video" id="video"
                            class="form-control @error('video') is-invalid @enderror"
                            value="{{ old('video', $berita->video ?? '') }}" placeholder="https://youtube.com/...">
                        @error('video')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-0">
                        <label for="content" class="form-label fw-semibold">Isi Berita <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" rows="8"
                            class="form-control summernote @error('content') is-invalid @enderror"
                            required>{{ old('content', $berita->content ?? '') }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-0">
                <div class="card-header bg-transparent py-3 d-flex align-items-center gap-2">
                    <span class="badge bg-info-subtle text-info rounded-circle p-2"><i class="bi bi-image"></i></span>
                    <div>
                        <h5 class="card-title mb-0">Gambar Utama</h5>
                        <small class="text-muted">Cover berita, maks 2 MB</small>
                    </div>
                </div>
                <div class="card-body">
                    <input type="file" name="featured_image" id="featured_image"
                        accept=".jpg,.jpeg,.png,.gif,.webp"
                        class="form-control @error('featured_image') is-invalid @enderror">
                    @error('featured_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div id="featuredImagePreview" class="mt-3"></div>

                    @if ($featuredImage)
                        <input type="hidden" id="existingCoverUrl" value="{{ asset('storage/' . $featuredImage) }}">
                        <img src="{{ asset('storage/' . $featuredImage) }}" alt="Featured"
                            class="img-fluid rounded-3 mt-3 border" style="max-height:220px;object-fit:cover;">
                    @endif
                    <div class="form-text"><i class="bi bi-info-circle"></i> JPG, PNG, GIF, atau WEBP. Kosongkan jika tidak ingin mengganti gambar.</div>
                </div>
            </div>

            <div class="card shadow-sm mb-0">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning-subtle text-warning rounded-circle p-2"><i class="bi bi-images"></i></span>
                        <div>
                            <h5 class="card-title mb-0">Gambar Slider Berita</h5>
                            <small class="text-muted">Tampil sebagai slider, opsional</small>
                        </div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary"><i class="bi bi-plus-lg"></i> Maks. 10 foto</span>
                </div>
                <div class="card-body">
                    @if ($berita && $berita->images->isNotEmpty())
                        <div class="berita-photo-grid mb-3">
                            @foreach ($berita->images as $image)
                                <label class="berita-photo-item" for="remove_image_{{ $image->uuid }}">
                                    <img src="{{ $image->url() }}" alt="Foto berita">
                                    <span class="berita-photo-remove"><i class="bi bi-trash"></i></span>
                                    <input type="checkbox" name="remove_images[]" value="{{ $image->uuid }}"
                                        id="remove_image_{{ $image->uuid }}" class="berita-photo-remove-input">
                                </label>
                            @endforeach
                        </div>
                        <div class="form-text mb-2">Pilih foto yang ingin dihapus saat berita disimpan.</div>
                    @endif
                    <input type="file" name="images[]" id="beritaImages" accept=".jpg,.jpeg,.png,.webp" multiple
                        class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
                    @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text"><i class="bi bi-info-circle"></i> JPG, PNG, WEBP. Maks. 2 MB per foto, hingga 10 foto.</div>
                    <div class="berita-photo-grid mt-3" id="beritaImagesPreview"></div>
                </div>
            </div>

            <div class="card shadow-sm mb-0">
                <div class="card-header bg-transparent py-3 d-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary rounded-circle p-2"><i class="bi bi-paperclip"></i></span>
                    <div>
                        <h5 class="card-title mb-0">Lampiran Dokumen</h5>
                        <small class="text-muted">File pendukung berita (opsional)</small>
                    </div>
                </div>
                <div class="card-body">
                    @if ($berita && $berita->attachments->isNotEmpty())
                        <ul class="list-group list-group-flush mb-3">
                            @foreach ($berita->attachments as $attachment)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <a href="{{ $attachment->url() }}" target="_blank" rel="noopener noreferrer" class="text-truncate me-2">
                                        <i class="bi bi-file-earmark me-1"></i>{{ $attachment->nama_asli }}
                                        <small class="text-muted">({{ $attachment->ukuran_label }})</small>
                                    </a>
                                    <label class="btn btn-sm btn-outline-danger mb-0" for="remove_attachment_{{ $attachment->uuid }}" title="Hapus lampiran">
                                        <i class="bi bi-trash"></i>
                                        <input type="checkbox" name="remove_attachments[]" value="{{ $attachment->uuid }}"
                                            id="remove_attachment_{{ $attachment->uuid }}" class="visually-hidden">
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <div class="form-text mb-2">Klik ikon hapus untuk menghapus lampiran saat berita disimpan.</div>
                    @endif
                    <input type="file" name="attachments[]" id="beritaAttachments" multiple
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.jpeg,.png,.webp"
                        class="form-control @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror">
                    @error('attachments')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @error('attachments.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text"><i class="bi bi-info-circle"></i> PDF, Office, ZIP, RAR, atau gambar. Maks. 5 MB per file, hingga 10 file.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="position-sticky" style="top: 90px;">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-send me-1 text-primary"></i> Publikasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            @if (auth()->user()->hasAnyRole(['super-admin', 'admin']))
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="published" @selected(old('status', $berita->status ?? 'published') === 'published')>Published</option>
                                    <option value="pending" @selected(old('status', $berita->status ?? '') === 'pending')>Pending (menunggu approval)</option>
                                    <option value="draft" @selected(old('status', $berita->status ?? '') === 'draft')>Draft</option>
                                    <option value="scheduled" @selected(old('status', $berita->status ?? '') === 'scheduled')>Scheduled</option>
                                </select>
                            @else
                                <input type="hidden" name="status" value="pending">
                                <div class="alert alert-warning mb-0 py-2 px-3 small">
                                    <i class="bi bi-hourglass-split me-1"></i>
                                    Berita akan dikirim untuk persetujuan admin.
                                </div>
                            @endif
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="scheduled_at" class="form-label fw-semibold">Tanggal Publikasi</label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                class="form-control @error('scheduled_at') is-invalid @enderror"
                                value="{{ $scheduledAt }}">
                            @error('scheduled_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="search_engine" class="form-label fw-semibold">Search Engine</label>
                            <select name="search_engine" id="search_engine"
                                class="form-select @error('search_engine') is-invalid @enderror" required>
                                <option value="index" @selected(old('search_engine', $berita->search_engine ?? 'index') === 'index')>Index</option>
                                <option value="noindex" @selected(old('search_engine', $berita->search_engine ?? 'index') === 'noindex')>No Index</option>
                            </select>
                            @error('search_engine')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_featured"
                                id="is_featured" value="1" @checked(old('is_featured', $berita->is_featured ?? false))>
                            <label class="form-check-label" for="is_featured">
                                <i class="bi bi-star-fill text-warning"></i> Berita <strong>Featured</strong>
                            </label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_popular"
                                id="is_popular" value="1" @checked(old('is_popular', $berita->is_popular ?? false))>
                            <label class="form-check-label" for="is_popular">
                                <i class="bi bi-fire text-danger"></i> Berita <strong>Populer</strong>
                            </label>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_urgent"
                                id="is_urgent" value="1" @checked(old('is_urgent', $berita->is_urgent ?? false))>
                            <label class="form-check-label" for="is_urgent">
                                <i class="bi bi-exclamation-triangle-fill text-danger"></i> Berita <strong>Urgent</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-tags me-1 text-success"></i> Kategori &amp; Tag</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_uuid" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="category_uuid" id="category_uuid"
                                class="form-select @error('category_uuid') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->uuid }}"
                                        @selected(old('category_uuid', $berita->category_uuid ?? '') === $category->uuid)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_uuid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-0">
                            <label for="tagging" class="form-label fw-semibold">Tags</label>
                            <input type="text" name="tagging" id="tagging"
                                class="form-control @error('tagging') is-invalid @enderror"
                                value="{{ old('tagging', $berita->tagging ?? '') }}"
                                placeholder="Pisahkan dengan koma: berita, kota bekasi">
                            @error('tagging')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-save"></i> {{ $submitLabel }}
                    </button>
                    <button class="btn btn-outline-primary" type="button" onclick="previewBerita()">
                        <i class="bi bi-eye"></i> Pratinjau
                    </button>
                    <a href="{{ route('beritas.index') }}" class="btn btn-light border">Batal</a>
                </div>

                <div class="berita-info-card mt-3">
                    <div class="berita-info-head">
                        <span class="berita-info-icon"><i class="bi bi-stars"></i></span>
                        <div><strong>Informasi Penting</strong><small>Panduan pengisian berita</small></div>
                    </div>
                    <div class="berita-info-item">
                        <span class="berita-info-item-icon"><i class="bi bi-card-text"></i></span>
                        <div><strong>Konten Berita</strong><p>Gunakan judul dan ringkasan yang jelas agar mudah dipahami pembaca.</p></div>
                    </div>
                    <div class="berita-info-item">
                        <span class="berita-info-item-icon"><i class="bi bi-image"></i></span>
                        <div><strong>Gambar</strong><p>Gambar utama berformat JPG, PNG, GIF, atau WEBP dengan ukuran maksimal 2 MB.</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .berita-photo-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; }
    .berita-photo-item { position: relative; overflow: hidden; border: 1px solid #e9eef5; border-radius: 10px; cursor: pointer; }
    .berita-photo-item img { display: block; width: 100%; height: 90px; object-fit: cover; }
    .berita-photo-remove { position: absolute; top: 4px; right: 4px; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; color: #fff; background: rgba(220,38,38,.9); border-radius: 50%; }
    .berita-photo-remove-input { position: absolute; left: 5px; bottom: 5px; accent-color: #dc2626; }
    .berita-photo-item:has(.berita-photo-remove-input:checked) { opacity: .4; }
    @media (max-width: 767.98px) { .berita-photo-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
</style>

<script>
    document.addEventListener('keydown', function (event) {
        if ((event.ctrlKey || event.metaKey) && event.key === 's') {
            event.preventDefault();
            document.getElementById('beritaForm')?.requestSubmit();
        }
    });

    function previewBerita() {
        const title = document.getElementById('title').value.trim() || 'Judul belum diisi';
        const excerpt = document.getElementById('excerpt').value.trim() || 'Ringkasan belum diisi';
        const categorySelect = document.getElementById('category_uuid');
        const category = categorySelect.selectedOptions[0]?.textContent.trim() || '-';
        const content = window.jQuery && jQuery('#content').data('summernote')
            ? jQuery('#content').summernote('code')
            : document.getElementById('content').value;
        const imageInput = document.getElementById('featured_image');
        let image = '';

        if (imageInput.files?.[0]) {
            image = '<img src="' + URL.createObjectURL(imageInput.files[0]) + '" alt="" class="img-fluid rounded mb-3" style="max-height:260px;object-fit:cover;width:100%;">';
        } else if (document.getElementById('existingCoverUrl')) {
            image = '<img src="' + document.getElementById('existingCoverUrl').value + '" alt="" class="img-fluid rounded mb-3" style="max-height:260px;object-fit:cover;width:100%;">';
        }

        const escapeHtml = (value) => value.replace(/[&<>"']/g, (character) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
        })[character]);
        const tags = document.getElementById('tagging').value.split(',')
            .map((tag) => tag.trim()).filter(Boolean)
            .map((tag) => '<span class="badge bg-light text-dark border me-1">' + escapeHtml(tag) + '</span>')
            .join('');
        const previewBody = document.getElementById('beritaPreviewBody');
        previewBody.innerHTML = '<div class="border rounded p-3">' + image +
            '<span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-2">' + escapeHtml(category) + '</span>' +
            '<h4 class="fw-bold mb-1">' + escapeHtml(title) + '</h4>' +
            '<p class="text-muted mb-3">' + escapeHtml(excerpt) + '</p><hr>' +
            '<div>' + (content || '<span class="text-muted"><em>Isi berita belum diisi</em></span>') + '</div>' +
            (tags ? '<div class="mt-3"><i class="bi bi-tags me-1"></i>' + tags + '</div>' : '') + '</div>';

        const modal = document.getElementById('beritaPreviewModal');
        if (window.bootstrap?.Modal) {
            bootstrap.Modal.getOrCreateInstance(modal).show();
        } else if (window.jQuery) {
            jQuery(modal).modal('show');
        }
    }

    const featuredImageInput = document.getElementById('featured_image');
    const featuredImagePreview = document.getElementById('featuredImagePreview');
    if (featuredImageInput && featuredImagePreview) {
        featuredImageInput.addEventListener('change', function () {
            const file = this.files && this.files.length ? this.files[0] : null;
            featuredImagePreview.innerHTML = '';
            if (!file) return;

            if (!file.type || !file.type.startsWith('image/')) {
                featuredImagePreview.textContent = 'File yang dipilih bukan gambar.';
                return;
            }

            const reader = new FileReader();
            reader.onload = function () {
                const image = document.createElement('img');
                image.src = reader.result;
                image.alt = 'Pratinjau gambar utama';
                image.className = 'img-fluid rounded-3 border';
                image.style.maxHeight = '220px';
                image.style.objectFit = 'cover';
                featuredImagePreview.appendChild(image);
            };
            reader.onerror = function () {
                featuredImagePreview.textContent = 'Gambar tidak dapat ditampilkan. Silakan pilih file gambar lain.';
            };
            reader.readAsDataURL(file);
        });
    }

    document.getElementById('beritaImages')?.addEventListener('change', function () {
        const preview = document.getElementById('beritaImagesPreview');
        preview.replaceChildren();

        Array.from(this.files || []).forEach((file) => {
            const reader = new FileReader();
            reader.onload = () => {
                const item = document.createElement('div');
                item.className = 'berita-photo-item';
                const image = document.createElement('img');
                image.src = reader.result;
                image.alt = file.name;
                item.appendChild(image);
                preview.appendChild(item);
            };
            reader.readAsDataURL(file);
        });
    });

    document.querySelectorAll('.berita-photo-remove-input').forEach((input) => {
        input.addEventListener('change', () => {
            input.closest('.berita-photo-item')?.classList.toggle('is-removed', input.checked);
        });
    });
</script>

<div class="modal fade" id="beritaPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-eye me-2"></i>Pratinjau Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="beritaPreviewBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<style>
    #beritaPreviewBody img { max-width: 100%; height: auto; }
    #beritaPreviewBody table { max-width: 100%; }
</style>
