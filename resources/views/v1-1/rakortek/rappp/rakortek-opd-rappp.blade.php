<x-app-layout-component :title="$app['title'] ?? null">

    <script>
        let temaRappp = @json($temaRappp);
    </script>

    <div style="font-size: 16px" class="alert alert-info mb-3 d-flex align-items-center justify-content-start gap-3 shadow fst-italic" role="info">
        <i class="fa-solid fa-circle-info fa-2xl fa-fade"></i>
        @if (!auth()->user()->hasRole(['admin']))
            <ul class="mb-0">
                <li>
                    Perangkat Daerah akan muncul setelah melakukan inputan pada <a href="/rakortek/urusan">Kinerja Bidang Urusan</a>
                </li>
                <li>Indikator yang telah dibahas (selain perbaikan) tidak dapat diubah!</li>
                <li>Indikator yang telah divalidasi tidak dapat diubah atau dihapus!</li>
                <li>Indikator yang sudah ada Rencana Anggaran Program (RAP) tidak dapat dihapus!</li>
            </ul>
        @else
            <span>
                <i>
                    Aman bro..karena ko admin jadi ko bebas 😉
                </i>
            </span>
        @endif
    </div>

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
            <div class="mb-3 d-flex justify-content-between gap-2">
                <button id="new-rappp-program" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newProgramRapppModal"><i class="fa-solid fa-square-plus"></i> Program</button>
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#arsipProgramRapppModal"><i class="fa-solid fa-folder-open"></i> Arsip</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size: 90%">
                    <thead class="align-middle table-primary">
                        <tr>
                            <th>ID</th>
                            <th>TEMA</th>
                            <th>PROGRAM</th>
                            <th>TARGET AKTIFITAS</th>
                            <th>SATUAN</th>
                            <th>VOLUME</th>
                            <th>SUMBERDANA</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rappps as $itemRappp)
                            @if (!$itemRappp->deleted_at)
                                @php
                                    $totalRap = $itemRappp->total_rap > 0 ? true : false;
                                    $pembahasan = $itemRappp->pembahasan && $itemRappp->pembahasan !== 'perbaikan' ? true : false;
                                @endphp
                                <tr class="align-middle @if ($pembahasan) table-warning @endif"' @if ($pembahasan) data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Sudah dibahas/ divalidasi, tidak dapat diubah!" @endif>
                                    <td>{{ $itemRappp->id }}</td>
                                    <td>{{ $itemRappp->tema }}</td>
                                    <td>{{ $itemRappp->program }}</td>
                                    <td>
                                        {{ $itemRappp->target_aktifitas }}
                                        @if ($itemRappp->validasi)
                                            <i class="fa-solid fa-check text-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Sudah divalidasi"></i>
                                        @endif
                                    </td>
                                    <td>{{ $itemRappp->satuan }}</td>
                                    <td>{{ $itemRappp->volume }}</td>
                                    <td>{{ $itemRappp->sumberdana }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            @if (!$pembahasan)
                                                <button class="btn btn-sm btn-primary btn-edit-rapp" value="{{ $itemRappp->id }}" data-bs-toggle="modal" data-bs-target="#editProgramRapppModal" data-rappp='@json($itemRappp)'><i class="fa-solid fa-pen-square"></i></button>
                                                @if (!$totalRap)
                                                    <button class="btn btn-sm btn-danger btn-delete-rappp" value="{{ $itemRappp->id }}"><i class="fa-solid fa-trash"></i></button>
                                                @endif
                                            @else
                                                <i class="fa-solid fa-circle-info text-info fa-beat" style="font-size: 1.5rem"></i>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    @include('v1-1.rakortek.rappp.modal.modal-new-rappp-program-opd')
    @include('v1-1.rakortek.rappp.modal.modal-edit-rappp-program-opd')
    @include('v1-1.rakortek.rappp.modal.modal-delete-rappp-program-opd')
    @include('v1-1.rakortek.rappp.modal.modal-arsip-rappp-program-opd')

    @include('v1-1.rakortek.rappp.script-rakortek-rappp')
</x-app-layout-component>
