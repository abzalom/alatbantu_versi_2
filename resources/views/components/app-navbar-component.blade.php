<nav class="navbar fixed-top bg-dark border-bottom navbar-expand-lg bg-body-tertiary mb-4" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">RAP OTSUS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 flex-wrap">
                @foreach ($navbar_menus as $itemMenu)
                    @if (auth()->user()->hasRole($itemMenu['roles']))
                        @if (count($itemMenu['subs']) == 0)
                            <li class="nav-item">
                                <a class="nav-link @if (request()->is($itemMenu['current']) || request()->is($itemMenu['current'] . '/*')) active @endif" @if (request()->is($itemMenu['current']) || request()->is($itemMenu['current'] . '/*')) aria-current="true" @endif href="{{ $itemMenu['path'] }}">
                                    {{ $itemMenu['name'] }}
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle @if (request()->is($itemMenu['current']) || request()->is($itemMenu['current'] . '/*')) active @endif" @if (request()->is($itemMenu['current']) || request()->is($itemMenu['current'] . '/*')) aria-current="true" @endif href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $itemMenu['name'] }}
                                </a>
                                @foreach ($itemMenu['subs'] as $itemMenuSub)
                                    <ul class="dropdown-menu">
                                        @if (auth()->user()->hasRole($itemMenuSub['roles']))
                                            <li>
                                                <a class="dropdown-item" href="{{ $itemMenuSub['path'] }}">{{ $itemMenuSub['name'] }}</a>
                                            </li>
                                        @endif
                                        {{-- <li><a class="dropdown-item" href="/rap/target-utama/opd">Cetak Target Utama Per OPD</a></li> --}}
                                    </ul>
                                @endforeach
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>
            <div class="d-flex text-white">
                <div class="nav-item dropdown me-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                        <i class="fa-solid fa-pen-to-square"></i> Tahun {{ session('tahun') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form action="/config/app/session/tahun" method="post">
                                @csrf
                                <input type="hidden" name="tahun" value="2024">
                                <button type="submit" class="dropdown-item" href="">2024</button>
                            </form>
                        </li>
                        <li>
                            <form action="/config/app/session/tahun" method="post">
                                @csrf
                                <input type="hidden" name="tahun" value="2025">
                                <button type="submit" class="dropdown-item" href="">2025</button>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                        <i class="fa-solid fa-user"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form action="/auth/logout" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item" href="">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
