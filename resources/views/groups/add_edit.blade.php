@extends('layouts.backend')

@php
    $addEdit = isset($group) ? 'Edit' : 'Add';
    $addUpdate = isset($group) ? 'Update' : 'Add';
@endphp

@section('page-name', $addEdit . ' group ' . ($group ? '#' . $group->name : ''))

@section('css_after')


@endsection

@section('content')
    <div class="content content-boxed">
        <div class="block block-rounded">
            <div class="block-header block-header-default d-flex justify-content-between align-items-center">
                <h3 class="block-title">
                    {{ $addEdit }} group {{ $group ? '#' . $group->title : '' }}
                </h3>
                <div>
                    <a href="{{ route('groups.index') }}" class="btn btn-primary me-2">Groups</a>
                </div>
            </div>

            <div class="block-content">
                <form id="groupForm" action="{{ $group ? route('groups.update', $group->id) : route('groups.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($group)
                        @method('PUT')
                    @endif

                    <div class="row push justify-content-center">
                        <div class="col-lg-12">
                            <div class="row mb-4">

                                {{-- Group Name --}}
                                <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input required type="text" value="{{ old('name', $group->name ?? '') }}"
                                        class="form-control" name="name" placeholder="Enter Name">
                                    @error('name')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Group Description --}}
                                <div class="col-lg-8 col-md-6 col-sm-6 mb-4">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea required class="form-control" name="description" placeholder="Enter description">{{ old('description', $group->description ?? '') }}</textarea>
                                    @error('description')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Add Members --}}
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label" for="members">Add Members (search by
                                        name/email/phone)</label>
                                    <select name="members[]" id="members" class="js-select2 form-select" multiple
                                        style="width: 100%">
                                        @if (isset($selectedUsers))
                                            @foreach ($selectedUsers as $user)
                                                <option value="{{ $user->id }}" selected>{{ $user->name }}
                                                    ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>


                                {{-- Existing Members --}}
                                @if ($group && $group->groupMembers->count())
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                                        <label class="form-label">Current Members</label>
                                        <ul class="list-group">
                                            @foreach ($group->groupMembers as $user)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $user->name }} ({{ $user->email }})
                                                    <span
                                                        class="badge bg-{{ $user->pivot->status === 'accepted' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($user->pivot->status) }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="col-lg-12 text-end">
                                    <button type="submit" form="groupForm" id="submitBtn"
                                        class="btn btn-success text-white">{{ $addUpdate }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js_after')


    <script>
        $(document).ready(function() {
            $('#members').select2({
                placeholder: 'Search users...',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route('users.search') }}', // You will define this route
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(user) {
                                return {
                                    id: user.id,
                                    text: user.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>

@endsection
