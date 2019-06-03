@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ Str::title(str_replace('-', ' ', $leaf_type->name)) }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form>
                @foreach(['title', 'slug', 'content', 'user_id'] as $field)
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon-{{$field}}">{{ $field }}</span>
                        </div>
                        <input type="text" class="form-control" placeholder="{{$field}}"
                               aria-label="123{{$field}}" aria-describedby="basic-addon-{{$field}}">
                    </div>
                @endforeach
            </form>
        </div>
    </div>
@endsection
