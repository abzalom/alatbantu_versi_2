$(document).ready(function () {

    $('#bahasRakorRapppModal').on('modal.bs.show', function () {
        $('#modal-bahas-rakortek-rappp-show-spinner').show();
        $('#modal-bahas-rakortek-rappp-show-content').hide();
        $('#bahas-opd_tag_otsus_id').val('');
        $('#bahas-target_aktifitas-show').text('');
        $('#bahas-satuan-show').text('');
        $('#bahas-volume-show').text('');
        $('#bahas-sumberdana-show').text('');
        $('#bahas-pembahasan-select').val('perbaikan');
        $('#bahas-pembahasan-select').attr('disabled', false);
        $('#bahas-catatan-textarea').val('');
        $('#bahas-catatan-textarea').attr('disabled', false);
        $('#ubah-pembahasan-rappp').hide();
    });

    $('.btn-bahas-rakor-rappp').on('click', function () {
        const data = $(this).data('target_aktifitas');
        // const data = null;

        if (!data) {
            setTimeout(() => {
                $('#bahasRakorRapppModal').modal('hide');
                showToast('Terjadi kesalahan, data tidak ditemukan', 'danger');
            }, 1000);
            return;
        }

        $('#bahas-opd_tag_otsus_id').val(data.rappp.id);
        $('#bahas-target_aktifitas-show').text(data.kode_target_aktifitas + ' - ' + data.uraian_target_aktifitas);
        $('#bahas-satuan-show').text(data.rappp.satuan);
        if (!data.rappp.pembahasan || data.rappp.pembahasan.length == "" || data.rappp.pembahasan == 'perbaikan') {
            $('#bahas-pembahasan-select').attr('disabled', false);
            $('#bahas-catatan-textarea').attr('disabled', false);
            $('#ubah-pembahasan-rappp').hide();
        } else {
            $('#ubah-pembahasan-rappp').show();
            $('#bahas-pembahasan-select').attr('disabled', true);
            $('#bahas-catatan-textarea').attr('disabled', true);
        }
        $('#bahas-volume-show').text(data.rappp.volume);
        $('#bahas-sumberdana-show').text(data.rappp.sumberdana);
        $('#bahas-pembahasan-select').val(data.rappp.pembahasan);
        $('#bahas-catatan-textarea').val(data.rappp.catatan);

        setTimeout(() => {
            $('#modal-bahas-rakortek-rappp-show-spinner').hide();
            $('#modal-bahas-rakortek-rappp-show-content').show();
        }, 1000);

        console.log(data);
    });

    $('#click-ubah-pembahasan-rappp').on('click', function () {
        $('#bahas-pembahasan-select').attr('disabled', false);
        $('#bahas-catatan-textarea').attr('disabled', false);
    });

    $('#lihatCatatanRakorRapppModal').on('modal.bs.hide', function () {
        $('#catatan-target_aktifitas-show').text('');
        $('#catatan-satuan-show').text('');
        $('#catatan-volume-show').text('');
        $('#catatan-sumberdana-show').text('');
        $('#catatan-status_pembahasan-show').text('');
        $('#catatan-catatan-show').text('');
    });

    $('.btn-bahas-rappp-lihat_catatan').on('click', function () {
        const data = $(this).data('rappp');
        console.log(data);
        if (!data || !data.rappp) {
            setTimeout(() => {
                $('#lihatCatatanRakorRapppModal').modal('hide');
                showToast('Terjadi kesalahan, data tidak ditemukan', 'danger');
            }, 1000);
            return;
        }
        $('#catatan-target_aktifitas-show').text(data.kode_target_aktifitas + ' - ' + data.uraian_target_aktifitas);
        $('#catatan-satuan-show').text(data.rappp.satuan);
        $('#catatan-volume-show').text(data.rappp.volume ? data.rappp.volume : '-');
        $('#catatan-sumberdana-show').text(data.rappp.sumberdana ? data.rappp.sumberdana : '-');
        $('#catatan-status_pembahasan-show').text(data.rappp.pembahasan ? data.rappp.pembahasan : 'Belum Dibahas');
        $('#catatan-catatan-show').text(data.rappp.catatan ? data.rappp.catatan : 'Belum ada catatan / belum dibahas');
    });

    if (bahas['status']) {
        $('#show-btn-setujui-all').show();
    }
    if (validasi['status']) {
        $('#show-btn-validasi-all').show();
    }

    $('#btn-setujui-all').on('click', function () {
        console.log(bahas);
        bahas['opd_tag_otsus_id'].forEach(id => {
            $('#bahas-all-opd_tag_otsus_id').append(
                `<input type="hidden" name="opd_tag_otsus_id[]" value="${id}">`
            );
        });
    });
    $('#btn-validasi-all').on('click', function () {
        console.log(validasi);
        validasi['opd_tag_otsus_id'].forEach(id => {
            $('#validasi-all-opd_tag_otsus_id').append(
                `<input type="hidden" name="opd_tag_otsus_id[]" value="${id}">`
            );
        });
    });


});
