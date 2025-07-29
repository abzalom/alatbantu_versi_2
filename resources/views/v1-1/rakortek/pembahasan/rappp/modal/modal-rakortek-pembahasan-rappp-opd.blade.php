<!-- Modal -->
<div class="modal fade" id="bahasRakorRapppModal" tabindex="-1" aria-labelledby="bahasRakorRapppModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-bahas-rakortek-rappp-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>


            <div id="modal-bahas-rakortek-rappp-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bahasRakorRapppModalLabel">Pembahasan Kinerja RAPPP</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="opd_tag_otsus_id" id="bahas-opd_tag_otsus_id">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless table-hover">
                                <tr>
                                    <td style="width: 30%">Target Aktifitas Utama</td>
                                    <td>:</td>
                                    <td id="bahas-target_aktifitas-show"></td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">Satuan</td>
                                    <td>:</td>
                                    <td id="bahas-satuan-show"></td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">Volume</td>
                                    <td>:</td>
                                    <td id="bahas-volume-show"></td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">Sumber Dana</td>
                                    <td>:</td>
                                    <td id="bahas-sumberdana-show"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="text-end d-flex justify-content-end align-content-end">
                                        <div data-bs-toggle="tooltip" data-bs-placement="top" title="bahas lagi" id="ubah-pembahasan-rappp" style="display: none">
                                            <i class="fa-solid fa-pen-to-square text-primary m-2" id="click-ubah-pembahasan-rappp" style="font-size: 17px"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">Status Pembahasan</td>
                                    <td>:</td>
                                    <td id="bahas-status_pembahasan-show">
                                        <select name="pembahasan" class="form-control" id="bahas-pembahasan-select" data-placeholder="Pilih..." disabled>
                                            <option value="">Pilih...</option>
                                            <option value="setujui">Setujui</option>
                                            <option value="perbaikan">Perbaikan</option>
                                            <option value="tolak">Tolak</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">Catatan</td>
                                    <td>:</td>
                                    <td>
                                        <textarea name="catatan" class="form-control" id="bahas-catatan-textarea" rows="3" placeholder="Masukkan catatan..." disabled></textarea>
                                    </td>
                            </table>
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
</div>
