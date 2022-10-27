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
                                    <center>PO</center>
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
                    <h4 class="modal-title"><span id="modaltitle">Edit Shipment</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
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
                                    <label class="col-sm-12 control-label">Invoice</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="invoice" name="invoice"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">BL Number</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobl" name="nobl"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD (Estimated Time Departure) Fix</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etd" name="etd"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimated Time Arrival) Fix</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="eta" name="eta"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Vessel</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="vessel" name="vessel"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
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
                        data: 'pono',
                        name: 'pono'
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
            var length;
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
                    let mydata = data.data.shipment;
                    let myremain = data.data.remaining;
                    console.log('mydata :>> ', mydata);
                    console.log('myremain :>> ', myremain);
                    idshipment = mydata.id_shipment;
                    idformpo = mydata.idformpo;
                    length = mydata.length;
                    $('#detailitem').empty();

                    html =
                        '<table border="0" style="width:100%"><tr><th style="text-align:center"><input type="checkbox" class="checkall" style="height:18px;width:18px" checked></th><th>Material</th><th>Style</th><th>Color Code</th><th>Size</th><th>Quantity Item</th><th>Remaining Quantity</th><th>Quantity Shipment</th></tr>';
                    for (let index = 0; index < mydata.length; index++) {
                        let remain;
                        // let qtyshipment = (mydata[index].qty_shipment==[]) ? '0' : mydata[index].qty_shipment;

                        if (myremain[index][0].qtyshipment == null) {
                            remain = mydata[index].qtypo;
                        } else if (myremain[index][0].qtyshipment == mydata[index].qtypo) {
                            remain = '0';
                        } else {
                            remain = mydata[index].qtypo - myremain[index][0].qtyshipment;
                        }

                        html +=
                            '<tr><td style="text-align:center"><input type="checkbox" class="check-' +
                            index + '" style="height:18px;width:18px" checked></td><td>' +
                            mydata[index]
                            .matcontents + '</td><td>' + mydata[index].style + '</td><td>' +
                            mydata[index].colorcode + '</td><td>' + mydata[index].size +
                            '</td><td>' + mydata[index].qtypo + '</td><td>' + remain +
                            '</td><td><input type="number" min="0" id="qtyship" name="qtyship" value="' +
                            mydata[index].qty_shipment +
                            '" class="form-control trigerinput cekinput-' +
                            index + '" data-idformshipment="' + mydata[index].id_shipment +
                            '"  data-idformpo="' + mydata[index].id_formpo +
                            '"></td></tr>';
                    }
                    html += "</table>";
                    $('#detailitem').html(html);
                    checkqtyall();

                    // idpo = mydata[].id;
                    // idformpo = databook.id_formpo;
                    // usernik = privilege.privilege_user_nik;
                    // usernama = privilege.privilege_user_name;
                    // tglpengajuan = databook.created_at;

                    $('#invoice').val(mydata[0].noinv);
                    $('#etd').val(mydata[0].etdfix);
                    $('#eta').val(mydata[0].etafix);
                    $('#nobl').val(mydata[0].nomor_bl);
                    $('#vessel').val(mydata[0].vessel);
                })
            });

            function checkqtyall() {

                $('.checkall').change(function(e) {
                    if (this.checked) {
                        $('.trigerinput').prop('disabled', false);
                        $('input[type="checkbox"]').prop('checked', true);
                    } else {
                        // $('.trigerinput').val('');
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
                            // $('.cekinput-' + index).val('');
                            $('.cekinput-' + index).prop('disabled', true);
                        }
                    });
                }
            }

            $('#btnupdate').click(function(e) {
                let inv = $('#invoice').val();
                // let qtyshipment = $('#qtyshipment').val();
                let etd = $('#etd').val();
                let eta = $('#eta').val();
                let nomorbl = $('#nobl').val();
                let vessel = $('#vessel').val();
                let file = $('#filebl').prop('files')[0];

                var arrayku = [];
                for (let index = 0; index < Number(length); index++) {
                    let val = $('.cekinput-' + index).val();

                    // if (val) {
                    let data = {
                        'idshipment': $('.cekinput-' + index).attr('data-idformshipment'),
                        'idformpo': $('.cekinput-' + index).attr('data-idformpo'),
                        'value': val,
                    };

                    arrayku.push(data);
                    // }
                }
                console.log('objectwew :>> ', JSON.stringify(arrayku));

                let form_data = new FormData();
                form_data.append('dataform', JSON.stringify(arrayku));
                // form_data.append('idshipment', idshipment);
                // form_data.append('idformpo', idformpo);
                form_data.append('inv', inv);
                // form_data.append('qtyshipment', qtyshipment);
                form_data.append('etd', etd);
                form_data.append('eta', eta);
                form_data.append('nomorbl', nomorbl);
                form_data.append('vessel', vessel);
                form_data.append('file', file);

                if (nomorbl == null || nomorbl == '') {
                    notifalert('BL Number');
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
