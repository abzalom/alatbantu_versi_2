<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <title>Login Page</title>
    <style>
        body {
            /* background: rgb(207, 223, 255); */
            /* background: linear-gradient(0deg, rgba(63, 125, 253, 1) 0%, rgba(87, 140, 253, 1) 37%, rgba(22, 216, 255, 1) 100%); */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="col-lg-5 col-md-6 mx-auto" style="margin-top: 5%">
            @if (session()->has('pesan'))
                <div class="alert alert-danger">{{ session()->get('pesan') }}</div>
            @endif
            <div class="card border-primary">
                <div class="card-header text-center">
                    <h3 class="card-title">Login Page</h3>
                </div>
                <form method="post">
                    <div class="card-body">
                        @csrf
                        <div class="mb-3">
                            <label for="usernameLoginInputForm" class="form-label">Username</label>
                            <input name="username" value="{{ old('username') }}" type="text" class="form-control @error('username') is-invalid @enderror" id="usernameLoginInputForm" placeholder="Username">
                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="passwordLoginInputForm" class="form-label">Password</label>
                            <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="passwordLoginInputForm" placeholder="Password">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="selectTahunLoginForm" class="form-label">Tahun Anggaran</label>
                            <select name="tahun" class="form-control @error('tahun') is-invalid @enderror" id="selectTahunLoginForm">
                                <option value="2025">2025</option>
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
                    <div class="card-footer text-end">
                        <button class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script src="/vendors/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>

</html>
