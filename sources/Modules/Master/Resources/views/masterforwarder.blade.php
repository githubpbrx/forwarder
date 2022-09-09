@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
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
                    {{-- <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Add Forwarder --}}
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
                        {{ csrf_field() }}
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

    {{-- Modal Edit Forwarder --}}
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
                        {{ csrf_field() }}
                        <input type="hidden" name="idku" id="idku">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Name Forwarder</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="namefwdedit" name="namefwdedit"
                                    autocomplete="off">
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

@endsection

@section('script_src')
@endsection

@section('script')
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
                    url: "{{ route('list_forwarder') }}"
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
                let emailfwd = $('#emailfwd').val();
                let namefinance = $('#namefinance').val();
                let nikfinance = $('#nikfinance').val();
                let emailfinance = $('#emailfinance').val();

                if (namefwd == '' || namefwd == null) {
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
                        url: "{!! route('masterfwd_save') !!}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            id: id,
                            namefwd: namefwd,
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
                                    .replace("{{ route('masterforwarder') }}"):
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
                $('#editforwarder').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('masterfwd_edit') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('data :>> ', data.data);
                    let datafwd = data.data;

                    $('#idku').val(datafwd.id);
                    $('#namefwdedit').val(datafwd.nama);
                })
            });

            $('#submitedit').click(function() {
                console.log('objectkuu :>> ', 'klik');
                let id = $('#idku').val();
                let namefwdedit = $('#namefwdedit').val();

                if (namefwdedit == '' || namefwdedit == null) {
                    notifalert('Name Forwarder');
                } else {
                    $.ajax({
                        url: "{!! route('masterfwd_update') !!}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            id: id,
                            namefwdedit: namefwdedit,
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
                                    .replace("{{ route('masterforwarder') }}"):
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
                let url = '{!! route('masterfwd_delete', ['id']) !!}';
                url = url.replace('id', idku);
                console.log('idku :>> ', url);
                Swal.fire({
                    title: 'Validasi hapus data!',
                    text: 'Apakah anda yakin akan menghapus semua data  ?',
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
                                            "{{ route('masterforwarder') }}"):
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

            $('#nikfinance').keyup(function(e) {
                var nik = $("#nikfinance").val();
                let url = '{!! route('getkaryawan', ['id']) !!}'
                url = url.replace('id', nik)
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $("#namefinance").val(data.data);
                    }
                });
            });

        });
    </script>
@endsection
