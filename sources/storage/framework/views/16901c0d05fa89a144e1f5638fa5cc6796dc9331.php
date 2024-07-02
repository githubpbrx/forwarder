<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('link_href'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-header">
                    <button class="btn btn-primary pull-right" id="adddata">Add Data</button>
                </div>
                <div class="card-body">
                    <table id="serverside" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Route Code</th>
                                <th>Route Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="modalroute">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Add Data Route</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        <input type="hidden" id="idku">
                        <?php echo e(csrf_field()); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Route Code</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="routecode" name="routecode"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Route Description</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="routedesc" name="routedesc"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary pull-left" id="submit">Submit</button>
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

            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(route('list_route')); ?>"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'routecode',
                        name: 'route_code'
                    },
                    {
                        data: 'routedesc',
                        name: 'route_desc'
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

            $('#adddata').click(function(e) {
                $('#modalroute').modal({
                    show: true,
                    backdrop: 'static'
                });

                $('#idku').val('');
                $('#routecode').val('');
                $('#routedesc').val('');
                $('#modaltitle').html('Add Data Route');
            });

            $('body').on('click', '#editdata', function() {
                $('#modalroute').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "<?php echo route('masterroute_edit'); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    let dataku = data.data;

                    $('#idku').val(dataku.id_route);
                    $('#routecode').val(dataku.route_code);
                    $('#routedesc').val(dataku.route_desc);
                    $('#modaltitle').html('Edit Data Route');
                })
            });

            $('#submit').click(function() {
                let id = $('#idku').val();
                console.log('id :>> ', id);
                let routecode = $('#routecode').val();
                let routedesc = $('#routedesc').val();

                if (routecode == '' || routecode == null) {
                    notifalert('Route Code')
                } else if (routedesc == '' || routedesc == null) {
                    notifalert('Route Description')
                } else {
                    $.ajax({
                        url: (id == null || id == '') ? "<?php echo route('masterroute_add'); ?>" :
                            "<?php echo route('masterroute_update'); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            id: id,
                            routecode: routecode,
                            routedesc: routedesc,
                        },
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                $('#modalroute').modal('hide');
                                oTable.ajax.reload();
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

            $('body').on('click', '#delbtn', function() {
                let idku = $(this).attr('data-id');
                // let url = '<?php echo route('masterhscode_delete', ['params']); ?>';
                // url = url.replace('params', idku);

                Swal.fire({
                    title: 'Validation delete data!',
                    text: 'Are you sure you want to delete the data  ?',
                    type: 'question',
                    showConfirmButton: true,
                    showCancelButton: true,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "GET",
                            url: "<?php echo url('master/route/deleteroute'); ?>" + "/" + idku,
                            dataType: "JSON",
                            success: function(response) {
                                Swal.fire({
                                    title: response.title,
                                    text: response.message,
                                    type: (response.status != 'error') ?
                                        'success' : 'error'
                                }).then(() => {
                                    oTable.ajax.reload();
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

        });

        function notifalert(params) {
            Swal.fire({
                title: 'Information',
                text: params + ' is required, please input data',
                type: 'warning'
            });
            return;
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Master\Resources/views/masterroute.blade.php ENDPATH**/ ?>