<!-- Modal -->
<div class="modal fade" id="newProgramRapppModal" tabindex="-1" aria-labelledby="newProgramRapppModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="newProgramRapppModalLabel">Tambah Target Kinerja Program RAPPP</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formNewProgramRappp" method="post">
                <input type="hidden" id="exists_check" name="exists_check">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="select-opd-rappp" class="form-label">Perangkat Daerah</label>
                        <select class="form-control" id="select-opd-rappp" data-placeholder="Pilih..." disabled>
                            <option value="{{ $opd->id }}" selected>{{ $opd->text }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="select-tema-rappp" class="form-label">Tema RAPPP</label>
                        <select class="form-control select2" id="select-tema-rappp" data-placeholder="Pilih...">
                        </select>
                        <span id="tema_error" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="select-program-rappp" class="form-label">Program RAPPP</label>
                        <select class="form-control select2" id="select-program-rappp" data-placeholder="Pilih..." disabled>
                        </select>
                        <span id="program_error" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="select-target_aktifitas-rappp" class="form-label">Target Aktifitas Utama RAPPP</label>
                        <select name="target_aktifitas" class="form-control select2" id="select-target_aktifitas-rappp" data-placeholder="Pilih..." disabled>
                        </select>
                        <span id="target_aktifitas_error" class="text-danger"></span>
                    </div>
                    <div id="satuan-exists" style="display: none;">
                        <div class="mb-3">
                            <label for="input-volume_target_aktifitas-rappp" class="form-label">Volume</label>
                            <div class="input-group">
                                <input name="volume_target_aktifitas" type="text" class="form-control format-angka" id="input-volume_target_aktifitas-rappp" placeholder="Volume" aria-describedby="satuan_target_aktifitas-addon" disabled>
                                <span class="input-group-text" id="satuan_target_aktifitas-addon">Satuan</span>
                            </div>
                            <span id="volume_target_aktifitas_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div id="satuan-not-exists" style="display: none;">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="input-volume_target_aktifitas-rappp-satuan-not-exists" class="form-label">Volume</label>
                                    <div class="input-group">
                                        <input name="volume_target_aktifitas_satuan_not_exists" type="text" class="form-control format-angka" id="input-volume_target_aktifitas-rappp-satuan-not-exists" placeholder="Volume" aria-describedby="satuan_target_aktifitas-addon" disabled>
                                    </div>
                                    <span id="volume_target_aktifitas_satuan_not_exists_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="input-satuan_traget_aktifitas-rappp-satuan-not-exists" class="form-label">Satuan</label>
                                    <div class="input-group">
                                        <input name="satuan_traget_aktifitas_satuan_not_exists" type="text" class="form-control" id="input-satuan_traget_aktifitas-rappp-satuan-not-exists" placeholder="Volume" aria-describedby="satuan_target_aktifitas-addon" disabled>
                                    </div>
                                    <span id="satuan_traget_aktifitas_satuan_not_exists_error" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="select-sumberdana-rappp" class="form-label">Sumber Pendanaan</label>
                        <select name="sumberdana" class="form-control" id="select-sumberdana-rappp" data-placeholder="Pilih..." disabled>
                            <option value="">Pilih...</option>
                            <option value="bg">Otsus 1%</option>
                            <option value="sg">Otsus 1,25%</option>
                            <option value="dti">DTI</option>
                        </select>
                        <span id="sumberdana_error" class="text-danger"></span>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
