<!-- Modal -->
<div class="modal fade" id="arsipRapTerhapusModal" tabindex="-1" aria-labelledby="arsipRapTerhapusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="arsipRapTerhapusModalLabel">Arsip RAP Yang Terhapus</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="table-container" style="max-height: 400px; overflow-y: auto; position: relative;">
                        <table class="table table-striped table-hover">
                            <thead class="table-info align-middle" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th>SUB KEGIATAN</th>
                                    <th>TARGET</th>
                                    <th>PAGU</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @foreach ($opd->raps as $rapDeleted)
                                    @if ($rapDeleted->deleted_at)
                                        <tr>
                                            <td>{{ $rapDeleted->text_subkegiatan }}</td>
                                            <td>{{ formatIdr($rapDeleted->vol_subkeg) . ' ' . $rapDeleted->satuan_subkegiatan }}</td>
                                            <td>{{ formatIdr($rapDeleted->anggaran) }}</td>
                                            <td>
                                                <input class="form-check-input arsip-checked border-2" value="{{ $rapDeleted->id }}" style="width: 20px; height: 20px;" type="checkbox" value="" id="flexCheckDefault">
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <div class="me-3">
                    <form action="/rap/destroy" method="post">
                        @csrf
                        <div class="list-arsip-checked-input"></div>
                        <button class="btn btn-danger"><i class="fa-solid fa-trash"></i> Hapus Permanent</button>
                    </form>
                </div>
                <div class="me-3 d-flex gap-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="/rap/restore" method="post">
                        @csrf
                        <div class="list-arsip-checked-input"></div>
                        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-arrows-rotate"></i> Kembalikan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
