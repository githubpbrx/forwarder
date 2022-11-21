@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                {{-- <div class="card-header">
                    <button class="btn btn-primary pull-right" id="adddata">Add Data</button>
                </div> --}}
                <div class="card-body">
                    <table id="serverside" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PO Number</th>
                                <th>HS Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    {{-- <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Forwarder --}}
    <div class="modal fade" id="edithscode">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Edit Data HS Code</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" name="idku" id="idku">
                        <input type="hidden" name="matcontents" id="matcontents">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">PO Number</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nopo" name="nopo"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">HS Code</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="hscode" name="hscode"
                                            autocomplete="off">
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

            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('list_hscode') }}"
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'pono',
                        name: 'pono'
                    },
                    {
                        data: 'hscode',
                        name: 'hscode'
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

            $('body').on('click', '#editdata', function() {
                console.log('objectproses :>> ', 'klik');
                $('#edithscode').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('masterhscode_edit') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    let dataku = data.data;
                    console.log('data :>> ', dataku);

                    $('#idku').val(dataku.id_hscode);
                    $('#matcontents').val(dataku.matcontents);
                    $('#nopo').val(dataku.pono);
                    $('#hscode').val(dataku.hscode);
                })
            });

            $('#submitedit').click(function() {
                console.log('objectkuu :>> ', 'klik');
                let id = $('#idku').val();
                let matcontents = $('#matcontents').val();
                let pono = $('#nopo').val();
                let myhscode = $('#hscode').val();

                if (myhscode == '' || myhscode == null) {
                    notifalert('HS Code');
                } else {
                    $.ajax({
                        url: "{!! route('masterhscode_update') !!}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            id: id,
                            matcontents: matcontents,
                            pono: pono,
                            hscode: myhscode,
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
                                    .replace("{{ route('masterhscode') }}"):
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
                let url = '{!! route('masterhscode_delete', ['params']) !!}';
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
                                    // oTable.ajax.reload();
                                    (response.status == 'success') ? window
                                        .location
                                        .replace("{{ route('masterhscode') }}"):
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

        });
    </script>
@endsection
