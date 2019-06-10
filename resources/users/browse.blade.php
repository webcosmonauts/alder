@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->

    <h1>Users</h1>

    <div class="d-sm-flex align-items-center mb-4">

        <h1 class="h3 mb-0 text-gray-800">{{ Str::title(str_replace('-', ' ', $leaf_type->name)) }}</h1>
        <!-- TODO: if can add new -->
        {{--        <a href="{{ route("alder.$leaf_type->name.create") }}" class="btn btn-success btn-icon-split ml-3">--}}
        {{--            <span class="icon text-white-50">--}}
        {{--                <i class="fas fa-plus-circle"></i>--}}
        {{--            </span>--}}
        {{--            <span class="text">{{ __('alder::generic.add_new') . ' ' . Str::title(Str::singular(str_replace('-', ' ', $leaf_type->name))) }}</span>--}}
        {{--        </a>--}}
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            {{--            @if($leaves->count() > 0)--}}
            {{--                <table class="table">--}}
            {{--                    <thead>--}}
            {{--                    <tr>--}}
            {{--                        @foreach($params->bread->browse->table_columns as $field)--}}
            {{--                            <td>--}}
            {{--                                {{ $field }}--}}
            {{--                            </td>--}}
            {{--                        @endforeach--}}
            {{--                        <td class="text-right">--}}
            {{--                            {{ __('alder::generic.actions') }}--}}
            {{--                        </td>--}}
            {{--                    </tr>--}}
            {{--                    </thead>--}}
            {{--                    <tbody>--}}
            {{--                    @foreach($leaves as $leaf)--}}
            {{--                        <tr>--}}
            {{--                            @foreach($params->bread->browse->table_columns as $field)--}}
            {{--                                <td>--}}
            {{--                                    {{ $leaf->$field }}--}}
            {{--                                </td>--}}
            {{--                            @endforeach--}}
            {{--                            <td class="text-right">--}}
            {{--                                <a href="{{ route("alder.$leaf_type->name.show", $leaf->slug) }}" class="btn btn-primary btn-icon-split ml-3">--}}
            {{--                                    <span class="icon text-white-50">--}}
            {{--                                        <i class="fas fa-book-open"></i>--}}
            {{--                                    </span>--}}
            {{--                                    <span class="text">{{ __('alder::generic.read') }}</span>--}}
            {{--                                </a>--}}

            {{--                                <!-- TODO if can edit -->--}}
            {{--                                <a href="{{ route("alder.$leaf_type->name.edit", $leaf->slug) }}" class="btn btn-warning btn-icon-split ml-3">--}}
            {{--                                    <span class="icon text-white-50">--}}
            {{--                                        <i class="fas fa-edit"></i>--}}
            {{--                                    </span>--}}
            {{--                                    <span class="text">{{ __('alder::generic.edit') }}</span>--}}
            {{--                                </a>--}}

            {{--                                <!-- TODO if can delete -->--}}
            {{--                                <a href="{{ route("alder.$leaf_type->name.destroy", $leaf->slug) }}" class="btn btn-danger btn-icon-split ml-3">--}}
            {{--                                    <span class="icon text-white-50">--}}
            {{--                                        <i class="fas fa-trash-alt"></i>--}}
            {{--                                    </span>--}}
            {{--                                    <span class="text">{{ __('alder::generic.delete') }}</span>--}}
            {{--                                </a>--}}
            {{--                            </td>--}}
            {{--                        </tr>--}}
            {{--                    @endforeach--}}
            {{--                    </tbody>--}}
            {{--                </table>--}}
            {{--            @else--}}
            {{--                <h3>{{ __('alder::datatable.sEmptyTable') }}</h3>--}}
            {{--            @endif--}}
        </div>
    </div>
@endsection
