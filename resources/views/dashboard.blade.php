@extends('alder::layouts.main')

@section('scripts-body')
@endsection

@section('content')
    <div class="tab-content mb-5">
        <div class="tab-pane" id="form-tab-pane" role="tabpanel" aria-labelledby="form-tab"></div>
        <div class="d-sm-flex align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __("alder::dashboard.singular") }}</h1>
            @include('alder::components.locale-switcher')
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow mb-4 ">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">{{ __('alder::leaf_types.pages.plural') }}</h5>
                    </div>
                    <div class="card-body">
                        <h3>{{ __('alder::dashboard.total') }}: {{ $pages }}</h3>
                        @if($pages > 0)
                            <h4>{{ __('alder::dashboard.last_updated') }}: {{ $lastpage->updated_at }}</h4>
                            <a href="/{{$lastpage->slug}}" class="btn btn-primary btn-success mb-2 mr-2">
                                {{ __('alder::dashboard.last_updated') }} {{ lcfirst(__('alder::leaf_types.pages.singular')) }}
                            </a>
                        @endif
                        <a href="{{ route('alder.pages.create') }}" class="btn btn-primary btn-success mb-2 mr-2">
                            {{ __('alder::dashboard.add_new_page') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow mb-4 ">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">{{ __('alder::leaf_types.posts.plural') }}</h5>
                    </div>
                    <div class="card-body">
                        <h3>{{ __('alder::dashboard.total') }}: {{ $posts }}</h3>
                        @if($posts > 0)
                            <h4>{{ __('alder::dashboard.last_updated') }}: {{ $lastpost->updated_at }}</h4>
                            <a href="/{{ lcfirst(__('alder::leaf_types.posts.plural')) }}/{{$lastpost->slug}}" class="btn btn-primary btn-success mb-2 mr-2">
                                {{ __('alder::dashboard.last_updated') }} {{ lcfirst(__('alder::leaf_types.posts.singular')) }}
                            </a>
                        @endif
                        <a href="{{ route('alder.posts.create') }}" class="btn btn-primary btn-success mb-2 mr-2">
                            {{ __('alder::dashboard.add_new_post') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow mb-4 ">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">{{ __('alder::leaf_types.users.plural') }}</h5>
                    </div>
                    <div class="card-body">
                        <h3>{{ __('alder::dashboard.users_total_active') }}: {{ $users }}</h3>
                        <h4>{{ __('alder::dashboard.users_total') }}: {{ $users_all }}</h4>
                        <a href="{{ route('alder.users.create') }}" class="btn btn-primary btn-success mb-2 mr-2">
                            {{ __('alder::dashboard.add_new_user') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
