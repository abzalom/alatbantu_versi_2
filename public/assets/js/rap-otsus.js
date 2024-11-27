$(document).ready(function () {

    $('#rap-select-target-aktifitas').on('change', function () {
        $('#rap-select-subkegiatan').attr('disabled', true);
        $('#rap-select-sumberdana').attr('disabled', true);
        let val = $(this).val();
        let satuan = $(this).find('option:selected').data('satuan');
        $('#rap-input-satuan-target-aktifitas').val(satuan);
        let volume = $(this).find('option:selected').data('volume');
        $('#rap-input-volume-target-aktifitas').val(volume);
        let sumberdana = $(this).find('option:selected').data('sumberdana');
        $('#rap-input-sumberdana-target-aktifitas').val(sumberdana);
        if (val !== '') {
            $('#rap-select-subkegiatan').attr('disabled', false);
            $('#rap-select-sumberdana').attr('disabled', false);
        }
    });

    $('#rap-select-subkegiatan').on('change', function () {
        let indikator = $(this).find('option:selected').data('indikator');
        let klasifikasi_belanja = $(this).find('option:selected').data('klasifikasi_belanja');
        let satuan = $(this).find('option:selected').data('satuan');

        $('#rap-disabled-klasfikasi-belanja').val(klasifikasi_belanja);
        $('#rap-disabled-indikator-subkegiatan').val(indikator);
        $('#rap-disabled-satuan-subkegiatan').val(satuan);
        console.log(indikator);
        console.log(klasifikasi_belanja);
        console.log(satuan);

    });

    [{
            className: '.copy-subkegiatan',
            dataName: 'subkegiatan'
        },
        {
            className: '.copy-anggaran',
            dataName: 'anggaran'
        },
        {
            className: '.copy-sumberdana',
            dataName: 'sumberdana'
        }
    ].map(function (item) {
        $(document).on(item.className, 'click', function () {
            let text = $(this).data(item.dataName);
            let id = $(this).data('id');
            $('.remove-bg-subkegiatan').removeClass('bg-warning');
            $('.remove-bg-sumberdana').removeClass('bg-warning');
            $('.remove-bg-anggaran').removeClass('bg-warning');
            if ($('#show-rap-subkegiatan-id-' + id).length) {
                $('#show-rap-subkegiatan-id-' + id).addClass('bg-warning');
            }
            if ($('#show-rap-anggaran-id-' + id).length) {
                $('#show-rap-anggaran-id-' + id).addClass('bg-warning');
            }
            if ($('#show-rap-sumberdana-id-' + id).length) {
                $('#show-rap-sumberdana-id-' + id).addClass('bg-warning');
            }
            copyToClipboard(text)
        });
    })

    $('#uploadIndikatorFile').on('change', function () {
        let fileName = $(this).val().split('\\').pop(); // Ambil nama file
        if (fileName) {
            console.log(fileName);
            $('#uploadIndikatorLebelName').html(fileName); // Setel teks pada tombol berikutnya
        } else {
            $(this).next('button').text('Upload'); // Jika tidak ada file, kembalikan teks default
        }
    });

    $('#rapFormUploadFileSubkegiatan').on('change', function () {
        let fileName = $(this).val().split('\\').pop(); // Ambil nama file
        if (fileName) {
            console.log(fileName);
            $('#uploadIndikatorLebelName').html(fileName); // Setel teks pada tombol berikutnya
        } else {
            $(this).next('button').text('Upload'); // Jika tidak ada file, kembalikan teks default
        }
    });

    const formEditName = [
        'sumberdana', 'vol_subkeg', 'anggaran', 'mulai', 'selesai', 'penerima_manfaat', 'jenis_layanan',
        'jenis_kegiatan', 'ppsb', 'multiyears', 'dana_lain', 'lokus', 'koordinat', 'keterangan', 'catatan'
    ];

    formEditName.forEach(function (field) {
        const fieldId = `#edit-rap-${field}`;

        // Menangani perubahan pada input atau select
        $(fieldId).on('input change', function () {
            $(this).removeClass('is-invalid');
            $('#' + field + '_error').html('');
        });
    });

    // Edit RAP Otsus

    $('#editRapModal').on('hidden.bs.modal', function () {
        $('#edit-rap-spinner').show();
        $('#edit-rap-show-data').hide();
        $('#form-edit-rap-otsus')[0].reset();
        $('#edit-rap-penerima-manfaat').val(null).trigger('change');
        $('#edit-rap-jenis_layanan').val(null).trigger('change');
        $('#edit-rap-jenis_kegiatan').val(null).trigger('change');
        $('#edit-rap-ppsb').val(null).trigger('change');
        $('#edit-rap-multiyears').val(null).trigger('change');
        // $('#edit-rap-dana_lain').val(null).trigger('change');
        // $('#edit-rap-lokus').val(null).trigger('change');

        $('#edit-rap-dana_lain option').each(function () {
            $(this).attr('selected', false).trigger('change');
        });
        $('#edit-rap-lokus option').each(function () {
            $(this).attr('selected', false).trigger('change');
        });

        formEditName.forEach(function (field) {
            $('#' + field + '_error').html('');
            $(`[name="${field}"]`).removeClass('is-invalid');
        });
    });

    $('.btn-edit-rap').on('click', function () {

        id = $(this).val();

        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/rap",
            data: {
                id: id
            },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    if (response.data.length > 0) {
                        let data = response.data[0];
                        // Target Aktifitas Utama
                        $('#edit-rap-disabled-target-aktifitas').val(data.target_aktifitas.kode_target_aktifitas + ' ' + data.target_aktifitas.uraian);
                        $('#edit-rap-disabled-target-aktifitas-satuan').val(data.target_aktifitas.satuan);
                        $('#edit-rap-disabled-target-aktifitas-kinerja').val(data.target_aktifitas.volume);
                        $('#edit-rap-disabled-target-aktifitas-sumberdana').val(data.target_aktifitas.sumberdana);
                        // RAP Otsus
                        $('#edit-rap-id').val(data.id);
                        $('#edit-rap-disabled-subkegiatan').val(data.text_subkegiatan);
                        $('#edit-rap-sumberdana').val(data.sumberdana);
                        console.log(data.sumberdana);
                        $('#edit-rap-vol_subkeg').val(data.vol_subkeg);
                        $('#edit-rap-anggaran').val(data.anggaran);
                        $('#edit-rap-mulai').val(data.mulai);
                        $('#edit-rap-selesai').val(data.selesai);
                        $('#edit-rap-penerima_manfaat').val(data.penerima_manfaat);
                        $('#edit-rap-jenis_layanan').val(data.jenis_layanan);
                        $('#edit-rap-jenis_kegiatan').val(data.jenis_kegiatan);
                        $('#edit-rap-ppsb').val(data.ppsb);
                        $('#edit-rap-multiyears').val(data.multiyears);

                        var dana_lain = JSON.parse(data.dana_lain);
                        console.log(dana_lain);

                        $('#edit-rap-dana_lain option').each(function () {
                            let idDanaLain = $(this).val();
                            console.log(idDanaLain);

                            if (idDanaLain && dana_lain.some(dana => dana.id == idDanaLain)) {
                                $(this).attr('selected', true).trigger('change');
                            }
                        });

                        var lokus = JSON.parse(data.lokus);
                        console.log(lokus);

                        $('#edit-rap-lokus option').each(function () {
                            let idLokus = $(this).val();
                            if (idLokus && lokus.some(lokus => lokus.id == idLokus)) {
                                $(this).attr('selected', true).trigger('change');
                            }
                        });

                        $('#edit-rap-koordinat').val(data.koordinat);
                        $('#edit-rap-keterangan').val(data.keterangan);
                        $('#edit-rap-catatan').val(data.catatan);

                        setTimeout(() => {
                            $('#edit-rap-spinner').hide();
                            $('#edit-rap-show-data').show();
                        }, 500);
                    }
                }
            }
        });
    });

    $('#form-edit-rap-otsus').on('submit', function (e) {
        e.preventDefault();
        $('#edit-rap-spinner').show();
        $('#edit-rap-show-data').hide();
        let dataForm = $(this).serialize();
        formEditName.forEach(function (field) {
            $('#' + field + '_error').html('');
            $(`[name="${field}"]`).removeClass('is-invalid');
        });
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/rap/update",
            data: dataForm,
            dataType: "JSON",
            success: function (response) {
                if (response.message || response.alert) {
                    showToast(response.message, response.alert || 'danger');
                }
                if (response.success) {
                    let data = response.data;
                    $('#editRapModal').modal('hide');
                    let copyDana = '';
                    if (data.sumberdana === 'Otsus 1,25%') {
                        copyDana = 'Dana Otonomi Khusus Kabupaten/Kota pada Provinsi Papua [SPESIFIC GRANT]';
                    }
                    if (data.sumberdana === 'Otsus 1%') {
                        copyDana = '1.2.01.03.01.0002 Dana Otonomi Khusus-Kabupaten/Kota pada Provinsi Papua-Umum [BLOCK GRANT]';
                    }
                    if (data.sumberdana === 'DTI') {
                        copyDana = '2.2.01.03.02.0002 Dana Tambahan Otonomi Khusus/Dana Tambahan Infrastruktur Kabupaten/Kota Papua [SPESIFIC GRANT]';
                    }
                    console.log(copyDana);

                    $('#show-rap-sumberdana-text-id-' + data.id).html(`
                        <button class="btn btn-sm btn-primary me-2 copy-sumberdana" data-sumberdana="${copyDana}" data-id="${data.id}">
                            <i class="fa-regular fa-clipboard"></i>
                        </button>
                        ${data.sumberdana}
                    `);
                    $('#show-rap-vol_subkeg-id-' + data.id).html(formatIDR(data.vol_subkeg));
                    $('#show-rap-anggaran-id-' + data.id).html(formatIDR(data.anggaran));
                }
            },
            error: function (xhr) {
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, 'danger');
                // Tampilkan pesan error pada form
                Object.entries(errorResponse.errors || {}).forEach(function ([key, value]) {
                    const mainKey = key.split('.')[0];
                    $(`[name="${mainKey}"]`).addClass('is-invalid');
                    if (formEditName.includes(mainKey)) {
                        $('#' + mainKey + '_error').html(value);
                    }
                });
                $('#edit-rap-spinner').hide();
                $('#edit-rap-show-data').show();
            }
        });
    });

    $('.btn-delete-rap').on('click', function () {
        let konfirmasi = confirm('Anda yakin ingin menghapus sub kegiatan ini?')
        if (konfirmasi) {
            let id = $(this).val();
            $.ajax({
                type: "POST",
                url: appApiUrl + "/api/data/rap/delete",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function (response) {
                    if (response.message || response.alert) {
                        showToast(response.message, response.alert || 'danger');
                    }
                    if (response.success) {
                        $('#rap-show-id-' + id).remove();
                        if ($('#rap-show tr').length === 0) { // Memastikan tbody digunakan
                            $('#rap-show').html(`
                                <tr id="rap-data-not-found">
                                    <td colspan="10" class="text-center">
                                        <h4>Data Not Found!</h4>
                                    </td>
                                </tr>
                            `);
                        }
                    }
                },
                error: function (xhr) {
                    let errorResponse = handleAjaxError(xhr);
                    showToast(errorResponse.message, 'danger');
                }
            });
        }
    });

    $('#rapViewFilesModal').on('hidden.bs.modal', function () {
        $('#rapViewFilesModalLabel').html('');
        $('#rap-show-list-files').html('');
    });

    $('.btn-view-files-rap').on('click', function () {
        let id_rap = $(this).val();
        let subkegiatan = $(this).data('subkegiatan');
        $('#rapViewFilesModalLabel').html(subkegiatan);
        console.log(id_rap);
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/rap/file-check",
            data: {
                id_rap: id_rap
            },
            dataType: "JSON",
            success: function (response) {
                if (response.status) {
                    console.log(response);
                    let data = response.data;

                    data.forEach(item => {
                        $('#rap-show-list-files').append(`
                            <tr>
                                <td>${item.filename.toUpperCase()}</td>
                                <td>:</td>
                                <td>${item.exists ? item.file : 'Tidak ada file!'}</td>
                            </tr>
                        `);
                    });

                }
            }
        });
    });

});
