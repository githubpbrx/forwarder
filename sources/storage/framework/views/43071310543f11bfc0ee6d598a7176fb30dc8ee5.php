<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('link_href'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-body">
                    
                    
                    <div class="d-flex">
                        <div class="col-auto">
                            <label>Year:</label>
                            <select class="form-control select2" style="width: 100%;" name="year" id="year">
                                <option value="" selected disabled>-- Select Year --</option>
                                <?php $__currentLoopData = $year; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($yr->tgl); ?>"><?php echo e($yr->tgl); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label>Month:</label>
                            <select class="form-control select2" style="width: 100%;" name="month" id="month">
                                <option value="" selected disabled>-- Select Month --</option>
                                <?php $__currentLoopData = $month; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $mt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"><?php echo e($mt); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-auto mt-4">
                            <button class="btn btn-success form-control mt-1" type="button" id="btnview">View</button>
                        </div>
                        <div id="download" class="col-auto ml-auto mt-4 d-none">
                            <a href="<?php echo e(url('report/resultratefcladmin/getexcel')); ?>" type="button"
                                class="btn btn-warning form-control">Download Excel</a>
                        </div>
                    </div>
                    <div id="kontent"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#year').select2();
            $('#month').select2();

            $('#btnview').click(function(e) {
                let year = $('#year').val();
                let month = $('#month').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo route('getreport'); ?>",
                    data: {
                        year: year,
                        month: month
                    },
                    // dataType: "json",
                    success: function(response) {
                        $('#kontent').html(response);
                        $('#download').removeClass('d-none');
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Report\Resources/views/resultfcladmin/index.blade.php ENDPATH**/ ?>