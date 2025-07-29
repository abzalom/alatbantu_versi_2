<!-- Modal -->
<div class="modal fade" id="newTargetIndikatorUrusanModal" tabindex="-1" aria-labelledby="newTargetIndikatorUrusanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-target-indikator-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-target-indikator-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newTargetIndikatorUrusanModalLabel">Tambahkan Indikator</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-indikator-target-daerah" action="/rakortek/urusan/{{ $opd->id }}/save/target_daerah" method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id_target_indikator" id="hidden_id_target_indikator">
                        <input type="hidden" name="id_indikator" id="hidden_id_indikator">
                        <div class="mb-3">
                            <label for="show-indikator-urusan" class="form-label">Indikator Bidang Urusan</label>
                            <input type="text" class="form-control" id="show-indikator-urusan" placeholder="Indikator Bidang Urusan" disabled readonly>
                        </div>
                        <div class="mb-3">
                            <label for="show-satuan-indikator-urusan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="show-satuan-indikator-urusan" placeholder="Satuan" disabled readonly>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="show-target-nasional-indikator-urusan" class="form-label">Target Nasional</label>
                                    <input type="text" class="form-control format-angka" id="show-target-nasional-indikator-urusan" placeholder="Target Nasional" @if (auth()->user()->hasRole('user')) disabled readonly @else name="target_nasional" @endif>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="show-target-daerah-indikator-urusan" class="form-label">Target Daerah</label>
                                    <input name="target_daerah" type="text" class="form-control format-angka" id="show-target-daerah-indikator-urusan" placeholder="Target Daerah">
                                </div>
                            </div>
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
