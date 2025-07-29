<!-- Modal -->
<div class="modal fade" id="addRapOpdModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addRapOpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-add_new_rap-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-add_new_rap-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addRapOpdModalLabel">Tambahkan RAP Sub Kegiatan {{ $sumberdana }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-new-rap">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="new-opd" name="opd" value="{{ $opd->id }}">
                        <input type="hidden" id="new-tahun" name="tahun" value="{{ session()->get('tahun') }}">
                        <div class="mb-3">
                            <label for="new-opd_tag_otsus" class="form-label">Target Aktifitas</label>
                            <select name="opd_tag_otsus" class="form-control select2" id="new-opd_tag_otsus" data-placeholder="Pilih...">
                                <option></option>
                                @foreach ($taggings as $itemSelectTag)
                                    <option value="{{ $itemSelectTag->id }}" data-volume="{{ $itemSelectTag->volume }}">{{ $itemSelectTag->target_text }}</option>
                                @endforeach
                            </select>
                            <span id="new_opd_tag_otsus_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="new-volume-tag" class="form-label">Volume Target Aktifitas</label>
                            <div class="input-group">
                                <input type="text" class="form-control format-angka" id="new-volume-tag" placeholder="Volume" aria-describedby="new-volume-tag-addon" disabled readonly>
                                <span class="input-group-text" id="new-volume-tag-addon">Satuan</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new-subkegiatan" class="form-label">Sub Kegiatan</label>
                            <select name="subkegiatan" class="form-control select2" id="new-subkegiatan" data-placeholder="Pilih..." disabled>
                                <option></option>
                            </select>
                            <span id="new_subkegiatan_error" class="text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label for="new-klasifikasi_belanja" class="form-label">Klasifikasi Belanja</label>
                            <textarea class="form-control" id="new-klasifikasi_belanja" placeholder="Klasifikasi Belanja" disabled readonly></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="new-indikator_subkegiatan" class="form-label">Indikator Sub Kegiatan</label>
                            <textarea class="form-control" id="new-indikator_subkegiatan" placeholder="Indikator Sub Kegiatan" rows="3" disabled readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="new-vol_subkeg" class="form-label">Kinerja Sub Kegiatan</label>
                            <div class="input-group">
                                <input name="vol_subkeg" type="text" class="form-control format-angka" id="new-vol_subkeg" placeholder="Volume" disabled>
                                <span id="satuan-subkegiatan" class="input-group-text">Satuan</span>
                            </div>
                            <span id="new_vol_subkeg_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="new-anggaran" class="form-label">Anggaran</label>
                            <div class="input-group">
                                <span class="input-group-text" id="new-anggaran-icon">Rp</span>
                                <input name="anggaran" type="text" class="form-control format-angka" id="new-anggaran" placeholder="000,00" aria-describedby="new-anggaran-icon" disabled>
                            </div>
                            <span id="new_anggaran_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="new-penerima_manfaat" class="form-label">Penerima Manfaat</label>
                                    <select name="penerima_manfaat" class="form-control select2" id="new-penerima_manfaat" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="oap">Khusus OAP</option>
                                        <option value="umum">Umum (OAP & Non OAP)</option>
                                    </select>
                                    <span id="new_penerima_manfaat_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="new-jenis_layanan" class="form-label">Jenis Layanan</label>
                                    <select name="jenis_layanan" class="form-control select2" id="new-jenis_layanan" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="pendukung">Sub Kegiatan Pendukung</option>
                                        <option value="terkait">Terkait Ke Penerima Manfaat</option>
                                    </select>
                                    <span id="new_jenis_layanan_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="new-jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                                    <select name="jenis_kegiatan" class="form-control select2" id="new-jenis_kegiatan" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="fisik">Fisik</option>
                                        <option value="nonfisik">Non Fisik</option>
                                    </select>
                                    <span id="new_jenis_kegiatan_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="new-ppsb" class="form-label">PPSB</label>
                                    <select name="ppsb" class="form-control select2" id="new-ppsb" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                    <span id="new_ppsb_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="new-multiyears" class="form-label">Multiyears</label>
                                    <select name="multiyears" class="form-control select2" id="new-multiyears" data-placeholder="Pilih..." disabled>
                                        <option></option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                    <span id="new_multiyears_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="new-mulai" class="form-label">Mulai Pelaksanaan</label>
                                    <input name="mulai" type="date" class="form-control" id="new-mulai" disabled>
                                    <span id="new_mulai_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="new-selesai" class="form-label">Selesai Pelaksanaan</label>
                                    <input name="selesai" type="date" class="form-control" id="new-selesai" disabled>
                                    <span id="new_selesai_error" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new-dana_lain" class="form-label">Sumber Pendanaan Lainnya</label>
                            <select name="dana_lain[]" class="form-control select2-multiple" id="new-dana_lain" data-placeholder="Pilih..." multiple disabled>
                                @foreach ($dana_lain as $newDanaLain)
                                    <option value="{{ $newDanaLain->id }}">{{ $newDanaLain->uraian }}</option>
                                @endforeach
                            </select>
                            <span id="new_dana_lain_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="new-lokus" class="form-label">Lokasi Fokus</label>
                            <select name="lokus[]" class="form-control select2-multiple" id="new-lokus" data-placeholder="Pilih..." multiple disabled>
                                @foreach ($lokasi as $newLokus)
                                    <option value="{{ $newLokus->id }}">{{ $newLokus->lokasi }}</option>
                                @endforeach
                            </select>
                            <span id="new_lokus_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="new-koordinat" class="form-label">Koordinat Lokasi Kegiatan <a href="https://www.google.com/maps" target="_blank" class="text-info" data-bs-toggle="tooltip" data-bs-palcement="Top" data-bs-title="Buka Google Maps">google maps <i class="fa-solid fa-up-right-from-square"></i></a>
                            </label>
                            <textarea name="koordinat" class="form-control" id="new-koordinat" placeholder="" rows="4" disabled></textarea>
                            <span id="new_koordinat_error" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label for="new-keterangan" class="form-label">Keterangan <small>(wajib)</small></label>
                            <textarea name="keterangan" class="form-control" id="new-keterangan" placeholder="Keterangan wajib di isi!" rows="3" disabled></textarea>
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
                            <label for="new-file-kak" class="form-label">Kerangka Acuan Kerja [KAK] <small class="text-muted">(*wajib)</small></label>
                            <div class="input-group">
                                <label class="input-group-text" for="new-file-kak" style="cursor: pointer">File KAK</label>
                                <input name="file_kak_name" class="form-control new-rap-file-upload" type="file" id="new-file-kak" accept=".pdf" disabled>
                            </div>
                            <span id="new_file_kak_name_error" class="text-danger"></span>
                        </div>
                        <div class="mb-4">
                            <label for="new-file-rab" class="form-label">Rencana Anggaran Biaya [RAB] <small class="text-muted">(*wajib)</small></label>
                            <div class="input-group">
                                <label class="input-group-text" for="new-file-rab" style="cursor: pointer">File RAB</label>
                                <input name="file_rab_name" class="form-control new-rap-file-upload" type="file" id="new-file-rab" accept=".pdf" disabled>
                            </div>
                            <span id="new_file_rab_name_error" class="text-danger"></span>
                        </div>
                        <div class="mb-4">
                            <label for="new-file-pendukung1" class="form-label">File pendukung lainnya</small></label>
                            <div class="input-group">
                                <label class="input-group-text" for="new-file-pendukung1" style="cursor: pointer">File Pendukung 1</label>
                                <input name="file_pendukung1_name" class="form-control new-rap-file-upload" type="file" id="new-file-pendukung1" accept=".pdf" disabled>
                            </div>
                            <span id="new_file_pendukung1_name_error" class="text-danger"></span>
                        </div>
                        <div class="mb-4">
                            <label for="new-file-pendukung2" class="form-label">File pendukung lainnya</small></label>
                            <div class="input-group">
                                <label class="input-group-text" for="new-file-pendukung2" style="cursor: pointer">File Pendukung 2</label>
                                <input name="file_pendukung2_name" class="form-control new-rap-file-upload" type="file" id="new-file-pendukung2" accept=".pdf" disabled>
                            </div>
                            <span id="new_file_pendukung2_name_error" class="text-danger"></span>
                        </div>
                        <div class="mb-4">
                            <label for="new-file-pendukung3" class="form-label">File pendukung lainnya</small></label>
                            <div class="input-group">
                                <label class="input-group-text" for="new-file-pendukung3" style="cursor: pointer">File Pendukung 3</label>
                                <input name="file_pendukung3_name" class="form-control new-rap-file-upload" type="file" id="new-file-pendukung3" accept=".pdf" disabled>
                            </div>
                            <span id="new_file_pendukung3_name_error" class="text-danger"></span>
                        </div>
                        <div class="mb-4">
                            <label for="new-link-file-dukung-lain" class="form-label">Link file pendukung lainnya <small class="text-muted">(Google Drive)</small></label>
                            <input name="link_file_dukung_lain" type="text" class="form-control" id="new-link-file-dukung-lain" placeholder="Link google drive" disabled>
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
