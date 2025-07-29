<x-app-layout-component :title="$app['title'] ?? null">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-lg">
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
                            <button id="btn-create-schedule" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal"><i class="fa-solid fa-square-plus"></i> Jadwal Baru</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark align-middle">
                                <tr>
                                    <th>#</th>
                                    <th>Tahapan</th>
                                    <th>Keterangan</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Penginputan</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($jadwals as $jadwal)
                                    @php
                                        $textMuted = '';
                                        if (!$jadwal->status) {
                                            $textMuted = 'text-muted';
                                        }
                                    @endphp
                                    <tr @if (!$jadwal->status) class="table-danger" @endif>
                                        <td class="{{ $textMuted }}">{{ $no++ }}</td>
                                        <td class="{{ $textMuted }}">{{ $jadwal->tahapan }}</td>
                                        <td class="{{ $textMuted }}">
                                            {{ $jadwal->keterangan }}
                                        </td>
                                        <td class="{{ $textMuted }}">
                                            {{ $jadwal->mulai }}
                                        </td>
                                        <td class="{{ $textMuted }}">
                                            {{ $jadwal->selesai }}
                                        </td>
                                        <form method="post" action="/config/schedule/rap/input_user">
                                            @csrf
                                            @if ($jadwal->status)
                                                <td class="text-nowrap">
                                                    @if ($jadwal->penginputan)
                                                        <input type="hidden" name="id" value="{{ $jadwal->id }}">
                                                        <input type="hidden" name="penginputan" value="false">
                                                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-placement="Top" data-bs-title="Penginputan RAP Aktif">Aktif</span> | <button class="btn btn-sm btn-danger"><i class="fa-solid fa-circle-xmark"></i></button>
                                                    @else
                                                        <input type="hidden" name="id" value="{{ $jadwal->id }}">
                                                        <input type="hidden" name="penginputan" value="true">
                                                        <span class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="Top" data-bs-title="Penginputan RAP dikunci. Terkecuali admin">Tidak Aktif</span> | <button class="btn btn-sm btn-primary"><i class="fa-solid fa-circle-check"></i></button>
                                                    @endif
                                                </td>
                                            @else
                                                <td class="text-center">
                                                    <i class="fa-solid fa-circle-xmark text-danger" style="font-size: 1.8rem"></i>
                                                </td>
                                            @endif
                                        </form>
                                        @if (!$jadwal->deleted_at)
                                            <td>
                                                @if ($jadwal->status)
                                                    <span class="badge bg-success">Aktif</span>
                                                    <br>
                                                    @php
                                                        $start = now();
                                                        $end = date_create($jadwal->selesai);
                                                        $diff = date_diff($start, $end);
                                                    @endphp
                                                    @if (now()->diffInDays($jadwal->selesai) <= 0)
                                                        <span class="badge bg-danger">Selesai</span>
                                                    @else
                                                        Sedang berlangsung
                                                        <br>
                                                        <div class="count-down-time"></div>
                                                    @endif
                                                    <br>
                                                @else
                                                    <span class="badge bg-danger">Dikunci</span>
                                                @endif
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <i class="fa-solid fa-circle-xmark text-danger" style="font-size: 1.8rem"></i>
                                            </td>
                                        @endif
                                        <td>
                                            @if ($jadwal->status)
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-primary btn-edit-schedule" value="{{ $jadwal->id }}" data-bs-toggle="modal" data-bs-target="#scheduleModal"><i class="fa-solid fa-pen-to-square"></i></button>
                                                    <form action="/config/schedule/rap/lock" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $jadwal->id }}">
                                                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-lock"></i></button>
                                                    </form>
                                                </div>
                                            @else
                                                Jadwal Telah Berakhir
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('app.pengaturan.schedule.schedule-modals.schedule-modal')
    @include('app.pengaturan.schedule.pengaturan-schedule-script')

</x-app-layout-component>
