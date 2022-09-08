@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-body">
                    <table id="serverside" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nomor Booking</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Approval Forwarder --}}
    <div class="modal fade" id="approvalfwd">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail Approval Forwarder</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-sm-12 control-label">Nomor PO</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobook" name="nobook" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Date Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="datebook" name="datebook" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD (Estimate Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etd" name="etd" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimate Acutal Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="eta" name="eta" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Ship Mode</label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="shipmode" name="shipmode"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="submode" name="submode"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="forwarder" name="forwarder" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">User Applicant</label>
                                    <div class="col-sm-8">
                                        {{-- <input class="form-control" type="text" name="" id="pengajunama"
                                            disabled> --}}
                                        <div id="pengajunama"></div>
                                        <input class="form-control" type="text" id="pengajunik" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">User Approved</label>
                                    <div class="col-sm-8">
                                        <div id="detailpengesah"></div>
                                        <input type="text" name="nikpengesah" id="nik" class="form-control">
                                        {{-- <input type="hidden" id="namaasli" name="namaasli"> --}}
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button type="button" class="btnapproval btnconfirm btn btn-success"
                                                        data-value="confirm">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button type="button" class="btnapproval btn btn-danger"
                                                        data-value="reject">Reject</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    {{-- <button type="button" class="btn btn-info" id="btnsubmit">Submit</button> --}}
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
                    url: "{{ route('list_approval') }}"
                },
                columns: [{
                        data: 'nobooking',
                        name: 'nobooking'
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

            var idpo;
            var idformpo;
            var usernik;
            var usernama;
            var tglpengajuan;
            $('body').on('click', '#prosesapproval', function() {
                console.log('objectproses :>> ', 'klik');
                $('#nik').val('');
                $('#detailpengesah').html('');
                // $('#modal-detail').modal('show');
                $('#approvalfwd').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('getdataapproval') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('data :>> ', data.data);
                    let databook = data.data.databooking;
                    let poku = data.data.datapo;
                    let forward = data.data.dataforward;
                    let privilege = data.data.privilege;

                    idpo = poku.id;
                    idformpo = databook.id_formpo;
                    usernik = privilege.privilege_user_nik;
                    usernama = privilege.privilege_user_name;
                    tglpengajuan = databook.created_at;

                    $('#nomorpo').val(poku.pono);
                    $('#nobook').val(databook.kode_booking);
                    $('#datebook').val(databook.date_booking);
                    $('#etd').val(databook.etd);
                    $('#eta').val(databook.eta);
                    $('#shipmode').val(databook.shipmode);
                    $('#submode').val(databook.subshipmode);
                    $('#forwarder').val(forward.nama);
                    $('#pengajunama').html(privilege.privilege_user_name);
                    $('#pengajunik').val(privilege.privilege_user_nik);
                })
            });

            var pengesahnik;
            $('#nik').keyup(function(e) {
                console.log('keyup :>> ', 'keyup');
                var nik = $("#nik").val();
                pengesahnik = nik;
                // $("#loading").show();
                let url = '{!! route('approvalgetkaryawan', ['id']) !!}'
                url = url.replace('id', nik)
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        console.log('data :>> ', data);
                        // $("#loading").hide();
                        // if (data.status == 'no') {
                        //     $("#nik").val('');
                        // }
                        $("#detailpengesah").html(data.data);
                        // $("#namaasli").val(data.namaasli);

                    }
                });
            });

            $('.btnapproval').click(function() {
                console.log('objectkuu :>> ', 'klik');
                let idku = $(this).attr('data-id');
                let val = $(this).attr('data-value');
                console.log('val :>> ', val);

                if (val == 'confirm') {
                    confirm();
                } else {
                    if (pengesahnik == '' || pengesahnik == null) {
                        Swal.fire({
                            title: 'Informasi',
                            text: 'Data NIK is required, please input NIK',
                            type: 'warning'
                        });
                    } else {
                        $('#modal_tolak').modal('show');
                    }
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
                        url: "{!! route('approvalstatus', ['ditolak']) !!}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            idpo: idpo,
                            idformpo: idformpo,
                            usernama: usernama,
                            usernik: usernik,
                            pengesahnik: pengesahnik,
                            tglpengajuan: tglpengajuan,
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
                if (pengesahnik == '' || pengesahnik == null) {
                    Swal.fire({
                        title: 'Information',
                        text: 'Data NIK is required, please input NIK',
                        type: 'warning'
                    });
                } else {
                    Swal.fire({
                            title: "Apakah anda yakin?",
                            text: "Apakah data yang anda verifikasi/setujui sudah benar?",
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
                                    url: "{!! route('approvalstatus', ['disetujui']) !!}",
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        _token: $('meta[name=csrf-token]').attr('content'),
                                        idpo: idpo,
                                        idformpo: idformpo,
                                        usernama: usernama,
                                        usernik: usernik,
                                        tglpengajuan: tglpengajuan,
                                        pengesahnik: pengesahnik
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
            }

        });
    </script>
@endsection
