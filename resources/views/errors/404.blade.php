<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendors/fontawesome-free-6.5.1-web/css/all.css">
    <title>404 | Page Not Found!</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <img src="/assets/img/mamberamo_raya_resize_250_x_251.png" alt="Kabupaten Mamberamo Raya" class="img-fluid mb-4" style="width: 80px; height: auto;">
                <h1 class="display-4 fw-bold text-muted">404</h1>
                <p class="lead">Halaman tidak ditemukan!</p>
                <p>Maaf, halaman yang anda cari tidak ada.</p>
                <a href="{{ url()->previous() }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</body>

</html>
