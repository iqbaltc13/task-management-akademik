<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            LabelSeeder::class,
            CurrencySeeder::class,
            CountrySeeder::class,
        ]);

        if ($this->command->confirm('Seed development data?', false)) {
            $this->call([
                UserSeeder::class,
                
            ]);

            auth()->setUser(User::role('admin')->first());

            $this->call([
                ProjectSeeder::class,
                TaskGroupSeeder::class,
                
            ]);
        } else {
            $this->call([ProductionSeeder::class]);
        }
    }
}
