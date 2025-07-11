@extends('layouts.app')

@section('content')

    <!-- Page Content -->
    <div class="hero-static d-flex align-items-center">
        <div class="content">
            <div class="row justify-content-center push">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <!-- Sign In Block -->
                    <div class="block block-rounded mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Sign In</h3>
                            <div class="block-options">


                                {{-- @if (Route::has('password.request'))

                                    <a class="btn-block-option fs-sm" href="{{ route('password.request') }}">Forgot
                                        Password?</a>
                                @endif --}}
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                {{-- <h1 class="h2 mb-4"><img src="{{asset("logo/main_logo.png")}}" height="200px" class="img-fluid" alt=""></h1> --}}
                                <p class="fw-medium text-muted">
                                    Welcome, please login.
                                </p>

                                <!-- Sign In Form -->
                                <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                                <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-validation-signin" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="py-3">
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
                                        <div class="mb-4">
                                            <label for="password">{{ __('Password') }}</label>
                                            <input type="password"
                                                class="form-control form-control-alt form-control-lg @error('password') is-invalid @enderror"
                                                id="login-password" name="password" required autocomplete="current-password"
                                                placeholder="Password">


                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="login-remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="login-remember">Remember Me</label>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 col-xl-5">
                                            <button type="submit" class="btn w-100 btn-alt-primary">
                                                <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Sign In
                                            </button>
                                        </div>

                                    </div>
                                </form>

                                {{-- dont have account? --}}
                                <div class="text-center">
                                    <p class="fs-sm text-muted mb-0">
                                        Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
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
                <strong>{{ config("app.name") }}, All Rights Reserved </strong> &copy; <span
                    data-toggle="year-copy"></span>
            </div>
            <br>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
