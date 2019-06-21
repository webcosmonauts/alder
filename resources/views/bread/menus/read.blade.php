@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $menu->title }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                {{--                <a class="navbar-brand" href="#">Navbar</a>--}}
                {{--                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">--}}
                {{--                    <span class="navbar-toggler-icon"></span>--}}
                {{--                </button>--}}
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                        {{--                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                        {{--                                Dropdown--}}
                        {{--                            </a>--}}
                        {{--                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">--}}
                        {{--                                <a class="dropdown-item" href="#">Action</a>--}}
                        {{--                                <a class="dropdown-item" href="#">Another action</a>--}}
                        {{--                                <div class="dropdown-divider"></div>--}}
                        {{--                                <a class="dropdown-item" href="#">Something else here</a>--}}
                        {{--                            </div>--}}
                        {{--                        </li>--}}
                        @php
                            function view_menu($array)
                            {
                                if(is_array($array)) {
                                    foreach ($array as $key => $value) {
                                        if(is_array($value)) {
                                            if ((array_key_exists('children', $value))) {
                                                echo '<ul style="padding-left: 0">';
                                                echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                                echo $value['text'].'</a>';
                                                echo '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                                                $val = (array) $value['children'][0];
                                                view_menu($val);
                                                echo '</div>';
                                                echo '</ul>';
                                            } else {
                                                echo '<li class="nav-item">';
                                                echo '<a class="nav-link" href="'.$value['href'].'">'.$value['text'].'</a>';
                                                echo '</li>';
                                            }
                                        }else {
                                        echo '<a class="dropdown-item" href="'.$array['href'].'">'.$array['text'].'</a>';
                                        }
                                    }
                                }
                            }
                            view_menu($new_content);
                        @endphp
                        {{--                        <li class="nav-item">--}}
                        {{--                            <a class="nav-link" href="{{$value['href']}}">{{$value['text']}}</a>--}}
                        {{--                        </li>--}}
                    </ul>
                </div>
            </nav>

        </div>
    </div>
@endsection
