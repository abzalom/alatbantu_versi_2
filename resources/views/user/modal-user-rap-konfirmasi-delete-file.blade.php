<!-- Modal -->
<div class="modal fade" id="konfirmasiDeleteFileRapModal" tabindex="-1" aria-labelledby="konfirmasiDeleteFileRapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="user-konfirmasi-delete-file-modal-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="user-konfirmasi-delete-file-modal-show-content" style="display: none">
                <div class="modal-header text-bg-danger">
                    <h1 class="modal-title fs-5" id="konfirmasiDeleteFileRapModalLabel">Konfirmasi Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-confirm-delete-file" action="/rap/opd/delete-data-dukung" method="post">
                        @csrf
                        <input type="hidden" id="hidden-delete-id" name="id_rap">
                        <input type="hidden" id="hidden-delete-filename" name="filename" value="file_kak_name">
                        <strong>Anda yakin ingin menghapus file ini?</strong>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td>Uraian</td>
                                    <td>:</td>
                                    <td id="konfimasi-delete-info-subkegiatan"></td>
                                </tr>
                                <tr>
                                    <td>File</td>
                                    <td>:</td>
                                    <td id="konfimasi-delete-info-file"></td>
                                </tr>
                            </tbody>
                        </table>
                        <strong>Masukan password anda untuk konfirmasi penghapusan file?</strong>
                        <div class="mb-3">
                            <label for="delete-konfirmasi-password" class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" id="delete-konfirmasi-password" placeholder="Konfirmasi Password Anda">
                            <small id="password_error" class="text-danger"></small>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btn-submit-delete-file" class="btn btn-primary">Korfirmasi</button>
                </div>
            </div>
        </div>
    </div>
</div>
