<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // disable foreign key checks to avoid issues with existing data
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // truncate the task_statuses table to start fresh
        TaskStatus::truncate();
        // re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        TaskStatus::firstOrCreate(['name' => 'Pending'], ['name' => 'Pending', 'description' => 'Task is pending']);
        TaskStatus::firstOrCreate(['name' => 'In Progress'], ['name' => 'In Progress', 'description' => 'Task is currently being worked on']);
        TaskStatus::firstOrCreate(['name' => 'Completed'], ['name' => 'Completed', 'description' => 'Task has been completed']);
        TaskStatus::firstOrCreate(['name' => 'Overdue'], ['name' => 'Overdue', 'description' => 'Task is overdue']);
    }
}
