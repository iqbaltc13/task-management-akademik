<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\TaskGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Task $task,
        public ?TaskGroup $oldGroup,
        public TaskGroup $newGroup,
    ) {
    }

    public function build()
    {
            return $this
                ->subject("Status permintaan \"{$this->task->name}\" diperbarui")
                ->view('emails.task.status-changed');    
    }
}