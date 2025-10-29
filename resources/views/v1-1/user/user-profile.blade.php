<x-app-layout-component :app="$app">

    <script>
        let user = @json($user);
        console.log(user);
    </script>

    {{-- display error --}}
    @if ($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
                <strong>Terjadi Kesalahan:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- kecil saja: samakan tinggi baris feedback agar grid tidak lompat --}}
    <style>
        .invalid-feedback.d-block {
            min-height: 1.25rem;
        }

        /* kira-kira 1 baris teks */
    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-sm-12">
                <div class="card border-0 rounded-4">
                    <div class="card-body py-4 px-5">
                        <div class="row align-items-center">
                            <!-- Left: Photo, Name, Roles -->
                            <div id="user-profile-left" class="col-md-4 text-center border-end d-flex flex-column align-items-center justify-content-center" style="min-height:340px;">
                                <img src="{{ $user->profile_photo_url ?? asset('/assets/img/mamberamo_raya_resize_250_x_251.png') }}" alt="Profile Photo" class="rounded-circle mb-3" width="120" height="120">
                                <h3 class="mb-1 fw-bold">{{ $user->name }}</h3>
                                <span class="text-muted d-block mb-2">{{ $user->email }}</span>
                                <div class="mt-3">
                                    <span class="fw-semibold text-secondary small">Roles:</span>
                                    <div class="d-flex flex-wrap justify-content-center mt-1 gap-1">
                                        @forelse($user->getRoleNames() as $role)
                                            <span class="badge rounded-pill bg-gradient bg-primary px-3 py-2 shadow-sm">{{ $role }}</span>
                                        @empty
                                            <span class="badge bg-secondary">-</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Tabs and Forms -->
                            <div class="col-md-8">
                                <ul class="nav nav-tabs mb-3" id="profileTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if ($tab === 'detail') active @endif" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button" role="tab" aria-controls="detail" aria-selected="{{ $tab === 'detail' ? 'true' : 'false' }}">Detail</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if ($tab === 'password') active @endif" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="{{ $tab === 'password' ? 'true' : 'false' }}">Password</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="profileTabContent">
                                    <!-- Detail Tab -->
                                    <div class="tab-pane fade @if ($tab === 'detail') show active @endif" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                        <form id="form-profile" method="POST" action="/user/profile">
                                            @csrf
                                            <div class="row g-3"><!-- gunakan gap grid -->
                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                    <div class="mb-1">
                                                        <label for="name" class="form-label">Nama</label>
                                                        <div class="input-group">
                                                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid bg-white @else bg-light @enderror" @unless ($errors->has('name')) readonly @endunless placeholder="Nama Lengkap">
                                                            @error('name')
                                                                <span class="input-group-text bg-white text-danger"><i class="bi bi-exclamation-circle"></i></span>
                                                            @enderror
                                                        </div>
                                                        <div class="invalid-feedback d-block {{ $errors->has('name') ? '' : 'invisible' }}">
                                                            {{ $errors->first('name') ?? 'placeholder' }}
                                                        </div>
                                                    </div>
                                                    <div class="mb-1">
                                                        <label for="username" class="form-label">Username</label>
                                                        <input type="text" id="username" name="username" value="{{ $user->username }}" class="form-control bg-light" readonly disabled>
                                                        <div class="invalid-feedback d-block invisible">placeholder</div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">

                                                    <div class="mb-1">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid bg-white @else bg-light @enderror" placeholder="example@email.com" readonly>
                                                        <div class="invalid-feedback d-block {{ $errors->has('email') ? '' : 'invisible' }}">
                                                            {{ $errors->first('email') ?? 'placeholder' }}
                                                        </div>
                                                    </div>

                                                    <div class="mb-1">
                                                        <label for="phone" class="form-label">Phone</label>
                                                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid bg-white @else bg-light @enderror" @unless ($errors->has('phone')) readonly @endunless placeholder="0812xxxx">
                                                        <div class="invalid-feedback d-block {{ $errors->has('phone') ? '' : 'invisible' }}">
                                                            {{ $errors->first('phone') ?? 'placeholder' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end gap-2 mt-2">
                                                <button type="button" class="btn btn-outline-primary rounded-pill px-4 btn-edit-profile @if ($errors->any()) d-none @endif">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit Profile
                                                </button>
                                                <button type="button" class="btn btn-secondary rounded-pill px-4 btn-cancel-profile @if ($errors->any()) d-inline-block @else d-none @endif">
                                                    <i class="bi bi-x-circle me-2"></i>Batal
                                                </button>
                                                <button type="submit" class="btn btn-success rounded-pill px-4 btn-save-profile @if ($errors->any()) d-inline-block @else d-none @endif">
                                                    <i class="bi bi-save me-2"></i>Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Password Tab -->
                                    <div class="tab-pane fade @if ($tab === 'password') show active @endif" id="password" role="tabpanel" aria-labelledby="password-tab">
                                        <form id="form-password" method="POST" action="/user/profile/password_change">
                                            @csrf
                                            <h5 class="fw-bold mb-3"><i class="bi bi-key me-2"></i>Ubah Password</h5>

                                            <div class="mb-3">
                                                <label for="current_password" class="form-label">Password Lama</label>
                                                <input type="password" id="current_password" name="current_password" class="form-control bg-white border-dark @error('current_password') is-invalid @enderror" required>
                                                <div class="invalid-feedback d-block {{ $errors->has('current_password') ? '' : 'invisible' }}">
                                                    {{ $errors->first('current_password') ?? 'placeholder' }}
                                                </div>
                                            </div>

                                            <div class="row g-3 mb-1">
                                                <div class="col-md-6">
                                                    <label for="new_password" class="form-label">Password Baru</label>
                                                    <input type="password" id="new_password" name="new_password" class="form-control bg-white border-dark @error('new_password') is-invalid @enderror" required>
                                                    <div class="invalid-feedback d-block {{ $errors->has('new_password') ? '' : 'invisible' }}">
                                                        {{ $errors->first('new_password') ?? 'placeholder' }}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control bg-white border-dark @error('new_password_confirmation') is-invalid @enderror" required>
                                                    <div class="invalid-feedback d-block {{ $errors->has('new_password_confirmation') ? '' : 'invisible' }}">
                                                        {{ $errors->first('new_password_confirmation') ?? 'placeholder' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-warning rounded-pill px-4">Update Password</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.row -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('v1-1.user.script-user-profile')
</x-app-layout-component>
