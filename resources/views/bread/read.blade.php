@extends('alder::master')

@section('content')
    <div class="row">
        <div class="col-xl-4"></div>
        <div class="col-xl-6">
            @if(isset($leaf))
                @foreach($leaf->getAttributes() as $key => $value)
                    <div class="row">
                        <div class="col-xl-2">{{ $key }}</div>
                        <div class="col-xl-4">{{ $value }}</div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="col-xl-4"></div>
    </div>
@endsection
