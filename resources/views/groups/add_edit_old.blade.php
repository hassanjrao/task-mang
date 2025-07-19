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
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                                    <label for="member-search">Add Members</label>
                                    <input type="text" class="form-control" id="member-search"
                                        placeholder="Search user by name/email/phone">
                                    <div id="search-results" class="border mt-2 p-2"
                                        style="max-height: 200px; overflow-y: auto; display: none;"></div>
                                    <div id="selected-users" class="mt-3 d-flex flex-wrap gap-2"></div>

                                    <input type="hidden" name="selected_user_ids" id="selected-user-ids">

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
        let selectedUsers = [];

        document.getElementById('member-search').addEventListener('input', function() {
            const query = this.value;
            if (query.length < 2) return;

            fetch(`/users/search?q=${query}`)
                .then(res => res.json())
                .then(users => {
                    const resultBox = document.getElementById('search-results');
                    resultBox.innerHTML = '';
                    users.forEach(user => {
                        if (!selectedUsers.some(u => u.id === user.id)) {
                            const div = document.createElement('div');
                            div.classList.add('p-2', 'border-bottom', 'cursor-pointer');
                            div.style.cursor = 'pointer';
                            div.textContent = `${user.name} (${user.email})`;
                            div.addEventListener('click', () => {
                                selectedUsers.push(user);
                                renderSelectedUsers();
                                resultBox.innerHTML = '';
                                document.getElementById('member-search').value = '';
                            });
                            resultBox.appendChild(div);
                        }
                    });
                    resultBox.style.display = 'block';
                });
        });

        function renderSelectedUsers() {
            const selectedBox = document.getElementById('selected-users');
            selectedBox.innerHTML = '';
            selectedUsers.forEach((user, index) => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-info text-white p-2 rounded d-flex align-items-center';
                badge.innerHTML = `
                ${user.name} (${user.email})
                <button type="button" class="btn-close btn-close-white ms-2" aria-label="Remove"></button>
            `;
                badge.querySelector('button').addEventListener('click', () => {
                    selectedUsers.splice(index, 1);
                    renderSelectedUsers();
                });
                selectedBox.appendChild(badge);
            });

            // Set hidden input
            document.getElementById('selected-user-ids').value = selectedUsers.map(u => u.id).join(',');
        }
    </script>

@endsection
