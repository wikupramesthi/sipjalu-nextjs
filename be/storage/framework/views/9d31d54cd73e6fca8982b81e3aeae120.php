<div class="modal fade" id="cekProfilModal-<?php echo e($user->uuid); ?>" tabindex="-1"
    aria-labelledby="cekProfilModalLabel-<?php echo e($user->uuid); ?>" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            
            <div class="modal-header border-0 px-4 pt-4 pb-3">

                <div class="d-flex align-items-center gap-3">

                    
                    <div class="rounded-circle bg-primary-subtle
                                d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px;">

                        <span class="fw-bold text-primary fs-5">
                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        </span>

                    </div>

                    <div>
                        <h5 class="modal-title fw-semibold mb-1" id="cekProfilModalLabel-<?php echo e($user->uuid); ?>">
                            Member Overview
                        </h5>

                        <small class="text-muted">
                            <?php echo e($user->name); ?>

                        </small>
                    </div>

                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>


            
            <div class="modal-body px-4 pb-4">

                
                <div class="mb-4">

                    <div class="mb-3">
                        <h6 class="fw-semibold mb-1">
                            Personal Information
                        </h6>

                        <small class="text-muted">
                            Basic member information
                        </small>
                    </div>


                    <div class="row g-3">

                        
                        <div class="col-md-6">
                            <div class="bg-primary-subtle rounded-3 p-3 h-100">

                                <small class="text-primary d-block mb-1">
                                    Full Name
                                </small>

                                <div class="fw-semibold text-dark">
                                    <?php echo e($user->name); ?>

                                </div>

                            </div>
                        </div>


                        
                        <div class="col-md-6">
                            <div class="bg-info-subtle rounded-3 p-3 h-100">

                                <small class="text-info-emphasis d-block mb-1">
                                    Email Address
                                </small>

                                <div class="fw-semibold text-dark text-break">
                                    <?php echo e($user->email); ?>

                                </div>

                            </div>
                        </div>


                        
                        <div class="col-md-6">
                            <div class="bg-success-subtle rounded-3 p-3 h-100">

                                <small class="text-success d-block mb-1">
                                    Phone Number
                                </small>

                                <div class="fw-semibold text-dark">
                                    <?php echo e($user->no_hp ?? '-'); ?>

                                </div>

                            </div>
                        </div>


                        
                        <div class="col-md-6">
                            <div class="bg-warning-subtle rounded-3 p-3 h-100">

                                <small class="text-warning-emphasis d-block mb-2">
                                    Status Verifikasi
                                </small>

                                <?php if($user->email_verified_at): ?>
                                    <span class="badge rounded-pill bg-success px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        Terverifikasi
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-secondary px-3 py-2">
                                        <i class="bi bi-dash-circle-fill me-1"></i>
                                        Belum Verifikasi
                                    </span>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>

                </div>


                
                <hr class="my-4">


                
                <div>

                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <div>
                            <h6 class="fw-semibold mb-1">
                                Informasi Akun
                            </h6>

                            <small class="text-muted">
                                Data pendaftaran akun
                            </small>
                        </div>

                    </div>


                    
                    <div class="row g-3">

                        
                        <div class="col-md-6">

                            <div class="bg-light border rounded-3 p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Tanggal Daftar
                                </small>

                                <div class="fw-semibold">
                                    <?php echo e($user->created_at ? $user->created_at->translatedFormat('d F Y H:i') : '-'); ?>

                                </div>

                            </div>

                        </div>


                        
                        <div class="col-md-6">

                            <div class="bg-light border rounded-3 p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Peran
                                </small>

                                <div class="fw-semibold">
                                    <?php echo e($user->getRoleNames()->first() ?? '-'); ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            
            <div class="modal-footer border-0 px-4 pb-4 pt-0">

                <button type="button" class="btn btn-danger border px-4" data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>

    </div>

</div>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\dashboard\modal-detail-user.blade.php ENDPATH**/ ?>