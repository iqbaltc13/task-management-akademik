<?php

namespace App\Listeners\Task;

use App\Events\Task\TaskGroupUpdateLogCreated;
use App\Mail\TaskStatusChangedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendTaskStatusChangedEmail implements ShouldQueue
{
    public function handle(TaskGroupUpdateLogCreated $event): void
    {
        $log = $event->log;
        $task = $log->task;

        if (! $task || ! $task->email) {
            return;
        }

        Mail::to($task->email)->send(new TaskStatusChangedMail(
            task: $task,
            oldGroup: $log->oldGroup,
            newGroup: $log->newGroup,
        ));
    }
}