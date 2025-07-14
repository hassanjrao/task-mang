<?php

namespace App\Jobs;

use App\Models\Task;
use App\Notifications\TaskReminderEmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTaskReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $task;
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Sending task reminder for task ID: ' . $this->task->id);
        $methods = json_decode($this->task->reminder_methods ?? '[]');

        if (in_array('email', $methods)) {
            $this->task->createdBy->notify(new TaskReminderEmailNotification($this->task));
        }

        if (in_array('web', $methods)) {
            // Notification::send($this->task->user, new TaskWebNotification($this->task));
        }
    }
}
