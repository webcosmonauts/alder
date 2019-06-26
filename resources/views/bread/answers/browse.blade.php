@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __("alder::leaf_types.answers.singular") }}</h1>
        <!-- TODO: if can add new -->
        @include('alder::components.locale-switcher')
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($answers->count() > 0)
                <table class="table" id="browse-table">
                    <thead>
                    <tr>
                        @foreach(['id','content'] as $field)
                            <th>
                                {{ __("alder::table_columns.$field") }}
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($answers as $leaf)
                        <tr>
                                <td>
                                    {{$leaf->id}}
                                </td>
                                <td>
                                    @php
                                        $val = json_decode($leaf->content);
                                    foreach ($val as $key => $value) {
                                        echo $key . ' : ' . $value . '<br>';
                                    }
                                    @endphp
                                </td>
{{--                            <td class="text-right">--}}
{{--                                @include('alder::components.actions', ['route' => 'users', 'leaf' => $leaf, 'preview' => true])--}}
{{--                            </td>--}}
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
