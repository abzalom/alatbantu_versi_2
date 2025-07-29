<x-app-layout-component :title="$app['title']">

    @php
        $admin = auth()->user()->hasRole('admin') ? true : false;
    @endphp

    @include('v1-1.rakortek.pembahasan.urusan.pembahasan-components.styles-rakortek-pembahasan-urusan-opd')

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
        @foreach ($info_card as $itemInfoCard)
            <div class="col-6 col-md-6 col-lg-3 mb-3">
                <div class="card info-card">
                    <div class="card-body info-card-body">
                        <div class="info-card-icon">
                            <i class="{{ $itemInfoCard['icon'] }}"></i>
                        </div>
                        <div class="info-card-text">
                            <span class="info-card-title">{{ $itemInfoCard['title'] }}</span>
                            <h5 class="info-card-data">{{ $itemInfoCard['count'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

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

            <div class="d-flex justify-content-between">
                <div></div>
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search-opd-bidang" placeholder="Cari SKPD / Bidang..." style="width: 300px">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-stripped">
                    <thead class="table-{{ $admin ? 'primary' : 'dark' }}">
                        <tr>
                            <th class="align-middle" rowspan="2" colspan="2">SKPD / BIDANG</th>
                            <th class="text-center align-middle" colspan="4">INDIKATOR</th>
                            <th rowspan="2"></th>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <th>Disetujui</th>
                            <th>Perbaikan</th>
                            <th>Ditolak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $itemOpd)
                            @if ($itemOpd->has_indikator['status'] && $itemOpd->has_indikator['count'] > 0)
                                <tr class="table-data">
                                    <th colspan="6">{{ $itemOpd->text_opd }}</th>
                                    <th>
                                        <div class="btn-group">
                                            <a href="/pembahasan/rakortek/urusan/opd?id={{ $itemOpd->id }}" class="btn btn-sm btn-{{ $admin ? 'primary' : 'secondary' }}">
                                                @if ($admin)
                                                    <i class="fa-regular fa-handshake"></i>
                                                @else
                                                    <i class="fa-solid fa-eye"></i>
                                                @endif
                                            </a>
                                        </div>
                                    </th>
                                </tr>
                                @foreach ($itemOpd->bidangs as $itemBidang)
                                    <tr class="table-data">
                                        <td style="width: 5%"></td>
                                        <td>{{ $itemBidang['text_bidang'] }}</td>
                                        <td class="text-center">{{ $itemBidang['indikators_count']['jumlah'] }}</td>
                                        <td class="text-center">{{ $itemBidang['indikators_count']['setujui'] }}</td>
                                        <td class="text-center">{{ $itemBidang['indikators_count']['perbaikan'] }}</td>
                                        <td class="text-center">{{ $itemBidang['indikators_count']['tolak'] }}</td>
                                        <td class="text-center"></td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('v1-1.rakortek.pembahasan.urusan.script-rakortek-pembahasan-urusan')
</x-app-layout-component>
