<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class TaskGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            $project->taskGroups()->createMany([
                ['code' =>'CREATED', 'name' => 'Diajukan'],
                ['code' =>'IN_PROGRESS', 'name' => 'Diproses'],
                ['code' =>'CANCELLED' 'name' => 'Tidak Dilanjutkan'],
                ['code' =>'REJECTED', 'name' =>'Ditolak'],
                ['code' =>'DONE', 'name' =>'Selesai'],
                //['name' => 'Deployed'],
            ]);
        }
    }
}
