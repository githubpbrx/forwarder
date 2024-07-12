<?php $__env->startSection('link_href'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <br>
    <p>Hi <b><i>,</i> <?php echo e($nama); ?></b></p>

    <p>Here is the LINK for User Activation <b>Web Forwarder</b> or enter the token code below:
        <center>
            <a href="<?php echo e($link); ?>"><button
                    style="background-color:  #6495ED; color: white; font-weight: bold; width:140px; height: 34px; border-radius: 11px">
                    LINK ACTIVATION </button></a>
            <br><br> or <br><b style="font-size:30pt"><?php echo e($token); ?></b>
        </center>
        <br>
    <p>Please activate the user before using the Web forwarder.
    </p>
    <b>If you don't request from Web FORWARDER, please ignore this email</b>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script_src'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/masteremail', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/System\Resources/views/login/emailaktifasi.blade.php ENDPATH**/ ?>