<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Lacodix\LaravelModelFilter\Traits\IsSortable;
use LaravelArchivable\Archivable;

class MasterJabatan extends Model
{
    use Archivable, IsSearchable, IsSortable;

    protected $table = 'master_jabatans';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    protected $searchable = [
        'code',
        'name',
    ];

    protected $sortable = [
        'code' => 'asc',
        'name' => 'asc',
    ];

    public function stos()
    {
        return $this->hasMany(MasterSto::class, 'id_jabatan');
    }
}
