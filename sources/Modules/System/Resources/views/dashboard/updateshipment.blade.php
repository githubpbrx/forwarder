@extends('system::template/master')
@section('title', $title)
@section('link_href')
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="card" style="font-size: 10pt;">
        <div class="card-body">
            <table id="serverside" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>
                            <center>List PO#</center>
                        </th>
                        <th>
                            <center>No Booking</center>
                        </th>
                        {{-- <th>
                            <center>Status</center>
                        </th> --}}
                        <th>
                            <center>Action</center>
                        </th>
                    </tr>
                </thead>
            </table>
            <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a>
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="updateshipment">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Formulir PO</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <form action="#" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor PO</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                    </div>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobook" name="nobook" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Date Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="datebook" name="datebook" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD (Estimate Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etd" name="etd" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimate Actual Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="eta" name="eta" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Quantity Shipment</label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" id="qtyshipment" name="qtyshipment">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">BL</label>
                                    <div class="col-sm-12">
                                        <input type="file" class="form-control" id="bl" name="bl">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor BL</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobl" name="nobl"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Vessel</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="vessel" name="vessel"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Invoice</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="invoice" name="invoice"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD (Estimated Time Departure) Fix</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etdfix" name="etdfix"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimated Time Arrival) Fix</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etafix" name="etafix"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" id="btnsubmit">Submit</button>
                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- /.modal content ----------------- --}}
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

            $('#etdfix').datepicker({
                changeYear: true,
                changeMonth: true,
                dateFormat: "yy-m-dd",
                yearRange: "-100:+20",
            });

            $('#etafix').datepicker({
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
                    url: "{{ route('list_update') }}"
                },
                columns: [{
                        data: 'listpo',
                        name: 'listpo'
                    },
                    {
                        data: 'nobook',
                        name: 'nobook'
                    },
                    // {
                    //     data: 'status',
                    //     name: 'status'
                    // },
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

            var length;
            $('body').on('click', '#updateship', function() {
                $('#updateshipment').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('form_update') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    let dataku = data.data.dataku;
                    console.log('dataku :>> ', dataku);
                    // let forwarderku = data.data.dataforwarder;

                    length = dataku.length;
                    $('#detailitem').empty();

                    html =
                        '<table border="0" style="width:100%"><tr><th>Material Contents</th><th>Color Code</th><th>Size</th><th>Quantity PO</th><th>Quantity Allocation</th><th>Status</th></tr>';
                    for (let index = 0; index < dataku.length; index++) {
                        html +=
                            '<tr><td>' +
                            dataku[index].matcontents + '</td><td>' +
                            dataku[index].colorcode + '</td><td>' + dataku[index].size +
                            '</td><td>' + dataku[index].qtypo + '</td><td>' + dataku[index]
                            .qty_allocation +
                            '</td><td>' + dataku[index].statusforwarder +
                            '</td><td><input type="hidden" id="dataid-' + index + '" data-idpo="' +
                            dataku[index].idpo + '" data-idformpo="' + dataku[index].id_formpo +
                            '"></td></tr>';
                    }

                    html += "</table>";
                    $('#detailitem').html(html);

                    $('#nomorpo').val(dataku[0].pono);
                    $('#nobook').val(dataku[0].kode_booking);
                    $('#datebook').val(dataku[0].date_booking);
                    $('#etd').val(dataku[0].etd);
                    $('#eta').val(dataku[0].eta);
                    $('#shipmode').val(dataku[0].shipmode);
                    $('#submode').val(dataku[0].subshipmode);
                    $('#qtyshipment').val(dataku[0].qtypo);
                })
            });

            $('#btnsubmit').click(function(e) {
                // let idpo = $('#dataid').attr('data-idpo');
                // let idformpo = $('#dataid').attr('data-idformpo');
                let nomorbl = $('#nobl').val();
                let vessel = $('#vessel').val();
                let file = $('#bl').prop('files')[0];
                let inv = $('#invoice').val();
                let etdfix = $('#etdfix').val();
                let etafix = $('#etafix').val();
                let qtyshipment = $('#qtyshipment').val();

                var arrayku = [];
                for (let index = 0; index < length; index++) {
                    let data = {
                        'idpo': $('#dataid-' + index).attr('data-idpo'),
                        'idformpo': $('#dataid-' + index).attr('data-idformpo'),
                    };
                    arrayku.push(data);
                }

                let form_data = new FormData();
                form_data.append('dataid', JSON.stringify(arrayku));
                // form_data.append('idpo', idpo);
                // form_data.append('idformpo', idformpo);
                form_data.append('qtyshipment', qtyshipment);
                form_data.append('nomorbl', nomorbl);
                form_data.append('vessel', vessel);
                form_data.append('file', file);
                form_data.append('invoice', inv);
                form_data.append('etdfix', etdfix);
                form_data.append('etafix', etafix);

                console.log('form :>> ', form_data);

                if (file == null || file == '') {
                    notifalert('File BL');
                } else if (nomorbl == null || nomorbl == '') {
                    notifalert('Nomor BL');
                } else if (vessel == null || vessel == '') {
                    notifalert('Vessel');
                } else if (inv == null || inv == '') {
                    notifalert('Invoice');
                } else if (etdfix == null || etdfix == '') {
                    notifalert('ETD Fix');
                } else if (etafix == null || etafix == '') {
                    notifalert('ETA Fix');
                } else if (qtyshipment == null || qtyshipment == '') {
                    notifalert('Quantity Shipment');
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ route('saveshipment') }}",
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
                                    .replace("{{ route('page_update') }}"):
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
