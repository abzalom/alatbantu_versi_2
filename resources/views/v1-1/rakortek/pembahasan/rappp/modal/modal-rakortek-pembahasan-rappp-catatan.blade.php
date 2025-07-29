<!-- Modal -->
<div class="modal fade" id="lihatCatatanRakorRapppModal" tabindex="-1" aria-labelledby="lihatCatatanRakorRapppModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="lihatCatatanRakorRapppModalLabel">Hasil Pembahasan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless table-hover">
                        <tr>
                            <td style="width: 30%">SKPD</td>
                            <td>:</td>
                            <td>{{ $opd->nama_opd }}</td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Target Aktifitas Utama</td>
                            <td>:</td>
                            <td id="catatan-target_aktifitas-show"></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Satuan</td>
                            <td>:</td>
                            <td id="catatan-satuan-show"></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Volume</td>
                            <td>:</td>
                            <td id="catatan-volume-show"></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Sumber Dana</td>
                            <td>:</td>
                            <td id="catatan-sumberdana-show"></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Status Pembahasan</td>
                            <td>:</td>
                            <td id="catatan-status_pembahasan-show"></td>
                        </tr>
                        <tr>
                            <td style="width: 30%">Catatan</td>
                            <td>:</td>
                            <td id="catatan-catatan-show"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
