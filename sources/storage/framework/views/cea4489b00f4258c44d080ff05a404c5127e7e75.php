<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('link_href'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card card-primary">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body" style="overflow-y: auto;">
                            <div class="row mb-2">
                                <div>
                                    <label class="control-label">PO :</label>
                                    <select class="select2" style="width: 100%;" name="datapo" id="datapo">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="ml-2">
                                    <label class="control-label">Forwarder :</label>
                                    <select class="select2" style="width: 100%;" name="datafwd" id="datafwd">
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
                                    <button type="button"class="btn btn-warning form-control" id="btndownload">Download
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
                                                    <center>Forwarder</center>
                                                </th>
                                                <th>
                                                    <center>Date Allocation</center>
                                                </th>
                                                <th>
                                                    <center>PI Delivery</center>
                                                </th>
                                                <th>
                                                    <center>Date Booking</center>
                                                </th>
                                                <th>
                                                    <center>Date Confirm</center>
                                                </th>
                                                <th>
                                                    <center>Date Shipment</center>
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

    
    <div class="modal fade" id="detailall">
        <div class="modal-dialog" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail Report Ready Allocation</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <div id="formdetail"></div>
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
                url: "<?php echo route('report_getchartalokasi'); ?>",
                beforeSend: function(response) {
                    $('#mychartpo').html(
                        '<h6 class="text-center mt-5"><span class="fa-stack text-info"><i class="fas fa-circle fa-stack-2x"></i><i class="fas fa-hourglass-half fa-spin fa-stack-1x fa-inverse"></i></span> Please wait! Checking Chart Allocation...</h6>'
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
                    url: "<?php echo e(url('report/alokasi/search')); ?>",
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    data: function(d) {
                        d.pono = $('#datapo').val(),
                            d.idmasterfwd = $('#datafwd').val(),
                            d.idsupplier = $('#datasupp').val(),
                            d.periode = $('#periode').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
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
                        data: 'forwarder',
                        name: 'forwarder'
                    },
                    {
                        data: 'dateallocation',
                        name: 'dateallocation'
                    },
                    {
                        data: 'pidelivery',
                        name: 'pidelivery'
                    },
                    {
                        data: 'datebook',
                        name: 'datebook'
                    },
                    {
                        data: 'dateconfirm',
                        name: 'dateconfirm'
                    },
                    {
                        data: 'dateshipment',
                        name: 'dateshipment'
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
                    url: "<?php echo route('report_getchartalokasi'); ?>",
                    data: {
                        pono: $('#datapo').val(),
                        idmasterfwd: $('#datafwd').val(),
                        idsupplier: $('#datasupp').val(),
                        periode: $('#periode').val()
                    },
                    beforeSend: function(response) {
                        $('#mychartpo').html(
                            '<h6 class="text-center mt-5"><span class="fa-stack text-info"><i class="fas fa-circle fa-stack-2x"></i><i class="fas fa-hourglass-half fa-spin fa-stack-1x fa-inverse"></i></span> Please wait! Checking Chart Allocation...</h6>'
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
                    url: "<?php echo route('report_getpoalokasi'); ?>",
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
                                    text: item.po_nomor,
                                    id: item.po_nomor,
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

            $('#datafwd').select2({
                placeholder: '-- Choose Forwarder --',
                ajax: {
                    url: "<?php echo route('report_getfwdalokasi'); ?>",
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
                                    text: item.name,
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

            $('#datasupp').select2({
                placeholder: '-- Choose Supplier --',
                ajax: {
                    url: "<?php echo route('report_getsupplieralokasi'); ?>",
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

            $('body').on('click', '#detailalokasi', function() {
                let idku = $(this).attr('data-id');
                let idmasterfwd = $(this).attr('data-idmasterfwd');
                $.ajax({
                    url: "<?php echo route('report_detailalokasi'); ?>",
                    type: 'POST',
                    // dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                        idmasterfwd: idmasterfwd
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
                        $('#detailall').modal({
                            show: true,
                            backdrop: 'static'
                        });
                        $('#formdetail').html(data);
                        swal.close();
                    }
                });
            });

            $("#btndownload").click(function(e) {
                let datapo = $('#datapo').val();
                let datafwd = $('#datafwd').val();
                let datasupp = $('#datasupp').val();
                let dataper = $('#periode').val();

                var query = {
                    'pono': datapo,
                    'idmasterfwd': datafwd,
                    'idsupplier': datasupp,
                    'periode': dataper,
                }
                var url = "<?php echo e(url('report/alokasi/getexcelalokasiall')); ?>?" + $.param(query);
                window.open(url, '_blank');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Report\Resources/views/readyallocation/reportalokasi.blade.php ENDPATH**/ ?>