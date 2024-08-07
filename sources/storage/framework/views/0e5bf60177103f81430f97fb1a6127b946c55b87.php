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
                            
                            <br>
                            <div class="table-responsive">
                                <table id="dataTables" class="table table-bordered table-striped table-hover"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <center>PO</center>
                                            </th>
                                            <th>
                                                <center>Material</center>
                                            </th>
                                            <th>
                                                <center>Status</center>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">PO</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Material</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="material" name="material"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Material Desc</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="matdesc" name="matdesc"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Color Code</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="colorcode" name="colorcode"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Size</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="size" name="size"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Quantity Item</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="qtypo" name="qtypo"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Price</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="price" name="price"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Supplier</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="supplier" name="supplier"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Plant</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="plant" name="plant"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Style</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="style" name="style"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Buyer</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="buyer" name="buyer"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
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


            var tabel = $('#dataTables').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(url('report/allocation/datatables')); ?>",
                    // data: function(d) {
                    //     d.po = $('#datapo').val()
                    // }
                },
                columns: [{
                        data: 'po',
                        name: 'po'
                    },
                    {
                        data: 'material',
                        name: 'material'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action'
                    // },
                ]
            });

            $('#dataTables').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('#search').click(function(e) {
                tabel.draw();
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
                    console.log('data :>> ', data.data);
                    let datapo = data.data.dataku;

                    $('#nomorpo').val(datapo.pono);
                    $('#material').val(datapo.matcontents);
                    $('#matdesc').val(datapo.itemdesc);
                    $('#qtypo').val(datapo.qtypo);
                    let curr = datapo.curr;
                    let pr = datapo.price;
                    $('#price').val(pr + ' ' + curr);
                    $('#supplier').val(datapo.nama);
                    $('#plant').val(datapo.plant);
                    $('#style').val(datapo.style);
                    $('#buyer').val(datapo.buyer);
                })
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Report\Resources/views/historyallocation.blade.php ENDPATH**/ ?>