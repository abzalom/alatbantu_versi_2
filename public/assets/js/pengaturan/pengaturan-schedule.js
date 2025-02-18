$(document).ready(function () {

    async function getRequestSchedule(path, type) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: path,
                type: type,
                dataType: 'json',
                headers: { // Perbaikan dari 'header' ke 'headers'
                    'x-token': userToken,
                },
                success: function (response) {
                    resolve(response);
                },
                error: function (error) {
                    reject(error);
                }
            })
        })
    }

    $('#scheduleModal').on('hide.bs.modal', function () {
        ['tahapan', 'keterangan', 'mulai', 'selesai'].forEach(element => {
            $(`#schedule-${element}`).val('');
            $(`#schedule-${element}`).attr('disabled', 'disabled');
            $(`#schedule-${element}`).attr('readonly', 'readonly');
        });
        $('#schedule-edit-element').html(``);
        $('#schedule-tahapan').html(``);
        $('#modal-schedule-show-spinner').show();
        $('#modal-schedule-show-content').hide();
    });

    $('#btn-create-schedule').on('click', async function () {
        let schedule = await getRequestSchedule('/api/schedule/get/active', 'POST');
        if (!schedule.active) {
            $('#scheduleModalLabel').html('Buat Jadwal');
            ['tahapan', 'keterangan', 'mulai', 'selesai'].forEach(element => {
                $(`#schedule-${element}`).val('');
                $(`#schedule-${element}`).removeAttr('disabled');
                $(`#schedule-${element}`).removeAttr('readonly');
            });
            $('#schedule-tahapan').html(`
                <option value="">Pilih...</option>
                <option value="ranwal">Rancangan Awal</option>
                <option value="rancangan">Rancangan</option>
                <option value="final">Finalisasi</option>
                <option value="perubahan">Perubahan</option>
                <option value="pelaporan">Pelaporan</option>
            `);
            $('#modal-schedule-show-spinner').hide();
            $('#modal-schedule-show-content').show();
        } else {
            showToast('jadwal sebelumnya masih aktif', 'danger');
            $('#scheduleModal').modal('hide');
        }
    });

    $('.btn-edit-schedule').on('click', function () {
        $('#scheduleModalLabel').html('Edit Jadwal');
        let data = $(this).val();
        data = JSON.parse(data);
        $('#schedule-edit-element').append(`
            <input type="hidden" name="id" value="${data.id}">
            <input type="hidden" name="updated_by" value="${auth.id}">
        `);
        ['keterangan', 'selesai'].forEach(element => {
            $(`#schedule-${element}`).val('');
            $(`#schedule-${element}`).removeAttr('disabled');
            $(`#schedule-${element}`).removeAttr('readonly');
        });
        $('#schedule-tahapan').html(`
            <option value="${data.tahapan}">${data.tahapan}</option>
        `);
        $('#schedule-keterangan').val(data.keterangan);
        $('#schedule-mulai').val(data.mulai);
        $('#schedule-selesai').val(data.selesai);
        $('#schedule-form').attr('action', '/config/schedule/update');

        $('#modal-schedule-show-spinner').hide();
        $('#modal-schedule-show-content').show();
    });
});
