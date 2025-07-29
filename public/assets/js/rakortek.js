$(document).ready(function () {
    $('#newTargetIndikatorUrusanModal').on('hide.bs.modal', function () {
        setTimeout(() => {
            $('#modal-target-indikator-show-spinner').show();
            $('#modal-target-indikator-show-content').hide();
            $('#hidden_id_indikator').val(null);
            $('#hidden_id_target_indikator').val(null);
            $('#show-indikator-urusan').val(null);
            $('#show-satuan-indikator-urusan').val(null);
            $('#show-target-nasional-indikator-urusan').val(null);
            $('#show-target-daerah-indikator-urusan').val(null);
            $('#show-target-daerah-indikator-urusan').attr('disabled', false);
        }, 500);
    });

    $('.edit-target-urusan').on('click', function () {
        let data = JSON.parse($(this).val()); // Menggunakan attr('value') bukan val()
        console.log(data);
        $('#hidden_id_indikator').val(data.id);
        $('#hidden_id_target_indikator').val(data.target ? data.target.id : '');
        $('#show-indikator-urusan').val(data.nama_indikator);
        $('#show-satuan-indikator-urusan').val(data.satuan);
        $('#show-target-nasional-indikator-urusan').val(data.target ? formatAngka(data.target.target_nasional) : '');
        $('#show-target-daerah-indikator-urusan').val(data.target ? formatAngka(data.target.usulan_target_daerah) : '');
        if (data.target.validasi || data.target.pembahasan !== 'perbaikan') {
            $('#show-target-daerah-indikator-urusan').attr('disabled', true);
        } else {
            $('#show-target-daerah-indikator-urusan').attr('disabled', false);
        }

        setTimeout(() => {
            $('#modal-target-indikator-show-spinner').hide();
            $('#modal-target-indikator-show-content').show();
            $('#show-target-daerah-indikator-urusan').focus();
        }, 1000);
    });


});
