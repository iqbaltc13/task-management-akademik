<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Lacodix\LaravelModelFilter\Traits\IsSortable;
use LaravelArchivable\Archivable;

class MasterSto extends Model
{
    use Archivable, IsSearchable, IsSortable;

    protected $table = 'master_stos';

    protected $guarded = [];

    protected $casts = [
        'is_pimpinan' => 'boolean',
        'level' => 'integer',
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
        'level' => 'asc',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'code_parent', 'code');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'code_parent', 'code');
    }

    public function unit()
    {
        return $this->belongsTo(MasterUnit::class, 'id_unit');
    }

    public function subUnit()
    {
        return $this->belongsTo(MasterSubUnit::class, 'id_sub_unit');
    }

    public function jabatan()
    {
        return $this->belongsTo(MasterJabatan::class, 'id_jabatan');
    }

    /**
     * Semua kode turunan (anak, cucu, dst.), termasuk yang diarsipkan.
     * Dipakai untuk mencegah induk memilih turunannya sendiri (siklus).
     *
     * @return array<int, string>
     */
    public function descendantCodes(): array
    {
        $codes = [];
        $frontier = [$this->code];

        while (! empty($frontier)) {
            $children = static::withArchived()
                ->whereIn('code_parent', $frontier)
                ->pluck('code')
                ->all();

            $children = array_values(array_diff($children, $codes));
            $codes = array_merge($codes, $children);
            $frontier = $children;
        }

        return $codes;
    }
}
