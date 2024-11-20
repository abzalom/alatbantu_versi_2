<x-app-layout-component :title="$app['title'] ?? null">

    <style>
        #tabel-alokasi {
            width: 45%
        }
    </style>

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
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-danger align-middle text-center">
                    <tr>
                        <th rowspan="2">Klasifiaskai Belanja</th>
                        <th colspan="2">Otsus 1%</th>
                        <th colspan="2">Otsus 1,25%</th>
                        <th colspan="2">DTI</th>
                        <th colspan="2">Belum ada sumberdana</th>
                        <th rowspan="2">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @php
                        $totalKlasBel = [
                            'Otsus 1%' => (float) 0,
                            'Otsus 1,25%' => (float) 0,
                            'DTI' => (float) 0,
                            'unknow' => (float) 0,
                            'total' => (float) 0,
                        ];
                    @endphp
                    @foreach ($dataKlasBel as $keyKlas => $itemKlas)
                        @php
                            $paguBg = isset($itemKlas['Otsus 1%']) ? $itemKlas['Otsus 1%']['pagu'] : 0;
                            $paguSg = isset($itemKlas['Otsus 1,25%']) ? $itemKlas['Otsus 1,25%']['pagu'] : 0;
                            $paguDti = isset($itemKlas['DTI']) ? $itemKlas['DTI']['pagu'] : 0;
                            $paguUnknow = isset($itemKlas['unknow']) ? $itemKlas['unknow']['pagu'] : 0;
                            $totalKlasBel['Otsus 1%'] += $paguBg;
                            $totalKlasBel['Otsus 1,25%'] += $paguSg;
                            $totalKlasBel['DTI'] += $paguDti;
                            $totalKlasBel['unknow'] += $paguUnknow;
                            $totalKlasBel['total'] += $paguBg + $paguSg + $paguDti + $paguUnknow;
                            $jumlahKlasBel = 0;
                        @endphp
                        <tr>
                            <td>{{ $keyKlas }}</td>
                            @isset($itemKlas['Otsus 1%'])
                                @php
                                    $jumlahKlasBel += $itemKlas['Otsus 1%']['pagu'];
                                @endphp
                                <td class="text-nowrap text-end">
                                    {{ formatIdr($itemKlas['Otsus 1%']['pagu'], 2) }}
                                </td>
                                <td class="text-nowrap text-end">
                                    <span class="badge text-bg-info">{{ $itemKlas['Otsus 1%']['persentase'] }}</span>
                                </td>
                            @else
                                <td class="text-nowrap text-end">
                                    0,00
                                </td>
                                <td class="text-nowrap text-end">
                                    <span class="badge text-bg-info">0%</span>
                                </td>
                            @endisset

                            @isset($itemKlas['Otsus 1,25%'])
                                @php
                                    $jumlahKlasBel += $itemKlas['Otsus 1,25%']['pagu'];
                                @endphp
                                <td class="text-nowrap text-end">
                                    {{ formatIdr($itemKlas['Otsus 1,25%']['pagu'], 2) }}
                                </td>
                                <td class="text-nowrap text-end">
                                    <span class="badge text-bg-info">{{ $itemKlas['Otsus 1,25%']['persentase'] }}</span>
                                </td>
                            @else
                                <td class="text-nowrap text-end">
                                    0,00
                                </td>
                                <td class="text-nowrap text-end">
                                    <span class="badge text-bg-info">0%</span>
                                </td>
                            @endisset

                            @isset($itemKlas['dti'])
                                @php
                                    $jumlahKlasBel += $itemKlas['dti']['pagu'];
                                @endphp
                                <td class="text-nowrap text-end">
                                    {{ formatIdr($itemKlas['dti']['pagu'], 2) }}
                                </td>
                                <td class="text-nowrap text-end">
                                    <span class="badge text-bg-info">{{ $itemKlas['dti']['persentase'] }}</span>
                                </td>
                            @else
                                <td class="text-nowrap text-end">
                                    0,00
                                </td>
                                <td class="text-nowrap text-end">
                                    <span class="badge text-bg-info">0%</span>
                                </td>
                            @endisset

                            @isset($itemKlas['unknow'])
                                @php
                                    $jumlahKlasBel += $itemKlas['unknow']['pagu'];
                                @endphp
                                <td class="text-nowrap text-end">
                                    {{ formatIdr($itemKlas['unknow']['pagu'], 2) }}
                                </td>
                                <td class="text-nowrap text-end">
                                    <span class="badge text-bg-info">{{ $itemKlas['unknow']['persentase'] }}</span>
                                </td>
                            @else
                                <td class="text-nowrap text-end">
                                    0,00
                                </td>
                                <td class="text-nowrap text-end">
                                    <span class="badge text-bg-info">0%</span>
                                </td>
                            @endisset

                            <td>{{ formatIdr($jumlahKlasBel, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="align-middle">
                    <tr>
                        <th rowspan="2" class="text-end">TOTAL</th>
                        <th colspan="2" class="text-end">{{ formatIdr($totalKlasBel['Otsus 1%'], 2) }}</th>
                        <th colspan="2" class="text-end">{{ formatIdr($totalKlasBel['Otsus 1,25%'], 2) }}</th>
                        <th colspan="2" class="text-end">{{ formatIdr($totalKlasBel['DTI'], 2) }}</th>
                        <th colspan="2" class="text-end">{{ formatIdr($totalKlasBel['unknow'], 2) }}</th>
                        <th colspan="2" class="text-end">{{ formatIdr($totalKlasBel['total'], 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>



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

                    <div class="table-responsive">
                        <table id="tabel-alokasi" class="table table-sm">
                            <thead class="table-secondary">
                                <tr>
                                    <th colspan="2">Uraian</th>
                                    <th>TKDD</th>
                                    <th>Pagu Inputan</th>
                                    <th>Selisih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $jumlahTkdd = 0;
                                    $jumlahRapInput = 0;
                                @endphp
                                @foreach ($alokasi_otsus as $itemAlokasi)
                                    @php
                                        $jumlahTkdd += $itemAlokasi['alokasi'];
                                        $jumlahRapInput += $itemAlokasi['pagu'];
                                    @endphp
                                    @if ($itemAlokasi['nama'] == 'UNKNOW' && $itemAlokasi['pagu'] > 0)
                                        <tr>
                                            <td class="text-nowrap">{{ $itemAlokasi['nama'] }}</td>
                                            <td class="text-nowrap text-center">:</td>
                                            <td class="text-nowrap text-end">Rp. {{ formatIdr($itemAlokasi['alokasi'], 2) }}</td>
                                            <td class="text-nowrap text-end">Rp. {{ formatIdr($itemAlokasi['pagu'], 2) }}</td>
                                            <td class="text-nowrap text-end">Rp. {{ formatIdr($itemAlokasi['selisih'], 2) }}</td>
                                        </tr>
                                    @endif
                                    @if ($itemAlokasi['nama'] !== 'UNKNOW')
                                        <tr>
                                            <td class="text-nowrap">{{ $itemAlokasi['nama'] }}</td>
                                            <td class="text-nowrap text-center">:</td>
                                            <td class="text-nowrap text-end">Rp. {{ formatIdr($itemAlokasi['alokasi'], 2) }}</td>
                                            <td class="text-nowrap text-end">Rp. {{ formatIdr($itemAlokasi['pagu'], 2) }}</td>
                                            <td class="text-nowrap text-end">Rp. {{ formatIdr($itemAlokasi['selisih'], 2) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <th class="text-nowrap">JUMLAH</th>
                                    <th class="text-nowrap text-center">:</th>
                                    <th class="text-nowrap text-end">Rp. {{ formatIdr($jumlahTkdd, 2) }}</th>
                                    <th class="text-nowrap text-end">Rp. {{ formatIdr($jumlahRapInput, 2) }}</th>
                                    <th class="text-nowrap text-end">Rp. {{ formatIdr($jumlahTkdd - $jumlahRapInput, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kode</th>
                                    <th>Uraian</th>
                                    <th>Pagu Otsus BG 1%</th>
                                    <th>Pagu Otsus SG 1,25%</th>
                                    <th>Pagu DTI%</th>
                                    <th>Jumlah Pagu</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($opds as $opd)
                                    <tr>
                                        <td>{{ $opd->kode_opd }}</td>
                                        <td>{{ $opd->nama_opd }}</td>
                                        <td class="text-end">{{ formatIdr($opd->alokasi_bg, 2) }}</td>
                                        <td class="text-end">{{ formatIdr($opd->alokasi_sg, 2) }}</td>
                                        <td class="text-end">{{ formatIdr($opd->alokasi_dti, 2) }}</td>
                                        <td class="text-end">{{ formatIdr($opd->pagu, 2) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="/rap/opd?id={{ $opd->id }}" class="btn btn-primary text-nowrap"><i class="fa-solid fa-clipboard-list"></i> RAP</a>
                                                <a href="/rap/indikator?opd={{ $opd->id }}" class="btn btn-info text-nowrap"><i class="fa-solid fa-square-plus"></i> Indikator</a>
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

    @include('rap.rap-script')
</x-app-layout-component>
