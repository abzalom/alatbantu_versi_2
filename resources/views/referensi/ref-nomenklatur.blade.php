<x-app-layout-component :title="$app['title'] ?? null">

    <div class="row mb-3">
        <div class="col-lg">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row align-middle">
                    <h5 class="card-title">
                        @isset($app['desc'])
                            {{ $app['desc'] }}
                        @else
                            Deskripsi Halaman
                        @endisset
                    </h5>
                    @if (auth()->user()->hasRole('admin'))
                        <div class="ms-md-auto mt-3 mt-md-0">
                            <form action="/ref/nomenklatur/update/sikd" method="post">
                                @csrf
                                <button type="submit" id="sinkron-data" type="button" class="btn btn-secondary">Update Dari SIKD</button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <form action="/ref/nomenklatur/cetak" method="get" target="_blank">
                        <div class="row mb-3">
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="bidang1" class="form-label">Bidang Urusan 1</label>
                                    <select name="bidang1" class="form-control select2" id="bidang1" data-placeholder="Pilih...">
                                        <option></option>
                                        @foreach ($data as $bidang1)
                                            <option value="{{ $bidang1['kode_bidang'] }}">{{ $bidang1['text_bidang'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="bidang2" class="form-label">Bidang Urusan 2</label>
                                    <select name="bidang2" class="form-control select2" id="bidang2" data-placeholder="Pilih...">
                                        <option></option>
                                        @foreach ($data as $bidang2)
                                            <option value="{{ $bidang2['kode_bidang'] }}">{{ $bidang2['text_bidang'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="bidang3" class="form-label">Bidang Urusan 3</label>
                                    <select name="bidang3" class="form-control select2" id="bidang3" data-placeholder="Pilih...">
                                        <option></option>
                                        @foreach ($data as $bidang3)
                                            <option value="{{ $bidang3['kode_bidang'] }}">{{ $bidang3['text_bidang'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-print"></i> Cetak</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>KODE BIDANG</th>
                                    <th>NAMA BIDANG</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data as $itemBidang)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $itemBidang['kode_bidang'] }}</td>
                                        <td>{{ $itemBidang['uraian'] }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-primary"><i class="fa-solid fa-list"></i></button>
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

        @include('referensi.ref-script')
</x-app-layout-component>
