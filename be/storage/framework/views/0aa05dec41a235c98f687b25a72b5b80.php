<?php $__env->startSection('title', 'Tambah Dokumen'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Tambah Dokumen','page' => 'Dokumen','active' => 'Tambah Dokumen','route' => ''.e(route('documents.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Breadcrumb::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal269900abaed345884ce342681cdc99f6)): ?>
<?php $attributes = $__attributesOriginal269900abaed345884ce342681cdc99f6; ?>
<?php unset($__attributesOriginal269900abaed345884ce342681cdc99f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal269900abaed345884ce342681cdc99f6)): ?>
<?php $component = $__componentOriginal269900abaed345884ce342681cdc99f6; ?>
<?php unset($__componentOriginal269900abaed345884ce342681cdc99f6); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<section class="section">

    <form action="<?php echo e(route('documents.store')); ?>" method="POST" enctype="multipart/form-data">

        <?php echo csrf_field(); ?>

        <div class="row g-4">

            
            <div class="col-12 col-lg-8">

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Informasi dokumen
                        </h5>

                        <p class="text-muted small mb-4">
                            Masukkan informasi dasar tentang dokumen ini.
                        </p>

                        
                        <div class="mb-4">

                            <label for="category_uuid" class="form-label fw-semibold">
                                Kategori Dokumen
                                <span class="text-danger">*</span>
                            </label>

                            <select id="category_uuid" name="category_uuid"
                                class="form-select <?php $__errorArgs = ['category_uuid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>

                                <option value="">
                                    -- Pilih Kategori --
                                </option>

                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->uuid); ?>"
                                        <?php echo e(old('category_uuid') == $category->uuid ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </select>

                            <?php $__errorArgs = ['category_uuid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                        
                        <div class="mb-4">

                            <label for="subcategory_uuid" class="form-label fw-semibold">
                                Subkategori Dokumen
                            </label>

                            <select id="subcategory_uuid" name="subcategory_uuid"
                                class="form-select <?php $__errorArgs = ['subcategory_uuid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                                <option value="">
                                    -- Pilih Subkategori --
                                </option>

                            </select>

                            <?php $__errorArgs = ['subcategory_uuid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                        
                        <div class="mb-4">

                            <label for="title" class="form-label fw-semibold">
                                Nama Dokumen
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" id="title" name="title"
                                class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title')); ?>"
                                placeholder="Masukkan Nama Dokumen" required>

                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                        
                        <div class="mb-4">

                            <label for="excerpt" class="form-label fw-semibold">
                                Ringkasan
                            </label>

                            <textarea id="excerpt" name="excerpt" rows="3" class="form-control <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Deskripsi singkat dokumen ini..."><?php echo e(old('excerpt')); ?></textarea>

                            <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                        
                        <div class="mb-0">

                            <label for="description" class="form-label fw-semibold">
                                Deskripsi
                            </label>

                            <textarea id="description" name="description" rows="8"
                                class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Tuliskan deskripsi dokumen lengkapnya..."><?php echo e(old('description')); ?></textarea>

                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                    </div>
                </div>


                
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Publikasi
                        </h5>

                        <p class="text-muted small mb-4">
                            Tetapkan tanggal publikasi dokumen ini.
                        </p>

                        <div class="mb-0">

                            <label for="published_at" class="form-label fw-semibold">
                                Tanggal Publikasi
                            </label>

                            <input type="date" id="published_at" name="published_at"
                                class="form-control <?php $__errorArgs = ['published_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('published_at')); ?>">

                            <?php $__errorArgs = ['published_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                    </div>

                </div>

            </div>


            
            <div class="col-12 col-lg-4">

                
                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            File Dokumen
                        </h5>

                        <p class="text-muted small mb-4">
                            Pilih dokumen dari URL atau unggah file.
                        </p>

                        <div class="mb-3">
                            <label for="file_source" class="form-label fw-semibold">Sumber Dokumen</label>
                            <select id="file_source" name="file_source" class="form-select <?php $__errorArgs = ['file_source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="upload" <?php if(old('file_source', 'upload') === 'upload'): echo 'selected'; endif; ?>>Unggah file</option>
                                <option value="url" <?php if(old('file_source') === 'url'): echo 'selected'; endif; ?>>Link URL</option>
                            </select>
                            <?php $__errorArgs = ['file_source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div id="fileUrlField" class="mb-3 d-none">
                            <label for="file_url" class="form-label fw-semibold">URL Dokumen</label>
                            <input type="url" id="file_url" name="file_url"
                                class="form-control <?php $__errorArgs = ['file_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('file_url')); ?>" placeholder="https://...">
                            <?php $__errorArgs = ['file_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div id="fileUploadField" class="mb-3">
                            <label for="file" class="form-label fw-semibold">Pilih File</label>
                            <input type="file" id="file" name="file"
                                class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.csv,.txt,.odt,.ods">
                            <div class="form-text">
                                PDF, DOC/DOCX, XLS/XLSX, PPT/PPTX, ZIP, RAR, CSV, TXT, ODT, atau ODS. Maksimal 5 MB.
                            </div>
                            <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                    </div>

                </div>


                
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Thumbnail (kosongkan jika tidak ada thumbnail)
                        </h5>

                        <p class="text-muted small mb-4">
                            Upload thumbnail untuk dokumen ini.
                        </p>

                        <div class="mb-3">

                            <div id="imagePreview"
                                class="rounded overflow-hidden bg-light d-flex align-items-center justify-content-center mb-3"
                                style="height: 220px;">

                                <div class="text-center text-muted">

                                    <i class="bi bi-image fs-1 d-block mb-2"></i>

                                    <small>
                                        No image selected
                                    </small>

                                </div>

                            </div>

                            <input type="file" id="thumbnail" name="thumbnail"
                                class="form-control <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                accept=".jpg,.jpeg,.png,.webp">

                            <div class="form-text">
                                Ukuran thumbnail 400 x 575px dengan format JPG, JPEG, PNG, or WEBP. Maksimal 1 MB.
                            </div>

                            <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                    </div>

                </div>


                
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Status
                        </h5>

                        <p class="text-muted small mb-4">
                            Atur status publikasi dokumen ini..
                        </p>

                        <div class="mb-3">

                            <label for="status" class="form-label fw-semibold">
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select id="status" name="status"
                                class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>

                                <option value="active" <?php echo e(old('status', 'active') === 'active' ? 'selected' : ''); ?>>
                                    Aktif
                                </option>

                                <option value="inactive" <?php echo e(old('status') === 'inactive' ? 'selected' : ''); ?>>
                                    Tidak Aktif
                                </option>

                            </select>

                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                    </div>

                </div>


                
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-check-lg me-1"></i>
                            Simpan Dokumen
                        </button>

                        <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-light w-100">

                            Batal

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</section>



<script>
    document.getElementById('thumbnail').addEventListener('change', function(event) {

        const file = event.target.files[0];

        const preview = document.getElementById('imagePreview');

        if (!file) {

            preview.innerHTML = `
            <div class="text-center text-muted">
                <i class="bi bi-image fs-1 d-block mb-2"></i>
                <small>No image selected</small>
            </div>
        `;

            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {

            preview.innerHTML = `
            <img src="${e.target.result}"
                alt="Document Thumbnail Preview"
                class="w-100 h-100"
                style="object-fit: cover;">
        `;

        };

        reader.readAsDataURL(file);

    });

        function loadSubcategories(selectedSubcategoryUuid = '') {
            const categorySelect = document.getElementById('category_uuid');
            const categoryUuid = categorySelect.value;
            const subcategorySelect = document.getElementById('subcategory_uuid');
            const subcategoryOptions = subcategorySelect.querySelectorAll('option:not([value=""])');

            subcategoryOptions.forEach(option => option.remove());

            if (categoryUuid) {
                fetch('<?php echo e(route("documents.getSubcategories")); ?>?category_uuid=' + categoryUuid)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.uuid;
                            option.textContent = subcategory.name;
                            option.selected = subcategory.uuid === selectedSubcategoryUuid;
                            subcategorySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading subcategories:', error));
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_uuid');
            categorySelect.addEventListener('change', () => loadSubcategories());
            loadSubcategories(<?php echo json_encode(old('subcategory_uuid', ''), 512) ?>);

            const fileSourceSelect = document.getElementById('file_source');
            const fileUrlField = document.getElementById('fileUrlField');
            const fileUploadField = document.getElementById('fileUploadField');
            const fileUrlInput = document.getElementById('file_url');
            const fileInput = document.getElementById('file');
            const updateFileFields = () => {
                const useUrl = fileSourceSelect.value === 'url';
                fileUrlField.classList.toggle('d-none', !useUrl);
                fileUploadField.classList.toggle('d-none', useUrl);
                fileUrlInput.required = useUrl;
                fileInput.required = !useUrl;
            };
            fileSourceSelect.addEventListener('change', updateFileFields);
            updateFileFields();
        });

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\documents\create.blade.php ENDPATH**/ ?>