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

                    <div class="mb-3">
                        <form action="/config/sinkron/opd-sipd" method="post">
                            @csrf
                            <input type="hidden" name="tahun" value="{{ session()->get('tahun') }}">
                            <button class="btn btn-primary"><i class="fa-solid fa-arrows-rotate"></i> Sinkron SKPD SIPD-RI</button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kode OPD</th>
                                    <th>Nama OPD</th>
                                    <th>Tahun</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($opds as $opd)
                                    <tr>
                                        <td>{{ $opd->kode_opd }}</td>
                                        <td>{{ $opd->nama_opd }}</td>
                                        <td>{{ $opd->tahun }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-primary"><i class="fa-regular fa-pen-to-square"></i></button>
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
