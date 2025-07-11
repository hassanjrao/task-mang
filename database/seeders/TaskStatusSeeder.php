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
        TaskStatus::firstOrCreate(['name' => 'Pending'], ['name' => 'Pending', 'description' => 'Task is pending']);
        TaskStatus::firstOrCreate(['name' => 'In Progress'], ['name' => 'In Progress', 'description' => 'Task is currently being worked on']);
        TaskStatus::firstOrCreate(['name' => 'Completed'], ['name' => 'Completed', 'description' => 'Task has been completed']);
        TaskStatus::firstOrCreate(['name' => 'On Hold'], ['name' => 'On Hold', 'description' => 'Task is on hold and not currently being worked on']);
        TaskStatus::firstOrCreate(['name' => 'Cancelled'], ['name' => 'Cancelled', 'description' => 'Task has been cancelled and will not be completed']);
        TaskStatus::firstOrCreate(['name' => 'Archived'], ['name' => 'Archived', 'description' => 'Task has been archived and is no longer active']);
        TaskStatus::firstOrCreate(['name' => 'Reviewed'], ['name' => 'Reviewed', 'description' => 'Task has been reviewed and feedback has been provided']);
        TaskStatus::firstOrCreate(['name' => 'Approved'], ['name' => 'Approved', 'description' => 'Task has been approved and is ready for the next steps']);
        TaskStatus::firstOrCreate(['name' => 'Rejected'], ['name' => 'Rejected', 'description' => 'Task has been rejected and will not proceed']);
        TaskStatus::firstOrCreate(['name' => 'Deferred'], ['name' => 'Deferred', 'description' => 'Task has been deferred to a later time']);
        TaskStatus::firstOrCreate(['name' => 'Escalated'], ['name' => 'Escalated', 'description' => 'Task has been escalated to a higher authority for resolution']);
        TaskStatus::firstOrCreate(['name' => 'Resolved'], ['name' => 'Resolved', 'description' => 'Task has been resolved and no further action is required']);
    }
}
