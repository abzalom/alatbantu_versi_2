<x-app-layout-component :title="$app['title'] ?? null">

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

                    @include('rekapan.menu-rekapan-indikator')

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>KODE</th>
                                    <th>URAIAN</th>
                                    <th>VOLUME</th>
                                    <th>SATUAN</th>
                                    <th>ANGGARAN</th>
                                    <th>SUMBER PENDANAAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $tema)
                                    <tr>
                                        <th>Tema</th>
                                        <th>{{ $tema->kode_tema }}</th>
                                        <th colspan="5">{{ $tema->uraian }}</th>
                                    </tr>
                                    @foreach ($tema->program as $program)
                                        <tr>
                                            <th>Program</th>
                                            <th>{{ $program->kode_program }}</th>
                                            <th colspan="5">{{ $program->uraian }}</th>
                                        </tr>
                                        @foreach ($program->keluaran as $keluaran)
                                            <tr>
                                                <th>Keluaran</th>
                                                <th>{{ $keluaran->kode_keluaran }}</th>
                                                <th colspan="5">{{ $keluaran->uraian }}</th>
                                            </tr>
                                            @foreach ($keluaran->aktifitas as $aktifitas)
                                                <tr>
                                                    <th>Aktifitas</th>
                                                    <th>{{ $aktifitas->kode_aktifitas }}</th>
                                                    <th colspan="5">{{ $aktifitas->uraian }}</th>
                                                </tr>
                                                @foreach ($aktifitas->target_aktifitas as $target_aktifitas)
                                                    <tr>
                                                        <td>Target Aktifitas</td>
                                                        <td>{{ $target_aktifitas->kode_target_aktifitas }}</td>
                                                        <td>{{ $target_aktifitas->uraian }}</td>
                                                        <td>{{ $target_aktifitas->volume }}</td>
                                                        <td>{{ $target_aktifitas->satuan }}</td>
                                                        <td>{{ $target_aktifitas->anggaran }}</td>
                                                        <td>
                                                            <ul>
                                                                @foreach ($target_aktifitas->raps as $dana)
                                                                    <li>
                                                                        {{ $dana->sumberdana }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('script-home')
</x-app-layout-component>
