<!-- Modal -->
<div class="modal fade" id="rapUploadSubkegiatanModal" tabindex="-1" aria-labelledby="rapUploadSubkegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="rapUploadSubkegiatanModalLabel">UPLOAD FILE SUB KEGIATAN <br> {{ $opd->nama_opd }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/rap/opd/upload-subkegiatan" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="opd" value="{{ $opd->id }}">
                    <h5 class="text-muted">File harus berformat .xlsx</h5>
                    <div class="mb-3 mt-4">
                        <label for="rapFormUploadFileSubkegiatan" class="form-label">Pilih File</label>
                        <input name="rap_file" class="form-control" type="file" id="rapFormUploadFileSubkegiatan" accept=".xlsx">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
