$(document).ready(function () {

    // Opd Tag Otsus Change Event
    $('#select-opd_tag_otsus').on('change', function () {
        var satuan = $(this).find(':selected').data('satuan');
        var volume = $(this).find(':selected').data('volume');
        if ($(this).val() === '') {
            satuan = 'Satuan';
            volume = '';
        }
        console.log(satuan, volume);
        $('#view-volume_tag').val(formatAngka(volume));
        $('#view-satuan_volume_tag').text(satuan);
        $('#input-vol_tag_otsus').val(volume);
        $('#input-satuan_volume_tag').val(satuan);
    });

    // Sub Kegiatan Change Event
    $('#select-subkegiatan').on('change', function () {
        var indikator = $(this).find(':selected').data('indikator');
        var klasifikasi_belanja = $(this).find(':selected').data('klasifikasi_belanja');
        var satuan = $(this).find(':selected').data('satuan');
        if ($(this).val() === '') {
            indikator = '';
            klasifikasi_belanja = '';
            satuan = 'Satuan';
        }
        $('#view-indikator').val(indikator);
        $('#view-klasifikasi_belanja').val(klasifikasi_belanja);
        $('#view-satuan_subkeg').text(satuan);
        $('#input-indikator').val(indikator);
        $('#input-klasifikasi_belanja').val(klasifikasi_belanja);
        $('#input-satuan_subkeg').val(satuan);
    });

    // Koordinat show if jenis kegiatan is 'Fisik'
    $('#select-jenis_kegiatan').change(function () {
        var jenisKegiatan = $(this).val();
        if (jenisKegiatan === 'fisik') {
            $('#div-koordinat').show();
            $('#input-koordinat').prop('disabled', false);
        } else {
            $('#div-koordinat').hide();
            $('#input-koordinat').prop('disabled', true);
        }
    });

    ['kak', 'rab', 'pendukung1', 'pendukung2', 'pendukung3'].forEach(function (fileType) {
        $('#upload-file-' + fileType).on('change', function () {
            var fileName = $(this).val().split('\\').pop();
            if (fileName === '') {
                if (fileType === 'kak' || fileType === 'rab') {
                    fileName = 'Pilih File ' + fileType.toUpperCase();
                    $('#error-div-' + fileType).html(`
                        <span id="file_${fileType}_name" class="text-danger">File ${fileType.toUpperCase()} wajib diunggah.</span>
                    `);
                    $('#show-file-' + fileType).addClass('is-invalid text-danger');
                } else {
                    fileName = 'Pilih File Pendukung Lainnya';
                }
            } else {
                $('#error-div-' + fileType).html('');
                $('#show-file-' + fileType).removeClass('is-invalid text-danger');
            }
            $('#show-file-' + fileType).text(fileName);
            console.log(fileName);
        });
    });

    // Validasi untuk semua select yang wajib diisi
    [{
            id: 'select-opd_tag_otsus',
            msg: 'Pilihan wajib diisi.'
        },
        {
            id: 'select-subkegiatan',
            msg: 'Pilihan wajib diisi.'
        },
        {
            id: 'select-jenis_layanan',
            msg: 'Pilihan wajib diisi.'
        },
        {
            id: 'select-penerima_manfaat',
            msg: 'Pilihan wajib diisi.'
        },
        {
            id: 'select-ppsb',
            msg: 'Pilihan wajib diisi.'
        },
        {
            id: 'select-multiyears',
            msg: 'Pilihan wajib diisi.'
        },
        {
            id: 'select-jenis_kegiatan',
            msg: 'Pilihan wajib diisi.'
        },
        {
            id: 'select-lokus',
            msg: 'Lokus wajib diisi.'
        },
        {
            id: 'select-dana_lain',
            msg: 'Dana lain wajib diisi.'
        }
    ].forEach(function (item) {
        $('#' + item.id).on('change', function () {
            var val = $(this).val();
            var isValid = Array.isArray(val) ? val.length > 0 : val !== '';
            if (!isValid) {
                $('#error-div-' + item.id).html(`
                    <span id="${item.id}" class="text-danger">${item.msg}</span>
                `);
                $(this).addClass('is-invalid');
            } else {
                $('#error-div-' + item.id).html('');
                $(this).removeClass('is-invalid');
            }
        });
    });

    [{
            'id': 'input-vol_subkeg',
            'msg': 'Volume wajib diisi.'
        }, {
            'id': 'input-anggaran',
            'msg': 'Anggaran wajib diisi.'
        }, {
            'id': 'input-mulai',
            'msg': 'Tanggal mulai wajib diisi.'
        },
        {
            'id': 'input-selesai',
            'msg': 'Tanggal selesai wajib diisi.'
        },
        {
            'id': 'input-koordinat',
            'msg': 'Koordinat wajib diisi.'
        }, {
            'id': 'input-keterangan',
            'msg': 'Keterangan wajib diisi.'
        }
    ].forEach(function (item) {
        $('#' + item.id).on('input', function () {
            var val = $(this).val();
            if (val.trim() === '') {
                $('#error-div-' + item.id).html(`
                    <span id="${item.id}" class="text-danger">${item.msg}</span>
                `);
                $(this).addClass('is-invalid');
            } else {
                $('#error-div-' + item.id).html('');
                $(this).removeClass('is-invalid');
            }
        });
    });

    $('#form-renja-rap').on('submit', function (e) {
        e.preventDefault();
        let hasError = false;
        ['upload-file-kak', 'upload-file-rab'].forEach(function (fileType) {
            var fileInput = $('#' + fileType);
            var typeKey = fileType.split('-')[2];
            // Jika isEdit false (tambah), file wajib diupload. Jika isEdit true (edit), file tidak wajib.
            if (!isEdit && fileInput.val() === '') {
                $('#show-file-' + typeKey).addClass('is-invalid text-danger');
                $('#error-div-' + typeKey).html(`
                    <span id="file_${typeKey}_name" class="text-danger">File ${typeKey.toUpperCase()} wajib diunggah.</span>
                `);
                fileInput.addClass('is-invalid');
                hasError = true;
            } else {
                $('#error-div-' + typeKey).html('');
                $('#show-file-' + typeKey).removeClass('is-invalid text-danger');
                fileInput.removeClass('is-invalid');
            }
        });

        if (!hasError) {
            this.submit();
        } else {
            showToast('File KAK & RAB wajib diunggah.', 'danger');
        }
    });

    $('.btn-remove-file-pendukung').on('click', function () {
        alert('Hapus file pendukung tidak didukung pada versi ini.');
    });
});
