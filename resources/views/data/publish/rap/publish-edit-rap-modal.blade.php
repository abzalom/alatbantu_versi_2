<!-- Modal -->
<div class="modal fade" id="editRapModal" tabindex="-1" aria-labelledby="editRapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editRapModalLabel">Laporan RAP</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3">
                        <label for="editSubkegiatan" class="form-label fw-bold">Sub Kegiatan</label>
                        <textarea type="text" class="form-control" id="editSubkegiatan" placeholder="Sub Kegiatan RAP" rows="4" disabled readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editIndikator" class="form-label fw-bold">Indikator</label>
                        <textarea type="text" class="form-control" id="editIndikator" placeholder="Indikator Keluaran" rows="4" disabled readonly></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-label fw-bold">Target Kinerja</div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="editKinerja" placeholder="Kinerja" disabled readonly>
                            <span class="input-group-text edit-satuan">@</span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-label fw-bold">Realisasi Kinerja</div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="editRealisasiKinerja" placeholder="Realisasi Kinerja">
                            <span class="input-group-text edit-satuan">@</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-label fw-bold">Target Anggaran</div>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" class="form-control" id="editAnggaran" placeholder="Target Anggaran" disabled readonly>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-label fw-bold">Realisasi Anggaran</div>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" class="form-control" id="editRealisasiAnggaran" placeholder="Realisasi Anggaran">
                        </div>
                    </div>
                </div>
                <hr>
                <h5 class="text-center mb-3">Upload Dokumentasi Visual</h5>
                <div class="row" id="displayImg">
                    <input type="file" name="img_dok" id="input_img" multiple hidden>
                    <div class="card" id="uploadContainer" role="button" onclick="document.getElementById('input_img').click()">
                        <div class="card-body text-center align-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
