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

                    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addHeaderSinkronDjpkSikdModal"><i class="fa-solid fa-square-plus"></i> Headers</button>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <td>#</td>
                                    <td>Jenis</td>
                                    <td>Name</td>
                                    <td>Method</td>
                                    <td>URL</td>
                                    <td>Param Key</td>
                                    <td>Param Value</td>
                                    <td>Sumber Dana</td>
                                    <td>Tahun</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data as $itemUrl)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $itemUrl->jenis }}</td>
                                        <td>{{ $itemUrl->name }}</td>
                                        <td>{{ $itemUrl->method }}</td>
                                        <td style="max-width: 300px">{{ $itemUrl->url }}</td>
                                        <td>{{ $itemUrl->param_key }}</td>
                                        <td style="max-width: 300px">{{ $itemUrl->param_value }}</td>
                                        <td>{{ $itemUrl->sumberdana }}</td>
                                        <td>{{ $itemUrl->tahun }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-primary btn-edit-request-header" data-bs-toggle="modal" data-bs-target="#editHeaderSinkronDjpkSikdModal" data-id="{{ $itemUrl->id }}" data-jenis="{{ $itemUrl->jenis }}" data-name="{{ $itemUrl->name }}" data-method="{{ $itemUrl->method }}" data-url="{{ $itemUrl->url }}"data-param_key="{{ $itemUrl->param_key }}" data-param_value="{{ $itemUrl->param_value }}" data-tahun="{{ $itemUrl->tahun }}" data-sumberdana="{{ $itemUrl->sumberdana }}"><i class="fa-solid fa-pen-to-square"></i></button>
                                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
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


    @include('sinkron-data.djpk.modal-add-header-sinkron-djpk-sikd')
    @include('sinkron-data.djpk.modal-edit-header-sinkron-djpk-sikd')
    @include('sinkron-data.djpk.script-sinkron-djpk-sikd')

</x-app-layout-component>
