<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskGroupUpdateLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'task_group_update_logs';
    protected $guarded = [];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function oldGroup()
    {
        return $this->belongsTo(TaskGroup::class, 'old_group_id');
    }

    public function newGroup()
    {
        return $this->belongsTo(TaskGroup::class, 'new_group_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
