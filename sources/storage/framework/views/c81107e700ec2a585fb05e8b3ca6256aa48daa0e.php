<?php $__env->startSection('link_href'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <br>
    <p>Hi <b><i>,</i> <?php echo e($nama); ?></b></p>

    <p>You Got New PO : <?php echo e($pono); ?> <br> Please Check In <b>Web Forwarder</b></p>
    <center>
        <a href="<?php echo e($link); ?>"><button
                style="background-color:  #6495ED; color: white; font-weight: bold; width:140px; height: 34px; border-radius: 11px">
                Web Forwarder </button></a>
        
    </center>
    <br>
    
    <b>If you don't request from Web FORWARDER, please ignore this email</b>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script_src'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('transaksi::layouts/masteremail', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Transaksi\Resources/views/layouts/notifpoemail.blade.php ENDPATH**/ ?>