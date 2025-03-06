<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="tahun" content="{{ session()->get('tahun') }}">
    <meta name="user-token" content="{{ session()->get('user_token') }}">

    <link rel="shortcut icon" href="/assets/img/mamberamo_raya.ico" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendors/fontawesome-free-6.5.1-web/css/all.css">
    <link rel="stylesheet" href="/vendors/select2-4.0.rc/css/select2.min.css">
    <link rel="stylesheet" href="/vendors/select2-bootstrap-5-theme-1.3.0/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="/vendors/summernote-0.8.18-dist/summernote.min.css">
    <link rel="stylesheet" href="/assets/styles/app_style.css">

    <script type="module" src="/assets/js/class/Schedule.js"></script>

    <title>
        @isset($app['title'])
            {{ env('APP_ENV') === 'local' ? 'Local ' : '' }}{{ $app['title'] }}
        @else
            {{ env('APP_ENV') === 'local' ? 'Local ' : '' }}RAP-APP
        @endisset
    </title>

    <script>
        var cient_timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        console.log(cient_timezone);
        (async () => {
            const {
                default: Schedule
            } = await import(`${window.location.origin}/assets/js/class/Schedule.js`);
            let schedule = new Schedule(new Date(jadwal_aktif.selesai).getTime());
            let x = setInterval(function() {
                let time = schedule.updateCountDown();
                $('.count-down-time').html(`${time.days} Hari ${time.hours} Jam ${time.minutes} Menit ${time.seconds} Detik`);
                $('#card_timer_title').html(`
                    ${jadwal_aktif.tahapan} <br> ${jadwal_aktif.keterangan}
                `);
                $('#days').html(time.days);
                $('#hours').html(time.hours);
                $('#minutes').html(time.minutes);
                $('#seconds').html(time.seconds);
                if (time.distance < 0) {
                    clearInterval(x);
                    $('.count-down-time').html('Waktu Habis');
                    $('#days').html('00');
                    $('#hours').html('00');
                    $('#minutes').html('00');
                    $('#seconds').html('00');
                }
                if (!time.countDownDate) {
                    clearInterval(x);
                    $('.count-down-time').html('Tidak Ada Jadwal Aktif');
                }
            }, 1000);
        })();

        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const hostname = window.location.hostname;
        const port = window.location.port;
        const appApiUrl = `http://${hostname}:${port}`;
        const jadwal_aktif = @json(session('jadwal_aktif'));

        function formatIDR(value) {
            value = parseFloat(value);
            return value.toLocaleString('id-ID', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Fungsi untuk memformat angka sesuai dengan kondisi
        function formatNumber(number) {
            // Memisahkan angka menjadi bagian sebelum dan setelah titik desimal
            let [integerPart, decimalPart] = number.toString().split('.');

            // Jika bagian desimal ada dan nilainya bukan 00
            if (decimalPart && decimalPart !== '00') {
                return number.toFixed(2); // Tampilkan dengan dua desimal
            }
            return integerPart; // Jika tidak, tampilkan hanya bagian integer
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const tahunAnggaran = document.querySelector('meta[name="tahun"]').getAttribute('content');
        const userToken = document.querySelector('meta[name="user-token"]').getAttribute('content');
        const old = @json(old());
    </script>
</head>

<body>
    <x-app-navbar-component></x-app-navbar-component>

    <div id="content" class="mx-4" style="margin-bottom: 20vh; margin-top: 15vh">
        <div id="card_timer">
            <div id="card_timer_body">
                <div class="countdown_section">
                    <div id="days" class="value">00</div>
                    <div class="countdown_label">HARI</div>
                </div>
                <div class="countdown_section">
                    <div id="hours" class="value">00</div>
                    <div class="countdown_label">JAM</div>
                </div>
                <div class="countdown_section">
                    <div id="minutes" class="value">00</div>
                    <div class="countdown_label">MENIT</div>
                </div>
                <div class="countdown_section">
                    <div id="seconds" class="value">00</div>
                    <div class="countdown_label">DETIK</div>
                </div>
                <h2 id="card_timer_title" class="card_title">
                </h2>
            </div>
        </div>

        <div class="row">
        </div>
        {{ $slot }}
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3 mt-5">
        <div id="alertBsToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div id="bodyBsToast" class="toast-body mx-0">
            </div>
        </div>
    </div>

    <x-app-footer-component></x-app-footer-component>

    <script>
        const navbar = document.getElementById("top-navbar");
        const content = document.getElementById("content");

        function adjustContentMargin() {
            const navbarHeight = navbar.offsetHeight; // Get the current height of the navbar
            content.style.marginTop = `${navbarHeight + 20}px`; // Set the margin-top of content dynamically
        }

        // Adjust on load
        adjustContentMargin();

        // Adjust on window resize
        window.addEventListener("resize", adjustContentMargin);

        // Optional: Observe changes in the navbar (if the height changes dynamically)
        const observer = new ResizeObserver(adjustContentMargin);
        observer.observe(navbar);

        $.ajaxSetup({
            headers: {
                'x-token': userToken
            }
        });
        let auth_username = @json(auth()->user()->username);
        let auth = @json(auth()->user());

        function copyToClipboard(text) {
            // Create a temporary input element to hold the text
            const tempInput = document.createElement('input');
            tempInput.value = text; // Set the value to the text you want to copy
            document.body.appendChild(tempInput); // Append it to the document body
            tempInput.select(); // Select the text
            document.execCommand('copy'); // Execute the copy command
            document.body.removeChild(tempInput); // Remove the temporary input element
        }

        // Fungsi untuk menampilkan toast notifikasi
        function showToast(message, alertType) {
            var alertIcon = alertType == 'success' ? '<i class="fa-solid fa-circle-check fa-lg fa-bounce"></i>' : '<i class="fa-solid fa-triangle-exclamation fa-lg fa-beat"></i>';
            $('#bodyBsToast').html(`
            <div class="container mx-0">
                <div class="row">
                    <div class="col-2 d-flex align-items-center" style="font-size: 180%">
                        ${alertIcon}
                    </div>
                    <div class="col-8 d-flex align-items-center" style="font-size: 120%">${message}</div>
                    <div class="col-2 p-0 d-flex align-items-center">
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        `);
            $('#alertBsToast').removeClass().addClass(`toast align-items-center text-bg-${alertType} border-0`);
            $('#alertBsToast').toast('show');
        }

        // Fungsi untuk menangani error response dari AJAX
        function handleAjaxError(xhr) {
            try {
                return JSON.parse(xhr.responseText);
            } catch (e) {
                console.error('Response bukan JSON:', xhr.responseText);
                return {
                    message: 'Terjadi kesalahan, silakan coba lagi.'
                };
            }
        }

        // Fungsi untuk menampilkan animasi perubahan warna latar belakang
        function highlightRow(rowId) {
            let $row = document.querySelector(rowId);
            $row.querySelectorAll('td').forEach(function(td) {
                td.style.backgroundColor = '#e2f7a9';
            });

            setTimeout(function() {
                $row.querySelectorAll('td').forEach(function(td) {
                    td.style.backgroundColor = '';
                });
            }, 1000);
        }

        $('.select2').each(function() {
            $(this).select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                dropdownParent: $(this).parent(),
                allowClear: true,
            })
        });

        $('.select2-multiple').each(function() {
            $(this).select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                dropdownParent: $(this).parent(),
                closeOnSelect: false,
                allowClear: true,
            })
        });

        const toastTrigger = document.getElementById('liveToastBtn')
        const toastLiveExample = document.getElementById('liveToast')

        if (toastTrigger) {
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
            toastTrigger.addEventListener('click', () => {
                toastBootstrap.show()
            })
        }

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>

    @include('alert.bs.toast')
</body>

</html>
