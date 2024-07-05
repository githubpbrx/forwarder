<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('link_href'); ?>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div>
                                    <label class="control-label">Choose PI Delivery Date :</label>
                                    <input type="text" class="form-control" id="selectdate" name="selectdate"
                                        autocomplete="off">
                                </div>
                                <div class="ml-2">
                                    <label class="control-label"> &nbsp; </label>
                                    <a href="#" type="button" id="search" class="btn btn-info form-control"
                                        data-value="klik">Search</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="serverside" class="table table-bordered table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <center><input type="checkbox" name="checkall" id="checkall"></center>
                                                </th>
                                                <th>
                                                    <center>PI Number</center>
                                                </th>
                                                <th>
                                                    <center>PO Number</center>
                                                </th>
                                                <th>
                                                    <center>PI Delivery</center>
                                                </th>
                                                <th>
                                                    <center>Supplier</center>
                                                </th>
                                                <th>
                                                    <center>Company</center>
                                                </th>
                                                <th>
                                                    <center>Status</center>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <a href="<?php echo e(route('dashcam')); ?>" type="button" class="btn btn-primary">Back</a>
                            <button type="button" class="btn btn-info" id="btnprocess">Process</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="formulir_po">
        <div class="modal-dialog" style="max-width: 80%">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Booking Detail</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="bodymodaldetail"></div>
            </div>
        </div>
    </div>
    

    <?php echo $__env->make('loading', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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

            $('#selectdate').datepicker({
                changeYear: true,
                changeMonth: true,
                // minDate: 0,
                dateFormat: "yy-m-dd",
                yearRange: "-100:+20",
            });

            // var repo = {};
            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(url('listpo')); ?>",
                    data: function(d) {
                        d.pidate = $('#selectdate').val()
                    }
                },
                columns: [{
                        data: 'cekbok',
                        name: 'cekbok',
                        orderable: false,
                        orderable: false,
                        // data: 'select-checkbox',
                        // className: 'select-checkbox',
                        // targets: 0
                        // 'targets': 0,
                        // 'checkboxes': {
                        //     'selectRow': true,
                        //     'selectCallback': function(cellNodes, isSelected) {
                        //         console.log('isSelected :>> ', isSelected);
                        //         var rowData = oTable.row($(cellNodes[0]).parent()).data();
                        //         // console.log('rowID :>> ', rowData);
                        //         //var rowPk = cellNodes.settings().rowId;
                        //         var rowID = rowData['action']; // rowData[rowPk];

                        //         if (isSelected === false) {
                        //             delete repo[rowID];
                        //         }

                        //         if (isSelected === true) {
                        //             repo[rowID] = rowData;
                        //         }
                        //     },
                        // }
                    },
                    {
                        data: 'pinomor',
                        name: 'pinomor'
                    },
                    {
                        data: 'listpo',
                        name: 'listpo'
                    },
                    {
                        data: 'pidel',
                        name: 'pidel'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'company',
                        name: 'company'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // },
                ],
                // select: {
                //     style: 'multi',
                //     selector: 'td:first-child'
                // },
                // 'order': [
                //     [1, 'asc']
                // ]
            });

            $('#serverside').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('#search').click(function(e) {
                oTable.draw();
                // table.ajax.reload();
            });

            $('#pidate').select2({
                placeholder: '-- Choose PI Date --',
                ajax: {
                    url: "<?php echo url('getpidate'); ?>",
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
                                    text: item.pideldate,
                                    id: item.pideldate,
                                    selected: true,
                                }
                            }),
                        };
                    },
                    cache: true
                }
            });

            var multi_id = [];
            $('#checkall').change(function(e) {
                $('#mycekbok').prop('checked', false);
                singleid = [];
                if (this.checked) {
                    $('input[type="checkbox"]').prop('checked', true);
                    let lengthtable = oTable.data().count();
                    let data = oTable.data();

                    for (let index = 0; index < Number(lengthtable); index++) {
                        let mydatapo = data[index]['id'] + '/' + data[index]['pino'];
                        multi_id.push(mydatapo);
                    }
                } else {
                    $('input[type="checkbox"]').prop('checked', false);
                    multi_id = [];
                }
            });

            var singleid = [];
            $('body').on('change', '#mycekbok', function() {
                if (multi_id.length != 0) {
                    $('#checkall').prop('checked', false);
                    var removeItem1 = this.value;
                    singleid = jQuery.grep(multi_id, function(value) {
                        return value != removeItem1;
                    });
                    multi_id = [];
                }

                if (this.checked) {
                    singleid.push(this.value)
                } else {
                    var removeItem = this.value;
                    singleid = jQuery.grep(singleid, function(value) {
                        return value != removeItem;
                    });
                }
            });

            $('#btnprocess').click(function(e) {
                $("#loading").show();

                let data;
                if (singleid.length == 0) {
                    data = multi_id;
                } else {
                    data = singleid;
                }

                if (singleid.length == 0 && multi_id.length == 0) {
                    Swal.fire({
                        title: 'Information',
                        text: 'Please Select PO Number',
                        type: 'warning'
                    });
                } else {
                    $.ajax({
                        type: "post",
                        url: "<?php echo route('form_po'); ?>",
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            dataku: data,
                        },
                        // dataType: "json",
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
                        success: function(response) {
                            $('#formulir_po').modal({
                                show: true,
                                backdrop: 'static'
                            });
                            $('#bodymodaldetail').html(response);
                            swal.close();
                        }
                    });
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/System\Resources/views/dashboard/listpo.blade.php ENDPATH**/ ?>