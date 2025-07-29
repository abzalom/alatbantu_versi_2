<!-- Modal -->
<div class="modal fade" id="bahasRakortekUrusanModal" tabindex="-1" aria-labelledby="bahasRakortekUrusanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-bahas-rakortek-urusan-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-bahas-rakortek-urusan-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bahasRakortekUrusanModalLabel">Pembahasan Kinerja Urusan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="target_id" id="target-indikator-urusan-id" />
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tr class="align-middle">
                                    <td style="width: 30%">Satuan</td>
                                    <td>:</td>
                                    <td id="bahas-satuan">
                                        <input type="text" class="form-control" id="input-satuan" disabled />
                                    </td>
                                </tr>
                                <tr class="align-middle">
                                    <td style="width: 30%">Target Nasional</td>
                                    <td>:</td>
                                    <td id="bahas-target-nasional">
                                        <input type="text" class="form-control format-angka" id="input-target-nasional" disabled />
                                    </td>
                                </tr>
                                <tr class="align-middle">
                                    <td style="width: 30%">Usulan Target Daerah</td>
                                    <td>:</td>
                                    <td id="bahas-usulan-target-daerah">
                                        <input type="text" class="form-control format-angka" id="input-usulan-target-daerah" disabled />
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="text-end d-flex justify-content-end align-content-end">
                                        <div data-bs-toggle="tooltip" data-bs-placement="top" title="ubah target" id="show-ubah-target-daerah" style="display: none">
                                            <i class="fa-solid fa-pen-to-square text-primary m-2" id="click-ubah-target-daerah" style="font-size: 17px"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="align-middle">
                                    <td style="width: 30%">Target Daerah <small>(yang disepakati)</small></td>
                                    <td style="width: 5%">:</td>
                                    <td style="width: 65%">
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="text" name="target_daerah" class="form-control format-angka" id="input-target-daerah" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">Status Pembahasan</td>
                                    <td>:</td>
                                    <td id="bahas-status-pembahasan">
                                        <select name="pembahasan" class="form-select" id="select-bahas-status">
                                            <option value="">Pilih...</option>
                                            <option value="setujui">Disetujui</option>
                                            <option value="perbaikan">Perbaikan</option>
                                            <option value="tolak">Ditolak</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">Catatan Pembahasan</td>
                                    <td>:</td>
                                    <td id="bahas-catatan">
                                        <textarea name="catatan" class="form-control" id="input-bahas-catatan" placeholder="Catatan pembahasan..." rows="3"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
