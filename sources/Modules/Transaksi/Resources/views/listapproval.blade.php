@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    {{-- <style>
        input:read-only {
            background-color: transparent !important;
        }
    </style> --}}

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
                    <h4 class="modal-title"><span id="modaltitle">Detail Booking Approval</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-sm-12 control-label">PO Number</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-sm-12 control-label">PI Number</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="nomorpi" name="nomorpi" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label">Route Code</label>
                                                <input type="text" class="form-control" id="routecode"
                                                    name="routecode" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label">Route Description</label>
                                                <input type="text" class="form-control" id="routedesc"
                                                    name="routedesc" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label">Port Of Loading Code</label>
                                                <input type="text" class="form-control" id="polcode" name="polcode"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label">Port Of Loading Name</label>
                                                <input type="text" class="form-control" id="polname" name="polname"
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
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label">Port Of Destination Code</label>
                                                <input type="text" class="form-control" id="podcode" name="podcode"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label">Port Of Destination Name</label>
                                                <input type="text" class="form-control" id="podname" name="podname"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">Package</label>
                                        <input type="text" class="form-control" id="package" name="package"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">User Applicant</label>
                                    <div class="col-sm-12">
                                        {{-- <input class="form-control" type="text" name="" id="pengajunama"
                                            disabled> --}}
                                        <div id="pengajunama"></div>
                                        <input class="form-control" type="text" id="pengajunik" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
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
                    beforeSend: function(param) {
                        Swal.fire({
                            title: 'Please Wait .......',
                            // html: '',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            onOpen: () => {
                                Swal.showLoading();
                            }
                        })
                    }
                }).done(function(data) {
                    Swal.close();
                    let mydata = data.data.dataku;
                    let mypo = data.data.datapo;

                    length = mydata.length;
                    $('#detailitem').empty();
                    $('#datashipmode').empty();

                    html =
                        '<table border="1" style="width:100%; text-align:center"><tr><th>PO Number</th><th>Material</th><th>Material Description</th><th>HS Code</th><th>Color</th><th>Size</th><th>Qty PO</th><th>Qty Book</th><th>Status</th></tr>';
                    for (let index = 0; index < mydata.length; index++) {
                        let myhs = '';
                        if (mydata[index].hscode) {
                            myhs = mydata[index].hscode;
                        }

                        html +=
                            '<tr><td>' + mydata[index].pono + '</td><td>' + mydata[index]
                            .matcontents + '</td><td>' + mydata[index]
                            .itemdesc + '</td><td>' + myhs + '</td><td>' + mydata[index]
                            .colorcode + '</td><td>' + mydata[index]
                            .size + '</td><td>' +
                            mydata[index].qtypo + '</td><td>' + mydata[index].qty_booking +
                            '</td><td>' + mydata[index].statusbooking +
                            '</td><td><input type="hidden" id="dataid-' + index + '" data-idpo="' +
                            mydata[index].id + '" data-idfwd="' + mydata[index].id_forwarder +
                            '" data-idformpo="' +
                            mydata[index].id_formpo +
                            '"></td></tr>';
                    }

                    html += "</table>";
                    $('#detailitem').html(html);

                    var arraypo = [];
                    var arraypi = [];
                    var arraysup = [];
                    var arraykodebook = [];
                    var arraydatebook = [];
                    var arrayetd = [];
                    var arrayeta = [];
                    var arraycreatedby = [];
                    var arrayroutecode = [];
                    var arrayroutedesc = [];
                    var arrayloadingcode = [];
                    var arrayloadingname = [];
                    var arraydestinationcode = [];
                    var arraydestinationname = [];
                    var arraypackage = [];
                    for (let indexpo = 0; indexpo < mypo.length; indexpo++) {
                        arraypo.push(mypo[indexpo]['pono']);
                        arraypi.push(mypo[indexpo]['pino']);
                        arraysup.push(mypo[indexpo]['nama']);
                        arraykodebook.push(mypo[indexpo]['kode_booking']);
                        arraydatebook.push(mypo[indexpo]['date_booking']);
                        arrayetd.push(mypo[indexpo]['etd']);
                        arrayeta.push(mypo[indexpo]['eta']);
                        arraycreatedby.push(mypo[indexpo]['created_by']);
                        arrayroutecode.push(mypo[indexpo]['route_code']);
                        arrayroutedesc.push(mypo[indexpo]['route_desc']);
                        arrayloadingcode.push(mypo[indexpo]['loadingcode']);
                        arrayloadingname.push(mypo[indexpo]['loadingname']);
                        arraydestinationcode.push(mypo[indexpo]['destinationcode']);
                        arraydestinationname.push(mypo[indexpo]['destinationname']);
                        arraypackage.push(mypo[indexpo]['package']);

                        if ((mypo[indexpo].shipmode == 'fcl')) {
                            let expfcl = mypo[indexpo].subshipmode.split("-");
                            let expfcl1 = expfcl[2].split("KG");
                            $('#datashipmode').append(
                                '<div class="row"><div class="col-sm-3"><label class="control-label">Ship Mode</label><input type="text" class="form-control" value="' +
                                mypo[indexpo].shipmode +
                                '" readonly></div><div class="col-sm-3"><label class="control-label">Size</label><input type="text" class="form-control" value="' +
                                expfcl[0] +
                                '" readonly></div><div class="col-sm-3"><label class="control-label">Volume</label><div class="input-group"><input type="number" min="0" class="form-control" autocomplete="off" value="' +
                                expfcl[1] +
                                '" readonly><div class="input-group-append"><span class="input-group-text">M3</span></div></div></div><div class="col-sm-3"><label class="control-label">Weight</label><div class="input-group"><input type="number" min="0" class="form-control" autocomplete="off" value="' +
                                expfcl1[0] +
                                '" readonly><div class="input-group-append"><span class="input-group-text">KG</span></div></div></div></div>'
                            );
                        } else {
                            let expsubship = mypo[indexpo].subshipmode.split("-");
                            let expsubship1 = expsubship[0].split("M3");
                            let expsubship2 = expsubship[1].split("KG");
                            $('#datashipmode').append(
                                '<div class="row"><div class="col-sm-3"><label class="control-label">Ship Mode</label><input type="text" class="form-control" value="' +
                                mypo[indexpo].shipmode +
                                '" readonly></div><div class="col-sm-4"><label class="control-label">Volume</label><div class="input-group"><input type="number" min="0" class="form-control" autocomplete="off" value="' +
                                expsubship1[0] +
                                '" readonly><div class="input-group-append"><span class="input-group-text">M3</span></div></div></div><div class="col-sm-4"><label class="control-label">Weight</label><div class="input-group"><input type="number" min="0" class="form-control" autocomplete="off" value="' +
                                expsubship2[0] +
                                '" readonly><div class="input-group-append"><span class="input-group-text">KG</span></div></div></div></div>'
                            );
                        }
                    }

                    let implodepo = arraypo.join(', ');
                    let implodepi = arraypi.join(', ');
                    let implodesup = arraysup.join(', ');
                    let implodekodebook = arraykodebook.join(', ');
                    let implodedatebook = arraydatebook.join(', ');
                    let implodeetd = arrayetd.join(', ');
                    let implodeeta = arrayeta.join(', ');
                    let implodecreatedby = arraycreatedby.join(', ');
                    let imploderoutecode = arrayroutecode.join(', ');
                    let imploderoutedesc = arrayroutedesc.join(', ');
                    let implodeloadingcode = arrayloadingcode.join(', ');
                    let implodeloadingname = arrayloadingname.join(', ');
                    let implodedestinationcode = arraydestinationcode.join(', ');
                    let implodedestinationname = arraydestinationname.join(', ');
                    let implodepackage = arraypackage.join(', ');

                    $('#nomorpo').val(implodepo);
                    $('#nomorpi').val(implodepi);
                    $('#supplier').val(implodesup);

                    $('#nobook').val(implodekodebook);
                    $('#datebook').val(implodedatebook);
                    $('#etd').val(implodeetd);
                    $('#eta').val(implodeeta);
                    $('#forwarder').val(mydata[0].name);
                    $('#pengajunama').html(mydata[0].privilege_user_name);
                    $('#pengajunik').val(implodecreatedby);
                    $('#routecode').val(imploderoutecode);
                    $('#routedesc').val(imploderoutedesc);
                    $('#polcode').val(implodeloadingcode);
                    $('#polname').val(implodeloadingname);
                    $('#podcode').val(implodedestinationcode);
                    $('#podname').val(implodedestinationname);
                    $('#package').val(implodepackage);
                })
            });

            $('.btnapproval').click(function() {
                let idku = $(this).attr('data-id');
                let val = $(this).attr('data-value');

                if (val == 'confirm') {
                    confirm();
                } else {
                    $('#modal_tolak').modal('show');
                }
            });

            $('#submittolak').click(function(e) {
                let tolak = $('#tolak_alasan').val();

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
                            dataid: arrayku,
                            tolak: tolak
                        },
                        beforeSend: function(xhr) {
                            Swal.fire({
                                title: "Please Wait ...",
                                html: "Data Will Be Reject",
                                showCancelButton: false,
                                showConfirmButton: false
                            })
                            Swal.showLoading()
                        },
                        success: function(response) {
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                Swal.close();
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
                        if (result.dismiss == 'cancel') {
                            return;
                        } else {
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
                                    dataid: arrayku,
                                },
                                beforeSend: function(xhr) {
                                    Swal.fire({
                                        title: "Please Wait ...",
                                        html: "Data Will Be Confirm",
                                        showCancelButton: false,
                                        showConfirmButton: false
                                    })
                                    Swal.showLoading()
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: response.title,
                                        text: response.message,
                                        type: (response.status != 'error') ? 'success' :
                                            'error'
                                    }).then((result) => {
                                        Swal.close();
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
