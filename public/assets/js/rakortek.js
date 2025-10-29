$(document).ready(function () {
    $("#newTargetIndikatorUrusanModal").on("hide.bs.modal", function () {
        setTimeout(() => {
            $("#modal-target-indikator-show-spinner").show();
            $("#modal-target-indikator-show-content").hide();
            $("#hidden_id_indikator").val(null);
            $("#hidden_id_target_indikator").val(null);
            $("#show-indikator-urusan").val(null);
            $("#show-satuan-indikator-urusan").val(null);
            $("#show-target-nasional-indikator-urusan").val(null);
            $("#show-target-daerah-indikator-urusan").val(null);
            $("#show-target-daerah-indikator-urusan").attr("disabled", false);
        }, 500);
    });

    $(".edit-target-urusan").on("click", function () {
        // Ambil data (pakai .attr('value') jika value berformat JSON statis di HTML)
        let data;
        try {
            data = JSON.parse($(this).val()); // atau: JSON.parse($(this).attr('value'))
        } catch (e) {
            console.error("JSON parse gagal", e);
            return;
        }

        // Isi field
        $("#hidden_id_indikator").val(data.id);
        $("#hidden_id_target_indikator").val(data.target ? data.target.id : "");
        $("#show-indikator-urusan").val(data.nama_indikator);
        $("#show-satuan-indikator-urusan").val(data.satuan);
        $("#show-target-nasional-indikator-urusan").val(data.target ? formatAngka(data.target.target_nasional) : "");
        $("#show-target-daerah-indikator-urusan").val(data.target ? formatAngka(data.target.usulan_target_daerah) : "");

        // Tentukan disabled tanpa return awal
        const el = $("#show-target-daerah-indikator-urusan");
        const t = data.target;
        let disabled = false;

        if (t) {
            if (t.validasi === 1 || t.validasi === true) {
                disabled = true;
            } else {
                const p = t.pembahasan;
                // disable jika pembahasan ADA & bukan "perbaikan"
                disabled = !(p === null || p === "" || p === "perbaikan");
            }
        }
        el.prop("disabled", disabled);

        // Tampilkan modal / konten (tetap berjalan meski t tidak ada)
        // $('#modal-target-indikator').modal('show'); // panggil ini jika belum dipanggil di tempat lain
        $("#modal-target-indikator-show-spinner").show();
        $("#modal-target-indikator-show-content").hide();

        setTimeout(() => {
            $("#modal-target-indikator-show-spinner").hide();
            $("#modal-target-indikator-show-content").show();
            el.trigger("focus");
        }, 1000); // gak perlu 1000ms; 300ms cukup untuk efek
    });
});
