<x-app-layout-component>
    <div class="dashboard-cards mb-4">
        <div class="dashboard-card dashboard-card-primary">
            <div class="card-content">
                <h5>Total Alokasi Otsus</h5>
                <h5>Rp. {{ formatIdr($totalAlokasiOtsusTkdd) }}</h5>
            </div>
        </div>

        <div class="dashboard-card dashboard-card-secondary">
            <div class="card-content">
                <h5>Alokasi Otsus BG 1%</h5>
                <h5>Rp. {{ formatIdr($alokasi_otsus ? $alokasi_otsus->alokasi_bg : 0) }}</h5>
            </div>
        </div>

        <div class="dashboard-card dashboard-card-success">
            <div class="card-content">
                <h5>Alokasi Otsus SG 1,25%</h5>
                <h5>Rp. {{ formatIdr($alokasi_otsus ? $alokasi_otsus->alokasi_sg : 0) }}</h5>
            </div>
        </div>

        <div class="dashboard-card dashboard-card-info">
            <div class="card-content">
                <h5>Alokasi DTI</h5>
                <h5>Rp. {{ formatIdr($alokasi_otsus ? $alokasi_otsus->alokasi_dti : 0) }}</h5>
            </div>
        </div>

        <div class="dashboard-card dashboard-card-warning text-dark">
            <div class="card-content">
                <h5>Jumlah SKPD</h5>
                <h5>{{ $countSkpd }} Organisasi</h5>
            </div>
        </div>

        <div class="dashboard-card dashboard-card-purple">
            <div class="card-content">
                <h5>Program RAP</h5>
                <h5>{{ $countProgram }} Program</h5>
            </div>
        </div>

        <div class="dashboard-card dashboard-card-teal">
            <div class="card-content">
                <h5>Kegiatan RAP</h5>
                <h5>{{ $countKegiatan }} Kegiatan</h5>
            </div>
        </div>

        <div class="dashboard-card dashboard-card-danger">
            <div class="card-content">
                <h5>Sub Kegiatan RAP</h5>
                <h5>{{ $countRap }} Sub Kegiatan</h5>
            </div>
        </div>

        @if (auth()->user()->hasRole('admin'))
            <div class="dashboard-card dashboard-card-light">
                <div class="card-content">
                    <h5>Total Otsus Terinput</h5>
                    <h5>Rp. {{ formatIdr($totalInputOtsus) }}</h5>
                </div>
            </div>

            <div class="dashboard-card dashboard-card-light">
                <div class="card-content">
                    @php
                        $selisihInputan = $totalInputOtsus - $totalAlokasiOtsusTkdd;
                    @endphp
                    <h5>
                        Sisa Input
                        @if ($selisihInputan < 0)
                            <span class="{{ $selisihInputan < 0 ? 'badge bg-danger' : ($selisihInputan > 0 ? 'badge bg-danger' : 'badge bg-success') }}">
                                Kurang
                                {{ $selisihInputan < 0 ? 'Kurang' : ($selisihInputan > 0 ? 'Lebih' : 'Sama') }}
                            </span>
                        @endif
                        @if ($selisihInputan > 0)
                            <span class="badge bg-warning">
                                Lebih
                            </span>
                        @endif
                        @if ($selisihInputan == 0)
                            <span class="badge bg-success">
                                Lebih
                            </span>
                        @endif
                    </h5>
                    <h5>Rp. {{ formatIdr($selisihInputan) }}</h5>
                </div>
            </div>

            <div class="dashboard-card dashboard-card-light">
                <div class="card-content">
                    @php
                        $selsiihInputBg = $alokasi_otsus ? $dataKlasBel['alokasi_bg']['alokasi_terinput'] - $alokasi_otsus->alokasi_bg : 0;
                    @endphp
                    <h5>
                        Sisa Otsus BG 1%
                        <span class="badge bg-danger">
                            @if ($selsiihInputBg < 0)
                                Kurang
                            @elseif ($selsiihInputBg == 0)
                                Pass
                            @else
                                Lebih
                            @endif
                        </span>
                    </h5>
                    <h5>Rp. {{ formatIdr($selsiihInputBg) }}</h5>
                </div>
            </div>

            <div class="dashboard-card dashboard-card-light">
                <div class="card-content">
                    @php
                        $selisihInputSg = $alokasi_otsus ? $dataKlasBel['alokasi_sg']['alokasi_terinput'] - $alokasi_otsus->alokasi_sg : 0;
                    @endphp
                    <h5>
                        Sisa Otsus SG 1,25%
                        <span class="badge bg-danger">
                            @if ($selisihInputSg < 0)
                                Kurang
                            @elseif ($selisihInputSg == 0)
                                Pass
                            @else
                                Lebih
                            @endif
                        </span>
                    </h5>
                    <h5>Rp. {{ formatIdr($selisihInputSg) }}</h5>
                </div>
            </div>

            <div class="dashboard-card dashboard-card-light">
                <div class="card-content">
                    @php
                        $selisihInputDti = $alokasi_otsus ? $dataKlasBel['alokasi_dti']['alokasi_terinput'] - $alokasi_otsus->alokasi_dti : 0;
                    @endphp
                    <h5>
                        Sisa DTI
                        <span class="badge bg-danger">
                            @if ($selisihInputDti < 0)
                                Kurang
                            @elseif ($selisihInputDti == 0)
                                Pass
                            @else
                                Lebih
                            @endif
                        </span>
                    </h5>
                    <h5>Rp. {{ formatIdr($selisihInputDti) }}</h5>
                </div>
            </div>
        @endif

    </div>

    <div class="card mb-3">
        <div class="card-header">
            Alokasi Otsus & DTI Berdasarkan Klasifikasi Belanja
        </div>
        <div class="card-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @foreach ($dataKlasBel as $klasBelPill)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $klasBelPill['active'] ? 'active' : '' }}" id="{{ $klasBelPill['id'] }}-tab" data-bs-toggle="pill" data-bs-target="#{{ $klasBelPill['id'] }}" type="button" role="tab" aria-controls="{{ $klasBelPill['id'] }}" aria-selected="{{ $klasBelPill['active'] ? 'true' : 'false' }}">
                            {{ $klasBelPill['name'] }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="pills-tabContent">
                @foreach ($dataKlasBel as $klasBelTab)
                    <div class="tab-pane fade {{ $klasBelTab['active'] ? 'show active' : '' }}" id="{{ $klasBelTab['id'] }}" role="tabpanel" aria-labelledby="{{ $klasBelTab['id'] }}-tab" tabindex="0">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Klasifikasi Belanja</th>
                                        <th>Alokasi Otsus {{ strtoupper($klasBelTab['alias']) }}</th>
                                        <th>Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($klasBelTab['klasifikasi'] as $itemKlasBel)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-start">{{ $itemKlasBel['name'] }}</td>
                                            <td class="text-end text-nowrap">Rp. {{ formatIdr($itemKlasBel['total']) }}</td>
                                            <td class="text-center">{{ formatIdr($itemKlasBel['persen']) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('script-home')
</x-app-layout-component>
