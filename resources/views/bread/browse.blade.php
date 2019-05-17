@extends('alder::master')

@section('content')
    @if($leafs->count() > 0)
        <table>
            <thead>
                <tr>
                    @foreach($leafs->first() as $leafs)
                        <td>

                        </td>
                    @endforeach
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    @endif
@endsection