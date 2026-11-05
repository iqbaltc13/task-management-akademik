<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Services\PermissionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * Untuk database yang SUDAH berjalan: menambahkan permission modul Struktur Organisasi
 * (unit, sub unit, jabatan, STO) dan memberikannya ke role admin. Aman dijalankan berulang.
 *
 *   php artisan db:seed --class=MasterStrukturOrganisasiPermissionSeeder
 */
class MasterStrukturOrganisasiPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::whereName('admin')->firstOrFail();

        $items = collect(PermissionService::$permissionsByRole['admin'])
            ->only(['Master Unit', 'Master Sub Unit', 'Master Jabatan', 'Struktur Organisasi'])
            ->collapse();

        foreach ($items as $item) {
            $permission = DB::table('permissions')->where('name', $item['name'])->first();

            $permissionId = $permission
                ? $permission->id
                : DB::table('permissions')->insertGetId([
                    'name' => $item['name'],
                    'title' => $item['title'],
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            $exists = DB::table('role_has_permissions')
                ->where('role_id', $admin->id)
                ->where('permission_id', $permissionId)
                ->exists();

            if (! $exists) {
                DB::table('role_has_permissions')->insert([
                    'role_id' => $admin->id,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        Artisan::call('cache:clear');
    }
}
