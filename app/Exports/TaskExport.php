<?php
namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TaskExport implements FromArray
{
    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function array(): array
    {
        return [
            ['Title', 'Description', 'Created By', 'Assignies', 'Priority','Status','Due Date','Created At'], // Header row
            [
                $this->task->title,
                $this->task->description,
                $this->task->createdBy ? $this->task->createdBy->name : 'N/A',
                implode(', ', $this->task->assignedUsers->pluck('name')->toArray()),
                $this->task->priority->name,
                $this->task->status->name,
                $this->task->due_datetime,
                $this->task->created_at,
            ],
        ];
    }
}
