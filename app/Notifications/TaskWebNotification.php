<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskWebNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // for web UI
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Reminder: ' . $this->task->title,
            'message' => 'This task is due soon: ' . $this->task->description,
            'task_id' => $this->task->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Reminder: ' . $this->task->title,
            'message' => 'This task is due soon: ' . $this->task->description,
            'task_id' => $this->task->id,
        ]);
    }
}
