@extends('layouts.backend')

@section('page-name', 'Profie')
@section('content')

    <!-- Page Content -->
    <div class="content content-boxed">

        <div class="block block-rounded">
            <div class="block-header block-header-default d-flex">
                <h3 class="block-title"> {{ $user->name }}</h3>


            </div>
            <div class="block-content">
                <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="py-3">

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <?php
                                    $value = old('name', $user ? $user->name : null);

                                    ?>
                                    <label for="name">{{ __('Name') }}</label>
                                    <input type="name"
                                        class="form-control form-control @error('name') is-invalid @enderror"
                                        value="{{ $value }}" required autocomplete="name" autofocus
                                        id="login-username" name="name" placeholder="Name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <?php
                                    $value = old('phone', $user ? $user->phone : null);
                                    ?>
                                    <label for="phone">{{ __('Phone') }}</label>
                                    <input type="tel"
                                        class="form-control form-control @error('phone') is-invalid @enderror"
                                        value="{{ $value }}" required autocomplete="phone" autofocus
                                        id="login-userphone" name="phone" placeholder="Phone">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <?php
                                    $value = old('username', $user ? $user->username : null);
                                    ?>
                                    <label for="username">{{ __('Username') }}</label>
                                    <input type="text"
                                        class="form-control form-control @error('username') is-invalid @enderror"
                                        value="{{ $value }}" required autocomplete="username" autofocus
                                        id="login-userusername" name="username" placeholder="username">
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <?php
                                    $value = old('email', $user ? $user->email : null);
                                    ?>
                                    <label for="email">{{ __('Email') }}</label>
                                    <input type="email"
                                        class="form-control form-control @error('email') is-invalid @enderror"
                                        value="{{ $value }}" required autocomplete="email" autofocus
                                        id="login-username" name="email" placeholder="Email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <label for="password">{{ __('Old Password') }}</label>
                                    <input type="password"
                                        class="form-control form-control @error('old_password') is-invalid @enderror"
                                        id="login-password" name="old_password" autocomplete="old-password"
                                        placeholder="Password">

                                    @error('old_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <label for="password-confirm">{{ __('New Password') }}</label>
                                    <input type="password"
                                        class="form-control form-control @error('password') is-invalid @enderror"
                                        id="login-password-confirm" name="password"
                                        autocomplete="current-password" placeholder="New Password">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-12 text-end">
                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Update
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>
    <!-- END Page Content -->
@endsection
