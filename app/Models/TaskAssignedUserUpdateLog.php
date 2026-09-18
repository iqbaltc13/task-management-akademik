<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskAssignedUserUpdateLog extends Model
{
    use HasFactory;
    protected $table = 'task_assigned_user_update_logs';
    protected $guarded = [];

     public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function oldAssignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'old_assigned_to_user_id');
    }

    public function newAssignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'new_assigned_to_user_id');
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
