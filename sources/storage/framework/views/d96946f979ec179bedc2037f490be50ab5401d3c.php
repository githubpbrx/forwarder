<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('link_href'); ?>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row" style="font-size: 10pt;">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-body">
                    <table id="serverside" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <center>PO</center>
                                </th>
                                <th>
                                    <center>Booking Number</center>
                                </th>
                                <th>
                                    <center>Action</center>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="detailupdatebooking">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Edit Booking</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalupdatebooking"></div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script_src'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(route('list_booking')); ?>"
                },
                columns: [{
                        data: 'pono',
                        name: 'pono'
                    },
                    {
                        data: 'kodebook',
                        name: 'kodebook'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            $('#serverside').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            })

            var idshipment;
            var idformpo;
            var length;
            $('body').on('click', '#editbtn', function() {
                let kodebook = $(this).attr('data-kodebooking');
                let idforwarder = $(this).attr('data-idforwarder');
                console.log('idforwarder :>> ', idforwarder);
                $.ajax({
                    url: "<?php echo route('getdatabooking'); ?>",
                    type: 'POST',
                    // dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        kodebook: kodebook,
                        idforwarder: idforwarder,
                    },
                    beforeSend: function(param) {
                        Swal.fire({
                            title: 'Please Wait .......',
                            // html: '',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            onOpen: () => {
                                swal.showLoading();
                            }
                        })
                    },
                    success: function(data) {
                        $('#detailupdatebooking').modal({
                            show: true,
                            backdrop: 'static'
                        });
                        $('#modalupdatebooking').html(data);
                        swal.close();
                    }
                })
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Transaksi\Resources/views/updatebooking/dataupdatebooking.blade.php ENDPATH**/ ?>