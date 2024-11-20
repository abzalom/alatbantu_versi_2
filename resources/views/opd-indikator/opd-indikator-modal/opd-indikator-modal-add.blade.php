<!-- Modal -->
<div class="modal fade" id="opdTagIndikatorModal" tabindex="-1" aria-labelledby="opdTagIndikatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="opdTagIndikatorModalLabel">Tagging Target Aktifitas Utama</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-indikator-otsus-opd" method="post">
                <div class="modal-body">

                    <div id="opd-tag-indikator-spinner" class="text-center" style="display: none">
                        <div class="spinner-border" style="width: 5rem; height: 5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="opd-tag-indikator-form-body" style="display: block">
                        <input type="hidden" name="id_opd" value="{{ $opd->id }}">
                        <input type="hidden" name="tahun" value="{{ session()->get('tahun') }}">
                        <div class="mb-3">
                            <label for="indikator-select-tema" class="form-label">Tema</label>
                            <select class="form-control select2" id="indikator-select-tema" data-placeholder="Pilih...">
                                <option></option>
                                @foreach ($temas as $tema)
                                    <option value="{{ $tema->id }}">{{ $tema->text }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="indikator-select-target-aktifitas" class="form-label">Target Aktifitas</label>
                            <select name="id_target_aktifitas" class="form-control select2" id="indikator-select-target-aktifitas" data-placeholder="Pilih..." disabled>
                                <option></option>
                            </select>
                            <span id="id_target_aktifitas_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="indikator-satuan" class="form-label">Satuan</label>
                                    <input type="text" class="form-control" id="indikator-satuan" placeholder="Volume" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="indikator-input-volume" class="form-label">Volume</label>
                                    <input type="text" name="volume_indikator" class="form-control" id="indikator-input-volume" placeholder="Volume" disabled>
                                    <span id="volume_indikator_error" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="indikator-select-sumberdana" class="form-label">Sumber Dana</label>
                            <select name="sumberdana_indikator" class="form-control select2" id="indikator-select-sumberdana" data-placeholder="Pilih..." disabled>
                                <option></option>
                                <option value="otsus 1%">OTSUS 1%</option>
                                <option value="otsus 1,25%">OTSUS 1,25%</option>
                                <option value="DTI">DTI</option>
                            </select>
                            <span id="sumberdana_indikator_error" class="text-danger"></span>
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
