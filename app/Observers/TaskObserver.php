<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskGroupUpdateLog;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $task->activities()->create([
            'project_id' => $task->project_id,
            'user_id' => auth()->id(),
            'title' => 'Permintaan baru',
            'subtitle' => "\"{$task->name}\" dibuat oleh ".auth()->user()->name,
        ]);

        if ($task->assigned_to_user_id !== null) {
            $task->assigned_at = now();
            $task->saveQuietly();
        }

        if ($task->group_id !== null) {
            TaskGroupUpdateLog::create([
                'task_id'      => $task->id,
                'old_group_id' => null,
                'new_group_id' => $task->group_id,
                'user_id'      => auth()->id(),
            ]);
        }
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if ($task->isDirty('name')) {
            $task->activities()->create([
                'project_id' => $task->project_id,
                'user_id' => auth()->id(),
                'title' => 'Nama permintaan diperbarui',
                'subtitle' => "Dari \"{$task->getOriginal('name')}\" menjadi \"{$task->name}\" oleh ".auth()->user()->name,
            ]);
        }
        if ($task->isDirty('description')) {
            $task->activities()->create([
                'project_id' => $task->project_id,
                'user_id' => auth()->id(),
                'title' => 'Deskripsi permintaan diperbarui',
                'subtitle' => "pada \"{$task->name}\" oleh ".auth()->user()->name,
            ]);
        }
        if ($task->isDirty('assigned_to_user_id')) {
            $task->activities()->create([
                'project_id' => $task->project_id,
                'user_id' => auth()->id(),
                'title' => $task->assigned_to_user_id ? 'Pengguna ditugaskan ke permintaan' : 'Pengguna yang ditugaskan telah dihapus',
                'subtitle' => $task->assigned_to_user_id
                    ? "\"{$task->name}\" ditugaskan ke {$task->assignedToUser->name} oleh ".auth()->user()->name
                    : "pada permintaan \"{$task->name}\" oleh ".auth()->user()->name,
            ]);

            $task->assigned_at = now();
            $task->saveQuietly();
        }
        if ($task->isDirty('due_on')) {
            $task->activities()->create([
                'project_id' => $task->project_id,
                'user_id' => auth()->id(),
                'title' => $task->due_on ? 'Batas waktu ditetapkan pada permintaan' : 'Batas waktu dihapus',
                'subtitle' => $task->due_on
                    ? "hingga {$task->due_on->format('F j, Y')} pada \"{$task->name}\" oleh ".auth()->user()->name
                    : "pada \"{$task->name}\" permintaan oleh ".auth()->user()->name,
            ]);
        }
        if ($task->isDirty('estimation')) {
            $task->activities()->create([
                'project_id' => $task->project_id,
                'user_id' => auth()->id(),
                'title' => 'Estimasi telah diatur',
                'subtitle' => "hingga {$task->estimation}h pada \"{$task->name}\" oleh ".auth()->user()->name,
            ]);
        }
        if ($task->isDirty('completed_at')) {
            $task->activities()->create([
                'project_id' => $task->project_id,
                'user_id' => auth()->id(),
                'title' => $task->completed_at ? 'Permintaan telah selesai' : 'Status permintaan diubah menjadi belum selesai',
                'subtitle' => "\"{$task->name}\" diubah menjadi ".($task->completed_at ? 'telah selesai' : 'belum selesai').' oleh '.auth()->user()->name,
            ]);
        }
        if ($task->isDirty('group_id')) {
            $oldGroupId = $task->getOriginal('group_id');
            $oldGroupName = $oldGroupId
                ? \App\Models\TaskGroup::find($oldGroupId)?->name
                : null;

            // Catat ke timeline aktivitas umum
            $task->activities()->create([
                'project_id' => $task->project_id,
                'user_id' => auth()->id(),
                'title' => 'Grup permintaan diperbarui',
                'subtitle' => $oldGroupName
                    ? "Dari \"{$oldGroupName}\" menjadi \"{$task->group->name}\" oleh ".auth()->user()->name
                    : "Diatur ke \"{$task->group->name}\" oleh ".auth()->user()->name,
            ]);

            // Catat juga ke tabel khusus histori grup
            TaskGroupUpdateLog::create([
                'task_id'      => $task->id,
                'old_group_id' => $oldGroupId,
                'new_group_id' => $task->group_id,
                'user_id'      => auth()->id(),
            ]);
        }
    }

    /**
     * Handle the Project "archived" event.
     */
    public function archived(Task $task): void
    {
        $task->activities()->create([
            'project_id' => $task->project_id,
            'user_id' => auth()->id(),
            'title' => 'Permintaan dihapus',
            'subtitle' => "\"{$task->name}\" dihapus oleh ".auth()->user()->name,
        ]);
    }

    /**
     * Handle the Project "unArchived" event.
     */
    public function unArchived(Task $task): void
    {
        $task->activities()->create([
            'project_id' => $task->project_id,
            'user_id' => auth()->id(),
            'title' => 'Permintaan dipulihkan',
            'subtitle' => "\"{$task->name}\" dipulihkan oleh ".auth()->user()->name,
        ]);
    }
}
