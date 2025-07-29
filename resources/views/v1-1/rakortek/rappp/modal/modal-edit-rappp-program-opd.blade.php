<!-- Modal -->
<div class="modal fade" id="editProgramRapppModal" tabindex="-1" aria-labelledby="editProgramRapppModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-edit-rappp-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-edit-rappp-show-content" style="display: none">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editProgramRapppModalLabel">Edit Target Kinerja Program RAPPP</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="edit-rappp-id" name="id_opd_tag_otsus">
                        <table class="table table-sm table-borderless">
                            <tr class="border-bottom">
                                <td>Perangkat Daerah</td>
                                <td>:</td>
                                <td>{{ $opd->text }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <td>Tema</td>
                                <td>:</td>
                                <td><span id="edit-rappp-tema"></span></td>
                            </tr>
                            <tr class="border-bottom">
                                <td>Program</td>
                                <td>:</td>
                                <td><span id="edit-rappp-program"></span></td>
                            </tr>
                            <tr class="border-bottom">
                                <td>Target Aktivitas</td>
                                <td>:</td>
                                <td><span id="edit-rappp-target_aktifitas"></span></td>
                            </tr>
                            <tr class="border-bottom">
                                <td>Satuan</td>
                                <td>:</td>
                                <td><span id="edit-rappp-satuan"></span></td>
                            </tr>
                            <tr class="border-bottom">
                                <td>Volume</td>
                                <td>:</td>
                                <td><span id="edit-rappp-volume"></span></td>
                            </tr>
                            <tr class="border-bottom">
                                <td>Sumber Pendanaan</td>
                                <td>:</td>
                                <td><span id="edit-rappp-sumberdana"></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
