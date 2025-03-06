$(document).ready(function () {

    async function getAllSchedules() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/api/schedule/get_schedule',
                type: 'POST',
                dataType: 'json',
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
        let schedule = await getAllSchedules('/api/schedule/get/active', 'POST');
        let tahapan = schedule.data.map((item) => item.tahapan);
        let active = schedule.data.some((item) => item.status == 1);

        if (!active) {
            $('#scheduleModalLabel').html('Buat Jadwal');
            ['tahapan', 'keterangan', 'mulai', 'selesai'].forEach(element => {
                $(`#schedule-${element}`).val('');
                $(`#schedule-${element}`).removeAttr('disabled');
                $(`#schedule-${element}`).removeAttr('readonly');
            });
            $('#schedule-tahapan').html(`
                <option value="">Pilih...</option>
                ${tahapan.includes('ranwal') ? '<option disabled readonly>Rancangan Awal</option>' : '<option value="ranwal">Rancangan Awal</option>'}
                ${tahapan.includes('rancangan') ? '<option disabled readonly>Rancangan</option>' : '<option value="rancangan">Rancangan</option>'}
                ${tahapan.includes('final') ? '<option disabled readonly>Finalisasi</option>' : '<option value="final">Finalisasi</option>'}
                ${tahapan.includes('perubahan') ? '<option disabled readonly>Perubahan</option>' : '<option value="perubahan">Perubahan</option>'}
                ${tahapan.includes('pelaporan') ? '<option disabled readonly>Pelaporan</option>' : '<option value="pelaporan">Pelaporan</option>'}
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
        let id_schedule = $(this).val();
        id_schedule = JSON.parse(id_schedule);

        $.ajax({
            type: "POST",
            url: "/api/schedule/get_schedule",
            data: {
                id: id_schedule
            },
            dataType: "JSON",
            success: function (response) {
                const data = response.data[0]
                if (!data.status) {
                    $('#scheduleModal').modal('hide');
                    showToast('jadwal sudah selesai', 'danger');
                } else {
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
                }
            }
        });

    });
});
