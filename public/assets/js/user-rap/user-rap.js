$(document).ready(function () {

    console.log(jadwal_aktif);


    $(`.btn-remove-file`).mouseover(function () {
        $(this).css('cursor', 'pointer');
    });

    $('#userUploadFileModal').on('hidden.bs.modal', function () {
        $('#userUploadFileModalLabel').html('');
        $('#user-rap-id-rap').val('');
        $('#user-rap-file-kak').val('');
        $('#user-rap-file-rab').val('');
        $('.user-rap-file-pendukung').val('');
        $('#user-rap-file-modal-show-spinner').show();
        $('#user-rap-file-modal-show-content').hide();
        $('#user-rap-file-kak').removeClass('is-invalid');
        $('#file_kak_error').html('');
        $('#user-rap-file-kak').attr('disabled', false);

        ['kak', 'rab', 'pendukung1', 'pendukung2', 'pendukung3'].forEach((item) => {
            $(`#user-rap-file-${item}`).attr('disabled', false);
            $(`#user-rap-file-${item}`).show();
            $(`#user-rap-file-${item}`).data('filename', '');
            $(`#${item}-name-show`).html('')
            $(`#${item}-exists-show`).hide();
        })

        $('#user-rap-file-rab').removeClass('is-invalid');
        $('#file_rab_error').html('');
    });

    async function fileCheck(url, data) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                contentType: "aplication/json",
                success: function (response) {
                    try {
                        resolve(response)
                    } catch (error) {
                        console.error(error);
                        reject(error)
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    reject(xhr);
                }
            });
        })
    }

    $('.btn-upluad-user-rap').on('click', function () {
        let id_rap = $(this).val();

        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/rap",
            data: {
                id: id_rap
            },
            dataType: "JSON",
            success: async function (response) {
                if (response.success) {
                    let data = response.data[0];
                    console.log(data);

                    $('#userUploadFileModalLabel').html(data.kode_subkegiatan + ' ' + data.nama_subkegiatan);
                    $('#user-rap-id-rap').val(data.id);
                    let items = ['kak', 'rab', 'pendukung1', 'pendukung2', 'pendukung3'];

                    let fileExists = await fileCheck(appApiUrl + "/api/data/rap/file-check", JSON.stringify({
                        id_rap: data.id,
                    }));

                    if (fileExists.status) {
                        let fileData = fileExists.data;
                        fileData.map(function (item) {
                            if (item.exists) {
                                $(`#user-rap-file-${item.filename}`).attr('disabled', true);
                                $(`#user-rap-file-${item.filename}`).hide();
                                $(`#user-rap-file-${item.filename}`).data('filename', item.file);
                                $(`#user-rap-file-${item.filename}`).data('file-exists', true);
                                $(`#${item.filename}-btn-remove`).data('filename', item.filename);
                                $(`#${item.filename}-btn-remove`).data('id_rap', data.id);
                                $(`#${item.filename}-name-show`).html(() => {
                                    const fileUrl = item.file; // URL asli
                                    const urlParts = fileUrl.split('/'); // Pisahkan URL berdasarkan '/'

                                    // Pisahkan path dan nama file
                                    const fileName = urlParts.pop(); // Ambil nama file (elemen terakhir)
                                    const filePath = urlParts.join('/'); // Gabungkan kembali sisanya menjadi path

                                    // Buat URL baru
                                    const newUrl = `/rap/view/file?path=${encodeURIComponent(filePath + '/')}&name=${fileName}`;

                                    // Return HTML baru
                                    return `<a href="${newUrl}" target="_blank">${item.name}</a>`;
                                });
                                $(`#${item.filename}-exists-show`).show();
                            }
                        })
                    }

                    $('#user-rap-link-file').val(data.link_file_dukung_lain);

                    setTimeout(() => {
                        $('#user-rap-file-modal-show-spinner').hide();
                        $('#user-rap-file-modal-show-content').show();
                    }, 500);
                }
            }
        });
    });

    $('#user-rap-file-kak').on('change', function () {
        let file = this.files[0];
        let filename = $(this).data('filename');
        const maxSize = 5 * 1024 * 1024;
        if (file) {
            if (file.type !== 'application/pdf') {
                alert('File hanya boleh berformat PDF')
                $(this).val('');
            }
            if (file.size > maxSize) {
                alert('Ukuran file tidak boleh lebih dari 5MB');
                $(this).val('');
                return;
            }
        }
    });

    $('#user-rap-file-rab').on('change', function () {
        let file = this.files[0];
        const maxSize = 5 * 1024 * 1024;
        if (file) {
            if (file.type !== 'application/pdf') {
                alert('File hanya boleh berformat PDF')
                $(this).val('');
            }
            if (file.size > maxSize) {
                alert('Ukuran file tidak boleh lebih dari 5MB');
                $(this).val('');
                return;
            }
        }
    });

    $('.user-rap-file-pendukung').on('change', function () {
        let file = this.files[0];
        const maxSize = 5 * 1024 * 1024;
        if (file) {
            if (file.type !== 'application/pdf') {
                alert('File hanya boleh berformat PDF')
                $(this).val('');
            }
            if (file.size > maxSize) {
                alert('Ukuran file tidak boleh lebih dari 5MB');
                $(this).val('');
                return;
            }
        }
    });

    $('.btn-remove-file').on('click', function () {
        let id_rap = $(this).data('id_rap');
        let filename = $(this).data('filename');
        $(`#user-rap-file-${filename}`).show();
        $(`#user-rap-file-${filename}`).attr('disabled', false);

        $(`#${filename}-exists-show`).hide();
        console.log({
            id_rap: id_rap,
            filename: filename,
        });
    });


    // Delete File Konfirmasi
    $('#konfirmasiDeleteFileRapModal').on('hidden.bs.modal', function () {
        $('#user-konfirmasi-delete-file-modal-show-spinner').show();
        $('#user-konfirmasi-delete-file-modal-show-content').hide();
        $('#hidden-delete-id').val('');
        $('#hidden-delete-filename').val('');
        $('#konfimasi-delete-info-subkegiatan').html('');
        $('#konfimasi-delete-info-file').html('');
        ['username', 'password'].forEach(function (field) {
            $('#' + field + '_error').html('');
            $('#delete-konfirmasi-' + field).removeClass('is-invalid');
        });
    });

    $('.btn-delete-file').on('click', function () {
        let id_rap = $(this).data('id_rap');
        let filename = $(this).data('filename');
        let subkegiatan = $(this).data('subkegiatan');
        let file = $(this).data('file');
        $('#hidden-delete-id').val(id_rap);
        $('#hidden-delete-filename').val(filename);
        $('#konfimasi-delete-info-subkegiatan').html(subkegiatan);
        $('#konfimasi-delete-info-file').html(file);
        $('#delete-konfirmasi-password').val('');
        $('#delete-konfirmasi-password').attr('disabled', false);

        setTimeout(() => {
            $('#user-konfirmasi-delete-file-modal-show-spinner').hide();
            $('#user-konfirmasi-delete-file-modal-show-content').show();
        }, 500);
    });

    $('#btn-submit-delete-file').on('click', function (e) {
        e.preventDefault();

        $('#user-konfirmasi-delete-file-modal-show-spinner').show();
        $('#user-konfirmasi-delete-file-modal-show-content').hide();

        ['password'].forEach(function (field) {
            $('#' + field + '_error').html('');
            $('#delete-konfirmasi-' + field).removeClass('is-invalid');
        });
        let user_password = $('#delete-konfirmasi-password').val();

        $('#delete-konfirmasi-password').attr('disabled', true);
        console.log(user_password);
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/auth/password/confirm",
            data: {
                username: auth_username,
                password: user_password
            },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    showToast('proses hapus file sedang berlangsung....', 'info');
                    setTimeout(() => {
                        $('#form-confirm-delete-file').submit();
                    }, 1000);
                }
            },
            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, 'danger');
                Object.entries(errorResponse.errors || {}).forEach(function ([key, value]) {
                    if (['password'].includes(key)) {
                        $('#delete-konfirmasi-' + key).addClass('is-invalid');
                        $('#' + key + '_error').html(value);
                    }
                });
                if (!errorResponse.success) {
                    $('#delete-konfirmasi-password').attr('disabled', false);
                    setTimeout(() => {
                        $('#user-konfirmasi-delete-file-modal-show-spinner').hide();
                        $('#user-konfirmasi-delete-file-modal-show-content').show();
                    }, 500);
                }
            }
        });
    });


    /**
     * Edit RAP Oleh User
     */

    $('#userEditRapModal').on('hidden.bs.modal', function () {
        $('#modal-edit-rap-show-content').hide();
        $('#modal-edit-rap-show-spinner').show();
        $('#userEditRapModalLabel').html('');
        $('#user-rap-show-indikator').html('');
        $('#user-rap-show-sumberdana').html('');
        $('#user-rap-show-anggaran').html('');
        $('#user-rap-show-jenis-kegiatan').html('');
        $('#rap-user-edit-target-kinerja').val('');
        $('#user-rap-show-satuan').html('');
        $('#rap-user-edit-lokus').val(null);
        $('#user-rap-show-jenis').hide();
        $('#user-rap-edit-koordinat').attr('disabled', true);
        $('#user-rap-edit-koordinat').val('');

        ['id_rap', 'vol_subkeg', 'lokus', 'koordinat', 'keterangan'].forEach(function (field) {
            if ($('#user-rap-edit-' + field).length > 0) {
                $('#user-rap-edit-' + field).removeClass('is-invalid');
            }
            $('#' + field + '_error').html('');
        });
    });

    $('.btn-user-edit-rap').on('click', function () {
        let id_rap = $(this).val();
        console.log(id_rap);

        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/rap",
            data: {
                id: id_rap
            },
            dataType: "JSON",
            success: async function (response) {
                if (response.success) {
                    let item = response.data[0];
                    let lokasi = JSON.parse(item.lokus);
                    $('#user-edit-rap-id').val(item.id);
                    $('#userEditRapModalLabel').html(item.text_subkegiatan);
                    $('#user-rap-show-indikator').html(item.indikator_subkegiatan);
                    $('#user-rap-show-sumberdana').html(item.sumberdana);
                    $('#user-rap-show-anggaran').html('Rp. ' + formatIDR(item.anggaran));
                    $('#user-rap-show-jenis-kegiatan').html(item.jenis_kegiatan === 'fisik' ? 'Pekerjaan Fisik' : 'Non Fisik');
                    $('#rap-user-edit-jenis-kegiatan').val(item.jenis_kegiatan);
                    $('#rap-user-edit-target-kinerja').val(item.vol_subkeg);
                    $('#user-rap-show-satuan').html(item.satuan_subkegiatan);

                    const lokusIds = lokasi.map(lokus => lokus.id);
                    $('#rap-user-edit-lokus').val(lokusIds).trigger('change');

                    if (item.jenis_kegiatan === 'fisik') {
                        $('#user-rap-show-jenis').show();
                        $('#user-rap-edit-koordinat').attr('disabled', false);
                        $('#user-rap-edit-koordinat').val(item.koordinat);
                    }

                    $('#user-rap-edit-keterangan').html(item.keterangan);
                    console.log(item);
                }
            },
            error: function (xhr, status, error) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, 'danger');
            }
        })

        setTimeout(() => {
            $('#modal-edit-rap-show-content').show();
            $('#modal-edit-rap-show-spinner').hide();
        }, 500);
    });

    $('#form-user-edit-rap').on('submit', function (e) {
        e.preventDefault();
        $('#modal-edit-rap-show-content').hide();
        $('#modal-edit-rap-show-spinner').show();
        ['id_rap', 'vol_subkeg', 'lokus', 'koordinat', 'keterangan'].forEach(function (field) {
            if ($('#user-rap-edit-' + field).length > 0) {
                $('#user-rap-edit-' + field).removeClass('is-invalid');
            }
            $('#' + field + '_error').html('');
        });
        let dataForm = $(this).serialize();
        console.log(dataForm);
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/user/rap/update",
            data: dataForm,
            contentType: "application/x-www-form-urlencoded",
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    console.log(response);
                    showToast(response.message, response.alert);
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            },
            error: function (xhr, status, error) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert);

                Object.entries(errorResponse.errors || {}).forEach(function ([key, value]) {
                    if (['id_rap', 'vol_subkeg', 'lokus', 'koordinat', 'keterangan'].includes(key)) {
                        if ($('#user-rap-edit-' + key).length > 0) {
                            $('#user-rap-edit-' + key).addClass('is-invalid');
                        }
                        $('#' + key + '_error').html(value);
                    }
                });

                setTimeout(() => {
                    $('#modal-edit-rap-show-content').show();
                    $('#modal-edit-rap-show-spinner').hide();
                }, 500);
            }
        });
    });

    $('#test-token').on('click', function () {
        console.log(userToken);
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/user/test/token",
            data: {
                user_token: userToken
            },
            dataType: "JSON",
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert);
                console.error(response);
                console.error(status);
                console.error(error);
            }
        });
    });

    $('#form-user-upload-file').on('submit', function (e) {
        e.preventDefault();
        let kak_input = $('#user-rap-file-kak').val();
        let rab_input = $('#user-rap-file-rab').val();
        let isValid = true;

        if (kak_input == '') {
            let kak_exist = $('#user-rap-file-kak').data('file-exists');
            if (!kak_exist) {
                $('#user-rap-file-kak').addClass('fa-bounce is-invalid');
                $('#file_kak_error').html('File KAK tidak boleh kosong!');
                setTimeout(() => {
                    $('#user-rap-file-kak').removeClass('fa-bounce');
                }, 500);
                isValid = false;
            }
        }
        if (rab_input == '') {
            let rab_exist = $('#user-rap-file-rab').data('file-exists');
            if (!rab_exist) {
                $('#user-rap-file-rab').addClass('fa-bounce is-invalid');
                $('#file_rab_error').html('File RAB tidak boleh kosong!');
                setTimeout(() => {
                    $('#user-rap-file-rab').removeClass('fa-bounce');
                }, 500);
                isValid = false;
            }
        }

        if (isValid) {
            $('#user-rap-file-modal-show-spinner').show();
            $('#user-rap-file-modal-show-content').hide();
            this.submit();
        }
    });

    $('#user-rap-file-kak').on('change', function () {
        let value = $(this).val();
        console.log(value !== '');
        if (value) {
            $('#user-rap-file-kak').removeClass('is-invalid');
            $('#file_kak_error').html('');
        }
    });

    $('#user-rap-file-rab').on('change', function () {
        let value = $(this).val();
        console.log(value !== '');
        if (value) {
            $('#user-rap-file-rab').removeClass('is-invalid');
            $('#file_rab_error').html('');
        }
    });

});
