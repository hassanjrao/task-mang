@extends('layouts.backend')


@section('page-name', 'Profile')

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">

        <div class="container-fluid">

            <div class="row">
                <div class="card custom-card">
                    <div class="card-header ">
                        <div class="card-title d-flex justify-content-between w-100">
                            <span>Profile</span>
                        </div>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('profile.update', $user->id) }}" method="POST" class="row g-3 mt-0"
                            enctype="multipart/form-data">

                            @method('PUT')
                            @csrf


                            <div class="col-md-6">
                                <?php
                                $value = old('name', $user ? $user->name : null);
                                ?>

                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $value }}">
                                @error('name')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <?php
                                $value = old('email', $user ? $user->email : null);
                                ?>

                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" value="{{ $value }}">
                                @error('email')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="col-md-6">

                                <label class="form-label">Old Password</label>
                                <input type="password" class="form-control" name="old_password" >
                                @error('old_password')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="col-md-6">

                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="password" >
                                @error('password')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>




                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>

        </div>

    </div>

@endsection
@push('scripts')
@endpush
