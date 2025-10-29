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

                    <div class="text-muted">
                        <strong>Jadwal terdiri dari beberapa tahapan yang harus dilalui yaitu</strong>
                        <ul>
                            <li>Rapat Koordinasi Teknis (Rakortek)</li>
                            <li>Rancangan Awal (Ranwal) RAP</li>
                            <li>Rancangan RAP</li>
                            <li>Finalisasi RAP</li>
                            <li>Perubahan RAP</li>
                        </ul>
                    </div>

                    <div class="row justify-content-between">
                        <div class="col-sm mb-2">
                            <button id="btn-create-schedule" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal"><i class="fa-solid fa-square-plus"></i> Jadwal Baru</button>
                        </div>
                        @if ($aktif)
                            <div class="col-sm mb-2 text-end">
                                @if ($aktif->status)
                                    @if ($aktif->selesai > now())
                                        <form method="post" action="/config/schedule/rap/input_user">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $aktif->id }}">
                                            <input type="hidden" name="penginputan" value="{{ $aktif->penginputan ? 'false' : 'true' }}">
                                            <button class="btn btn-sm {{ $aktif->penginputan ? 'btn-danger' : 'btn-secondary' }}" data-bs-toggle="tooltip" data-bs-title="{{ $aktif->penginputan ? 'Nonaktifkan Penginputan' : 'Aktifkan Penginputan' }}">
                                                <i class="fa-solid {{ $aktif->penginputan ? 'fa-lock' : 'fa-lock-open' }}"></i> Penginputan
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" style="font-size: 90%">
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
                                        <td class="{{ $textMuted }} text-nowrap">
                                            {{ Carbon\Carbon::parse($jadwal->mulai)->translatedFormat('d F Y') }}
                                            <br>
                                            {{ Carbon\Carbon::parse($jadwal->mulai)->translatedFormat('\P\u\k\u\l H:i:s A') }}
                                        </td>
                                        <td class="{{ $textMuted }} text-nowrap">
                                            {{ Carbon\Carbon::parse($jadwal->selesai)->translatedFormat('d F Y') }}
                                            <br>
                                            {{ Carbon\Carbon::parse($jadwal->selesai)->translatedFormat('\P\u\k\u\l H:i:s A') }}
                                        </td>
                                        <td class="text-nowrap text-center">
                                            @if ($jadwal->status)
                                                @if ($jadwal->selesai > now())
                                                    @if ($jadwal->penginputan)
                                                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-placement="Top" data-bs-title="Penginputan RAP Aktif">Aktif</span>
                                                    @else
                                                        <span class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="Top" data-bs-title="Penginputan RAP dikunci. Terkecuali admin">Tidak Aktif</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="Top" data-bs-title="Penginputan RAP dikunci. Terkecuali admin">Tidak Aktif</span>
                                                @endif
                                            @else
                                                <i class="fa-solid fa-circle-xmark text-danger" style="font-size: 1.8rem"></i>
                                            @endif
                                        </td>
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
                                                    <span class="badge text-bg-danger">Selesai</span>
                                                @else
                                                    <span class="badge text-bg-info">Sedang berlangsung</span>
                                                    {{-- <br>
                                                    <div class="count-down-time"></div> --}}
                                                @endif
                                                <br>
                                            @else
                                                <span class="badge bg-danger">Dikunci</span>
                                            @endif
                                        </td>
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
