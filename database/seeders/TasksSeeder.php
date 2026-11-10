<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Project;
use App\Models\TaskGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::with(['taskGroups'])->get();
        $admin = User::role('admin')->first();

        // TaskObserver mencatat aktivitas atas nama user yang sedang login
        auth()->setUser($admin);

        foreach ($projects as $project) {
            $number = 1;

            $project->taskGroups->each(function (TaskGroup $taskGroup, int $key) use ($project, $admin, &$number) {
                // grup berkode DONE = pelayanan selesai -> completed_at terisi; grup lain -> NULL
                $isDone = strtoupper(trim((string) $taskGroup->code)) === 'DONE';

                for ($i = 0; $i < random_int(0, 8); $i++) {
                    // dibuat 0-30 hari lalu, supaya completed_at (di antara dibuat s/d sekarang) tidak mendahului dibuat
                    $createdAt = now()->subDays(random_int(0, 30))->subMinutes(random_int(0, 1439));

                    $task = $taskGroup->tasks()->create([
                        'project_id' => $project->id,
                        'created_by_user_id' => $admin->id,
                        'assigned_to_user_id' => $admin->id,
                        'name' => fake()->sentence,
                        'number' => $number++,
                        'description' => fake()->sentences(4, true),
                        'due_on' => fake()->randomElement([
                            now()->addDays(random_int(1, 9)),
                            now()->subDays(random_int(1, 9)),
                            null,
                            null,
                            null,
                            null,
                            null,
                        ]),
                        'estimation' => 0,
                        'hidden_from_clients' => false,
                        'billable' => true,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                        'completed_at' => $isDone ? fake()->dateTimeBetween($createdAt, 'now') : null,
                    ]);

                    Label::inRandomOrder()
                        ->limit(random_int(0, 3))
                        ->get()
                        ->each(function (Label $label) use ($task) {
                            $task->labels()->attach($label);
                        });
                }
            });
        }
    }
}
