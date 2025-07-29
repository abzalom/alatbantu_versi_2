<x-app-layout-component :title="$app['title']">

    <script>
        let bahas = {
            'status': false,
            'opd_tag_otsus_id': []
        };
        let validasi = {
            'status': false,
            'opd_tag_otsus_id': []
        };
    </script>

    @php
        $admin = auth()->user()->hasRole('admin') ? true : false;
    @endphp


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
            <div class="mb-3 d-flex justify-content-between">
                <h5>{{ $opd->text }}</h5>
                @if (auth()->user()->hasRole('admin'))
                    <div class="d-flex justify-content-end align-items-center gap-2">
                        <div id="show-btn-setujui-all" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Bahas semua" style="display: none">
                            <button id="btn-setujui-all" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bahasRakortekRapppAllModal"><i class="fa-solid fa-handshake"></i></button>
                        </div>
                        <div id="show-btn-validasi-all" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Validasi semua yang sudah dibahas & setujui" style="display: none">
                            <button id="btn-validasi-all" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#validasiRakortekRapppAllModal"><i class="fa-solid fa-circle-check"></i></button>
                        </div>
                    </div>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-stripped">
                    <thead class="align-middle table-{{ $admin ? 'primary' : 'dark' }}">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">KODE</th>
                            <th scope="col">URAIAN</th>
                            <th scope="col">SATUAN</th>
                            <th scope="col">VOLUME</th>
                            <th scope="col">SUMBER DANA</th>
                            <th scope="col">STATUS</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($data as $tema)
                            <tr>
                                <th>Tema Pembangunan</th>
                                <th>{{ $tema['kode_tema'] }}</th>
                                <th colspan="6">{{ $tema['uraian_tema'] }}</th>
                            </tr>
                            @foreach ($tema['programs'] as $program)
                                <tr>
                                    <th>Program Prioritas</th>
                                    <th>{{ $program['kode_program'] }}</th>
                                    <th colspan="6">{{ $program['uraian_program'] }}</th>
                                </tr>
                                @foreach ($program['keluarans'] as $keluaran)
                                    <tr>
                                        <th>Target Keluaran Strategis</th>
                                        <th>{{ $keluaran['kode_keluaran'] }}</th>
                                        <th colspan="6">{{ $keluaran['uraian_keluaran'] }}</th>
                                    </tr>
                                    @foreach ($keluaran['aktifitas'] as $aktifitas)
                                        <tr>
                                            <th>Aktifitas Utama</th>
                                            <th>{{ $aktifitas['kode_aktifitas'] }}</th>
                                            <th colspan="6">{{ $aktifitas['uraian_aktifitas'] }}</th>
                                        </tr>
                                        @foreach ($aktifitas['target_aktifitas'] as $target_aktifitas)
                                            @if (!$target_aktifitas['rappp']['pembahasan'] || $target_aktifitas['rappp']['pembahasan'] !== 'setujui')
                                                <script>
                                                    bahas['status'] = true;
                                                    bahas['opd_tag_otsus_id'].push({{ $target_aktifitas['rappp']['id'] }});
                                                </script>
                                            @endif
                                            @if ($target_aktifitas['rappp']['pembahasan'] && $target_aktifitas['rappp']['pembahasan'] == 'setujui' && !$target_aktifitas['rappp']['validasi'])
                                                <script>
                                                    validasi['status'] = true;
                                                    validasi['opd_tag_otsus_id'].push({{ $target_aktifitas['rappp']['id'] }});
                                                </script>
                                            @endif
                                            <tr>
                                                <td>Target Aktifitas Utama</td>
                                                <td>{{ $target_aktifitas['kode_target_aktifitas'] }}</td>
                                                <td>
                                                    {{ $target_aktifitas['uraian_target_aktifitas'] }}
                                                    @if ($target_aktifitas['rappp']['validasi'])
                                                        <i class="fa-solid fa-circle-check text-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Sudah divalidasi" style="font-size: 16px"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $target_aktifitas['rappp']['satuan'] }}</td>
                                                <td>{{ $target_aktifitas['rappp']['volume'] }}</td>
                                                <td>{{ $target_aktifitas['rappp']['sumberdana'] }}</td>
                                                <td>
                                                    @if ($target_aktifitas['rappp']['pembahasan'] == 'setujui')
                                                        <span class="badge bg-success">Disetujui {{ $target_aktifitas['rappp']['validasi'] ? '& divalidasi' : '' }}</span>
                                                    @elseif ($target_aktifitas['rappp']['pembahasan'] == 'perbaikan')
                                                        <span class="badge bg-warning">Perbaikan</span>
                                                    @elseif ($target_aktifitas['rappp']['pembahasan'] == 'tolak')
                                                        <span class="badge bg-danger">Ditolak {{ $target_aktifitas['rappp']['validasi'] ? '& divalidasi' : '' }}</span>
                                                    @else
                                                        <span class="badge bg-info">Belum dibahas</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                                        @if ($admin)
                                                            @if (!$target_aktifitas['rappp']['validasi'])
                                                                <div data-bs-toggle="tooltip" data-bs-placement="top" title="Bahas">
                                                                    <button class="btn btn-sm btn-primary btn-bahas-rakor-rappp" data-bs-toggle="modal" data-bs-target="#bahasRakorRapppModal" data-target_aktifitas='@json($target_aktifitas)'>
                                                                        <i class="fa-solid fa-handshake"></i>
                                                                    </button>
                                                                </div>
                                                                @if ($target_aktifitas['rappp']['pembahasan'] && $target_aktifitas['rappp']['pembahasan'] !== 'perbaikan')
                                                                    <form action="/pembahasan/rakortek/rappp/opd/validasi" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="opd_tag_otsus_id" value="{{ $target_aktifitas['rappp']['id'] }}">
                                                                        <button class="btn btn-sm btn-secondary btn-validasi-rappp" data-bs-toggle="tooltip" data-bs-placement="top" title="Validasi">
                                                                            <i class="fa-solid fa-circle-check"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @else
                                                                <form action="/pembahasan/rakortek/rappp/opd/validasi" method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="opd_tag_otsus_id" value="{{ $target_aktifitas['rappp']['id'] }}">
                                                                    <button class="btn btn-sm btn-danger btn-validasi-rappp" data-bs-toggle="tooltip" data-bs-placement="top" title="Batalkan Validasi">
                                                                        <i class="fa-solid fa-circle-xmark"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @else
                                                            <div data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat catatan">
                                                                <button class="btn btn-sm btn-secondary btn-bahas-rappp-lihat_catatan" data-bs-toggle="modal" data-bs-target="#lihatCatatanRakorRapppModal" data-rappp='@json($target_aktifitas)'>
                                                                    <i class="fa-solid fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($admin)
        @include('v1-1.rakortek.pembahasan.rappp.modal.modal-rakortek-pembahasan-rappp-opd')
        @include('v1-1.rakortek.pembahasan.rappp.modal.modal-rakortek-pembahasan-rappp-setujui-all')
        @include('v1-1.rakortek.pembahasan.rappp.modal.modal-rakortek-pembahasan-rappp-validasi-all')
    @else
        @include('v1-1.rakortek.pembahasan.rappp.modal.modal-rakortek-pembahasan-rappp-catatan')
    @endif

    @include('v1-1.rakortek.pembahasan.rappp.script-rakortek-pembahasan-rappp')
</x-app-layout-component>
