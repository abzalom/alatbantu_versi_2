$(document).ready(function () {
    $('#search-user-input').on('focus', function () {
        $(this).removeClass('border-primary');
    });
    $('#search-user-input').on('focusout', function () {
        $(this).addClass('border-primary');
    });

    $('#search-user-input').on('keyup', function () {
        let value = $(this).val().toLowerCase();
        $("#table-list-user tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $('#search-user-select-skpd').on('keyup', function () {
        let value = $(this).val().toLowerCase();
        $("#show-list-select-skpd tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $('#addUserModal').on('hidden.bs.modal', function () {
        $('#modal-add-user-show-spinner').hide();
        $('#modal-add-user-show-content').show();
        $(this).find('form')[0].reset();
    });

    ['name', 'username', 'email', 'phone'].forEach(function (field) {
        $('#add-user-' + field).on('keyup', function () {
            if ($(this).val() !== '') {
                $('#add_' + field + '_error').html('');
                $(this).removeClass('is-invalid');
            }
        });
    });

    $('#add-user-role').on('change', function () {
        $('#add_role_error').html('');
        $(this).removeClass('is-invalid');
    });

    $('#form-add-user').on('submit', function (e) {
        e.preventDefault();
        $('#modal-add-user-show-spinner').show();
        $('#modal-add-user-show-content').hide();

        let dataForm = $(this).serialize();
        console.log(dataForm);

        ['name', 'username', 'email', 'phone', 'role'].forEach(function (field) {
            $('#add_' + field + '_error').html('');
        });

        $.ajax({
            type: "POST",
            url: "/api/data/user/create",
            data: dataForm,
            contentType: "application/x-www-form-urlencoded",
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    showToast(response.message, response.alert);
                    setTimeout(() => {
                        return window.location.href = 'http://localhost:8080/config/user';
                    }, 1500);
                }
            },
            error: function (xhr, status, error) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert);
                Object.entries(errorResponse.errors || {}).forEach(function ([key, value]) {
                    if (['name', 'username', 'email', 'phone', 'role'].includes(key)) {
                        $('#add-user-' + key).addClass('is-invalid');
                        if (value.length > 1) {
                            value.forEach(valItem => {
                                $('#add_' + key + '_error').append(`<li>${valItem}</li>`);
                            })
                        } else {
                            $('#add_' + key + '_error').html(`<li>${value}</li>`);
                        }
                    }
                });

                setTimeout(() => {
                    $('#modal-add-user-show-spinner').hide();
                    $('#modal-add-user-show-content').show();
                }, 500);
            }
        });

    });


    // Edit Data User

    $('#editUserModal').on('hidden.bs.modal', function () {
        $('#modal-edit-user-show-spinner').show();
        $('#modal-edit-user-show-content').hide();
        $('#editUserModalLabel').html('Edit User');
        $(this).find('form')[0].reset();
        $('#edit-user-role').val(null).trigger('change');
    });

    $('.btn-edit-user').on('click', function () {
        let user_id = $(this).val();
        $.ajax({
            type: "POST",
            url: "/api/data/user",
            data: {
                id: user_id
            },
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                if (response.success) {
                    let data = response.data[0];
                    $('#editUserModalLabel').html(data.name);
                    $('#edit-user-id').val(data.id);
                    $('#edit-user-name').val(data.name);
                    $('#edit-user-username').val(data.username);
                    $('#edit-user-email').val(data.email);
                    $('#edit-user-phone').val(data.phone);
                    $('#edit-user-role').val(data.roles[0]);
                }
            },
            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, 'danger');
            }
        });

        setTimeout(() => {
            $('#modal-edit-user-show-spinner').hide();
            $('#modal-edit-user-show-content').show();
        }, 500);
    });

    $('#form-edit-user').on('submit', function (e) {
        e.preventDefault();
        $('#modal-edit-user-show-spinner').show();
        $('#modal-edit-user-show-content').hide();

        let dataForm = $(this).serialize();
        console.log(dataForm);

        ['name', 'username', 'email', 'phone', 'role'].forEach(function (field) {
            $('#edit_' + field + '_error').html('');
        });

        $.ajax({
            type: "POST",
            url: "/api/data/user/update",
            data: dataForm,
            contentType: "application/x-www-form-urlencoded",
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                if (response.success) {
                    showToast(response.message, response.alert);
                    setTimeout(() => {
                        return window.location.href = 'http://localhost:8080/config/user';
                    }, 1500);
                }
            },
            error: function (xhr, status, error) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert);

                Object.entries(errorResponse.errors || {}).forEach(function ([key, value]) {
                    if (['name', 'username', 'email', 'phone', 'role'].includes(key)) {
                        $('#edit-user-' + key).addClass('is-invalid');
                        if (value.length > 1) {
                            value.forEach(valItem => {
                                $('#edit_' + key + '_error').append(`<li>${valItem}</li>`);
                            })
                        } else {
                            $('#edit_' + key + '_error').html(`<li>${value}</li>`);
                        }
                    }
                });
                setTimeout(() => {
                    $('#modal-edit-user-show-spinner').hide();
                    $('#modal-edit-user-show-content').show();
                }, 500);
            }
        });
    });

    // Reset Password User

    $('#resetPasswordUserModal').on('hidden.bs.modal', function () {
        $('#reset-password-user-id').val('');
        $('#reset-password-user-name').html('');
        $('#reset-password-user-username').html('');
        $('#modal-reset-password-user-show-spinner').show();
        $('#modal-reset-password-user-show-content').hide();
    });


    $('.btn-reset-password-user').on('click', function () {
        let user_id = $(this).val();
        let user_name = $(this).data('name');
        let user_username = $(this).data('username');
        console.log(user_id);
        console.log(user_name);
        console.log(user_username);

        $('#reset-password-user-id').val(user_id);
        $('#reset-password-user-name').html(user_name);
        $('#reset-password-user-username').html(user_username);

        setTimeout(() => {
            $('#modal-reset-password-user-show-spinner').hide();
            $('#modal-reset-password-user-show-content').show();
        }, 500);
        // console.log(user_id);
    });

    $('#form-reset-password-user').on('submit', function (e) {
        e.preventDefault();
        let dataForm = $(this).serialize();
        console.log(dataForm);


        $('#modal-reset-password-user-show-spinner').show();
        $('#modal-reset-password-user-show-content').hide();

        $.ajax({
            type: "POST",
            url: "/api/data/user/reset-password",
            data: dataForm,
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    showToast(response.message, response.alert);
                    setTimeout(() => {
                        $('#modal-reset-password-user-show-spinner').show();
                        $('#modal-reset-password-user-show-content').hide();
                    }, 500);
                    setTimeout(() => {
                        return window.location.href = '/config/user';
                    }, 500);
                } else {
                    setTimeout(() => {
                        $('#modal-reset-password-user-show-spinner').hide();
                        $('#modal-reset-password-user-show-content').show();
                    }, 500);
                    showToast('Gagal reset password!', 'danger');
                }
            },
            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert);
                setTimeout(() => {
                    $('#modal-reset-password-user-show-spinner').hide();
                    $('#modal-reset-password-user-show-content').show();
                }, 500);
            },
            complete: function () {
                setTimeout(() => {
                    $('#modal-reset-password-user-show-spinner').show();
                    $('#modal-reset-password-user-show-content').hide();
                }, 500);
            }
        });
    });

    // Reset Lock User

    $('#lockUserModal').on('hidden.bs.modal', function () {
        $('#lock-user-id').val('');
        $('#lock-user-name').html('');
        $('#lock-user-username').html('');
        $('#modal-lock-user-show-spinner').show();
        $('#modal-lock-user-show-content').hide();
    });

    $('.btn-lock-user').on('click', function () {
        let user_id = $(this).val();
        let user_name = $(this).data('name');
        let user_username = $(this).data('username');
        console.log(user_id);
        console.log(user_name);
        console.log(user_username);

        $('#lock-user-id').val(user_id);
        $('#lock-user-name').html(user_name);
        $('#lock-user-username').html(user_username);

        setTimeout(() => {
            $('#modal-lock-user-show-spinner').hide();
            $('#modal-lock-user-show-content').show();
        }, 500);
        // console.log(user_id);
    });

    $('#form-lock-user').on('submit', function (e) {
        e.preventDefault();
        let dataForm = $(this).serialize();
        console.log(dataForm);


        $('#modal-lock-user-show-spinner').show();
        $('#modal-lock-user-show-content').hide();

        $.ajax({
            type: "POST",
            url: "/api/data/user/lock-user",
            data: dataForm,
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    showToast(response.message, response.alert);
                    setTimeout(() => {
                        $('#modal-lock-user-show-spinner').show();
                        $('#modal-lock-user-show-content').hide();
                    }, 500);
                    setTimeout(() => {
                        return window.location.href = '/config/user';
                    }, 500);
                } else {
                    setTimeout(() => {
                        $('#modal-lock-user-show-spinner').hide();
                        $('#modal-lock-user-show-content').show();
                    }, 500);
                    showToast('Gagal reset password!', 'danger');
                }
            },
            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert);
                setTimeout(() => {
                    $('#modal-lock-user-show-spinner').hide();
                    $('#modal-lock-user-show-content').show();
                }, 500);
            },
            complete: function () {
                setTimeout(() => {
                    $('#modal-lock-user-show-spinner').show();
                    $('#modal-lock-user-show-content').hide();
                }, 500);
            }
        });
    });

    // Unlock User
    $('.btn-unlock-user').on('click', function () {
        const button = $(this); // Simpan tombol yang diklik
        let user_id = button.val(); // Ambil ID user

        if (!confirm('Aktifkan kembali user?')) {
            return; // Batalkan jika user tidak mengonfirmasi
        }

        // Tampilkan modal
        $('#unlockUserModal').modal('show');

        $.ajax({
            type: "POST",
            url: "/api/data/user/unlock-user",
            data: {
                id: user_id
            },
            dataType: "json", // Perbaikan: Sesuaikan dengan format data dari server
            success: function (response) {
                if (response.success) {
                    showToast(response.message, response.alert);
                    setTimeout(() => {
                        $('#unlockUserModal').modal('hide');
                        window.location.href = '/config/user';
                    }, 500);
                } else {
                    showToast('Gagal mengaktifkan user!', 'danger');
                }
            },
            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert);
            },
            complete: function () {
                // Tutup modal setelah permintaan selesai
                setTimeout(() => {
                    $('#unlockUserModal').modal('hide');
                }, 500);
            }
        });
    });


    // Tangging SKPD dengan User

    if ($('#show-list-select-skpd').children().length === 0) {
        $('#show-list-select-skpd').html(`
            <tr>
                <td colspan="2" class="text-center"><h5>Data Not Found</h5></td>
            </tr>
        `);
    }

    $('#userTaggingSkpdModal').on('hidden.bs.modal', function () {
        $('#show-list-user-skpd').html('');
        $('#modal-user-tagging-skpd-show-spinner').show();
        $('#modal-user-tagging-skpd-show-content').hide();
    });

    $('.btn-tagging-skpd-user, .btn-back-to-tagging-user-skpd-modal').on('click', function () {
        let user_id = $(this).val();
        $('#show-list-select-skpd').html('');
        $('#show-list-user-skpd').html('');
        $('.btn-back-to-tagging-user-skpd-modal').val(user_id);
        $('#btn-open-modal-select-user-skpd').val(user_id);

        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/user/skpd",
            data: {
                id: user_id,
                only_user: true,
            },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    let userdata = response.data;
                    let opd_data = userdata.opds.sort((a, b) => a.kode_opd.localeCompare(b.kode_opd));
                    $('#user-tagging-skpd-username').html(userdata.username);

                    if (opd_data.length) {
                        // Menampilkan daftar OPD milik user
                        opd_data.forEach(user_opd => {
                            $('#show-list-user-skpd').append(`
                                <tr id="data-list-user-skpd-${userdata.id}">
                                    <td>${user_opd.kode_opd + ' ' + user_opd.nama_opd}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger btn-remove-user-skpd" data-opd="${user_opd.id}" data-user="${userdata.id}">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        </button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        $('#show-list-user-skpd').html(`
                            <tr>
                                <td colspan="2" class="text-center"><h5>Tidak mempunyai Perangkat Daerah</h5></td>
                            </tr>
                        `);
                    }

                    // Sembunyikan spinner dan tampilkan konten
                    setTimeout(() => {
                        $('#modal-user-tagging-skpd-show-spinner').hide();
                        $('#modal-user-tagging-skpd-show-content').show();
                    }, 500);
                }
            },

            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert);
                setTimeout(() => {
                    $('#modal-user-tagging-skpd-show-spinner').hide();
                    $('#modal-user-tagging-skpd-show-content').show();
                }, 500);
            }
        });
    });

    $('#btn-open-modal-select-user-skpd').on('click', function () {
        $('#modal-user-select-skpd-show-spinner').show();
        $('#modal-user-select-skpd-show-content').hide();
        $('#show-list-select-skpd').html('');
        $('#search-user-select-skpd').val('');
        let user_id = $(this).val();
        console.log(user_id);

        // Menampilkan daftar OPD yang belum ditandai

        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/user/skpd",
            data: {
                id: user_id,
            },
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                if (response.success) {
                    const skpds = response.data;
                    skpds.forEach(opd => {
                        $('#show-list-select-skpd').append(`
                            <tr id="row-skpd-list-${opd.id}">
                                <td>${opd.kode_opd + ' ' + opd.nama_opd}</td>
                                <td>
                                    <button class="btn btn-sm btn-secondary btn-user-skpd-checked" value="${opd.id}" data-user="${user_id}">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    setTimeout(() => {
                        $('#modal-user-select-skpd-show-spinner').hide();
                        $('#modal-user-select-skpd-show-content').show();
                    }, 500);
                }
            },
            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert);
                setTimeout(() => {
                    $('#modal-user-select-skpd-show-spinner').hide();
                    $('#modal-user-select-skpd-show-content').show();
                }, 500);
            }
        });

    });

    $('body').on('click', '.btn-user-skpd-checked', function () {
        const button = $(this);
        $(this).attr('disabled', true);
        $(this).html('<i class="fa-solid fa-spinner fa-spin-pulse"></i>');
        let opd_id = $(this).val();
        let user_id = $(this).data('user');
        console.log(user_id);
        console.log(opd_id);

        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/user/tagging-skpd",
            data: {
                user_id: user_id,
                opd_id: opd_id,
            },
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                showToast(response.message, response.alert);
            },
            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert);
                button.attr('disabled', false);
                button.html('<i class="fa-solid fa-circle-check"></i>');
            },
            complete: function (result) {
                console.log(result);
                const data = result.responseJSON;
                if (data.success) {
                    $(`#row-skpd-list-${opd_id}`).fadeOut(300, function () {
                        $(this).remove();
                        if ($('#show-list-select-skpd').children().length === 0) {
                            $('#show-list-select-skpd').html(`
                                <tr>
                                    <td colspan="2" class="text-center"><h5>Data Not Found</h5></td>
                                </tr>
                            `);
                        }
                    });
                }
            }
        });

    });

    $('body').on('click', '.btn-remove-user-skpd', function () {
        let konfirmasi = confirm('anda yakin hapus data skpd ini?');
        if (konfirmasi) {
            let button = $(this);
            button.attr('disabled', true);
            button.html('<i class="fa-solid fa-spinner fa-spin-pulse"></i>');
            let user_id = button.data('user');
            let opd_id = button.data('opd');

            $.ajax({
                type: "POST",
                url: appApiUrl + "/api/data/user/remove-skpd",
                data: {
                    user_id: user_id,
                    opd_id: opd_id
                },
                dataType: "JSON",
                success: function (response) {
                    console.log(response);
                    showToast(response.message, response.alert);
                    if (response.success) {
                        setTimeout(() => {
                            $(`#data-list-user-skpd-${user_id}`).fadeOut(300, function () {
                                $(this).remove();
                                if ($('#show-list-user-skpd').children().length === 0) {
                                    $('#show-list-user-skpd').html(`
                                        <tr>
                                            <td colspan="2" class="text-center"><h5>Tidak mempunyai Perangkat Daerah</h5></td>
                                        </tr>
                                    `);
                                }
                            });
                        }, 300);
                    }
                },
                error: function (xhr) {
                    let errorResponse = handleAjaxError(xhr);
                    showToast(errorResponse.message, errorResponse.alert);
                    button.attr('disabled', false);
                    button.html('<i class="fa-solid fa-circle-xmark"></i>');
                },
            });
        }

    });

});
