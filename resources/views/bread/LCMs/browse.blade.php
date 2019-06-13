@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('alder::lcm.plural') }}</h1>
        <!-- TODO: if can add new -->
        <a href="{{ route("alder.LCMs.create") }}" class="btn btn-success btn-icon-split ml-3">
            <span class="icon text-white-50">
                <i class="fas fa-plus-circle"></i>
            </span>
            <span class="text">{{ __('alder::generic.add_new') . ' ' . lcfirst(__('alder::lcm.singular')) }}</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($LCMs->count() > 0)
                <table class="table">
                    <thead>
                    <tr>
                        <td>{{ __('alder::generic.title') }}</td>
                        <td>{{ __('alder::lcm.group_title') }}</td>
                        <td>{{ __('alder::generic.created_at') }}</td>
                        <td class="text-right">{{ __('alder::generic.actions') }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($LCMs as $LCM)
                        <tr>
                            <td>{{ $LCM->title }}</td>
                            <td>{{ $LCM->group_title }}</td>
                            <td>{{ $LCM->created_at }}</td>
                            <td class="text-right">
                                <a href="{{ route("alder.LCMs.show", $LCM->slug) }}" class="btn btn-primary btn-icon-split ml-3">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-book-open"></i>
                                    </span>
                                    <span class="text">{{ __('alder::generic.read') }}</span>
                                </a>

                                <!-- TODO if can edit -->
                                <a href="{{ route("alder.LCMs.edit", $LCM->slug) }}" class="btn btn-warning btn-icon-split ml-3">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span class="text">{{ __('alder::generic.edit') }}</span>
                                </a>

                                <!-- TODO if can delete -->
                                <a href="{{ route("alder.LCMs.destroy", $LCM->slug) }}" class="btn btn-danger btn-icon-split ml-3">
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
                <h3>{{ __('alder::datatable.sEmptyTable') }}</h3>
            @endif
        </div>
    </div>
@endsection
