<x-app-layout-component :title="$app['title']">
    @php
        $admin = auth()->user()->hasRole('admin') ? true : false;
    @endphp


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
                <table class="table table-hover table-bordered table-stripped">
                    <thead class="table-{{ $admin ? 'primary' : 'dark' }}">
                        <tr>
                            <th scope="col">KODE OPD</th>
                            <th scope="col">NAMA OPD</th>
                            <th scope="col"></th>
                    </thead>
                    <tbody>
                        @foreach ($data as $opd)
                            <tr>
                                <td>{{ $opd->kode_opd }}</td>
                                <td>{{ $opd->nama_opd }}</td>
                                <td class="text-center">
                                    <a target="_blank" href="/pembahasan/rakortek/rappp/opd?id={{ $opd->id }}" class="btn btn-sm btn-{{ $admin ? 'primary' : 'secondary' }}">
                                        @if ($admin)
                                            <i class="fa-solid fa-handshake"></i>
                                        @else
                                            <i class="fa-solid fa-eye"></i>
                                        @endif
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('script-home')
</x-app-layout-component>
