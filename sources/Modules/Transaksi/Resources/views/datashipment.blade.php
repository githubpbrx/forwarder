@extends('system::template/master')
@section('title', $title)
@section('link_href')
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
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
                                    <center>Code Booking</center>
                                </th>
                                <th>
                                    <center>Invoice</center>
                                </th>
                                <th>
                                    <center>Action</center>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Shipment --}}
    <div class="modal fade" id="detailshipment">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail Shipment</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        {{-- <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
                        <div class="row">
                            <div class="col-md-12">
                                <div id="detailitem"></div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" /> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Invoice</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="invoice" name="invoice">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Quantity Shipment</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="qtyshipment" name="qtyshipment">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD (Estimated Time Departure) Fix</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etd" name="etd">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimated Time Arrival) Fix</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="eta" name="eta">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor BL</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobl" name="nobl">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Vessel</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="vessel" name="vessel">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">File BL</label>
                                    <div class="col-sm-12">
                                        <input type="file" class="form-control" id="filebl">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" id="btnupdate">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_src')
@endsection

@section('script')
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#etd').datepicker({
                changeYear: true,
                changeMonth: true,
                dateFormat: "yy-m-dd",
                yearRange: "-100:+20",
            });

            $('#eta').datepicker({
                changeYear: true,
                changeMonth: true,
                dateFormat: "yy-m-dd",
                yearRange: "-100:+20",
            });

            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('list_shipment') }}"
                },
                columns: [{
                        data: 'kodebook',
                        name: 'kodebook'
                    },
                    {
                        data: 'inv',
                        name: 'inv'
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

            var idshipment;
            var idformpo;
            $('body').on('click', '#detailbtn', function() {
                $('#detailshipment').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('getdatashipment') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('datakuh :>> ', data);

                    let mydata = data.shipment;
                    idshipment = mydata.id_shipment;
                    idformpo = mydata.idformpo;
                    // length = mydata.length;
                    // $('#detailitem').empty();

                    // html =
                    //     '<table border="0" style="width:100%"><tr><th>Material</th><th>Qty Item</th><th>Qty Allocation</th><th>Status Allocation</th></tr>';
                    // for (let index = 0; index < mydata.length; index++) {

                    //     html +=
                    //         '<tr><td>' +
                    //         mydata[index].matcontents + '</td><td>' +
                    //         mydata[index].qtypo + '</td><td>' + mydata[index].qty_allocation +
                    //         '</td><td>' + mydata[index].statusforwarder +
                    //         '</td><td><input type="hidden" id="dataid-' + index + '" data-idpo="' +
                    //         mydata[index].id + '" data-idfwd="' + mydata[index].id_forwarder +
                    //         '" data-idformpo="' +
                    //         mydata[index].id_formpo +
                    //         '"></td></tr>';
                    // }

                    // html += "</table>";
                    // $('#detailitem').html(html);

                    // idpo = mydata[].id;
                    // idformpo = databook.id_formpo;
                    // usernik = privilege.privilege_user_nik;
                    // usernama = privilege.privilege_user_name;
                    // tglpengajuan = databook.created_at;

                    $('#invoice').val(mydata.noinv);
                    $('#qtyshipment').val(mydata.qty_shipment);
                    $('#etd').val(mydata.etdfix);
                    $('#eta').val(mydata.etafix);
                    $('#nobl').val(mydata.nomor_bl);
                    $('#vessel').val(mydata.vessel);
                })
            });

            $('#btnupdate').click(function(e) {
                let inv = $('#invoice').val();
                let qtyshipment = $('#qtyshipment').val();
                let etd = $('#etd').val();
                let eta = $('#eta').val();
                let nomorbl = $('#nobl').val();
                let vessel = $('#vessel').val();
                let file = $('#filebl').prop('files')[0];

                let form_data = new FormData();
                form_data.append('idshipment', idshipment);
                form_data.append('idformpo', idformpo);
                form_data.append('inv', inv);
                form_data.append('qtyshipment', qtyshipment);
                form_data.append('etd', etd);
                form_data.append('eta', eta);
                form_data.append('nomorbl', nomorbl);
                form_data.append('vessel', vessel);
                form_data.append('file', file);

                if (qtyshipment == null || qtyshipment == '') {
                    notifalert('File BL');
                } else if (nomorbl == null || nomorbl == '') {
                    notifalert('Nomor BL');
                } else if (vessel == null || vessel == '') {
                    notifalert('Vessel');
                } else if (inv == null || inv == '') {
                    notifalert('Invoice');
                } else if (etd == null || etd == '') {
                    notifalert('ETD Fix');
                } else if (eta == null || eta == '') {
                    notifalert('ETA Fix');
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ route('updateshipment') }}",
                        processData: false,
                        contentType: false,
                        data: form_data,
                        // data: {
                        //     _token: $('meta[name=csrf-token]').attr('content'),
                        //     'idpo': idku,
                        //     'idformpo': formpo,
                        //     'file': form_data,
                        //     'nomorbl': nomorbl,
                        //     'vessel': vessel,
                        // },
                        dataType: "json",
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                (response.status == 'success') ? window.location
                                    .replace("{{ route('datashipment') }}"):
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

            function notifalert(params) {
                Swal.fire({
                    title: 'Information',
                    text: params + ' Can not be empty',
                    type: 'warning'
                });
                return;
            }

        });
    </script>
@endsection
