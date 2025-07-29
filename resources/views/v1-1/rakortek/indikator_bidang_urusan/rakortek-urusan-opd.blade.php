<x-app-layout-component :title="$app['title'] ?? null">
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
            <h5 class="mb-3">URAIAN URUSAN BIDANG PADA {{ $opd->nama_opd }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark align-middle">
                        <tr>
                            <th>KODE</th>
                            <th>Urusan / Kinerja Urusan</th>
                            <th>Satuan</th>
                            <th>Target Nasional</th>
                            <th>Usulan Target Daerah</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($opd->tag_bidang as $item)
                            <tr>
                                <th>{{ $item->bidang->kode_bidang }}</th>
                                <th colspan="5">{{ $item->bidang->uraian }}</th>
                            </tr>
                            @foreach ($item->indikators as $itemIndikator)
                                <tr class="{{ $itemIndikator->target && $itemIndikator->target->validasi ? 'table-warning' : '' }}">
                                    <td>{{ $itemIndikator->kode_indikator }}</td>
                                    <td>{{ $itemIndikator->nama_indikator }}</td>
                                    <td>{{ $itemIndikator->satuan }}</td>
                                    <td>{{ $itemIndikator->target && $itemIndikator->target->target_nasional ? $itemIndikator->target->target_nasional : '' }}</td>
                                    <td id="show-target-urusan-{{ $itemIndikator->target ? $itemIndikator->target->id : $loop->iteration }}">{{ $itemIndikator->target && $itemIndikator->target->usulan_target_daerah ? $itemIndikator->target->usulan_target_daerah : '' }}</td>
                                    <td class="text-center">
                                        @if ($itemIndikator->target && $itemIndikator->target->validasi)
                                            <i class="fa-solid fa-circle-info text-info" style="font-size: 30px" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Telah dibahas dan divalidasi"></i>
                                        @else
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-primary edit-target-urusan" value="{{ $itemIndikator }}" data-bs-toggle="modal" data-bs-target="#newTargetIndikatorUrusanModal"><i class="fa-solid fa-pen-to-square"></i></button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('v1-1.rakortek.indikator_bidang_urusan.modal.modal-new-target-indikator-urusan')

    @include('v1-1.rakortek.indikator_bidang_urusan.script-rakortek-urusan')
</x-app-layout-component>
