<!-- User Upload Modal File -->
<div class="modal fade" id="userEditRapModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userEditRapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-edit-rap-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-edit-rap-show-content" style="display: none">
                <div class="modal-header text-bg-primary">
                    <h1 class="modal-title fs-5" id="userEditRapModalLabel">Modal Title</h1>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <form id="form-user-edit-rap" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="user-edit-rap-id" name="id_rap">
                        <h5 id="id_rap_error" class="text-danger"></h5>
                        <div class="row">
                            <div class="col-sm-12 mb-4 me-2">
                                <div class="fw-bold mb-2">
                                    Indikator
                                </div>
                                <div id="user-rap-show-indikator">
                                </div>
                            </div>
                            <div class="col-sm-5 mb-4 me-2">
                                <div class="fw-bold mb-2">
                                    Sumber Pendanaan
                                </div>
                                <div id="user-rap-show-sumberdana">
                                </div>
                            </div>
                            <div class="col-sm-5 mb-4 me-2">
                                <div class="fw-bold mb-2">
                                    Pagu Dana
                                </div>
                                <div id="user-rap-show-anggaran">
                                </div>
                            </div>
                            <div class="col-sm-5 mb-4 me-2">
                                <div class="fw-bold mb-2">
                                    Jenis
                                </div>
                                <div id="user-rap-show-jenis-kegiatan">
                                </div>
                                <input type="hidden" id="rap-user-edit-jenis-kegiatan" name="jenis_kegiatan">
                            </div>
                            <div class="col-sm-5 mb-4 me-2">
                                <label for="rap-user-edit-target-kinerja" class="form-label fw-bold">Target Kinerja (Wajib)</label>
                                <div class="input-group">
                                    <input name="vol_subkeg" type="text" class="form-control" id="rap-user-edit-target-kinerja" placeholder="Target Kinerja">
                                    <span class="input-group-text" id="user-rap-show-satuan">Unit</span>
                                    <small id="vol_subkeg_error" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-4">
                                <div class="mb-3">
                                    <label for="rap-user-edit-lokus" class="form-label fw-bold">Lokasi Fokus Pelaksaan (Wajib)</label>
                                    <select name="lokus[]" class="form-control select2-multiple" id="rap-user-edit-lokus" data-placeholder="Pilih..." multiple>
                                        <option></option>
                                        @foreach ($lokasi as $lokus)
                                            <option value="{{ $lokus->id }}">{{ $lokus->text }}</option>
                                        @endforeach
                                    </select>
                                    <small id="lokus_error" class="text-danger"></small>
                                </div>
                            </div>
                            <div id="user-rap-show-jenis" class="col-sm-12 mb-4" style="display: none">
                                <div class="mb-3">
                                    <label for="user-rap-edit-koordinat" class="form-label">
                                        <span class="fw-bold">Koordinat</span>
                                        &nbsp;<small class="text-muted">(pekerjaan fisik wajib memasukan koordinat lokasi pekerjaan)</small>
                                        <br>
                                        <a href="https://map.google.com" class="form-label" target="_blank">Google Map <i class="fa-solid fa-up-right-from-square fa-2xs"></i></a>
                                    </label>
                                    <textarea name="koordinat" class="form-control" id="user-rap-edit-koordinat" rows="3" disabled placeholder="Kegiatan fisik wajib memasukan koordinat lokasi kegiatan"></textarea>
                                    <small id="koordinat_error" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-4">
                                <div class="mb-3">
                                    <label for="user-rap-edit-keterangan" class="form-label fw-bold">Keterangan (Wajib)</label>
                                    <textarea name="keterangan" class="form-control" id="user-rap-edit-keterangan" rows="3" placeholder="Ketarangan wajib untuk mengetahui maksud, tujuan dan capaian hasil kegiatan"></textarea>
                                    <small id="keterangan_error" class="text-danger"></small>
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
</div>
