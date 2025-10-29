<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ConfigRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('v1-1.config.roles.roles', [
            'app' => [
                'title' => 'Roles & Permissions',
                'desc' => 'Manage user roles and permissions',
            ],
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        if ($request->input('form_name') === 'role_form') {
            $validated = Validator::make($request->all(), [
                'role_name'  => 'required|string|max:255|unique:roles,name',
                'permission'  => 'nullable|array',
                'permission.*' => 'string|distinct|exists:permissions,name',
                // opsional, kalau mau set guard eksplisit
                'guard_name' => 'required|string|in:web,api',
            ], [
                'role_name.required' => 'Role tidak boleh kosong',
                'role_name.unique'   => 'Role sudah ada',
                'guard_name.in'      => 'Guard name harus web atau api',
                'permission.*.exists' => 'Permission tidak valid',
            ]);
            if ($validated->fails()) {
                return back()->withErrors($validated)->withInput();
            }
            $validated = $validated->validated();
            $role = Role::create([
                'name'       => $validated['role_name'],
                'guard_name' => $validated['guard_name'] ?? config('auth.defaults.guard', 'web'),
            ]);
            if (!empty($validated['permission'])) {
                $role->syncPermissions($validated['permission']);
            }
            return redirect()->route('roles.index')->with('success', 'Role created successfully.')->withInput([
                'form_name' => 'role_form',
            ]);
        }

        if ($request->input('form_name') === 'permission_form') {
            $validated = Validator::make($request->all(), [
                'permission_name' => 'required|string|max:255|unique:permissions,name',
            ], [
                'permission_name.required' => 'Permission tidak boleh kosong',
                'permission_name.unique'   => 'Permission sudah ada',
            ]);
            if ($validated->fails()) {
                return back()->withErrors($validated)->withInput();
            }
            $validated = $validated->validated();
            Permission::create([
                'name' => $validated['permission_name'],
            ]);
            return redirect()->route('roles.index')->with('success', 'Permission created successfully.')->withInput([
                'form_name' => 'permission_form',
            ]);
        }

        return back()->with('error', 'Invalid form submission.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return "Show Role ID: " . $id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findById($id);
        if (!$role) {
            return redirect()->route('roles.index')->with('error', 'Role not found.');
        }
        return view('v1-1.config.roles.edit-roles', [
            'app' => [
                'title' => 'Edit Role: ' . $role->name,
                'desc' => 'Edit role and its permissions',
            ],
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->input('form_name') === 'role_edit_form') {
            $validated = Validator::make($request->all(), [
                'role_name' => 'required|string|max:255|unique:roles,name,' . $id,
                'guard_name' => 'required|string|in:web,api',
                'permission.*' => 'nullable|string|distinct|exists:permissions,name',
            ], [
                'role_name.required' => 'Role tidak boleh kosong',
                'role_name.unique'   => 'Role sudah ada',
                'guard_name.in'      => 'Guard name harus web atau api',
                'permission.*.string' => 'Permission harus berupa string',
                'permission.*.distinct' => 'Permission duplikat',
                'permission.*.exists' => 'Permission tidak valid',
            ]);
            if ($validated->fails()) {
                return back()->withErrors($validated)->withInput();
            }
            $validated = $validated->validated();
            // return $validated;
            $role = Role::findById($id);
            if (!$role) {
                return back()->with('error', 'Role not found.');
            }
            $data = [
                'name' => in_array($validated['role_name'], ['admin', 'user'], true) ? $role->name : $validated['role_name'],
                'guard_name' => $validated['guard_name'] ?? config('auth.defaults.guard', 'web'),
            ];
            $role->update($data);
            $permission = $request->input('permission', []);
            $role->syncPermissions($permission);
            return redirect()->route('roles.index')->with('success', 'Role updated successfully.')->withInput([
                'form_name' => 'role_form',
            ]);
        }
        return back()->with('error', 'Invalid form submission.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        if ($request->input('form_name') == 'role_form') {
            $getRole = Role::query()->find($id);
            if (!$getRole) {
                return back()->with('error', 'Role not found.');
            }
            // cegah hapus role spesial jika perlu
            if (in_array(strtolower($getRole->name), ['admin', 'user'], true)) {
                return back()->with('error', 'Admin dan User role tidak dapat dihapus.');
            }

            $role = Role::findById($getRole->id, $getRole->guard_name);

            $role->delete();

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            return back()
                ->with('success', "Role '{$role->name}' (guard: {$role->guard_name}) berhasil dihapus.")
                ->withInput([
                    'form_name' => 'role_form',
                ]);
        }

        if ($request->input('form_name') == 'permission_form') {
            $getPermission = Permission::query()->find($id);
            if (!$getPermission) {
                return back()->with('error', 'Permission not found.');
            }
            // Cegah hapus permission spesial jika perlu
            if (in_array(strtolower($getPermission->name), ['manage articles', 'manage users'], true)) {
                return back()->with('error', 'Permission spesial tidak dapat dihapus.');
            }

            $permission = Permission::findById($getPermission->id, $getPermission->guard_name);

            $permission->delete();

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            return back()
                ->with('success', "Permission '{$permission->name}' (guard: {$permission->guard_name}) berhasil dihapus.")
                ->withInput([
                    'form_name' => 'permission_form',
                ]);
        }
        return back()->with('error', 'Invalid form submission.');
    }
}
