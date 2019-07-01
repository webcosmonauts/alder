@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{__('alder::permissions.title')}}</h1>
        @include('alder::components.locale-switcher')
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
                        {{__('alder::permissions.capabilities_editor')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#link3" role="tablist" aria-expanded="false">
                        {{__('alder::permissions.default')}}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            @if(auth()->user()->can('delete others pages'))

            @endif
            <div class="tab-content ">
                <div class="tab-pane active" id="link1" aria-expanded="true">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            @if(!empty($roles))
                                <div id="accordion" role="tablist">
                                    @foreach($roles as $single_role)
                                        <div class="card card-collapse">
                                            <div class="card-header" role="tab" id="heading-{{$single_role->name}}">
                                                <h5 class="mb-0">
                                                    <div class="float-left">
                                                        {{ucfirst($single_role->name)}}
                                                    </div>
                                                    <div class="float-left">
                                                        <a href="{{url('/alder/capabilities?role='.$single_role->id)}}">
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                    </div>
                                                    <a data-toggle="collapse" href="#collapse-{{$single_role->name}}"
                                                       aria-expanded="false"
                                                       aria-controls="collapse-{{$single_role->name}}">

                                                        <i class="material-icons">keyboard_arrow_down</i>
                                                    </a>
                                                </h5>
                                            </div>

                                            <div id="collapse-{{$single_role->name}}" class="collapse" role="tabpanel"
                                                 aria-labelledby="heading-{{$single_role->name}}"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <ul>
                                                        @if(!empty($single_role->permissions))
                                                            @foreach($single_role->permissions as $single_permission)
                                                                <li>
                                                                    {{$single_permission->name}}
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                            @endif
                            <h2>{{__("alder::roles.add_new_role")}}</h2>
                            <form action="{{route('alder.roles.add_new')}}" method="post">
                                @csrf
                                <input type="text" class="form-control mb-3" name="name"
                                       placeholder="{{__("alder::roles.role_name")}}" required>
                                <button type="submit"
                                        class="btn btn-success">{{__("alder::roles.add_new_role")}}</button>
                            </form>
                        </div>
                        <div class="col-12 col-lg-8">
                            @if($role_editor_enabler)
                                <h3>{{__('alder::generic.edit')}} <b><u>{{ucfirst($selected_role->name)}}</u></b></h3>
                                <form action="{{route('alder.capabilities.update_roles_capability')}}" method="post">
                                    @csrf
                                    @if(!empty($permissions))
                                        @foreach($permissions as $single_permission)
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input
                                                            {{in_array($single_permission->name, $selected_role->permissions->pluck('name')->toArray()) ? "checked" : ""}}
                                                            class="form-check-input" type="checkbox" name="permissions[]"
                                                            value="{{$single_permission->name}}">
                                                    {{$single_permission->name}}
                                                    <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                                </label>
                                            </div>

                                        @endforeach
                                    @endif
                                    <input type="hidden" name="selected_role" value="{{$role_editor_enabler}}">
                                    <button class="btn btn-success">
                                  <span class="btn-label">
                                    <i class="material-icons">save</i>
                                  </span>
                                        {{__('alder::generic.update')}}
                                        <div class="ripple-container"></div>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="tab-pane " id="link2" aria-expanded="false">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-lg-4">
                                <form action="{{route('alder.capabilities.add_new_capability')}}" method="post">
                                    <h2>{{__('alder::permissions.add_new_cap')}}</h2>
                                    @csrf
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                              <span class="input-group-text">
                               <i class="material-icons">plus_one</i>
                              </span>
                                        </div>
                                        <input type="text" name="new_capability" required class="form-control"
                                               placeholder="Capability name">
                                    </div>
                                    <br>
                                    <button class="btn btn-success">
                          <span class="btn-label">
                            <i class="material-icons">check</i>
                          </span>
                                        {{__('alder::permissions.add_new_cap')}}
                                        <div class="ripple-container"></div>
                                    </button>
                                </form>
                            </div>
                            <div class="col-12 col-lg-8">
                                {{__('alder::permissions.content_cap')}}
                                <br>
                                <strong>{{__('alder::permissions.p_s_cap')}}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                @if(!empty($permissions))
                                    <h3>{{__('alder::permissions.existing_capabilities')}}</h3>
                                    <div class="table-responsive">


                                        <table class="table table-striped " id="browse-table">
                                            <thead>
                                            <tr>
                                                <th>{{__('alder::generic.name')}}</th>
                                                <th>{{__('alder::generic.created_at')}}</th>
                                                <th>{{__('alder::generic.updated_at')}}</th>
                                                <th class="text-right">{{__('alder::generic.actions')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($permissions as $single_existing_permission)
                                                <tr>
                                                    <td class="text-center">{{$single_existing_permission->name}}</td>
                                                    <td class="text-left">{{$single_existing_permission->created_at}}</td>
                                                    <td class="text-left">{{$single_existing_permission->updated_at}}</td>
                                                    <td class="text-right">
                                                        <form action="{{route('alder.capabilities.delete_capability')}}"
                                                              method="post">
                                                            @csrf
                                                            <input name="id" value="{{$single_existing_permission->id}}"
                                                                   type="hidden"/>
                                                            <button type="submit"
                                                                    class="btn btn-link btn-danger btn-just-icon remove">
                                                                <i class="material-icons">close</i>
                                                                <div class="ripple-container"></div>
                                                            </button>

                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane " id="link3" aria-expanded="false">
                    <div class="row">
                        <div class="col-12 ">
                            <form action="{{route('alder.capabilities.init_default_capabilities')}}"
                                  method="post">
                                @csrf
                                <button class="btn btn-success"
                                        type="submit">{{__("alder::permissions.init_capabilities")}}</button>
                            </form>
                            <form action="{{route('alder.capabilities.init_default_capabilities_for_roles')}}"
                                  method="post">
                                @csrf
                                <button class="btn btn-success"
                                        type="submit">{{__("alder::permissions.init_capabilities_for_roles")}}</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
