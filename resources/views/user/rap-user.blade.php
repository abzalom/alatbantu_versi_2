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
                    <div class="table-responsive">
                        <table class="table table-sm align-middle" style="width: 25%">
                            @php
                                $jumlahKlasBel = 0;
                            @endphp
                            @foreach ($klasBel as $itemKlasBel)
                                @php
                                    $jumlahKlasBel += $itemKlasBel['pagu_alokasi'];
                                @endphp
                                <tr>
                                    <td class="text-muted text-nowrap">{{ $itemKlasBel['klasifikasi_belanja'] }}</td>
                                    <td class="text-muted">:</td>
                                    <td class="text-muted text-end">{{ formatIdr($itemKlasBel['pagu_alokasi'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="text-muted text-end text-nowrap">Jumlah</th>
                                <th class="text-muted">:</th>
                                <th class="text-muted text-end">{{ formatIdr($jumlahKlasBel, 2) }}</th>
                            </tr>
                        </table>
                    </div>
                    <div class="row mb-3 mx-2">
                        @foreach ($sumberdanas as $itemDana)
                            <div class="col-sm-12 col-md-6 col-lg-4 me-3">
                                <div class="row alert text-bg-light border-info">
                                    <strong>{{ $itemDana['nama_dana'] }}</strong>
                                    <strong>Rp. {{ formatIdr($itemDana['pagu_dana'], 2) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- <div class="mb-3 mt-3">
                        <button id="test-token" class="btn btn-danger">Test Token</button>
                    </div> --}}

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped" style="font-size: 90%">
                            <thead class="table-dark align-middle">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th></th>
                                    <th>KLASIFIKASI BELANJA</th>
                                    <th>SUB KEGIATAN</th>
                                    <th>INDIKATOR</th>
                                    <th>TARGET</th>
                                    <th>PAGU</th>
                                    <th>LOKASI</th>
                                    <th>DATA PENDUKUNG</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data->raps as $rap)
                                    @php
                                        $target = $rap->vol_subkeg . ' ' . $rap->satuan_subkegiatan;
                                        $kakFile = $rap->file_path && $rap->file_kak_name ? $rap->file_path . $rap->file_kak_name : null;
                                        $rabFile = $rap->file_path && $rap->file_rab_name ? $rap->file_path . $rap->file_rab_name : null;
                                        $pendukung1File = $rap->file_path && $rap->file_pendukung1_name ? $rap->file_path . $rap->file_rab_name : null;
                                        $pendukung2File = $rap->file_path && $rap->file_pendukung2_name ? $rap->file_path . $rap->file_rab_name : null;
                                        $pendukung3File = $rap->file_path && $rap->file_pendukung3_name ? $rap->file_path . $rap->file_rab_name : null;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-primary btn-user-edit-rap text-nowrap" value="{{ $rap->id }}" data-bs-toggle="modal" data-bs-target="#userEditRapModal">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit RAP
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ $rap->klasifikasi_belanja }}</td>
                                        <td>{{ $rap->text_subkegiatan }}</td>
                                        <td>{{ $rap->indikator_subkegiatan }}</td>
                                        <td>{{ $target }}</td>
                                        <td class="text-end">{{ formatIdr($rap->anggaran, 2) }}</td>
                                        <td>
                                            @foreach (json_decode($rap->lokus, true) as $lokus)
                                                {{ $lokus['kampung'] }},
                                            @endforeach
                                        </td>

                                        <td class="text-nowrap">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-start align-items-center" style="background: none">
                                                    @if ($kakFile && Storage::disk('public')->exists($kakFile))
                                                        <div class="me-3">
                                                            <i class="fa-solid fa-circle-check fa-lg" style="color: #566ef5;"></i>
                                                            <a href="{{ route('view.file', ['path' => $rap->file_path, 'name' => $rap->file_kak_name]) }}" target="_blank">KAK</a>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete-file" data-subkegiatan="{{ $rap->text_subkegiatan }}" data-file="Kerangka Acuan Kerja (KAK)" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .65rem;" data-id_rap="{{ $rap->id }}" data-filename="file_kak_name" data-bs-toggle="modal" data-bs-target="#konfirmasiDeleteFileRapModal"><i class="fa-solid fa-trash"></i></button>
                                                    @else
                                                        <div>
                                                            <i class="fa-solid fa-circle-xmark fa-lg" style="color: #ff5252;"></i>
                                                            KAK
                                                        </div>
                                                    @endif
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-start align-items-center" style="background: none">
                                                    @if ($rabFile && Storage::disk('public')->exists($rabFile))
                                                        <div class="me-3">
                                                            <i class="fa-solid fa-circle-check fa-lg" style="color: #566ef5;"></i>
                                                            <a href="{{ route('view.file', ['path' => $rap->file_path, 'name' => $rap->file_rab_name]) }}" target="_blank">RAB</a>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete-file" data-subkegiatan="{{ $rap->text_subkegiatan }}" data-file="Rincian Anggaran Belanja (RAB)" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .65rem;" data-id_rap="{{ $rap->id }}" data-filename="file_rab_name" data-bs-toggle="modal" data-bs-target="#konfirmasiDeleteFileRapModal"><i class="fa-solid fa-trash"></i></button>
                                                    @else
                                                        <div>
                                                            <i class="fa-solid fa-circle-xmark fa-lg" style="color: #ff5252;"></i>
                                                            RAB
                                                        </div>
                                                    @endif
                                                </li>
                                                @if ($pendukung1File && Storage::disk('public')->exists($pendukung1File))
                                                    <li class="list-group-item d-flex justify-content-between align-items-start align-items-center" style="background: none">
                                                        <div class="me-3">
                                                            <i class="fa-solid fa-circle-check fa-lg" style="color: #566ef5;"></i>
                                                            <a href="{{ route('view.file', ['path' => $rap->file_path, 'name' => $rap->file_pendukung1_name]) }}" target="_blank">Pendukung1</a>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete-file" data-subkegiatan="{{ $rap->text_subkegiatan }}" data-file="File Pendukung 1" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .65rem;" data-id_rap="{{ $rap->id }}" data-filename="file_pendukung1_name" data-bs-toggle="modal" data-bs-target="#konfirmasiDeleteFileRapModal"><i class="fa-solid fa-trash"></i></button>
                                                    </li>
                                                @endif
                                                @if ($pendukung2File && Storage::disk('public')->exists($pendukung2File))
                                                    <li class="list-group-item d-flex justify-content-between align-items-start align-items-center" style="background: none">
                                                        <div class="me-3">
                                                            <i class="fa-solid fa-circle-check fa-lg" style="color: #566ef5;"></i>
                                                            <a href="{{ route('view.file', ['path' => $rap->file_path, 'name' => $rap->file_pendukung2_name]) }}" target="_blank">Pendukung2</a>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete-file" data-subkegiatan="{{ $rap->text_subkegiatan }}" data-file="File Pendukung 2" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .65rem;" data-id_rap="{{ $rap->id }}" data-filename="file_pendukung2_name" data-bs-toggle="modal" data-bs-target="#konfirmasiDeleteFileRapModal"><i class="fa-solid fa-trash"></i></button>
                                                    </li>
                                                @endif
                                                @if ($pendukung3File && Storage::disk('public')->exists($pendukung3File))
                                                    <li class="list-group-item d-flex justify-content-between align-items-start align-items-center" style="background: none">
                                                        <div class="me-3">
                                                            <i class="fa-solid fa-circle-check fa-lg" style="color: #566ef5;"></i>
                                                            <a href="{{ route('view.file', ['path' => $rap->file_path, 'name' => $rap->file_pendukung3_name]) }}" target="_blank">Pendukung3</a>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete-file" data-subkegiatan="{{ $rap->text_subkegiatan }}" data-file="File Pendukung 3" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .65rem;" data-id_rap="{{ $rap->id }}" data-filename="file_pendukung3_name" data-bs-toggle="modal" data-bs-target="#konfirmasiDeleteFileRapModal"><i class="fa-solid fa-trash"></i></button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-secondary btn-upluad-user-rap text-nowrap" value="{{ $rap->id }}" data-bs-toggle="modal" data-bs-target="#userUploadFileModal">
                                                    <i class="fa-regular fa-folder-open"></i> Upload File
                                                </button>
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

    @include('user.modal-user-edit-rap')
    @include('user.modal-user-rap-upload-files')
    @include('user.modal-user-rap-konfirmasi-delete-file')
    @include('user.script-user-rap')
</x-app-layout-component>
