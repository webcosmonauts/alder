@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Roots</h1>
        @include('alder::components.locale-switcher')
    </div>

    @foreach($root_types as $root_type)
        <div class="card shadow mb-4">
            <a href="#collapseCard{{ Str::camel($root_type->slug) }}" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCard{{ Str::camel($root_type->name) }}">
                <h6 class="m-0 font-weight-bold text-primary">{{ __("alder::root_types.$root_type->slug") }}</h6>
            </a>
            <div class="collapse show" id="collapseCard{{ Str::camel($root_type->slug) }}" style="">
                <div class="card-body">
                    @foreach($root_type->roots as $root)
                        @if($root->slug == 'ico')
                            <div>ICO - urmomgae</div>
                        @else
                            @include('alder::components.root_input', $root)
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endsection
