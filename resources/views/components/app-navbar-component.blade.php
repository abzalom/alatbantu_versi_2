<header>
    <i id="burger-menu" class="fa-solid fa-bars icon-burger"></i>
    <div id="header-logo">
        <img src="/assets/img/mamberamo_raya_resize_250_x_251.png" alt="Kab. Mamberamo Raya" />
        <a href="/">eRAPOT-MR</a>
    </div>
    <div id="header-content">
        <div id="schedule-header" class="text-center">
            <span id="timer-header-label">Tahapan : Nama Tahapan <small class="badge text-bg-secondary">status</small></span>
            <div id="timer-header">
                00 Hari 00 Jam 00 Menit 00 Detik
            </div>
        </div>
        <div id="schedule-penginputan" class="text-center">
            <span id="schedule-input-label">Penginputan</span>
            <br>
            <div class="d-flex justify-content-center align-items-center gap-2">
                <span id="schedule-input-status" class="badge text-bg-{{ session('jadwal_aktif') && session('jadwal_aktif.penginputan') ? 'secondary' : 'danger' }}">
                    @if (session('jadwal_aktif'))
                        {{ session('jadwal_aktif.penginputan') ? 'Aktif' : 'Terkunci' }}
                    @endif
                </span>
                @if (auth()->user()->hasRole('admin'))
                    <form method="post" action="/config/schedule/rap/input_user">
                        @csrf
                        <input type="hidden" name="id" value="{{ session('jadwal_aktif.id') }}">
                        <input type="hidden" name="penginputan" value="{{ session('jadwal_aktif.penginputan') ? 'false' : 'true' }}">
                        <button type="submit" id="schedule-input-status-btn" class="small border-0" style="cursor: pointer; background: none;">
                            <i class="fa-solid fa-toggle-{{ session('jadwal_aktif.penginputan') ? 'on text-white' : 'off text-danger' }} fa-2x"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div id="header-end">
        <div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Tahun {{ session('tahun') }}
            </a>
            <ul class="dropdown-menu" style="z-index: 101">
                @foreach ($selectTahunAnggaran as $itemTahunAnggaran)
                    <li>
                        <form action="/config/app/session/tahun" method="post">
                            @csrf
                            <input type="hidden" name="tahun" value="{{ $itemTahunAnggaran->tahun }}">
                            <button type="submit" class="dropdown-item" href="">
                                {{ $itemTahunAnggaran->tahun }}
                                @if ($itemTahunAnggaran->tahun == session('tahun'))
                                    <i class="fa-solid fa-check"></i>
                                @endif
                                @if ($itemTahunAnggaran->tahun == date('Y'))
                                    <span class="badge bg-primary">Aktif</span>
                                @endif
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-user"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu" style="z-index: 101">
                <li class="dropdown-item">
                    <a href="/user/profile" class="text-dark d-block">
                        <i class="fa-solid fa-user-gear"></i>
                        Profile
                    </a>
                </li>
                <li class="dropdown-item">
                    <a href="https://wa.me/6281247469077" class="text-dark d-block" target="_blank">
                        <i class="fa-solid fa-phone"></i>
                        Kontak Admin
                    </a>
                </li>
                <li>
                    <form action="/auth/logout" method="post" style="margin:0; padding:0;">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
