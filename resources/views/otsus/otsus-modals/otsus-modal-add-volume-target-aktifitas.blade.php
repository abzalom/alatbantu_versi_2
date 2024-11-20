<!-- Modal -->
<div class="modal fade" id="addVolTargetAktifitasMmodal" tabindex="-1" aria-labelledby="addVolTargetAktifitasMmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addVolTargetAktifitasMmodalLabel">Nama Target</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-volume-target-aktifitas" method="post">
                <div class="modal-body">

                    <div id="target-aktifitas-spinner" class="text-center" style="display: none">
                        <div class="spinner-border" style="width: 5rem; height: 5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="target-aktifitas-fom-element" style="display: block">
                        <input id="id_target_aktifitas" type="hidden" name="id">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="target-aktifitas-input-volume" class="form-label">Volume</label>
                                    <input name="volume" type="text" class="form-control" id="target-aktifitas-input-volume" placeholder="Volume">
                                    <span id="volume_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="target-aktifitas-select-sumberdana" class="form-label">Sumber Dana</label>
                                    <select name="sumberdana" class="form-control" id="target-aktifitas-select-sumberdana">
                                        <option value="">Pilih...</option>
                                        @foreach ($sumberdanas as $sumberdana)
                                            <option value="{{ $sumberdana->uraian }}">{{ $sumberdana->uraian }}</option>
                                        @endforeach
                                    </select>
                                    <span id="sumberdana_error" class="text-danger"></span>
                                </div>
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
