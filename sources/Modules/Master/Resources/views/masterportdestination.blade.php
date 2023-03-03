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

    {{-- Modal Add POD --}}
    <div class="modal fade" id="modalpod">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Add Data Port Of Destination</span></h4>
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
                                        <input type="text" class="form-control" id="podcode" name="podcode"
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
                                        <input type="text" class="form-control" id="podname" name="podname"
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
    {{-- End Add Edit POD --}}

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
                    url: "{{ route('list_pod') }}"
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
                $('#modalpod').modal({
                    show: true,
                    backdrop: 'static'
                });

                $('#idku').val('');
                $('#podcode').val('');
                $('#podname').val('');
                $('#modaltitle').html('Add Data Port Of Destination');
            });

            $('body').on('click', '#editdata', function() {
                $('#modalpod').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('masterpod_edit') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    let dataku = data.data;

                    $('#idku').val(dataku.id_portdestination);
                    $('#podcode').val(dataku.code_port);
                    $('#podname').val(dataku.name_port);
                    $('#modaltitle').html('Edit Data Port Of Destination');
                })
            });

            $('#submit').click(function() {
                let id = $('#idku').val();
                let podcode = $('#podcode').val();
                let podname = $('#podname').val();

                if (podcode == null || podcode == '') {
                    notifalert('Port Code')
                } else if (podname == null || podname == '') {
                    notifalert('Port Name')
                } else {
                    $.ajax({
                        url: (id == null || id == '') ? "{!! route('masterpod_add') !!}" :
                            "{!! route('masterpod_update') !!}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            id: id,
                            codeport: podcode,
                            nameport: podname,
                        },
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                $('#modalpod').modal('hide');
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
                            url: "{!! url('master/pod/deletepod') !!}" + "/" + idku,
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
