@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{__('alder::roles.title')}}</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills nav-pills-primary" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#link1" role="tablist" aria-expanded="true">
                        {{__('alder::roles.title')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#link2" role="tablist" aria-expanded="false">
                        {{__('alder::roles.default')}}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content ">
                <div class="tab-pane active" id="link1" aria-expanded="true">
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            @if(!empty($roles))
                                <ul class="list-group">
                                    @foreach($roles as $role)
                                        <li class="list-group-item">
                                            <b class="float-left">{{ucfirst($role['name'])}}</b>
                                            <form action="{{route("alder.roles.delete")}}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$role['id']}}">
                                                <button type="submit" class="btn btn-danger float-right"
                                                        title="{{__("alder::roles.remove_role")}}">
                                                    <em class="fa fa-trash"></em>
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <div class="col-12 col-lg-3">
                            <h2>{{__("alder::roles.add_new_role")}}</h2>
                            <form action="{{route('alder.roles.add_new')}}" method="post">
                                @csrf
                                <input type="text" class="form-control mb-3" name="name"
                                       placeholder="{{__("alder::roles.role_name")}}" required>
                                <button type="submit"
                                        class="btn btn-success">{{__("alder::roles.add_new_role")}}</button>
                            </form>

                        </div>

                    </div>
                </div>
                <div class="tab-pane" id="link2" aria-expanded="false">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{route('alder.roles.init_default_roles')}}" method="post">
                                @csrf
                                <button class="btn btn-success" type="submit">{{__("alder::roles.init_roles")}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
