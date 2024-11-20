<x-app-layout-component :title="$app['title'] ?? null">

    @if (session('failures'))
        <div class="row">
            <div class="alert alert-danger col-lg">
                <h5>Terjadi kesalahan berikut:</h5>
                <ul>
                    @foreach (session('failures') as $failure)
                        <li class="mb-4">
                            Pada baris ke {{ $failure['row'] }} sub kegiatan => {{ $failure['values']['text_target_aktifitas'] }} :
                            <ul>
                                @foreach ($failure['errors'] as $error)
                                    <li class="mb-3">
                                        <strong>Kolom [{{ $error['attribute'] }}]</strong> => {!! $error['message'] !!}
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <hr>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

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
                    <div class="row mb-3">
                        <div class="col-8">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#opdTagIndikatorModal"><i class="fa-solid fa-square-plus"></i> Tema</button>
                            <a href="/rap/opd?id={{ $opd->id }}" class="btn btn-info"><i class="fa-solid fa-clipboard-list"></i> RAP</a>
                        </div>
                        <div class="col-4 text-end">
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#uploadFileIndikatorRapOpd"><i class="fa-solid fa-file-arrow-up"></i> Upload</button>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark align-middle">
                                <tr>
                                    <th>Kode</th>
                                    <th>Target Aktifitas Utama</th>
                                    <th>Satuan</th>
                                    <th>Volume</th>
                                    <th>Sumberdana</th>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody id="opd-indikator-show" class="align-middle">
                                @if ($opd->tag_otsus->count() == 0)
                                    <tr id="opd-indikator-show-data-not-found">
                                        <td colspan="6" class="text-center">
                                            <h4>Data Not Found!</h4>
                                        </td>
                                    </tr>
                                @endif

                                @foreach ($opd->tag_otsus as $tagging)
                                    <tr id="opd-indikator-show-data-id-{{ $tagging->id }}">
                                        <td>{{ $tagging->target_aktifitas->kode_target_aktifitas }}</td>
                                        <td>{{ $tagging->target_aktifitas->uraian }}</td>
                                        <td>{{ $tagging->satuan }}</td>
                                        <td id="opd-indikator-show-volume-id-{{ $tagging->id }}">{{ $tagging->volume }}</td>
                                        <td id="opd-indikator-show-sumberdana-id-{{ $tagging->id }}">{{ $tagging->sumberdana }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-primary btn-edit-opd-indikator" value="{{ $tagging->id }}" data-bs-toggle="modal" data-bs-target="#edit-opdTagIndikatorModal"><i class="fa-solid fa-pen-to-square"></i></button>
                                                @if (!$tagging->raps)
                                                    <button class="btn btn-sm btn-danger btn-delete-opd-indikator" value="{{ $tagging->id }}"><i class="fa-solid fa-trash"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


    @include('opd-indikator.opd-indikator-modal.opd-indikator-modal-upload')
    @include('opd-indikator.opd-indikator-modal.opd-indikator-modal-add')
    @include('opd-indikator.opd-indikator-modal.opd-indikator-modal-edit')
    @include('opd-indikator.opd-indikator-script')
</x-app-layout-component>
