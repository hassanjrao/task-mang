<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkOverdueTasksCommand extends Command
{
    protected $signature = 'tasks:mark-overdue';
    protected $description = 'Mark tasks as overdue if their due date has passed';


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

        $overDueTaskStatusId=4;
        $completedTaskStatusId=3;


        $affected = Task::whereNotIn('task_status_id', [$completedTaskStatusId, $overDueTaskStatusId])
            ->where('due_datetime', '<', $now)
            ->update(['task_status_id' => $overDueTaskStatusId]);

        $this->info("Marked $affected tasks as overdue.");
    }
}
