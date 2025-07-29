<!-- Modal -->
<div class="modal fade" id="editRapOpdModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editRapOpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>


            <div class="modal-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editRapOpdModalLabel">Edit RAP</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="form-edit-rap">
                    <div class="modal-body">
                        <input name="id" type="hidden" id="edit-hidden-id-rap">
                        <div class="mb-3">
                            <label for="edit-show-skpd" class="form-label">Perangkat Daerah</label>
                            <textarea class="form-control" id="edit-show-skpd" placeholder="Nama Perangkat Daerah" rows="3" disabled readonly>{{ $opd->nama_opd }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-show-target-aktifitas-utama" class="form-label">Target Aktifitas Utama</label>
                            <textarea class="form-control" id="edit-show-target-aktifitas-utama" placeholder="Target Aktifitas Utama" rows="3" disabled readonly></textarea>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edit-show-volume-target-aktifitas-utama" class="form-label">Kinerja Aktifitas</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="edit-show-volume-target-aktifitas-utama" placeholder="Kinerja Target" aria-describedby="edit-satuan-target-aktifitas-utama" disabled readonly>
                                        <label for="edit-show-volume-target-aktifitas-utama" class="input-group-text" id="edit-satuan-target-aktifitas-utama">Volume</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edit-show-sumberdana-target-aktifitas-utama" class="form-label">Sumber Dana Aktifitas</label>
                                    <input type="text" class="form-control" id="edit-show-sumberdana-target-aktifitas-utama" placeholder="Sumber Dana" disabled readonly>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-show-subkegiatan" class="form-label">Sub Kegiatan</label>
                            <textarea class="form-control" id="edit-show-subkegiatan" placeholder="Nama Sub Kegiatan" rows="3" disabled readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-show-indikator" class="form-label">Indikator Sub Kegiatan</label>
                            <textarea class="form-control" id="edit-show-indikator" placeholder="Indikator Sub Kegiatan" rows="3" disabled readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-vol_subkeg" class="form-label">Target Sub Kegiatan</label>
                            <div class="input-group">
                                <input name="vol_subkeg" type="text" class="form-control" id="edit-vol_subkeg" placeholder="Target" aria-label="Target Sub Kegiatan" aria-describedby="show-satuan-subkegiatan">
                                <label class="input-group-text" id="show-satuan-subkegiatan">Satuan Pendidikan</label>
                            </div>
                            <span class="text-danger" id="edit_vol_subkeg_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="edit-anggaran" class="form-label">Anggaran</label>
                            <div class="input-group">
                                <label class="input-group-text">Rp.</label>
                                <input name="anggaran" type="text" class="form-control" id="edit-anggaran" placeholder="000,00">
                            </div>
                            <span class="text-danger" id="edit_anggaran_error"></span>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edit-sumberdana" class="form-label">Sumber Dana</label>
                                    <select name="sumberdana" class="form-control" id="edit-sumberdana" disabled readonly>
                                        @if ($jenis == 'bg')
                                            <option value="Otsus 1%" selected>Otsus 1% (BG)</option>
                                        @elseif ($jenis == 'sg')
                                            <option value="Otsus 1,25%" selected>Otsus 1,25% (SG)</option>
                                        @else
                                            <option value="DTI" selected>DTI</option>
                                        @endif
                                    </select>
                                    <span class="text-danger" id="edit_sumberdana_error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edit-penerima-manfaat" class="form-label">Penerima Manfaat</label>
                                    <select name="penerima_manfaat" class="form-control" id="edit-penerima-manfaat">
                                        <option value="">Pilih...</option>
                                        <option value="oap">OAP</option>
                                        <option value="umum">Umum (OAP dan Non-OAP)</option>
                                    </select>
                                    <span class="text-danger" id="edit_penerima_manfaat_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="edit-lokasi" class="form-label">Lokasi Fokus</label>
                                    <select name="lokus[]" class="form-control select2-multiple" id="edit-lokasi" data-placeholder="Pilih.." multiple>
                                        @foreach ($lokasi as $key => $lokus)
                                            <option value="{{ $lokus->id }}">{{ $lokus->lokasi }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="edit_lokus_error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edit-mulai" class="form-label">Mulai Pelaksanaan</label>
                                    <input name="mulai" type="date" class="form-control" id="edit-mulai">
                                    <span class="text-danger" id="edit_mulai_error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edit-selesai" class="form-label">Selesai Pelaksanaan</label>
                                    <input name="selesai" type="date" class="form-control" id="edit-selesai">
                                    <span class="text-danger" id="edit_selesai_error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edit-rap-jenis_layanan" class="form-label">Jenis Layanan</label>
                                    <select name="jenis_layanan" class="form-control" id="edit-rap-jenis_layanan">
                                        <option value="">Pilih...</option>
                                        <option value="pendukung">Sub Kegiatan Pendukung</option>
                                        <option value="terkait">Terkait Langsung Ke Penerima Manfaat</option>
                                    </select>
                                    <span class="text-danger" id="edit_jenis_layanan_error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edit-rap-jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                                    <select name="jenis_kegiatan" class="form-control @error('jenis_kegiatan') is-invalid @enderror" id="edit-rap-jenis_kegiatan">
                                        <option value="">Pilih...</option>
                                        <option value="fisik">Fisik</option>
                                        <option value="nonfisik">Non Fisik</option>
                                    </select>
                                    <span class="text-danger" id="edit_jenis_kegiatan_error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edit-rap-ppsb" class="form-label">PPSB</label>
                                    <select name="ppsb" class="form-control @error('ppsb') is-invalid @enderror" id="edit-rap-ppsb">
                                        <option value="">Pilih...</option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                    <span class="text-danger" id="edit_ppsb_error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="edit-rap-multiyears" class="form-label">Multiyear</label>
                                    <select name="multiyears" class="form-control @error('multiyears') is-invalid @enderror" id="edit-rap-multiyears">
                                        <option value="">Pilih...</option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                    <span class="text-danger" id="edit_multiyears_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-koordinat" class="form-label">Koordinat Kegiataan</label>
                            <textarea name="koordinat" class="form-control" id="edit-koordinat" placeholder="Koordinat Kegiatan" rows="3"></textarea>
                            <span class="text-danger" id="edit_koordinat_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="edit-dana-lain" class="form-label">Sinergi Pendanaan Lain</label>
                            <select name="dana_lain[]" class="form-control select2-multiple" id="edit-dana-lain" data-placeholder="Pilih..." multiple>
                            </select>
                            <span class="text-danger" id="edit_dana_lain_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="edit-keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" id="edit-keterangan" placeholder="Keterangan" rows="3"></textarea>
                            <span class="text-danger" id="edit_keterangan_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="edit-catatan" class="form-label">Catatan Pembahasan</label>
                            <textarea name="catatan" class="form-control" id="edit-catatan" placeholder="Catatan Pembahasan" rows="3"></textarea>
                            <span class="text-danger" id="edit_catatan_error"></span>
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
</div>
