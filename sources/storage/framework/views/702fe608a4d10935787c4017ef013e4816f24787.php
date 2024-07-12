<?php $__env->startSection('title', 'Renew Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="login-box">
    <div class="login-logo">
        <?php echo e($title); ?>

    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">

            <form action="<?php echo e(route('exp_pass_action')); ?>" method="GET" id="exp_pass_form">
                <?php echo e(csrf_field()); ?>

                <input value="<?php echo e($nik); ?>" id="nik" name="nik" type="hidden">
                <input value="<?php echo e($tgl_exp); ?>" id="tgl_exp" name="tgl_exp" type="hidden">

                <label class="text-black">Please Change Password Now !</label>
                <label id="label_date">Date of Birth</label>
                <div class="form-group mb-3">
                    <input name="birthday" id="birthday" type="date" class="form-control" required>
                    <small><code id="warn_birthday"></code></small>
                </div>
                <label>Old Password</label>
                <div class="form-group mb-3">
                    <input name="old_pass" type="password" class="form-control" placeholder="Enter Old Password" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input id="new_pass" name="new_pass" onkeyup="checkPassword()" type="password" class="form-control " placeholder="Enter New password..." required>
                    <small><code id="warning"></code></small>
                </div>
                <div class="form-group">
                    <label>Retype Password</label>
                    <input id="retype_new_pass" name="retype_new_pass" onkeyup="checkPassword()" type="password" class="form-control" placeholder="Enter retype password..." required>
                </div>
                <div class="row">
                    <div class="col-4">
                        <a href="<?php echo e(url('login')); ?>" class="btn btn-danger btn-block">Cancel</a>
                    </div>
                    <div class="col-8">
                        <button id="submit" type="submit" class="btn btn-info btn-block">Change</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    $(function() {
        load_function()
    });

    function load_function() {
        check_birthday()
        checkPassword()
        cek_alphanumeric()
    }

    function check_birthday() {
        let tgl = $('#birthday').val()
        let nik = $('#nik').val()
        $('#birthday').change(function() {
            let form_data = $('#exp_pass_form').serialize();
            $.ajax({
                type: 'get',
                url: '<?php echo e(url("login/checkbirthday")); ?>',
                data: form_data,
                beforeSend: function(data) {
                    $('#label_date').html('<i class="fas fa-sync fa-spin" id="loading"></i>');
                },
                success: function(data) {
                    $('#loading').hide()
                    if (data == 0) {
                        sweetAlert('error', 'Wrong date of birth');
                        $('#warn_birthday').html('Wrong Date of Birth')
                        $('#submit').attr('disabled', 'disabled');
                    } else {
                        $('#warn_birthday').html('')
                        $('#submit').removeAttr('disabled');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    toast('error', textStatus + ' : ' + errorThrown);
                }
            });
        })
    }

    function checkPassword() {
        var password = $('#new_pass').val();
        var password_re = $('#retype_new_pass').val();

        if (password != '' && password_re != '') {
            if (password != password_re) {
                $('#retype_new_pass').addClass('is-invalid');
                $('#retype_new_pass').removeClass('is-valid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#retype_new_pass').removeClass('is-invalid');
                $('#retype_new_pass').addClass('is-valid');
                $('#submit').removeAttr('disabled');
            }
        }

        if (password.length < 6) {
            // $('#new_pass').addClass('is-invalid');
            $('#new_pass').removeClass('is-valid');

            $('#warning').html('*Mininum length : 6');
            $('#submit').attr('disabled', 'disabled');
        } else {
            pass_numb = password.replace(/[^0-9]/g, '').length;
            pass_char = password.replace(/[0-9]/g, '').length;

            if (pass_numb == 0) {
                $('#new_pass').addClass('is-invalid');
                $('#new_pass').removeClass('is-valid');

                $('#warning').html('*Must contain Number');
                $('#submit').attr('disabled', 'disabled');
            } else if (pass_char == 0) {
                $('#new_pass').addClass('is-invalid');
                $('#new_pass').removeClass('is-valid');

                $('#warning').html('*Must contain Letter');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#new_pass').removeClass('is-invalid');
                $('#new_pass').addClass('is-valid');
                $('#warning').html('');
            }
        }
    }

    // function checkAlphaAndNumeric(params) {
    function cek_alphanumeric() {
        $("#new_pass,#retype_new_pass").keypress(function(event) {
            var ew = event.which;

            if (48 <= ew && ew <= 57)
                return true;
            if (65 <= ew && ew <= 90)
                return true;
            if (97 <= ew && ew <= 122)
                return true;
            return false;
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('system::login/login_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/System\Resources/views/login/login_password_expired.blade.php ENDPATH**/ ?>