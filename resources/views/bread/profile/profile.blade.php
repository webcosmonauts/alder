@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>{{ __("alder::leaf_types.profile.singular") }}: {{$user->name}}
        </h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    {{--                        @foreach(['name', 'surname', 'email', 'password'] as $field)--}}
                    {{--                            <div class="mb-2">--}}
                    {{--                                <label for="{{ $field }}">{{__('alder::leaf_types.profile.' . $field) }}</label>--}}
                    {{--                                <div class="input-group mb-4">--}}
                    {{--                                    <input type="text" name="{{ $field}}" id="{{ $field }}" class="form-control"--}}
                    {{--                                           placeholder="{{ $field}}"--}}
                    {{--                                           aria-label="{{ $field }}" aria-describedby="{{ $field }}"--}}
                    {{--                                           value="{{ $user ? (($field == 'password') ? '' : $user->$field) : '' }}">--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        @endforeach--}}

                    {{--                        <div class="mb-2">--}}
                    {{--                            <div class="custom-control custom-checkbox pl-0">--}}
                    {{--                                <input {{$user ? ($user->is_active != 1 ? : 'checked') : ''}} type="checkbox"--}}
                    {{--                                       name="is_active" class="custom-control-input" id="is_active">--}}
                    {{--                                <label class="custom-control-label pl-4"--}}
                    {{--                                       for="is_active"> {{__('alder::leaf_types.profile.is_active') }}</label>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}


                    <div style="margin-bottom: 10px" class="card-header card-header-icon card-header-warning">
                        <div class="card-icon">
                            @if ($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" class="w-40">
                            @else
                                {{--                                <img src="{{ asset('storage/'.$user->avatar) }}" class="w-50">--}}
                                <i class="material-icons"> account_circle </i>
                            @endif
                        </div>
                        <h4 class="card-title">Edit Profile -
                            <small class="category">Complete your profile</small>
                        </h4>
                    </div>
                    <div style="margin-top: 20px">
                        {{--                        <label for="userfile"></label>--}}
                        {{--                        <input class="form-control-file col-md-4" name="userfile" type="file" value="{{ __("alder::leaf_types.profile.avatar") }}">--}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.name') }}:</label>
                                    <input disabled name="name" type="text" class="form-control" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <div class="form-group bmd-form-group">
                                        <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.surname') }}:</label>
                                        <input disabled name="surname" type="text" class="form-control" value="{{ $user->surname }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.email') }}:</label>
                                    <input disabled name="email" type="email" class="form-control" value="{{ $user->email }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.verified_at') }}:</label>
                                    <input disabled type="text" class="form-control" value="{{ $user->email_verified_at ? $user->email_verified_at : __('alder::generic.no') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.created_at') }}:</label>
                                    <input disabled type="text" class="form-control" value="{{ $user->created_at }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.updated_at') }}:</label>
                                    <input disabled type="text" class="form-control" value="{{ $user->updated_at }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input disabled name="is_active" class="form-check-input" {{$user ? ($user->is_active != 1 ? : 'checked') : ''}} type="checkbox" > {{__('alder::leaf_types.profile.is_active') }}
                                        <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <a href="{{route("alder.users.edit",  $user->id)}}" style="margin-top: 20px" type="submit" class="btn btn-warning btn-icon-split">
                            <span class="icon text-white-50">
                              <i class="fas fa-save"></i>
                            </span>
                            <span class="text">{{ __('alder::generic.edit') }}</span>
                        </a>
                    </div>
                </div>
            </div>


        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body">
                    @if($roles)
                        <h3>{{__('alder::roles.title')}}</h3>
                        @foreach($roles as $single_role)
                            <div class="form-check">
                                <label class="form-check-label" for="role-{{$single_role->id}}">
                                    <input disabled
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

                </div>
            </div>
        </div>

    </div>


@endsection
