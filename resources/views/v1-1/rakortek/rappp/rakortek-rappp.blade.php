<x-app-layout-component :title="$app['title'] ?? null">

    <div style="font-size: 18px" class="alert alert-info mb-3 d-flex align-items-center justify-content-start gap-3 shadow" role="info">
        <i class="fa-solid fa-circle-info fa-2xl fa-fade"></i>
        @if (!auth()->user()->hasRole(['admin']))
            <ul>
                <li class="mb-2">
                    <i>
                        Perangkat Daerah yang muncul disini adalah Perangkat Daerah yang mempunyai indikator urusan dan sudah diinput terget daerah telah dibahas serta divalidasi dengan status disetujui.
                    </i>
                </li>
                <li class="mb-2">
                    <i>
                        Untuk perangkat daerah yang tidak mempunyai indikator urusan akan muncul secara otomatis.
                    </i>
                </li>
                <li>
                    <i>
                        Silakan input <a href="/rakortek/urusan">Kinerja Bidang Urusan</a> terlebih dahulu
                    </i>
                </li>
            </ul>
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
                        @foreach ($opds as $opd)
                            @if (!$opd->punya_indikator)
                                <tr>
                                    <td>{{ $opd->kode_opd }}</td>
                                    <td>{{ $opd->id . ' - ' . $opd->nama_opd }}</td>
                                    <td>
                                        <a href="/rakortek/rappp/opd?id={{ $opd->id }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-list"></i>
                                        </a>
                                    </td>
                                </tr>
                            @elseif ($opd->punya_indikator && $opd->punya_target)
                                <tr>
                                    <td>{{ $opd->kode_opd }}</td>
                                    <td>{{ $opd->id . ' - ' . $opd->nama_opd }}</td>
                                    <td>
                                        <a href="/rakortek/rappp/opd?id={{ $opd->id }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-list"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    @include('v1-1.rakortek.rappp.script-rakortek-rappp')
</x-app-layout-component>
