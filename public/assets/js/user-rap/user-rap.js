$(document).ready(function () {

    $(`.btn-remove-file`).mouseover(function () {
        $(this).css('cursor', 'pointer');
    });

    $('#userUploadFileModal').on('hidden.bs.modal', function () {
        $('#userUploadFileModalLabel').html('');
        $('#user-rap-id-rap').val('');
        $('#user-rap-file-kak').val('');
        $('#user-rap-file-rab').val('');
        $('.user-rap-file-pendukung').val('');
        $('#modal-show-spinner').show();
        $('#modal-show-content').hide();
        $('#user-rap-file-kak').attr('disabled', false);

        ['kak', 'rab', 'pendukung1', 'pendukung2', 'pendukung3'].forEach((item) => {
            $(`#user-rap-file-${item}`).attr('disabled', false);
            $(`#user-rap-file-${item}`).show();
            $(`#user-rap-file-${item}`).data('filename', '');
            $(`#${item}-name-show`).html('')
            $(`#${item}-exists-show`).hide();
        })
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
                    $('#userUploadFileModalLabel').html(data.kode_subkegiatan + ' ' + data.nama_subkegiatan);
                    $('#user-rap-id-rap').val(data.id);
                    let items = ['kak', 'rab', 'pendukung1', 'pendukung2', 'pendukung3'];

                    let fileExists = await await fileCheck(appApiUrl + "/api/data/rap/file-check", JSON.stringify({
                        id_rap: data.id,
                    }));

                    if (fileExists.status) {
                        let fileData = fileExists.data;
                        fileData.map(function (item) {
                            if (item.exists) {
                                console.log(item.filename);
                                $(`#user-rap-file-${item.filename}`).attr('disabled', true);
                                $(`#user-rap-file-${item.filename}`).hide();
                                $(`#user-rap-file-${item.filename}`).data('filename', item.file);
                                $(`#${item.filename}-btn-remove`).data('filename', item.filename);
                                $(`#${item.filename}-btn-remove`).data('id_rap', data.id);
                                $(`#${item.filename}-name-show`).html(() => {
                                    const fileUrl = item.file; // URL asli
                                    const urlParts = fileUrl.split('/'); // Pisahkan URL berdasarkan '/'

                                    // Pisahkan path dan nama file
                                    const fileName = urlParts.pop(); // Ambil nama file (elemen terakhir)
                                    const filePath = urlParts.join('/'); // Gabungkan kembali sisanya menjadi path

                                    // Buat URL baru
                                    const newUrl = `/rap/download/file?path=${encodeURIComponent(filePath + '/')}&name=${fileName}`;

                                    // Return HTML baru
                                    return `<a href="${newUrl}" target="_blank">${item.name}</a>`;
                                });
                                $(`#${item.filename}-exists-show`).show();
                            }
                        })
                    }

                    console.log(response);

                    setTimeout(() => {
                        $('#modal-show-spinner').hide();
                        $('#modal-show-content').show();
                    }, 1000);
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
    });

    $('#btn-submit-delete-file').on('click', function (e) {
        e.preventDefault();
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
                    $('#form-confirm-delete-file').submit();
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
                }
            }
        });
    });

});
