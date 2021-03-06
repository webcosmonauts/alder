@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>{{ __("alder::leaf_types.users.singular") }}</h1>
        @include('alder::components.locale-switcher')
    </div>



    <form action="{{ $user ? route("alder.users.update",  $user->id) : route("alder.users.store") }}" method="POST"
          enctype="multipart/form-data">
        @csrf

        {{$user ? method_field('PUT') : method_field('POST')}}

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                @if(session()->has('error_image'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('error_image') }}
                                    </div>
                                @endif

                                @if ($user)
                                    <div class="card-header card-header-icon card-header-warning">
                                        @if ($user->avatar)
                                            <div class="card-icon" style="width: 150px; height : 150px">
                                                <img src="{{ asset('storage/'.$user->avatar) }}" class="w-40" style="display: block; width: 100%">
                                            </div>
                                        @else
                                            <div class="card-icon" style="width: 60px; height: 60px">
                                                <i class="material-icons" style="position: center"> account_circle </i>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="btn btn-round btn-warning btn-file">
                                        <span class="fileinput-exists">{{ __("alder::leaf_types.profile.avatar") }}</span>
                                        <input type="hidden"><input type="file" name="userfile">
                                        <div class="ripple-container"></div>
                                    </span>
                            </div>
                            <div class="col-8">
                                <div style="width: 100%">
                                    <h4  class="card-title" style="margin-bottom: 25px">{{ __("alder::generic.edit") }}: {{$user ? $user->name : "" }}
                                    </h4>
                                </div>
                                @endif
                            </div>
                            <div class="mb-12">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group bmd-form-group">
                                                <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.name') }}:</label>
                                                <input name="name" type="text" class="form-control" value="{{$user ? $user->name : "" }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group bmd-form-group">
                                                <div class="form-group bmd-form-group">
                                                    <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.surname') }}:</label>
                                                    <input name="surname" type="text" class="form-control" value="{{$user ? $user->surname : ''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group bmd-form-group">
                                                <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.email') }}:</label>
                                                <input name="email" type="email" class="form-control" value="{{$user ? $user->email : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    {{--                                <div class="row">--}}
                                    {{--                                    <div class="col-md-4">--}}
                                    {{--                                        <div class="form-group bmd-form-group">--}}
                                    {{--                                            <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.verified_at') }}:</label>--}}
                                    {{--                                            <input  type="text" class="form-control" value="{{ $user->verified_at ? $user->verified_at : __('alder::generic.no') }}">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="col-md-4">--}}
                                    {{--                                        <div class="form-group bmd-form-group">--}}
                                    {{--                                            <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.created_at') }}:</label>--}}
                                    {{--                                            <input  type="text" class="form-control" value="{{ $user->created_at }}">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="col-md-4">--}}
                                    {{--                                        <div class="form-group bmd-form-group">--}}
                                    {{--                                            <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.updated_at') }}:</label>--}}
                                    {{--                                            <input  type="text" class="form-control" value="{{ $user->updated_at }}">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    {{--                                </div>--}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group bmd-form-group">
                                                <label class="bmd-label-floating">{{ __('alder::leaf_types.profile.password') }}:</label>
                                                <input name="password"  type="password" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input name="is_active" class="form-check-input" {{$user ? ($user->is_active != 1 ? : 'checked') : ''}} type="checkbox" > {{__('alder::leaf_types.profile.is_active') }}
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <button style="margin-top: 20px" type="submit" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                              <i class="fas fa-save"></i>
                            </span>
                                        <span class="text">{{ __('alder::generic.save') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            @if($user)
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-body">

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

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </form>
@endsection
