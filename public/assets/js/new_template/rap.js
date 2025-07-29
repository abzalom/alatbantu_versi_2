$(document).ready(function () {

    const formName = [
        'sumberdana', 'vol_subkeg', 'anggaran', 'mulai', 'selesai', 'penerima_manfaat', 'jenis_layanan',
        'jenis_kegiatan', 'ppsb', 'multiyears', 'dana_lain', 'lokus', 'koordinat', 'keterangan', 'catatan',
        'opd_tag_otsus', 'subkegiatan', 'file_kak_name', 'file_rab_name', 'file_pendukung1_name', 'file_pendukung2_name', 'file_pendukung3_name',
        'link_file_dukung_lain'
    ];

    let jenisKegiatan = ''; // Variabel untuk menyimpan jenis kegiatan yang dipilih

    const formToDisabled = [
        '#new-opd_tag_otsus', '#new-subkegiatan', '#new-vol_subkeg', '#new-anggaran', '#new-penerima_manfaat',
        '#new-jenis_layanan', '#new-jenis_kegiatan', '#new-ppsb', '#new-multiyears', '#new-mulai', '#new-selesai',
        '#new-dana_lain', '#new-lokus', '#new-koordinat', '#new-keterangan', '#new-file-kak', '#new-file-rab', '#new-file-pendukung1',
        '#new-file-pendukung2', '#new-file-pendukung3', '#new-link-file-dukung-lain'
    ]


    /**
     * Create New RAP
     */

    $('#addRapOpdModal').on('hide.bs.modal', function () {
        $('#new-subkegiatan').html('<option></option>');
        formToDisabled.forEach((item) => {
            if (item === '#new-opd_tag_otsus') {
                $(item).val(null).trigger('change');
            } else {
                $(item).attr('disabled', true).val(null).trigger('change');
            }
        })

        formName.forEach(function (field) {
            $('#new_' + field + '_error').html('');
            $(`new-#${field}`).removeClass('is-invalid');
        });
    });

    $('#btn-new-rap').on('click', function () {
        setTimeout(() => {
            $('#modal-add_new_rap-show-spinner').hide();
            $('#modal-add_new_rap-show-content').show();
        }, 1000);
    });


    $('#new-opd_tag_otsus').on('change', function () {
        let value = $(this).val();
        if (value) {
            let volume = $(this).find('option:selected').data('volume');
            $('#new-volume-tag').val(volume);
            nomen_sikd.forEach((item) => {
                $('#new-subkegiatan').append(`
                    <option value="${item.id}" data-item='${JSON.stringify(item)}'>${item.text}</option>
                `);
            });
            $('#new-subkegiatan').attr('disabled', false);
            $(this).removeClass('is-invalid');
            $('#new_opd_tag_otsus_error').html('');
        } else {
            $('#new-volume-tag').val('');
            $('#new-subkegiatan').html('<option></option>');
            $('#new-subkegiatan').attr('disabled', true);
        }
    });

    $('#new-subkegiatan').on('change', function () {
        formToDisabled.forEach((disItem) => {
            if (disItem !== '#new-subkegiatan' && disItem !== '#new-opd_tag_otsus') {
                $(disItem).attr('disabled', true).val(null).trigger('change');
            }
        })
        let id = $(this).val();
        if (id) {
            let item = $(this).find('option:selected').data('item');
            formToDisabled.forEach((elmItem) => {
                if (elmItem !== '#new-subkegiatan' && elmItem !== '#new-opd_tag_otsus') {
                    $(elmItem).attr('disabled', false).val(null);
                }
            })
            $('#new-klasifikasi_belanja').val(item.klasifikasi_belanja);
            $('#new-indikator_subkegiatan').val(item.indikator);
            $('#satuan-subkegiatan').html(item.satuan);
            $(this).removeClass('is-invalid');
            $('#new_subkegiatan_error').html('');
            setTimeout(() => {
                $('#new-vol_subkeg').focus();
            }, 100);
        } else {
            $('#new-klasifikasi_belanja').val('');
            $('#new-indikator_subkegiatan').val('');
        }
    });

    $('#form-new-rap').on('submit', function (e) {
        e.preventDefault();
        // let dataForm = $(this).serialize();
        let formElement = $(this)[0];
        let formData = new FormData(formElement);
        formName.forEach((item) => {
            $(`#new_${item}_error`).html('');
            $(`#new-${item}`).removeClass('is-invalid');
        })
        $.ajax({
            type: "POST",
            url: `/api/data/rap/create/${jenisDana}/renja`,
            data: formData,
            contentType: false, // <--- penting
            processData: false, // <--- penting
            dataType: "JSON",
            success: function (response) {
                // $.each(response, function (index, item) {
                //     console.log(`%c${index}`, "color: #3498db; font-weight: bold;", `: ${item}`);
                // });
                $('#addRapOpdModal').modal('hide');
                showToast(response.message, response.alert);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            },
            error: function (xhr) {
                console.log(xhr);
                let dataError = xhr.responseJSON;
                console.log(dataError);
                showToast(dataError.message, dataError.alert);
                formName.forEach((item) => {
                    if (dataError.errors[item]) {
                        $(`#new_${item}_error`).html(dataError.errors[item][0]);
                        $(`#new-${item}`).addClass('is-invalid');
                    }
                })
            }
        });
    });

    /**
     * End Of Create New RAP
     */

    // --------------------------------------------------------------------------------------

    /**
     * Edit RAP
     */

    $('#editRapOpdModal').on('hide.bs.modal', function () {
        // Reset all form
        $('#editRapOpdModal .modal-show-spinner').hide();
        $('#editRapOpdModal .modal-show-content').show();
        $('#edit-show-target-aktifitas-utama').val('');
        $('#edit-show-volume-target-aktifitas-utama').val('');
        $('#edit-satuan-target-aktifitas-utama').val('');
        $('#edit-show-sumberdana-target-aktifitas-utama').val('');
        $('#edit-show-subkegiatan').val('');
        $('#edit-show-indikator').val('');
        $('#edit-vol_subkeg').val('');
        $('#show-satuan-subkegiatan').val('');
        $('#edit-anggaran').val('');
        $('#edit-anggara').val('');
        $('#edit-vol_subkeg').val('');
        $('#edit-penerima-manfaat').val(null).trigger('change');
        $('#edit-lokasi').html('');
        $('#edit-mulai').val('');
        $('#edit-selesai').val('');
        $('#edit-rap-jenis_layanan').val(null).trigger('change');
        $('#edit-rap-jenis_kegiatan').val(null).trigger('change');
        $('#edit-rap-ppsb').val(null).trigger('change');
        $('#edit-rap-multiyears').val(null).trigger('change');
        $('#edit-koordinat').val('');
        $('#edit-dana-lain').html('');
        $('#edit-keterangan').val('');

        formName.forEach(function (field) {
            $('#edit_' + field + '_error').html('');
            $(`[name="${field}"]`).removeClass('is-invalid');
        });

    });

    $('.btn-edit-rap').on('click', function () {
        let data = JSON.parse($(this).val());
        $('#edit-lokasi').html('');

        $('#edit-hidden-id-rap').val(data.id);
        $('#edit-show-target-aktifitas-utama').val(data.tagging.target_aktifitas.kode_target_aktifitas + ' - ' + data.tagging.target_aktifitas.uraian);
        $('#edit-show-volume-target-aktifitas-utama').val(data.tagging.volume);
        $('#edit-satuan-target-aktifitas-utama').html(data.tagging.satuan);
        $('#edit-show-sumberdana-target-aktifitas-utama').val(data.tagging.sumberdana);
        $('#edit-show-subkegiatan').val(data.kode_subkegiatan + ' - ' + data.nama_subkegiatan);
        $('#edit-show-indikator').val(data.indikator_subkegiatan);
        $('#edit-vol_subkeg').val(formatInputAngka(data.vol_subkeg.replace('.', ',')));
        $('#show-satuan-subkegiatan').html(data.satuan_subkegiatan);
        $('#edit-anggaran').val(formatInputAngka(data.anggaran.replace('.', ',')));

        ['#edit-anggaran', '#edit-vol_subkeg'].forEach(function (id) {
            $(id).on('input', function () {
                let angka = $(this).val();
                let formatAngka = formatInputAngka(angka);
                $(this).val(formatAngka);
            });
        });

        $('#edit-penerima-manfaat').val(data.penerima_manfaat);

        let lokus = JSON.parse(data.lokus);
        console.log(lokus);

        dataLokasi.forEach(function (lokasi) {
            if (lokus.some(data => data.id == lokasi.id)) {
                $('#edit-lokasi').append(`<option value="${lokasi.id}" selected>${lokasi.lokasi}</option>`);
            } else {
                $('#edit-lokasi').append(`<option value="${lokasi.id}">${lokasi.lokasi}</option>`);
            }
        });

        $('#edit-mulai').val(data.mulai);
        $('#edit-selesai').val(data.selesai);
        $('#edit-rap-jenis_layanan').val(data.jenis_layanan);
        $('#edit-rap-jenis_kegiatan').val(data.jenis_kegiatan);
        $('#edit-rap-ppsb').val(data.ppsb);
        $('#edit-rap-multiyears').val(data.multiyears);

        let dana_lain = JSON.parse(data.dana_lain);
        let optionsDanaLain = '';

        listDanaLain.forEach(function (dana) {
            optionsDanaLain += `
            <option value="${dana.id}" ${dana_lain.some(item => item.id == dana.id) ? 'selected' : ''}>
                ${dana.uraian}
            </option>
        `;
        });

        $('#edit-koordinat').val(data.koordinat);
        $('#edit-dana-lain').html(optionsDanaLain);
        $('#edit-keterangan').val(data.keterangan);
        $('#edit-catatan').val(data.catatan);

        $('#editRapOpdModal .modal-show-spinner').hide();
        $('#editRapOpdModal .modal-show-content').show();
    });

    $('#form-edit-rap').on('submit', function (e) {
        // /api/data/rap/update
        e.preventDefault();
        $('#editRapOpdModal .modal-show-spinner').show();
        $('#editRapOpdModal .modal-show-content').hide();
        let formData = $(this).serializeArray();
        // filter name from data

        formName.forEach(function (field) {
            $('#edit_' + field + '_error').html('');
            $(`[name="${field}"]`).removeClass('is-invalid');
        });

        $.ajax({
            type: "POST",
            url: "/api/data/rap/update",
            data: formData,
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    let data = response.data;
                    console.log(data);

                    $('#editRapOpdModal .modal-show-spinner').show();
                    $('#editRapOpdModal .modal-show-content').hide();

                    $(`.rap-kinerja-${data.id}`).html(formatIDR(data.vol_subkeg) + ' ' + data.satuan_subkegiatan);
                    $(`.rap-anggaran-${data.id}`).html(formatIDR(data.anggaran));
                    console.log(data.lokus);
                    let lokus = JSON.parse(data.lokus);

                    $(`.rap-lokus-${data.id}`).html('');
                    lokus.forEach(function (lokasi) {
                        $(`.rap-lokus-${data.id}`).append(`<span class="badge bg-secondary">${lokasi.kampung}</span>`);
                    });

                    $('#editRapOpdModal').modal('hide');
                    showToast(response.message, response.alert);

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            },
            error: function (error) {
                let errorFields = error.responseJSON.errors;
                console.log(error);
                // Loop melalui setiap field yang memiliki error
                Object.keys(errorFields).forEach(function (name) {
                    $('#edit_' + name + '_error').html(errorFields[name].join(", "));
                    $(`[name="${name}"]`).addClass('is-invalid');
                });
                $('#editRapOpdModal .modal-show-spinner').hide();
                $('#editRapOpdModal .modal-show-content').show();
                showToast(error.responseJSON.message, error.responseJSON.alert);
            }
        });
    });

    /**
     * End Of Edit RAP
     */


    [{
            name: 'sumberdana',
            event: 'change',
            erroMsg: 'Sumber Dana tidak boleh kosong!'
        },
        {
            name: 'vol_subkeg',
            event: 'keyup',
            erroMsg: 'Kinerja Sub Kegiatan tidak boleh kosong!'
        },
        {
            name: 'anggaran',
            event: 'keyup',
            erroMsg: 'Anggaran tidak boleh kosong!'
        },
        {
            name: 'mulai',
            event: 'change',
            erroMsg: 'Mulai Pelaksanaan tidak boleh kosong!'
        },
        {
            name: 'selesai',
            event: 'change',
            erroMsg: 'Selesai Pelaksanaan tidak boleh kosong!'
        },
        {
            name: 'penerima_manfaat',
            event: 'change',
            erroMsg: 'Penerima manfaat harus dipilih!'
        },
        {
            name: 'jenis_layanan',
            event: 'change',
            erroMsg: 'Jenis Layanan harus dipilih!'
        },
        {
            name: 'jenis_kegiatan',
            event: 'change',
            erroMsg: 'Jenis Kegiatan harus dipilih!'
        },
        {
            name: 'ppsb',
            event: 'change',
            erroMsg: 'PPSB harus dipilih!'
        },
        {
            name: 'multiyears',
            event: 'change',
            erroMsg: 'Multiyears harus dipilih!'
        },
        {
            name: 'dana_lain',
            event: 'change',
            erroMsg: 'Sumber Pendanaan Lainnya harus dipilih!'
        },
        {
            name: 'lokus',
            event: 'change',
            erroMsg: 'Lokasi Fokus harus dipilih!'
        },
        {
            name: 'koordinat',
            event: 'keyup',
            erroMsg: 'Kegiatan fisik wajib memasukan koordinat lokasi kegiatan!'
        },
        // {
        //     name: 'keterangan',
        //     event: 'keyup',
        //     erroMsg: 'Keterangan tidak boleh kosong!'
        // },
    ].forEach((item) => {
        $(`#new-${item.name}`).on(item.event, function () {
            if (!$(this).is(':disabled')) {
                let value = $(this).val();

                // Jika field 'jenis_kegiatan' berubah, update variabel 'jenisKegiatan'
                if (item.name === 'jenis_kegiatan') {
                    jenisKegiatan = value;

                    // Jika berubah dari "fisik" ke nonfisik, hilangkan error koordinat
                    if (jenisKegiatan !== 'fisik') {
                        $('#new-koordinat').removeClass('is-invalid');
                        $('#new_koordinat_error').html('');
                    }
                }

                // Khusus untuk koordinat, wajib diisi hanya jika jenis_kegiatan = "fisik"
                if (item.name === 'koordinat') {
                    if (jenisKegiatan !== 'fisik') {
                        $(this).removeClass('is-invalid');
                        $(`#new_${item.name}_error`).html('');
                        return; // Skip validasi jika bukan "fisik"
                    }
                }

                if (value) {
                    $(this).removeClass('is-invalid');
                    $(`#new_${item.name}_error`).html('');
                } else {
                    $(this).addClass('is-invalid');
                    $(`#new_${item.name}_error`).html(item.erroMsg);
                }
            }
        });
    });


    /**
     * Delete RAP
     */

    $('.btn-delete-rap').on('click', function () {
        let konfirmasi = confirm('Anda yakin ingin menghapus sub kegiatan ini?')
        if (konfirmasi) {
            let id = $(this).val();
            $.ajax({
                type: "POST",
                url: appApiUrl + "/api/data/rap/delete",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function (response) {
                    if (response.success) {
                        if (response.message || response.alert) {
                            showToast(response.message, response.alert || 'danger');
                        }
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function (xhr) {
                    let errorResponse = handleAjaxError(xhr);
                    showToast(errorResponse.message, 'danger');
                }
            });
        }
    });

    $('.arsip-checked').on('change', function () {
        let id = $(this).val();
        let inputSelector = `.input-checked-${id}`;

        if ($(this).is(':checked')) {
            if (!$(inputSelector).length) {
                $('.list-arsip-checked-input').append(`<input class="input-checked-${id}" name="id[]" value="${id}" type="hidden">`);
            }
        } else {
            $(inputSelector).remove();
        }
    });


    /**
     * 
     * Detail RAP
     * 
     */

    $('#detailRapOpdModal').on('hide.bs.modal', function () {
        $('#detailRapOpdModalLabel').text('');
        $('#modal-detail-rap-target_aktifitas').text('');
        $('#modal-detail-rap-volume_target').text('');
        $('#modal-detail-rap-klasifikasi_belanja').text('');
        $('#modal-detail-rap-subkegiatan').text('');
        $('#modal-detail-rap-indikator_subkegiatan').text('');
        $('#modal-detail-rap-kinerja_subkegiatan').text('');
        $('#modal-detail-rap-anggaran').text('');
        $('#modal-detail-rap-penerima_manfaat').text('');
        $('#modal-detail-rap-jenis_layanan').text('');
        $('#modal-detail-rap-jenis_kegiatan').text('');
        $('#modal-detail-rap-ppbs').text('');
        $('#modal-detail-rap-multiyears').text('');
        $('#modal-detail-rap-waktu_pelaksanaan').text('');
        $('#modal-detail-rap-dana_lain').text('');
        $('#modal-detail-rap-lokus').text('');
        $('#modal-detail-rap-koordinat').text('');
        $('#modal-detail-rap-keterangan').text('');
        $('#modal-detail-rap-data_dukung').html('');
        $('#modal-detail-rap-status').text('');
        $('#modal-detail-rap-catatan').text('');
    });


    $('.btn-detail-rap').on('click', function () {
        const data = $(this).data('rap');
        const opd = data.opd;
        const rap = data.rap;
        console.log(data);
        console.log(opd);
        console.log(rap);
        if (!data) {
            setTimeout(() => {
                $('#detailRapOpdModal').modal('hide');
            }, 800);
            setTimeout(() => {
                showToast('Data RAP tidak ditemukan', 'danger');
            }, 815);
            return;
        }

        $('#detailRapOpdModalLabel').text(opd);

        $('#modal-detail-rap-target_aktifitas').text(rap.tagging.target_aktifitas.kode_target_aktifitas + ' - ' + rap.tagging.target_aktifitas.uraian);
        $('#modal-detail-rap-volume_target').text(rap.tagging.volume + ' ' + rap.tagging.satuan);
        $('#modal-detail-rap-klasifikasi_belanja').text(rap.klasifikasi_belanja);
        $('#modal-detail-rap-subkegiatan').text(rap.text_subkegiatan);
        $('#modal-detail-rap-indikator_subkegiatan').text(rap.indikator_subkegiatan);
        $('#modal-detail-rap-kinerja_subkegiatan').text(rap.vol_subkeg + ' ' + rap.satuan_subkegiatan);
        $('#modal-detail-rap-anggaran').text('Rp. ' + formatIDR(rap.anggaran));
        $('#modal-detail-rap-penerima_manfaat').text(rap.penerima_manfaat == 'oap' ? 'Khusus Orang Asli Papua' : 'Umum (OAP dan Non OAP)');
        $('#modal-detail-rap-jenis_layanan').text(rap.jenis_layanan == 'terkait' ? 'Terkait langsung ke masyarakat' : 'Kegiatan Pendukung');
        $('#modal-detail-rap-jenis_kegiatan').text(rap.jenis_kegiatan == 'fisik' ? 'Fisik' : 'Non Fisik');
        $('#modal-detail-rap-ppbs').text(rap.ppsb == 'ya' ? 'Ya' : 'Tidak');
        $('#modal-detail-rap-multiyears').text(rap.multiyears == 'ya' ? 'Ya' : 'Tidak');
        $('#modal-detail-rap-waktu_pelaksanaan').text(rap.mulai + ' s.d. ' + rap.selesai);
        $('#modal-detail-rap-dana_lain').text(rap.dana_lain ? JSON.parse(rap.dana_lain).map(item => item.uraian).join(', ') : 'Tidak ada dana lain');
        $('#modal-detail-rap-lokus').text(rap.lokus ? JSON.parse(rap.lokus).map(item => item.kampung).join(', ') : 'Tidak ada lokasi fokus');
        $('#modal-detail-rap-koordinat').text(rap.koordinat ? rap.koordinat : 'Tidak ada koordinat');
        $('#modal-detail-rap-keterangan').text(rap.keterangan ? rap.keterangan : 'Tidak ada keterangan');
        $('#modal-detail-rap-data_dukung').html(`
            <ul>
                <li>
                    <a href="/rap/view/file?path=${encodeURIComponent(rap.file_path)}&name=${rap.file_kak_name}" target="_blank">Kerangka Acuan Kerja (KAK) <i class="fa-solid fa-up-right-from-square"></a></i>
                </li>
                <li>
                    <a href="/rap/view/file?path=${encodeURIComponent(rap.file_path)}&name=${rap.file_rab_name}" target="_blank">Rencana Anggaran Biaya (RAB) <i class="fa-solid fa-up-right-from-square"></a></i>
                </li>
                ${rap.file_pendukung1_name ?
                    `<li>
                        <a href="/rap/view/file?path=${encodeURIComponent(rap.file_path)}&name=${rap.file_pendukung1_name}" target="_blank">Data Pendukung Tambahan <i class="fa-solid fa-up-right-from-square"></a></i>
                    </li>` 
                : ''}
                ${rap.file_pendukung2_name ?
                    `<li>
                        <a href="/rap/view/file?path=${encodeURIComponent(rap.file_path)}&name=${rap.file_pendukung2_name}" target="_blank">Data Pendukung Tambahan <i class="fa-solid fa-up-right-from-square"></a></i>
                    </li>`
                : ''}
                ${rap.file_pendukung3_name ?
                    `<li>
                        <a href="/rap/view/file?path=${encodeURIComponent(rap.file_path)}&name=${rap.file_pendukung3_name}" target="_blank">Data Pendukung Tambahan <i class="fa-solid fa-up-right-from-square"></a></i>
                    </li>`
                : ''}
                ${rap.link_file_dukung_lain ?
                    `<li>
                        <a href="${rap.link_file_dukung_lain}" target="_blank">Link Drive Tambahan File Pendukung Lainnya <i class="fa-solid fa-up-right-from-square"></a></i>
                    </li>`
                : ''}
            </ul>
        `);
        let pembahasan = 'Belum dibahas & Belum divalidasi';

        if (rap.pemabahasan) {
            pembahasan = rap.pemabahasan == 'setujui' ? 'Disetujui' : (rap.pembahasan == 'perbaikan' ? 'Perbaikan' : 'Tidak disetujui');
            pembahasan += rap.validasi ? ' & Telah divalidasi' : ' & Belum divalidasi';
        }

        $('#modal-detail-rap-status').text(pembahasan);
        $('#modal-detail-rap-catatan').text(rap.catatan);

        setTimeout(() => {
            $('#modal-detail-rap-show-spinner').hide();
            $('#modal-detail-rap-show-content').show();
        }, 800);
    });

});
