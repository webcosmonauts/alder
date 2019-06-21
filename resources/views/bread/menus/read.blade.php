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
                <div class=" navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        @foreach($content as $menu)
                            @if(isset($menu->children))
                                <li class="nav-item dropdown">
                                    <ul style="padding-left: 0">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ $menu->text }}
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            @foreach($menu->children as $submenu)
                                                @if(isset($submenu->children))
                                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ $submenu->text }}
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                        @foreach($submenu->children as $subsubmenu)
                                                            <a class="dropdown-item" href="{{ $subsubmenu->href }}">{{ $subsubmenu->text }}</a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <a class="dropdown-item" href="{{ $submenu->href }}">{{ $submenu->text }}</a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{$menu->href}}">{{$menu->text}}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </nav>

        </div>
    </div>
@endsection
