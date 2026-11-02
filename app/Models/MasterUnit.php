<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Lacodix\LaravelModelFilter\Traits\IsSortable;
use LaravelArchivable\Archivable;

class MasterUnit extends Model
{
    use Archivable, IsSearchable, IsSortable;

    protected $table = 'master_units';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    protected $searchable = ['code', 'name'];

    protected $sortable = [
        'code' => 'asc',
        'name' => 'asc',
    ];

    public function subUnits()
    {
        return $this->hasMany(MasterSubUnit::class, 'master_unit_code', 'code');
    }

    public function stos()
    {
        return $this->hasMany(MasterSto::class, 'id_unit');
    }
}