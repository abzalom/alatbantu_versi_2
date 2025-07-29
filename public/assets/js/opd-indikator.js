$(document).ready(function () {

    // Add Indikator
    $('#opdTagIndikatorModal').on('hide.bs.modal', function () {
        $('#opd-tag-indikator-spinner').hide();
        $('#opd-tag-indikator-form-body').show();
        $('#indikator-select-target-aktifitas').html('<option></option>');
        $('#indikator-select-tema').val(null).trigger('change');
        $('#id_target_aktifitas_error').html('');

        $('#indikator-input-volume').val(null);
        $('#indikator-select-sumberdana').val(null).trigger('change');
        $('#indikator-input-volume').attr('disabled', true);
        $('#indikator-select-sumberdana').attr('disabled', true);
    });

    $('#indikator-select-tema').on('change', function () {
        $('#indikator-select-target-aktifitas').attr('disabled', true);
        $('#indikator-select-target-aktifitas').html('<option></option>');

        $('#indikator-input-volume').val(null);
        $('#indikator-select-sumberdana').val(null).trigger('change');
        $('#indikator-input-volume').attr('disabled', true);
        $('#indikator-select-sumberdana').attr('disabled', true);
        let kode_tema = parseInt($(this).val());
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/otsus/indikator/target_aktifitas_utama",
            data: {
                kode_tema: kode_tema
            },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    if (response.data.length > 0) {
                        let data = response.data;
                        data.forEach(function (value, key) {
                            $('#indikator-select-target-aktifitas').append('<option value="' + value.id + '" data-satuan="' + value.satuan + '">' + value.text + '</option>');
                        });
                        $('#indikator-select-target-aktifitas').attr('disabled', false);
                    }
                }
            }
        });
    });

    $('#indikator-select-target-aktifitas').on('change', function () {
        let satuan = $(this).find(':selected').data('satuan');
        $('#indikator-satuan').val(satuan);
        $('#indikator-input-volume').val(null);
        $('#indikator-select-sumberdana').val(null).trigger('change');
        $('#indikator-input-volume').attr('disabled', false);
        $('#indikator-select-sumberdana').attr('disabled', false);
    });

    $('#indikator-input-volume').on('input', function () {
        // Ganti koma (,) dengan titik (.)
        let value = $(this).val().replace(',', '.');

        // Hanya izinkan angka dan titik (.)
        if (!/^[0-9.]*$/.test(value)) {
            value = value.replace(/[^0-9.]/g, ''); // Hapus karakter tidak valid
        }

        $(this).val(value);
    });

    $('#form-indikator-otsus-opd').submit(function (e) {
        e.preventDefault();
        $('#opd-tag-indikator-spinner').show();
        $('#opd-tag-indikator-form-body').hide();
        let dataForm = $(this).serialize();

        ['id_target_aktifitas', 'volume_indikator', 'sumberdana_indikator'].forEach(function (field) {
            $('#' + field + '_error').html('');
        });

        $.ajax({
            type: "POST",
            url: "/api/data/indikator/opd/add",
            data: dataForm,
            dataType: "JSON",
            success: function (response) {
                if (response.message || response.alert) {
                    showToast(response.message, response.alert || 'danger');
                }
                if (response.success) {
                    let data = response.data;
                    console.log(data);
                    $('#opdTagIndikatorModal').modal('hide');
                    $('#opd-indikator-show-data-not-found').remove();

                    data.tag_otsus.forEach(function (tagValue, tagKey) {
                        console.log(tagValue);
                        $('#opd-indikator-show').append(`
                            <tr id="opd-indikator-show-data-id-${tagValue.id}">
                                <td>${tagValue.target_aktifitas.kode_target_aktifitas}</td>
                                <td>${tagValue.target_aktifitas.uraian}</td>
                                <td>${tagValue.satuan}</td>
                                <td>${tagValue.volume}</td>
                                <td>${tagValue.sumberdana}</td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-delete-opd-indikator" value="${tagValue.id}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    })
                }
            },
            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, 'danger');
                // Tampilkan pesan error pada form
                Object.entries(errorResponse.errors || {}).forEach(function ([key, value]) {
                    if (['id_target_aktifitas', 'volume_indikator', 'sumberdana_indikator'].includes(key)) {
                        $('#' + key + '_error').html(value);
                    }
                });
                $('#opd-tag-indikator-spinner').hide();
                $('#opd-tag-indikator-form-body').show();
            }
        });
    });

    // Delete Indikator
    $(document).on('click', '.btn-delete-opd-indikator', function () {
        let konfirmasi = confirm('Anda yakin menghapus indikator ini?');
        if (konfirmasi) {
            let id = $(this).val();
            $.ajax({
                type: "POST",
                url: appApiUrl + "/api/data/rap/delete/indikator",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function (response) {
                    if (response.success) {
                        $('#opd-indikator-show-data-id-' + id).remove();
                    }

                    if ($('#opd-indikator-show tr').length === 0) {
                        $('#opd-indikator-show').html(`
                            <tr id="opd-indikator-show-data-not-found">
                                <td colspan="6" class="text-center">
                                    <h4>Data Not Found!</h4>
                                </td>
                            </tr>
                        `);
                    }

                    if (response.message || response.alert) {
                        showToast(response.message, response.alert || 'danger');
                    }
                },
                error: function (xhr) {
                    let errorResponse = handleAjaxError(xhr);
                    showToast(errorResponse.message, 'danger');
                }
            });
        }
    });

    if (window.location.pathname === '/rap/opd/form-subkegiatan') {
        const urlParams = new URLSearchParams(window.location.search);
        const opd = urlParams.get('opd');
        console.log(opd); // Output: 1
        $('#rap-select-subkegiatan').attr('disabled', true);
        $('#rap-select-subkegiatan').html('<option></option>');
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/opd/subkegiatan",
            data: {
                id: opd
            },
            dataType: "JSON",
            success: function (response) {
                let data;
                if (response.success) {
                    data = response.data;
                }
                console.log(data);
                $('#rap-select-target-aktifitas').on('change', function () {
                    let sumberdana = $(this).find('option:selected').data('sumberdana');
                    let kode = $(this).find('option:selected').data('kode').replace(/^1\./, '').charAt(0);

                    $('#rap-input-sumberdana-target-aktifitas').val(sumberdana);
                    let volume = $(this).find('option:selected').data('volume');
                    $('#rap-input-volume-target-aktifitas').val(volume);
                    let satuan = $(this).find('option:selected').data('satuan');
                    $('#rap-input-satuan-target-aktifitas').val(satuan);
                    $('#rap-select-subkegiatan').html('<option></option>');
                    $('#rap-disabled-indikator-subkegiatan').val('');
                    $('#rap-disabled-klasfikasi-belanja').val('');
                    $('#rap-disabled-satuan-subkegiatan').val('');

                    if (sumberdana && volume) {
                        $('#rap-select-subkegiatan').attr('disabled', false);
                        data.forEach(function (subkegValue, subkegKey) {
                            $('#rap-select-subkegiatan').append(`
                                <option value="${subkegValue.id}" data-indikator="${subkegValue.indikator}" data-klasifikasi="${subkegValue.klasifikasi_belanja}" data-satuan="${subkegValue.satuan}">
                                    ${subkegValue.kode_subkegiatan + ' ' + subkegValue.uraian}
                                </option>`);
                        })
                    } else if (kode === 'X' && sumberdana) {
                        $('#rap-select-subkegiatan').attr('disabled', false);
                        data.forEach(function (subkegValue, subkegKey) {
                            $('#rap-select-subkegiatan').append(`
                                <option value="${subkegValue.id}" data-indikator="${subkegValue.indikator}" data-klasifikasi="${subkegValue.klasifikasi_belanja}" data-satuan="${subkegValue.satuan}">
                                    ${subkegValue.kode_subkegiatan + ' ' + subkegValue.uraian}
                                </option>`);
                        })
                    } else {
                        $('#rap-select-subkegiatan').html('<option></option>');
                        $('#rap-select-subkegiatan').attr('disabled', true);
                    }
                });
                if (old) {
                    console.log(old);
                    if (old.opd_tag_otsus) {
                        $('#rap-select-target-aktifitas').val(old.opd_tag_otsus).trigger('change');
                    }
                    if (old.subkegiatan) {
                        $('#rap-select-subkegiatan').val(old.subkegiatan).trigger('change');
                    }
                }
            }
        });
    }

    $(document).on('change', '#rap-select-subkegiatan', function () {
        let id = $(this).val() ? $(this).val() : null;
        let indikator = $(this).find('option:selected').data('indikator');
        let klasifikasi = $(this).find('option:selected').data('klasifikasi');
        let satuan = $(this).find('option:selected').data('satuan');

        if (id) {
            console.log({
                id: id,
                indikator: indikator,
                klasifikasi: klasifikasi,
                satuan: satuan,
            });
            $('#rap-disabled-indikator-subkegiatan').val(indikator);
            $('#rap-disabled-klasfikasi-belanja').val(klasifikasi);
            $('#rap-disabled-satuan-subkegiatan').val(satuan);
        } else {
            $('#rap-disabled-indikator-subkegiatan').val('');
            $('#rap-disabled-klasfikasi-belanja').val('');
            $('#rap-disabled-satuan-subkegiatan').val('');
        }

    });

    // Edit Indikator


    $('#edit-indikator-input-volume').on('input', function () {
        // Ganti koma (,) dengan titik (.)
        let value = $(this).val().replace(',', '.');

        // Hanya izinkan angka dan titik (.)
        if (!/^[0-9.]*$/.test(value)) {
            value = value.replace(/[^0-9.]/g, ''); // Hapus karakter tidak valid
        }

        $(this).val(value);
    });

    $('#edit-opdTagIndikatorModal').on('hidden.bs.modal', function () {
        $('#opd-edit-tag-indikator-spinner').show();
        $('#opd-edit-tag-indikator-form-body').hide();
        $('#edit-indikator-select-tema').val(null).trigger('change');
        $('#edit-indikator-select-target-aktifitas').text('');
        $('#edit-indikator-satuan').val('');
        $('#edit-indikator-input-volume').val('');
        $('#edit-indikator-select-sumberdana').val(null).trigger('change');
    });

    $('.btn-edit-opd-indikator').on('click', function () {
        let id = $(this).val();
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/indikator/opd",
            data: {
                id: id,
                tahun: tahunAnggaran
            },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    let data = response.data[0];
                    console.log(data);
                    $('#edit-indikator-hidden-id').val(data.id);
                    $('#edit-indikator-select-tema').val(data.kode_tema).trigger('change');
                    $('#edit-indikator-select-target-aktifitas').text(data.target_aktifitas.kode_target_aktifitas + ' ' + data.target_aktifitas.uraian);
                    $('#edit-indikator-satuan').val(data.satuan);
                    $('#edit-indikator-input-volume').val(data.volume);
                    $('#edit-indikator-select-sumberdana').val(data.sumberdana).trigger('change');
                    $('#edit-indikator-input-volume').attr('disabled', false);
                    $('#edit-indikator-select-sumberdana').attr('disabled', false);
                    setTimeout(() => {
                        $('#opd-edit-tag-indikator-spinner').hide();
                        $('#opd-edit-tag-indikator-form-body').show();
                    }, 500);
                }
            }
        });
    });

    $('#edit-form-indikator-opd').on('submit', function (e) {
        e.preventDefault();
        $('#opd-edit-tag-indikator-spinner').show();
        $('#opd-edit-tag-indikator-form-body').hide();
        let dataForm = $(this).serialize();

        ['id_indikator', 'volume_indikator', 'sumberdana_indikator'].forEach(function (field) {
            $('#edit_' + field + '_error').html('');
        });

        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/indikator/opd/update",
            data: dataForm,
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                if (response.success) {
                    $('#edit-opdTagIndikatorModal').modal('hide');
                    $('#opd-indikator-show-volume-id-' + response.data.id).html(response.data.volume);
                    $('#opd-indikator-show-sumberdana-id-' + response.data.id).html(response.data.sumberdana);
                }
            },
            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, 'danger');
                // Tampilkan pesan error pada form
                Object.entries(errorResponse.errors || {}).forEach(function ([key, value]) {
                    if (['id_indikator', 'volume_indikator', 'sumberdana_indikator'].includes(key)) {
                        $('#edit_' + key + '_error').html(value);
                    }
                });
                $('#opd-edit-tag-indikator-spinner').hide();
                $('#opd-edit-tag-indikator-form-body').show();
            }
        });
    });

});
