<!-- Modal -->
<div class="modal fade" id="lockUserModal" tabindex="-1" aria-labelledby="lockUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-lock-user-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-lock-user-show-content" style="display: none">
                <div class="modal-header text-bg-danger">
                    <h1 class="modal-title fs-5" id="lockUserModalLabel">Konfirmasi Penguncian User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-lock-user">
                        <input id="lock-user-id" type="hidden" name="id">
                        <div class="mb-3 text-center">
                            <strong>Anda akan mengunci dan menonaktifkan user ini!</strong>
                        </div>
                        <div class="mb-3">
                            <label for="lock-user-name" class="form-label">Nama</label>
                            <div class="form-control" id="lock-user-name"></div>
                        </div>
                        <div class="mb-3">
                            <label for="lock-user-username" class="form-label">Username</label>
                            <div class="form-control" id="lock-user-username"></div>
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
