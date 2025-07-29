<x-app-layout-component :title="$app['title'] ?? null">

    <style>
        .table-disabled {
            /* pointer-events: none; */
            opacity: 0.6;
        }
    </style>

    <div style="font-size: 18px" class="alert alert-info mb-3 d-flex align-items-center justify-content-start gap-3 shadow" role="info">
        <i class="fa-solid fa-circle-info fa-2xl fa-fade"></i>
        @if (!auth()->user()->hasRole(['admin']))
            <span>
                <i>
                    Perangkat Daerah yang disabled atau akses penginputan RAPPP dikunci (<i class="fa-solid fa-lock"></i>) adalah Perangkat Daerah yang mempunyai indikator urusan namun belum diinput. Silakan input <a href="/rakortek/urusan">Kinerja Bidang Urusan</a> terlebih dahulu
                </i>
            </span>
        @else
            <span>
                <i>
                    Aman bro..karena ko admin jadi ko bebas 😉
                </i>
            </span>
        @endif
    </div>

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
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 15%">KODE OPD</th>
                            <th>NAMA OPD</th>
                            <th style="width: 15%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($opds as $itemOpd)
                            @php
                                $notAllow =
                                    !auth()
                                        ->user()
                                        ->hasRole(['admin']) &&
                                    $itemOpd->has_indikator['status'] &&
                                    $itemOpd->has_indikator['count'] == 0
                                        ? true
                                        : false;
                            @endphp
                            <tr class="{{ $notAllow ? 'table-disabled' : '' }}">
                                <td>{{ $itemOpd->kode_opd }}</td>
                                <td>{{ $itemOpd->id . ' - ' . $itemOpd->nama_opd }}</td>
                                <td class="text-nowrap text-center">
                                    <div class="btn-group">
                                        @if (!$notAllow)
                                            <a href="/rakortek/rappp/opd?id={{ $itemOpd->id }}" class="btn btn-sm btn-primary">
                                                <i class="fa-solid fa-list"></i>
                                            </a>
                                        @endif
                                        @if ($notAllow)
                                            <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Wajib menginput indikator urusan terlebih dahulu!">
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fa-solid fa-lock"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    @include('v1-1.rakortek.rappp.script-rakortek-rappp')
</x-app-layout-component>
