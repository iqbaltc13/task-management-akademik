<?php

namespace Database\Seeders;

use App\Models\JobTitle;
use App\Models\MasterJenisPelayanan;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DemoTaskSeeder extends Seeder
{
    private string $projectId = '42a86534-904f-4cd7-9ffa-5c5656c0f2a0';

    private array $groupIds = [
        'f76c692a-94a3-4cff-ac78-486957841182', // Diajukan
        'd9e5b706-14f9-4ed1-a59b-09d31ee40054', // Diproses
        'a8c47c0f-c938-41c8-8f54-7f6330347deb', // Tidak Dilanjutkan
        '7158cf7a-aa56-421f-86cb-83caf93a40ec', // Ditolak
        '4e50f885-806b-4755-9349-ea76147802a3', // Selesai
    ];

    private array $userIds = [
        'b0c6a894-e446-4d90-b727-2d01f35417c5', // Rifqi Maula Iqbal
        '77dd7755-11bc-4d08-a20f-7b2fca7fdf37', // Dirgantari Rukmana
        '7771d751-1c98-40ea-81be-6012a29ffb72', // Zahara Madania
        '4449a8c3-84f0-4a49-a009-b1d71f56abba', // M. Gervais Pratama
        'e441742f-ed30-40eb-9487-6ea8060163f6', // Ragil Nur Sumanjaya
        'fa8c248b-8d27-4677-87f5-0744c225cb92', // Ella Alvianita Farikha
        '34f12906-7009-4853-9155-5c9f1c78db98', // Johan Setiawan
        '50d3f607-ed92-4b16-a1ff-064b899ecbbb', // Moch. Taufiq Yahya
    ];

    public function run(int $count = 50): void
    {
        $path = storage_path('app/seeders/Data_Pendaftar_2026.xlsx');

        if (! file_exists($path)) {
            $this->command?->error("File tidak ditemukan: {$path}");
            return;
        }

        $spreadsheet = IOFactory::load($path);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
        array_shift($rows); // buang baris header

        $pendaftar = collect($rows)
            ->filter(fn ($r) => !empty($r[0]) && !empty($r[2])) // namapendaftar, nim
            ->map(fn ($r) => ['name' => trim($r[0]), 'nim' => trim($r[2])])
            ->shuffle()
            ->values();

        if ($pendaftar->isEmpty()) {
            $this->command?->error('Tidak ada baris valid di file excel.');
            return;
        }

        $jenisPelayananCodes = MasterJenisPelayanan::pluck('code')->all();

        if (empty($jenisPelayananCodes)) {
            $this->command?->error('Tabel master_jenis_pelayanans masih kosong. Jalankan seeder-nya dulu.');
            return;
        }

        $jobTitleCodes = JobTitle::pluck('code')->all();

        $originalUser = Auth::user();

        for ($i = 0; $i < $count; $i++) {
            $pemohon = $pendaftar[$i % $pendaftar->count()];
            $groupId = $this->groupIds[array_rand($this->groupIds)];
            $assignedTo = $this->userIds[array_rand($this->userIds)];
            $createdBy = $this->userIds[array_rand($this->userIds)];

            // login sebagai "pembuat" supaya auth()->user() di TaskObserver tidak null,
            // dan TaskAssignedUserUpdateLog/TaskGroupUpdateLog tercatat via Observer otomatis
            Auth::loginUsingId($createdBy);

            Task::create([
                'project_id' => $this->projectId,
                'group_id' => $groupId,
                'created_by_user_id' => $createdBy,
                'assigned_to_user_id' => $assignedTo,
                'code_jenis_pelayanan' => $jenisPelayananCodes[array_rand($jenisPelayananCodes)],
                'name' => $pemohon['name'],
                'identity_number' => $pemohon['nim'],
                'code' => 'PLYN-'.now()->format('YmdHis').sprintf('%03d', $i),
                'description' => 'Permintaan pelayanan untuk '.$pemohon['name'],
                'due_on' => now()->addDays(rand(3, 14)),
                'job_title' => $jobTitleCodes ? $jobTitleCodes[array_rand($jobTitleCodes)] : null,
            ]);
        }

        // kembalikan auth ke kondisi semula setelah seeding selesai
        if ($originalUser) {
            Auth::login($originalUser);
        } else {
            Auth::logout();
        }

        $this->command?->info("{$count} data pelayanan berhasil dibuat.");
    }
}