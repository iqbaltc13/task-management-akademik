<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TaskListExport implements FromArray, WithEvents
{
    private array $headings = [
        'Nama Pemohon', 'Nomor Pelayanan', 'Penerima Pelayanan', 'Penerima Tugas',
        'Status', 'Tanggal Masuk', 'Batas Waktu', 'Periode Pelayanan',
    ];

    private int $headerRows = 6; // baris kosong untuk kop + judul, sebelum baris heading tabel

    public function __construct(private Collection $rows)
    {
    }

    public function array(): array
    {
        $blank = array_fill(0, count($this->headings), '');

        $topBlanks = array_fill(0, $this->headerRows, $blank);

        $dataRows = $this->rows->map(fn ($row) => array_values($row))->toArray();

        return array_merge($topBlanks, [$this->headings], $dataRows);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastCol = chr(ord('A') + count($this->headings) - 1); // 'H' untuk 8 kolom

                $sheet->mergeCells("C1:{$lastCol}1");
                $sheet->setCellValue('C1', 'KEMENTERIAN AGAMA REPUBLIK INDONESIA');
                $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells("C2:{$lastCol}2");
                $sheet->setCellValue('C2', 'UNIVERSITAS ISLAM NEGERI SYEKH WASIL KEDIRI');
                $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle('C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells("C3:{$lastCol}3");
                $sheet->setCellValue('C3', 'Jalan Sunan Ampel Nomor 07 Ngronggo Kota Kediri Kode Pos 64127');
                $sheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells("C4:{$lastCol}4");
                $sheet->setCellValue('C4', 'Telepon (0354) 689282 Faksimile (0354) 686564 Website: www.uinkediri.ac.id');
                $sheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells("A6:{$lastCol}6");
                $sheet->setCellValue('A6', 'DAFTAR PELAYANAN');
                $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // baris heading tabel (baris setelah $headerRows, index mulai dari 1)
                $headingRow = $this->headerRows + 1;
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$headingRow}")->getFont()->setBold(true);

                $logoPath = public_path('images/logo-uinkediri.png');
                if (file_exists($logoPath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Logo UIN');
                    $drawing->setPath($logoPath);
                    $drawing->setHeight(75);
                    $drawing->setCoordinates('A1');
                    $drawing->setWorksheet($sheet);
                }
            },
        ];
    }
}