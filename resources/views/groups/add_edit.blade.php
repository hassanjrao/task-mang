@extends('layouts.backend')

@php
    $addEdit = isset($group) ? 'Edit' : 'Add';
    $addUpdate = isset($group) ? 'Update' : 'Add';
@endphp

@section('page-name', $addEdit . ' group ' . ($group ? '#' . $group->name : ''))

@section('css_after')
@endsection

@section('content')

    <!-- Page Content -->
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

                        <div class="col-lg-12 ">

                            <div class="row mb-4">


                                <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
                                    <?php
                                    $value = old('name', $group ? $group->name : null);
                                    ?>
                                    <label class="form-label" for="label">name <span
                                            class="text-danger">*</span></label>
                                    <input required type="text" value="{{ $value }}" class="form-control"
                                        id="name" name="name" placeholder="Enter name">
                                    @error('name')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="col-lg-8 col-md-6 col-sm-6 mb-4">
                                    <?php
                                    $value = old('description', $group ? $group->description : null);

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
    <!-- END Page Content -->
@endsection

@section('js_after')

@endsection
