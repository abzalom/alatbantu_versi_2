<x-app-layout-component :title="$app['title'] ?? null">
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
                        @foreach ($opds as $opd)
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
