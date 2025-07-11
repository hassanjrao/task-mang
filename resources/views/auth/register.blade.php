@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="hero-static d-flex align-items-center">
        <div class="content">
            <div class="row justify-content-center push">
                <div class="col-lg-6">
                    <!-- Sign In Block -->
                    <div class="block block-rounded mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Sign Up</h3>
                            <div class="block-options">


                                {{-- @if (Route::has('password.request'))

                                    <a class="btn-block-option fs-sm" href="{{ route('password.request') }}">Forgot
                                        Password?</a>
                                @endif --}}
                            </div>
                        </div>
                        <div class="block-content">
                            <div class=" px-lg-4 px-xxl-5 py-lg-5">
                                {{-- <h1 class="h2 mb-4"><img src="{{asset("logo/main_logo.png")}}" height="200px" class="img-fluid" alt=""></h1> --}}
                                <p class="fw-medium text-muted">
                                    Welcome, please register.
                                </p>

                                <!-- Sign In Form -->
                                <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                                <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-validation-signin" action="{{ route('register') }}" method="POST">
                                    @csrf
                                    <div class="py-3">

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label for="name">{{ __('Name') }}</label>
                                                    <input type="name"
                                                        class="form-control form-control-alt form-control-lg @error('name') is-invalid @enderror"
                                                        value="{{ old('name') }}" required autocomplete="name" autofocus
                                                        id="login-username" name="name" placeholder="name">
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label for="email">{{ __('Email') }}</label>
                                                    <input type="email"
                                                        class="form-control form-control-alt form-control-lg @error('email') is-invalid @enderror"
                                                        value="{{ old('email') }}" required autocomplete="email" autofocus
                                                        id="login-username" name="email" placeholder="Email">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label for="password">{{ __('Password') }}</label>
                                                    <input type="password"
                                                        class="form-control form-control-alt form-control-lg @error('password') is-invalid @enderror"
                                                        id="login-password" name="password" required
                                                        autocomplete="current-password" placeholder="Password">

                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror

                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                                    <input type="password"
                                                        class="form-control form-control-alt form-control-lg @error('password_confirmation') is-invalid @enderror"
                                                        id="login-password-confirm" name="password_confirmation" required
                                                        autocomplete="current-password" placeholder="Confirm Password">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            {{-- role --}}
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label for="role">Role</label>
                                                    <select name="role" id="role" required
                                                        class="form-select form-control form-control-alt form-control-lg @error('role') is-invalid @enderror">
                                                        <option value="">Select Role</option>
                                                        <option value="student">Student</option>
                                                        <option value="worker">Worker</option>
                                                    </select>
                                                    @error('role')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 justify-content-center">
                                        <div class="col-md-6 col-xl-5">
                                            <button type="submit" class="btn w-100 btn-alt-primary">
                                                <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Sign Up
                                            </button>
                                        </div>

                                    </div>
                                </form>

                                {{-- dont have account --}}
                                <div class="text-center">
                                    <p class="fs-sm text-muted mb-0">
                                        Already have an account? <a href="{{ route('login') }}">Sign In</a>
                                    </p>
                                </div>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                    </div>
                    <!-- END Sign In Block -->
                </div>
            </div>
            <div class="fs-sm text-muted text-center">
                <strong>{{ config('app.name') }}, All Rights Reserved </strong> &copy; <span
                    data-toggle="year-copy"></span>
            </div>
            <br>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
