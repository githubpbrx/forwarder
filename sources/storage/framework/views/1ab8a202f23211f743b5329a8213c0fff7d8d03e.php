<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('link_href'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row" style="font-size: 10pt;">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="row">
                    <div class="col-md-12">
                        <div id="fullscreen-container" class="card-body" style="overflow-y: auto;">
                            

                            <div class="table-responsive" style="padding-top: 20px;">
                                <table id="dataTables" class="table table-bordered table-striped table-hover"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <center>PO#</center>
                                            </th>
                                            <th>
                                                <center>Date Allocation</center>
                                            </th>
                                            <th>
                                                <center>Forwarder</center>
                                            </th>
                                            <th>
                                                <center>Status</center>
                                            </th>
                                            <th>
                                                <center>Move To</center>
                                            </th>
                                            <th>
                                                <center>Action</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Move Forwarder -->
    <div class="modal fade" id="modal-movefwd">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle"></span> Data Allocation Move Forwarder</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt">
                    <form class="form-horizontal" id="form-detail">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" id="idmasterfwd">
                        <input type="hidden" id="ponomor">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Forwarder</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="datafwd" id="datafwd">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" id="btnsubmit">Move</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Allocation -->
    <div class="modal fade" id="modal-detailallocation">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle"></span> Data Detail Allocation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt">
                    <form class="form-horizontal" id="form-detail">
                        <?php echo e(csrf_field()); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="namafwddetail" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="datapo"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
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

            var table = $('#dataTables').DataTable({
                processing: true,
                serverSide: true,
                "ordering": false,
                ajax: {
                    url: "<?php echo e(url('transaksi/allocation/datatables')); ?>",
                    // data: function(d) {
                    //     d.supplier = $('#supplier').val(),
                    //         d.tanggal1 = $('#tanggal1').val(),
                    //         d.tanggal2 = $('#tanggal2').val(),
                    //         d.status = $('#status').val()
                    // }
                },
                columns: [{
                        data: 'poku',
                        name: 'poku'
                    },
                    {
                        data: 'dateallocation',
                        name: 'dateallocation'
                    },
                    {
                        data: 'namafwd',
                        name: 'namafwd'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'moveto',
                        name: 'moveto'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

            // $(".searchEmail").keyup(function() {
            // $('#searchdata').click(function(e) {
            //     table.draw();
            // });

            $('#dataTables').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('body').on('click', '#cancelbtn', function() {
                let idku = $(this).attr('data-id');
                let idfwd = $(this).attr('data-fwd');
                // console.log('idku :>> ', url);
                Swal.fire({
                    title: 'Validation cancel data!',
                    text: 'Are you sure you want to cancel the data  ?',
                    type: 'question',
                    showConfirmButton: true,
                    showCancelButton: true,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "GET",
                            // url: url,
                            url: "<?php echo url('transaksi/allocation/cancelallocation/'); ?>" + "/" + idku + "/" + idfwd,
                            dataType: "JSON",
                            success: function(response) {
                                Swal.fire({
                                    title: response.title,
                                    text: response.message,
                                    type: (response.status != 'error') ?
                                        'success' : 'error'
                                }).then(() => {
                                    $('#dataTables').DataTable().ajax.reload();
                                })
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
                        return false;
                    }
                })
            });

            $('#supplier').select2({
                placeholder: '-- Choose Supplier --',
                ajax: {
                    url: "<?php echo route('allocation_getsupplier'); ?>",
                    dataType: 'json',
                    delay: 500,
                    type: 'post',
                    data: function(params) {
                        var query = {
                            q: params.term,
                            // page: params.page || 1,
                            _token: $('meta[name=csrf-token]').attr('content')
                        }
                        return query;
                    },
                    processResults: function(data, params) {
                        return {

                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.id,
                                    selected: true,
                                }
                            }),
                        };
                    },
                    cache: true
                }
            });

            $('body').on('click', '#editbtn', function() {
                $('#modal-movefwd').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                let idfwd = $(this).attr('data-fwd');

                $('#ponomor').val(idku);
                $('#idmasterfwd').val(idfwd);
            });

            $('body').on('click', '#detailbtn', function() {
                $('#modal-detailallocation').modal({
                    show: true,
                    backdrop: 'static'
                });
                let pono = $(this).attr('data-id');
                let idfwd = $(this).attr('data-fwd');
                $.ajax({
                    url: "<?php echo route('allocation_detail'); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: pono,
                        idfwd: idfwd
                    },
                }).done(function(data) {
                    console.log('data :>> ', data.data);
                    let dataku = data.data.datadetail;

                    $('#datapo').empty();

                    // var tot = 0;
                    // length = poku.length;
                    html =
                        '<table border="1" style="width:100%"><tr><th>PO Number</th><th>Id Line</th><th>Material</th><th>Color</th><th>Size</th><th>Qty PO</th></tr>';
                    for (let index = 0; index < dataku.length; index++) {
                        // let nullku;

                        // if (poku[index].qtyall == null) {
                        //     nullku = poku[index].qtypo;
                        // } else if (poku[index].qtyall == poku[index].qtypo) {
                        //     nullku = '0';
                        // } else {
                        //     nullku = poku[index].qtypo - poku[index].qtyall;
                        // }

                        // tot = tot + Number(poku[index].qtypo);
                        html +=
                            '<tr><td>' + dataku[index].po_nomor + '</td> <td>' + dataku[index]
                            .line_id + '</td><td>' + dataku[index].matcontents + '</td><td>' +
                            dataku[index]
                            .colorcode + '</td><td>' + dataku[index].size + '</td><td>' + dataku[
                                index].qtypo + '</td></tr>';
                    }
                    html += "</table>";
                    $('#datapo').html(html);


                    $('#namafwddetail').val(dataku[0].name);
                })
            });

            function checkqtyall() {

                $('.checkall').change(function(e) {
                    if (this.checked) {
                        $('.trigerinput').prop('disabled', false);
                        $('input[type="checkbox"]').prop('checked', true);
                    } else {
                        $('.trigerinput').val('');
                        $('.trigerinput').prop('disabled', true);
                        $('input[type="checkbox"]').prop('checked', false);
                    }
                });

                for (let index = 0; index < Number(length); index++) {
                    $('.check-' + index).change(function(e) {
                        if (this.checked) {
                            console.log('objectsijine :>> ', 'isChecked');
                            $('.cekinput-' + index).prop('disabled', false);
                            // }
                        } else {
                            console.log('objectsijine :>> ', 'notChecked');
                            $('.cekinput-' + index).val('');
                            $('.cekinput-' + index).prop('disabled', true);
                        }
                    });
                }
            }

            $('#datafwd').select2({
                placeholder: '-- Choose Forwarder --',
                ajax: {
                    url: "<?php echo route('get_forwarder'); ?>",
                    dataType: 'json',
                    delay: 500,
                    type: 'post',
                    data: function(params) {
                        var query = {
                            q: params.term,
                            // page: params.page || 1,
                            _token: $('meta[name=csrf-token]').attr('content')
                        }
                        return query;
                    },
                    processResults: function(data, params) {
                        return {

                            results: $.map(data, function(item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                    selected: true,
                                }
                            }),
                        };
                    },
                    cache: true
                }
            });

            $('#btnsubmit').click(function(e) {
                let pono = $('#ponomor').val();
                let idku = $('#idmasterfwd').val();
                let fwd = $('#datafwd').val();

                if (fwd == '' || fwd == []) {
                    Swal.fire({
                        title: 'Information!',
                        text: 'Forwarder Not Be Empty, please input data',
                        type: 'warning'
                    });
                    return;
                } else {
                    $.ajax({
                        type: "post",
                        url: "<?php echo e(route('allocation_movefwd')); ?>",
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            pono: pono,
                            idmasterfwd: idku,
                            datamasterfwd: fwd
                        },
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {

                                $('#modal-movefwd').modal('hide');
                                table.ajax.reload();
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
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Transaksi\Resources/views/data_allocation.blade.php ENDPATH**/ ?>