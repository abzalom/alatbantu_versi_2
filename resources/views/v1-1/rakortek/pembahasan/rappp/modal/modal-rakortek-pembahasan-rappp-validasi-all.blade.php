<!-- Modal -->
<div class="modal fade" id="validasiRakortekRapppAllModal" tabindex="-1" aria-labelledby="validasiRakortekRapppAllModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="validasiRakortekRapppAllModalLabel">Validasi Semua</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/pembahasan/rakortek/rappp/opd/validasi/all" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="opd_id" value="{{ $opd->id }}">
                    <div id="validasi-all-opd_tag_otsus_id">
                    </div>
                    <h4>Anda akan melakukan validasi terhadap semua usulan indikator RAPPP</h4>
                    <p class="text-muted">Perhatian :</p>
                    <ul class="text-muted">
                        <li>Pastikan semua data sudah benar sebelum melakukan validasi.</li>
                        <li>Jika ada yang salah, silakan lakukan perbaikan terlebih dahulu.</li>
                        <li>Jika sudah yakin, silakan klik tombol "Validasi Semua".</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">validasi Semua</button>
                </div>
            </form>
        </div>
    </div>
</div>
