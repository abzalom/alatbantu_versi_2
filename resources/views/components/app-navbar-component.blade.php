<nav id="top-navbar" class="navbar fixed-top bg-dark border-bottom navbar-expand-lg bg-body-tertiary mb-4" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="navbar-brand">
            <img src="/assets/img/mamberamo_raya_resize_250_x_251.png" class="p-0 m-0" alt="Logo Kabupaten Mamberamo Raya" style="width: 30px; height: auto;">
            <a href="/" class="text-decoration-none text-white">
                E-RAPOT
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 flex-wrap">
                @foreach ($navbar_menus as $mainMenu)
                    @if (auth()->user()->hasRole($mainMenu['roles']))
                        @if (count($mainMenu['subs']) == 0)
                            <li class="nav-item">
                                <a class="nav-link @if (request()->is($mainMenu['current']) || request()->is($mainMenu['current'] . '/*')) active @endif" @if (request()->is($mainMenu['current']) || request()->is($mainMenu['current'] . '/*')) aria-current="true" @endif href="{{ $mainMenu['path'] }}">
                                    {{ $mainMenu['name'] }}
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle @if (request()->is($mainMenu['current']) || request()->is($mainMenu['current'] . '/*')) active @endif" @if (request()->is($mainMenu['current']) || request()->is($mainMenu['current'] . '/*')) aria-current="true" @endif href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $mainMenu['name'] }}
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach ($mainMenu['subs'] as $subMenu)
                                        @if (auth()->user()->hasRole($subMenu['roles']))
                                            <a class="dropdown-item" href="{{ $subMenu['path'] }}">{{ $subMenu['name'] }}</a>
                                        @endif
                                    @endforeach
                                </ul>
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
                        <i class="fa-solid fa-user"></i> {{ auth()->user()->username }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
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
        </div>
    </div>
</nav>

{{-- @dump($debugSubMenu); --}}
