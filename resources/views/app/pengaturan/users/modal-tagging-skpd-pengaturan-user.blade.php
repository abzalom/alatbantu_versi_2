<!-- Modal -->
<div class="modal fade" id="userTaggingSkpdModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userTaggingSkpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="modal-user-tagging-skpd-show-spinner" style="display: block">
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="modal-user-tagging-skpd-show-content" style="display: none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="userTaggingSkpdModalLabel">Tagging Perangkat Daerah</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button id="btn-open-modal-select-user-skpd" type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#userSelectSkpdModal">
                        {{-- <button id="btn-open-modal-select-user-skpd" type="button" class="btn btn-secondary mb-3"> --}}
                        <i class="fa-solid fa-square-plus"></i> Pilih SKPD
                    </button>

                    <div class="mb-3">
                        List SKPD yang tertagging pada user <strong id="user-tagging-skpd-username">Username</strong>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-sm table-hover">
                            <tbody id="show-list-user-skpd" class="align-middle">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="userSelectSkpdModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userSelectSkpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">



            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userSelectSkpdModalLabel">Pilih Perangkat Daerah</h1>
                {{-- <button type="button" class="btn-close btn-back-to-tagging-user-skpd-modal" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-primary mb-4 mt-3 btn-back-to-tagging-user-skpd-modal" data-bs-toggle="modal" data-bs-target="#userTaggingSkpdModal">Selesai</button>

                <div id="modal-user-select-skpd-show-spinner" style="display: block">
                    <div class="modal-body text-center mb-4 mt-3">
                        <div class="spinner-border" style="width: 7rem; height: 7rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <div id="modal-user-select-skpd-show-content" style="display: none">
                    <div class="mb-3">
                        <input type="text" class="form-control border-primary" id="search-user-select-skpd" placeholder="Search...">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm tabl-hover">
                            <tbody id="show-list-select-skpd">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-back-to-tagging-user-skpd-modal" data-bs-toggle="modal" data-bs-target="#userTaggingSkpdModal">Selesai</button>
            </div>
        </div>
    </div>
</div>
