<?php

namespace App\Services;

use App\Models\ClientCompany;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Collection;

class PermissionService
{
    public static $permissionsByRole = [
        'admin' => [
            'User' => ['lihat pengguna', 'lihat rate pengguna', 'buat pengguna', 'edit pengguna', 'pengguna terhapus', 'kembalikan pengguna'],
            'Label' => ['lihat label', 'buat label', 'edit label', 'label terhapus', 'kembalikan label'],
            'Role' => ['lihat peran', 'buat peran', 'edit peran', 'peran terhapus', 'kembalikan peran'],
            'Owner Company' => ['lihat perusahaan pemilik', 'edit perusahaan pemilik'],
            'Client User' => ['lihat pengguna client', 'buat pengguna client', 'edit pengguna client', 'pengguna client terhapus', 'kembalikan pengguna client'],
            'Client Company' => ['lihat perusahaan client', 'buat perusahaan client', 'edit perusahaan client', 'perusahaan client terhapus', 'kembalikan perusahaan client'],
            'Project' => ['lihat proyek', 'lihat proyek detail', 'buat proyek', 'edit proyek', 'proyek terhapus', 'kembalikan proyek', 'edit akses pengguna proyek'],
            'TaskGroups' => ['buat grup permintaan', 'edit grup permintaan', 'grup permintaan terhapus', 'kembalikan grup permintaan', 'urutan grup permintaan'],
            'Tasks' => [
                'lihat permintaan', 'buat permintaan', 'edit permintaan', 'arsip permintaan', 'kembalikan permintaan', 'urutan permintaan', 'selesaikan permintaan', 'tambah log waktu', 'hapus log waktu',  
                'lihat log waktu', 'lihat komentar',
            ],
            'Invoices' => ['lihat invoice', 'buat invoice', 'edit invoice', 'hapus invoice', 'kembalikan invoice', 'ubah status invoice', 'unduh invoice', 'cetak invoice'],
            //'Reports' => ['lihat laporan  ringkasan waktu total ', 'lihat laporan  waktu harian', 'lihat laporan  total harga fix'],
            'Reports' => ['lihat laporan  ringkasan waktu total ', 'lihat laporan  waktu harian', 'lihat laporan  total harga fix'],
            
            'Activities' => ['lihat aktifitas'],
        ],
        'helpdesk' => [
            //'User' => ['lihat pengguna', 'lihat rate pengguna', 'buat pengguna', 'edit pengguna', 'pengguna terhapus', 'kembalikan pengguna'],
            'Label' => ['lihat label', 'buat label', 'edit label', 'label terhapus', 'kembalikan label'],
            //'Role' => ['lihat peran', 'buat peran', 'edit peran', 'peran terhapus', 'kembalikan peran'],
            'Owner Company' => ['lihat perusahaan pemilik', 'edit perusahaan pemilik'],
            'Client User' => ['lihat pengguna client', 'buat pengguna client', 'edit pengguna client', 'pengguna client terhapus', 'kembalikan pengguna client'],
            'Client Company' => ['lihat perusahaan client', 'buat perusahaan client', 'edit perusahaan client', 'perusahaan client terhapus', 'kembalikan perusahaan client'],
            'Project' => ['lihat proyek', 'lihat proyek detail', 'buat proyek', 'edit proyek', 'proyek terhapus', 'kembalikan proyek', 'edit akses pengguna proyek'],
            'TaskGroups' => ['buat grup permintaan', 'edit grup permintaan', 'grup permintaan terhapus', 'kembalikan grup permintaan', 'urutan grup permintaan'],
            'Tasks' => [
                'lihat permintaan', 'buat permintaan', 'edit permintaan', 'arsip permintaan', 'kembalikan permintaan', 'urutan permintaan', 'selesaikan permintaan', 'tambah log waktu', 'hapus log waktu',
                'lihat log waktu', 'lihat komentar',
            ],
            'Invoices' => ['lihat invoice', 'buat invoice', 'edit invoice', 'hapus invoice', 'kembalikan invoice', 'ubah status invoice', 'unduh invoice', 'cetak invoice'],
            'Reports' => ['lihat laporan  ringkasan waktu total ', 'lihat laporan  waktu harian', 'lihat laporan  total harga fix'],
            'Activities' => ['lihat aktifitas'],
        ],
        // 'manager' => [
        //     'User' => ['view users'],
        //     'Project' => ['view projects', 'view project', 'create project', 'edit project', 'archive project', 'restore project', 'edit project user access'],
        //     'TaskGroups' => ['create task group', 'edit task group', 'archive task group', 'restore task group', 'reorder task group'],
        //     'Tasks' => [
        //         'view tasks', 'create task', 'edit task', 'archive task', 'restore task', 'reorder task', 'complete task', 'add time log', 'delete time log',
        //         'view time logs', 'view comments',
        //     ],
        //     'Reports' => ['view logged time sum report', 'view daily logged time report', 'view fixed price sum report'],
        // ],
        // 'developer' => [
        //     'Project' => ['view projects', 'view project'],
        //     'Tasks' => [
        //         'view tasks', 'create task', 'edit task', 'restore task', 'reorder task', 'complete task', 'add time log', 'delete time log',
        //         'view time logs', 'view comments',
        //     ],
        // ],
        // 'qa engineer' => [
        //     'Project' => ['view projects', 'view project'],
        //     'Tasks' => [
        //         'view tasks', 'create task', 'edit task', 'add time log', 'delete time log', 'view time logs', 'view comments',
        //     ],
        // ],
        // 'designer' => [
        //     'Project' => ['view projects', 'view project'],
        //     'Tasks' => [
        //         'view tasks', 'create task', 'edit task', 'restore task', 'reorder task', 'complete task', 'add time log', 'delete time log',
        //         'view time logs', 'view comments',
        //     ],
        // ],
        'client' => [
            'Project' => ['lihat proyek', 'lihat proyek detail'],
            'Tasks' => [
                'lihat permintaan', 'buat permintaan', 'lihat log waktu', 'lihat komentar',
            ],
        ],
    ];

