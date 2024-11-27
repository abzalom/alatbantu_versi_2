<!-- Modal -->
<div class="modal fade" id="resetPasswordUserModal" tabindex="-1" aria-labelledby="resetPasswordUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-reset-password-user-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-reset-password-user-show-content" style="display: none">
                <div class="modal-header text-bg-danger">
                    <h1 class="modal-title fs-5" id="resetPasswordUserModalLabel">Konfirmasi Reset Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-reset-password-user">
                        <input id="reset-password-user-id" type="hidden" name="id">
                        <div class="mb-3 text-center">
                            <strong>Anda akan mereset ulang password user</strong>
                        </div>
                        <div class="mb-3">
                            <label for="reset-password-user-name" class="form-label">Nama</label>
                            <div class="form-control" id="reset-password-user-name"></div>
                        </div>
                        <div class="mb-3">
                            <label for="reset-password-user-username" class="form-label">Username</label>
                            <div class="form-control" id="reset-password-user-username"></div>
                        </div>
                        <div class="mt-5 mb-2 text-center">
                            <button type="button" class="btn btn-secondary me-5" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger ms-5">Yakin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
