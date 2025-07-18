<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Priority;
use App\Models\RepeatType;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::with(['createdBy', 'assignedTo', 'group'])
            ->where(function ($query) {
                $query->where('created_by', auth()->id())
                    ->orWhereHas('assignedUsers', function ($q) {
                        $q->where('user_id', auth()->id());
                    });
            })
            ->latest()
            ->get();

        $priorities = Priority::all();
        $taskStatuses = TaskStatus::all();

        return view('tasks.index', compact('tasks', 'priorities', 'taskStatuses'));
    }


    public function dataForCreateEdit()
    {
        $assignableUsers = auth()->user()->groups->flatMap->groupMembers->unique('id');

        $users = User::where('id', '!=', auth()->id())
            ->latest()
            ->get();
        $priorities = Priority::all();
        $taskStatuses = TaskStatus::all();
        $repeatTypes = RepeatType::all();

        return [
            'assignableUsers' => $assignableUsers,
            'users' => $users,
            'priorities' => $priorities,
            'taskStatuses' => $taskStatuses,
            'repeatTypes' => $repeatTypes,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = null;

        $data = $this->dataForCreateEdit();
        $assignableUsers = $data['assignableUsers'];
        $users = $data['users'];
        $priorities = $data['priorities'];
        $taskStatuses = $data['taskStatuses'];
        $repeatTypes = $data['repeatTypes'];
        $canEdit = true; // Always allow creating new tasks

        return view('tasks.add_edit', compact('task', 'assignableUsers', 'users', 'priorities', 'taskStatuses', 'canEdit', 'repeatTypes'));
    }

    public function show($id)
    {

        $task = Task::with(['createdBy', 'assignedTo', 'group', 'priority', 'status'])
            ->where(function ($query) {
                $query->where('created_by', auth()->id())
                    ->orWhereHas('assignedUsers', function ($q) {
                        $q->where('user_id', auth()->id());
                    });
            })
            ->findOrFail($id);

        $data = $this->dataForCreateEdit();
        $assignableUsers = $data['assignableUsers'];
        $users = $data['users'];
        $priorities = $data['priorities'];
        $taskStatuses = $data['taskStatuses'];
        $repeatTypes = $data['repeatTypes'];

        $subTasks = $task->subTasks;

        $canEdit = $task->created_by === auth()->id();

        return view('tasks.add_edit', compact('task', 'assignableUsers', 'users', 'priorities', 'taskStatuses', 'subTasks', 'canEdit', 'repeatTypes'));
    }

    public function edit($id)
    {

        $task = Task::with(['createdBy', 'assignedTo', 'group', 'priority', 'status'])
            ->where(function ($query) {
                $query->where('created_by', auth()->id());
            })
            ->findOrFail($id);

        $data = $this->dataForCreateEdit();
        $assignableUsers = $data['assignableUsers'];
        $users = $data['users'];
        $priorities = $data['priorities'];
        $taskStatuses = $data['taskStatuses'];
        $repeatTypes = $data['repeatTypes'];

        $subTasks = $task->subTasks;

        $canEdit = $task->created_by === auth()->id();

        if (!$canEdit) {
            abort(403, 'You do not have permission to edit this task.');
        }

        return view('tasks.add_edit', compact('task', 'assignableUsers', 'users', 'priorities', 'taskStatuses', 'subTasks', 'canEdit', 'repeatTypes'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|integer',
            'status' => 'required|integer',
            'sub_tasks' => 'array',
            'sub_tasks.*' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx,xlsx|max:5120', // 5MB max
            'group_id' => 'nullable|exists:groups,id',
            'assigned_to' => 'nullable',
            'due_datetime' => 'nullable|date',
            'reminder_offset' => 'nullable|integer',
            'reminder_methods' => 'nullable|array',
            'repeat_type_id' => 'nullable|exists:repeat_types,id'
        ]);


        $assignableUsers = auth()->user()->groups->flatMap->groupMembers->unique('id');


        foreach ((array) $request->assigned_to as $userId) {
            if (!in_array($userId, $assignableUsers->pluck('id')->toArray())) {
                abort(403, 'User not in your group');
            }
        }

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority_id' => $validated['priority'],
            'task_status_id' => $validated['status'],
            'group_id' => $request->group_id,
            'created_by' => auth()->id(),
            'due_datetime' => $request->due_datetime,
            'reminder_offset' => $request->reminder_offset,
            'reminder_methods' => $request->reminder_methods ? json_encode($request->reminder_methods) : null,
        ]);

        $assignedTo = $request->input('assigned_to', []) ?: [auth()->id()];
        $task->assignedUsers()->sync($assignedTo);


        if ($request->repeat_type_id) {
            $repeatType = RepeatType::findOrFail($request->repeat_type_id);
            $this->updateRepeatType($task, $repeatType);
        } else {
            $task->repeat_type_id = null;
            $task->next_repeat_at = null;
            $task->save();
        }

        $subTasks = $request->input('sub_tasks', []);
        $completed = $request->input('completed', []); // Contains values like new_0, new_1

        foreach ($subTasks as $index => $title) {
            if (empty($title)) continue;

            $inputId = 'new_' . $index;
            $isCompleted = in_array($inputId, $completed);

            $task->subTasks()->create([
                'title' => $title,
                'is_completed' => $isCompleted,
            ]);
        }

        $this->uploadAttachments($task, $request->input('uploaded_attachments'));

        return redirect()->route('tasks.index')->withToastSuccess('Task created successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|integer',
            'status' => 'required|integer',
            'sub_tasks' => 'array',
            'sub_tasks.*' => 'nullable|string',
            'sub_task_ids' => 'array',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx,xlsx|max:5120', // 5MB max
            'reminder_offset' => 'nullable|integer',
            'reminder_methods' => 'nullable|array',
            'due_datetime' => 'nullable|date',
            'repeat_type_id' => 'nullable|exists:repeat_types,id',
        ]);

        $assignableUsers = auth()->user()->groups->flatMap->groupMembers->unique('id');

        foreach ((array) $request->assigned_to as $userId) {
            if (!in_array($userId, $assignableUsers->pluck('id')->toArray())) {
                abort(403, 'User not in your group');
            }
        }

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority_id' => $validated['priority'],
            'task_status_id' => $validated['status'],
            'group_id' => $request->group_id,
            'due_datetime' => $request->due_datetime,
            'reminder_offset' => $request->reminder_offset,
            'reminder_methods' => $request->reminder_methods ? json_encode($request->reminder_methods) : null,
        ]);

        if ($request->repeat_type_id) {
            $repeatType = RepeatType::findOrFail($request->repeat_type_id);
            $this->updateRepeatType($task, $repeatType);
        } else {
            $task->repeat_type_id = null;
            $task->next_repeat_at = null;
            $task->save();
        }

        $assignedTo = $request->input('assigned_to', []) ?: [auth()->id()];
        $task->assignedUsers()->sync($assignedTo);


        $submittedSubTasks = $request->input('sub_tasks', []);
        $submittedIds = $request->input('sub_task_ids', []);
        $completed = $request->input('completed', []); // Contains both IDs (int) and "new_0", "new_1"

        $keepSubtaskIds = [];

        foreach ($submittedSubTasks as $index => $title) {
            if (!$title) continue;

            $subtaskId = $submittedIds[$index] ?? null;

            // Check if this subtask is completed
            $isCompleted = false;

            if ($subtaskId && is_numeric($subtaskId)) {
                $isCompleted = in_array($subtaskId, $completed);
            } else {
                $isCompleted = in_array('new_' . $index, $completed);
            }

            if ($subtaskId && is_numeric($subtaskId)) {
                // Update existing subtask
                $subtask = $task->subTasks()->where('id', $subtaskId)->first();
                if ($subtask) {
                    $subtask->update([
                        'title' => $title,
                        'is_completed' => $isCompleted,
                    ]);
                    $keepSubtaskIds[] = $subtask->id;
                }
            } else {
                // Create new subtask
                $new = $task->subTasks()->create([
                    'title' => $title,
                    'is_completed' => $isCompleted,
                ]);
                $keepSubtaskIds[] = $new->id;
            }
        }

        // Delete removed subtasks
        $task->subTasks()->whereNotIn('id', $keepSubtaskIds)->delete();


        // Handle attachments
        if ($request->has('uploaded_attachments')) {
            $this->uploadAttachments($task, $request->input('uploaded_attachments'));
        }



        return redirect()->route('tasks.index')->withToastSuccess('Task updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::where(function ($query) {
            $query->where('created_by', auth()->id());
        })->findOrFail($id);

        $task->delete();

        return redirect()->route('tasks.index')->withToastSuccess('Task deleted successfully.');
    }

    public function updatePriority(Request $request, Task $task)
    {
        $request->validate([
            'priority' => 'required|exists:priorities,id',
        ]);

        $task->update(['priority_id' => $request->priority]);

        return redirect()->back()->withToastSuccess('Task priority updated successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|exists:task_statuses,id',
        ]);

        $task->update(['task_status_id' => $request->status]);

        return redirect()->back()->withToastSuccess('Task status updated successfully.');
    }


    public function uploadAttachments(Task $task, $attachments)
    {
        foreach ((array)$attachments as $attachmentId) {
            if (is_numeric($attachmentId)) {
                // Already stored attachment, just associate
                $attachment = TaskAttachment::find($attachmentId);
                if ($attachment && !$attachment->task_id) {
                    $attachment->task_id = $task->id;
                    $attachment->save();
                }
            }
        }
    }

    public function updateRepeatType(Task $task, RepeatType $repeatType)
    {
        $task->repeat_type_id = $repeatType->id;
        // interval is like: 1 day, 7 days, 1 month, 1 year
        $task->next_repeat_at = now()->add($repeatType->interval);
        $task->save();
    }
}
