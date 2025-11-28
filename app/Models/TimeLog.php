<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class TimeLog extends Model
{
    use Uuids;
    public $incrementing = false;



    // protected $fillable = [
    //     'task_id',
    //     'user_id',
    //     'minutes',
    //     'timer_start',
    //     'timer_stop',
    // ];
    protected $guarded = [

    ];

    const UPDATED_AT = null;

    protected $casts = [
        'minutes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
