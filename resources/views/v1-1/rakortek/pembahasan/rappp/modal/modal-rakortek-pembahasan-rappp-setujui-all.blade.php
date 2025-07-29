<!-- Modal -->
<div class="modal fade" id="bahasRakortekRapppAllModal" tabindex="-1" aria-labelledby="bahasRakortekRapppAllModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="bahasRakortekRapppAllModalLabel">Bahas Semua</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/pembahasan/rakortek/rappp/opd/pembahasan/setujui/all" method="post">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="opd_id" value="{{ $opd->id }}">
                    <div id="bahas-all-opd_tag_otsus_id">
                    </div>
                    <div class="mb-3">
                        <label for="bahas-all-status-pembahasan" class="form-label">Status Pembahasan</label>
                        <select name="pembahasan" class="form-control" id="bahas-all-status-pembahasan" name="status_pembahasan">
                            <option value="">Pilih...</option>
                            <option value="setujui">Setujui</option>
                            <option value="perbaikan">Perbaikan</option>
                            <option value="tolak">Tolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bahas-all-catatan" class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" id="bahas-all-catatan" placeholder="Catatan untuk semua Target Aktifitas RAPPP" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
