<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelArchivable\Archivable;

class MasterJenisPelayanan extends Model
{
    use Archivable;

    protected $table = 'master_jenis_pelayanans';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'archived_at' => 'datetime',
    ];
}