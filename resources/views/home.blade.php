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
                    <h4>Selamat Datang</h4>
                    @if (auth()->user()->hasRole('user'))
                        <h4>Anda login sebagai Perangkat Daerah</h4>
                        <h4>{{ auth()->user()->opd->kode_opd . ' ' . auth()->user()->opd->nama_opd }}</h4>
                    @else
                        <h4>Anda login sebagai {{ auth()->user()->name }}</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('script-home')
</x-app-layout-component>
