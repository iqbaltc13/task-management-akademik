<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $project->activities()->create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'title' => 'Periode permintaan baru',
            'subtitle' => "\"{$project->name}\" dibuat oleh ".auth()->user()->name,
        ]);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        if ($project->isDirty(['name'])) {
            $project->activities()->create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'title' => 'Nama periode permintaan diubah',
                'subtitle' => "dari \"{$project->getOriginal('name')}\" menjadi \"{$project->name}\" oleh ".auth()->user()->name,
            ]);
        }
    }

    /**
     * Handle the Project "archived" event.
     */
    public function archived(Project $project): void
    {
        $project->activities()->create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'title' => 'Periode permintaan dihapus',
            'subtitle' => "\"{$project->name}\" dihapus oleh ".auth()->user()->name,
        ]);
    }

    /**
     * Handle the Project "unArchived" event.
     */
    public function unArchived(Project $project): void
    {
        $project->activities()->create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'title' => 'Periode Permintaan dipulihkan',
            'subtitle' => "\"{$project->name}\" dipulihkan oleh ".auth()->user()->name,
        ]);
    }
}
