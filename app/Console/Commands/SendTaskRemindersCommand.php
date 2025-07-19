<?php

namespace App\Console\Commands;

use App\Events\TaskReminderEvent;
use App\Jobs\SendTaskReminderJob;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTaskRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        $task=Task::find(1);
        broadcast(new TaskReminderEvent($task));
        dispatch(new SendTaskReminderJob($task));


        // Task::whereNotNull('due_datetime')
        //     ->whereNotNull('reminder_offset')
        //     ->get()
        //     ->each(function ($task) use ($now) {
        //         $remindAt = Carbon::parse($task->due_datetime)->subMinutes($task->reminder_offset);

        //         if ($remindAt->format('Y-m-d H:i') === $now->format('Y-m-d H:i')) {
        //             dispatch(new SendTaskReminderJob($task));
        //         }
        //     });
    }
}
