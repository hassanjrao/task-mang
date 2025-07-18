<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class RepeatTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:repeat';

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

        $now = now();

        $tasks = Task::whereNotNull('repeat_type_id')
            ->where('next_repeat_at', '<=', $now)
            ->get();
        // $tasks = Task::where('id',16)
        //     ->get();

        foreach ($tasks as $task) {
            // Clone the task
            $newTask = $task->replicate();
            $newTask->created_at = now();
            $newTask->updated_at = now();
            $newTask->next_repeat_at = null;
            $newTask->save();

            // clone subtasks
            foreach ($task->subtasks as $subtask) {
                $newSubtask = $subtask->replicate();
                $newSubtask->task_id = $newTask->id; // Associate with the new task
                $newSubtask->created_at = now();
                $newSubtask->updated_at = now();
                $newSubtask->save();
            }

            // clone attachments
            foreach ($task->attachments as $attachment) {
                $newAttachment = $attachment->replicate();
                $newAttachment->task_id = $newTask->id; // Associate with the new task
                $newAttachment->created_at = now();
                $newAttachment->updated_at = now();
                $newAttachment->save();
            }

            // Update original task’s next_repeat_at
            $interval = $task->repeatType->interval;
            $task->next_repeat_at = $now->add($interval); // or Carbon::parse
            $task->save();
        }
    }
}
