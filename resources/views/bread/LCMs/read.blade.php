@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('alder::lcm.singular') }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if(isset($LCM))
                @dump($LCM)
            @endif
        </div>
    </div>
@endsection
