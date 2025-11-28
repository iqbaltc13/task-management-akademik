<?php

namespace App\Models;

use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Lacodix\LaravelModelFilter\Traits\IsSortable;
use LaravelArchivable\Archivable;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Role extends SpatieRole
{
    use Archivable, IsSearchable, IsSortable;

    //protected $fillable = ['name', 'guard_name'];

    protected $guarded = [

    ];
    protected $searchable = [
        'name',
    ];

    protected $sortable = [
        'name' => 'asc',
    ];
}
