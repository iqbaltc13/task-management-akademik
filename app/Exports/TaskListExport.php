<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TaskListExport implements FromCollection, WithHeadings
{
    public function __construct(private Collection $rows)
    {
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return array_keys($this->rows->first() ?? [
            'Nama Pemohon', 'Nomor Pelayanan', 'Penerima Pelayanan', 'Penerima Tugas',
            'Status', 'Tanggal Masuk', 'Batas Waktu', 'Periode Pelayanan',
        ]);
    }
}