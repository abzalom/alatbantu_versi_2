<x-app-layout-component :title="$app['title'] ?? null">

    @if (session()->has('pesan-success'))
        <div class="row mb-3">
            <div class="alert alert-success" role="alert">{{ session()->get('pesan-success') }}</div>
        </div>
    @endif
    @if (session()->has('pesan-error'))
        <div class="row mb-3">
            <div class="alert alert-danger" role="alert">{{ session()->get('pesan-error') }}</div>
        </div>
    @endif
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

                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-8 mb-3">
                            @if (auth()->user()->hasRole('admin'))
                                <form action="/config/sinkron/opd-sipd" method="post">
                                    @csrf
                                    <input type="hidden" name="tahun" value="{{ session()->get('tahun') }}">
                                    <button class="btn btn-primary"><i class="fa-solid fa-arrows-rotate"></i> Sinkron SKPD SIPD-RI</button>
                                </form>
                            @endif
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="mb-3 ms-3">
                                <input type="text" class="form-control border-primary" id="search-opd-input" placeholder="Search...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kode OPD</th>
                                    <th>Nama OPD</th>
                                    <th>Kepala OPD</th>
                                    <th>Tahun</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table-list-opd">
                                @foreach ($opds as $opd)
                                    <tr>
                                        <td>{{ $opd->kode_opd }}</td>
                                        <td>{{ $opd->nama_opd }}</td>
                                        <td id="row-kepala-opd-{{ str_replace('.', '_', $opd->kode_unik_opd) }}" class="text-nowrap">
                                            @if ($opd->kepala_aktif)
                                                {{ $opd->kepala_aktif->nama }}
                                                <br>
                                                NIP. {{ $opd->kepala_aktif->nip }}
                                                <br>
                                                {{ $opd->kepala_aktif->pangkat }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $opd->tahun }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info btn-kepala-opd" value="{{ $opd->id }}" data-bs-toggle="modal" data-bs-target="#listKepalaOpdModal"><i class="fa-solid fa-user-tie"></i></button>
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

    @include('app.pengaturan.opd.modal.modal-kepala-opd')
    @include('app.pengaturan.opd.script-pengaturan-opd')
</x-app-layout-component>
