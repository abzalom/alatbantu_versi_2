<!-- Modal -->
<div class="modal fade" id="arsipProgramRapppModal" tabindex="-1" aria-labelledby="arsipProgramRapppModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="arsipProgramRapppModalLabel">Arsip Target Aktifitas Utama yang dihapus!</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped" style="font-size: 90%">
                        <thead class="table-warning">
                            <tr>
                                <th>#</th>
                                <th>Target Aktivitas</th>
                                <th>Volume</th>
                                <th>Sumber Dana</th>
                                <th>Tanggal Hapus</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rappps as $detItemRappp)
                                @if ($detItemRappp->deleted_at)
                                    <tr>
                                        <td>{{ $detItemRappp->id }}</td>
                                        <td>{{ $detItemRappp->target_aktifitas }}</td>
                                        <td>{{ $detItemRappp->volume }}</td>
                                        <td>{{ $detItemRappp->sumberdana }}</td>
                                        <td>{{ $detItemRappp->deleted_at }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Pulihkan data">
                                                    <form action="/rakortek/rappp/restore" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $detItemRappp->id }}">
                                                        <button type="submit" class="btn btn-sm btn-primary btn-restore-rappp" value="{{ $detItemRappp->id }}"><i class="fa-solid fa-rotate-left"></i></button>
                                                    </form>
                                                </div>
                                                <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Hapus permanen">
                                                    <form action="/rakortek/rappp/destroy" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $detItemRappp->id }}">
                                                        <button type="submit" class="btn btn-sm btn-danger btn-delete-rappp" value="{{ $detItemRappp->id }}"><i class="fa-solid fa-circle-xmark"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
