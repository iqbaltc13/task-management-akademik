<?php

namespace Database\Seeders;

use App\Models\TaskGroup;
use App\Models\TaskGroupUpdateLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Menyamakan tasks.completed_at dengan status (task_groups.code):
 *  - task di grup ber-code DONE   -> completed_at diisi (bila masih kosong)
 *  - task di grup selain DONE     -> completed_at dikosongkan (NULL)
 *
 * Dijalankan: php artisan db:seed --class=TaskCompletedAtSyncSeeder
 */
class TaskCompletedAtSyncSeeder extends Seeder
{
    public function run(): void
    {
        $doneGroupIds = DB::table('task_groups')
            ->whereRaw('UPPER(TRIM(code)) = ?', [TaskGroup::CODE_DONE])
            ->pluck('id')
            ->all();

        // Pengaman: tanpa grup DONE, langkah "kosongkan" akan menghapus completed_at SEMUA task.
        if (empty($doneGroupIds)) {
            $this->command?->error('Tidak ada task_groups dengan code DONE. Seeder dibatalkan, tidak ada data diubah.');

            return;
        }

        $filled = 0;
        $cleared = 0;

        DB::transaction(function () use ($doneGroupIds, &$filled, &$cleared) {
            // 1) Task di grup DONE: isi completed_at.
            //    Waktunya diambil dari saat task masuk ke grup DONE (log perpindahan terakhir),
            //    cadangan: updated_at, lalu waktu sekarang.
            DB::table('tasks')
                ->whereIn('group_id', $doneGroupIds)
                ->whereNull('completed_at')
                ->select('id', 'group_id', 'updated_at')
                ->chunkById(200, function ($tasks) use (&$filled) {
                    foreach ($tasks as $task) {
                        $enteredDoneAt = TaskGroupUpdateLog::where('task_id', $task->id)
                            ->where('new_group_id', $task->group_id)
                            ->latest('created_at')
                            ->value('created_at');

                        DB::table('tasks')->where('id', $task->id)->update([
                            'completed_at' => $enteredDoneAt ?? $task->updated_at ?? now(),
                        ]);

                        $filled++;
                    }
                });

            // 2) Task di grup selain DONE (atau tanpa grup): kosongkan completed_at.
            $cleared = DB::table('tasks')
                ->whereNotNull('completed_at')
                ->where(function ($query) use ($doneGroupIds) {
                    $query->whereNotIn('group_id', $doneGroupIds)->orWhereNull('group_id');
                })
                ->update(['completed_at' => null]);
        });

        $this->command?->info("completed_at diisi: {$filled} task, dikosongkan: {$cleared} task.");
    }
}
