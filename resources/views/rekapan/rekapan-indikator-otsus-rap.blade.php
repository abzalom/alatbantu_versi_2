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
                                @foreach ($data as $itemIndikator)
                                    <tr>
                                        <th>Target Aktifitas</th>
                                        <th>{{ $itemIndikator->kode_target_aktifitas }}</th>
                                        <th>{{ $itemIndikator->uraian }}</th>
                                        <th>{{ $itemIndikator->volume }}</th>
                                        <th>{{ $itemIndikator->satuan }}</th>
                                        <th class="text-end">{{ formatIdr($itemIndikator->anggaran) }}</th>
                                        <th></th>
                                    </tr>
                                    @foreach ($itemIndikator->raps as $itemRap)
                                        <tr>
                                            <td>Target Aktifitas</td>
                                            <td>{{ $itemRap->kode_subkegiatan }}</td>
                                            <td>{{ $itemRap->nama_subkegiatan }}</td>
                                            <td>{{ $itemRap->vol_subkeg }}</td>
                                            <td>{{ $itemRap->satuan_subkegiatan }}</td>
                                            <td class="text-end">{{ formatIdr($itemRap->anggaran) }}</td>
                                            <td>{{ $itemRap->sumberdana }}</td>
                                        </tr>
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
