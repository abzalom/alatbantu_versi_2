<ul class="nav nav-tabs mb-4">
    @isset($jenis_dana)
        <li class="nav-item">
            <a class="nav-link" href="/sinkron/djpk/sikd/{{ $jenisRequest }}">SET REQUEST {{ strtoupper($jenisRequest) }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $jenis_dana == 'bg' ? 'active' : '' }}" href="{{ $jenis_dana !== 'bg' ? '/sinkron/djpk/sikd/' . $jenisRequest . '/bg' : '#' }}">{{ strtoupper($jenisRequest) }} BG 1%</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $jenis_dana == 'sg' ? 'active' : '' }}" href="{{ $jenis_dana !== 'sg' ? '/sinkron/djpk/sikd/' . $jenisRequest . '/sg' : '#' }}">{{ strtoupper($jenisRequest) }} SG 1,25%</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $jenis_dana == 'dti' ? 'active' : '' }}" href="{{ $jenis_dana !== 'dti' ? '/sinkron/djpk/sikd/' . $jenisRequest . '/dti' : '#' }}">{{ strtoupper($jenisRequest) }} DTI</a>
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
