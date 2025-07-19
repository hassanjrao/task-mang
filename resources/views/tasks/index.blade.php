@extends('layouts.backend')

@section('page-name', 'Tasks')
@section('css_before')
    <!-- Page JS Plugins CSS -->

@endsection


@section('content')
    <!-- Page Content -->
    <div id="vue-app">
        <task-listing />
    </div>
    <!-- END Page Content -->
@endsection

@section('js_after')


@endsection
