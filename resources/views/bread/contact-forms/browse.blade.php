@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->

    <h1>{{ __("alder::leaf_types.contact-forms.plural") }}</h1>

    <div class="d-sm-flex align-items-center mb-4">

        <h1 class="h3 mb-0 text-gray-800"></h1>
        <!-- TODO: if can add new -->
        <a href="{{ route("alder.contact-forms.create") }}" class="btn btn-success btn-icon-split ml-3">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-circle"></i>
                            </span>
            <span class="text">{{ __('alder::generic.add_new')}}</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($forms->count() > 0)
{{--                                 @dd($forms->User)--}}
                <table class="table">
                    <thead>
                    <tr>
                        @foreach(['Title','Slug','Is accessable', 'Status','Created at', 'Update at'] as $field)
                            <td>
                                {{ $field }}
                            </td>
                        @endforeach
                        <td class="text-right">
                            {{ __('alder::generic.actions') }}
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($forms as $leaf)
                        <tr>

                            @foreach(['title','slug','is_accessable', 'status_id','created_at', 'updated_at'] as $field)
                                <td>
                                    {{ $leaf->$field }}
                                </td>
                            @endforeach
                            <td class="text-right">
                                <a href="{{ route("alder.contact-forms.read",  $leaf->id) }}" class="btn btn-primary btn-icon-split ml-3">
                                     <span class="icon text-white-50">
                                         <i class="fas fa-book-open"></i>
                                     </span>
                                    <span class="text">{{ __('alder::generic.read') }}</span>
                                </a>
                                <!-- TODO if can edit -->
                                <a href="{{ route("alder.contact-forms.edit",  $leaf->id) }}" class="btn btn-warning btn-icon-split ml-3">
                                     <span class="icon text-white-50">
                                         <i class="fas fa-edit"></i>
                                     </span>
                                    <span class="text">{{ __('alder::generic.edit') }}</span>
                                </a>

                                <!-- TODO if can delete -->
                                <a href="{{ route("alder.contact-forms.destroy",  $leaf->id) }}" class="btn btn-danger btn-icon-split ml-3">
                                     <span class="icon text-white-50">
                                         <i class="fas fa-trash-alt"></i>
                                     </span>
                                    <span class="text">{{ __('alder::generic.delete') }}</span>
                                </a>
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
