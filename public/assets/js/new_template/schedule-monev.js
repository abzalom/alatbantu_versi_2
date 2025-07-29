$(document).ready(function () {

    $('#ScheduleMonevModal').on('hide.bs.modal', function () {
        // Reset semua inputan
        $('#ScheduleMonevModal .modal-show-content').hide();
        $('#ScheduleMonevModal .modal-show-spinner').show();
        $('#new-item-input').html(``);
        $('#edit-item-input').html(``);
    });

    $('#btn-create-schedule').on('click', function () {
        console.log(dataJadwalMonev);
        let jadwalAktif = dataJadwalMonev.filter(jadwal => jadwal.status == 1);
        if (jadwalAktif.length > 0) {
            // modal #ScheduleMonevModal hide
            setTimeout(() => {
                setTimeout(() => {
                    showToast('Jadwal Monev Aktif sudah ada, silahkan nonaktifkan terlebih dahulu', 'danger');
                }, 100);
                $('#ScheduleMonevModal').modal('hide');
            }, 1000);
            return;
        } else {
            $('#new-item-input').html(`
                <input type="hidden" name="created_by" value="${auth.id}"></input>
            `);
            $('#ScheduleMonevModal .modal-show-spinner').hide();
            $('#ScheduleMonevModal .modal-show-content').show();
        }
    });

    $('.btn-edit-schedule').on('click', function () {
        let data = $(this).val() ? JSON.parse($(this).val()) : null;
        if (data) {
            $('#edit-item-input').html(`
                <input type="hidden" name="id" value="${data.id}"></input>
                <input type="hidden" name="updated_by" value="${auth.id}"></input>
            `);
            $('#add-schedule-monev-nama').val(data.nama);
            $('#add-schedule-monev-keterangan').val(data.keterangan);
            setTimeout(() => {
                $('#ScheduleMonevModal .modal-show-spinner').hide();
                $('#ScheduleMonevModal .modal-show-content').show();
            }, 500);
        } else {
            setTimeout(() => {
                setTimeout(() => {
                    showToast('Terjadi kesalahan', 'danger');
                }, 100);
                $('#ScheduleMonevModal').modal('hide');
            }, 1000);
        }
        console.log(data);
    });
});
