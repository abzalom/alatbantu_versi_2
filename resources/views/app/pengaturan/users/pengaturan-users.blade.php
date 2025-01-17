<x-app-layout-component :title="$app['title'] ?? null">

    <script>
        const listSkpd = @json($opds);
    </script>

    @if (session()->has('pesan-success'))
        <div class="row mb-3">
            <div class="alert alert-success" role="alert">{{ session()->get('pesan-success') }}</div>
        </div>
    @endif
    @if (session()->has('pesan-error'))
        <div class="row mb-3">
            <div class="alert alert-danger" role="alert">{{ session()->get('pesan-error') }}</div>
        </div>
    @endif
    <div class="row mb-3">
        <div class="col-lg">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        @isset($app['desc'])
                            {{ $app['desc'] }}
                        @else
                            Deskripsi Halaman
                        @endisset
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4 mt-3">
                        <div class="col-sm-12 col-md-6 col-lg-8 mb-3">
                            <button id="btn-add-user" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fa-solid fa-square-plus"></i> User</button>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="mb-3 me-3">
                                <input type="text" class="form-control border-primary" id="search-user-input" placeholder="Search...">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-info">
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Roles</th>
                                    <th>SKPD</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table-list-user">
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data as $user)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            @if ($user->deleted_at)
                                                <i class="fa-solid fa-user-lock"></i>
                                            @endif
                                            {{ $user->username }}
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>
                                            @foreach ($user->getRoleNames() as $role)
                                                @php
                                                    $color = $role == 'admin' ? 'primary' : 'success';
                                                @endphp
                                                <span class="badge text-bg-{{ $color }}">{{ $role }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if (!$user->deleted_at)
                                                <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tangging SKPD">
                                                    <button class="btn btn-sm btn-primary btn-tagging-skpd-user" value="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#userTaggingSkpdModal"><i class="fa-solid fa-list"></i></button>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center" style="width: 2%">
                                            <div class="d-flex gap-1 justify-content-center">
                                                @if (!$user->deleted_at)
                                                    <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit User">
                                                        <button class="btn btn-sm btn-secondary btn-edit-user" value="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="fa-solid fa-pen-to-square"></i></button>
                                                    </div>
                                                    <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Atur Ulang Password">
                                                        <button class="btn btn-sm btn-warning btn-reset-password-user" value="{{ $user->id }}" data-name="{{ $user->name }}" data-username="{{ $user->username }}" data-bs-toggle="modal" data-bs-target="#resetPasswordUserModal"><i class="fa-solid fa-arrows-rotate"></i></button>
                                                    </div>
                                                    <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Non Aktifkan User">
                                                        <button class="btn btn-sm btn-danger btn-lock-user" value="{{ $user->id }}" data-name="{{ $user->name }}" data-username="{{ $user->username }}" data-bs-toggle="modal" data-bs-target="#lockUserModal"><i class="fa-solid fa-user-lock"></i></button>
                                                    </div>
                                                @else
                                                    <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Aktifkan User">
                                                        <button class="btn btn-sm btn-info btn-unlock-user" value="{{ $user->id }}"><i class="fa-solid fa-lock-open"></i></button>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('app.pengaturan.users.modal-add-pengaturan-user')
    @include('app.pengaturan.users.modal-edit-pengaturan-user')
    @include('app.pengaturan.users.modal-reset-password-pengaturan-user')
    @include('app.pengaturan.users.modal-lock-pengaturan-user')
    @include('app.pengaturan.users.modal-unlock-pengaturan-user')
    @include('app.pengaturan.users.modal-tagging-skpd-pengaturan-user')
    @include('app.pengaturan.users.script-pengaturan-user')
</x-app-layout-component>
