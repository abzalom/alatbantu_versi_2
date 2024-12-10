<ul class="nav nav-pills mb-4">
    @foreach ($menuRekapan as $itemMenuRekapan)
        <li class="nav-item">
            <a class="nav-link {{ $itemMenuRekapan['active'] }}" @if (request()->is($itemMenuRekapan['link'])) aria-current="page" @endif href="{{ $itemMenuRekapan['link'] }}">{{ $itemMenuRekapan['name'] }}</a>
        </li>
    @endforeach
</ul>
