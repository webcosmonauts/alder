@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $leaf_type->title }}</h1>
        <!-- TODO: if can add new -->
        <a href="{{ route("alder.$leaf_type->slug.create") }}" class="btn btn-success btn-icon-split ml-3">
            <span class="icon text-white-50">
                <i class="fas fa-plus-circle"></i>
            </span>
            <span class="text">{{ __('alder::generic.add_new') . ' ' . Str::singular($leaf_type->title) }}</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($leaves->count() > 0)
                <table class="table table-bordered" id="browse-table">
                    <thead>
                    <tr>
                        @foreach($params->bread->browse->table_columns as $field)
                            <th>
                                {{ $field }}
                            </th>
                        @endforeach
                        <th class="text-right">
                            {{ __('alder::generic.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($leaves as $leaf)
                        <tr>
                            @foreach($params->bread->browse->table_columns as $field)
                                <td>
                                    {{ $leaf->$field }}
                                </td>
                            @endforeach
                            <td class="text-right">
                                @include('alder::components.actions', ['route' => $leaf_type->slug, 'param' => $leaf->slug])
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h3>{{ __('alder::datatable.sEmptyTable') }}</h3>
            @endif
        </div>
    </div>
@endsection
