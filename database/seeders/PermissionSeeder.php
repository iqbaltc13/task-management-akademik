<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Services\PermissionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $insertPermissions = fn ($role) => collect(PermissionService::$permissionsByRole[$role])
                ->collapse()
                ->map(function ($item) {
                    print_r($item);
                    $name = $item['name'];
                    $title = $item['title'];

                    $permission = DB::table('permissions')->where('name', $name)->first();

                    return $permission
                        ? $permission->id
                        : (is_null($name) || is_null($title)
                            ? 0
                            : DB::table('permissions')
                                ->insertGetId([
                                    'name' => $name,
                                    'title' => $title,
                                    'guard_name' => 'web',
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]));
            })
            ->toArray();

        $permissionIdsByRole = [
            'admin' => $insertPermissions('admin'),
            'helpdesk' => $insertPermissions('helpdesk'),
            'client' => $insertPermissions('client'),
        ];

        foreach ($permissionIdsByRole as $role => $permissionIds) {
            $role = Role::whereName($role)->first();

            DB::table('role_has_permissions')
                ->insert(
                    collect($permissionIds)->map(fn ($id) => [
                        'role_id' => $role->id,
                        'permission_id' => $id,
                    ])->toArray()
                );
        }

        Artisan::call('cache:clear');
    }
}
