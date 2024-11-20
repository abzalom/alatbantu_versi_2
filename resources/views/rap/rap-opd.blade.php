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
                        <table class="table table-bordered table-striped" style="font-size: 90%">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Target Aktifitas Utama</th>
                                    <th>Sub Kegiatan</th>
                                    <th>Klasifikasi Belanja</th>
                                    <th>Indikator</th>
                                    <th>Satuan</th>
                                    <th>Target</th>
                                    <th>Alokasi Pagu</th>
                                    <th>Lokasi Fokus</th>
                                    <th>Sumberdana</th>
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
                                        <tr id="rap-show-id-{{ $rap->id }}">
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $rap->target_aktifitas->text }}</td>
                                            <td id="show-rap-subkegiatan-id-{{ $rap->id }}" class="remove-bg-subkegiatan">
                                                <button class="btn btn-sm btn-primary me-2 copy-subkegiatan" data-subkegiatan="{{ $rap->kode_subkegiatan }}" data-id="{{ $rap->id }}">
                                                    <i class="fa-regular fa-clipboard"></i>
                                                </button>
                                                {{ $rap->text_subkegiatan }}
                                            </td>
                                            <td>{{ $rap->klasifikasi_belanja }}</td>
                                            <td>{{ $rap->indikator_subkegiatan }}</td>
                                            <td>{{ $rap->satuan_subkegiatan }}</td>
                                            <td id="show-rap-vol_subkeg-id-{{ $rap->id }}">{{ formatIdr($rap->vol_subkeg, 2) }}</td>
                                            <td id="show-rap-anggaran-id-{{ $rap->id }}" class="remove-bg-anggaran">
                                                <div class="d-flex">
                                                    <button class="btn btn-sm btn-primary me-2 copy-anggaran" data-anggaran="{{ $rap->anggaran }}" data-id="{{ $rap->id }}">
                                                        <i class="fa-regular fa-clipboard"></i>
                                                    </button>
                                                    {{ formatIdr($rap->anggaran, 2) }}
                                                </div>
                                            </td>
                                            <td>{{ $rap->lokasi_text }}</td>
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
    @include('rap.rap-script')
</x-app-layout-component>
