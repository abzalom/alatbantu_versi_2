<x-app-layout-component :title="$app['title'] ?? null">

    <script>
        let dataLokasi = @json($lokasi);
        let listDanaLain = @json($dana_lain);
        let nomen_sikd = @json($nomen_sikd);
        let jenisDana = @json($jenis)
    </script>

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
            {{ session()->get('tahun') }}
            <div class="row">
                <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                    <button id="btn-new-rap" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addRapOpdModal"><i class="fa-solid fa-square-plus"></i> RAP</button>
                    <a href="/rap/{{ $jenis }}/renja/{{ $opd->id }}/form" class="btn btn-primary"><i class="fa-solid fa-square-plus"></i> RAP</a>
                    <div class="dropdown d-inline">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-download"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                <li><a class="dropdown-item" href="#"><i class="fa-solid fa-file-excel"></i> Excel</a></li>
                            </form>
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                <li><a class="dropdown-item" href="#"><i class="fa-regular fa-file-pdf"></i> pdf</a></li>
                            </form>
                        </ul>
                    </div>
                </div>
                <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="mb-3 col-sm-12 col-md-8 col-lg-8">
                            <div class="input-group">
                                <input type="text" class="form-control filter-input" data-table=".table-rap" placeholder="Filter..." aria-label="Filter renja" aria-describedby="filter-renja-rap">
                                <span class="input-group-text" id="filter-renja-rap"><i class="fa-solid fa-search"></i></span>
                            </div>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-4 col-lg-4 text-end">
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#arsipRapTerhapusModal"><i class="fa-solid fa-folder-open"></i> Arsip</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-rap" style="font-size: 12px">
                    <thead class="table-primary align-middle">
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>URAIAN</th>
                            <th>KLASIFIKASI BELANJA</th>
                            <th>INDIKATOR</th>
                            <th>TARGET</th>
                            <th>PAGU</th>
                            <th>LOKUS</th>
                            <th>STATUS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($opd->raps as $rap)
                            @if (!$rap->deleted_at)
                                <tr>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if (auth()->user()->hasRole('admin'))
                                                <form method="POST">
                                                    <button class="btn btn-sm btn-secondary" style="border-radius: 0.25rem 0 0 0.25rem;"><i class="fa-solid fa-circle-check"></i></button>
                                                </form>
                                                <button class="btn btn-sm btn-info"><i class="fa-solid fa-handshake"></i></button>
                                            @else
                                                <div data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail RAP">
                                                    <button class="btn btn-sm btn-secondary btn-detail-rap" data-bs-toggle="modal" data-bs-target="#detailRapOpdModal" data-rap='@json(['opd' => $opd->text, 'rap' => $rap])'><i class="fa-solid fa-eye"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $rap->text_subkegiatan }}</td>
                                    <td>{{ $rap->klasifikasi_belanja }}</td>
                                    <td>{{ $rap->indikator_subkegiatan }}</td>
                                    <td class="rap-kinerja-{{ $rap->id }}">{{ $rap->kinerja_subkegiatan }}</td>
                                    <td class="rap-anggaran-{{ $rap->id }}">{{ formatIdr($rap->anggaran) }}</td>
                                    <td class="rap-lokus-{{ $rap->id }}">
                                        @foreach (json_decode($rap->lokus, true) as $lokus)
                                            <span class="badge bg-secondary">{{ $lokus['kampung'] }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($rap->pembahasan == 'setujui')
                                            <span class="badge bg-secondary">Disetujui</span>
                                        @elseif ($rap->pembahasan == 'perbaikan')
                                            <span class="badge bg-warning">Perbaikan</span>
                                        @elseif ($rap->pembahasan == 'tolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-info">Belum dibahas</span>
                                        @endif
                                    <td>
                                        <div class="btn-group" role="group">
                                            {{-- <button class="btn btn-sm btn-primary btn-edit-rap" value="{{ $rap }}" data-bs-toggle="modal" data-bs-target="#editRapOpdModal"><i class="fa-solid fa-pen-square"></i></button> --}}
                                            <a href="/rap/{{ $jenis }}/renja/{{ $opd->id }}/form?edit={{ $rap->id }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-pen-square"></i></a>
                                            <button class="btn btn-sm btn-danger btn-delete-rap" value="{{ $rap->id }}"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('v1-1.rap.modal.modal-add-rap-opd')
    @include('v1-1.rap.modal.modal-edit-rap-opd')
    @include('v1-1.rap.modal.modal-arsip-terhapus-rap-opd')
    @include('v1-1.rap.modal.modal-detail-rap-opd')
    @include('v1-1.rap.script-rap')
</x-app-layout-component>
