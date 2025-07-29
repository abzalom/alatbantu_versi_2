$(document).ready(function () {

    $('#bahasRakortekUrusanModal').on('hide.bs.modal', function () {
        $('#modal-bahas-rakortek-urusan-show-spinner').show();
        $('#modal-bahas-rakortek-urusan-show-content').hide();
        $('#bahasRakortekUrusanModalLabel').text('');
        $('#input-satuan').val('');
        $('#input-target-nasional').val('');
        $('#input-usulan-target-daerah').val('');
        $('#input-target-daerah').val('');
        $('#select-bahas-status').val(null).trigger('change');
        $('#input-bahas-catatan').val('');
        $('#input-target-daerah').prop('disabled', false);
        $('#select-bahas-status').prop('disabled', false);
        $('#input-bahas-catatan').prop('disabled', false);
        $('#show-ubah-target-daerah').hide();
    });

    $('.btn-pembahasan-urusan').on('click', function () {
        const data = $(this).data('indikator');
        console.log(data);
        const targetDaerah = data.target.target_daerah ? data.target.target_daerah : data.target.usulan_target_daerah;

        $('#target-indikator-urusan-id').val(data.target.id);
        $('#bahasRakortekUrusanModalLabel').text(`${data.kode_indikator} ${data.nama_indikator}`);
        $('#input-satuan').val(data.target.satuan);
        $('#input-target-nasional').val(formatAngkaUrusan(data.target.target_nasional));
        $('#input-usulan-target-daerah').val(formatAngkaUrusan(data.target.usulan_target_daerah));
        $('#input-target-daerah').val(formatAngkaUrusan(targetDaerah));
        $('#select-bahas-status').val(data.target.pembahasan).trigger('change');
        $('#input-bahas-catatan').val(data.target.catatan);
        if (data.target.pembahasan && data.target.pembahasan !== 'perbaikan') {
            $('#input-target-daerah').prop('disabled', true);
            $('#select-bahas-status').prop('disabled', true);
            $('#input-bahas-catatan').prop('disabled', true);
            $('#show-ubah-target-daerah').show();
        }
        setTimeout(() => {
            $('#modal-bahas-rakortek-urusan-show-spinner').hide();
            $('#modal-bahas-rakortek-urusan-show-content').show();
        }, 1000);
    });

    $('#click-ubah-target-daerah').on('click', function () {
        $('#input-target-daerah').prop('disabled', false);
        $('#select-bahas-status').prop('disabled', false);
        $('#input-bahas-catatan').prop('disabled', false);
        // $('#show-ubah-target-daerah').hide();
    });

    function formatAngkaUrusan(angka) {
        if (angka === null || angka === undefined || angka === '') {
            return '-';
        }

        // pastikan angka dalam bentuk number
        angka = parseFloat(angka);

        // pembulatan ke dua angka desimal
        let dibulatkan = Math.round(angka * 100) / 100;

        // cek apakah hasil pembulatan punya desimal
        if (dibulatkan % 1 === 0) {
            // bilangan bulat, tampilkan tanpa desimal
            return dibulatkan.toString();
        } else {
            // ada desimal, ganti titik desimal menjadi koma
            return dibulatkan.toFixed(2).replace('.', ',');
        }
    }

    let debounceTimer;
    $('#search-opd-bidang').on('keyup', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            let searchValue = $(this).val().toLowerCase();
            $('.table-data').each(function () {
                let itemText = $(this).text().toLowerCase();
                $(this).toggle(itemText.includes(searchValue));
            });
        }, 300); // hanya eksekusi jika 300ms tidak diketik
    });

});
