<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <title>
        @if ($app && $app['title'])
            {{ $app['title'] }}
        @endif
    </title>
</head>

<body>

    <div class="container">
        <div class="col-sm-12 col-md-6 col-lg-4 mx-auto" style="margin-top: 20vh">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Atur Tahun Anggaran</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="/session/tahun">
                        @csrf
                        <div class="mb-3">
                            <label for="select-tahun" class="form-label">Pilih Tahun Anggaran</label>
                            <select name="tahun" class="form-control @error('tahun') is-invalid @enderror" id="select-tahun">
                                <option value="">Pilih...</option>
                                @foreach ($tahuns as $tahun)
                                    <option value="{{ $tahun->tahun }}">{{ $tahun->tahun }}</option>
                                @endforeach
                            </select>
                            @error('tahun')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectTahun = document.getElementById('select-tahun');
            selectTahun.addEventListener('change', function() {
                if (this.value) {
                    this.classList.remove('is-invalid');
                } else {
                    this.classList.add('is-invalid');
                }
            });

            // remove the error message when the user selects a year
            selectTahun.addEventListener('input', function() {
                if (this.value) {
                    const errorSpan = this.nextElementSibling;
                    if (errorSpan && errorSpan.classList.contains('text-danger')) {
                        errorSpan.remove();
                    }
                }
            });
        });
    </script>

</body>

</html>
