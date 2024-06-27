<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('link_href'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body" style="overflow-y: auto;">
                            <div class="row">
                                <div>
                                    <label class="control-label">PO :</label>
                                    <select class="select2" style="width: 100%;" name="datapo" id="datapo">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="ml-2">
                                    <label class="control-label">Supplier :</label>
                                    <select class="select2" style="width: 100%;" name="datasupp" id="datasupp"
                                        multiple="multiple">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="ml-2">
                                    <label class="control-label">Periode :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="periode" class="form-control float-right"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <label class="control-label">&nbsp;</label>
                                    <a href="#" type="button" id="search" class="btn btn-info form-control"
                                        data-value="klik">Search</a>
                                </div>
                                <div class="ml-auto">
                                    <label class="control-label">&nbsp;</label>
                                    <button type="button" class="btn btn-warning form-control" id="btndownload">Download
                                        Excel</button>
                                </div>
                            </div>
                            <div class="row mt-3 mb-2">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body overflow-auto">
                                            <div id="mychartpo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="dataTables" class="table table-bordered table-striped table-hover"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <center>NO</center>
                                                </th>
                                                <th>
                                                    <center>PO</center>
                                                </th>
                                                <th>
                                                    <center>Date</center>
                                                </th>
                                                <th>
                                                    <center>Amount</center>
                                                </th>
                                                <th>
                                                    <center>Supplier</center>
                                                </th>
                                                <th>
                                                    <center>Shipmode</center>
                                                </th>
                                                <th>
                                                    <center>Status</center>
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
    </div>

    
    <div class="modal fade" id="detailpomodal">
        <div class="modal-dialog" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail PO</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <form action="#" class="form-horizontal">
                        <?php echo e(csrf_field()); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">PO</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Supplier</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="supplier" name="supplier" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
                        <div class="row">
                            <div class="col-md-12">
                                <div id="formdetailpo"></div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script_src'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#periode').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#periode').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('#periode').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $.ajax({
                type: "POST",
                url: "<?php echo route('report_getchart'); ?>",
                beforeSend: function(response) {
                    $('#mychartpo').html(
                        '<h6 class="text-center mt-5"><span class="fa-stack text-info"><i class="fas fa-circle fa-stack-2x"></i><i class="fas fa-hourglass-half fa-spin fa-stack-1x fa-inverse"></i></span> Please wait! Checking Chart PO...</h6>'
                    );
                },
                success: function(response) {
                    $('#mychartpo').html(response);
                }
            });

            var tabel = $('#dataTables').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(url('report/po/search')); ?>",
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    data: function(d) {
                        d.po = $('#datapo').val(),
                            d.supplier = $('#datasupp').val(),
                            d.periode = $('#periode').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex'
                    }, {
                        data: 'po',
                        name: 'po'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'shipmode',
                        name: 'shipmode'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
                // ,
                // createdRow: function(row, data, index, cells) {
                //     $(cells[1]).css('color', 'white')

                //     if (data.kyc == 'confirm') {
                //         $(cells[1]).css('background-color', '#42ba96')
                //     } else {
                //         $(cells[1]).css('background-color', '#df4759')
                //     }
                // },
            });

            $('#dataTables').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('#search').click(function(e) {
                tabel.draw();
                $.ajax({
                    type: "POST",
                    url: "<?php echo route('report_getchart'); ?>",
                    data: {
                        po: $('#datapo').val(),
                        supplier: $('#datasupp').val(),
                        periode: $('#periode').val()
                    },
                    beforeSend: function(response) {
                        $('#mychartpo').html(
                            '<h6 class="text-center mt-5"><span class="fa-stack text-info"><i class="fas fa-circle fa-stack-2x"></i><i class="fas fa-hourglass-half fa-spin fa-stack-1x fa-inverse"></i></span> Please wait! Checking Chart PO...</h6>'
                        );
                    },
                    success: function(response) {
                        $('#mychartpo').html(response);
                    }
                });
                // table.ajax.reload();
            });

            $('#datapo').select2({
                placeholder: '-- Choose PO --',
                ajax: {
                    url: "<?php echo url('report/po/getpo'); ?>",
                    dataType: 'json',
                    delay: 500,
                    type: 'post',
                    data: function(params) {
                        var query = {
                            q: params.term,
                            page: params.page || 1,
                            _token: $('meta[name=csrf-token]').attr('content')
                        }
                        return query;
                    },
                    processResults: function(data, params) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.pono,
                                    id: item.pono,
                                    selected: true,
                                }
                            }),
                            pagination: {
                                more: data.to < data.total
                            }
                        };
                    },
                    cache: true
                }
            });

            $('#datasupp').select2({
                placeholder: '-- Choose Supplier --',
                ajax: {
                    url: "<?php echo url('report/po/getsupplier'); ?>",
                    dataType: 'json',
                    delay: 500,
                    type: 'post',
                    data: function(params) {
                        var query = {
                            q: params.term,
                            page: params.page || 1,
                            _token: $('meta[name=csrf-token]').attr('content')
                        }
                        return query;
                    },
                    processResults: function(data, params) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.id,
                                    selected: true,
                                }
                            }),
                            pagination: {
                                more: data.to < data.total
                            }
                        };
                    },
                    cache: true
                }
            });

            $('body').on('click', '#detailpo', function() {
                $('#detailpomodal').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "<?php echo route('report_detailpo'); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    let datapo = data.data;

                    $('#formdetailpo').empty();

                    html =
                        '<table border="1" style="width:100%"><tr><th>Material</th><th>Material Desc</th><th>Color</th><th>Size</th><th>Qty PO</th><th>Status</th></tr>';
                    for (let index = 0; index < datapo.length; index++) {
                        let status;
                        if (datapo[index].statusconfirm == 'confirm') {
                            status = 'Confirmed';
                        } else if (datapo[index].statusconfirm == 'reject') {
                            status = 'Rejected';
                        } else {
                            status = 'Unprocessed';
                        }

                        html +=
                            '<tr><td>' + datapo[index].matcontents + '</td><td>' + datapo[index]
                            .itemdesc + '</td><td>' + datapo[index].colorcode +
                            '</td><td>' + datapo[index].size + '</td><td>' + datapo[index].qtypo +
                            '</td><td>' + status +
                            '</td></tr>';
                    }

                    html += "</table>";
                    $('#formdetailpo').html(html);

                    $('#nomorpo').val(datapo[0].pono);
                    // $('#material').val(datapo.matcontents);
                    // $('#matdesc').val(datapo.itemdesc);
                    // $('#qtypo').val(datapo.qtypo);
                    // let curr = datapo.curr;
                    // let pr = datapo.price;
                    // $('#price').val(pr + ' ' + curr);
                    $('#supplier').val(datapo[0].nama);
                    // $('#plant').val(datapo.plant);
                    // $('#style').val(datapo.style);
                    // $('#buyer').val(datapo.buyer);
                })
            });

            $("#btndownload").click(function(e) {
                let datapo = $('#datapo').val();
                let datasupp = $('#datasupp').val();
                let dataper = $('#periode').val();

                var query = {
                    'po': datapo,
                    'supplier': datasupp,
                    'periode': dataper,
                }
                var url = "<?php echo e(url('report/po/getexcelpoall')); ?>?" + $.param(query);
                window.open(url, '_blank');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Report\Resources/views/outstandingpo/reportpo.blade.php ENDPATH**/ ?>