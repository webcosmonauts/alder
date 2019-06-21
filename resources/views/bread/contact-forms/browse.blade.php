@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __("alder::leaf_types.contact-forms.plural") }}</h1>
        <!-- TODO: if can add new -->
        <a href="{{ route("alder.contact-forms.create") }}" class="btn btn-success btn-icon-split ml-3">
            <span class="icon text-white-50">
                <i class="fas fa-plus-circle"></i>
            </span>
            <span class="text">{{ __('alder::generic.add_new') . ' ' .  lcfirst(__("alder::leaf_types.contact-forms.singular"))}}</span>
        </a>
        @include('alder::components.locale-switcher')
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($leaves->count() > 0)
                <table class="table" id="browse-table">
                    <thead>
                    <tr>
                        @foreach(['Title','Slug','Is accessable', 'Status','Created at', 'Update at'] as $field)
                            <th>
                                {{ $field }}
                            </th>
                        @endforeach
                        <td class="text-right">
                            {{ __('alder::generic.actions') }}
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($leaves as $leaf)
                        <tr>
                            @foreach(['title','slug','is_accessible', 'status_id','created_at', 'updated_at'] as $field)
                                <td>
                                    {{ $leaf->$field }}
                                </td>
                            @endforeach
                            <td class="text-right">
                                @include('alder::components.actions', ['route' => 'contact-forms', 'leaf' => $leaf, 'preview' => true])
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
@endsection
