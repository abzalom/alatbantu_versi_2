<x-app-layout-component :title="$app['title'] ?? null">

    @if (session()->has('pesan-success'))
        <div class="row mb-3">
            <div class="alert alert-success" role="alert">{{ session()->get('pesan-success') }}</div>
        </div>
    @endif
    @if (session()->has('pesan-error'))
        <div class="row mb-3">
            <div class="alert alert-danger" role="alert">{{ session()->get('pesan-error') }}</div>
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark align-middle">
                                <tr>
                                    <th></th>
                                    <th>Kode</th>
                                    <th>Uraian</th>
                                    <th>Satuan</th>
                                    <th>Kondisi Awal</th>
                                    <th>Target Impact</th>
                                    {{-- <th>Sumber Dana</th> --}}
                                    {{-- <th></th> --}}
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @foreach ($data as $tema)
                                    <tr>
                                        <th style="background: #f7d5d5">Tema Pembangunan</th>
                                        <th style="background: #f7d5d5">{{ $tema->kode_tema }}</th>
                                        <th style="background: #f7d5d5">{{ $tema->uraian }}</th>
                                        <th style="background: #f7d5d5"></th>
                                        <th style="background: #f7d5d5"></th>
                                        <th style="background: #f7d5d5"></th>
                                        {{-- <th style="background: #f7d5d5"></th> --}}
                                        {{-- <th style="background: #f7d5d5"></th> --}}
                                    </tr>
                                    @foreach ($tema->indikator as $indikator)
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>{{ $indikator->uraian }}</th>
                                            <th>{{ $indikator->satuan }}</th>
                                            <th class="text-center">{{ formatIdr($indikator->kondisi_awal, 2) }}</th>
                                            <th class="text-center">{{ formatIdr($indikator->target_impact, 2) }}</th>
                                            {{-- <td></td> --}}
                                            {{-- <td></td> --}}
                                        </tr>
                                    @endforeach
                                    @foreach ($tema->program as $program)
                                        <tr>
                                            <th>Program Prioritas</th>
                                            <th>{{ $program->kode_program }}</th>
                                            <th>{{ $program->uraian }}</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            {{-- <th></th> --}}
                                            {{-- <th></th> --}}
                                        </tr>
                                        @foreach ($program->keluaran as $keluaran)
                                            <tr>
                                                <th>Target Keluaran Strategis</th>
                                                <th>{{ $keluaran->kode_keluaran }}</th>
                                                <th>{{ $keluaran->uraian }}</th>
                                                <th>{{ $keluaran->satuan }}</th>
                                                <th></th>
                                                <th></th>
                                                {{-- <th></th> --}}
                                                {{-- <th></th> --}}
                                            </tr>
                                            @foreach ($keluaran->aktifitas as $aktifitas)
                                                <tr>
                                                    <th>Aktivitas Utama</th>
                                                    <th>{{ $aktifitas->kode_aktifitas }}</th>
                                                    <th>{{ $aktifitas->uraian }}</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    {{-- <th></th> --}}
                                                    {{-- <th></th> --}}
                                                </tr>
                                                @foreach ($aktifitas->target_aktifitas as $target_aktifitas)
                                                    <tr id="target-aktifitas-{{ $target_aktifitas->id }}">
                                                        <td>Target Aktivitas Utama</td>
                                                        <td>{{ $target_aktifitas->kode_target_aktifitas }}</td>
                                                        <td>{{ $target_aktifitas->text }}</td>
                                                        <td>{{ $target_aktifitas->satuan }}</td>
                                                        <td></td>
                                                        <td id="volume-target-{{ $target_aktifitas->id }}" class="text-center">{{ $target_aktifitas->volume ? formatIdr($target_aktifitas->volume, 2) : '' }}</td>
                                                        {{-- <td id="sumberdana-target-{{ $target_aktifitas->id }}">{{ $target_aktifitas->sumberdana }}</td> --}}
                                                        {{-- <td class="text-center" style="width: 10%">
                                                            <div class="btn-group">
                                                                <div data-bs-toggle="tooltip" data-bs-title="edit volume">
                                                                    <button value="{{ $target_aktifitas->id }}" class="btn btn-primary btn-add-volume-target" data-bs-toggle="modal" data-bs-target="#addVolTargetAktifitasMmodal">
                                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                                    </button>
                                                                </div>
                                                                <div data-bs-toggle="tooltip" data-bs-title="reset volume">
                                                                    <button class="btn btn-danger btn-reset-volume-target" value="{{ $target_aktifitas->id }}"><i class="fa-solid fa-arrows-rotate"></i></button>
                                                                </div>
                                                            </div>
                                                        </td> --}}
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

    @include('otsus.otsus-modals.otsus-modal-add-volume-target-aktifitas')
    @include('otsus.otsus-script')
</x-app-layout-component>
