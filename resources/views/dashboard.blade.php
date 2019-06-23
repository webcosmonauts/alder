@extends('alder::layouts.main')

@section('scripts-body')
@endsection

@section('content')
    <div class="tab-content mb-5">


        <div class="tab-pane" id="form-tab-pane" role="tabpanel" aria-labelledby="form-tab"></div>


        <div class="d-sm-flex align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __("alder::leaf_types.dashboard.singular") }}</h1>
            <!-- TODO: if can add new -->
            <a href="{{ route("alder.users.create") }}" class="btn btn-success btn-icon-split ml-3">
            @include('alder::components.locale-switcher')
        </div>


        <div class="tab-content">
            <div>
                <h3>Posts</h3>
            </div>
            <div class="row">
                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <h5>Total number of posts: {{$posts}}</h5>
                    </div>
                </div>


                {{--                @dd($lastpost)--}}
                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <h5>Lasted post updated in {{$posts > 0 ? $lastpost->updated_at : 0}}</h5>
                    </div>
                </div>
                @if ($posts > 0)
                    <div class="card col-lg border-left-success" style="margin-left: 10px">

                        <div class="card-body">
                            <a href="posts/{{$lastpost->slug}}">
                                <button class="btn btn-primary btn-success">Lasted updated post</button>
                            </a>
                            <a href="posts/create">
                                <button class="btn btn-primary btn-success">Create new post</button>
                            </a>
                        </div>

                    </div>
                @endif
            </div>
        </div>

        <div class="tab-content">
            <div>
                <h3>Pages</h3>
            </div>
            <div class="row">
                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <h5>Total number of pages: {{$pages}}</h5>
                    </div>
                </div>

                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <h5>Lasted page updated in {{$pages > 0 ? $lastpage->updated_at : 0}}</h5>
                    </div>
                </div>
                @if ($pages > 0)
                    <div class="card col-lg border-left-success" style="margin-left: 10px">
                        <div class="card-body">
                            <a href="pages/{{$lastpage->slug}}">
                                <button class="btn btn-primary btn-success">Lasted updated page</button>
                            </a>
                            <a href="pages/create">
                                <button class="btn btn-primary btn-success">Create new page</button>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="tab-content">
            <div>
                <h3>Users</h3>
            </div>
            <div class="row">
                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <h5>Total number of users: {{$users_all}}</h5>
                    </div>
                </div>

                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <h5>Total number of active users {{$users}}</h5>
                    </div>
                </div>
                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <a href="alder/users/create">
                            <button class="btn btn-primary btn-success">Create new user</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection