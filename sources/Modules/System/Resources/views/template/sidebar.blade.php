<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @php
        $system = Session::get('system');
        $menu_session = Session::get('menu');
        // print_r($session);
    @endphp
    <!-- Brand Logo -->
    <a href="{{ url('') }}" class="brand-link">
        <img src="{{ url('public/pbrx.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{!! $system['sidebar_title'] !!}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ url('public/adminlte/dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>

        @php
            $session = Session::get('session');
        @endphp

        <div class="info">
            <a href="#" class="d-block">{{ $session['user_nama'] }}</a>
        </div>
    </div>

    @php
        $active_menu    = 'active';
        $dashboard      = '';
        
        // you can append the menus
        if ($menu == 'dashboard') {
            $dashboard = $active_menu;
        }
    @endphp

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ url('') }}" class="nav-link {{ $dashboard }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        @if($menu_session != '')
            @include($menu_session.'::template/'.$menu_session.'_sidebar')
        @endif
        
        <li class="nav-item mt-2">
            <a href="{{ url('logout') }}" class="nav-link">
                <i class="nav-icon fas fa-power-off"></i>
                <p>Logout</p>
            </a>
        </li>
        </ul>
    </nav>
    </div>
</aside>