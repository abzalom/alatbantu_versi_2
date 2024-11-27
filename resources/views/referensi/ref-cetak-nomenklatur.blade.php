<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <title>{{ $title }}</title>
</head>

<body>

    <table class="table table-bordered table-striped">
        <thead class="table-dark align-middle">
            <tr>
                <th>KODE</th>
                <th>URAIAN</th>
                <th>INDIKATOR</th>
                <th>SATUAN</th>
                <th>KLASIFIKASI BELANJA</th>
            </tr>
        </thead>
        <tbody class="align-middle">
            @if (count($data) > 0)
                @foreach ($data as $bidang)
                    <tr>
                        <th>{{ $bidang['kode_bidang'] }}</th>
                        <th colspan="4">{{ $bidang['uraian'] }}</th>
                    </tr>
                    @foreach ($bidang['programs'] as $program)
                        <tr>
                            <th>{{ $program['kode_program'] }}</th>
                            <th colspan="4">{{ $program['uraian'] }}</th>
                        </tr>
                        @foreach ($program['kegiatans'] as $kegiatan)
                            <tr>
                                <th>{{ $kegiatan['kode_kegiatan'] }}</th>
                                <th colspan="4">{{ $kegiatan['uraian'] }}</th>
                            </tr>
                            @foreach ($kegiatan['subkegiatans'] as $subkegiatan)
                                <tr>
                                    <td>{{ $subkegiatan['kode_subkegiatan'] }}</td>
                                    <td>{{ $subkegiatan['uraian'] }}</td>
                                    <td>{{ $subkegiatan['indikator'] }}</td>
                                    <td>{{ $subkegiatan['satuan'] }}</td>
                                    <td>{{ $subkegiatan['klasifikasi_belanja'] }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @else
                <tr>
                    <th colspan="5" class="text-center">
                        <h4>Data Not Found!</h4>
                    </th>
                </tr>
            @endif
        </tbody>
    </table>

</body>

</html>
