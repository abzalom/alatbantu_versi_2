<x-app-layout-component :title="$app['title'] ?? null">
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
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 15%">KODE OPD</th>
                            <th>NAMA OPD</th>
                            <th style="width: 15%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($opds as $opdRakor)
                            <tr>
                                <td>{{ $opdRakor->kode_opd }}</td>
                                <td>{{ $opdRakor->nama_opd }}</td>
                                <td class="text-center">
                                    <div class="btn-group text-nowrap">
                                        <a href="/rakortek/urusan/{{ $opdRakor->id }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-handshake"></i> Indikator</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('v1-1.rakortek.indikator_bidang_urusan.script-rakortek-urusan')
</x-app-layout-component>
