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

                </div>
            </div>
        </div>
    </div>

    @include('data.publish.rap.publish-rap-script')

</x-app-layout-component>
