@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>{{ __("alder::leaf_types.users.singular") }}</h1>
    </div>



    <form action="{{ $user ? route("alder.users.update",  $user->id) : route("alder.users.store") }}" method="POST">
        @csrf

        {{$user ? method_field('PUT') : method_field('POST')}}

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @foreach(['name', 'surname', 'email', 'password', 'is_active'] as $field)
                            <label for="{{ $field }}">{{ $field }}</label>
                            <div class="input-group mb-4">
                                <input type="text" name="{{ $field}}" id="{{ $field }}" class="form-control"
                                       placeholder="{{ $field}}"
                                       aria-label="{{ $field }}" aria-describedby="{{ $field }}"
                                       value="{{ $user ? (($field == 'password') ? '' : $user->$field) : '' }}">

                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                              <i class="fas fa-save"></i>
                            </span>
                            <span class="text">{{ __('alder::generic.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
