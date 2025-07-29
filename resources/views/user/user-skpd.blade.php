<x-app-layout-component :title="$app['title'] ?? null">

    {{-- @include('user.user-skpd-statistik') --}}
    <div class="klas-card-container">
        <div class="card klas-card">
            <div class="card-body klas-card-body">
                <div class="klas-content-text">
                    <h5 class="text-wrap">Belanja Pemeliharaan dan Pelestarian Lingkungan</h5>
                    <h4>Rp. 1.000.000.000</h4>
                </div>
                <span class="klas-persen">100%</span>
            </div>
        </div>
    </div>

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
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>KODE SKPD</th>
                                    <th>NAMA SKPD</th>
                                    <th>JUMLAH RAP</th>
                                    <th>PAGU OTSUS BG (1%)</th>
                                    <th>PAGU OTSUS SG (1,25%)</th>
                                    <th>PAGU DTI</th>
                                    <th>JUMLAH PAGU</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $opd)
                                    <tr>
                                        <td>{{ $opd->kode_opd }}</td>
                                        <td>{{ $opd->nama_opd }}</td>
                                        <td class="text-center">{{ $opd->jumlah_rap }}</td>
                                        <td class="text-end">{{ formatIdr($opd->pagu_bg) }}</td>
                                        <td class="text-end">{{ formatIdr($opd->pagu_sg) }}</td>
                                        <td class="text-end">{{ formatIdr($opd->pagu_dti) }}</td>
                                        <td class="text-end">{{ formatIdr($opd->pagu_bg + $opd->pagu_sg + $opd->pagu_dti) }}</td>
                                        <td class="text-nowrap">
                                            <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Lihat RAP">
                                                <a href="/user/rap/skpd/{{ $opd->id }}" class="btn btn-primary"><i class="fa-solid fa-list"></i> RAP</a>
                                            </div>
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

    @include('script-home')

</x-app-layout-component>
