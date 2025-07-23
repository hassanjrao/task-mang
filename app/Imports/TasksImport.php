<?php

namespace App\Imports;

use App\Models\Priority;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TasksImport implements ToModel,WithHeadingRow
{
    public $createdBy;
    public function __construct(User $createdBy)
    {
        $this->createdBy = $createdBy;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $priority = isset($row['priority']) ? $row['priority'] : 'low';
        $priority=Priority::where('name', strtolower($priority))->first();

        $status= isset($row['status']) ? $row['status'] : 'pending';
        $taskStatus=TaskStatus::where('name',strtolower($status))->first();


        return new Task([
            'title' => $row['title'],
            'description' => $row['description'],
            'created_by' => $this->createdBy->id,
            'task_status_id' => $taskStatus ? $taskStatus->id : TaskStatus::where('name', 'pending')->first()->id,
            'priority_id' => $priority ? $priority->id : Priority::where('name', 'low')->first()->id,
            'due_datetime' => isset($row['due_date']) ? \Carbon\Carbon::parse($row['due_date']) : null,
        ]);
    }
}
