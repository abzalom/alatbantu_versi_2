<!-- Modal -->
<div class="modal fade" id="editHeaderSinkronDjpkSikdModal" tabindex="-1" aria-labelledby="editHeaderSinkronDjpkSikdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editHeaderSinkronDjpkSikdModalLabel">Header Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/sinkron/djpk/sikd/request/update-link" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" id="edit-header-request-id">
                    <div class="mb-3">
                        <label for="edit-header-request-name" class="form-label">Name</label>
                        <input name="name" type="text" class="form-control" id="edit-header-request-name" placeholder="Name">
                    </div>
                    <div class="mb-3">
                        <label for="edit-header-request-sumberdana" class="form-label">Sumberdana Dana</label>
                        <select name="sumberdana" class="form-control" id="edit-header-request-sumberdana">
                            <option value="">Pilih...</option>
                            <option value="bg">Block Grant 1%</option>
                            <option value="sg">Spesific Grant 1%</option>
                            <option value="dti">DTI</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-header-request-jenis" class="form-label">Jenis</label>
                        <select name="jenis" class="form-control" id="edit-header-request-jenis">
                            @if ($jenisRequest === 'nomenklatur')
                                <option value="nomenklatur">Nomenklatur</option>
                            @endif
                            @if ($jenisRequest === 'rap')
                                <option value="rap">Recana Anggaran Program (RAP)</option>
                            @endif
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="edit-header-request-method" class="form-label">Method</label>
                                <select name="method" class="form-control" id="edit-header-request-method">
                                    <option value="">Pilih...</option>
                                    <option value="get">GET</option>
                                    <option value="post">POST</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="mb-3">
                                <label for="edit-header-request-url" class="form-label">URL</label>
                                <input name="url" type="text" class="form-control" id="edit-header-request-url" placeholder="Input URL">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="edit-header-request-param_key" class="form-label">Param Key</label>
                                <input name="param_key" type="text" class="form-control" id="edit-header-request-param_key" placeholder="Key">
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="mb-3">
                                <label for="edit-header-request-param_value" class="form-label">Param Value</label>
                                <input name="param_value" type="text" class="form-control" id="edit-header-request-param_value" placeholder="Value">
                            </div>
                        </div>
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
