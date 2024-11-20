<!-- Modal -->
<div class="modal fade" id="edit-opdTagIndikatorModal" tabindex="-1" aria-labelledby="edit-opdTagIndikatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="edit-opdTagIndikatorModalLabel">Tagging Target Aktifitas Utama</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-form-indikator-opd" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_indikator" id="edit-indikator-hidden-id">
                    <div id="opd-edit-tag-indikator-spinner" class="text-center" style="display: block">
                        <div class="spinner-border" style="width: 5rem; height: 5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="opd-edit-tag-indikator-form-body" style="display: none">
                        <input type="hidden" name="id_opd" value="{{ $opd->id }}">
                        <div class="mb-3">
                            <label for="edit-indikator-select-tema" class="form-label">Tema</label>
                            <select class="form-control select2" id="edit-indikator-select-tema" data-placeholder="Pilih..." disabled>
                                <option></option>
                                @foreach ($temas as $tema)
                                    <option value="{{ $tema->id }}" data-kode_tema="{{ $tema->kode_tema }}">{{ $tema->text }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-indikator-select-target-aktifitas" class="form-label">Target Aktifitas</label>
                            <textarea class="form-control" id="edit-indikator-select-target-aktifitas" placeholder="Terget Aktifitas Utama" disabled></textarea>
                            <span id="edit_id_indikator_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit-indikator-satuan" class="form-label">Satuan</label>
                                    <input type="text" class="form-control" id="edit-indikator-satuan" placeholder="Volume" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit-indikator-input-volume" class="form-label">Volume</label>
                                    <input type="text" name="volume_indikator" class="form-control" id="edit-indikator-input-volume" placeholder="Volume" disabled>
                                    <span id="edit_volume_indikator_error" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-indikator-select-sumberdana" class="form-label">Sumber Dana</label>
                            <select name="sumberdana_indikator" class="form-control select2" id="edit-indikator-select-sumberdana" data-placeholder="Pilih..." disabled>
                                <option></option>
                                <option value="Otsus 1%">OTSUS 1%</option>
                                <option value="Otsus 1,25%">OTSUS 1,25%</option>
                                <option value="DTI">DTI</option>
                            </select>
                            <span id="edit_sumberdana_indikator_error" class="text-danger"></span>
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
