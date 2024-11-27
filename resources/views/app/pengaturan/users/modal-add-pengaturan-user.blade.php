<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">


            <div id="modal-add-user-show-spinner" style="display: none">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-add-user-show-content" style="display: block">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addUserModalLabel">Tambah User Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-add-user" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add-user-name" class="form-label">Nama</label>
                            <input name="name" type="text" class="form-control" id="add-user-name" placeholder="Nama">
                            <small id="add_name_error" class="text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="add-user-username" class="form-label">Username</label>
                            <input name="username" type="text" class="form-control" id="add-user-username" placeholder="Username">
                            <small id="add_username_error" class="text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="add-user-email" class="form-label">Email (Pilihan)</label>
                            <input name="email" type="text" class="form-control" id="add-user-email" placeholder="name@example.com">
                            <small id="add_email_error" class="text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="add-user-phone" class="form-label">Telepone (Pilihan)</label>
                            <input name="phone" type="text" class="form-control" id="add-user-phone" placeholder="08XXX">
                            <small id="add_phone_error" class="text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="add-user-role" class="form-label">Role</label>
                            <select name="role" type="text" class="form-control" id="add-user-role">
                                <option value="">Pilih...</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            <small id="add_role_error" class="text-danger"></small>
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
