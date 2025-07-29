<x-app-layout-component :title="$app['title'] ?? null">
    <style>
        .hidden {
            display: none;
        }
    </style>
    <div class="card mx-auto">
        <div class="card-header">
            <h5>{{ $app['desc'] }}</h5>
        </div>
        <div class="card-body">
            <form method="post">
                @csrf
                <div class="row">
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
                        <x-rap-form.input-addon-component class="format-angka" label="Volume Target Aktifitas Utama" addon="right" placeholder="Volume" :addonName="request()->has('edit') && $edit_rap ? $edit_rap->tagging->satuan : 'Satuan'" :value="request()->has('edit') && $edit_rap ? formatNumber($edit_rap->tagging->volume) : ''" :disabled="true" />
                    </div>
                    <!-- Akhir Dari Volume Target Aktifitas Utama -->

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
                            {{ request()->has('edit') && $edit_rap ? $edit_rap->indikator_subkegiatan : '' }}
                        </x-rap-form.textarea-component>
                    </div>
                    <!-- Akhir Dari Indikator Sub Kegiatan -->

                    <!-- Klasifikasi Belanja Sub Kegiatan -->
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <x-rap-form.textarea-component id="view-klasifikasi_belanja" label="Klasifikasi Belanja Sub Kegiatan" :placeholder="request()->has('edit') && $edit_rap ? $edit_rap->klasifikasi_belanja : 'Klasifikasi Belanja Sub Kegiatan'" :disabled="true">
                            {{ request()->has('edit') && $edit_rap ? $edit_rap->klasifikasi_belanja : '' }}
                        </x-rap-form.textarea-component>
                    </div>
                    <!-- Akhir Dari Klasifikasi Belanja Sub Kegiatan -->

                    <!-- Volume Target Sub Kegiatan -->
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <x-rap-form.input-addon-component name="vol_subkeg" id="input-vol_subkeg" class="format-angka" label="Target Kinerja Sub Kegiatan <small class='text-muted'>(*wajib)</small>" :value="old('vol_subkeg') ? formatNumber(old('vol_subkeg')) : (request()->has('edit') && $edit_rap ? formatNumber($edit_rap->vol_subkeg) : '')" placeholder="Target Kinerja" addon="right" :addonName="request()->has('edit') && $edit_rap ? $edit_rap->satuan_subkegiatan : 'Satuan'" />
                    </div>
                    <!-- Akhir Dari Volume Target Sub Kegiatan -->

                    <!-- Anggaran Sub Kegiatan -->
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <x-rap-form.input-addon-component name="anggaran" id="input-anggaran" class="format-angka" label="Anggaran <small class='text-muted'>(*wajib)</small>" :value="old('anggaran') ? formatNumber(old('anggaran')) : (request()->has('edit') && $edit_rap ? formatNumber($edit_rap->anggaran) : '')" placeholder="Target Kinerja" addonName="Rp. " />
                    </div>
                    <!-- Akhir Dari Anggaran Sub Kegiatan -->

                    <!-- Penerima Manfaat -->
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <x-rap-form.select-component name="penerima_manfaat" id="select-penerima_manfaat" label="Penerima Manfaat <small class='text-muted'>(*wajib)</small>">
                            <option value="">Pilih...</option>
                            <option value="oap" {{ old('penerima_manfaat') ? (old('penerima_manfaat') == 'oap' ? 'selected' : '') : (request()->has('edit') && $edit_rap->penerima_manfaat == 'oap' ? 'selected' : '') }}>OAP</option>
                            <option value="umum" {{ old('penerima_manfaat') ? (old('penerima_manfaat') == 'umum' ? 'selected' : '') : (request()->has('edit') && $edit_rap->penerima_manfaat == 'umum' ? 'selected' : '') }}>Umum</option>
                        </x-rap-form.select-component>
                    </div>
                    <!-- Akhir Dari Penerima Manfaat -->

                    <!-- Jenis Layanan -->
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <x-rap-form.select-component name="jenis_layanan" id="select-jenis_layanan" label="Jenis Layanan <small class='text-muted'>(*wajib)</small>">
                            <option value="">Pilih...</option>
                            <option value="terkait" {{ old('jenis_layanan') ? (old('jenis_layanan') == 'terkait' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->jenis_layanan == 'terkait' ? 'selected' : '') }}>Terkait Langsung Ke Penerima Manfaat</option>
                            <option value="pendukung" {{ old('jenis_layanan') ? (old('jenis_layanan') == 'pendukung' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->jenis_layanan == 'pendukung' ? 'selected' : '') }}>Kegiatan Pendukung</option>
                        </x-rap-form.select-component>
                    </div>
                    <!-- Akhir Dari Jenis Layanan -->

                    <!-- Prioritas Bersama Provinsi Papua -->
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <x-rap-form.select-component name="ppsb" id="select-ppsb" label="Prioritas Bersama Provinsi Papua <small class='text-muted'>(*wajib)</small>">
                            <option value="">Pilih...</option>
                            <option value="ya" {{ old('ppsb') ? (old('ppsb') == 'ya' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->ppsb == 'ya' ? 'selected' : '') }}>Ya </option>
                            <option value="tidak" {{ old('ppsb') ? (old('ppsb') == 'tidak' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->ppsb == 'tidak' ? 'selected' : '') }}>Tidak</option>
                        </x-rap-form.select-component>
                    </div>
                    <!-- Akhir Dari Prioritas Bersama Provinsi Papua -->

                    <!-- Kegiatan Multiyears -->
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <x-rap-form.select-component name="multiyears" id="select-multiyears" label="Kegiatan Multiyears <small class='text-muted'>(*wajib)</small>">
                            <option value="">Pilih...</option>
                            <option value="ya" {{ old('multiyears') ? (old('multiyears') == 'ya' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->multiyears == 'ya' ? 'selected' : '') }}>Ya</option>
                            <option value="tidak" {{ old('multiyears') ? (old('multiyears') == 'tidak' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->multiyears == 'tidak' ? 'selected' : '') }}>Tidak</option>
                        </x-rap-form.select-component>
                    </div>
                    <!-- Akhir Dari Kegiatan Multiyears -->

                    <!-- Waktu Mulai Pelaksanaan -->
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <x-rap-form.input-basic-component name="mulai" inputType="date" id="input-mulai" label="Waktu Mulai Pelaksanaan <small class='text-muted'>(*wajib)</small>" :value="old('mulai') ? old('mulai') : (request()->has('edit') && $edit_rap ? $edit_rap->mulai : '')" />
                    </div>
                    <!-- Akhir Dari Waktu Mulai Pelaksanaan -->

                    <!-- Waktu Selesai Pelaksanaan -->
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <x-rap-form.input-basic-component name="selesai" inputType="date" id="input-selesai" label="Waktu Selesai Pelaksanaan <small class='text-muted'>(*wajib)</small>" :value="old('selesai') ? old('selesai') : (request()->has('edit') && $edit_rap ? $edit_rap->selesai : '')" />
                    </div>
                    <!-- Akhir Dari Waktu Selesai Pelaksanaan -->

                    <!-- Jenis Kegiatan -->
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <x-rap-form.select-component name="jenis_kegiatan" id="select-jenis_kegiatan" label="Jenis Kegiatan <small class='text-muted'>(*wajib)</small>">
                            <option value="">Pilih...</option>
                            <option value="fisik" {{ old('jenis_kegiatan') ? (old('jenis_kegiatan') == 'fisik' ? 'selected' : '') : (request()->has('edit') && $edit_rap && $edit_rap->jenis_kegiatan == 'fisik' ? 'selected' : '') }}>Fisik</option>
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
                    <div id="div-koordinat" class="col-sm-12 col-md-12 col-lg-12" @if (request()->has('edit') && $edit_rap && $edit_rap->jenis_kegiatan == 'fisik') style="display: block;" @else style="display: none;" @endif>
                        <x-rap-form.textarea-component name="koordinat" id="input-koordinat" label="Koordinat Lokasi Fokus Kegiatan <a href='https://www.google.com/maps' target='_blank' class='text-info' data-bs-toggle='tooltip' data-bs-palcement='Top' data-bs-title='Buka Google Maps'>google maps <i class='fa-solid fa-up-right_from_square'></i></a> (<small>*wajib untuk jenis kegiatan fisik</small>)">
                            {{ old('koordinat') ? old('koordinat') : (request()->has('edit') && $edit_rap ? $edit_rap->koordinat : '') }}
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
                            {{ old('keterangan') ? old('keterangan') : (request()->has('edit') && $edit_rap ? $edit_rap->keterangan : '') }}
                        </x-rap-form.textarea-component>
                    </div>
                    <!-- Akhir Dari Keterangan -->

                    <div class="row">

                        @php
                            $isEdit = request()->has('edit') && $edit_rap;
                        @endphp

                        {{-- File KAK --}}
                        <div class="mb-4">
                            <label for="file_kak_name" class="form-label">Kerangka Acuan Kerja [KAK] <small class="text-muted">(*wajib)</small></label>
                            <div class="input-group">
                                <label class="input-group-text {{ $isEdit ? 'text-bg-danger' : '' }}" for="file_kak_name">
                                    @if ($isEdit)
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    @else
                                        File KAK
                                    @endif
                                </label>

                                <input type="file" name="file_kak_name" id="file_kak_name" accept=".pdf" class="form-control @error('file_kak_name') is-invalid @enderror" @if (!$isEdit || ($isEdit && !$edit_rap->file_kak_name)) required @endif>

                                @if ($isEdit && $edit_rap->file_kak_name)
                                    <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_kak_name }}" target="_blank" class="btn btn-outline-secondary">
                                        {{ $edit_rap->file_kak_name }}
                                    </a>
                                @endif
                            </div>
                            @error('file_kak_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- File RAB --}}
                        <div class="mb-4">
                            <label for="file_rab_name" class="form-label">Rencana Anggaran Biaya [RAB] <small class="text-muted">(*wajib)</small></label>
                            <div class="input-group">
                                <label class="input-group-text {{ $isEdit ? 'text-bg-danger' : '' }}" for="file_rab_name">
                                    @if ($isEdit)
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    @else
                                        File RAB
                                    @endif
                                </label>

                                <input type="file" name="file_rab_name" id="file_rab_name" accept=".pdf" class="form-control @error('file_rab_name') is-invalid @enderror" @if (!$isEdit || ($isEdit && !$edit_rap->file_rab_name)) required @endif>

                                @if ($isEdit && $edit_rap->file_rab_name)
                                    <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_rab_name }}" target="_blank" class="btn btn-outline-secondary">
                                        {{ $edit_rap->file_rab_name }}
                                    </a>
                                @endif
                            </div>
                            @error('file_rab_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- File Pendukung 1 --}}
                        <div class="mb-4">
                            <label for="file_pendukung1_name" class="form-label">File Pendukung 1</label>
                            <div class="input-group">
                                <label class="input-group-text {{ $isEdit ? 'text-bg-danger' : '' }}" for="file_pendukung1_name">
                                    @if ($isEdit)
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    @else
                                        File Tambahan
                                    @endif
                                </label>

                                <input type="file" name="file_pendukung1_name" id="file_pendukung1_name" accept=".pdf" class="form-control @error('file_pendukung1_name') is-invalid @enderror">

                                @if ($isEdit && $edit_rap->file_pendukung1_name)
                                    <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_pendukung1_name }}" target="_blank" class="btn btn-outline-secondary">
                                        {{ $edit_rap->file_pendukung1_name }}
                                    </a>
                                @endif
                            </div>
                            @error('file_pendukung1_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- File Pendukung 2 --}}
                        <div class="mb-4">
                            <label for="file_pendukung2_name" class="form-label">File Pendukung 2</label>
                            <div class="input-group">
                                <label class="input-group-text {{ $isEdit && $edit_rap->file_pendukung2_name ? 'text-bg-danger' : '' }}" for="file_pendukung2_name">
                                    @if ($isEdit && $edit_rap->file_pendukung2_name)
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    @else
                                        File Tambahan
                                    @endif
                                </label>

                                <input type="file" name="file_pendukung2_name" id="file_pendukung2_name" accept=".pdf" class="form-control @error('file_pendukung2_name') is-invalid @enderror">

                                @if ($isEdit && $edit_rap->file_pendukung2_name)
                                    <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_pendukung2_name }}" target="_blank" class="btn btn-outline-secondary">
                                        {{ $edit_rap->file_pendukung2_name }}
                                    </a>
                                @endif
                            </div>
                            @error('file_pendukung2_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- File Pendukung 3 --}}
                        <div class="mb-4">
                            <label for="file_pendukung3_name" class="form-label">File Pendukung 3</label>
                            <div class="input-group">
                                <label class="input-group-text {{ $isEdit && $edit_rap->file_pendukung3_name ? 'text-bg-danger' : '' }}" for="file_pendukung3_name">
                                    @if ($isEdit && $edit_rap->file_pendukung3_name)
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    @else
                                        File Tambahan
                                    @endif
                                </label>

                                <input type="file" name="file_pendukung3_name" id="file_pendukung3_name" accept=".pdf" class="form-control @error('file_pendukung3_name') is-invalid @enderror">

                                @if ($isEdit && $edit_rap->file_pendukung3_name)
                                    <a href="/rap/view/file?path={{ $edit_rap->file_path }}&name={{ $edit_rap->file_pendukung3_name }}" target="_blank" class="btn btn-outline-secondary">
                                        {{ $edit_rap->file_pendukung3_name }}
                                    </a>
                                @endif
                            </div>
                            @error('file_pendukung3_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    @include('script-home')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const textarea = document.getElementById("input-koordinat");
            if (textarea) {
                textarea.placeholder =
                    "Contoh:\n-2.3273906949173777, 138.01587621732185 | Ruas Jalan Otonom Burmeso KM 0;" +
                    "\n-2.328209223413783, 138.00490790225876 | Ruas Jalan Otonom Burmeso KM 1;";
            }
        });
    </script>
</x-app-layout-component>
