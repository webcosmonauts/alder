@extends('alder::layouts.main')
@section('scripts-body')
    <link rel="stylesheet" href="{{asset('/vendor/file-manager/css/file-manager.css')}}">
    <script src="{{asset('/vendor/file-manager/file-manager.js')}}"></script>
@endsection
@section('content')
    <div style="height: 800px;">
        <div id="fm"></div>
    </div>
@endsection
