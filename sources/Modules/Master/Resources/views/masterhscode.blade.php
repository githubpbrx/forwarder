@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-header d-flex">
                    <button class="btn btn-primary" id="adddata">Add Data</button>
                    <button class="btn btn-success ml-auto" id="importexcel"> <i class="fas fa-file-excel"></i> Import
                        Excel</button>
                </div>
                <div class="card-body">
                    <table id="serverside" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mat Contents</th>
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

    {{-- Modal Add HS Code --}}
    <div class="modal fade" id="modalhscode">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Add Data HS Code</span></h4>
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
                                    <label class="col-sm-12 control-label">Mat Contents</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="matcontents" name="matcontents"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End Add Edit HS Code --}}

    {{-- Modal Upload Excel HS Code --}}
    <div class="modal fade" id="modalexcelhscode">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Import Excel HS Code</span></h4>
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
                                    <label class="col-sm-12 control-label">Import Excel</label>
                                    <div class="col-sm-12">
                                        <input type="file" accept=".xlsx" class="form-control" id="customFile">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" id="downloadtemplate" target="_BLANK" type="button"
                        class="btn btn-info mr-auto">Download Template</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitexcel">Submit</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End Import Excel HS Code --}}

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
                        data: 'matcontents',
                        name: 'matcontents'
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

            $('#adddata').click(function(e) {
                $('#modalhscode').modal({
                    show: true,
                    backdrop: 'static'
                });

                $('#idku').val('');
                $('#matcontents').val('');
                $('#hscode').val('');
                $('#modaltitle').html('Add Data HS Code');
            });

            $('#importexcel').click(function(e) {
                $('#modalexcelhscode').modal({
                    show: true,
                    backdrop: 'static'
                });
                $('#modaltitle').html('Import HS Code');
            });

            $('body').on('click', '#editdata', function() {
                $('#modalhscode').modal({
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

                    $('#idku').val(dataku.id_hscode);
                    $('#matcontents').val(dataku.matcontent);
                    $('#hscode').val(dataku.hscode);
                    $('#modaltitle').html('Edit Data HS Code');
                })
            });

            $('#submit').click(function() {
                let id = $('#idku').val();
                let matcontents = $('#matcontents').val();
                let myhscode = $('#hscode').val();

                if (myhscode == '' || myhscode == null) {
                    notifalert('HS Code');
                } else if (matcontents == '' || matcontents == null) {
                    notifalert('Mat Content');
                } else {
                    $.ajax({
                        url: (id == null || id == '') ? "{!! route('masterhscode_add') !!}" :
                            "{!! route('masterhscode_update') !!}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            id: id,
                            matcontents: matcontents,
                            hscode: myhscode,
                        },
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                $('#modalhscode').modal('hide');
                                // $('#approvalfwd').modal('hide');
                                oTable.ajax.reload();
                                // (response.status == 'success') ? window.location
                                //     .replace("{{ route('masterhscode') }}"):
                                //     ''
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
                                    oTable.ajax.reload();
                                    // (response.status == 'success') ? window
                                    //     .location
                                    //     .replace("{{ route('masterhscode') }}"):
                                    //     ''

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

            $('#submitexcel').click(function(e) {
                var data = new FormData();
                data.append('excel', $('#customFile')[0].files[0]);
                $.ajax({
                    url: "{!! route('masterhscode_uploadexcel') !!}",
                    type: "POST",
                    cache: true,
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Importing ...',
                            html: 'Please wait data was importing!',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            backdrop: true,
                            onOpen: () => {
                                Swal.showLoading()
                            },
                        })
                    },
                    success: function(response) {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status !=
                                    'error') ?
                                'success' : 'error'
                        }).then((result) => {
                            $("#modalexcelhscode").modal('toggle');
                            window.location.reload();
                            Swal.close();
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
                })
            });

            $('#downloadtemplate').click(function(e) {
                var base = "{!! url('sources/storage/public/template_hscode.xlsx') !!}";
                $('#downloadtemplate').attr('href', base);
            });

        });
    </script>
@endsection
