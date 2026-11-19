<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use File;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use App\Services\TelegramService;


class SendAndDeleteFileBackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-and-delete-file-backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send and delete file backup database from cron';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->disk =  Storage::build(['driver' => 'local','root' => public_path('backup_db')]);
        $this->telegram_service = new TelegramService();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
        public function handle()
    {
        $failed = false;

        foreach ($this->disk->directories('/') as $dir) {
            $allSent = true;

            foreach ($this->disk->files($dir) as $attachFile) {
                $filePath = $this->disk->path($attachFile);

                try {
                    $this->telegram_service->sendFile($filePath, 'Backup Database File');
                    $this->info("Terkirim: {$attachFile}");
                } catch (\Throwable $ex) {
                    $allSent = false;
                    $this->error("Gagal mengirim {$attachFile}: ".$ex->getMessage());
                }
            }

            if ($allSent) {
                $this->disk->deleteDirectory($dir);
            } else {
                $failed = true;
                $this->warn("Folder {$dir} dipertahankan karena ada file yang gagal dikirim.");
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
