<?php $__env->startSection('title', 'PT. Pan Brothers Tbk'); ?>

<?php $__env->startSection('content'); ?>
    <div class="login-box">
        <div class="login-logo">
            <a href="<?php echo e(url('adminlte/index2.html')); ?>"> <b>PB</b> Login</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <?php
                    $login_chance = Session::get('login_chance');
                    if (Session::has('login_chance')) {
                        $chance = $login_chance['chance'];
                        $time = $login_chance['time_start'];
                    } else {
                        $chance = 5;
                        $time = 0;
                    }

                    if (Session::has('time_chance')) {
                        $time_chance = date('i:s', Session::get('time_chance'));
                    } else {
                        $time_chance = '00:00';
                    }
                ?>
                
                
                <?php if($chance > 0): ?>
                    <p class="login-box-msg">Login to access </p>
                    <form action="<?php echo e(url('loginadminaction')); ?>" method="post">
                        <?php echo e(csrf_field()); ?>

                        <label for="">Forwarder</label>
                        <div class="input-group mb-3">
                            <select name="masterfwd" id="masterfwd" style="width: 100%;">
                                <option value=""></option>
                                <?php $__currentLoopData = $masterfwd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->privilege_user_nik); ?>"><?php echo e($item->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <label for="">Username</label>
                        <div class="input-group mb-3">
                            <input name="nik" type="text" class="form-control" placeholder="Username" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <label for="">Password</label>
                        <div class="input-group mb-3">
                            <input name="password" type="password" class="form-control" placeholder="Password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-4">
                                <button type="submit" class="btn btn-info btn-block">Login</button>
                            </div>
                            <div class="col-12 mb-4">
                                <a href="<?php echo e(url('forgotpassword')); ?>" class="btn btn-danger btn-block">Forgot Password</a>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <h1 id="time_remaining" class="text-center"></h1>
                    
                <?php endif; ?>

            </div>
        </div>
    </div>
    <?php if(Session::has('notify')): ?>
        <?php
            $notify = Session::get('notify');
        ?>
        <div class="modal fade" id="modal_notify">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Pengumuman</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-footer p-1 mb-2">
                            <code>
                                <?php echo $notify['desc']; ?>

                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $("#masterfwd").select2({
            dropdownAutoWIdth: true,
            placeholder: 'Select Forwarder'
        })

        function chance() {
            $.ajax({
                url: '<?php echo e(url('loginChance')); ?>',
                success: function(data) {
                    console.log(data);
                },
            });
        }

        $(function() {
            //Initialize Select2 Elements
            $('#modal_notify').modal('show');

            <?php if($chance <= 0): ?>
                var timer2 = '<?php echo e($time_chance); ?>';
                var interval = setInterval(function() {

                    var timer = timer2.split(':');
                    //by parsing integer, I avoid all extra string processing
                    var minutes = parseInt(timer[0], 10);
                    var seconds = parseInt(timer[1], 10);
                    --seconds;
                    minutes = (seconds < 0) ? --minutes : minutes;
                    if (minutes < 0) clearInterval(interval);
                    seconds = (seconds < 0) ? 59 : seconds;
                    seconds = (seconds < 10) ? '0' + seconds : seconds;
                    //minutes = (minutes < 10) ?  minutes : minutes;

                    if (minutes == 0 && seconds == 0) {
                        window.location.href = "<?php echo e(url('login')); ?>";
                    }

                    $('#time_remaining').html(minutes + ':' + seconds);
                    timer2 = minutes + ':' + seconds;
                }, 1000);
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::login/login_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/System\Resources/views/login/login_formadmin.blade.php ENDPATH**/ ?>