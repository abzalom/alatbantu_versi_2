<header>
    <i id="burger-menu" class="fa-solid fa-bars icon-burger"></i>
    <div id="header-logo">
        <img src="/assets/img/mamberamo_raya_resize_250_x_251.png" alt="Kab. Mamberamo Raya" />
        <a href="/">eRAPOT-MR</a>
    </div>
    <div id="header-content">
        <div id="schedule-header">
            <span id="timer-header-label">Tahapan : Nama Tahapan <small class="badge text-bg-secondary">status</small></span>
            <div id="timer-header">
                00 Hari 00 Jam 00 Menit 00 Detik
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
                <li class="dropdown-item">Profile</li>
                <li>
                    <form action="/auth/logout" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item" href="">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
