@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="row" style="font-size: 10pt;">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-body">
                    <table id="serverside" class="table table-bordered table-striped">
                        <thead>
                            <tr>

                                <th>
                                    <center>PO Number</center>
                                </th>
                                <th>
                                    <center>Booking Number</center>
                                </th>
                                <th>
                                    <center>Action</center>
                                </th>
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
                <div class="modal-body" style="font-size: 10pt;">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-sm-12 control-label">PO Number</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-sm-12 control-label">Supplier</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="supplier" name="supplier" readonly>
                                </div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
                        <div class="row">
                            <div class="col-md-12">
                                <div id="detailitem"></div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Booking Number</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobook" name="nobook" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Date Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="datebook" name="datebook" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD (Estimated Time Departure)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etd" name="etd" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimated Time Arrival)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="eta" name="eta" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div id="datashipmode"></div>
                                    </div>
                                </div>
                            </div>
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
                                    <br>
                                    <br>
                                    {{-- <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-sm-12"> --}}
                                    <button type="button" class="btnapproval btnconfirm btn btn-success"
                                        data-value="confirm">Confirm</button>
                                    {{-- </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-sm-12"> --}}
                                    <button type="button" class="btnapproval btn btn-danger"
                                        data-value="reject">Reject</button>
                                    {{-- </div>
                                            </div>
                                        </div>
                                    </div> --}}
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
                        data: 'nomorpo',
                        name: 'nomorpo'
                    },
                    {
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
            // var length;
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

                    let mydata = data.data.dataku;

                    length = mydata.length;
                    $('#detailitem').empty();

                    html =
                        '<table border="0" style="width:100%"><tr><th>Material</th><th>Material Description</th><th>HS Code</th><th>Qty Item</th><th>Qty Allocation</th><th>Status Allocation</th></tr>';
                    for (let index = 0; index < mydata.length; index++) {
                        html +=
                            '<tr><td>' + mydata[index].matcontents + '</td><td>' + mydata[index]
                            .itemdesc + '</td><td>' + 'kosong' + '</td><td>' +
                            mydata[index].qtypo + '</td><td>' + mydata[index].qty_allocation +
                            '</td><td>' + mydata[index].statusforwarder +
                            '</td><td><input type="hidden" id="dataid-' + index + '" data-idpo="' +
                            mydata[index].id + '" data-idfwd="' + mydata[index].id_forwarder +
                            '" data-idformpo="' +
                            mydata[index].id_formpo +
                            '"></td></tr>';
                    }

                    html += "</table>";
                    $('#detailitem').html(html);


                    if ((mydata[0].shipmode == 'fcl')) {
                        let exp = mydata[0].subshipmode.split("-");
                        $('#datashipmode').append(
                            '<div class="row"><div class="col-sm-3"><label class="control-label">Ship Mode</label><input type="text" class="form-control" value="' +
                            mydata[0].shipmode +
                            '" readonly></div><div class="col-sm-3"><label class="control-label">SubShipmode</label><input type="text" class="form-control" value="' +
                            exp[0] +
                            '&Prime;" readonly></div><div class="col-sm-3"><label class="control-label">Amount</label><input type="text" class="form-control" value="' +
                            exp[1] +
                            '" readonly></div><div class="col-sm-3"><label class="control-label">Weight</label><input type="text" class="form-control" value="' +
                            exp[2] + '" readonly></div></div>'
                        );
                    } else {
                        $('#datashipmode').append(
                            '<div class="row"><div class="col-sm-6"><label class="control-label">Ship Mode</label><input type="text" class="form-control" value="' +
                            mydata[0].shipmode +
                            '" readonly></div><div class="col-sm-6"><label class="control-label">SubShipmode</label><input type="text" class="form-control" value="' +
                            mydata[0].subshipmode +
                            '" readonly></div></div>'
                        );
                    }

                    $('#nomorpo').val(mydata[0].pono);
                    $('#supplier').val(mydata[0].nama);
                    $('#nobook').val(mydata[0].kode_booking);
                    $('#datebook').val(mydata[0].date_booking);
                    $('#etd').val(mydata[0].etd);
                    $('#eta').val(mydata[0].eta);
                    $('#forwarder').val(mydata[0].name);
                    $('#pengajunama').html(mydata[0].privilege_user_name);
                    $('#pengajunik').val(mydata[0].privilege_user_nik);
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
                // let idpo = $('#dataid').attr('data-idpo');
                // let idfwd = $('#dataid').attr('data-idfwd');
                // let idformpo = $('#dataid').attr('data-idformpo');

                var arrayku = [];
                for (let index = 0; index < Number(length); index++) {
                    let data = {
                        'idpo': $('#dataid-' + index).attr('data-idpo'),
                        'idfwd': $('#dataid-' + index).attr('data-idfwd'),
                        'idformpo': $('#dataid-' + index).attr('data-idformpo'),
                    };

                    arrayku.push(data);
                }

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
                            // idpo: idpo,
                            // idfwd: idfwd,
                            // idformpo: idformpo,
                            dataid: arrayku,
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
                                    .replace("{{ route('page_approval') }}"):
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
                        text: "Is the data you verified/approved correct",
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
                            // let idpo = $('#dataid').attr('data-idpo');
                            // let idfwd = $('#dataid').attr('data-idfwd');
                            // let idformpo = $('#dataid').attr('data-idformpo');

                            var arrayku = [];
                            for (let index = 0; index < Number(length); index++) {
                                let data = {
                                    'idpo': $('#dataid-' + index).attr('data-idpo'),
                                    'idfwd': $('#dataid-' + index).attr('data-idfwd'),
                                    'idformpo': $('#dataid-' + index).attr('data-idformpo'),
                                };

                                arrayku.push(data);
                            }
                            $.ajax({
                                url: "{!! route('approvalstatus', ['disetujui']) !!}",
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    _token: $('meta[name=csrf-token]').attr('content'),
                                    // idpo: idpo,
                                    // idfwd: idfwd,
                                    // idformpo: idformpo,
                                    dataid: arrayku,
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
                                            .replace("{{ route('page_approval') }}"):
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

        });
    </script>
@endsection
