@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{__('alder::theme.appearance_title')}}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body ">
            <h2>{{$theme->theme_name}}</h2>
            <div class="row">
                <div class="col-12 col-lg-3">

                </div>
                <div class="col-12 col-lg-9">

                        @php
                            function recursiveArrayToList(Array $array = array())
                            {
                                echo '<ul>';

                                foreach ($array as $key => $value) {

                                    if (is_array($value)) {
                                        echo '<li>' . $key;
                                        recursiveArrayToList($value);
                                        echo '</li>';
                                    }
                                    else{
                                        echo "<li>".$value."</li>";
                                    }
                                }
                                echo '</ul>';
                            }

                            recursiveArrayToList($views_hierarchy);
                        @endphp
                </div>
            </div>
        </div>
    </div>
@endsection
