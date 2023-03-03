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
                                <th>Port Code</th>
                                <th>Port Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    {{-- <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Add POL --}}
    <div class="modal fade" id="modalpol">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Add Data Port Of Loading</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        <input type="hidden" id="idku">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Port Code</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="polcode" name="polcode"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Port Name</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="polname" name="polname"
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
    {{-- End Add Edit POL --}}

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

            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('list_pol') }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'codeport',
                        name: 'code_port'
                    },
                    {
                        data: 'nameport',
                        name: 'name_port'
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
                $('#modalpol').modal({
                    show: true,
                    backdrop: 'static'
                });

                $('#idku').val('');
                $('#polcode').val('');
                $('#polname').val('');
                $('#modaltitle').html('Add Data Port Of Loading');
            });

            $('body').on('click', '#editdata', function() {
                $('#modalpol').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('masterpol_edit') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    let dataku = data.data;

                    $('#idku').val(dataku.id_portloading);
                    $('#polcode').val(dataku.code_port);
                    $('#polname').val(dataku.name_port);
                    $('#modaltitle').html('Edit Data Port Of Loading');
                })
            });

            $('#submit').click(function() {
                let id = $('#idku').val();
                let polcode = $('#polcode').val();
                let polname = $('#polname').val();

                if (polcode == '' || polcode == null) {
                    notifalert('Port Code');
                } else if (polname == '' || polname == null) {
                    notifalert('Port Name');
                } else {
                    $.ajax({
                        url: (id == null || id == '') ? "{!! route('masterpol_add') !!}" :
                            "{!! route('masterpol_update') !!}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            id: id,
                            codeport: polcode,
                            nameport: polname,
                        },
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                $('#modalpol').modal('hide');
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
                // let url = '{!! route('masterhscode_delete', ['params']) !!}';
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
                            url: "{!! url('master/pol/deletepol') !!}" + "/" + idku,
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
@endsection
