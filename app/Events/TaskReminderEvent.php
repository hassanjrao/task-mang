<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskReminderEvent implements ShouldBroadcast
{
    public $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function broadcastOn()
    {
        return ['task-reminders'];
    }

    public function broadcastAs()
    {
        return 'TaskDue';
    }
}
