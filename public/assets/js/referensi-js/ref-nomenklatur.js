$(document).ready(function () {
    $('#btn-upload-nomenklatur-sipd-xlsx').on('click', function () {
        $('#upload-nomenklatur-sipd-xlsx').click();
    });

    $('#upload-nomenklatur-sipd-xlsx').on('change', function () {
        var fileName = $(this).val().split('\\').pop();
        if (fileName) {
            if (confirm('Apakah Anda yakin ingin mengunggah file "' + fileName + '" untuk memperbarui nomenklatur SIPD?')) {
                $(this).closest('form').submit();
            }
        }
    });

});
