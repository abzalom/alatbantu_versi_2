<!-- Modal -->
<div class="modal fade" id="uploadFileIndikatorRapOpd" tabindex="-1" aria-labelledby="uploadFileIndikatorRapOpdLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="/rap/indikator/upload" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="opd" value="{{ $opd->id }}">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Pilih File</label>
                        <input name="file" class="form-control" type="file" id="formFile" accept=".xlsx">
                        <span class="text-muted">File harus berformat .xlsx</span>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
