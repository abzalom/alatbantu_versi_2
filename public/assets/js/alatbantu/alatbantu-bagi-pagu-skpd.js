$(document).ready(function () {




    $('#alatBantuAddPaguModal').on('hide.bs.modal', function () {
        $('#modal-add-pagu-nama-skpd').html('');
    });

    $('#add-sumberdana').on('click', function () {
        let formItemDana = $('#add-sumberdana-form-control').find('.input-dana-item');
        let countFormItemDana = formItemDana.length + 1;
        console.log(countFormItemDana);

        // Update status `selected` pada listDana sesuai item yang sudah dipilih
        formItemDana.find('.select-item-dana').each(function () {
            let selectedValue = $(this).find('option').val();
            listDana.forEach(item => {
                if (item.value === selectedValue) {
                    item.selected = true;
                }
            });
        });

        // Buat option HTML untuk dropdown dengan status `selected` diperbarui
        let selectDana = listDana.map(item => {
            let siDisabled = item.selected ? "disabled" : "";
            return `<option value="${item.value}" ${siDisabled}>${item.name}</option>`;
        }).join('');

        // Append elemen form baru ke DOM
        $('#add-sumberdana-form-control').append(`
            <div class="row input-dana-item">
                <hr />
                <div class="col-sm-6 mb-3">
                    <label class="form-label">Sumber Dana</label>
                    <select class="form-control select2-add select-item-dana" data-placeholder="Pilih...">
                        <option></option>
                        ${selectDana}
                    </select>
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="alokasi" class="form-label">Alokasi</label>
                    <div class="input-group">
                        <input type="text" class="form-control add-alokasi-dana-item" placeholder="Alokasi" aria-label="Alokasi" aria-describedby="button-addon2">
                        <button class="btn btn-outline-danger remove-input-dana-item" type="button" id="button-addon2"><i class="fa-regular fa-circle-xmark"></i></button>
                    </div>
                </div>
            </div>
        `);

        // Inisialisasi ulang select2 untuk item yang baru ditambahkan
        $('.select2-add').select2({
            theme: "bootstrap-5",
            width: '100%',
            placeholder: "Pilih...",
            dropdownParent: $('#add-sumberdana-form-control')
        });
    });

    $(document).on('click', '.remove-input-dana-item', function () {
        $(this).parent().closest('.input-dana-item').remove();
    });

    $(document).on('change', '.select-item-dana', function () {
        let dana = $(this).val();
        $(this).closest('.input-dana-item').find('.add-alokasi-dana-item').attr('name', dana);
    });

    $('#first-select-item').on('change', function () {
        let value = $(this).val();
        let formItemDana = $('#add-sumberdana-form-control').find('.input-dana-item');
        let countFormItemDana = formItemDana.length;
        console.log(countFormItemDana);

        if (countFormItemDana === 1) {
            if (value) {
                $('#add-sumberdana').attr('hidden', false);
            } else {
                $('#add-sumberdana').attr('hidden', true);
            }
        }
    });

    $('.btn-add-pagu-skpd').on('click', function () {
        let id_skpd = $(this).val();
        $.ajax({
            type: "POST",
            url: appApiUrl + "/api/data/opd",
            data: {
                id: id_skpd
            },
            dataType: "JSON",
            success: function (response) {
                let opd = response.data[0];
                $('#modal-add-pagu-nama-skpd').html(opd.nama_opd);
            }
        });
    });

});
