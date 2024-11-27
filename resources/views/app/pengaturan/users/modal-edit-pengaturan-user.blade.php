<!-- Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-edit-user-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-edit-user-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editUserModalLabel">Edit User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-edit-user" method="post">
                    <div class="modal-body">
                        <input id="edit-user-id" type="hidden" name="id">
                        <div class="mb-3">
                            <label for="edit-user-name" class="form-label">Nama</label>
                            <input name="name" type="text" class="form-control" id="edit-user-name" placeholder="Nama">
                            <small id="edit_name_error" class="text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="edit-user-username" class="form-label">Username</label>
                            <input name="username" type="text" class="form-control" id="edit-user-username" placeholder="Username">
                            <small id="edit_username_error" class="text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="edit-user-email" class="form-label">Email</label>
                            <input name="email" type="text" class="form-control" id="edit-user-email" placeholder="name@example.com">
                            <small id="edit_email_error" class="text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="edit-user-phone" class="form-label">Telepone</label>
                            <input name="phone" type="text" class="form-control" id="edit-user-phone" placeholder="08XXX">
                            <small id="edit_phone_error" class="text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="edit-user-role" class="form-label">Role</label>
                            <select name="role" type="text" class="form-control" id="edit-user-role">
                                <option value="">Pilih...</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            <small id="edit_role_error" class="text-danger"></small>
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
