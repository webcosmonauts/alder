@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ Str::title(str_replace('-', ' ', $leaf->leaf_type->name)) }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if(isset($leaf))
                @foreach($leaf->getAttributes() as $key => $value)
                    <div class="row">
                        <div class="col-xl-2">{{ $key }}</div>
                        <div class="col-xl-4">{{ $value }}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
