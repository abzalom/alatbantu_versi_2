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


    // Event saat tombol tag SKPD ditekan

    $('#tagSkpdModal').on('hide.bs.modal', function () {
        $('#target-aktifitas').val('');
        $('#select-opd').html('');
        $('#input-vol-tag-opd').val('');
        $('#select-sumberdana').val(null).trigger('change');

        $('#target-aktifitas').removeClass('is-invalid');
        $('#select-opd').removeClass('is-invalid');
        $('#input-vol-tag-opd').removeClass('is-invalid');
        $('#select-sumberdana').removeClass('is-invalid');

        $('#opd_error').html('');
        $('#volume_error').html('');
        $('#sumberdana_error').html('');
        $('#table-data-skpds').html('');
        $('#tagSkpdModalLabel').html('');
        setTimeout(() => {
            $('#tagSkpdModal .modal-show-content').hide();
            $('#tagSkpdModal .modal-show-spinner').show();
        }, 500);
    });

    $('.btn-tag-skpd').on('click', function () {
        let id_target_aktifitas = $(this).val();
        $.ajax({
            type: "POST",
            url: "/api/data/tagging/target/opd_list",
            data: {
                id: id_target_aktifitas,
                tahun: tahunAnggaran,
            },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    let dataTarget = response.data;
                    let dataTag = response.data.tagging_opd;
                    $('#tagSkpdModalLabel').html(dataTarget.kode_target + ' - ' + dataTarget.nama_target);
                    $('#id-target-aktifitas').val(dataTarget.id_target);
                    $('#show-satuan-target').html(dataTarget.satuan_target);
                    dataTag.forEach((item) => {
                        let btnDelete = item.raps_count ? `
                            <div class="btn-group" role="group">
                                <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tidak bisa dihapus. RAP sudah ada. Hapus RAP dan Arsip Terhapus dahulu untuk menghapus tagging ini!">
                                    <button class="btn btn-sm btn-danger btn-delete-tagging" value="${item.opd_tag_otsus}" disabled>
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        ` : `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-danger btn-delete-tagging" value="${item.opd_tag_otsus}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        `;

                        // Pastikan tidak ada duplikasi sebelum menambahkan
                        if ($(`#table-data-skpds tr#${item.kode_unik_opd_tag_otsus.replace(/\./g, '_')}`).length === 0) {
                            $('#table-data-skpds').append(`
                                <tr id="${item.kode_unik_opd_tag_otsus.replace(/\./g, '_')}">
                                    <td>${item.kode_opd + ' ' + item.nama_opd}</td>
                                    <td>${item.volume}</td>
                                    <td>${item.satuan}</td>
                                    <td>${item.sumberdana}</td>
                                    <td>${btnDelete}</td>
                                </tr>
                            `);
                            $('[data-bs-toggle="tooltip"]').tooltip();
                        }
                    });
                    $('#select-opd').html(`<option></option>`);
                    listOpds.forEach((opd) => {
                        $('#select-opd').append(`
                            <option value="${opd.id}">${opd.kode_opd + ' ' + opd.nama_opd}</option>
                        `);
                    })
                }

                $('#tagSkpdModal .modal-show-spinner').hide();
                $('#tagSkpdModal .modal-show-content').show();
            },
            error: function (xhr) {
                const dataError = xhr.responseJSON;
                showToast(dataError.errors['id'][0], dataError.alert);
            }
        });
    });

    $('#form-tambah-skpd').on('submit', function (e) {
        e.preventDefault();
        $('#tagSkpdModal .modal-show-content').hide();
        $('#tagSkpdModal .modal-show-spinner').show();
        let satuan = $('#show-satuan-target').html();
        // Hapus pesan error sebelumnya
        $('#opd_error').html('');
        $('#volume_error').html('');
        $('#sumberdana_error').html('');

        // Hapus class error sebelumnya
        $('#select-opd').removeClass('is-invalid');
        $('#input-vol-tag-opd').removeClass('is-invalid');
        $('#select-sumberdana').removeClass('is-invalid');

        // Ambil nilai input
        let valOpdSelect = $('#select-opd').val();
        let volume = $('#input-vol-tag-opd').val();
        let sumberdana = $('#select-sumberdana').val();

        let hasError = false;

        // Validasi Perangkat Daerah
        if (!valOpdSelect) {
            $('#select-opd').addClass('is-invalid');
            $('#opd_error').html('Perangkat Daerah belum dipilih!');
            hasError = true;
        }

        // Validasi Volume
        if (!volume) {
            $('#input-vol-tag-opd').addClass('is-invalid');
            $('#volume_error').html('Volume tidak boleh kosong!');
            hasError = true;
        }

        // Validasi Sumber Dana
        if (!sumberdana) {
            $('#select-sumberdana').addClass('is-invalid');
            $('#sumberdana_error').html('Sumberdana tidak boleh kosong!');
            hasError = true;
        }

        // Jika tidak ada error, tampilkan alert atau lakukan sesuatu
        if (!hasError) {
            let dataForm = $(this).serialize();
            $('#select-opd').attr('disabled', true);
            $('#input-vol-tag-opd').attr('disabled', true);
            $('#select-sumberdana').attr('disabled', true);

            $.ajax({
                type: "POST",
                url: "/api/data/tagging/opd/otsus/new",
                data: dataForm,
                dataType: "JSON",
                success: function (response) {
                    if (response.success) {
                        console.log(response);

                        let opd = response.data;
                        let btnDelete = opd.tag.raps_count ? `
                            <div class="btn-group" role="group">
                                <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tidak bisa dihapus. RAP sudah ada. Hapus RAP dahulu untuk menghapus tagging ini!">
                                    <button class="btn btn-sm btn-danger btn-delete-tagging" value=${opd.tag.id} disabled>
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        ` : `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-danger btn-delete-tagging" value=${opd.tag.id}>
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        `;
                        if (!$(`#${opd.tag.kode_unik_opd_tag_otsus.replace(/\./g, '_')}`).length) {
                            $('#table-data-skpds').append(`
                                <tr id="${opd.tag.kode_unik_opd_tag_otsus.replace(/\./g, '_')}">
                                    <td>${opd.opd_text}</td>
                                    <td>${opd.tag.volume.replace('.', '').replace(',', '.')}</td>
                                    <td>${opd.tag.satuan}</td>
                                    <td>${opd.tag.sumberdana}</td>
                                    <td>${btnDelete}</td>
                                </tr>
                            `);
                        } else {
                            $(`#${opd.tag.kode_unik_opd_tag_otsus.replace(/\./g, '_')}`).html(`
                                <td>${opd.opd_text}</td>
                                <td>${opd.tag.volume.replace('.', '').replace(',', '.')}</td>
                                <td>${opd.tag.satuan}</td>
                                <td>${opd.tag.sumberdana}</td>
                                <td>${btnDelete}</td>
                            `);
                        }
                        $('#select-opd').val(null).trigger('change');
                        $('#input-vol-tag-opd').val('');
                        $('#select-sumberdana').val(null).trigger('change');
                        $('#select-opd').attr('disabled', false);
                        $('#input-vol-tag-opd').attr('disabled', false);
                        $('#select-sumberdana').attr('disabled', false);
                        $(`#taggingCount${response.data.target_aktifitas}`).html(response.data.countTagOpd);
                        showToast(response.message, response.alert);
                    }
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    let error = data.errors;
                    console.log(xhr.responseJSON);
                    if (error['id_target_aktifitas'] || error['tahun']) {
                        showToast(`Terjadi kesalahan. silakan hubungi admin`, 'danger');
                    }
                    if (error['opd'][0] || error['volume'][0] || error['sumberdana'][0]) {
                        $(`#opd_error`).html(error['opd'][0]);
                        $(`#volume_error`).html(error['volume'][0]);
                        $(`#sumberdana_error`).html(error['sumberdana'][0]);
                    }
                    if (error['opd']) {
                        $(`#opd_error`).html(error['opd'][0]);
                        $('#select-opd').addClass('is-invalid');
                        $('#select-opd').attr('disabled', false);
                    }
                    if (error['volume']) {
                        $(`#volume_error`).html(error['volume'][0]);
                        $('#input-vol-tag-opd').addClass('is-invalid');
                        $('#input-vol-tag-opd').attr('disabled', false);
                    }
                    if (error['sumberdana']) {
                        $(`#sumberdana_error`).html(error['sumberdana'][0]);
                        $('#select-sumberdana').addClass('is-invalid');
                        $('#select-sumberdana').attr('disabled', false);
                    }
                }
            });
        }
        setTimeout(() => {
            $('#tagSkpdModal .modal-show-content').show();
            $('#tagSkpdModal .modal-show-spinner').hide();
        }, 1000);
    });

    $('#input-vol-tag-opd').on('input', function () {
        let vol = $(this).val();
        if (vol) {
            let formatAngka = formatInputAngka(vol);
            $(this).val(formatAngka);
        }
    });

    $(document).on('click', '.btn-delete-tagging', function () {
        $('#tagSkpdModal .modal-show-content').hide();
        $('#tagSkpdModal .modal-show-spinner').show();
        let id_tagging = $(this).val();
        $.ajax({
            type: "POST",
            url: "/api/data/tagging/opd/otsus/delete",
            data: {
                id: id_tagging
            },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    let tagDelete = response.data;
                    if (tagDelete.kode_unik_opd_tag_otsus.replace(/\./g, '_') && $(`#${tagDelete.kode_unik_opd_tag_otsus.replace(/\./g, '_')}`).length) {
                        $(`#${tagDelete.kode_unik_opd_tag_otsus.replace(/\./g, '_')}`).remove();
                        setTimeout(() => {
                            showToast(response.message, response.alert);
                            $(`#taggingCount${response.target_aktifitas}`).html(response.countTagOpd);
                            console.log(response.target_aktifitas);
                        }, 100);
                    }
                }
                setTimeout(() => {
                    $('#tagSkpdModal .modal-show-content').show();
                    $('#tagSkpdModal .modal-show-spinner').hide();
                }, 500);
            },
            error: function (xhr) {
                let dataError = xhr.responseJSON
                showToast(dataError.message, dataError.alert);
                setTimeout(() => {
                    $('#tagSkpdModal .modal-show-content').show();
                    $('#tagSkpdModal .modal-show-spinner').hide();
                }, 500);
            }
        });
    });

});
