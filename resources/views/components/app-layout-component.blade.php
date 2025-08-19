<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="tahun" content="{{ session()->get('tahun') }}">
    <meta name="user-token" content="{{ session()->get('user_token') }}">

    <link rel="shortcut icon" href="/assets/img/mamberamo_raya.ico" type="image/x-icon">

    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendors/fontawesome-free-6.5.1-web/css/all.css">
    <link rel="stylesheet" href="/vendors/select2-4.0.rc/css/select2.min.css">
    <link rel="stylesheet" href="/vendors/select2-bootstrap-5-theme-1.3.0/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="/vendors/summernote-0.8.18-dist/summernote.min.css">
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" href="/assets/css/customs.css" />
    <title>
        @isset($app['title'])
            {{ env('APP_ENV') === 'local' ? 'Local ' : '' }}{{ $app['title'] }}
        @else
            {{ env('APP_ENV') === 'local' ? 'Local ' : '' }}eRAPOT-MR
        @endisset
    </title>

    <script>
        const isAdmin = @json(auth()->user()->hasRole('admin') ?? false);
        var cient_timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const hostname = window.location.hostname;
        const port = window.location.port;
        const appApiUrl = `http://${hostname}:${port}`;
        const jadwal_aktif = @json(session('jadwal_aktif'));
        const jadwal_monev = @json(session('jadwal_monev'));


        (async () => {
            const {
                default: Schedule
            } = await import(`${window.location.origin}/assets/js/class/Schedule.js`);
            let schedule = new Schedule(new Date(jadwal_aktif.selesai ? jadwal_aktif.selesai : 0).getTime());
            if (!jadwal_monev.status) {
                let x = setInterval(function() {
                    let time = schedule.updateCountDown();
                    if (time.countDownDate) {
                        $('.count-down-time').html(`${time.days} Hari ${time.hours} Jam ${time.minutes} Menit ${time.seconds} Detik`);
                        $('#card_timer_title').html(`
                            ${capWords(jadwal_aktif.tahapan)} <br> ${jadwal_aktif.keterangan}
                        `);
                        $('#schedule-float-label').html('Tahapan ' + capWords(jadwal_aktif.tahapan));
                        $('#timer-header-label').html(`Tahapan :  ${capWords(jadwal_aktif.tahapan)} <small class="badge text-bg-secondary">${jadwal_aktif.status ? 'berlangsung' : ''}</small>`);
                        $('#days > .count').html(time.days);
                        $('#hours > .count').html(time.hours);
                        $('#minutes > .count').html(time.minutes);
                        $('#seconds > .count').html(time.seconds);
                        $('#timer-header').html(`${time.days} Hari ${time.hours} Jam ${time.minutes} Menit ${time.seconds} Detik`);
                        if (time.distance < 0) {
                            clearInterval(x);
                            $('.count-down-time').html('Waktu Habis');
                            $('#days > .count').html('00');
                            $('#hours > .count').html('00');
                            $('#minutes > .count').html('00');
                            $('#seconds > .count').html('00');
                            $('#timer-header').html(`waktu habis`);
                            $('#timer-float-info').show();
                            $('#timer-float').hide();
                            $('#timer-float-info').html('waktu habis');
                        }
                    }
                    if (!time.countDownDate) {
                        clearInterval(x);
                        $('#timer-float').hide();
                        $('#timer-float-info').show();
                        $('#timer-float-info').html('Tidak Ada Jadwal Aktif');
                        $('#schedule-float-label').html('Belum ada tahapan');
                        $('#timer-header-label').html(`Tahapan : belum ada tahapan`);
                        $('#timer-header').html(`Tidak Ada Jadwal Aktif`);
                        $('.count-down-time').html('Tidak Ada Jadwal Aktif');
                    }
                }, 1000);
            } else {
                document.getElementById('timer-float').style.display = 'none';
                document.getElementById('timer-float-info').style.display = 'block';
                document.getElementById('schedule-float-label').innerHTML = 'Pelaporan Kinerja';
                document.getElementById('timer-float-info').innerHTML = jadwal_monev.nama;
                document.getElementById('timer-header-label').innerHTML = jadwal_monev.nama;
                document.getElementById('timer-header').innerHTML = jadwal_monev.keterangan;
            }
        })();

        function formatAngka(value) {
            // Ubah ke float dulu
            const number = parseFloat(value);
            if (isNaN(number)) return '';

            // Jika angka adalah bilangan bulat
            if (Number.isInteger(number)) {
                return number.toString();
            }

            // Jika ada desimal yang signifikan
            return number.toFixed(2).replace('.', ',');
        }

        function formatIDR(value) {
            value = parseFloat(value);
            return value.toLocaleString('id-ID', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Fungsi untuk memformat angka sesuai kondisi
        function formatNumber(number) {
            // Hapus pemisah ribuan (titik)
            number = number.replace(/\./g, '');

            // Konversi ke angka
            number = parseFloat(number);

            // Jika bukan angka valid, kembalikan string kosong
            if (isNaN(number)) return '';

            // Pisahkan angka menjadi bagian integer dan desimal
            let [integerPart, decimalPart] = number.toString().split('.');

            // Jika ada bagian desimal dan bukan "00", format dengan 2 desimal
            if (decimalPart && decimalPart !== '00') {
                return number.toFixed(2);
            }

            // Jika tidak, kembalikan angka bulat
            return integerPart;
        }

        function formatInputAngka(num) {
            // Hapus karakter selain angka dan titik
            num = num.replace(/[^0-9.,]/g, '');
            if (num !== null) {
                // Pastikan hanya ada satu titik desimal (.)
                let parts = num.split(',');
                if (parts.length > 2) {
                    num = parts[0] + ',' + parts.slice(1).join('');
                }
                // Pisahkan angka sebelum dan sesudah desimal
                let splitNum = num.split(',');
                let integerPart = splitNum[0].replace(/\D/g, ''); // Pastikan hanya angka di integer
                let decimalPart = splitNum.length > 1 ? ',' + splitNum[1].replace(/\D/g, '') : '';
                // Format bagian ribuan
                let formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                let result = formattedInteger + decimalPart;
                return result;
            }
        }


        function capWords(text) {
            return text.replace(/\b\w/g, (char) => char.toUpperCase());
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const tahunAnggaran = document.querySelector('meta[name="tahun"]').getAttribute('content');
        const userToken = document.querySelector('meta[name="user-token"]').getAttribute('content');
        const old = @json(old());
    </script>
</head>

<body>
    <x-app-navbar-component></x-app-navbar-component>
    <div id="container">
        <x-app-sidebar-component :app="$app"></x-app-sidebar-component>
        <main class="schedule-active">
            @include('mobile-component.schedule-float')
            {{ $slot }}
            <div id="content">
            </div>
        </main>
    </div>
    <footer></footer>
    @include('alert.bs.toast')

    <div class="toast-container position-fixed top-0 end-0 p-3 mt-5">
        <div id="alertBsToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div id="bodyBsToast" class="toast-body mx-0">
            </div>
        </div>
    </div>

    <script src="/assets/js/new_template/home.js"></script>
    <script>
        const navbar = document.getElementById("top-navbar");
        const content = document.getElementById("content");

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

        document.querySelectorAll('.format-angka').forEach(function(element) {
            element.addEventListener('input', function() {
                let vol = this.value;
                if (vol) {
                    let formatAngka = formatInputAngka(vol);
                    this.value = formatAngka;
                }
            });
        });
    </script>
</body>

</html>
