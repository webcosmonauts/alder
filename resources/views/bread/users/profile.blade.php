@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>{{ __("alder::leaf_types.profile.singular") }}: {{ $user->full_name }}
        </h1>
    </div>


    <div class="card shadow mb-8">
        <div class="card-body">
            <section>
                <div class="container py-3">
                    <div class="row">
                        <div class="col-md-4">
                            @if(isset($img) && !empty($img))
                                <img src="{{ $img }}" class="w-100">
                            @endif
                        </div>
                        <div class="col-md-8 px-3">
                            <div class="card-block px-3">
                                <h4 class="card-title">{{ $user->full_name }}</h4>
                                <p class="card-text">
                                    @if(isset($user))
                                        {{ __('alder::leaf_types.profile.name') }}: {{ $user->name }}<br>
                                        {{ __('alder::leaf_types.profile.surname') }}: {{ $user->surname }}<br>
                                        {{ __('alder::leaf_types.profile.email') }}: {{ $user->email }}<br>
                                        {{ __('alder::leaf_types.profile.verified_at') }}: {{ $user->verified_at }}<br>
                                        {{ __('alder::leaf_types.profile.created_at') }}: {{ $user->created_at }}<br>
                                        {{ __('alder::leaf_types.profile.updated_at') }}: {{ $user->updated_at }}<br>
                                        {{ __('alder::leaf_types.profile.is_active') }}: {{ $user->is_active }}<br>

                                    @endif
                                </p>
                                {{--                                    <p class="card-text"></p>--}}
                                <a href="{{ route("alder.users.edit", $user->id)}}"
                                   class="btn btn-warning btn-icon-split ml-3">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus-circle"></i>
                                        </span>
                                    <span class="text">{{ __('alder::generic.edit')}}</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
        </section>
    </div>
    </div>




@endsection
