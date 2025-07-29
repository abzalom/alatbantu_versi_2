<style>
    .table-detail-rap {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .table-detail-rap td {
        padding: 0.25rem 0.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .table-detail-rap tr:last-child td {
        border: none;
    }
</style>
<!-- Modal -->
<div class="modal fade" id="detailRapOpdModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detailRapOpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-detail-rap-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>


            <div id="modal-detail-rap-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="detailRapOpdModalLabel">Detail RAP</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row p-2">
                        <div class="table-responsive">
                            <table class="table-sm table-hover table-detail-rap">
                                <tr>
                                    <td style="width: 25%">Target Aktifitas Utama</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-target_aktifitas"></td>
                                </tr>
                                <tr>
                                    <td>Volume Target</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-volume_target"></td>
                                </tr>
                                <tr>
                                    <td>Klasifikasi Belanja</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-klasifikasi_belanja"></td>
                                </tr>
                                <tr>
                                    <td>Sub Kegiatan</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-subkegiatan"></td>
                                </tr>
                                <tr>
                                    <td>Indikator</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-indikator_subkegiatan"></td>
                                </tr>
                                <tr>
                                    <td>Kinerja Sub Kegiatan</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-kinerja_subkegiatan"></td>
                                </tr>
                                <tr>
                                    <td>Anggaran</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-anggaran"></td>
                                </tr>
                                <tr>
                                    <td>Penerima Manfaat</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-penerima_manfaat"></td>
                                </tr>
                                <tr>
                                    <td>Jenis Layanan</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-jenis_layanan"></td>
                                </tr>
                                <tr>
                                    <td>Jenis Kegiatan</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-jenis_kegiatan"></td>
                                </tr>
                                <tr>
                                    <td>Program Bersama Provinsi Papua</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-ppbs"></td>
                                </tr>
                                <tr>
                                    <td style="width: 25%">Program Multiyears</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-multiyears"></td>
                                </tr>
                                <tr>
                                    <td>Waktu Pelaksanaan</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-waktu_pelaksanaan"></td>
                                </tr>
                                <tr>
                                    <td>Sumber Pendanaan Lainnya</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-dana_lain"></td>
                                </tr>
                                <tr>
                                    <td>Lokasi Fokus Pelaksanaan</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-lokus"></td>
                                </tr>
                                <tr>
                                    <td>Koordinat Lokasi</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-koordinat"></td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-keterangan"></td>
                                </tr>
                                <tr>
                                    <td>Data Pendukung</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-data_dukung"></td>
                                </tr>
                                <tr>
                                    <td>Status Pembahasan</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-status"></td>
                                </tr>
                                <tr>
                                    <td>Catatan Pembahasan</td>
                                    <td>:</td>
                                    <td id="modal-detail-rap-catatan"></td>
                                </tr>
                            </table>
                            {{-- <div class="col-sm-12 col-md-12 col-lg-6" id="table_col1">
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
