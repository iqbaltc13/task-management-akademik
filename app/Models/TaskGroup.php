<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use LaravelArchivable\Archivable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;
use Illuminate\Support\Facades\DB;

class TaskGroup extends Model implements AuditableContract, Sortable
{
    use Archivable, Auditable, HasFactory, SortableTrait, Uuids;
    public $incrementing = false;



    public $timestamps = false;

    // protected $fillable = ['name', 'project_id', 'order_column'];
    protected $guarded = [

    ];

    protected static function booted(): void
    {
        static::addGlobalScope('ordered', function ($query) {
            $query->ordered();
        });
    }

    /** task_groups.code untuk status selesai; task di grup ini otomatis completed_at terisi. */
    public const CODE_DONE = 'DONE';

    public static function isDoneGroup(?string $groupId): bool
    {
        if (blank($groupId)) {
            return false;
        }

        $code = DB::table('task_groups')->where('id', $groupId)->value('code');

        return strtoupper(trim((string) $code)) === self::CODE_DONE;
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'group_id');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'activity_capable');
    }
}
