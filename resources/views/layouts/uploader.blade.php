@extends('alder::master')

@section('scripts-body')
    <link rel="stylesheet" href="{{ asset('file-manager/css/file-manager.css') }}">
    <script src="{{ asset('file-manager/js/file-manager.js') }}"></script>
@endsection

@section('main')
    <div style="height: 600px;">
        <div id="fm"></div>
    </div>
@endsection