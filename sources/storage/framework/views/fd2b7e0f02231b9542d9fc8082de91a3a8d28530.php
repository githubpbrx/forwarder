<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('link_href'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="text-center">REKAPITULASI FORM FCL RATE PB</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <div class="col-auto">
                            <label>Periode:</label>
                            <select class="form-control select2" style="width: 100%;" name="periode" id="periode">
                                <option value="" selected disabled>-- Select Periode --</option>
                                <?php $__currentLoopData = $periode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $per): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $perawal = date('d M Y', strtotime($per->periodeawal));
                                        $perakhir = date('d M Y', strtotime($per->periodeakhir));
                                    ?>
                                    <option value="<?php echo e($per->periodeawal . '/' . $per->periodeakhir); ?>">
                                        <?php echo e($perawal . ' - ' . $perakhir); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-auto mt-4">
                            <button class="btn btn-success form-control mt-1" type="button" id="btnview">View</button>
                        </div>
                        <div id="download" class="col-auto ml-auto mt-4 d-none">
                            <a href="<?php echo e(url('report/bestratefcl/getexcel')); ?>" type="button"
                                class="btn btn-warning form-control">Download Excel</a>
                        </div>
                    </div>
                    
                    <div id="kontent"></div>
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

            $('#periode').select2({
                dropdownAutoWidth: true,
                width: 'auto'
            });

            $('#btnview').click(function(e) {
                let periode = $('#periode').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo route('getbestrate'); ?>",
                    data: {
                        periode: periode
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

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Report\Resources/views/bestratefcl/index.blade.php ENDPATH**/ ?>