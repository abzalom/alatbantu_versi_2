<!-- Modal -->
<div class="modal fade" id="RapRenjaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="RapRenjaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-new_rap_renja-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-new_rap_renja-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="RapRenjaModalLabel">Tambahkan RAP Sub Kegiatan {{ $sumberdana }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-renja-rap">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="rap-renja-opd" name="opd" value="{{ $opd->id }}">
                        <input type="hidden" id="rap-renja-tahun" name="tahun" value="{{ session()->get('tahun') }}">
                        <div class="mb-3">
                            <label for="rap-renja-opd_tag_otsus" class="form-label">Target Aktifitas</label>
                            <select name="opd_tag_otsus" class="form-control select2" id="rap-renja-opd_tag_otsus" data-placeholder="Pilih...">
                                <option></option>
                                @foreach ($taggings as $itemSelectTag)
                                    <option value="{{ $itemSelectTag->id }}" data-volume="{{ $itemSelectTag->volume }}">{{ $itemSelectTag->target_text }}</option>
                                @endforeach
                            </select>
                            <span id="new_opd_tag_otsus_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="rap-renja-volume-tag" class="form-label">Volume Target Aktifitas</label>
                            <div class="input-group">
                                <input type="text" class="form-control format-angka" id="rap-renja-volume-tag" placeholder="Volume" aria-describedby="new-volume-tag-addon" disabled readonly>
                                <span class="input-group-text" id="rap-renja-volume-tag-addon">Satuan</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="rap-renja-subkegiatan" class="form-label">Sub Kegiatan</label>
                            <select name="subkegiatan" class="form-control select2" id="rap-renja-subkegiatan" data-placeholder="Pilih..." disabled>
                                <option></option>
                            </select>
                            <span id="new_subkegiatan_error" class="text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label for="rap-renja-klasifikasi_belanja" class="form-label">Klasifikasi Belanja</label>
                            <textarea class="form-control" id="rap-renja-klasifikasi_belanja" placeholder="Klasifikasi Belanja" disabled readonly></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="rap-renja-indikator_subkegiatan" class="form-label">Indikator Sub Kegiatan</label>
                            <textarea class="form-control" id="rap-renja-indikator_subkegiatan" placeholder="Indikator Sub Kegiatan" rows="3" disabled readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="rap-renja-vol_subkeg" class="form-label">Kinerja Sub Kegiatan</label>
                            <div class="input-group">
                                <input name="vol_subkeg" type="text" class="form-control format-angka" id="rap-renja-vol_subkeg" placeholder="Volume" disabled>
                                <span id="satuan-subkegiatan" class="input-group-text">Satuan</span>
                            </div>
                            <span id="new_vol_subkeg_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="rap-renja-anggaran" class="form-label">Anggaran</label>
                            <div class="input-group">
                                <span class="input-group-text" id="rap-renja-anggaran-icon">Rp</span>
                                <input name="anggaran" type="text" class="form-control format-angka" id="rap-renja-anggaran" placeholder="000,00" aria-describedby="new-anggaran-icon" disabled>
                            </div>
                            <span id="new_anggaran_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="rap-renja-penerima_manfaat" class="form-label">Penerima Manfaat</label>
                                    <select name="penerima_manfaat" class="form-control select2" id="rap-renja-penerima_manfaat" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="oap">Khusus OAP</option>
                                        <option value="umum">Umum (OAP & Non OAP)</option>
                                    </select>
                                    <span id="new_penerima_manfaat_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="rap-renja-jenis_layanan" class="form-label">Jenis Layanan</label>
                                    <select name="jenis_layanan" class="form-control select2" id="rap-renja-jenis_layanan" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="pendukung">Sub Kegiatan Pendukung</option>
                                        <option value="terkait">Terkait Ke Penerima Manfaat</option>
                                    </select>
                                    <span id="new_jenis_layanan_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="rap-renja-jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                                    <select name="jenis_kegiatan" class="form-control select2" id="rap-renja-jenis_kegiatan" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="fisik">Fisik</option>
                                        <option value="nonfisik">Non Fisik</option>
                                    </select>
                                    <span id="new_jenis_kegiatan_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="rap-renja-ppsb" class="form-label">PPSB</label>
                                    <select name="ppsb" class="form-control select2" id="rap-renja-ppsb" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                    <span id="new_ppsb_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="rap-renja-multiyears" class="form-label">Multiyears</label>
                                    <select name="multiyears" class="form-control select2" id="rap-renja-multiyears" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                    <span id="new_multiyears_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="rap-renja-mulai" class="form-label">Mulai Pelaksanaan</label>
                                    <input name="mulai" type="date" class="form-control" id="rap-renja-mulai" disabled>
                                    <span id="new_mulai_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="rap-renja-selesai" class="form-label">Selesai Pelaksanaan</label>
                                    <input name="selesai" type="date" class="form-control" id="rap-renja-selesai" disabled>
                                    <span id="new_selesai_error" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="rap-renja-dana_lain" class="form-label">Sumber Pendanaan Lainnya</label>
                            <select name="dana_lain[]" class="form-control select2-multiple" id="rap-renja-dana_lain" data-placeholder="Pilih..." multiple disabled>
                                @foreach ($dana_lain as $newDanaLain)
                                    <option value="{{ $newDanaLain->id }}">{{ $newDanaLain->uraian }}</option>
                                @endforeach
                            </select>
                            <span id="new_dana_lain_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="rap-renja-lokus" class="form-label">Lokasi Fokus</label>
                            <select name="lokus[]" class="form-control select2-multiple" id="rap-renja-lokus" data-placeholder="Pilih..." multiple disabled>
                                @foreach ($lokasi as $newLokus)
                                    <option value="{{ $newLokus->id }}">{{ $newLokus->lokasi }}</option>
                                @endforeach
                            </select>
                            <span id="new_lokus_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="rap-renja-koordinat" class="form-label">Koordinat Lokasi Kegiatan <a href="https://www.google.com/maps" target="_blank" class="text-info" data-bs-toggle="tooltip" data-bs-palcement="Top" data-bs-title="Buka Google Maps">google maps <i class="fa-solid fa-up-right-from-square"></i></a>
                            </label>
                            <textarea name="koordinat" class="form-control" id="rap-renja-koordinat" placeholder="" rows="4" disabled></textarea>
                            <span id="new_koordinat_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="rap-renja-keterangan" class="form-label">Keterangan <small>(wajib)</small></label>
                            <textarea name="keterangan" class="form-control" id="rap-renja-keterangan" placeholder="Keterangan wajib di isi!" rows="3" disabled></textarea>
                            <span id="new_keterangan_error" class="text-danger"></span>
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
                            .new-rap-file-upload::file-selector-button {
                                display: none;
                                /* content: 'Pilih File'; */
                            }
                        </style>
                        <div class="mb-4">
                            <label for="rap-renja-file-kak" class="form-label">Kerangka Acuan Kerja [KAK] <small class="text-muted">(*wajib)</small></label>
                            <div class="input-group">
                                <label class="input-group-text" for="rap-renja-file-kak" style="cursor: pointer">File KAK</label>
                                <input name="file_kak_name" class="form-control new-rap-file-upload" type="file" id="rap-renja-file-kak" accept=".pdf" disabled>
                            </div>
                            <span id="new_file_kak_name_error" class="text-danger"></span>
                        </div>
                        <div class="mb-4">
                            <label for="rap-renja-file-rab" class="form-label">Rencana Anggaran Biaya [RAB] <small class="text-muted">(*wajib)</small></label>
                            <div class="input-group">
                                <label class="input-group-text" for="rap-renja-file-rab" style="cursor: pointer">File RAB</label>
                                <input name="file_rab_name" class="form-control new-rap-file-upload" type="file" id="rap-renja-file-rab" accept=".pdf" disabled>
                            </div>
                            <span id="new_file_rab_name_error" class="text-danger"></span>
                        </div>
                        <div class="mb-4">
                            <label for="rap-renja-file-pendukung1" class="form-label">File pendukung lainnya</small></label>
                            <div class="input-group">
                                <label class="input-group-text" for="rap-renja-file-pendukung1" style="cursor: pointer">File Pendukung 1</label>
                                <input name="file_pendukung1_name" class="form-control new-rap-file-upload" type="file" id="rap-renja-file-pendukung1" accept=".pdf" disabled>
                            </div>
                            <span id="new_file_pendukung1_name_error" class="text-danger"></span>
                        </div>
                        <div class="mb-4">
                            <label for="rap-renja-file-pendukung2" class="form-label">File pendukung lainnya</small></label>
                            <div class="input-group">
                                <label class="input-group-text" for="rap-renja-file-pendukung2" style="cursor: pointer">File Pendukung 2</label>
                                <input name="file_pendukung2_name" class="form-control new-rap-file-upload" type="file" id="rap-renja-file-pendukung2" accept=".pdf" disabled>
                            </div>
                            <span id="new_file_pendukung2_name_error" class="text-danger"></span>
                        </div>
                        <div class="mb-4">
                            <label for="rap-renja-file-pendukung3" class="form-label">File pendukung lainnya</small></label>
                            <div class="input-group">
                                <label class="input-group-text" for="rap-renja-file-pendukung3" style="cursor: pointer">File Pendukung 3</label>
                                <input name="file_pendukung3_name" class="form-control new-rap-file-upload" type="file" id="rap-renja-file-pendukung3" accept=".pdf" disabled>
                            </div>
                            <span id="new_file_pendukung3_name_error" class="text-danger"></span>
                        </div>
                        <div class="mb-4">
                            <label for="rap-renja-link-file-dukung-lain" class="form-label">Link file pendukung lainnya <small class="text-muted">(Google Drive)</small></label>
                            <input name="link_file_dukung_lain" type="text" class="form-control" id="rap-renja-link-file-dukung-lain" placeholder="Link google drive" disabled>
                            <span id="new_link_file_dukung_lain_error" class="text-danger"></span>
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

    <script>
        document.getElementById("new-koordinat").placeholder =
            "Contoh:\n-2.3273906949173777, 138.01587621732185 | Ruas Jalan Otonom Burmeso KM 0;"
        // +"\n-2.328209223413783, 138.00490790225876 | Ruas Jalan Otonom Burmeso KM 1,34";
    </script>
</div>
