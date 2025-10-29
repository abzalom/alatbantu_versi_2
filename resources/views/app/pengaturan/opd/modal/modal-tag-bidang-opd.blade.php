<!-- Modal -->
<div class="modal fade" id="tagBidalOpdModal" tabindex="-1" aria-labelledby="tagBidalOpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="modal-tagBidangOpd-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-tagBidangOpd-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tagBidalOpdModalLabel">Tagging Bidang Perangat Daerah</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/config/opd/tag_bidang" method="post">

                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="opd_id" name="opd_id" value="">
                        <div class="mb-3">
                            <label for="nama_opd" class="form-label">Nama Perangkat Daerah</label>
                            <textarea class="form-control" id="nama_opd" placeholder="Nama Perangkat Daerah" rows="3" disabled></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="bidang" class="form-label">Bidang </label>
                            <select name="bidangs[]" class="form-control bidang-select" id="bidang" data-placeholder="Pilih..." data-max="5" multiple>
                                @foreach ($bidangs as $bid)
                                    <option id="optBid_{{ $bid->id }}" value="{{ $bid->id }}">{{ $bid->text }}</option>
                                @endforeach
                            </select>
                            <span id="bidang_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
