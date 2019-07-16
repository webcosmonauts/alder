@extends('alder::layouts.main')

@section('scripts-body')
@endsection

@section('content')
    <div class="tab-content mb-12">
        <div class="tab-pane" id="form-tab-pane" role="tabpanel" aria-labelledby="form-tab"></div>
        <div class="d-sm-flex align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __("alder::dashboard.singular") }}</h1>
            @include('alder::components.locale-switcher')
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-12">
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">insert_drive_file</i>
                        </div>
                        <p class="card-category">{{ __('alder::leaf_types.pages.plural') }}</p>
                        <p class="card-title">{{ __('alder::dashboard.total') }}: {{ $pages }}</p>
                        {{-- <p class="card-title">{{ __('alder::dashboard.last_updated') }}
                             : {{ $lastpage->updated_at }}</p>--}}
                    </div>
                    <div class="card-footer">
                        <div class="">
                            @if($pages > 0)
                                <button style="margin: 0; padding: 5px;"
                                        class="d-flex align-items-center btn btn-social btn-link btn-twitter">
                                    <i style="margin-right: 8px" class="material-icons">pageview</i>
                                    <a href="/{{$lastpage->slug}}">{{ __('alder::dashboard.last_updated') }} {{ lcfirst(__('alder::leaf_types.pages.singular')) }}</a>
                                </button>
                            @endif
                            <button style="margin: 0; padding: 5px;"
                                    class="d-flex align-items-center btn btn-social btn-link btn-twitter">
                                <i style="margin-right: 8px" class="material-icons">add</i>
                                <a href="{{ route('alder.pages.create') }}">{{ __('alder::dashboard.add_new_page') }}</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-12">
                <div class="card card-stats">
                    <div class="card-header card-header-rose card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">subtitles</i>
                        </div>
                        <p class="card-category">{{ __('alder::leaf_types.posts.plural') }}</p>
                        @if($posts > 0)
                            <p class="card-title">{{ __('alder::dashboard.total') }}: {{ $posts }}</p>
                            {{--  <p class="card-title">{{ __('alder::dashboard.last_updated') }}
                                  : {{ $lastpost->updated_at }}</p>--}}
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="">
                            @if($posts > 0)
                                <button style="margin: 0; padding: 5px;"
                                        class="d-flex align-items-center btn btn-social btn-link btn-twitter">
                                    <i style="margin-right: 8px" class="material-icons">pageview</i>
                                    <a href="/{{ lcfirst(__('alder::leaf_types.posts.plural')) }}/{{$lastpost->slug}}">{{ __('alder::dashboard.last_updated') }} {{ lcfirst(__('alder::leaf_types.posts.singular')) }}</a>
                                </button>
                            @endif
                            <button style="margin: 0; padding: 5px;"
                                    class="d-flex align-items-center btn btn-social btn-link btn-twitter">
                                <i style="margin-right: 8px" class="material-icons">add</i>
                                <a href="{{ route('alder.posts.create') }}">{{ __('alder::dashboard.add_new_post') }}</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-12">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">people </i>
                        </div>
                        <p class="card-category">{{ __('alder::leaf_types.users.plural') }}</p>
                        <p class="card-title">{{ __('alder::dashboard.users_total_active') }}: {{ $users }}</p>
                        <p class="card-title">{{ __('alder::dashboard.users_total') }}: {{ $users_all }}</p>
                    </div>
                    <div class="card-footer">
                        <div class="">
                            <button style="margin: 0; padding: 5px;"
                                    class="d-flex align-items-center btn btn-social btn-link btn-twitter">
                                <i style="margin-right: 8px" class="material-icons">add</i>
                                <a href="{{ route('alder.users.create') }}">{{ __('alder::dashboard.add_new_user') }}</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($posts > 0)
            @php
                $counter = 0 ;

                $images = [
                    '',
                    'https://img1.goodfon.ru/original/800x480/1/a3/breakfast-cats-bad-girl.jpg',
                    'https://img5.goodfon.ru/original/800x480/c/93/central-and-western-district-hong-kong-gorod.jpg',
                    'https://img5.goodfon.ru/original/800x480/9/e0/vestrahorn-auster-skaftafellssysla-iceland-islandiia-1.jpg'
                ];
            @endphp


            <h3> {{__('alder::generic.last_posts')}} </h3>
            <br>
            <div class="row">
                @foreach($last_posts as $post)
                    @php $counter++; @endphp

                    @if($counter < 4)
                        <div class="col-xl-4 col-lg-5 col-md-6">
                            <div class="card card-product">
                                <div class="card-header card-header-image">
                                    <img src="@php if($post->thumbnail) echo $post->thumbnail; else echo $images[$counter]; @endphp"
                                         alt="{{$post->title}}">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">{{$post->title}}</h4>
                                    <div class="card-description">
                                        @php

                                            $post_content = explode(" ", $post->content);
                                            $post_content_str = "";

                                            for($i = 0; $i < 25; $i++){
                                                $post_content_str .= $post_content[$i] . " ";
                                            }

                                            if(count($post_content) > 25) $post_content_str .= "...";

                                            echo $post_content_str;
                                        @endphp
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button style="margin: 0; padding: 5px;"
                                            class="d-flex align-items-center btn btn-social btn-link btn-twitter">
                                        <i style="margin-right: 8px" class="material-icons">create</i>
                                        <a href="alder/posts/{{$post->id}}/edit">{{ __('alder::generic.edit') }}</a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

    </div>


@endsection
