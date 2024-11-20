@if (session()->has('success') || session()->has('error') || session()->has('info'))
    @php
        $bg_alert = session()->has('success') ? 'text-bg-success' : (session()->has('info') ? 'text-bg-info' : 'text-bg-danger');
        // $alert_icon = session()->has('success') ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-triangle-exclamation"></i>';
    @endphp

    <div class="toast-container position-fixed top-0 end-0 p-3 mt-5">
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
        });
    </script>
@endif
