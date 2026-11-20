<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAndDeleteFileBackupDatabase extends Command
{
    /**
     * Nama command dipertahankan agar cron/Kernel yang sudah ada tidak rusak.
     * File TIDAK lagi dihapus di sini: retensi 14 hari diurus oleh backup-pgsql.sh.
     */
    protected $signature = 'send-and-delete-file-backup-database
                            {--path=/var/backups/postgres : Folder induk hasil backup-pgsql.sh}
                            {--max-age=26 : Kirim peringatan jika tidak ada backup baru dalam N jam}';

    protected $description = 'Kirim file backup database (data & schema) ke Telegram, lalu tandai sebagai terkirim';

    /** Batas upload bot Telegram 50 MB; sisakan ruang aman. */
    private const TELEGRAM_LIMIT = 49 * 1024 * 1024;
    private const CHUNK_SIZE     = 45 * 1024 * 1024;

    private TelegramService $telegram;

    public function handle(): int
    {
        $this->telegram = app(TelegramService::class);

        $root   = rtrim((string) $this->option('path'), '/');
        $maxAge = (int) $this->option('max-age');

        if (!is_dir($root) || !is_readable($root)) {
            $this->reportError("Folder backup tidak ada atau tidak terbaca: {$root}");
            return self::FAILURE;
        }

        $sent    = 0;
        $failed  = false;
        $newest  = 0;

        foreach (glob($root . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
            // glob() melewati file berawalan titik, jadi *.partial otomatis diabaikan
            $files = glob($dir . '/*.sql.zst') ?: [];
            sort($files);

            foreach ($files as $file) {
                $newest = max($newest, (int) filemtime($file));

                if (is_file($file . '.sent')) {
                    continue; // sudah pernah terkirim
                }

                if (!$this->checksumOk($file)) {
                    $failed = true;
                    $this->reportError('Checksum tidak cocok, file TIDAK dikirim: ' . basename($file));
                    continue;
                }

                try {
                    $this->sendOne($file);
                    file_put_contents($file . '.sent', date('c'));
                    $sent++;
                    $this->info('Terkirim: ' . basename($file));
                } catch (\Throwable $e) {
                    $failed = true;
                    $this->reportError('Gagal mengirim ' . basename($file) . ': ' . $e->getMessage());
                }
            }
        }

        // Deteksi backup yang diam-diam berhenti
        if ($newest === 0 || (time() - $newest) > $maxAge * 3600) {
            $failed = true;
            $this->reportError("Tidak ada file backup baru dalam {$maxAge} jam terakhir. Cek /var/log/backup-pgsql.log");
        }

        $this->info("Selesai. File terkirim: {$sent}");

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function sendOne(string $file): void
    {
        $size    = (int) filesize($file);
        $caption = sprintf("Backup Database\n%s\nUkuran: %s", basename($file), $this->humanSize($size));

        if ($size <= self::TELEGRAM_LIMIT) {
            $this->deliver($file, $caption);
            return;
        }

        $this->sendInParts($file, $caption, $size);
    }

    /** File > 49 MB dipecah; gabungkan kembali dengan: cat nama.sql.zst.part* > nama.sql.zst */
    private function sendInParts(string $file, string $caption, int $size): void
    {
        $total = (int) ceil($size / self::CHUNK_SIZE);
        $in    = fopen($file, 'rb');
        if ($in === false) {
            throw new \RuntimeException('Tidak bisa membuka file');
        }

        try {
            for ($i = 1; $i <= $total; $i++) {
                $part = sprintf('%s/%s.part%03d', sys_get_temp_dir(), basename($file), $i);
                $out  = fopen($part, 'wb');
                @chmod($part, 0600);
                $left = self::CHUNK_SIZE;

                while ($left > 0 && !feof($in)) {
                    $buf = fread($in, min(1048576, $left));
                    if ($buf === false || $buf === '') {
                        break;
                    }
                    fwrite($out, $buf);
                    $left -= strlen($buf);
                }
                fclose($out);

                try {
                    $this->deliver(
                        $part,
                        "{$caption}\nBagian {$i}/{$total}\nGabung: cat " . basename($file) . '.part* > ' . basename($file)
                    );
                } finally {
                    @unlink($part);
                }
            }
        } finally {
            fclose($in);
        }
    }

    private function deliver(string $path, string $caption): void
    {
        $result = $this->telegram->sendFile($path, $caption);

        // Jika TelegramService mengembalikan false saat gagal (bukan melempar exception)
        if ($result === false) {
            throw new \RuntimeException('TelegramService::sendFile mengembalikan false');
        }
    }

    /** Cocokkan dengan file .sha256 yang dibuat backup-pgsql.sh (dilewati bila tidak ada). */
    private function checksumOk(string $file): bool
    {
        $sumFile = $file . '.sha256';
        if (!is_file($sumFile)) {
            return true;
        }

        $expected = strtok(trim((string) file_get_contents($sumFile)), ' ');

        return is_string($expected) && hash_equals($expected, (string) hash_file('sha256', $file));
    }

    private function reportError(string $message): void
    {
        $this->error($message);
        Log::error('[backup-db] ' . $message);

        try {
            if (method_exists($this->telegram ?? null, 'sendMessage')) {
                $this->telegram->sendMessage('⚠️ Backup DB: ' . $message);
            }
        } catch (\Throwable $e) {
            Log::warning('[backup-db] Gagal mengirim notifikasi Telegram: ' . $e->getMessage());
        }
    }

    private function humanSize(int $bytes): string
    {
        return number_format($bytes / 1048576, 1) . ' MB';
    }
}