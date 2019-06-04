@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ Str::title(str_replace('-', ' ', $leaf_type->name)) }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route("alder.$leaf_type->name.store") }}" method="POST">
                @csrf

                @foreach(['title', 'slug', 'content', 'user_id'] as $field)
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon-{{$field}}">{{ $field }}</span>
                        </div>
                        <input type="text" class="form-control" placeholder="{{$field}}"
                               aria-label="{{$field}}" aria-describedby="basic-addon-{{$field}}">
                    </div>
                @endforeach

                @foreach($params->fields as $field_name => $field_modifiers)
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon-{{$field_name}}">{{ $field_name }}</span>
                        </div>
                        <input type="text" class="form-control" placeholder="{{$field_name}}"
                               aria-label="{{$field_name}}" aria-describedby="basic-addon-{{$field_name}}">
                    </div>
                @endforeach

                <button type="submit" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-save"></i>
                    </span>
                    <span class="text">{{ __('alder::generic.save') }}</span>
                </button>
            </form>
        </div>
    </div>
@endsection
