<!-- Modal -->
<div class="modal fade" id="deteleProgramRapppModal" tabindex="-1" aria-labelledby="deteleProgramRapppModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h1 class="modal-title fs-5" id="deteleProgramRapppModalLabel">Konfirmasi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-3">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-5x text-danger mb-3"></i>
                    <h5 class="mb-2">Anda yakin ingin menghapus target program ini?</h5>
                    <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <table class="table table-sm">
                    <tr>
                        <td>Target Aktifitas Utama</td>
                        <td>:</td>
                        <td id="delete-rappp-target_aktifitas">:</td>
                    </tr>
                    <tr>
                        <td>Target Kinerja</td>
                        <td>:</td>
                        <td id="delete-rappp-target_kinerja">:</td>
                    </tr>
                    <tr>
                        <td>Sumber Pendanaa</td>
                        <td>:</td>
                        <td id="delete-rappp-sumberdana">:</td>
                    </tr>
                </table>
                <div class="d-flex justify-content-center">
                    <form class="delete-rappp-progam" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="delete-rappp-id">
                        <button type="submit" class="btn btn-danger mt-3">Ya, Hapus</button>
                    </form>
                    <button type="button" class="btn btn-secondary mt-3 ms-2" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
