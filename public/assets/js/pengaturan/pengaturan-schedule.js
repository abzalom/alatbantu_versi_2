$(document).ready(function () {

    async function getAllSchedules(url) {
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
        let year = tahunAnggaran - new Date().getFullYear();
        let schedule = await getAllSchedules();

        let active = schedule.data.some(item => item.status == 1);

        if (!active) {
            $('#scheduleModalLabel').html('Buat Jadwal');
            ['tahapan', 'keterangan', 'mulai', 'selesai'].forEach(element => {
                $(`#schedule-${element}`).val('').removeAttr('disabled readonly');
            });

            let rakortek = schedule.data.find(item => item.tahapan === 'rakortek'); // Ambil satu saja
            let ranwal = schedule.data.find(item => item.tahapan === 'ranwal'); // Ambil satu saja
            let rancangan = schedule.data.find(item => item.tahapan === 'rancangan'); // Ambil satu saja
            let final = schedule.data.find(item => item.tahapan === 'final'); // Ambil satu saja
            let perubahan = schedule.data.find(item => item.tahapan === 'perubahan'); // Ambil satu saja

            if (typeof rakortek == "undefined" && typeof ranwal == "undefined" && typeof rancangan == "undefined" && typeof final == "undefined" && typeof perubahan == "undefined") {
                $('#schedule-tahapan').html(`
                    <option value="" selected>Pilih...</option>
                    <option value="rakortek">Rakortek</option>
                    <option disabled readonly>Ranwal</option>
                    <option disabled readonly>Rancangan</option>
                    <option disabled readonly>Final</option>
                    <option disabled readonly>Perubahan</option>
                `);
            } else if (typeof rakortek !== "undefined" && typeof ranwal == "undefined" && typeof rancangan == "undefined" && typeof final == "undefined" && typeof perubahan == "undefined") {
                $('#schedule-tahapan').html(`
                    <option value="" selected>Pilih...</option>
                    <option value="rakortek">Rakortek</option>
                    <option value="ranwal">Ranwal</option>
                    <option disabled readonly>Rancangan</option>
                    <option disabled readonly>Final</option>
                    <option disabled readonly>Perubahan</option>
                `);
            } else if (typeof rakortek !== "undefined" && typeof ranwal != "undefined" && typeof rancangan == "undefined" && typeof final == "undefined" && typeof perubahan == "undefined") {
                $('#schedule-tahapan').html(`
                    <option value="" selected>Pilih...</option>
                    <option disabled readonly>Rakortek</option>
                    <option value="ranwal">Ranwal</option>
                    <option value="rancangan">Rancangan</option>
                    <option disabled readonly>Final</option>
                    <option disabled readonly>Perubahan</option>
                `);
            } else if (typeof rakortek !== "undefined" && typeof ranwal != "undefined" && typeof rancangan != "undefined" && typeof final == "undefined" && typeof perubahan == "undefined") {
                $('#schedule-tahapan').html(`
                    <option value="" selected>Pilih...</option>
                    <option disabled readonly>Rakortek</option>
                    <option disabled readonly>Ranwal</option>
                    <option value="rancangan">Rancangan</option>
                    <option value="final">Final</option>
                    <option disabled readonly>Perubahan</option>
                `);
            } else if (typeof rakortek !== "undefined" && typeof ranwal != "undefined" && typeof rancangan != "undefined" && typeof final != "undefined" && typeof perubahan == "undefined") {
                $('#schedule-tahapan').html(`
                    <option value="" selected>Pilih...</option>
                    <option disabled readonly>Rakortek</option>
                    <option disabled readonly>Ranwal</option>
                    <option disabled readonly>Rancangan</option>
                    <option value="final">Final</option>
                    <option value="perubahan">Perubahan</option>
                `);
            } else if (typeof rakortek !== "undefined" && typeof ranwal != "undefined" && typeof rancangan != "undefined" && typeof final != "undefined" && typeof perubahan != "undefined") {
                $('#schedule-tahapan').html(`
                    <option value="" selected>Pilih...</option>
                    <option disabled readonly>Rakortek</option>
                    <option disabled readonly>Ranwal</option>
                    <option disabled readonly>Rancangan</option>
                    <option disabled readonly>Final</option>
                    <option value="perubahan">Perubahan</option>
                `);
            }


            $('#modal-schedule-show-spinner').hide();
            $('#modal-schedule-show-content').show();
        } else {
            showToast('Jadwal sebelumnya masih aktif', 'danger');
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
                    $('#schedule-form').attr('action', '/config/schedule/rap/update');

                    $('#modal-schedule-show-spinner').hide();
                    $('#modal-schedule-show-content').show();
                }
            }
        });

    });
});
