$(document).ready(function () {

    async function ajaxRequest(method, url, data) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: method,
                url: appApiUrl + url,
                data: data,
                dataType: "JSON",
                success: function (response) {
                    try {
                        resolve(response);
                    } catch (error) {
                        console.error(error);
                        reject(error)
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    reject(xhr);
                }
            });
        })
    }

    $('#addHeaderSinkronDjpkSikdModal, #editHeaderSinkronDjpkSikdModal').on('hidden.bs.modal', function () {
        $(this)
            .find("input:not([name='_token']), textarea, select")
            .val('')
            .end()
            .find("input[type=checkbox]:not([name='_token']), input[type=radio]:not([name='_token'])")
            .prop("checked", false)
            .end();
    });

    $('.btn-edit-request-header').on('click', function () {
        let id = $(this).data('id');
        let jenis = $(this).data('jenis');
        let name = $(this).data('name');
        let method = $(this).data('method');
        let param_key = $(this).data('param_key');
        let param_value = $(this).data('param_value');
        let tahun = $(this).data('tahun');

        let attributes = [{
                key: 'id',
                value: $(this).data('id')
            },
            {
                key: 'name',
                value: $(this).data('name')
            },
            {
                key: 'sumberdana',
                value: $(this).data('sumberdana')
            },
            {
                key: 'jenis',
                value: $(this).data('jenis')
            },
            {
                key: 'method',
                value: $(this).data('method')
            },
            {
                key: 'url',
                value: $(this).data('url')
            },
            {
                key: 'param_key',
                value: $(this).data('param_key')
            },
            {
                key: 'param_value',
                value: $(this).data('param_value')
            }
        ];

        attributes.forEach(itemAttr => {
            $(`#edit-header-request-${itemAttr.key}`).val(itemAttr.value);
        })

    });

    async function appendNomenklatur(data) {
        $('#table-header-show-data-sinkron').html(`
            <tr>
                <th>id</th>
                <th>Sumber Dana</th>
                <th>bidang_urusan</th>
                <th>program</th>
                <th>kegiatan</th>
                <th>text</th>
                <th>indikator</th>
                <th>satuan</th>
                <th>klasifikasi_belanja</th>
            </tr>
        `);

        let totalData = data.length;
        let no = 0;

        setTimeout(() => {
            for (const item of data) {
                no++;
                $('#table-body-show-data-sinkron').append(`
                    <tr>
                        <td>${item.id_subkegiatan}</td>
                        <td>${item.sumberdana}</td>
                        <td class="text-wrap" style="width: 2px !important;">
                            ${item.kode_bidang + ' ' + item.nama_bidang}
                        </td>
                        <td class="text-wrap" style="width: 2px !important;">
                            ${item.kode_program + ' ' + item.nama_program}
                        </td>
                        <td class="text-wrap" style="width: 2px !important;">
                            ${item.kode_kegiatan + ' ' + item.nama_kegiatan}
                        </td>
                        <td class="text-wrap" style="width: 2px !important;">
                            ${item.text}
                        </td>
                        <td class="text-wrap" style="width: 2px !important;">
                            ${item.indikator}
                        </td>
                        <td>
                            ${item.satuan}
                        </td>
                        <td>
                            ${item.klasifikasi_belanja}
                        </td>
                    </tr>
                `);

                if (no == totalData) {
                    $('#spinner-tampil-data-show').hide();
                    $('#table-show-data-sinkron').show();
                }
            }
        }, 1000);
        // Sembunyikan spinner dan tampilkan tabel setelah data selesai dimuat
    }

    function appendRap(data) {
        $('#table-header-show-data-sinkron').html(`
            <tr>
                <th>id</th>
                <th>Klasifikasi Belanja</th>
                <th>Sub Kegiatan</th>
                <th>Indikator</th>
                <th>Target</th>
                <th>Anggaran</th>
                <th>Lokasi</th>
            </tr>
        `);
        let totalData = data.length;
        let no = 0;

        setTimeout(() => {
            for (const item of data) {
                no++;
                console.log(typeof item.lokus);

                $('#table-body-show-data-sinkron').append(`
                    <tr>
                        <td>${item.id_rap}</td>
                        <td>${item.klasifikasi_belanja}</td>
                        <td class="text-wrap" style="width: 2px !important;">
                            ${item.text_subkegiatan}
                        </td>
                        <td class="text-wrap">
                            ${item.indikator_keluaran}
                        </td>
                        <td class="text-wrap">
                            ${item.target_keluaran + ' ' + item.satuan}
                        </td>
                        <td class="text-wrap">
                            ${item.pagu_alokasi}
                        </td>
                        <td>
                            ${ item.lokus.map((item) => item).join(', ') }
                        </td>
                    </tr>
                `);

                if (no == totalData) {
                    $('#spinner-tampil-data-show').hide();
                    $('#table-show-data-sinkron').show();
                }
            }
        }, 1000);
    }

    $('#request-select-data').on('change', function (params) {
        let value = $(this).val();
        if (value) {
            $(this).removeClass('is-invalid');
        }
    });

    function chunkArray(data, size) {
        const chunks = [];
        for (let i = 0; i < data.length; i += size) {
            chunks.push(data.slice(i, i + size));
        }
        return chunks;
    }

    $('#btn-get-data-sikd').on('click', async function () {
        $('#spinner-progres-data-show').hide();
        $('#spinner-progres-title').html('');
        let req = $('#request-select-data');
        req.removeClass('is-invalid fa-shake');
        $('#request_data_error').html('');
        let req_id = req.find(':selected').val();
        let req_jenis = req.find(':selected').data('jenis');
        let req_sumberdana = req.find(':selected').data('sumberdana');
        if (req_id === "" || req_jenis === "" || req_sumberdana === "") {
            req.addClass('is-invalid fa-shake');
            $('#request_data_error').html('URL belum di pilih...');
            setTimeout(() => {
                req.removeClass('fa-shake');
            }, 150);
        } else {
            $(this).attr('disabled', true);
            $('#spinner-progres-data-show').show();
            $('#spinner-progres-title').html('download data...');
            let res = await ajaxRequest('POST', '/api/data/sinkron/djpk/sikd', {
                id: req_id
            });
            if (res.success) {
                let data = res.data;
                $('#spinner-progres-data-show').hide();
                $('#spinner-progres-title').html('');
                showToast('koneksi berhasil', 'success');
                $('#info-data-request').show();
                $('#info-data-request h5').html('data berhasil diambil. jumlah data ' + data.length);
                $(this).attr('disabled', false);
                console.log(data);
                $('#btn-sinkron-data-to-db').on('click', async function () {
                    $('#table-show-data-sinkron').hide();
                    $('#table-header-show-data-sinkron').html('');
                    $('#table-body-show-data-sinkron').html('');
                    $('#spinner-tampil-data-show').show();
                    let sinkronData = await ajaxRequest('POST', '/api/data/sinkron/djpk/sikd/create', {
                        sumberdana: req_sumberdana,
                        jenis: req_jenis,
                        data: JSON.stringify(data),
                    })
                    if (sinkronData.success) {
                        console.log(sinkronData);
                        console.log(req_jenis);

                        if (req_jenis === 'nomenklatur') {
                            appendNomenklatur(sinkronData.data.data);
                        }

                        if (req_jenis === 'rap') {
                            appendRap(sinkronData.data.data);
                        }
                    } else {
                        showToast('Data gagal tersimpan!', 'danger');
                    }
                });
            }
        }

    });

});
