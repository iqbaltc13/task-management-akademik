<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use File;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
//use App\Services\TelegramService;


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
        //$this->telegram_service = new TelegramService();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = [
            'title' => '',
            'body' => ''
        ];
        $filePath = ''; // Change this to the actual file path
        $subject = 'Notification Backup Database';
        $arrDir = $this->disk->directories('/');
        foreach ($arrDir as $key => $dir) {
            $getFiles = $this->disk->files($dir);
            try {
                foreach ($getFiles as $attachFile) {
                    $data = [
                        'title' => 'Email Cron Backup Database',
                        'body' => 'this email contain file of backup database'
                    ];
                    $filePath = public_path('backup_db/'.$attachFile);
                    //send to telegram channel
                    $this->telegram_service->sendFile($filePath, 'Backup Database File');
                    //send mail

                    //Mail::to(env('EMAIL_BACKUP_DATABASE', 'bagiundanganofficial@gmail.com'))->send(new SendMail($data, $filePath, $subject));


                }
            } catch (\Exception $ex) {
                //$this->disk->deleteDirectory($dir);
                continue;
            }
            $this->disk->deleteDirectory($dir);
            //delete files

        }
    }
}
