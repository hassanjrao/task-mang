<?php

namespace App\Console\Commands;

use App\Events\TaskReminderEvent;
use App\Jobs\SendTaskReminderJob;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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

        // $task=Task::find(1);
        // // broadcast(new TaskReminderEvent($task));
        // dispatch(new SendTaskReminderJob($task));

//         {
//     "task_id": 2,
//     "remind_at": "2025-07-22 09:54",
//     "now": "2025-07-22 09:56",
//     "due_datetime": "2025-07-22 09:54:00",
//     "reminder_offset": 0,
//     "match": false
// }

        Log::info('SendTaskRemindersCommand');

        Task::whereNotNull('due_datetime')
            ->whereNotNull('reminder_offset')
            ->get()
            ->each(function ($task) use ($now) {
                $remindAt = Carbon::parse($task->due_datetime)->subMinutes($task->reminder_offset);

                Log::info('SendTaskRemindersCommand', [
                    'task_id' => $task->id,
                    'remind_at' => $remindAt->format('Y-m-d H:i'),
                    'now' => $now->format('Y-m-d H:i'),
                    'due_datetime' => $task->due_datetime,
                    'reminder_offset' => $task->reminder_offset,
                    'match'=> $remindAt->format('Y-m-d H:i') === $now->format('Y-m-d H:i'),
                ]);

                if ($remindAt->format('Y-m-d H:i') === $now->format('Y-m-d H:i')) {
                    dispatch(new SendTaskReminderJob($task));
                }
            });
    }
}
