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
                            <center>Booking Number</center>
                        </th>
                        <th>
                            <center>Quantity PO</center>
                        </th>
                        <th>
                            <center>Status</center>
                        </th>
                        <th>
                            <center>Action</center>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="updateshipment">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Allocation Shipment</span></h4>
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
                                    <label class="col-sm-12 control-label">Booking Number</label>
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
                                    <label class="col-sm-12 control-label">ETD (Estimated Time Departure)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etd" name="etd" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimated Time Arrival)</label>
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
                                    <label class="col-sm-12 control-label">BL</label>
                                    <div class="col-sm-12">
                                        <input type="file" class="form-control" id="bl" name="bl">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">BL Number</label>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Invoice</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="invoice" name="invoice"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                    url: "{{ route('list_shipmentprocess') }}"
                },
                columns: [{
                        data: 'listpo',
                        name: 'listpo'
                    },
                    {
                        data: 'nobook',
                        name: 'nobook'
                    },
                    {
                        data: 'qtypo',
                        name: 'qtypo'
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

            var length;
            $('body').on('click', '#updateship', function() {
                $('#updateshipment').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('form_shipmentprocess') !!}",
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
                        '<table border="0" style="width:100%"><tr><th style="text-align:center"><input type="checkbox" class="checkall" style="height:18px;width:18px"></th><th>Material</th><th>Style</th><th>Color Code</th><th>Size</th><th>Quantity Item</th><th>Remaining Quantity</th><th>Quantity Allocation</th></tr>';
                    for (let index = 0; index < dataku.length; index++) {
                        let remain;

                        // let qtypo = dataku[index].qtypo;
                        // let newqtypo = qtypo.replace(".", "");

                        if (dataku[index].qtyship == null) {
                            remain = dataku[index].qtypo;
                        } else if (dataku[index].qtyship == dataku[index].qtypo) {
                            remain = '0';
                        } else {
                            remain = dataku[index].qtypo - dataku[index].qtyship;
                        }

                        html +=
                            '<tr><td style="text-align:center"><input type="checkbox" class="check-' +
                            index + '" style="height:18px;width:18px"></td><td>' +
                            dataku[index].matcontents + '</td><td>' + dataku[index].style +
                            '</td><td>' + dataku[index].colorcode + '</td><td>' + dataku[index]
                            .size +
                            '</td><td>' + dataku[index].qtypo + '</td><td>' + remain +
                            '</td><td><input type="number" min="0" id="qty_allocation" name="qty_allocation" value="' +
                            remain + '" class="form-control trigerinput cekinput-' +
                            index + '" data-idpo="' + dataku[index].idpo + '"  data-idformpo="' +
                            dataku[index].id_formpo + '" disabled></td></tr>';
                    }

                    html += "</table>";
                    $('#detailitem').html(html);
                    $('input[type="checkbox"]').prop('checked', true);
                    for (let index = 0; index < Number(length); index++) {
                        $('.cekinput-' + index).prop('disabled', false);
                    }
                    checkqtyall();

                    $('#nomorpo').val(dataku[0].pono);
                    $('#nobook').val(dataku[0].kode_booking);
                    $('#datebook').val(dataku[0].date_booking);
                    $('#etd').val(dataku[0].etd);
                    $('#eta').val(dataku[0].eta);
                    $('#shipmode').val(dataku[0].shipmode);
                    $('#submode').val(dataku[0].subshipmode);
                    $('#etdfix').val(dataku[0].etd);
                    $('#etafix').val(dataku[0].eta);
                })
            });

            function checkqtyall() {

                $('.checkall').change(function(e) {
                    if (this.checked) {
                        $('.trigerinput').prop('disabled', false);
                        $('input[type="checkbox"]').prop('checked', true);
                    } else {
                        $('.trigerinput').val('');
                        $('.trigerinput').prop('disabled', true);
                        $('input[type="checkbox"]').prop('checked', false);
                    }
                });

                for (let index = 0; index < Number(length); index++) {
                    $('.check-' + index).change(function(e) {
                        if (this.checked) {
                            console.log('objectsijine :>> ', 'isChecked');
                            $('.cekinput-' + index).prop('disabled', false);
                            // }
                        } else {
                            console.log('objectsijine :>> ', 'notChecked');
                            $('.cekinput-' + index).val('');
                            $('.cekinput-' + index).prop('disabled', true);
                        }
                    });
                }
            }

            $('#btnsubmit').click(function(e) {
                // let idpo = $('#dataid').attr('data-idpo');
                // let idformpo = $('#dataid').attr('data-idformpo');
                let nomorbl = $('#nobl').val();
                let vessel = $('#vessel').val();
                let file = $('#bl').prop('files')[0];
                let inv = $('#invoice').val();
                let etdfix = $('#etdfix').val();
                let etafix = $('#etafix').val();

                var arrayku = [];
                for (let index = 0; index < Number(length); index++) {
                    let val = $('.cekinput-' + index).val();

                    if (val) {
                        let data = {
                            'idpo': $('.cekinput-' + index).attr('data-idpo'),
                            'idformpo': $('.cekinput-' + index).attr('data-idformpo'),
                            'value': val,
                        };

                        arrayku.push(data);
                    }
                }

                let form_data = new FormData();
                form_data.append('dataid', JSON.stringify(arrayku));
                // form_data.append('idpo', idpo);
                // form_data.append('idformpo', idformpo);
                form_data.append('nomorbl', nomorbl);
                form_data.append('vessel', vessel);
                form_data.append('file', file);
                form_data.append('invoice', inv);
                form_data.append('etdfix', etdfix);
                form_data.append('etafix', etafix);

                console.log('form :>> ', arrayku);

                if (arrayku == null || arrayku == '') {
                    notifalert('Quantity Allocation');
                } else if (file == null || file == '') {
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
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ route('saveshipmentprocess') }}",
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
                                    .replace("{{ route('process_shipment') }}"):
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
