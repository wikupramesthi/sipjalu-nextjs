<!-- Modals add menu -->
<div id="modal-form-add-user" class="modal fade modal-form-user" tabindex="-1" aria-labelledby="modal-form-add-user-label"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="modal-form" action="{{ route('user.store') }}" method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-form-add-user-label">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            placeholder="User Name" name="name">
                        @error('name')
                            <div class='invalid-feedback'>
                                {{ $message }}
                            </div>
                            </a>
                        @enderror
                        {{-- <x-form.validation.error name="name" /> --}}
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            placeholder="Email" name="email">
                        @error('email')
                            <div class='invalid-feedback'>
                                {{ $message }}
                            </div>
                            </a>
                        @enderror
                        {{-- <x-form.validation.error name="email" /> --}}
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role Name</label>
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role"
                            data-choices data-choices-removeItem>
                            <option value="" disabled selected>Choose</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role') == $role->name)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('role')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" placeholder="Minimal 12 karakter" name="password"
                            autocomplete="new-password" minlength="12" required>
                        <small class="text-muted">Wajib 12+ karakter dengan huruf besar, huruf kecil, dan angka.</small>
                        @error('password')
                            <div class='invalid-feedback'>
                                {{ $message }}
                            </div>
                        @enderror
                        {{-- <x-form.validation.error name="password" /> --}}
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <label for="verified" class="form-label">Verified</label>
                            <input class="form-check-input code-switcher" type="checkbox" id="tables-small-showcode"
                                name="verified" value="1">
                        </div>
                        {{-- <x-form.validation.error name="verified" /> --}}
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ">Simpan</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
