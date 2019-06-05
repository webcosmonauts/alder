@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Roots</h1>
    </div>

    @foreach($root_types as $root_type)
        <div class="card shadow mb-4">
            <a href="#collapseCard{{ Str::camel($root_type->name) }}" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCard{{ Str::camel($root_type->name) }}">
                <h6 class="m-0 font-weight-bold text-primary">{{ $root_type->name }}</h6>
            </a>
            <div class="collapse show" id="collapseCard{{ Str::camel($root_type->name) }}" style="">
                <div class="card-body">
                    @foreach($root_type->roots as $root)
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon-{{ Str::camel($root->name) }}">{{ $root->name }}</span>
                            </div>
                            <input type="text" class="form-control" placeholder="{{ $root->name }}"
                                   aria-label="{{ $root->name }}" aria-describedby="basic-addon-{{ Str::camel($root->name) }}"
                                   value="{{ $root->value }}">
                        </div>
                        {{ dump($root) }}
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endsection
