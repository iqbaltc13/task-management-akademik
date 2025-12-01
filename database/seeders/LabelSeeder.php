<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Label::insert([
            ['name' => 'Selesai', 'color' => '#37B24D'],
            ['name' => 'Diajukan', 'color' => '#AE3EC9'],
            ['name' => 'Ditolak', 'color' => '#F03E3E'],
            //['name' => 'Bug', 'color' => '#D6336C'],
            ['name' => 'Rework', 'color' => '#0000FF'],
        ]);
    }
}
