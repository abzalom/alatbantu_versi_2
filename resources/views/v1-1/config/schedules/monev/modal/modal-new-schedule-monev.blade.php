<!-- Modal -->
<div class="modal fade" id="ScheduleMonevModal" tabindex="-1" aria-labelledby="ScheduleMonevModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">


            <div class="modal-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>


            <div class="modal-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ScheduleMonevModalLabel">Buat Jadwal Monev Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/config/schedule/monev/new" method="post">
                    <div class="modal-body">
                        @csrf
                        <div id="new-item-input">
                        </div>
                        <div id="edit-item-input">
                            <input type="hidden" name="id" value="">
                        </div>
                        <div class="mb-3">
                            <label for="add-schedule-monev-nama" class="form-label">Nama Jadwal</label>
                            <input name="nama" type="text" class="form-control" id="add-schedule-monev-nama" placeholder="Nama Jadwal">
                        </div>
                        <div class="mb-3">
                            <label for="add-schedule-monev-keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" id="add-schedule-monev-keterangan" rows="3" placeholder="Keterangan"></textarea>
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
