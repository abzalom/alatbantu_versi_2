<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/assets/img/mamberamo_raya.ico" type="image/x-icon">
    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendors/fontawesome-free-6.5.1-web/css/all.css">

    <title>Login Page RAP-APP</title>
    <style>
        body {
            background: #f2f2f29d
                /* background: rgb(207, 223, 255); */
                /* background: linear-gradient(0deg, rgba(63, 125, 253, 1) 0%, rgba(87, 140, 253, 1) 37%, rgba(22, 216, 255, 1) 100%); */
        }

        .card {
            background: none !important;
        }

        .form-input {
            background: #ffffff !important;
            border-radius: 15px !important;
        }
    </style>
</head>

<body>

    <div id="container" class="container">
        <div class="col-lg-4 col-md-6 mx-auto" style="margin-top: 3%">
            @if (session()->has('pesan'))
                <div class="alert alert-danger">{{ session()->get('pesan') }}</div>
            @endif
            <div class="card border-0">
                <div class="card-header text-center border-0" style="background: none !important">
                    <img src="/assets/img/mamberamo_raya_resize_250_x_251.png" alt="kabupaten mamberamo raya" style="width: 70px; height: auto;">
                    <h4 class="card-title mt-2">Login RAP-APP</h4>
                </div>
                <form method="post">
                    <div class="card-body">
                        @csrf
                        <div class="form-floating mb-3">
                            <input name="username" value="{{ old('username') }}" type="text" class="form-control form-input @error('username') is-invalid @enderror" id="usernameLoginInputForm" placeholder="Username">
                            <label for="usernameLoginInputForm" class="form-label">Username</label>
                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input name="password" type="password" class="form-control form-input @error('password') is-invalid @enderror" id="passwordLoginInputForm" placeholder="Password">
                            <label for="passwordLoginInputForm" class="form-label">Password</label>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            {{-- <label for="selectTahunLoginForm" class="form-label">Tahun Anggaran</label> --}}
                            <select name="tahun" class="form-control form-input @error('tahun') is-invalid @enderror" id="selectTahunLoginForm">
                                <option value="2025" selected>2025</option>
                            </select>
                            @error('tahun')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-check form-switch">
                            <input name="rememberme" class="form-check-input" type="checkbox" role="switch" id="rememberChecked">
                            <label class="form-check-label" for="rememberChecked">Ingat login saya</label>
                        </div>
                    </div>
                    <div class="card-footer text-end border-0" style="background: none !important">
                        <a href="https://wa.me/6281247469077" target="_blank" class="me-4">Lupa Password?</a>
                        <button class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="/vendors/jquery-3.7.1.min.js"></script>
    <script src="/vendors/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>

    <script></script>


    @if (session()->has('success') || session()->has('error') || session()->has('info'))
        @php
            $bg_alert = session()->has('success') ? 'text-bg-success' : (session()->has('info') ? 'text-bg-info' : 'text-bg-danger');
            // $alert_icon = session()->has('success') ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-triangle-exclamation"></i>';
        @endphp

        <div class="toast-container position-fixed top-0 end-0 p-3 mt-1">
            <div id="alertToast" class="toast align-items-center {{ $bg_alert }} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                <div class="toast-body mx-0">
                    <div class="container mx-0">
                        <div class="row">
                            <div class="col-2 d-flex align-items-center" style="font-size: 180%">
                                @if (session()->has('success'))
                                    <i class="fa-solid fa-circle-check fa-lg fa-bounce"></i>
                                @elseif(session()->has('info'))
                                    <i class="fa-solid fa-circle-info fa-lg fa-shake"></i>
                                @else
                                    <i class="fa-solid fa-triangle-exclamation fa-lg fa-beat"></i>
                                @endif
                            </div>
                            <div class="col-8 d-flex align-items-center" style="font-size: 120%">
                                @if (session()->has('success'))
                                    {{ session()->get('success') }}
                                @elseif(session()->has('info'))
                                    {{ session()->get('info') }}
                                @else
                                    {{ session()->get('error') }}
                                @endif
                            </div>
                            <div class="col-2 p-0 d-flex align-items-center">
                                <button type="button" class="btn-close btn-close-white me-2 m-0 h-100 w-100" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Menampilkan toast saat halaman dimuat
            $(document).ready(function() {
                $('#alertToast').toast('show');
                let session_error = @json(session()->has('error') ? true : false);
                if (session_error) {
                    $('#container').addClass('fa-bounce');
                    setTimeout(() => {
                        $('#container').removeClass('fa-bounce');
                    }, 500);
                }
            });
        </script>
    @endif
</body>

</html>
