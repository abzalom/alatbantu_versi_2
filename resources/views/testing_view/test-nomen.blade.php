<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendors/select2-4.0.rc/css/select2.min.css">
    <link rel="stylesheet" href="/vendors/select2-bootstrap-5-theme-1.3.0/select2-bootstrap-5-theme.min.css">

    <title>Test Nomenklatur</title>
</head>

<body>

    <div class="container">
        <h1>Test Nomenklatur Sikd</h1>
        <form method="get">
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="klasifikasi" class="form-label">Filter Klasifikasi Belanja</label>
                        <select name="klasifikasi" class="form-select select2" id="filter_klasifikasi_select" data-placeholder="Pilih...">
                            <option></option>
                            @foreach ($klasList as $klas)
                                <option value="{{ $klas->klasifikasi_belanja }}" {{ request('klasifikasi') == $klas->klasifikasi_belanja ? 'selected' : '' }}>
                                    {{ $klas->klasifikasi_belanja }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="/test" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
        <hr>
        <h2>Daftar Nomenklatur Sikd</h2>
        {{-- {{ $nomenklatur->links() }} --}}
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark align-middle text-center">
                    <tr>
                        <th style="word-wrap: break-word; min-width: 10%; max-width: 10%;">ID</th>
                        <th style="word-wrap: break-word; min-width: 10%; max-width: 10%;">Klasifikasi Belanja</th>
                        <th style="word-wrap: break-word; min-width: 21%; max-width: 21%;">Bidang Urusan</th>
                        <th style="word-wrap: break-word; min-width: 8%; max-width: 8%;">Kodefikasi</th>
                        <th style="word-wrap: break-word; min-width: 21%; max-width: 21%;">Nomenklatur</th>
                        <th style="word-wrap: break-word; min-width: 21%; max-width: 21%;">Indikator</th>
                        <th style="word-wrap: break-word; min-width: 10%; max-width: 10%;">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nomenklatur as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->klasifikasi_belanja }}</td>
                            <td>{{ $item->kode_bidang . ' ' . $item->nama_bidang }}</td>
                            <td>{{ $item->kode_subkegiatan }}</td>
                            <td>{{ $item->nama_subkegiatan }}</td>
                            <td>{{ $item->indikator }}</td>
                            <td>{{ $item->satuan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('script-home')
    <script>
        $('.select2').each(function() {
            $(this).select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                dropdownParent: $(this).parent(),
                allowClear: true,
            })
        });
    </script>
</body>

</html>
