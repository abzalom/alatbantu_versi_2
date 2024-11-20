$(document).ready(function () {

    $('#alokasDanaOtsusModal').on('hide.bs.modal', function () {
        $('#form-post-alokasi-dana')[0].reset();
        $('#alokasDanaOtsusModalLabel').html('Input Alokas Dana Otsus Dan DTI');
        $('#tahun_error').html('');
        $('#alokasi_bg_error').html('');
        $('#alokasi_sg_error').html('');
        $('#alokasi_dti_error').html('');
        $('#status_error').html('');
        $('#hidden-input').html('');
        $('#tahun-input').attr('disabled', false);
    });

    $('#form-post-alokasi-dana').on('submit', function (e) {
        e.preventDefault(); // Mencegah reload halaman saat form disubmit

        // Ambil data dari form dan tentukan URL berdasarkan ada tidaknya 'id'
        var dataForm = $(this).serialize();
        let url = dataForm.includes('id') ? '/api/data/otsus/alokasi_dana/update' : '/api/data/otsus/alokasi_dana/insert';

        // Bersihkan pesan error sebelumnya
        ['tahun', 'alokasi_bg', 'alokasi_sg', 'alokasi_dti', 'status'].forEach(function (field) {
            $('#' + field + '_error').html('');
        });

        // Lakukan request AJAX
        $.ajax({
            type: "POST",
            url: appApiUrl + url,
            data: dataForm,
            success: function (response) {
                // Tampilkan toast notifikasi berdasarkan response
                showToast(response.message, response.alert || 'danger');

                if (response.success) {
                    // Format angka dan data untuk menampilkan ke dalam tabel
                    updateDanaTable(response.data);
                    $('#alokasDanaOtsusModal').modal('hide');
                } else {
                    showToast('Terjadi kesalahan. tidak ada respon data!', 'danger');
                }
            },
            error: function (xhr) {
                // Tangani error dengan parsing JSON response
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, 'danger');

                // Tampilkan pesan error pada form
                Object.entries(errorResponse.errors || {}).forEach(function ([key, value]) {
                    if (['tahun', 'alokasi_bg', 'alokasi_sg', 'alokasi_dti', 'status'].includes(key)) {
                        $('#' + key + '_error').html(value);
                    }
                });
            }
        });
    });

    // Fungsi untuk memperbarui tabel dana
    function updateDanaTable(data) {
        if ($(document).find('#not-found-data').length > 0) {
            $('#dana-show').html('');
        }
        let alokasi_bg = parseFloat(data.alokasi_bg);
        let alokasi_sg = parseFloat(data.alokasi_sg);
        let alokasi_dti = parseFloat(data.alokasi_dti);
        let rowHtml = `
            <td class="text-center">${data.tahun}</td>
            <td class="text-end">${formatIDR(alokasi_bg)}</td>
            <td class="text-end">${formatIDR(alokasi_sg)}</td>
            <td class="text-end">${formatIDR(alokasi_dti)}</td>
            <td class="text-end">${formatIDR(alokasi_bg + alokasi_sg + alokasi_dti)}</td>
            <td class="text-center">${data.status}</td>
            <td style="width: 2%" class="text-center">
                <div class="btn-group">
                    <button class="btn btn-sm btn-info btn-edit-dana" data-id="${data.id}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete-dana" data-id="${data.id}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        `;

        let $row = $(`#dana-id-${data.id}`);
        if ($row.length) {
            $row.html(rowHtml);
        } else {
            $('#dana-show').append(`<tr id="dana-id-${data.id}">${rowHtml}</tr>`);
        }

        $('#total-otsus').html(formatIDR(data.total_otsus));

        // Animasi background berubah dan kembali
        highlightRow(`#dana-id-${data.id}`);
    }


    $(document).on('click', '.btn-edit-dana', function () {
        let id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/otsus/alokasi_dana",
            data: {
                id: id
            },
            dataType: "JSON",
            success: function (response) {
                let data = response.data[0];
                console.log(data);
                if (response.success) {
                    $('#alokasDanaOtsusModal').modal('show');
                    $('#alokasDanaOtsusModalLabel').html('Update Alokasi Tahun ' + data.tahun);
                    $('#hidden-input').html('<input type="hidden" name="id" value="' + data.id + '">');
                    $('#tahun-input').val(data.tahun).change();
                    $('#tahun-input').attr('disabled', true);
                    $('#alokasi-bg-input').val(parseFloat(data.alokasi_bg).toFixed(2));
                    $('#alokasi-sg-input').val(parseFloat(data.alokasi_sg).toFixed(2));
                    $('#alokasi-dti-input').val(parseFloat(data.alokasi_dti).toFixed(2));
                    $('#status-input').val(data.status);
                }
            }
        });
    });

    $(document).on('click', '.btn-delete-dana', function () {
        let id = $(this).data('id');

        let konfirmasi = confirm('Anda yakin ingin menghapus data ini?');

        if (konfirmasi) {
            $.ajax({
                type: "POST",
                url: appApiUrl + "/api/data/otsus/alokasi_dana/delete",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function (response) {
                    if (response) {
                        console.log(response);
                        $(`#dana-id-${id}`).remove();
                        if ($('#dana-show tr').length === 0) {
                            $('#dana-show').html(`
                                <tr id="not-found-data">
                                    <td colspan="6" class="text-center"><h4>Data Not Found!</h4></td>
                                </tr>
                            `);
                        }
                        showToast(response.message, response.alert);
                    }
                },
                error: function (xhr) {
                    let errorResponse = handleAjaxError(xhr);
                    showToast(errorResponse.message, 'danger');
                }
            });
        }

    });

});
