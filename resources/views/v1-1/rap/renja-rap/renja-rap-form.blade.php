<x-app-layout-component :title="$app['title'] ?? null">

    <script>
        let isEdit = {{ request()->has('edit') ? 'true' : 'false' }};
    </script>


    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <style>
        .form-box {
            position: relative;
            margin-top: 40px;
            /* memberi ruang atas */
        }

        .box-label {
            position: absolute;
            top: -18px;
            /* mengangkat label dari border */
            left: 20px;
            background-color: #3456ff;
            color: white;
            padding: 4px 8px;
            font-weight: 600;
            font-size: 16px;
            border-radius: 6px;
        }

        .box-content {
            border: 1px solid #565656;
            border-radius: 10px;
        }

        .line-pembatas {
            margin-top: 25px;
        }

        .upload-rap-file-upload::file-selector-button {
            display: none;
            /* content: 'Pilih File'; */
        }
    </style>

    @if (request()->has('edit') && $edit_rap)
        <style>
            .box-label {
                background-color: #f7b600;
                color: rgb(0, 0, 0)
                    /* Warna kuning untuk edit */
            }
        </style>
    @endif

    {{-- Buat Tombol Kembali --}}
    <div class="d-flex justify-content-between mb-3">
        <a href="/rap/{{ $jenis }}/renja?skpd={{ $opd->id }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card mx-auto">
        <div class="card-header {{ request()->has('edit') ? 'text-bg-warning' : 'text-bg-primary' }}">
            <h5>
                {{ request()->has('edit') ? 'EDIT' : 'TAMBAH' }} RENJA RAP {{ strtoupper($jenis) }} - {{ $opd->nama_opd }}
            </h5>
        </div>
        {{-- <div class="card-body" @if (request()->has('edit') && $edit_rap) style="background-color: #f7fbcf78;" @endif> --}}
        <div class="card-body">
            <h5 class="mb-4">Sumber Pendanaan : {{ $sumberdana }}</h5>
            <form id="form-renja-rap" method="post" action="{{ request()->has('edit') ? '/rap/' . $jenis . '/renja/' . $opd->id . '/form/update' : '/rap/' . $jenis . '/renja/' . $opd->id . '/form' }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="key_form" value="{{ request()->has('edit') ? hash('sha256', 'update_' . session('tahun')) : hash('sha256', 'new_' . session('tahun')) }}">
                @if (request()->has('edit') && $edit_rap)
                    <input type="hidden" name="id_rap" value="{{ $edit_rap->id }}">
                @else
                    <input type="hidden" name="id_opd" value="{{ $opd->id }}">
                    <input type="hidden" name="jenis" value="{{ $jenis }}">
                    <input type="hidden" name="tahun" value="{{ session()->get('tahun') }}">
                @endif

                <div class="form-box">
                    <span class="box-label">Target Aktifitas Utama</span>
                    <div class="row p-3 m-2 box-content">
                        <!-- Target Aktifitas Utama -->
                        <div class="col-sm-12 col-md-12 col-lg-7">
                            <x-rap-form.select-component name="opd_tag_otsus" id="select-opd_tag_otsus" class="select2" label="Target Aktifitas Utama <small class='text-muted'>(*wajib)</small>" :disabled="request()->has('edit')">
                                <option value=""></option>
                                @if (request()->has('edit') && $edit_rap)
                                    <option selected>{{ $taggings->target_aktifitas->target_text }}</option>
                                @else
                                    @foreach ($taggings as $tagOtsus)
                                        <option value="{{ $tagOtsus->id }}" data-volume="{{ $tagOtsus->volume }}" data-satuan="{{ $tagOtsus->satuan }}" {{ old('opd_tag_otsus') == $tagOtsus->id ? 'selected' : '' }}>{{ $tagOtsus->target_text }}</option>
                                    @endforeach
                                @endif
                            </x-rap-form.select-component>
                        </div>
                        <!-- Akhir Dari Target Aktifitas Utama -->

                        <!-- Volume Target Aktifitas Utama -->
                        <div class="col-sm-12 col-md-12 col-lg-5">
                            <x-rap-form.input-addon-component class="format-angka" id="view-volume_tag" label="Volume Target Aktifitas Utama" addon="right" placeholder="Volume" addonId="view-satuan_volume_tag" :addonName="old('satuan_volume_tag') ? old('satuan_volume_tag') : (request()->has('edit') && $edit_rap ? $edit_rap->tagging->satuan : '')" :value="old('vol_tag_otsus') ? formatNumber(old('vol_tag_otsus')) : (request()->has('edit') && $edit_rap ? formatNumber($edit_rap->tagging->volume) : '')" :disabled="true" />
                            <input type="hidden" name="vol_tag_otsus" id="input-vol_tag_otsus" value="{{ old('vol_tag_otsus', request()->has('edit') && $edit_rap ? $edit_rap->tagging->volume : '') }}">
                            <input type="hidden" name="satuan_volume_tag" id="input-satuan_volume_tag" value="{{ old('satuan_volume_tag', request()->has('edit') && $edit_rap ? $edit_rap->tagging->satuan : '') }}">
                        </div>
                        <!-- Akhir Dari Volume Target Aktifitas Utama -->
                    </div>
                </div>

                <hr class="line-pembatas">

                <div id="form-renja-rap" style="display: block;">
                    <div class="form-box">
                        <span class="box-label">Input Renja RAP</span>
                        <div class="row p-3 m-2 box-content">

                            <!-- Sub Kegiatan -->
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <x-rap-form.select-component name="id_subkegiatan" id="select-subkegiatan" class="select2" label="Pilih Sub Kegiatan <small class='text-muted'>(*wajib)</small>" :disabled="request()->has('edit')">
                                    <option value=""></option>
                                    @if (request()->has('edit') && $edit_rap)
                                        <option selected>{{ $edit_rap->text_subkegiatan }}</option>
                                    @else
                                        @foreach ($nomen_sikd as $subkegiatan)
                                            <option value="{{ $subkegiatan->id }}" data-indikator="{{ $subkegiatan->indikator }}" data-klasifikasi_belanja="{{ $subkegiatan->klasifikasi_belanja }}" data-satuan="{{ $subkegiatan->satuan }}" {{ old('id_subkegiatan') == $subkegiatan->id ? 'selected' : '' }}>{{ $subkegiatan->text }}</option>
                                        @endforeach
                                    @endif
                                </x-rap-form.select-component>
                            </div>
                            <!-- Akhir Dari Sub Kegiatan -->

                            <!-- Indikator Sub Kegiatan -->
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <x-rap-form.textarea-component id="view-indikator" label="Indikator Kinerja Sub Kegiatan" :placeholder="request()->has('edit') && $edit_rap ? $edit_rap->indikator : 'Indikator Kinerja Sub Kegiatan'" :disabled="true">
                                    {{ old('indikator') ? old('indikator') : (request()->has('edit') && $edit_rap ? $edit_rap->indikator_subkegiatan : '') }}
                                </x-rap-form.textarea-component>
                                <input type="hidden" name="indikator" id="input-indikator" value="{{ old('indikator', request()->has('edit') && $edit_rap ? $edit_rap->indikator : '') }}">
                            </div>
                            <!-- Akhir Dari Indikator Sub Kegiatan -->

                            <!-- Klasifikasi Belanja Sub Kegiatan -->
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <x-rap-form.textarea-component id="view-klasifikasi_belanja" label="Klasifikasi Belanja Sub Kegiatan" :placeholder="request()->has('edit') && $edit_rap ? $edit_rap->klasifikasi_belanja : 'Klasifikasi Belanja Sub Kegiatan'" :disabled="true">
                                    {{ old('klasifikasi_belanja') ? old('klasifikasi_belanja') : (request()->has('edit') && $edit_rap ? $edit_rap->klasifikasi_belanja : '') }}
                                </x-rap-form.textarea-component>
                                <input type="hidden" name="klasifikasi_belanja" id="input-klasifikasi_belanja" value="{{ old('klasifikasi_belanja', request()->has('edit') && $edit_rap ? $edit_rap->klasifikasi_belanja : '') }}">
                            </div>
                            <!-- Akhir Dari Klasifikasi Belanja Sub Kegiatan -->

                            <!-- Volume Target Sub Kegiatan -->
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <x-rap-form.input-addon-component name="vol_subkeg" id="input-vol_subkeg" class="format-angka" label="Target Kinerja Sub Kegiatan <small class='text-muted'>(*wajib)</small>" :value="old('vol_subkeg') ? formatNumber(old('vol_subkeg')) : (request()->has('edit') && $edit_rap ? formatNumber($edit_rap->vol_subkeg) : formatNumber(1))" placeholder="Target Kinerja" addon="right" addonId="view-satuan_subkeg" :addonName="old('satuan_subkeg') ? old('satuan_subkeg') : (request()->has('edit') && $edit_rap ? $edit_rap->satuan_subkegiatan : 'Satuan')" />
                                <input type="hidden" name="satuan_subkeg" id="input-satuan_subkeg" value="{{ old('satuan_subkeg', request()->has('edit') && $edit_rap ? $edit_rap->satuan_subkegiatan : '') }}">
                            </div>
                            <!-- Akhir Dari Volume Target Sub Kegiatan -->

                            <!-- Anggaran Sub Kegiatan -->
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <x-rap-form.input-addon-component name="anggaran" id="input-anggaran" class="format-angka" label="Anggaran <small class='text-muted'>(*wajib)</small>" :value="old('anggaran') ? formatNumber(old('anggaran')) : (request()->has('edit') && $edit_rap ? formatNumber($edit_rap->anggaran) : formatNumber(200000000))" placeholder="Target Kinerja" addonName="Rp. " />
                            </div>
                            <!-- Akhir Dari Anggaran Sub Kegiatan -->

                            <!-- Penerima Manfaat -->
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <x-rap-form.select-component name="penerima_manfaat" id="select-penerima_manfaat" label="Penerima Manfaat <small class='text-muted'>(*wajib)</small>">
                                    <option value="">Pilih...</option>
                                    <option value="oap" {{ old('penerima_manfaat') ? (old('penerima_manfaat') == 'oap' ? 'selected' : '') : (request()->has('edit') && $edit_rap->penerima_manfaat == 'oap' ? 'selected' : '') }}>OAP</option>
                                    <option value="umum" {{ old('penerima_manfaat') ? (old('penerima_manfaat') == 'umum' ? 'selected' : '') : (request()->has('edit') && $edit_rap->penerima_manfaat == 'umum' ? 'selected' : 'selected') }}>Umum</option>
                                </x-rap-form.select-component>
                            </div>
                            <!-- Akhir Dari Penerima Manfaat -->

                            <!-- Jenis Layanan -->
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <x-rap-form.select-component name="jenis_layanan" id="select-jenis_layanan" label="Jenis Layanan <small class='text-muted'>(*wajib)</small>">
                                    <option value="">Pilih...</option>
                                    <option value="terkait" {{ old('jenis_layanan') ? (old('jenis_layanan') == 'terkait' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->jenis_layanan == 'terkait' ? 'selected' : '') }}>Terkait Langsung Ke Penerima Manfaat</option>
                                    <option value="pendukung" {{ old('jenis_layanan') ? (old('jenis_layanan') == 'pendukung' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->jenis_layanan == 'pendukung' ? 'selected' : 'selected') }}>Kegiatan Pendukung</option>
                                </x-rap-form.select-component>
                            </div>
                            <!-- Akhir Dari Jenis Layanan -->

                            <!-- Prioritas Bersama Provinsi Papua -->
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <x-rap-form.select-component name="ppsb" id="select-ppsb" label="Prioritas Bersama Papua <small class='text-muted'>(*wajib)</small>">
                                    <option value="">Pilih...</option>
                                    <option value="ya" {{ old('ppsb') ? (old('ppsb') == 'ya' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->ppsb == 'ya' ? 'selected' : '') }}>Ya </option>
                                    <option value="tidak" {{ old('ppsb') ? (old('ppsb') == 'tidak' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->ppsb == 'tidak' ? 'selected' : 'selected') }}>Tidak</option>
                                </x-rap-form.select-component>
                            </div>
                            <!-- Akhir Dari Prioritas Bersama Provinsi Papua -->

                            <!-- Kegiatan Multiyears -->
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <x-rap-form.select-component name="multiyears" id="select-multiyears" label="Kegiatan Multiyears <small class='text-muted'>(*wajib)</small>">
                                    <option value="">Pilih...</option>
                                    <option value="ya" {{ old('multiyears') ? (old('multiyears') == 'ya' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->multiyears == 'ya' ? 'selected' : '') }}>Ya</option>
                                    <option value="tidak" {{ old('multiyears') ? (old('multiyears') == 'tidak' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->multiyears == 'tidak' ? 'selected' : '') }} selected>Tidak</option>
                                </x-rap-form.select-component>
                            </div>
                            <!-- Akhir Dari Kegiatan Multiyears -->

                            <!-- Waktu Mulai Pelaksanaan -->
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <x-rap-form.input-basic-component name="mulai" inputType="date" id="input-mulai" label="Waktu Mulai Pelaksanaan <small class='text-muted'>(*wajib)</small>" :value="old('mulai') ? old('mulai') : (request()->has('edit') && $edit_rap ? $edit_rap->mulai : '2026-01-01')" />
                            </div>
                            <!-- Akhir Dari Waktu Mulai Pelaksanaan -->

                            <!-- Waktu Selesai Pelaksanaan -->
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <x-rap-form.input-basic-component name="selesai" inputType="date" id="input-selesai" label="Waktu Selesai Pelaksanaan <small class='text-muted'>(*wajib)</small>" :value="old('selesai') ? old('selesai') : (request()->has('edit') && $edit_rap ? $edit_rap->selesai : '2026-11-01')" />
                            </div>
                            <!-- Akhir Dari Waktu Selesai Pelaksanaan -->

                            <!-- Jenis Kegiatan -->
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <x-rap-form.select-component name="jenis_kegiatan" id="select-jenis_kegiatan" label="Jenis Kegiatan <small class='text-muted'>(*wajib)</small>">
                                    <option value="">Pilih...</option>
                                    <option value="fisik" {{ old('jenis_kegiatan') ? (old('jenis_kegiatan') == 'fisik' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->jenis_kegiatan == 'fisik' ? 'selected' : '') }} selected>Fisik</option>
                                    <option value="nonfisik" {{ old('jenis_kegiatan') ? (old('jenis_kegiatan') == 'nonfisik' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->jenis_kegiatan == 'nonfisik' ? 'selected' : '') }}>Non Fisik</option>
                                </x-rap-form.select-component>
                            </div>
                            <!-- Akhir Dari Jenis Kegiatan -->

                            <!-- Lokasi -->
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <x-rap-form.select-component name="lokus[]" id="select-lokus" class="select2-multiple" label="Lokasi Fokus Kegiatan <small class='text-muted'>(*wajib)</small>" :multiple="true">
                                    @foreach ($lokasi as $lokus)
                                        @if (request()->has('edit') && $edit_rap)
                                            @php
                                                $lokus_ids = collect(json_decode($edit_rap->lokus, true))->pluck('id');
                                            @endphp
                                        @endif
                                        <option value="{{ $lokus->id }}" {{ old('lokus') ? (in_array($lokus->id, old('lokus')) ? 'selected' : '') : (request()->has('edit') && $edit_rap && $lokus_ids->contains($lokus->id) ? 'selected' : '') }}>{{ $lokus->lokasi }}</option>
                                    @endforeach
                                </x-rap-form.select-component>
                            </div>
                            <!-- Akhir Dari Lokasi -->

                            <!-- Koordinat Lokasi Fokus Kegiatan -->
                            <div id="div-koordinat" class="col-sm-12 col-md-12 col-lg-12" @if (old('jenis_kegiatan') === 'fisik' || (request()->has('edit') && $edit_rap && $edit_rap->jenis_kegiatan === 'fisik')) style="display: block;" @else style="display: none;" @endif>
                                <x-rap-form.textarea-component name="koordinat" id="input-koordinat" label="Koordinat Lokasi Fokus Kegiatan <a href='https://www.google.com/maps' target='_blank' class='text-info' data-bs-toggle='tooltip' data-bs-palcement='Top' data-bs-title='Buka Google Maps'>google maps <i class='fa-solid fa-up-right_from_square'></i></a> (<small>*wajib untuk jenis kegiatan fisik</small>)" :disabled="old('jenis_kegiatan') === 'nonfisik' || (!old('jenis_kegiatan') && request()->has('edit') && $edit_rap && $edit_rap->jenis_kegiatan === 'nonfisik')">
                                    {{ old('koordinat') ?? (request()->has('edit') && $edit_rap ? $edit_rap->koordinat : 'Test Koordinat') }}
                                </x-rap-form.textarea-component>
                            </div>
                            <!-- Akhir Dari Koordinat Lokasi Fokus Kegiatan -->


                            <!-- Sumber Pendanaan Lainnya -->
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <x-rap-form.select-component name="dana_lain[]" id="select-dana_lain" class="select2-multiple" label="Sumber Pendanaan Lainnya <small class='text-muted'>(*wajib)</small>" :multiple="true">
                                    @foreach ($dana_lains as $dana)
                                        @if (request()->has('edit') && $edit_rap)
                                            @php
                                                $dana_lain_ids = collect(json_decode($edit_rap->dana_lain, true))->pluck('id');
                                            @endphp
                                        @endif
                                        <option value="{{ $dana->id }}" {{ old('dana_lain') ? (in_array($dana->id, old('dana_lain')) ? 'selected' : '') : (request()->has('edit') && $edit_rap && $dana_lain_ids->contains($dana->id) ? 'selected' : '') }}>{{ $dana->uraian }}</option>
                                    @endforeach
                                </x-rap-form.select-component>
                            </div>
                            <!-- Akhir Dari Sumber Pendanaan Lainnya -->

                            <!-- Keterangan -->
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <x-rap-form.textarea-component name="keterangan" id="input-keterangan" label="Keterangan (<small>*wajib menjelaskan konteks kegiatan</small>)" placeholder="Keterangan">
                                    {{ old('keterangan') ? old('keterangan') : (request()->has('edit') && $edit_rap ? $edit_rap->keterangan : 'Test Keterangan') }}
                                </x-rap-form.textarea-component>
                            </div>
                            <!-- Akhir Dari Keterangan -->
                        </div>
                    </div>

                    <hr class="line-pembatas">

                    <div class="form-box">
                        <span class="box-label">Upload File Pendukung</span>
                        <div class="row p-3 m-2" style="border: 1px solid #565656; border-radius: 5px;">
                            <div class="text-muted mb-3 fst-italic">
                                <span class="mb-3">Silahkan upload file pendukung RAP, seperti KAK, RAB, dan lainnya.</span>
                                <span class="mb-3">Keterangan: </span>
                                <ul class="mb-3" style="fonsont-size: 12px">
                                    <li>File harus berjenis .pdf</li>
                                    <li>Ukuran file tidak boleh lebih dari 5MB</li>
                                    <li>File KAK dan RAB Wajib di input</li>
                                    <li>File pendukung lainnya dapat di upload dalam sebuah folder pada <a href="https://drive.google.com" target="_blank"> google drive <i class="fa-solid fa-up-right-from-square"></i></a> dan masukan url pada kolom link file</li>
                                </ul>
                            </div>

                            @php
                                $isEdit = request()->has('edit') && $edit_rap;
                            @endphp

                            <div class="row">
                                <!-- File KAK -->
                                <div class="mb-4">
                                    <label for="upload-file-kak" class="form-label">Kerangka Acuan Kerja [KAK] <small class="text-muted">(*wajib)</small></label>
                                    <div id="upload-file-kak-group" class="input-group">
                                        <label class="input-group-text {{ $isEdit ? 'text-bg-danger' : '' }}" style="cursor: pointer" @if ($isEdit) onclick="document.getElementById('upload-file-kak').click()" @else for="upload-file-kak" @endif>
                                            @if ($isEdit)
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            @else
                                                File KAK
                                            @endif
                                        </label>
                                        <input type="file" name="file_kak_name" id="upload-file-kak" accept=".pdf" style="display: none;">
                                        <span id="show-file-kak" class="form-control upload-rap-file-upload @error('file_kak_name') is-invalid text-danger @enderror" type="file" @if (($isEdit && !$edit_rap->file_kak_name) || !$isEdit) onclick="document.getElementById('upload-file-kak').click()" @endif>
                                            @if ($isEdit && $edit_rap->file_kak_name)
                                                <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_kak_name }}" target="_blank">{{ $edit_rap->file_kak_name }}</a>
                                            @else
                                                Pilih File KAK
                                            @endif
                                        </span>
                                    </div>
                                    <div id="error-div-kak">
                                        @error('file_kak_name')
                                            <span id="file_kak_name" class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Akhir File KAK -->


                                <!-- File RAB -->
                                <div class="mb-4">
                                    <label for="upload-file-rab" class="form-label">Rencana Anggaran Biaya [RAB] <small class="text-muted">(*wajib)</small></label>
                                    <div id="upload-file-rab-group" class="input-group">
                                        <label class="input-group-text {{ $isEdit ? 'text-bg-danger' : '' }}" style="cursor: pointer" @if ($isEdit) onclick="document.getElementById('upload-file-rab').click()" @else for="upload-file-rab" @endif>
                                            @if ($isEdit)
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            @else
                                                File RAB
                                            @endif
                                        </label>
                                        <input type="file" name="file_rab_name" id="upload-file-rab" accept=".pdf" style="display: none;">
                                        <span id="show-file-rab" class="form-control upload-rap-file-upload @error('file_rab_name') is-invalid text-danger @enderror" type="file" @if (($isEdit && !$edit_rap->file_rab_name) || !$isEdit) onclick="document.getElementById('upload-file-rab').click()" @endif>
                                            @if ($isEdit && $edit_rap->file_rab_name)
                                                <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_rab_name }}" target="_blank">{{ $edit_rap->file_rab_name }}</a>
                                            @else
                                                Pilih File RAB
                                            @endif
                                        </span>
                                    </div>
                                    <div id="error-div-rab">
                                        @error('file_rab_name')
                                            <span id="file_rab_name" class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- File Pendukung 1 -->
                                <div class="mb-4">
                                    <label for="upload-file-pendukung1" class="form-label">File pendukung 1 <small class="text-muted">(*wajib)</small></label>
                                    <div class="input-group">
                                        <label class="input-group-text {{ $isEdit ? 'text-bg-danger' : '' }}" style="cursor: pointer" @if ($isEdit) onclick="document.getElementById('upload-file-pendukung1').click()" @else for="upload-file-pendukung1" @endif>
                                            @if ($isEdit)
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            @else
                                                File Tambahan
                                            @endif
                                        </label>
                                        <input type="file" id="upload-file-pendukung1" name="file_pendukung1_name" accept=".pdf" style="display: none;">
                                        <span id="show-file-pendukung1" class="form-control upload-rap-file-upload @error('file_pendukung1_name') is-invalid text-danger @enderror" type="file" @if (($isEdit && !$edit_rap->file_pendukung1_name) || !$isEdit) onclick="document.getElementById('upload-file-pendukung1').click()" @endif>
                                            @if ($isEdit && $edit_rap->file_pendukung1_name)
                                                <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_pendukung1_name }}" target="_blank">{{ $edit_rap->file_pendukung1_name }}</a>
                                            @else
                                                Pilih File Pendukung Lainya
                                            @endif
                                        </span>
                                    </div>
                                    <div id="error-div-pendukung1">
                                        @error('file_pendukung1_name')
                                            <span id="file_pendukung1_name" class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- File Pendukung 2 -->
                                <div class="mb-4">
                                    <label for="upload-file-pendukung2" class="form-label">File pendukung lainnya</small></label>
                                    <div class="input-group">
                                        <label class="input-group-text {{ $isEdit && $edit_rap->file_pendukung2_name ? 'text-bg-danger' : '' }}" style="cursor: pointer" @if ($isEdit) onclick="document.querySelector('input[name=file_pendukung2_name]').click()" @else for="upload-file-pendukung2" @endif>
                                            @if ($isEdit && $edit_rap->file_pendukung2_name)
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            @else
                                                File Tambahan
                                            @endif
                                        </label>
                                        <input id="upload-file-pendukung2" type="file" name="file_pendukung2_name" accept=".pdf" style="display: none;">
                                        <span id="show-file-pendukung2" class="form-control upload-rap-file-upload @error('file_pendukung2_name') is-invalid text-danger @enderror" type="file" @if (($isEdit && !$edit_rap->file_pendukung2_name) || !$isEdit) onclick="document.getElementById('upload-file-pendukung2').click()" @endif>
                                            @if ($isEdit && $edit_rap->file_pendukung2_name)
                                                <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_pendukung2_name }}" target="_blank">{{ $edit_rap->file_pendukung2_name }}</a>
                                            @else
                                                Pilih File Pendukung Lainya
                                            @endif
                                        </span>
                                    </div>
                                    <div id="error-div-pendukung2">
                                        @error('file_pendukung2_name')
                                            <span id="file_pendukung2_name" class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- File Pendukung Lainnya -->
                                <div class="mb-4">
                                    <label for="upload-file-pendukung3" class="form-label">File pendukung lainnya</small></label>
                                    <div class="input-group">
                                        <label class="input-group-text {{ $isEdit && $edit_rap->file_pendukung3_name ? 'text-bg-danger' : '' }}" style="cursor: pointer" @if ($isEdit) onclick="document.querySelector('input[name=file_pendukung3_name]').click()" @else for="upload-file-pendukung3" @endif>
                                            @if ($isEdit && $edit_rap->file_pendukung3_name)
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            @else
                                                File Tambahan
                                            @endif
                                        </label>
                                        <input id="upload-file-pendukung3" type="file" name="file_pendukung3_name" accept=".pdf" style="display: none;">
                                        <span id="show-file-pendukung3" class="form-control upload-rap-file-upload @error('file_pendukung3_name') is-invalid text-danger @enderror" type="file" @if (($isEdit && !$edit_rap->file_pendukung3_name) || !$isEdit) onclick="document.getElementById('upload-file-pendukung3').click()" @endif>
                                            @if ($isEdit && $edit_rap->file_pendukung3_name)
                                                <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_pendukung3_name }}" target="_blank">{{ $edit_rap->file_pendukung3_name }}</a>
                                            @else
                                                Pilih File Pendukung Lainya
                                            @endif
                                        </span>
                                    </div>
                                    <div id="error-div-pendukung3">
                                        @error('file_pendukung3_name')
                                            <span id="file_pendukung3_name" class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Link File Pendukung Lainnya -->
                                <div class="mb-4">
                                    <label for="input-link-file-dukung-lain" class="form-label">Link file pendukung lainnya <small class="text-muted">(Google Drive)</small></label>
                                    <input name="link_file_dukung_lain" type="text" value="{{ $isEdit ? $edit_rap->link_file_dukung_lain : '' }}" class="form-control  @error('link_file_dukung_lain') is-invalid @enderror" id="input-link-file-dukung-lain" placeholder="Link google drive">
                                    <span id="new_link_file_dukung_lain_error" class="text-danger"></span>
                                </div>
                            </div>

                        </div>

                        <div class="row p-3 m-2">
                            <div class="col-6 text-start">
                                <a href="/rap/{{ $jenis }}/renja?skpd={{ $opd->id }}" class="btn btn-secondary">
                                    Batal
                                </a>
                            </div>
                            <div class="col-6 text-end">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>

            </form>
        </div>
    </div>

    @include('v1-1.rap.renja-rap.script-renja-rap-form')

    <script>
        document.getElementById("input-koordinat").placeholder =
            "Contoh:\n-2.3273906949173777, 138.01587621732185 | Ruas Jalan Otonom Burmeso KM 0;" +
            "\n-2.328209223413783, 138.00490790225876 | Ruas Jalan Otonom Burmeso KM 1;";
    </script>
</x-app-layout-component>
