$(document).ready(function () {

    $('#addVolTargetAktifitasMmodal').on('hide.bs.modal', function () {
        $('#addVolTargetAktifitasMmodalLabel').html('');
        $('#id_target_aktifitas').val('');
        $('#target-aktifitas-input-volume').val('');
        $('#target-aktifitas-select-sumberdana').val(null).trigger('change');
        $('#target-aktifitas-spinner').hide();
        $('#target-aktifitas-fom-element').show();
    });

    $('.btn-add-volume-target').on('click', function () {
        let id = $(this).val();
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/otsus/indikator/target_aktifitas_utama",
            data: {
                id: id
            },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    let data = response.data[0];
                    console.log(data);
                    $('#addVolTargetAktifitasMmodalLabel').html(data.text);
                    $('#id_target_aktifitas').val(data.id);
                    $('#target-aktifitas-input-volume').val(data.volume);
                    $('#target-aktifitas-select-sumberdana').val(data.sumberdana).trigger('change');
                }
            }
        });
    });

    $('#target-aktifitas-input-volume').on('input', function () {
        // Ganti koma (,) dengan titik (.)
        let value = $(this).val().replace(',', '.');

        // Hanya izinkan angka dan titik (.)
        if (!/^[0-9.]*$/.test(value)) {
            value = value.replace(/[^0-9.]/g, ''); // Hapus karakter tidak valid
        }

        $(this).val(value);
    });

    $('#form-edit-volume-target-aktifitas').on('submit', function (e) {
        e.preventDefault();
        let dataForm = $(this).serialize();

        ['volume', 'sumberdana'].forEach(function (field) {
            $('#' + field + '_error').html('');
        });

        $('#target-aktifitas-spinner').show();
        $('#target-aktifitas-fom-element').hide();
        setTimeout(() => {
            $.ajax({
                type: "POST",
                url: appApiUrl + "/api/data/otsus/indikator/target_aktifitas_utama/volume",
                data: dataForm,
                dataType: "JSON",
                success: function (response) {
                    if (response.message || response.alert) {
                        showToast(response.message, response.alert || 'danger');
                    }
                    if (response.success) {
                        $('#addVolTargetAktifitasMmodal').modal('hide');
                        $('#volume-target-' + response.data.id).html(response.data.volume ? formatIDR(response.data.volume) : '');
                        $('#sumberdana-target-' + response.data.id).html(response.data.sumberdana);
                        highlightRow('#target-aktifitas-' + response.data.id);
                    }
                },
                error: function (xhr) {
                    let errorResponse = handleAjaxError(xhr);
                    showToast(errorResponse.message, 'danger');
                    // Tampilkan pesan error pada form
                    Object.entries(errorResponse.errors || {}).forEach(function ([key, value]) {
                        if (['volume', 'sumberdana'].includes(key)) {
                            $('#' + key + '_error').html(value);
                        }
                    });
                }
            });
        }, 1000)
    });

    $('.btn-reset-volume-target').on('click', function () {
        let id = $(this).val();
        let icon = $(this).find('i');
        icon.addClass('fa-spin fa-lg');

        setTimeout(() => {
            $.ajax({
                type: "POST",
                url: appApiUrl + "/api/data/otsus/indikator/target_aktifitas_utama/volume/reset",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function (response) {
                    if (response.message || response.alert) {
                        showToast(response.message, response.alert || 'danger');
                    }
                    if (response.success) {
                        $('#volume-target-' + id).html('');
                        $('#sumberdana-target-' + id).html('');
                        icon.removeClass('fa-spin fa-lg');
                    }
                },
                error: function (xhr) {
                    let errorResponse = handleAjaxError(xhr);
                    showToast(errorResponse.message, 'danger');
                    icon.removeClass('fa-spin fa-lg');
                }
            });
        }, 1000);
    });

});
