<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('link_href'); ?>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card" style="font-size: 10pt;">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="serverside" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <center>List PO#</center>
                                    </th>
                                    <th>
                                        <center>Booking Number</center>
                                    </th>
                                    <th>
                                        <center>Quantity PO</center>
                                    </th>
                                    <th>
                                        <center>Quantity Booking</center>
                                    </th>
                                    <th>
                                        <center>Status</center>
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
    </div>
    
    <div class="modal fade" id="updateshipment">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Shipment Detail</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalkushipment"></div>
            </div>
        </div>
    </div>
    
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
                    url: "<?php echo e(route('list_shipmentprocess')); ?>"
                },
                columns: [{
                        data: 'listpo',
                        name: 'listpo'
                    },
                    {
                        data: 'nobook',
                        name: 'nobook'
                    },
                    {
                        data: 'qtypo',
                        name: 'qtypo'
                    },
                    {
                        data: 'qtybooking',
                        name: 'qtybooking'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

            // var length;
            $('body').on('click', '#updateship', function() {
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "<?php echo route('form_shipmentprocess'); ?>",
                    type: 'POST',
                    // dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
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
                        $('#updateshipment').modal({
                            show: true,
                            backdrop: 'static'
                        });
                        $('#modalkushipment').html(data);
                        swal.close();
                    }
                })
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Transaksi\Resources/views/outstandingshipment/process_shipment.blade.php ENDPATH**/ ?>