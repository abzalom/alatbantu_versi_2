$(document).ready(function () {

    function getRapppData(type, url, data) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: type,
                url: url,
                data: data,
                dataType: "JSON",
                success: function (response) {
                    try {
                        resolve(response);
                    } catch (error) {
                        reject(error);
                    }
                },
                error: function (error) {
                    reject(error);
                }
            });
        });
    }

    $('#newProgramRapppModal').on('hide.bs.modal', function () {
        $('#select-tema-rappp').html('<option></option>');

        $('#select-program-rappp').attr('disabled', true);
        $('#select-program-rappp').html('<option></option>');

        $('#select-target_aktifitas-rappp').attr('disabled', true);
        $('#select-target_aktifitas-rappp').html('<option></option>');

        $('#input-volume_target_aktifitas-rappp').attr('disabled', true);
        $('#input-volume_target_aktifitas-rappp').val('');

        $('#satuan_target_aktifitas-addon').html('Satuan');
        $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').attr('disabled', true);
        $('#input-volume_target_aktifitas-rappp-satuan-not-exists').attr('disabled', true);
        $('#input-volume_target_aktifitas-rappp-satuan-not-exists').val('');
        $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').val('');
        $('#satuan-exists').hide();
        $('#satuan-not-exists').hide();
        $('#select-sumberdana-rappp').val(null);
    });

    $('#new-rappp-program').on('click', function () {
        $('#select-tema-rappp').html('<option></option>');
        temaRappp.forEach(item => {
            $('#select-tema-rappp').append(`
                <option value="${item.kode_tema}">${item.kode_tema + '. ' + item.uraian}</option>
            `);
        });
    });

    $('#select-tema-rappp').on('change', async function () {
        let kode_tema = $(this).val();
        $('#select-program-rappp').attr('disabled', true);
        $('#select-target_aktifitas-rappp').attr('disabled', true);
        $('#select-program-rappp').html('<option></option>');
        $('#select-target_aktifitas-rappp').html('<option></option>');
        $('#input-volume_target_aktifitas-rappp').attr('disabled', true);
        $('#input-volume_target_aktifitas-rappp').val('');
        $('#satuan_target_aktifitas-addon').html('Satuan');
        $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').attr('disabled', true);
        $('#input-volume_target_aktifitas-rappp-satuan-not-exists').attr('disabled', true);
        $('#input-volume_target_aktifitas-rappp-satuan-not-exists').val('');
        $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').val('');
        $('#satuan-exists').hide();
        $('#satuan-not-exists').hide();
        $('#select-sumberdana-rappp').attr('disabled', true);
        $('#select-sumberdana-rappp').val(null);
        if (!kode_tema) {
            $('#select-program-rappp').attr('disabled', true);
        } else {
            let response = await getRapppData('POST', '/api/data/otsus/indikator/program', {
                kode_tema: kode_tema
            });
            if (response.success) {
                let data = response.data;
                data.forEach(item => {
                    $('#select-program-rappp').append(`
                                <option value="${item.kode_program}">${item.kode_program + '. ' + item.uraian}</option>
                            `);
                })
                $('#select-program-rappp').attr('disabled', false);
            }
        }
    });

    $('#select-program-rappp').on('change', async function () {
        let kode_program = $(this).val();
        $('#select-target_aktifitas-rappp').attr('disabled', true);
        $('#select-target_aktifitas-rappp').html('<option></option>');
        $('#input-volume_target_aktifitas-rappp').attr('disabled', true);
        $('#input-volume_target_aktifitas-rappp').val('');
        $('#satuan_target_aktifitas-addon').html('Satuan');
        $('#input-volume_target_aktifitas-rappp').attr('disabled', true);
        $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').attr('disabled', true);
        $('#input-volume_target_aktifitas-rappp-satuan-not-exists').attr('disabled', true);
        $('#input-volume_target_aktifitas-rappp-satuan-not-exists').val('');
        $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').val('');
        $('#satuan-exists').hide();
        $('#satuan-not-exists').hide();
        $('#select-sumberdana-rappp').attr('disabled', true);
        $('#select-sumberdana-rappp').val(null);
        if (!kode_program) {
            $('#select-target_aktifitas-rappp').attr('disabled', true);
        } else {
            let response = await getRapppData('POST', '/api/data/otsus/indikator/target_aktifitas_utama', {
                kode_program: kode_program
            });
            if (response.success) {
                let data = response.data;
                data.forEach(item => {
                    $('#select-target_aktifitas-rappp').append(`
                        <option value="${item.kode_target_aktifitas}" data-satuan="${item.satuan}">${item.kode_target_aktifitas + '. ' + item.uraian}</option>
                    `);
                })
                $('#select-target_aktifitas-rappp').attr('disabled', false);
            }
        }
    })

    $('#select-target_aktifitas-rappp').on('change', function () {
        let value = $(this).val();
        $('#input-volume_target_aktifitas-rappp').attr('disabled', true);
        $('#satuan_target_aktifitas-addon').html('');
        $('#input-volume_target_aktifitas-rappp-satuan-not-exists').val('');
        $('#input-volume_target_aktifitas-rappp-satuan-not-exists').attr('disabled', true);
        $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').val('');
        $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').attr('disabled', true);

        $('#satuan-exists').hide();
        $('#satuan-not-exists').hide();

        $('#select-sumberdana-rappp').attr('disabled', true);
        $('#select-sumberdana-rappp').val(null);

        let satuan = $(this).find(':selected').data('satuan');
        console.log(value);
        console.log(satuan);
        if (value) {
            if (satuan) {
                $('#exists_check').val('yes');
                $('#input-volume_target_aktifitas-rappp').attr('disabled', false);
                $('#satuan_target_aktifitas-addon').html(satuan);
                $('#select-sumberdana-rappp').attr('disabled', false);
                $('#select-sumberdana-rappp').val(null);
                $('#satuan-exists').show();
            } else {
                $('#exists_check').val('no');
                $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').attr('disabled', false);
                $('#input-volume_target_aktifitas-rappp-satuan-not-exists').attr('disabled', false);
                $('#select-sumberdana-rappp').attr('disabled', false);
                $('#select-sumberdana-rappp').val(null);
                $('#satuan-not-exists').show();
            }
        } else {
            $('#input-volume_target_aktifitas-rappp').attr('disabled', true);
            $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').attr('disabled', true);
            $('#input-volume_target_aktifitas-rappp-satuan-not-exists').attr('disabled', true);
            $('#input-volume_target_aktifitas-rappp').val('');
            $('#input-volume_target_aktifitas-rappp-satuan-not-exists').val('');
            $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').val('');
            $('#satuan_target_aktifitas-addon').html('Satuan');
            $('#select-sumberdana-rappp').attr('disabled', true);
            $('#select-sumberdana-rappp').val(null);
        }
    });

    $('#formNewProgramRappp').on('submit', function (e) {
        e.preventDefault();

        let exists_check = $('#exists_check').val();
        let sumberdana = $('#select-sumberdana-rappp').val()

        // Reset error messages
        $('#target_aktifitas_error, #volume_target_aktifitas_error, #volume_target_aktifitas_satuan_not_exists_error, #satuan_traget_aktifitas_satuan_not_exists_error, #sumberdana_error').html('');

        if (exists_check && exists_check == 'yes') {
            let target_aktifitas = $('#select-target_aktifitas-rappp').val();
            let volume_target_aktifitas = $('#input-volume_target_aktifitas-rappp').val();

            // Validasi input
            if (!target_aktifitas) {
                $('#target_aktifitas_error').html('Target Aktifitas harus dipilih!');
            }
            if (!volume_target_aktifitas || volume_target_aktifitas == 0) {
                $('#volume_target_aktifitas_error').html('Volume harus diisi dan tidak boleh bernilai 0!');
            }

            if (!sumberdana) {
                $('#sumberdana_error').html('sumberdana tidak boleh kosong')
            }

            // Jika ada error, tampilkan toast dan hentikan submit
            if (!target_aktifitas || !volume_target_aktifitas || volume_target_aktifitas == 0 || !sumberdana) {
                showToast('Terjadi kesalahan!', 'danger');
                return;
            }
        } else {
            let target_aktifitas = $('#select-target_aktifitas-rappp').val();
            let volume_target_aktifitas = $('#input-volume_target_aktifitas-rappp-satuan-not-exists').val();
            let satuan_target_aktifitas = $('#input-satuan_traget_aktifitas-rappp-satuan-not-exists').val();

            // Validasi input
            if (!target_aktifitas) {
                $('#target_aktifitas_error').html('Target Aktifitas harus dipilih!');
            }
            if (!volume_target_aktifitas || volume_target_aktifitas == 0) {
                $('#volume_target_aktifitas_satuan_not_exists_error').html('Volume harus diisi dan tidak boleh bernilai 0!');
            }
            if (!satuan_target_aktifitas) {
                $('#satuan_traget_aktifitas_satuan_not_exists_error').html('Satuan harus diisi!');
            }

            if (!sumberdana) {
                $('#sumberdana_error').html('sumberdana tidak boleh kosong')
            }

            // Jika ada error, tampilkan toast dan hentikan submit
            if (!target_aktifitas || !volume_target_aktifitas || volume_target_aktifitas == 0 || !satuan_target_aktifitas || !sumberdana) {
                showToast('Terjadi kesalahan!', 'danger');
                return;
            }
        }

        // Jika semua valid, lanjutkan submit
        this.submit();
    });

    /**
     * 
     * 
     * Edit Rapp Program dimulai dari sini
     * 
     * 
     */

    $('#editProgramRapppModal').on('hide.bs.modal', function () {
        $('#edit-rappp-tema').html('');
        $('#edit-rappp-program').html('');
        $('#edit-rappp-target_aktifitas').html('');
        $('#edit-rappp-satuan').html('');
        $('#edit-rappp-volume').html('');
        $('#edit-rappp-sumberdana').html('');
        $('#modal-edit-rappp-show-spinner').show();
        $('#modal-edit-rappp-show-content').hide();
    })

    $('.btn-edit-rapp').on('click', function () {
        const id_opd_tag_otsus = $(this).val();
        const data = $(this).data('rappp');
        console.log(data);

        // Validasi awal data
        if (!id_opd_tag_otsus || !data) {
            showToast('Data tidak valid atau kosong.', 'danger');
            return;
        }

        $.ajax({
            type: "POST",
            url: "/api/data/otsus/indikator/target_aktifitas_utama",
            data: {
                kode_target_aktifitas: data.kode_target_aktifitas,
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (!response.success || !response.data) {
                    $('#editProgramRapppModal').modal('hide');
                    setTimeout(() => {
                        showToast('Data tidak ditemukan!', 'danger');
                    }, 300);
                    return;
                }

                const target_aktifitas = response.data[0];

                // Update modal content
                $('#edit-rappp-id').val(data.id);
                $('#edit-rappp-tema').html(data.tema || '-');
                $('#edit-rappp-program').html(data.program || '-');
                $('#edit-rappp-target_aktifitas').html(data.target_aktifitas || '-');
                $('#edit-rappp-satuan').html(target_aktifitas.satuan || `<input type="text" name="satuan" class="form-control border-danger" id="edit-rappp-satuan-input" value="${data.satuan || ''}">`);
                $('#edit-rappp-volume').html(`<input type="text" name="volume" class="form-control border-danger" id="edit-rappp-volume-input" value="${data.volume || ''}">`);
                $('#edit-rappp-sumberdana').html(`
                    <select name="sumberdana" class="form-control border-danger" id="edit-rappp-sumberdana-select">
                        <option value="">Pilih Sumber Dana</option>
                        <option value="bg" ${data.alias_dana === 'bg' ? 'selected' : ''}>OTSUS 1%</option>
                        <option value="sg" ${data.alias_dana === 'sg' ? 'selected' : ''}>OTSUS 1,25%</option>
                        <option value="dti" ${data.alias_dana === 'dti' ? 'selected' : ''}>DTI</option>
                    </select>
                `);
                setTimeout(() => {
                    $('#modal-edit-rappp-show-spinner').hide();
                    $('#modal-edit-rappp-show-content').show();
                }, 1000);
            },
            error: function (xhr, status, error) {
                $('#editProgramRapppModal').modal('hide');
                showToast('Gagal mengambil data dari server.', 'danger');
                $('#modal-edit-rappp-show-spinner').show();
                $('#modal-edit-rappp-show-content').hide();
                console.error(error);
            }
        });
    });


    /**
     * * Edit Rapp Program berakhir di sini
     */

    $('.btn-delete-rappp').on('click', function () {
        const data = $(this).data('rappp');
        console.log(data);
        $('#delete-rappp-id').val(data.id);
        $('#delete-rappp-target_aktifitas').html(data.target_aktifitas);
        $('#delete-rappp-target_kinerja').html(data.volume + ' ' + data.satuan);
        $('#delete-rappp-sumberdana').html(data.sumberdana);
        $('#deteleProgramRapppModal').modal('show');
    });

});
