<x-app-layout-component :title="$app['title'] ?? null">

    <script>
        let listDana = @json($sumberdanas);
    </script>

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
                        <table class="table table-bordered">
                            <thead class="table-success">
                                <tr>
                                    <th></th>
                                    <th>Kode SKPD</th>
                                    <th>Nama SKPD</th>
                                    <th>Uraian</th>
                                    <th>Sumberdana</th>
                                    <th>Alokasi</th>
                                    <th>Jenis</th>
                                    <th>Belanja</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($opds as $opd)
                                    <tr>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-primary btn-add-pagu-skpd" value="{{ $opd->id }}" data-bs-toggle="modal" data-bs-target="#alatBantuAddPaguModal"><i class="fa-solid fa-square-plus"></i></button>
                                            </div>
                                        </td>
                                        <td>{{ $opd->kode_opd }}</td>
                                        <td>{{ $opd->nama_opd }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('alatbantu.alatbantu-pagu-skpd.modal-alat-bantu-pagu-skpd.modal-alat-bantu-pagu-skpd-add-pagu')
    @include('alatbantu.alatbantu-pagu-skpd.script-alatbantu-pagu-skpd')
</x-app-layout-component>
