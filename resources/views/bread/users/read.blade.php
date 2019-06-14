@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __("alder::leaf_types.users.singular") }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if(isset($user))
                @foreach($user->getAttributes() as $key => $value)
                    @if ($key != 'password')
                        <div class="row">
                            <div class="col-xl-2">{{ $key }}</div>
                            <div class="col-xl-4">{{ $value }}</div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@endsection
