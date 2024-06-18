<?php
    $system = Session::get('system');
    $menu_session = Session::get('menu');
    // print_r($menu_session);
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-info">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>

        <?php ($session = Session::get('session')) ?>
        <li class="nav-item d-none d-sm-inline-block nav-link">NIK : <?php echo e($session['user_nik']); ?></li>

        <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-cogs"></i> Tools</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li><a href="<?php echo e(url('changepassword')); ?>" class="dropdown-item">Change Password</a></li>

                <?php if(RoleAccess::whereMenu(5) > 0): ?>
                <li><a href="<?php echo e(url('factory')); ?>" class="dropdown-item">Manage Factory</a></li>    
                <?php endif; ?>
                <?php if(RoleAccess::whereMenuIn([4, 2]) > 0): ?>
                <li class="dropdown-submenu dropdown-hover">
                    <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Manage Access</a>
                    <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                        <?php if(RoleAccess::whereMenu(2) > 0): ?>
                        <li><a href="<?php echo e(url('privilege/user_access')); ?>" class="dropdown-item">Manage User Access</a></li>
                        <?php endif; ?>
                        <?php if(RoleAccess::whereMenu(4) > 0): ?>
                        <li><a href="<?php echo e(url('privilege/group_access')); ?>" class="dropdown-item">Manage Group Access</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(RoleAccess::whereMenu(3) > 0): ?>
                <li><a href="<?php echo e(url('settings/application')); ?>" class="dropdown-item">Manage Application</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo e(url('logout')); ?>" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>

    <?php if($menu_session != ''): ?>
        <?php echo $__env->make($menu_session.'::template/'.$menu_session.'_navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
</nav>
<!-- /.navbar -->
<?php /**PATH D:\laragon\www\forwarder\sources\Modules/System\Resources/views/template/navbar.blade.php ENDPATH**/ ?>