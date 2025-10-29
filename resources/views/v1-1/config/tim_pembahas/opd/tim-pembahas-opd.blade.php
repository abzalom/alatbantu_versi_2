<x-app-layout-component :app="$app">
    <style>
        .active-tim {
            font-weight: bold !important;
            background-color: #d0e2fd !important;
            color: black !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ isset($app) && $app['desc'] ? $app['desc'] : 'Pengaturan Tim Pembahas' }}</h3>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('config/team/bappeda') ? 'active active-tim' : '' }}" aria-current="page" href="/config/team/bappeda">Tim Bappeda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('config/team/opd') ? 'active active-tim' : '' }}" href="/config/team/opd">Tim Perangkat Daerah</a>
                </li>
            </ul>

            <div class="d-flex justify-content-end">
                <div class="mb-3">
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text" id="search-addon1"><i class="fa-solid fa-search"></i></span>
                            <input type="text" class="form-control" id="search" placeholder="Search..." aria-describedby="search-addon1">
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-stripped table-hover">
                    <thead class="thead table-info align-middle">
                        <tr>
                            <th colspan="2">OPD/ Nama Member</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($opds as $opd)
                            <tr>
                                <th colspan="4" style="background: #edfdd0">{{ $opd->opd }}</th>
                            </tr>
                            @foreach ($opd->members as $team)
                                <tr>
                                    <td>{{ $team->urutan + 1 }}</td>
                                    <td>{{ $team->nama }}</td>
                                    <td>{{ $team->nip }}</td>
                                    <td>{{ $team->jabatan }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('v1-1.config.tim_pembahas.opd.script-tim-pembahas-opd')
</x-app-layout-component>
