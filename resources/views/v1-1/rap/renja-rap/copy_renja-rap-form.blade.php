<x-app-layout-component :title="$app['title'] ?? null">
    <div class="card mx-auto">
        <div class="card-header {{ request()->has('edit') ? 'text-bg-warning' : 'text-bg-primary' }}">
            <h5>
                {{ request()->has('edit') ? 'EDIT' : 'TAMBAH' }} RENJA RAP {{ strtoupper($jenis) }} - {{ $opd->nama_opd }}
            </h5>
        </div>
        <div class="card-body" @if (request()->has('edit') && $edit_rap) style="background-color: #f7fbcf78;" @endif>
            <h5 class="mb-4">Sumber Pendanaan : {{ $sumberdana }}</h5>
            <form method="post">
                @csrf
                <input type="hidden" name="key_form" value="{{ request()->has('edit') ? hash('sha256', 'update_' . session('tahun')) : hash('sha256', 'new_' . session('tahun')) }}">
                @if (request()->has('edit') && $edit_rap)
                    <input type="hidden" name="id_rap" value="{{ $edit_rap->id }}">
                @else
                    <input type="hidden" name="id_opd" value="{{ $opd->id }}">
                    <input type="hidden" name="jenis" value="{{ $jenis }}">
                    <input type="hidden" name="tahun" value="{{ session()->get('tahun') }}">
                @endif
                {{-- <div class="col-sm-12 col-md-12 col-lg-8 mx-auto"> --}}
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-7">
                            <div class="mb-3">
                                <label for="select-opd_tag_otsus" class="form-label">Target Aktifitas Utama</label>
                                <select name="opd_tag_otsus" class="form-control @error('opd_tag_otsus') is-invalid @enderror select2 select-form" id="select-opd_tag_otsus" data-placeholder="Pilih..." {{ request()->has('edit') && $edit_rap ? 'disabled' : '' }}>
                                    <option value=""></option>
                                    @if (request()->has('edit') && $edit_rap)
                                        <option value="{{ $edit_rap->tagging->id }}" data-volume="{{ $edit_rap->tagging->volume }}" data-satuan="{{ $edit_rap->tagging->satuan }}" selected>{{ $edit_rap->tagging->target_aktifitas->target_text }}</option>
                                    @else
                                        @foreach ($taggings as $tag)
                                            <option value="{{ $tag->id }}" data-volume="{{ $tag->volume }}" data-satuan="{{ $tag->satuan }}">{{ $tag->target_text }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('opd_tag_otsus')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-5">
                            <div class="mb-3">
                                <label for="view-volume_tag" class="form-label">Volume Target Aktifitas Utama</label>
                                <div class="input-group">
                                    <input type="text" class="form-control view-form" id="view-volume_tag" value="{{ request()->has('edit') && $edit_rap ? formatNumber($edit_rap->tagging->volume) : '' }}" placeholder="Volume" aria-describedby="view-satuan_volume_tag" disabled>
                                    <span class="input-group-text vew-text" id="view-satuan_volume_tag">{{ request()->has('edit') && $edit_rap ? $edit_rap->tagging->satuan : 'Satuan' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="form-renja-rap" style="display: block; margin-top: 20px;">
                        <hr>
                        <div class="row">
                            <div class="mb-3">
                                <label for="select-subkegiatan" class="form-label">Pilih Sub Kegiatan</label>
                                <select name="id_subkegiatan" class="form-control @error('id_subkegiatan') is-invalid @enderror select2 select-form" id="select-subkegiatan" data-placeholder="Pilih..." {{ request()->has('edit') && $edit_rap ? 'disabled' : '' }}>
                                    <option value=""></option>
                                    @if (request()->has('edit') && $edit_rap)
                                        <option selected>{{ $edit_rap->nomen_sikd->text }}</option>
                                    @else
                                        @foreach ($nomen_sikd as $subkegiatan)
                                            <option value="{{ $subkegiatan->id }}" data-indikator="{{ $subkegiatan->indikator }}" data-klasifikasi_belanja="{{ $subkegiatan->klasifikasi_belanja }}" data-satuan="{{ $subkegiatan->satuan }}">{{ $subkegiatan->text }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('id_subkegiatan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="view-indikator" class="form-label">Indikator Sub Kegiatan</label>
                                    <textarea class="form-control" id="view-indikator" placeholder="Indikator Sub Kegiatan" rows="3" disabled>{{ request()->has('edit') && $edit_rap ? $edit_rap->nomen_sikd->indikator : '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="view-klasifikasi_belanja" class="form-label">Klasifikasi Belanja Sub Kegiatan</label>
                                    <textarea class="form-control" id="view-klasifikasi_belanja" placeholder="Klasifikasi Belanja Sub Kegiatan" rows="3" disabled>{{ request()->has('edit') && $edit_rap ? $edit_rap->nomen_sikd->klasifikasi_belanja : '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="input-vol_subkeg" class="form-label">Target Kinerja Sub Kegiatan</label>
                                    <div class="input-group">
                                        <input name="vol_subkeg" type="text" value="{{ request()->has('edit') && $edit_rap ? formatNumber($edit_rap->vol_subkeg) : '' }}" class="form-control @error('vol_sukeg') is-invalid @enderror format-angka" id="input-vol_subkeg" placeholder="Target Kinerja" aria-describedby="view-satuan_subkeg">
                                        <span class="input-group-text" id="view-satuan_subkeg">{{ request()->has('edit') && $edit_rap ? $edit_rap->nomen_sikd->satuan : '' }}</span>
                                    </div>
                                    @error('vol_subkeg')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="input-anggaran" class="form-label">Anggaran Sub Kegiatan</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="view-rp">Rp</span>
                                        <input name="anggaran" type="text" value="{{ request()->has('edit') && $edit_rap ? formatNumber($edit_rap->anggaran) : '' }}" class="form-control format-angka" id="input-anggaran" placeholder="000,00-" aria-describedby="view-rp">
                                    </div>
                                    @error('anggaran')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="select-penerima_manfaat" class="form-label">Penerima Manfaat</label>
                                    <select name="penerima_manfaat" class="form-control" id="select-penerima_manfaat">
                                        <option value="">Pilih...</option>
                                        <option value="oap" {{ request()->has('edit') && $edit_rap && $edit_rap->penerima_manfaat == 'oap' ? 'selected' : '' }}>Khusus OAP</option>
                                        <option value="umum" {{ request()->has('edit') && $edit_rap && $edit_rap->penerima_manfaat == 'umum' ? 'selected' : '' }}>Umum (OAP & Non OAP)</option>
                                    </select>
                                    @error('penerima_manfaat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="select-jenis_layanan" class="form-label">Jenis Layanan</label>
                                    <select name="jenis_layanan" class="form-control" id="select-jenis_layanan">
                                        <option value="">Pilih...</option>
                                        <option value="terkait" {{ request()->has('edit') && $edit_rap && $edit_rap->jenis_layanan == 'terkait' ? 'selected' : '' }}>Terkait Langsung Ke Penerima Manfaat</option>
                                        <option value="pendukung" {{ request()->has('edit') && $edit_rap && $edit_rap->jenis_layanan == 'pendukung' ? 'selected' : '' }}>Kegiatan Pendukung</option>
                                    </select>
                                    @error('jenis_layanan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="select-ppsb" class="form-label">Prioritas Bersama Papua</label>
                                    <select name="ppsb" class="form-control" id="select-ppsb">
                                        <option value="">Pilih...</option>
                                        <option value="ya" {{ request()->has('edit') && $edit_rap && $edit_rap->ppsb == 'ya' ? 'selected' : '' }}>Ya </option>
                                        <option value="tidak" {{ request()->has('edit') && $edit_rap && $edit_rap->ppsb == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                    @error('ppsb')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="select-multiyears" class="form-label">Kegiatan Multiyears (tahunan)</label>
                                    <select name="multiyears" class="form-control" id="select-multiyears">
                                        <option value="">Pilih...</option>
                                        <option value="ya" {{ request()->has('edit') && $edit_rap && $edit_rap->multiyears == 'ya' ? 'selected' : '' }}>Ya</option>
                                        <option value="tidak" {{ request()->has('edit') && $edit_rap && $edit_rap->multiyears == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                    @error('multiyears')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="input-mulai" class="form-label">Waktu Mulai Pelaksanaan</label>
                                    <input name="mulai" type="date" value="{{ request()->has('edit') && $edit_rap ? $edit_rap->mulai : '' }}" class="form-control" id="input-mulai">
                                    @error('mulai')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="input-selesai" class="form-label">Waktu Selesai Pelaksanaan</label>
                                    <input name="selesai" type="date" value="{{ request()->has('edit') && $edit_rap ? $edit_rap->selesai : '' }}" class="form-control" id="input-selesai">
                                    @error('selesai')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="select-jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                                    <select name="jenis_kegiatan" class="form-control" id="select-jenis_kegiatan">
                                        <option value="">Pilih...</option>
                                        <option value="fisik" {{ request()->has('edit') && $edit_rap && $edit_rap->jenis_kegiatan == 'fisik' ? 'selected' : '' }}>Kegiatan Fisik</option>
                                        <option value="nonfisik" {{ request()->has('edit') && $edit_rap && $edit_rap->jenis_kegiatan == 'nonfisik' ? 'selected' : '' }}>Kegiatan Non Fisik</option>
                                    </select>
                                    @error('jenis_kegiatan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="select-lokus" class="form-label">Lokasi Fokus Pelaksanaan Kegiatan</label>
                                    <select name="lokus" class="form-control select2-multiple" id="select-lokus" data-placeholder="Pilih..." multiple>
                                        <option value=""></option>
                                        @foreach ($lokasi as $lokus)
                                            @if (request()->has('edit') && $edit_rap)
                                                @php
                                                    $lokus_ids = collect(json_decode($edit_rap->lokus, true))->pluck('id');
                                                @endphp
                                            @endif
                                            <option value="{{ $lokus->id }}" {{ request()->has('edit') && $edit_rap && $lokus_ids->contains($lokus->id) ? 'selected' : '' }}>{{ $lokus->lokasi }}</option>
                                        @endforeach
                                    </select>
                                    @error('lokus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12" id="div-koordinat" @if (request()->has('edit') && $edit_rap && $edit_rap->jenis_kegiatan == 'nonfisik') style="display: none;" @endif>
                                <div class="mb-3">
                                    <label for="input-koordinat" class="form-label">Koordinat Lokasi Kegiatan <a href="https://www.google.com/maps" target="_blank" class="text-info" data-bs-toggle="tooltip" data-bs-palcement="Top" data-bs-title="Buka Google Maps">google maps <i class="fa-solid fa-up-right_from_square"></i></a></label>
                                    <textarea name="koordinat" class="form-control jenis-kegiatan-selected" id="input-koordinat" rows="3" {{ request()->has('edit') && $edit_rap ? '' : 'disabled' }}>{{ request()->has('edit') && $edit_rap ? $edit_rap->koordinat : '' }}</textarea>
                                    @error('koordinat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="select-dana_lain" class="form-label">Sumber Pendanaan Lainnya</label>
                                    <select name="dana_lain" class="form-control select2-multiple" id="select-dana_lain" data-placeholder="Pilih..." multiple>
                                        <option value=""></option>
                                        @foreach ($dana_lains as $dana)
                                            @if (request()->has('edit') && $edit_rap)
                                                @php
                                                    $dana_lain_ids = collect(json_decode($edit_rap->dana_lain, true))->pluck('id');
                                                @endphp
                                            @endif
                                            <option value="{{ $dana->id }}" {{ request()->has('edit') && $edit_rap && $dana_lain_ids->contains($dana->id) ? 'selected' : '' }}>{{ $dana->uraian }}</option>
                                        @endforeach
                                    </select>
                                    @error('dana_lain')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="input-keterangan" class="form-label">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" id="input-keterangan" placeholder="Keterangan" rows="3">{{ request()->has('edit') && $edit_rap ? $edit_rap->keterangan : '' }}</textarea>
                                    @error('keterangan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Upload File Pendukung</h5>
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

                        <style>
                            .upload-rap-file-upload::file-selector-button {
                                display: none;
                                /* content: 'Pilih File'; */
                            }
                        </style>

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
                                    <span class="form-control upload-rap-file-upload" type="file" @if ($isEdit && !$edit_rap->file_kak_name) || !$isEdit) onclick="document.getElementById('upload-file-kak').click()" @endif>
                                        @if ($isEdit && $edit_rap->file_kak_name)
                                            <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_kak_name }}" target="_blank">{{ $edit_rap->file_kak_name }}</a>
                                        @else
                                            Pilih File KAK
                                        @endif
                                    </span>
                                </div>
                                @error('file_kak_name')
                                    <span id="file_kak_name" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

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
                                    <span class="form-control upload-rap-file-upload" type="file" @if (($isEdit && !$edit_rap->file_rab_name) || !$isEdit) onclick="document.getElementById('upload-file-rab').click()" @endif>
                                        @if ($isEdit && $edit_rap->file_rab_name)
                                            <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_rab_name }}" target="_blank">{{ $edit_rap->file_rab_name }}</a>
                                        @else
                                            Pilih File RAB
                                        @endif
                                    </span>
                                </div>
                                @error('file_rab_name')
                                    <span id="file_rab_name" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- File Pendukung 1 -->
                            <div class="mb-4">
                                <label for="upload-file-pendukung1" class="form-label">File pendukung 1 <small class="text-muted">(*wajib)</small></label>
                                <div class="input-group">
                                    <label class="input-group-text {{ $isEdit ? 'text-bg-danger' : '' }}" style="cursor: pointer" @if ($isEdit) onclick="document.getElementById('upload-file-pendukung1').click()" @else for="upload-file-pendukung1" @endif>
                                        @if ($isEdit)
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        @else
                                            File Pendukung 1
                                        @endif
                                    </label>
                                    <input type="file" id="upload-file-pendukung1" name="file_pendukung1_name" accept=".pdf" style="display: none;">
                                    <span class="form-control upload-rap-file-upload" type="file" @if ($isEdit && !$edit_rap->file_pendukung1_name) || !$isEdit) onclick="document.getElementById('upload-file-pendukung1').click()" @endif>
                                        @if ($isEdit && $edit_rap->file_pendukung1_name)
                                            <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_pendukung1_name }}" target="_blank">{{ $edit_rap->file_pendukung1_name }}</a>
                                        @else
                                            Pilih File Pendukung 1
                                        @endif
                                    </span>
                                </div>
                                @error('file_pendukung1_name')
                                    <span id="file_pendukung1_name" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- File Pendukung 2 -->
                            <div class="mb-4">
                                <label for="upload-file-pendukung2" class="form-label">File pendukung lainnya</small></label>
                                <div class="input-group">
                                    <label class="input-group-text {{ $isEdit && $edit_rap->file_pendukung2_name ? 'text-bg-danger' : '' }}" style="cursor: pointer" @if ($isEdit) onclick="document.querySelector('input[name=file_pendukung2_name]').click()" @else for="upload-file-pendukung2" @endif>
                                        @if ($isEdit && $edit_rap->file_pendukung2_name)
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        @else
                                            File Pendukung 2
                                        @endif
                                    </label>
                                    <input id="upload-file-pendukung2" type="file" name="file_pendukung2_name" accept=".pdf" style="display: none;">
                                    <span class="form-control upload-rap-file-upload" type="file" @if (($isEdit && !$edit_rap->file_pendukung2_name) || !$isEdit) onclick="document.getElementById('upload-file-pendukung2').click()" @endif>
                                        @if ($isEdit && $edit_rap->file_pendukung2_name)
                                            <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_pendukung2_name }}" target="_blank">{{ $edit_rap->file_pendukung2_name }}</a>
                                        @else
                                            Pilih File
                                        @endif
                                    </span>
                                </div>
                                @error('file_pendukung2_name')
                                    <span id="file_pendukung2_name" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- File Pendukung Lainnya -->
                            <div class="mb-4">
                                <label for="upload-file-pendukung3" class="form-label">File pendukung lainnya</small></label>
                                <div class="input-group">
                                    <label class="input-group-text {{ $isEdit && $edit_rap->file_pendukung3_name ? 'text-bg-danger' : '' }}" style="cursor: pointer" @if ($isEdit) onclick="document.querySelector('input[name=file_pendukung3_name]').click()" @else for="upload-file-pendukung3" @endif>
                                        @if ($isEdit && $edit_rap->file_pendukung3_name)
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        @else
                                            File Pendukung 3
                                        @endif
                                    </label>
                                    <input id="upload-file-pendukung3" type="file" name="file_pendukung3_name" accept=".pdf" style="display: none;">
                                    <span class="form-control upload-rap-file-upload" type="file" @if (($isEdit && !$edit_rap->file_pendukung3_name) || !$isEdit) onclick="document.getElementById('upload-file-pendukung3').click()" @endif>
                                        @if ($isEdit && $edit_rap->file_pendukung3_name)
                                            <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_pendukung3_name }}" target="_blank">{{ $edit_rap->file_pendukung3_name }}</a>
                                        @else
                                            Pilih File
                                        @endif
                                    </span>
                                </div>
                                @error('file_pendukung3_name')
                                    <span id="file_pendukung3_name" class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Link File Pendukung Lainnya -->
                            <div class="mb-4">
                                <label for="input-link-file-dukung-lain" class="form-label">Link file pendukung lainnya <small class="text-muted">(Google Drive)</small></label>
                                <input name="link_file_dukung_lain" type="text" value="{{ $isEdit ? $edit_rap->link_file_dukung_lain : '' }}" class="form-control" id="input-link-file-dukung-lain" placeholder="Link google drive">
                                <span id="new_link_file_dukung_lain_error" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="mt-5 mb-4">
                            <button class="btn btn-primary">Simpan</button>
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
