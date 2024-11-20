<div class="modal fade" id="editRapModal" tabindex="-1" aria-labelledby="editRapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editRapModalLabel">Edit RAP</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-rap-otsus" action="/rap/opd/form-update-subkegiatan" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-rap-id">

                    <div id="edit-rap-spinner" class="text-center" style="display: block">
                        <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="edit-rap-show-data" style="display: none">
                        <div class="mb-3">
                            <label for="edit-rap-disabled-opd" class="form-label">Prangkat Daerah</label>
                            <textarea class="form-control" id="edit-rap-disabled-opd" rows="3" disabled>{{ $opd->text }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-rap-disabled-target-aktifitas" class="form-label">Target Aktifitas Utama</label>
                            <textarea class="form-control" id="edit-rap-disabled-target-aktifitas" rows="3" disabled></textarea>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="edit-rap-disabled-target-aktifitas-satuan" class="form-label">Satuan Target</label>
                                    <input type="text" class="form-control" id="edit-rap-disabled-target-aktifitas-satuan" placeholder="Satuan Target" disabled>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="edit-rap-disabled-target-aktifitas-kinerja" class="form-label">Kinerja Target</label>
                                    <input type="text" class="form-control" id="edit-rap-disabled-target-aktifitas-kinerja" placeholder="Kinerja Target" disabled>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="edit-rap-disabled-target-aktifitas-sumberdana" class="form-label">Sumber Dana</label>
                                    <input type="text" class="form-control" id="edit-rap-disabled-target-aktifitas-sumberdana" placeholder="Sumber Dana" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit-rap-disabled-subkegiatan" class="form-label">Sub Kegiatan</label>
                            <textarea class="form-control" id="edit-rap-disabled-subkegiatan" rows="3" disabled></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="mb-3">
                                    <label for="edit-rap-sumberdana" class="form-label">Sumber Pendanaan</label>
                                    <select name="sumberdana" class="form-control @error('sumberdana') is-invalid @enderror" id="edit-rap-sumberdana">
                                        <option value="">Pilih...</option>
                                        <option value="Otsus 1%">Otsus Block Grant 1%</option>
                                        <option value="Otsus 1,25%">Otsus Spesific Grant 1,25%</option>
                                        <option value="DTI">Dana Tambahan Infrastruktur</option>
                                    </select>
                                    <span class="text-danger" id="sumberdana_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="mb-3">
                                    <label for="edit-rap-vol_subkeg" class="form-label">Target</label>
                                    <input name="vol_subkeg" type="number" class="form-control" id="edit-rap-vol_subkeg" placeholder="Target">
                                    <span class="text-danger" id="vol_subkeg_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="mb-3">
                                    <label for="edit-rap-anggaran" class="form-label">Anggaran</label>
                                    <input name="anggaran" type="number" class="form-control @error('anggaran') is-invalid @enderror" id="edit-rap-anggaran" placeholder="Anggaran">
                                    <span class="text-danger" id="anggaran_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="mb-3">
                                    <label for="edit-rap-mulai" class="form-label">Mulai Pelaksanaan</label>
                                    <input name="mulai" type="date" class="form-control @error('mulai') is-invalid @enderror" id="edit-rap-mulai">
                                    <span class="text-danger" id="mulai_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="mb-3">
                                    <label for="edit-rap-selesai" class="form-label">Selesai Pelaksanaan</label>
                                    <input name="selesai" type="date" class="form-control @error('selesai') is-invalid @enderror" id="edit-rap-selesai">
                                    <span class="text-danger" id="selesai_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6">
                                <div class="mb-3">
                                    <label for="edit-rap-penerima_manfaat" class="form-label">Penerima Manfaat</label>
                                    <select name="penerima_manfaat" class="form-control @error('penerima_manfaat') is-invalid @enderror" id="edit-rap-penerima_manfaat">
                                        <option value="">Pilih...</option>
                                        <option value="oap">OAP</option>
                                        <option value="umum">Umum (OAP dan Non-OAP)</option>
                                    </select>
                                    <span class="text-danger" id="penerima_manfaat_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="mb-3">
                                    <label for="edit-rap-jenis_layanan" class="form-label">Jenis Layanan</label>
                                    <select name="jenis_layanan" class="form-control @error('jenis_layanan') is-invalid @enderror" id="edit-rap-jenis_layanan">
                                        <option value="">Pilih...</option>
                                        <option value="pendukung">Sub Kegiatan Pendukung</option>
                                        <option value="terkait">Terkait Langsung Ke Penerima Manfaat</option>
                                    </select>
                                    <span class="text-danger" id="jenis_layanan_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-4 col-sm-4">
                                <div class="mb-3">
                                    <label for="edit-rap-jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                                    <select name="jenis_kegiatan" class="form-control @error('jenis_kegiatan') is-invalid @enderror" id="edit-rap-jenis_kegiatan">
                                        <option value="">Pilih...</option>
                                        <option value="fisik">Fisik</option>
                                        <option value="nonfisik">Non Fisik</option>
                                    </select>
                                    <span class="text-danger" id="jenis_kegiatan_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4">
                                <div class="mb-3">
                                    <label for="edit-rap-ppsb" class="form-label">PPSB</label>
                                    <select name="ppsb" class="form-control @error('ppsb') is-invalid @enderror" id="edit-rap-ppsb">
                                        <option value="">Pilih...</option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                    <span class="text-danger" id="ppsb_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4">
                                <div class="mb-3">
                                    <label for="edit-rap-multiyears" class="form-label">Multiyear</label>
                                    <select name="multiyears" class="form-control @error('multiyears') is-invalid @enderror" id="edit-rap-multiyears">
                                        <option value="">Pilih...</option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                    <span class="text-danger" id="multiyears_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12">
                                <div class="mb-3">
                                    <label for="edit-rap-dana_lain" class="form-label">Sinergi Pendanaan Lain</label>
                                    <select name="dana_lain[]" class="form-control select2-multiple @error('dana_lain') is-invalid @enderror" id="edit-rap-dana_lain" data-placeholder="Pilih..." multiple>
                                        @foreach ($referensi['sumberdana'] as $dana_lain)
                                            <option value="{{ $dana_lain->id }}">{{ $dana_lain->uraian }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="dana_lain_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12">
                                <div class="mb-3">
                                    <label for="edit-rap-lokus" class="form-label">Lokasi Fokus</label>
                                    <select name="lokus[]" class="form-control select2-multiple @error('lokus') is-invalid @enderror" id="edit-rap-lokus" data-placeholder="Pilih..." multiple>
                                        @foreach ($referensi['lokus'] as $lokus)
                                            <option value="{{ $lokus->id }}">{{ $lokus->text }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="lokus_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-rap-koordinat" class="form-label">Koordinat Lokasi Kegiatan</label>
                            <textarea name="koordinat" class="form-control @error('koordinat') is-invalid @enderror" id="edit-rap-koordinat" rows="3"></textarea>
                            <span class="text-danger" id="koordinat_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="edit-rap-keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" id="edit-rap-keterangan" rows="3"></textarea>
                            <span class="text-danger" id="keterangan_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="edit-rap-catatan" class="form-label">Catatan Pembahasan</label>
                            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" id="edit-rap-catatan" rows="3"></textarea>
                            <span class="text-danger" id="catatan_error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
