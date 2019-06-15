@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>{{ __("alder::leaf_types.profile.singular") }}: {{$user->name}}
        </h1>
        </div>


    <div class="card shadow mb-8">
        <div class="card-body">
            <section>
                <div class="container py-3">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{$img}}" class="w-100">
                            </div>
                            <div class="col-md-8 px-3">
                                <div class="card-block px-3">
                                    <h4 class="card-title">{{$user->name}} {{$user->surname}}</h4>
                                    <p class="card-text">
                                    @if(isset($user))
                                        @foreach($user->getAttributes() as $key => $value)
                                            @switch($key)
                                                @case('name')
                                                    {{$key}} : {{$value}}<br>
                                                    @break
                                                @case('surname')
                                                    {{$key}} : {{$value}}<br>
                                                    @break
                                                @case('email')
                                                    {{$key}} : {{$value}}<br>
                                                    @break
                                                @case('email_verified_at')
                                                    {{$key}} : {{$value}}<br>
                                                    @break
                                                @case('created_at')
                                                    {{$key}} : {{$value}}<br>
                                                    @break
                                                @case('updated_at')
                                                    {{$key}} : {{$value}}<br>
                                                    @break
                                                @case('is_active')
                                                    {{$value ? 'Activation : Yes' : 'Activation : No'}}<br>
                                                    @break
                                            @endswitch

                                        @endforeach
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
        </section>
        </div>
    </div>




@endsection
