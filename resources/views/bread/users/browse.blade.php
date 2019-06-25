@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __("alder::leaf_types.users.plural") }}</h1>
        <!-- TODO: if can add new -->
        <a href="{{ route("alder.users.create") }}" class="btn btn-success btn-icon-split ml-3">
            <span class="icon text-white-50">
                <i class="fas fa-plus-circle"></i>
            </span>
            <span class="text">{{  __("alder::leaf_types.users.add_new") }}</span>
        </a>
        @include('alder::components.locale-switcher')
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($users->count() > 0)
                <table class="table" id="browse-table">
                    <thead>
                    <tr>
                        @foreach(['title','surname','email','created_at', 'active'] as $field)
                            <th>
                                {{ __("alder::table_columns.$field") }}
                            </th>
                        @endforeach
                        <td class="text-right">
                            {{ __('alder::generic.actions') }}
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $leaf)
                        <tr>
                            @foreach(['name', 'surname', 'email', 'created_at', 'is_active'] as $field)
                                <td>
                                    {{ $leaf->$field }}
                                </td>
                            @endforeach
                            <td class="text-right">
                                @include('alder::components.actions', ['route' => 'users', 'leaf' => $leaf, 'preview' => true])
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h3>{{ __('alder::datatable.sZeroRecords') }}</h3>
            @endif
        </div>
    </div>

    <!-- CONFIRM DELETE -->
    <div class="modal fade in" id="confirm-delete" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLabel">{{__('alder::generic.are_you_sure')}}?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="" method="POST">
                        @method("DELETE")
                        @csrf
                        <button class="btn btn-primary"> {{__('alder::lcm.yes')}} </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
