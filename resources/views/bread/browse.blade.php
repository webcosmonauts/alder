@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $leaf_type->name }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($leaves->count() > 0)
                <table class="table">
                    <thead>
                    <tr>
                        @foreach($leaves->first()->table_columns as $field)
                            <td>
                                {{ $field }}
                            </td>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($leaves as $leaf)
                        <tr>
                            @foreach($leaf->table_columns as $field)
                                <td>
                                    {{ $leaf->$field }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
