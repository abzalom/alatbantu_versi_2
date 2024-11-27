<div class="row mb-3 row-gap-3">
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card" style="background: #d9e0f7">
            <div class="card-body d-flex flex-column">
                <div class="text-muted">
                    TOTAL PAGU
                </div>
                <div class="h5">
                    Rp. {{ formatIdr($data->sum('pagu_rap')) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card" style="background: #d9e0f7">
            <div class="card-body d-flex flex-column">
                <div class="text-muted">
                    PAGU OTSUS BG (1%)
                </div>
                <div class="h5">
                    Rp. {{ formatIdr($data->sum('pagu_bg')) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card" style="background: #d9e0f7">
            <div class="card-body d-flex flex-column">
                <div class="text-muted">
                    PAGU OTSUS SG (1,25%)
                </div>
                <div class="h5">
                    Rp. {{ formatIdr($data->sum('pagu_sg')) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card" style="background: #d9e0f7">
            <div class="card-body d-flex flex-column">
                <div class="text-muted">
                    PAGU DTI
                </div>
                <div class="h5">
                    Rp. {{ formatIdr($data->sum('pagu_dti')) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card" style="background: #d9e0f7">
            <div class="card-body d-flex flex-column">
                <div class="text-muted">
                    JUMLAH SKPD
                </div>
                <div class="h5">
                    {{ auth()->user()->opds->count() }} SKPD
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card" style="background: #d9e0f7">
            <div class="card-body d-flex flex-column">
                <div class="text-muted">
                    JUMLAH RAP
                </div>
                <div class="h5">
                    {{ $data->sum('jumlah_rap') }} Sub Kegiatan
                </div>
            </div>
        </div>
    </div>
</div>
