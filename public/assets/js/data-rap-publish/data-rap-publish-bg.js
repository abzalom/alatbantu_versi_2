$(document).ready(function () {

    function showImage(src) {
        return `
        <div class="card card-img-container">
            <img src="${src}" class="card-img-top" alt="Image Name">
            <div class="card-body p-1">
                <div class="mb-3 p-0 m-0">
                    <label for="editKeteranganDokumentasi" class="form-label text-muted fw-bold">Keterangan</label>
                    <textarea type="text" class="form-control" id="editKeteranganDokumentasi" placeholder="Keterangan Dokumentasi" rows="4"></textarea>
                </div>
            </div>
        </div>
        `;
    }

    $('.btn-show-detail').on('click', function () {
        const icon = $(this).find('i'); // Cari elemen <i> di dalam tombol

        if (icon.css('transform') === 'matrix(0, -1, 1, 0, 0, 0)') {
            $(this).removeClass('btn-warning').addClass('btn-secondary'); // Ubah warna tombol
            // Kembalikan rotasi ke posisi awal
            icon.css({
                transform: 'rotate(0deg)',
                transition: 'transform 0.3s ease-in-out'
            });
        } else {
            $(this).removeClass('btn-secondary').addClass('btn-warning'); // Ubah warna tombol
            // Tambahkan rotasi 270 derajat
            icon.css({
                transform: 'rotate(-90deg)',
                transition: 'transform 0.3s ease-in-out'
            });
        }
    });

    $('#editRapModal').on('hide.bs.modal', function () {
        $('#editSubkegiatan').val('');
        $('#editIndikator').val('');
        $('#editKinerja').val('');
        $('.edit-satuan').html('');
        $('#editAnggaran').val(formatIDR(0));
    });

    $('.btn-edit-rap').on('click', function () {
        const data = JSON.parse($(this).val());
        console.log(data);
        $('#editSubkegiatan').val(data.text_subkegiatan);
        $('#editIndikator').val(data.indikator_keluaran);
        $('#editKinerja').val(data.target_keluaran + ' ' + data.satuan);
        $('.edit-satuan').html(data.satuan);
        $('#editAnggaran').val(formatIDR(data.pagu_alokasi));
    });

    $('#input_img').on('change', function () {
        const files = $(this)[0].files;
        console.log(files);
        let newDataImg = [];
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function (e) {
                const src = e.target.result; // Ambil URL data gambar
                newDataImg.push({
                    index: i,
                    src: src,
                });

                // const newElement = showImage(src); // Buat elemen gambar baru
                // $('#uploadContainer').before(newElement); // Sisipkan sebelum #uploadContainer
            };

            reader.readAsDataURL(file); // Konversi file ke Data URL
        }

        console.log(newDataImg);

    });
});
