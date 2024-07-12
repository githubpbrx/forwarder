<?php $__env->startSection('title', $title); ?>

<?php $__env->startSection('content'); ?>
<div class="login-box">
    <div class="login-logo">
        <?php echo e($nama); ?> <br>
        
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <form action="<?php echo e(url('login/newnohripspasswordaction')); ?>" method="POST" >
                <?php echo e(csrf_field()); ?>

                <input value="<?php echo e($nik); ?>" id="nik" name="nik" type="hidden">
                <input value="<?php echo e($nama); ?>" id="nama" name="nama" type="hidden">
                <div class="form-group">
                    <label>New Password</label>
                    <input id="password" name="password" onkeyup="checkPassword()" type="password" class="form-control " placeholder="Enter password..." required>
                    <small><code id="warning"></code></small>
                </div>
                <div class="form-group">
                    <label>Retype Password</label>
                    <input id="password_retype" name="password_retype" onkeyup="checkPassword()" type="password" class="form-control" placeholder="Retype password..." required>
                </div>
                <button id="submit" type="submit" class="btn btn-success float-right">Change</button>
            </form>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    });


    

    function checkPassword() {
        var password    = $('#password').val();
        var password_re = $('#password_retype').val();

        if (password != '' && password_re != '') {
            if (password != password_re) {
                $('#password_retype').addClass('is-invalid');
                $('#password_retype').removeClass('is-valid');

                $('#submit').attr('disabled', 'disabled');
            }else{
                $('#password_retype').removeClass('is-invalid');
                $('#password_retype').addClass('is-valid');
                $('#submit').removeAttr('disabled');
            }
        }

        if (password.length < 6) {
            $('#password').addClass('is-invalid');
            $('#password').removeClass('is-valid');

            $('#warning').html('*Mininum length : 6');
            $('#submit').attr('disabled', 'disabled');
        }else{
            pass_numb = password.replace(/[^0-9]/g, '').length;
            pass_char = password.replace(/[0-9]/g, '').length;
                                 
            if (pass_numb == 0) {
                $('#password').addClass('is-invalid');
                $('#password').removeClass('is-valid');

                $('#warning').html('*Must contain Number');
                $('#submit').attr('disabled', 'disabled');
            }else if(pass_char == 0){
                $('#password').addClass('is-invalid');
                $('#password').removeClass('is-valid');

                $('#warning').html('*Must contain Letter');
                $('#submit').attr('disabled', 'disabled');
            }else{
                $('#password').removeClass('is-invalid');
                $('#password').addClass('is-valid');
                $('#warning').html('');
            }
        }
    }

    // function checkAlphaAndNumeric(params) {
    $("#password,#a_1,#a_2").keypress(function(event){
        var ew = event.which;
        
        if(48 <= ew && ew <= 57)
            return true;
        if(65 <= ew && ew <= 90)
            return true;
        if(97 <= ew && ew <= 122)
            return true;
        return false;
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('system::login/login_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/System\Resources/views/login/login_new_nohrips_password.blade.php ENDPATH**/ ?>