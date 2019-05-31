<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <i class="fas fa-fw fa-leaf"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Alder</div>
    </a>

    @foreach($admin_menu_items as $section)
        @if(count($section->children) > 0)
            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                {{ $section->title }}
            </div>
            @foreach($section->children as $menu_item)
                @if(count($menu_item->children) > 0)
                    <li class="nav-item">
                        <a class="nav-link {{ $menu_item->is_current ? '' : 'collapsed' }}"
                           href="#" data-toggle="collapse" data-target="#collapse{{$menu_item->id}}"
                           aria-expanded="true" aria-controls="collapse{{$menu_item->id}}">
                            @if(!empty($menu_item->icon))
                                <i class="fas fa-fw fa-{{$menu_item->icon}}"></i>
                            @endif
                            <span>{{ $menu_item->title }}</span>
                        </a>
                        <div id="collapse{{$menu_item->id}}" data-parent="#accordionSidebar"
                             class="collapse  {{ $menu_item->is_current ? 'show' : '' }}">
                            <div class="bg-white py-2 collapse-inner rounded">
                                @foreach($menu_item->children as $submenu_item)
                                    <a class="collapse-item {{ $submenu_item->is_current ? 'active' : '' }}"
                                       href="/alder/{{ $submenu_item->slug }}">
                                        @if(!empty($submenu_item->icon))
                                            <i class="fas fa-fw fa-{{$submenu_item->icon}}"></i>
                                        @endif
                                        {{ $submenu_item->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ $menu_item->is_current ? 'active' : '' }}" href="charts.html">
                            @if(!empty($menu_item->icon))
                                <i class="fas fa-fw fa-{{$menu_item->icon}}"></i>
                            @endif
                            <span>{{ $menu_item->title }}</span></a>
                    </li>
                @endif
            @endforeach
        @else
            <hr class="sidebar-divider my-0">
            <li class="nav-item {{ $section->is_current ? 'active' : '' }}">
                <a class="nav-link" href="index.html">
                    @if(!empty($section->icon))
                        <i class="fas fa-fw fa-{{$section->icon}}"></i>
                    @endif
                    <span>{{ $section->title }}</span></a>
            </li>
        @endif
    @endforeach

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>