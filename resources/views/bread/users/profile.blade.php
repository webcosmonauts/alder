@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    </div>
    <h1>Profile</h1>
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
