@extends('alder::master')

@section('content')
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
@endsection