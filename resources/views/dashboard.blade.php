@extends('alder::layouts.main')

@section('scripts-body')
@endsection

@section('content')
    <div class="tab-content mb-5">


        <div class="tab-pane" id="form-tab-pane" role="tabpanel" aria-labelledby="form-tab"></div>

        <h1>Dashboard</h1>

        <div class="tab-content">
            <div>
                <h3>Posts</h3>
            </div>
            <div class="row">
                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <h5>Кол-во созданных постов {{$posts}}</h5>
                    </div>
                </div>

                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <h5>Lasted post updated in {{$lastpost->updated_at}}</h5>
                    </div>
                </div>
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
            </div>
        </div>

        <div class="tab-content">
            <div>
                <h3>Pages</h3>
            </div>
            <div class="row">
                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <h5>Кол-во созданных страниц {{$pages}}</h5>
                    </div>
                </div>

                <div class="card col-lg border-left-success" style="margin-left: 10px">
                    <div class="card-body">
                        <h5>Lasted page updated in {{$lastpage->updated_at}}</h5>
                    </div>
                </div>
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
                        <a href="">
                            <button class="btn btn-primary btn-success">Create new user</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection