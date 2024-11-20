<x-app-layout-component :title="$app['title'] ?? null">

    <style>
        td {
            transition: background-color 1s ease;
        }
    </style>

    <div class="row mb-3">
        <div class="col-lg">
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
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 me-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#alokasDanaOtsusModal"><i class="fa-solid fa-square-plus"></i></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tahun</th>
                                    <th>Alokasi BG</th>
                                    <th>Alokasi SG</th>
                                    <th>Alokasi DTI</th>
                                    <th>Jumlah Alokasi</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="dana-show">
                                @if ($data->count() == 0)
                                    <tr id="not-found-data">
                                        <td colspan="7" class="text-center">
                                            <h4>Data Not Found!</h4>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($data as $dana)
                                        <tr id="dana-id-{{ $dana->id }}">
                                            <td class="text-center">{{ $dana->tahun }}</td>
                                            <td class="text-end">{{ formatIdr($dana->alokasi_bg, 2) }}</td>
                                            <td class="text-end">{{ formatIdr($dana->alokasi_sg, 2) }}</td>
                                            <td class="text-end">{{ formatIdr($dana->alokasi_dti, 2) }}</td>
                                            <td class="text-end">{{ formatIdr($dana->alokasi_bg + $dana->alokasi_sg + $dana->alokasi_dti, 2) }}</td>
                                            <td class="text-center">{{ $dana->status }}</td>
                                            <td style="width: 8%" class="text-center">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-info btn-edit-dana" data-id="{{ $dana->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                                                    <button class="btn btn-sm btn-danger btn-delete-dana" data-id="{{ $dana->id }}"><i class="fa-solid fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <th colspan="4" class="text-end">TOTAL</th>
                                    <th id="total-otsus" class="text-end">{{ formatIdr($data->sum('alokasi_bg') + $data->sum('alokasi_sg') + $data->sum('alokasi_dti'), 2) }}</th>\
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('otsus.otsus-modals.otsus-modal-alokasi-dana')
    @include('otsus.otsus-script')
</x-app-layout-component>
