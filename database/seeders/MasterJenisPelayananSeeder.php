<?php

namespace Database\Seeders;

use App\Models\MasterJenisPelayanan;
use Illuminate\Database\Seeder;

class MasterJenisPelayananSeeder extends Seeder
{
    public function run(): void
    {
        MasterJenisPelayanan::truncate();
        
        // jumlah_hari = batas waktu penyelesaian (tanggal pelayanan dibuat + jumlah_hari).
        // null = batas waktu dipilih manual oleh penginput, sama seperti jenis "LAINNYA".
        // Isi angkanya di bawah ini sesuai ketentuan layanan masing-masing.
        $data = [
            ['code' => '1466', 'name' => 'BEASISWA KIP KULIAH', 'jumlah_hari' => 2],
            ['code' => '1467', 'name' => 'REVISI NILAI PADA KHS', 'jumlah_hari' => 2],
            ['code' => '1468', 'name' => 'HER REGISTRASI MAHASISWA LAMA', 'jumlah_hari' => 2],
            ['code' => '1469', 'name' => 'PELAYANAN PEMBUATAN KTM RUSAK ATAU HILANG', 'jumlah_hari' => 4],
            ['code' => '1470', 'name' => 'PMB SPAN-PTKIN', 'jumlah_hari' => 4],
            ['code' => '1471', 'name' => 'PENERIMAAN MAHASISWA ASING', 'jumlah_hari' => 4],
            ['code' => '1472', 'name' => 'REGISTRASI MAHASISWA BARU', 'jumlah_hari' => 3],
            ['code' => '1473', 'name' => 'PENGAMBILAN IJAZAH', 'jumlah_hari' => 3],
            ['code' => '1474', 'name' => 'MENGUNDURKAN DIRI', 'jumlah_hari' => 3],
            ['code' => '1475', 'name' => 'WISUDA', 'jumlah_hari' => 4],
            ['code' => '1476', 'name' => 'PENGGANTI IJAZAH HILANG RUSAK', 'jumlah_hari' => 5],
            ['code' => '1477', 'name' => 'PELAYANAN PEMBERIAN IZIN CUTI STUDI', 'jumlah_hari' => 5],
            ['code' => '1478', 'name' => 'SURAT KETERANGAN TIDAK MENERIMA BEASISWA', 'jumlah_hari' => 5],
            ['code' => '1479', 'name' => 'PENGAJUAN SKL', 'jumlah_hari' => 3],
            ['code' => '1480', 'name' => 'PERUBAHAN NILAI', 'jumlah_hari' => 3],
            ['code' => '1481', 'name' => 'PENGAJUAN SURAT KETERANGAN ALUMNI', 'jumlah_hari' => 3],
            ['code' => '1482', 'name' => 'PENCABUTAN BEASISWA', 'jumlah_hari' => 3],
            ['code' => '1483', 'name' => 'BEASISWA PEMDA', 'jumlah_hari' => 3],
            ['code' => '1484', 'name' => 'BEASISWA BAZNAS', 'jumlah_hari' => 5],
            ['code' => '1485', 'name' => 'PERUBAHAN PEMROGRAMAN MATA KULIAH', 'jumlah_hari' => 4],
            ['code' => '1486', 'name' => 'MUTASI KELUAR', 'jumlah_hari' => 3],
            ['code' => '1487', 'name' => 'BEASISWA NON-AKADEMIK', 'jumlah_hari' => 3],
            ['code' => '1488', 'name' => 'MUTASI MASUK', 'jumlah_hari' => 3],
            ['code' => '1489', 'name' => 'PMB MANDIRI', 'jumlah_hari' => 2],
            ['code' => '1490', 'name' => 'PMB UMPTKIN', 'jumlah_hari' => 2],
            ['code' => '1491', 'name' => 'PENERBITAN IJAZAH SARJANA', 'jumlah_hari' => 2],
            ['code' => '1547', 'name' => 'PENERBITAN IJAZAH PASCASARJANA DAN DOKTORAL', 'jumlah_hari' => 2],
            ['code' => '1548', 'name' => 'BANDING UKT MAHASISWA BARU', 'jumlah_hari' => 2],
            [
                'code' => 'LAINNYA',
                'name' => 'LAINNYA',
                'jumlah_hari' => null,
                'keterangan' => 'Batas waktu dipilih manual oleh penginput pelayanan.',
            ],
        ];

        foreach ($data as $row) {
            // withArchived(): jenis yang sudah diarsipkan tetap ditemukan, bukan dibuat ulang
            $jenis = MasterJenisPelayanan::withArchived()->firstOrNew(['code' => $row['code']]);

            $jenis->name = $row['name'];

            // jumlah_hari & keterangan hanya diisi bila masih kosong, supaya hasil edit
            // lewat halaman Jenis Pelayanan tidak tertimpa saat seeder dijalankan ulang.
            $jenis->jumlah_hari ??= $row['jumlah_hari'];
            $jenis->keterangan ??= $row['keterangan'] ?? 'Mengacu pada SOP '.$row['name'].' (No. '.$row['code'].').';

            $jenis->save();
        }
    }
}
