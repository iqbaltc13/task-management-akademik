<?php

namespace App\Models;

use App\Models\Filters\WhereInFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Activity extends Model
{
    use HasFilters, Uuids;

    const UPDATED_AT = null;
    public $incrementing = false;
    // protected $fillable = [
    //     'user_id',
    //     'project_id',
    //     'title',
    //     'subtitle',
    //     'created_at',
    // ];

    protected $guarded = [

    ];

    public function filters(): array
    {
        return [
            (new WhereInFilter('project_id'))->setQueryName('project'),
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activityCapable(): MorphTo
    {
        return $this->morphTo();
    }
}
