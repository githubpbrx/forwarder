<?php $__env->startSection('title', $title); ?>

<style>
    input {
        border-top-style: hidden;
        border-right-style: hidden;
        border-left-style: hidden;
        border-bottom-style: groove;
        background-color: transparent;
    }

    .no-outline:focus {
        outline: none;
    }
</style>

<?php $__env->startSection('content'); ?>
    <form action="#" class="form-horizontal" enctype="multipart/form-data">
        <?php echo e(csrf_field()); ?>

        <div class="login-box">
            <div class="login-logo">
                Validation KYC <bR>
            </div>
            <div class="card">
                <div class="card-header bg-primary">Validation KYC</div>
                <div class="card-body" style="background-color: #A5F1E9">

                    <input value="<?php echo e($nik); ?>" id="nik" name="nik" type="hidden">
                    <input value="<?php echo e($nama); ?>" id="nama" name="nama" type="hidden">
                    <?php if($statuskyc == '0'): ?>
                        <div class="form-group">
                            <input id="filekyc" name="filekyc" type="file" class="form-control "
                                placeholder="Enter File..." required>
                        </div>
                    <?php elseif($statuskyc == '1'): ?>
                        <div class="form-group">
                            <center>
                                <h3>
                                    PLEASE WAIT!
                                    <br>
                                    YOUR DATA IS CURRENTLY PROCESSING
                                </h3>
                                
                                <hr>
                                <div class="form-group">
                                    <label>Re-Upload KYC</label>
                                    <input id="filekyc" name="filekyc" type="file" class="form-control "
                                        placeholder="Enter File..." required>
                                </div>
                            </center>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <center>
                                <h3>
                                    YOUR DATA IS REJECTED
                                    <br>
                                    PLEASE CHECK AGAIN
                                </h3>
                                <br>
                                <h5>
                                    DESCRIPTION : <input type="text" class="no-outline" id="deskripsi" readonly>
                                </h5>
                                <br>
                                <a href="<?php echo e(route('validasikycreject')); ?>" class="btn btn-success">Try Again</a>
                            </center>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-primary">
                    <div class="float-left">
                        <a href="<?php echo e(url('logout')); ?>"><i class="btn btn-danger float-left">Exit</i></a>
                    </div>
                    <?php if($statuskyc != '2'): ?>
                        <div class="float-right">
                            <button id="upload" type="button" class="btn btn-success float-right">Upload</button>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if($statuskyc == '0'): ?>
                    <a href="<?php echo e(url('sources\storage\public\file_kyc.xlsx')); ?>" target="_BLANK" class="btn"
                        style="background-color: #82a1f5;">DOWNLOAD FILE KYC</a>
                <?php endif; ?>

            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var ketkyc = <?php echo json_encode($kycku, 15, 512) ?>;
            console.log('ketkyc :>> ', ketkyc);
            if (ketkyc != null) {
                $('#deskripsi').val(ketkyc.ket_tolak);
            }


            $('#upload').click(function(e) {
                console.log('klik :>> ', 'klik');
                let nikku = $('#nik').val();
                let namaku = $('#nama').val();
                let fileku = $('#filekyc').prop('files')[0];
                // let token = $('input[name="_token"]').val();
                let token = $('meta[name=csrf-token]').attr('content');
                let form_data = new FormData();
                form_data.append('nik', nikku);
                form_data.append('nama', namaku);
                form_data.append('file', fileku);
                form_data.append('_token', token);

                console.log('form :>> ', form_data);

                if (fileku == null || fileku == '') {
                    Swal.fire({
                        title: 'Information',
                        text: ' File KYC Can not be empty',
                        type: 'warning'
                    });
                } else {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo e(url('validasikycaction')); ?>",
                        processData: false,
                        contentType: false,
                        data: form_data,
                        dataType: "json",
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                (response.status == 'success') ? window.location
                                    .replace("<?php echo e(route('dashcam')); ?>"):
                                    ''
                            });
                            return;
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Unsuccessfully Saved Data',
                                text: 'Check Your Data',
                                type: 'error'
                            });
                            return;
                        }
                    });
                }
            });

            $('#sendemail').click(function(e) {
                console.log('klik :>> ', 'klik');

                $.ajax({
                    type: "POST",
                    url: "<?php echo e(url('validasikycaction')); ?>",
                    processData: false,
                    contentType: false,
                    data: form_data,
                    dataType: "json",
                    success: function(response) {
                        console.log('response :>> ', response);
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {
                            (response.status == 'success') ? window.location
                                .replace("<?php echo e(route('dashcam')); ?>"):
                                ''
                        });
                        return;
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Unsuccessfully Saved Data',
                            text: 'Check Your Data',
                            type: 'error'
                        });
                        return;
                    }
                });
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::login/login_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/System\Resources/views/login/aktifasikyc.blade.php ENDPATH**/ ?>