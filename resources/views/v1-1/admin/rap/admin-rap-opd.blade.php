<x-app-layout-component :title="$app['title'] ?? null">

    <div class="custom-container">
        <!-- Card Section -->
        <div class="custom-card-container">
            <div class="custom-card">
                <div class="custom-card-icon green">💲</div>
                <div>
                    <div>Pagu SKPD <small>({{ $jenis == 'bg' || $jenis == 'sg' ? 'OTSUS ' . strtoupper($jenis) : strtoupper($jenis) }})</small></div>
                    <div>Rp {{ formatIdr($opd->alokasi) }}</div>
                </div>
            </div>
            <div class="custom-card">
                <div class="custom-card-icon yellow">😊</div>
                <div>
                    <div>Program</div>
                    <div>{{ $jumlah_program }}</div>
                </div>
            </div>
            <div class="custom-card">
                <div class="custom-card-icon black">😊</div>
                <div>
                    <div>Kegiatan</div>
                    <div>{{ $jumlah_kegiatan }}</div>
                </div>
            </div>
            <div class="custom-card">
                <div class="custom-card-icon blue">😊</div>
                <div>
                    <div>Subkegiatan</div>
                    <div>{{ $jumlah_subkegiatan }}</div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="custom-table-container">
            <table class="custom-table">
                @foreach ($dataKlasBel as $itemKlasBel)
                    <tr>
                        <td>🔵 {{ $itemKlasBel->nama }}</td>
                        <td>{{ formatIdr($itemKlasBel->anggaran) }}</td>
                        <td><span class="custom-badge green">{{ formatIdr($itemKlasBel->persen * 100) }}%</span></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>RAP OTSUS {{ strtoupper($jenis) }} - {{ $opd->nama_opd }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size: 12px">
                    <thead class="table-primary">
                        <tr>
                            <th>URAIAN</th>
                            <th>INDIKATOR</th>
                            <th>TARGET</th>
                            <th>PAGU</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($opd->raps as $rap)
                            <tr>
                                <td>{{ $rap->text_subkegiatan }}</td>
                                <td>{{ $rap->indikator_subkegiatan }}</td>
                                <td>{{ $rap->kinerja_subkegiatan }}</td>
                                <td>{{ formatIdr($rap->anggaran) }}</td>
                                <td><button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#adminEditRapModal"><i class="fa-solid fa-pen-square"></i></button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('v1-1.admin.modal.admin-rap-skpd-modal')
    @include('v1-1.admin.rap.script-admin-rap')
</x-app-layout-component>
