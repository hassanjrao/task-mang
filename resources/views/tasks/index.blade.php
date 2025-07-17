@extends('layouts.backend')

@section('page-name', 'Tasks')
@section('css_before')
    <!-- Page JS Plugins CSS -->

@endsection


@section('content')
    <!-- Page Content -->
    <div class="content">

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Tasks
                </h3>

                <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add</a>

            </div>

            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created By</th>
                                <th>Assignies</th>
                                <th>Priority</th>
                                <th style="width: 250px !important;">Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>

                            </tr>


                        </thead>

                        <tbody>
                            @foreach ($tasks as $ind => $task)
                                <tr>

                                    <td>{{ $ind + 1 }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->createdBy->name }}</td>
                                    <td>
                                        @if ($task->assignedUsers->count() > 0)
                                            {{-- show assigned users as badges --}}
                                            @foreach ($task->assignedUsers as $user)
                                                <span class="badge bg-primary">{{ $user->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-secondary">No Assignee</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- select box to update priority --}}
                                        <form action="{{ route('tasks.update-priority', $task->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select class="form-select" name="priority" onchange="this.form.submit()">
                                                @foreach ($priorities as $priority)
                                                    <option value="{{ $priority->id }}"
                                                        {{ $task->priority_id == $priority->id ? 'selected' : '' }}>
                                                        {{ ucfirst($priority->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                    <td style="width: 250px !important;">
                                        <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select class="form-select" name="status" onchange="this.form.submit()">
                                                @foreach ($taskStatuses as $status)
                                                    <option value="{{ $status->id }}"
                                                        {{ $task->task_status_id == $status->id ? 'selected' : '' }}>
                                                        {{ ucfirst($status->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                    <td>{{ $task->created_at }}</td>
                                    <td>{{ $task->updated_at }}</td>

                                    <td>
                                        @if ($task->created_by == auth()->id())
                                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary"
                                                data-toggle="tooltip" title="Edit">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <form id="form-{{ $task->id }}"
                                                action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="button" onclick="confirmDelete({{ $task->id }})"
                                                    class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                            </form>
                                        @else
                                            {{-- view button --}}
                                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-info"
                                                data-toggle="tooltip" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>








    </div>
    <!-- END Page Content -->
@endsection

@section('js_after')


@endsection
