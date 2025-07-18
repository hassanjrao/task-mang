@extends('layouts.backend')

@php
    $addEdit = isset($task) ? 'Edit' : 'Add';
    $addUpdate = isset($task) ? 'Update' : 'Add';
@endphp

@section('page-name', $addEdit . ' Task ' . ($task ? '#' . $task->title : ''))

@section('css_after')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

@endsection

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

                @if ($canEdit)
                    <form id="taskForm" action="{{ $task ? route('tasks.update', $task->id) : route('tasks.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($task)
                            @method('PUT')
                        @endif

                @endif

                <div class="row push justify-content-center">

                    <div class="col-lg-12 ">

                        <div class="row mb-4">


                            <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
                                <?php
                                $value = old('title', $task ? $task->title : null);
                                ?>
                                <label class="form-label" for="label">Title <span class="text-danger">*</span></label>
                                <input required type="text" value="{{ $value }}" class="form-control"
                                    {{ $canEdit ? '' : 'readonly' }} id="title" name="title"
                                    placeholder="Enter Title">
                                @error('title')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 mb-4">

                                <label class="form-label">Assignies <span class="text-danger"></span></label>
                                <select name="assigned_to[]" class="form-control" multiple {{ $canEdit ? '' : 'readonly' }}>
                                    @foreach ($assignableUsers as $user)
                                        <option value="{{ $user->id }}"
                                            {{ isset($task) && $task->assignedUsers->contains($user->id) ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('assigned_to')
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
                                <select required class="form-select" id="priority" name="priority"
                                    {{ $canEdit ? '' : 'disabled' }}>
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
                                <label class="form-label" for="label"> Status <span class="text-danger">*</span></label>
                                <select required class="form-select" id="status" name="status"
                                    {{ $canEdit ? '' : 'disabled' }}>
                                    <option value="">Select Status</option>
                                    @foreach ($taskStatuses as $status)
                                        <option value="{{ $status->id }}" {{ $value == $status->id ? 'selected' : '' }}>
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

                            <div class="col-lg-4 col-md-6 mb-4">
                                <?php
                                $value = old('due_datetime', $task ? $task->due_datetime : null);
                                ?>
                                <label class="form-label">Due Date & Time</label>
                                <input type="datetime-local" class="form-control" name="due_datetime"
                                    {{ $canEdit ? '' : 'disabled' }}
                                    value="{{ $value ? \Carbon\Carbon::parse($value)->format('Y-m-d\TH:i') : '' }}">
                                @error('due_datetime')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <label class="form-label">Repeat</label>
                                <select name="repeat_type_id" id="repeat_type_id" class="form-select">
                                    <option value="">No Repeat</option>
                                    @foreach ($repeatTypes as $repeatType)
                                        <option value="{{ $repeatType->id }}"
                                            {{ old('repeat_type_id', $task ? $task->repeat_type_id : null) == $repeatType->id ? 'selected' : '' }}>
                                            {{ ucfirst($repeatType->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('repeat_type_id')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-6 mb-4">
                                <label class="form-label">Remind me before</label>
                                <select name="reminder_offset" class="form-select" {{ $canEdit ? '' : 'disabled' }}>
                                    <option value="">No Reminder</option>
                                    <option value="0"
                                        {{ old('reminder_offset', $task && $task->reminder_offset ? $task->reminder_offset : '') == 0 ? 'selected' : '' }}>
                                        On
                                        Time</option>
                                    <option value="5"
                                        {{ old('reminder_offset', $task && $task->reminder_offset ? $task->reminder_offset : '') == 5 ? 'selected' : '' }}>
                                        5
                                        mins</option>
                                    <option value="10"
                                        {{ old('reminder_offset', $task && $task->reminder_offset ? $task->reminder_offset : '') == 10 ? 'selected' : '' }}>
                                        10 mins</option>
                                    <option value="60"
                                        {{ old('reminder_offset', $task && $task->reminder_offset ? $task->reminder_offset : '') == 60 ? 'selected' : '' }}>
                                        1
                                        hour</option>
                                    <option value="1440"
                                        {{ old('reminder_offset', $task && $task->reminder_offset ? $task->reminder_offset : '') == 1440 ? 'selected' : '' }}>
                                        1 day</option>
                                </select>
                                @error('reminder_offset')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>



                            <div class="col-lg-4 mb-4">
                                <label class="form-label">Reminder Type</label>
                                @php
                                    $selectedMethods = old(
                                        'reminder_methods',
                                        $task && $task->reminder_methods
                                            ? json_decode($task->reminder_methods, true)
                                            : [],
                                    );
                                @endphp
                                <div class="form-check">
                                    <input {{ $canEdit ? '' : 'disabled' }} class="form-check-input" type="checkbox"
                                        name="reminder_methods[]" value="web"
                                        {{ in_array('web', $selectedMethods) ? 'checked' : '' }}> Web
                                    Notification
                                </div>
                                <div class="form-check">
                                    <input {{ $canEdit ? '' : 'disabled' }} class="form-check-input" type="checkbox"
                                        name="reminder_methods[]" value="email"
                                        {{ in_array('email', $selectedMethods) ? 'checked' : '' }}>
                                    Email
                                </div>
                                <div class="form-check">
                                    <input {{ $canEdit ? '' : 'disabled' }} class="form-check-input" type="checkbox"
                                        name="reminder_methods[]" value="sound"
                                        {{ in_array('sound', $selectedMethods) ? 'checked' : '' }}> Play
                                    Sound
                                </div>
                                <div class="form-check">
                                    <input {{ $canEdit ? '' : 'disabled' }} class="form-check-input" type="checkbox"
                                        name="reminder_methods[]" value="tts"
                                        {{ in_array('tts', $selectedMethods) ? 'checked' : '' }}>
                                    Text-to-Speech
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-6 col-sm-6 mb-4">
                                <?php
                                $value = old('description', $task ? $task->description : null);

                                ?>
                                <label class="form-label" for="label"> Description <span
                                        class="text-danger">*</span></label>

                                <textarea {{ $canEdit ? '' : 'readonly' }} required class="form-control" id="editor" name="description"
                                    placeholder="Enter description">{{ $value }}</textarea>

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
                                        $subTasks = old('sub_tasks', $task && $task->subTasks ? $task->subTasks : ['']);
                                        $subTaskIds = old(
                                            'sub_task_ids',
                                            $task && $task->subTasks ? $task->subTasks->pluck('id')->toArray() : [],
                                        );
                                        $completed = old(
                                            'completed',
                                            $task && $task->subTasks
                                                ? $task->subTasks->where('is_completed', true)->pluck('id')->toArray()
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
                                                <input type="text" {{ $canEdit ? '' : 'disabled' }}
                                                    name="sub_tasks[{{ $index }}]" class="form-control"
                                                    value="{{ $title }}" placeholder="Enter sub task">
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    {{-- <input type="checkbox" class="form-check-input" name="completed[]"
                                                        id="completed_{{ $index }}" value="{{ $inputId }}"
                                                        {{ $isCompleted ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="completed_{{ $index }}">Completed</label> --}}
                                                </div>
                                            </div>

                                            @if ($canEdit)
                                                <div class="col-md-2">
                                                    <button type="button"
                                                        class="btn btn-danger remove-sub-task">×</button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if ($canEdit)
                                    <button type="button" class="btn btn-sm btn-secondary" id="addSubTaskBtn">+ Add Sub
                                        Task</button>
                                @endif

                                @error('sub_tasks.*')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>



                            <div class="col-lg-8">
                                @if ($task && $task->attachments && $task->attachments->count())
                                    <div class="block mt-4 p-4 border rounded">
                                        <h4 class="mb-3">Uploaded Attachments</h4>
                                        <div class="row">
                                            @foreach ($task->attachments as $attachment)
                                                <div class="col-md-3 mb-4">
                                                    <div class="border rounded p-2 position-relative">
                                                        @php
                                                            $extension = pathinfo(
                                                                $attachment->file_path,
                                                                PATHINFO_EXTENSION,
                                                            );
                                                            $isImage = in_array(strtolower($extension), [
                                                                'jpg',
                                                                'jpeg',
                                                                'png',
                                                                'webp',
                                                            ]);
                                                        @endphp

                                                        @if ($isImage)
                                                            <img src="{{ asset($attachment->file_url) }}"
                                                                alt="{{ $attachment->original_name }}"
                                                                class="img-fluid mb-2"
                                                                style="max-height: 150px; object-fit: cover;">
                                                        @else
                                                            <div class="text-center py-4 bg-light">
                                                                <i class="fa fa-file fa-3x text-muted"></i>
                                                                <p class="mt-2">{{ $attachment->original_name }}</p>
                                                            </div>
                                                        @endif

                                                        <div class="text-end">
                                                            <a href="{{ $attachment->file_url }}" download
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="fa fa-download"></i> Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($canEdit)
                                <div class="col-lg-4">
                                    <label class="form-label">Attachments</label>
                                    <input type="file" name="file" id="fileUpload" multiple />
                                    <div id="uploaded-files">
                                        @if ($task && $task->attachments)
                                            @foreach ($task->attachments as $attachment)
                                                <input type="hidden" name="uploaded_attachments[]"
                                                    value="{{ $attachment->id }}">
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if ($canEdit)
                                <div class="col-lg-12 text-end">

                                    <button type="submit" form="taskForm" id="submitBtn"
                                        class="btn btn-success text-white">{{ $addUpdate }}</button>
                                </div>
                            @endif

                        </div>


                    </div>

                </div>

                @if ($canEdit)
                    </form>
                @endif


            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection

@section('js_after')

    <!-- JS -->
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        const pond = FilePond.create(document.querySelector('#fileUpload'), {
            files: [
                @foreach (isset($task) && $task->attachments ? $task->attachments : [] as $attachment)
                    {
                        source: '{{ $attachment->id }}',
                        options: {
                            type: 'local',
                            file: {
                                name: '{{ $attachment->original_name ?? 'file' }}',
                                size: '{{ $attachment->file_size }}', // Optional: fake size, not validated
                            },
                            metadata: {
                                serverId: '{{ $attachment->file_url }}',
                            }
                        }
                    },
                @endforeach
            ],
            allowMultiple: true,
            allowImagePreview: true,
            server: {
                process: {
                    url: '{{ route('attachments.upload') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    onload: (response) => {
                        // response = path like "uploads/tasks/abc.pdf"
                        let hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'uploaded_attachments[]';
                        hidden.value = response; // attachment ID
                        document.getElementById('uploaded-files').appendChild(hidden);
                        return response;
                    },
                    onerror: (err) => {
                        console.error('Upload error', err);
                    }
                },

                revert: (serverId, load) => {
                    fetch(`{{ route('attachments.revert') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            path: serverId
                        }),
                    }).then(() => {
                        // Remove the hidden input for this file if it exists
                        const inputs = document.querySelectorAll(
                            'input[name="uploaded_attachments[]"]');
                        inputs.forEach(input => {
                            if (input.value === serverId) {
                                input.remove();
                            }
                        });

                        load();
                    });
                },
                remove: (source, load) => {
                    fetch(`{{ route('attachments.remove') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: source
                        }),
                    }).then(() => {
                        // Remove the hidden input for this file if it exists
                        const inputs = document.querySelectorAll(
                            'input[name="uploaded_attachments[]"]');
                        inputs.forEach(input => {
                            if (input.value === source) {
                                input.remove();
                            }
                        });

                        load();
                    });
                },
            },
        });
    </script>

    <script>
        let subTaskIndex = {{ count($subTasks) }};

        document.getElementById('addSubTaskBtn').addEventListener('click', function() {
            const wrapper = document.getElementById('subTasksWrapper');
            const row = document.createElement('div');
            row.classList.add('row', 'mb-2', 'sub-task-input', 'align-items-center');

            row.innerHTML = `
            <input type="hidden" name="sub_task_ids[${subTaskIndex}]" value="">
            <div class="col-md-8">
                <input type="text" name="sub_tasks[${subTaskIndex}]" class="form-control" placeholder="Enter sub task">
            </div>
            <div class="col-md-2">
                
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-sub-task">×</button>
            </div>
        `;
            wrapper.appendChild(row);
            subTaskIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-sub-task')) {
                e.target.closest('.sub-task-input').remove();
            }
        });
    </script>
@endsection
