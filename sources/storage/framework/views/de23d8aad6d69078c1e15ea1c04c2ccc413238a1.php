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
                                <th>Name Forwarder</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="addforwarder">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Add Data Forwarder</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        <?php echo e(csrf_field()); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Name Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="namefwd" name="namefwd"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Email Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="emailfwd" name="emailfwd"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Position</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="position" name="position"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Company</label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="company" name="company"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Address</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="address" name="address"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">NIK Finance</label>
                                    <div class="col-sm-12">
                                        <input type="number" min="0" class="form-control" id="nikfinance"
                                            name="nikfinance" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Name Finance</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="namefinance" name="namefinance"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Email Finance</label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="emailfinance" name="emailfinance"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary pull-left" id="submitbtn">Submit</button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="editforwarder">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Edit Data Forwarder</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" name="idku" id="idku">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Name Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="namefwdedit" name="namefwdedit"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Email Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="emailfwdedit" name="emailfwdedit"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Position</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="positionedit" name="positionedit"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Company</label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="companyedit" name="companyedit"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Address</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="addressedit" name="addressedit"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">NIK Finance</label>
                                    <div class="col-sm-12">
                                        <input type="number" min="0" class="form-control" id="nikfinanceedit"
                                            name="nikfinanceedit" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Name Finance</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="namefinanceedit"
                                            name="namefinanceedit" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Email Finance</label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="emailfinanceedit"
                                            name="emailfinanceedit" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary pull-left" id="submitedit">Submit</button>
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

            function notifalert(params) {
                Swal.fire({
                    title: 'Information',
                    text: params + ' is required, please input data',
                    type: 'warning'
                });
                return;
            }

            function IsEmail(email) {
                var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (!regex.test(email)) {
                    return false;
                } else {
                    return true;
                }
            }

            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(route('list_forwarder')); ?>"
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'namefwd',
                        name: 'namefwd'
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
                $('#idku').val('');
                $('#namefwd').val('');
                $('#position').val('');
                $('#company').val('');
                $('#address').val('');
                $('#emailfwd').val('');
                $('#namefinance').val('');
                $('#nikfinance').val('');
                $('#emailfinance').val('');
                $('#addforwarder').modal({
                    show: true,
                    backdrop: 'static'
                });
            });

            $('#submitbtn').click(function() {
                console.log('objectkuu :>> ', 'klik');
                let id = $('#idku').val();
                let namefwd = $('#namefwd').val();
                let position = $('#position').val();
                let company = $('#company').val();
                let address = $('#address').val();
                let emailfwd = $('#emailfwd').val();
                let namefinance = $('#namefinance').val();
                let nikfinance = $('#nikfinance').val();
                let emailfinance = $('#emailfinance').val();

                if (namefwd == '' || namefwd == null) {
                    notifalert('Name Forwarder');
                } else if (position == '' || position == null) {
                    notifalert('Position Forwarder');
                } else if (company == '' || company == null) {
                    notifalert('Company Forwarder');
                } else if (address == '' || address == null) {
                    notifalert('Address Forwarder');
                } else if (emailfwd == '' || emailfwd == null) {
                    notifalert('Email Forwarder');
                } else if (IsEmail(emailfwd) == false) {
                    Swal.fire({
                        title: 'Information',
                        text: ' Please use format email in Email Forwarder',
                        type: 'warning'
                    });
                    return;
                } else if (nikfinance == '' || nikfinance == null) {
                    notifalert('NIK Finance');
                } else if (namefinance == '' || namefinance == null) {
                    notifalert('Name Finance');
                } else if (emailfinance == '' || emailfinance == null) {
                    notifalert('Email Finance');
                } else if (IsEmail(emailfinance) == false) {
                    Swal.fire({
                        title: 'Information',
                        text: ' Please use format email in Email Finance',
                        type: 'warning'
                    });
                    return;
                } else {
                    $.ajax({
                        url: "<?php echo route('masterfwd_save'); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            id: id,
                            namefwd: namefwd,
                            position: position,
                            company: company,
                            address: address,
                            emailfwd: emailfwd,
                            namefinance: namefinance,
                            nikfinance: nikfinance,
                            emailfinance: emailfinance
                        },
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                // $('#modal_tolak').modal('hide');
                                // $('#approvalfwd').modal('hide');
                                // table.ajax.reload();
                                (response.status == 'success') ? window.location
                                    .replace("<?php echo e(route('masterforwarder')); ?>"):
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

            $('body').on('click', '#editfwd', function() {
                console.log('objectproses :>> ', 'klik');
                $('#namefwdedit').val('');
                $('#positionedit').val('');
                $('#companyedit').val('');
                $('#addressedit').val('');
                $('#emailfwdedit').val('');
                $('#emailfinanceedit').val('');
                $('#nikfinanceedit').val('');
                $("#namefinanceedit").val('');

                $('#editforwarder').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "<?php echo route('masterfwd_edit'); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('data :>> ', data.data);
                    let datafwd = data.data.datafwd;
                    let datapriv = data.data.datapriv;

                    if (datapriv != null) {
                        $('#emailfwdedit').prop('readonly', true);
                    } else {
                        $('#emailfwdedit').prop('readonly', false);
                    }

                    $('#idku').val(datafwd.id);
                    $('#namefwdedit').val(datafwd.name);
                    $('#positionedit').val(datafwd.position);
                    $('#companyedit').val(datafwd.company);
                    $('#addressedit').val(datafwd.address);
                    $('#emailfwdedit').val(datapriv.privilege_user_nik);
                    let nikk = $('#nikfinanceedit').val(datapriv.nikfinance);
                    $('#emailfinanceedit').val(datapriv.emailfinance);

                    let urlnik = '<?php echo route('getkaryawan', ['params']); ?>'
                    urlnik = urlnik.replace('params', nikk[0]['value'])
                    $.ajax({
                        url: urlnik,
                        type: 'GET',
                        success: function(data) {
                            console.log('datanik :>> ', data);
                            $("#namefinanceedit").val(data.data);
                        }
                    });
                })
            });

            $('#submitedit').click(function() {
                console.log('objectkuu :>> ', 'klik');
                let id = $('#idku').val();
                let namefwdedit = $('#namefwdedit').val();
                let position = $('#positionedit').val();
                let company = $('#companyedit').val();
                let address = $('#addressedit').val();
                let emailfwd = $('#emailfwdedit').val();
                let namefinance = $('#namefinanceedit').val();
                let nikfinance = $('#nikfinanceedit').val();
                let emailfinance = $('#emailfinanceedit').val();

                if (namefwdedit == '' || namefwdedit == null) {
                    notifalert('Name Forwarder');
                } else if (emailfwd == '' || emailfwd == null) {
                    notifalert('Email Forwarder');
                } else if (IsEmail(emailfwd) == false) {
                    Swal.fire({
                        title: 'Information',
                        text: ' Please use format email in Email Forwarder',
                        type: 'warning'
                    });
                    return;
                } else if (position == '' || position == null) {
                    notifalert('Position Forwarder');
                } else if (company == '' || company == null) {
                    notifalert('Company Forwarder');
                } else if (address == '' || address == null) {
                    notifalert('Address Forwarder');
                } else if (nikfinance == '' || nikfinance == null) {
                    notifalert('NIK Finance');
                } else if (namefinance == '' || namefinance == null) {
                    notifalert('Name Finance');
                } else if (emailfinance == '' || emailfinance == null) {
                    notifalert('Email Finance');
                } else if (IsEmail(emailfinance) == false) {
                    Swal.fire({
                        title: 'Information',
                        text: ' Please use format email in Email Finance',
                        type: 'warning'
                    });
                    return;
                } else {
                    $.ajax({
                        url: "<?php echo route('masterfwd_update'); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            id: id,
                            namefwdedit: namefwdedit,
                            positionedit: position,
                            companyedit: company,
                            addressedit: address,
                            emailfwdedit: emailfwd,
                            namefinanceedit: namefinance,
                            nikfinanceedit: nikfinance,
                            emailfinanceedit: emailfinance
                        },
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                // $('#modal_tolak').modal('hide');
                                // $('#approvalfwd').modal('hide');
                                // table.ajax.reload();
                                (response.status == 'success') ? window.location
                                    .replace("<?php echo e(route('masterforwarder')); ?>"):
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

            $('body').on('click', '#delbtn', function() {
                console.log('objectdelete :>> ', 'klik');
                let idku = $(this).attr('data-id');
                let url = '<?php echo route('masterfwd_delete', ['params']); ?>';
                url = url.replace('params', idku);
                console.log('idku :>> ', url);
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
                            url: url,
                            dataType: "JSON",
                            success: function(response) {
                                Swal.fire({
                                    title: response.title,
                                    text: response.message,
                                    type: (response.status != 'error') ?
                                        'success' : 'error'
                                }).then(() => {
                                    (response.status == 'success') ? window
                                        .location
                                        .replace(
                                            "<?php echo e(route('masterforwarder')); ?>"):
                                        ''
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

            //for add
            $('#nikfinance').keyup(function(e) {
                var nik = $("#nikfinance").val();
                let url = '<?php echo route('getkaryawan', ['params']); ?>'
                url = url.replace('params', nik)
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $("#namefinance").val(data.data);
                    }
                });
            });

            //for edit
            $('#nikfinanceedit').keyup(function(e) {
                var nik = $("#nikfinanceedit").val();
                let url = '<?php echo route('getkaryawan', ['params']); ?>'
                url = url.replace('params', nik)
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $("#namefinanceedit").val(data.data);
                    }
                });
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/Master\Resources/views/masterforwarder.blade.php ENDPATH**/ ?>