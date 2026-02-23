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
                ['name' => 'Masuk Antrian'],
                ['name' => 'Akan Dikerjakan'],
                ['name' => 'Sedang Dikerjakan'],
                //['name' => 'QA'],
                ['name' => 'Selesai'],
                //['name' => 'Deployed'],
            ]);
        }
    }
}
