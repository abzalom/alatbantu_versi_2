<aside>
    <div id="sidebar-header">
        <h4>{{ $sidebarHeaderName }}</h4>
        <span>{{ $sidebarHeaderSubName }}</span>
    </div>

    <ul id="sidebar-menu">
        @foreach ($menus as $SideMenuKey => $SideMenu)
            @if (auth()->user()->hasRole($SideMenu['roles']))
                @php
                    $isActive = request()->is($SideMenu['current']) || request()->is($SideMenu['current'] . '/*') ? true : false;
                @endphp
                @if (!$SideMenu['subs'])
                    <li class="menu-item {{ $isActive ? 'active' : '' }}">
                        <div class="sidebar-menu-link">
                            <i class="{{ $SideMenu['icon'] }} icon-menu"></i>
                            <a href="{{ $SideMenu['path'] }}">{{ $SideMenu['name'] }}</a>
                        </div>
                    </li>
                @else
                    <li class="menu-item {{ $isActive ? 'active' : '' }}">
                        <div class="sidebar-menu-link">
                            <i class="{{ $SideMenu['icon'] }} icon-menu"></i>
                            <a href="#{{ str_replace('/', '_', $SideMenu['current']) }}" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="{{ str_replace('/', '_', $SideMenu['current']) }}">
                                {{ $SideMenu['name'] }}
                                <i class="fa-solid fa-chevron-circle-left icon-expand {{ $isActive ? 'rotate' : '' }}"></i>
                            </a>
                        </div>
                        <ul class="sidebar-submenu collapse {{ $isActive ? 'show' : '' }}" id="{{ str_replace('/', '_', $SideMenu['current']) }}">
                            @foreach ($SideMenu['subs'] as $SideSubMenu)
                                @if (auth()->user()->hasRole($SideSubMenu['roles']))
                                    <li class="sidebar-submenu-link">
                                        <a class="{{ request()->is($SideSubMenu['current']) || request()->is($SideSubMenu['current'] . '/*') ? 'active' : '' }}" href="{{ $SideSubMenu['path'] }}">{{ $SideSubMenu['name'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endif
        @endforeach
    </ul>

</aside>
