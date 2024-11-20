<!-- User Upload Modal File -->
<div class="modal fade" id="userUploadFileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userUploadFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div id="modal-show-spinner">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-show-content" style="display: none">
                <div class="modal-header text-bg-primary">
                    <h1 class="modal-title fs-5" id="userUploadFileModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/rap/opd/upload-data-dukung" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="user-rap-id-rap" name="id_rap">
                        <div class="mb-3">
                            <h5 class="mb-0 pb-0">Aturan File</h5>
                            <ul class="mt-0 pt-0">
                                <li>File dalam format PDF dan ukuran maksimal 5MB</li>
                            </ul>
                        </div>
                        <div class="row">
                            <div id="kak-form-control" class="mb-5 col-sm-12 col-md-12 col-lg-6" #>
                                <label for="user-rap-file-kak" class="form-label">Pilih File Kerangka Acuan Kerja (KAK) (wajib):</label>
                                <input name="file_kak" class="form-control" type="file" id="user-rap-file-kak" data-filename="">
                                <div id="kak-exists-show" style="display: none">
                                    <div class="row">
                                        <div class="col-1 align-content-center">
                                            <i id="kak-btn-remove" class="fa-solid fa-pen-to-square fa-sm btn-remove-file" style="color: #ff3131" data-id_rap="" data-filename="kak"></i>
                                        </div>
                                        <div class="col-11 align-middle">
                                            <small id="kak-name-show" class="text-primary">Nama File</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="rab-form-control" class="mb-5 col-sm-12 col-md-12 col-lg-6">
                                <label for="user-rap-file-rab" class="form-label">Pilih File Rincian Anggaran Belanja (RAB) (wajib):</label>
                                <input name="file_rab" class="form-control" type="file" id="user-rap-file-rab" data-filename="">
                                <div id="rab-exists-show" style="display: none">
                                    <div class="row">
                                        <div class="col-1 align-content-center">
                                            <i id="rab-btn-remove" class="fa-solid fa-pen-to-square fa-sm btn-remove-file" style="color: #ff3131" data-id_rap="" data-filename="rab"></i>
                                        </div>
                                        <div class="col-11 align-middle">
                                            <small id="rab-name-show" class="text-primary">Nama File</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="pendukung1-form-control" class="mb-5 col-sm-12 col-md-12 col-lg-6">
                                <label for="user-rap-file-pendukung1" class="form-label">Pilih File Pendukung Lainnya (Pilihan 1):</label>
                                <input name="file_pendukung1" class="form-control user-rap-file-pendukung" type="file" id="user-rap-file-pendukung1" data-filename="">
                                <div id="pendukung1-exists-show" style="display: none">
                                    <div class="row">
                                        <div class="col-1 align-content-center">
                                            <i id="pendukung1-btn-remove" class="fa-solid fa-pen-to-square fa-sm btn-remove-file" style="color: #ff3131" data-id_rap="" data-filename="pendukung1"></i>
                                        </div>
                                        <div class="col-11 align-middle">
                                            <small id="pendukung1-name-show" class="text-primary">Nama File</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="pendukung2-form-control" class="mb-5 col-sm-12 col-md-12 col-lg-6">
                                <label for="user-rap-file-pendukung2" class="form-label">Pilih File Pendukung Lainnya (Pilihan 2):</label>
                                <input name="file_pendukung2" class="form-control user-rap-file-pendukung" type="file" id="user-rap-file-pendukung2" data-filename="">
                                <div id="pendukung2-exists-show" style="display: none">
                                    <div class="row">
                                        <div class="col-1 align-content-center">
                                            <i id="pendukung2-btn-remove" class="fa-solid fa-pen-to-square fa-sm btn-remove-file" style="color: #ff3131" data-id_rap="" data-filename="pendukung2"></i>
                                        </div>
                                        <div class="col-11 align-middle">
                                            <small id="pendukung2-name-show" class="text-primary">Nama File</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="pendukung3-form-control" class="mb-5 col-sm-12 col-md-12 col-lg-6">
                                <label for="user-rap-file-pendukung3" class="form-label">Pilih File Pendukung Lainnya (Pilihan 3):</label>
                                <input name="file_pendukung3" class="form-control user-rap-file-pendukung" type="file" id="user-rap-file-pendukung3" data-filename="">
                                <div id="pendukung3-exists-show" style="display: none">
                                    <div class="row">
                                        <div class="col-1 align-content-center">
                                            <i id="pendukung3-btn-remove" class="fa-solid fa-pen-to-square fa-sm btn-remove-file" style="color: #ff3131" data-id_rap="" data-filename="pendukung3"></i>
                                        </div>
                                        <div class="col-11 align-middle">
                                            <small id="pendukung3-name-show" class="text-primary">Nama File</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="link-pendukung-form-control" class="mb-5 col-sm-12 col-md-12 col-lg-6">
                                <label for="user-rap-link-file" class="form-label">Link <i class="fa-brands fa-google-drive"></i> Google Drive (Jika ada)</label>
                                <input name="link_file_dukung_lain" type="url" class="form-control" id="user-rap-link-file" placeholder="Link Google Drive">
                                <small class="text-muted">Jika ada file pendukung lainnya yang berukuran besar bisa di simpan pada <i class="fa-brands fa-google-drive"></i> Google Drive dan masukan linknya di sini</small>
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
