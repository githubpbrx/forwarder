@extends('system::template/master')
@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary" id="add_user">Add User</button>
        </div>
        <div class="card-body">
            <table id="serverside" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    {{-- modal for add user --}}
    <div class="modal fade" id="adduser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Add User</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" id="idku">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Email User Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="emailuser" name="emailuser"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Name Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="namefwd" name="namefwd"
                                            autocomplete="off" readonly>
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

    {{-- modal for detail reject --}}
    <div class="modal fade" id="detailreject">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail Reject User</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Email User Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="emailuserreject"
                                            name="emailuserreject" autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Name Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="namefwdreject" name="namefwdreject"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Description</label>
                                    <div class="col-sm-12">
                                        <textarea name="deskripsi" id="deskripsi" cols="60" rows="3" disabled></textarea>
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
    {{-- ----------------- /.modal content ----------------- --}}
@endsection

@section('script')
    <script type="text/javascript">
        var oTable = $('#serverside').DataTable({
            order: [],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url('privilege/fwd_access/datatablefwd') }}',
            },

            "fnCreatedRow": function(row, data, index) {
                $('td', row).eq(0).html(index + 1);
            },

            columns: [
                // data is for view, name is for real value
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'privilege_user_nik',
                    name: 'privilege_user_nik'
                },
                {
                    data: 'privilege_user_name',
                    name: 'privilege_user_name'
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

        $('#add_user').click(function(e) {
            let data = oTable.data();

            $('#namefwd').val(data[0]['privilege_user_name']);
            $('#namefinance').val(data[0]['nama_finance']);
            $('#nikfinance').val(data[0]['nik_finance']);
            $('#emailfinance').val(data[0]['email_finance']);
            $('#adduser').modal({
                show: true,
                backdrop: 'static'
            });
        });

        function IsEmail(email) {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test(email)) {
                return false;
            } else {
                return true;
            }
        }

        $('body').on('click', '#edituserfwd', function() {
            console.log('objectproses :>> ', 'klik');
            $('#adduser').modal({
                show: true,
                backdrop: 'static'
            });
            let idku = $(this).attr('data-id');

            $.ajax({
                url: "{!! url('privilege/fwd_access/edituserfwd') !!}",
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    id: idku,
                },
            }).done(function(data) {
                let dataku = data.data;
                console.log('data :>> ', dataku);

                $('#idku').val(dataku.privilege_id);
                $('#emailuser').val(dataku.privilege_user_nik);
                $('#namefwd').val(dataku.privilege_user_name);
                $('#namefinance').val(dataku.namafinance);
                $('#nikfinance').val(dataku.nikfinance);
                $('#emailfinance').val(dataku.emailfinance);
            })
        });

        $('#submitbtn').click(function(e) {
            let id = $('#idku').val();
            let emailuser = $('#emailuser').val();
            let namefwd = $('#namefwd').val();
            let namefinane = $('#namefinance').val();
            let nikfinance = $('#nikfinance').val();
            let emailfinance = $('#emailfinance').val();
            console.log('id :>> ', id);
            if (emailuser == null || emailuser == '') {
                Swal.fire({
                    title: 'Information',
                    text: ' Email User Can Not Empty!!',
                    type: 'warning'
                });
                return;
            } else if (IsEmail(emailuser) == false) {
                Swal.fire({
                    title: 'Information',
                    text: ' Please use format email in Email User',
                    type: 'warning'
                });
                return;
            } else {
                $.ajax({
                    type: "post",
                    url: (id == '') ? "{{ url('privilege/fwd_access/saveuserfwd') }}" :
                        "{{ url('privilege/fwd_access/updateuserfwd') }}",
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: id,
                        emailuser: emailuser,
                        namefwd: namefwd,
                        namefinane: namefinane,
                        nikfinance: nikfinance,
                        emailfinance: emailfinance,
                    },
                    dataType: "json",
                    success: function(response) {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {
                            // $('#modal_tolak').modal('hide');
                            // $('#approvalfwd').modal('hide');
                            // table.ajax.reload();
                            (response.status == 'success') ? window.location
                                .replace("{{ url('privilege/accessfwd') }}"):
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

        $('body').on('click', '#deleteuser', function() {
            console.log('objectdelete :>> ', 'klik');
            let idku = $(this).attr('data-id');
            let url = '{!! url('privilege/fwd_access/deleteuserfwd') !!}' + '/' + idku;
            // url = url.replace('params', idku);
            // console.log('idku :>> ', url);
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
                                // oTable.ajax.reload();
                                (response.status == 'success') ? window
                                    .location
                                    .replace("{{ url('privilege/accessfwd') }}"):
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

        $('body').on('click', '#detailuser', function() {
            console.log('objectproses :>> ', 'klik');
            $('#detailreject').modal({
                show: true,
                backdrop: 'static'
            });
            let idku = $(this).attr('data-id');

            $.ajax({
                url: "{!! url('privilege/fwd_access/detailuserfwd') !!}",
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    id: idku,
                },
            }).done(function(data) {
                let dataku = data.data;
                console.log('data :>> ', dataku);

                $('#emailuserreject').val(dataku.privilege_user_nik);
                $('#namefwdreject').val(dataku.privilege_user_name);
                $('#deskripsi').val(dataku.ket_tolak);
            })
        });
    </script>
@endsection
