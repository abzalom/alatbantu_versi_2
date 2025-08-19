<x-app-layout-component :title="$app['title'] ?? null">

    @if ($errors->any())
        <div class="alert alert-danger shadow" role="alert">
            <h4>Terjadi kesalahan!</h4>
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation me-2 fa-2xl text-danger fa-beat-fade"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

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
            <div class="row">
                <div class="mb-3 col-sm-12 col-md-12 col-lg-6">
                    <a href="/rap/{{ $jenis }}/renja/{{ $opd->id }}/form" class="btn btn-sm btn-primary text-nowrap me-2"><i class="fa-solid fa-square-plus"></i> RAP</a>
                    <button class="btn btn-sm btn-info text-nowrap me-2" data-bs-toggle="modal" data-bs-target="#arsipRapTerhapusModal"><i class="fa-solid fa-folder-open"></i> Arsip</button>
                    <div class="dropdown d-inline me-2">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-print"></i> Cetak
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/cetak/rap?list=semua&jenis={{ $jenis }}&opd={{ $opd->id }}" target="_blank"><i class="fa-solid fa-star"></i> Semua RAP</a></li>
                            <li><a class="dropdown-item" href="/cetak/rap?list=setujui&jenis={{ $jenis }}&opd={{ $opd->id }}" target="_blank"><i class="fa-solid fa-circle-check"></i> RAP hanya yang disetujui</a></li>
                            <li><a class="dropdown-item" href="/cetak/rap?list=tolak&jenis={{ $jenis }}&opd={{ $opd->id }}" target="_blank"><i class="fa-solid fa-circle-xmark"></i> RAP hanya yang ditolak</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mb-3 col-sm-12 col-md-12 col-lg-6">
                    <div class="input-group">
                        <input type="text" class="form-control filter-input" data-table=".table-rap" placeholder="Filter..." aria-label="Filter renja" aria-describedby="filter-renja-rap">
                        <span class="input-group-text" id="filter-renja-rap"><i class="fa-solid fa-search"></i></span>
                    </div>
                </div>
                {{-- <div class="mb-3 col-sm-12 col-md-7 col-lg-7">
                    <div class="row d-flex justify-content-center align-items-center">
                    </div>
                </div> --}}

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
                        @foreach ($opd->tag_otsus as $tagging)
                            @foreach ($tagging->raps as $rap)
                                @if (!$rap->deleted_at)
                                    <tr>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if ($rap->pembahasan && $rap->pembahasan !== 'perbaiki')
                                                    @if (auth()->user()->hasRole('admin'))
                                                        <form action="/rap/validasi" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id_rap" value="{{ $rap->id }}">
                                                            <input type="hidden" name="validasi" value="{{ $rap->validasi ? 0 : 1 }}">
                                                            @if ($rap->validasi)
                                                                <div data-bs-toggle="tooltip" data-bs-placement="top" title="Batalkan Validasi RAP">
                                                                    <button class="btn btn-sm btn-danger" style="border-radius: 0.25rem 0 0 0.25rem;"><i class="fa-solid fa-circle-xmark"></i></button>
                                                                </div>
                                                            @else
                                                                <div data-bs-toggle="tooltip" data-bs-placement="top" title="Validasi RAP">
                                                                    <button class="btn btn-sm btn-secondary" style="border-radius: 0.25rem 0 0 0.25rem;"><i class="fa-solid fa-circle-check"></i></button>
                                                                </div>
                                                            @endif
                                                        </form>
                                                    @endif
                                                @endif
                                                <div data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail RAP">
                                                    <button @if (auth()->user()->hasRole('admin')) style="border-radius: 0 0.25rem 0.25rem 0;" class="btn btn-sm btn-info btn-detail-rap" @else class="btn btn-sm btn-secondary btn-detail-rap" @endif data-bs-toggle="modal" data-bs-target="#detailRapOpdModal" data-rap='@json(['opd' => $opd->text, 'tagging' => $tagging, 'rap' => $rap])'>
                                                        @if (auth()->user()->hasRole('admin'))
                                                            <i class="fa-solid fa-handshake"></i>
                                                        @else
                                                            <i class="fa-solid fa-eye"></i>
                                                        @endif
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $rap->text_subkegiatan }} <span class="badge text-bg-info">{{ $rap->alias_dana }}</span>
                                        </td>
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
                                                <span class="badge text-bg-secondary">Disetujui</span>
                                            @elseif ($rap->pembahasan == 'perbaiki')
                                                <span class="badge text-bg-warning">Perbaiki</span>
                                            @elseif ($rap->pembahasan == 'tolak')
                                                <span class="badge text-bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge text-bg-info">Belum dibahas</span>
                                            @endif

                                            @if ($rap->validasi)
                                                <span class="badge text-bg-primary">Divalidasi</span>
                                            @endif
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                {{-- <button class="btn btn-sm btn-primary btn-edit-rap" value="{{ $rap }}" data-bs-toggle="modal" data-bs-target="#editRapOpdModal"><i class="fa-solid fa-pen-square"></i></button> --}}

                                                @if (!$rap->validasi)
                                                    @if (!$rap->pembahasan || !in_array($rap->pembahasan, ['setujui', 'tolak']))
                                                        <a href="/rap/{{ $jenis }}/renja/{{ $opd->id }}/form?edit={{ $rap->id }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-pen-square"></i></a>
                                                    @else
                                                        <small class="text-muted">Menunggu Validasi</small>
                                                    @endif
                                                @else
                                                    <i class="fa-solid fa-lock fa-2xl text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="Telah divalidasi! RAP dikunci!"></i>
                                                @endif

                                                @if (!$rap->pembahasan)
                                                    <button class="btn btn-sm btn-danger btn-delete-rap" value="{{ $rap->id }}"><i class="fa-solid fa-trash"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- @include('v1-1.rap.modal.modal-add-rap-opd') --}}
    @include('v1-1.rap.modal.modal-edit-rap-opd')
    @include('v1-1.rap.modal.modal-arsip-terhapus-rap-opd')
    @include('v1-1.rap.modal.modal-detail-rap-opd')
    @include('v1-1.rap.script-rap')
</x-app-layout-component>
