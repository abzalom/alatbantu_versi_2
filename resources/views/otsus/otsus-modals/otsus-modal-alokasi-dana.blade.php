<!-- Modal -->
<div class="modal fade" id="alokasDanaOtsusModal" tabindex="-1" aria-labelledby="alokasDanaOtsusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="alokasDanaOtsusModalLabel">Input Alokas Dana Otsus Dan DTI</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-post-alokasi-dana" method="POST">
                <div class="modal-body">
                    <div id="hidden-input">
                    </div>
                    <div class="mb-3">
                        <label for="tahun-input" class="form-label">Tahun Anggaran</label>
                        <select name="tahun" class="form-control" id="tahun-input">
                            @for ($i = 2022; $i <= now()->year + 1; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <span id="tahun_error" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="alokasi-bg-input" class="form-label">Otsus Block Grant 1%</label>
                        <input name="alokasi_bg" type="number" class="form-control" id="alokasi-bg-input" placeholder="Masukan Jumlah Alokasi BG 1%">
                        <span id="alokasi_bg_error" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="alokasi-sg-input" class="form-label">Otsus Specifict Grant 1%</label>
                        <input name="alokasi_sg" type="number" class="form-control" id="alokasi-sg-input" placeholder="Masukan Jumlah Alokasi SG 1.25%">
                        <span id="alokasi_sg_error" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="alokasi-dti-input" class="form-label">Dana Tambahan Infrastruktur (DTI)</label>
                        <input name="alokasi_dti" type="number" class="form-control" id="alokasi-dti-input" placeholder="Masukan Jumlah Alokasi DTI">
                        <span id="alokasi_dti_error" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="status-input" class="form-label">Status</label>
                        <select name="status" class="form-control" id="status-input">
                            <option value="realisasi">Realisasi</option>
                            <option value="indikatif">Indikatif</option>
                            <option value="perkiraan">Perkiraan</option>
                        </select>
                        <span id="status_error" class="text-danger"></span>
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
