@extends('alder::master')

@section('sidebar')
    <div class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" data-color="rose"
         data-background-color="black"
         id="accordionSidebar">

        <div class="logo">
            <!-- Sidebar - Brand -->
            <a class="simple-text logo-mini" href="/alder">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-fw fa-leaf"></i>
                </div>
            </a>
            <a href="/alder" class="simple-text logo-normal">Alder</a>
        </div>

        <div class="sidebar-wrapper ps-container ps-theme-default ps-active-y">
            @foreach($admin_menu_items as $section)
                <hr class="sidebar-divider my-0">
                <ul class="nav">
                    @if(count($section->children) > 0)
                        <li class="nav-item {{ $section->is_current ? 'active' : '' }} has-dropdown">
                            <a class="nav-link"
                               href="/alder/{{$section->slug}}">
                                @if(!empty($section->icon))
                                    <i class="fas fa-fw fa-{{$section->icon}}"></i>
                                @endif
                                <p>{{ $section->title }}</p>
                            </a>

                            <span class="toggle-collapse {{ $section->is_current ? '' : 'collapsed' }}"
                                  data-toggle="collapse"
                                  data-target="#collapse{{$section->id}}"
                                  aria-expanded="true" aria-controls="collapse{{$section->id}}"></span>

                            <div id="collapse{{$section->id}}" data-parent="#accordionSidebar"
                                 class="collapse  {{ $section->is_current ? 'show' : '' }}">
                                <ul class="nav">
                                    @foreach($section->children as $menu_item)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $menu_item->is_current ? 'active' : '' }}"
                                               href="/alder/{{ $menu_item->slug }}">
                                                @if(!empty($menu_item->icon))
                                                    <i class="fas fa-fw fa-{{$menu_item->icon}}"></i>
                                                @endif
                                                <p>{{ $menu_item->title }}</p>
                                            </a>
                                        </li>

                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @else
                        <li class="nav-item {{ $section->is_current ? 'active' : '' }}">
                            <a class="nav-link {{ $section->is_current ? 'active' : '' }}"
                               href="/alder/{{ $section->slug }}">
                                @if(!empty($section->icon))
                                    <i class="fas fa-fw fa-{{$section->icon}}"></i>
                                @endif
                                <p>{{ $section->title }}</p>
                            </a>
                        </li>
                    @endif
                </ul>
            @endforeach
        </div>

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </div>
@endsection

@section('topbar')
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">

        <div class="navbar-wrapper">
            <div class="navbar-minimize">
                <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                    <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
                    <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
                </button>
            </div>
        </div>


        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-search fa-fw"></i>
                </a>
                <!-- Dropdown - Messages -->
                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                     aria-labelledby="searchDropdown">
                    <form class="form-inline mr-auto w-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small"
                                   placeholder="Search for..." aria-label="Search"
                                   aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>


            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        {{ Auth::user()->fullname }}
                    </span>
                    {{--<img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">--}}
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{route('alder.profile.index')}}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        {{ __('alder::generic.profile') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('alder.logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item" type="submit">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            {{ __('alder::generic.logout') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>

        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
    </nav>
@endsection

@section('main')
    <div class="container-fluid">
        @include('alder::components.messages')
        @yield('content')
    </div>
@endsection
