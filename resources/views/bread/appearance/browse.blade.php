@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{__('alder::theme.appearance_title')}}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body row">
            <div class="col-12 col-lg-3">
                {{--                @dd($theme)--}}
                <img src="{{asset($theme->screenshot)}}" alt="" class="img-fluid">
            </div>
            <div class="col-12 col-lg-9">


                <h2>{{$theme->theme_name}}</h2>

                <h4>{{$theme->theme_description}}</h4>
                <br>
                <?php $autors = explode('>', $theme->author)?>
                <?php
                foreach($autors as $value) {
                    $val = explode('<', $value);
                    foreach ($val as $values){
                        echo $values;
                    }
                    echo '<br>';
                }
                ?>

            </div>
        </div>
    </div>
@endsection
