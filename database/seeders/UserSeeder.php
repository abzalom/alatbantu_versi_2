<?php

namespace Database\Seeders;

use App\Models\Data\Opd;
use App\Models\Scopes\TahunScope;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        Permission::create(['name' => 'create']);
        Permission::create(['name' => 'read']);
        Permission::create(['name' => 'update']);
        Permission::create(['name' => 'delete']);

        $adminRole->syncPermissions(['create', 'read', 'update', 'delete']);
        $userRole->syncPermissions(['read', 'update']);


        User::truncate();
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'password' => Hash::make('adminotsus123'),
            ]
        );

        $admin->assignRole('admin');

        $skpds = json_decode(Storage::disk('public')->get('/data/users/user-skpd2.json'), true);

        foreach ($skpds as $skpd) {
            $user = User::updateOrCreate(
                ['username' => $skpd['username']],
                [
                    'name' => $skpd['name'],
                    'password' => Hash::make($skpd['username'] . '123'),
                ]
            );

            $opd24 = Opd::withoutGlobalScope(TahunScope::class)->where('kode_unik_opd', '2024-' . $skpd['kode_opd'])->first();
            if ($opd24) {
                $opd24->username = $user->username;
                $opd24->save();
            }
            $opd25 = Opd::withoutGlobalScope(TahunScope::class)->where('kode_unik_opd', '2025-' . $skpd['kode_opd'])->first();
            if ($opd25) {
                $opd25->username = $user->username;
                $opd25->save();
            }

            $user->assignRole($skpd['role']);
        }
    }
}
