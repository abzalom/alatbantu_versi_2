<!-- Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-schedule-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-schedule-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="scheduleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="schedule-form" action="/config/schedule/rap/new" method="post">
                    <div class="modal-body">
                        <div id="schedule-edit-element"></div>
                        @csrf
                        <input type="hidden" name="created_by" id="schedule-user" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="tahun" id="schedule-tahun" value="{{ session('tahun') }}">
                        <div class="mb-3">
                            <label for="schedule-tahapan" class="form-label">Tahapan</label>
                            <select name="tahapan" class="form-control" id="schedule-tahapan" readonly disabled>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="schedule-keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" rows="4" class="form-control" id="schedule-keterangan" placeholder="Keterangan" readonly disabled></textarea>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="schedule-mulai" class="form-label">Mulai</label>
                                    <input name="mulai" type="datetime-local" class="form-control" id="schedule-mulai" placeholder="Mulai" readonly disabled>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="schedule-selesai" class="form-label">Selesai</label>
                                    <input name="selesai" type="datetime-local" class="form-control" id="schedule-selesai" placeholder="Selesai" readonly disabled>
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