    public static function allPermissionsGrouped(): array
    {
        return self::$permissionsByRole['admin'];
    }

    private static $usersWithAccessToProject = [];

    public static function usersWithAccessToProject($project): Collection
    {
        if (isset(self::$usersWithAccessToProject[$project->id])) {
            return self::$usersWithAccessToProject[$project->id];
        }

        $admins = User::role('admin')
            ->with('roles:id,name')
            ->get(['id', 'name', 'avatar'])
            ->map(fn ($user) => [...$user->toArray(), 'reason' => 'admin']);
        
        $owners = collect([]);

        if($project->clientCompany) {
            $owners = $project
                ->clientCompany
                ->clients
                ->load('roles:id,name')
                ->map(fn ($user) => [...$user->toArray(), 'reason' => 'company owner']);
        }

        $givenAccess = $project
            ->users
            ->load('roles:id,name')
            ->map(fn ($user) => [...$user->toArray(), 'reason' => 'given access']);

        return self::$usersWithAccessToProject[$project->id] = collect([
            ...$admins,
            ...$owners,
            ...$givenAccess,
        ])
            ->unique('id')
            ->sortBy('name')
            ->values();
    }

    private static $projectsThatUserCanAccess = null;

    public static function projectsThatUserCanAccess(User $user): Collection
    {
        if (self::$projectsThatUserCanAccess !== null) {
            return self::$projectsThatUserCanAccess;
        }
        if ($user->hasRole('admin')) {
            return Project::all();
        }
        $projects = collect($user->projects->toArray());
        $user->load('clientCompanies.projects');

        return self::$projectsThatUserCanAccess = $projects
            ->merge(
                $user
                    ->clientCompanies
                    ->map(fn (ClientCompany $company) => $company->projects->toArray())
                    ->collapse()
            )
            ->unique('id')
            ->sortBy('name')
            ->values();
    }
}
