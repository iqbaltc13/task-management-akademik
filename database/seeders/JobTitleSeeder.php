<?php

namespace Database\Seeders;

use App\Models\JobTitle;
use Illuminate\Database\Seeder;

class JobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobTitle::insert([
            ['code' => 'mhs', 'name' => 'Mahasiswa'],
            ['code' => 'umum', 'name' => 'Umum'],
            ['code' => 'dosen', 'name' => 'Dosen'],
            ['code' => 'tendik', 'name' => 'Tenaga Kependidikan'],
        ]);
    }
}
