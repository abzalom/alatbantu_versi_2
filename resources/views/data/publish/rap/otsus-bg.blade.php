<x-app-layout-component :title="$app['title'] ?? null">

    <style>
        .fa-rotate-270 {
            transform: rotate(-90deg);
            transition: transform 0.3s ease-in-out;
        }

        .fa-rotate-0 {
            transform: rotate(90);
            transition: transform 0.3s ease-in-out;
        }

        .card-img-top {
            height: 150px;
            /* Atur tinggi gambar */
            object-fit: cover;
            /* Pastikan gambar sesuai tanpa distorsi */
            width: 100%;
            /* Pastikan gambar memenuhi lebar card */
            margin: 0 auto;
        }

        #uploadContainer {
            border: 2px dashed #ccc;
            border-radius: 10px;
            cursor: pointer;
            /* height: 150px; */
            margin: 10px;
            padding: 0;
            width: 45%;
            /* buat align conten vertical ke tengah */
            justify-content: center;
        }

        .card-img-container {
            padding: 0;
            margin: 10px;
            /* Atur jarak antar card */
            width: 45%;
            /* Pastikan card memenuhi lebar container */
        }

        @media (max-width: 768px) {
            .card-img-container {
                padding: 0;
                margin: 10px;
                /* Atur jarak antar card */
                width: 90%;
                /* Pastikan card memenuhi lebar container */
            }

            .card-img-top {
                height: 250px;
                /* Atur tinggi gambar */
                object-fit: cover;
                /* Pastikan gambar sesuai tanpa distorsi */
                width: 250px;
                /* Pastikan gambar memenuhi lebar card */
            }
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
                    <h5>Sumber Pendanaan : {{ $pendanaan }}</h5>
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark align-middle text-center">
                            <tr>
                                <th rowspan="2"></th>
                                <th rowspan="2">Klasifikasi Belanja</th>
                                <th rowspan="2">Kode Sub Kegiatan</th>
                                <th rowspan="2">Nama Sub Kegiatan</th>
                                <th colspan="2">Kinerja</th>
                                <th colspan="2">Anggaran (Rp)</th>
                                <th rowspan="2"></th>
                            </tr>
                            <tr>
                                <th>Target</th>
                                <th>Realisasi</th>
                                <th>Target</th>
                                <th>Realisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $rap)
                                <tr>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn-show-detail btn btn-secondary" style="--bs-btn-padding-y: .15rem; --bs-btn-padding-x: .35rem; --bs-btn-font-size: .75rem;" data-bs-animation="{true}" data-bs-toggle="collapse" data-bs-target="#collapseRap{{ $rap->id }}" data-bs-config='{"delay":"1000", "title":"123"}'>
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>{{ $rap->klasifikasi_belanja }}</td>
                                    <td>{{ $rap->kode_subkegiatan_full }}</td>
                                    <td>{{ $rap->subkegiatan_uraian }}</td>
                                    <td class="text-start">{{ $rap->target_keluaran . ' ' . $rap->satuan }}</td>
                                    <td class="text-start"></td>
                                    <td class="text-end">{{ formatIdr($rap->pagu_alokasi) }}</td>
                                    <td class="text-end">{{ formatIdr(0) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn-edit-rap btn btn-sm btn-primary" value="{{ $rap }}" data-bs-toggle="modal" data-bs-target="#editRapModal"><i class="fa-solid fa-pen-to-square"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="collapse" id="collapseRap{{ $rap->id }}">
                                    <td colspan="9">
                                        <strong>Indikator</strong>
                                        <p>{{ $rap->indikator_keluaran }}</p>
                                        <hr>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('data.publish.rap.publish-rap-script')
    @include('data.publish.rap.publish-edit-rap-modal')

</x-app-layout-component>
