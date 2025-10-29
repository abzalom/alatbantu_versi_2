$(document).ready(function () {
    $('.btn-edit-role').on('click', function() {
        var data = $(this).data('role');
        console.log(data);
        $('#btn-save-role').removeClass('btn-primary').addClass('btn-warning').html('Update');
        $('#form_name').val('role_edit_form');
        $('#role_name').val(data.name);
        $('#guard_name').val(data.guard_name);
        $('#permission').val([...data.permissions.map(p => p.name)]).trigger('change');
        $('#form-create-role').attr('action', '/config/roles/' + data.id);
        $('#role_form_method').val('PUT');
        $('#btn-cancel-role').show();
    });

    $('#btn-cancel-role, #permissions-tab').on('click', function() {
        $('#btn-save-role').removeClass('btn-warning').addClass('btn-primary').html('Simpan');
        $('#form_name').val('role_form');
        $('#role_name').val('');
        $('#guard_name').val('web');
        $('#permission').val([]).trigger('change');
        $('#form-create-role').attr('action', '/config/roles');
        $('#role_form_method').val('POST');
        $('#btn-cancel-role').hide();
    });

    $('#btn-role-tab').on('click', function () {
        // reset permission form
        $('#form-create-permission').trigger('reset');
    });

    $('#roles-tab').on('click', function () {
       $('#permission_name').val('');
    });
});