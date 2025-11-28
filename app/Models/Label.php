<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Lacodix\LaravelModelFilter\Traits\IsSortable;
use LaravelArchivable\Archivable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Label extends Model
{
    use Archivable, IsSearchable, IsSortable, Uuids;
    public $incrementing = false;
    // protected $fillable = ['name', 'color'];
    protected $guarded = [

    ];

    protected $searchable = [
        'name',
    ];

    protected $sortable = [
        'name' => 'asc',
    ];
}
