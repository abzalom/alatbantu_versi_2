<!-- Modal -->
<div class="modal fade" id="pengaturanPaguSkpdModal" tabindex="-1" aria-labelledby="pengaturanPaguSkpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-pengaturan-pagu-skpd-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-pengaturan-pagu-skpd-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="pengaturanPaguSkpdModalLabel">Pengaturan Batasan Pagu SKPD</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id_opd" id="id_opd">
                        {{-- <div class="mb-3">
                            <label for="sumberdana" class="form-label">Sumber Pendanaan</label>
                            <select name="sumberdana" class="form-control" id="sumberdana">
                                <option value="">Pilih...</option>
                                <option value="bg">OTSUS 1%</option>
                                <option value="sg">OTSUS 1,25%</option>
                                <option value="dti">DTI</option>
                            </select>
                            <span id="sumberdana_error" class="text-danger"></span>
                        </div> --}}
                        <div class="mb-3">
                            <label for="bg" class="form-label">Batasan Pagu Otsus 1% Bersifat Umum</label>
                            <div class="input-group">
                                <span class="input-group-text" id="rp-bg-icon">Rp</span>
                                <input name="bg" type="text" class="form-control format-angka" id="bg" placeholder="0.000" aria-describedby="rp-bg-icon">
                            </div>
                            <span id="bg_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="sg" class="form-label">Batasan Pagu Otsus 1,25% Bersifat Khusus</label>
                            <div class="input-group">
                                <span class="input-group-text" id="rp-sg-icon">Rp</span>
                                <input name="sg" type="text" class="form-control format-angka" id="sg" placeholder="0.000" aria-describedby="rp-sg-icon">
                            </div>
                            <span id="sg_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="dti" class="form-label">Batasan Pagu Dana Tambahan Infrastruktur</label>
                            <div class="input-group">
                                <span class="input-group-text" id="rp-dti-icon">Rp</span>
                                <input name="dti" type="text" class="form-control format-angka" id="dti" placeholder="0.000" aria-describedby="rp-dti-icon">
                            </div>
                            <span id="dti_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" id="keterangan" placeholder="Keterangan" rows="3"></textarea>
                            <span id="keterangan_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
