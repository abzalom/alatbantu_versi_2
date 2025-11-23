<x-app-layout-component :title="$app['title'] ?? null">

    <style>
        .tim-opd-item.dragging :where(.tim-opd-item-content, i) {
            opacity: 0;
        }
    </style>

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
        let jenisDana = @json($jenis);
        let opdTimPembahas = @json($opd->tim_pembahas);
        let timPembahas = @json($tim_pembahas);
    </script>

    <div class="custom-container">
        <!-- Card Section -->
        <div class="custom-card-container">
            <div class="custom-card">
                <div class="custom-card-icon green">💲</div>
                <div>
                    <div>Pagu OPD <small>({{ $jenis == 'bg' || $jenis == 'sg' ? 'OTSUS ' . strtoupper($jenis) : strtoupper($jenis) }})</small></div>
                    <div>Rp {{ formatIdr($opd->pagu ? $opd->pagu->$jenis : '0') }}</div>
                </div>
            </div>
            <div class="custom-card">
                <div class="custom-card-icon green">💲</div>
                <div>
                    <div>Total RAP <small>({{ $jenis == 'bg' || $jenis == 'sg' ? 'OTSUS ' . strtoupper($jenis) : strtoupper($jenis) }})</small></div>
                    <div>Rp {{ formatIdr($opd->alokasi) }}</div>
                </div>
            </div>
            <div class="custom-card">
                <div class="custom-card-icon black">📒</div>
                <div>
                    <div>Kegiatan</div>
                    <div>{{ $jumlah_kegiatan }}</div>
                </div>
            </div>
            <div class="custom-card">
                <div class="custom-card-icon blue">🧾</div>
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
            <div class="d-flex align-items-center">
                <div class="col-10">
                    <h5>RAP OTSUS {{ strtoupper($jenis) }} - {{ $opd->nama_opd }}</h5>
                </div>
                <div class="col-auto text-end flex-grow-1">
                    <a href="/rap/{{ $jenis }}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="mb-3 col-sm-12 col-md-12 col-lg-6">
                    @if (input_rap() || auth()->user()->hasRole('admin'))
                        <a href="/rap/{{ $jenis }}/renja/{{ $opd->id }}/form" class="btn btn-sm btn-primary text-nowrap me-2">
                            <i class="fa-solid fa-square-plus"></i> RAP
                        </a>
                    @endif
                    @if (auth()->user()->hasRole('admin'))
                        <button id="btnTimPembahasRapOpd" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalTimPembahasRapOpd"><i class="fa-solid fa-users"></i> Tim Pembahasan</button>
                        <button class="btn btn-sm btn-info text-nowrap me-2" data-bs-toggle="modal" data-bs-target="#arsipRapTerhapusModal"><i class="fa-solid fa-folder-open"></i> Arsip</button>
                        <a class="btn btn-sm btn-secondary" href="/cetak/rap?list=semua&jenis={{ $jenis }}&opd={{ $opd->id }}" target="_blank"><i class="fa-solid fa-file-pdf"></i> Berita Acara</a>
                    @endif
                </div>
                <div class="mb-3 col-sm-12 col-md-12 col-lg-4">
                    <div class="input-group">
                        <input type="text" class="form-control filter-input" data-table=".table-rap" placeholder="Filter..." aria-label="Filter renja" aria-describedby="filter-renja-rap">
                        <span class="input-group-text" id="filter-renja-rap"><i class="fa-solid fa-search"></i></span>
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
                        @foreach ($opd->tag_otsus as $tagging)
                            @foreach ($tagging->raps as $rap)
                                @if (!$rap->deleted_at)
                                    <tr>
                                        <td>
                                            <div class="btn-group" role="group">
                                                {{-- <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Lihat Detail RAP (Admin)">
                                                    <button style="border-radius: 0 0.25rem 0.25rem 0;" class="btn btn-sm btn-success btn-detail-rap" data-bs-toggle="modal" data-bs-target="#detailRapOpdModal" data-rap='@json(['opd' => $opd->text, 'tagging' => $tagging, 'rap' => $rap])'>
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                </div> --}}
                                                @if (auth()->user()->hasRole('admin') && $rap->kirim)
                                                    @if ($rap->pembahasan && $rap->pembahasan !== 'perbaiki' && auth()->user()->hasRole('admin'))
                                                        <form action="/rap/validasi" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="id_rap" value="{{ $rap->id }}">
                                                            <input type="hidden" name="validasi" value="{{ $rap->validasi ? 0 : 1 }}">
                                                            <button class="btn btn-sm {{ $rap->validasi ? 'btn-danger' : 'btn-secondary' }}" style="border-radius: 0.25rem 0 0 0.25rem;" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $rap->validasi ? 'Batalkan Validasi RAP' : 'Validasi RAP' }}">
                                                                <i class="fa-solid {{ $rap->validasi ? 'fa-circle-xmark' : 'fa-circle-check' }}"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if (auth()->user()->hasRole('admin'))
                                                        @if (!$rap->validasi)
                                                            <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Bahas RAP (Admin)">
                                                                <button style="border-radius: 0 0.25rem 0.25rem 0;" class="btn btn-sm btn-info btn-detail-rap" data-bs-toggle="modal" data-bs-target="#detailRapOpdModal" data-rap='@json(['opd' => $opd->text, 'tagging' => $tagging, 'rap' => $rap])'>
                                                                    <i class="fa-solid fa-handshake"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Lihat Detail RAP">
                                                            <button class="btn btn-sm btn-secondary btn-detail-rap" data-bs-toggle="modal" data-bs-target="#detailRapOpdModal" data-rap='@json(['opd' => $opd->text, 'tagging' => $tagging, 'rap' => $rap])'>
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $rap->text_subkegiatan }} <span class="badge text-bg-info">{{ $rap->alias_dana }}</span>
                                            <br>
                                            <small class="text-muted">
                                                ({{ \Carbon\Carbon::parse($rap->created_at)->diffForHumans() }})
                                            </small>
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
                                            @if (auth()->user()->hasRole('user') && !$rap->kirim)
                                                <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Kirim RAP untuk dibahas">
                                                    <form action="/rap/kirim" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id_rap" value="{{ $rap->id }}">
                                                        <button class="btn btn-outline-secondary text-nowrap" style="width: auto; height: 24px; padding: 2px; font-size: 10px;">
                                                            <i class="fa-solid fa-paper-plane"></i> Kirim
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                            @if (auth()->user()->hasRole('user') && $rap->kirim)
                                                <span class="badge text-bg-primary">Terkirim</span>
                                                <br>
                                                @if ($rap->pembahasan == 'setujui')
                                                    <span class="badge text-bg-secondary">Disetujui</span>
                                                @elseif ($rap->pembahasan == 'tolak')
                                                    <span class="badge text-bg-danger">Ditolak</span>
                                                @elseif ($rap->pembahasan == 'perbaiki')
                                                    <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Kirim perbaikan RAP untuk dibahas kembali">
                                                        <form action="/rap/kirim" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id_rap" value="{{ $rap->id }}">
                                                            <button class="btn btn-outline-secondary text-nowrap" style="width: auto; height: 24px; padding: 2px; font-size: 10px;">
                                                                <i class="fa-solid fa-paper-plane"></i> Kirim
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="badge text-bg-info">Belum dibahas</span>
                                                @endif
                                            @endif

                                            @if ($rap->validasi)
                                                <span class="badge text-bg-primary">Divalidasi</span>
                                            @endif
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @if (auth()->user()->hasRole('admin') || !$rap->kirim)
                                                    <a href="/rap/{{ $jenis }}/renja/{{ $opd->id }}/form?edit={{ $rap->id }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-pen-square"></i></a>
                                                @endif
                                                {{-- @if (auth()->user()->hasRole('user') && $rap->kirim && $rap->validasi)
                                                    <i class="fa-solid fa-lock fa-2xl text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="Telah dibahas dan divalidasi! RAP dikunci!"></i>
                                                @else
                                                    @if (input_rap() || auth()->user()->hasRole('admin'))
                                                        @if (!$rap->pembahasan || !in_array($rap->pembahasan, ['setujui', 'tolak']))
                                                            <a href="/rap/{{ $jenis }}/renja/{{ $opd->id }}/form?edit={{ $rap->id }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-pen-square"></i></a>
                                                        @else
                                                            <small class="text-muted">Menunggu Validasi</small>
                                                        @endif
                                                        @if (!$rap->pembahasan)
                                                            <button class="btn btn-sm btn-danger btn-delete-rap" value="{{ $rap->id }}"><i class="fa-solid fa-trash"></i></button>
                                                        @endif
                                                    @endif
                                                @endif --}}
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

    @include('v1-1.rap.modal.modal-tim-pembahas-rap-opd')
    @include('v1-1.rap.modal.modal-arsip-terhapus-rap-opd')
    @include('v1-1.rap.modal.modal-detail-rap-opd')
    @include('v1-1.rap.script-rap')
</x-app-layout-component>
