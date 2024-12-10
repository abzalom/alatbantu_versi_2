<x-app-layout-component :title="$app['title'] ?? null">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

                    @include('sinkron-data.djpk.menu-sinkron-data-sikd')

                    <div class="row">
                        <div class="col-sm-12 col-md-8 col-lg-6 mb-4">
                            <label for="request-select-data" class="form-label">Pilih URL</label>
                            <div class="input-group">
                                <select class="form-select" id="request-select-data" aria-label="Example select with button addon">
                                    <option value="">Pilih...</option>
                                    @foreach ($data as $itemData)
                                        <option value="{{ $itemData->id }}" data-jenis="{{ $itemData->jenis }}" data-sumberdana="{{ $itemData->sumberdana }}">{{ $itemData->name }}</option>
                                    @endforeach
                                </select>
                                <button id="btn-get-data-sikd" class="btn btn-outline-secondary" type="button">Ambil data</button>
                            </div>
                            <small id="request_data_error" class="text-danger"></small>
                        </div>
                    </div>

                    <div id="spinner-progres-data-show" class="text-center p-3 mb-4" style="background: #e9e9e9; display: none">
                        <h5 id="spinner-progres-title"></h5>
                        <div id="spinner" class="spinner-border" style="width: 4rem; height: 4rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="info-data-request" class="mb-4" style="display: none">
                        <h5></h5>
                        <button id="btn-sinkron-data-to-db" type="button" class="btn btn-primary mb-3 mt-2">Sinkron Data</button>
                    </div>

                    <div id="spinner-tampil-data-show" class="text-center p-3 mb-4" style="background: #e9e9e9; display: none">
                        <div class="spinner-border" style="width: 4rem; height: 4rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="table-show-data-sinkron" style="display: none">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="font-size: 90%">
                                <thead id="table-header-show-data-sinkron" class="table-dark">
                                </thead>
                                <tbody id="table-body-show-data-sinkron">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('sinkron-data.djpk.script-sinkron-djpk-sikd')

</x-app-layout-component>
