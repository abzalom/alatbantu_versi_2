<x-app-layout-component :app="$app">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ $app && $app['desc'] ? $app['desc'] : 'Manage user roles and permissions' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.update', $role->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="role_name" class="form-label">Role Name</label>
                    <input type="text" class="form-control" id="role_name" name="role_name" value="{{ $role->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="guard_name" class="form-label">Guard Name</label>
                    <select class="form-select" id="guard_name" name="guard_name">
                        <option value="web" {{ $role->guard_name === 'web' ? 'selected' : '' }}>web</option>
                        <option value="api" {{ $role->guard_name === 'api' ? 'selected' : '' }}>api</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Role</button>
            </form>
        </div>
    </div>
</x-app-layout-component>
