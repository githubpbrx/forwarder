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
                                    <center>Invoice</center>
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

    
    <div class="modal fade" id="detailshipment">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Edit Shipment</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalupdateshipment"></div>
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
                    url: "<?php echo e(route('list_shipment')); ?>"
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
                        data: 'inv',
                        name: 'inv'
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
            $('body').on('click', '#detailbtn', function() {
                $('#detailshipment').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "<?php echo route('getdatashipment'); ?>",
                    type: 'POST',
                    // dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    $('#modalupdateshipment').html(data);
                    // console.log('datakuh :>> ', data);
                    // let mydata = data.data.shipment;
                    // let myremain = data.data.remaining;
                    // console.log('mydata :>> ', mydata);
                    // console.log('myremain :>> ', myremain);
                    // idshipment = mydata.id_shipment;
                    // idformpo = mydata.idformpo;
                    // length = mydata.length;
                    // $('#detailitem').empty();

                    // html =
                    //     '<table border="0" style="width:100%"><tr><th style="text-align:center"><input type="checkbox" class="checkall" style="height:18px;width:18px" checked></th><th>Material</th><th>BL Number</th><th>Color Code</th><th>Size</th><th>Qty Item</th><th>Remaining Qty</th><th>Qty Shipment</th></tr>';
                    // for (let index = 0; index < mydata.length; index++) {
                    //     let remain;
                    //     let block;
                    //     // let qtyshipment = (mydata[index].qty_shipment==[]) ? '0' : mydata[index].qty_shipment;

                    //     if (myremain[index][0].qtyshipment == null) {
                    //         remain = mydata[index].qtypo;
                    //         block = 'disabled';
                    //     } else if (myremain[index][0].qtyshipment == mydata[index].qtypo) {
                    //         remain = '0';
                    //         block = 'disabled';
                    //     } else {
                    //         remain = mydata[index].qtypo - myremain[index][0].qtyshipment;
                    //         block = 'disabled';
                    //     }

                    //     html +=
                    //         '<tr><td style="text-align:center"><input type="checkbox" class="check-' +
                    //         index + '" style="height:18px;width:18px" checked></td><td>' +
                    //         mydata[index]
                    //         .matcontents + '</td><td>' + mydata[index].nomor_bl + '</td><td>' +
                    //         mydata[index].colorcode + '</td><td>' + mydata[index].size +
                    //         '</td><td>' + mydata[index].qtypo + '</td><td>' + remain +
                    //         '</td><td><input type="number" min="0" id="qtyship" name="qtyship" value="' +
                    //         mydata[index].qty_shipment +
                    //         '" class="form-control trigerinput cekinput-' +
                    //         index + '" data-idformshipment="' + mydata[index].id_shipment +
                    //         '"  data-idformpo="' + mydata[index].id_formpo +
                    //         '"></td></tr>';
                    // }
                    // html += "</table>";
                    // $('#detailitem').html(html);
                    // checkqtyall();

                    // idpo = mydata[].id;
                    // idformpo = databook.id_formpo;
                    // usernik = privilege.privilege_user_nik;
                    // usernama = privilege.privilege_user_name;
                    // tglpengajuan = databook.created_at;

                    // $('#invoice').val(mydata[0].noinv);
                    // $('#etd').val(mydata[0].etdfix);
                    // $('#eta').val(mydata[0].etafix);
                    // $('#nobl').val(mydata[0].nomor_bl);
                    // $('#vessel').val(mydata[0].vessel);
                })
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Transaksi\Resources/views/datashipment.blade.php ENDPATH**/ ?>