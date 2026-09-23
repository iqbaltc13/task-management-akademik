<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TaskListExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private array $headings = [
        'Nama Pemohon', 'Nomor Pelayanan', 'Penerima Pelayanan', 'Penerima Tugas',
        'Status', 'Tanggal Masuk', 'Batas Waktu', 'Periode Pelayanan',
    ];

    public function __construct(private Collection $rows)
    {
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        // pakai array_values supaya urutan kolom PASTI sama dengan headings(),
        // tidak bergantung urutan key associative array
        return array_values($row);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}