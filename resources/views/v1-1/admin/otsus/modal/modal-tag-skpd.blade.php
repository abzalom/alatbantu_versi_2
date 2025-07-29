<!-- Modal -->
<div class="modal fade" id="tagSkpdModal" tabindex="-1" aria-labelledby="tagSkpdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="modal-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tagSkpdModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-tambah-skpd">
                        <input type="hidden" name="tahun" value="{{ session()->get('tahun') }}">
                        <input type="hidden" name="id_target_aktifitas" id="id-target-aktifitas">
                        <div class="mb-3">
                            <label for="select-opd" class="form-label">Perangkat Daerah</label>
                            <select name="opd" class="form-control select2" id="select-opd" data-placeholder="Pilih...">
                            </select>
                            <span class="text-danger" id="opd_error"></span>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-sm-12 col-md-7 col-lg-6">
                                <label for="input-vol-tag-opd" class="form-label">Volume</label>
                                <div class="input-group mb-3">
                                    <input name="volume" type="text" class="form-control" id="input-vol-tag-opd" placeholder="Volume Target" aria-describedby="show-satuan-target">
                                    <span class="input-group-text" id="show-satuan-target"></span>
                                </div>
                                <span class="text-danger" id="volume_error"></span>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-5 col-lg-6">
                                <label for="select-sumberdana" class="form-label">Sumber Dana</label>
                                <select name="sumberdana" class="form-control" id="select-sumberdana">
                                    <option value="">Pilih...</option>
                                    <option value="Otsus 1%">Otsus 1% (BG)</option>
                                    <option value="Otsus 1,25%">Otsus 1,25% (SG)</option>
                                    <option value="DTI">DTI</option>
                                </select>
                                <span class="text-danger" id="sumberdana_error"></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" id="btn-check-skpd" class="btn btn-sm btn-primary"><i class="fa-solid fa-circle-check"></i></button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-info">
                                <tr>
                                    <th>PERANGKAT DAERAH</th>
                                    <th>VOLUME</th>
                                    <th>SATUAN</th>
                                    <th>SUMBERDANA</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table-data-skpds">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Selesai</button>
                    {{-- <button type="button" class="btn btn-primary">Simpan</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>
