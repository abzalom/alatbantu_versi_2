$(document).ready(function () {

    function resultSearch(value) {
        // Mengubah array value menjadi string dengan separator baris baru
        return value.map(item => item.split(',')).join('|');
    }

    $('#lokasi-search').on('change', function () {
        let text = $(this).val(); // Mendapatkan nilai yang dipilih dari select
        let result = resultSearch(text); // Mengolah data
        if (result.length > 0) {
            console.log(result);
            $('#lokasi-result-search').val(result); // Menampilkan di dalam textarea
        }
    });

    $('#lokasi-copy-result').on('click', function () {
        let lokasi = $('#lokasi-result-search').val();
        navigator.clipboard.writeText(lokasi);
    });

    $('#sumberdana-search').on('change', function () {
        let text = $(this).val(); // Mendapatkan nilai yang dipilih dari select
        let result = resultSearch(text); // Mengolah data
        if (result.length > 0) {
            console.log(result);
            $('#sumberdana-result-search').val(result); // Menampilkan di dalam textarea
        }
    });

    $('#sumberdana-copy-result').on('click', function () {
        let sumberdana = $('#sumberdana-result-search').val();
        navigator.clipboard.writeText(sumberdana);
    });


});
