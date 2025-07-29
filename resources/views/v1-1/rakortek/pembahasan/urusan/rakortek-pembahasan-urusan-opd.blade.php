<x-app-layout-component :title="$app['title']">
    @php
        $admin = auth()->user()->hasRole('admin') ? true : false;
    @endphp
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
            @if (isset($opd))
                <h5>{{ $opd->text }}</h5>
            @else
                <h5>OPD tidak tersedia.</h5>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-bordered table-stripped">
                    <thead class="table-{{ $admin ? 'primary' : 'dark' }} align-middle">
                        <tr>
                            <th>KODE</th>
                            <th>BIDANG/ INIDKATOR URUSAN</th>
                            <th class="text-center">SATUAN</th>
                            <th class="text-center">TARGET NASIONAL</th>
                            <th class="text-center">USULAN TARGET DAERAH</th>
                            <th class="text-center">TARGET DAERAH <small>(hasil pembahasan)</small></th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">{{ $admin ? '' : 'CATATAN' }}</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($opd->tag_bidang as $tag_bidang)
                            <tr>
                                <th>{{ $tag_bidang->bidang->kode_bidang }}</th>
                                <th colspan="7">{{ $tag_bidang->bidang->uraian }}</th>
                            </tr>
                            @foreach ($tag_bidang->indikators as $indikator)
                                <tr>
                                    <td class="text-nowrap">{{ $tag_bidang->kode_bidang . '-' . $indikator->kode_indikator }}</td>
                                    <td>
                                        {{ $indikator->nama_indikator }}
                                        @if ($indikator->target->validasi)
                                            <i class="fa-regular fa-circle-check text-primary" style="font-size: 20px" data-bs-toggle="tooltip" data-bs-placement="top" title="Telah divalidasi"></i>
                                            {{-- <div style="border: 1px solid black; width: fit-content" data-bs-toggle="tooltip" data-bs-placement="top" title="Telah divalidasi">
                                            </div> --}}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $indikator->target->satuan }}</td>
                                    <td class="text-center">{{ $indikator->target->target_nasional }}</td>
                                    <td class="text-center">{{ $indikator->target->usulan_target_daerah }}</td>
                                    <td class="text-center">{{ $indikator->target->target_daerah }}</td>
                                    <td class="text-center">
                                        @php
                                            $validasi_msg = $indikator->target->validasi ? '& divalidasi' : '';
                                        @endphp
                                        @if ($indikator->target->pembahasan == 'setujui')
                                            <span class="badge bg-success">Disetujui {{ $validasi_msg }}</span>
                                        @elseif ($indikator->target->pembahasan == 'perbaikan')
                                            <span class="badge bg-warning">Perbaikan</span>
                                        @elseif ($indikator->target->pembahasan == 'tolak')
                                            <span class="badge bg-danger">Ditolak {{ $validasi_msg }}</span>
                                        @else
                                            <span class="badge bg-secondary">Belum dibahas</span>
                                        @endif
                                    </td>
                                    <td class="{{ $admin ? 'text-center' : '' }}">
                                        @if ($admin)
                                            <div class="d-flex justify-content-center gap-1">
                                                @if (!$indikator->target->validasi)
                                                    <button class="btn btn-sm btn-primary btn-pembahasan-urusan" data-bs-toggle="modal" data-bs-target="#bahasRakortekUrusanModal" data-indikator='@json($indikator)'><i class="fa-regular fa-handshake"></i></button>
                                                @endif
                                                @if ($indikator->target->pembahasan == 'setujui' || $indikator->target->pembahasan == 'tolak')
                                                    <div data-bs-toggle="tooltip" data-bs-placement="top" title="{{ !$indikator->target->validasi ? 'Validasi' : 'Batal Validasi' }}">
                                                        <form action="/pembahasan/rakortek/urusan/opd/validasi" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $indikator->target->id }}" />
                                                            <button class="btn btn-sm btn-{{ !$indikator->target->validasi ? 'secondary' : 'danger' }}"><i class="fa-solid {{ !$indikator->target->validasi ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i></button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">{{ $indikator->target->catatan }}</span>
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

    @if ($admin)
        @include('v1-1.rakortek.pembahasan.urusan.modal.modal-rakortek-pembahasan-urusan-opd')
    @endif

    @include('v1-1.rakortek.pembahasan.urusan.script-rakortek-pembahasan-urusan')
</x-app-layout-component>
