$(document).ready(function () {
    const name_input = ['nama', 'nip', 'pangkat', 'jabatan'];

    $('#search-opd-input').on('focus', function () {
        $(this).removeClass('border-primary');
    });
    $('#search-opd-input').on('focusout', function () {
        $(this).addClass('border-primary');
    });

    $('#search-opd-input').on('keyup', function () {
        let value = $(this).val().toLowerCase();
        $("#table-list-opd tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });


    $('#listKepalaOpdModal, #kepalaOpdModal').on('hide.bs.modal', function () {
        // reset input dan select pada formKepalaOpd by name
        name_input.forEach(function (name) {
            $(`#formKepalaOpd [name="${name}"]`).val('').trigger('change');
        });
        // reset invalid dan error message
        name_input.forEach(function (name) {
            $(`#formKepalaOpd [name="${name}"]`).removeClass('is-invalid');
            $(`#${name}_error`).text('');
        });
    });

    $('.btn-kepala-opd').on('click', function () {
        $('.listKepalaOpdModalDescription').text('');
        $('#listKepalaOpdModalList').empty();
        const id_opd = $(this).val();
        console.log(id_opd);
        $.ajax({
            type: "post",
            url: "/api/data/opd",
            data: {
                id: id_opd
            },
            dataType: "json",
            success: function (response) {
                if (response.success && response.data) {
                    let opd = response.data[0];
                    $('#id_opd').val(opd.id);
                    $('.listKepalaOpdModalDescription').text(opd.nama_opd);
                    $.each(opd.kepala, function (indexKepala, valueKapala) {
                        let iconAksi = valueKapala.status ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-check"></i>';
                        let btnCollor = valueKapala.status ? 'btn-danger' : 'btn-success';
                        let btnAksi = `
                            <button class="btn btn-sm ${btnCollor} btn-status-kepala-opd" value="${valueKapala.nip}" data-bs-toggle="tooltip" title="${valueKapala.status ? 'Nonaktifkan' : 'Aktifkan'}">
                                ${iconAksi}
                            </button>
                        `
                        $('#listKepalaOpdModalList').append(`
                            <tr>
                                <td>
                                ${valueKapala.nama}
                                <br>
                                NIP. ${valueKapala.nip}
                                <br>
                                ${valueKapala.pangkat}
                                </td>
                                <td>${valueKapala.jabatan}</td>
                                <td>${valueKapala.tahun}</td>
                                <td id="row-modal-status-kepala-opd-${valueKapala.nip.replace(/\./g, '_')}">${valueKapala.status ? 'Aktif' : 'Tidak Aktif'}</td>
                                <td id="row-modal-action-kepala-opd-${valueKapala.nip.replace(/\./g, '_')}" class="text-center">
                                    ${btnAksi}
                                </td>
                            </tr>
                        `);
                    });
                    console.log(opd);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert ? errorResponse.alert : 'danger');
            }
        });
    });


    // body delegate untuk btn-set-status-kepala
    $(document).on('click', '.btn-status-kepala-opd', function () {
        const nip = $(this).val();
        $(`#row-modal-action-kepala-opd-${nip.replace(/\./g, '_')}`).html(`
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `);
        $.ajax({
            type: "POST",
            url: "/api/update/kepala_opd/status",
            data: {
                nip: nip
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.success && response.data) {
                    console.log(response.data.status);
                    setTimeout(() => {
                        showToast(`Status kepala OPD ${response.data.nama} berhasil diubah.`, 'success');
                        let btnCollor = response.data.status ? 'btn-danger' : 'btn-success';
                        let iconAksi = response.data.status ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-check"></i>';
                        let btnAksi = `
                            <button class="btn btn-sm ${btnCollor} btn-status-kepala-opd" value="${response.data.nip}" data-bs-toggle="tooltip" title="${response.data.status ? 'Nonaktifkan' : 'Aktifkan'}">
                                ${iconAksi}
                            </button>
                        `;
                        $(`#row-modal-status-kepala-opd-${response.data.nip.replace(/\./g, '_')}`).html(response.data.status ? 'Aktif' : 'Tidak Aktif');
                        $(`#row-modal-action-kepala-opd-${response.data.nip.replace(/\./g, '_')}`).html(btnAksi);
                        // set title
                        if (response.data.status) {
                            $('#row-kepala-opd-' + response.data.kode_unik_opd.replace(/\./g, '_')).html(`
                                ${response.data.nama} <br>
                                NIP. ${response.data.nip} <br>
                                ${response.data.pangkat}
                            `);
                        } else {
                            $('#row-kepala-opd-' + response.data.kode_unik_opd.replace(/\./g, '_')).html('-');
                        }
                    }, 1000);
                } else {
                    showToast(`Gagal mengubah status kepala OPD: ${response.message}`, 'danger');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert ? errorResponse.alert : 'danger');
            }
        });
    });

    $('#formKepalaOpd').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        // validasi formData dengan name input
        let isValid = true;

        name_input.forEach(function (name) {
            let input = $(`[name="${name}"]`);
            if (input.val() === '') {
                isValid = false;
                input.addClass('is-invalid');
                $(`#${name}_error`).text(`Field ${name} tidak boleh kosong.`);
            } else {
                input.removeClass('is-invalid');
                $(`#${name}_error`).text('');
            }
        });

        if (!isValid) {
            showToast('Harap lengkapi semua field yang diperlukan.', 'danger');
            return;
        }
        console.log(formData);
        $.ajax({
            type: "post",
            url: '/api/save/kepala_opd/save',
            data: formData,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    showToast('Kepala OPD berhasil disimpan.', 'success');
                    $('#kepalaOpdModal').modal('hide');
                    $('#listKepalaOpdModal').modal('show');
                    $('#listKepalaOpdModalList').append(`
                        <tr id="row-kepala-opd-${response.data.kode_unik_opd.replace(/\./g, '_')}">
                            <td>
                            ${response.data.nama}
                            <br>
                            ${response.data.nip}
                            <br>
                            ${response.data.pangkat}
                            </td>
                            <td>${response.data.jabatan == 'kepala' ? 'Kepala' : response.data.jabatan == 'direktur' ? 'Direktur' : 'Kepala Unit'}</td>
                            <td>${response.data.tahun}</td>
                            <td id="row-modal-status-kepala-opd-${response.data.nip.replace(/\./g, '_')}">
                                ${response.data.status ? 'Aktif' : 'Tidak Aktif'}
                            </td>
                            <td id="row-modal-action-kepala-opd-${response.data.nip.replace(/\./g, '_')}" class="text-center">
                                <button class="btn btn-sm btn-success btn-status-kepala-opd" value="${response.data.nip}" data-bs-toggle="tooltip" title="${response.data.status ? 'Nonaktifkan' : 'Aktifkan'}">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                let errorResponse = handleAjaxError(xhr);
                showToast(errorResponse.message, errorResponse.alert ? errorResponse.alert : 'danger');
            }
        });
    });

    // reset invalid if input not empty but set invalid if empty
    $('#formKepalaOpd .form-control').on('input', function () {
        if ($(this).val() !== '') {
            $(this).removeClass('is-invalid');
            $(`#${$(this).attr('name')}_error`).text('');
        } else {
            $(this).addClass('is-invalid');
            $(`#${$(this).attr('name')}_error`).text(`Field ${$(this).attr('name')} tidak boleh kosong.`);
        }
    });

});
