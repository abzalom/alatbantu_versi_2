<x-app-layout-component :app="$app">

    {{-- show errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ $app && $app['desc'] ? $app['desc'] : 'Manage user roles and permissions' }}</h4>
        </div>
        <div class="card-body">

            <ul class="nav nav-tabs mb-3" id="rolePermissionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ !old() ? 'active' : (old('form_name') == 'role_form' ? 'active' : '') }}" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles-box" type="button" role="tab" aria-controls="roles" aria-selected="true">
                        Roles
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ !old() ? '' : (old('form_name') == 'permission_form' ? 'active' : '') }}" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions-box" type="button" role="tab" aria-controls="permissions" aria-selected="false">
                        Permissions
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="rolePermissionTabsContent">
                <div class="tab-pane fade {{ !old() ? 'show active' : (old('form_name') == 'permission_form' ? '' : 'show active') }}" id="roles-box" role="tabpanel" aria-labelledby="roles-tab">
                    {{-- <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab"> --}}
                    <h4 class="mb-3">Manage Roles</h4>
                    <form id="form-create-role" action="{{ route('roles.store') }}" class="mb-3" method="POST">
                        @csrf
                        <input id="form_name" type="hidden" name="form_name" value="role_form">
                        <input id="role_form_method" type="hidden" name="_method" value="POST">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-3">
                                <div class="mb-3">
                                    <label for="role_name" class="form-label" id="role_name_label">Name</label>
                                    <input type="text" class="form-control" placeholder="Role Name" name="role_name" id="role_name" value="{{ old('role_name') }}">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-3">
                                <div class="mb-3">
                                    <label for="guard_name" class="form-label" id="guard_name_label">Guard</label>
                                    <select class="form-select" name="guard_name" id="guard_name">
                                        <option value="web" selected>web</option>
                                        <option value="api">api</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="permission" class="form-label">Permission</label>
                                    <select class="form-select select2-multiple" name="permission[]" id="permission" data-placeholder="Select Permissions" multiple>
                                        @foreach ($permissions as $permission)
                                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6 d-flex justify-content-between">
                                <div class="text-start">
                                    <button type="submit" id="btn-save-role" class="btn btn-sm btn-primary m-auto">Simpan</button>
                                </div>
                                <div class="text-end">
                                    <button type="button" id="btn-cancel-role" class="btn btn-sm btn-secondary" style="display: none">Batal</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Role Name</th>
                                <th>Guard Name</th>
                                <th>Has Permission</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->guard_name }}</td>
                                    <td>
                                        @foreach ($role->getPermissionNames() as $permission)
                                            <span class="badge text-bg-info">{{ $permission }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-secondary btn-edit-role" data-role="{{ $role }}"><i class="fa-solid fa-pen-square"></i></button>
                                            @if (!in_array($role->name, ['admin', 'super-admin', 'user']))
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="post" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="form_name" value="role_form">
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade {{ !old() ? '' : (old('form_name') == 'permission_form' ? 'show active' : '') }}" id="permissions-box" role="tabpanel" aria-labelledby="permissions-tab">
                    {{-- <div class="tab-pane fade show active" id="permissions" role="tabpanel" aria-labelledby="permissions-tab"> --}}
                    <h4 class="mb-3">Manage Permissions</h4>
                    <form id="form-create-permission" action="{{ route('roles.store') }}" class="mb-3" method="POST">
                        @csrf
                        <input type="hidden" name="form_name" value="permission_form">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="input-group mb-3">
                                    <label for="permission_name" class="input-group-text">Permission Name</label>
                                    <input type="text" id="permission_name" class="form-control" placeholder="Permission Name" name="permission_name">
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i></button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Permission Name</th>
                                <th>Guard Name</th>
                                <th>Has Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->guard_name }}</td>
                                    <td>
                                        @foreach ($roles as $role)
                                            <span class="badge bg-secondary">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('roles.destroy', $permission->id) }}" method="post" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="form_name" value="permission_form">
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @include('v1-1.config.roles.script-roles')
</x-app-layout-component>
