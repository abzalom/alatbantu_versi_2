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
                    <h4>Anda login sebagai User Perangkat Daerah</h4>
                    @else
                    <h4>Anda login sebagai {{ auth()->user()->name }}</h4>
                    @endif

                    {{-- <button id="test_api" class="btn btn-danger mb-3 mt-3">Test Api</button> --}}
                </div>
            </div>
        </div>
    </div>

    @include('script-home')

    {{-- <script>
        $('#test_api').on('click', function() {
            $.ajax({
                type: "POST",
                url: appApiUrl + "/api/test",
                data: {
                    test: 'Testing Data'
                },
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                }
            });
        });
    </script> --}}

</x-app-layout-component>