<x-app-layout-component>


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
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>KODE SKPD</th>
                            <th>NAMA SKPD</th>
                            <th>PAGU OTSUS 1%</th>
                            <th>PAGU OTSUS 1,25%</th>
                            <th>PAGU DTI</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $opd)
                            <tr>
                                <td>{{ $opd->kode_opd }}</td>
                                <td>{{ $opd->nama_opd }}</td>
                                <td class="text-nowrap">{{ $opd->pagu && $opd->pagu->bg ? 'Rp. ' . formatIdr($opd->pagu->bg) : '' }}</td>
                                <td class="text-nowrap">{{ $opd->pagu && $opd->pagu->sg ? 'Rp. ' . formatIdr($opd->pagu->sg) : '' }}</td>
                                <td class="text-nowrap">{{ $opd->pagu && $opd->pagu->dti ? 'Rp. ' . formatIdr($opd->pagu->dti) : '' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary btn-pagu-skpd" data-bs-toggle="modal" data-bs-target="#pengaturanPaguSkpdModal" data-opd='@json($opd)'><i class="fa-solid fa-cog"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('v1-1.config.pagu.modal.modal-config-pagu-skpd')
    @include('v1-1.config.pagu.script-config-pagu-skpd')
</x-app-layout-component>
