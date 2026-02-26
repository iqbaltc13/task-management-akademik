<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class ResultOfTaskAttachment extends Model
{
    use Uuids, SoftDeletes;
    public $incrementing = false;
    protected $table = 'result_of_task_attachments';
    // protected $fillable = [
    //     'task_id',
    //     'user_id',
    //     'name',
    //     'path',
    //     'thumb',
    //     'type',
    //     'size',
    // ];
    protected $guarded = [

    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
