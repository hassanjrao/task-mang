<?php

namespace App\Http\Controllers;

use App\Exports\TaskExport;
use App\Models\Task;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TaskExportController extends Controller
{

    public function exportSingle(Task $task)
    {
        // replace spaces with hypens
        $title = str_replace(' ', '-', $task->title);
        return Excel::download(new TaskExport($task), 'task-' . $title . '.xlsx');
    }
}
