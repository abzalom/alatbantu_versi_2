<!-- Modal -->
<style>
    .table-responsive-scroll {
        max-height: 250px;
        overflow-y: auto;
    }

    .table-responsive-scroll thead {
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 1;
    }

    .box-tim-pembahas {
        border: thin solid #dee2e6;
        /* border-radius: 5px; */
        padding: 10px;
    }
</style>
<div>
    <div class="modal fade" id="modalTimPembahasRapOpd" tabindex="-1" aria-labelledby="modalTimPembahasRapOpdLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div id="modal-modalTimPembahasRapOpd-show-spinner" style="display: block">
                    <div class="modal-body text-center">
                        <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <div id="modal-modalTimPembahasRapOpd-show-content" style="display: none">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalTimPembahasRapOpdLabel">Daftar tim pembahas RAP pada {{ ucwords(strtolower($opd->nama_opd)) }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <small class="text-muted mb-4 d-block">
                            * Drag dan drop untuk mengurutkan anggota tim pembahas.
                        </small>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6 mb-4 box-tim-pembahas">
                                <h4 class="mb-4">Tim Bappeda</h4>
                                <div class="col-12">
                                    <button id="btn-open-modalListTimPembahas" type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalListTimPembahas"><i class="fa-solid fa-plus-square"></i> Tambah Tim Bappeda</button>
                                </div>
                                <div class="mb-4">
                                    @if ($opd->tim_pembahas && $opd->tim_pembahas->count() > 0)
                                        <ul class="list-unstyled" id="tim-bappeda-list">
                                            @foreach ($opd->tim_pembahas as $tim_pembahas_bappeda)
                                                @if ($tim_pembahas_bappeda->role === 'ketua')
                                                    @continue
                                                @endif
                                                <li class="mb-2 border p-2 rounded d-flex tim-bappeda-item" data-tim_id="{{ $tim_pembahas_bappeda->id }}" data-opd_id="{{ $opd->id }}" onmouseover="this.style.cursor='move'" onmouseout="this.style.cursor='default'" draggable="true">
                                                    <div class="d-flex gap-2 tim-bappeda-item-content flex-shrink-1" style="flex: 0 0 92%;">
                                                        <span class="urutan-tim-bappeda-{{ $tim_pembahas_bappeda->id }}">
                                                            {{ $loop->iteration }}.
                                                        </span>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-primary">
                                                                {{ $tim_pembahas_bappeda->nama }}
                                                            </span>
                                                            <span class="text-primary">
                                                                Jabatan: {{ $tim_pembahas_bappeda->jabatan }}
                                                            </span>
                                                            @if ($tim_pembahas_bappeda->nip)
                                                                <span class="text-primary">
                                                                    NIP. {{ $tim_pembahas_bappeda->nip }}
                                                                </span>
                                                            @endif
                                                            <small class="text-muted">
                                                                ({{ $tim_pembahas_bappeda->role }})
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="remove-tim-opd flex-shrink-0 text-end border border-dark d-flex justify-content-center align-items-center rounded-1 text-bg-danger" style="flex: 0 0 8%; height: 25px;" onmouseover="this.style.cursor='pointer'" onmouseout="this.style.cursor='default'" data-tim_id="{{ $tim_pembahas_bappeda->id }}" data-opd_id="{{ $opd->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus dari tim pembahas">
                                                        <i class="fa-solid fa-xmark" style="font-size: 16px"></i>
                                                    </div>
                                                    {{-- <div class="d-flex gap-2">
                                                    </div> --}}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>Belum ada tim pembahas yang ditambahkan.</p>
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <p class="text-center">
                                        Mengetahui
                                        <br>
                                        Ketua Tim Pembahas
                                        <br>
                                        <br>
                                        <br>
                                        @if ($ketua_tim_pembahas)
                                            <strong>
                                                {{ $ketua_tim_pembahas->nama }}<br>
                                                @if ($ketua_tim_pembahas->nip)
                                                    NIP. {{ formatNip($ketua_tim_pembahas->nip) }}<br>
                                                @endif
                                                {{ $ketua_tim_pembahas->jabatan }}
                                            </strong>
                                        @else
                                            <em>Belum ada ketua tim pembahas yang ditetapkan.</em>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-6 mb-4 box-tim-pembahas">
                                <h4 class="mb-4">Tim Perangkat Daerah</h4>
                                <div class="col-12">
                                    <button id="btn-open-modalListTimPembahasOpd" type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNewTimPembahasOpd"><i class="fa-solid fa-plus-square"></i> Tambah Tim OPD</button>
                                </div>

                                <div class="mb-4">
                                    @if ($opd->tim_pembahas_opd && $opd->tim_pembahas_opd->count() > 0)
                                        <ul class="list-unstyled" id="tim-opd-list">
                                            @foreach ($opd->tim_pembahas_opd as $tim_pembahas_opd)
                                                @if ($tim_pembahas_opd->role === 'ketua')
                                                    @continue
                                                @endif
                                                <li class="mb-2 border p-2 rounded d-flex tim-opd-item" data-tim_id="{{ $tim_pembahas_opd->id }}" data-opd_id="{{ $opd->id }}" onmouseover="this.style.cursor='move'" onmouseout="this.style.cursor='default'" draggable="true">
                                                    <div class="d-flex gap-2 tim-opd-item-content flex-shrink-1" style="flex: 0 0 82%;">
                                                        <span class="urutan-tim-opd-{{ $tim_pembahas_opd->id }}">
                                                            {{ $loop->iteration }}.
                                                        </span>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-primary">
                                                                {{ $tim_pembahas_opd->nama }}
                                                            </span>
                                                            <span class="text-primary">
                                                                Jabatan: {{ $tim_pembahas_opd->jabatan }}
                                                            </span>
                                                            @if ($tim_pembahas_opd->nip)
                                                                <span class="text-primary">
                                                                    NIP. {{ $tim_pembahas_opd->nip }}
                                                                </span>
                                                            @endif
                                                            <small class="text-muted">
                                                                (Tim OPD)
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-1" style="flex: 0 0 16%;">
                                                        <div class="col-6 d-flex justify-content-center flex-shrink-0 text-end border border-dark align-items-center rounded-1 text-bg-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit data tim pembahas" onmouseover="this.style.cursor='pointer'" onmouseout="this.style.cursor='default'" style="height: 25px;">
                                                            <div class="edit-tim-opd d-flex justify-content-center align-items-center" data-member='@json($tim_pembahas_opd)' data-bs-toggle="modal" data-bs-target="#modalNewTimPembahasOpd">
                                                                <i class="fa-solid fa-pencil" style="font-size: 16px"></i>
                                                            </div>
                                                        </div>
                                                        <div class="remove-tim-opd flex-shrink-0 text-end border border-dark d-flex justify-content-center align-items-center rounded-1 text-bg-danger col-6" style="height: 25px;" onmouseover="this.style.cursor='pointer'" onmouseout="this.style.cursor='default'" data-tim_id="{{ $tim_pembahas_opd->id }}" data-opd_id="{{ $opd->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus dari tim pembahas">
                                                            <i class="fa-solid fa-xmark" style="font-size: 16px"></i>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>Belum ada tim pembahas yang ditambahkan.</p>
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <p class="text-center">
                                        Mengetahui
                                        <br>
                                        @if ($opd->kepala_aktif)
                                            {{ $opd->kepala_aktif->jabatan->label() . ' ' . ucwords(strtolower($opd->nama_opd)) }}
                                            <br>
                                            <br>
                                            <br>
                                            <strong>
                                                {{ $opd->kepala_aktif->nama }}<br>
                                                {{ $opd->kepala_aktif->pangkat }}<br>
                                                NIP. {{ formatNip($opd->kepala_aktif->nip) }}<br>
                                            </strong>
                                        @else
                                            <em>Belum ada kepala OPD yang ditetapkan</em>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Selesai</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalListTimPembahas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalListTimPembahasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modal-modalListTimPembahas-show-spinner" style="display: block">
                    <div class="modal-body text-center">
                        <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <div id="modal-modalListTimPembahas-show-content" style="display: none">
                    <div class="modal-header" style="background: #efe2b8">
                        <h1 class="modal-title fs-5" id="modalListTimPembahasLabel">Tim Pembahas Bappeda</h1>
                    </div>
                    <form id="formListTimPembahas" action="/config/team/bappeda/add_tim_opd" method="post">
                        @csrf
                        <input type="hidden" name="opd_id" id="formOpdId" value="{{ $opd->id }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-secondary mb-3 btn-open-modalTimPembahasRapOpd" data-bs-toggle="modal" data-bs-target="#modalTimPembahasRapOpd"><i class="fa-solid fa-arrow-left"></i> Kembali</button>
                                <button type="button" class="btn btn-sm btn-info mb-3 btn-open-modalNewTimPembahas" data-bs-toggle="modal" data-bs-target="#modalNewTimPembahas"><i class="fa-solid fa-plus-square"></i> Tambah Baru</button>
                            </div>
                            <div class="mb-3">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="searchListTim" placeholder="Cari..." aria-describedby="searchListTim-addon">
                                    {{-- <div class="input-group">
                                        <button class="input-group-text" id="searchListTim-addon"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="table-responsive-scroll">
                                <table class="table table-sm table-borderless table-striped datatables">
                                    <thead class="table-info">
                                        <tr>
                                            <th style="width: 5%!important">No</th>
                                            <th style="width: 5%!important">#</th>
                                            <th style="width: 50%!important">Nama</th>
                                            <th style="width: 35%!important">Jabatan</th>
                                            <th style="width: 5%!important">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listTimPembahas">
                                        @if ($tim_pembahas && $tim_pembahas->count() > 0)
                                            @php
                                                // Ambil hanya member yang bukan ketua dan belum ada di $opd->tim_pembahas
                                                $eligible = $tim_pembahas->filter(function ($m) use ($opd) {
                                                    return $m->role !== 'ketua' && !optional($opd->tim_pembahas)->contains('id', $m->id);
                                                });
                                            @endphp

                                            @forelse($eligible as $listMember)
                                                <tr class="list-member-id-{{ $listMember->id }}">
                                                    <td class="list-member-no">{{ $loop->iteration }}</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input chkTimPembahas border-1 border-primary" name="tim_pembahas[]" value="{{ $listMember->id }}" style="height: 20px; width: 20px;">
                                                    </td>
                                                    <td class="list-member-nama">{{ $listMember->nama }}</td>
                                                    <td class="list-member-jabatan">{{ $listMember->jabatan }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm btn-primary btn-edit-tim-pembahas" data-bs-toggle="modal" data-bs-target="#modalNewTimPembahas" data-member="{{ $listMember }}"><i class="fa-solid fa-pen-square"></i></button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5"><em>Semua anggota tim pembahas bappeda sudah ditambahkan</em></td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <tr>
                                                <td colspan="5"><em>Belum ada data tim pembahas bappeda tersedia.</em></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-target="#modalTimPembahasRapOpd" data-bs-toggle="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btnSimpanTimPembahas">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalNewTimPembahas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalNewTimPembahasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modal-modalNewTimPembahas-show-spinner" style="display: block">
                    <div class="modal-body text-center">
                        <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <div id="modal-modalNewTimPembahas-show-content" style="display: none">
                    <div class="modal-header" style="background: #efe2b8">
                        <h1 class="modal-title fs-5" id="modalNewLabel">Tambah Anggota Tim Pembahas Bappeda Baru</h1>
                    </div>
                    <form id="formNewTimPembahas" action="{{ route('bappeda.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="_method" id="httpMethodNewTimPembahas" value="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="inputNamaTimPembahas" class="form-label">Nama Lengkap <small class="text-muted">(Wajib)</small></label>
                                    <input name="nama" type="text" class="form-control" id="inputNamaTimPembahas" placeholder="Masukkan nama anggota tim pembahas">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="inputNipTimPembahas" class="form-label">NIP <small class="text-muted">(Opsional)</small></label>
                                    <input name="nip" type="text" class="form-control" id="inputNipTimPembahas" placeholder="Masukkan NIP anggota tim pembahas">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="inputJabatanTimPembahas" class="form-label">Jabatan <small class="text-muted">(Opsional)</small></label>
                                    <input name="jabatan" type="text" class="form-control" id="inputJabatanTimPembahas" placeholder="Masukkan jabatan anggota tim pembahas">
                                </div>
                                {{-- <div class="col-12 mb-3">
                                    <label for="selectRoleTimPembahas" class="form-label">Role <small class="text-muted">(Wajib)</small></label>
                                    <select class="form-select" id="selectRoleTimPembahas">
                                        <option value="" selected disabled>Pilih role</option>
                                        <option value="ketua">Ketua</option>
                                        <option value="anggota">Anggota</option>
                                    </select>
                                </div> --}}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalListTimPembahas">Kembali</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Tim Pembahas OPD --}}

    <!-- Modal -->
    <div class="modal fade" id="modalNewTimPembahasOpd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalNewTimPembahasOpdLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modal-modalNewTimPembahasOpd-show-spinner" style="display: block">
                    <div class="modal-body text-center">
                        <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <div id="modal-modalNewTimPembahasOpd-show-content" style="display: none">
                    <div class="modal-header text-bg-info">
                        <h1 class="modal-title fs-5" id="modalNewTimPembahasOpdLabel">Tambah Anggota Tim Pembahas OPD Baru</h1>
                    </div>
                    <form id="formNewTimPembahasOpd" action="{{ route('opd.store') }}" method="post">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="_method" id="httpMethodNewTimPembahasOpd" value="POST">
                            <input type="hidden" name="opd_id" value="{{ $opd->id }}">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="inputNamaTimPembahasOpd" class="form-label">Nama Lengkap <small class="text-muted">(Wajib)</small></label>
                                    <input name="nama" type="text" class="form-control" id="inputNamaTimPembahasOpd" placeholder="Masukkan nama anggota tim pembahas">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="inputNipTimPembahasOpd" class="form-label">NIP <small class="text-muted">(Opsional)</small></label>
                                    <input name="nip" type="text" class="form-control" id="inputNipTimPembahasOpd" placeholder="Masukkan NIP anggota tim pembahas">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="inputJabatanTimPembahasOpd" class="form-label">Jabatan <small class="text-muted">(Opsional)</small></label>
                                    <input name="jabatan" type="text" class="form-control" id="inputJabatanTimPembahasOpd" placeholder="Masukkan jabatan anggota tim pembahas">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalTimPembahasRapOpd">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
