<x-app-layout-component :app="$app">
    <div class="col-sm-12 col-md-12 col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ isset($app) && $app['desc'] ? $app['desc'] : 'Pengaturan Tim Pembahas' }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ $team ? route('bappeda.update', $team->id) : route('bappeda.store') }}" method="post">
                    @csrf
                    @if ($team)
                        @method('PUT')
                    @endif
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap & Titile * <small>(Wajib diisi)</small></label>
                        <input type="text" value="{{ old('nama', $team->nama ?? '') }}" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Contoh: Drs. H. Nama Anda, M.Si">
                        @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP <small>(Opsional)</small></label>
                        <input type="text" value="{{ old('nip', $team->nip ?? '') }}" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" placeholder="Contoh: 196501011990031001 (18 digit tanpa spasi)">
                        @error('nip')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan <small>(Opsional)</small></label>
                        <input type="text" value="{{ old('jabatan', $team->jabatan ?? '') }}" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" placeholder="Contoh: Kepala Bidang Perencanaan">
                        @error('jabatan')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Peran</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                            <option value="">Pilih Peran</option>
                            <option value="ketua" {{ old('role', $team->role ?? '') == 'ketua' ? 'selected' : '' }}>Ketua</option>
                            <option value="anggota" {{ old('role', $team->role ?? '') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                        </select>
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="mb-3">
                            <a href="{{ route('bappeda.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('v1-1.config.tim_pembahas.bappeda.script-tim-pembahas')
</x-app-layout-component>
