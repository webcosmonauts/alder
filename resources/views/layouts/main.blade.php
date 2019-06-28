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

        @php
            $auth_user = Auth::user();
        @endphp

        <div class="sidebar-wrapper ps-container ps-theme-default ps-active-y">
            @foreach($admin_menu_items as $section)
                @if(is_null($section->urc) || $auth_user->can($section->urc))
                    <ul class="nav">
                        @php
                            $can_access_children = count($section->children) > 0;
                            if ($can_access_children) {
                                $can_access_children = false;
                                foreach ($section->children as $menu_item) {
                                    if (isset($menu_item->urc) && $auth_user->can($menu_item->urc))
                                        $can_access_children = true;
                                }
                            }
                        @endphp
                        @if($can_access_children)
                            <li class="nav-item {{ $section->is_current ? 'active' : '' }} has-dropdown">
                                <a class="nav-link" href="/alder/{{$section->slug}}">
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
                                            @if(is_null($menu_item->urc) || $auth_user->can($menu_item->urc))
                                                <li class="nav-item">
                                                    <a class="nav-link {{ $menu_item->is_current ? 'active' : '' }}"
                                                       href="/alder/{{ $menu_item->slug }}">
                                                        @if(!empty($menu_item->icon))
                                                            <i class="fas fa-fw fa-{{$menu_item->icon}}"></i>
                                                        @endif
                                                        <p>{{ $menu_item->title }}</p>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @else
                            @if(is_null($section->urc) || $auth_user->can($section->urc))
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
                        @endif
                    </ul>
                @endif
            @endforeach
        </div>

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </div>
@endsection

@section('topbar')
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top" style="height: 50px;">

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

            <!-- Nav Item - Alerts -->
            {{--<li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <span class="badge badge-danger badge-counter">3+</span>
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Alerts Center
                    </h6>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="mr-3">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">December 12, 2019</div>
                            <span class="font-weight-bold">A new monthly report is ready to download!</span>
                        </div>
                    </a>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="mr-3">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-donate text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">December 7, 2019</div>
                            $290.29 has been deposited into your account!
                        </div>
                    </a>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="mr-3">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">December 2, 2019</div>
                            Spending Alert: We've noticed unusually high spending for your account.
                        </div>
                    </a>
                    --}}{{--<a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>--}}{{--
                </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>--}}

            {{--@if(!empty(Auth::user()->avatar))
                <div id="profile-avatar"
                     style="background-image: url('{{asset('storage/'.Auth::user()->avatar) }}')">
                </div>
            @endif--}}

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 6px;">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        {{ Auth::user()->fullname }}
                    </span>
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
