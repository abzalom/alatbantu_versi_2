<x-app-layout-component :title="$app['title'] ?? null">

    <div class="row mb-3">
        <div class="col-md-12 mb-4">
            <div class="card border-secondary">
                <div class="card-header">
                    <h5 class="card-title">
                        Referensi Lokasi Kegiatan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="lokasi-search" class="form-label">Cari Lokasi</label>
                                <select class="form-control select2-multiple" id="lokasi-search" data-placeholder="Cari Lokasi" multiple>
                                    @foreach ($data['lokasi'] as $searchLokasi)
                                        <option value="{{ $searchLokasi->id . '~' . $searchLokasi->kampung }}">{{ $searchLokasi->text }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lokasi-result-search" class="form-label">Hasil Pencarian Lokasi <a id="lokasi-copy-result" href="#"><i class="fa-regular fa-clipboard"></i></a></label>
                                <textarea class="form-control" id="lokasi-result-search" rows="3" placeholder="Hasil Pencarian Lokasi" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-4">
            <div class="card border-primary">
                <div class="card-header">
                    <h5 class="card-title">
                        Referensi Sumber Dana
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sumberdana-search" class="form-label">Cari Sumber Dana</label>
                                <select class="form-control select2-multiple" id="sumberdana-search" data-placeholder="Cari Sumber Dana" multiple>
                                    @foreach ($data['sumberdana'] as $searchSumberdana)
                                        <option value="{{ $searchSumberdana->id . '~' . $searchSumberdana->uraian }}">{{ $searchSumberdana->uraian }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sumberdana-result-search" class="form-label">Hasil Pencarian Sumber Dana <a id="sumberdana-copy-result" href="#"><i class="fa-regular fa-clipboard"></i></a></label>
                                <textarea class="form-control" id="sumberdana-result-search" rows="3" placeholder="Hasil Pencarian Sumber Dana" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('referensi.ref-script')
    <script src="/assets/js/referensi-js/ref-lokasi.js"></script>
</x-app-layout-component>
