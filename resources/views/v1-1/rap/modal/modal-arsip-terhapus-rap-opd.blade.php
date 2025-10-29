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
                                @foreach ($opd->tag_otsus as $tagOtsusDelete)
                                    @foreach ($tagOtsusDelete->raps as $rapDeleted)
                                        @if ($rapDeleted->deleted_at)
                                            <tr>
                                                <td>
                                                    {{ $rapDeleted->text_subkegiatan }}
                                                    <br>
                                                    <small class="text-muted">
                                                        (dibuat : {{ \Carbon\Carbon::parse($rapDeleted->created_at)->diffForHumans() }})
                                                        <br>
                                                        (dihapus : {{ \Carbon\Carbon::parse($rapDeleted->deleted_at)->diffForHumans() }})
                                                    </small>
                                                </td>
                                                <td>{{ formatIdr($rapDeleted->vol_subkeg) . ' ' . $rapDeleted->satuan_subkegiatan }}</td>
                                                <td>{{ formatIdr($rapDeleted->anggaran) }}</td>
                                                <td>
                                                    @if (input_rap() || auth()->user()->hasRole('admin'))
                                                        <input class="form-check-input arsip-checked border-2" value="{{ $rapDeleted->id }}" style="width: 20px; height: 20px;" type="checkbox" value="" id="flexCheckDefault">
                                                    @else
                                                        <i class="fa-solid fa-lock fa-xl" data-bs-toggle="tooltip" data-bs-placement="top" title="Jadwal penginputan terkunci atau tidak aktif"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <div class="me-3">
                    @if (input_rap() || auth()->user()->hasRole('admin'))
                        <form action="/rap/destroy" method="post">
                            @csrf
                            <div class="list-arsip-checked-input"></div>
                            <button class="btn btn-danger"><i class="fa-solid fa-trash"></i> Hapus Permanent</button>
                        </form>
                    @endif
                </div>
                <div class="me-3 d-flex gap-3">
                    @if (input_rap() || auth()->user()->hasRole('admin'))
                        <form action="/rap/restore" method="post">
                            @csrf
                            <div class="list-arsip-checked-input"></div>
                            <button type="submit" class="btn btn-warning"><i class="fa-solid fa-arrows-rotate"></i> Kembalikan</button>
                        </form>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
