<div class="modal fade" id="cekProfilModal-{{ $user->uuid }}" tabindex="-1"
    aria-labelledby="cekProfilModalLabel-{{ $user->uuid }}" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- ================= HEADER ================= --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">

                <div class="d-flex align-items-center gap-3">

                    {{-- Avatar --}}
                    <div class="rounded-circle bg-primary-subtle
                                d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px;">

                        <span class="fw-bold text-primary fs-5">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>

                    </div>

                    <div>
                        <h5 class="modal-title fw-semibold mb-1" id="cekProfilModalLabel-{{ $user->uuid }}">
                            Member Overview
                        </h5>

                        <small class="text-muted">
                            {{ $user->name }}
                        </small>
                    </div>

                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>


            {{-- ================= BODY ================= --}}
            <div class="modal-body px-4 pb-4">

                {{-- ================= PERSONAL INFORMATION ================= --}}
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

                        {{-- Full Name --}}
                        <div class="col-md-6">
                            <div class="bg-primary-subtle rounded-3 p-3 h-100">

                                <small class="text-primary d-block mb-1">
                                    Full Name
                                </small>

                                <div class="fw-semibold text-dark">
                                    {{ $user->name }}
                                </div>

                            </div>
                        </div>


                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="bg-info-subtle rounded-3 p-3 h-100">

                                <small class="text-info-emphasis d-block mb-1">
                                    Email Address
                                </small>

                                <div class="fw-semibold text-dark text-break">
                                    {{ $user->email }}
                                </div>

                            </div>
                        </div>


                        {{-- Phone --}}
                        <div class="col-md-6">
                            <div class="bg-success-subtle rounded-3 p-3 h-100">

                                <small class="text-success d-block mb-1">
                                    Phone Number
                                </small>

                                <div class="fw-semibold text-dark">
                                    {{ $user->no_hp ?? '-' }}
                                </div>

                            </div>
                        </div>


                        {{-- Verification Status --}}
                        <div class="col-md-6">
                            <div class="bg-warning-subtle rounded-3 p-3 h-100">

                                <small class="text-warning-emphasis d-block mb-2">
                                    Status Verifikasi
                                </small>

                                @if ($user->email_verified_at)
                                    <span class="badge rounded-pill bg-success px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary px-3 py-2">
                                        <i class="bi bi-dash-circle-fill me-1"></i>
                                        Belum Verifikasi
                                    </span>
                                @endif

                            </div>
                        </div>

                    </div>

                </div>


                {{-- ================= DIVIDER ================= --}}
                <hr class="my-4">


                {{-- ================= ACCOUNT ================= --}}
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


                    {{-- ================= ACCOUNT DETAILS ================= --}}
                    <div class="row g-3">

                        {{-- Registered Date --}}
                        <div class="col-md-6">

                            <div class="bg-light border rounded-3 p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Tanggal Daftar
                                </small>

                                <div class="fw-semibold">
                                    {{ $user->created_at ? $user->created_at->translatedFormat('d F Y H:i') : '-' }}
                                </div>

                            </div>

                        </div>


                        {{-- Role --}}
                        <div class="col-md-6">

                            <div class="bg-light border rounded-3 p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Peran
                                </small>

                                <div class="fw-semibold">
                                    {{ $user->getRoleNames()->first() ?? '-' }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================= FOOTER ================= --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0">

                <button type="button" class="btn btn-danger border px-4" data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>

    </div>

</div>
