<x-app-layout-component :title="$app['title'] ?? null">

    <div class="row mb-5">
        <div class="col-10 mx-auto">
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
                <form method="post">
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="/rap/opd?id={{ $opd->id }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                        </div>
                        <hr>
                        @csrf
                        <input type="hidden" name="opd" value="{{ $opd->id }}">
                        <h5 class="card-title mb-4">NAMA SKPD : {{ $opd->text }}</h5>
                        <hr>
                        <h5 class="card-title text-center">INDIKATOR RIPPP DAN RAPPP</h5>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="rap-select-target-aktifitas" class="form-label">Target Aktifitas Utama</label>
                                    <select name="opd_tag_otsus" class="form-control select2 @error('opd_tag_otsus') is-invalid @enderror" id="rap-select-target-aktifitas" data-placeholder="Pilih...">
                                        <option></option>
                                        @foreach ($opd->tag_otsus as $tagging)
                                            <option value="{{ $tagging->id }}" data-kode="{{ $tagging->kode_target_aktifitas }}" data-sumberdana="{{ $tagging->sumberdana }}" data-volume="{{ $tagging->volume }}" data-satuan="{{ $tagging->satuan }}">{{ $tagging->target_aktifitas->text }}</option>
                                        @endforeach
                                    </select>
                                    @error('opd_tag_otsus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="rap-input-satuan-target-aktifitas" class="form-label">Satuan</label>
                                    <input type="text" class="form-control" id="rap-input-satuan-target-aktifitas" placeholder="Sauan Target Kinerja" disabled>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="rap-input-volume-target-aktifitas" class="form-label">Target Kinerja</label>
                                    <input type="number" class="form-control" id="rap-input-volume-target-aktifitas" placeholder="Target Kinerja" disabled>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="rap-input-sumberdana-target-aktifitas" class="form-label">Sumber Dana</label>
                                    <input type="text" class="form-control" id="rap-input-sumberdana-target-aktifitas" placeholder="Sumber Dana" disabled>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 class="card-title text-center">SUB KEGIATAN</h5>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="rap-select-subkegiatan" class="form-label">Sub Kegiatan</label>
                                    <select name="subkegiatan" class="form-control select2 @error('subkegiatan') is-invalid @enderror" id="rap-select-subkegiatan" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        @foreach ($opd->tag_bidang as $bidang)
                                            @foreach ($bidang->subkegiatan as $subkegiatan)
                                                @php
                                                    $subkeg_text = $subkegiatan->kode_subkegiatan . ' ' . $subkegiatan->uraian;
                                                @endphp
                                                <option value="{{ $subkegiatan->id }}" data-indikator="{{ $subkegiatan->indikator }}" data-klasifikasi_belanja="{{ $subkegiatan->klasifikasi_belanja }}" data-satuan="{{ $subkegiatan->satuan }}">{{ $subkeg_text }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    @error('subkegiatan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="rap-disabled-klasfikasi-belanja" class="form-label">Klasifikasi Belanja</label>
                                    <input type="text" class="form-control" id="rap-disabled-klasfikasi-belanja" placeholder="Klasifikasi Belanja" disabled>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="rap-select-sumberdana" class="form-label">Sumber Pendanaan</label>
                                    <select name="sumberdana" class="form-control select2 @error('sumberdana') is-invalid @enderror" id="rap-select-sumberdana" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="Otsus 1%">Otsus Block Grant 1%</option>
                                        <option value="Otsus 1,25%">Otsus Spesifict Grant 1,25%</option>
                                        <option value="DTI">Dana Tambahan Infrastruktur</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="rap-disabled-indikator-subkegiatan" class="form-label">Indikator Sub Kegiatan</label>
                                    <textarea class="form-control" id="rap-disabled-indikator-subkegiatan" rows="5" placeholder="Indikator Sub Kegiatan" disabled></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="rap-disabled-satuan-subkegiatan" class="form-label">Satuan</label>
                                            <input type="text" class="form-control" id="rap-disabled-satuan-subkegiatan" placeholder="Satuan" disabled>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="rap-input-target-subkegiatan" class="form-label">Target Sub Kegiatan</label>
                                            <input name="vol_subkeg" type="number" class="form-control @error('vol_subkeg') is-invalid @enderror" value="{{ old('vol_subkeg') ? old('vol_subkeg') : '' }}" id="rap-input-target-subkegiatan" placeholder="Target">
                                            @error('vol_subkeg')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="mb-3">
                                            <label for="rap-input-anggaran-subkegiatan" class="form-label">Anggaran Sub Kegiatan</label>
                                            <input name="anggaran" type="number" class="form-control @error('anggaran') is-invalid @enderror" value="{{ old('anggaran') ? old('anggaran') : '' }}" id="rap-input-anggaran-subkegiatan" placeholder="Anggaran">
                                            @error('anggaran')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="rap-select-jenis-kegiatan" class="form-label">Jenis Kegiatan</label>
                                            <select name="jenis_kegiatan" class="form-control @error('jenis_kegiatan') is-invalid @enderror" id="rap-select-jenis-kegiatan">
                                                <option value="">Pilih...</option>
                                                <option {{ old('jenis_kegiatan') == 'fisik' ? 'selected' : '' }} value="fisik">Fisik</option>
                                                <option {{ old('jenis_kegiatan') == 'nonfisik' ? 'selected' : '' }} value="nonfisik">Non Fisik</option>
                                            </select>
                                            @error('jenis_kegiatan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="rap-input-mulai-pelaksanaan" class="form-label">Mulai Pelaksanaan Kegiatan</label>
                                    <input name="mulai" type="date" class="form-control @error('mulai') is-invalid @enderror" value="{{ old('mulai') ? old('mulai') : '2025-01-01' }}" id="rap-input-mulai-pelaksanaan" placeholder="dd-mm-yyyy">
                                    @error('mulai')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="rap-input-selesai-pelaksanaan" class="form-label">Selesai Pelaksanaan Kegiatan</label>
                                    <input name="selesai" type="date" class="form-control @error('selesai') is-invalid @enderror" value="{{ old('selesai') ? old('selesai') : '2025-11-01' }}" id="rap-selesai-input-pelaksanaan" placeholder="dd-mm-yyyy">
                                    @error('selesai')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="rap-select-penerima-manfaat" class="form-label">Penerima Manfaat</label>
                                    <select name="penerima_manfaat" class="form-control @error('penerima_manfaat') is-invalid @enderror" id="rap-select-penerima-manfaat">
                                        <option value="">Pilih...</option>
                                        <option {{ old('penerima_manfaat') == 'oap' ? 'selected' : '' }} value="oap">OAP</option>
                                        <option {{ old('penerima_manfaat') == 'umum' ? 'selected' : '' }} value="umum">Umum (OAP dan Non-OAP)</option>
                                    </select>
                                    @error('penerima_manfaat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="rap-select-jenis-layanan" class="form-label">Jenis Layanan</label>
                                    <select name="jenis_layanan" class="form-control @error('jenis_layanan') is-invalid @enderror" id="rap-select-jenis-layanan">
                                        <option value="">Pilih...</option>
                                        <option {{ old('jenis_layanan') == 'pendukung' ? 'selected' : '' }} value="pendukung">Sub Kegiatan Pendukung</option>
                                        <option {{ old('jenis_layanan') == 'terkait' ? 'selected' : '' }} value="terkait">Terkait Langsung Ke Penerima Manfaat</option>
                                    </select>
                                    @error('jenis_layanan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="mb-3">
                                    <label for="rap-select-ppsb" class="form-label">PPSB</label>
                                    <select name="ppsb" class="form-control @error('ppsb') is-invalid @enderror" id="rap-select-ppsb">
                                        <option value="">Pilih...</option>
                                        <option {{ old('ppsb') == 'ya' ? 'selected' : '' }} value="ya">Ya</option>
                                        <option {{ old('ppsb') == 'tidak' ? 'selected' : '' }} value="tidak">Tidak</option>
                                    </select>
                                    @error('ppsb')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="mb-3">
                                    <label for="rap-select-multiyears" class="form-label">Multiyears</label>
                                    <select name="multiyears" class="form-control @error('multiyears') is-invalid @enderror" id="rap-select-multiyears">
                                        <option value="">Pilih...</option>
                                        <option {{ old('multiyears') == 'ya' ? 'selected' : '' }} value="ya">Ya</option>
                                        <option {{ old('multiyears') == 'tidak' ? 'selected' : '' }} value="tidak">Tidak</option>
                                    </select>
                                    @error('multiyears')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="rap-select-dana-lain" class="form-label">Sinergi Pendanaan Lain</label>
                                    <select name="dana_lain[]" class="form-control select2-multiple @error('dana_lain') is-invalid @enderror" id="rap-select-dana-lain" data-placeholder="Pilih..." multiple>
                                        <option></option>
                                        @foreach ($sumberdanas as $sumberdana)
                                            <option {{ in_array($sumberdana->id, old('dana_lain', [])) ? 'selected' : '' }} value="{{ $sumberdana->id }}">{{ $sumberdana->uraian }}</option>
                                        @endforeach
                                    </select>
                                    @error('dana_lain')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="rap-select-lokus" class="form-label">Lokasi Fokus Kegiatan</label>
                                    <select name="lokus[]" class="form-control select2-multiple @error('lokus') is-invalid @enderror" id="rap-select-lokus" data-placeholder="Pilih..." multiple>
                                        <option></option>
                                        @foreach ($lokasi as $lokus)
                                            <option {{ in_array($lokus->id, old('lokus', [])) ? 'selected' : '' }} value="{{ $lokus->id }}">{{ $lokus->text }}</option>
                                        @endforeach
                                    </select>
                                    @error('lokus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="rap-textarea-koordinat" class="form-label">Koordinat Lokasi Kegiatan</label>
                                    <textarea name="koordinat" class="form-control @error('koordinat') is-invalid @enderror" value="{{ old('koordinat') ? old('koordinat') : '2025-01-01' }}" id="rap-textarea-koordinat" rows="6" placeholder="Koordinat Lokasi Kegiatan"></textarea>
                                    @error('koordinat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3">
                                <label for="rap-textarea-keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" id="rap-textarea-keterangan" rows="6" placeholder="Keterangan">{{ old('keterangan') ? old('keterangan') : null }}</textarea>
                                @error('keterangan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <h5 class="card-title text-center">FILE DOKUMEN TEKNIS</h5>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="rap-upload-file-kak" class="form-label">Upload File Kerangka Acuan Kerja (KAK) (wajib):</label>
                                    <input name="file_kak" class="form-control" type="file" id="rap-upload-file-kak">
                                    @error('file_kak')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="rap-upload-file-rab" class="form-label">Upload File Rincian Anggaran Belanja (RAB) (wajib):</label>
                                    <input name="file_rab" class="form-control" type="file" id="rap-upload-file-rab">
                                    @error('file_rab')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="rap-upload-file-dukung-1" class="form-label">Upload File Pendukung Lainnya (pilihan):</label>
                                    <input name="file_dukung_1" class="form-control" type="file" id="rap-upload-file-dukung-1">
                                    @error('file_dukung_1')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="rap-upload-file--dukung-2" class="form-label">Upload File Pendukung Lainnya (pilihan):</label>
                                    <input name="file_dukung_2" class="form-control" type="file" id="rap-upload-file--dukung-2">
                                    @error('file_dukung_2')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="/rap/opd?id={{ $opd->id }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('rap.rap-script')
</x-app-layout-component>
