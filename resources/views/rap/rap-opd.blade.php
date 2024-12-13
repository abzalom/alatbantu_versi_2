<x-app-layout-component :title="$app['title'] ?? null">

    @if (session('failures'))
        {{-- @dump(session('failures')) --}}
        <div class="row">
            <div class="alert alert-danger col-lg">
                <h5>Terjadi kesalahan berikut:</h5>
                <ul>
                    @foreach (session('failures') as $failure)
                        <li class="mb-4">
                            Pada baris ke {{ $failure['row'] }} sub kegiatan => {{ $failure['values']['text_subkegiatan'] }} :
                            <ul>
                                @foreach ($failure['errors'] as $error)
                                    <li class="mb-3">
                                        <strong>Kolom [{{ $error['attribute'] }}]</strong> => {{ $error['message'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <hr>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-6">
            <table class="table table-bordered table-striped">
                <thead class="table-secondary align-middle">
                    <tr>
                        <th>Klasifikasi Belanja</th>
                        <th>Pagu Inputan</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach ($klasifikasi_belanja as $klasifikasi)
                        <tr>
                            <td>{{ $klasifikasi['klasifikasi_belanja'] }}</td>
                            <td class="text-end">{{ formatIdr($klasifikasi['total_anggaran'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-4">
            <table class="table table-bordered table-striped">
                <thead class="table-secondary align-middle">
                    <tr>
                        <th>Uraian</th>
                        <th>Pagu Inputan</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    <tr>
                        <td>Otsus 1%</td>
                        <td class="text-end">{{ formatIdr($opd->alokasi_bg, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Otsus 1,25%</td>
                        <td class="text-end">{{ formatIdr($opd->alokasi_sg, 2) }}</td>
                    </tr>
                    <tr>
                        <td>DTI</td>
                        <td class="text-end">{{ formatIdr($opd->alokasi_dti, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot class="table-secondary align-middle">
                    <tr>
                        <th class="text-end">Total Inputan Pagu</th>
                        <th class="text-end">{{ formatIdr($opd->pagu, 2) }}</th>
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
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <a href="/rap/indikator?opd={{ $opd->id }}" class="btn btn-info me-3 mb-3"><i class="fa-solid fa-list-ol"></i> Indikator</a>
                            <a href="/rap/opd/form-subkegiatan?opd={{ $opd->id }}" class="btn btn-primary me-3 mb-3"><i class="fa-solid fa-square-plus"></i> Tambah Subkegiatan</a>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#rapUploadSubkegiatanModal">
                                <i class="fa-solid fa-file-arrow-up"></i> Upload
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped" style="font-size: 85%">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th></th>
                                    <th>Target Aktifitas Utama</th>
                                    <th>Klasifikasi Belanja</th>
                                    <th>Sub Kegiatan</th>
                                    <th>Target Kinerja</th>
                                    <th>Alokasi Pagu</th>
                                    <th>Lokasi Fokus</th>
                                    <th>Sumberdana</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="rap-show">
                                @php
                                    $no = 1;
                                @endphp
                                @if ($opd->raps->count() == 0)
                                    <tr id="rap-data-not-found">
                                        <td colspan="10" class="text-center">
                                            <h4>Data Not Found!</h4>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($opd->raps as $rap)
                                        @php
                                            $kakFile = $rap->file_path && $rap->file_kak_name ? $rap->file_path . $rap->file_kak_name : null;
                                            $rabFile = $rap->file_path && $rap->file_rab_name ? $rap->file_path . $rap->file_rab_name : null;
                                            $pendukung1File = $rap->file_path && $rap->file_pendukung1_name ? $rap->file_path . $rap->file_rab_name : null;
                                            $pendukung2File = $rap->file_path && $rap->file_pendukung2_name ? $rap->file_path . $rap->file_rab_name : null;
                                            $pendukung3File = $rap->file_path && $rap->file_pendukung3_name ? $rap->file_path . $rap->file_rab_name : null;
                                        @endphp
                                        <tr id="rap-show-id-{{ $rap->id }}">
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary btn-view-detail-rap" value="{{ $rap->id }}" data-bs-toggle="modal" data-bs-target="#detailRapViewModal"><i class="fa-solid fa-list"></i></button>
                                            </td>
                                            <td class="text-nowrap">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex justify-content-between align-items-start align-items-center" style="background: none">
                                                        @if ($kakFile && Storage::disk('public')->exists($kakFile))
                                                            <div class="me-3">
                                                                <i class="fa-solid fa-circle-check fa-lg" style="color: #566ef5;"></i>
                                                                <a href="{{ route('view.file', ['path' => $rap->file_path, 'name' => $rap->file_kak_name]) }}" target="_blank">KAK</a>
                                                            </div>
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
                                                        </li>
                                                    @endif
                                                    @if ($pendukung2File && Storage::disk('public')->exists($pendukung2File))
                                                        <li class="list-group-item d-flex justify-content-between align-items-start align-items-center" style="background: none">
                                                            <div class="me-3">
                                                                <i class="fa-solid fa-circle-check fa-lg" style="color: #566ef5;"></i>
                                                                <a href="{{ route('view.file', ['path' => $rap->file_path, 'name' => $rap->file_pendukung2_name]) }}" target="_blank">Pendukung2</a>
                                                            </div>
                                                        </li>
                                                    @endif
                                                    @if ($pendukung3File && Storage::disk('public')->exists($pendukung3File))
                                                        <li class="list-group-item d-flex justify-content-between align-items-start align-items-center" style="background: none">
                                                            <div class="me-3">
                                                                <i class="fa-solid fa-circle-check fa-lg" style="color: #566ef5;"></i>
                                                                <a href="{{ route('view.file', ['path' => $rap->file_path, 'name' => $rap->file_pendukung3_name]) }}" target="_blank">Pendukung3</a>
                                                            </div>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </td>
                                            <td id="show-rap-target-atifitas-id-{{ $rap->id }}" class="remove-bg-subkegiatan">
                                                <button class="btn btn-sm btn-primary me-2 copy-targetakitifiats" data-targetakitifiats="{{ $rap->target_aktifitas->kode_target_aktifitas }}" data-id="{{ $rap->id }}">
                                                    <i class="fa-regular fa-clipboard"></i>
                                                </button>
                                                {{ $rap->target_aktifitas->text }}
                                            </td>
                                            <td>{{ $rap->klasifikasi_belanja }}</td>
                                            <td id="show-rap-subkegiatan-id-{{ $rap->id }}" class="remove-bg-subkegiatan">
                                                <button class="btn btn-sm btn-primary me-2 copy-subkegiatan" data-subkegiatan="{{ $rap->kode_subkegiatan }}" data-id="{{ $rap->id }}">
                                                    <i class="fa-regular fa-clipboard"></i>
                                                </button>
                                                <span class="user-select-all">{{ $rap->kode_subkegiatan }}</span>
                                                <span class="user-select-all">{{ $rap->nama_subkegiatan }}</span>
                                            </td>
                                            {{-- <td>{{ $rap->indikator_subkegiatan }}</td> --}}
                                            <td id="show-rap-vol_subkeg-id-{{ $rap->id }}">
                                                <span class="user-select-all">{{ formatIdr($rap->vol_subkeg, 2) }}</span>
                                                {{ $rap->satuan_subkegiatan }}
                                            </td>
                                            <td id="show-rap-anggaran-id-{{ $rap->id }}" class="remove-bg-anggaran">
                                                <div class="d-flex">
                                                    <button class="btn btn-sm btn-primary me-2 copy-anggaran" data-anggaran="{{ $rap->anggaran }}" data-id="{{ $rap->id }}">
                                                        <i class="fa-regular fa-clipboard"></i>
                                                    </button>
                                                    {{ formatIdr($rap->anggaran, 2) }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $jsonData = json_decode($rap->lokus, true);
                                                @endphp
                                                @if (is_array($jsonData) && !empty($jsonData))
                                                    @foreach ($jsonData as $lokusItem)
                                                        @if ($loop->last)
                                                            <span class="user-select-all">{{ $lokusItem['kampung'] }}</span>
                                                        @else
                                                            <span class="user-select-all">{{ $lokusItem['kampung'] }},</span>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>

                                            <td id="show-rap-sumberdana-id-{{ $rap->id }}" class="remove-bg-sumberdana">
                                                @php
                                                    $copyDana = '';
                                                    if ($rap->sumberdana == 'Otsus 1,25%') {
                                                        $copyDana = 'Dana Otonomi Khusus Kabupaten/Kota pada Provinsi Papua [SPESIFIC GRANT]';
                                                    }
                                                    if ($rap->sumberdana == 'Otsus 1%') {
                                                        $copyDana = '1.2.01.03.01.0002 Dana Otonomi Khusus-Kabupaten/Kota pada Provinsi Papua-Umum [BLOCK GRANT]';
                                                    }
                                                    if ($rap->sumberdana == 'DTI') {
                                                        $copyDana = '2.2.01.03.02.0002 Dana Tambahan Otonomi Khusus/Dana Tambahan Infrastruktur Kabupaten/Kota Papua [SPESIFIC GRANT]';
                                                    }
                                                @endphp

                                                <div id="show-rap-sumberdana-text-id-{{ $rap->id }}">
                                                    <button class="btn btn-sm btn-primary me-2 copy-sumberdana" data-sumberdana="{{ $copyDana }}" data-id="{{ $rap->id }}">
                                                        <i class="fa-regular fa-clipboard"></i>
                                                    </button>
                                                    {{ $rap->sumberdana }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <div data-bs-toggle="tooltip" data-bs-title="Edit Rap">
                                                        <button class="btn btn-sm btn-primary btn-edit-rap" value="{{ $rap->id }}" data-bs-toggle="modal" data-bs-target="#editRapModal"><i class="fa-solid fa-pen-to-square"></i></button>
                                                    </div>
                                                    <div data-bs-toggle="tooltip" data-bs-title="Hapus Rap" onmouseover="$(this).find('i').addClass('fa-beat')" onmouseout="$(this).find('i').removeClass('fa-beat')">
                                                        <button class="btn btn-sm btn-danger btn-delete-rap" value="{{ $rap->id }}"><i class="fa-solid fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('rap.rap-modal.rap-modal-edit')
    @include('rap.rap-modal.rap-modal-upload-subkegiatan')
    @include('rap.rap-modal.rap-modal-view-files')
    @include('rap.rap-modal.rap-modal-detail-rap')
    @include('rap.rap-script')
</x-app-layout-component>
