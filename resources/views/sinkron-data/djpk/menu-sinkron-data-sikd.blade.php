<ul class="nav nav-tabs mb-4">
    @isset($sumberdana)
        <li class="nav-item">
            <a class="nav-link" href="/sinkron/djpk/sikd/{{ $jenisRequest }}">SET REQUEST {{ strtoupper($jenisRequest) }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $sumberdana == 'bg' ? 'active' : '' }}" href="{{ $sumberdana !== 'bg' ? '/sinkron/djpk/sikd/' . $jenisRequest . '/bg' : '#' }}">{{ strtoupper($jenisRequest) }} BG 1%</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $sumberdana == 'sg' ? 'active' : '' }}" href="{{ $sumberdana !== 'sg' ? '/sinkron/djpk/sikd/' . $jenisRequest . '/sg' : '#' }}">{{ strtoupper($jenisRequest) }} SG 1,25%</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $sumberdana == 'dti' ? 'active' : '' }}" href="{{ $sumberdana !== 'dti' ? '/sinkron/djpk/sikd/' . $jenisRequest . '/dti' : '#' }}">{{ strtoupper($jenisRequest) }} DTI</a>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">SET REQUEST {{ strtoupper($jenisRequest) }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/sinkron/djpk/sikd/{{ $jenisRequest }}/bg">{{ strtoupper($jenisRequest) }} BG 1%</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/sinkron/djpk/sikd/{{ $jenisRequest }}/sg">{{ strtoupper($jenisRequest) }} SG 1,25%</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/sinkron/djpk/sikd/{{ $jenisRequest }}/dti">{{ strtoupper($jenisRequest) }} DTI</a>
        </li>
    @endisset
</ul>
