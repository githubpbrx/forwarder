<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php
        $system = Session::get('system');
        $menu_session = Session::get('menu');
        // print_r($session);
    ?>
    <!-- Brand Logo -->
    <a href="<?php echo e(url('')); ?>" class="brand-link">
        <img src="<?php echo e(url('public/pbrx.png')); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light"><?php echo $system['sidebar_title']; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo e(url('public/adminlte/dist/img/avatar5.png')); ?>" class="img-circle elevation-2"
                    alt="User Image">
            </div>

            <?php
                $session = Session::get('session');
                $fwdses = Session::get('sessionfwd');
            ?>

            <div class="info">
                <a href="#" class="d-block"><?php echo e($session['user_nama']); ?></a>
            </div>
        </div>

        <?php
            $active_menu = 'active';
            $dashboard = '';

            // you can append the menus
            if ($menu == 'dashboard') {
                $dashboard = $active_menu;
            }
        ?>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview"
                role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?php echo e(url('')); ?>" class="nav-link <?php echo e($dashboard); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <?php if(RoleAccess::whereMenu(8) +
                        RoleAccess::whereMenu(14) +
                        RoleAccess::whereMenu(18) +
                        RoleAccess::whereMenu(19) +
                        RoleAccess::whereMenu(20) +
                        RoleAccess::whereMenu(22) +
                        RoleAccess::whereMenu(23) +
                        RoleAccess::whereMenu(24) +
                        RoleAccess::whereMenu(25) >
                        0): ?>
                    <li
                        class="nav-item mt-2 <?php echo e(request()->is('master/forwarder*') || request()->is('master/hscode*') || request()->is('master/route*') || request()->is('master/pol*') || request()->is('master/pod*') || request()->is('master/country*') || request()->is('master/polcity*') || request()->is('master/podcity*') || request()->is('master/shipping*') ? 'menu-open' : ''); ?>">
                        <a href="#"
                            class="nav-link <?php echo e(request()->is('master/forwarder*') || request()->is('master/hscode*') || request()->is('master/route*') || request()->is('master/pol*') || request()->is('master/pod*') || request()->is('master/country*') || request()->is('master/polcity*') || request()->is('master/podcity*') || request()->is('master/shipping*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-server"></i>
                            <p>
                                Master
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(8) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('masterforwarder')); ?>"
                                        class="nav-link <?php echo e(request()->is('master/forwarder*') ? 'active' : ''); ?>">
                                        <i class="fas fa-forward nav-icon"></i>
                                        <p>Master Forwarder</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(14) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('masterhscode')); ?>"
                                        class="nav-link <?php echo e(request()->is('master/hscode*') ? 'active' : ''); ?>">
                                        <i class="fas fa-dot-circle nav-icon"></i>
                                        <p>Master HSCode</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(18) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('masterroute')); ?>"
                                        class="nav-link <?php echo e(request()->is('master/route*') ? 'active' : ''); ?>">
                                        <i class="fas fa-route nav-icon"></i>
                                        <p>Master Route</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(19) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('masterpol')); ?>"
                                        class="nav-link <?php echo e(request()->is('master/pol*') ? 'active' : ''); ?>">
                                        <i class="fas fa-spinner nav-icon"></i>
                                        <p>Master Port Of Loading</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(20) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('masterpod')); ?>"
                                        class="nav-link <?php echo e(request()->is('master/pod*') ? 'active' : ''); ?>">
                                        <i class="fas fa-location-arrow nav-icon"></i>
                                        <p>Master Port Of Destination</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(22) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('mastercountry')); ?>"
                                        class="nav-link <?php echo e(request()->is('master/country*') ? 'active' : ''); ?>">
                                        <i class="fas fa-flag nav-icon"></i>
                                        <p>Master Country</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(23) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('masterpolcity')); ?>"
                                        class="nav-link <?php echo e(request()->is('master/polcity*') ? 'active' : ''); ?>">
                                        <i class="fas fa-spinner nav-icon"></i>
                                        <p>Master POL (City)</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(24) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('masterpodcity')); ?>"
                                        class="nav-link <?php echo e(request()->is('master/podcity*') ? 'active' : ''); ?>">
                                        <i class="fas fa-location-arrow nav-icon"></i>
                                        <p>Master POD (City)</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(24) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('mastershipping')); ?>"
                                        class="nav-link <?php echo e(request()->is('master/shipping*') ? 'active' : ''); ?>">
                                        <i class="fas fa-shipping-fast nav-icon"></i>
                                        <p>Master Shipping Line</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if(RoleAccess::whereMenu(6) > 0): ?>
                    <li class="nav-item mt-2 <?php echo e(request()->is('transaksi/allocation*') ? 'menu-open' : ''); ?>">
                        <a href="#"
                            class="nav-link <?php echo e(request()->is('transaksi/allocation*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-server"></i>
                            <p>
                                Logistic
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(6) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('data_allocation')); ?>"
                                        class="nav-link <?php echo e(request()->is('transaksi/allocation*') ? 'active' : ''); ?>">
                                        <i class="fas fa-share nav-icon"></i>
                                        <p>Cancel Allocation</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        
                    </li>
                <?php endif; ?>

                <?php if(RoleAccess::whereMenu(26) > 0): ?>
                    <li class="nav-item mt-2 <?php echo e(request()->is('transaksi/mappingratefcl*') ? 'menu-open' : ''); ?>">
                        <a href="#"
                            class="nav-link <?php echo e(request()->is('transaksi/mappingratefcl*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-server"></i>
                            <p>
                                Rate FCL
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(26) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('mappingratefcl')); ?>"
                                        class="nav-link <?php echo e(request()->is('transaksi/mappingratefcl*') ? 'active' : ''); ?>">
                                        <i class="fas fa-map-marked nav-icon"></i>
                                        <p>Mapping Rate FCL</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        
                    </li>
                <?php endif; ?>

                <?php if(RoleAccess::whereMenu(9) +
                        RoleAccess::whereMenu(10) +
                        RoleAccess::whereMenu(17) +
                        RoleAccess::whereMenu(29) +
                        RoleAccess::whereMenu(30) >
                        0): ?>
                    <li
                        class="nav-item mt-2 <?php echo e(request()->is('report/po*') || request()->is('report/alokasi*') || request()->is('report/shipment*') || request()->is('report/resultratefcladmin*') || request()->is('report/bestratefcladmin*') ? 'menu-open' : ''); ?>">
                        <a href="#"
                            class="nav-link <?php echo e(request()->is('report/po*') || request()->is('report/alokasi*') || request()->is('report/shipment*') || request()->is('report/resultratefcladmin*') || request()->is('report/bestratefcladmin*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-server"></i>
                            <p>
                                Report
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(9) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('reportpo')); ?>"
                                        class="nav-link <?php echo e(request()->is('report/po*') ? 'active' : ''); ?>">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Outstanding PO</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(10) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('reportalokasi')); ?>"
                                        class="nav-link <?php echo e(request()->is('report/alokasi*') ? 'active' : ''); ?>">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Ready Allocation</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(17) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('reportreadyshipment')); ?>"
                                        class="nav-link <?php echo e(request()->is('report/shipment*') ? 'active' : ''); ?>">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Ready Shipment</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(29) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('resultratefcladmin')); ?>"
                                        class="nav-link <?php echo e(request()->is('report/resultratefcladmin*') ? 'active' : ''); ?>">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Result Rate FCL</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <?php if(RoleAccess::whereMenu(30) > 0): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('bestratefcladmin')); ?>"
                                        class="nav-link <?php echo e(request()->is('report/bestratefcladmin*') ? 'active' : ''); ?>">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Best Rate FCL</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                
                <?php if(RoleAccess::whereMenu(13) > 0): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('process_shipment')); ?>"
                            class="nav-link <?php echo e(request()->is('transaksi/outstandingshipment*') ? 'active' : ''); ?>">
                            <i class="fa fa-share nav-icon"></i>
                            <p>Outstanding Shipment</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(RoleAccess::whereMenu(21) > 0): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('dataupdatebooking')); ?>"
                            class="nav-link <?php echo e(request()->is('transaksi/updatebooking*') ? 'active' : ''); ?>">
                            <i class="fa fa-book nav-icon"></i>
                            <p>Update Booking</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(RoleAccess::whereMenu(12) > 0): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('datashipment')); ?>"
                            class="nav-link <?php echo e(request()->is('transaksi/shipment*') ? 'active' : ''); ?>">
                            <i class="fa fa-archive nav-icon"></i>
                            <p>Update Shipment</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(RoleAccess::whereMenu(27) > 0): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('inputratefcl')); ?>"
                            class="nav-link <?php echo e(request()->is('transaksi/inputratefcl*') ? 'active' : ''); ?>">
                            <i class="fas fa-percentage nav-icon"></i>
                            <p>Input FCL Rate</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(RoleAccess::whereMenu(28) > 0): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('bestratefcl')); ?>"
                            class="nav-link <?php echo e(request()->is('report/bestratefcl*') ? 'active' : ''); ?>">
                            <i class="fas fa-poll-h nav-icon"></i>
                            <p>Best Rate FCL</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(RoleAccess::whereMenu(11) > 0): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('reportforwarder')); ?>"
                            class="nav-link <?php echo e(request()->is('report/forwarder*') ? 'active' : ''); ?>">
                            <i class="fa fa-file nav-icon"></i>
                            <p>Report Forwarder</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if($fwdses == 1 && RoleAccess::whereMenu(15) > 0): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('privilege/accessfwd')); ?>"
                            class="nav-link <?php echo e(request()->is('privilege/accessfwd*') ? 'active' : ''); ?>">
                            <i class="fa fa-users nav-icon"></i>
                            <p>Manage User Forwarder</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(RoleAccess::whereMenu(16) > 0): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('historyallocation')); ?>"
                            class="nav-link <?php echo e(request()->is('report/allocation*') ? 'active' : ''); ?>">
                            <i class="fa fa-history nav-icon"></i>
                            <p>History List PO</p>
                        </a>
                    </li>
                <?php endif; ?>
                

                <?php if($menu_session != ''): ?>
                    <?php echo $__env->make($menu_session . '::template/' . $menu_session . '_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>

                <li class="nav-item mt-2">
                    <a href="<?php echo e(url('logout')); ?>" class="nav-link">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<?php /**PATH D:\laragon\www\forwarder\sources\Modules/System\Resources/views/template/sidebar.blade.php ENDPATH**/ ?>