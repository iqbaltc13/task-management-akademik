<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Lacodix\LaravelModelFilter\Traits\IsSortable;
use LaravelArchivable\Archivable;

class MasterJenisPelayanan extends Model
{
    use Archivable, IsSearchable, IsSortable;

    protected $table = 'master_jenis_pelayanans';

    protected $guarded = [];

    protected $casts = [
        'jumlah_hari' => 'integer',
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
        'jumlah_hari' => 'asc',
    ];
}
