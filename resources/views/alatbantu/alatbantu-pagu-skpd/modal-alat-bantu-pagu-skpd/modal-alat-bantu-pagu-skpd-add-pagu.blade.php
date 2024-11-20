<!-- Modal -->
<div class="modal fade" id="alatBantuAddPaguModal" tabindex="-1" aria-labelledby="alatBantuAddPaguModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="alatBantuAddPaguModalLabel">Add Pagu SKPD</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="modal-add-pagu-nama-skpd"></h4>
                <hr>
                <div class="mb-3">
                    <label for="modal-add-pagu-uraian" class="form-label">Uraian</label>
                    <textarea class="form-control" id="modal-add-pagu-uraian" rows="3"></textarea>
                </div>

                <div id="add-sumberdana-form-control">
                    <div class="row input-dana-item">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Sumber Dana</label>
                            <select id="first-select-item" class="form-control select2 select-item-dana" data-placeholder="Pilih...">
                                <option></option>
                                @foreach ($sumberdanas as $itemDana)
                                    <option value="{{ $itemDana['value'] }}">{{ $itemDana['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="alokasi" class="form-label">Alokasi</label>
                            <input type="text" class="form-control add-alokasi-dana-item" placeholder="Aloksi">
                        </div>
                    </div>
                </div>
                <button id="add-sumberdana" class="btn btn-sm btn-primary" hidden><i class="fa-solid fa-square-plus"></i></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
