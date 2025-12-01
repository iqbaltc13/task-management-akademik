<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private array $jobTitleToRole = [
        'Helpdesk' => 'helpdesk',
        'Admin Siakad' => 'admin',
        
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesExceptClient = collect(RoleSeeder::$roles)
            ->filter(fn ($i) => $i !== 'client')
            ->toArray();

        // foreach ($rolesExceptClient as $role) {
        //     User::factory()
        //         ->create(['email' => "$role@mail.com", 'job_title' => $this->getJobTitle($role)])
        //         ->assignRole($role);
        // }
        $dataUsers = [
            [
                'name'=> 'Moch. Taufiq Yahya',
                'email'=> 'yahya@gmail.com',
                'username'=> 'yahya',
                'password'=> bcrypt('akademik689282'),
                'avatar'=> 'https://ui-avatars.com/api/?name=Moch.+Taufiq+Yahya,+S.Kom&background=random',
                'phone'=> '628563626317',
                'job_title'=> 'Admin Siakad',
            ],
            [
                'name'=> 'Johan Setiawan',
                'email'=> 'johan@gmail.com',
                'username'=> 'johan',
                'password'=> bcrypt('akademik689282'),
                'avatar'=> 'https://ui-avatars.com/api/?name=Muhammad+Fadli,+S.Kom&background=random',
                'phone'=> '6285655561692',
                'job_title'=> 'Admin Siakad',
            ],
            [
                'name'=> 'M. Gervais Pratama',
                'email'=> 'gervais@gmail.com',
                'username'=> 'gervais',
                'password'=> bcrypt('akademik689282'),
                'avatar'=> 'https://ui-avatars.com/api/?name=Muhammad+Fadli,+S.Kom&background=random',
                'phone'=> '6285649103217',
                'job_title'=> 'Admin Siakad',
            ],
            [
                'name'=> 'Ella Alvianita Farikha',
                'email'=> 'ella@gmail.com',
                'username'=> 'ella',
                'password'=> bcrypt('akademik689282'),
                'avatar'=> 'https://ui-avatars.com/api/?name=Muhammad+Fadli,+S.Kom&background=random',
                'phone'=> '6282323836232',
                'job_title'=> 'Admin Siakad',
            ],
            [
                'name'=> 'Rifqi Maula Iqbal',
                'email'=> 'bootstrapcss94@gmail.com',
                'username'=> 'iqbal',
                'password'=> bcrypt('akademik689282'),
                'avatar'=> 'https://ui-avatars.com/api/?name=Muhammad+Fadli,+S.Kom&background=random',
                'phone'=> '6282140942063',
                'job_title'=> 'Admin Siakad',
            ],
            [
                'name'=> 'Malak Diana Dewi',
                'email'=> 'malakdiana00@gmail.com',
                'username'=> 'malak',
                'password'=> bcrypt('akademik689282'),
                'avatar'=> 'https://ui-avatars.com/api/?name=Muhammad+Fadli,+S.Kom&background=random',
                'phone'=> '6281333390340',
                'job_title'=> 'Admin Siakad',
            ],
            
            [
                'name'=> 'Dirgantari Rukmana',
                'email'=> 'dirgantari@gmail.com',
                'username'=> 'dirgantari',
                'password'=> bcrypt('akademik689282'),
                'avatar'=> 'https://ui-avatars.com/api/?name=Muhammad+Fadli,+S.Kom&background=random',
                'phone'=> '62895398547329',
                'job_title'=> 'Helpdesk',
            ],
            [
                'name'=> 'Zahara Madania',
                'email'=> 'zahara@gmail.com',
                'username'=> 'zahara',
                'password'=> bcrypt('akademik689282'),
                'avatar'=> 'https://ui-avatars.com/api/?name=Muhammad+Fadli,+S.Kom&background=random',
                'phone'=> '6289687044688',
                'job_title'=> 'Helpdesk',
            ],
            [
                'name'=> 'Ragil Nur Sumanjaya',
                'email'=> 'ragil@gmail.com',
                'username'=> 'ragil',
                'password'=> bcrypt('akademik689282'),
                'avatar'=> 'https://ui-avatars.com/api/?name=Muhammad+Fadli,+S.Kom&background=random',
                'phone'=> '62895399570070',
                'job_title'=> 'Helpdesk',
            ],

        ];
        foreach ($dataUsers as $user) {
            $user = User::create($user)->assignRole($this->jobTitleToRole[$user['job_title']]);
        }
        // User::factory(20)
        //     ->create()
        //     ->each(fn (User $user) => $user->assignRole($this->jobTitleToRole[$user->job_title]));
    }

    private function getJobTitle(string $role): string
    {
        foreach ($this->jobTitleToRole as $title => $value) {
            if ($role === $value) {
                return $title;
            }
        }
    }
}
