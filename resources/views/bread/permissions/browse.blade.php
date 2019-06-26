@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{__('alder::permissions.title')}}</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills nav-pills-warning" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#link1" role="tablist" aria-expanded="true">
                        {{__('alder::permissions.title')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#link2" role="tablist" aria-expanded="false">
                        {{__('alder::permissions.default')}}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content ">
                <div class="tab-pane active" id="link1" aria-expanded="true">
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            @if(!empty($permissions))
                                <ul class="list-group">
                                @foreach($permissions as $single_permission)
                                    <li class="list-group-item">
                                        {{$single_permission->name}}
                                    </li>
                                @endforeach
                                </ul>
                            @endif
                        </div>
                        <div class="col-12 col-lg-3">

                        </div>

                    </div>
                </div>
                <div class="tab-pane " id="link2" aria-expanded="false">
                    <div class="row">
                        <div class="col-12 ">
                            <form action="{{route('alder.capabilities.init_default_capabilities')}}" method="post">
                                @csrf
                                <button class="btn btn-success" type="submit">{{__("alder::permissions.init_capabilities")}}</button>
                            </form>
                            <form action="{{route('alder.capabilities.init_default_capabilities_for_roles')}}" method="post">
                                @csrf
                                <button class="btn btn-success" type="submit">{{__("alder::permissions.init_capabilities_for_roles")}}</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
