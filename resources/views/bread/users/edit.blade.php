@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>{{ __("alder::leaf_types.users.singular") }}</h1>
    </div>



    <form action="{{ $user ? route("alder.users.update",  $user->id) : route("alder.users.store") }}" method="POST"
          enctype="multipart/form-data">
        @csrf

        {{$user ? method_field('PUT') : method_field('POST')}}

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @foreach(['name', 'surname', 'email', 'password'] as $field)
                            <div class="mb-2">
                                <label for="{{ $field }}">{{__('alder::leaf_types.profile.' . $field) }}</label>
                                <div class="input-group mb-4">
                                    <input type="text" name="{{ $field}}" id="{{ $field }}" class="form-control"
                                           placeholder="{{ $field}}"
                                           aria-label="{{ $field }}" aria-describedby="{{ $field }}"
                                           value="{{ $user ? (($field == 'password') ? '' : $user->$field) : '' }}">
                                </div>
                            </div>
                        @endforeach

                        <div class="mb-2">
                            <div class="custom-control custom-checkbox pl-0">
                                <input {{$user ? ($user->is_active != 1 ? : 'checked') : ''}} type="checkbox"
                                       name="is_active" class="custom-control-input" id="is_active">
                                <label class="custom-control-label pl-4"
                                       for="is_active"> {{__('alder::leaf_types.profile.is_active') }}</label>
                            </div>
                        </div>

                        <div class="mb-2">

                            <label for="userfile">{{ __("alder::leaf_types.profile.avatar") }}</label>
                            <input class="form-control-file" name="userfile" type="file"/>
                            <br>
                            @if($roles)
                                <h3>{{__('alder::roles.title')}}</h3>
                                @foreach($roles as $single_role)
                                    <div class="form-check">
                                        <label class="form-check-label" for="role-{{$single_role->id}}">
                                            <input
                                                {{in_array($single_role->name,$user->roles->pluck('name')->toArray()) ? "checked" : ""}}
                                                id="role-{{$single_role->id}}" class="form-check-input" name="roles[]"
                                                type="checkbox" value="{{$single_role->name}}">
                                            {{ucfirst($single_role->name)}}
                                            <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                            <br>
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
