@extends('layouts.backend')

@section('page-name', 'Groups')
@section('css_before')
    <!-- Page JS Plugins CSS -->

@endsection


@section('content')
    <!-- Page Content -->
    <div class="content">

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Groups
                </h3>

                <a href="{{ route('groups.create') }}" class="btn btn-primary">Add</a>

            </div>

            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>

                            </tr>


                        </thead>

                        <tbody>
                            @foreach ($groups as $ind => $group)
                                <tr>

                                    <td>{{ $ind + 1 }}</td>
                                    <td>{{ $group->name }}</td>
                                    <td>{{ $group->description }}</td>
                                    <td>{{ $group->createdBy->name }}</td>
                                    <td>{{ $group->created_at }}</td>
                                    <td>{{ $group->updated_at }}</td>

                                    <td>
                                        @if ($group->created_by == auth()->id())
                                            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-sm btn-primary"
                                                data-toggle="tooltip" title="Edit">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <form id="form-{{ $group->id }}"
                                                action="{{ route('groups.destroy', $group->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="button" onclick="confirmDelete({{ $group->id }})"
                                                    class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                            </form>
                                        @else
                                            {{-- show join button --}}
                                            {{-- if joined then show leave button --}}
                                            @if ($group->groupMembers->contains(auth()->id()))
                                                <form id="form-{{ $group->id }}"
                                                    action="{{ route('groups.leave', $group->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        data-toggle="tooltip" title="Leave">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form id="form-{{ $group->id }}"
                                                    action="{{ route('groups.join', $group->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        data-toggle="tooltip" title="Join">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </form>
                                            @endif
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
