@extends('system::template/master')
@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-body">

            <table id="serverside" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Name File</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a>
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="listkyc">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Data KYC</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Name Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="namekyc" name="namekyc" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Name File KYC</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="namefilekyc" name="namefilekyc"
                                            readonly>
                                        <br>
                                        <a href="#" id="kycdownload" target="_BLANK" class="btn btn-info">Download
                                            File</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <br><br>
                                <div class="form-group">
                                    <button type="button" class="btnapproval btnconfirm btn btn-success"
                                        data-value="confirm">Confirm</button>
                                    <button type="button" class="btnapproval btn btn-danger"
                                        data-value="reject">Reject</button>
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

    {{-- Modal Tolak Approval --}}
    <div class="modal fade" id="modal_tolak">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Refuse Submission</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" id="form_tolak" method="GET">
                        @csrf
                        <div class="form-group">
                            <label>Reason</label>
                            <textarea name="tolak_alasan" id="tolak_alasan" class="form-control text-bullets" rows="3"
                                placeholder="Reason ..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button id="submittolak" type="button" class="btn btn-danger" form="form_tolak">Reject</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ----------------- /.modal content ----------------- --}}
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
                    url: "{{ route('list_kyc') }}"
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'namefile',
                        name: 'namefile'
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

            var idfwd;
            var datafile;
            $('body').on('click', '#processkyc', function() {
                $('#listkyc').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('form_kyc') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('object :>> ', data.data);
                    let datakyc = data.data.datakyc;

                    idfwd = datakyc.idmasterfwd;
                    datafile = datakyc.file_kyc;

                    $('#namekyc').val(datakyc.name_kyc);
                    $('#namefilekyc').val(datakyc.file_kyc);
                })
            });

            $('.btnapproval').click(function() {
                console.log('objectkuu :>> ', 'klik');
                let idku = $(this).attr('data-id');
                let val = $(this).attr('data-value');
                console.log('val :>> ', val);

                if (val == 'confirm') {
                    confirm();
                } else {
                    $('#modal_tolak').modal('show');
                }
            });

            $('#submittolak').click(function(e) {
                let tolak = $('#tolak_alasan').val();

                if (tolak == '' || tolak == null) {
                    Swal.fire({
                        title: 'Information',
                        text: 'Data Reason is required, please input reason',
                        type: 'warning'
                    });
                } else {
                    $.ajax({
                        url: "{!! route('statuskyc', ['ditolak']) !!}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            idfwd: idfwd,
                            tolak: tolak
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
                                    .replace("{{ route('dashcam') }}"):
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

            function confirm() {
                Swal.fire({
                        title: "Are You Sure?",
                        text: "Is the data you verified/approved correct?",
                        type: "warning",
                        showCancelButton: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    })
                    .then((result) => {
                        console.log('willDelete :>> ', result);
                        if (result.dismiss == 'cancel') {
                            console.log('object :>> ', 'cancel');
                            return;
                        } else {
                            console.log('object :>> ', 'ok');
                            $.ajax({
                                url: "{!! route('statuskyc', ['disetujui']) !!}",
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    _token: $('meta[name=csrf-token]').attr('content'),
                                    idfwd: idfwd,
                                },
                                success: function(response) {
                                    console.log('response :>> ', response);
                                    Swal.fire({
                                        title: response.title,
                                        text: response.message,
                                        type: (response.status != 'error') ? 'success' :
                                            'error'
                                    }).then((result) => {
                                        // $('#approvalfwd').modal('hide');
                                        // table.ajax.reload();
                                        (response.status == 'success') ? window.location
                                            .replace("{{ route('dashcam') }}"):
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
            }

            function notifalert(params) {
                Swal.fire({
                    title: 'Information',
                    text: params + ' Can not be empty',
                    type: 'warning'
                });
                return;
            }

            $('#kycdownload').click(function(e) {
                var base = "{!! url('sources/storage/app') !!}" + "/" + datafile;
                $('#kycdownload').attr('href', base);
            });

        });
    </script>
@endsection
