<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class MasterJenisPelayananPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            ['name' => 'view jenis pelayanans', 'title' => 'Lihat semua jenis pelayanan'],
            ['name' => 'create jenis pelayanan', 'title' => 'Buat jenis pelayanan'],
            ['name' => 'edit jenis pelayanan', 'title' => 'Edit jenis pelayanan'],
            ['name' => 'archive jenis pelayanan', 'title' => 'Arsipkan jenis pelayanan'],
            ['name' => 'restore jenis pelayanan', 'title' => 'Kembalikan jenis pelayanan'],
        ];

        $admin = Role::whereName('admin')->first();

        foreach ($permissions as $item) {
            $permission = Permission::firstOrCreate(
                ['name' => $item['name'], 'guard_name' => 'web'],
                ['title' => $item['title']]
            );

            $admin?->givePermissionTo($permission);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
