@extends('alder::master')

@section('sidebar')
    @include('alder::components.sidebar')
@endsection

@section('topbar')
    @include('alder::components.topbar')
@endsection

@section('main')
    <div class="container-fluid">
        @yield('content')
    </div>
@endsection