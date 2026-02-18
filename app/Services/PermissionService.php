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
            'User' => [
                [
                    'name' => 'view users',
                    'title' => 'Lihat semua pengguna',
                ],
                [
                    'name' => 'view user rates',
                    'title' => 'Lihat semua rate pengguna',
                ],
                [
                    'name' => 'create user',
                    'title' => 'Buat pengguna',
                ],
                [
                    'name' => 'edit user',
                    'title' => 'Edit pengguna',
                ],
                [
                    'name' => 'archive user',
                    'title' => 'Arsipkan pengguna',
                ],
                [
                    'name' => 'restore user',
                    'title' => 'Kembalikan pengguna',
                ],
            ],
            'Label' => [
                [
                    'name' => 'view labels',
                    'title' => 'Lihat semua label',
                ],
                [
                    'name' => 'create label',
                    'title' => 'Buat label',
                ],
                [
                    'name' => 'edit label',
                    'title' => 'Edit label',
                ],
                [
                    'name' => 'archive label',
                    'title' => 'Arsipkan label',
                ],
                [
                    'name' => 'restore label',
                    'title' => 'Kembalikan label',
                ],
            ],
            'Role' => [
                [
                    'name' => 'view roles',
                    'title' => 'Lihat semua peran',
                ],
                [
                    'name' => 'create role',
                    'title' => 'Buat peran',
                ],
                [
                    'name' => 'edit role',
                    'title' => 'Edit peran',
                ],
                [
                    'name' => 'archive role',
                    'title' => 'Arsipkan peran',
                ],
                [
                    'name' => 'restore role',
                    'title' => 'Kembalikan peran',
                ],
            ],
            'Owner Company' => [
                [
                    'name' => 'view owner company',
                    'title' => 'Lihat perusahaan pemilik',
                ],
                [
                    'name' => 'edit owner company',
                    'title' => 'Edit perusahaan pemilik',
                ],
            ],
            'Client User' => [
                [
                    'name' => 'view client users',
                    'title' => 'Lihat semua pengguna client',
                ],
                [
                    'name' => 'create client user',
                    'title' => 'Buat pengguna client',
                ],
                [
                    'name' => 'edit client user',
                    'title' => 'Edit pengguna client',
                ],
                [
                    'name' => 'archive client user',
                    'title' => 'Arsipkan pengguna client',
                ],
                [
                    'name' => 'restore client user',
                    'title' => 'Kembalikan pengguna client',
                ],
            ],
            'Client Company' => [
                [
                    'name' => 'view client companies',
                    'title' => 'Lihat semua perusahaan client',
                ],
                [
                    'name' => 'create client company',
                    'title' => 'Buat perusahaan client',
                ],
                [
                    'name' => 'edit client company',
                    'title' => 'Edit perusahaan client',
                ],
                [
                    'name' => 'archive client company',
                    'title' => 'Arsipkan perusahaan client',
                ],
                [
                    'name' => 'restore client company',
                    'title' => 'Kembalikan perusahaan client',
                ],
            ],
            'Owner Company' => [
                [
                    'name' => 'view owner company',
                    'title' => 'Lihat Institusi',
                ],
                [
                    'name' => 'edit owner company',
                    'title' => 'Edit Institusi',
                ],
            ],
            'Client User' => [
                [
                    'name' => 'view client users',
                    'title' => 'Lihat semua pengguna client',
                ],
                [
                    'name' => 'create client user',
                    'title' => 'Buat pengguna client',
                ],
                [
                    'name' => 'edit client user',
                    'title' => 'Edit pengguna client',
                ],
                [
                    'name' => 'archive client user',
                    'title' => 'Arsipkan pengguna client',
                ],
                [
                    'name' => 'restore client user',
                    'title' => 'Kembalikan pengguna client',
                ],
            ],
            'Client Company' => [
                [
                    'name' => 'view client companies',
                    'title' => 'Lihat semua Institusi client',
                ],
                [
                    'name' => 'create client company',
                    'title' => 'Buat Institusi client',
                ],
                [
                    'name' => 'edit client company',
                    'title' => 'Edit Institusi client',
                ],
                [
                    'name' => 'archive client company',
                    'title' => 'Arsipkan Institusi client',
                ],
                [
                    'name' => 'restore client company',
                    'title' => 'Kembalikan Institusi client', 
                ],
            ],
            'Project' => [
                [
                    'name' => 'view projects',
                    'title' => 'Lihat semua periode permintaan',
                ],
                [
                    'name' => 'view project',
                    'title' => 'Lihat periode permintaan detail',
                ],
                [
                    'name' => 'create project',
                    'title' => 'Buat periode permintaan',
                ],
                [
                    'name' => 'edit project',
                    'title' => 'Edit periode permintaan',
                ],
                [
                    'name' => 'archive project',
                    'title' => 'Arsipkan periode permintaan',
                ],
                [
                    'name' => 'restore project',
                    'title' => 'Kembalikan periode permintaan',
                ],
                [
                    'name' => 'edit project user access',
                    'title' => 'Edit akses pengguna periode permintaan',
                ],
            ],
            'TaskGroups' => [
                [
                    'name' => 'create task group',
                    'title' => 'Buat grup permintaan',
                ],
                [
                    'name' => 'edit task group',
                    'title' => 'Edit grup permintaan',
                ],
                [
                    'name' => 'archive task group',
                    'title' => 'Arsipkan grup permintaan',
                ],
                [
                    'name' => 'restore task group',
                    'title' => 'Kembalikan grup permintaan',
                ],
                [
                    'name' => 'reorder task group',
                    'title' => 'Urutkan grup permintaan',
                ],
            ],
            'Tasks' => [
                [
                    'name' => 'view tasks',
                    'title' => 'Lihat semua permintaan',
                ],
                [
                    'name' => 'create task',
                    'title' => 'Buat permintaan',
                ],
                [
                    'name' => 'edit task',
                    'title' => 'Edit permintaan',
                ],
                [
                    'name' => 'archive task',
                    'title' => 'Arsipkan permintaan',
                ],
                [
                    'name' => 'restore task',
                    'title' => 'Kembalikan permintaan',
                ],
                [
                    'name' => 'reorder task',
                    'title' => 'Urutkan permintaan',
                ],
                [
                    'name' => 'complete task',
                    'title' => 'Selesaikan permintaan',
                ],
                [
                    'name' => 'add time log',
                    'title' => 'Tambah log waktu',
                ],
                [
                    'name' => 'delete time log',
                    'title' => 'Hapus log waktu',
                ],
                [
                    'name' => 'view time logs',
                    'title' => 'Lihat log waktu',
                ],
                [
                    'name' => 'view comments',
                    'title' => 'Lihat komentar',
                ],
            ],
            'Invoices' => [
                [
                    'name' => 'view invoices',
                    'title' => 'Lihat semua invoice',
                ],
                [
                    'name' => 'create invoice',
                    'title' => 'Buat invoice',
                ],
                [
                    'name' => 'edit invoice',
                    'title' => 'Edit invoice',
                ],
                [
                    'name' => 'archive invoice',
                    'title' => 'Arsipkan invoice',
                ],
                [
                    'name' => 'restore invoice',
                    'title' => 'Kembalikan invoice',
                ],
                [
                    'name' => 'change invoice status',
                    'title' => 'Ubah status invoice',
                ],
                [
                    'name' => 'download invoice',
                    'title' => 'Unduh invoice',
                ],
                [
                    'name' => 'print invoice',
                    'title' => 'Cetak invoice',
                ],
            
            ],
            'Reports' => [
                [
                    'name' => 'view logged time sum report',
                    'title' => 'Lihat laporan  ringkasan waktu total ',
                ],
                [
                    'name' => 'view daily logged time report',
                    'title' => 'Lihat laporan  waktu harian',
                ],
                [
                    'name' => 'view fixed price sum report',
                    'title' => 'Lihat laporan  total harga fix',
                ],
            ],
            'Activities' => [
                [
                    'name' => 'view activities',
                    'title' => 'Lihat aktivitas',
                ],
            ],
        ],
        
        'helpdesk' => [
            
            'Label' => [
                [
                    'name' => 'view labels',
                    'title' => 'Lihat semua label',
                ],
                [
                    'name' => 'create label',
                    'title' => 'Buat label',
                ],
                [
                    'name' => 'edit label',
                    'title' => 'Edit label',
                ],
                [
                    'name' => 'archive label',
                    'title' => 'Arsipkan label',
                ],
                [
                    'name' => 'restore label',
                    'title' => 'Kembalikan label',
                ],
            ],
           
            'Owner Company' => [
                [
                    'name' => 'view owner company',
                    'title' => 'Lihat perusahaan pemilik',
                ],
                [
                    'name' => 'edit owner company',
                    'title' => 'Edit perusahaan pemilik',
                ],
            ],
            'Client User' => [
                [
                    'name' => 'view client users',
                    'title' => 'Lihat semua pengguna client',
                ],
                [
                    'name' => 'create client user',
                    'title' => 'Buat pengguna client',
                ],
                [
                    'name' => 'edit client user',
                    'title' => 'Edit pengguna client',
                ],
                [
                    'name' => 'archive client user',
                    'title' => 'Arsipkan pengguna client',
                ],
                [
                    'name' => 'restore client user',
                    'title' => 'Kembalikan pengguna client',
                ],
            ],
            'Client Company' => [
                [
                    'name' => 'view client companies',
                    'title' => 'Lihat semua perusahaan client',
                ],
                [
                    'name' => 'create client company',
                    'title' => 'Buat perusahaan client',
                ],
                [
                    'name' => 'edit client company',
                    'title' => 'Edit perusahaan client',
                ],
                [
                    'name' => 'archive client company',
                    'title' => 'Arsipkan perusahaan client',
                ],
                [
                    'name' => 'restore client company',
                    'title' => 'Kembalikan perusahaan client',
                ],
            ],
            'Owner Company' => [
                [
                    'name' => 'view owner company',
                    'title' => 'Lihat Institusi',
                ],
                [
                    'name' => 'edit owner company',
                    'title' => 'Edit Institusi',
                ],
            ],
            'Client User' => [
                [
                    'name' => 'view client users',
                    'title' => 'Lihat semua pengguna client',
                ],
                [
                    'name' => 'create client user',
                    'title' => 'Buat pengguna client',
                ],
                [
                    'name' => 'edit client user',
                    'title' => 'Edit pengguna client',
                ],
                [
                    'name' => 'archive client user',
                    'title' => 'Arsipkan pengguna client',
                ],
                [
                    'name' => 'restore client user',
                    'title' => 'Kembalikan pengguna client',
                ],
            ],
            'Client Company' => [
                [
                    'name' => 'view client companies',
                    'title' => 'Lihat semua Institusi client',
                ],
                [
                    'name' => 'create client company',
                    'title' => 'Buat Institusi client',
                ],
                [
                    'name' => 'edit client company',
                    'title' => 'Edit Institusi client',
                ],
                [
                    'name' => 'archive client company',
                    'title' => 'Arsipkan Institusi client',
                ],
                [
                    'name' => 'restore client company',
                    'title' => 'Kembalikan Institusi client', 
                ],
            ],
            'Project' => [
                [
                    'name' => 'view projects',
                    'title' => 'Lihat semua periode permintaan',
                ],
                [
                    'name' => 'view project',
                    'title' => 'Lihat periode permintaan detail',
                ],
                [
                    'name' => 'create project',
                    'title' => 'Buat periode permintaan',
                ],
                [
                    'name' => 'edit project',
                    'title' => 'Edit periode permintaan',
                ],
                [
                    'name' => 'archive project',
                    'title' => 'Arsipkan periode permintaan',
                ],
                [
                    'name' => 'restore project',
                    'title' => 'Kembalikan periode permintaan',
                ],
                [
                    'name' => 'edit project user access',
                    'title' => 'Edit akses pengguna periode permintaan',
                ],
            ],
            'TaskGroups' => [
                [
                    'name' => 'create task group',
                    'title' => 'Buat grup permintaan',
                ],
                [
                    'name' => 'edit task group',
                    'title' => 'Edit grup permintaan',
                ],
                [
                    'name' => 'archive task group',
                    'title' => 'Arsipkan grup permintaan',
                ],
                [
                    'name' => 'restore task group',
                    'title' => 'Kembalikan grup permintaan',
                ],
                [
                    'name' => 'reorder task group',
                    'title' => 'Urutkan grup permintaan',
                ],
            ],
            'Tasks' => [
                [
                    'name' => 'view tasks',
                    'title' => 'Lihat semua permintaan',
                ],
                [
                    'name' => 'create task',
                    'title' => 'Buat permintaan',
                ],
                [
                    'name' => 'edit task',
                    'title' => 'Edit permintaan',
                ],
                [
                    'name' => 'archive task',
                    'title' => 'Arsipkan permintaan',
                ],
                [
                    'name' => 'restore task',
                    'title' => 'Kembalikan permintaan',
                ],
                [
                    'name' => 'reorder task',
                    'title' => 'Urutkan permintaan',
                ],
                [
                    'name' => 'complete task',
                    'title' => 'Selesaikan permintaan',
                ],
                [
                    'name' => 'add time log',
                    'title' => 'Tambah log waktu',
                ],
                [
                    'name' => 'delete time log',
                    'title' => 'Hapus log waktu',
                ],
                [
                    'name' => 'view time logs',
                    'title' => 'Lihat log waktu',
                ],
                [
                    'name' => 'view comments',
                    'title' => 'Lihat komentar',
                ],
            ],
            'Invoices' => [
                [
                    'name' => 'view invoices',
                    'title' => 'Lihat semua invoice',
                ],
                [
                    'name' => 'create invoice',
                    'title' => 'Buat invoice',
                ],
                [
                    'name' => 'edit invoice',
                    'title' => 'Edit invoice',
                ],
                [
                    'name' => 'archive invoice',
                    'title' => 'Arsipkan invoice',
                ],
                [
                    'name' => 'restore invoice',
                    'title' => 'Kembalikan invoice',
                ],
                [
                    'name' => 'change invoice status',
                    'title' => 'Ubah status invoice',
                ],
                [
                    'name' => 'download invoice',
                    'title' => 'Unduh invoice',
                ],
                [
                    'name' => 'print invoice',
                    'title' => 'Cetak invoice',
                ],
            
            ],
            'Reports' => [
                [
                    'name' => 'view logged time sum report',
                    'title' => 'Lihat laporan  ringkasan waktu total ',
                ],
                [
                    'name' => 'view daily logged time report',
                    'title' => 'Lihat laporan  waktu harian',
                ],
                [
                    'name' => 'view fixed price sum report',
                    'title' => 'Lihat laporan  total harga fix',
                ],
            ],
            'Activities' => [
                [
                    'name' => 'view activities',
                    'title' => 'Lihat aktivitas',
                ],
            ],
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
