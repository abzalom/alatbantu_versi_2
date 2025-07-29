$(document).ready(function () {

    function formatPagu(angka) {
        if (angka === null || angka === undefined) {
            return '';
        }
        let part = angka.toString().split('.');
        part[0] = part[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        part[1] = part[1] ? ',' + part[1] : '';
        let result = part.join('');
        return result;
    }

    $('#pengaturanPaguSkpdModal').on('hide.bs.modal', function (event) {
        $('#pengaturanPaguSkpdModalLabel').text('');
        $('#id_opd').val('');
        $('#sumberdana').val(null).trigger('change');
        $('#pagu').val('');
        $('#modal-pengaturan-pagu-skpd-show-spinner').show();
        $('#modal-pengaturan-pagu-skpd-show-content').hide();
    });

    $('.btn-pagu-skpd').on('click', function () {
        const data = $(this).data('opd');
        $('#pengaturanPaguSkpdModalLabel').text(data.kode_opd + ' - ' + data.nama_opd);
        $('#id_opd').val(data.id);
        $('#bg').val(data.pagu ? formatPagu(data.pagu.bg) : '');
        $('#sg').val(data.pagu ? formatPagu(data.pagu.sg) : '');
        $('#dti').val(data.pagu ? formatPagu(data.pagu.dti) : '');
        $('#keterangan').val(data.keterangan ? data.pagu.keterangan : '');
        setTimeout(() => {
            $('#modal-pengaturan-pagu-skpd-show-spinner').hide();
            $('#modal-pengaturan-pagu-skpd-show-content').show();
        }, 1000);
    });
});
