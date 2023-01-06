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
                <img src="{{ url('public/adminlte/dist/img/avatar5.png') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>

            @php
                $session = Session::get('session');
                $fwdses = Session::get('sessionfwd');
            @endphp

            <div class="info">
                <a href="#" class="d-block">{{ $session['user_nama'] }}</a>
            </div>
        </div>

        @php
            $active_menu = 'active';
            $dashboard = '';

            // you can append the menus
            if ($menu == 'dashboard') {
                $dashboard = $active_menu;
            }
        @endphp

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview"
                role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ url('') }}" class="nav-link {{ $dashboard }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if (RoleAccess::whereMenu(8) + RoleAccess::whereMenu(14) > 0)
                    <li class="nav-item mt-2">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-server"></i>
                            <p>
                                Master
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (RoleAccess::whereMenu(8) > 0)
                                <li class="nav-item">
                                    <a href="{{ route('masterforwarder') }}" class="nav-link">
                                        <i class="fas fa-forward nav-icon"></i>
                                        <p>Master Forwarder</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                        <ul class="nav nav-treeview">
                            @if (RoleAccess::whereMenu(14) > 0)
                                <li class="nav-item">
                                    <a href="{{ route('masterhscode') }}" class="nav-link">
                                        <i class="fas fa-dot-circle nav-icon"></i>
                                        <p>Master HSCode</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (RoleAccess::whereMenu(6) > 0)
                    <li class="nav-item mt-2">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-server"></i>
                            <p>
                                Logistic
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (RoleAccess::whereMenu(6) > 0)
                                <li class="nav-item">
                                    <a href="{{ route('data_allocation') }}" class="nav-link">
                                        <i class="fas fa-share nav-icon"></i>
                                        <p>Cancel Allocation</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                        {{-- <ul class="nav nav-treeview">
                            @if (RoleAccess::whereMenu(7) > 0)
                                <li class="nav-item">
                                    <a href="{{ route('approvalconfirmation') }}" class="nav-link">
                                        <i class="fas fa-check-double nav-icon"></i>
                                        <p>Approval Cofirmation</p>
                                    </a>
                                </li>
                            @endif
                        </ul> --}}
                    </li>
                @endif

                @if (RoleAccess::whereMenu(9) + RoleAccess::whereMenu(10) > 0)
                    <li class="nav-item mt-2">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-server"></i>
                            <p>
                                Report
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (RoleAccess::whereMenu(9) > 0)
                                <li class="nav-item">
                                    <a href="{{ route('reportpo') }}" class="nav-link">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Outstanding PO</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                        <ul class="nav nav-treeview">
                            @if (RoleAccess::whereMenu(10) > 0)
                                <li class="nav-item">
                                    <a href="{{ route('reportalokasi') }}" class="nav-link">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Ready Allocation</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- start for forwarder --}}
                @if (RoleAccess::whereMenu(13) > 0)
                    <li class="nav-item">
                        <a href="{{ route('process_shipment') }}" class="nav-link">
                            <i class="fa fa-share nav-icon"></i>
                            <p>Outstanding Shipment</p>
                        </a>
                    </li>
                @endif

                @if (RoleAccess::whereMenu(12) > 0)
                    <li class="nav-item">
                        <a href="{{ route('datashipment') }}" class="nav-link">
                            <i class="fa fa-archive nav-icon"></i>
                            <p>Update Shipment</p>
                        </a>
                    </li>
                @endif

                @if (RoleAccess::whereMenu(11) > 0)
                    <li class="nav-item">
                        <a href="{{ route('reportforwarder') }}" class="nav-link">
                            <i class="fa fa-file nav-icon"></i>
                            <p>Report Forwarder</p>
                        </a>
                    </li>
                @endif

                @if ($fwdses == 1 && RoleAccess::whereMenu(15) > 0)
                    <li class="nav-item">
                        <a href="{{ url('privilege/accessfwd') }}" class="nav-link">
                            <i class="fa fa-users nav-icon"></i>
                            <p>Manage User Forwarder</p>
                        </a>
                    </li>
                @endif

                @if (RoleAccess::whereMenu(16) > 0)
                    <li class="nav-item">
                        <a href="{{ route('historyallocation') }}" class="nav-link">
                            <i class="fa fa-history nav-icon"></i>
                            <p>History List PO</p>
                        </a>
                    </li>
                @endif
                {{-- end for forwarder --}}

                @if ($menu_session != '')
                    @include($menu_session . '::template/' . $menu_session . '_sidebar')
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
