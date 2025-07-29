<x-app-layout-component :title="$app['title'] ?? null">

    <div style="font-size: 18px" class="alert alert-warning mb-3 d-flex align-items-center justify-content-start gap-3" role="info">
        <i class="fa-solid fa-circle-info fa-2xl fa-fade"></i>
        @if (!auth()->user()->hasRole(['admin']))
            <span>
                <i>
                    Perangkat Daerah akan muncul jika Usulan target Indikator RAPPP telah disetujui dan divalidasi. Silahkan lihat hasil <a href="/pembahasan/rakortek/rappp" class="text-decoration-underline">Pembahasan Indikator RAPPP</a>
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

    <div class="klas-card-container">
        @foreach ($dataKlasBel as $klasBel)
            <div class="card klas-card">
                <div class="card-body klas-card-body">
                    <div class="klas-content-text">
                        <h5 class="text-wrap">{{ $klasBel->nama }}</h5>
                        <h4>Rp. {{ formatIdr($klasBel->anggaran) }}</h4>
                    </div>
                    <span class="klas-persen">{{ formatIdr($klasBel->persen * 100) }}%</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-4">
                @if (auth()->user()->hasRole('admin'))
                    <table class="table-sm table-striped">
                        <tr>
                            <td>Alokasi Pagu {{ $sumberdana }}</td>
                            <td>:</td>
                            <td>Rp. {{ formatIdr($pagu_alokasi) }}</td>
                        </tr>
                        <tr>
                            <td>Input RAP {{ $sumberdana }}</td>
                            <td>:</td>
                            <td>Rp. {{ formatIdr($total_input_rap) }}</td>
                        </tr>
                        <tr style="border-top: 1px solid rgb(164, 164, 164); color: #333; font-weight: 600;">
                            <td>Selisih</td>
                            <td>:</td>
                            <td>
                                Rp. {{ formatIdr($selisih_input) }}
                                @if ($selisih_input < 0)
                                    <span class="badge bg-secondary">over</span>
                                @endif
                                @if ($selisih_input > 0)
                                    <span class="badge bg-secondary">sisa</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>KODE</th>
                            <th>PERANGKAT DAERAH</th>
                            <th>ALOKASI {{ strtoupper($jenis) }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $opd)
                            <tr>
                                <td>{{ $opd->kode_opd }}</td>
                                <td>{{ $opd->nama_opd }}</td>
                                <td>{{ formatIdr($opd->alokasi) }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/rap/{{ $jenis }}/renja?skpd={{ $opd->id }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="RAP"><i class="fa-solid fa-list"></i></a>
                                        <a href="#" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Pelaporan"><i class="fa-solid fa-chart-bar"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('v1-1.admin.rap.script-admin-rap')
</x-app-layout-component>
