<?php

namespace Database\Seeders;

use App\Models\MasterJenisPelayanan;
use Illuminate\Database\Seeder;

class MasterJenisPelayananSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['code' => '1466', 'name' => 'BEASISWA KIP KULIAH'],
            ['code' => '1467', 'name' => 'REVISI NILAI PADA KHS'],
            ['code' => '1468', 'name' => 'HER REGISTRASI MAHASISWA LAMA'],
            ['code' => '1469', 'name' => 'PELAYANAN PEMBUATAN KTM RUSAK ATAU HILANG'],
            ['code' => '1470', 'name' => 'PMB SPAN-PTKIN'],
            ['code' => '1471', 'name' => 'PENERIMAAN MAHASISWA ASING'],
            ['code' => '1472', 'name' => 'REGISTRASI MAHASISWA BARU'],
            ['code' => '1473', 'name' => 'PENGAMBILAN IJAZAH'],
            ['code' => '1474', 'name' => 'MENGUNDURKAN DIRI'],
            ['code' => '1475', 'name' => 'WISUDA'],
            ['code' => '1476', 'name' => 'PENGGANTI IJAZAH HILANG RUSAK'],
            ['code' => '1477', 'name' => 'PELAYANAN PEMBERIAN IZIN CUTI STUDI'],
            ['code' => '1478', 'name' => 'SURAT KETERANGAN TIDAK MENERIMA BEASISWA'],
            ['code' => '1479', 'name' => 'PENGAJUAN SKL'],
            ['code' => '1480', 'name' => 'PERUBAHAN NILAI'],
            ['code' => '1481', 'name' => 'PENGAJUAN SURAT KETERANGAN ALUMNI'],
            ['code' => '1482', 'name' => 'PENCABUTAN BEASISWA'],
            ['code' => '1483', 'name' => 'BEASISWA PEMDA'],
            ['code' => '1484', 'name' => 'BEASISWA BAZNAS'],
            ['code' => '1485', 'name' => 'PERUBAHAN PEMROGRAMAN MATA KULIAH'],
            ['code' => '1486', 'name' => 'MUTASI KELUAR'],
            ['code' => '1487', 'name' => 'BEASISWA NON-AKADEMIK'],
            ['code' => '1488', 'name' => 'MUTASI MASUK'],
            ['code' => '1489', 'name' => 'PMB MANDIRI'],
            ['code' => '1490', 'name' => 'PMB UMPTKIN'],
            ['code' => '1491', 'name' => 'PENERBITAN IJAZAH SARJANA'],
            ['code' => '1547', 'name' => 'PENERBITAN IJAZAH PASCASARJANA DAN DOKTORAL'],
            ['code' => '1548', 'name' => 'BANDING UKT MAHASISWA BARU'],
        ];

        foreach ($data as $row) {
            MasterJenisPelayanan::updateOrCreate(
                ['code' => $row['code']],
                ['name' => $row['name']]
            );
        }
    }
}