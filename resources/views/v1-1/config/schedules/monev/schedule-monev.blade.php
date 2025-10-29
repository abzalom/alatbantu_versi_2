<x-app-layout-component :title="$app['title'] ?? null">

    <script>
        let dataJadwalMonev = @json($data);
    </script>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                @isset($app['desc'])
                    {{ $app['desc'] }}
                @else
                    Deskripsi Halaman
                @endisset
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm mb-2">
                    <button id="btn-create-schedule" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ScheduleMonevModal"><i class="fa-solid fa-square-plus"></i> Monev</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark align-middle">
                        <tr>
                            <th>#</th>
                            <th>Tahapan</th>
                            <th>Nama</th>
                            <th>Keterangan</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($data as $itemJadwal)
                            <tr @if (!$itemJadwal->status) class="table-danger" @endif>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $itemJadwal->tahapan }}</td>
                                <td>{{ $itemJadwal->nama }}</td>
                                <td>{{ $itemJadwal->keterangan }}</td>
                                <td>{{ $itemJadwal->created_by ? $itemJadwal->created_by->username : '-' }}</td>
                                <td>{{ $itemJadwal->updated_by ? $itemJadwal->updated_by->username : '-' }}</td>
                                <td>
                                    @if ($itemJadwal->status)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($itemJadwal->status)
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning btn-edit-schedule" value="{{ $itemJadwal }}" data-bs-toggle="modal" data-bs-target="#ScheduleMonevModal" data-id="{{ $itemJadwal->id }}" data-nama="{{ $itemJadwal->nama }}" data-keterangan="{{ $itemJadwal->keterangan }}" data-status="{{ $itemJadwal->status }}"><i class="fa-solid fa-pencil"></i></button>
                                            <form action="/config/schedule/monev/lock" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $itemJadwal->id }}">
                                                <button type="submit" onclick="return confirm('Yakin ingin mengunci jadwal ini?')" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deactivateScheduleMonevModal" data-id="{{ $itemJadwal->id }}" data-nama="{{ $itemJadwal->nama }}"><i class="fa-solid fa-lock"></i></button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="btn-group" role="group">
                                            <form action="/config/schedule/monev/activate" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $itemJadwal->id }}">
                                                <div data-bs-toggle="tooltip" data-bs-placement="Top" data-bs-title="Aktifkan Jadwal">
                                                    <button type="submit" class="btn btn-sm btn-secondary"><i class="fa-solid fa-check"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('v1-1.config.schedules.monev.modal.modal-new-schedule-monev')

    @include('v1-1.config.schedules.monev.script-schedule-monev')
</x-app-layout-component>
