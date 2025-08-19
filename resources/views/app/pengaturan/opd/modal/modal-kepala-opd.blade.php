<!-- Modal -->
<div class="modal fade" id="listKepalaOpdModal" tabindex="-1" aria-labelledby="listKepalaOpdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="listKepalaOpdModalLabel">Daftar Kepala OPD</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h5 class="listKepalaOpdModalDescription"></h5>
                </div>
                <div class="mb-3">
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kepalaOpdModal">Tambah Kepala OPD</button>
                </div>
                <div class="mb-3">
                    <table class="table table-sm table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Detail Kepala</th>
                                <th>Jabatan</th>
                                <th>Tahun</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="listKepalaOpdModalList">
                            <!-- Data akan dimasukkan di sini melalui JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Selesai</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="kepalaOpdModal" tabindex="-1" aria-labelledby="kepalaOpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-bg-info">
                <h1 class="modal-title fs-5" id="kepalaOpdModalLabel">Tambah Kepala OPD</h1>
                <button type="button" class="btn-close" data-bs-toggle="modal" data-bs-target="#listKepalaOpdModal"></button>
            </div>
            <form method="post" id="formKepalaOpd">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id_opd" id="id_opd">
                    <div class="mb-3">
                        <h5 class="listKepalaOpdModalDescription"></h5>
                    </div>
                    <div class="mb-3">
                        <label for="nama_kepala_opd" class="form-label">Nama Kepala OPD</label>
                        <input name="nama" type="text" class="form-control" id="nama_kepala_opd" name="nama_kepala_opd" placeholder="Masukkan Nama Kepala OPD">
                        <span id="nama_error" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="nip_kepala_opd" class="form-label">NIP Kepala OPD</label>
                        <input name="nip" type="text" class="form-control" id="nip_kepala_opd" name="nip_kepala_opd" placeholder="Masukkan NIP Kepala OPD">
                        <span id="nip_error" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="pangkat" class="form-label">Pangkat</label>
                        <select name="pangkat" class="form-control select2" id="pangkat" data-placeholder="Pilih...">
                            <option value=""></option>
                            @foreach ($pangkats as $pangkat)
                                <option value="{{ $pangkat->value }}">
                                    {{ $pangkat->value }}
                                </option>
                            @endforeach
                        </select>
                        <span id="pangkat_error" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select name="jabatan" class="form-control select2" id="jabatan" data-placeholder="Pilih...">
                            <option value=""></option>
                            <option value="kepala">Kepala</option>
                            <option value="direktur">Direktur</option>
                            <option value="kepala_unit">Kepala Unit</option>
                        </select>
                        <span id="jabatan_error" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="status_jabatan" class="form-label">Status Jabatan</label>
                        <select name="status_jabatan" class="form-control select2" id="status_jabatan" data-placeholder="Pilih...">
                            <option value=""></option>
                            <option value="plt">Pelaksana Tugas</option>
                            <option value="definitif">Definitif</option>
                        </select>
                        <span id="status_jabatan_error" class="text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#listKepalaOpdModal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
