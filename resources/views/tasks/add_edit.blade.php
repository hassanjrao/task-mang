@extends('layouts.backend')

@php
    $addEdit = isset($task) ? 'Edit' : 'Add';
    $addUpdate = isset($task) ? 'Update' : 'Add';
@endphp
@section('page-name', $addEdit . ' Task '. ($task ? '#' . $task->title : ''))
@section('content')

    <!-- Page Content -->
    <div class="content content-boxed">

        <div class="block block-rounded">

            <div class="block-header block-header-default d-flex justify-content-between align-items-center">
                <h3 class="block-title">
                    {{ $addEdit }} Task {{ $task ? '#' . $task->title : '' }}
                </h3>
                <div>
                    <a href="{{ route('tasks.index') }}" class="btn btn-primary me-2">Tasks</a>

                </div>
            </div>


            <div class="block-content">

                <form id="taskForm" action="{{ $task ? route('tasks.update', $task->id) : route('tasks.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($task)
                        @method('PUT')
                    @endif


                    <div class="row push justify-content-center">

                        <div class="col-lg-12 ">

                            <div class="row mb-4">


                                <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
                                    <?php
                                    $value = old('title', $task ? $task->title : null);
                                    ?>
                                    <label class="form-label" for="label">Title <span
                                            class="text-danger">*</span></label>
                                    <input required type="text" value="{{ $value }}" class="form-control"
                                        id="title" name="title" placeholder="Enter Title">
                                    @error('title')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
                                    <?php
                                    $value = old('group_id', $task ? $task->group_id : null);
                                    ?>
                                    <label class="form-label" for="label"> Group <span
                                            class="text-danger"></span></label>
                                    <select class="form-select" id="group_id" name="group_id">
                                        <option value="">Select Group</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ $value == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('group_id')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
                                    <?php
                                    $value = old('priority', $task ? $task->priority_id : null);
                                    ?>
                                    <label class="form-label" for="label"> Priority <span
                                            class="text-danger">*</span></label>
                                    <select required class="form-select" id="priority" name="priority">
                                        <option value="">Select Priority</option>
                                        @foreach ($priorities as $priority)
                                            <option value="{{ $priority->id }}"
                                                {{ $value == $priority->id ? 'selected' : '' }}>
                                                {{ ucfirst($priority->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
                                    <?php
                                    $value = old('status', $task ? $task->task_status_id : null);
                                    ?>
                                    <label class="form-label" for="label"> Status <span
                                            class="text-danger">*</span></label>
                                    <select required class="form-select" id="status" name="status">
                                        <option value="">Select Status</option>
                                        @foreach ($taskStatuses as $status)
                                            <option value="{{ $status->id }}"
                                                {{ $value == $status->id ? 'selected' : '' }}>
                                                {{ ucfirst($status->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                                    <?php
                                    $value = old('description', $task ? $task->description : null);

                                    ?>
                                    <label class="form-label" for="label"> Description <span
                                            class="text-danger">*</span></label>

                                    <textarea required class="form-control" id="editor" name="description" placeholder="Enter description">{{ $value }}</textarea>

                                    @error('description')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                                    <label class="form-label">Sub Tasks</label>

                                    <div id="subTasksWrapper">
                                        @php
                                            $subTasks = old(
                                                'sub_tasks',
                                                $task && $task->subTasks ? $task->subTasks : [''],
                                            );
                                            $subTaskIds = old(
                                                'sub_task_ids',
                                                $task && $task->subTasks ? $task->subTasks->pluck('id')->toArray() : [],
                                            );
                                            $completed = old(
                                                'completed',
                                                $task && $task->subTasks
                                                    ? $task->subTasks
                                                        ->where('is_completed', true)
                                                        ->pluck('id')
                                                        ->toArray()
                                                    : [],
                                            );
                                        @endphp

                                        @foreach ($subTasks as $index => $subTask)
                                            @php
                                                $title = is_object($subTask) ? $subTask->title : $subTask;
                                                $id = is_object($subTask) ? $subTask->id : null;
                                                $inputId = $id ?? 'new_' . $index;
                                                $isCompleted = in_array($inputId, $completed);
                                            @endphp

                                            <div class="row mb-2 sub-task-input align-items-center">
                                                <input type="hidden" name="sub_task_ids[{{ $index }}]"
                                                    value="{{ $id }}">

                                                <div class="col-md-8">
                                                    <input type="text" name="sub_tasks[{{ $index }}]"
                                                        class="form-control" value="{{ $title }}"
                                                        placeholder="Enter sub task">
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="completed[]"
                                                            id="completed_{{ $index }}" value="{{ $inputId }}"
                                                            {{ $isCompleted ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="completed_{{ $index }}">Completed</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger remove-sub-task">×</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="button" class="btn btn-sm btn-secondary" id="addSubTaskBtn">+ Add Sub
                                        Task</button>

                                    @error('sub_tasks.*')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>




                                <div class="col-lg-12 text-end">

                                    <button type="submit" form="taskForm" id="submitBtn"
                                        class="btn btn-success text-white">{{ $addUpdate }}</button>
                                </div>


                            </div>


                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection

@section('js_after')
<script>
    let subTaskIndex = {{ count($subTasks) }};

    document.getElementById('addSubTaskBtn').addEventListener('click', function () {
        const wrapper = document.getElementById('subTasksWrapper');
        const row = document.createElement('div');
        row.classList.add('row', 'mb-2', 'sub-task-input', 'align-items-center');

        row.innerHTML = `
            <input type="hidden" name="sub_task_ids[${subTaskIndex}]" value="">
            <div class="col-md-8">
                <input type="text" name="sub_tasks[${subTaskIndex}]" class="form-control" placeholder="Enter sub task">
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input"
                        name="completed[]" id="completed_${subTaskIndex}"
                        value="new_${subTaskIndex}">
                    <label class="form-check-label" for="completed_${subTaskIndex}">Completed</label>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-sub-task">×</button>
            </div>
        `;
        wrapper.appendChild(row);
        subTaskIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-sub-task')) {
            e.target.closest('.sub-task-input').remove();
        }
    });
</script>
@endsection

